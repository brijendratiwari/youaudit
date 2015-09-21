<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Franchise_Model extends CI_Model {

    public function getFranchiseAccountName($account_id) {
        $this->db->select('*');
        $this->db->where('id', $account_id);
        $res = $this->db->get('franchise_ac');
        $result = $res->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    public function getCustomerPackage() {
        $this->db->select('*');
        $this->db->where('enable', 1);
        $res = $this->db->get('packages');
        $result = $res->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    // Action For Fetch System Admin Name
    public function getSysAdminName($franchise_account_id) {
        $this->db->select('sys_franchise_name');
        $this->db->where('id', $franchise_account_id);
        $res = $this->db->get('franchise_ac');
        $result = $res->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    public function addCustomerAc($arrCustomer) {
        $this->load->model('admin_section_model');
        $this->load->model('categories_model');
        $franchise_id = $this->input->post('masterid');
        $acc_limit = $this->db->select('account_limit')->where('id', $franchise_id)->get('franchise_ac')->row();
        $cus_count = $this->db->where(array('account_id' => $franchise_id, 'account_type' => 2))->get('accounts')->result();
        // check account limit of franchise account
        if (count($cus_count) < $acc_limit->account_limit) {

            if (isset($arrCustomer)) {
                $this->db->insert('accounts', $arrCustomer);
                $id = $this->db->insert_id();
                if ($id) {
                    $users = array('firstname' => $arrCustomer['firstname'],
                        'lastname' => $arrCustomer['lastname'],
                        'username' => $this->input->post('username'),
                        'password' => $this->input->post('contact_password'),
                        'nickname' => $this->input->post('contact_name'),
                        'level_id' => 4,
                        'account_id' => $id,
                        'active' => 1,
                    );
                    $this->db->insert('users', $users);
                    $userid = $this->db->insert_id();
                    if ($userid) {
                        if ($arrCustomer['add_owner'] != 0) {
                            $newOwner = array('owner_name' => $arrCustomer['firstname'] . ' ' . $arrCustomer['lastname'],
                                'account_id' => $id, 'active' => 1, 'archive' => 1, 'is_user' => $userid);
                            $this->db->insert('owner', $newOwner);
                        }
                    }

                    $profiles = $this->db->where('profile_id', $this->input->post('profile'))->get('profile')->result();

                    if ($profiles[0]->custom_field) {
                        $fields = json_decode($profiles[0]->custom_field);
                        for ($i = 0; $i < count($fields->name); $i++) {
                            $custom_data = array('field_name' => $fields->name[$i], 'account_id' => $id, 'field_value' => $fields->type[$i], 'pick_values' => $fields->values[$i], 'profile' => 1);
                            $this->db->insert('custom_fields', $custom_data);
                            $cus_id = $this->db->insert_id();
                            $ids[] = $cus_id;
                        }
                    }

                    if ($profiles[0]->owner) {
                        $owners = json_decode($profiles[0]->owner);
                        $owners = array_filter($owners);
                        foreach ($owners as $owner) {
                            if ($this->admin_section_model->checkowner($owner, $id) == 0) {
                                $owner_data = array('owner_name' => $owner, 'account_id' => $id, 'active' => 1);
                                $this->db->insert('owner', $owner_data);
                            }
                        }
                    }
                    if ($profiles[0]->category) {
                        $categories = json_decode($profiles[0]->category);
                        $categories = array_filter($categories);
                        foreach ($categories as $category) {
                            if ($this->categories_model->doCheckCategoryNameIsUniqueOnAccount($category, $id)) {
                                $category_data = array('name' => $category, 'account_id' => $id, 'active' => 1);
                                $this->db->insert('categories', $category_data);
                                $cat_id = $this->db->insert_id();
                            }
                            if ($cat_id) {
                                $this->db->set('custom_fields', json_encode($ids));
                                $this->db->where('id', $cat_id);
                                $this->db->update('categories');
                            }
                        }
                    }

                    if ($profiles[0]->manu) {
                        $manulist = json_decode($profiles[0]->manu);
                        $manulist = array_filter($manulist);
                        foreach ($manulist as $manu) {
                            if ($this->admin_section_model->checkitem($manu, $id) == 0) {
                                $manu_data = array('item_manu_name' => $manu, 'account_id' => $id);
                                $this->db->insert('item_manu', $manu_data);
                            }
                        }
                    }

                    if ($profiles[0]->manufacturer) {
                        $manufacturers = json_decode($profiles[0]->manufacturer);
                        $manufacturers = array_filter($manufacturers);
                        foreach ($manufacturers as $manufacturer) {
                            if ($this->admin_section_model->checkmanufacturer($manufacturer, $id) == 0) {
                                $manufacturer_data = array('manufacturer_name' => $manufacturer, 'account_id' => $id);
                                $this->db->insert('manufacturer_list', $manufacturer_data);
                            }
                        }
                    }
                }
                return 1;
            }
        } else {
            return False;
        }
    }

    // Edit Master Customer User
    public function editFranchiseCustomerAc($editArrCustomer) {
        if (isset($editArrCustomer)) {

            $data = array(
                'name' => $editArrCustomer['name'],
                'address' => $editArrCustomer['address'],
                'city' => $editArrCustomer['city'],
                'state' => $editArrCustomer['state'],
                'postcode' => $editArrCustomer['postcode'],
                'firstname' => $editArrCustomer['firstname'],
                'lastname' => $editArrCustomer['lastname'],
                'contact_name' => $editArrCustomer['contact_name'],
                'contact_number' => $editArrCustomer['contact_number'],
                'add_owner' => $editArrCustomer['add_owner'],
                'support_email' => $editArrCustomer['support_email'],
                'qr_refcode' => $editArrCustomer['qr_refcode'],
                'package_id' => $editArrCustomer['package_id'],
                'verified' => $editArrCustomer['verified'],
                'annual_value' => $editArrCustomer['annual_value'],
                'compliance' => $editArrCustomer['compliance'],
                'fleet' => $editArrCustomer['fleet'],
                'condition_module' => $editArrCustomer['condition_module'],
                'depereciation_module' => $editArrCustomer['depereciation_module'],
                'reporting_module' => $editArrCustomer['reporting_module'],
                'create_date' => $editArrCustomer['create_date'],
            );

            foreach ($data as $key => $value) {
                if ($value == '') {
                    unset($data[$key]);
                }
            }
            $this->db->where('id', $editArrCustomer['customer_id']);
            $this->db->update('accounts', $data);

            if ($this->input->post('edit_contact_password')) {
                $this->db->where('username', $this->input->post('edit_contact_username'));
                $this->db->update('users', array('password' => $this->input->post('edit_contact_password')));
            }
            if ($editArrCustomer['add_owner'] == 0) {
                $userid = $this->db->select('id as user')->where('account_id', $editArrCustomer['customer_id'])->get('users')->row();
                $this->db->where('is_user', $userid->user);
                $this->db->delete('owner');
            }
            return 1;
        } else {
            return FALSE;
        }
    }

    public function checkFranchiseRefcode($reference_code) {
        $this->db->select('qr_refcode');
        $this->db->where('qr_refcode', $reference_code);
        $res = $this->db->get('accounts');
        if ($res->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function disableFranchiseCustomer($customer_id) {

        if (isset($customer_id)) {
            $this->db->where('id', $customer_id);
            $this->db->update('accounts', array('active' => 0));
            return 1;
        } else {
            return FALSE;
        }
    }

    public function enableFranchiseCustomer($customer_id) {

        if (isset($customer_id)) {
            $this->db->where('id', $customer_id);
            $this->db->update('accounts', array('active ' => 1));
            return 1;
        } else {
            return FALSE;
        }
    }

    // Action For Add AdminUser.
    public function addFranchiseAdminUser($arrAdminUser) {

        if (isset($arrAdminUser)) {


            $this->db->insert('systemadmin_franchise', $arrAdminUser);
            return 1;
        } else {
            return FALSE;
        }
    }

    public function changeFranchiseAdminUserPassword($changeAdminUserPassword) {

        if (isset($changeAdminUserPassword['adminuser_id'])) {

            $data = array(
                'password' => $changeAdminUserPassword['new_password'],
                'pin_number' => $changeAdminUserPassword['pin_number'],
            );

            foreach ($data as $key => $value) {
                if ($value == '') {
                    unset($data[$key]);
                }
            }

            $this->db->where('id', $changeAdminUserPassword['adminuser_id']);
            $this->db->update('systemadmin_franchise', $data);
            return 1;
        } else {
            return false;
        }
    }

    //Action For Edit AdminUser.
    public function editFranchiseAdminUser($editAdminUser) {

        if (isset($editAdminUser)) {

            $data = array(
                'firstname' => $editAdminUser['firstname'],
                'lastname' => $editAdminUser['lastname'],
                'nickname' => $editAdminUser['nickname'],
            );



            $this->db->where('id', $editAdminUser['adminuser_id']);
            $this->db->update('systemadmin_franchise', $data);
            return 1;
        } else {
            return FALSE;
        }
    }

    public function disableFranchiseAdminUser($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('systemadmin_franchise', array('active' => 0));
            return 1;
        } else {
            return FALSE;
        }
    }

    public function enableFranchiseAdminUser($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('systemadmin_franchise', array('active' => 1));
            return 1;
        } else {
            return FALSE;
        }
    }

    // Check Username In Database For Master Acc.
    public function check_franchiseAdminUsername($username) {

        $this->db->select('username');
        $this->db->where('username', $username);
        $res = $this->db->get('systemadmin_franchise');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    public function check_franchiseCustomerUsername($username) {

        $this->db->select('username');
        $this->db->where('username', $username);
        $res = $this->db->get('users');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    public function getEditFranchiseCustomerdata($customer_id) {
        if (isset($customer_id)) {

            $this->db->select('*');
            $this->db->from('accounts');
            $this->db->where('id', $customer_id);
            $res = $this->db->get();
            $result = $res->result_array();
            return $result[0];
        } else {
            return FALSE;
        }
    }

    public function franchisecustomerlist($franchiseid) {
        $this->db->select('state');
        $this->db->where('account_id', $franchiseid);
        $this->db->where('account_type', 2);
        $this->db->group_by('state');
        $customer = $this->db->get('accounts')->result();
        return $customer;
    }

    public function franchisepackagelist($franchiseid) {
        $this->db->select('accounts.package_id,packages.item_limit,packages.name');
        $this->db->where('accounts.account_id', $franchiseid);
        $this->db->where('account_type', 2);
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->group_by('accounts.package_id');
        $packages = $this->db->get()->result();
        return $packages;
    }

    public function ChangeUserPassword($changeUserPassword) {

        if (isset($changeUserPassword['adminuser_id'])) {

            $this->db->set('password', $changeUserPassword['new_password']);
            $this->db->where('id', $changeUserPassword['adminuser_id']);
            $this->db->update('users');
            return 1;
        } else {
            return false;
        }
    }

    public function disableCustomerUser($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('users', array('active' => 0));
            return 1;
        } else {
            return FALSE;
        }
    }

    public function enableCustomerUser($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('users', array('active' => 1));
            return 1;
        } else {
            return FALSE;
        }
    }

    //Action For Edit CustomerUser.
    public function editCustomerUser($editCustomerUser) {

        if (isset($editCustomerUser)) {

            $data = array(
                'first_name' => $editCustomerUser['first_name'],
                'last_name' => $editCustomerUser['last_name']
            );

            $this->db->where('id', $editCustomerUser['adminuser_id']);
            $this->db->update('users', $data);
            return 1;
        } else {
            return False;
        }
    }

    public function getcustomername($customerid) {
        $company = $this->db->select('name')->where('id', $customerid)->get('accounts')->row();
        return $company->company_name;
    }

    // Get System Summary for Franchise Dashboard
    public function summary($franchise_id) {

        $this->db->where(array('account_id' => $franchise_id, 'account_type' => 2));
        $rs = $this->db->get('accounts')->result();
        $total = count($rs);

        $where = array('account_id' => $franchise_id, 'active' => 1, 'account_type' => 2);
        $this->db->where($where);
        $res = $this->db->get('accounts')->result();
        $enable = count($res);
        $disable = $total - $enable;

        $summary = array();
        $summary[] = array(
            'live' => $enable,
            'disable' => $disable
        );
        return $summary;
    }

    // Get Recently Added Accounts for Franchise Dashboard
    public function getrecentaccounts($franchise_id) {

        $this->db->select('accounts.name As company_name,accounts.package_id,packages.name,packages.item_limit');
        $this->db->where(array('accounts.account_id' => $franchise_id, 'accounts.account_type' => 2));
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->order_by('accounts.id desc');
        $recent = $this->db->get()->result();
        return $recent;
    }

    // add profile
    public function addProfile($owner, $category, $manu, $manufacturer) {
        $data = $this->session->userdata('AdminUserFranchise');


        $profiledata = array(
            'profile_name' => $this->input->post('profile_name'),
            'owner' => (json_encode($owner) == 'null') ? 0 : json_encode($owner),
            'category' => (json_encode($category) == 'null') ? 0 : json_encode($category),
            'manu' => (json_encode($manu) == 'null') ? 0 : json_encode($manu),
            'manufacturer' => (json_encode($manufacturer) == 'null') ? 0 : json_encode($manufacturer),
            'account_id' => $data['franchise_account_id'],
            'account_type' => 2
        );
        $this->db->insert('profile', $profiledata);
        $id = $this->db->insert_id();
        if ($id) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function inaddProfile($owner, $category, $manu, $manufacturer, $field_name, $field_type, $field_values) {
        if (($owner[0]) || ($category[0]) || ($manu[0]) || ($manufacturer[0]) || ($field_name)) {
            $data = $this->input->post('masterid');
            if ($field_name) {
                for ($i = 0; $i < count($field_name); $i++) {
                    $arr['name'][$i] = $field_name[$i];
                    $arr['type'][$i] = $field_type[$i];
                    $arr['values'][$i] = $field_values[$i];
                }
            }
            $profiledata = array('profile_name' => $this->input->post('profile_name'),
                'owner' => json_encode($owner),
                'category' => json_encode($category),
                'manu' => json_encode($manu),
                'manufacturer' => json_encode($manufacturer),
                'account_id' => $data,
                'account_type' => 2,
                'custom_field' => (json_encode($field_name) == 'null') ? 0 : json_encode($arr)
            );

            foreach ($profiledata as $key => $value) {
                if ($value == '[""]') {
                    unset($profiledata[$key]);
                }
            }
            $this->db->insert('profile', $profiledata);
            $id = $this->db->insert_id();
            if ($id) {
                return TRUE;
            }
        }
        return FALSE;
    }

    // Get Profile List
    public function profilelist() {
        $data = $this->session->userdata('AdminUserFranchise');
        $this->db->where('account_id', $data['franchise_account_id']);
        $this->db->where('account_type', 2);
        $profileinfo = $this->db->get('profile')->result();
        return $profileinfo;
    }

    // Get Profile List For Youaduit
    public function inprofilelist($id) {

        $this->db->where('account_id', $id);
        $this->db->where('account_type', 2);
        $profileinfo = $this->db->get('profile')->result();
        return $profileinfo;
    }

//Action For Edit Profile.
    public function editProfile($editProfile) {

        if (isset($editProfile)) {

            $this->db->set('profile_name', $editProfile['profilename']);
            $this->db->where('profile_id', $editProfile['adminuser_id']);
            $this->db->update('profile');
            return 1;
        } else {
            return False;
        }
    }

    public function addComplianceTestForMaster($data) {

        $idArr = array();
        $arr = $data['task_details'];
        $arr = explode(',', $arr);
        foreach ($arr as $key => $value) {
            $newArr = explode('|', $value);
            if (!empty($newArr)) {
                if ($newArr[0] == 'true') {

                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'template_task' => 1, 'admin_id' => $data['masterid'], 'account_type' => 2);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'template_task' => 1, 'admin_id' => $data['masterid'], 'account_type' => 2);

                    $this->db->insert('tasks', $temp);
                    $id = $this->db->insert_id();
                    $idArr[] = $id;
                }
                else {
                    $idArr[] = $newArr[2];
                }
            }
        }
        $tasks = implode(',', $idArr);
        $dateInput = explode('/', $data['start_of_check']);
        $dateOutput = $dateInput[2] . '-' . $dateInput[1] . '-' . $dateInput[0];
        $set = array(
            'Compliance_check_name' => $data['Compliance_check_name'],
            'mandatory' => $data['mandatory'],
            'frequency' => $data['frequency'],
            'tasks' => $tasks,
            'admin_id' => $data['masterid'],
            'account_type' => 2
        );
        $this->db->insert('compliance_template', $set);
//        $check_id = $this->db->insert_id();
//        foreach ($idArr as $key => $value) {
//            $this->db->insert('compliance_tasks', array('compliance_id' => $check_id, 'task_id' => $value));
//        }
        return true;
    }

    public function getAllTasksForFranchiseAdmins($masterid) {
        $sql = "SELECT tasks.id, tasks.task_name, tasks.type_of_task, measurements.measurement_name, measurements.id as mid  FROM tasks LEFT JOIN measurements ON tasks.measurement = measurements.id WHERE tasks.account_id = '0' and tasks.template_task = '1' and tasks.archive = '0' and admin_id=$masterid and account_type='2'";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAllMasterCompliances($masterid) {

        $this->db->select('compliance_template.id as cid,compliance_template.Compliance_check_name,compliance_template.mandatory,compliance_template.frequency,test_freq.test_frequency as freq_name,compliance_template.tasks');
        $this->db->from('compliance_template');
        $this->db->join('test_freq', 'compliance_template.frequency = test_freq.test_freq_id');
        $this->db->where('compliance_template.admin_id', $masterid);
        $this->db->where('compliance_template.account_type', 2);
        $query = $this->db->order_by('compliance_template.id', 'desc')->get();

        if ($query->num_rows > 0) {
            $query = $query->result_array();
            foreach ($query as $key => $value) {
                $tasks = array();
                $tasks = explode(',', $value['tasks']);

                $query[$key]['total_tasks'] = count($tasks);
            }

            return $query;
        } else {
            return false;
        }
    }

    public function updateCompliance($data, $masterid) {

        $idArr = array();
        $arr = $data['task_details'];
        $delTask = $data['oldDeletedTask'];
        $arr = explode(',', $arr);
        foreach ($arr as $key => $value) {
            $newArr = explode('|', $value);
            if (!empty($newArr)) {
                if ($newArr[0] == 'true') {


                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'account_id' => 0, 'template_task' => 1, 'admin_id' => $masterid, 'account_type' => 2);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'account_id' => 0, 'template_task' => 1, 'admin_id' => $masterid, 'account_type' => 2);
                    $this->db->insert('tasks', $temp);
                    $id = $this->db->insert_id();
                    $idArr[] = $id;
                }
//                else {
//                    $idArr[] = $newArr[2];
//                }

                if ($newArr[0] == 'false') {

                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'account_id' => 0, 'template_task' => 1);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'account_id' => 0, 'template_task' => 1);

                    $this->db->where('id', $newArr[2])->update('tasks', $temp);
//                    $id = $this->db->insert_id();
                    $idArr[] = $newArr[2];
                }
            }
        }

        foreach ($delTask as $key => $value) {
            $this->db->where('id', $data['compliance_check_id'])->update('compliance_template', array('tasks' => ''));
        }
        if ($idArr[0] != '') {
            $tasks = implode(',', $idArr);
            $this->db->where('id', $data['compliance_check_id'])->update('compliance_template', array('tasks' => $tasks));
        }
//        $dateInput = explode('/', $data['start_of_task']);
//        $dateOutput = $dateInput[2] . '-' . $dateInput[1] . '-' . $dateInput[0];
        $set = array('Compliance_check_name' => $data['compliance_check_name'], 'mandatory' => $data['mandatory'], 'frequency' => $data['frequency']);

        $te = $this->db->where('id', $data['compliance_check_id'])->update('compliance_template', $set);

        return $te;
    }

    public function updateMultiCompliance($data) {
        if ($data['compliances_id'] != '') {
            $ids = explode(',', $data['compliances_id']);
            $set = array('frequency' => $data['frequency'], 'mandatory' => $data['mandatory']);
            $this->db->where_in('id', $ids)->update('compliance_template', $set);
        }
        return true;
    }

    public function getProfile($profile_id) {
        if ($profile_id) {
            $this->db->select('*');
            $this->db->where('profile_id', $profile_id);
            $res = $this->db->get('profile');
            $result = $res->result_array();
            foreach ($result as $record) {

                $mydata = array(
                    'owner' => json_decode($record['owner']),
                    'category' => json_decode($record['category']),
                    'manu' => json_decode($record['manu']),
                    'manufacturer' => json_decode($record['manufacturer'])
                );
            }

            return $mydata;
        } else {
            return FALSE;
        }
    }

    // Action For Genrate Master Customer PDf
    public function exportFranchiseCusPdf($export, $account_id) {
        if ($export != '') {
            $query = "SELECT  accounts.name AS company_name, city, state, postcode, qr_refcode, packages.name, annual_value, compliance, fleet, condition_module, depereciation_module, reporting_module, create_date, accounts.account_id, account_type, accounts.active, Count( items.id ) AS number_asset 
FROM accounts
LEFT JOIN packages ON package_id = packages.id
LEFT JOIN items ON accounts.id = items.account_id
WHERE account_type =2
AND accounts.account_id =$account_id
GROUP BY accounts.id
HAVING (
COUNT( accounts.id ) >=1
)";
        }
        $res = $this->db->query($query);

        $res1 = $this->db->where('id', $account_id)->select('company_name,contact_name')->from('franchise_ac')->get()->result_array();

        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Company Name', 'strFieldReference' => 'company_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'City', 'strFieldReference' => 'city', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'State', 'strFieldReference' => 'state', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'PostCode', 'strFieldReference' => 'postcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'QR Ref Code', 'strFieldReference' => 'qr_refcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Package', 'strFieldReference' => 'name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Annual Value', 'strFieldReference' => 'annual_value', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'No of Assets', 'strFieldReference' => 'number_asset', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Compliance', 'strFieldReference' => 'compliance', 'strConversion' => 'compliance', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Fleet', 'strFieldReference' => 'fleet', 'strConversion' => 'fleet', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Condition', 'strFieldReference' => 'condition_module', 'strConversion' => 'condition_module', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Depreciation', 'strFieldReference' => 'depereciation_module', 'strConversion' => 'depereciation_module', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Reporting', 'strFieldReference' => 'reporting_module', 'strConversion' => 'reporting_module', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'AC Created Date', 'strFieldReference' => 'create_date', 'strConversion' => 'create_date', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );
            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array(), $res1[0]['contact_name'], $res1[0]['company_name']);
        }
    }

    //Get Master Admin USer  Pdf
    public function exportFranchiseAdminUser($export, $master_id) {
        if ($export != '') {
            $query = "select firstname,lastname,username
                  FROM systemadmin_franchise WHERE franchise_account_id =" . $master_id;
        }
        $res = $this->db->query($query);

        $res1 = $this->db->where('id', $master_id)->select('company_name,contact_name')->from('franchise_ac')->get()->result_array();

        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'First Name', 'strFieldReference' => 'firstname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Last Name', 'strFieldReference' => 'lastname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Username', 'strFieldReference' => 'username', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );
            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array(), $res1[0]['contact_name'], $res1[0]['company_name']);
        }
    }

    public function exportFranchiseProfilePdf($export, $masterid) {
        if ($export != '') {
            $query = "select profile_name,owner,category,manu,manufacturer
                  FROM profile WHERE account_type = '2' AND account_id =" . $masterid;
        }
        $res = $this->db->query($query);

        $res1 = $this->db->where('id', $masterid)->select('company_name,contact_name')->from('franchise_ac')->get()->result_array();

        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Profile Name', 'strFieldReference' => 'profile_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Owner', 'strConversion' => 'owner', 'strFieldReference' => 'owner', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Category', 'strConversion' => 'category', 'strFieldReference' => 'category', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Item/Manu', 'strConversion' => 'manu', 'strFieldReference' => 'manu', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Manufacturer', 'strConversion' => 'manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );
            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array(), $res1[0]['contact_name'], $res1[0]['company_name']);
        }
    }

    public function outputPdfFile($strReportName, $arrFields, $arrResults, $customerName, $cmpName, $booOutputHtml = false) {





        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"includes/css/report.css\" /></head>";

        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>" . $customerName . "/" . $cmpName . "</h1>";
        $strHtml .= "<h2>" . $strReportName . "</h2>";
        $strHtml .= "</td><td class=\"right\">";

        $logo = 'logo.png';
        if (isset($this->session->userdata['theme_design']->logo)) {
            $logo = $this->session->userdata['theme_design']->logo;
        }

        $strHtml .= "<img alt=\"ictracker\" src='brochure/logo/" . $logo . "'>";

        $strHtml .= "</td></tr></table>";



        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead >";
        $strHtml .= "<tr style='background-color:#00AEEF'>";
        foreach ($arrFields as $arrReportField) {
            $strHtml .= "<th style='color:white'>" . $arrReportField['strName'] . "</th>";
        }

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        $arrTotals = array();
        foreach ($arrResults as $objItem) {

            $strHtml .= "<tr>";
            $arrPageData['arrSessionData'] = $this->session->userdata;

            foreach ($arrFields as $arrReportField) {

                $strHtml .= "<td style='height:50px'>";
                if (array_key_exists('strConversion', $arrReportField)) {
                    switch ($arrReportField['strConversion']) {
                        case 'compliance':

                            if ($objItem['compliance'] == 1) {
                                $strHtml .= 'YES';
                            } else {
                                $strHtml .= "NO";
                            }
                            break;
                        case 'fleet':
                            if ($objItem['fleet'] == 1) {
                                $strHtml .= 'YES';
                            } else {
                                $strHtml .= "NO";
                            }
                            break;
                        case 'condition_module':
                            if ($objItem['condition_module'] == 1) {
                                $strHtml .= 'YES';
                            } else {
                                $strHtml .= "NO";
                            }
                            break;
                        case 'depereciation_module':
                            if ($objItem['depereciation_module'] == 1) {
                                $strHtml .= 'YES';
                            } else {
                                $strHtml .= "NO";
                            }
                            break;
                        case 'reporting_module':
                            if ($objItem['reporting_module'] == 1) {
                                $strHtml .= 'YES';
                            } else {
                                $strHtml .= "NO";
                            }
                            break;
                        case 'create_date':

                            $strHtml .= date('d/m/Y', $objItem['create_date']);

                            break;

                        case 'owner':

                            $owner = json_decode($objItem['owner']);
                            if (!empty($owner)) {
                                foreach ($owner as $val) {
                                    if ($val == '') {
                                        $strHtml .= "N/A";
                                        $strHtml .= "<br>";
                                    } else {
                                        $strHtml .= $val;
                                        $strHtml .= "<br>";
                                    }
                                }
                            } else {
                                $strHtml .= "N/A";
                            }

                            break;

                        case 'category':

                            $category = json_decode($objItem['category']);
                            if (!empty($category)) {
                                foreach ($category as $cat) {
                                    if ($cat == '') {
                                        $strHtml .= "N/A";
                                        $strHtml .= "<br>";
                                    } else {
                                        $strHtml .= $cat;
                                        $strHtml .= "<br>";
                                    }
                                }
                            } else {
                                $strHtml .= 'N/A';
                            }

                            break;

                        case 'manu':

                            $manu = json_decode($objItem['manu']);
                            if (!empty($manu)) {
                                foreach ($manu as $man) {
                                    if ($man == '') {
                                        $strHtml .= "N/A";
                                        $strHtml .= "<br>";
                                    } else {
                                        $strHtml .= $man;
                                        $strHtml .= "<br>";
                                    }
                                }
                            } else {
                                $strHtml .= "N/A";
                            }

                            break;

                        case 'manufacturer':

                            $manufacturer = json_decode($objItem['manufacturer']);
                            if (!empty($manufacturer)) {
                                foreach ($manufacturer as $manufact) {
                                    if ($manufact == '') {
                                        $strHtml .= "N/A";
                                        $strHtml .= "<br>";
                                    } else {
                                        $strHtml .= $manufact;
                                        $strHtml .= "<br>";
                                    }
                                }
                            } else {
                                $strHtml .= "N/A";
                            }

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


        if (($arrPageData['arrSessionData']["objAdminUser"])) {
            $strHtml .= "<p>Produced by " . $arrPageData['arrSessionData']["objAdminUser"]->firstname . " " . $arrPageData['arrSessionData']["objAdminUser"]->lastname . " (" . $arrPageData['arrSessionData']["objAdminUser"]->username . ") on " . date('d/m/Y') . "</p>";
            $strHtml .= "</div></body></html>";
        } else {
            $strHtml .= "<p>Produced by " . $arrPageData['arrSessionData']['YouAuditSystemAdmin']['firstname'] . " " . $arrPageData['arrSessionData']['YouAuditSystemAdmin']['lastname'] . " (" . $arrPageData['arrSessionData']['YouAuditSystemAdmin']['username'] . ") on " . date('d/m/Y') . "</p>";
            $strHtml .= "</div></body></html>";
        }
        echo $strHtml;
        die();

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

// check unique qrcode
    public function check_qrcode($qr_code) {

        $this->db->select('qr_refcode');
        $this->db->where('qr_refcode', $qr_code);
        $res = $this->db->get('accounts');

        if ($res->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    // Archive Customer 
    public function customerArchive($customer_id) {

        if (isset($customer_id)) {
            $this->db->where('id', $customer_id);
            $this->db->update('accounts', array('archive' => 0, 'active' => 0));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function editMultipleAccount($data) {
        if ($data['account_id'] != '') {
            if (strpos($data['account_id'], 'on') !== FALSE) {
                $data['account_id'] = str_replace('on,', '', $data['account_id']);
            }
            $ids = explode(',', $data['account_id']);



            $update_list = array(
                'package_id' => $this->input->post('multiple_package_type'),
                'compliance' => $this->input->post('multiple_compliance_module'),
                'fleet' => $this->input->post('multiple_fleet_module'),
                'condition_module' => $this->input->post('multiple_condition_module'),
                'depereciation_module' => $this->input->post('multiple_depreciation_module'),
                'reporting_module' => $this->input->post('multiple_reporting_module'),
            );





            foreach ($update_list as $key => $value) {
                if ($value == '') {
                    unset($update_list[$key]);
                }
            }
            if (empty($update_list)) {
                
            } else {

                $this->db->where_in('id', $ids)->update('accounts', $update_list);
            }
            return TRUE;
        }
    }

// Edit Multiple Franchise account
    public function editMultiple_FranchiseAc() {
        $data = $this->input->post();
        if ($data['account_id'] != '') {
            if (strpos($data['account_id'], 'on') !== FALSE) {
                $data['account_id'] = str_replace('on,', '', $data['account_id']);
            }
            $ids = explode(',', $data['account_id']);



            $update_list = array(
                'account_limit' => $this->input->post('multiple_account_limit'),
            );





            foreach ($update_list as $key => $value) {
                if ($value == '') {
                    unset($update_list[$key]);
                }
            }
            if (empty($update_list)) {
                
            } else {

                $this->db->where_in('id', $ids)->update('franchise_ac', $update_list);
            }
            return TRUE;
        }
    }

    public function customerrestore($customer_id) {

        if (isset($customer_id)) {
            $this->db->where('id', $customer_id);
            $this->db->update('accounts', array('active' => 1, 'archive' => 1));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Profile name
    public function checkProfile($profilename, $account_id) {

        $this->db->select('profile_name');
        $this->db->where('account_type', 2);
        $this->db->where('account_id', $account_id);
        $this->db->where('profile_name', $profilename);
        $res_franchise = $this->db->get('profile');

        if ($res_franchise->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
