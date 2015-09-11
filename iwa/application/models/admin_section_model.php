<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_Section_Model extends CI_Model {

// Action to add item/manu
    public function addItem_Manu($arritem_manu) {

        if (isset($arritem_manu)) {
            if ($this->checkitem($arritem_manu['item_manu_name'], $arritem_manu['account_id']) == 0) {
                $this->db->insert('item_manu', $arritem_manu);
                $insert_id = $this->db->insert_id();
                return $insert_id;
            }
        } else {
            return FALSE;
        }
    }

// Action to get itemlist/manulist
    public function getItem_Manu($account_id, $export = '') {

        $this->db->select('*');
        $this->db->where('account_id', $account_id);
        $this->db->order_by('item_manu_name');
        $res = $this->db->get('item_manu');
        $result['list'] = $res->result_array();
        $result['num_list'] = $res->num_rows();

        $manufacturer = $this->getManufacturer($account_id);
        $item_manu = array_replace_recursive($result['list'], $manufacturer);


        // csv and pdf code
        if ($export == 'CSV') {
            foreach ($item_manu as $key => $value) {
                $output[] = preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', $value));
            }
            foreach ($output as $key => $value) {
                unset($output[$key]['id'], $output[$key]['account_id']);
            }


            $this->load->helper('csv');
            getcsv($output, "Item&Manufacturer.csv");
//            $this->load->dbutil();
//            $this->load->helper('download');
//            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Item Name', 'strFieldReference' => 'item_manu_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Manufacturer Name', 'strFieldReference' => 'manufacturer_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $item_manu);
        }

        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

// Action to add manufacturer
    public function addManufacturer($arrData) {

        if (isset($arrData)) {
            $data = array(
                "manufacturer_name" => $arrData['manufacturer_name'],
                "account_id" => $arrData['account_id'],
            );
            if ($this->checkmanufacturer($data['manufacturer_name'], $data['account_id']) == 0) {
                $this->db->insert('manufacturer_list', $data);
                $insert_id = $this->db->insert_id();
                return $insert_id;
            }
        } else {
            return FALSE;
        }
    }

// Action to get Manufacturer list 
    public function getManufacturer($account_id) {

        $this->db->select('*');
        $this->db->where('account_id', $account_id);
        $this->db->order_by('manufacturer_name', 'ASC');
        $res = $this->db->get('manufacturer_list');
        $result = $res->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

// Action to edit item/manu
    public function editItems_Manu($editItem) {

        if (isset($editItem)) {
            $this->load->model('items_model');

            for ($i = 0; $i < count($editItem['item_id']); $i++) {
                $data = array(
                    'item_manu_name' => $editItem['item_manu_name'][$i],
                );

                if ($editItem['doc']['name'][$i] != '') {

                    $file = $editItem['doc']["tmp_name"][$i];
                    $ext = end((explode(".", $editItem['doc']['name'][$i])));
                    $strFileName = preg_replace('/[^a-zA-Z0-9]+/', '', $editItem['doc']['name'][$i]);
                    $strFileName = $strFileName . time() . '.' . $ext;
                    $this->items_model->update_file($editItem['item_id'][$i], $strFileName);
                    $this->load->library('s3');

                    $this->s3->putObjectFile($file, "smartaudit", 'youaudit/' . $editItem['item_id'][$i] . '/' . $strFileName, S3::ACL_PUBLIC_READ);
                }

                $this->db->where('id', $editItem['item_id'][$i]);
                $this->db->update('item_manu', $data);
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Action to edit multiple categories 
    public function editmulticategories($editdata) {

        if (isset($editdata)) {

            for ($i = 0; $i < count($editdata['category_id']); $i++) {
                $data = array(
                    'name' => $editdata['category_name'][$i],
                );
                if ($this->input->post('custom_fields_' . $i)) {
                    $custom_fields = $this->input->post('custom_fields_' . $i);
                    $custom_data = array_values(array_filter($custom_fields));

                    foreach ($custom_data as $custom_ids) {
                        if (!empty($custom_ids)) {
                            $data['custom_fields'] = json_encode($custom_data);
                        }
                    }
                }
                if (trim($editdata['supportemails'][$i]) != "") {
                    if (strpos(trim($editdata['supportemails'][$i]), ' ') != FALSE) {

                        $arr = explode(' ', trim($editdata['supportemails'][$i]));
                        $str = implode(',', $arr);
                        $data['support_emails'] = $str;
                    } else {
                        $data['support_emails'] = $editdata['supportemails'][$i];
                    }
                } else {
                    $data['support_emails'] = NULL;
                }
//                var_dump($editdata['supportemails'][$i]);
//                die;
//                var_dump($editdata['supportemails'][$i]);
                $this->db->where('id', $editdata['category_id'][$i]);
                $this->db->update('categories', $data);
            }

            return TRUE;
        } else {
            return FALSE;
        }
    }

// Action to edit manufacturer
    public function editManufacturer($editManufacturer) {

        if (isset($editManufacturer)) {


            for ($i = 0; $i < count($editManufacturer['manufacturer_id']); $i++) {
                $data = array(
                    'manufacturer_name' => $editManufacturer['manufacturer_name'][$i]
                );
                $this->db->where('id', $editManufacturer['manufacturer_id'][$i]);
                $this->db->update('manufacturer_list', $data);
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

// get ownerlist
    public function ownerlist($customer_id, $export = '') {
        if ($export != '') {
            $this->db->select('owner.owner_name,locations.name as location');
            $this->db->join('locations', 'owner.location_id=locations.id', 'left');
        } else {
            $this->db->select('owner.*,locations.name,locations.id as locationId');

            $this->db->join('locations', 'owner.location_id=locations.id', 'left');
        }
        $this->db->where('owner.account_id', $customer_id);
        $this->db->where('owner.archive', 1);
        $this->db->from('owner');
        $this->db->order_by('owner.owner_name', 'ASC');
        $resQuery = $this->db->get();

        $ownerinfo = $resQuery->result_array();

        if ($export == 'CSV') {
            foreach ($ownerinfo as $key => $value) {
                $output[] = preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', $value));
            }
            foreach ($output as $key => $value) {
                unset($output[$key]['id'], $output[$key]['account_id']);
            }


            $this->load->helper('csv');
            getcsv($output, "Ownerlist.csv");
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Owner Name', 'strFieldReference' => 'owner_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Location Name', 'strFieldReference' => 'location', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $resQuery->result_array());
        }
        return $ownerinfo;
    }

    // Disable Owner
    public function disableowner($id) {
        if (($id > 0) && ($this->doCheckOwnerHasNoActiveItems($id))) {
            if (isset($id)) {
                $this->db->set('active', 0);
                $this->db->where('id', $id);
                $this->db->update('owner');
                return 1;
            } else {
                return False;
            }
        }
        return FALSE;
    }

// Enable Owner
    public function enableowner($id) {

        if (isset($id)) {
            $this->db->set('active', 1);
            $this->db->where('id', $id);
            $this->db->update('owner');
            return 1;
        } else {
            return False;
        }
    }

    // Add Multiple Owners
    public function multiple_owner($ownerdata) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        foreach ($ownerdata['ownername'] as $value) {
            $multiple = array('owner_name' => rtrim($value, ','),
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                'active' => 1,
                'archive' => 1,
                'location_id' => $ownerdata['location_id']
            );
            if ($multiple['owner_name'] != '' && $this->checkowner($multiple['owner_name'], $multiple['account_id']) == 0) {
                foreach ($multiple as $key => $value) {
                    if ($value == '') {
                        unset($multiple[$key]);
                    }
                }
                $rs = $this->db->insert('owner', $multiple);
            }
        }
        if ($rs) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Archive Owner
    public function archiveOwner($id) {
        if (($id > 0) && ($this->doCheckOwnerHasNoActiveItems($id))) {
            if (isset($id)) {
                $this->db->set('archive', 0);
                $this->db->where('id', $id);
                $this->db->update('owner');
                return TRUE;
            } else {
                return False;
            }
        }

        return FALSE;
    }

// Action to add owner
    public function add_owner() {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $ownerdata = array('owner_name' => trim($this->input->post('owner_name')),
            'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            'active' => 1,
            'archive' => 1,
            'location_id' => $this->input->post('location_id')
        );

        foreach ($ownerdata as $key => $value) {
            if ($value == '') {
                unset($ownerdata[$key]);
            }
        }


        if ($this->checkowner($ownerdata['owner_name'], $ownerdata['account_id']) == 0) {
            $this->db->insert('owner', $ownerdata);
            $id = $this->db->insert_id();
        }
        if ($id) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Action For Edit Owner.
    public function edit_owner($editOwner) {

        if (isset($editOwner)) {
            $data = array(
                'owner_name' => $editOwner['ownername'],
                'location_id' => $editOwner['location_id']
            );
            $this->db->where('id', $editOwner['adminuser_id']);
            $this->db->update('owner', $data);

            return TRUE;
        } else {
            return False;
        }
    }

    // get categories customfields
    public function getCustomField($account_id) {
        $this->db->select('*');
        $this->db->where('account_id', $account_id);
        $res = $this->db->get('custom_fields');
        $result = $res->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

// Action to Get Users
    public function getUser($account_id) {
        $this->db->select('*');
        $this->db->where('account_id', $account_id);
        $this->db->where('archive', 1);
        $res = $this->db->get('users');
        $result = $res->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

// Action to Get Categorylist
    public function getCategory($account_id, $export = '') {
        if ($export != '') {
            $this->db->select('name,support_emails,supplier_user');
        } else {
            $this->db->select('*');
        }
        $this->db->where('account_id', $account_id);
        $this->db->where('archive', 1);
        $res = $this->db->get('categories');
        $result = $res->result_array();

        // csv and pdf code
        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Category Name', 'strFieldReference' => 'name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Alert/Reminder Email Address', 'strFieldReference' => 'support_emails', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array());
        }

        if ($result) {
            foreach ($result as $key => $value) {
                $arrCustomFields = json_decode($value['custom_fields']);
                if ($arrCustomFields) {

                    $this->db->where_in('id', $arrCustomFields);
                    $query = $this->db->get('custom_fields');
                    $my_arr = $query->result();
                } else {
                    $my_arr = array();
                }

                foreach ($my_arr as $record) {
                    $result[$key][$record->field_name] = 'YES';
                }
            }

            return $result;
        } else {
            return FALSE;
        }
    }

    // Get Edit Category Data
    public function getcategorydata($account_id, $category_id) {


        $this->db->select('*,count(custom_fields.field_name) as fields');
        $this->db->where('categories.account_id', $account_id);
        $this->db->where('categories.id', $category_id);
        $this->db->where('categories.archive', 1);
        $this->db->from('categories');
        $this->db->join('custom_fields', 'custom_fields.account_id=categories.account_id');
        $res = $this->db->get();
        $result = $res->result_array();
        if ($result) {
            foreach ($result as $key => $value) {
                $arrCustomFields = json_decode($value['custom_fields']);
                if ($arrCustomFields) {

                    $this->db->where_in('id', $arrCustomFields);
                    $query = $this->db->get('custom_fields');
                    $my_arr = $query->result();
                } else {
                    $my_arr = array();
                }

                foreach ($my_arr as $record) {
                    $result[$key][$record->field_name] = 'YES';
                }
            }

            return $result[0];
        } else {
            return FALSE;
        }
    }

    // add category
    public function addCategory($arrCategory) {

        if (isset($arrCategory)) {
            $this->db->insert('categories', $arrCategory);
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    //Action For Edit Catgeory.
    public function editCategory($editCategory) {
        if (isset($editCategory)) {

            $data = array(
                'name' => $editCategory['name'],
                'support_emails' => $editCategory['support_emails'],
                'quantity_enabled' => $editCategory['quantity_enabled']
            );
            
            if (isset($editCategory['supplier_user'])) {
                $data['supplier_user'] = $editCategory['supplier_user'];
            }
            
            if (isset($editCategory['custom_fields'])) {
                $data['custom_fields'] = $editCategory['custom_fields'];
            } else {
                $data['custom_fields'] = NULL;
            }
            $this->db->where('id', $editCategory['category_id']);
            $this->db->update('categories', $data);
            return TRUE;
        } else {
            return False;
        }
    }

    // Action Disable Category
    public function disableCategory($category_id) {
        $this->load->model('categories_model');
        if (isset($category_id) && ($this->categories_model->doCheckCategoryHasNoActiveItems($category_id))) {
            $this->db->where('id', $category_id);
            $this->db->update('categories', array('active' => 0));
            return True;
        } else {
            return False;
        }
    }

    // Action Enable Category
    public function enableCategory($category_id) {

        if (isset($category_id)) {
            $this->db->where('id', $category_id);
            $this->db->update('categories', array('active' => 1));
            return True;
        } else {
            return False;
        }
    }

// Action to add multiple Category
    public function addMultipleCategory($arrCategory) {

        if (isset($arrCategory)) {

            $this->db->insert('categories', $arrCategory);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Action Archive Category
    public function archiveCategory($category_id) {

        if (isset($category_id)) {
            $this->db->where('id', $category_id);
            $this->db->update('categories', array('archive' => 0, 'active' => 0));
            return True;
        } else {
            return False;
        }
    }

    // Archive List
    public function archiveCategoryList($account_id) {

        $this->db->select('*');
        $this->db->where('account_id', $account_id);
        $this->db->where('archive', 0);
        $res = $this->db->get('categories');
        $result = $res->result_array();
        if ($result) {
            foreach ($result as $key => $value) {
                $arrCustomFields = json_decode($value['custom_fields']);
                if ($arrCustomFields) {

                    $this->db->where_in('id', $arrCustomFields);
                    $query = $this->db->get('custom_fields');
                    $my_arr = $query->result();
                } else {
                    $my_arr = array();
                }

                foreach ($my_arr as $record) {
                    $result[$key][$record->field_name] = 'YES';
                }
            }

            return $result;
        } else {
            return FALSE;
        }
    }

// get customer detail
    public function getCustomerName($account_id) {
        $this->db->select('*');
        $this->db->where('id', $account_id);
        $res = $this->db->get('accounts');
        $result = $res->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    // get admin userlist
    public function userlist($customer_id, $export = '') {
        if ($export != '') {
            $this->db->select('u.firstname,u.lastname,u.username,u.is_owner as owner,levels.name as level,u.push_notification as notification');
        } else {
            $this->db->select('u.*,levels.id as levelid,levels.name');
        }
        $this->db->where('u.account_id', $customer_id);
        $this->db->where('u.archive', 1);
        $this->db->from('users as u');
        $this->db->join('levels', 'levels.id=u.level_id');
        $resQuery = $this->db->get();
        $userinfo = $resQuery->result_array();

        $users = array();
//        $owners = array();
        for ($i = 0; $i < count($userinfo); $i++) {

            $supp_exist = $this->db->where('user_id', $userinfo[$i]['id'])->get('supplier_user');
            if ($supp_exist->num_rows() === 0) {
                $users[$i] = $userinfo[$i];
//                $owner = $this->db->where('owner_name', $value['username'])->get('owner');
                if ($userinfo[$i]['owner'] != 0) {
                    $users[$i]['owner'] = "Yes";
                } else {
                    $users[$i]['owner'] = "No";
                }
                if ($userinfo[$i]['notification'] != 0) {
                    $users[$i]['notification'] = "Yes";
                } else {
                    $users[$i]['notification'] = "No";
                }
//                $users = array_replace_recursive($info, $owners);
            }
        }


//        $users = array_replace_recursive($owners, $info);        
// csv and pdf code
        if ($export == 'CSV') {

            foreach ($users as $key => $value) {
                $output[] = preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', $value));
            }

            $this->load->helper('csv');
            getcsv($output, "Userlist.csv");
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'First Name', 'strFieldReference' => 'firstname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Last Name', 'strFieldReference' => 'lastname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Username/Email Address', 'strFieldReference' => 'username', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Access Level', 'strFieldReference' => 'level', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Owner', 'strFieldReference' => 'owner', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Notification', 'strFieldReference' => 'notification', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $users);
        }


        return $users;
    }

// check unique username
    public function check_username($username) {

        $this->db->select('username');
        $this->db->where('username', $username);
        $res = $this->db->get('users');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

// Disable User
    public function disableuser($id) {

        if (isset($id)) {
            $this->db->set('active', 0);
            $this->db->where('id', $id);
            $this->db->update('users');
            return 1;
        } else {
            return False;
        }
    }

// Enable User
    public function enableuser($id) {

        if (isset($id)) {
            $this->db->set('active', 1);
            $this->db->where('id', $id);
            $this->db->update('users');
            return 1;
        } else {
            return False;
        }
    }

// Archive User
    public function archiveUser($id) {

        if (isset($id)) {
            $this->db->set(array('archive' => 0, 'active' => 0));
            $this->db->where('id', $id);
            $this->db->update('users');
            return 1;
        } else {
            return False;
        }
    }

// Action to Add User and add owner (if user is owner)
    public function add_users($userdata) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->db->insert('users', $userdata);
        $id = $this->db->insert_id();
        if ($id) {
            if ($userdata['is_owner']) {
                $data = array('owner_name' => $userdata['firstname'] . ' ' . $userdata['lastname'],
                    'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                    'active' => 1,
                    'is_user' => $id);
                $this->db->insert('owner', $data);
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

// Action to Update User Password
    public function changeUserPassword($changeUserPassword) {
        $this->load->model('actions_model');
        if (isset($changeUserPassword['adminuser_id'])) {
            $this->db->set('password', $changeUserPassword['new_password']);
            $this->db->where('id', $changeUserPassword['adminuser_id']);
            $this->db->update('users');
// generate history
            $log_report = array(
                'action' => 'Updated Password',
                'table' => 'users',
                'who_did_it' => $this->session->userdata('objSystemUser')->userid,
                'on_account' => $this->session->userdata('objSystemUser')->accountid,
                'when' => date('Y-m-d H:i:s', time()),
                'to_what' => $changeUserPassword['adminuser_id'],
            );
            $this->actions_model->logOne($log_report);
            return 1;
        } else {
            return False;
        }
    }

    //Action For Edit User and delete user from ownerlist (if user is not owner)
    public function editUser($editUser) {
        $this->load->model('users_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        if (isset($editUser)) {

            $data = array(
                'firstname' => $editUser['firstname'],
                'lastname' => $editUser['lastname'],
                'level_id' => $editUser['level'],
                'push_notification' => $editUser['push_notification'],
                'username' => $editUser['username']
            );
            if ($this->input->post('edit_add_owner')) {
                $data['is_owner'] = 1;
            } else {
                $data['is_owner'] = 0;
            }

            $this->db->where('id', $editUser['adminuser_id']);
            $this->db->update('users', $data);

            $arruserinfo = $this->users_model->getOneWithoutAccount($editUser['adminuser_id']);
            $name = $arruserinfo['result'][0]->firstname . '' . $arruserinfo['result'][0]->lastname;
            if (!$this->input->post('edit_add_owner')) {
                $user = $arruserinfo['result'][0]->userid;



                $this->db->select('id');
                $this->db->where('account_id', $arrPageData['arrSessionData']['objSystemUser']->accountid);
                $this->db->where('is_user', $user);
                $this->db->from('owner');
                $userinfo = $this->db->get()->result();

                if (isset($userinfo[0]->id)) {
                    $this->db->where('id', $userinfo[0]->id);
                    $this->db->delete('owner');
                }
            } else {
                $data = array('owner_name' => $name,
                    'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                    'active' => 1,
                    'is_user' => $editUser['adminuser_id']);
                $this->db->insert('owner', $data);
            }
            return 1;
        } else {
            return False;
        }
    }

// Add Multiple Users
    public function add_multiple($userdata) {
        $this->load->model('users_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $owners = array();
        foreach ($userdata as $value) {
            $multiple = array('firstname' => $value['first_name'],
                'lastname' => $value['last_name'],
                'username' => $value['user_name'],
                'password' => $value['mpassword'],
                'level_id' => $value['level'],
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                'active' => 1);

            if ($this->users_model->checkUserName($multiple['username'])) {
                
            } else {
                if ($value['owner'] == 1) {
                    $multiple['is_owner'] = 1;
                }
                $rs = $this->db->insert('users', $multiple);
                $id = $this->db->insert_id();
            }
            if ($value['owner'] == 1) {
                $data = array('owner_name' => $value['user_name'],
                    'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                    'active' => 1,
                    'is_user' => $id);
                $this->db->insert('owner', $data);
            }
        }
        if ($rs) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get site list
    public function sitelist($customer_id, $export = '') {
        if ($export != '') {
            $this->db->select('name as sitename');
        } else {
            $this->db->select('*');
        }
        $this->db->where('account_id', $customer_id);
        $this->db->where('archive', 1);
        $res = $this->db->get('sites');
        $siteinfo = $res->result();

        // csv and pdf code
        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Site Name', 'strFieldReference' => 'sitename', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array());
        }

        return $siteinfo;
    }

    // add site
    public function add_site() {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $sitedata = array('name' => $this->input->post('site_name'),
            'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            'active' => 1,
            'archive' => 1
        );

        foreach ($sitedata as $key => $value) {
            if ($value == '') {
                unset($sitedata[$key]);
            }
        }
        $this->db->insert('sites', $sitedata);
        $id = $this->db->insert_id();
        if ($id) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Action For Edit Site.
    public function editSite($editSite) {

        if (isset($editSite)) {

            $this->db->set('name', $editSite['sitename']);
            $this->db->where('id', $editSite['adminuser_id']);
            $this->db->update('sites');
            return 1;
        } else {
            return False;
        }
    }

    // Disable Site
    public function disablesite($id) {
        if (($id > 0) && ($this->doCheckSiteHasNoActiveItems($id))) {
            if (isset($id)) {
                $this->db->set('active', 0);
                $this->db->where('id', $id);
                $this->db->update('sites');
                return 1;
            } else {
                return False;
            }
        }
        return FALSE;
    }

// Enable Site
    public function enablesite($id) {

        if (isset($id)) {
            $this->db->set('active', 1);
            $this->db->where('id', $id);
            $this->db->update('sites');
            return 1;
        } else {
            return False;
        }
    }

    // Add Multiple Sites
    public function multiple_site($sitedata) {

        $arrPageData['arrSessionData'] = $this->session->userdata;
        foreach ($sitedata['sitename'] as $value) {

            $multiple = array('name' => $value,
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                'active' => 1,
                'archive' => 1
            );

            $rs = $this->db->insert('sites', $multiple);
        }
        if ($rs) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Archive Site
    public function archiveSite($id) {
        if (($id > 0) && ($this->doCheckSiteHasNoActiveItems($id))) {
            if (isset($id)) {
                $this->db->set('archive', 0);
                $this->db->where('id', $id);
                $this->db->update('sites');
                return 1;
            } else {
                return False;
            }
        }
        return FALSE;
    }

    // get locationlist 
    public function locationlist($customer_id, $export = '') {
        if ($export != '') {

            $this->db->select('locations.name as location,barcode,sites.name as url,owner.owner_name');
            $this->db->join('sites', 'locations.site_id = sites.id');
            $this->db->join('owner', 'locations.id = owner.location_id');
        } else {
            $this->db->select('locations.*,sites.name as url,locations.id as id,sites.id as site_id,owner.owner_name,owner.id as owner_id');

            $this->db->join('sites', 'locations.site_id = sites.id');
            $this->db->join('owner', 'locations.id = owner.location_id');
        }
        $this->db->from('locations');
        $this->db->where('locations.account_id', $customer_id);
        $this->db->where('locations.archive', 1);

        $res = $this->db->get();
        $locationinfo = $res->result();

        // csv and pdf code
        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Location Name', 'strFieldReference' => 'location', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Location QR Code', 'strFieldReference' => 'barcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Site', 'strFieldReference' => 'url', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Owner', 'strFieldReference' => 'owner_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array());
        }

        return $locationinfo;
    }

    // add location
    public function add_location() {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $locationdata = array('name' => $this->input->post('location_name'),
            'site_id' => $this->input->post('site_name'),
            'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            'active' => 1,
            'archive' => 1);

        if ($this->input->post('qr_code')) {
            $locationdata['barcode'] = $arrPageData['arrSessionData']['objSystemUser']->qrcode . $this->input->post('qr_code');
        }



        foreach ($locationdata as $key => $value) {
            if ($value == '') {
                unset($locationdata[$key]);
            }
        }

        $rs = $this->db->insert('locations', $locationdata);

        $id = $this->db->insert_id();
        if ($id) {
            $this->db->where('id', $this->input->post('new_owner_id'));
            $this->db->update('owner', array('location_id' => $id));

            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Action For Edit Location.
    public function editLocation($editLocation) {
        
        if (isset($editLocation)) {
            $data = array('name' => $editLocation['locationname'],
                'site_id' => $editLocation['sitename'],
                'barcode' => $editLocation['qrcode']);

            $this->db->where('id', $editLocation['adminuser_id']);
            $this->db->update('locations', $data);
            if ($this->input->post('edit_owner_id')) {
                $this->db->set('location_id','')->where('id',$this->input->post('editownerid'))->update('owner');
                $arr = array('location_id'=>$editLocation['adminuser_id']);
                $this->db->where('id', $this->input->post('edit_owner_id'));
                $this->db->update('owner',$arr);
            }
            return 1;
        } else {
            return False;
        }
    }

    // Disable Location
    public function disablelocation($id) {
        if (($id > 0) && ($this->doCheckLocationHasNoActiveItems($id))) {
            if (isset($id)) {
                $this->db->set('active', 0);
                $this->db->where('id', $id);
                $this->db->update('locations');
                return 1;
            } else {
                return False;
            }
        }
        return false;
    }

// Enable Location
    public function enablelocation($id) {

        if (isset($id)) {
            $this->db->set('active', 1);
            $this->db->where('id', $id);
            $this->db->update('locations');
            return 1;
        } else {
            return False;
        }
    }

// Add Multiple Location
    public function multiple_location($locationdata) {

        $arrPageData['arrSessionData'] = $this->session->userdata;
        foreach ($locationdata as $value) {

            $multiple = array('name' => $value['locationname'],
                'site_id' => $value['sitename'],
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                'active' => 1,
                'archive' => 1
            );
            if (!empty($value['qrcode'])) {
                $multiple['barcode'] = $value['qrcode'];
            }


            $rs = $this->db->insert('locations', $multiple);
            $id = $this->db->insert_id();
            if ($id) {
                $this->db->where('id', $value['owner_id']);
                $this->db->update('owner', array('location_id' => $id));
            }
        }
        return TRUE;
    }

    // archive Location
    public function archiveLocation($id) {
        if (($id > 0) && ($this->doCheckLocationHasNoActiveItems($id))) {
            if (isset($id)) {
                $this->db->set('archive', 0);
                $this->db->where('id', $id);
                $this->db->update('locations');
                return TRUE;
            } else {
                return False;
            }
        }
        return FALSE;
    }

    // get all customfields
    public function getAll($id) {
        $query = $this->db->get_where('custom_fields', array('account_id' => $id));
        return $query->result();
    }

    // add custom field
    public function addField($data) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $rs = $this->get_customcount($arrPageData['arrSessionData']['objSystemUser']->accountid);
//        var_dump($rs);
//        var_dump(intval($arrPageData['arrSessionData']['objSystemUser']->custom_count));
//        die;
        $this->load->model('customfields_model');
        if ($data) {
            if (!($rs >= intval($arrPageData['arrSessionData']['objSystemUser']->custom_count))) {
                if ($this->customfields_model->checkDoesNotExist($data['field_name'])) {
                    $this->db->insert('custom_fields', $data);
                    return $this->db->insert_id();
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

// edit custom field
    public function editField($data) {

        $this->db->where('id', $data['id']);
        if ($this->db->update('custom_fields', $data)) {
            return TRUE;
        }

        return False;
    }

// delete custom field
    public function deleteField($field_id) {
        $this->db->where('id', $field_id);
        $this->db->delete('custom_fields');
        return true;
    }

// Check Unique QRCode
    public function checkQRNumber($qrcode) {

        $this->db->select('barcode');
        $this->db->where('barcode', $qrcode);
        $res = $this->db->get('locations');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

// get supplier list
    public function supplierlist($export = '') {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        if ($export != '') {
            $this->db->select('supplier_name,type,ref_no,support_email,support_number,service_level,response,contract_name,contract_email,contract_no,supplier_address,supplier_city,supplier_state,supplier_postcode');
        } else {
            $this->db->select('*');
        }
        $this->db->where(array('account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid, 'archive' => 1));
        $res = $this->db->get('suppliers');
        $suppliers = $res->result_array();

        // csv and pdf code

        if ($export == 'CSV') {
            foreach ($suppliers as $key => $value) {
                $output[] = preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', $value));
            }

            $this->load->helper('csv');
            getcsv($output, "Supplierlist.csv");
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Company Name', 'strFieldReference' => 'supplier_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Type', 'strFieldReference' => 'type', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Ref No', 'strFieldReference' => 'ref_no', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Support Email', 'strFieldReference' => 'support_email', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Support Number', 'strFieldReference' => 'support_number', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Service Level', 'strFieldReference' => 'service_level', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Response', 'strFieldReference' => 'response', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contract Name', 'strFieldReference' => 'contract_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contract Email', 'strFieldReference' => 'contract_email', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contract Number', 'strFieldReference' => 'contract_no', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Address', 'strFieldReference' => 'supplier_address', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'City', 'strFieldReference' => 'supplier_city', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'State', 'strFieldReference' => 'supplier_state', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Postcode', 'strFieldReference' => 'supplier_postcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array());
        }

        return $suppliers;
    }

    // Get Supplier Detail
    public function get_supplier($supplier_id) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $supplier_detail = $this->db->where(array('supplier_id' => $supplier_id, 'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid))->get('suppliers')->result_array();
        return $supplier_detail[0];
    }

    // Check Ref Number Unique

    public function check_refnumber($refno) {

        $this->db->select('ref_no');
        $this->db->where('ref_no', $refno);
        $res = $this->db->get('suppliers');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

// Disable Supplier
    public function disablesupplier($id) {

        if (isset($id) && ($this->doCheckSupplierHasNoActiveItems($id))) {
            if (isset($id)) {
                $this->db->set('active', 0);
                $this->db->where('supplier_id', $id);
                $this->db->update('suppliers');
                return 1;
            } else {
                return False;
            }
        }
        return FALSE;
    }

    // check Supplier associated with item or not
    public function doCheckSupplierHasNoActiveItems($supplierid = -1) {
        if ($supplierid > 0) {
            $this->db->select('suppliers.supplier_id');
            // we need to do a sub query, this
            $this->db->from('suppliers');
            $this->db->join('items', 'suppliers.supplier_id = items.supplier', 'left');
            $this->db->where('suppliers.supplier_id', $supplierid);
            $this->db->where('items.active', 1);
            $resQuery = $this->db->get();


            if ($resQuery->num_rows() > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

// Enable Supplier
    public function enablesupplier($id) {

        if (isset($id)) {
            $this->db->set('active', 1);
            $this->db->where('supplier_id', $id);
            $this->db->update('suppliers');
            return 1;
        } else {
            return False;
        }
    }

    // Add To Archive Supplier
    public function archivesupplier($id) {

        if (isset($id)) {
            $this->db->set('archive', 0);
            $this->db->where('supplier_id', $id);
            $this->db->update('suppliers');
            return 1;
        } else {
            return False;
        }
    }

// Action To Add Supplier
    public function add_suppliers() {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        if ($this->input->post('service_type') == 'customer') {
            $support_email = '';
            $support_no = '';
            $service_level = '';
            $response = '';
            $start_date = '';
            $end_date = '';
        }
        if ($this->input->post('service_type') == 'supplier') {
            $support_email = $this->input->post('support_email');
            $support_no = $this->input->post('support_number');
            $service_level = '';
            $response = '';
            $start_date = '';
            $end_date = '';
        }
        if ($this->input->post('service_type') == 'service') {
            $support_email = $this->input->post('support_email');
            $support_no = $this->input->post('support_number');
            $service_level = $this->input->post('service_level');
            $response = $this->input->post('response');
            if ($this->input->post('contract_start')) {
                $start_date = strtotime($this->input->post('contract_start'));
            } else {
                $start_date = time();
            }
            if ($this->input->post('contract_end')) {
                $end_date = strtotime($this->input->post('contract_end'));
            } else {
                $end_date = time();
            }
        }

        $supplierdata = array(
            'supplier_name' => $this->input->post('supplier_name'),
            'type' => $this->input->post('service_type'),
            'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
            'ref_no' => $this->input->post('ref_code'),
            'support_email' => $support_email,
            'support_number' => $support_no,
            'service_level' => $service_level,
            'response' => $response,
            'contract_startdate' => $start_date,
            'contract_enddate' => $end_date,
            'contract_name' => $this->input->post('contract_name'),
            'supplier_title' => $this->input->post('contract_title'),
            'contract_no' => $this->input->post('contract_number'),
            'contract_email' => $this->input->post('contract_email'),
            'supplier_address' => $this->input->post('address'),
            'supplier_city' => $this->input->post('city'),
            'supplier_state' => $this->input->post('state'),
            'supplier_postcode' => $this->input->post('postcode'),
            'active' => 1,
            'archive' => 1);

        $this->db->insert('suppliers', $supplierdata);
        $id = $this->db->insert_id();
        if ($id) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

//Action For Edit Supplier.
    public function editSupplier() {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $supplier_id = $this->input->post('adminuser_id');
        if ($this->input->post('editcontract_start')) {
            $start_date = strtotime($this->input->post('editcontract_start'));
        } else {
            $start_date = time();
        }
        if ($this->input->post('editcontract_end')) {
            $end_date = strtotime((string) $this->input->post('editcontract_end'));
        } else {
            $end_date = time();
        }
        if (isset($supplier_id)) {

            $data = array(
                'supplier_name' => $this->input->post('editsupplier_name'),
                'type' => $this->input->post('service_type'),
                'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                'ref_no' => $this->input->post('editref_code'),
                'support_email' => $this->input->post('editsupport_email'),
                'support_number' => $this->input->post('editsupport_number'),
                'service_level' => $this->input->post('editservice_level'),
                'response' => $this->input->post('edit_response'),
                'contract_startdate' => $start_date,
                'contract_enddate' => $end_date,
                'contract_name' => $this->input->post('editcontract_name'),
                'supplier_title' => $this->input->post('editcontract_title'),
                'contract_no' => $this->input->post('editcontract_number'),
                'contract_email' => $this->input->post('editcontract_email'),
                'supplier_address' => $this->input->post('edit_address'),
                'supplier_city' => $this->input->post('edit_city'),
                'supplier_state' => $this->input->post('edit_state'),
                'supplier_postcode' => $this->input->post('edit_postcode')
            );

            $this->db->where('supplier_id', $supplier_id);
            $this->db->update('suppliers', $data);
            return 1;
        } else {
            return False;
        }
    }

    // archive location list
    public function archiveLocationList($customer_id) {

        $this->db->where('account_id', $customer_id);
        $this->db->where('archive', 0);
        $locationinfo = $this->db->get('locations')->result();
        return $locationinfo;
    }

// Archive Owner List
    public function archiveOwnerList($customer_id) {

        $this->db->where('account_id', $customer_id);
        $this->db->where('archive', 0);
        $ownerinfo = $this->db->get('owner')->result();
        return $ownerinfo;
    }

    // get site list
    public function archiveSiteList($customer_id) {

        $this->db->where('account_id', $customer_id);
        $this->db->where('archive', 0);
        $siteinfo = $this->db->get('sites')->result();
        return $siteinfo;
    }

    // Get Archive User List
    public function archiveUserList($account_id) {

        $this->db->select('u.*,levels.id as levelid,levels.name');
        $this->db->where('u.account_id', $account_id);
        $this->db->where('u.archive', 0);
        $this->db->from('users as u');
        $this->db->join('levels', 'levels.id=u.level_id');
        $userinfo = $this->db->get()->result_array();
        return $userinfo;
    }

// Archive User
    public function restoreUser($id) {

        if (isset($id)) {
            $this->db->set(array('archive' => 1, 'active' => 1));
            $this->db->where('id', $id);
            $this->db->update('users');
            return 1;
        } else {
            return False;
        }
    }

// Restore Owner
    public function restoreOwner($id) {

        if (isset($id)) {
            $this->db->set('archive', 1);
            $this->db->where('id', $id);
            $this->db->update('owner');
            return 1;
        } else {
            return False;
        }
    }

// Action Restore Category
    public function restoreCategory($category_id) {

        if (isset($category_id)) {
            $this->db->where('id', $category_id);
            $this->db->update('categories', array('archive' => 1, 'active' => 1));
            return True;
        } else {
            return False;
        }
    }

// Restore Archive Site
    public function restoreSite($id) {

        if (isset($id)) {
            $this->db->set('archive', 1);
            $this->db->where('id', $id);
            $this->db->update('sites');
            return 1;
        } else {
            return False;
        }
    }

    // Restore Archive Location
    public function restoreLocation($id) {

        if (isset($id)) {
            $this->db->set('archive', 1);
            $this->db->where('id', $id);
            $this->db->update('locations');
            return TRUE;
        } else {
            return False;
        }
    }

    public function outputPdfFile($strReportName, $arrFields, $arrResults, $booOutputHtml = false) {
        $this->load->model('accounts_model');
        $currency = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"includes/css/report.css\" /></head>";

        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>".$this->session->userdata('objSystemUser')->firstname." ".$this->session->userdata('objSystemUser')->lastname."/".$this->session->userdata('objSystemUser')->accountname."</h1>";
        $strHtml .= "<h2>" . $strReportName . "</h2>";
        $strHtml .= "</td><td class=\"right\">";

        $logo = 'logo.png';
        if (isset($this->session->userdata['theme_design']->logo)) {
            $logo = $this->session->userdata['theme_design']->logo;
        }

        $strHtml .= "<img alt=\"ictracker\" src='brochure/logo/" . $logo . "'>";

        $strHtml .= "</td></tr></table>";



        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF'>";

        foreach ($arrFields as $arrReportField) {
            $strHtml .= "<th style='color:#FFFFFF'>" . $arrReportField['strName'] . "</th>";
        }

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        $arrTotals = array();
        foreach ($arrResults as $objItem) {

            $strHtml .= "<tr>";

            foreach ($arrFields as $arrReportField) {
                $strHtml .= "<td style='height:50px'>";
                if (array_key_exists('strConversion', $arrReportField)) {
                    switch ($arrReportField['strConversion']) {
                        case 'date':
                            $arrDate = explode('-', $objItem->{$arrReportField['strFieldReference']});
                            if (count($arrDate) > 1) {
                                $strHtml .= $arrDate[2] . "/" . $arrDate[1] . "/" . $arrDate[0];
                            } else {
                                $strHtml .= "Unknown";
                            }
                            break;
                        case 'datetime':
                            $arrDateTime = explode(' ', $objItem->{$arrReportField['strFieldReference']});
                            $strTime = $arrDateTime[1];
                            $arrDate = explode('-', $arrDateTime[0]);
                            $strHtml .= $arrDate[2] . "/" . $arrDate[1] . "/" . $arrDate[0] . " " . $strTime;
                            break;
                        case 'owner':

                            if ($objItem['owner'] == 1) {
                                $strHtml.="Yes";
                            } else {
                                $strHtml.="No";
                            }

                            break;
                        case 'price':
                            $strHtml .= $currency . $objItem->{$arrReportField['strFieldReference']};
                            break;
                    }
                } else {
                    $strHtml .= $objItem[$arrReportField['strFieldReference']];
                }
                if (array_key_exists('arrFooter', $arrReportField) && array_key_exists('booTotal', $arrReportField['arrFooter'])) {
                    if (array_key_exists($arrReportField['strFieldReference'], $arrTotals)) {
                        $arrTotals[$arrReportField['strFieldReference']] += $objItem[$arrReportField['strFieldReference']];
                    } else {
                        $arrTotals[$arrReportField['strFieldReference']] = $objItem[$arrReportField['strFieldReference']];
                    }
                }

                $strHtml .= "</td>";
            }

            $strHtml .= "</tr>";
        }
        $strHtml .= "</tbody>";

        $strHtml .= "<tfoot><tr>";

        foreach ($arrFields as $arrReportField) {
            if (array_key_exists('arrFooter', $arrReportField)) {
                if (array_key_exists('booTotal', $arrReportField['arrFooter']) && $arrReportField['arrFooter']['booTotal']) {
                    $strHtml .= "<td>";
                    if (array_key_exists('strConversion', $arrReportField) && ($arrReportField['strConversion'] == "price")) {
                        $strHtml .= $currency;
                    }
                    $strHtml .= $arrTotals[$arrReportField['strFieldReference']];
                    $strHtml .= "</td>";
                } else {
                    if (array_key_exists('booTotalLabel', $arrReportField['arrFooter']) && $arrReportField['arrFooter']['booTotalLabel']) {
                        $strHtml .= "<td";
                        if (array_key_exists('intColSpan', $arrReportField['arrFooter']) && ($arrReportField['arrFooter']['intColSpan'] > 0)) {
                            $strHtml .= " colspan=\"" . $arrReportField['arrFooter']['intColSpan'] . "\"";
                        }
                        $strHtml .= " class=\"right\">";
                        $strHtml .= "Totals</td>";
                    }
                }
            }
        }
        $strHtml .= "</tr></tfoot>";


        $strHtml .= "</table>";


        $strHtml .= "<p>Produced by " . $this->session->userdata('objSystemUser')->firstname . " " . $this->session->userdata('objSystemUser')->lastname . " (" . $this->session->userdata('objSystemUser')->username . ") on " . date('d/m/Y') . "</p>";
        $strHtml .= "</div></body></html>";

        if (!$booOutputHtml) {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB', 'A4');
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("YouAudit_" . date('Ymd_His') . ".pdf", "D");
        } else {
            echo $strHtml;
            die();
        }
    }

    public function updateMultipleUser($data) {



        if ($data['user_id'] != '') {
            if (strpos($data['user_id'], 'on') !== FALSE) {
                $data['user_id'] = str_replace('on,', '', $data['user_id']);
            }
            $ids = explode(',', $data['user_id']);

            if ($data['notification'] == 'on') {
                $notify = 1;
            } else {

                $notify = 0;
            }


            $update_list = array(
                'level_id' => $data['edit_access_level'],
                'push_notification' => $notify
            );

//            var_dump($update_list);die;

            foreach ($update_list as $key => $value) {
                if ($value == -1) {
                    unset($update_list[$key]);
                }
            }
            if (empty($update_list)) {
                
            } else {

                $this->db->where_in('id', $ids)->update('users', $update_list);
            }
            return TRUE;
        }
    }

    public function userPdf($userid) {

        if ($userid) {
            $this->db->select('u.*,levels.id as levelid,levels.name');

            $this->db->where('u.id', $userid);
            $this->db->where('u.archive', 1);
            $this->db->from('users as u');
            $this->db->join('levels', 'levels.id=u.level_id');
            $resQuery = $this->db->get();
            $userinfo = $resQuery->result_array();

            $info = array();
            foreach ($userinfo as $value) {
                $owner = $this->db->where('owner_name', $value['username'])->get('owner');
                if ($owner->num_rows() > 0) {
                    $info[]['owner'] = 1;
                } else {
                    $info[]['owner'] = 0;
                }
            }


            $users = array_replace_recursive($userinfo, $info);



            $arrFields = array(
                array('strName' => 'First Name', 'strFieldReference' => 'firstname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Last Name', 'strFieldReference' => 'lastname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Username/Email Address', 'strFieldReference' => 'username', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Access Level', 'strFieldReference' => 'name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Owner', 'strFieldReference' => 'owner', 'strConversion' => 'owner', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $users);



            return $users;
        }
    }

    // check Owner associte with item or not


    public function doCheckOwnerHasNoActiveItems($ownerid = -1) {
        if ($ownerid > 0) {
            $this->db->select('owner.id AS owner_id');
            // we need to do a sub query, this
            $this->db->from('owner');
            $this->db->join('items', 'owner.id = items.owner_now', 'left');
            $this->db->where('owner.id', $ownerid);
            $this->db->where('items.active', 1);
            $resQuery = $this->db->get();


            if ($resQuery->num_rows() > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

    // check Site associated with item or not

    public function doCheckSiteHasNoActiveItems($siteid = -1) {
        if ($siteid > 0) {
            $this->db->select('items_sites_link.item_id AS site_id');
            // we need to do a sub query, this
            $this->db->from('items_sites_link');
            $this->db->join('items', 'items_sites_link.item_id = items.id', 'left');
            $this->db->where('items_sites_link.site_id', $siteid);
            $this->db->where('items.active', 1);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

    // check Location associated with item or not
    public function doCheckLocationHasNoActiveItems($locationid = -1) {
        if ($locationid > 0) {
            $this->db->select('items_locations_link.item_id AS location_id');
            // we need to do a sub query, this
            $this->db->from('items_locations_link');
            $this->db->join('items', 'items_locations_link.item_id = items.id', 'left');
            $this->db->where('items_locations_link.location_id', $locationid);
            $this->db->where('items.active', 1);
            $resQuery = $this->db->get();


            if ($resQuery->num_rows() > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

    // check Item Manu associated with item or not
    public function doCheckItemHasNoActiveItems($itemid = -1) {
        if ($itemid > 0) {
            $this->db->select('items.item_manu');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');
            $this->db->where('items.item_manu', $itemid);
            $this->db->where('items.active', 1);
            $resQuery = $this->db->get();


            if ($resQuery->num_rows() > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

    // check Manufacturer associated with item or not
    public function doCheckManufacturHasNoActiveItems($manufacture = -1) {
        if ($manufacture) {
            $this->db->select('manufacturer_name');
            $this->db->where('id', $manufacture);
            $name = $this->db->get('manufacturer_list')->row();

            $this->db->select('items.manufacturer');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('manufacturer_list', 'items.manufacturer = manufacturer_list.manufacturer_name', 'left');

            $this->db->where('items.active', 1);
            $this->db->where('items.manufacturer', $name->manufacturer_name);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

    function archiveItem($item_id) {

        if (($item_id > 0) && ($this->doCheckItemHasNoActiveItems($item_id))) {
            if (isset($item_id)) {
                $this->db->where('id', $item_id);
                $this->db->delete('item_manu');
                return TRUE;
            } else {
                return False;
            }
        }
        return FALSE;
    }

    function archiveManufacturer($manufacturer_id) {
        if (($manufacturer_id > 0) && ($this->doCheckManufacturHasNoActiveItems($manufacturer_id))) {
            if ($manufacturer_id) {
                $this->db->where('id', $manufacturer_id);
                $this->db->delete('manufacturer_list');
                return TRUE;
            } else {
                return False;
            }
        }
        return FALSE;
    }

    // Action to Add Supplier User 
    public function add_supplier_user($userdata, $supplier_id) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->db->insert('users', $userdata);
        $id = $this->db->insert_id();
        if ($id) {
            if ($supplier_id) {
                $data = array('supplier_id' => $supplier_id,
                    'user_id' => $id,
                );
                $this->db->insert('supplier_user', $data);
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Get Supplier User

    function supplieruserlist($customer_id) {
        $this->db->select('u.*,u.firstname,u.lastname,u.username,supplier_user.id as supplier_id,suppliers.supplier_name,suppliers.supplier_id as supplierid');
        $this->db->where('u.account_id', $customer_id);
        $this->db->where('u.archive', 1);
        $this->db->from('users as u');
        $this->db->join('supplier_user', 'supplier_user.user_id =u.id');
        $this->db->join('suppliers', 'supplier_user.supplier_id = suppliers.supplier_id');
        $resQuery = $this->db->get();
        $userinfo = $resQuery->result_array();
        return $userinfo;
    }

    function editSupplierUser($data) {

        $this->load->model('users_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        if (isset($data)) {

            $editUser = array(
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
            );

            $this->db->where('id', $data['adminuser_id']);
            $this->db->update('users', $editUser);

            if ($this->db->affected_rows()) {
                $this->db->where('user_id', $data['adminuser_id']);
                $this->db->update('supplier_user', array('supplier_id' => $data['supplier_id']));
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Action to Get Users
    public function getSupplierUser($account_id) {
        $this->db->select('*');
        $this->db->where('account_id', $account_id);
        $this->db->where('archive', 1);
        $this->db->from('users');
        $this->db->join('supplier_user', 'users.id = supplier_user.user_id');
        $res = $this->db->get();
        $result = $res->result_array();

        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    public function getlocationbyowner($owner_id) {
        $this->db->select('*');
        $this->db->where('id', $owner_id);
        $this->db->where('account_id', $this->session->userdata('objSystemUser')->accountid);
        $this->db->from('owner');
        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query(), 'results' => array());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrSites = array();
            // If there are levels, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrSites[] = $arrRow;
            }
            $arrResult['results'] = $arrSites;
            return $arrResult;
        } else {
            return array();
        }
    }

    public function get_customcount($acc_id) {
        $this->db->select('*');
        $this->db->where(array('account_id' => $acc_id, 'profile' => 0));
        $custom = $this->db->get('custom_fields')->result_array();
        return count($custom);
    }

    public function checkcategory($category_name, $account_id) {
        if ($category_name) {
            $this->db->select('*');
            if (is_numeric($category_name)) {
                $this->db->where('id', $category_name);
            } else {
                $this->db->where('name', $category_name);
            }
            $this->db->where('archive', 1);
            $this->db->where('account_id', $account_id);
            $query = $this->db->get('categories');
            $rowcount = $query->num_rows();

            return $rowcount;
        }
    }

    public function checkitem($item_name, $account_id) {
        if ($item_name) {
            $this->db->select('*');
            if (is_numeric($item_name)) {

                $this->db->where('id', $item_name);
            } else {

                $this->db->where('item_manu_name', $item_name);
            }

            $this->db->where('account_id', $account_id);
            $query = $this->db->get('item_manu');
            $rowcount = $query->num_rows();

            return $rowcount;
        }
    }

    public function checkmanufacturer($manufacturer_name, $account_id) {
        if ($manufacturer_name) {
            $this->db->select('*');
            if (is_numeric($manufacturer_name)) {
                $this->db->where('id', $manufacturer_name);
            } else {
                $this->db->where('manufacturer_name', $manufacturer_name);
            }

            $this->db->where('account_id', $account_id);
            $query = $this->db->get('manufacturer_list');
            $rowcount = $query->num_rows();

            return $rowcount;
        }
    }

    public function checksite($site_name, $account_id) {
        if ($site_name) {
            $this->db->select('*');
            if (is_numeric($site_name)) {
                $this->db->where('id', $site_name);
            } else {
                $this->db->where('name', $site_name);
            }

            $this->db->where('account_id', $account_id);
            $this->db->where('active', 1);
            $query = $this->db->get('sites');
            $rowcount = $query->num_rows();

            return $rowcount;
        }
    }

    public function checklocation($location_name, $account_id) {
        if ($location_name) {
            $this->db->select('*');
            if (is_numeric($location_name)) {
                $this->db->where('id', $location_name);
            } else {
                $this->db->where('name', $location_name);
            }

            $this->db->where('account_id', $account_id);
            $this->db->where('active', 1);

            $query = $this->db->get('locations');

            $rowcount = $query->num_rows();

            return $rowcount;
        }
    }

    public function checkowner($owner_name, $account_id) {
        if ($owner_name) {
            $this->db->select('*');
            if (is_numeric($owner_name)) {
                $this->db->where('id', $owner_name);
            } else {
                $this->db->where('owner_name', $owner_name);
            }

            $this->db->where('account_id', $account_id);
            $this->db->where('active', 1);

            $query = $this->db->get('owner');
            $rowcount = $query->num_rows();

            return $rowcount;
        }
    }

    public function checksupplier($supplier_name, $account_id) {
        if ($supplier_name) {
            $this->db->select('*');
            if (is_numeric($owner_name)) {
                $this->db->where('id', $supplier_name);
            } else {
                $this->db->where('supplier_name', $supplier_name);
            }

            $this->db->where('account_id', $account_id);
            $this->db->where('active', 1);

            $query = $this->db->get('suppliers');
            $rowcount = $query->num_rows();

            return $rowcount;
        }
    }

    // add import site
    public function import_site($sitedata) {


        foreach ($sitedata as $key => $value) {
            if ($value == '') {
                unset($sitedata[$key]);
            }
        }
        $this->db->insert('sites', $sitedata);
        $id = $this->db->insert_id();
        if ($id) {
            return $id;
        } else {
            return FALSE;
        }
    }

    // add import location
    public function import_location($locationdata) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        foreach ($locationdata as $key => $value) {
            if ($value == '') {
                unset($locationdata[$key]);
            }
        }

        $rs = $this->db->insert('locations', $locationdata);

        $id = $this->db->insert_id();
        if ($id) {

            return $id;
        } else {
            return FALSE;
        }
    }

    // add import new owner

    public function import_owner($ownerdata) {



        foreach ($ownerdata as $key => $value) {
            if ($value == '') {
                unset($ownerdata[$key]);
            }
        }


        if ($this->checkowner($ownerdata['owner_name'], $ownerdata['account_id']) == 0) {
            $this->db->insert('owner', $ownerdata);
            $id = $this->db->insert_id();
        }
        if ($id) {
            return $id;
        } else {
            return FALSE;
        }
    }

    // add import supplier

    public function import_supplier($supplierdata) {


        $this->db->insert('suppliers', $supplierdata);
        $id = $this->db->insert_id();
        if ($id) {
            return $id;
        } else {
            return FALSE;
        }
    }

    //update custom field in linked data when import data
    public function editCustomField($editCategory) {

        if (isset($editCategory)) {

            if (isset($editCategory['custom_fields'])) {
                $data['custom_fields'] = $editCategory['custom_fields'];
            } else {
                $data['custom_fields'] = NULL;
            }
            $this->db->where('id', $editCategory['category_id']);
            $this->db->update('categories', $data);
            return TRUE;
        } else {
            return False;
        }
    }

    public function getmanufacturerbyid($manufacturer_id) {

        $query = $this->db->get_where('manufacturer_list', array('id' => $manufacturer_id));
        return $query->row();
    }

    public function updateMultiplecategories($data) {
        $this->load->model('customfields_model');

        if ($data['category_id'] != '') {
            if (strpos($data['category_id'], 'on') !== FALSE) {
                $data['category_id'] = str_replace('on,', '', $data['category_id']);
            }
            $ids = explode(',', $data['category_id']);

            $custom_fields = $data['custom_fields'];
            $custom_data = array_values(array_filter($custom_fields));
            if (!empty($custom_data)) {
                foreach ($custom_data as $custom_ids) {
                    if ($custom_ids != "") {
                        $data['custom_fields'] = json_encode($custom_data);
                    } else {
                        $data['custom_fields'] = null;
                    }
                }
            } else {
                $data['custom_fields'] = null;
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

                $data['support_emails'] = $mails;
            }

            $cat = array('custom_fields' => $data['custom_fields'],
                'support_emails' => $data['support_emails']
            );


            foreach ($cat as $key => $value) {
                if ($value == null) {
                    unset($cat[$key]);
                }
            }

            if (empty($cat)) {
                
            } else {
                for ($j = 0; $j < count($ids); $j++) {
                    $this->db->where('id', $ids[$j])->update('categories', $cat);
                }
            }

            return TRUE;
        }
    }

    
  //update fault,safety and default alert email....
    public function addFaultEmail($accountId,$emailData){
        
        $this->db->where('id',$accountId);
        $this->db->set($emailData);
       $res =  $this->db->update('accounts');
       
       if($res){
           
           return TRUE;
           
       } else{
           
           return FALSE;
       }
    }
    
  // get all set alert email from user table...
    
    public function alertEmailList($accountId){
        $result = $this->db->where('id',$accountId)->select('support_email,fault_alert_email,safety_alert_email')->from('accounts')->get();
                        
        if($result->num_rows() > 0){
            
            return $result->result_array();
            
        }else{
            return FALSE;
        }
            
        
    }
    
}
