<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customfields extends CI_Controller {

    public function index() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/accounts/edit/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $this->load->model('users_model');
        $this->load->model('customfields_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = 'Custom Fields';
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if($this->session->userdata('objSystemUser')->levelid > 2) {
            $booPermission = true;
        } else {
            $booPermission = false;
        }
        $booSuccess = false;

        if ($booPermission) {
            /* Load existing custom fields */
            $arrPageData['arrCustomFields'] = $this->customfields_model->getAll();
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        /* Load views */
        $this->load->view('common/header', 	$arrPageData);

        if ($booPermission) {
            $this->load->view('customfields/index', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }

        $this->load->view('common/footer', 	$arrPageData);
    }

    public function add() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/accounts/edit/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $this->load->model('users_model');
        $this->load->model('customfields_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Add custom field";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if($this->session->userdata('objSystemUser')->levelid > 2) {
            $booPermission = true;
        } else {
            $booPermission = false;
        }
        $booSuccess = false;

        if ($booPermission) {
            /* If form submitted */
            if($this->input->post()) {
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                $this->form_validation->set_rules('field_name', 'Field Name', 'trim|required');

                if ($this->form_validation->run()) {
                    if(!$this->customfields_model->checkDoesNotExist($this->input->post('field_name'))) {

                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Custom field already exists on this account.')));
                        redirect('customfields/add/', 'refresh');
                    } else {
                        if($this->customfields_model->addField($this->input->post())) {
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Custom field added.')));
                            redirect('customfields', 'refresh');
                        } else {
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Custom field could not be added. Please contact support.')));
                            redirect('customfields/add/', 'refresh');
                        }
                    }
                }
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        /* Load views */
        $this->load->view('common/header', 	$arrPageData);
        if ($booPermission) {
            $this->load->view('customfields/add', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', 	$arrPageData);
    }

    public function edit($id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/accounts/edit/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $this->load->model('users_model');
        $this->load->model('customfields_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit custom field";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if($this->session->userdata('objSystemUser')->levelid > 2) {
            $booPermission = true;
        } else {
            $booPermission = false;
        }
        $booSuccess = false;

        if ($booPermission) {

            if(!$arrPageData['custom_field'] = $this->customfields_model->getField($id)) {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Custom field could not be found. Please contact support if you need further assistance.')));
            }

            /* If form submitted */
            if($this->input->post()) {
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                $this->form_validation->set_rules('field_name', 'Field Name', 'trim|required');

                if ($this->form_validation->run()) {

                    if($this->input->post('field_name') != $arrPageData['custom_field']->field_name) {
                        if(!$this->customfields_model->checkDoesNotExist($this->input->post('field_name'))) {

                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Custom field already exists on this account.')));
                            redirect('customfields/edit/' . $id, 'refresh');
                        } else {
                            if($this->customfields_model->editField($id, $this->input->post())) {
                                $this->session->set_userdata('booCourier', true);
                                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Custom field saved.')));
                                redirect('customfields', 'refresh');
                            } else {
                                $this->session->set_userdata('booCourier', true);
                                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Custom field could not be saved. Please contact support.')));
                                redirect('customfields/edit/' . $id, 'refresh');
                            }
                        }
                    }

                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Custom field saved.')));
                    redirect('customfields', 'refresh');
                }
            }

        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        /* Load views */
        $this->load->view('common/header', 	$arrPageData);
        if ($booPermission) {
            $this->load->view('customfields/edit', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', 	$arrPageData);
    }

    public function delete($field_id) {
        $this->load->model('customfields_model');
        if($this->customfields_model->fieldHasContent($field_id)) {
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('This custom field is being used. Please remove all data from items using this field first.')));
            redirect('/customfields/', 'refresh');
        } else {
            $this->customfields_model->deleteField($field_id);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Custom field removed.')));
            redirect('customfields', 'refresh');
        }
    }

}
