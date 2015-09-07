<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends CI_Controller {

    public function index() {
        $this->edit();
    }

    public function edit() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/accounts/edit/');
            redirect('users/login/', 'refresh');
        }
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Account.edit");
        $booSuccess = false;
        if ($booPermission) {

            // models
            $this->load->model('accounts_model');
            $this->load->model('theme_model');
            $this->load->model('actions_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            // Use model to find account details
            $arrPageData['arrAccount'] = $this->accounts_model->getOne($this->session->userdata('objSystemUser')->accountid);

            // Check the account was found
            if ($arrPageData['arrAccount']['booSuccess'] != true) {
                // write error
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We apologise for this error.";
                $arrPageData['arrErrorMessages'][] = "Account not found";
            } else {

                // set the form fields ready for display
                $arrPageData['strAccountName'] = $arrPageData['arrAccount']['result'][0]->accountname;
                $arrPageData['strAccountAddress'] = $arrPageData['arrAccount']['result'][0]->accountaddress;
                $arrPageData['strAccountCity'] = $arrPageData['arrAccount']['result'][0]->accountcity;
                $arrPageData['strAccountCounty'] = $arrPageData['arrAccount']['result'][0]->accountcounty;
                $arrPageData['strAccountPostCode'] = $arrPageData['arrAccount']['result'][0]->accountpostcode;
                $arrPageData['strAccountCountry'] = $arrPageData['arrAccount']['result'][0]->accountcountry;
                $arrPageData['strAccountSecurityQuestion'] = $arrPageData['arrAccount']['result'][0]->accountsecurityquestion;
                $arrPageData['strAccountSecurityAnswer'] = $arrPageData['arrAccount']['result'][0]->accountsecurityanswer;
                $arrPageData['strAccountContactName'] = $arrPageData['arrAccount']['result'][0]->accountcontactname;
                $arrPageData['strAccountContactEmail'] = $arrPageData['arrAccount']['result'][0]->accountcontactemail;
                $arrPageData['strAccountContactNumber'] = $arrPageData['arrAccount']['result'][0]->accountcontactnumber;
                $arrPageData['strAccountSupportAddress'] = $arrPageData['arrAccount']['result'][0]->accountsupportemail;
                $arrPageData['strAccountFleetContact'] = $arrPageData['arrAccount']['result'][0]->accountfleetcontact;
                $arrPageData['strAccountFleetEmail'] = $arrPageData['arrAccount']['result'][0]->accountfleetemail;
                $arrPageData['strAccountComplianceContact'] = $arrPageData['arrAccount']['result'][0]->accountcompliancecontact;
                $arrPageData['strAccountComplianceEmail'] = $arrPageData['arrAccount']['result'][0]->accountcomplianceemail;
                $arrPageData['currency'] = $arrPageData['arrAccount']['result'][0]->currency;
                $arrPageData['intAccountId'] = $this->session->userdata('objSystemUser')->accountid;

                // Check if updated
                if ($this->input->post()) {

                    $config = array(
                        'upload_path' => 'brochure/img/',
                        'allowed_types' => 'gif|jpg|png|jpeg',
                        'max_size' => '100',
                        'max_width' => '1024',
                        'max_height' => '500',
                        'encrypt_name' => false,
                    );

                    $this->load->library('upload', $config);

                    $this->form_validation->set_rules('account_name', 'Name', 'trim|required');
                    $this->form_validation->set_rules('account_address', 'Address', 'trim|required');
                    $this->form_validation->set_rules('account_city', 'City', 'trim|required');
                    $this->form_validation->set_rules('account_postcode', 'Post Code', 'trim|required');
                    $this->form_validation->set_rules('account_securityquestion', 'Security Question', 'trim|required');
                    $this->form_validation->set_rules('account_securityanswer', 'Security Answer', 'trim|required');
                    $this->form_validation->set_rules('account_contactname', 'Contact Name', 'trim|required');
                    $this->form_validation->set_rules('account_contactemail', 'Contact Email', 'trim|required');
                    $this->form_validation->set_rules('account_contactnumber', 'Contact Number', 'trim|required');
                    $this->form_validation->set_rules('account_supportaddress', 'Support eMail', 'trim|required');
                    $this->form_validation->set_rules('color', 'color', 'trim|required');

                    if ($this->input->post('account_fleetemail')) {
                        $this->form_validation->set_rules('account_fleetcontact', 'Fleet Contact Name', 'trim|required');
                    }

                    if ($this->input->post('account_complianceemail')) {
                        $this->form_validation->set_rules('account_compliancecontact', 'Fleet Contact Name', 'trim|required');
                    }

                    $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                    if ($this->form_validation->run()) {
                        $theme_data = array();

                        if (!$this->upload->do_upload('file')) {
                            $error = array('upload_message' => $this->upload->display_errors());
                        } else {
                            $upload_data = $this->upload->data();
                            $theme_data['logo'] = $upload_data['file_name'];
                            $arrdata = array('filename' => $upload_data['file_name']);
                            $this->session->userdata('objSystemUser')->accountcompliancefilename = $upload_data['file_name'];
                            $this->accounts_model->update($this->session->userdata('objSystemUser')->accountid, $arrdata);
                        }
                        $config_favicon = array(
                            'upload_path' => 'brochure/img/',
                            'allowed_types' => '*',
                      
                        );

                        $this->load->library('upload', $config_favicon);
                        if (!$this->upload->do_upload('file_favicon')) {
                            $error = array('upload_message' => $this->upload->display_errors());
                        } else {

                            $upload_data = $this->upload->data();
                            $theme_data['favicon'] = $upload_data['file_name'];
                        }

                        $theme_data['userid'] = $this->session->userdata('objSystemUser')->userid;
                        $theme_data['color'] = $this->input->post('color');
                        $theme_data['status'] = 0;
                        $theme_data['when'] = date('Y-m-d H:i:s', time());


                        // the form validated, so try to create
                        //does the record create?
                        if ($this->theme_model->select_Theme($this->session->userdata('objSystemUser')->userid)) {

                            $this->theme_model->update_Theme($theme_data);
                            // Generate log file
                            $log_report = array(
                                'action' => 'Update Theme',
                                'table' => 'theme',
                                'who_did_it' => $this->session->userdata('objSystemUser')->userid,
                                'on_account' => $this->session->userdata('objSystemUser')->accountid,
                                'when' => date('Y-m-d H:i:s', time())
                            );
                            $this->actions_model->logOne($log_report);
                        } else {
                            // Generate Log file
                            $log_report = array(
                                'action' => 'Change Theme',
                                'table' => 'theme',
                                'who_did_it' => $this->session->userdata('objSystemUser')->userid,
                                'on_account' => $this->session->userdata('objSystemUser')->accountid,
                                'when' => date('Y-m-d H:i:s', time())
                            );
                            $this->actions_model->logOne($log_report);

                            $this->theme_model->insert_Theme($theme_data);
                        }
                        $arrInput = array(
                            'name' => $this->input->post('account_name'),
                            'address' => $this->input->post('account_address'),
                            'city' => $this->input->post('account_city'),
                            'county' => $this->input->post('account_county'),
                            'postcode' => $this->input->post('account_postcode'),
                            'country' => $this->input->post('account_country'),
                            'security_question' => $this->input->post('account_securityquestion'),
                            'security_answer' => $this->input->post('account_securityanswer'),
                            'contact_name' => $this->input->post('account_contactname'),
                            'contact_email' => $this->input->post('account_contactemail'),
                            'contact_number' => $this->input->post('account_contactnumber'),
                            'support_email' => $this->input->post('account_supportaddress'),
                            'fleet_contact' => $this->input->post('account_fleetcontact'),
                            'fleet_email' => $this->input->post('account_fleetemail'),
                            'compliance_contact' => $this->input->post('account_compliancecontact'),
                            'compliance_email' => $this->input->post('account_complianceemail'),
                            'currency' => $this->input->post('currency'),
                            'color' => $this->input->post('color'),
                        );

                        if ($this->accounts_model->update($this->session->userdata('objSystemUser')->accountid, $arrInput)) {
                            // Yes				
                            // We need to set some user messages before redirect
                            $this->session->userdata('objSystemUser')->accountcompliancecolor = $this->input->post('color');
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Account Updated')));
                            redirect('welcome/index/', 'refresh');
                        } else {
                            // No. ERROR
                            $arrPageData['arrErrorMessages'][] = "Account Not Updated";
                        }
                    } else {
                        // if we're here, there's an error somewhere, so repopulate the form fields.

                        $arrPageData['strAccountName'] = $this->input->post('account_name');
                        $arrPageData['strAccountAddress'] = $this->input->post('account_address');
                        $arrPageData['strAccountCity'] = $this->input->post('account_city');
                        $arrPageData['strAccountCounty'] = $this->input->post('account_county');
                        $arrPageData['strAccountPostCode'] = $this->input->post('account_postcode');
                        $arrPageData['strAccountCountry'] = $this->input->post('account_country');
                        $arrPageData['strAccountSecurityQuestion'] = $this->input->post('account_securityquestion');
                        $arrPageData['strAccountSecurityAnswer'] = $this->input->post('account_securityanswer');
                        $arrPageData['strAccountContactName'] = $this->input->post('account_contactname');
                        $arrPageData['strAccountContactEmail'] = $this->input->post('account_contactemail');
                        $arrPageData['strAccountContactNumber'] = $this->input->post('account_contactnumber');
                        $arrPageData['strAccountSupportAddress'] = $this->input->post('account_supportaddress');
                    }
                }
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }
        // load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission) {
            //load the correct view
            $this->load->view('accounts/edit', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
