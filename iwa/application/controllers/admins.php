<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admins extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        if (!$this->session->userdata('booAdminLogin')) {
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
        $this->load->model('master_model');
        $this->load->model('accounts_model');
        $arrSuperAdminRequests = $this->admins_model->getSuperAdminRequests();
        $arrPageData['customer_package'] = $this->master_model->getCustomerPackage();


        $arrPageData['arrSuperAdminRequests'] = $arrSuperAdminRequests;
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $arrPageData['arrAdmins'] = $this->admins_model->getAllMaster($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['arrAccounts'] = $this->accounts_model->getAllAccountForMaster($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['summary'] = $this->admins_model->masterSummary($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
            $arrPageData['accounts'] = $this->admins_model->getRecentAccountsForMaster($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
            $arrPageData['profilelist'] = $this->admins_model->inProfileListMaster($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
        } else {
            $arrPageData['arrAccounts'] = $this->accounts_model->getAllAccountForFranchise($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['summary'] = $this->admins_model->franchiseSummary($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
            $arrPageData['accounts'] = $this->admins_model->getRecentAccountsForFranchise($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
            $arrPageData['arrAdmins'] = $this->admins_model->getAllFranchises($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['profilelist'] = $this->admins_model->inProfileListFranchise($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
        }
        $arrPageData['intActiveAccounts'] = $this->admins_model->getActiveAccountCount();
        foreach ($arrPageData['arrAccounts']['results'] as $key => $value) {
            $asset = $this->db->where(array('account_id' => $value->customer_id))->get('items');
            if ($asset->num_rows > 0) {
                $total_asset = count($asset->result_array());
                $arrPageData['arrAccounts']['results'][$key]->noOfAsset = $total_asset;
            } else {
                $total_asset = 0;
                $arrPageData['arrAccounts']['results'][$key]->noOfAsset = $total_asset;
            }
        }



        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/index', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function logIn($booLogout = false) {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "SysAdmin Login";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if ($this->session->userdata('booUserLogin')) {
            $this->session->set_userdata('strReferral', '/admins/login/');
            redirect('users/logout/', 'refresh');
        }


        if ($booLogout == true) {
            $arrPageData['arrUserMessages'][] = "You were successfully logged out";
        }

        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');

        // load models
        $this->load->model('admins_model');

        // check to see if the user has submitted
        if ($this->input->post('submit')) {

            //does the record exist?
            $arrmasterAdminData = $this->admins_model->logInMaster();
            //does the record exist?
            $arrFranchiseAdminData = $this->admins_model->logInFranchise();

            if ($arrmasterAdminData['booSuccess']) {
                $this->session->set_userdata('AdminUserName', $arrmasterAdminData['result'][0]->username);
                redirect('admins/pinCheck/master', 'refresh');
            } elseif ($arrFranchiseAdminData['booSuccess']) {
                $this->session->set_userdata('AdminUserName', $arrFranchiseAdminData['result'][0]->username);
                redirect('admins/pinCheck/frenchise', 'refresh');
            } else {
                $this->session->set_userdata('booAdminLogin', FALSE);
                $arrPageData['arrErrorMessages'][] = "Log-in failure";
                $this->session->set_flashdata('arrCourier', "Log-in failure");
                redirect('/admins/login/', 'refresh');
            }
            die;
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/login', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function pinCheck($account_type = '') {
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "SysAdmin Pin Number";

        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $arrPageData['account_type'] = $account_type;
        if ($this->input->post()) {
            $this->load->model('admins_model');

            $this->form_validation->set_rules('pin_number', 'Pin Number', 'trim|required|md5');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run()) {
                $arrInput = array(
                    'pin_number' => $this->input->post('pin_number'),
                    'username' => $this->input->post('username')
                );


                if ($account_type == "master") {

                    $arrAdminData = $this->admins_model->masterPincodeAuthentication($arrInput);
                } else {

                    $arrAdminData = $this->admins_model->franchisePincodeAuthentication($arrInput);
                }


                if ($arrAdminData['booSuccess']) {
                    $this->session->set_userdata('booAdminLogin', TRUE);
                    $this->session->set_userdata('objAdminUser', $arrAdminData['result'][0]);
                    $this->session->set_userdata('ParentAccountName', $arrAdminData['Account_Name']);
                    // We need to set some user messages before redirect
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('You were successfully logged in')));

                    if (!$this->session->userdata('strReferral')) {
                        redirect('/admins/index/', 'refresh');
                    } else {
                        $strReferral = $this->session->userdata('strReferral');
                        $this->session->unset_userdata('strReferral');
                        redirect($strReferral, 'refresh');
                    }
                } else {
                    $this->session->set_userdata('booAdminLogin', FALSE);
                    $this->session->set_flashdata('arrCourier', "Log-in failure");
                    $arrPageData['arrErrorMessages'][] = "Log-in failure";
                    redirect('/admins/login/', 'refresh');
                }
            }
        }
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/pincode_authentication', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function logout() {
        $this->session->unset_userdata(array('booAdminLogin', 'objAdminUser'));
        $this->session->sess_destroy();


        // We need to set some user messages before redirect
        redirect('/admins/login/true/', 'refresh');
    }

    public function viewAdmins() {
        if (!$this->session->userdata('booAdminLogin')) {
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

//        $arrPageData['arrAdmins'] = $this->admins_model->getAll();
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $arrPageData['arrAdmins'] = $this->admins_model->getAllMaster($arrPageData['arrSessionData']['objAdminUser']);
        } else {
            $arrPageData['arrAdmins'] = $this->admins_model->getAllFranchises($arrPageData['arrSessionData']['objAdminUser']);
        }

        // Check the user was found
        if ($arrPageData['arrAdmins']['booSuccess'] != true) {
            // write error
            $arrPageData['arrErrorMessages'][] = "No SysAdmins were found";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/all', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function viewAdmin($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
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


        if ($intId > 0) {
            // load models
            $this->load->model('admins_model');

            // Use model to find user details
            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                $arrPageData['arrAdmins'] = $this->admins_model->getOneMaster();
            } else {
                $arrPageData['arrAdmins'] = $this->admins_model->getOneFranchise();
            }



            // Check the user was found
            if ($arrPageData['arrAdmin']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "SysAdmin not found";
            }
        } else {
            $arrPageData['arrAdmin']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "SysAdmin Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrAdmin']['booSuccess'] == true) {
            $this->load->view('admins/admin', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function deleteAdmin($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/deleteadmin/' . $intId . '/');
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
        ;


        if ($intId > 0) {
            // load models
            $this->load->model('admins_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {

                $arrPageData['arrAdmin'] = $this->admins_model->deleteOneMaster($intId);
            } else {

                $arrPageData['arrAdmin'] = $this->admins_model->deleteOneFranchise($intId);
            }

            if ($arrPageData['arrAdmin'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
            } else {

                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Deleted')));
                redirect('admins/viewadmins/', 'refresh');
            }
        }
    }

    public function reactiveAdmin($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/deleteadmin/' . $intId . '/');
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



        if ($intId > 0) {
            // load models
            $this->load->model('admins_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                $arrPageData['arrAdmin'] = $this->admins_model->reactiveOneMaster($intId);
            } else {
                $arrPageData['arrAdmin'] = $this->admins_model->reactiveOneFranchise($intId);
            }


            if ($arrPageData['arrAdmin'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
            } else {

                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Admin User Active Successfully')));
                redirect("admins/viewarchive/", "refresh");
            }
        }
    }

    public function edit($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
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

        if ($intId > 0) {
            // load models
            $this->load->model('admins_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrAdmin'] = $this->admins_model->getOne($intId);
            // Check the user was found
            if ($arrPageData['arrAdmin']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "SysAdmin not found";
            } else {

                // set the form fields ready for display
                $arrPageData['strFirstName'] = $arrPageData['arrAdmin']['result'][0]->firstname;
                $arrPageData['strLastName'] = $arrPageData['arrAdmin']['result'][0]->lastname;
                $arrPageData['strNickName'] = $arrPageData['arrAdmin']['result'][0]->nickname;
                $arrPageData['strUserName'] = $arrPageData['arrAdmin']['result'][0]->username;
                $arrPageData['intAdminId'] = $intId;

                // Check if updated
                if ($this->input->post()) {
                    $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
                    $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');

                    if ($this->input->post('password') != "") {
                        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
                    }

                    $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                    if ($this->form_validation->run()) {
                        // the form validated, so try to create
                        //does the record create?
                        if ($this->admins_model->setOne($intId)) {
                            // Yes				
                            // We need to set some user messages before redirect
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Record Updated')));
                            redirect('admins/viewadmins/', 'refresh');
                        } else {
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
        $this->load->view('common/header', $arrPageData);

        if ($arrPageData['arrAdmin']['booSuccess'] == true) {
            $this->load->view('admins/edit', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function changeCredentials($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/changecredentials/' . $intId . '/');
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

        if ($intId > 0) {
            // load models
            $this->load->model('admins_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrAdmin'] = $this->admins_model->getOne($intId);
            // Check the user was found
            if ($arrPageData['arrAdmin']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "SysAdmin not found";
            } else {

                // set the form fields ready for display
                $arrPageData['strUserName'] = $arrPageData['arrAdmin']['result'][0]->username;
                $arrPageData['intAdminId'] = $intId;

                // Check if updated
                if ($this->input->post('submit')) {
                    $booPasswordChanged = false;
                    $booUsernameChanged = false;
                    if ($this->input->post('username') != $arrPageData['arrAdmin']['result'][0]->username) {
                        $this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email|is_unique[systemadmins.username]');
                        $booUsernameChanged = true;
                    }
                    if ($this->input->post('password') != '') {
                        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
                        $booPasswordChanged = true;
                    }
                    $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                    if (($booUsernameChanged || $booPasswordChanged) && $this->form_validation->run()) {
                        // the form validated, so try to create
                        //does the record create?
                        if ($this->admins_model->setCredentials($intId, $booUsernameChanged, $booPasswordChanged)) {
                            // Yes				
                            // We need to set some user messages before redirect
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Record Updated')));
                            redirect('admins/viewadmins/', 'refresh');
                        } else {
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
        $this->load->view('common/header', $arrPageData);

        if ($arrPageData['arrAdmin']['booSuccess'] == true) {
            $this->load->view('admins/credentials', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function create() {
        if (!$this->session->userdata('booAdminLogin')) {
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


        // Check if updated
        if ($this->input->post()) {

            $this->form_validation->set_rules('user_password', 'Password', 'trim|required|md5');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run()) {

                if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                    $arrPageData['arrAccounts'] = $this->admins_model->setOneMaster($arrPageData['arrSessionData']['objAdminUser']);
                } else {
                    $arrPageData['arrAccounts'] = $this->admins_model->setOneFranchise($arrPageData['arrSessionData']['objAdminUser']);
                }
                if ($arrPageData['arrAccounts'] == TRUE) {
                    // Yes				
                    // We need to set some user messages before redirect
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Record Created')));

                    redirect('admins/viewadmins/', 'refresh');
                } else {
                    // No. ERROR
                    $arrPageData['arrErrorMessages'][] = "System Admin Not Created";
                }
            }
        }
    }

    public function viewAccounts() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/viewaccounts/');
            redirect('admins/login/', 'refresh');
        }
        $this->load->model('master_model');
        $this->load->model('admins_model');
        // housekeeping
        $arrPageData = array();
        $arrPageData['customer_package'] = $this->master_model->getCustomerPackage();
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
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {

            $arrPageData['arrAccounts'] = $this->accounts_model->getAllAccountForMaster($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['options'] = $this->admins_model->mastercustomerlist($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
            $arrPageData['packages'] = $this->admins_model->masterpackagelist($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
            $arrPageData['profilelist'] = $this->admins_model->inProfileListMaster($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
        } else {
            $arrPageData['arrAccounts'] = $this->accounts_model->getAllAccountForFranchise($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['options'] = $this->admins_model->Franchisecustomerlist($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
            $arrPageData['packages'] = $this->admins_model->Franchisepackagelist($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
            $arrPageData['profilelist'] = $this->admins_model->inProfileListFranchise($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
        }
//        foreach($arrPageData['arrAccounts'] as $val)
//        {
//            var_dump($val);
//            
//             $users = $this->db->where(array('account_id' => $val['id']))->get('users');
//                if ($users->num_rows > 0) {
//                    $total_users = count($users->result_array());
//                } else {
//                    $total_users = 0;
//                        }
//        }

        foreach ($arrPageData['arrAccounts']['results'] as $key => $value) {
            $users = $this->db->where(array('account_id' => $value->customer_id))->get('users');
            if ($users->num_rows > 0) {
                $total_users = count($users->result_array());
                $arrPageData['arrAccounts']['results'][$key]->noOfUser = $total_users;
            } else {
                $total_users = 0;
                $arrPageData['arrAccounts']['results'][$key]->noOfUser = $total_users;
            }
        }

        foreach ($arrPageData['arrAccounts']['results'] as $key => $value) {
            $asset = $this->db->where(array('account_id' => $value->customer_id))->get('items');
            if ($asset->num_rows > 0) {
                $total_asset = count($asset->result_array());
                $arrPageData['arrAccounts']['results'][$key]->noOfAsset = $total_asset;
            } else {
                $total_asset = 0;
                $arrPageData['arrAccounts']['results'][$key]->noOfAsset = $total_asset;
            }
        }

        // Check the user was found
        if ($arrPageData['arrAccounts']['booSuccess'] != true) {
            // write error
            $arrPageData['arrErrorMessages'][] = "No accounts were found";
        }

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/accounts_all', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function viewAccount($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/viewaccount/' . $intId . '/');
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

        if ($intId > 0) {
            // load model
            $this->load->model('accounts_model');

            // Use model to find user details
            $arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);

            // Check the user was found
            if ($arrPageData['arrAccount']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "Account not found";
            }
        } else {
            $arrPageData['arrAccount']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "Account Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrAccount']['booSuccess'] == true) {
            $this->load->view('admins/account', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function deleteAccount($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/deleteaccount/' . $intId . '/');
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


        if ($intId > 0) {
            // load models
            $this->load->model('accounts_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);

            // Check the user was found
            if ($arrPageData['arrAccount']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "Account not found";
            } else {
                // Check if updated
                if ($this->input->post('submit') && ($this->input->post('safety') == "1")) {
                    if ($this->accounts_model->deleteOne($intId)) {
                        // Yes				
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Deleted')));
                        redirect('admins/viewaccounts/', 'refresh');
                    } else {
                        //shouldn't happen, but here for catching the error
                        $arrPageData['arrAccount']['booSuccess'] = false;
                        // write error
                        $arrPageData['arrErrorMessages'][] = "Account could not be deleted";

                        $arrPageData['strPageTitle'] = "System Error";
                        $arrPageData['strPageText'] = "We apologise for this error.";
                    }
                }
            }
        } else {
            $arrPageData['arrAccount']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "User Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrAccount']['booSuccess'] == true) {
            $this->load->view('admins/account_delete', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function reactivateAccount($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/reactivateaccount/' . $intId . '/');
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


        if ($intId > 0) {
            // load models
            $this->load->model('accounts_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);

            // Check the user was found
            if ($arrPageData['arrAccount']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "Account not found";
            } else {
                // Check if updated
                if ($this->input->post() && ($this->input->post('safety') == "1")) {
                    if ($this->accounts_model->reactivateOne($intId)) {
                        // Yes				
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Reactivated')));
                        redirect('admins/viewaccounts/', 'refresh');
                    } else {
                        //shouldn't happen, but here for catching the error
                        $arrPageData['arrAccount']['booSuccess'] = false;
                        // write error
                        $arrPageData['arrErrorMessages'][] = "Account could not be reactivated";

                        $arrPageData['strPageTitle'] = "System Error";
                        $arrPageData['strPageText'] = "We apologise for this error.";
                    }
                }
            }
        } else {
            $arrPageData['arrUser']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "Account Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrAccount']['booSuccess'] == true) {
            $this->load->view('admins/account_reactivate', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function editAccount($intId = -1) {


//        if (!$this->session->userdata('booAdminLogin')) {
//            $this->session->set_userdata('strReferral', '/admins/editaccount/' . $intId . '/');
//            redirect('admins/login/', 'refresh');
//        }
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
        if ($intId > 0) {

            // load models
            $this->load->model('accounts_model');
            $this->load->model('packages_model');
            $arrPageData['arrPackages'] = $this->packages_model->getAll();
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find account details
            //$arrPageData['arrAccount'] = $this->accounts_model->getOne($intId);
            // Check the account was found
            if ($this->input->post()) {
                $this->form_validation->set_rules('edit_contact_password', 'Password', 'trim|md5');
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                if ($this->form_validation->run()) {
                    // the form validated, so try to create
                    //does the record create?
                    if ($this->accounts_model->setOne($intId)) {

                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Updated')));
                        redirect('admins/viewaccounts/', 'refresh');
                    } else {
                        // No. ERROR
                        $arrPageData['arrErrorMessages'][] = "Account Not Updated";
                    }
                }
            }
        }

        // load views
        $this->load->view('common/header', $arrPageData);

        if ($arrPageData['arrAccount']['booSuccess'] == true) {
            $this->load->view('admins/account_edit', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function createAccount() {
        if (!$this->session->userdata('booAdminLogin')) {
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


        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');



        // Check if updated
        if ($this->input->post()) {
//            $this->form_validation->set_rules('account_name', 'Name', 'trim|required');
//            $this->form_validation->set_rules('account_address', 'Address', 'trim|required');
//            $this->form_validation->set_rules('account_city', 'City', 'trim|required');
//            $this->form_validation->set_rules('account_postcode', 'Post Code', 'trim|required');
//            $this->form_validation->set_rules('account_securityquestion', 'Security Question', 'trim|required');
//            $this->form_validation->set_rules('account_securityanswer', 'Security Answer', 'trim|required');
//            $this->form_validation->set_rules('account_contactname', 'Contact Name', 'trim|required');
//            $this->form_validation->set_rules('account_contactemail', 'Contact Email', 'trim|required|xss_clean|valid_email|is_unique[users.username]');
//            $this->form_validation->set_message('is_unique', 'There is already someone using this email address to access the system.');
            $this->form_validation->set_rules('contact_password', 'Password', 'trim|required|md5');
//            $this->form_validation->set_rules('account_contactnumber', 'Contact Number', 'trim|required');
//            $this->form_validation->set_rules('account_packageid', 'Package', 'required|is_natural_no_zero');
//            $this->form_validation->set_rules('account_supportaddress', 'Support Email', 'trim|xss_clean|valid_email');
//            $this->form_validation->set_message('is_natural_no_zero', 'You must select a valid package for this user.');
//
//            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run()) {

                if ($this->accounts_model->setOne()) {
                    // Yes				
                    // We need to set some user messages before redirect
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Created, with one admin user')));
                    if ($this->input->post('accountbydshboard')) {
                        redirect('admins/index/', 'refresh');
                    } else {
                        redirect('admins/viewaccounts/', 'refresh');
                    }
                } else {
                    // No. ERROR
                    $arrPageData['arrErrorMessages'][] = "Account Not Updated";
                }
            } else {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array("Account Does'nt Created")));
                redirect('admins/viewaccounts/', 'refresh');
            }
        }



        // load views
        $this->load->view('common/header', $arrPageData);

        $this->load->view('admins/account_create', $arrPageData);

        $this->load->view('common/footer', $arrPageData);
    }

    public function viewUsers($intAccountId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/viewusers/' . $intAccountId . '/');
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
        $arrPageData['access_level'] = $this->db->get('levels')->result();
        // load models
        $this->load->model('users_model');
        $this->load->model('admins_model');

        if ($intAccountId > 0) {
            $arrPageData['arrUsers'] = $this->users_model->getAll($intAccountId);
            $arrPageData['strAccountName'] = $this->admins_model->getAccountName($intAccountId);
            // Check the user was found


            if ($arrPageData['arrUsers']['booSuccess'] != true) {
                // write error
                $arrPageData['arrErrorMessages'][] = "No users were found";
            }
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        if ($intAccountId > 0) {
            $this->load->view('admins/users_all', $arrPageData);
        } else {
            $arrPageData['arrErrorMessages'][] = "No account selected.";
            // write error
            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function viewUser($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/viewuser/' . $intId . '/');
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

        if ($intId > 0) {
            // load model
            $this->load->model('users_model');

            // Use model to find user details
            $arrPageData['arrUser'] = $this->users_model->getOne($intId);

            // Check the user was found
            if ($arrPageData['arrUser']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "User not found";
            }
        } else {
            $arrPageData['arrUser']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "User Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrUser']['booSuccess'] == true) {
            $this->load->view('admins/user', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function inheritUser($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            redirect('admins/login/', 'refresh');
        }
        $this->load->helper('url');
        // housekeeping
        $arrUserData = array();

        if ($intId > 0) {
            // load model
            $this->load->model('users_model');

            // Use model to find user details
            $arrUserData = $this->users_model->getBasicCredentialsFor($intId);

            // Check the user was found
            if ($arrUserData['booSuccess'] != true) {
                // write error
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('System was unable to inherit that user profile.')));
                redirect('admins/index/', 'refresh');
            } else {
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

    public function deinheritUser() {
        if (!$this->session->userdata('booAdminLogin')) {
            redirect('admins/login/', 'refresh');
        }
        // Check the user was found
        $this->session->set_userdata('booInheritedUser', false);
        $this->session->unset_userdata('theme_design');
        $this->session->unset_userdata('objInheritedUser');
        $this->session->unset_userdata('objSystemUser');
        $this->session->unset_userdata('booInheritedUser');
        $this->session->set_userdata('booCourier', true);
        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The user was deinherited.')));
        redirect('admins/index/', 'refresh');
    }

    public function deleteUser($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/deleteuser/' . $intId . '/');
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


        if ($intId > 0) {
            // load models
            $this->load->model('users_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrUser'] = $this->users_model->getOne($intId);

            // Check the user was found
            if ($arrPageData['arrUser']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "User not found";
            } else {
                // Check if updated
                if ($this->input->post('submit') && ($this->input->post('safety') == "1")) {
                    if ($this->users_model->deleteOne($intId)) {
                        // Yes				
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Deleted')));
                        redirect('admins/viewusers/', 'refresh');
                    } else {
                        //shouldn't happen, but here for catching the error
                        $arrPageData['arrUser']['booSuccess'] = false;
                        // write error
                        $arrPageData['arrErrorMessages'][] = "User could not be deleted";

                        $arrPageData['strPageTitle'] = "System Error";
                        $arrPageData['strPageText'] = "We apologise for this error.";
                    }
                }
            }
        } else {
            $arrPageData['arrUser']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "User Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrUser']['booSuccess'] == true) {
            $this->load->view('admins/user_delete', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function reactivateUser($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/reactivateuser/' . $intId . '/');
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


        if ($intId > 0) {
            // load models
            $this->load->model('users_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrUser'] = $this->users_model->getOneWithoutAccount($intId);



            // Check the user was found
            if ($arrPageData['arrUser']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "User not found";
            } else {
                // Check if updated
                if ($intId) {
                    if ($this->users_model->reactivate($intId)) {
                        // Yes				
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Reactivated')));
                        redirect('admins/viewusers/' . $arrPageData['arrUser']['result'][0]->accountid, 'refresh');
                    } else {
                        //shouldn't happen, but here for catching the error
                        $arrPageData['arrUser']['booSuccess'] = false;
                        // write error
                        $arrPageData['arrErrorMessages'][] = "User could not be reactivated";

                        $arrPageData['strPageTitle'] = "System Error";
                        $arrPageData['strPageText'] = "We apologise for this error.";
                    }
                }
            }
        } else {
            $arrPageData['arrUser']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "User Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrUser']['booSuccess'] == true) {
            $this->load->view('admins/user_reactivate', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function makeSuperAdmin($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/makesuperadmin/' . $intId . '/');
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


        if ($intId > 0) {
            // load models
            $this->load->model('users_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrUser'] = $this->users_model->getBasicCredentialsFor($intId);

            // Check the user was found
            if ($arrPageData['arrUser']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "User not found";
            } else {
                // Check if updated
                if ($this->input->post() && ($this->input->post('safety') == "1")) {
                    if ($this->users_model->makeSuperAdmin($intId, $arrPageData['arrUser']['result'][0]->accountid)) {
                        // Yes				
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SuperAdmin changed')));
                        redirect('admins/', 'refresh');
                    } else {
                        //shouldn't happen, but here for catching the error
                        $arrPageData['arrUser']['booSuccess'] = false;
                        // write error
                        $arrPageData['arrErrorMessages'][] = "Account could not be updated";

                        $arrPageData['strPageTitle'] = "System Error";
                        $arrPageData['strPageText'] = "We apologise for this error.";
                    }
                }
            }
        } else {
            $arrPageData['arrUser']['booSuccess'] = false;
            // write error
            $arrPageData['arrErrorMessages'][] = "User Id not valid";

            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We apologise for this error.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        // errors?
        if ($arrPageData['arrUser']['booSuccess'] == true) {
            $this->load->view('admins/user_makesuperadmin', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function editUser($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/edituser/' . $intId . '/');
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

        if ($intId > 0) {
            // load models
            $this->load->model('users_model');
            $this->load->model('levels_model');

            // Use levels model to find levels available
            $arrPageData['arrLevels'] = $this->levels_model->getAll();

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrUser'] = $this->users_model->getOne($intId);
            // Check the user was found
            if ($arrPageData['arrUser']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "User not found";
            } else {
                if ($this->input->post('submit')) {
                    $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
                    $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
                    $this->form_validation->set_rules('level_id', 'Level', 'required|is_natural_no_zero');
                    $this->form_validation->set_message('is_natural_no_zero', 'You must select a valid option for this user.');

                    $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                    if ($this->form_validation->run()) {
                        // the form validated, so try to create
                        //does the record create?
                        if ($this->users_model->setOne($intId)) {
                            // Yes				
                            // We need to set some user messages before redirect
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Record Updated')));
                            redirect('admins/viewusers/', 'refresh');
                        } else {
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
        $this->load->view('common/header', $arrPageData);

        if ($arrPageData['arrUser']['booSuccess'] == true) {
            $this->load->view('admins/user_edit', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function changeCredentialsUser($intId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/changecredentialsuser/' . $intId . '/');
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
        $intId = $this->input->post('user_id');

        if ($intId > 0) {
            // load models
            $this->load->model('users_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            // Use model to find user details
            $arrPageData['arrUser'] = $this->users_model->getOneWithoutAccount($intId);
            // Check the user was found
            if ($arrPageData['arrUser']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "User not found";
            } else {

                // set the form fields ready for display
                $arrPageData['strUserName'] = $arrPageData['arrUser']['result'][0]->username;
                $arrPageData['strFirstName'] = $arrPageData['arrUser']['result'][0]->firstname;
                $arrPageData['strLastName'] = $arrPageData['arrUser']['result'][0]->lastname;
                $arrPageData['strLevelName'] = $arrPageData['arrUser']['result'][0]->levelname;
                $arrPageData['strAccountName'] = $arrPageData['arrUser']['result'][0]->accountname;
                $arrPageData['intUserId'] = $intId;

                // Check if updated
                if ($this->input->post()) {
                    $booPasswordChanged = false;
                    $booUsernameChanged = false;
                    if ($this->input->post('username') != $arrPageData['arrUser']['result'][0]->username) {

                        $booUsernameChanged = true;
                    }
                    if ($this->input->post('password') != '') {
                        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
                        $booPasswordChanged = true;
                    }
                    $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                    if (($booUsernameChanged || $booPasswordChanged) && $this->form_validation->run()) {
                        // the form validated, so try to create
                        //does the record create?
                        if ($this->users_model->setCredentials($intId, $booUsernameChanged, $booPasswordChanged)) {
                            // Yes				
                            // We need to set some user messages before redirect
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Record Updated')));
                            redirect('admins/viewusers/' . $arrPageData['arrUser']['result'][0]->accountid, 'refresh');
                        } else {
                            // No. ERROR
                            $arrPageData['arrErrorMessages'][] = "User Not Updated";
                        }
                    } else {
                        $arrPageData['arrErrorMessages'][] = "User credentials not changed";
                    }

                    // if we're here, there's an error somewhere, so repopulate the form fields.
                    $arrPageData['strUserName'] = $this->input->post('username');
                }
            }
        }

        // load views
        $this->load->view('common/header', $arrPageData);

        if ($arrPageData['arrUser']['booSuccess'] == true) {
            $this->load->view('admins/user_credentials', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function createUser($intAccountId = -1, $intLevelId = -1) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/createuser/' . $intAccountId . '/' . $intLevelId . '/');
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
        $arrPageData['arrAccounts'] = $this->accounts_model->getAll();
        $arrPageData['arrLevels'] = $this->levels_model->getAll();
        $arrPageData['intAccountId'] = $intAccountId;
        $arrPageData['intLevelId'] = $intLevelId;

        // set the form fields ready for display
        $arrPageData['strFirstName'] = "";
        $arrPageData['strLastName'] = "";
        $arrPageData['strUserName'] = "";
        $arrPageData['strNickName'] = "";

        // Check if updated
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('account_id', 'Account', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('level_id', 'Level', 'required|is_natural_no_zero');
            $this->form_validation->set_message('is_natural_no_zero', 'You must select a valid option for this user.');
            $this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email|is_unique[users.username]');
            $this->form_validation->set_message('is_unique', 'There is already someone using this email address to access the system.');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run()) {
                // the form validated, so try to create
                //does the record create?
                if ($this->users_model->setOne()) {
                    // Yes
                    // We need to set some user messages before redirect
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Record Created')));
                    redirect('admins/viewusers/', 'refresh');
                } else {
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
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/user_create', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function import() {

        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/viewadmins/');
            redirect('admins/login/', 'refresh');
        }
        $this->load->helper('form');
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strTab'] = "Import Data";
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

        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $accounts = $this->accounts_model->getAllAccountForMaster($arrPageData['arrSessionData']['objAdminUser']);
        } else {
            $accounts = $this->accounts_model->getAllAccountForFranchise($arrPageData['arrSessionData']['objAdminUser']);
        }
        $arrPageData['accounts'] = $accounts['results'];

        if ($this->input->post()) {

            $this->load->library('form_validation');
            $this->load->library('parsecsv');
            $config['upload_path'] = './uploads/';
//                $config['allowed_types'] = 'csv';
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            $this->upload->allowed_types = '*';

            /* Config and Load CI File Upload Class */
            if ($error = $this->upload->do_upload()) {

                $this->items_model->import($this->upload->data(), $this->input->post('account_id'));
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('File Imported Succesfully')));
                redirect('admins/', 'refresh');
            } else {
                $error = array('error' => $this->upload->display_errors());
                redirect('admins/import', 'refresh');
                die();
            }
        }
        //$this->output->enable_profiler(TRUE);
        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/import', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function vehicleChecks() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/vehiclechecks/');
            redirect('admins/login/', 'refresh');
        }

        // housekeeping

        $this->load->model('fleet_model');

        // Restore check if requested
        if ($id != NULL) {
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
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/deletedchecks', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function editCheck($check_id) {
        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booAdminLogin')) {
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

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/editcheck', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function deleteCheck($check_id) {
        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booAdminLogin')) {
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
            if ($this->input->post('delete') == 1) {
                $this->fleet_model->editCheckStatus($check_id, 0);
            }
            redirect('/admins/vehicleChecks/', 'refresh');
        }



        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/deletecheck', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function deletedchecks($id = NULL) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/vehiclechecks/');
            redirect('admins/login/', 'refresh');
        }

        // housekeeping

        $this->load->model('fleet_model');

        // Restore check if requested
        if ($id != NULL) {
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
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/deletedchecks', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function newcheck() {
        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booAdminLogin')) {
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

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/newcheck', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function complianceChecks() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrPageParameters']['strTab'] = "Compliance Checks";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model('admins_model');
        $this->load->model('categories_model');
        $this->load->model('master_model');
        $this->load->model('franchise_model');
        $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
//              $arrPageData['allTests'] = $this->tests_model->getAllTests($this->input->post());
//        $arrPageData['allTests'] = $this->tests_model->getAllTasksAdmins();
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $arrPageData['allTests'] = $this->master_model->getAllTasksForMasterAdmins($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
        } else {
            $arrPageData['allTests'] = $this->franchise_model->getAllTasksForFranchiseAdmins($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
        }
        $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
        if ($this->input->post()) {

//            $this->admins_model->addComplianceTest($this->input->post());
            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                $this->master_model->addComplianceTestForMaster($this->input->post());
            } else {
                $this->franchise_model->addComplianceTestForMaster($this->input->post());
            }
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Template was successfully added')));
            redirect('/admins/complianceChecks', 'refresh');
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/complianceadmin', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function compliancesList() {

        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrPageParameters']['strTab'] = "Compliance Checks";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model('categories_model');
        $this->load->model('admins_model');
        $this->load->model('master_model');
        $this->load->model('franchise_model');

//            $arrPageData['categories'] = $this->categories_model->getAll();
//        $arrPageData['allCompliances'] = $this->admins_model->getAllCompliances();
//            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown();
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $arrPageData['allCompliances'] = $this->master_model->getAllMasterCompliances($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
        } else {
            $arrPageData['allCompliances'] = $this->franchise_model->getAllMasterCompliances($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
        }
        $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
        $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();

//            var_dump($arrPageData);
        /* Check filter */



        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/compliancelist', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function editTemplateCompliance() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrPageParameters']['strTab'] = "Compliance Checks";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model('categories_model');
        $this->load->model('admins_model');

        if ($this->input->post()) {
            $this->admins_model->updateCompliance($this->input->post());
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Compliance(s) was/were successfully updated')));
            redirect('/admins/compliancesList', 'refresh');
        }
    }

    public function editMultiTemplateCompliance() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrPageParameters']['strTab'] = "Compliance Checks";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model('categories_model');
        $this->load->model('admins_model');

        if ($this->input->post()) {
            $this->admins_model->updateMultiCompliance($this->input->post());
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Compliance(s) was/were successfully updated')));
            redirect('/admins/compliancesList', 'refresh');
        }
    }

    public function get_edit_customerdata($id) {

        $this->load->model('master_model');
        $customer_id = $id;
        $result = $this->master_model->geteditCustomerdata($customer_id);
        echo json_encode($result);
        die;
    }

    // Action For Disable Customer Account.
    public function disableCustomer($customer_id) {


        $this->load->model('master_model');
        $result = $this->master_model->disableCustomer($customer_id);
        if ($result) {

            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Customer Disable Successfully')));
            redirect("admins/viewAccounts/", "refresh");
        }
    }

    // Action For Disable Customer Account.
    public function enableCustomer($customer_id) {


        $this->load->model('master_model');
        $result = $this->master_model->enableCustomer($customer_id);
        if ($result) {
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Customer Enable Successfully')));
            redirect("admins/viewAccounts/", "refresh");
        }
    }

    //     Generate Random String For Account

    public function generateRandomString() {

        $refrenceCode = random_string('alnum', '4');
        $this->load->model('master_model');
        $res = $this->master_model->checkRefcode($refrenceCode);
        if ($res) {
            return $this->generateRandomString();
        } else {
            echo ($refrenceCode);
        }
    }

    // Check Username For Customer
    public function check_masterusername() {

        $this->load->model('master_model');
        $res = $this->master_model->check_masterusername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    public function editCustomer() {
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if ($this->input->post()) {
            $this->load->model('master_model');
            $this->load->helper('date');
            $this->form_validation->set_rules('edit_contact_password', 'Password', 'trim|md5');
            if ($this->form_validation->run()) {

                $editArrCustomer = array(
                    'name' => $this->input->post('edit_company_name'),
                    'address' => $this->input->post('edit_comapany_address'),
                    'city' => $this->input->post('edit_company_city'),
                    'state' => $this->input->post('edit_company_state'),
                    'postcode' => $this->input->post('edit_company_postcode'),
                    'firstname' => $this->input->post('edit_first_name'),
                    'lastname' => $this->input->post('edit_last_name'),
                    'add_owner' => $this->input->post('edit_add_owner'),
                    'support_email' => $this->input->post('edit_support_email'),
                    'qr_refcode' => $this->input->post('edit_qr_refcode_hidden'),
                    'package_id' => $this->input->post('edit_package_type'),
                    'verified' => $this->input->post('edit_verify_package'),
                    'annual_value' => $this->input->post('edit_annual_value'),
                    'compliance' => $this->input->post('edit_compliance_module'),
                    'fleet' => $this->input->post('edit_fleet_module'),
                    'condition_module' => $this->input->post('edit_condition_module'),
                    'depereciation_module' => $this->input->post('edit_depreciation_module'),
                    'reporting_module' => $this->input->post('edit_reporting_module'),
                    'contact_name' => $this->input->post('edit_contact_name'),
                    'contact_number' => $this->input->post('edit_contact_phone'),
                    'customer_id' => $this->input->post('edit_customer_id'),
                );
                $result = $this->master_model->editCustomerAc($editArrCustomer);
                if ($result) {
                    $this->session->set_flashdata('success', 'Customer Account Edit Successfully');
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Customer Record Updated')));
                    redirect("admins/viewAccounts/", "refresh");
                }
            }
        }
    }

    public function checkMasterAdminUsername() {

        $this->load->model('admins_model');
        $res = $this->admins_model->checkMasterAdminUsername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    // Action For Edit Master Admin User
    public function editAdminUser() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/editAdminUser/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrPageParameters']['strTab'] = "Compliance Checks";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if ($this->input->post()) {

            $this->load->model('admins_model');

            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                $arrPageData['arrAdmin'] = $this->admins_model->setOneMaster($arrPageData['arrSessionData']['objAdminUser']);
            } else {
                $arrPageData['arrAdmin'] = $this->admins_model->setOneFranchise($arrPageData['arrSessionData']['objAdminUser']);
            }
            if ($arrPageData['arrAdmin'] == TRUE) {
                // Yes				
                // We need to set some user messages before redirect
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Record Edit Successfully')));
                redirect('admins/viewadmins/', 'refresh');
            } else {
                // No. ERROR
                $arrPageData['arrErrorMessages'][] = "System Admin Not Created";
            }
        }
    }

    // Action For Change Admin User Password.
    public function changeAdminUserPassword() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/changeAdminUserPassword/');
            redirect('admins/login/', 'refresh');
        }
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrPageParameters']['strTab'] = "Compliance Checks";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if ($this->input->post()) {

            $this->load->model('admins_model');
            $this->form_validation->set_rules('new_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('new_pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $changeAdminUserPassword = array(
                    'new_password' => $this->input->post('new_password'),
                    'pin_number' => $this->input->post('new_pin_number'),
                    'adminuser_id' => $this->input->post('change_adminuser_id'),
                );

                if ($changeAdminUserPassword['new_password'] != '' || $changeAdminUserPassword['pin_number'] != '') {
                    if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                        $arrPageData['arrAdmin'] = $this->admins_model->changeMasterAdminPassword($changeAdminUserPassword);
                    } else {
                        $arrPageData['arrAdmin'] = $this->admins_model->changeFranchiseAdminPassword($changeAdminUserPassword);
                    }


                    if ($arrPageData['arrAdmin'] == TRUE) {
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Password & Pin Number Change Successfully')));
                        redirect('admins/viewadmins/', 'refresh');
                    }
                } else {

                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_flashdata('error', 'Password & Pin Number Could not update Successfully');
                    redirect('admins/viewadmins/', 'refresh');
                }
            }
        }
    }

    // Get All Profile
    public function profiles() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/profiles/');
            redirect('admins/login/', 'refresh');
        }
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admins_model');
        //$account_name = $this->master_model->getAccountName($data['master_account_id']);
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $arrPageData['profilelist'] = $this->admins_model->Masterprofilelist($arrPageData['arrSessionData']['objAdminUser']->master_account_id);
        } else {
            $arrPageData['profilelist'] = $this->admins_model->Franchiseprofilelist($arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
        }


        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/profile', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Add Profile
    public function add_profile() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/add_profile/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();


        $owner = array();
        $this->load->model('admins_model');
        $owner_data = array();
        $owner_name = $this->input->post('owner_name');
        $category_data = array();
        $category_name = $this->input->post('category_name');
        $manu_data = array();
        $manu_name = $this->input->post('manu_name');
        $manufacturer_data = array();
        $manufacturer_name = $this->input->post('manufacturer_name');

        if ($owner_name[0]) {
            $owner_data = explode("\n", $owner_name[0]);
            $owner_data = array_map('trim', $owner_data);
        }

        for ($i = 0; $i < count($owner_data); $i++) {
            if ($owner_data[$i]) {
                $check_owner = $this->db->where('owner_name', $owner_data[$i])->get('owner');
                if ($check_owner->num_rows() < 1) {
                    $owner[] = $owner_data[$i];
                }
            } else {
                $owner[] = "";
            }
        }

        if ($category_name[0]) {
            $category_data = explode("\n", $category_name[0]);
            $category_data = array_map('trim', $category_data);
        }

        for ($j = 0; $j < count($category_data); $j++) {
            if ($category_data[$j]) {
                $category[] = $category_data[$j];
            } else {
                $category[] = "";
            }
        }

        if ($manu_name[0]) {
            $manu_data = explode("\n", $manu_name[0]);
            $manu_data = array_map('trim', $manu_data);
        }

        for ($k = 0; $k < count($manu_data); $k++) {
            if ($manu_data[$k]) {
                $manu[] = $manu_data[$k];
            } else {
                $manu[] = "";
            }
        }

        if ($manufacturer_name[0]) {
            $manufacturer_data = explode("\n", $manufacturer_name[0]);
            $manufacturer_data = array_map('trim', $manufacturer_data);
        }

        for ($m = 0; $m < count($manufacturer_data); $m++) {
            if ($manufacturer_data[$m]) {
                $manufacturer[] = $manufacturer_data[$m];
            } else {
                $manufacturer[] = "";
            }
        }
        $data = array();
        for ($n = 1; $n <= ($this->input->post('fieldname')); $n++) {
            if ($this->input->post('field_name' . $n)) {
                $field_name[] = $this->input->post('field_name' . $n);
            }
            if ($this->input->post('field_type' . $n)) {
                $field_type[] = $this->input->post('field_type' . $n);
            }

            if ($this->input->post('field_values' . $n)) {

                $data = explode("\n", trim($this->input->post('field_values' . $n)));
                $data = array_map('trim', $data);
                $field_val = implode(',', $data);
                $field_values[] = $field_val;
            } else {
                $field_values[] = "";
            }
        }

        $response = $this->admins_model->addProfile($owner, $category, $manu, $manufacturer, $field_name, $field_type, $field_values, $arrPageData['arrSessionData']['objAdminUser']);
        if ($response) {
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Profile Added Successfully')));
            redirect('admins/profiles/', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Profile.Choose Atleast One From Owners,Categories,Item/Manu OR Manufacturer.');
            redirect("admins/profiles", "refresh");
        }
    }

    // Edit Profile
    public function editProfile() {

        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/editProfile/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if ($this->input->post()) {

//            $this->load->model('admins_model');
//
//            $owner = $this->input->post('owner');
//            
//            foreach ($owner as $key => $value) {
//                if ($value == '') {
//                    unset($owner[$key]);
//                }
//            }
//
//            $category = $this->input->post('category');
//
//            foreach ($category as $key => $value) {
//                if ($value == '') {
//                    unset($category[$key]);
//                }
//            }
//
//            $item = $this->input->post('item');
//
//            foreach ($item as $key => $value) {
//                if ($value == '') {
//                    unset($item[$key]);
//                }
//            }
//            $manufacturer = $this->input->post('manufacturer');
//            foreach ($manufacturer as $key => $value) {
//                if ($value == '') {
//                    unset($manufacturer[$key]);
//                }
//            }

            $owner = array();
            $this->load->model('admins_model');
            $owner_data = array();
            $owner_name = $this->input->post('owner');
            $category_data = array();
            $category_name = $this->input->post('category');
            $manu_data = array();
            $manu_name = $this->input->post('item');
            $manufacturer_data = array();
            $manufacturer_name = $this->input->post('manufacturer');

            if ($owner_name[0]) {
                $owner_data = explode("\n", $owner_name[0]);
                $owner_data = array_map('trim', $owner_data);
            }

            for ($i = 0; $i < count($owner_data); $i++) {
                if ($owner_data[$i]) {
                    $owner[] = $owner_data[$i];
                } else {
                    $owner[] = "";
                }
            }

            if ($category_name[0]) {
                $category_data = explode("\n", $category_name[0]);
                $category_data = array_map('trim', $category_data);
            }

            for ($j = 0; $j < count($category_data); $j++) {
                if ($category_data[$j]) {
                    $category[] = $category_data[$j];
                } else {
                    $category[] = "";
                }
            }

            if ($manu_name[0]) {
                $manu_data = explode("\n", $manu_name[0]);
                $manu_data = array_map('trim', $manu_data);
            }

            for ($k = 0; $k < count($manu_data); $k++) {
                if ($manu_data[$k]) {
                    $manu[] = $manu_data[$k];
                } else {
                    $manu[] = "";
                }
            }

            if ($manufacturer_name[0]) {
                $manufacturer_data = explode("\n", $manufacturer_name[0]);
                $manufacturer_data = array_map('trim', $manufacturer_data);
            }

            for ($m = 0; $m < count($manufacturer_data); $m++) {
                if ($manufacturer_data[$m]) {
                    $manufacturer[] = $manufacturer_data[$m];
                } else {
                    $manufacturer[] = "";
                }
            }

            $field_name = $this->input->post('names');
            $field_type = $this->input->post('types');
            $data = array();
            for ($i = 0; $i < count($field_name); $i++) {
                $custom['name'][$i] = $field_name[$i];
                $custom['type'][$i] = $field_type[$i];
                if ($this->input->post('field_values' . $i)) {

                    $data = explode("\n", trim($this->input->post('field_values' . $i)));
                    $data = array_map('trim', $data);

                    $field_val = implode(',', $data);
                    $custom['values'][$i] = $field_val;
                } else {
                    $custom['values'][$i] = "";
                }
            }

            foreach ($field_name as $key => $value) {
                if ($value == '') {
                    unset($field_name[$key]);
                }
            }
            foreach ($field_type as $key => $value) {
                if ($value == '') {
                    unset($field_type[$key]);
                }
            }

            $owner = array_values($owner);
            $category = array_values($category);
            $item = array_values($manu);
            $manufacturer = array_values($manufacturer);

            $editProfile = array(
                'profile_name' => $this->input->post('edit_profile_name'),
                'owner' => json_encode($owner),
                'category' => json_encode($category),
                'manu' => json_encode($item),
                'manufacturer' => json_encode($manufacturer),
                'custom_field' => json_encode($custom)
            );
//            var_dump($editProfile);die;
            $result = $this->admins_model->editProfile($editProfile, $this->input->post('adminuser_id'));
            if ($result) {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('SysAdmin Profile Edit Successfully')));
                redirect('admins/profiles/', 'refresh');
            }
        }
    }

    public function disableUser($userid) {

        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/editProfile/');
            redirect('admins/login/', 'refresh');
        }
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->model('admin_section_model');
        $this->load->model('users_model');
        $arrPageData['arrUser'] = $this->users_model->getOneWithoutAccount($userid);
        $result = $this->admin_section_model->disableuser($userid);
        if ($result) {

            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Disable Successfully')));

            redirect('admins/viewusers/' . $arrPageData['arrUser']['result'][0]->accountid, 'refresh');
        }
    }

    // Edit User
    // Edit User
    public function editInhertUser() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/editInhertUser/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if ($this->input->post()) {

            $this->load->model('admin_section_model');
            $this->load->model('users_model');
            $editUser = array(
                'firstname' => $this->input->post('edit_first_name'),
                'lastname' => $this->input->post('edit_last_name'),
                'level' => $this->input->post('edit_access_level'),
                'adminuser_id' => $this->input->post('adminuser_id'),
            );

            $result = $this->admin_section_model->editUser($editUser);
            $arrPageData['arrUser'] = $this->users_model->getOneWithoutAccount($editUser['adminuser_id']);
            if ($result) {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Edit Successfully')));
                redirect('admins/viewusers/' . $arrPageData['arrUser']['result'][0]->accountid, 'refresh');
            }
        }
    }

    public function exportCustomerPdf($param) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/exportCustomerPdf/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->model('master_model');
        $this->load->model('franchise_model');
        if ($param) {

            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                $this->master_model->expotMasterCusPdf($param, $arrPageData['arrSessionData']['objAdminUser']->master_account_id);
            } else {
                $this->franchise_model->exportFranchiseCusPdf($param, $arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
            }
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function exportAdminPdf($param) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/exportAdminPdf/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->model('master_model');
        $this->load->model('franchise_model');
        if ($param) {

            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                $this->master_model->exportAdminUser($param, $arrPageData['arrSessionData']['objAdminUser']->master_account_id);
            } else {
                $this->franchise_model->exportFranchiseAdminUser($param, $arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
            }
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function exportProfilePdf($param) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/exportAdminPdf/');
            redirect('admins/login/', 'refresh');
        }

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->model('master_model');
        $this->load->model('franchise_model');
        if ($param) {

            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
                $this->master_model->exportProfilePdf($param, $arrPageData['arrSessionData']['objAdminUser']->master_account_id);
            } else {
                $this->franchise_model->exportFranchiseProfilePdf($param, $arrPageData['arrSessionData']['objAdminUser']->franchise_account_id);
            }
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    // Archive Admin
    public function archiveAdmin($admin_id) {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/archiveAdmin/');
            redirect('admins/login/', 'refresh');
        }

        if ($admin_id) {

            $arrPageData['arrSessionData'] = $this->session->userdata;
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();
            $this->load->model('admins_model');
            if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {

                $result = $this->admins_model->archiveMasterAdmin($admin_id);
            } else {
                $result = $this->admins_model->archiveFranchiseAdmin($admin_id);
            }



            if ($result) {
                $this->session->set_userdata('booCourier', true);

                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Admin User Archive Successfully')));
                redirect('admins/viewadmins/', 'refresh');
            } else {
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Admin User Could Not Archive Successfully')));
                redirect("admins/viewAdmins/", "refresh");
            }
        } else {
            
        }
    }

    // Check Profile
    public function checkProfile() {

        $this->load->model('admins_model');
        $res = $this->admins_model->checkProfile(trim($this->input->post('profile_name')));
        echo $res;
        die;
    }

    // Archive Customer
    public function customerArchive($customer_id) {
        if ($customer_id) {
            $this->load->model('admins_model');
            $result = $this->admins_model->customerArchive($customer_id);
            if ($result) {

                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Customer Archive Successfully')));
                redirect("admins/viewAccounts/", "refresh");
            }
        }
    }

    // Check Edit Username For Customer
    public function edit_check_masterusername() {

        $this->load->model('admins_model');
        $res = $this->admins_model->edit_check_masterusername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    // Edit Multipal Account

    public function editMultipleAccount() {
        $this->load->model('admins_model');

        if ($this->input->post()) {
            $result = $this->admins_model->editMultipleAccount($this->input->post());

            if ($result) {

                $this->session->set_flashdata('success', 'Customer Account Edit Successfully');
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Customer Record Updated')));
                redirect("admins/viewAccounts/", "refresh");
            } else {
                
            }
        }
    }

    public function viewarchive() {
        if (!$this->session->userdata('booAdminLogin')) {
            $this->session->set_userdata('strReferral', '/admins/viewArchiveAccounts/');
            redirect('admins/login/', 'refresh');
        }
        $this->load->model('master_model');
        $this->load->model('admins_model');
        // housekeeping
        $arrPageData = array();
        $arrPageData['customer_package'] = $this->master_model->getCustomerPackage();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strTab'] = "Archive";
        $arrPageData['arrPageParameters']['strSubSection'] = "Accounts";
        $arrPageData['arrPageParameters']['strPage'] = "View Archive Accounts";
        $arrPageData['arrSessionData'] = $this->session->userdata;


        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('accounts_model');
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {

            $arrPageData['arrAccounts'] = $this->accounts_model->getAllAccountForArchiveMaster($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['arrAdmins'] = $this->admins_model->getAllArchiveMaster($arrPageData['arrSessionData']['objAdminUser']);
        } else {
            $arrPageData['arrAccounts'] = $this->accounts_model->getAllAccountForArchiveFranchise($arrPageData['arrSessionData']['objAdminUser']);
            $arrPageData['arrAdmins'] = $this->admins_model->getAllArchiveFranchises($arrPageData['arrSessionData']['objAdminUser']);
        }



        foreach ($arrPageData['arrAccounts']['results'] as $key => $value) {
            $users = $this->db->where(array('account_id' => $value->customer_id))->get('users');
            if ($users->num_rows > 0) {
                $total_users = count($users->result_array());
                $arrPageData['arrAccounts']['results'][$key]->noOfUser = $total_users;
            } else {
                $total_users = 0;
                $arrPageData['arrAccounts']['results'][$key]->noOfUser = $total_users;
            }
        }

        foreach ($arrPageData['arrAccounts']['results'] as $key => $value) {
            $asset = $this->db->where(array('account_id' => $value->customer_id))->get('items');
            if ($asset->num_rows > 0) {
                $total_asset = count($asset->result_array());
                $arrPageData['arrAccounts']['results'][$key]->noOfAsset = $total_asset;
            } else {
                $total_asset = 0;
                $arrPageData['arrAccounts']['results'][$key]->noOfAsset = $total_asset;
            }
        }

        // Check the user was found
        if ($arrPageData['arrAccounts']['booSuccess'] != true) {
            // write error
            $arrPageData['arrErrorMessages'][] = "No accounts were found";
        }

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admins/archivecustomerlist', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Disable Customer Account.
    public function restoreCustomer($customer_id) {


        $this->load->model('admins_model');
        $result = $this->admins_model->restoreCustomer($customer_id);
        if ($result) {

            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Customer Restore Successfully')));
            redirect("admins/viewarchive/", "refresh");
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */