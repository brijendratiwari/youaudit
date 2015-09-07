<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Compliance extends MY_Controller {

    public function index() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
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
        $this->load->model('tests_model');
        $this->load->model('categories_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
              $start_time = microtime(TRUE);
            $arrPageData['neverTested'] = $this->tests_model->getNeverTested($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['dueTests'] = $this->tests_model->getDueTests($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['upcomingTests'] = $this->tests_model->getUpcomingTests($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['overdueMandatory'] = $this->tests_model->getComplianceStats();
            $arrPageData['allTests'] = $this->tests_model->getAllTests($this->input->post());
           
            // Multiple Compliance
          
            $arrPageData['allComplianceChild'] = $this->tests_model->getAllComplianceChild();
           
            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['category_filter'] = $this->input->post('filter_cat');

            /* Check filter */
        } else {
            
        }

        // load views
      
	$end_time = microtime(TRUE);
 
        $time_taken = $end_time - $start_time;

        $time_taken = round($time_taken,2);

        echo 'Page generated in '.$time_taken.' seconds.';
        
        
        $this->load->view('common/header', $arrPageData);
        $this->load->view('compliance/index', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function view($id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
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
        $this->load->model('tests_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            $arrPageData['test'] = $this->tests_model->getTest($id);
        } else {
            
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('compliance/view', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function edit($id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

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
        $this->load->model('tests_model');
        $this->load->model("categories_model");
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['test'] = $this->tests_model->getTest($id);
            $arrPageData['id'] = $id;

            if ($this->input->post()) {

                $this->tests_model->saveTest($this->input->post('id'), $this->input->post());
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully updated')));
                redirect('/compliance/view/' . $this->input->post('id'), 'refresh');
            }
        } else {
            
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('compliance/edit', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function add() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

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
        $this->load->model('tests_model');
        $this->load->model("categories_model");
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");
        if ($booPermission) {
            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();

            if ($this->input->post()) {
              
                $this->tests_model->addTest($this->input->post('id'), $this->input->post());
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully added')));
                redirect('/compliance/', 'refresh');
            }
        } else {
            
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('compliance/add', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function log($itemid) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

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
        $this->load->model('tests_model');
        $this->load->model('categories_model');
        $this->load->model('items_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            $item_cat = $this->items_model->getCategoryFor($itemid);
            $arrPageData['checks'] = $this->tests_model->getTestsByCat($item_cat);

            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['id'] = $itemid;

            if ($this->input->post()) {

                $this->tests_model->logTest($this->input->post());
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully logged')));
                redirect('/items/view/' . $this->input->post('test_item_id'), 'refresh');
            }
        } else {
            
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('compliance/log', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }
    
    public function addmultiple() {
        
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
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
        $this->load->model('tests_model');
        $this->load->model('categories_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            $arrPageData['neverTested'] = $this->tests_model->getNeverTested($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['dueTests'] = $this->tests_model->getDueTests($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['upcomingTests'] = $this->tests_model->getUpcomingTests($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['overdueMandatory'] = $this->tests_model->getComplianceStats();
            $arrPageData['allTests'] = $this->tests_model->getAllTests($this->input->post());
            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['category_filter'] = $this->input->post('filter_cat');

            
                if ($this->input->post()) {
          if($this->input->post('parent_name')){
              $this->tests_model->add_multiple_compliance($this->input->post());
          }
      }
            /* Check filter */
        } else {
            
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('compliance/addmultiple', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

}
