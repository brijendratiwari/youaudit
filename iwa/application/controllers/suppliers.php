<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends MY_Controller {

    /**
     * Index Method
     */
    public function index() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            redirect('users/login/', 'refresh');
        }
        $this->load->model('suppliers_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Suppliers";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // Query Suppliers Model
        $arrPageData['suppliers'] = $this->suppliers_model->getAll();

        // Check if any suppliers came back, if not show error
        if(!$arrPageData['suppliers']) {
            $arrPageData['arrErrorMessages'][] = "Unable to find any suppliers.";
        }

        $this->load->view('common/header', 	$arrPageData);
        $this->load->view('suppliers/index', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function view($id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            redirect('users/login/', 'refresh');
        }
        $this->load->model('suppliers_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Suppliers";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // Query Suppliers Model
        $arrPageData['supplier'] = $this->suppliers_model->getOne($id);

        // Check if any suppliers came back, if not show error
        if(!$arrPageData['supplier']) {
            $arrPageData['arrErrorMessages'][] = "Unable to find any suppliers.";
        }

        $this->load->view('common/header', 	$arrPageData);
        $this->load->view('suppliers/view', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    /**
     * Supplier Add Method
     */
    public function add() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            redirect('users/login/', 'refresh');
        }
        $this->load->model('suppliers_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Add Suppliers";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if($this->input->post()) {
            if($this->suppliers_model->add($this->input->post())) {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Supplier successfully added')));
            } else {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Supplier could not be added, please contact support.')));
            }

            redirect('suppliers');
        }

        $this->load->view('common/header', 	$arrPageData);
        $this->load->view('suppliers/add', $arrPageData);
        $this->load->view('common/footer', $arrPageData);

    }

    public function edit($id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            redirect('users/login/', 'refresh');
        }
        $this->load->model('suppliers_model');

        $arrPageData = array();

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit Suppliers";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if($this->input->post()) {
            if($this->suppliers_model->edit($id, $this->input->post())) {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Supplier successfully updated.')));
            } else {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Supplier cannot be updated, please contact support.')));
            }
            redirect('suppliers');
        }

        $arrPageData['supplier'] = $this->suppliers_model->getOne($id);

        $this->load->view('common/header', 	$arrPageData);
        $this->load->view('suppliers/edit', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function delete($id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            redirect('users/login/', 'refresh');
        }
        $this->load->model('suppliers_model');

        $arrPageData = array();

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit Suppliers";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $arrPageData['supplier'] = $this->suppliers_model->getOne($id);

        $this->load->view('common/header', 	$arrPageData);
        $this->load->view('suppliers/delete', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function confirm_delete($id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            redirect('users/login/', 'refresh');
        }
        $this->load->model('suppliers_model');

        $arrPageData = array();

        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit Suppliers";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if($this->suppliers_model->delete($id)) {
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Supplier Confirmed Deleted.')));
        } else {
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Supplier cannot be deleted as it is in use by items')));
        }
        redirect('suppliers');

    }
}