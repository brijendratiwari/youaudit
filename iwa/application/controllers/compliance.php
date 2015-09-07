<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compliance extends MY_Controller {
    
    public function index($filter = 1) {
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
//        $this->load->model('categories_model');
	$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            $start_time = microtime(TRUE);
//            $arrPageData['neverTested'] = $this->tests_model->getNeverTested($this->session->userdata('objSystemUser')->accountid);  
            if($filter != NULL){
                switch ($filter){
                    case '1':{
                        $end_date = date('Y-m-d',  strtotime('+7 days'));
                        $start_date = date('Y-m-d',  strtotime('7 days ago'));
                        $this->session->set_userdata('checksDue_chk',1);
                        break;
                    }
                    case '2':{
                        $end_date = date('Y-m-d',  strtotime('+1 month'));
                        $start_date = date('Y-m-d',  strtotime('31 days ago'));
                        $this->session->set_userdata('checksDue_chk',2);
                        break;
                    }
                    case '3':{
                        $end_date = date('Y-m-d',  strtotime('+3 months'));
                        $start_date = date('Y-m-d',  strtotime('31 days ago'));
                        $this->session->set_userdata('checksDue_chk',3);
                        break;
                    }
                    case '4':{
                        $end_date = date('Y-m-d',  strtotime('+6 months'));
                        $start_date = date('Y-m-d',  strtotime('45 days ago'));
                        $this->session->set_userdata('checksDue_chk',4);
                        break;
                    }
                    case '5':{
                        $end_date = date('Y-m-d',  strtotime('+12 months'));
                        $start_date = date('Y-m-d',  strtotime('90 days ago'));
                        $this->session->set_userdata('checksDue_chk',5);
                        break;
                    }
                    default :{
                        $end_date = date('Y-m-d',  strtotime('+7 days'));
                        $start_date = date('Y-m-d',  strtotime('7 days ago'));
                        $this->session->set_userdata('checksDue_chk',1);
                        break;
                    }
                }
            }
            
            $arrPageData['dueTests'] = $this->tests_model->getDueTests($this->session->userdata('objSystemUser')->accountid,array('start_date'=>$start_date,'end_date'=>$end_date));
            
            

//            var_dump($arrPageData['dueTests']["dueMandatory"]);
//            die;
            
            /* Check filter */

        } else {
            
        }
   
        
	// load views
        $end_time = microtime(TRUE);
 
        $time_taken = $end_time - $start_time;

        $time_taken = round($time_taken,2);

        echo 'Page generated in '.$time_taken.' seconds.';
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/index', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);
    }
    
    public function adhoc($filter = 1) {
        
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
//        $this->load->model('categories_model');
	$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            $start_time = microtime(TRUE);
            
//            if($filter != NULL){
//                switch ($filter){
//                    case '1':{
//                        $end_date = date('Y-m-d',  strtotime('+7 days'));
//                        $start_date = date('Y-m-d',  strtotime('7 days ago'));
//                        $this->session->set_userdata('adhocChecksDue_chk',1);
//                        break;
//                    }
//                    case '2':{
//                        $end_date = date('Y-m-d',  strtotime('+1 month'));
//                        $start_date = date('Y-m-d',  strtotime('31 days ago'));
//                        $this->session->set_userdata('adhocChecksDue_chk',2);
//                        break;
//                    }
//                    case '3':{
//                        $end_date = date('Y-m-d',  strtotime('+3 months'));
//                        $start_date = date('Y-m-d',  strtotime('31 days ago'));
//                        $this->session->set_userdata('adhocChecksDue_chk',3);
//                        break;
//                    }
//                    case '4':{
//                        $end_date = date('Y-m-d',  strtotime('+6 months'));
//                        $start_date = date('Y-m-d',  strtotime('45 days ago'));
//                        $this->session->set_userdata('adhocChecksDue_chk',4);
//                        break;
//                    }
//                    default :{
//                        $end_date = date('Y-m-d',  strtotime('+7 days'));
//                        $start_date = date('Y-m-d',  strtotime('7 days ago'));
//                        $this->session->set_userdata('adhocChecksDue_chk',1);
//                        break;
//                    }
//                }
//            }
//            $arrPageData['neverTested'] = $this->tests_model->getNeverTested($this->session->userdata('objSystemUser')->accountid);  
            
            
            $arrPageData['dueTests'] = $this->tests_model->getAdhocDueTests($this->session->userdata('objSystemUser')->accountid);


        } else {
            
        }
   
//        var_dump($arrPageData);die;
	// load views
        $end_time = microtime(TRUE);
 
        $time_taken = $end_time - $start_time;

        $time_taken = round($time_taken,2);

        echo 'Page generated in '.$time_taken.' seconds.';
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/adhoclist', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);
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
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/view', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);        
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
            
                if($this->input->post()) {
                    
                    $this->tests_model->saveTest($this->input->post('id'), $this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully updated')));
                    redirect('/compliance/view/'.$this->input->post('id'), 'refresh');
                }
        } else {
            
        }
        
	// load views
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/edit', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);         
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
            
                if($this->input->post()) {
                    
                    $this->tests_model->addTest($this->input->post('id'), $this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully added')));
                    redirect('/compliance/', 'refresh');
                }
        } else {
            
        }
        
	// load views
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/add', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);         
    }

    public function remove($id) {
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

        if ($booPermission && (int)$id) {
            $this->tests_model->removeTest((int)$id);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully removed')));
            redirect('/compliance/', 'refresh');
        }

        // load views
        $this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/add', $arrPageData);
        $this->load->view('common/footer', 	$arrPageData);
    }
    
    public function removeTask($id) {
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
        $this->load->model("categories_model");
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission && (int)$id) {
            $this->tests_model->removeTask((int)$id);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully removed')));
            redirect('/compliance/compliancesadmin', 'refresh');
        }

    }
    public function removeTemplate($id) {
        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model("categories_model");
        
        if ((int)$id) {
            $this->tests_model->removeTemplate((int)$id);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Template was successfully removed')));
            redirect('/admins/compliancesList', 'refresh');
        }

    }
    public function removeTaskAdmins($id) {
//        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
//            $this->session->set_userdata('strReferral', '/categories/viewall/');
//            redirect('users/login/', 'refresh');
//        }
//        // housekeeping
//        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();
//        $arrPageData['arrPageParameters']['strPage'] = "View All";
//        $arrPageData['arrSessionData'] = $this->session->userdata;
//        $this->session->set_userdata('booCourier', false);
//        $this->session->set_userdata('arrCourier', array());
//        $arrPageData['arrErrorMessages'] = array();
//        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model("categories_model");
//        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ((int)$id) {
            $this->tests_model->removeTask((int)$id);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully removed')));
            redirect('/admins/complianceChecks', 'refresh');
        }

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
            $arrPageData['itemDetails'] = $this->items_model->getOne($itemid,$this->session->userdata('objSystemUser')->accountid);
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['id'] = $itemid;
            
                if($this->input->post()) {

                    $this->tests_model->logTest($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully logged')));
                    redirect('/items/view/' . $this->input->post('test_item_id'), 'refresh');
                }
        } else {
            
        }
                   $end_date = date('Y-m-d',  strtotime('+1 month'));
                   $start_date = date('Y-m-d',  strtotime('31 days ago'));
         $arrPageData['dueTests'] = $this->tests_model->getDueTests($this->session->userdata('objSystemUser')->accountid,array('start_date'=>$start_date,'end_date'=>$end_date));
    
         foreach ($arrPageData['dueTests']['dueMandatory'] as $duerecord){
         if($duerecord['item']->itemid == $itemid){
             $arrPageData['item_dueTests'] = $duerecord;
         }
             
         }
         
//         var_dump( $arrPageData['dueTests']['dueMandatory']);
         
	// load views
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/log', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);         
    }
    
    public function complianceshistory() {
            if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
	
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
        $this->load->model('items_model');
        $this->load->model('categories_model');
	$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            $start_time = microtime(TRUE);
//            date_default_timezone_set('Asia/Calcutta');
            $start_date = date('Y-m-d',strtotime('now')).' 23:59:00';
            if($this->input->post())
            {
                $filter = $this->input->post('filter');
            }
            else
            {
                $filter = 1;
            }
            switch ($filter){
                case 1:{
                    $end_date = date('Y-m-d',strtotime('7 days ago')).' 00:01:00';
                    break;
                }
                case 2:{
                    $end_date = date('Y-m-d',strtotime('1 month ago')).' 00:01:00';
                    break;
                }
                case 3:{
                    $end_date = date('Y-m-d',strtotime('3 months ago')).' 00:01:00';
                    break;
                }
                case 4:{
                    $end_date = date('Y-m-d',strtotime('6 months ago')).' 00:01:00';
                    break;
                }
                case 5:{
                    $end_date = date('Y-m-d',strtotime('1 year ago')).' 00:01:00';
                    break;
                }
                case 6:{
                    $end_date = '';
                    break;
                }
                default :{
                    $end_date = date('Y-m-d',strtotime('7 days ago')).' 00:01:00';
                    break;
                }
            }
            $this->session->set_userdata('comHistory_chk',$filter);
            $arrPageData['neverTested'] = $this->tests_model->getNeverTested($this->session->userdata('objSystemUser')->accountid);  
            $arrPageData['dueTests'] = $this->tests_model->getComplianceHistoryFiltered($start_date,$end_date);
            $arrPageData['missedTests'] = $this->tests_model->getMissedHistory($start_date,$end_date);
//            var_dump($arrPageData['dueTests']);
//            var_dump($arrPageData['missedTests']);
            foreach ($arrPageData['dueTests'] as $key => $value) {
                $arrPageData['dueTests'][$key]['location_name'] = $this->tests_model->getLocationforHistory($value['test_item_id'],$value['test_date']);
                $arrPageData['dueTests'][$key]['owner_name'] = $this->tests_model->getOwnerNameforHistory($value['test_item_id'],$value['test_date']);
                $arrPageData['dueTests'][$key]['site_name'] = $this->tests_model->getSiteNameforHistory($value['test_item_id'],$value['test_date']);
                $arrPageData['dueTests'][$key]['manager'] = $this->tests_model->getManagerforHistory($value['test_item_id'],$value['test_date']);

                $arrPageData['dueTests'][$key]['test_type_name'] = $this->tests_model->getComplianceNameforHistory($value['test_item_id'],$value['test_date']);
                $arrPageData['dueTests'][$key]['name'] = $this->tests_model->getCategoryforHistory($value['test_item_id'],$value['test_date']);
//
                $arrPageData['dueTests'][$key]['total_tasks'] = $this->tests_model->getTaskCount($value['test_date']);
                $arrPageData['dueTests'][$key]['tasks'] = $this->tests_model->getTaskCount($value['test_date'],'details');
            }
            if($arrPageData['missedTests']){
                foreach ($arrPageData['missedTests'] as $key => $value) {
                    $arrPageData['missedTests'][$key]['location_name'] = $this->tests_model->getLocation($value['item_id']);
                    $arrPageData['missedTests'][$key]['owner_name'] = $this->tests_model->getOwnerName($value['item_id']);
                    $arrPageData['missedTests'][$key]['site_name'] = $this->tests_model->getSiteName($value['item_id']);

                    $arrPageData['missedTests'][$key]['tasks'] = $this->tests_model->getComplianceTasks($value['compliance_id']);
                    $arrPageData['missedTests'][$key]['manager'] = $this->tests_model->getManagerOfCheck($value['compliance_id']);
                    $arrPageData['missedTests'][$key]['total_tasks'] = count($arrPageData['missedTests'][$key]['tasks']);

                }
            }
            
//            echo '<pre>';var_dump($arrPageData['dueTests']);echo '</pre>';
//            die;
//            $arrPageData['upcomingTests'] = $this->tests_model->getUpcomingTests($this->session->userdata('objSystemUser')->accountid);
//            $arrPageData['allTests'] = $this->tests_model->getAllTests($this->input->post());
//            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
//            $arrPageData['category_filter'] = $this->input->post('filter_cat');

            /* Check filter */

        } else {
            
        }
        
        $end_time = microtime(TRUE);
 
        $time_taken = $end_time - $start_time;

        $time_taken = round($time_taken,2);

        echo 'Page generated in '.$time_taken.' seconds.';
       
        // load views
	$this->load->view('common/header',$arrPageData);
        $this->load->view('compliance/compliancehistory', $arrPageData);
	$this->load->view('common/footer',$arrPageData);
    }
    
    public function complianceslist() {
        
            if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
	
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
            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['allCompliances'] = $this->tests_model->getAllCompliances($this->session->userdata('objSystemUser')->accountid,0);
            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
            
//            var_dump($arrPageData['allCompliances']);
            /* Check filter */

        } else {
            
        }
       
        // load views
	$this->load->view('common/header',$arrPageData);
        $this->load->view('compliance/compliancelist', $arrPageData);
	$this->load->view('common/footer',$arrPageData);
    }
    public function report() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
	
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
        $this->load->model('items_model');
	$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");
        $account_id = $this->session->userdata('objSystemUser')->accountid;
        if ($booPermission) {
            $arrPageData['allCompliances'] = $this->tests_model->getAllCompliances($account_id,1);
            $result = $this->items_model->getAll($account_id, array('limit' => 0));
            $arrPageData['items'] = $result['results'];
//            var_dump($arrPageData['allCompliances']);
            /* Check filter */

        } else {
            
        }
       
        // load views
	$this->load->view('common/header',$arrPageData);
        $this->load->view('compliance/compliancehistoryreport', $arrPageData);
	$this->load->view('common/footer',$arrPageData);
    }
    public function templates() {
        
            if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
	
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
            
            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['allTemplates'] = $this->tests_model->getAllTemplates($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
            
//            var_dump($arrPageData['allCompliances']);
            /* Check filter */

        } else {
            
        }
       
        // load views
	$this->load->view('common/header',$arrPageData);
        $this->load->view('compliance/templates', $arrPageData);
	$this->load->view('common/footer',$arrPageData);
    }
    
    public function editcompliance() {
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
            
                if($this->input->post()) {
                    $this->tests_model->updateCompliance($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Compliance was successfully updated')));
                    redirect('/compliance/complianceslist', 'refresh');
                }
        } else {
            
        }
              
    }
    public function editmulticompliance() {
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
            
                if($this->input->post()) {
                    $this->tests_model->updateMultiCompliance($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Compliance(s) was/were successfully updated')));
                    redirect('/compliance/complianceslist', 'refresh');
                }
        } else {
            
        }
              
    }
    
    public function listalltasks()
    {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
	
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
            if($this->input->post())
            {
                $taskDetails = array();
                $tasks = $this->input->post('tasks');
                $temp = explode(',', $tasks);
                foreach ($temp as $key => $value) {
                    $taskDetails[] = $this->tests_model->getTask($value);
                }
//                var_dump($taskDetails);
                $arrPageData['taskDetails'] = $taskDetails;
            }
//            var_dump($arrPageData['allCompliances']);
            /* Check filter */

        } else {
            
        }
       
        // load views
	$this->load->view('common/header',$arrPageData);
        $this->load->view('compliance/compliancetasklist', $arrPageData);
	$this->load->view('common/footer',$arrPageData);
    }
    
    public function listAllTasksJson(){
        $this->load->model('tests_model');
        $taskDetails = array();
        $tasks = $this->input->post('tasks');
        if(is_numeric($tasks))   {     
        $temp = explode(',', $tasks);
        foreach ($temp as $key => $value) {
            $taskDetails[] = $this->tests_model->getTask($value);
        }}
       
//               var_dump($taskDetails);
        echo json_encode($taskDetails);
        die;
    }
    
    public function compliancesadmin() {
        
            if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }
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
             $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
             $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
             $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);
//              $arrPageData['allTests'] = $this->tests_model->getAllTests($this->input->post());
              $arrPageData['allTests'] = $this->tests_model->getAllTasks();
              $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();;
             if($this->input->post()) {
//                 var_dump($this->input->post());
//                 die;
                    $this->tests_model->addComplianceTest($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully added')));
                    redirect('/compliance/compliancesadmin', 'refresh');
                }

        } else {
            
        }
       
        // load views
	$this->load->view('common/header',$arrPageData);
        $this->load->view('compliance/complianceadmin', $arrPageData);
	$this->load->view('common/footer',$arrPageData);
    }
    public function generateHistoryPdf()
    {
        $allData = $this->input->post('allData');
        $tasks = $this->input->post('tasks');
//        var_dump($allData,$tasks);die;
        $this->load->model('tests_model');
        $this->tests_model->outputHistoryPdfFile($allData,$tasks);
    }
    public function exportToPdf()
    {
        $allData = $this->input->post('allData');
        $filename = $this->input->post('filename');
        $this->load->model('tests_model');
        $this->tests_model->exportPdfFile($allData,$filename);
    }
    public function exporttocsv()
    { 
        $output = array();
//        var_dump($this->input->post('allData'));
        $filename = $this->input->post('filename');
        $allData = explode('|', $this->input->post('allData'));
//        var_dump($allData);die;
        foreach ($allData as $key => $value) {
            $output[] = preg_replace('/<\/?[a-zA-Z]*[^>]*>/','',preg_replace('/<\/?[a-zA-Z]*[^>]*>/','',$value));
        }
        foreach ($output as $key => $value) {
            $output[$key] = explode(',', $value);
        }
//        var_dump($output);die;
        $this->load->helper('csv');
        getcsv($output,"$filename.csv");
    }
    public function addCompliance(){
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
            
                if($this->input->post()) {
                    
                    $this->tests_model->addComplianceTest($this->input->post('id'), $this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully added')));
                    redirect('/compliance/compliancesadmin', 'refresh');
                }
        } else {
            redirect('/compliance/compliancesadmin', 'refresh');
        }
        
    }
    
    public function addTask() {
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
            
            // measurment for numerical value
            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
                if($this->input->post()) {
                    if(isset($this->session->userdata('objSystemUser')->accountid))
                    {
                        $acid = $this->session->userdata('objSystemUser')->accountid;
                    }  else {
                        $acid = 0;
                    }
                    if($this->input->post('type_of_task') == '0'){
                        $data = array(
                            'task_name' => $this->input->post('task_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => '0',
                            'account_id'=>$acid
                        );
                    } 
                    else{
                       $data = array(
                            'task_name' => $this->input->post('task_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => $this->input->post('measurement_type'),
                           'account_id'=>$acid
                        );
                    }
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Task was successfully added')));
                    $this->tests_model->insertTask($data);
                 
                         redirect('compliance/compliancesadmin', 'refresh');
                }
        } else {
            
        }
        
	// load views
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/addtask', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);       
        
    }
    public function addTaskAdmins() {
//       if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
//            $this->session->set_userdata('strReferral', '/categories/viewall/');
//            redirect('users/login/', 'refresh');
//        }
	$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	$arrPageData = array();
	$arrPageData['arrPageParameters']['strSection'] = get_class();
	$arrPageData['arrPageParameters']['strPage'] = "View All";
	$arrPageData['arrSessionData'] = $this->session->userdata;
	$this->session->set_userdata('booCourier', false);
	$this->session->set_userdata('arrCourier', array());
	$arrPageData['arrErrorMessages'] = array();
	$arrPageData['arrUserMessages'] = array();
        // housekeeping
//	$arrPageData = array();
//	$arrPageData['arrPageParameters']['strSection'] = get_class();
//	$arrPageData['arrPageParameters']['strPage'] = "View All";
//	$arrPageData['arrSessionData'] = $this->session->userdata;
//	$this->session->set_userdata('booCourier', false);
//	$this->session->set_userdata('arrCourier', array());
//	$arrPageData['arrErrorMessages'] = array();
//	$arrPageData['arrUserMessages'] = array();
//        
	$this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model("categories_model");
////	$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");
////
////        if ($booPermission) {
//            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
//            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
//            
//            // measurment for numerical value
//            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
                
                if($this->input->post()) {
                    $acid = 0;
                    if($this->input->post('type_of_task') == '0'){
                        $data = array(
                            'task_name' => $this->input->post('task_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => '0',
                            'account_id'=>$acid,
                            'template_task'=>'1'
                        );
                    } 
                    else{
                       $data = array(
                            'task_name' => $this->input->post('task_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => $this->input->post('measurement_type'),
                           'account_id'=>$acid,
                           'template_task'=>'1'
                        );
                    }
                    
                      if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
             $data['admin_id'] = $arrPageData['arrSessionData']['objAdminUser']->master_account_id;
             $data['account_type']=1;
        } else {
            $data['admin_id'] = $arrPageData['arrSessionData']['objAdminUser']->franchise_account_id;
            $data['account_type']=2;
        }
       
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Task was successfully added')));
                    $this->tests_model->insertTask($data);
                 
                         redirect('/admins/complianceChecks', 'refresh');
                }
//        } else {
//            
//        }
        
	// load views
//	$this->load->view('common/header', 	$arrPageData);
//        $this->load->view('compliance/addtask', $arrPageData);
//	$this->load->view('common/footer', 	$arrPageData);       
        
    }
        public function editTask($id) {
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
            $arrPageData['test'] = $this->tests_model->getTask($id);
             // measurment for numerical value
            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
            $arrPageData['id'] = $id;
            
                if($this->input->post()) {
                    
                    
                   if($this->input->post('type_of_task') == '0'){
                        $data = array(
                            'task_name' => $this->input->post('test_type_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => '0'
                        );
                    }
                    else{
                       $data = array(
                            'task_name' => $this->input->post('test_type_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => $this->input->post('measurement_type'),
                        );
                        
                    }
                    $this->tests_model->updateTask($id, $data);
//                    var_dump($data);die;
                    
                    
//                    $this->tests_model->saveTest($this->input->post('id'), $this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Task was successfully updated')));
                    redirect('/compliance/compliancesadmin', 'refresh');
                }
        } else {
            
        }
        
	// load views
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/edittask', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);         
    }
        public function editTaskAdmins($id) {
//        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
//            $this->session->set_userdata('strReferral', '/categories/viewall/');
//            redirect('users/login/', 'refresh');
//        }
	$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
        // housekeeping
//	$arrPageData = array();
//	$arrPageData['arrPageParameters']['strSection'] = get_class();
//	$arrPageData['arrPageParameters']['strPage'] = "View All";
//	$arrPageData['arrSessionData'] = $this->session->userdata;
//	$this->session->set_userdata('booCourier', false);
//	$this->session->set_userdata('arrCourier', array());
//	$arrPageData['arrErrorMessages'] = array();
//	$arrPageData['arrUserMessages'] = array();
//        
	$this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model("categories_model");
//	$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");
//
//        if ($booPermission) {
//            $arrPageData['categories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
//            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
//            $arrPageData['test'] = $this->tests_model->getTask($id);
//             // measurment for numerical value
//            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
//            $arrPageData['id'] = $id;
            
                if($this->input->post()) {
                    
                    
                   if($this->input->post('type_of_task') == '0'){
                        $data = array(
                            'task_name' => $this->input->post('test_type_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => '0'
                        );
                    }
                    else{
                       $data = array(
                            'task_name' => $this->input->post('test_type_name'),
                            'type_of_task' => $this->input->post('type_of_task'),
                            'measurement' => $this->input->post('measurement_type'),
                        );
                        
                    }
                    $this->tests_model->updateTask($id, $data);
//                    var_dump($data);die;
                    
                    
//                    $this->tests_model->saveTest($this->input->post('id'), $this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Task was successfully updated')));
                    redirect('/admins/complianceChecks', 'refresh');
                }
//        } else {
//            
//        }
        
	// load views
	$this->load->view('common/header', 	$arrPageData);
        $this->load->view('compliance/edittask', $arrPageData);
	$this->load->view('common/footer', 	$arrPageData);         
    }
    
    public function getTasks($filter = false,$id = false){
//        var_dump($filter,$id);
        $this->load->model('tests_model');
        if($filter){
            $data = $this->tests_model->getTask($id);
        }else{
//            var_dump($this->input->post('complianceId'));
            $data = $this->tests_model->getComplianceTasks($this->input->post('complianceId'));
        }
        echo json_encode($data);
        die;
    }
        
    public function makeComplianceCheck(){
        $this->load->model('tests_model');
        $this->load->model('items_model');
        $flag = $this->input->post('test_freq_id');
        
        
        $filter_arr = $this->input->post('filter_state');
        $filter_arr = json_encode(explode(',', $filter_arr));
        $this->session->set_userdata('filter_state',$filter_arr);

        $email_address =  $this->tests_model->fetchEmailAddOfCategory($this->input->post('compliance_check_id'));
        $manager =  $this->tests_model->getManagerOfCheck($this->input->post('compliance_check_id'));
         
        if($email_address && $this->input->post('failedChecks')!='')
        {
            $message = '<style>b{color:#ED9C28;text-shadow: 0 0 1px rgb(211, 211, 211);} table tr td{border:1px solid lightgrey; text-align:center;text-shadow: 0 0 1px rgb(211, 211, 211);}table tr th{color:#ED9C28; border:1px solid lightgrey;text-shadow: 0 0 1px rgb(211, 211, 211);}</style><ul style="list-style-type:none;"><li><b>QR Code:</b> %s</li>
            <li><b>MANUFACTURER:</b> %s</li>
            <li><b>MODEL:</b> %s</li>
            <li><b>CATEGORY:</b> %s</li>
            <li><b>LOCATION:</b> %s</li>
            <li><b>SITE:</b> %s</li>
            <li><b>LOGGED BY:</b> %s</li>
            <li><b>MANAGER OF CHECK:</b> %s</li>
            </ul>
            <ul style="list-style-type:none;"><li><b>COMPLIANCE NAME:</b> %s</li>
            <li><b>COMPLETE DATE:</b> %s</li>
            <li><b>COMPLETE TIME:</b> %s</li>
            </ul>
            <ul style="list-style-type:none;"><li><table style="min-width:350px; cellspacing: 5px;">
            <tr><td colspan="3"><b>CHECKS FAILED</b></td></tr>
            <tr><th>TASK NAME</th><th>TASK RESULT</th><th>NOTES</th></tr>
            
            ';
            
        
            $this->db->select('items.owner_since AS \'date\',
                items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode,
                categories.id AS categoryid, categories.name AS categoryname,
                users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->where('items.id', $this->input->post('item_id'));
            $this->db->where('items.account_id', $this->session->userdata('objSystemUser')->accountid);

            $resQuery = $this->db->get();
            $itemDetails = $resQuery->result_array();
            
            if(!empty($itemDetails)){
                $message = sprintf($message,$itemDetails[0]['barcode'],$itemDetails[0]['manufacturer'],$itemDetails[0]['model'],$itemDetails[0]['categoryname'],$itemDetails[0]['locationname'],$itemDetails[0]['sitename'],$this->session->userdata('objSystemUser')->firstname.' '.$this->session->userdata('objSystemUser')->lastname,$manager,  $this->input->post('compliance_check_name'),date('d/m/Y'),date('h:i A'));
            }
            
            $temp = explode(',', $this->input->post('failedChecks'));
            if ($temp[0] != '') {
                foreach ($temp as $key => $value) {
                    $failed[] = explode('|', $value);
                }
                foreach ($failed as $key => $value) {
                    $tasks = $this->tests_model->getTask((int)$value[0]);
                    
                    $message .= '<tr><td>'.$tasks['task_name'].'</td><td>Fail</td><td>'.$value[1].'</td></tr>';
                }
            }
            
            $message .= '</table></li></ul>';
            
            $config['mailtype'] = 'html';
            $this->load->library('email',$config);

            $this->email->from('info@iworkaudit.com.au', 'Compliance Team');
//            $this->email->to('mayank@ignisitsolutions.com');
            $this->email->to($email_address);

            $this->email->subject('Compliance Failed Alert');
            $this->email->message($message);

            $this->email->send();
        }
        else {
        }
        
        $data = $this->tests_model->recordCheck($this->input->post());
        $this->session->set_userdata('booCourier', true);
        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully added')));
        if($flag != 10)
                redirect('/compliance', 'refresh');
        else
            redirect('/compliance/adhoc', 'refresh');
    }
    
    public function makeComplianceCheckItem(){
        $this->load->model('tests_model');
        $this->load->model('items_model');
        $flag = $this->input->post('test_freq_id');
        
        if($email_address && $this->input->post('failedChecks')!='')
        {

            $message = '<ul style="list-style-type:none;"><li>QR Code: %s</li>
            <li>MANUFACTURER: %s</li>
            <li>MODEL: %s</li>
            <li>CATEGORY: %s</li>
            <li>LOCATION: %s</li>
            <li>SITE: %s</li>
            <li>LOGGED BY: %s</li>
            <li>MANAGER OF CHECK: %s</li>
            </ul>
            <ul style="list-style-type:none;"><li>COMPLIANCE NAME: %s</li>
            <li>COMPLETE DATE: %s</li>
            <li>COMPLETE TIME: %s</li>
            </ul>
            <ul style="list-style-type:none;"><li><table style="min-width:350px; cellspacing: 5px;">
            <tr><td colspan="3">CHECKS FAILED</td></tr>
            <tr><td>TASK NAME</td><td>TASK RESULT</td><td>NOTES</td></tr>
            
            ';
            $email_address =  $this->tests_model->fetchEmailAddOfCategory($this->input->post('compliance_check_id'));
        
            $this->db->select('items.owner_since AS \'date\',
                items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode,
                categories.id AS categoryid, categories.name AS categoryname,
                users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->where('items.id', $this->input->post('item_id'));
            $this->db->where('items.account_id', $this->session->userdata('objSystemUser')->accountid);

            $resQuery = $this->db->get();
            $itemDetails = $resQuery->result_array();
            
            if(!empty($itemDetails)){
                $message = sprintf($message,$itemDetails[0]['barcode'],$itemDetails[0]['manufacturer'],$itemDetails[0]['model'],$itemDetails[0]['categoryname'],$itemDetails[0]['locationname'],$itemDetails[0]['sitename'],$this->session->userdata('objSystemUser')->firstname.' '.$this->session->userdata('objSystemUser')->lastname,'Maneger Name',  $this->input->post('compliance_check_name'),date('d/m/Y'),date('h:i:s'));
            }
            
            $temp = explode(',', $this->input->post('failedChecks'));
            if ($temp[0] != '') {
                foreach ($temp as $key => $value) {
                    $failed[] = explode('|', $value);
                }
                foreach ($failed as $key => $value) {
                    $message .= '<tr><td>'.(int) $value[0].'</td><td>Fail</td><td>'.$value[1].'</td></tr>';
                }
            }
            
            $message .= '</table></li></ul>';
            
            $config['mailtype'] = 'html';
            $this->load->library('email',$config);

            $this->email->from('info@iworkaudit.com.au', 'Compliance Team');
//            $this->email->to('mayank.shakalya@gmail.com');
            $this->email->to($email_address);

            $this->email->subject('Compliance Failed Alert');
            $this->email->message('This mail is to notify you about fail of compliance');

            $this->email->send();
        }
        else {
        }
        
        $data = $this->tests_model->recordCheck($this->input->post());
        $this->session->set_userdata('booCourier', true);
        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully added')));
        redirect('/compliance/log/'.$this->input->post('item_id'), 'refresh');
       }
    
    
    public function addModifiedTemplate() {
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
            
                if($this->input->post()) {
                    $this->tests_model->addModifiedTemplate($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Template was successfully Added')));
                    redirect('/compliance/templates', 'refresh');
                }
        } else {
            
        }
              
    }
    public function editMultiTemplates() {
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
            
                if($this->input->post()) {
                    $this->tests_model->updateMultiTemplates($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Template(s) was/were successfully updated')));
                    redirect('/compliance/templates', 'refresh');
                }
        } else {
            
        }
              
    }
    
    public function getreport(){
        $this->load->model('tests_model');
//        var_dump($this->input->post());
        $data = array();
        
        
        $temp0 = explode('/', $this->input->post('from_date'));
        $temp1 = explode('/', $this->input->post('to_date'));
        $from = $temp0[2].'-'.$temp0[1].'-'.$temp0[0];
        $to = $temp1[2].'-'.$temp1[1].'-'.$temp1[0].' 11:59:00';
        
        if( $this->input->post('item_barcode') == '0' && $this->input->post('manufacturer_items') == '0')
        {
            $items = explode(',', $this->input->post('item_selected'));
        }
        else{
            $items = $this->input->post('item_barcode');
        }
        if( $this->input->post('manufacturer_items') != '0')
        {
            $items = explode(',', $this->input->post('manufacturer_items'));
        }
        $header = array(0=>'Compliance Name',1=>'Category',2=>'Frequency',3=>'Manager',4=>'QR Code',5=>'Manufacturer',6=>'Model',7=>'Owner',8=>'Location',9=>'Site',10=>'Logged By',11=>'Due Date',12=>'Complete Date',13=>'Complete Time',14=>'Result');
        $history = $this->tests_model->getComplianceHistoryReport((int)$this->input->post('check_name'),$to,$from,$items);
        foreach ($history as $key => $value) {
                
                $task_results = array();
                
                $history[$key]['location_name'] = $this->tests_model->getLocationforHistory($value['test_item_id'],$value['test_date']);
                $history[$key]['owner_name'] = $this->tests_model->getOwnerNameforHistory($value['test_item_id'],$value['test_date']);
                $history[$key]['site_name'] = $this->tests_model->getSiteNameforHistory($value['test_item_id'],$value['test_date']);
                $history[$key]['manager'] = $this->tests_model->getManagerforHistory($value['test_item_id'],$value['test_date']);
                $history[$key]['test_type_name'] = $this->tests_model->getComplianceNameforHistory($value['test_item_id'],$value['test_date']);
                $history[$key]['name'] = $this->tests_model->getCategoryforHistory($value['test_item_id'],$value['test_date']);

                $history[$key]['total_tasks'] = $this->tests_model->getTaskCount($value['test_date']);
                
                $history[$key]['tasks'] = $this->tests_model->getTaskCount($value['test_date'],'details');
                if((int)$value['test_type_id'] == (int)$this->input->post('check_name')){
                    $result = true;
//                    var_dump($history[$key]['tasks']);
                    foreach ($history[$key]['tasks'] as $k => $v) {


                        if($v['result'] == '0'){
                            $task_results[] = 'Fail';
                            $result = false;
                        }elseif($v['result'] == '1'){
                            $task_results[] = 'Pass';
                        }else{
                            $task_results[] = $v['result'];

                        }

                    }
                    $due_date = 'NA';
                    if($value['due_date'])
                    {
                        $due_date = $value['due_date'];
                    }
                    if($result)
                    {
                        $result = 'Pass';
                    }else
                    {
                        $result = 'Fail';
                    }
                    $test_date = date('Y-m-d',strtotime($value['test_date']));
                    if(date('A',strtotime($value['test_date'])) == 'AM'){
                        $mer = 'PM';
                    }else{
                        $mer = 'AM';
                    }
                    $test_time = date('h:i',strtotime($value['test_date'])).' '.$mer;
                    $comp_details = array(0=>$history[$key]['test_type_name'],1=>$history[$key]['name'],2=>$value['frequency_name'],3=>$history[$key]['manager'],4=>$value['barcode'],5=>$value['manufacturer'],6=>$value['model'],7=>$history[$key]['owner_name'],8=>$history[$key]['location_name'],9=>$history[$key]['site_name'],10=>$value['test_person'],11=>$due_date,12=>$test_date,13=>$test_time,14=>$result);
                    $data[] = array_merge($comp_details,$task_results);
                }
        }
//        var_dump($history);
//        var_dump($data);
//        die;
        if($history == false){
            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('There is No Data to Show')));
            redirect('/compliance/report', 'refresh');
        }
        else{
            $header_part = array();
            $header_part_names = array();
            foreach ($header as $key => $value) {
                array_push($header_part_names,'');
            }
//            $header_part_names = array(0=>'',1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'');
            foreach ($history[0]['tasks'] as $key => $value) {
//                var_dump($value['type_of_task']);
                
                if((int)$value['type_of_task'] == 0)
                {
//                    echo 'Task Name';
                    array_push($header_part,'Task Name');
                    array_push($header_part_names,$value['task_name']);
                }
                else
                {
//                    echo 'Task Measurement Name';
                    array_push($header_part,'Task Numerical Name');
                    array_push($header_part_names,$value['task_name']);
                }
                
            }
            
            $header = array_merge($header,$header_part);
//            $header_part = implode(',',$header_part);
//            $header_part_names = implode(',',$header_part_names);
            $final_header = array(0=>$header,1=>$header_part_names);
//            var_dump($final_header);die;
            $final_data = array_merge($final_header,$data);
//            var_dump($final_data);die;
            $this->load->helper('csv');
            getcsv($final_data,"Compliance Report by Check.csv"); 
        }
    }
    public function archieve($id) {
        $this->db->where('test_type_id',$id)->update('test_type',array('archieved'=>1));
        $this->session->set_userdata('booCourier', true);
        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Check was Successfully Deleted')));
        redirect('/compliance/complianceslist', 'refresh');
    }
    
    public function historyRecorder() {
        $tests = $this->db->limit(0,500)->get('tests');
        $tests = $tests->result_array();
        $temp = array();
//        var_dump($tests);
        $due_on = date('Y-m-d',  strtotime('now'));
        foreach ($tests as $key => $value) {
//            var_dump($value);
            $com = $this->db->select('*')->from('test_type as tt')->join('users', 'tt.manager_of_check = users.id', 'left')->where('test_type_id',$value['test_type'])->get();
            $com = $com->result_array();
//            var_dump($com);
            $manager = $com[0]['firstname'].' '.$com[0]['lastname'];
            $this->db->select('items.owner_since AS \'date\',
			      items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode,
			      categories.id AS categoryid, categories.name AS categoryname,
			      users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
			      locations.id AS locationid, locations.name AS locationname,
                              sites.id AS siteid, sites.name AS sitename');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->where('items.id', $value['test_item_id']);

            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                $temp1 = $resQuery->result();
//                var_dump($temp1);
                $temp = array('test_type'=>$value['test_type'],'test_date'=>$value['test_date'],'test_item_id'=>$value['test_item_id'],'test_notes'=>$value['test_notes'],'test_person'=>$value['test_person'],'test_category'=>$temp1[0]->categoryname,'test_owner'=>$temp1[0]->userfirstname.' '.$temp1[0]->userlastname,'test_location'=>$temp1[0]->locationname,'test_site'=>$temp1[0]->sitename,'test_manager'=>$manager,'test_compliance_name'=>$com[0]['test_type_name'],'result'=>$value['result'],'test_notify'=>0,'due_on'=>$due_on,'account_id'=>$this->session->userdata('objSystemUser')->accountid);
//                $this->db->insert('tests_history',$temp);
            }
        }
        
//        var_dump($temp);
                
    }
    public function historyAccountidRecorder() {
        $items = $this->db->select('id,account_id')->limit(500,4001)->get('items');
        $items = $items->result_array();
//        var_dump($items);
        foreach ($items as $key => $value) {
//            $this->db->where('test_item_id',$value['id'])->update('tests_history',array('account_id'=>$value['account_id']));
        }
    }

}
