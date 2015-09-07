<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sites extends MY_Controller {

	
	public function index()
	{
		$this->viewAll();
	}
	
	public function editOne($intId)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/sites/edit/'.$intId.'/');
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
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Sites.editOne");
		$booSuccess = false;
		
		if ($booPermission)
		{
			// models
			$this->load->model('sites_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                        
			$arrPageData['arrSiteData'] = array('results' => array());
			$arrPageData['strName'] 		= "";
			
			$arrPageData['intSiteId'] 		= $intId;
			
			
			$mixSitesData = $this->sites_model->getOne($intId, $this->session->userdata('objSystemUser')->accountid);
			
			
			
			// did we find any?
			if ($mixSitesData && (count($mixSitesData['results']) == 1))
			{
				$arrPageData['arrSitesData'] = $mixSitesData;
				$booSuccess = true;
				$arrPageData['strName'] 		= $mixSitesData['results'][0]->sitename;
				
				
				// is there a submission?
				if ($this->input->post())
				{
					
					if ($this->input->post('name') != $mixSitesData['results'][0]->sitename)
					{
						//$this->form_validation->set_rules('name', 'Name', 'trim|required');
					}
					
					//if ($this->form_validation->run())
					//{
						
						$arrSiteData = array(
									'name' => $this->input->post('name')
									);
                                                
						if ($this->sites_model->editOne($intId, $arrSiteData))
						{
                                                    // Log it first
                                                    
                                                    $this->logThis("Edited site", "sites", $intId);
							$this->session->set_userdata('booCourier', true);
							$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The site was successfully updated')));
							redirect('/sites/index/', 'refresh');
						}
						else
						{
							$arrPageData['arrErrorMessages'][] = "Unable to edit the site.";
						}
					//}
					$arrPageData['strName'] 		= $this->input->post('name');
					
				}
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Unable to find the site.";
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
			$this->load->view('sites/edit', $arrPageData);
			$this->load->view('sites/forms/add', $arrPageData);
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
			$this->session->set_userdata('strReferral', '/sites/viewall/');
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
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Sites.viewAll");
		
		if ($booPermission)
		{
			// models
			$this->load->model('sites_model');
			$arrPageData['arrSitesData'] = array('results' => array());
			
			$mixSitesData = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid, false);
			
			// did we find any?
			if ($mixSitesData && (count($mixSitesData['results']) > 0))
			{
				$arrPageData['arrSitesData'] = $mixSitesData;
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Unable to find any sites.";
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
			$this->load->view('sites/viewall', $arrPageData);
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
			$this->session->set_userdata('strReferral', '/sites/addone/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Add a Site";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Sites.addOne");
		
		if ($booPermission)
		{
			// models
			$this->load->model('sites_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			$arrPageData['strName'] 		= "";
			
			
			// is there a submission?
			if ($this->input->post())
			{
				$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_checkSiteName');
				if ($this->form_validation->run())
				{
					$arrSiteData = array(
								'name' => $this->input->post('name'),
								'account_id' => $this->session->userdata('objSystemUser')->accountid
								);
                                        $mixSuccess = $this->sites_model->addOneAndReturnId($arrSiteData);
					if ($mixSuccess)
					{
                                            // Log it first
                                        
                                            $this->logThis("Added site", "sites", $mixSuccess);
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The site was successfully added')));
						redirect('/sites/index/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Unable to add the site.";
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
			$this->load->view('sites/add', $arrPageData);
			$this->load->view('sites/forms/add', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
        public function checkSiteName($strName)
        {
            
            // models
            $this->load->model('sites_model');
            if ($this->sites_model->doCheckSiteNameIsUniqueOnAccount($strName, $this->session->userdata('objSystemUser')->accountid))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('checkSiteName', 'There is already a site called that.');
                return false;
            }
            
		
        }
        
	public function deleteOne($intSiteId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/sites/deleteone/'.$intSiteId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Delete a Site";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Sites.deleteOne");
		$booNoErrors = true;
		$arrPageData['intSiteId'] 		= $intSiteId;
		
		if ($booPermission)
		{
			// models
			$this->load->model('sites_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			$mixSitesData = $this->sites_model->getOne($intSiteId, $this->session->userdata('objSystemUser')->accountid);
			
			// did we find any?
			if ($mixSitesData && (count($mixSitesData['results']) == 1))
			{
			// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->sites_model->deleteOne($intSiteId))
					{
					// Yes		
                                        // Log it first
                                        
                                            $this->logThis("Deactivated site", "sites", $intSiteId);		
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Site Deleted')));
						redirect('sites/viewall/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Site could not be deleted";
						$arrPageData['strPageTitle'] = "Oooops!";
						$arrPageData['strPageText'] = "You cannot delete this site, perhaps it has active items linked to it?";
						$booNoErrors = false;
					}
			
				}
				$arrPageData['strName'] 		= $mixSitesData['results'][0]->sitename;
				
			
				
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Site could not be found.";
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve site information.";
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
			$this->load->view('sites/delete', $arrPageData);
			$this->load->view('common/forms/safetycheck', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function reactivateOne($intSiteId = -1)
	{
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
			$this->session->set_userdata('strReferral', '/sites/reactivateone/'.$intSiteId.'/');
			redirect('users/login/', 'refresh');
		}
		
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Reactivate a Site";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Sites.reactivateOne");
		$booNoErrors = true;
		$arrPageData['intSiteId'] 		= $intSiteId;
		
		if ($booPermission)
		{
			// models
			$this->load->model('sites_model');
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			$mixSitesData = $this->sites_model->getOne($intSiteId, $this->session->userdata('objSystemUser')->accountid);
			
			// did we find any?
			if ($mixSitesData && (count($mixSitesData['results']) == 1))
			{
				// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->sites_model->reactivateOne($intSiteId))
					{
					// Yes			
                                        // Log it first
                                        
                                            $this->logThis("Reactivated site", "sites", $intSiteId);
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Site Reactivated')));
						redirect('sites/viewall/', 'refresh');
					}
					else
					{
						$arrPageData['arrErrorMessages'][] = "Site could not be reactivated";
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to reactivate the site.";
						$booNoErrors = false;
					}
			
				}
				
				$arrPageData['strName'] 		= $mixSitesData['results'][0]->sitename;
				
			
					
			}
			else
			{
				$arrPageData['arrErrorMessages'][] = "Site could not be found.";
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve site information.";
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
			$this->load->view('sites/reactivate', $arrPageData);
			$this->load->view('common/forms/safetycheck', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
        
        
        
         
         
}