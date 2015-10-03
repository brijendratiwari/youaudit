<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Franchise_admins extends CI_Controller {

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

    public function franchise_customerList($account_id) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();

        // $arrPageData['arrPageParameters']['strSection'] = get_class();

        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Franchise_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/franchise_customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Franchise Customer";
        if (isset($account_id)) {

            $arrData = array();
            $this->load->model('franchise_model');
            $account_name = $this->franchise_model->getFranchiseAccountName($account_id);
            $customer_package = $this->franchise_model->getCustomerPackage();
            $customer_data = $this->franchise_model->franchisecustomerlist($account_id);
            $packages = $this->franchise_model->franchisepackagelist($account_id);
            $profile = $this->franchise_model->inprofilelist($account_id);
            $arrData = array(
                'account_name' => $account_name[0]['company_name'],
                'customer_package' => $customer_package,
                'masterid' => $account_id,
                'packages' => $packages,
                'option' => $customer_data,
                'profilelist' => $profile
            );

            $this->load->view('common/header', $arrPageData);
            $this->load->view('youaudit/admins/franchiseadmin/customerlist', $arrData);
            $this->load->view('common/footer', $arrPageData);
        }
    }

    // Action For Add Customer  Account
    public function addFranchiseCustomerAc() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        if ($this->input->post()) {
            $this->load->model('franchise_model');
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
                    'account_type' => 2,
                    'archive' => 1
                );


                $account_id = $this->input->post('masterid');
                $system_admin_name = $this->franchise_model->getSysAdminName($account_id);
                $package = $this->accounts_model->getOnePackage($this->input->post('package_type'));

                $mail_content = array(
                    'sys_admin_name' => $system_admin_name[0]['sys_franchise_name'],
                    'account_type' => 'Franchises Account',
                    'customer_name' => $this->input->post('company_name'),
                    'package_type' => $package,
                    'date_added' => date("Y-m-d H:i:s"),
                );


                $result = $this->franchise_model->addCustomerAc($arrCustomer);
                if ($result) {
                    $this->sendMailConfirmation($mail_content);
                    $this->session->set_flashdata('success', 'Franchise Customer Account Added Successfully');

                    redirect("youaudit/franchise_customerlist/" . $arrCustomer['account_id'], "refresh");
                } else {
                    $this->session->set_flashdata('error', 'You Can Not Add More Customer Accounts. Account Limit Is Finished.');
                    redirect("youaudit/franchise_customerlist/" . $arrCustomer['account_id'], "refresh");
                }
            }
        }
    }

    // check uniqueness of qrcode
    public function checkQrcode() {

        $this->load->model('franchise_model');
        $res = $this->franchise_model->check_qrcode(trim($this->input->post('bar_code')));
        echo $res;
        die;
    }

    public function sendMailConfirmation($data) {

        $this->load->library('email');
        $list = array('prateek.jain@ignisitsolutions.com', 'dharmendra@ignisitsolutions.com', 'deepika@ignisitsolutions.com');
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
//            echo "send";
        } else {
//            print_r($this->email->print_debugger());
        }
    }

    // Action For Edit Customer  Account
    public function editFranchiseCustomerAc() {


        if ($this->input->post()) {
            $this->load->model('franchise_model');
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
                    'account_id' => $this->input->post('masterid'),
                    'access_customer' => 1,
                    'customer_id' => $this->input->post('edit_customer_id'),
                );


                $result = $this->franchise_model->editFranchiseCustomerAc($editArrCustomer);
                if ($result) {
                    $this->session->set_flashdata('success', 'Customer Account Edit Successfully');
                    redirect("youaudit/franchise_customerlist/" . $editArrCustomer['account_id'], "refresh");
                }
            }
        }
    }

    // genrate random string

    public function generateRandomString() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $refrenceCode = random_string('alnum', '4');
        $this->load->model('franchise_model');
        $res = $this->franchise_model->checkFranchiseRefcode($refrenceCode);
        if ($res) {
            return $this->generateRandomString();
        } else {
            echo ($refrenceCode);
        }
    }

    // Action For Get Customer  Account

    public function getFranchiseCustomerAc($account_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("accounts.id", "accounts.name", "accounts.city", "accounts.state", "accounts.postcode", "accounts.qr_refcode", "packages.name", "accounts.annual_value", "accounts.compliance", "accounts.fleet", "accounts.condition_module", "accounts.depereciation_module", "accounts.reporting_module", "accounts.create_date", "accounts.active", "accounts.account_id", "accounts.account_type");

        $query = "SELECT accounts.id, accounts.name AS company_name, city, state, postcode, qr_refcode, packages.name, annual_value, compliance, fleet, condition_module, depereciation_module, reporting_module, create_date, accounts.account_id, account_type, accounts.active, Count( items.id ) AS number_asset 
FROM accounts
LEFT JOIN packages ON package_id = packages.id
LEFT JOIN items ON accounts.id = items.account_id
WHERE account_type =2
AND archive=1
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
            if ($val['account_type'] == 2) {
                if ($val['active'] == 1) {
                    $active_icon = '<span class="action-w"><a id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableFranchiseCustomer/' . $val['id'] . '/' . $val['account_id']) . '" data_customer_id=' . $val['id'] . '  title="Disable" class="disablecustomer"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
                } else {

                    $active_icon = '<span class="action-w"><a id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableFranchiseCustomer/' . $val['id'] . '/' . $val['account_id']) . '" data_customer_id=' . $val['id'] . '  title="Enable" class="enablecustomer"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
                }

                $users = $this->db->where(array('account_id' => $val['id']))->get('users');
                if ($users->num_rows > 0) {
                    $total_users = count($users->result_array());
                } else {
                    $total_users = 0;
                }

//                $asset = $this->db->where(array('account_id' => $val['id']))->get('items');
//                if ($asset->num_rows > 0) {
//                    $total_asset = count($asset->result_array());
//                } else {
//                    $total_asset = 0;
//                }



                $output['aaData'][] = array("DT_RowId" => $val['id'], '<input type="checkbox" class="multiComSelect" value=' . $val['id'] . '><input class="" type="hidden" id="customer_id_' . $val['id'] . '" value="">', $val['company_name'], $val['city'], $val['state'], $val['postcode'], $val['qr_refcode'], $val['name'], $val['annual_value'], $val['number_asset'], ($val['compliance'] == 1) ? 'YES' : 'NO', ($val['fleet'] == 1) ? 'YES' : 'NO', ($val['condition_module'] == 1) ? 'YES' : 'NO', ($val['depereciation_module'] == 1) ? 'YES' : 'NO', ($val['reporting_module'] == 1) ? 'YES' : 'NO', date('d/m/Y', $val['create_date']), $total_users, $active_icon . '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('youaudit/franchise_admins/customerArchive/' . $val['id'] . '/' . $val['account_id']) . '" title="Archive" class="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_' . $val['id'] . '" href="#edit_customer_ac" title="Edit" class="edit_franchise_customer_data" data_customer_id=' . $val['id'] . '><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span>');
            }
        }

        echo json_encode($output);
        die;
    }

    // Action For Disable Customer Account.
    public function disableFranchiseCustomer($customer_id, $masterid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('franchise_model');
        $result = $this->franchise_model->disableFranchiseCustomer($customer_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Customer Disable Successfully');
            redirect("youaudit/franchise_customerlist/" . $masterid, "refresh");
        }
    }

    // Action For Enaable Admin User Account.
    public function enableFranchiseCustomer($customer_id, $masterid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('franchise_model');
        $result = $this->franchise_model->enableFranchiseCustomer($customer_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Customer Enable Successfully');
            redirect("youaudit/franchise_customerlist/" . $masterid, "refresh");
        }
    }

    // Load Admin User List
    public function franchiseAdminUser($account_id = false) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData = array();

        // $arrPageData['arrPageParameters']['strSection'] = get_class();

        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Franchise_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/franchise_customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Franchise Admin User";
        $this->load->model('franchise_model');
        $account_name = $this->franchise_model->getFranchiseAccountName($account_id);

        $arrData = array(
            'account_name' => $account_name[0]['company_name'],
            'masterid' => $account_id
        );



        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/franchiseadmin/adminuser', $arrData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function addFranchiesAdminUser() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {
            $this->load->model('franchise_model');
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
                    'franchise_account_id' => $this->input->post('masterid'),
                    'active' => '1'
                );


                $result = $this->franchise_model->addFranchiseAdminUser($arrAdminUser);
                if ($result) {
                    $this->session->set_flashdata('success', 'Franchise Admin User Added Successfully');
                    redirect("youaudit/franchiseAdminUser/" . $arrAdminUser['franchise_account_id'], "refresh");
                }
            }
        }
    }

    // Action For Get franhise Admin User
    public function getFranchiseAdminUserData($franchise_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("systemadmin_franchise.id", "systemadmin_franchise.firstname", "systemadmin_franchise.lastname", "systemadmin_franchise.nickname", "systemadmin_franchise.username", "systemadmin_franchise.active", "systemadmin_franchise.franchise_account_id");

        $query = "select id ,firstname,lastname,username,nickname,active,franchise_account_id
                  FROM systemadmin_franchise WHERE active=1 AND franchise_account_id =" . $franchise_id;

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
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableFranchiseAdminUser/' . $val['id'] . '/' . $val['franchise_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableFranchiseAdminUser/' . $val['id'] . '/' . $val['franchise_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }


            $output['aaData'][] = array("DT_RowId" => $val['id'], $val['firstname'], $val['lastname'], $val['username'], '<span class="action-w"><a data-toggle="modal"   id="changepassword_id_' . $val['id'] . '" href="#change_password_model" data_adminuser_id=' . $val['id'] . ' class="change_password_model"  title="Change Password"><i class="glyphicon glyphicon-lock franchises-i"></i></a>Password</span><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_' . $val['id'] . '" href="#edit_admin_user_form" title="Edit" data_firstname=' . $val['firstname'] . ' data_lastname=' . $val['lastname'] . ' data_username=' . $val['username'] . ' data_contactname=' . $val['nickname'] . '  data_adminuser_id=' . $val['id'] . '  class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)"  id="disableuser_id_' . $val['id'] . '" data-href="' . base_url('youaudit/disableFranchiseAdminUser/' . $val['id'] . '/' . $val['franchise_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Archive" class="disableadminuser"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span>');
        }



        echo json_encode($output);
        die;
    }

    // Action For Change Admin User Password.
    public function changeFranchiseAdminUserPassword() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('franchise_model');
            $this->form_validation->set_rules('new_password', 'Password', 'trim|md5');
            $this->form_validation->set_rules('new_pin_number', 'Pin', 'trim|md5');
            if ($this->form_validation->run()) {
                $changeAdminUserPassword = array(
                    'new_password' => $this->input->post('new_password'),
                    'pin_number' => $this->input->post('new_pin_number'),
                    'adminuser_id' => $this->input->post('change_adminuser_id'),
                    'franchise_account_id' => $this->input->post('masterid'),
                );
                if ($changeAdminUserPassword['new_password'] != '' || $changeAdminUserPassword['pin_number'] != '') {
                    $result = $this->franchise_model->changeFranchiseAdminUserPassword($changeAdminUserPassword);

                    if ($result) {
                        $this->session->set_flashdata('success', 'Franchise Admin User Password & Pin Change Successfully');
                        redirect("youaudit/franchiseAdminUser/" . $changeAdminUserPassword['franchise_account_id'], "refresh");
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password & Pin Number Could not update Successfully');
                    redirect("youaudit/franchiseAdminUser/" . $changeAdminUserPassword['franchise_account_id'], "refresh");
                }
            }
        }
    }

// Action For Edit Franchise Admin User
    public function editFranchiseAdminUser() {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('franchise_model');

            $editAdminUser = array(
                'firstname' => $this->input->post('edit_first_name'),
                'lastname' => $this->input->post('edit_last_name'),
                'nickname' => $this->input->post('edit_contact_name'),
                'adminuser_id' => $this->input->post('adminuser_id'),
                'franchise_account_id' => $this->input->post('masterid'),
            );

            $result = $this->franchise_model->editFranchiseAdminUser($editAdminUser);
            if ($result) {
                $this->session->set_flashdata('success', 'Franchise Admin User Edit Successfully');
                redirect("youaudit/franchiseAdminUser/" . $editAdminUser['franchise_account_id'], "refresh");
            }
        }
    }

    // Action For Disable Admin User Account.
    public function disableFranchiseAdminUser($userid, $franchiseid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $this->load->model('franchise_model');
        $result = $this->franchise_model->disableFranchiseAdminUser($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Admin User Archive Successfully');
            redirect("youaudit/franchiseAdminUser/" . $franchiseid, "refresh");
        }
    }

    // call ajax for check username For Master Account
    public function check_franchiseAdminUsername() {

        $this->load->model('franchise_model');
        $res = $this->franchise_model->check_franchiseAdminUsername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    public function check_franchiseCustomerUsername() {

        $this->load->model('franchise_model');
        $res = $this->franchise_model->check_franchiseCustomerUsername(trim($this->input->post('username')));
        echo $res;
        die;
    }

    // Get Customer Edit Data
    public function getEditFranchiseCustomerdata() {
        if ($this->input->post()) {
            $this->load->model('franchise_model');
            $customer_id = $this->input->post('id');
            $result = $this->franchise_model->getEditFranchiseCustomerdata($customer_id);
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
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Franchise_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/franchise_customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Franchise Profile";
        if (isset($account_id)) {

            $this->load->model('franchise_model');
            $account_name = $this->franchise_model->getFranchiseAccountName($account_id);

            $profiles = $this->franchise_model->inprofilelist($account_id);

            $arrData = array(
                'account_name' => $account_name[0]['company_name'],
                'masterid' => $account_id,
                'profilelist' => $profiles
            );

            $this->load->view('common/header', $arrPageData);
            $this->load->view('youaudit/admins/franchiseadmin/profile', $arrData);
            $this->load->view('common/footer', $arrPageData);
        }
    }

    // Add Profile
    public function add_profile() {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $owner = array();
        $this->load->model('franchise_model');
        $data = array();
        $masterid = $this->input->post('masterid');

        $masterid = $this->input->post('masterid');
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
                $check_category = $this->db->where('name', $category_data[$j])->get('categories');
                if ($check_category->num_rows() < 1) {
                    $category[] = $category_data[$j];
                }
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
                $check_manu = $this->db->where('item_manu_name', $manu_data[$k])->get('item_manu');
                if ($check_manu->num_rows() < 1) {
                    $manu[] = $manu_data[$k];
                }
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
                $check_manufacturer = $this->db->where('manufacturer_name', $manufacturer_data[$m])->get('manufacturer_list');
                if ($check_manufacturer->num_rows() < 1) {
                    $manufacturer[] = $manufacturer_data[$m];
                }
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

        $response = $this->franchise_model->inaddProfile($owner, $category, $manu, $manufacturer, $field_name, $field_type, $field_values);
        if ($response) {
            $this->session->set_flashdata('success', 'Profile Added Successfully');
            redirect("youaudit/franchise_profiles/" . $masterid, "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Profile.Choose Atleast One From Owners,Categories,Item/Manu OR Manufacturer.');
            redirect("youaudit/franchise_profiles/" . $masterid, "refresh");
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
                    $check_category = $this->db->where('name', $category_data[$j])->get('categories');
                    if ($check_category->num_rows() < 1) {
                        $category[] = $category_data[$j];
                    }
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
                    $check_manu = $this->db->where('item_manu_name', $manu_data[$k])->get('item_manu');
                    if ($check_manu->num_rows() < 1) {
                        $manu[] = $manu_data[$k];
                    }
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
                    $check_manufacturer = $this->db->where('manufacturer_name', $manufacturer_data[$m])->get('manufacturer_list');
                    if ($check_manufacturer->num_rows() < 1) {
                        $manufacturer[] = $manufacturer_data[$m];
                    }
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

            $result = $this->admins_model->editProfile($editProfile, $this->input->post('adminuser_id'));
            if ($result) {
                $this->session->set_flashdata('success', 'Profile Edited Successfully');
                redirect("youaudit/franchise_profiles/" . $masterid, "refresh");
            }
        }
    }

    public function complianceChecksForFranchise($franchiseid) {
        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        if ($franchiseid) {

            $arrPageData = array();

            $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Franchise_admin';
            $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/franchise_customerlist/' . $franchiseid;
            $arrPageData['arrPageParameters']['strPage'] = "Franchise Compliance";
            $arrPageData['arrSessionData'] = $this->session->userdata;

            $this->session->set_userdata('booCourier', false);
            $this->session->set_userdata('arrCourier', array());
            $arrPageData['arrErrorMessages'] = array();
            $arrPageData['arrUserMessages'] = array();

            $this->load->model('users_model');
            $this->load->model('tests_model');

            $this->load->model('categories_model');
            $this->load->model('franchise_model');
            $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
            $arrPageData['masterid'] = $franchiseid;
//              $arrPageData['allTests'] = $this->tests_model->getAllTests($this->input->post());
            $arrPageData['allTests'] = $this->franchise_model->getAllTasksForFranchiseAdmins($franchiseid);
            $arrPageData['account_name'] = $this->franchise_model->getFranchiseAccountName($franchiseid);
            $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
            if ($this->input->post()) {
                $this->franchise_model->addComplianceTestForMaster($this->input->post());

                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Template was successfully added')));
                redirect('/youaudit/franchise_admins/complianceChecksForFranchise/' . $franchiseid, 'refresh');
            }

            // load views
            $this->load->view('common/header', $arrPageData);
            $this->load->view('youaudit/admins/franchiseadmin/complianceadmin', $arrPageData);
            $this->load->view('common/footer', $arrPageData);
        }
    }

    public function compliancesListForFranchise($franchiseid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }

        $arrPageData = array();


        // $arrPageData['arrPageParameters']['strSection'] = get_class();

        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Franchise_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/franchise_customerlist/' . $franchiseid;
        $arrPageData['arrPageParameters']['strPage'] = "Franchise Compliance";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $this->load->model('tests_model');
        $this->load->model('categories_model');
        $this->load->model('admins_model');
        $this->load->model('franchise_model');
//            $arrPageData['categories'] = $this->categories_model->getAll();
        $arrPageData['allCompliances'] = $this->franchise_model->getAllMasterCompliances($franchiseid);

//            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown();
        $arrPageData['frequencies'] = $this->tests_model->getTestFreqs();
        $arrPageData['allMeasurements'] = $this->tests_model->getAllMeasurements();
        $arrPageData['masterid'] = $franchiseid;
//            var_dump($arrPageData);
        /* Check filter */

//        var_dump($arrPageData);
        $arrPageData['account_name'] = $this->franchise_model->getFranchiseAccountName($franchiseid);
        // load views
        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/franchiseadmin/compliancelist', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function editTemplateCompliance($franchiseid) {
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
        $this->load->model('franchise_model');
        if ($this->input->post()) {

            $this->franchise_model->updateCompliance($this->input->post(), $franchiseid);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Compliance(s) was/were successfully updated')));
            redirect('/youaudit/franchise_admins/compliancesListForFranchise/' . $franchiseid, 'refresh');
        }
    }

    public function editMultiTemplateCompliance($franchiseid) {
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
            redirect('/youaudit/franchise_admins/compliancesListForFranchise/' . $franchiseid, 'refresh');
        }
    }

    public function editTaskAdmins($id, $franchiseid) {
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
            redirect("youaudit/franchise_admins/complianceChecksForFranchise/$franchiseid", 'refresh');
        }
    }

    public function removeTaskAdmins($id, $franchiseid) {
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
            redirect("youaudit/franchise_admins/complianceChecksForFranchise/$franchiseid", 'refresh');
        }
    }

    public function addTaskForFranchise($franchiseid) {

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
                    'account_type' => 2
                );
            } else {
                $data = array(
                    'task_name' => $this->input->post('task_name'),
                    'type_of_task' => $this->input->post('type_of_task'),
                    'measurement' => $this->input->post('measurement_type'),
                    'account_id' => $acid,
                    'template_task' => '1',
                    'admin_id' => $this->input->post('master_id'),
                    'account_type' => 2
                );
            }

            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Task was successfully added')));
            $this->tests_model->insertTask($data);

            redirect("youaudit/franchise_admins/complianceChecksForFranchise/$franchiseid", 'refresh');
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
            redirect('/youaudit/franchise_admins/compliancesListForFranchise/' . $masterid, 'refresh');
        }
    }

    // View Profile
    public function viewProfile($profile_id) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        if ($profile_id) {

            $this->load->model('franchise_model');
            $result = $this->franchise_model->getProfile($profile_id);
            //print_r($result);die;
            echo json_encode($result);
        }
    }

    public function exportFranchiseCusPdf($param, $masterid) {
        if ($param) {
            $this->load->model('franchise_model');
            $this->franchise_model->exportFranchiseCusPdf($param, $masterid);

            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function exportFranchiseAdminUser($param, $masterid) {
        if ($param) {
            $this->load->model('franchise_model');
            $this->franchise_model->exportFranchiseAdminUser($param, $masterid);

            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function exportFranchiseProfilePdf($param, $masterid) {
        if ($masterid) {
            $this->load->model('franchise_model');
            $this->franchise_model->exportFranchiseProfilePdf($param, $masterid);
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function customerArchive($customer_id, $franchiseid) {
        if ($customer_id) {
            $this->load->model('franchise_model');
            $result = $this->franchise_model->customerArchive($customer_id);
            if ($result) {
                $this->session->set_flashdata('success', 'Customer Archive Successfully');
                redirect("youaudit/franchise_customerlist/" . $franchiseid, "refresh");
            } else {
                $this->session->set_flashdata('error', 'Customer Archive Could not Archived.');
                redirect("youaudit/franchise_customerlist/" . $franchiseid, "refresh");
            }
        }
    }

    public function editMultipleAccount() {
        $this->load->model('franchise_model');

        if ($this->input->post()) {

            $franchiseid = $this->input->post('masterid');
            $result = $this->franchise_model->editMultipleAccount($this->input->post());

            if ($result) {
                $this->session->set_flashdata('success', 'Customers Accounts Edit Successfully');
                redirect("youaudit/franchise_customerlist/" . $franchiseid, "refresh");
            } else {
                $this->session->set_flashdata('error', 'Customers Accounts Could Not Edit.');
                redirect("youaudit/franchise_customerlist/" . $franchiseid, "refresh");
            }
        }
    }

    // Load archive customer
    public function restorecustomer($account_id = false) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $arrPageData['arrPageParameters']['strSectionYouaudit'] = 'Franchise_admin';
        $arrPageData['arrPageParameters']['strSectionYouauditdashboard'] = 'Youaudit/franchise_customerlist/' . $account_id;
        $arrPageData['arrPageParameters']['strPage'] = "Franchise Archive Account";
        $this->load->model('franchise_model');
        $account_name = $this->franchise_model->getFranchiseAccountName($account_id);

        $arrData = array(
            'account_name' => $account_name[0]['company_name'],
            'masterid' => $account_id
        );



        $this->load->view('common/header', $arrPageData);
        $this->load->view('youaudit/admins/franchiseadmin/customerarchivelist', $arrData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action For Get Customer  Account

    public function getFranchiseArchiveCustomerAc($account_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("accounts.id", "accounts.name", "accounts.city", "accounts.state", "accounts.postcode", "accounts.qr_refcode", "packages.name", "accounts.annual_value", "accounts.compliance", "accounts.fleet", "accounts.condition_module", "accounts.depereciation_module", "accounts.reporting_module", "accounts.create_date", "accounts.active", "accounts.account_id", "accounts.account_type");

        $query = "SELECT accounts.id, accounts.name AS company_name, city, state, postcode, qr_refcode, packages.name, annual_value, compliance, fleet, condition_module, depereciation_module, reporting_module, create_date, accounts.account_id, account_type, accounts.active, Count( items.id ) AS number_asset 
FROM accounts
LEFT JOIN packages ON package_id = packages.id
LEFT JOIN items ON accounts.id = items.account_id
WHERE account_type =2
AND archive=0
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
            if ($val['account_type'] == 2) {
                if ($val['active'] == 1) {
                    $active_icon = '<span class="action-w"><a id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableFranchiseCustomer/' . $val['id'] . '/' . $val['account_id']) . '" data_customer_id=' . $val['id'] . '  title="Disable" class="disablecustomer"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
                } else {

                    $active_icon = '<span class="action-w"><a id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableFranchiseCustomer/' . $val['id'] . '/' . $val['account_id']) . '" data_customer_id=' . $val['id'] . '  title="Enable" class="enablecustomer"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
                }

                $users = $this->db->where(array('account_id' => $val['id']))->get('users');
                if ($users->num_rows > 0) {
                    $total_users = count($users->result_array());
                } else {
                    $total_users = 0;
                }

//                $asset = $this->db->where(array('account_id' => $val['id']))->get('items');
//                if ($asset->num_rows > 0) {
//                    $total_asset = count($asset->result_array());
//                } else {
//                    $total_asset = 0;
//                }



                $output['aaData'][] = array("DT_RowId" => $val['id'], $val['company_name'], $val['city'], $val['state'], $val['qr_refcode'], $val['name'], $val['annual_value'], $val['number_asset'], date('d/m/Y', $val['create_date']), $total_users, '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('youaudit/franchise_admins/customerestore/' . $val['id'] . '/' . $val['account_id']) . '" title="Restore" class="Restore"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>');
            }
        }

        echo json_encode($output);
        die;
    }

    // Action For Disable Customer Account.
    public function customerestore($customer_id, $masterid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }
        $this->load->model('franchise_model');
        $result = $this->franchise_model->customerRestore($customer_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Customer Restore Successfully');
            redirect("youaudit/franchise_admins/restorecustomer/" . $masterid, "refresh");
        } else {
            $this->session->set_flashdata('error', 'Franchise Customer Could Not Restore Successfully');
            redirect("youaudit/franchise_admins/restorecustomer/" . $masterid, "refresh");
        }
    }

    // Action For Get Archive franhise Admin User
    public function getArchiveAdminUser($franchise_id) {
        $sLimit = "";
        $lenght = 10;
        $str_point = 0;

        $col_sort = array("systemadmin_franchise.id", "systemadmin_franchise.firstname", "systemadmin_franchise.lastname", "systemadmin_franchise.nickname", "systemadmin_franchise.username", "systemadmin_franchise.active", "systemadmin_franchise.franchise_account_id");

        $query = "select id ,firstname,lastname,username,nickname,active,franchise_account_id
                  FROM systemadmin_franchise WHERE active=0 AND franchise_account_id =" . $franchise_id;

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
                $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/disableFranchiseAdminUser/' . $val['id'] . '/' . $val['franchise_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
            } else {
                $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('youaudit/enableFranchiseAdminUser/' . $val['id'] . '/' . $val['franchise_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
            }


            $output['aaData'][] = array("DT_RowId" => $val['id'], $val['firstname'], $val['lastname'], $val['username'], '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="restoreadmin(this)"  id="disableuser_id_' . $val['id'] . '" data-href="' . base_url('youaudit/franchise_admins/restoreadminuser/' . $val['id'] . '/' . $val['franchise_account_id']) . '" data_adminuser_id=' . $val['id'] . '  title="Archive" class="disableadminuser"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>');
        }



        echo json_encode($output);
        die;
    }

    // Action For Enaable Admin User Account.
    public function restoreadminuser($userid, $franchiseid) {

        if (!$this->session->userdata('booSystemAdminLogin')) {
            redirect('youaudit/login/', 'refresh');
        }


        $this->load->model('franchise_model');
        $result = $this->franchise_model->enableFranchiseAdminUser($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'Franchise Admin User Restore Successfully');
            redirect("youaudit/franchise_admins/restorecustomer/" . $franchiseid, "refresh");
        } else {
            $this->session->set_flashdata('error', 'Franchise Admin User Could Not Restore Successfully');
            redirect("youaudit/franchise_admins/restorecustomer/" . $franchiseid, "refresh");
        }
    }

    // Check Profile
    public function checkProfile($account_id) {

        $this->load->model('franchise_model');
        $res = $this->franchise_model->checkProfile(trim($this->input->post('profile_name')), $account_id);
        echo $res;
        die;
    }

}
