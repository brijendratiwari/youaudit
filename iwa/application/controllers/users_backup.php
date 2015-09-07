<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->viewAll();
    }

    public function logIn($booLogout = false) {
        $mydata = array();
        $this->load->model('theme_model');
        $mydata = $this->theme_model->fetch_Theme();
        $this->session->set_userdata('theme_design', $mydata[0]);
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "System Login";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if ($this->session->userdata('booAdminLogin') && !$this->session->userdata('booInheritedUser')) {
            redirect('admins/index/', 'refresh');
        }

        if ($booLogout == true) {
            $arrPageData['arrUserMessages'][] = "You were successfully logged out";
        }

        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        // load models
        $this->load->model('users_model');

        // check to see if the user has submitted
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run()) {
                // the form validated, so try to find user
                //Set up the array
                $arrInput = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password'),
                    'active' => 1
                );
                //does the record exist?
                $arrLoginData = $this->users_model->logIn($arrInput);

                if ($arrLoginData['booSuccess']) {
                    $this->load->model('accounts_model');
                    $arrUserData = $this->users_model->getBasicCredentialsFor($arrLoginData['result'][0]->id);
                    $arrAccountResult = $this->accounts_model->getOne($arrLoginData['result'][0]->account_id);

                    if (($arrAccountResult['result'][0]->accountactive == 1) && ($arrAccountResult['result'][0]->accountverified == 1)) {

                        $this->session->set_userdata('booUserLogin', TRUE);
                        $this->session->set_userdata('objSystemUser', $arrUserData['result'][0]);
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('You were successfully logged in')));

                        if (!$this->session->userdata('strReferral')) {
                            redirect('/welcome/index/', 'refresh');
                        } else {
                            $strReferral = $this->session->userdata('strReferral');
                            $this->session->unset_userdata('strReferral');
                            redirect($strReferral, 'refresh');
                        }
                    } else {
                        $this->session->set_userdata('booUserLogin', FALSE);
                        $arrPageData['arrErrorMessages'][] = "Your organisation's account is currently not activated";
                    }
                } else {
                    $this->session->set_userdata('booUserLogin', FALSE);
                    $arrPageData['arrErrorMessages'][] = "Log-in failure";
                }
            }
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('users/forms/login', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function logout() {
        $this->session->unset_userdata(array('booUserLogin', 'objSystemUser'));
        $this->session->sess_destroy();
        // We need to set some user messages before redirect
//        redirect('/users/login/true/', 'refresh');
        redirect('');
    }

    public function viewAll() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/');
            redirect('users/login/', 'refresh');
        }

        // housekeepings
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();



        // load models
        $this->load->model('users_model');
        $booSuccess = false;
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Users.viewAll");

        if ($booPermission) {

            $mixResult = $this->users_model->getAllForAccount($this->session->userdata('objSystemUser')->accountid);

            // Check the user was found
            if ($mixResult) {
                $booSuccess = true;
                $arrPageData['arrUsers'] = $mixResult;
            } else {
                // write error
                $arrPageData['arrErrorMessages'][] = "No users were found";
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have the correct permissions to do this";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "You do not have the correct permissions to do this";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booSuccess) {
            $this->load->view('users/all', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function edit($intUserId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/edit/' . $intUserId . '/');
            redirect('users/login/', 'refresh');
        }

        if ($this->session->userdata('objSystemUser')->userid == $intUserId) {
            redirect('users/editMe/', 'refresh');
        }
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit A User";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Users.edit");
        $arrPageData['booSuppressPasswordChange'] = false;
        $arrPageData['booSuppressCurrentPassword'] = true;

        if ($booPermission) {

            $arrUserData = $this->users_model->getOne($intUserId, $this->session->userdata('objSystemUser')->accountid);




            $arrPageData['intUserId'] = $intUserId;
            $arrPageData['strFirstName'] = $arrUserData['result'][0]->firstname;
            $arrPageData['strLastName'] = $arrUserData['result'][0]->lastname;
            $arrPageData['strNickName'] = $arrUserData['result'][0]->nickname;
            $arrPageData['intLevelId'] = $arrUserData['result'][0]->levelid;
            $arrPageData['intPhotoId'] = $arrUserData['result'][0]->photoid;
            $arrPageData['strPhotoTitle'] = $arrUserData['result'][0]->phototitle;

            if ($intUserId > 0) {
                // helpers
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                // load models
                $this->load->model('levels_model');
                $this->load->model('photos_model');

                // Use levels model to find levels available
                $arrPageData['arrLevels'] = $this->levels_model->getAll();

                // Check if updated
                if ($this->input->post()) {
                    $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
                    $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
                    if ($this->input->post('newpassword1') != "") {
                        $this->form_validation->set_rules('newpassword1', 'New Password', 'trim|required|matches[newpassword2]|md5');
                        $this->form_validation->set_rules('newpassword2', 'New Password (again)', 'trim|required|md5');
                    }
                    $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                    if ($this->form_validation->run()) {
                        $arrUserData = array('firstname' => $this->input->post('firstname'),
                            'lastname' => $this->input->post('lastname')
                        );
                        if ($this->input->post('nickname') == '') {
                            $arrUserData['nickname'] = $this->input->post('firstname');
                        } else {
                            $arrUserData['nickname'] = $this->input->post('nickname');
                        }
                        if ($this->input->post('newpassword1') != '') {
                            $arrUserData['password'] = $this->input->post('newpassword1');
                        }

                        if ($this->input->post('level_id') != $arrPageData['intLevelId']) {
                            $arrUserData['level_id'] = $this->input->post('level_id');
                        }
                        if ($this->users_model->setThisOne($intUserId, $arrUserData)) {

                            // Log it first
                            $this->logThis("Edited user", "users", $intUserId);


                            $intPhotoError = 0;
                            //is a photo uploaded?

                            /* $resTemp = $_FILES;
                              var_dump($resTemp);
                              die();
                             */
                            if (array_key_exists('photo_file', $_FILES)) {
                                $arrConfig['upload_path'] = './uploads/';
                                $arrConfig['allowed_types'] = 'gif|jpg|png';
                                $arrConfig['max_size'] = '1024';


                                // load helper
                                $this->load->library('upload', $arrConfig);

                                // photo upload done
                                if ($this->upload->do_upload('photo_file')) {
                                    $strPhotoTitle = "My Profile Picture";
                                    if ($this->input->post('photo_title') != "") {
                                        $strPhotoTitle = $this->input->post('photo_title');
                                    }
                                    $this->users_model->setPhoto($intUserId, $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "user/profile"));
                                } else {
                                    $intPhotoError = 1;
                                }
                            }

                            // We need to set some user messages before redirect
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The user details were successfully updated')));
                            redirect('/users/viewall/', 'refresh');
                        } else {
                            $arrPageData['arrErrorMessages'][] = "Unable to update profile.";
                        }// if set
                    }// if validation
                    $arrPageData['strFirstName'] = $this->input->post('firstname');
                    $arrPageData['strLastName'] = $this->input->post('lastname');
                    $arrPageData['strNickName'] = $this->input->post('nickname');
                    $arrPageData['intLevelId'] = $this->input->post('level_id');
                }//if submit
            } //if +ve User Id
        }// if permissions 
        else {
            //no permissions
            $arrPageData['arrErrorMessages'][] = "You do not have the correct permissions to do this";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "You do not have the correct permissions to do this";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission) {
            $this->load->view('users/edit', $arrPageData);
            $this->load->view('users/forms/edit', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function view($intUserId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/view/' . $intUserId . '/');
            redirect('users/login/', 'refresh');
        }
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View A User";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booErrors = false;
        $arrUserData = $this->users_model->getOne($intUserId, $this->session->userdata('objSystemUser')->accountid);
        if ($arrUserData) {
            $arrPageData['intUserId'] = $intUserId;

            $arrPageData['objUser'] = $arrUserData['result'][0];
        } else {
            $arrPageData['arrErrorMessages'][] = "Unable to find the User";
            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "Unable to find the User requested";
            $booErrors = true;
        }



        // load views
        $this->load->view('common/header', $arrPageData);
        if (!$booErrors) {
            $this->load->view('users/view', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function editMe() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/editme/');
            redirect('users/login/', 'refresh');
        }
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit My Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');

        $intId = $this->session->userdata('objSystemUser')->userid;

        $arrPageData['strFirstName'] = $this->session->userdata('objSystemUser')->firstname;
        $arrPageData['strLastName'] = $this->session->userdata('objSystemUser')->lastname;
        $arrPageData['strNickName'] = $this->session->userdata('objSystemUser')->nickname;
        $arrPageData['intPhotoId'] = $this->session->userdata('objSystemUser')->photoid;
        $arrPageData['strPhotoTitle'] = $this->session->userdata('objSystemUser')->phototitle;

        $arrPageData['booSuppressPasswordChange'] = false;
        $arrPageData['booSuppressCurrentPassword'] = false;
        // load models

        $this->load->model('photos_model');

        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        // Check if updated
        if ($this->input->post()) {
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            if ($this->input->post('password') != "") {
                $this->form_validation->set_rules('password', 'Current Password', 'trim|required|md5|callback_checkPassword');
                $this->form_validation->set_rules('newpassword1', 'New Password', 'trim|required|matches[newpassword2]|md5');
                $this->form_validation->set_rules('newpassword2', 'New Password (again)', 'trim|required|md5');
            }
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run()) {
                $arrUserData = array('firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname')
                );
                if ($this->input->post('nickname') == '') {
                    $arrUserData['nickname'] = $this->input->post('firstname');
                } else {
                    $arrUserData['nickname'] = $this->input->post('nickname');
                }
                if ($this->input->post('password') != "") {
                    $arrUserData['password'] = $this->input->post('newpassword1');
                }

                if ($this->users_model->setThisOne($intId, $arrUserData)) {
                    $intPhotoError = 0;
                    //is a photo uploaded?

                    /* $resTemp = $_FILES;
                      var_dump($resTemp);
                      die();
                     */
                    if (array_key_exists('photo_file', $_FILES) && ($_FILES['photo_file']['size'] > 0)) {
                        $arrConfig['upload_path'] = './uploads/';
                        $arrConfig['allowed_types'] = 'gif|jpg|png';
                        $arrConfig['max_size'] = '1024';


                        // load helper
                        $this->load->library('upload', $arrConfig);

                        // photo upload done
                        if ($this->upload->do_upload('photo_file')) {
                            $strPhotoTitle = "My Profile Picture";
                            if ($this->input->post('photo_title') != "") {
                                $strPhotoTitle = $this->input->post('photo_title');
                            }
                            $this->users_model->setPhoto($intId, $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "user/profile"));
                        } else {
                            $intPhotoError = 1;
                        }
                    }


                    $arrUpdatedData = $this->users_model->getBasicCredentialsFor($intId);
                    $this->session->set_userdata('objSystemUser', $arrUpdatedData['result'][0]);
                    // We need to set some user messages before redirect

                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Your details were successfully updated')));

                    if ($intPhotoError > 0) {
                        $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Your image failed to upload successfully')));
                    }
                    redirect('/welcome/index/', 'refresh');
                } else {
                    $arrPageData['arrErrorMessages'][] = "Unable to update your profile.";
                }
            }
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('users/editme', $arrPageData);
        $this->load->view('users/forms/edit', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function changeMyPassword() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/changemypassword/');
            redirect('users/login/', 'refresh');
        }
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Change My Password";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');

        $intId = $this->session->userdata('objSystemUser')->userid;

        // load models
        $this->load->model('users_model');

        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        // Check if updated
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('password', 'Current Password', 'trim|required|md5|callback_checkPassword');
            $this->form_validation->set_rules('newpassword1', 'New Password', 'trim|required|matches[newpassword2]|md5');
            $this->form_validation->set_rules('newpassword2', 'New Password (again)', 'trim|required|md5');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run()) {
                $arrUserData = array('password' => $this->input->post('newpassword1'));
                if ($this->users_model->setThisOne($intId, $arrUserData)) {
                    $arrUpdatedData = $this->users_model->getBasicCredentialsFor($intId);
                    $this->session->set_userdata('objSystemUser', $arrUpdatedData['result'][0]);
                    // We need to set some user messages before redirect
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Your password was successfully updated')));
                    redirect('/welcome/index/', 'refresh');
                } else {
                    $arrPageData['arrErrorMessages'][] = "Unable to update your password.";
                }
            }
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('users/changemypassword', $arrPageData);
        $this->load->view('users/forms/password', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function checkPassword($strPassword) {
        if ($strPassword == $this->session->userdata('objSystemUser')->password) {
            return true;
        } else {
            $this->form_validation->set_message('checkPassword', 'You did not match the current password.');
            return false;
        }
    }

    public function add() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/addone/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Add a User";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Users.add");


        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        // load models
        $this->load->model('levels_model');
        $this->load->model('photos_model');

        // Use levels model to find levels available
        $arrPageData['arrLevels'] = $this->levels_model->getAll();

        $arrPageData['booSuppressPasswordChange'] = true;
        $arrPageData['booSuppressCurrentPassword'] = true;
        // Check if updated
        if ($booPermission) {
            $arrPageData['strFirstName'] = '';
            $arrPageData['strLastName'] = '';
            $arrPageData['strNickName'] = '';
            $arrPageData['intLevelId'] = 0;
            $arrPageData['strUserName'] = '';
            $arrPageData['intPhotoId'] = 1;



            if ($this->input->post()) {
                $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');

                $this->form_validation->set_rules('level_id', 'Level', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required|xss_clean|valid_email|is_unique[users.username]');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

                if ($this->form_validation->run()) {

                    //does the record create?
                    $arrInput = array(
                        'firstname' => $this->input->post('firstname'),
                        'lastname' => $this->input->post('lastname'),
                        'level_id' => $this->input->post('level_id'),
                        'photo_id' => 1,
                        'username' => $this->input->post('username'),
                        'password' => $this->input->post('password')
                    );

                    if ($this->input->post('nickname') != '') {
                        $arrInput['nickname'] = $this->input->post('nickname');
                    } else {
                        $arrInput['nickname'] = $this->input->post('firstname');
                    }

                    $mixNewUserId = $this->users_model->createOnAccount($this->session->userdata('objSystemUser')->accountid, $arrInput);

                    if ($mixNewUserId) {

                        // Log it first

                        $this->logThis("Added user", "users", $mixNewUserId);

                        $intPhotoError = 0;
                        //is a photo uploaded?

                        /* $resTemp = $_FILES;
                          var_dump($resTemp);
                          die();
                         */
                        if (array_key_exists('photo_file', $_FILES) && ($_FILES['photo_file']['size'] > 0)) {
                            $arrConfig['upload_path'] = './uploads/';
                            $arrConfig['allowed_types'] = 'gif|jpg|png';
                            $arrConfig['max_size'] = '1024';


                            // load helper
                            $this->load->library('upload', $arrConfig);

                            // photo upload done
                            if ($this->upload->do_upload('photo_file')) {
                                $strPhotoTitle = "My Profile Picture";
                                if ($this->input->post('photo_title') != "") {
                                    $strPhotoTitle = $this->input->post('photo_title');
                                }
                                $this->users_model->setPhoto($mixNewUserId, $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "user/profile"));
                            } else {
                                $intPhotoError = 1;
                            }
                        }


                        // Yes				
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The user was successfully added')));
                        if ($intPhotoError > 0) {
                            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('The image failed to upload successfully')));
                        }
                        redirect('/users/viewall/', 'refresh');
                    } else {
                        // No. ERROR
                        $arrPageData['arrErrorMessages'][] = "User Record Not Created";
                    }
                }
                $arrPageData['strFirstName'] = $this->input->post('firstname');
                $arrPageData['strLastName'] = $this->input->post('lastname');
                $arrPageData['strNickName'] = $this->input->post('nickname');
                $arrPageData['intLevelId'] = $this->input->post('level_id');
                $arrPageData['strUsername'] = $this->input->post('username');
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have the correct permissions to do this";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "You do not have the correct permissions to do this";
        }


        // load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission) {
            $this->load->view('users/add', $arrPageData);
            $this->load->view('users/forms/edit', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function delete($intUserId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/delete/' . $intUserId . '/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Delete a User";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Users.delete");
        $booNoErrors = true;
        $arrPageData['intUserId'] = $intUserId;

        if ($booPermission) {

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            $mixUsersData = $this->users_model->getOne($intUserId, $this->session->userdata('objSystemUser')->accountid);

            // did we find any?
            if ($mixUsersData && (count($mixUsersData['result']) == 1)) {
                // Check if updated
                if ($this->input->post() && ($this->input->post('safety') == "1")) {
                    if ($this->users_model->delete($intUserId)) {
                        // Yes				
                        // Log it first
                        $this->logThis("Deactivated user", "users", $intUserId);
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User Deleted')));
                        redirect('users/viewall/', 'refresh');
                    } else {
                        $arrPageData['arrErrorMessages'][] = "User could not be deleted";
                        $arrPageData['strPageTitle'] = "Oooops!";
                        $arrPageData['strPageText'] = "You cannot delete this user, perhaps they have active items linked?";
                        $booNoErrors = false;
                    }
                }
                $arrPageData['strFirstName'] = $mixUsersData['result'][0]->firstname;
                $arrPageData['strLastName'] = $mixUsersData['result'][0]->lastname;
            } else {
                $arrPageData['arrErrorMessages'][] = "User could not be found.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve user information.";
                $booNoErrors = false;
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booNoErrors) {
            //load the correct view
            $this->load->view('users/delete', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function reactivate($intUserId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/reactivate/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Reactivate User";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Users.reactivate");
        $booNoErrors = true;



        $arrPageData['intUserId'] = $intUserId;

        if ($booPermission) {

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            $mixUsersData = $this->users_model->getOne($intUserId
                    , $this->session->userdata('objSystemUser')->accountid);

            // did we find any?
            if ($mixUsersData && (count($mixUsersData['result']) == 1)) {
                // Check if updated
                if ($this->input->post() && ($this->input->post('safety') == "1")) {
                    if ($this->users_model->reactivate($intUserId)) {
                        // Yes				
                        // Log it first
                        $this->logThis("Reactivated user", "users", $intUserId);
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('User was reactivated')));
                        redirect('users/viewall/', 'refresh');
                    } else {
                        $arrPageData['arrErrorMessages'][] = "Unable to complete the request";
                        $arrPageData['strPageTitle'] = "System Error";
                        $arrPageData['strPageText'] = "There was an error attempting to reactivate the user";
                        $booNoErrors = false;
                    }
                }
                $arrPageData['strFirstName'] = $mixUsersData['result'][0]->firstname;
                $arrPageData['strLastName'] = $mixUsersData['result'][0]->lastname;
            } else {
                $arrPageData['arrErrorMessages'][] = "User could not be found.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve user information.";
                $booNoErrors = false;
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booNoErrors) {
            //load the correct view
            $this->load->view('users/reactivate', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function becomeSuperAdmin() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/users/becomesuperadmin/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Become the Super Admin";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Users.becomeSuperAdmin");
        $booNoErrors = true;

        $intUserId = $this->session->userdata('objSystemUser')->userid;

        $arrPageData['intUserId'] = $intUserId;

        if ($booPermission) {

            $arrSuperAdminRequest = $this->users_model->getSuperAdminRequestFor($this->session->userdata('objSystemUser')->accountid);
            if (count($arrSuperAdminRequest['results']) == 0) {


                // helpers
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                $mixUsersData = $this->users_model->getOne($intUserId
                        , $this->session->userdata('objSystemUser')->accountid);

                // did we find any?
                if ($mixUsersData && (count($mixUsersData['result']) == 1)) {
                    // Check if updated
                    if ($this->input->post() && ($this->input->post('safety') == "1")) {
                        if ($this->users_model->requestSuperAdmin($intUserId)) {
                            // Yes				
                            // Log it first
                            $this->logThis("Requested SuperAdmin Status", "users", $intUserId);
                            // We need to set some user messages before redirect
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Super Admin status request stored')));
                            redirect('users/viewall/', 'refresh');
                        } else {
                            $arrPageData['arrErrorMessages'][] = "Unable to complete the request";
                            $arrPageData['strPageTitle'] = "System Error";
                            $arrPageData['strPageText'] = "There was an error attempting to request Super Admin status";
                            $booNoErrors = false;
                        }
                    }
                    $arrPageData['strFirstName'] = $mixUsersData['result'][0]->firstname;
                    $arrPageData['strLastName'] = $mixUsersData['result'][0]->lastname;
                } else {
                    $arrPageData['arrErrorMessages'][] = "User could not be found.";
                    $arrPageData['strPageTitle'] = "System Error";
                    $arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve user information.";
                    $booNoErrors = false;
                }
            } else {
                $arrPageData['arrErrorMessages'][] = "Request Already Pending";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "Another user has already requested to become the SuperAdmin.";
                $booNoErrors = false;
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booNoErrors) {
            //load the correct view
            $this->load->view('users/becomesuperadmin', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    // function not necesary
    /*
      public function view($intId)
      {
      // housekeeping
      $arrPageData = array();
      $arrPageData['arrPageParameters']['strSection'] = get_class();
      $arrPageData['arrPageParameters']['strPage'] = "Get User";
      $arrPageData['arrErrorMessages'] = array();
      $arrPageData['arrUserMessages'] = array();

      // load model
      $this->load->model('users_model');

      // Use model to find user details
      $arrPageData['arrUser'] = $this->users_model->getOne($intId);

      // Check the user was found
      if ($arrPageData['arrUser']['booSuccess'] != true)
      {
      // write error
      $arrPageData['arrErrorMessages'][] = "User not found";
      }

      // load views
      $this->load->view('header', 	$arrPageData);
      if ($arrPageData['arrUser']['booSuccess'] == true)
      {
      $this->load->view('user', 	$arrPageData);
      }
      $this->load->view('footer', 	$arrPageData);
      }
     */
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */