<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_Section extends MY_Controller {

// Get manulist
    public function index() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $arrPageData['getlist'] = $this->admin_section_model->getItem_Manu($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['getmanufacturer'] = $this->admin_section_model->getManufacturer($arrPageData['arrSessionData']['objSystemUser']->accountid);

        // load views
        $this->load->view('common/header', $arrPageData);

        $this->load->view('admin_section/item_manu', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

//    ADD ITEM/MANU
    public function addItemsManu() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $data = array();
        $items = explode("\n", $this->input->post('item_name'));
        $this->load->model('admin_section_model');
        foreach ($items as $key => $value) {
            if ($value == '') {
                unset($items[$key]);
            }
        }
        $this->load->model('admin_section_model');
        for ($i = 0; $i < count($items); $i++) {
            $data = array(
                'item_manu_name' => $items[$i],
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            );
            $result[] = $this->admin_section_model->addItem_Manu($data);
        }
        if ($result) {
            for ($i = 0; $i < count($result); $i++) {
                $this->logThis("Added Item Manu", "item_manu", $result[$i]);
            }
            $this->session->set_flashdata('success', 'Item(s) Added Successfully');
            redirect("admin_section/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Item(s) Could Not Be Added Successfully');
            redirect("admin_section/", "refresh");
        }
    }

//    Add Manufacturer
    public function addManufacturer() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $data = array();
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $manufacture = explode("\n", $this->input->post('manufacture_name'));
        $this->load->model('admin_section_model');
        foreach ($manufacture as $key => $value) {
            if ($value == '') {
                unset($manufacture[$key]);
            }
        }


        for ($i = 0; $i < count($manufacture); $i++) {

            $data = array(
                'manufacturer_name' => $manufacture[$i],
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            );

            $result[] = $this->admin_section_model->addManufacturer($data);
        }

        if ($result) {
            for ($i = 0; $i < count($result); $i++) {
                $this->logThis("Added Manufacturer", "manufacturer_list", $result[$i]);
            }
            $this->session->set_flashdata('success', 'Manufacturer(s) Added Successfully');
            redirect("admin_section/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Manufacturer(s) Could Not Be Added Successfully');
            redirect("admin_section/", "refresh");
        }
    }

// action to edit itenm/manu
    public function editItems_Manu() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $data = array();
        $this->load->model('admin_section_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        if ($this->input->post('item_name') || $_FILES["pdf_file"] || ($this->input->post('item_name') && $_FILES["pdf_file"])) {
            $data = array(
                'item_manu_name' => $this->input->post('item_name'),
                'item_id' => $this->input->post('item_id'),
                'doc' => $_FILES["pdf_file"]
            );
            $result = $this->admin_section_model->editItems_Manu($data);
        }
        if ($this->input->post('manufacturer_name')) {
            $data1 = array(
                'manufacturer_name' => $this->input->post('manufacturer_name'),
                'manufacturer_id' => $this->input->post('manufacturer_id'),
            );

            $result1 = $this->admin_section_model->editManufacturer($data1);
        }

        if ($result || $result1 || ($result && $result1)) {
            if (($result) && (!$result1)) {
                for ($m = 0; $m < count($result); $m++) {
                    $this->logThis("Updated Item Manu", "item_manu", $result[$m]);
                }
            }
            if (($result1) && (!$result)) {
                for ($n = 0; $n < count($result1); $n++) {
                    $this->logThis("Updated Manufacturer", "manufacturer_list", $result1[$n]);
                }
            }
            if (($result) && ($result1)) {
                for ($m = 0; $m < count($result); $m++) {
                    $this->logThis("Updated Item Manu", "item_manu", $result[$m]);
                }
                for ($n = 0; $n < count($result1); $n++) {
                    $this->logThis("Updated Manufacturer", "manufacturer_list", $result1[$n]);
                }
            }
            $this->session->set_flashdata('success', 'Item(s) and Manufacturer(s) Updated Successfully');
            redirect("admin_section/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Item(s) and Manufacturer(s) Could Not Be Updated Successfully');
            redirect("admin_section/", "refresh");
        }
    }

    // Action For Load Admin Owner
    public function admin_owner() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');
        $this->load->model('locations_model');

        $owners = $this->admin_section_model->ownerlist($arrPageData['arrSessionData']['objSystemUser']->accountid);

        $arrPageData['owners'] = $owners;
        $location = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['location'] = $location['results'];

        $arrPageData['customer_data'] = $arrPageData['arrSessionData']['objSystemUser']->accountname;

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_owner', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Action To Disable Owner

    public function disableOwner($ownerid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->disableowner($ownerid);
        if ($result) {

            $this->session->set_flashdata('success', 'Owner Disabled Successfully');
            redirect("admin_section/admin_owner/", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to disabling this data field.");
            redirect("admin_section/admin_owner/", "refresh");
        }
    }

    // Action To Enable Owner
    public function enableOwner($ownerid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->enableowner($ownerid);
        if ($result) {
            $this->session->set_flashdata('success', 'Owner Enabled Successfully');
            redirect("admin_section/admin_owner/", "refresh");
        }
    }

    // Action To Add Multiple Owners
    public function add_multipleowners() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $data = array();
        $data = array('ownername' => explode("\n", $this->input->post('owners')), 'location_id' => $this->input->post('multi_location_id'));
        foreach ($data as $key => $value) {
            if ($value == '') {
                unset($data[$key]);
            }
        }


        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->multiple_owner($data);
        if ($result) {
            $this->session->set_flashdata('success', 'Owner(s) Added Successfully');
            redirect("admin_section/admin_owner/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Owner(s) Could Not Be Added Successfully');
            redirect("admin_section/admin_owner/", "refresh");
        }
    }

    // Action For archive Owner.
    public function archiveOwner($ownerid) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');


        $result = $this->admin_section_model->archiveOwner($ownerid);


        if ($result) {
            $this->logThis("Archived Owner", "owner", $ownerid);
            $this->session->set_flashdata('success', 'Owner Archive Successfully');
            redirect("admin_section/admin_owner/", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to archiving this data field.");
            redirect("admin_section/admin_owner/", "refresh");
        }
    }

// Add Owner
    public function add_owner() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $response = $this->admin_section_model->add_owner();
        if ($response) {
            $this->logThis("Added Owner", "owner", $response);
            $this->session->set_flashdata('success', 'Owner Added Successfully');
            redirect("admin_section/admin_owner", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Owner');
            redirect("admin_section/admin_owner", "refresh");
        }
    }

    // Edit Owner
    public function editOwner() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('admin_section_model');

            $editOwner = array(
                'ownername' => $this->input->post('edit_owner_name'),
                'adminuser_id' => $this->input->post('adminuser_id'),
                'location_id' => $this->input->post('edit_location_id'),
            );



            $result = $this->admin_section_model->edit_owner($editOwner);
            if ($result) {
                $this->logThis("Updated Owner", "owner", $editOwner['adminuser_id']);
                $this->session->set_flashdata('success', 'Owner Edited Successfully');
                redirect("admin_section/admin_owner/", "refresh");
            }
        }
    }

    // load categories
    public function admin_categories() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $arrPageData['custom_field'] = $this->admin_section_model->getCustomField($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['all_user'] = $this->admin_section_model->getUser($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['get_category'] = $this->admin_section_model->getCategory($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['user_data'] = $arrPageData['arrSessionData']['objSystemUser']->accountid;
        $arrPageData['customer_data'] = $this->admin_section_model->getCustomerName($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['supplier_user'] = $this->admin_section_model->getSupplierUser($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['alertEmail'] = $this->admin_section_model->alertEmailList($arrPageData['arrSessionData']['objSystemUser']->accountid);

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_categories', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Get Category Name
    public function checkCategory() {
        if ($this->input->post()) {
            $this->load->model(categories_model);
            $result = $this->categories_model->doCheckCategoryNameIsUniqueOnAccount($this->input->post('category'), $this->input->post('account_id'));
            echo $result;
            die;
        }
    }

    // Get Edit Category Data
    public function getcategorydata($category_id) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->load->model('admin_section_model');

        $getcategory = $this->admin_section_model->getcategorydata($arrPageData['arrSessionData']['objSystemUser']->accountid, $category_id);
        echo json_encode($getcategory);
        die;
    }

    // Action for Add Category
    public function addCategory() {
        if ($this->input->post()) {
            $this->load->model('admin_section_model');
            $arrCategory = array(
                'name' => $this->input->post('category_name'),
                'account_id' => $this->input->post('account_id'),
                'active' => 1,
                'supplier_user' => $this->input->post('select_anather_user'),
            );

            $custom_fields = $this->input->post('custom_fields');
            $custom_data = array_values(array_filter($custom_fields));
            foreach ($custom_data as $custom_ids) {
                if (!empty($custom_ids)) {
                    $arrCategory['custom_fields'] = json_encode($custom_data);
                }
            }
            if ($this->input->post('select_user1') == '' && $this->input->post('select_user2') == '' && $this->input->post('select_user3') == '' && $this->input->post('add_email') == '' && $this->input->post('select_anather_user') == '' && $this->input->post('add_another_email') == '') {
                $support_emails = '';
            } else {
                $support_emails = $this->input->post('select_user1') . "," . $this->input->post('select_user2') . "," . $this->input->post('select_user3') . "," . $this->input->post('add_email') . "," . $this->input->post('select_anather_user') . "," . $this->input->post('add_another_email');


                $arr = explode(",", $support_emails);
                for ($i = 0; $i < count($arr); $i++) {
                    if ($arr[$i] != "") {
                        $arr1[] = $arr[$i];
                    } else {
                        $arr1[] = "";
                    }
                }

                $mails = implode(",", $arr1);

                $arrCategory['support_emails'] = $mails;
            }
            $result = $this->admin_section_model->addCategory($arrCategory);
            if ($result) {
                $this->logThis("Added Category", "categories", $result);
                $this->session->set_flashdata('success', 'Category Added Successfully');
                redirect("admin_section/admin_categories/", "refresh");
            }
        }
    }

    // Action for Edit Category
    public function editCategory() {
        if ($this->input->post()) {
            $this->load->model('admin_section_model');

            $editCategory = array(
                'name' => $this->input->post('edit_category_name'),
                'category_id' => $this->input->post('category_id'),
            );
            if ($this->input->post('edit_select_anather_user') != '1') {
                $editCategory['supplier_user'] = $this->input->post('edit_select_anather_user');
            }
            $custom_fields = $this->input->post('custom_fields');
            $custom_data = array_values(array_filter($custom_fields));

            foreach ($custom_data as $custom_ids) {
                if (!empty($custom_ids)) {
                    $editCategory['custom_fields'] = json_encode($custom_data);
                }
            }

            if ($this->input->post('edit_select_user1') == '' && $this->input->post('edit_select_user2') == '' && $this->input->post('edit_select_user3') == '' && $this->input->post('edit_email') == '' && $this->input->post('edit_select_user4') == '' && $this->input->post('edit_add_another_email') == '') {
                $support_emails = '';
            }
            $support_emails = $this->input->post('edit_selectuser1') . "," . $this->input->post('edit_selectuser2') . "," . $this->input->post('edit_selectuser3') . "," . $this->input->post('edit_email') . "," . $this->input->post('edit_select_user4') . "," . $this->input->post('edit_add_another_email');


            $arr = explode(",", $support_emails);
            for ($i = 0; $i < count($arr); $i++) {
                if ($arr[$i] != "") {
                    $arr1[] = $arr[$i];
                } else {
                    $arr1[] = "";
                }
            }


            $mails = implode(",", $arr1);
            $editCategory['support_emails'] = $mails;

            $result = $this->admin_section_model->editCategory($editCategory);

            if ($result) {
                $this->logThis("Updated Category", "categories", $editCategory['category_id']);
                $this->session->set_flashdata('success', 'Category Edited Successfully');
                redirect("admin_section/admin_categories/", "refresh");
            }
        }
    }

    // Action For Disable Category.
    public function disableCategory($category_id) {


        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->disableCategory($category_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Category Disable Successfully');
            redirect("admin_section/admin_categories/", "refresh");
        } else {
            $this->session->set_flashdata('arrCourier', " Assets are associated with this data field, please change all asset data prior to disabling this data field.");
            redirect("admin_section/admin_categories/", "refresh");
        }
    }

    // Action For Enaable Category.
    public function enableCategory($category_id) {


        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->enableCategory($category_id);
        if ($result) {
            $this->session->set_flashdata('success', 'Category Enable Successfully');
            redirect("admin_section/admin_categories/", "refresh");
        }
    }

// action to add multiple categories
    public function addMultipleCategory() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $data = array();
        $categories = $this->input->post('count_row');
        $this->load->model('admin_section_model');
        $this->load->model('categories_model');

        for ($i = 1; $i <= $categories; $i++) {
            if ($this->input->post('category_name_' . $i) != '') {
                $custom_fields = $this->input->post('custom_field_' . $i);
                //  array_filter($custom_fields);
                $custom_data = array_values(array_filter($custom_fields));

                $data = array(
                    'name' => $this->input->post('category_name_' . $i),
                    'account_id' => $this->input->post('account_id'),
                    'support_emails' => $this->input->post('multi_select_user1_' . $i) . "," . $this->input->post('multi_select_user2_' . $i),
                    'archive' => 1
                );
                if (!empty($custom_data)) {
                    $data['custom_fields'] = json_encode($custom_data);
                }


                if ($this->categories_model->doCheckCategoryNameIsUniqueOnAccount($data['name'], $data['account_id'])) {
                    $result = $this->admin_section_model->addMultipleCategory($data);
                } else {
                    continue;
                }
            }
        }

        if ($result) {
            $this->session->set_flashdata('success', 'Category(s) Added Successfully');
            redirect("admin_section/admin_categories/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Category(s) Could Not Be Added Successfully');
            redirect("admin_section/admin_categories/", "refresh");
        }
    }

    // Action For archive Category.
    public function archiveCategory($category_id) {


        $this->load->model('categories_model');
        $result = $this->categories_model->deleteOne($category_id);

        if ($result) {
            $this->logThis("Archived Category", "categories", $category_id);
            $this->session->set_flashdata('success', 'Category Archived Successfully');
            redirect("admin_section/admin_categories/", "refresh");
        } else {

            $this->session->set_flashdata('arrCourier', "Assets are associated with this data field, please change all asset data prior to archiving this data field.");
            redirect("admin_section/admin_categories/", "refresh");
        }
    }

    // Action For Load Admin User
    public function admin_user() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $this->load->model('suppliers_model');

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $arrPageData['arrSuppliers'] = $this->suppliers_model->getAll();
        $level = $this->db->get('levels')->result();
        $users = $this->admin_section_model->userlist($arrPageData['arrSessionData']['objSystemUser']->accountid);

        $arrPageData['access_level'] = $level;
        $arrPageData['users'] = $users;
        $arrPageData['customer_data'] = $arrPageData['arrSessionData']['objSystemUser']->accountname;
        $supplier = $this->admin_section_model->supplieruserlist($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['supplier_users'] = $supplier;

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_user', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // check uniqueness of username
    public function checkUsername() {

        $this->load->model('admin_section_model');
        $res = $this->admin_section_model->check_username(trim($this->input->post('username')));
        echo $res;
        die;
    }

// Action To Disable User

    public function disableUser($userid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->disableuser($userid);
        if ($result) {

            $this->session->set_flashdata('success', 'User Disabled Successfully');
            redirect("admin_section/admin_user/", "refresh");
        }
    }

// Action To Enaable User
    public function enableUser($userid) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->enableuser($userid);
        if ($result) {
            $this->session->set_flashdata('success', 'User Enabled Successfully');
            redirect("admin_section/admin_user/", "refresh");
        }
    }

    // Action For archive User.
    public function archiveUser($user_id) {

        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->archiveUser($user_id);
        if ($result) {
            $this->logThis("Archive User", "users", $user_id);
            $this->session->set_flashdata('success', 'User Archived Successfully');
            redirect("admin_section/admin_user/", "refresh");
        }
    }

    // Action For archive Supplier.
    public function archiveSupplier($supplier_id) {

        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->archive_Supplier($supplier_id);
        if ($result) {
            $this->logThis("Archived Supplier", "suppliers", $supplier_id);
            $this->session->set_flashdata('success', 'Supplier Archived Successfully');
            redirect("admin_section/admin_supplier/", "refresh");
        }
    }

    // Add User
    public function add_user() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        if ($this->input->post('my-checkbox') == 'on') {
            $notification = 1;
        } else {
            $notification = 0;
        }
        $data = array('firstname' => strtolower($this->input->post('first_name')),
            'lastname' => $this->input->post('last_name'),
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('contact_password')),
            'level_id' => $this->input->post('access_level'),
            'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            'active' => 1,
            'push_notification' => $notification
        );
        if ($this->input->post('add_owner') == 1) {
            $data['is_owner'] = 1;
        }

        $response = $this->admin_section_model->add_users($data);
        if ($response) {
            $this->logThis("Added User", "users", $response);
            $this->session->set_flashdata('success', 'User Added Successfully');
            redirect("admin_section/admin_user", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding User');
            redirect("admin_section/admin_user", "refresh");
        }
    }

    // Edit User Password
    public function edit_user() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('admin_section_model');
            $this->form_validation->set_rules('new_password', 'Password', 'trim|md5');
            if ($this->form_validation->run()) {
                $changeUserPassword = array(
                    'new_password' => $this->input->post('new_password'),
                    'adminuser_id' => $this->input->post('adminuser_id')
                );
                if ($changeUserPassword['new_password'] != '') {
                    $result = $this->admin_section_model->changeUserPassword($changeUserPassword);
                    if ($result) {
                        $this->session->set_flashdata('success', 'User Password Changed Successfully');
                        redirect("admin_section/admin_user/", "refresh");
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password Could Not Updated Successfully');
                    redirect("admin_section/admin_user/", "refresh");
                }
            }
        }
    }

    // Edit User
    public function editUser() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('admin_section_model');
            if ($this->input->post('my-checkbox') == 'on') {
                $notify = 1;
            } else {

                $notify = 0;
            }
            $editUser = array(
                'firstname' => $this->input->post('edit_first_name'),
                'lastname' => $this->input->post('edit_last_name'),
                'level' => $this->input->post('edit_access_level'),
                'adminuser_id' => $this->input->post('adminuser_id'),
                'username' => $this->input->post('edit_username'),
                'push_notification' => $notify
            );



            $result = $this->admin_section_model->editUser($editUser);
            if ($result) {
                $this->logThis("Updated User", "users", $editUser['adminuser_id']);
                $this->session->set_flashdata('success', 'User Edited Successfully');
                redirect("admin_section/admin_user/", "refresh");
            }
        }
    }

// action to add multiple users
    public function add_multipleusers() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $data = array();
        $users = $this->input->post('users');
        for ($i = 1; $i <= $users; $i++) {
            if ($this->input->post('multiple-notify' . $i) == 'on') {
                $notify = 1;
            } else {
                $notify = 0;
            }
            $data[] = array('first_name' => $this->input->post('first_name' . $i),
                'last_name' => $this->input->post('last_name' . $i),
                'user_name' => $this->input->post('user_name' . $i),
                'mpassword' => md5($this->input->post('mpassword' . $i)),
                'level' => $this->input->post('level' . $i),
                'owner' => $this->input->post('add_owner' . $i),
                'push_notification' => $notify);
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->add_multiple($data);
        if ($result) {
            for ($k = 0; $k < count($result); $k++) {
                $this->logThis("Added User", "users", $result[$k]);
            }
            $this->session->set_flashdata('success', 'User(s) Added Successfully');
            redirect("admin_section/admin_user/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'User(s) Could Not Be Added Successfully');
            redirect("admin_section/admin_user/", "refresh");
        }
    }

// Action For Load Admin Site
    public function admin_sites() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $sites = $this->admin_section_model->sitelist($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $customer_detail = $arrPageData['arrSessionData']['objSystemUser']->accountname;
        $arrPageData['sites'] = $sites;
        $arrPageData['customer_data'] = $customer_detail;
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_sites', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

// Add Site
    public function add_site() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');

        $response = $this->admin_section_model->add_site();
        if ($response) {
            $this->logThis("Added Sites", "sites", $response);
            $this->session->set_flashdata('success', 'Site Added Successfully');
            redirect("admin_section/admin_sites", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Site');
            redirect("admin_section/admin_sites", "refresh");
        }
    }

    // Edit Site
    public function editSite() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('admin_section_model');

            $editSite = array(
                'sitename' => $this->input->post('edit_site_name'),
                'adminuser_id' => $this->input->post('adminuser_id'),
            );

            $result = $this->admin_section_model->editSite($editSite);
            if ($result) {
                $this->logThis("Updated Sites", "sites", $editSite['adminuser_id']);
                $this->session->set_flashdata('success', 'Site Edited Successfully');
                redirect("admin_section/admin_sites/", "refresh");
            }
        }
    }

    // Action To Disable Site

    public function disableSite($siteid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->disablesite($siteid);
        if ($result) {

            $this->session->set_flashdata('success', 'Site Disabled Successfully');
            redirect("admin_section/admin_sites/", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to disabling this data field.");
            redirect("admin_section/admin_sites/", "refresh");
        }
    }

    // Action To Enable Site
    public function enableSite($siteid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->enablesite($siteid);
        if ($result) {
            $this->session->set_flashdata('success', 'Site Enabled Successfully');
            redirect("admin_section/admin_sites/", "refresh");
        }
    }

// Action to add multiple sites
    public function add_multiplesites() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $data = array();
//        $sites = $this->input->post('site_name');
//        $text = trim($_POST['site_name']); // remove the last \n or whitespace character

        $data = array('sitename' => explode("\n", $this->input->post('site_name')));
        foreach ($data as $key => $value) {
            if ($value == '') {
                unset($data[$key]);
            }
        }
//        for ($i = 1; $i <= $sites; $i++) {
//            $data[] = array('sitename' => $this->input->post('site_name_' . $i));
//        }

        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->multiple_site($data);
        if ($result) {
            for ($j = 0; $j < count($result); $j++) {
                $this->logThis("Added Site", "sites", $result[$j]);
            }
            $this->session->set_flashdata('success', 'Site(s) Added Successfully');
            redirect("admin_section/admin_sites/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Site(s) Could Not Be Added Successfully');
            redirect("admin_section/admin_sites/", "refresh");
        }
    }

    // Action For archive Owner.
    public function archiveSite($site_id) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->archiveSite($site_id);
        if ($result) {
            $this->logThis("Archived Sites", "sites", $site_id);
            $this->session->set_flashdata('success', 'Site Archived Successfully');
            redirect("admin_section/admin_sites/", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to archiving this data field.");
            redirect("admin_section/admin_sites/", "refresh");
        }
    }

// Action For Load Admin Location
    public function admin_location() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $this->load->model('sites_model');
        $this->load->model('users_model');
        $this->load->model('audits_model');
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $locations = $this->admin_section_model->locationlist($arrPageData['arrSessionData']['objSystemUser']->accountid);

        $arrPageData['arrSites'] = $this->sites_model->getAll($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['arrOwners'] = $this->users_model->getAllForOwner($this->session->userdata('objSystemUser')->accountid);

        $customer_detail = $arrPageData['arrSessionData']['objSystemUser']->accountname;

        for ($i = 0; $i < count($locations); $i++) {
            $locations[$i]->loc_date = $this->audits_model->getLastAuditForLocation($locations[$i]->id);
        }
        $arrPageData['locations'] = $locations;
        $arrPageData['customer_data'] = $customer_detail;

        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_location', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // Add Location
    public function add_location() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $response = $this->admin_section_model->add_location();
        if ($response) {
            $this->logThis("Added Location", "locations", $response);
            $this->session->set_flashdata('success', 'Location Added Successfully');
            redirect("admin_section/admin_location", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Location');
            redirect("admin_section/admin_location", "refresh");
        }
    }

    // Edit Location
    public function editLocation() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
//        var_dump($this->input->post());die;
        if ($this->input->post()) {

            $this->load->model('admin_section_model');

            $editLocation = array(
                'locationname' => $this->input->post('edit_location_name'),
                'qrcode' => $this->session->userdata('objSystemUser')->qrcode . $this->input->post('edit_qr_code'),
                'sitename' => $this->input->post('edit_site_name'),
                'adminuser_id' => $this->input->post('adminuser_id'),
            );

            $result = $this->admin_section_model->editLocation($editLocation);
            if ($result) {
                $this->logThis("Updated Location", "locations", $editLocation['adminuser_id']);
                $this->session->set_flashdata('success', 'Location Edited Successfully');
                redirect("admin_section/admin_location", "refresh");
            }
        }
    }

    // Action To Disable Location

    public function disableLocation($locationid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->disablelocation($locationid);
        if ($result) {

            $this->session->set_flashdata('success', 'Location Disabled Successfully');
            redirect("admin_section/admin_location", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to disabling this data field.");
            redirect("admin_section/admin_location/", "refresh");
        }
    }

    // Action To Enable Location
    public function enableLocation($locationid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->enablelocation($locationid);
        if ($result) {
            $this->session->set_flashdata('success', 'Location Enabled Successfully');
            redirect("admin_section/admin_location", "refresh");
        }
    }

// Action To Add Multiple Location
    public function add_multiplelocations() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $data = array();
        $number_of_location = $this->input->post('count_row');
        for ($i = 1; $i <= $number_of_location; $i++) {
            $data[] = array('locationname' => $this->input->post('location_name_' . $i),
                'qrcode' => $this->input->post('qrcode_' . $i),
                'sitename' => $this->input->post('site_name_' . $i),
                'owner_id' => $this->input->post('multi_owner_id_' . $i));
        }

        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->multiple_location($data);
        if ($result) {
            for ($j = 0; $j < count($result); $j++) {
                $this->logThis("Added Location", "locations", $result[$j]);
            }
            $this->session->set_flashdata('success', 'Location(s) Added Successfully');
            redirect("admin_section/admin_location/", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Location(s) Could Not Be Added Successfully');
            redirect("admin_section/admin_location/", "refresh");
        }
    }

    // Action For archive Location.
    public function archiveLocation($location_id) {


        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->archiveLocation($location_id);
        if ($result) {
            $this->logThis("Archived Locations", "locations", $location_id);
            $this->session->set_flashdata('success', 'Location Archived Successfully');
            redirect("admin_section/admin_location/", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to archiving this data field.");
            redirect("admin_section/admin_location/", "refresh");
        }
    }

    // check uniqueness of qrcode
    public function checkQRNumber() {

        $this->load->model('admin_section_model');
        $res = $this->admin_section_model->checkQRNumber(trim($this->input->post('qr_code')));
        echo $res;
        die;
    }

// Action to get all Custom Fields
    public function customFields() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

//        var_dump($arrPageData['arrSessionData']['objSystemUser']->accountid);    
        $arrPageData['arrCustomFields'] = $this->admin_section_model->getAll($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_customfields', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

// Action to add Custom Field
    public function add() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        $data = array();

        $data = explode("\n", trim($this->input->post('field_values')));
        $field_val = implode(',', $data);

        if ($this->input->post()) {
            $this->load->model('admin_section_model');

            $arrPageData['arrSessionData'] = $this->session->userdata;
            if ($this->input->post('field_type') == 'pick_list_type') {
                $values = $field_val;
            } else {
                $values = '';
            }
            $data = array(
                'field_name' => $this->input->post('field_name'),
                'field_value' => $this->input->post('field_type'),
                'pick_values' => $values,
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid
            );
            $arrPageData['custom_count'] = $this->admin_section_model->get_customcount($arrPageData['arrSessionData']['objSystemUser']->accountid);
//            var_dump($arrPageData['custom_count']);
//            var_dump($arrPageData['arrSessionData']['objSystemUser']->custom_count);
            $result = $this->admin_section_model->addField($data);
            if ($result) {
                $this->logThis("Added Custom Field", "custom_fields", $result);
                $this->session->set_flashdata('success', 'Custom Field Added Successfully');
                redirect("admin_section/customFields", "refresh");
            } else {
                $this->session->set_flashdata('error', 'you have reached the limit of your custom fields, please contact YouAudit to increase this limit');
                redirect("admin_section/customFields", "refresh");
            }
        }

        $this->load->view('common/header');
        $this->load->view('admin_section/admin_customfields');
        $this->load->view('common/footer');
    }

// Action to edit Custom Field
    public function edit() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        $data = array();

        $data = explode("\n", trim($this->input->post('field_values')));
        $field_val = implode(',', $data);

        if ($this->input->post()) {
            $this->load->model('admin_section_model');
            if ($this->input->post('field_type') == 'pick_list_type') {
                $values = $field_val;
            } else {
                $values = '';
            }

            $data = array(
                'field_name' => $this->input->post('edit_custom_name'),
                'field_value' => $this->input->post('field_type'),
                'pick_values' => $values,
                'id' => $this->input->post('custom_id')
            );


            $result = $this->admin_section_model->editField($data);
            if ($result) {
                $this->logThis("Updated Custom Field", "custom_fields", $data['id']);
                $this->session->set_flashdata('success', 'Custom Field Updated Successfully');
                redirect("admin_section/customFields", "refresh");
            } else {
                $this->session->set_flashdata('error', 'Custom Field Could Not Be Updated Successfully');
                redirect("admin_section/customFields", "refresh");
            }
        }
    }

// Action to delete Custom Field
    public function delete($field_id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->deleteField($field_id);

        if ($result) {
            $this->logThis("Archived Custom Field", "custom_fields", $field_id);
            $this->session->set_flashdata('success', 'Custom Field Deleted Successfully');
            redirect("admin_section/customFields", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Custom Field Could Not Be Deleted Successfully');
            redirect("admin_section/customFields", "refresh");
        }
    }

// Action For Load Admin Supplier
    public function admin_supplier() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $suppliers = $this->admin_section_model->supplierlist();
        $arrPageData['suppliers'] = $suppliers;
        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_supplier', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

// Get Supplier Detail
    public function getsupplierdata($supplier_id) {
        $this->load->model('admin_section_model');
        $supplierdata = $this->admin_section_model->get_supplier($supplier_id);
        if ($supplierdata) {
            echo json_encode($supplierdata);
            die;
        } else {
            echo '';
        }
    }

    // check unique ref number
    public function checkrefnumber() {

        $this->load->model('admin_section_model');
        $res = $this->admin_section_model->check_refnumber(trim($this->input->post('ref_no')));
        echo $res;
        die;
    }

// Action To Disable Supplier

    public function disableSupplier($supplierid) {

        if (!$this->session->userdata('booUserLogin')) {
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->disablesupplier($supplierid);
        if ($result) {

            $this->session->set_flashdata('success', 'Supplier Disabled Successfully');
            redirect("admin_section/admin_supplier", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to disabling this data field.");
            redirect("admin_section/admin_supplier/", "refresh");
        }
    }

    // Action To Enable Supplier
    public function enableSupplier($supplierid) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->enablesupplier($supplierid);
        if ($result) {
            $this->session->set_flashdata('success', 'Supplier Enabled Successfully');
            redirect("admin_section/admin_supplier", "refresh");
        }
    }

    // Action To Archive Supplier

    public function archivedSupplier($supplierid) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->archivesupplier($supplierid);
        if ($result) {
            $this->logThis("Archived Supplier", "suppliers", $supplierid);
            $this->session->set_flashdata('success', 'Supplier Added To Archive Successfully');
            redirect("admin_section/admin_supplier", "refresh");
        }
    }

    // Action To Add Supplier
    public function add_supplier() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $response = $this->admin_section_model->add_suppliers();
        if ($response) {
            $this->logThis("Added Supplier", "suppliers", $response);
            $this->session->set_flashdata('success', 'Supplier Added Successfully');
            redirect("admin_section/admin_supplier", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding Supplier');
            redirect("admin_section/admin_supplier", "refresh");
        }
    }

    // Edit Supplier
    public function edit_supplier() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('admin_section_model');
            $supplier_id = $this->input->post('adminuser_id');
            $result = $this->admin_section_model->editSupplier();
            if ($result) {
                $this->logThis("Updated Supplier", "suppliers", $supplier_id);
                $this->session->set_flashdata('success', 'Supplier Edited Successfully');
                redirect("admin_section/admin_supplier", "refresh");
            }
        }
    }

    // Action For Load Admin Archive
    public function admin_archive() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');

        $arrPageData['get_category'] = $this->admin_section_model->archiveCategoryList($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['custom_field'] = $this->admin_section_model->getCustomField($arrPageData['arrSessionData']['objSystemUser']->accountid);

        $arrPageData['location'] = $this->admin_section_model->archiveLocationList($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['owner'] = $this->admin_section_model->archiveOwnerList($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['site'] = $this->admin_section_model->archiveSiteList($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['user'] = $this->admin_section_model->archiveUserList($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $suppliers = $this->admin_section_model->archiveSupplierList($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['suppliers'] = $suppliers;
        $arrPageData['customer_data'] = $arrPageData['arrSessionData']['objSystemUser']->accountname;


        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/admin_archive', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

// Action to restore users
    public function restoreUser($userID) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->restoreUser($userID);
        if ($result) {

            $this->session->set_flashdata('success', 'User Restore Successfully');
            redirect("admin_section/admin_archive/", "refresh");
        }
    }
    
    // Action to restore users
    public function restoreSupplier($supplierID) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->restoreSupplier($supplierID);
        if ($result) {

            $this->session->set_flashdata('success', 'Supplier Restored Successfully');
            redirect("admin_section/admin_archive/", "refresh");
        }
    }

// Action to restore owners
    public function restoreOwner($ownerID) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->restoreOwner($ownerID);
        if ($result) {

            $this->session->set_flashdata('success', 'Owner Restore Successfully');
            redirect("admin_section/admin_archive/", "refresh");
        }
    }

// Action to restore categories
    public function restoreCategory($categoryID) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {

            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->restoreCategory($categoryID);
        if ($result) {
            $this->session->set_flashdata('success', 'Category Restore Successfully');
            redirect("admin_section/admin_archive/", "refresh");
        }
    }

// Action to restore sites
    public function restoreSite($siteID) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {

            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->restoreSite($siteID);
        if ($result) {
            $this->session->set_flashdata('success', 'Site Restore Successfully');
            redirect("admin_section/admin_archive/", "refresh");
        }
    }

// Action to restore location
    public function restoreLocation($locationID) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {

            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->restoreLocation($locationID);
        if ($result) {
            $this->session->set_flashdata('success', 'Location Restore Successfully');
            redirect("admin_section/admin_archive/", "refresh");
        }
    }

//     Export PDF For Owner
    public function exportPDFForOwner($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');

        $owners = $this->admin_section_model->ownerlist($arrPageData['arrSessionData']['objSystemUser']->accountid, $type);


        echo "<pre>";
        var_dump($owners);
        echo "</pre>";
        die("here");
    }

    //     Export PDF For User
    public function exportPDFForUser($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');

        $users = $this->admin_section_model->userlist($arrPageData['arrSessionData']['objSystemUser']->accountid, $type);


        echo "<pre>";
        var_dump($users);
        echo "</pre>";
        die("here");
    }

    //     Export PDF For category
    public function exportPDFForCategory($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');

        $categories = $this->admin_section_model->getCategory($arrPageData['arrSessionData']['objSystemUser']->accountid, $type);

        echo "<pre>";
        var_dump($categories);
        echo "</pre>";
        die("here");
    }

    //     Export PDF For Item
    public function exportPDFForItem($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');

        $items = $this->admin_section_model->getItem_Manu($arrPageData['arrSessionData']['objSystemUser']->accountid, $type);

        echo "<pre>";
        var_dump($items);
        echo "</pre>";
        die("here");
    }

    //     Export PDF For Site
    public function exportPDFForSite($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');

        $sites = $this->admin_section_model->sitelist($arrPageData['arrSessionData']['objSystemUser']->accountid, $type);

        echo "<pre>";
        var_dump($sites);
        echo "</pre>";
        die("here");
    }

//     Export PDF For Location
    public function exportPDFForLocation($type = '') {
        $this->load->model('admin_section_model');
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();



        $locations = $this->admin_section_model->locationlist($arrPageData['arrSessionData']['objSystemUser']->accountid, $type);

        echo "<pre>";
        var_dump($locations);
        echo "</pre>";
        die("here");
    }

//     Export PDF For Supplier
    public function exportPDFForSupplier($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('admin_section_model');

        $suppliers = $this->admin_section_model->supplierlist($type);

        echo "<pre>";
        var_dump($suppliers);
        echo "</pre>";
        die("here");
    }

    public function editMultipleUser() {
        $this->load->model('admin_section_model');

        if ($this->input->post()) {
            $result = $this->admin_section_model->updateMultipleUser($this->input->post());

            if ($result) {

                $this->session->set_flashdata('success', 'User Edit Successfully');
                redirect("admin_section/admin_user/", "refresh");
            } else {
                
            }
        }
    }

    public function userPdf($userID = '') {

        if ($userID) {
            $this->load->model('admin_section_model');
            $this->admin_section_model->userPdf($userID);
            echo "<pre>";
            var_dump($suppliers);
            echo "</pre>";
            die("here");
        }
    }

    // Action For archive Item.
    public function archiveItem($item_id) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->archiveItem($item_id);
        if ($result) {
            $this->logThis("Archived Item", "item_manu", $item_id);
            $this->session->set_flashdata('success', 'Item Deleted Successfully');
            redirect("admin_section/", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to archiving this data field.");
            redirect("admin_section/", "refresh");
        }
    }

    // Action For archive Manufacturer.
    public function archiveManufacturer($manufacturer_id) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $result = $this->admin_section_model->archiveManufacturer($manufacturer_id);
        if ($result) {
            $this->logThis("Archived Manufacturer", "manufacturer_list", $manufacturer_id);
            $this->session->set_flashdata('success', 'Manufacturer Deleted Successfully');
            redirect("admin_section/", "refresh");
        } else {

            $this->session->set_flashdata('error', "Assets are associated with this data field, please change all asset data prior to archiving this data field.");
            redirect("admin_section/", "refresh");
        }
    }

    // Add User
    public function add_supplier_user() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('admin_section_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $data = array('firstname' => $this->input->post('supplier_first_name'),
            'lastname' => $this->input->post('supplier_last_name'),
            'username' => $this->input->post('supplier_username'),
            'password' => md5($this->input->post('supplier__password')),
            'level_id' => 5,
            'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            'active' => 1
        );
        $response = $this->admin_section_model->add_supplier_user($data, $this->input->post('supplier_id'));
        if ($response) {
            $this->logThis("Added Supplier", "users", $response);
            $this->session->set_flashdata('success', 'Supplier User Added Successfully');
            redirect("admin_section/admin_user", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Adding User');
            redirect("admin_section/admin_user", "refresh");
        }
    }

    // Edit User
    public function editSupplierUser() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/sites/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        if ($this->input->post()) {

            $this->load->model('admin_section_model');

            $editUser = array(
                'firstname' => $this->input->post('edit_supplier_first_name'),
                'lastname' => $this->input->post('edit_supplier_last_name'),
                'supplier_id' => $this->input->post('edit_supplier_id'),
                'adminuser_id' => $this->input->post('supplieradminuser_id'),
            );

            $result = $this->admin_section_model->editSupplierUser($editUser);

            if ($result) {
                $this->logThis("Updated Supplier", "users", $editUser['adminuser_id']);
                $this->session->set_flashdata('success', 'Supplier User Edited Successfully');
                redirect("admin_section/admin_user/", "refresh");
            }
        }
    }

    public function data_import() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Admin Section";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->model('categories_model');
        $this->load->model('sites_model');
        $this->load->model('locations_model');
        $this->load->model('itemstatus_model');
        $this->load->model('suppliers_model');
        $this->load->model('admin_section_model');
        $this->load->model('users_model');
        $this->load->model('items_model');
        $arrCategories = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrSites = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrLocations = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrItemStatuses = $this->itemstatus_model->getAll();
        $arrSuppliers = $this->suppliers_model->getAll();
        $getitemmanu = $this->admin_section_model->getItem_Manu($this->session->userdata('objSystemUser')->accountid);
        $arrManufaturer = $this->admin_section_model->getManufacturer($this->session->userdata('objSystemUser')->accountid);
        $arrOwners = $this->users_model->getAllForOwner($this->session->userdata('objSystemUser')->accountid);
        $arrCondition = $this->items_model->get_condition();
        $account_id = $this->session->userdata('objSystemUser')->accountid;
        if ($this->input->post()) {
            $start_qrcode = $this->input->post('start_qrcode');
            $no_of_asset = $this->input->post('no_of_asset');
            $qr_code = $this->input->post('qr_code');
            $key = array('category', 'item', 'manufacturer', 'model', 'quantity', 'site', 'location', 'owner', 'serial_no', 'supplier', 'status', 'condition', 'purchase_date', 'purchase_price', 'warranty_date', 'replacement_date');


            $html = '';

            $f = fopen($_FILES['file']['tmp_name'], "r");
            $count = 0;
            while (($line = fgetcsv($f)) !== false) {
                $full_data = array_combine($key, $line);
                $my_array[] = $full_data;
            }
            $html = '';
            for ($i = 0; $i < count($my_array); $i++) {
                $total_qr = $start_qrcode + $i;

                if ($i < $no_of_asset) {
                    $html .= "<tr><td></td><td><input type='text' required disabled value='$qr_code$total_qr'  class='form-control'><input type='hidden' readonly value='$qr_code$total_qr' name='qrcode[]'></td>";
                    foreach ($my_array[$i] as $key => $value) {

                        if ($key == 'category') {

                            if ($this->admin_section_model->checkcategory($my_array[$i]['category'], $account_id) == 0) {

                                $html.="<td><input required readonly type='text'  class='form-control category' id='category' name='category[]' value=" . $my_array[$i]['category'] . ">";
                                $html.="<div class='import_error'></div></td>";
                            } else {

                                $html.="<td><select required readonly class='form-control category' name='category[]'>";
                                foreach ($arrCategories['results'] as $category) {
                                    if ($category->categoryname == $my_array[$i]['category']) {
                                        $str = "selected='selected'";
                                    } else {
                                        $str = '';
                                    }
                                    $html.="<option value=" . $category->categoryid . " $str>" . $category->categoryname . "</option>";
                                }
                                $html.="</select><div class='import_error'></div></td>";
                            }
                        }
                        if ($key == 'item') {


                            if ($this->admin_section_model->checkitem($my_array[$i]['item'], $account_id) == 0) {

                                $html.="<td><input readonly type='text' class='form-control' name='item[]' value=" . $my_array[$i]['item'] . ">";
                                $html.="</td>";
                            } else {
                                $html.="<td><select readonly class='form-control' name='item[]'>";
                                foreach ($getitemmanu['list'] as $item) {
                                    if ($item['item_manu_name'] == $my_array[$i]['item']) {
                                        $str = "selected='selected'";
                                    } else {
                                        $str = '';
                                    }
                                    $html.="<option value=" . $item['id'] . " $str>" . $item['item_manu_name'] . "</option>";
                                }
                                $html.="</select></td>";
                            }
                        }

                        if ($key == 'manufacturer') {
                            if ($this->admin_section_model->checkmanufacturer($my_array[$i]['manufacturer'], $account_id) == 0) {

                                $html.="<td><input readonly type='text' class='form-control' name='manufacturer[]' value=" . $my_array[$i]['manufacturer'] . ">";
                                $html.="</td>";
                            } else {
                                $html.="<td><select readonly class='form-control' name='manufacturer[]'>";
                                foreach ($arrManufaturer as $manufacturer) {
                                    if ($manufacturer['manufacturer_name'] == $my_array[$i]['manufacturer']) {
                                        $str = "selected='selected'";
                                    } else {
                                        $str = '';
                                    }
                                    $html.="<option value=" . $manufacturer['manufacturer_name'] . " $str>" . $manufacturer['manufacturer_name'] . "</option>";
                                }
                                $html.="</select></td>";
                            }
                        }
                        if ($key == 'model') {
                            $html.="<td><input readonly type='text' class='form-control' name='model[]' value=" . $my_array[$i]['model'] . ">";

                            $html.="</td>";
                        }
                        if ($key == 'quantity') {
                            if ($my_array[$i]['quantity'] == '') {
                                $quantity = 1;
                            } else {
                                $quantity = $my_array[$i]['quantity'];
                            }
                            $html.="<td><input readonly class='form-control' name='quantity[]' type='text' value=" . $quantity . ">";

                            $html.="</td>";
                        }


                        if ($key == 'site') {


                            if ($this->admin_section_model->checksite($my_array[$i]['site'], $account_id) == 0) {

                                $html.="<td><input type='text' required readonly class='form-control site' name='site[]'  value=" . $my_array[$i]['site'] . ">";
                                $html.="<div class='import_error'></div></td>";
                            } else {
                                $html.="<td><select readonly required class='form-control site' id='site_$i' data_id='$i' name='site[]'><option value='0'>----SELECT----</option>";
                                foreach ($arrSites['results'] as $arrSite) {
                                    if ($arrSite->sitename == $my_array[$i]['site']) {
                                        $str = "selected='selected'";
                                    } else {
                                        $str = '';
                                    }
                                    $html.="<option value=" . $arrSite->siteid . " $str>" . $arrSite->sitename . "</option>";
                                }
                                $html.="</select><div class='import_error'></div></td>";
                            }
                        }

                        if ($key == 'location') {
                            if ($this->admin_section_model->checklocation($my_array[$i]['location'], $account_id) == 0) {

                                $html.="<td><input required readonly type='text' class='form-control location' name='location[]' value=" . $my_array[$i]['location'] . ">";
                                $html.="<div class='import_error'></div></td>";
                            } else {
                                $html.="<td><select required readonly class='form-control location' id='location_$i' data_id='$i' name='location[]'> <option value='0'>----SELECT----</option>";
                                foreach ($arrLocations['results'] as $arrLocation) {
                                    if ($arrLocation->locationname == $my_array[$i]['location']) {
                                        $str = "selected='selected'";
                                    } else {
                                        $str = '';
                                    }
                                    $html.="<option value=" . $arrLocation->locationid . " $str>" . $arrLocation->locationname . "</option>";
                                }
                                $html.="</select><div class='import_error'></div></td>";
                            }
                        }

                        if ($key == 'owner') {
                            if ($this->admin_section_model->checkowner($my_array[$i]['owner'], $account_id) == 0) {

                                $html.="<td><input required readonly type='text' class='form-control owner' name='owner[]' value=" . $my_array[$i]['owner'] . ">";
                                $html.="<div class='import_error'></div></td>";
                            } else {
                                $html.="<td><select required readonly class='form-control owner' name='owner[]'>";
                                foreach ($arrOwners['results'] as $arrOwner) {
                                    if ($arrOwner->owner_name == $my_array[$i]['owner']) {
                                        $str = "selected='selected'";
                                    } else {
                                        $str = '';
                                    }
                                    $html.="<option value=" . $arrOwner->ownerid . " $str>" . $arrOwner->owner_name . "</option>";
                                }
                                $html.="</select><div class='import_error'></div></td>";
                            }
                        }
                        if ($key == 'serial_no') {
                            $html.="<td><input readonly type='text' class='form-control' name='serial_no[]' value=" . $my_array[$i]['serial_no'] . ">";

                            $html.="</td>";
                        }

                        if ($key == 'supplier') {
                            if ($this->admin_section_model->checksupplier($my_array[$i]['supplier'], $account_id) == 0) {

                                $html.="<td><input readonly type='text' class='form-control' name='supplier[]'  value=" . $my_array[$i]['supplier'] . ">";
                                $html.="</td>";
                            } else {
                                $html.="<td><select readonly class='form-control' name='supplier[]'>";
                                foreach ($arrSuppliers as $supplier) {
                                    if ($supplier['supplier_name'] == $my_array[$i]['supplier']) {
                                        $str = "selected='selected'";
                                    } else {
                                        $str = '';
                                    }
                                    $html.="<option value=" . $supplier['supplier_id'] . " $str>" . $supplier['supplier_name'] . "</option>";
                                }
                                $html.="</select></td>";
                            }
                        }

                        if ($key == 'status') {
                            $html.="<td><select readonly class='form-control' name='status[]'>";
                            foreach ($arrItemStatuses['results'] as $arrStatus) {
                                if ($arrStatus->statusname == $my_array[$i]['status']) {
                                    $str = "selected='selected'";
                                } else {
                                    $str = '';
                                }
                                $html.="<option value=" . $arrStatus->statusid . " $str>" . $arrStatus->statusname . "</option>";
                            }
                            $html.="</select></td>";
                        }

                        if ($key == 'condition') {
                            $html.="<td><select readonly class='form-control' name='condition[]' >";
                            foreach ($arrCondition as $con) {
                                if ($con['condition'] == $my_array[$i]['condition']) {
                                    $str = "selected='selected'";
                                } else {
                                    $str = '';
                                }
                                $html.="<option value=" . $con['id'] . " $str>" . $con['condition'] . "</option>";
                            }
                            $html.="</select></td>";
                        }

                        if ($key == 'purchase_date') {
                            $html.="<td><input readonly type='text' class='form-control item_date'  name='purchase_date[]' value=" . $my_array[$i]['purchase_date'] . ">";

                            $html.="</td>";
                        }



                        if ($key == 'purchase_price') {
                            $html.="<td><input type='text' readonly class='form-control' name='purchase_price[]'  value=" . $my_array[$i]['purchase_price'] . ">";

                            $html.="</td>";
                        }

                        if ($key == 'warranty_date') {
                            $html.="<td><input type='text' readonly class='form-control item_date' name='warranty_date[]' value=" . $my_array[$i]['warranty_date'] . ">";

                            $html.="</td>";
                        }

                        if ($key == 'replacement_date') {
                            $html.="<td><input type='text' readonly class='form-control item_date' name='replacement_date[]' value=" . $my_array[$i]['replacement_date'] . ">";

                            $html.="</td>";
                        }
                    }
                    $html.= "</tr>\n";
                }
            }
            fclose($f);
            $arrPageData['import_data'] = $html;
        }




        $this->load->view('common/header', $arrPageData);
        $this->load->view('admin_section/data_import', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function import_custom_field() {

        if ($this->input->post()) {
            $this->load->model('admin_section_model');

            $data = array(
                'field_name' => $this->input->post('field_name'),
                'field_value' => 'text_type',
                'account_id' => $this->session->userdata('objSystemUser')->accountid
            );


            $result = $this->admin_section_model->addField($data);
            if ($result) {
                echo $result;
            } else {
                echo 0;
            }
        }
    }

    public function doFormatDate($strDate) {
        if ($strDate != "") {
            $arrDate = explode('/', $strDate);
            return $arrDate[2] . "-" . $arrDate[1] . "-" . $arrDate[0];
        }
        return NULL;
    }

    public function add_import_data() {

        if ($this->input->post()) {
            $account_id = $this->session->userdata('objSystemUser')->accountid;
            $this->load->model('items_model');
            $this->load->model('categories_model');
            $this->load->model('admin_section_model');
            $this->load->model('tickets_model');
            $this->load->model('customfields_model');
            $qr_code = $this->input->post('qrcode');
            $category = $this->input->post('category');
            $item = $this->input->post('item');
            $manufacturer = $this->input->post('manufacturer');
            $model = $this->input->post('model');
            $quantity = $this->input->post('quantity');
            $site = $this->input->post('site');
            $location = $this->input->post('location');
            $owner = $this->input->post('owner');
            $supplier = $this->input->post('supplier');
            $serial_number = $this->input->post('serial_no');
            $status = $this->input->post('status');
            $condition = $this->input->post('condition');
            $purchase_date = $this->input->post('purchase_date');
            $purchase_price = $this->input->post('purchase_price');
            $warranty_date = $this->input->post('warranty_date');
            $warranty_date = $this->input->post('warranty_date');
            $replacement_date = $this->input->post('replacement_date');
            if ($this->input->post('custom_field_name')) {
                $custom_field_name = $this->input->post('custom_field_name');
            }




            for ($i = 0; $i < count($qr_code); $i++) {

                $item_details = array(
                    'qr_code' => $qr_code[$i],
                    'category' => $category[$i],
                    'item' => $item[$i],
                    'manufacturer' => $manufacturer[$i],
                    'model' => $model[$i],
                    'quantity' => $quantity[$i],
                    'site' => $site[$i],
                    'location' => $location[$i],
                    'owner' => $owner[$i],
                    'supplier' => $supplier[$i],
                    'serial_number' => $serial_number[$i],
                    'status' => $status[$i],
                    'condition' => $condition[$i],
                    'purchase_date' => $purchase_date[$i],
                    'purchase_price' => $purchase_price[$i],
                    'warranty_date' => $warranty_date[$i],
                    'replacement_date' => $replacement_date[$i],
                );

                for ($j = 0; $j < count($custom_field_name); $j++) {

                    $result = $this->customfields_model->getField($custom_field_name[$j]);
                    $content[] = $this->input->post($result->field_name);
                    $content_field[] = $item_details[$custom_field_name[$j]];
                    $item_details['custom_field'][$custom_field_name[$j]] = $content[$j][$i];
                }


                $my_new[] = $item_details;
            }






            for ($i = 0; $i < count($my_new); $i++) {
                foreach ($my_new as $item_value) {



                    $bool = $this->items_model->checkBarcodeForItem($item_value['qr_code']);

                    if (!$bool) {



                        /* ----------------------add qrcode-------------------------------------------- */

                        $data['barcode'] = $item_value['qr_code'];

                        /* ----------------------add category-------------------------------------------- */
                        if ($this->admin_section_model->checkcategory($item_value['category'], $account_id) == 0) {
                            $arrCategory = array(
                                'name' => $item_value['category'],
                                'account_id' => $account_id,
                                'active' => 1,
                            );
                            if (!empty($custom_field_name)) {
                                for ($j = 0; $j < count($custom_field_name); $j++) {
                                    $custom_fields[] = $custom_field_name[$j];
                                    $custom_data = array_values(array_filter($custom_fields));

                                    foreach ($custom_data as $custom_ids) {
                                        if (!empty($custom_ids)) {
                                            $arrCategory['custom_fields'] = json_encode($custom_data);
                                        }
                                    }
                                }
                            }

                            $category_id = $this->admin_section_model->addCategory($arrCategory);
                            if ($category_id == False) {
                                $category_id = $item_value['category'];
                            }
                        } else {
                            $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFields($item_value['category']);
                            foreach ($arrPageData['arrCustomFields'] as $custom) {
                                $custom_fields[] = $custom->id;
                                $custom_data = array_values(array_filter($custom_fields));
                            }
                            if (!empty($custom_field_name)) {
                                for ($j = 0; $j < count($custom_field_name); $j++) {
                                    $custom_fields[] = $custom_field_name[$j];
                                    $custom_data = array_values(array_filter($custom_fields));
                                }
                            }
                            foreach ($custom_data as $custom_ids) {
                                if (!empty($custom_ids)) {
                                    $arrCategory['custom_fields'] = json_encode($custom_data);
                                }
                            }
                            if (!empty($arrCategory)) {
                                $arrCategory['category_id'] = $item_value['category'];
                                $this->admin_section_model->editCustomField($arrCategory);
                            }
                            $category_id = $item_value['category'];
                        }

                        /* ---------------------------add item --------------------------------------- */
                        if ($this->admin_section_model->checkitem($item_value['item'], $account_id) == 0) {
                            $arrItem = array(
                                'item_manu_name' => $item_value['item'],
                                'account_id' => $account_id,
                            );
                            $data['item_manu'] = $this->admin_section_model->addItem_Manu($arrItem);
                            if ($data['item_manu'] == False) {
                                $data['item_manu'] = $item_value['item'];
                            }
                        } else {
                            $data['item_manu'] = $item_value['item'];
                        }

                        /* ---------------------------add manufecture --------------------------------------- */

                        if ($this->admin_section_model->checkmanufacturer($item_value['manufacturer'], $account_id) == 0) {
                            $manufacturer = array(
                                'manufacturer_name' => $item_value['manufacturer'],
                                'account_id' => $account_id,
                            );


                            $manufacturer = $this->admin_section_model->addManufacturer($manufacturer);
                            if ($manufacturer == False) {
                                $data['manufacturer'] = $item_value['manufacturer'];
                            } else {
                                $res = $this->admin_section_model->getmanufacturerbyid($manufacturer);
                                $data['manufacturer'] = $res->manufacturer_name;
                            }
                        } else {
                            $data['manufacturer'] = $item_value['manufacturer'];
                        }


                        /* ---------------------------add model --------------------------------------- */

                        $data['model'] = $item_value['model'];

                        /* ---------------------------add quantity --------------------------------------- */

                        $data['quantity'] = $item_value['quantity'];

                        /* ---------------------------add Site --------------------------------------- */
                        if ($this->admin_section_model->checksite($item_value['site'], $account_id) == 0) {
                            $sitedata = array('name' => $item_value['site'],
                                'account_id' => $account_id,
                                'active' => 1,
                                'archive' => 1
                            );
                            $data['site'] = $this->admin_section_model->import_site($sitedata);
                            if ($data['site'] == False) {
                                $data['site'] = $item_value['site'];
                            }
                        } else {
                            $data['site'] = $item_value['site'];
                        }

                        /* ---------------------------add location --------------------------------------- */
                        if ($this->admin_section_model->checklocation($item_value['location'], $account_id) == 0) {
                            $locationdata = array('name' => $item_value['location'],
                                'site_id' => $data['site'],
                                'account_id' => $account_id,
                                'active' => 1,
                                'archive' => 1);
                            $data['location_now'] = $this->admin_section_model->import_location($locationdata);
                            if ($data['location_now'] == False) {
                                $data['location_now'] = $item_value['location'];
                                $data['location_since'] = date('Y-m-d H:i:s');
                            }
                        } else {
                            $data['location_now'] = $item_value['location'];
                            $data['location_since'] = date('Y-m-d H:i:s');
                        }


                        /* ---------------------------add owner --------------------------------------- */
                        if ($this->admin_section_model->checkowner($item_value['owner'], $account_id) == 0) {
                            $ownerdata = array('owner_name' => $item_value['owner'],
                                'account_id' => $account_id,
                                'active' => 1,
                                'archive' => 1,
                                'location_id' => $item_value['location']
                            );
                            $data['owner_now'] = $this->admin_section_model->import_owner($ownerdata);
                            if ($data['owner_now'] == False) {
                                $data['owner_now'] = $item_value['owner'];
                            }
                            $data['owner_since'] = date('Y-m-d H:i:s');
                        } else {
                            $data['owner_now'] = $item_value['owner'];
                            $data['owner_since'] = date('Y-m-d H:i:s');
                        }

                        /* ---------------------------add supplier --------------------------------------- */
                        if ($this->admin_section_model->checksupplier($item_value['supplier'], $account_id) == 0) {
                            $supplierdata = array(
                                'supplier_name' => $item_value['supplier'],
                                'account_id' => $account_id,
                                'active' => 1,
                                'archive' => 1
                            );

                            $data['supplier'] = $this->admin_section_model->import_supplier($supplierdata);
                        } else {
                            $data['supplier'] = $item_value['supplier'];
                        }

                        /* ---------------------------add serial number --------------------------------------- */

                        $data['serial_number'] = $item_value['serial_number'];

                        /* ---------------------------add status --------------------------------------- */

                        $data['status_id'] = $item_value['status'];

                        /* ---------------------------add condition --------------------------------------- */

                        $data['condition_now'] = $item_value['condition'];
                        $data['condition_since'] = date('Y-m-d H:i:s');


                        /* ---------------------------purchase date --------------------------------------- */
                        if ($item_value['purchase_date'] == '') {
                            $data['purchase_date'] = null;
                        } else {
                            $data['purchase_date'] = $this->doFormatDate($item_value['purchase_date']);
                        }
                        /* ---------------------------purchase price --------------------------------------- */

                        $data['current_value'] = $item_value['purchase_price'];

                        /* ---------------------------warranty_date--------------------------------------- */
                        if ($item_value['warranty_date'] == '') {
                            $data['warranty_date'] = null;
                        } else {
                            $data['warranty_date'] = $this->doFormatDate($item_value['warranty_date']);
                        }
                        /* ---------------------------replacement_date--------------------------------------- */
                        if ($item_value['replacement_date'] == '') {
                            $data['replace_date'] = null;
                        } else {
                            $data['replace_date'] = $this->doFormatDate($item_value['replacement_date']);
                        }

                        $data['active'] = 1;
                        $data ['account_id'] = $account_id;





                        /* ---------------------------end--------------------------------------- */
                        $mixNewItemId = $this->items_model->addOne($data, $category_id, $data['location_now'], $this->session->userdata('objSystemUser')->userid, $data['site']);
                        if ($mixNewItemId) {
                            if ($data['status_id'] == 2 || $data['status_id'] == 3) {
                                $fault_data = array(
                                    'item_id' => $mixNewItemId,
                                    'user_id' => $this->session->userdata('objSystemUser')->userid,
                                    'date' => date("Y-m-d H:i:s"),
                                    'ticket_action' => "Open Job",
                                    'status' => $data['status_id'],
                                );
                                $this->tickets_model->insertTicket($fault_data);
                            }

                            $this->logThis("Added Item", "items", $mixNewItemId);

//                                Add Condition 
                            if ($data['condition_now']) {
                                $this->items_model->logConditionHistory($mixNewItemId, $data['condition_now']);
                            }



                            /* ---------------------------add custom field data--------------------------------------- */

                            if (!empty($item_value['custom_field'])) {
                                foreach ($item_value['custom_field'] as $key => $value) {
                                    $custom_data = array(
                                        'custom_field_id' => $key,
                                        'account_id' => $account_id,
                                        'item_id' => $mixNewItemId,
                                        'content' => $value,
                                        'category_id' => $category_id
                                    );
                                    $this->customfields_model->importcustomdata($custom_data);
                                }
                            }
                        } else {
                            $this->session->set_flashdata('error', 'There Is Some Error In Import Data');
                            redirect("admin_section/data_import", "refresh");
                        }
                    } else {
                        continue;
                    }
                }
            }
            $this->session->set_flashdata('success', 'Data Import Successfully');
            redirect("admin_section/data_import", "refresh");
        } else {
            $this->session->set_flashdata('error', 'There Is Some Error In Import Data');
            redirect("admin_section/data_import", "refresh");
        }
    }

    // edit multiple custom fields in categories

    public function editmulticategories() {
        $this->load->model('admin_section_model');

        if ($this->input->post()) {
            $this->admin_section_model->updateMultiplecategories($this->input->post());
        }
        redirect('admin_section/admin_categories/', 'refresh');
    }

    // Action for Edit Multiple Category
    public function editMultipleCategory() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }

        $data = array();
        $this->load->model('admin_section_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;

        if ($this->input->post('edit_categoryname')) {

            $data = array(
                'category_name' => $this->input->post('edit_categoryname'),
                'category_id' => $this->input->post('category_id'),
                'supportemails' => $this->input->post('editmultipleuser'),
            );

            $result = $this->admin_section_model->editmulticategories($data);
        }

        if ($result) {
            for ($s = 0; $s < count($result); $s++) {
                $this->logThis("Updated category", "categories", $result[$s]);
            }
            $this->session->set_flashdata('success', 'Category(s) Updated Successfully');
            redirect("admin_section/admin_categories", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Category(s) Could Not Be Updated Successfully');
            redirect("admin_section/admin_categories", "refresh");
        }
    }

    //save fault alert email,default alert emial,safety alert email...
    function saveFaultEmails() {
        $this->load->model('admin_section_model');
        $emailData = array(
            'support_email' => $this->input->post('default_alert_email'),
            'fault_alert_email' => $this->input->post('fault_alert_email'),
            'safety_alert_email' => $this->input->post('safety_alert_email'),
        );
        $result = $this->admin_section_model->addFaultEmail($this->input->post('account_id'), $emailData);

        if ($result) {
            $this->session->set_flashdata('success', 'Email added Successfully');
            redirect("admin_section/admin_categories", "refresh");
        } else {
            $this->session->set_flashdata('error', 'Something went wrong.. Please try again.');
            redirect("admin_section/admin_categories", "refresh");
        }
    }

}
