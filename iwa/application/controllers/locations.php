<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locations extends MY_Controller {

	
	public function index()
	{
		$this->viewAll();
	}
	
	public function editOne($intId)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/locations/editone/'.$intId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Edit Location";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Locations.editOne");
		$booSuccess = false;
		
		if ($booPermission)
		{
			// models
			$this->load->model('locations_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			$arrPageData['arrLocationsData'] = array('results' => array());
			$arrPageData['strName'] 		= "";
			$arrPageData['strBarcode'] 		= "";
			$arrPageData['intLocationId'] 		= $intId;
			
			
			$mixLocationsData = $this->locations_model->getOne($intId, $this->session->userdata('objSystemUser')->accountid);
			
			
			
			// did we find any?
			if ($mixLocationsData && (count($mixLocationsData['results']) == 1))
			{
				$arrPageData['arrLocationsData'] = $mixLocationsData;
				$booSuccess = true;
				$arrPageData['strName'] 		= $mixLocationsData['results'][0]->locationname;
				$arrPageData['strBarcode'] 		= $mixLocationsData['results'][0]->locationbarcode;
				
				// is there a submission?
				if ($this->input->post())
				{
					if ($this->input->post('barcode') != $mixLocationsData['results'][0]->locationbarcode)
					{
						$this->form_validation->set_rules('name', 'Name', 'trim|unique[locations.barcode]');
                        $this->form_validation->set_rules('barcode', 'Barcode', 'callback_checkUniqueBarcode');
					}
					if ($this->input->post('name') != $mixLocationsData['results'][0]->locationname)
					{
						$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_checkLocationName');
					}
					
					if ($this->form_validation->run())
					{
						
						$arrLocationData = array(
									'barcode' => $this->input->post('barcode'),
									'name' => $this->input->post('name')
									);
						if ($this->locations_model->editOne($intId, $arrLocationData))
						{
                                                    // Log it first
                                            $this->logThis("Edited location", "locations", $intId);
							$this->session->set_userdata('booCourier', true);
							$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The location was successfully updated')));
							redirect('/locations/index/', 'refresh');
						}
						else
						{
							$arrPageData['arrErrorMessages'][] = "Unable to edit the location.";
						}
					}
					$arrPageData['strName'] 		= $this->input->post('name');
					$arrPageData['strBarcode'] 		= $this->input->post('barcode');
				}
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Unable to find the location.";
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "The location search was not valid.";
			}
		}
		else
		{
			$arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
			$arrPageData['strPageTitle'] = "Security Check Point";
			$arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		if ($booPermission && $booSuccess)
		{
			//load the correct view
			$this->load->view('locations/edit', $arrPageData);
			$this->load->view('locations/forms/add', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function viewAll()
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/locations/viewall/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "View All";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Locations.viewAll");
		
		if ($booPermission)
		{
			// models
			$this->load->model('locations_model');
                        $this->load->model('audits_model');
			$arrPageData['arrLocationsData'] = array('results' => array());
			
			$mixLocationsData = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid, false);
			
                        
			// did we find any?
			if ($mixLocationsData && (count($mixLocationsData['results']) > 0))
			{
                                $arrLocationsOutput['results'] = array();
                                foreach($mixLocationsData['results'] as $objLocation)
                                {
                                    $mixAuditData = $this->audits_model->getLastAuditForLocation($objLocation->locationid);
                                    $arrLocationsOutput['results'][] = (object) array_merge((array)$objLocation, array('location_audit'=>$mixAuditData));
                                }
				$arrPageData['arrLocationsData'] = $arrLocationsOutput;
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Unable to find any locations.";
			}
		}
		else
		{
			$arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
			$arrPageData['strPageTitle'] = "Security Check Point";
			$arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		if ($booPermission)
		{
			//load the correct view
			$this->load->view('locations/viewall', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function addOne()
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/locations/addone/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Add a Location";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Locations.addOne");
		
		if ($booPermission)
		{
			// models
			$this->load->model('locations_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                        
			$arrPageData['strName'] 		= "";
			$arrPageData['strBarcode'] 		= "";
			
			// is there a submission?
			if ($this->input->post())
			{
				$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_checkLocationName');
				$this->form_validation->set_rules('barcode', 'Barcode', 'callback_checkUniqueBarcode');


				if ($this->form_validation->run())
				{
					$arrLocationData = array(
								'barcode' => $this->input->post('barcode'),
								'name' => $this->input->post('name'),
								'account_id' => $this->session->userdata('objSystemUser')->accountid
								);
                                        
                                        $mixSuccess = $this->locations_model->addOneAndReturnId($arrLocationData);
					if ($mixSuccess)
					{
                                            // Log it first
                                            $this->logThis("Added location", "locations", $mixSuccess);
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The location was successfully added')));
						redirect('/locations/index/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Unable to add the location.";
					}
				}
			}
			
			
		}
		else	
		{
			$arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
			$arrPageData['strPageTitle'] = "Security Check Point";
			$arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
		}
		
		
		$this->load->view('common/header', 	$arrPageData);
		if ($booPermission)
		{
			//load the correct view
			$this->load->view('locations/add', $arrPageData);
			$this->load->view('locations/forms/add', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
        
        public function checkLocationName($strName)
        {
            
            // models
            $this->load->model('locations_model');
            if ($this->locations_model->doCheckLocationNameIsUniqueOnAccount($strName, $this->session->userdata('objSystemUser')->accountid))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('checkLocationName', 'There is already a location called that.');
                return false;
            }
            
		
        }

    public function checkUniqueBarcode($barcode) {
        // models
        $this->load->model('locations_model');
        if ($this->locations_model->doCheckLocationBarcodeIsUnique($barcode) || empty($barcode) || !isset($barcode))
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('checkUniqueBarcode', 'There is already a location with that barcode.');
            return false;
        }
    }
	
	public function deleteOne($intLocationId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/locations/deleteone/'.$intLocationId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Delete a Location";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Locations.deleteOne");
		$booNoErrors = true;
		$arrPageData['intLocationId'] 		= $intLocationId;
		
		if ($booPermission)
		{
			// models
			$this->load->model('locations_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			$mixLocationsData = $this->locations_model->getOne($intLocationId, $this->session->userdata('objSystemUser')->accountid);
			
			// did we find any?
			if ($mixLocationsData && (count($mixLocationsData['results']) == 1))
			{
			// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->locations_model->deleteOne($intLocationId))
					{
					// Yes		
                                         // Log it first
                                            $this->logThis("Deactivated location", "locations", $intLocationId);		
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Location Deleted')));
						redirect('locations/viewall/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Location could not be deleted";
						$arrPageData['strPageTitle'] = "Oooops!";
						$arrPageData['strPageText'] = "You cannot delete this location, perhaps it has active items linked to it?";
						$booNoErrors = false;
					}
			
				}
				$arrPageData['strName'] 		= $mixLocationsData['results'][0]->locationname;
				$arrPageData['strBarcode'] 		= $mixLocationsData['results'][0]->locationbarcode;
			
				
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Location could not be found.";
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve location information.";
				$booNoErrors = false;
			}
		}
		else
		{
			$arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
			$arrPageData['strPageTitle'] = "Security Check Point";
			$arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
		}
		
		$this->load->view('common/header', 	$arrPageData);
		if ($booPermission && $booNoErrors)
		{
			//load the correct view
			$this->load->view('locations/delete', $arrPageData);
			$this->load->view('common/forms/safetycheck', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function reactivateOne($intLocationId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/locations/reactivateone/'.$intLocationId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Reactivate a Location";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Locations.reactivateOne");
		$booNoErrors = true;
		$arrPageData['intLocationId'] 		= $intLocationId;
		
		if ($booPermission)
		{
			// models
			$this->load->model('locations_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			$mixLocationsData = $this->locations_model->getOne($intLocationId, $this->session->userdata('objSystemUser')->accountid);
			
			// did we find any?
			if ($mixLocationsData && (count($mixLocationsData['results']) == 1))
			{
				// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->locations_model->reactivateOne($intLocationId))
					{
					// Yes				
                                        // Log it first
                                            $this->logThis("Reactivated location", "locations", $intLocationId);
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Location Reactivated')));
						redirect('locations/viewall/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Location could not be reactivated";
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to reactivate the location.";
						$booNoErrors = false;
					}
			
				}
				
				$arrPageData['strName'] 		= $mixLocationsData['results'][0]->locationname;
				$arrPageData['strBarcode'] 		= $mixLocationsData['results'][0]->locationbarcode;
			
					
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Location could not be found.";
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve location information.";
				$booNoErrors = false;
			}
		}
		else
		{
			$arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
			$arrPageData['strPageTitle'] = "Security Check Point";
			$arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
		}
		
		$this->load->view('common/header', 	$arrPageData);
		if ($booPermission && $booNoErrors)
		{
			//load the correct view
			$this->load->view('locations/reactivate', $arrPageData);
			$this->load->view('common/forms/safetycheck', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
}