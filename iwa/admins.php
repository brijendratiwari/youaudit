<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admins extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function index()
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/index/');
			redirect('admins/login/', 'refresh');
		}
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Dashboard";
		$arrPageData['arrPageParameters']['strPage'] = "Welcome";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$arrPageData['strPageTitle'] = "Welcome to iWork Audit";
		$arrPageData['strPageText'] = "You are a SysAdmin User";
		
                $this->load->model('admins_model');
                
                $arrSuperAdminRequests = $this->admins_model->getSuperAdminRequests();
		$arrPageData['arrSuperAdminRequests'] = $arrSuperAdminRequests;
                
                $arrPageData['arrAdmins'] = $this->admins_model->getAll();
                $arrPageData['intActiveAccounts'] = $this->admins_model->getActiveAccountCount();
                
                
                
		$this->load->view('common/header', 	$arrPageData);	
		$this->load->view('admins/index', 	$arrPageData);
		$this->load->view('common/footer', 	$arrPageData);		
	}
	
	public function logIn($booLogout = false)
	{
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "SysAdmin Login";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
                if ($this->session->userdata('booUserLogin'))
                {
                    $this->session->set_userdata('strReferral', '/admins/login/');
                    redirect('users/logout/', 'refresh');
                }
                
                
		if ($booLogout == true)
		{
			$arrPageData['arrUserMessages'][] = "You were successfully logged out";
		}
		
		// helpers
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// load models
		$this->load->model('admins_model');
		
		// check to see if the user has submitted
		if ($this->input->post('submit'))
		{
			//does the record exist?
			$arrAdminData = $this->admins_model->logIn();
	
			if ($arrAdminData['booSuccess'])
			{
				$this->session->set_userdata('booAdminLogin', TRUE);
				$this->session->set_userdata('objAdminUser', $arrAdminData['result'][0]);
				// We need to set some user messages before redirect
				$this->session->set_userdata('booCourier', true);
				$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('You were successfully logged in')));
				
				if (!$this->session->userdata('strReferral'))
				{
					redirect('/admins/index/', 'refresh');
				}
				else
				{
					$strReferral = $this->session->userdata('strReferral');
					$this->session->unset_userdata('strReferral');
					redirect($strReferral, 'refresh');
				}
			}
			else
			{
				$this->session->set_userdata('booAdminLogin', FALSE);
				$arrPageData['arrErrorMessages'][] = "Log-in failure";
			}
		}
	
		// load views
		$this->load->view('common/header', 	$arrPageData);
		$this->load->view('admins/login', 	$arrPageData);
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function logout()
	{	
		$this->session->unset_userdata(array('booAdminLogin', 'objAdminUser'));
		$this->session->sess_destroy();
		
		
		// We need to set some user messages before redirect
		redirect('/admins/login/true/', 'refresh');
	}
	
	public function viewAdmins()
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/viewadmins/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Administrators";
		$arrPageData['arrPageParameters']['strPage'] = "View SysAdmin Users";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('admins_model');
		
		$arrPageData['arrAdmins'] = $this->admins_model->getAll();
		
		// Check the user was found
		if ($arrPageData['arrAdmins']['booSuccess'] != true)
		{
			// write error
			$arrPageData['arrErrorMessages'][] = "No SysAdmins were found";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		$this->load->view('admins/all', 	$arrPageData);
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function viewAdmin($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/viewadmin/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Administrators";
		$arrPageData['arrPageParameters']['strPage'] = "View SysAdmin User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('admins_model');
			
			// Use model to find user details
			$arrPageData['arrAdmin'] = $this->admins_model->getOne($intId);
			
			// Check the user was found
			if ($arrPageData['arrAdmin']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "SysAdmin not found";
			}
		}
		else
		{
			$arrPageData['arrAdmin']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "SysAdmin Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrAdmin']['booSuccess'] == true)
		{
			$this->load->view('admins/admin', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function deleteAdmin($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/deleteadmin/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Administrators";
		$arrPageData['arrPageParameters']['strPage'] = "Delete SysAdmin User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('admins_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrAdmin'] = $this->admins_model->getOne($intId);
			
			// Check the user was found
			if ($arrPageData['arrAdmin']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "SysAdmin not found";
			}
			else
			{
				// Check if updated
				if ($this->input->post('submit') && ($this->input->post('safety') == "1"))
				{
					if ($this->admins_model->deleteOne($intId))
					{
					// Yes				
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Deleted')));
						redirect('admins/viewadmins/', 'refresh');
					}
					else
					{
						//shouldn't happen, but here for catching the error
						$arrPageData['arrAdmin']['booSuccess'] = false;
						// write error
						$arrPageData['arrErrorMessages'][] = "SysAdmin could not be deleted";
						
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "Are you the last SysAdmin?";
					}
				}
			}
		
		}
		else
		{
			$arrPageData['arrAdmin']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "SysAdmin Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrAdmin']['booSuccess'] == true)
		{
			$this->load->view('admins/delete', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function edit($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/edit/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Administrators";
		$arrPageData['arrPageParameters']['strPage'] = "Edit SysAdmin User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$arrPageData['strFirstName'] = "";
		$arrPageData['strLastName'] = "";
		$arrPageData['strNickName'] = "";
                $arrPageData['strUserName'] = "";
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('admins_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrAdmin'] = $this->admins_model->getOne($intId);
			// Check the user was found
			if ($arrPageData['arrAdmin']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "SysAdmin not found";
			}
			else
			{
				
				// set the form fields ready for display
				$arrPageData['strFirstName'] = $arrPageData['arrAdmin']['result'][0]->firstname;
				$arrPageData['strLastName'] = $arrPageData['arrAdmin']['result'][0]->lastname;
				$arrPageData['strNickName'] = $arrPageData['arrAdmin']['result'][0]->nickname;
                                $arrPageData['strUserName'] = $arrPageData['arrAdmin']['result'][0]->username;
				$arrPageData['intAdminId']  = $intId;
				
				// Check if updated
				if ($this->input->post())
				{
					$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
					$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
                                        
                                        if ($this->input->post('password') != "")
                                        {
                                            $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
                                        }
                                        
					$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
					
					if ($this->form_validation->run())
					{
					// the form validated, so try to create
						//does the record create?
						if ($this->admins_model->setOne($intId))
						{
							// Yes				
							// We need to set some user messages before redirect
							$this->session->set_userdata('booCourier', true);
							$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Record Updated')));
							redirect('admins/viewadmins/', 'refresh');
						}
						else
						{
							// No. ERROR
							$arrPageData['arrErrorMessages'][] = "System Admin Not Created";
							
						}
					}
					
					// if we're here, there's an error somewhere, so repopulate the form fields.
					$arrPageData['strFirstName'] = $this->input->post('firstname');
					$arrPageData['strLastName'] = $this->input->post('lastname');
					$arrPageData['strNickName'] = $this->input->post('nickname');
					
					
					
				}
				
			}
			
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		
		if ($arrPageData['arrAdmin']['booSuccess'] == true)
		{
			$this->load->view('admins/edit', 	$arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
		
	}
	
	public function changeCredentials($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/changecredentials/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Administrators";
		$arrPageData['arrPageParameters']['strPage'] = "Change SysAdmin Credentials";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$arrPageData['strUserName'] = "";
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('admins_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrAdmin'] = $this->admins_model->getOne($intId);
			// Check the user was found
			if ($arrPageData['arrAdmin']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "SysAdmin not found";
			}
			else
			{
				
				// set the form fields ready for display
				$arrPageData['strUserName'] = $arrPageData['arrAdmin']['result'][0]->username;
				$arrPageData['intAdminId']  = $intId;
				
				// Check if updated
				if ($this->input->post('submit'))
				{
					$booPasswordChanged = false;
					$booUsernameChanged = false;
					if ($this->input->post('username') != $arrPageData['arrAdmin']['result'][0]->username)
					{
						$this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email|is_unique[systemadmins.username]');
						$booUsernameChanged = true;
					}	
					if ($this->input->post('password') != '')
					{
						$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
						$booPasswordChanged = true;
					}
					$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
					
					if (($booUsernameChanged || $booPasswordChanged) && $this->form_validation->run())
					{
					// the form validated, so try to create
						//does the record create?
						if ($this->admins_model->setCredentials($intId, $booUsernameChanged, $booPasswordChanged))
						{
							// Yes				
							// We need to set some user messages before redirect
							$this->session->set_userdata('booCourier', true);
							$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Record Updated')));
							redirect('admins/viewadmins/', 'refresh');
						}
						else
						{
							// No. ERROR
							$arrPageData['arrErrorMessages'][] = "System Admin Not Updated";
							
						}
					}
					
					// if we're here, there's an error somewhere, so repopulate the form fields.
					$arrPageData['strUserName'] = $this->input->post('username');					
					
				}
				
			}
			
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		
		if ($arrPageData['arrAdmin']['booSuccess'] == true)
		{
			$this->load->view('admins/credentials', 	$arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
		
	}
	
	public function create()
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/create/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Administrators";
		$arrPageData['arrPageParameters']['strPage'] = "Create SysAdmin User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// helpers
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// load models
		$this->load->model('admins_model');
		
		// set the form fields ready for display
		$arrPageData['strFirstName'] = "";
		$arrPageData['strLastName'] = "";
		$arrPageData['strUserName'] = "";
		$arrPageData['strNickName'] = "";
		
		// Check if updated
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email|is_unique[systemadmins.username]');
			$this->form_validation->set_message('is_unique', 'There is already someone using this email address to access the system.');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			if ($this->form_validation->run())
			{
			// the form validated, so try to create
				//does the record create?
				if ($this->admins_model->setOne())
				{
					// Yes				
					// We need to set some user messages before redirect
					$this->session->set_userdata('booCourier', true);
					$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Record Created')));
					redirect('admins/viewadmins/', 'refresh');
				}
				else
				{
					// No. ERROR
					$arrPageData['arrErrorMessages'][] = "System Admin Not Created";
					
				}
			}
			// if we're here, there's an error somewhere, so repopulate the form fields.
			$arrPageData['strFirstName'] = $this->input->post('firstname');
			$arrPageData['strLastName'] = $this->input->post('lastname');
			$arrPageData['strUserName'] = $this->input->post('username');
			$arrPageData['strNickName'] = $this->input->post('nickname');
			
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		$this->load->view('admins/create', 	$arrPageData);
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function viewAccounts ()
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/viewaccounts/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "View Accounts";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('accounts_model');
		
		$arrPageData['arrAccounts'] = $this->accounts_model->getAll(false);
		
		// Check the user was found
		if ($arrPageData['arrAccounts']['booSuccess'] != true)
		{
			// write error
			$arrPageData['arrErrorMessages'][] = "No accounts were found";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		$this->load->view('admins/accounts_all',$arrPageData);
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function viewAccount($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/viewaccount/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "View Account";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		if ($intId > 0)
		{
			// load model
			$this->load->model('accounts_model');
			
			// Use model to find user details
			$arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);
			
			// Check the user was found
			if ($arrPageData['arrAccount']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "Account not found";
			}
		}
		else
		{
			$arrPageData['arrAccount']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "Account Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrAccount']['booSuccess'] == true)
		{
			$this->load->view('admins/account', 	$arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
		
	}
	
	public function deleteAccount($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/deleteaccount/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "Delete Account";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('accounts_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);
			
			// Check the user was found
			if ($arrPageData['arrAccount']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "Account not found";
			}
			else
			{
				// Check if updated
				if ($this->input->post('submit') && ($this->input->post('safety') == "1"))
				{
					if ($this->accounts_model->deleteOne($intId))
					{
					// Yes				
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Deleted')));
						redirect('admins/viewaccounts/', 'refresh');
					}
					else
					{
						//shouldn't happen, but here for catching the error
						$arrPageData['arrAccount']['booSuccess'] = false;
						// write error
						$arrPageData['arrErrorMessages'][] = "Account could not be deleted";
						
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "We apologise for this error.";
					}
				}
			}
		
		}
		else
		{
			$arrPageData['arrAccount']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "User Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrAccount']['booSuccess'] == true)
		{
			$this->load->view('admins/account_delete', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function reactivateAccount($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/reactivateaccount/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "Reactivate Account";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('accounts_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);
			
			// Check the user was found
			if ($arrPageData['arrAccount']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "Account not found";
			}
			else
			{
				// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->accounts_model->reactivateOne($intId))
					{
					// Yes				
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Reactivated')));
						redirect('admins/viewaccounts/', 'refresh');
					}
					else
					{
						//shouldn't happen, but here for catching the error
						$arrPageData['arrAccount']['booSuccess'] = false;
						// write error
						$arrPageData['arrErrorMessages'][] = "Account could not be reactivated";
						
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "We apologise for this error.";
					}
				}
			}
		
		}
		else
		{
			$arrPageData['arrUser']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "Account Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrAccount']['booSuccess'] == true)
		{
			$this->load->view('admins/account_reactivate', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function editAccount ($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/editaccount/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "Edit an Account";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('accounts_model');
			$this->load->model('packages_model');
			$arrPageData['arrPackages'] 	= $this->packages_model->getAll();
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find account details
			$arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);
			
			// Check the account was found
			if ($arrPageData['arrAccount']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "Account not found";
			}
			else
			{
				// set the form fields ready for display
				$arrPageData['strAccountName'] = 		$arrPageData['arrAccount']['result'][0]->accountname;
				$arrPageData['strAccountAddress'] =		$arrPageData['arrAccount']['result'][0]->accountaddress;
				$arrPageData['strAccountCity'] = 		$arrPageData['arrAccount']['result'][0]->accountcity;
				$arrPageData['strAccountCounty'] = 		$arrPageData['arrAccount']['result'][0]->accountcounty;
				$arrPageData['strAccountPostCode'] = 		$arrPageData['arrAccount']['result'][0]->accountpostcode;
				$arrPageData['strAccountCountry'] = 		$arrPageData['arrAccount']['result'][0]->accountcountry;
				$arrPageData['strAccountSecurityQuestion'] = 	$arrPageData['arrAccount']['result'][0]->accountsecurityquestion;
				$arrPageData['strAccountSecurityAnswer'] = 	$arrPageData['arrAccount']['result'][0]->accountsecurityanswer;
				$arrPageData['strAccountContactName'] = 	$arrPageData['arrAccount']['result'][0]->accountcontactname;
				$arrPageData['strAccountContactEmail'] = 	$arrPageData['arrAccount']['result'][0]->accountcontactemail;
				$arrPageData['strAccountContactNumber'] = 	$arrPageData['arrAccount']['result'][0]->accountcontactnumber;
				$arrPageData['intAccountPackageId'] = 		$arrPageData['arrAccount']['result'][0]->accountpackageid;
				$arrPageData['intAccountActive'] = 		$arrPageData['arrAccount']['result'][0]->accountactive;
				$arrPageData['intAccountVerified'] = 		$arrPageData['arrAccount']['result'][0]->accountverified;
                $arrPageData['strAccountSupportAddress'] = 	$arrPageData['arrAccount']['result'][0]->accountsupportemail;
                $arrPageData['strAccountFleetContact'] =      $arrPageData['arrAccount']['result'][0]->accountfleetcontact;
                $arrPageData['strAccountFleetEmail'] =      $arrPageData['arrAccount']['result'][0]->accountfleetemail;
                $arrPageData['strAccountComplianceContact'] =      $arrPageData['arrAccount']['result'][0]->accountcompliancecontact;
                $arrPageData['strAccountComplianceEmail'] =      $arrPageData['arrAccount']['result'][0]->accountcomplianceemail;
                $arrPageData['intAccountFleet'] =               $arrPageData['arrAccount']['result'][0]->accountfleet;
                $arrPageData['intAccountCompliance'] =          $arrPageData['arrAccount']['result'][0]->accountcompliance;
				$arrPageData['intAccountId']  = 		$intId;
				
				// Check if updated
				if ($this->input->post())
				{
					$this->form_validation->set_rules('account_name', 'Name', 'trim|required');
					$this->form_validation->set_rules('account_address', 'Address', 'trim|required');
					$this->form_validation->set_rules('account_city', 'City', 'trim|required');
					$this->form_validation->set_rules('account_postcode', 'Post Code', 'trim|required');
					$this->form_validation->set_rules('account_securityquestion', 'Security Question', 'trim|required');
					$this->form_validation->set_rules('account_securityanswer', 'Security Answer', 'trim|required');
					$this->form_validation->set_rules('account_contactname', 'Contact Name', 'trim|required');
					$this->form_validation->set_rules('account_contactemail', 'Contact Email', 'trim|required');
                    $this->form_validation->set_rules('account_supportaddress', 'Support Email', 'trim|required');
					$this->form_validation->set_rules('account_contactnumber', 'Contact Number', 'trim|required');
					$this->form_validation->set_rules('account_packageid', 'Package', 'required|is_natural_no_zero');
					$this->form_validation->set_message('is_natural_no_zero', 'You must select a valid package for this user.');
					
					
					$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
					
					if ($this->form_validation->run())
					{
					// the form validated, so try to create
						//does the record create?
						if ($this->accounts_model->setOne($intId))
						{
							// Yes				
							// We need to set some user messages before redirect
							$this->session->set_userdata('booCourier', true);
							$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Updated')));
							redirect('admins/viewaccounts/', 'refresh');
						}
						else
						{
							// No. ERROR
							$arrPageData['arrErrorMessages'][] = "Account Not Updated";
						}
					}
					else
					{
						// if we're here, there's an error somewhere, so repopulate the form fields.

						$arrPageData['strAccountName'] = 		$this->input->post('account_name');
						$arrPageData['strAccountAddress'] =		$this->input->post('account_address');
						$arrPageData['strAccountCity'] = 		$this->input->post('account_city');
						$arrPageData['strAccountCounty'] = 		$this->input->post('account_county');
						$arrPageData['strAccountPostCode'] = 		$this->input->post('account_postcode');
						$arrPageData['strAccountCountry'] = 		$this->input->post('account_country');
						$arrPageData['strAccountSecurityQuestion'] = 	$this->input->post('account_securityquestion');
						$arrPageData['strAccountSecurityAnswer'] = 	$this->input->post('account_securityanswer');
						$arrPageData['strAccountContactName'] = 	$this->input->post('account_contactname');
						$arrPageData['strAccountContactEmail'] = 	$this->input->post('account_contactemail');
						$arrPageData['strAccountContactNumber'] = 	$this->input->post('account_contactnumber');
						$arrPageData['intAccountPackageId'] = 		$this->input->post('account_packageid');
						$arrPageData['intAccountVerified'] = 		$this->input->post('account_verified');
                        $arrPageData['strAccountSupportAddress'] = 	$this->input->post('account_supportemail');
                        $arrPageData['strAccountFleetContact'] =    $this->input->post('account_fleetcontact');
                        $arrPageData['strAccountFleetEmail'] =      $this->input->post('account_fleetemail');
                        $arrPageData['strAccountComplianceContact'] = $this->input->post('account_compliancecontact');
                        $arrPageData['strAccountComplianceEmail'] =   $this->input->post('account_complianceemail');
						$arrPageData['intAccountId']  = 		$intId;
					}
				}
			}
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		
		if ($arrPageData['arrAccount']['booSuccess'] == true)
		{
			$this->load->view('admins/account_edit', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function createAccount ()
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/createaccount/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "Create an Account";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('accounts_model');
		$this->load->model('packages_model');
		
		$arrPageData['arrPackages'] 	= $this->packages_model->getAll();
		// helpers
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set the form fields ready for display
		$arrPageData['strAccountName'] = 		"";
		$arrPageData['strAccountAddress'] =		"";
		$arrPageData['strAccountCity'] = 		"";
		$arrPageData['strAccountCounty'] = 		"";
		$arrPageData['strAccountPostCode'] = 		"";
		$arrPageData['strAccountCountry'] = 		"";
		$arrPageData['strAccountSecurityQuestion'] = 	"";
		$arrPageData['strAccountSecurityAnswer'] = 	"";
		$arrPageData['strAccountContactName'] = 	"";
		$arrPageData['strAccountContactEmail'] = 	"";
		$arrPageData['strAccountContactNumber'] = 	"";
		$arrPageData['intAccountPackageId'] = 		0;
		$arrPageData['intAccountActive'] = 		1;
		$arrPageData['intAccountVerified'] = 		0;
                $arrPageData['strAccountSupportAddress'] =      "";
			
		// Check if updated
		if ($this->input->post())
		{
			$this->form_validation->set_rules('account_name', 'Name', 'trim|required');
			$this->form_validation->set_rules('account_address', 'Address', 'trim|required');
			$this->form_validation->set_rules('account_city', 'City', 'trim|required');
			$this->form_validation->set_rules('account_postcode', 'Post Code', 'trim|required');
			$this->form_validation->set_rules('account_securityquestion', 'Security Question', 'trim|required');
			$this->form_validation->set_rules('account_securityanswer', 'Security Answer', 'trim|required');
			$this->form_validation->set_rules('account_contactname', 'Contact Name', 'trim|required');
			$this->form_validation->set_rules('account_contactemail', 'Contact Email', 'trim|required|xss_clean|valid_email|is_unique[users.username]');
			$this->form_validation->set_message('is_unique', 'There is already someone using this email address to access the system.');
			$this->form_validation->set_rules('account_contactpassword', 'Password', 'trim|required|md5');
			$this->form_validation->set_rules('account_contactnumber', 'Contact Number', 'trim|required');
			$this->form_validation->set_rules('account_packageid', 'Package', 'required|is_natural_no_zero');
                        $this->form_validation->set_rules('account_supportaddress', 'Support Email', 'trim|xss_clean|valid_email');
                        
			$this->form_validation->set_message('is_natural_no_zero', 'You must select a valid package for this user.');
			
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			if ($this->form_validation->run())
			{
			// the form validated, so try to create
				//does the record create?
				if ($this->accounts_model->setOne())
				{
					// Yes				
					// We need to set some user messages before redirect
					$this->session->set_userdata('booCourier', true);
					$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Created, with one admin user')));
					redirect('admins/viewaccounts/', 'refresh');
				}
				else
				{
					// No. ERROR
					$arrPageData['arrErrorMessages'][] = "Account Not Updated";
				}
			}
			else
			{
				// if we're here, there's an error somewhere, so repopulate the form fields.
				$arrPageData['arrErrorMessages'][] = "Unable to create account. Please check for form errors.";
				$arrPageData['strAccountName'] = 		$this->input->post('account_name');
				$arrPageData['strAccountAddress'] =		$this->input->post('account_address');
				$arrPageData['strAccountCity'] = 		$this->input->post('account_city');
				$arrPageData['strAccountCounty'] = 		$this->input->post('account_county');
				$arrPageData['strAccountPostCode'] = 		$this->input->post('account_postcode');
				$arrPageData['strAccountCountry'] = 		$this->input->post('account_country');
				$arrPageData['strAccountSecurityQuestion'] = 	$this->input->post('account_securityquestion');
				$arrPageData['strAccountSecurityAnswer'] = 	$this->input->post('account_securityanswer');
				$arrPageData['strAccountContactName'] = 	$this->input->post('account_contactname');
				$arrPageData['strAccountContactEmail'] = 	$this->input->post('account_contactemail');
				$arrPageData['strAccountContactNumber'] = 	$this->input->post('account_contactnumber');
				$arrPageData['intAccountPackageId'] = 		$this->input->post('account_packageid');
				$arrPageData['intAccountVerified'] = 		$this->input->post('account_verified');
                                $arrPageData['intAccountFleet'] =               $arrPageData['arrAccount']['result'][0]->accountfleet;
                                $arrPageData['intAccountCompliance'] =          $arrPageData['arrAccount']['result'][0]->accountcompliance;
                                $arrPageData['strAccountSupportAddress'] =      $this->input->post('account_supportaddress');
				
			}
		}
		
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		
		$this->load->view('admins/account_create', $arrPageData);
		
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function viewUsers ($intAccountId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/viewusers/'.$intAccountId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strSubSection'] = "Users";
		$arrPageData['arrPageParameters']['strPage'] = "View System Users";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// load models
		$this->load->model('users_model');
		$this->load->model('admins_model');
                
                if ($intAccountId > 0)
                {
                    $arrPageData['arrUsers'] = $this->users_model->getAll($intAccountId);
                    $arrPageData['strAccountName'] = $this->admins_model->getAccountName($intAccountId);
                    // Check the user was found
                    if ($arrPageData['arrUsers']['booSuccess'] != true)
                    {
                            // write error
                            $arrPageData['arrErrorMessages'][] = "No users were found";
                    }   
                }
                
		// load views
		$this->load->view('common/header', 	$arrPageData);
                if ($intAccountId > 0)
                {
                    $this->load->view('admins/users_all', 	$arrPageData);
                }
                else
                {
                    $arrPageData['arrErrorMessages'][] = "No account selected.";
                    // write error
		    $arrPageData['strPageTitle'] = "System Error";
                    $arrPageData['strPageText'] = "We apologise for this error.";
                    $this->load->view('common/system_message', $arrPageData);
                }
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function viewUser($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/viewuser/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strSubSection'] = "Users";
                $arrPageData['arrPageParameters']['strTab'] = "Users";
		$arrPageData['arrPageParameters']['strPage'] = "View System User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		if ($intId > 0)
		{
			// load model
			$this->load->model('users_model');
			
			// Use model to find user details
			$arrPageData['arrUser'] = $this->users_model->getOne($intId);
			
			// Check the user was found
			if ($arrPageData['arrUser']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "User not found";
			}
		}
		else
		{
			$arrPageData['arrUser']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "User Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrUser']['booSuccess'] == true)
		{
			$this->load->view('admins/user', 	$arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
		
	}
	
	public function inheritUser($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		
		if ($intId > 0)
		{
			// load model
			$this->load->model('users_model');
		
			// Use model to find user details
			$arrUserData = $this->users_model->getBasicCredentialsFor($intId);
			
			// Check the user was found
			if ($arrUserData['booSuccess'] != true)
			{
				// write error
				$this->session->set_userdata('booCourier', true);
				$this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('System was unable to inherit that user profile.')));
				redirect('admins/index/', 'refresh');
			}
			else
			{
				// Yes				
				// We need to set some user messages before redirect
				$this->session->set_userdata('booInheritedUser', TRUE);
				$this->session->set_userdata('objInheritedUser', $arrUserData['result'][0]);
				$this->session->set_userdata('objSystemUser', $arrUserData['result'][0]);
				
				$this->session->set_userdata('booCourier', true);
				$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('You have inherited the user profile.')));
				redirect('welcome/index/', 'refresh');
			}
		}
	}
	
	public function deinheritUser()
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			redirect('admins/login/', 'refresh');
		}
		// Check the user was found
		$this->session->set_userdata('booInheritedUser', false);
		$this->session->unset_userdata(array('booInheritedUser', 'objInheritedUser', 'objSystemUser'));
		$this->session->set_userdata('booCourier', true);
		$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The user was deinherited.')));
		redirect('admins/index/', 'refresh');		
	}
	
	public function deleteUser($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/deleteuser/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Users";
		$arrPageData['arrPageParameters']['strPage'] = "Delete User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('users_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrUser'] = $this->users_model->getOne($intId);
			
			// Check the user was found
			if ($arrPageData['arrUser']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "User not found";
			}
			else
			{
				// Check if updated
				if ($this->input->post('submit') && ($this->input->post('safety') == "1"))
				{
					if ($this->users_model->deleteOne($intId))
					{
					// Yes				
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Deleted')));
						redirect('admins/viewusers/', 'refresh');
					}
					else
					{
						//shouldn't happen, but here for catching the error
						$arrPageData['arrUser']['booSuccess'] = false;
						// write error
						$arrPageData['arrErrorMessages'][] = "User could not be deleted";
						
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "We apologise for this error.";
					}
				}
			}
		
		}
		else
		{
			$arrPageData['arrUser']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "User Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrUser']['booSuccess'] == true)
		{
			$this->load->view('admins/user_delete', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
	public function reactivateUser($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/reactivateuser/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strPage'] = "Reactivate User";
                $arrPageData['arrPageParameters']['strTab'] = "Users";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('users_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrUser'] = $this->users_model->getOneWithoutAccount($intId);
			
                        
                        
			// Check the user was found
			if ($arrPageData['arrUser']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "User not found";
			}
			else
			{
				// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->users_model->reactivate($intId))
					{
					// Yes				
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Reactivated')));
						redirect('admins/viewusers/'.$arrPageData['arrUser']['result'][0]->accountid, 'refresh');
					}
					else
					{
						//shouldn't happen, but here for catching the error
						$arrPageData['arrUser']['booSuccess'] = false;
						// write error
						$arrPageData['arrErrorMessages'][] = "User could not be reactivated";
						
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "We apologise for this error.";
					}
				}
			}
		
		}
		else
		{
			$arrPageData['arrUser']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "User Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrUser']['booSuccess'] == true)
		{
			$this->load->view('admins/user_reactivate', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
	
        public function makeSuperAdmin($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/makesuperadmin/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "Make SuperAdmin";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('users_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrUser'] = $this->users_model->getBasicCredentialsFor($intId);
			
			// Check the user was found
			if ($arrPageData['arrUser']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "User not found";
			}
			else
			{
				// Check if updated
				if ($this->input->post() && ($this->input->post('safety') == "1"))
				{
					if ($this->users_model->makeSuperAdmin($intId,$arrPageData['arrUser']['result'][0]->accountid))
					{
					// Yes				
						// We need to set some user messages before redirect
						$this->session->set_userdata('booCourier', true);
						$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SuperAdmin changed')));
						redirect('admins/', 'refresh');
					}
					else
					{
						//shouldn't happen, but here for catching the error
						$arrPageData['arrUser']['booSuccess'] = false;
						// write error
						$arrPageData['arrErrorMessages'][] = "Account could not be updated";
						
						$arrPageData['strPageTitle'] = "System Error";
						$arrPageData['strPageText'] = "We apologise for this error.";
					}
				}
			}
		
		}
		else
		{
			$arrPageData['arrUser']['booSuccess'] = false;
			// write error
			$arrPageData['arrErrorMessages'][] = "User Id not valid";
			
			$arrPageData['strPageTitle'] = "System Error";
			$arrPageData['strPageText'] = "We apologise for this error.";
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		// errors?
		if ($arrPageData['arrUser']['booSuccess'] == true)
		{
			$this->load->view('admins/user_makesuperadmin', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
	}
        
	public function editUser($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/edituser/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strTab'] = "Users";
		$arrPageData['arrPageParameters']['strPage'] = "Edit User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$arrPageData['strFirstName'] = "";
		$arrPageData['strLastName'] = "";
		$arrPageData['strNickName'] = "";
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('users_model');
			$this->load->model('levels_model');
		
			// Use levels model to find levels available
			$arrPageData['arrLevels'] 	= $this->levels_model->getAll();
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrUser'] = $this->users_model->getOne($intId);
			// Check the user was found
			if ($arrPageData['arrUser']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "User not found";
			}
			else
			{
				
				// set the form fields ready for display
				$arrPageData['strFirstName'] = $arrPageData['arrUser']['result'][0]->firstname;
				$arrPageData['strLastName'] = $arrPageData['arrUser']['result'][0]->lastname;
				$arrPageData['strNickName'] = $arrPageData['arrUser']['result'][0]->nickname;
				$arrPageData['intUserId']  = $intId;
				$arrPageData['intLevelId']  = $arrPageData['arrUser']['result'][0]->levelid;
				$arrPageData['intAccountId']  = $arrPageData['arrUser']['result'][0]->accountid;
				
				// Check if updated
				if ($this->input->post('submit'))
				{
					$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
					$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
					$this->form_validation->set_rules('level_id', 'Level', 'required|is_natural_no_zero');
					$this->form_validation->set_message('is_natural_no_zero', 'You must select a valid option for this user.');
					
					$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
					
					if ($this->form_validation->run())
					{
					// the form validated, so try to create
						//does the record create?
						if ($this->users_model->setOne($intId))
						{
							// Yes				
							// We need to set some user messages before redirect
							$this->session->set_userdata('booCourier', true);
							$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Record Updated')));
							redirect('admins/viewusers/', 'refresh');
						}
						else
						{
							// No. ERROR
							$arrPageData['arrErrorMessages'][] = "User Not Created";
							
						}
					}
					
					// if we're here, there's an error somewhere, so repopulate the form fields.
					$arrPageData['strFirstName'] = $this->input->post('firstname');
					$arrPageData['strLastName'] = $this->input->post('lastname');
					$arrPageData['strNickName'] = $this->input->post('nickname');
					
					
					
				}
				
			}
			
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		
		if ($arrPageData['arrUser']['booSuccess'] == true)
		{
			$this->load->view('admins/user_edit', 	$arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
		
	}
	
	public function changeCredentialsUser($intId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/changecredentialsuser/'.$intId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
                $arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
		$arrPageData['arrPageParameters']['strPage'] = "Change User Credentials";
                $arrPageData['arrPageParameters']['strTab'] = "Accounts";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		$arrPageData['strUserName'] = "";
		
		if ($intId > 0)
		{
			// load models
			$this->load->model('users_model');
			
			// helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// Use model to find user details
			$arrPageData['arrUser'] = $this->users_model->getOneWithoutAccount($intId);
			// Check the user was found
			if ($arrPageData['arrUser']['booSuccess'] != true)
			{
				// write error
				$arrPageData['strPageTitle'] = "System Error";
				$arrPageData['strPageText'] = "We apologise for this error.";
				$arrPageData['arrErrorMessages'][] = "User not found";
			}
			else
			{
				
				// set the form fields ready for display
				$arrPageData['strUserName'] = $arrPageData['arrUser']['result'][0]->username;
                                $arrPageData['strFirstName'] = $arrPageData['arrUser']['result'][0]->firstname;
                                $arrPageData['strLastName'] = $arrPageData['arrUser']['result'][0]->lastname;
                                $arrPageData['strLevelName'] = $arrPageData['arrUser']['result'][0]->levelname;
                                $arrPageData['strAccountName'] = $arrPageData['arrUser']['result'][0]->accountname;
				$arrPageData['intUserId']  = $intId;
				
				// Check if updated
				if ($this->input->post())
				{
					$booPasswordChanged = false;
					$booUsernameChanged = false;
					if ($this->input->post('username') != $arrPageData['arrUser']['result'][0]->username)
					{
						$this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email|is_unique[users.username]');
						$booUsernameChanged = true;
					}	
					if ($this->input->post('password') != '')
					{
						$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
						$booPasswordChanged = true;
					}
					$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
					
					if (($booUsernameChanged || $booPasswordChanged) && $this->form_validation->run())
					{
					// the form validated, so try to create
						//does the record create?
                                            if ($this->users_model->setCredentials($intId, $booUsernameChanged, $booPasswordChanged))
                                            {
                                                    // Yes				
                                                    // We need to set some user messages before redirect
                                                    $this->session->set_userdata('booCourier', true);
                                                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Record Updated')));
                                                    redirect('admins/viewusers/'.$arrPageData['arrUser']['result'][0]->accountid, 'refresh');
                                            }
                                            else
                                            {
                                                    // No. ERROR
                                                    $arrPageData['arrErrorMessages'][] = "User Not Updated";

                                            }
					}
                                        else 
                                        {
                                            $arrPageData['arrErrorMessages'][] = "User credentials not changed";
                                        }
					
					// if we're here, there's an error somewhere, so repopulate the form fields.
					$arrPageData['strUserName'] = $this->input->post('username');					
					
				}
				
			}
			
		}
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		
		if ($arrPageData['arrUser']['booSuccess'] == true)
		{
			$this->load->view('admins/user_credentials', 	$arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
		
	}
	
	public function createUser ($intAccountId = -1, $intLevelId = -1)
	{
		if (!$this->session->userdata('booAdminLogin'))
		{
			$this->session->set_userdata('strReferral', '/admins/createuser/'.$intAccountId.'/'.$intLevelId.'/');
			redirect('admins/login/', 'refresh');
		}
		// housekeeping
		$arrPageData = array();
		$arrPageData['arrPageParameters']['strSection'] = get_class();
		$arrPageData['arrPageParameters']['strSubSection'] = "Users";
                $arrPageData['arrPageParameters']['strTab'] = "Users";
		$arrPageData['arrPageParameters']['strPage'] = "Create System User";
		$arrPageData['arrSessionData'] = $this->session->userdata;
		$this->session->set_userdata('booCourier', false);
		$this->session->set_userdata('arrCourier', array());
		$arrPageData['arrErrorMessages'] = array();
		$arrPageData['arrUserMessages'] = array();
		
		// helpers
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// load models
		$this->load->model('users_model');
		$this->load->model('accounts_model');
		$this->load->model('levels_model');
		
		// Use levels model to find levels available
		$arrPageData['arrAccounts'] 	= $this->accounts_model->getAll();
		$arrPageData['arrLevels'] 	= $this->levels_model->getAll();
		$arrPageData['intAccountId']	= $intAccountId;
		$arrPageData['intLevelId']	= $intLevelId;
		
		// set the form fields ready for display
		$arrPageData['strFirstName'] = "";
		$arrPageData['strLastName'] = "";
		$arrPageData['strUserName'] = "";
		$arrPageData['strNickName'] = "";
		
		// Check if updated
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
			$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('account_id', 'Account', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('level_id', 'Level', 'required|is_natural_no_zero');
			$this->form_validation->set_message('is_natural_no_zero', 'You must select a valid option for this user.');
			$this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email|is_unique[users.username]');
			$this->form_validation->set_message('is_unique', 'There is already someone using this email address to access the system.');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			if ($this->form_validation->run())
			{
			// the form validated, so try to create
				//does the record create?
				if ($this->users_model->setOne())
				{
					// Yes
					// We need to set some user messages before redirect
					$this->session->set_userdata('booCourier', true);
					$this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Record Created')));
					redirect('admins/viewusers/', 'refresh');
				}
				else
				{
					// No. ERROR
					$arrPageData['arrErrorMessages'][] = "User Record Not Created";
					
				}
				
			}
			
			$arrPageData['intLevelId'] = $this->input->post('level_id');
			$arrPageData['intAccountId'] = $this->input->post('account_id');
			$arrPageData['strFirstName'] = $this->input->post('firstname');
			$arrPageData['strLastName'] = $this->input->post('lastname');
			$arrPageData['strUserName'] = $this->input->post('username');
			$arrPageData['strNickName'] = $this->input->post('nickname');
		}		
		
		// load views
		$this->load->view('common/header', 	$arrPageData);
		$this->load->view('admins/user_create', $arrPageData);
		$this->load->view('common/footer', 	$arrPageData);
		
		
	}
        
        public function import() {

            if (!$this->session->userdata('booAdminLogin'))
            {
                    $this->session->set_userdata('strReferral', '/admins/viewadmins/');
                    redirect('admins/login/', 'refresh');
            }
            $this->load->helper('form');
            // housekeeping
            $arrPageData = array();
            $arrPageData['arrPageParameters']['strSection'] = get_class();
            $arrPageData['arrPageParameters']['strTab'] = "Administrators";
            $arrPageData['arrPageParameters']['strPage'] = "Import Items";
            $arrPageData['arrSessionData'] = $this->session->userdata;
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            // load models
            $this->load->model('admins_model');
            $this->load->model('items_model');
            $this->load->model('accounts_model');
            $accounts = $this->accounts_model->getAll();
            $arrPageData['accounts'] = $accounts['results'];
            
            if($this->input->post()) {

                $this->load->library('form_validation');
                $this->load->library('parsecsv');
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'csv';
                $this->load->library('upload', $config);


                /* Config and Load CI File Upload Class */
                if($error = $this->upload->do_upload()) { 

                    $this->items_model->import($this->upload->data(), $this->input->post('account_id'));
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('File Imported Succesfully')));
                    redirect('admins/', 'refresh');                        
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    var_dump($error);
                    die();
                }
             
                
                    
            }
            //$this->output->enable_profiler(TRUE);
            // load views
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('admins/import', 	$arrPageData);
            $this->load->view('common/footer', 	$arrPageData);          
        }

        public function vehicleChecks ()
        {
            if (!$this->session->userdata('booAdminLogin'))
            {
                $this->session->set_userdata('strReferral', '/admins/vehiclechecks/');
                redirect('admins/login/', 'refresh');
            }

            // housekeeping

            $this->load->model('fleet_model');

            // Restore check if requested
            if($id != NULL) {
                $this->fleet_model->editCheckStatus($id, 1);
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The vehicle check was succesfully restored')));
                redirect('/admins/vehicleChecks/', 'refresh');
            }

            $arrPageData = array();
            $arrPageData['arrPageParameters']['strSection'] = get_class();
            $arrPageData['arrPageParameters']['strTab'] = "Vehicle Checks";
            $arrPageData['arrPageParameters']['strSubSection'] = "Deleted Checks";
            $arrPageData['arrPageParameters']['strPage'] = "Deleted Checks";
            $arrPageData['arrSessionData'] = $this->session->userdata;
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            $arrPageData['arrVehicleChecks'] = $this->fleet_model->getDeletedChecks();


            // load views
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('admins/deletedchecks',$arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }

        public function editCheck($check_id) {
            $this->load->model('users_model');
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if (!$this->session->userdata('booAdminLogin'))
            {
                $this->session->set_userdata('strReferral', '/admins/viewadmins/');
                redirect('admins/login/', 'refresh');
            }


            // housekeeping
            $arrPageData = array();
            $arrPageData['arrPageParameters']['strSection'] = get_class();
            $arrPageData['arrPageParameters']['strPage'] = "Select";
            $arrPageData['arrSessionData'] = $this->session->userdata;
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            $this->load->model('fleet_model');
            /* Get all vehicle checks */
            $arrPageData['objCheck'] = $this->fleet_model->getCheck($check_id);

            if ($this->input->post()) {
                $this->form_validation->set_rules('check_name', 'Check name', 'required');
                if ($this->form_validation->run()) {
                    $this->fleet_model->editCheck($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The vehicle check was succesfully modified')));
                    redirect('/admins/vehicleChecks/', 'refresh');
                }

            }

            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('admins/editcheck', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);


        }

        public function deleteCheck($check_id) {
            $this->load->model('users_model');
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if (!$this->session->userdata('booAdminLogin'))
            {
                $this->session->set_userdata('strReferral', '/admins/viewadmins/');
                redirect('admins/login/', 'refresh');
            }

            // housekeeping
            $arrPageData = array();
            $arrPageData['arrPageParameters']['strSection'] = get_class();
            $arrPageData['arrPageParameters']['strPage'] = "Select";
            $arrPageData['arrSessionData'] = $this->session->userdata;
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            $this->load->model('fleet_model');
            /* Get all vehicle checks */
            $arrPageData['objCheck'] = $this->fleet_model->getCheck($check_id);

            if ($this->input->post()) {
                if($this->input->post('delete') == 1) {
                    $this->fleet_model->editCheckStatus($check_id, 0);
                }
                redirect('/admins/vehicleChecks/', 'refresh');
            }



            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('admins/deletecheck', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);


        }

        public function deletedchecks($id = NULL) {
            if (!$this->session->userdata('booAdminLogin'))
            {
                $this->session->set_userdata('strReferral', '/admins/vehiclechecks/');
                redirect('admins/login/', 'refresh');
            }

            // housekeeping

            $this->load->model('fleet_model');

            // Restore check if requested
            if($id != NULL) {
                $this->fleet_model->editCheckStatus($id, 1);
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The vehicle check was succesfully restored')));
                redirect('/admins/vehicleChecks/', 'refresh');
            }

            $arrPageData = array();
            $arrPageData['arrPageParameters']['strSection'] = get_class();
            $arrPageData['arrPageParameters']['strTab'] = "Vehicle Checks";
            $arrPageData['arrPageParameters']['strSubSection'] = "Deleted Checks";
            $arrPageData['arrPageParameters']['strPage'] = "Deleted Checks";
            $arrPageData['arrSessionData'] = $this->session->userdata;
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            $arrPageData['arrVehicleChecks'] = $this->fleet_model->getDeletedChecks();


            // load views
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('admins/deletedchecks',$arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }

        public function newcheck() {
            $this->load->model('users_model');
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if (!$this->session->userdata('booAdminLogin'))
            {
                $this->session->set_userdata('strReferral', '/admins/vehiclechecks/');
                redirect('admins/login/', 'refresh');
            }


            // housekeeping
            $arrPageData = array();
            $arrPageData['arrPageParameters']['strSection'] = get_class();
            $arrPageData['arrPageParameters']['strPage'] = "Select";
            $arrPageData['arrSessionData'] = $this->session->userdata;
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            $this->load->model('fleet_model');
            if ($this->input->post()) {
                $this->form_validation->set_rules('check_name', 'Check name', 'required');
                if ($this->form_validation->run()) {
                    $this->fleet_model->newDefaultCheck($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The new default vehicle check was succesfully recorded')));
                    redirect('/admins/vehiclechecks/', 'refresh');
                }

            }

            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('admins/newcheck', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);

        }




	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */