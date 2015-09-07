<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Youaudit_Admins extends CI_Controller {

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

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $this->dashboard();
    }

    public function login() {
        $arrPageData = array();
        $arrPageData['arrSessionData'] = $this->session->userdata;

//        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Youaduit Admin Login";


        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

        $this->load->model('youaudit_admins_model');

        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username/Email Address', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run()) {
                $arrInput = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password'),
                    'active' => 1
                );


                $arrAdminData = $this->youaudit_admins_model->logIn($arrInput);

                if ($arrAdminData['booSuccess']) {
                    $this->session->set_userdata('sysAdminUserName', $arrAdminData['result'][0]['username']);
                    $this->session->set_userdata('booSystemAdminLogin', TRUE);
                    redirect('youaudit/pincodeAuthentication', 'refresh');
                } else {
                    $this->session->set_userdata('booSystemAdminLogin', FALSE);
                    $this->session->set_flashdata('arrCourier', 'Incorrect username and password');
                    redirect('youaudit/login', 'refresh');
                }
            }
        }
        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/login', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function pincodeAuthentication() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "SysAdmin Pin Number";




        if ($this->input->post()) {

            $this->load->model('youaudit_admins_model');
            $this->form_validation->set_rules('pin_number', 'Pin Number', 'trim|required|md5');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run()) {
                $arrInput = array(
                    'pin_number' => $this->input->post('pin_number'),
                    'username' => $this->input->post('username')
                );


                $arrAdminData = $this->youaudit_admins_model->pincodeAuthentication($arrInput);

                if ($arrAdminData['success']) {
                    $this->session->set_userdata('YouAuditSystemAdmin', $arrAdminData['result'][0]);
                    $this->session->set_flashdata('success', 'Well done! You were successfully logged in');
                    redirect('youaudit/dashboard', 'refresh');
                } else {

                    $this->session->set_flashdata('arrCourier', 'Incorrect pin number');
                    redirect('youaudit/login', 'refresh');
                }
            }
        }
        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/pincode_authentication', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function dashboard() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        // housekeeping
        $arrPageData = array();

        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Youaudit_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/dashboard';
        $arrPageData['arrPageParameters']['strPage'] = "Youaudit Dashboard";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();



        $this->load->model('youaudit_admins_model');


        $acc_master = $this->youaudit_admins_model->recent_masteraccounts();
        $acc_franchise = $this->youaudit_admins_model->recent_franchiseaccounts();

        $arr = array_merge($acc_master, $acc_franchise);
        usort($arr, array($this, "sortDate"));



        $latest_news = $this->youaudit_admins_model->getLastNews();
        $master = $this->youaudit_admins_model->get_masterdata();
        $franchise = $this->youaudit_admins_model->get_franchisedata();

        $summary = array_merge($master, $franchise);
        $arrPageData['recent_accounts'] = $arr;
        $arrPageData['summary'] = $summary;



        if ($latest_news) {
            $arrPageData['latest_news'] = $latest_news[0];
        }

        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/dashboard', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // sort date in decreasing order
    private function sortDate($val1, $val2) {
        if ($val1['created'] == $val2['created']) {
            return 0;
        }

        return ($val1['created'] > $val2['created']) ? -1 : 1;
    }

    // Action For Show MasterAccount.
    public function masterAccount() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();

        $arrPageData['arrPageParameters']['strPage'] = "Youaudit Master Account";
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Youaudit_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/dashboard';
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/masteraccount', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Show Franchise Account.
    public function franchisesAccount() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();

        $arrPageData['arrPageParameters']['strPage'] = "Youaudit Franchise Account";
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Youaudit_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/dashboard';
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/franchisesaccount', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Show AdminList
    public function adminlist() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }


        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Youaudit_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/dashboard';
        $arrPageData['arrPageParameters']['strPage'] = "Youaudit Admins";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/adminlist', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Show AdminList
    public function archive() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }


        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Youaudit_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/dashboard';
        $arrPageData['arrPageParameters']['strPage'] = "Youaudit Archive Account";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/archive', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Add Master Account.
    public function addmasterAccount() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            $this->form_validation->set_rules('contact_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('pin_number', 'Pin', 'trim|md5');
            $this->form_validation->set_rules('contact_username', 'Username', 'is_unique[master_ac.username]');
            $this->form_validation->set_rules('contact_username', 'Username', 'is_unique[franchise_ac.username]');

            if ($this->form_validation->run()) {
                $arrMasterAcc = array(
                    'sys_admin_name' => $this->input->post('sys_admin_name'),
                    'company_name' => $this->input->post('company_name'),
                    'contact_name' => $this->input->post('contact_name'),
                    'firstname' => $this->input->post('first_name'),
                    'lastname' => $this->input->post('last_name'),
                    'email' => $this->input->post('contact_email'),
                    'phone' => $this->input->post('contact_phone'),
                    'username' => $this->input->post('contact_username'),
                    'password' => $this->input->post('contact_password'),
                    'pin_number' => $this->input->post('pin_number'),
                    'account_limit' => $this->input->post('account_limit'),
                    'active' => 1
                );


                if (!$this->input->post('report_allow') == false) {
                    $arrMasterAcc['enable_report'] = 1;
                }

                // Load Model
                $result = $this->youaudit_admins_model->addMasterAccount($arrMasterAcc);
                if ($result) {
                    $this->session->set_flashdata('success', 'Master Account Added Successfully');
                }
                if ($this->input->post("master") == 1) {

                    redirect("youaudit/dashboard", 'refresh');
                } else {
                    redirect("youaudit/masterAccount", 'refresh');
                }
            } else {
                $this->session->set_flashdata('error', 'Master Username Should Be Unique');
                if ($this->input->post("master") == 1) {

                    redirect("youaudit/dashboard", 'refresh');
                } else {
                    redirect("youaudit/masterAccount", 'refresh');
                }
            }
        }
    }

    // Action For Add Master Account.
    public function editMasterAccount() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');
            $editMasterAcc = array(
                'sys_admin_name' => $this->input->post('edit_sys_admin_name'),
                'company_name' => $this->input->post('edit_company_name'),
                'contact_name' => $this->input->post('edit_contact_name'),
                'email' => $this->input->post('edit_contact_email'),
                'phone' => $this->input->post('edit_contact_phone'),
                'account_limit' => $this->input->post('edit_account_limit'),
                'master_id' => $this->input->post('edit_master_id'),
            );
            if (!$this->input->post('edit_report_allow') == false) {
                $editMasterAcc['enable_report'] = 1;
            } else {
                $editMasterAcc['enable_report'] = 0;
            }
            $result = $this->youaudit_admins_model->editMasterAccount($editMasterAcc);

            if ($result) {
                $this->session->set_flashdata('success', 'Master Account Edit Successfully');
                redirect("youaudit/masterAccount", 'refresh');
            }
        }
    }

    // Action For Add Franchises Account.
    public function addfranchiseAccount() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');
            $this->form_validation->set_rules('contact_password_franchise', 'Password', 'trim|md5');
            $this->form_validation->set_rules('pin_number', 'Pin', 'trim|md5');
            $this->form_validation->set_rules('contact_username_franchises', 'Username', 'is_unique[franchise_ac.username]');
            $this->form_validation->set_rules('contact_username_franchises', 'Username', 'is_unique[master_ac.username]');

            if ($this->form_validation->run()) {
                $arrFranchiseAcc = array(
                    'sys_franchise_name' => $this->input->post('sys_franchise_name'),
                    'company_name' => $this->input->post('company_name'),
                    'contact_name' => $this->input->post('contact_name'),
                    'email' => $this->input->post('contact_email'),
                    'firstname' => $this->input->post('first_name'),
                    'lastname' => $this->input->post('last_name'),
                    'phone' => $this->input->post('contact_phone'),
                    'username' => $this->input->post('contact_username_franchises'),
                    'password' => $this->input->post('contact_password_franchise'),
                    'pin_number' => $this->input->post('pin_number'),
                    'account_limit' => $this->input->post('account_limit'),
                    'active' => 1
                );
                $result = $this->youaudit_admins_model->addFranchiseAccount($arrFranchiseAcc);
                if ($result) {
                    $this->session->set_flashdata('success', 'Franchises Account Added Successfully');
                }
                if ($this->input->post("franchise") == 1) {
                    redirect("youaudit/dashboard", "refresh");
                } else {
                    redirect("youaudit/franchisesAccount", "refresh");
                }
            } else {
                $this->session->set_flashdata('error', 'Franchise Username Should Be Unique');
                if ($this->input->post("franchise") == 1) {
                    redirect("youaudit/dashboard", "refresh");
                } else {
                    redirect("youaudit/franchisesAccount", "refresh");
                }
            }
        }
    }

    //add Server side datatable

    public function getMasterAccountData() {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("master_ac.id", "master_ac.sys_admin_name", "master_ac.company_name", "master_ac.contact_name", "master_ac.email", "master_ac.phone", "master_ac.username", "master_ac.account_limit", "master_ac.total_amount", "master_ac.active");

        $query = "select master_ac.id,master_ac.sys_admin_name,master_ac.company_name,master_ac.contact_name,master_ac.email,master_ac.phone,master_ac.username,master_ac.account_limit,master_ac.total_amount,master_ac.active,systemadmin_master.id As account_id 
                  FROM master_ac 
                  left join systemadmin_master on master_ac.username=systemadmin_master.username where master_ac.archive=1";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" where ( sys_admin_name REGEXP '$words'
                          OR company_name REGEXP '$words'
                          OR contact_name REGEXP '$words'
                          OR email REGEXP '$words'
                          OR phone REGEXP '$words'
                          OR master_ac.username REGEXP '$words'
                          OR account_limit REGEXP '$words'
                          OR total_amount REGEXP '$words' ) ";
        }
//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if ($_GET['sSearch_3'] == "") {

//            $words = "Active,On Hold,Completed";
            $query .="";
        }
        if (isset($_GET['iSortCol_0'])) {
            $query .= " ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $query = substr_replace($query, "", -2);
            if ($query == "ORDER BY") {
                $query .= "";
            }
        }

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
        $res = $this->db->query($query_res);
        $count_res = $this->db->query($query);
        $result = $res->result_array();
        $count_result = $count_res->result_array();
        $total_record = count($count_result);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $total_record,
            "iTotalDisplayRecords" => $total_record,
            "aaData" => array()
        );
//echo $query;
//      $result = $records->result_array();

        $i = 0;
        $final = array();
        $loggedin_id = $this->session->userdata('objSystemUser');
        foreach ($result as $val) {
            if ($val['active'] == 1) {
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableMasterAccount/' . $val['account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableMasterAccount/' . $val['account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }
            $amount = $this->db->select('sum(annual_value) as amount')->where(array('account_id' => $val['id'], 'account_type' => 1))->get('accounts')->result_array();
            if ($amount[0]['amount'] != null) {
                $total_amount = $amount[0]['amount'];
            } else {
                $total_amount = 0;
            }

            $output['aaData'][] = array("DT_RowId" => $val['id'], '<input type="checkbox" id="master_check_id' . $val['id'] . '" class="multiComSelect" value=' . $val['id'] . ' name="master_check_id[]"><input class="" type="hidden" id="customer_id_' . $val['id'] . '" value="">', '<a  href="' . base_url('youaudit/customerlist/' . $val['id']) . '">' . $val['sys_admin_name'] . '</a>', $val['company_name'], $val['contact_name'], $val['email'], $val['phone'], $val['username'], $val['account_limit'], $total_amount, '<span class="action-w"><a  href="' . base_url('youaudit/customerlist/' . $val['id']) . '"><i class="glyphicon glyphicon-download franchises-i" title="inherit_account"></i></a>Inherit</span><span class="action-w"><a data-toggle="modal"   class="editmasteracc" data_master_id="' . $val['id'] . '" id="editmasteracc" href="#edit_add_master"  title="Edit"><i class="glyphicon glyphicon-edit franchises-i" title="Edit"></i></a>Edit</span><span class="action-w"><a data-toggle="modal"   id="changesPassword" href="#changepassword" class="change_master_password" data_master_id="' . $val['id'] . '" data_sys_admin_name="' . $val['sys_admin_name'] . '" data_user_name="' . $val['username'] . '"  title="Password"><i class="glyphicon glyphicon-lock franchises-i"></i></a>Password</span>' . $access_icon . '<span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('youaudit/archiveMaster/' . $val['id']) . '"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span>');
        }

        echo json_encode($output);
        die;
    }

// Archive System Admin User
    public function archiveMaster($admin_id) {
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->archive_Master($admin_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Master Account Archived Successfully');
            redirect("youaudit/masterAccount", "refresh");
        }
    }

    // Archive System Admin User
    public function archiveFranchise($admin_id) {
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->archive_Franchise($admin_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Account Archived Successfully');
            redirect("youaudit/franchisesAccount", "refresh");
        }
    }

// Archive System Admin User
    public function archiveAdmin($admin_id) {
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->archive_admin($admin_id);
        if ($result) {
            $this->session->set_flashdata('success', 'System Admin Archived Successfully');
            redirect("youaudit/adminlist", "refresh");
        }
    }

    // get franchise data for datatable
    public function getFranchiseAccountData() {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("franchise_ac.id", "franchise_ac.sys_franchise_name", "franchise_ac.company_name", "franchise_ac.contact_name", "franchise_ac.email", "franchise_ac.phone", "franchise_ac.username", "franchise_ac.account_limit", "franchise_ac.total_amount", "franchise_ac.active");

        $query = "select franchise_ac.id ,franchise_ac.sys_franchise_name,franchise_ac.company_name,franchise_ac.contact_name,franchise_ac.email,franchise_ac.phone,franchise_ac.username,franchise_ac.account_limit,franchise_ac.total_amount,franchise_ac.active 
                  FROM franchise_ac left join systemadmin_franchise on franchise_ac.username=systemadmin_franchise.username where franchise_ac.archive=1
                  ";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" where ( sys_franchise_name REGEXP '$words'
                          OR company_name REGEXP '$words'
                          OR contact_name REGEXP '$words'
                          OR email REGEXP '$words'
                          OR phone REGEXP '$words'
                          OR franchise_ac.username REGEXP '$words'
                          OR account_limit REGEXP '$words'
                          OR total_amount REGEXP '$words' ) ";
        }
//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if ($_GET['sSearch_3'] == "") {

//            $words = "Active,On Hold,Completed";
            $query .="";
        }
        if (isset($_GET['iSortCol_0'])) {
            $query .= " ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $query = substr_replace($query, "", -2);
            if ($query == "ORDER BY") {
                $query .= "";
            }
        }

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
        $res = $this->db->query($query_res);
        $count_res = $this->db->query($query);
        $result = $res->result_array();
        $count_result = $count_res->result_array();
        $total_record = count($count_result);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $total_record,
            "iTotalDisplayRecords" => $total_record,
            "aaData" => array()
        );
//echo $query;
//        $result = $records->result_array();

        $i = 0;
        $final = array();
        $loggedin_id = $this->session->userdata('objSystemUser');
        foreach ($result as $val) {
            if ($val['active'] == 1) {
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableFranchiseAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableFranchiseAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser "><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }


            $amount = $this->db->select('sum(annual_value) as amount')->where(array('account_id' => $val['id'], 'account_type' => 2))->get('accounts')->result_array();
            if ($amount[0]['amount'] != null) {
                $total_amount = $amount[0]['amount'];
            } else {
                $total_amount = 0;
            }

            $output['aaData'][] = array("DT_RowId" => $val['id'], '<input type="checkbox" id="franchise_check_id' . $val['id'] . '" class="multiComSelect" value=' . $val['id'] . ' name="franchise_check_id[]"><input class="" type="hidden" id="customer_id_' . $val['id'] . '" value="">', '<a  href="' . base_url('youaudit/franchise_customerlist/' . $val['id']) . '">' . $val['sys_franchise_name'] . '</a>', $val['company_name'], $val['contact_name'], $val['email'], $val['phone'], $val['username'], $val['account_limit'], $total_amount, '<span class="action-w"><a  href="' . base_url('youaudit/franchise_customerlist/' . $val['id']) . '"><i class="glyphicon glyphicon-download franchises-i" title="inherit_account"></i></a>Inherit</span><span class="action-w"><a href="#edit_franchise_master" class="editfranchiseacc" data_franchise_id="' . $val['id'] . '" data-toggle="modal" id="editfranchise" class="editfranchise"><i class="glyphicon glyphicon-edit franchises-i" title="Edit"></i></a>Edit</span><span class="action-w"><a data-toggle="modal"   id="changesPassword" href="#changepassword" class="change_franchise_password" data_franchise_id="' . $val['id'] . '" data_system_franchise_name="' . $val['sys_franchise_name'] . '" data_user_name="' . $val['username'] . '"  title="Password">
<i class="glyphicon glyphicon-lock franchises-i"></i></a>Password</span>' . $access_icon . '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('youaudit/archiveFranchise/' . $val['id']) . '"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span>');
        }


        echo json_encode($output);
        die;
    }

    // Add  System Admin Account 
    public function addSystemAccount() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');
            $this->form_validation->set_rules('user_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $arrSystemAc = array(
                    'firstname' => $this->input->post('first_name'),
                    'lastname' => $this->input->post('last_name'),
                    'password' => $this->input->post('user_password'),
                    'username' => $this->input->post('username'),
                    'pin_number' => $this->input->post('pin_number'),
                    'active' => 1,
                    'archive' => 1
                );


                $result = $this->youaudit_admins_model->addSystemAccount($arrSystemAc);
                if ($result) {
                    $this->session->set_flashdata('success', 'System User Added Successfully');
                    redirect("youaudit/adminlist", "refresh");
                }
            }
        }
    }

    // get System Admin Data For DataTable
    public function getSystemAccountData() {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $id = $arrPageData['arrSessionData']["YouAuditSystemAdmin"]['id'];

        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("systemadmins.id", "systemadmins.firstname", "systemadmins.lastname", "systemadmins.username", "systemadmins.active");

        $query = "select id ,firstname,lastname,username,active
                  FROM systemadmins where archive=1 ";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" where ( firstname REGEXP '$words'
                          OR lastname REGEXP '$words'
                          OR username REGEXP '$words'
                         
                          ) ";
        }
//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if ($_GET['sSearch_3'] == "") {

//            $words = "Active,On Hold,Completed";
            $query .="";
        }
        if (isset($_GET['iSortCol_0'])) {
            $query .= "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $query = substr_replace($query, "", -2);
            if ($query == "ORDER BY") {
                $query .= "";
            }
        }

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
        $res = $this->db->query($query_res);
        $count_res = $this->db->query($query);
        $result = $res->result_array();
        $count_result = $count_res->result_array();
        $total_record = count($count_result);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $total_record,
            "iTotalDisplayRecords" => $total_record,
            "aaData" => array()
        );
//echo $query;
//        $result = $records->result_array();

        $i = 0;
        $final = array();
        $loggedin_id = $this->session->userdata('objSystemUser');
        foreach ($result as $val) {
            if ($val['id'] != $id) {
                if ($val['active'] == 1) {
                    $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableSystemAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
                } else {
                    $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableSystemAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
                }
                $archive_icon = '<span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)"  id="archiveuser_id_' . $val['id'] . '" data-href="' . base_url('youaudit/youaudit_admins/archiveSystemAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="Archive" class="archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span>';
            }
            $output['aaData'][] = array("DT_RowId" => $val['id'], $val['firstname'], $val['lastname'], $val['username'], '<span class="action-w"><a data-toggle="modal"   id="changesPassword" href="#changepassword" class="change_system_user_password" data_system_userid=' . $val['id'] . '  title="Password"><i class="glyphicon glyphicon-lock franchises-i"></i></a>Password</span><span class="action-w"><a data-toggle="modal"   id="edit_sytem_user" class="edit_system_user" href="#edit_system_admin" data_system_userid=' . $val['id'] . ' data_firstname=' . $val['firstname'] . ' data_lastname=' . $val['lastname'] . ' data_username=' . $val['username'] . '><i class="glyphicon glyphicon-edit franchises-i" title="Edit"></i></a>Edit</span>' . $access_icon . '' . $archive_icon);
        }

        echo json_encode($output);
        die;
    }

    // Edit  System Admin Account 
    public function editSystemAccount() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');


            $arrEditSystemAc = array(
                'firstname' => $this->input->post('edit_first_name'),
                'lastname' => $this->input->post('edit_last_name'),
                'system_id' => $this->input->post('system_id')
            );

            $result = $this->youaudit_admins_model->editSystemAccount($arrEditSystemAc);
            if ($result) {
                $this->session->set_flashdata('success', 'System User Edit Successfully');
                redirect("youaudit/adminlist", "refresh");
            }
        }
    }

    // Get Master Edit Data
    public function get_edit_masterdata() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');
            $master_id = $this->input->post('id');
            $result = $this->youaudit_admins_model->get_edit_masterdata($master_id);
            echo json_encode($result);
            die;
        } else {
            echo "";
        }
    }

    //Get Franchise Edit Data
    public function get_edit_franchisedata() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');
            $franchise_id = $this->input->post('id');
            $result = $this->youaudit_admins_model->get_edit_franchisedata($franchise_id);
            echo json_encode($result);
            die;
        } else {
            echo "";
        }
    }

    // Edit Franchise Acc
    public function editFranchiseAccount() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('youaudit_admins_model');
            $editFranchiseAcc = array(
                'company_name' => $this->input->post('edit_company_name'),
                'contact_name' => $this->input->post('edit_contact_name'),
                'email' => $this->input->post('edit_contact_email'),
                'phone' => $this->input->post('edit_contact_phone'),
                'account_limit' => $this->input->post('edit_account_limit'),
                'franchise_id' => $this->input->post('edit_franchise_id'),
            );


            $result = $this->youaudit_admins_model->editFranchiseAccount($editFranchiseAcc);

            if ($result) {
                $this->session->set_flashdata('success', 'Franchise Account Edit Successfully');
                redirect("youaudit/franchisesAccount", 'refresh');
            }
        }
    }

    // Change Franchise Master
    public function changeFranchisePassword() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('youaudit_admins_model');
            $this->form_validation->set_rules('new_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('update_pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $changePassword = array(
                    'new_password' => $this->input->post('new_password'),
                    'new_pin_number' => $this->input->post('update_pin_number'),
                    'franchise_account_id' => $this->input->post('change_franchise_id'),
                    'username' => $this->input->post('franchiseusername'),
                );
                if ($changePassword['new_password'] != '' || $changePassword['new_pin_number'] != '') {

                    $result = $this->youaudit_admins_model->changeFranchisePassword($changePassword);
                    if ($result) {
                        $this->session->set_flashdata('success', 'Franchise User Password & Pin Change Successfully');
                        redirect("youaudit/franchisesAccount", "refresh");
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password & Pin Number Could Not Update Successfully');
                    redirect("youaudit/franchisesAccount", "refresh");
                    ;
                }
            }
        }
    }

    // Action For Change Admin User Password.
    public function changeMasterUserPassword() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('youaudit_admins_model');
            $this->form_validation->set_rules('new_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('update_pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $changePassword = array(
                    'new_password' => $this->input->post('new_password'),
                    'new_pin_number' => $this->input->post('update_pin_number'),
                    'master_account_id' => $this->input->post('change_master_id'),
                    'username' => $this->input->post('masterusername')
                );
                if ($changePassword['new_password'] != '' || $changePassword['new_pin_number'] != '') {

                    $result = $this->youaudit_admins_model->changeMasterUserPassword($changePassword);

                    if ($result) {
                        $this->session->set_flashdata('success', 'Master User Password & Pin Change Successfully');
                        redirect("youaudit/masterAccount", "refresh");
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password & Pin Number Could Not Update Successfully');
                    redirect("youaudit/masterAccount", "refresh");
                }
            }
        }
    }

    // Action For Change Admin User Password.
    public function changeSystemAdminPassword() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('youaudit_admins_model');
            $this->form_validation->set_rules('new_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('update_pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $changePassword = array(
                    'new_password' => $this->input->post('new_password'),
                    'new_pin_number' => $this->input->post('update_pin_number'),
                    'system_account_id' => $this->input->post('system_id_password'),
                );

                if ($changePassword['new_password'] != '' || $changePassword['new_pin_number'] != '') {
                    $result = $this->youaudit_admins_model->changeSystemAdminPassword($changePassword);
                    if ($result) {

                        $this->session->set_flashdata('success', 'System User Password & Pin Change Successfully');
                        redirect("youaudit/adminlist", "refresh");
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password & Pin Number Could not update Successfully');
                    redirect("youaudit/adminlist", "refresh");
                }
            }
        }
    }

    // Check Username For System Admin
    public function check_username_systemadmin() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('youaudit_admins_model');
        $res = $this->youaudit_admins_model->checkSystemAdminUsername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    // Action For Change Admin User Password.

    public function disableMasterAccount($userid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->disableMasterAccount($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'Master Admin User Disable Successfully');
            redirect("youaudit/masterAccount", "refresh");
        }
    }

    // Action For Enaable Admin User Account.
    public function enableMasterAccount($userid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->enableMasterAccount($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'Master Admin User Enable Successfully');
            redirect("youaudit/masterAccount", "refresh");
        }
    }

    // Action For Disable Admin User Account.

    public function disableFranchiseAccount($franchise_id) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->disableFranchiseAccount($franchise_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Admin User Disable Successfully');
            redirect("youaudit/franchisesAccount", "refresh");
        }
    }

    // Action For Enable Admin User Account.
    public function enableFranchiseAccount($franchise_id) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->enableFranchiseAccount($franchise_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Admin User Enable Successfully');
            redirect("youaudit/franchisesAccount", "refresh");
        }
    }

    // Action For Disable System User .

    public function disableSystemAccount($userid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->disableSystemAccount($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'System Admin User Disable Successfully');
            redirect("youaudit/adminlist", "refresh");
        }
    }

    // Action For Enaable Admin User Account.
    public function enableSystemAccount($userid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->enableSystemAccount($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'System Admin User Enable Successfully');
            redirect("youaudit/adminlist   ", "refresh");
        }
    }

    public function check_sysAdminName() {

        $this->load->model('youaudit_admins_model');
        $res = $this->youaudit_admins_model->checkSysAdminName(trim($this->input->post('sys_admin_name')));
        echo $res;
        die;
    }

    // call ajax for check username For Master Account
    public function check_username() {

        $this->load->model('youaudit_admins_model');
        $res = $this->youaudit_admins_model->checkUsername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    // Check System Admin Name For Franchises Account
    public function check_sysAdminNameForFranchises() {
        $this->load->model('youaudit_admins_model');
        $res = $this->youaudit_admins_model->checkSysFranchiseName(trim($this->input->post('sys_franchises_name')));
        echo $res;
        die;
    }

    // call ajax for check username For Franchises Account
    public function check_username_franchies() {

        $this->load->model('youaudit_admins_model');
        $res = $this->youaudit_admins_model->checkUsernameFranchise(trim($this->input->post('username')));
        echo $res;
        die;
    }

    // Set News By System Admin
    function setNews() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {


            $this->load->helper('date');
            $this->load->model('youaudit_admins_model');


            $this->form_validation->set_rules('news_text', 'Text Required', 'required');
            if ($this->form_validation->run()) {

                $arrNews = array(
                    'news_text' => $this->input->post('news_text'),
                    'system_admin_id' => $this->session->userdata('sysAdminID'),
                    'create_date' => time()
                );
                $res = $this->youaudit_admins_model->setNews($arrNews);
                if ($res) {
                    $this->session->set_flashdata('success', 'News Added Successfully');
                    redirect("youaudit/dashboard", "refresh");
                }
            }
        }
    }

    // Action For Destroy Session Varible. 
    public function logout() {
        $this->session->unset_userdata(array('YouAuditSystemAdmin', 'sysAdminUserName', 'booSystemAdminLogin', 'arrSessionData'));
        $this->session->sess_destroy();

        // We need to set some user messages before redirect
//                    $this->session->set_flashdata('success', 'Well done! You were successfully logged out');
        redirect('youaudit/login/', 'refresh');
    }

    public function exportPdfForMaster($type = '') {

//                    $this->load->library('Mpdf');
//            $mpdf = new Pdf('en-GB', 'A4');
//            $mpdf->setFooter('{PAGENO} of {nb}');
//            $mpdf->WriteHTML($strHtml);
//            $mpdf->Output("YouAudit_" . date('Ymd_His') . ".pdf", "D");

        if ($type) {
            $this->load->model('youaudit_admins_model');
            $result = $this->youaudit_admins_model->exportPdfForMaster($type);
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function exportPdfForFranchise($type = '') {
        if ($type) {
            $this->load->model('youaudit_admins_model');
            $result = $this->youaudit_admins_model->exportPdfForFranchise($type);
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

// Action For Show PackageList
    public function packagelist() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }


        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Youaudit_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/dashboard';
        $arrPageData['arrPageParameters']['strPage'] = "Youaudit Packages";
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->model('youaudit_admins_model');
        $packages = $this->youaudit_admins_model->package_list();
        $arrPageData['packages'] = $packages;
        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/packagelist', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function package_limit($id) {
        $this->load->model('youaudit_admins_model');
        $packages = $this->youaudit_admins_model->package_name($id);
        echo json_encode($packages);
        die;
    }

    // Add Package
    public function add_package() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $this->load->model('youaudit_admins_model');
        $response = $this->youaudit_admins_model->addpackage();
        if ($response) {
            $this->session->set_flashdata('success', 'Package Added Successfully');
            redirect("youaudit/packagelist", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Package');
            redirect("youaudit/packagelist", "refresh");
        }
    }

// Edit package
    public function edit_package() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('youaudit_admins_model');

            $editPackage = array(
                'packagename' => $this->input->post('editpackage_name'),
                'editpackage_asset' => $this->input->post('editpackage_asset'),
                'adminuser_id' => $this->input->post('adminuser_id')
            );


            $result = $this->youaudit_admins_model->editPackage($editPackage);
            if ($result) {
                $this->session->set_flashdata('success', 'Package Edited Successfully');
                redirect("youaudit/packagelist", "refresh");
            } else {
                $this->session->set_flashdata('error', 'Package Could Not Edited Successfully OR Allocated To A Customer Account');
                redirect("youaudit/packagelist", "refresh");
            }
        }
    }

    // archive sytem admin
    public function archiveSystemAccount($userid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->archiveSystemAccount($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'System Admin User Archive Successfully');
            redirect("youaudit/adminlist", "refresh");
        }
    }

    public function editMultipleFranchiseAc() {
        $this->load->model('franchise_model');
        if ($this->input->post()) {

            $result = $this->franchise_model->editMultiple_FranchiseAc();

            if ($result) {
                $this->session->set_flashdata('success', 'Franchise Accounts Edit Successfully');
                redirect("youaudit/franchisesAccount", "refresh");
            } else {
                $this->session->set_flashdata('error', 'Franchise Accounts Could Not Edit.');
                redirect("youaudit/franchisesAccount", "refresh");
            }
        }
    }

    public function editMultipleMasterAc() {
        $this->load->model('master_model');
        if ($this->input->post()) {

            $result = $this->master_model->editMultiple_MasterAc();

            if ($result) {
                $this->session->set_flashdata('success', 'Master Accounts Edit Successfully');
                redirect("youaudit/masterAccount", "refresh");
            } else {
                $this->session->set_flashdata('error', 'Master Accounts Could Not Edit.');
                redirect("youaudit/masterAccount", "refresh");
            }
        }
    }

    //add Server side datatable For Archive Acc

    public function getArchiveMasterAccountData() {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("master_ac.id", "master_ac.sys_admin_name", "master_ac.company_name", "master_ac.contact_name", "master_ac.email", "master_ac.phone", "master_ac.username", "master_ac.account_limit", "master_ac.total_amount", "master_ac.active");

        $query = "select master_ac.id,master_ac.sys_admin_name,master_ac.company_name,master_ac.contact_name,master_ac.email,master_ac.phone,master_ac.username,master_ac.account_limit,master_ac.total_amount,master_ac.active,systemadmin_master.id As account_id 
                  FROM master_ac 
                  left join systemadmin_master on master_ac.username=systemadmin_master.username where master_ac.archive=0";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" where ( sys_admin_name REGEXP '$words'
                          OR company_name REGEXP '$words'
                          OR contact_name REGEXP '$words'
                          OR email REGEXP '$words'
                          OR phone REGEXP '$words'
                          OR master_ac.username REGEXP '$words'
                          OR account_limit REGEXP '$words'
                          OR total_amount REGEXP '$words' ) ";
        }
//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if ($_GET['sSearch_3'] == "") {

//            $words = "Active,On Hold,Completed";
            $query .="";
        }
        if (isset($_GET['iSortCol_0'])) {
            $query .= " ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $query = substr_replace($query, "", -2);
            if ($query == "ORDER BY") {
                $query .= "";
            }
        }

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
        $res = $this->db->query($query_res);
        $count_res = $this->db->query($query);
        $result = $res->result_array();
        $count_result = $count_res->result_array();
        $total_record = count($count_result);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $total_record,
            "iTotalDisplayRecords" => $total_record,
            "aaData" => array()
        );
//echo $query;
//      $result = $records->result_array();

        $i = 0;
        $final = array();
        $loggedin_id = $this->session->userdata('objSystemUser');
        foreach ($result as $val) {
            if ($val['active'] == 1) {
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableMasterAccount/' . $val['account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableMasterAccount/' . $val['account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }
            $amount = $this->db->select('sum(annual_value) as amount')->where(array('account_id' => $val['id'], 'account_type' => 1))->get('accounts')->result_array();
            if ($amount[0]['amount'] != null) {
                $total_amount = $amount[0]['amount'];
            } else {
                $total_amount = 0;
            }

            $output['aaData'][] = array("DT_RowId" => $val['id'], '<a  href="' . base_url('youaudit/customerlist/' . $val['id']) . '">' . $val['sys_admin_name'] . '</a>', $val['company_name'], $val['contact_name'], $val['username'], $val['account_limit'], $total_amount, '<span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('youaudit/restoreMaster/' . $val['id']) . '"  title="Archive"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>');
        }

        echo json_encode($output);
        die;
    }

    //  Archive System Admin User
    public function restoreMaster($master) {
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->restoreMaster($master);
        if ($result) {
            $this->session->set_flashdata('success', 'Master Account Restore Successfully');
            redirect("youaudit/archive", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Master Account Could Not Restore Successfully');
            redirect("youaudit/archive", "refresh");
        }
    }

    // get Archive franchise data for datatable 
    public function getArchiveFranchiseAccountData() {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("franchise_ac.id", "franchise_ac.sys_franchise_name", "franchise_ac.company_name", "franchise_ac.contact_name", "franchise_ac.email", "franchise_ac.phone", "franchise_ac.username", "franchise_ac.account_limit", "franchise_ac.total_amount", "franchise_ac.active");

        $query = "select franchise_ac.id ,franchise_ac.sys_franchise_name,franchise_ac.company_name,franchise_ac.contact_name,franchise_ac.email,franchise_ac.phone,franchise_ac.username,franchise_ac.account_limit,franchise_ac.total_amount,franchise_ac.active 
                  FROM franchise_ac left join systemadmin_franchise on franchise_ac.username=systemadmin_franchise.username where franchise_ac.archive=0
                  ";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" where ( sys_franchise_name REGEXP '$words'
                          OR company_name REGEXP '$words'
                          OR contact_name REGEXP '$words'
                          OR email REGEXP '$words'
                          OR phone REGEXP '$words'
                          OR franchise_ac.username REGEXP '$words'
                          OR account_limit REGEXP '$words'
                          OR total_amount REGEXP '$words' ) ";
        }
//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if ($_GET['sSearch_3'] == "") {

//            $words = "Active,On Hold,Completed";
            $query .="";
        }
        if (isset($_GET['iSortCol_0'])) {
            $query .= " ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $query = substr_replace($query, "", -2);
            if ($query == "ORDER BY") {
                $query .= "";
            }
        }

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
        $res = $this->db->query($query_res);
        $count_res = $this->db->query($query);
        $result = $res->result_array();
        $count_result = $count_res->result_array();
        $total_record = count($count_result);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $total_record,
            "iTotalDisplayRecords" => $total_record,
            "aaData" => array()
        );
//echo $query;
//        $result = $records->result_array();

        $i = 0;
        $final = array();
        $loggedin_id = $this->session->userdata('objSystemUser');
        foreach ($result as $val) {
            if ($val['active'] == 1) {
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableFranchiseAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableFranchiseAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser "><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }


            $amount = $this->db->select('sum(annual_value) as amount')->where(array('account_id' => $val['id'], 'account_type' => 2))->get('accounts')->result_array();
            if ($amount[0]['amount'] != null) {
                $total_amount = $amount[0]['amount'];
            } else {
                $total_amount = 0;
            }

            $output['aaData'][] = array("DT_RowId" => $val['id'], '<a  href="' . base_url('youaudit/franchise_customerlist/' . $val['id']) . '">' . $val['sys_franchise_name'] . '</a>', $val['company_name'], $val['contact_name'], $val['username'], $val['account_limit'], $total_amount, '<span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="restorefranchise(this)" data-href="' . base_url('youaudit/restoreFranchise/' . $val['id']) . '"  title="Archive"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>');
        }


        echo json_encode($output);
        die;
    }

    //  Archive System franchise User
    public function restoreFranchise($franchise) {
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->restoreFranchise($franchise);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Account Restore Successfully');
            redirect("youaudit/archive", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Franchise Account Could Not Restore Successfully');
            redirect("youaudit/archive", "refresh");
        }
    }

    // get System Admin Data For DataTable
    public function getArchibeSystemAccountData() {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $id = $arrPageData['arrSessionData']["YouAuditSystemAdmin"]['id'];

        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("systemadmins.id", "systemadmins.firstname", "systemadmins.lastname", "systemadmins.username", "systemadmins.active");

        $query = "select id ,firstname,lastname,username,active
                  FROM systemadmins where archive=0 ";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" where ( firstname REGEXP '$words'
                          OR lastname REGEXP '$words'
                          OR username REGEXP '$words'
                         
                          ) ";
        }
//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if ($_GET['sSearch_3'] == "") {

//            $words = "Active,On Hold,Completed";
            $query .="";
        }
        if (isset($_GET['iSortCol_0'])) {
            $query .= "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $query = substr_replace($query, "", -2);
            if ($query == "ORDER BY") {
                $query .= "";
            }
        }

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
        $res = $this->db->query($query_res);
        $count_res = $this->db->query($query);
        $result = $res->result_array();
        $count_result = $count_res->result_array();
        $total_record = count($count_result);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $total_record,
            "iTotalDisplayRecords" => $total_record,
            "aaData" => array()
        );
//echo $query;
//        $result = $records->result_array();

        $i = 0;
        $final = array();
        $loggedin_id = $this->session->userdata('objSystemUser');
        foreach ($result as $val) {

            $output['aaData'][] = array("DT_RowId" => $val['id'], $val['firstname'], $val['lastname'], $val['username'], '<span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="restoresystem(this)"  id="archiveuser_id_' . $val['id'] . '" data-href="' . base_url('youaudit/restoreSystemAccount/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="Archive" class="archive"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>');
        }

        echo json_encode($output);
        die;
    }

    // Archive System Admin User
    public function restoreSystemAccount($admin_id) {
        $this->load->model('youaudit_admins_model');
        $result = $this->youaudit_admins_model->restoreSystemAccount($admin_id);
        if ($result) {
            $this->session->set_flashdata('success', 'System Admin User Restore Successfully');
            redirect("youaudit/archive", "refresh");
        } else {
            $this->session->set_flashdata('error', 'System Admin User Could Not Restore Successfully');
            redirect("youaudit/archive", "refresh");
        }
    }

}
