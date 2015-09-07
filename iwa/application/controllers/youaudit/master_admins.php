<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_Admins extends CI_Controller {

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
        
    }

    // Showup all customer list

    public function customerList($account_id) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();

        // $arrPageData['arrPageParameters']['strSection'] = get_class();

        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Master_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Master Customer";
//        $arrPageData['arrPageParameters']['strPage'] = "Youaudit Master Account";
        if (isset($account_id)) {

            $this->load->model('master_model');
            $account_name = $this->master_model->getAccountName($account_id);
            $customer_package = $this->master_model->getCustomerPackage();

            $customer_data = $this->master_model->mastercustomerlist($account_id);
            $packages = $this->master_model->masterpackagelist($account_id);
            $profile = $this->master_model->inprofilelist($account_id);
            $arrData = array(
                'account_name' => $account_name[0]['company_name'],
                'customer_package' => $customer_package,
                'masterid' => $account_id,
                'packages' => $packages,
                'option' => $customer_data,
                'profilelist' => $profile,
            );

            $this->load->view('common/header', $arrPageData);
            $this->load->view('youaudit/admins/masteradmin/customerlist', $arrData);
            $this->load->view('common/footer', $arrPageData);
        }
    }

    // check uniqueness of qrcode
    public function checkQrcode() {

        $this->load->model('master_model');
        $res = $this->master_model->check_qrcode(trim($this->input->post('bar_code')));
        echo $res;
        die;
    }

//     Generate Random String For Account

    public function generateRandomString() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $refrenceCode = random_string('alnum', '4');
        $this->load->model('master_model');
        $res = $this->master_model->checkRefcode($refrenceCode);
        if ($res) {
            return $this->generateRandomString();
        } else {
            echo ($refrenceCode);
        }
    }

    // Load Admin User List
    public function adminuser($account_id = false) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();

//            $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Master_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Master Admin User";
        $this->load->model('master_model');
        $account_name = $this->master_model->getAccountName($account_id);

        $arrData = array(
            'account_name' => $account_name[0]['company_name'],
            'masterid' => $account_id
        );



        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/masteradmin/adminuser', $arrData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Add Master Admin User
    public function addAdminUser() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('master_model');
            $this->form_validation->set_rules('user_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $arrAdminUser = array(
                    'firstname' => $this->input->post('first_name'),
                    'lastname' => $this->input->post('last_name'),
                    'password' => $this->input->post('user_password'),
                    'nickname' => $this->input->post('contact_name'),
                    'username' => $this->input->post('username'),
                    'pin_number' => $this->input->post('pin_number'),
                    'master_account_id' => $this->input->post('masterid'),
                    'active' => '1'
                );

                $result = $this->master_model->addAdminUser($arrAdminUser);
                if ($result) {
                    $this->session->set_flashdata('success', 'Master Admin User Added Successfully');
                    redirect("youaudit/Adminuser/" . $arrAdminUser['master_account_id'], "refresh");
                }
            }
        }
    }

    // Action For Get Master Admin User
    public function getAdminUserData($master_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("systemadmin_master.id", "systemadmin_master.firstname", "systemadmin_master.lastname", "systemadmin_master.nickname", "systemadmin_master.username", "systemadmin_master.active", "systemadmin_master.master_account_id");

        $query = "select id ,firstname,lastname,nickname,username,active,master_account_id
                  FROM systemadmin_master WHERE active = 1 AND master_account_id =" . $master_id;

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" AND ( firstname REGEXP '$words'
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
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableadminuser/' . $val['id'] . '/' . $val['master_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableadminuser/' . $val['id'] . '/' . $val['master_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }


            $output['aaData'][] = array("DT_RowId" => $val['id'], $val['firstname'], $val['lastname'], $val['username'], '<span class="action-w"><a data-toggle="modal"   id="changepassword_id_' . $val['id'] . '" href="#change_password_model" data_adminuser_id=' . $val['id'] . ' class="change_password_model"  title="Change Password"><i class="glyphicon glyphicon-lock franchises-i"></i></a>Password</span><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_' . $val['id'] . '" href="#edit_admin_user_form" title="Edit" data_firstname=' . $val['firstname'] . ' data_lastname=' . $val['lastname'] . ' data_username=' . $val['username'] . ' data_adminuser_id=' . $val['id'] . ' data_contactname=' . $val['nickname'] . '  class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)"  id="disableuser_id_' . $val['id'] . '" data-href="' . base_url('youaudit/disableadminuser/' . $val['id'] . '/' . $val['master_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Archive" class="disableadminuser"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span>');
        }



        echo json_encode($output);
        die;
    }

// Action For Edit Master Admin User
    public function editAdminUser() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('master_model');

            $editAdminUser = array(
                'firstname' => $this->input->post('edit_first_name'),
                'lastname' => $this->input->post('edit_last_name'),
                'nickname' => $this->input->post('edit_contact_name'),
                'adminuser_id' => $this->input->post('adminuser_id'),
                'master_account_id' => $this->input->post('masterid'),
            );

            $result = $this->master_model->editAdminUser($editAdminUser);
            if ($result) {
                $this->session->set_flashdata('success', 'Master Admin User Edit Successfully');
                redirect("youaudit/Adminuser/" . $editAdminUser['master_account_id'], "refresh");
            }
        }
    }

// Action For Change Admin User Password.
    public function changeAdminUserPassword() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('master_model');
            $this->form_validation->set_rules('new_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('new_pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $changeAdminUserPassword = array(
                    'new_password' => $this->input->post('new_password'),
                    'pin_number' => $this->input->post('new_pin_number'),
                    'adminuser_id' => $this->input->post('change_adminuser_id'),
                    'master_account_id' => $this->input->post('masterid'),
                );
                if ($changeAdminUserPassword['new_password'] != '' || $changeAdminUserPassword['pin_number'] != '') {
                    $result = $this->master_model->changeAdminUserPassword($changeAdminUserPassword);
                    if ($result) {
                        $this->session->set_flashdata('success', 'Master Admin User Password & Pin Change Successfully');
                        redirect("youaudit/Adminuser/" . $changeAdminUserPassword['master_account_id'], "refresh");
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password & Pin Number Could not update Successfully');
                    redirect("youaudit/Adminuser/" . $changeAdminUserPassword['master_account_id'], "refresh");
                }
            }
        }
    }

    // Action For Change Admin User Password.

    public function disableadminuser($userid = '', $masterid = '') {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $this->load->model('master_model');
        $result = $this->master_model->disableadminuser($userid);
        if ($result) {

            $this->session->set_flashdata('success', 'Master Admin User Disable Successfully');
            redirect("youaudit/Adminuser/" . $masterid, "refresh");
        }
    }

    // Action For Enaable Admin User Account.
    public function enableadminuser($userid, $masterid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('master_model');
        $result = $this->master_model->enableadminuser($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'Master Admin User Enable Successfully');
            redirect("youaudit/Adminuser/" . $masterid, "refresh");
        }
    }

// Action For Add Customer  Account
    public function addCustomerAc() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        if ($this->input->post()) {
            $this->load->model('master_model');
            $this->load->model('accounts_model');
            $this->load->helper('date');
            $this->form_validation->set_rules('contact_password', 'Password', 'trim|md5');
            if ($this->form_validation->run()) {

                $arrCustomer = array(
                    'name' => $this->input->post('company_name'),
                    'address' => $this->input->post('comapany_address'),
                    'city' => $this->input->post('company_city'),
                    'state' => $this->input->post('company_state'),
                    'postcode' => $this->input->post('company_postcode'),
                    'contact_name' => $this->input->post('contact_name'),
                    'contact_email' => $this->input->post('username'),
                    'contact_number' => $this->input->post('contact_phone'),
                    'firstname' => $this->input->post('first_name'),
                    'lastname' => $this->input->post('last_name'),
                    'add_owner' => $this->input->post('add_owner'),
                    'support_email' => $this->input->post('support_email'),
                    'custom_count' => $this->input->post('custom_count'),
                    'qr_refcode' => strtoupper($this->input->post('qr_refcode')),
                    'package_id' => $this->input->post('package_type'),
                    'verified' => $this->input->post('verify_package'),
                    'annual_value' => $this->input->post('annual_value'),
                    'compliance' => $this->input->post('compliance_module'),
                    'fleet' => $this->input->post('fleet_module'),
                    'condition_module' => $this->input->post('condition_module'),
                    'depereciation_module' => $this->input->post('depreciation_module'),
                    'reporting_module' => $this->input->post('reporting_module'),
                    'profile' => $this->input->post('profile'),
                    'create_date' => time(),
                    'account_id' => $this->input->post('masterid'),
                    'active' => 1,
                    'account_type' => 1,
                    'archive' => 1
                );


                $master_account_id = $this->input->post('masterid');
                $system_admin_name = $this->master_model->getSysAdminName($master_account_id);
                $package = $this->accounts_model->getOnePackage($this->input->post('package_type'));

                $mail_content = array(
                    'sys_admin_name' => $system_admin_name[0]['sys_admin_name'],
                    'account_type' => 'Master Account',
                    'customer_name' => $this->input->post('company_name'),
                    'package_type' => $package,
                    'date_added' => date("Y-m-d H:i:s"),
                );


                $result = $this->master_model->addCustomerAc($arrCustomer);
                if ($result) {
                    $this->sendMailConfirmation($mail_content);
                    $this->session->set_flashdata('success', 'Customer Account Added Successfully');
                    redirect("youaudit/customerlist/" . $arrCustomer['account_id'], "refresh");
                } else {
                    $this->session->set_flashdata('error', 'You Can Not Add More Customer Accounts. Account Limit Is Finished.');
                    redirect("youaudit/customerlist/" . $arrCustomer['account_id'], "refresh");
                }
            }
        }
    }

    public function sendMailConfirmation($data) {

        $this->load->library('email');
        $list = array('prateek.jain@ignisitsolutions.com', 'dharmendra@ignisitsolutions.com', 'deepika@ignisitsolutions.com', 'email@youaudit.com.au');
        $this->email->from('youaudit@youaudit.com', 'EMAIL');
        $this->email->to($list);
        $this->email->subject('New Account Created in ' . $data['account_type']);
        $this->email->set_mailtype("html");
        $arrData = array(
            'customer_data' => $data
        );
        $msg = $this->load->view('youaudit/admins/email/create_account', $arrData, TRUE);
        $this->email->message($msg);

        if ($this->email->send()) {
            
        } else {
            
        }
    }

    // Action For Edit Customer  Account
    public function editCustomerAc() {


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
                    'create_date' => time(),
                    'master_account_id' => $this->input->post('masterid'),
                    'access_customer' => 1,
                    'customer_id' => $this->input->post('edit_customer_id'),
                );


                $result = $this->master_model->editCustomerAc($editArrCustomer);
                if ($result) {
                    $this->session->set_flashdata('success', 'Customer Account Edit Successfully');
                    redirect("youaudit/customerlist/" . $editArrCustomer['master_account_id'], "refresh");
                }
            }
        }
    }

    // Action For Get Customer  Account
    public function getCustomerAc($account_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("accounts.id", "accounts.name", "accounts.city", "accounts.state", "accounts.postcode", "accounts.qr_refcode", "packages.name", "accounts.annual_value", "accounts.compliance", "accounts.fleet", "accounts.condition_module", "accounts.depereciation_module", "accounts.reporting_module", "accounts.create_date", "accounts.active", "accounts.account_id", "accounts.account_type");

//        $query = "select accounts.id ,accounts.name AS company_name,city,state,postcode,qr_refcode,packages.name,annual_value,compliance,fleet,condition_module,depereciation_module,reporting_module,create_date,account_id,account_type
//                  ,active FROM accounts inner join packages on package_id = packages.id  WHERE account_type=1 AND account_id = " . $account_id;
        $query = "SELECT accounts.id, accounts.name AS company_name, city, state, postcode, qr_refcode, packages.name, annual_value, compliance, fleet, condition_module, depereciation_module, reporting_module, create_date, accounts.account_id, account_type, accounts.active, Count( items.id ) AS number_asset 
FROM accounts
LEFT JOIN packages ON package_id = packages.id
LEFT JOIN items ON accounts.id = items.account_id
WHERE archive=1 AND account_type =1
AND accounts.account_id =$account_id
GROUP BY accounts.id
HAVING (
COUNT( accounts.id ) >=1
)";


//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" AND ( company_name REGEXP '$words'
                          OR city REGEXP '$words'
                          OR state REGEXP '$words'
                          OR postcode REGEXP '$words'
                          OR qr_refcode REGEXP '$words'
                          OR annual_value REGEXP '$words'
                          OR create_date REGEXP '$words'
                          OR packages.name REGEXP '$words'
) ";
        }

        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {

            $words = $_GET['sSearch_3'];
            $query .=" and ( state REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_6']) && $_GET['sSearch_6'] != "") {

            $words = $_GET['sSearch_6'];
            $query .=" and ( packages.name REGEXP '$words'
                         ) ";
        }
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
            if ($val['account_type'] == 1) {
                if ($val['active'] == 1) {
                    $active_icon = '<span class="action-w"><a id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableCustomer/' . $val['id'] . '/' . $val['account_id']) . '" data_customer_id=' . $val['id'] . '  title="Disable" class="disablecustomer"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
                } else {

                    $active_icon = '<span class="action-w"><a id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableCustomer/' . $val['id'] . '/' . $val['account_id']) . '" data_customer_id=' . $val['id'] . '  title="Enable" class="enablecustomer"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
                }

                $users = $this->db->where(array('account_id' => $val['id']))->get('users');
                if ($users->num_rows > 0) {
                    $total_users = count($users->result_array());
                } else {
                    $total_users = 0;
                }



                $output['aaData'][] = array("DT_RowId" => $val['id'], '<input type="checkbox" class="multiComSelect" value=' . $val['id'] . '><input class="" type="hidden" id="customer_id_' . $val['id'] . '" value="">', $val['company_name'], $val['city'], $val['state'], $val['postcode'], $val['qr_refcode'], $val['name'], $val['annual_value'], $val['number_asset'], ($val['compliance'] == 1) ? 'YES' : 'NO', ($val['fleet'] == 1) ? 'YES' : 'NO', ($val['condition_module'] == 1) ? 'YES' : 'NO', ($val['depereciation_module'] == 1) ? 'YES' : 'NO', ($val['reporting_module'] == 1) ? 'YES' : 'NO', date('d/m/Y', $val['create_date']), $total_users, $active_icon . '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('youaudit/master_admins/customerArchive/' . $val['id'] . '/' . $val['account_id']) . '" title="Archive" class="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_' . $val['id'] . '" href="#edit_customer_ac" title="Edit" class="edit_customer_data" data_customer_id=' . $val['id'] . '><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span>');
            }
        }
        echo json_encode($output);
        die;
    }

    // Action For Disable Customer Account.
    public function disableCustomer($customer_id, $masterid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('master_model');
        $result = $this->master_model->disableCustomer($customer_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Customer Disable Successfully');
            redirect("youaudit/customerlist/" . $masterid, "refresh");
        }
    }

    // Action For Enaable Admin User Account.
    public function enableCustomer($customer_id, $masterid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('master_model');
        $result = $this->master_model->enableCustomer($customer_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Customer Enable Successfully');
            redirect("youaudit/customerlist/" . $masterid, "refresh");
        }
    }

    public function check_masterusername() {

        $this->load->model('master_model');
        $res = $this->master_model->check_masterusername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    public function checkMasterAdminUsername() {

        $this->load->model('master_model');
        $res = $this->master_model->checkMasterAdminUsername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    // Get Customer Edit Data
    public function get_edit_customerdata() {
        if ($this->input->post()) {
            $this->load->model('master_model');
            $customer_id = $this->input->post('id');
            $result = $this->master_model->geteditCustomerdata($customer_id);
            echo json_encode($result);
            die;
        } else {
            echo "";
        }
    }

    // Master Profile
    public function profiles($account_id) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();

        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Master_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Master Profile";

        if (isset($account_id)) {

            $this->load->model('master_model');
            $account_name = $this->master_model->getAccountName($account_id);
            $profiles = $this->master_model->inprofilelist($account_id);

            $arrData = array(
                'account_name' => $account_name[0]['company_name'],
                'masterid' => $account_id,
                'profilelist' => $profiles
            );

            $this->load->view('common/header', $arrPageData);
            $this->load->view('youaudit/admins/masteradmin/profile', $arrData);
            $this->load->view('common/footer', $arrPageData);
        }
    }

    // Add Profile
    public function add_profile() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $owner = array();
        $this->load->model('master_model');
        $data = array();
        $masterid = $this->input->post('masterid');

        for ($i = 1; $i <= ($this->input->post('owner')); $i++) {
            if ($this->input->post('owner_name' . $i)) {
                $owner[] = $this->input->post('owner_name' . $i);
            } else {
                $owner[] = "";
            }
        }

        for ($j = 1; $j <= ($this->input->post('category')); $j++) {
            if ($this->input->post('category_name' . $j)) {
                $category[] = $this->input->post('category_name' . $j);
            } else {
                $category[] = "";
            }
        }

        for ($k = 1; $k <= ($this->input->post('manu')); $k++) {
            if ($this->input->post('manu_name' . $k)) {
                $manu[] = $this->input->post('manu_name' . $k);
            } else {
                $manu[] = "";
            }
        }

        for ($m = 1; $m <= ($this->input->post('manufacturer')); $m++) {
            if ($this->input->post('manufacturer_name' . $m)) {
                $manufacturer[] = $this->input->post('manufacturer_name' . $m);
            } else {
                $manufacturer[] = "";
            }
        }

        for ($n = 1; $n <= ($this->input->post('fieldname')); $n++) {
            if ($this->input->post('field_name' . $n)) {
                $field_name[] = $this->input->post('field_name' . $n);
            }
            if ($this->input->post('field_type' . $n)) {
                $field_type[] = $this->input->post('field_type' . $n);
            }

            if ($this->input->post('field_values' . $n)) {
                $data = array();

                $data = explode("\n", trim($this->input->post('field_values' . $n)));
                $field_val = implode(',', $data);
                $field_values[] = $field_val;
            } else {
                $field_values[] = "";
            }
        }

        $response = $this->master_model->inaddProfile($owner, $category, $manu, $manufacturer, $field_name, $field_type, $field_values);
        if ($response) {
            $this->session->set_flashdata('success', 'Profile Added Successfully');
            redirect("youaudit/profiles/" . $masterid, "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Profile.Choose Atleast One From Owners,Categories,Item/Manu OR Manufacturer.');
            redirect("youaudit/profiles/" . $masterid, "refresh");
        }
    }

    // Edit Profile
    public function editProfile() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $masterid = $this->input->post('masterid');
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Profile";
        $arrPageData['arrPageParameters']['strTab'] = "Profile";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        if ($this->input->post()) {

            $this->load->model('admins_model');

            $owner = $this->input->post('owner');

            foreach ($owner as $key => $value) {
                if ($value == '') {
                    unset($owner[$key]);
                }
            }

            $category = $this->input->post('category');

            foreach ($category as $key => $value) {
                if ($value == '') {
                    unset($category[$key]);
                }
            }

            $item = $this->input->post('item');

            foreach ($item as $key => $value) {
                if ($value == '') {
                    unset($item[$key]);
                }
            }
            $manufacturer = $this->input->post('manufacturer');
            foreach ($manufacturer as $key => $value) {
                if ($value == '') {
                    unset($manufacturer[$key]);
                }
            }

            $field_name = $this->input->post('names');
            $field_type = $this->input->post('types');
            for ($i = 0; $i < count($field_name); $i++) {
                $custom['name'][$i] = $field_name[$i];
                $custom['type'][$i] = $field_type[$i];
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
            $item = array_values($item);
            $manufacturer = array_values($manufacturer);
            $fields = array_values($custom);
//            if(!empty($owner)){
//            $owner_list = json_encode($owner);
//            }
//            else{
//            $owner_list = null;
//            }
//            if(!empty($category)){
//            $category_list = json_encode($category);
//            }
//            else{
//             $category_list = null;
//            }
//            if(!empty($item)){
//            $item_list = json_encode($item);
//            }
//            else{
//            $item_list = null;
//            }
//            if(!empty($manufacturer)){
//            $manufacturer_list = json_encode($manufacturer);
//            }
//            else{
//            $manufacturer_list = null;
//            }


            $editProfile = array(
                'profile_name' => $this->input->post('edit_profile_name'),
                'owner' => json_encode($owner),
                'category' => json_encode($category),
                'manu' => json_encode($item),
                'manufacturer' => json_encode($manufacturer),
                'custom_field' => json_encode($custom)
            );


            $result = $this->admins_model->editProfile($editProfile, $this->input->post('adminuser_id'));
            if ($result) {
                $this->session->set_flashdata('success', 'Profile Edited Successfully');
                redirect("youaudit/profiles/" . $masterid, "refresh");
            }
        }
    }

    // View Profile
    public function viewProfile($profile_id) {
        if ($profile_id) {

            $this->load->model('master_model');
            $result = $this->master_model->getProfile($profile_id);
            //print_r($result);die;
            echo json_encode($result);
        }
    }

    // Compliance check for master
    public function complianceChecks($masterid) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        if ($masterid) {

            $arrPageData = array();

            $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Master_admin';
            $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/customerlist/' . $masterid;
            $arrPageData['arrPageParameters']['strPage'] = "Compliance";
            $arrPageData['arrSessionData'] = $this->session->userdata;

            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            $this->load->model('users_model');
            $this->load->model('tests_model');

            $this->load->model('categories_model');
            $this->load->model('master_model');
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['masterid'] = $masterid;
            $arrPageData['account_name'] = $this->master_model->getAccountName($masterid);

//              $arrPageData['allTests'] = $this->tests_model->getAllTests($this->input->post());
            $arrPageData['allTests'] = $this->master_model->getAllTasksForMasterAdmins($masterid);

            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
            if ($this->input->post()) {
                $this->master_model->addComplianceTestForMaster($this->input->post());

                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Template was successfully added')));
                redirect('/youaudit/master_admins/complianceChecks/' . $masterid, 'refresh');
            }

            // load views
            $this->load->view('common/header', $arrPageData);
            $this->load->view('youaudit/admins/masteradmin/complianceadmin', $arrPageData);
            $this->load->view('common/footer', $arrPageData);
        }
    }

    public function compliancesList($master_id) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($master_id) {
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
//            $arrPageData['categories'] = $this->categories_model->getAll();
            $arrPageData['allCompliances'] = $this->master_model->getAllMasterCompliances($master_id);
            $arrPageData['account_name'] = $this->master_model->getAccountName($master_id);

//            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown();
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
            $arrPageData['masterid'] = $master_id;
//            var_dump($arrPageData);
            /* Check filter */

//        var_dump($arrPageData);
            // load views
            $this->load->view('common/header', $arrPageData);
            $this->load->view('youaudit/admins/masteradmin/compliancelist', $arrPageData);
            $this->load->view('common/footer', $arrPageData);
        }
    }

    public function editTemplateCompliance($masterid) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
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
        if ($this->input->post()) {

            $this->master_model->updateCompliance($this->input->post(), $masterid);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Compliance(s) was/were successfully updated')));
            redirect('/youaudit/master_admins/compliancesList/' . $masterid, 'refresh');
        }
    }

    public function editMultiTemplateCompliance($masterid) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
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
            redirect('/youaudit/master_admins/compliancesList/' . $masterid, 'refresh');
        }
    }

    public function editTaskAdmins($id, $masterid) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');



        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model("categories_model");


        if ($this->input->post()) {


            if ($this->input->post('type_of_task') == '0') {
                $data = array(
                    'task_name' => $this->input->post('test_type_name'),
                    'type_of_task' => $this->input->post('type_of_task'),
                    'measurement' => '0'
                );
            } else {
                $data = array(
                    'task_name' => $this->input->post('test_type_name'),
                    'type_of_task' => $this->input->post('type_of_task'),
                    'measurement' => $this->input->post('measurement_type'),
                );
            }

            $this->tests_model->updateTask($id, $data);
//                  
//                   
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Task was successfully updated')));
            redirect("youaudit/master_admins/complianceChecks/$masterid", 'refresh');
        }
    }

    public function removeTaskAdmins($id, $masterid) {
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

        if ((int) $id) {
            $this->tests_model->removeTask((int) $id);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The check was successfully removed')));
            redirect("youaudit/master_admins/complianceChecks/$masterid", 'refresh');
        }
    }

    public function addTaskForMaster($masterid) {

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model("categories_model");

        if ($this->input->post()) {
            $acid = 0;
            if ($this->input->post('type_of_task') == '0') {
                $data = array(
                    'task_name' => $this->input->post('task_name'),
                    'type_of_task' => $this->input->post('type_of_task'),
                    'measurement' => '0',
                    'account_id' => $acid,
                    'template_task' => '1',
                    'admin_id' => $this->input->post('master_id'),
                    'account_type' => 1
                );
            } else {
                $data = array(
                    'task_name' => $this->input->post('task_name'),
                    'type_of_task' => $this->input->post('type_of_task'),
                    'measurement' => $this->input->post('measurement_type'),
                    'account_id' => $acid,
                    'template_task' => '1',
                    'admin_id' => $this->input->post('master_id'),
                    'account_type' => 1
                );
            }
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Task was successfully added')));
            $this->tests_model->insertTask($data);

            redirect("youaudit/master_admins/complianceChecks/$masterid", 'refresh');
        }
    }

    public function removeTemplate($id, $masterid) {
        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model("categories_model");

        if ((int) $id) {
            $this->tests_model->removeTemplate((int) $id);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Template was successfully removed')));
            redirect('/youaudit/master_admins/compliancesList/' . $masterid, 'refresh');
        }
    }

    public function expotMasterCusPdf($param, $masterid) {
        if ($param) {
            $this->load->model('master_model');
            $this->master_model->expotMasterCusPdf($param, $masterid);

            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function exportAdminUser($param, $masterid) {
        if ($param) {
            $this->load->model('master_model');
            $this->master_model->exportAdminUser($param, $masterid);

            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function exportProfilePdf($param, $masterid) {
        if ($masterid) {
            $this->load->model('master_model');
            $this->master_model->exportProfilePdf($param, $masterid);
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    // Archive Customer

    public function customerArchive($customer_id, $masterid) {
        if ($customer_id) {
            $this->load->model('master_model');
            $result = $this->master_model->customerArchive($customer_id);
            if ($result) {
                $this->session->set_flashdata('success', 'Customer Archive Successfully');
                redirect("youaudit/customerlist/" . $masterid, "refresh");
            } else {
                $this->session->set_flashdata('error', 'Customer Archive Could not Archived.');
                redirect("youaudit/customerlist/" . $masterid, "refresh");
            }
        }
    }

    public function editMultipleAccount() {
        $this->load->model('master_model');

        if ($this->input->post()) {

            $masterid = $this->input->post('masterid');
            $result = $this->master_model->editMultipleAccount($this->input->post());

            if ($result) {
                $this->session->set_flashdata('success', 'Customers Accounts Edit Successfully');
                redirect("youaudit/customerlist/" . $masterid, "refresh");
            } else {
                $this->session->set_flashdata('error', 'Customers Accounts Could Not Edit.');
                redirect("youaudit/customerlist/" . $masterid, "refresh");
            }
        }
    }

    //
    // Load Archive Customer  List
    public function arcivelist($account_id = false) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();

//            $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Master_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Master Archive Account";
        $this->load->model('master_model');
        $account_name = $this->master_model->getAccountName($account_id);

        $arrData = array(
            'account_name' => $account_name[0]['company_name'],
            'masterid' => $account_id
        );

        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/masteradmin/archivecustomerlist', $arrData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Get Customer  Account
    public function getArchiveCustomerAc($account_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("accounts.id", "accounts.name", "accounts.city", "accounts.state", "accounts.postcode", "accounts.qr_refcode", "packages.name", "accounts.annual_value", "accounts.compliance", "accounts.fleet", "accounts.condition_module", "accounts.depereciation_module", "accounts.reporting_module", "accounts.create_date", "accounts.active", "accounts.account_id", "accounts.account_type");

//        $query = "select accounts.id ,accounts.name AS company_name,city,state,postcode,qr_refcode,packages.name,annual_value,compliance,fleet,condition_module,depereciation_module,reporting_module,create_date,account_id,account_type
//                  ,active FROM accounts inner join packages on package_id = packages.id  WHERE account_type=1 AND account_id = " . $account_id;
        $query = "SELECT accounts.id, accounts.name AS company_name, city, state, postcode, qr_refcode, packages.name, annual_value, compliance, fleet, condition_module, depereciation_module, reporting_module, create_date, accounts.account_id, account_type, accounts.active, Count( items.id ) AS number_asset 
FROM accounts
LEFT JOIN packages ON package_id = packages.id
LEFT JOIN items ON accounts.id = items.account_id
WHERE archive=0 AND account_type =1
AND accounts.account_id =$account_id
GROUP BY accounts.id
HAVING (
COUNT( accounts.id ) >=1
)";


//        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {
//
//            $words = $_GET['sSearch_3'];
//            $query .=" and ( master_ac.company_name REGEXP '$words'
//                         ) ";
//        }
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" AND ( company_name REGEXP '$words'
                          OR city REGEXP '$words'
                          OR state REGEXP '$words'
                          OR postcode REGEXP '$words'
                          OR qr_refcode REGEXP '$words'
                          OR annual_value REGEXP '$words'
                          OR create_date REGEXP '$words'
                          OR packages.name REGEXP '$words'
) ";
        }

        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {

            $words = $_GET['sSearch_3'];
            $query .=" and ( state REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_6']) && $_GET['sSearch_6'] != "") {

            $words = $_GET['sSearch_6'];
            $query .=" and ( packages.name REGEXP '$words'
                         ) ";
        }
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
            if ($val['account_type'] == 1) {


                $users = $this->db->where(array('account_id' => $val['id']))->get('users');
                if ($users->num_rows > 0) {
                    $total_users = count($users->result_array());
                } else {
                    $total_users = 0;
                }



                $output['aaData'][] = array("DT_RowId" => $val['id'], $val['company_name'], $val['city'], $val['state'], $val['qr_refcode'], $val['name'], $val['annual_value'], $val['number_asset'], date('d/m/Y', $val['create_date']), $total_users, '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('youaudit/master_admins/restorecustomer/' . $val['id'] . '/' . $val['account_id']) . '" title="Archive" class="Archive"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>');
            }
        }
        echo json_encode($output);
        die;
    }

    // Action For restore Customer Account.
    public function restorecustomer($customer_id, $masterid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('master_model');
        $result = $this->master_model->restoreCustomer($customer_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Master Customer Restore Successfully');
            redirect("youaudit/master_admins/arcivelist/" . $masterid, "refresh");
        }
    }

    // Archive master Admin user


    public function getArchiveAdminUserData($master_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("systemadmin_master.id", "systemadmin_master.firstname", "systemadmin_master.lastname", "systemadmin_master.nickname", "systemadmin_master.username", "systemadmin_master.active", "systemadmin_master.master_account_id");

        $query = "select id ,firstname,lastname,nickname,username,active,master_account_id
                  FROM systemadmin_master WHERE active = 0 AND master_account_id =" . $master_id;

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" AND ( firstname REGEXP '$words'
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
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableadminuser/' . $val['id'] . '/' . $val['master_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableadminuser/' . $val['id'] . '/' . $val['master_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }


            $output['aaData'][] = array("DT_RowId" => $val['id'], $val['firstname'], $val['lastname'], $val['username'], '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteadminTemplate(this)"  id="disableuser_id_' . $val['id'] . '" data-href="' . base_url('youaudit/master_admins/restoreadminuser/' . $val['id'] . '/' . $val['master_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Archive" class="disableadminuser"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>');
        }



        echo json_encode($output);
        die;
    }

    // Action For Change Admin User Password.

    public function restoreadminuser($userid = '', $masterid = '') {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $this->load->model('master_model');
        $result = $this->master_model->restoreadminuser($userid);
        if ($result) {

            $this->session->set_flashdata('success', 'Master Admin User Restore Successfully');
            redirect("youaudit/master_admins/arcivelist/" . $masterid, "refresh");
        }
    }

    // Check Profile
    public function checkProfile($account_id) {

        $this->load->model('master_model');
        $res = $this->master_model->checkProfile(trim($this->input->post('profile_name')), $account_id);
        echo $res;
        die;
    }

}
