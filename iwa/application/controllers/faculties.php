<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faculties extends MY_Controller {

	
	public function index()
	{
		$this->viewAll();
	}
	
	public function editOne($intId)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/faculties/edit/'.$intId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Faculties.editOne");
		$booSuccess = false;
		
		if ($booPermission)
		{
			// models
			$this->load->model('faculties_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                        
			$arrPageData['arrFacultyData'] = array('results' => array());
			$arrPageData['strName'] 		= "";
			
			$arrPageData['intFacultyId'] 		= $intId;
			
			
			$mixFacultiesData = $this->faculties_model->getOne($intId, $this->session->userdata('objSystemUser')->accountid);
			
			
			
			// did we find any?
			if ($mixFacultiesData && (count($mixFacultiesData['results']) == 1))
			{
				$arrPageData['arrFacultiesData'] = $mixFacultiesData;
				$booSuccess = true;
				$arrPageData['strName'] 		= $mixFacultiesData['results'][0]->facultyname;
				
				
				// is there a submission?
				if ($this->input->post())
				{
					
					if ($this->input->post('name') != $mixFacultiesData['results'][0]->facultyname)
					{
						$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_checkFacultyName');
					}
					
					if ($this->form_validation->run())
					{
						
						$arrFacultyData = array(
									'name' => $this->input->post('name')
									);
						if ($this->faculties_model->editOne($intId, $arrFacultyData))
						{
                                                    // Log it first
                                        
                                                    $this->logThis("Edited faculty", "faculties", $intId);
							$this->session->set_userdata('booCourier', true);
							$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The faculty was successfully updated')));
							redirect('/faculties/index/', 'refresh');
						}
						else
						{
							$arrPageData['arrErrorMessages'][] = "Unable to edit the faculty.";
						}
					}
					$arrPageData['strName'] 		= $this->input->post('name');
					
				}
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Unable to find the faculty.";
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
			$this->load->view('faculties/edit', $arrPageData);
			$this->load->view('faculties/forms/add', $arrPageData);
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
			$this->session->set_userdata('strReferral', '/faculties/viewall/');
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
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Faculties.viewAll");
		
		if ($booPermission)
		{
			// models
			$this->load->model('faculties_model');
			$arrPageData['arrFacultiesData'] = array('results' => array());
			
			$mixFacultiesData = $this->faculties_model->getAll($this->session->userdata('objSystemUser')->accountid, false);
			
			// did we find any?
			if ($mixFacultiesData && (count($mixFacultiesData['results']) > 0))
			{
				$arrPageData['arrFacultiesData'] = $mixFacultiesData;
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Unable to find any faculties.";
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
			$this->load->view('faculties/viewall', $arrPageData);
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
			$this->session->set_userdata('strReferral', '/faculties/addone/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Add a Faculty";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Faculties.addOne");
		
		if ($booPermission)
		{
			// models
			$this->load->model('faculties_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			$arrPageData['strName'] 		= "";
			
			
			// is there a submission?
			if ($this->input->post())
			{
				$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_checkFacultyName');
				if ($this->form_validation->run())
				{
					$arrFacultyData = array(
								'name' => $this->input->post('name'),
								'account_id' => $this->session->userdata('objSystemUser')->accountid
								);
                                        $mixSuccess = $this->faculties_model->addOneAndReturnId($arrFacultyData);
					if ($mixSuccess)
					{
                                            // Log it first
                                        
                                            $this->logThis("Added faculty", "faculties", $mixSuccess);
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The faculty was successfully added')));
						redirect('/faculties/index/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Unable to add the faculty.";
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
			$this->load->view('faculties/add', $arrPageData);
			$this->load->view('faculties/forms/add', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
        public function checkFacultyName($strName)
        {
            
            // models
            $this->load->model('faculties_model');
            if ($this->faculties_model->doCheckFacultyNameIsUniqueOnAccount($strName, $this->session->userdata('objSystemUser')->accountid))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('checkFacultyName', 'There is already a faculty called that.');
                return false;
            }
            
		
        }
        
	public function deleteOne($intFacultyId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/faculties/deleteone/'.$intFacultyId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Delete a Faculty";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Faculties.deleteOne");
		$booNoErrors = true;
		$arrPageData['intFacultyId'] 		= $intFacultyId;
		
		if ($booPermission)
		{
			// models
			$this->load->model('faculties_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			$mixFacultiesData = $this->faculties_model->getOne($intFacultyId, $this->session->userdata('objSystemUser')->accountid);
			
			// did we find any?
			if ($mixFacultiesData && (count($mixFacultiesData['results']) == 1))
			{
			// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->faculties_model->deleteOne($intFacultyId))
					{
					// Yes		
                                        // Log it first
                                        
                                            $this->logThis("Deactivated faculty", "faculties", $intFacultyId);		
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Faculty Deleted')));
						redirect('faculties/viewall/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Faculty could not be deleted";
						$arrPageData['strPageTitle'] = "Oooops!";
						$arrPageData['strPageText'] = "You cannot delete this faculty, perhaps it has active items linked to it?";
						$booNoErrors = false;
					}
			
				}
				$arrPageData['strName'] 		= $mixFacultiesData['results'][0]->facultyname;
				
			
				
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Faculty could not be found.";
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve faculty information.";
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
			$this->load->view('faculties/delete', $arrPageData);
			$this->load->view('common/forms/safetycheck', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function reactivateOne($intFacultyId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/faculties/reactivateone/'.$intFacultyId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Reactivate a Faculty";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Faculties.reactivateOne");
		$booNoErrors = true;
		$arrPageData['intFacultyId'] 		= $intFacultyId;
		
		if ($booPermission)
		{
			// models
			$this->load->model('faculties_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			$mixFacultiesData = $this->faculties_model->getOne($intFacultyId, $this->session->userdata('objSystemUser')->accountid);
			
			// did we find any?
			if ($mixFacultiesData && (count($mixFacultiesData['results']) == 1))
			{
				// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->faculties_model->reactivateOne($intFacultyId))
					{
					// Yes			
                                        // Log it first
                                        
                                            $this->logThis("Reactivated faculty", "faculties", $intFacultyId);
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Faculty Reactivated')));
						redirect('faculties/viewall/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Faculty could not be reactivated";
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to reactivate the faculty.";
						$booNoErrors = false;
					}
			
				}
				
				$arrPageData['strName'] 		= $mixFacultiesData['results'][0]->facultyname;
				
			
					
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Faculty could not be found.";
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve faculty information.";
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
			$this->load->view('faculties/reactivate', $arrPageData);
			$this->load->view('common/forms/safetycheck', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
        
        
        
         
         
}