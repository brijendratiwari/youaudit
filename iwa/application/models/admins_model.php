<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Admins_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function logInMaster() {

//	if ($this->doAdminsExistMaster()) {
        $arrInput = array(
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
            'active' => 1
        );
        $resQuery = $this->db->get_where('systemadmin_master', $arrInput, 1);

        // Make sure we capture the SQL for debugging
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result();
            $this->load->model('master_model');
            $master_data = $this->master_model->getAccountName($arrResult['result'][0]->master_account_id);
            $arrResult['Account_Name'] = strtoupper($master_data[0]['company_name']);
            $arrResult['booSuccess'] = true;
            return $arrResult;
        } else {
            // If we didn't find rows,
            // then return false
            $arrResult['result'] = array();
            $arrResult['booSuccess'] = false;
            return $arrResult;
        }
    }

//    }

    public function logInFranchise() {
        if ($this->doAdminsExistFranchises()) {
            $arrInput = array(
                'username' => $this->input->post('username'),
                'password' => md5($this->input->post('password')),
                'active' => 1
            );
            $resQuery = $this->db->get_where('systemadmin_franchise', $arrInput, 1);

            // Make sure we capture the SQL for debugging
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if ($resQuery->num_rows == 1) {
                $arrResult['result'] = $resQuery->result();
                $this->load->model('franchise_model');
                $franchise_data = $this->franchise_model->getFranchiseAccountName($arrResult['result'][0]->franchise_account_id);
                $arrResult['Account_Name'] = strtoupper($franchise_data[0]['company_name']);
                $arrResult['booSuccess'] = true;
                return $arrResult;
            } else {
                // If we didn't find rows,
                // then return false
                $arrResult['result'] = array();
                $arrResult['booSuccess'] = false;
                return $arrResult;
            }
        }
    }

    //check Pin Number For Master
    public function masterPincodeAuthentication($arrInput) {
        $this->load->model('master_model');

        $this->db->select('*');
        $this->db->where('username', $arrInput['username']);
        $this->db->where('pin_number', $arrInput['pin_number']);
        $res = $this->db->get('systemadmin_master');
        if ($res->num_rows() > 0) {
            $arrResult['result'] = $res->result();
            $master_data = $this->master_model->getAccountName($arrResult['result'][0]->master_account_id);
            $arrResult['Account_Name'] = strtoupper($master_data[0]['company_name']);
            $arrResult['booSuccess'] = TRUE;
            return $arrResult;
        } else {
            return False;
        }
    }

    //check Pin Number For Master
    public function franchisePincodeAuthentication($arrInput) {
        $this->load->model('franchise_model');


        $this->db->select('*');
        $this->db->where('username', $arrInput['username']);
        $this->db->where('pin_number', $arrInput['pin_number']);
        $resQuery = $this->db->get('systemadmin_franchise');
        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result();

            $franchise_data = $this->franchise_model->getFranchiseAccountName($arrResult['result'][0]->franchise_account_id);
            $arrResult['Account_Name'] = strtoupper($franchise_data[0]['company_name']);
            $arrResult['booSuccess'] = true;
            return $arrResult;
        } else {
            // If we didn't find rows,
            // then return false
            $arrResult['result'] = array();
            $arrResult['booSuccess'] = false;
            return $arrResult;
        }
    }

    public function doAdminsExistMaster() {
        if ($this->getCount() != 0) {
            if ($this->getActiveCount() > 0) {
                //no problem, move along
                return true;
            } else {
                // find the temporary user
                $arrAdminData = $this->getByUserNameMaster('temp@temp.com', false);
                //did we get him?
                if ($arrAdminData['booSuccess'] == true) {
                    $this->setActive($arrAdminData['result'][0]->adminid);
                    echo "<pre>SYSTEM MESSAGE: RE-ACTIVATED TEMPORARY USER.</pre>";
                    die();
                } else {
                    // no idea
                    echo "<pre>SYSTEM ERROR: UNABLE TO ACTIVATE TEMPORARY ADMIN. CONTACT SUPPORT.</pre>";
                    die();
                }
            }
        } else {
            //write the temporary user
            $arrInput = array(
                'firstname' => 'Delete',
                'lastname' => 'Me',
                'nickname' => 'Delete Me',
                'username' => 'temp@temp.com',
                'password' => md5('deleteme'),
                'photo_id' => 1
            );
            $this->db->insert('systemadmins', $arrInput);
            echo "<pre>SYSTEM MESSAGE: CREATED TEMPORARY USER.</pre>";
            die();
        }
    }

    public function doAdminsExistFranchises() {
        if ($this->getCount() != 0) {
            if ($this->getActiveCount() > 0) {
                //no problem, move along
                return true;
            } else {
                // find the temporary user
                $arrAdminData = $this->getByUserNameFranchises('temp@temp.com', false);
                //did we get him?
                if ($arrAdminData['booSuccess'] == true) {
                    $this->setActive($arrAdminData['result'][0]->adminid);
                    echo "<pre>SYSTEM MESSAGE: RE-ACTIVATED TEMPORARY USER.</pre>";
                    die();
                } else {
                    // no idea
                    echo "<pre>SYSTEM ERROR: UNABLE TO ACTIVATE TEMPORARY ADMIN. CONTACT SUPPORT.</pre>";
                    die();
                }
            }
        } else {
            //write the temporary user
            $arrInput = array(
                'firstname' => 'Delete',
                'lastname' => 'Me',
                'nickname' => 'Delete Me',
                'username' => 'temp@temp.com',
                'password' => md5('deleteme'),
                'photo_id' => 1
            );
            $this->db->insert('systemadmins', $arrInput);
            echo "<pre>SYSTEM MESSAGE: CREATED TEMPORARY USER.</pre>";
            die();
        }
    }

    public function getByUserNameMaster($strUserName = '', $booActiveOnly = true) {
        if ($strUserName != "") {
            // Run the query
            $this->db->select('systemadmin_master.id AS adminid,
				systemadmin_master.username AS username,
				systemadmin_master.firstname AS firstname,
				systemadmin_master.lastname AS lastname,
				systemadmin_master.nickname AS nickname,
				photos.id AS photoid,
				photos.title AS phototitle,
				photos.uri AS photouri');
            $this->db->from('systemadmin_master');
            $this->db->join('photos', 'systemadmin_master.photo_id = photos.id', 'left');
            $this->db->where('systemadmin_master.username', $strUserName);
            if ($booActiveOnly) {
                $this->db->where('systemadmin_master.active', 1);
            }

            $resQuery = $this->db->get();

            // Make sure we capture the SQL for debugging
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if ($resQuery->num_rows == 1) {
                $arrResult['result'] = $resQuery->result();
                $arrResult['booSuccess'] = true;
            } else {
                // If we didn't find rows,
                // then return false
                $arrResult['result'] = array();
                $arrResult['booSuccess'] = false;
            }
        } else {
            $arrResult['result'] = array();
            $arrResult['booSuccess'] = false;
        }

        return $arrResult;
    }

    public function getByUserNameFranchises($strUserName = '', $booActiveOnly = true) {
        if ($strUserName != "") {
            // Run the query
            $this->db->select('systemadmin_franchise.id AS adminid,
				systemadmin_franchise.username AS username,
				systemadmin_franchise.firstname AS firstname,
				systemadmin_franchise.lastname AS lastname,
				systemadmin_franchise.nickname AS nickname,
				photos.id AS photoid,
				photos.title AS phototitle,
				photos.uri AS photouri');
            $this->db->from('systemadmin_franchise');
            $this->db->join('photos', 'systemadmin_franchise.photo_id = photos.id', 'left');
            $this->db->where('systemadmin_franchise.username', $strUserName);
            if ($booActiveOnly) {
                $this->db->where('systemadmin_franchise.active', 1);
            }

            $resQuery = $this->db->get();

            // Make sure we capture the SQL for debugging
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if ($resQuery->num_rows == 1) {
                $arrResult['result'] = $resQuery->result();
                $arrResult['booSuccess'] = true;
            } else {
                // If we didn't find rows,
                // then return false
                $arrResult['result'] = array();
                $arrResult['booSuccess'] = false;
            }
        } else {
            $arrResult['result'] = array();
            $arrResult['booSuccess'] = false;
        }

        return $arrResult;
    }

    public function setOne($intId = -1) {
        $arrInput = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'photo_id' => (int) $this->input->post('photo_id')
        );
        if ($this->input->post('password') != '') {
            $arrInput['password'] = $this->input->post('password');
        }

        if ($this->input->post('nickname') != '') {
            $arrInput['nickname'] = $this->input->post('nickname');
        } else {
            $arrInput['nickname'] = $this->input->post('firstname');
        }

        if ($intId > 0) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmins', $arrInput);
        } else {
            $arrInput['username'] = $this->input->post('username');
            return $this->db->insert('systemadmins', $arrInput);
        }
    }

    public function setOneMaster($arrSessionData = array()) {
        $arrInput = array(
            'firstname' => $this->input->post('first_name'),
            'lastname' => $this->input->post('last_name'),
            'password' => md5($this->input->post('user_password')),
            'username' => $this->input->post('username'),
            'nickname' => $this->input->post('contact_name'),
            'pin_number' => md5($this->input->post('pin_number')),
            'master_account_id' => $arrSessionData->master_account_id,
            'active' => '1'
        );

        if ($this->input->post('adminuser_id')) {
            $this->db->where('id', $this->input->post('adminuser_id'));
            $editAdminUser = array(
                'firstname' => $this->input->post('edit_first_name'),
                'lastname' => $this->input->post('edit_last_name'),
                'nickname' => $this->input->post('edit_contact_name'),
                'master_account_id' => $arrSessionData->master_account_id
            );
            $this->db->update('systemadmin_master', $editAdminUser);
            return TRUE;
        } else {
            return $this->db->insert('systemadmin_master', $arrInput);
        }
    }

    public function setOneFranchise($arrSessionData = array()) {
        $arrInput = array(
            'firstname' => $this->input->post('first_name'),
            'lastname' => $this->input->post('last_name'),
            'password' => md5($this->input->post('user_password')),
            'username' => $this->input->post('username'),
            'nickname' => $this->input->post('contact_name'),
            'pin_number' => md5($this->input->post('pin_number')),
            'franchise_account_id' => $arrSessionData->franchise_account_id,
            'active' => '1'
        );

        if ($this->input->post('adminuser_id')) {
            $editAdminUser = array(
                'firstname' => $this->input->post('edit_first_name'),
                'lastname' => $this->input->post('edit_last_name'),
                'nickname' => $this->input->post('edit_contact_name'),
                'franchise_account_id' => $arrSessionData->franchise_account_id
            );
            $this->db->where('id', $this->input->post('adminuser_id'));
            $this->db->update('systemadmin_franchise', $editAdminUser);
            return TRUE;
        } else {
            return $this->db->insert('systemadmin_franchise', $arrInput);
        }
    }

    public function setActive($intId = -1) {
        if ($intId > 0) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmins', array('active' => 1));
        } else {
            return false;
        }
    }

    public function setCredentials($intId = -1, $booUsernameChanged = false, $booPasswordChanged = false) {
        $arrInput = array();

        if ($booUsernameChanged) {
            $arrInput['username'] = $this->input->post('username');
        }

        if ($booPasswordChanged) {
            $arrInput['password'] = $this->input->post('password');
        }

        if (($intId > 0) && ( $booUsernameChanged || $booPasswordChanged)) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmins', $arrInput);
        } else {
            return false;
        }
    }

    public function deleteOne($intId = -1) {

        if (($intId > 0) && ($this->getActiveCount() > 1)) {
            $arrInput = array('active' => 0);
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmins', $arrInput);
        }
        return false;
    }

    public function deleteOneMaster($intId = -1) {

        if ($intId) {

            $arrInput = array('active' => 0);
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmin_master', $arrInput);
        }
        return false;
    }

    public function deleteOneFranchise($intId = -1) {

        if ($intId) {

            $arrInput = array('active' => 0);
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmin_franchise', $arrInput);
        }
        return false;
    }

    public function reactiveOneMaster($intId = -1) {

        if ($intId) {

            $arrInput = array('active' => 1);
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmin_master', $arrInput);
        }
        return false;
    }

    public function reactiveOneFranchise($intId = -1) {

        if ($intId) {
            $arrInput = array('active' => 1);
            $this->db->where('id', (int) $intId);
            return $this->db->update('systemadmin_franchise', $arrInput);
        }
        return false;
    }

    public function getCount() {
        $strSelectFields = 'COUNT(*) AS number';
        $this->db->select($strSelectFields);
        $this->db->from('systemadmins');

        // Do it
        $resQuery = $this->db->get();

        $objRow = $resQuery->result();

        return $objRow[0]->number;
    }

    public function getActiveCount() {
        $strSelectFields = 'COUNT(*) AS number';
        $this->db->select($strSelectFields);
        $this->db->from('systemadmins');
        $this->db->where('systemadmins.active', 1);

        // Do it
        $resQuery = $this->db->get();

        $objRow = $resQuery->result();

        return $objRow[0]->number;
    }

    public function getActiveAccountCount() {
        $strSelectFields = 'COUNT(*) AS number';
        $this->db->select($strSelectFields);
        $this->db->from('accounts');
        $this->db->where('accounts.active', 1);
        $this->db->where('accounts.verified', 1);
        $this->db->where('accounts.test_account', 0);

        // Do it
        $resQuery = $this->db->get();

        $objRow = $resQuery->result();

        return $objRow[0]->number;
    }

    public function getAccountName($intAccountId) {
        $this->db->select("accounts.name");
        $this->db->from('accounts');
        $this->db->where('accounts.id', $intAccountId);
        $resQuery = $this->db->get();

        $objRow = $resQuery->result();

        return $objRow[0]->name;
    }

    public function getAllMaster($arrSessionData = array()) {

        if (isset($arrSessionData)) {
            $strSelectFields = 'systemadmins.id AS adminid,
			    systemadmins.username AS username,
			    systemadmins.firstname AS firstname,
			    systemadmins.lastname AS lastname,
			    systemadmins.nickname AS nickname,
                            systemadmins.active AS active,
			    photos.id AS photoid,
			    photos.title AS phototitle,
			    photos.path AS photouri';

            $this->db->select($strSelectFields);
            $this->db->from('systemadmin_master AS systemadmins');

            // joining on photos
            $this->db->join('photos', 'systemadmins.photo_id = photos.id', 'left');

            $this->db->where('systemadmins.master_account_id', $arrSessionData->master_account_id);
            $this->db->where('systemadmins.active', 1);

            // Do it
            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            if ($resQuery->num_rows != 0) {
                $arrAdmins = array();
                // If there are sysadmins, then load 

                foreach ($resQuery->result() as $objRow) {
                    $arrAdmins[] = $objRow;
                }

                $arrResult['results'] = $arrAdmins;
                $arrResult['booSuccess'] = true;
            } else {

                // If we didn't find rows,
                // then return false
                $arrResult['results'] = array();
                $arrResult['booSuccess'] = false;
            }

            return $arrResult;
        }
    }

    public function getAllFranchises($arrSessionData = array()) {

        $strSelectFields = 'systemadmin_franchise.id AS adminid,
			    systemadmin_franchise.username AS username,
			    systemadmin_franchise.firstname AS firstname,
			    systemadmin_franchise.lastname AS lastname,
			    systemadmin_franchise.nickname AS nickname,
                            systemadmin_franchise.active AS active,
			    photos.id AS photoid,
			    photos.title AS phototitle,
			    photos.path AS photouri';

        $this->db->select($strSelectFields);
        $this->db->from('systemadmin_franchise');

        // joining on photos
        $this->db->join('photos', 'systemadmin_franchise.photo_id = photos.id', 'left');
        $this->db->where('systemadmin_franchise.franchise_account_id', $arrSessionData->franchise_account_id);
        $this->db->where('systemadmin_franchise.active', 1);


        // Do it
        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        if ($resQuery->num_rows != 0) {
            $arrAdmins = array();
            // If there are sysadmins, then load 

            foreach ($resQuery->result() as $objRow) {
                $arrAdmins[] = $objRow;
            }

            $arrResult['results'] = $arrAdmins;
            $arrResult['booSuccess'] = true;
        } else {

            // If we didn't find rows,
            // then return false
            $arrResult['results'] = array();
            $arrResult['booSuccess'] = false;
        }

        return $arrResult;
    }

    public function getOneMaster($intId) {
        // Run the query
        $this->db->select('systemadmins.id AS adminid,
			    systemadmins.username AS username,
			    systemadmins.firstname AS firstname,
			    systemadmins.lastname AS lastname,
			    systemadmins.nickname AS nickname,
			    photos.id AS photoid,
			    photos.title AS phototitle, 
                            photos.file_name AS photofilename,
                            photos.image_width AS photowidth,
                            photos.image_height AS photoheight
			   ');
        $this->db->from('systemadmin_master AS systemadmins');
        $this->db->join('photos', 'systemadmins.photo_id = photos.id', 'left');
        $this->db->where('systemadmins.id', $intId);

        $resQuery = $this->db->get();

        // Make sure we capture the SQL for debugging
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result();
            $arrResult['booSuccess'] = true;
        } else {
            // If we didn't find rows,
            // then return false
            $arrResult['result'] = array();
            $arrResult['booSuccess'] = false;
        }

        return $arrResult;
    }

    public function getOneFranchise($intId) {
        // Run the query
        $this->db->select('systemadmins.id AS adminid,
			    systemadmins.username AS username,
			    systemadmins.firstname AS firstname,
			    systemadmins.lastname AS lastname,
			    systemadmins.nickname AS nickname,
			    photos.id AS photoid,
			    photos.title AS phototitle, 
                            photos.file_name AS photofilename,
                            photos.image_width AS photowidth,
                            photos.image_height AS photoheight
			   ');
        $this->db->from('systemadmin_franchise AS systemadmins');
        $this->db->join('photos', 'systemadmins.photo_id = photos.id', 'left');
        $this->db->where('systemadmins.id', $intId);

        $resQuery = $this->db->get();

        // Make sure we capture the SQL for debugging
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result();
            $arrResult['booSuccess'] = true;
        } else {
            // If we didn't find rows,
            // then return false
            $arrResult['result'] = array();
            $arrResult['booSuccess'] = false;
        }

        return $arrResult;
    }

    public function getSuperAdminRequests() {
        $strSelectFields = 'users.id AS userid,
			    users.username AS username,
			    users.password AS password,
			    users.firstname AS firstname,
			    users.lastname AS lastname,
			    users.nickname AS nickname,
			    users.active As active,
			    levels.name AS levelname,
			    levels.id AS levelid,
			    photos.id AS photoid,
                            photos.title AS phototitle,
                            photos.file_name AS photofilename,
                            photos.image_width AS photowidth,
                            photos.image_height AS photoheight,
			    accounts.id AS accountid,
			    accounts.name AS accountname';
        $this->db->select($strSelectFields);
        // need to start by selecting from accounts
        $this->db->from('accounts');
        // join onto users
        $this->db->join('users', 'accounts.id = users.account_id', 'left');
        // joining on levels
        $this->db->join('levels', 'users.level_id = levels.id', 'left');
        // joining on photos
        $this->db->join('photos', 'users.photo_id = photos.id', 'left');
        // do the Query

        $this->db->where('users.request_super_admin', 1);

        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow) {
                $arrItemsData[] = $objRow;
            }
            return array('results' => $arrItemsData, 'query' => $this->db->last_query());
        } else {
            return array('results' => array(), 'query' => $this->db->last_query());
        }
    }

    public function addComplianceTest($data) {
        $idArr = array();
        $arr = $data['task_details'];
        $arr = explode(',', $arr);
        foreach ($arr as $key => $value) {
            $newArr = explode('|', $value);
            if (!empty($newArr)) {
                if ($newArr[0] == 'true') {

                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'template_task' => 1);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'template_task' => 1);

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
            'tasks' => $tasks
        );
        $this->db->insert('compliance_template', $set);
//        $check_id = $this->db->insert_id();
//        foreach ($idArr as $key => $value) {
//            $this->db->insert('compliance_tasks', array('compliance_id' => $check_id, 'task_id' => $value));
//        }
        return true;
    }

    public function getAllCompliances() {
        $this->db->select('ct.id as cid,ct.Compliance_check_name,ct.mandatory,ct.frequency,f.test_frequency as freq_name,ct.tasks')->from('compliance_template as ct')->join('test_freq as f', 'ct.frequency = f.test_freq_id');

        $query = $this->db->order_by('cid', 'desc')->get();

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

    public function updateCompliance($data) {
        $idArr = array();
        $arr = $data['task_details'];
        $delTask = $data['oldDeletedTask'];
        $arr = explode(',', $arr);
        foreach ($arr as $key => $value) {
            $newArr = explode('|', $value);
            if (!empty($newArr)) {
                if ($newArr[0] == 'true') {

                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'account_id' => 0, 'template_task' => 1);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'account_id' => 0, 'template_task' => 1);
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

    // Check Master Admin Name
    public function checkMasterAdminUsername($username) {

        $this->db->select('username');
        $this->db->where('username', $username);
        $res_master = $this->db->get('systemadmin_master');
        $this->db->select('username');
        $this->db->where('username', $username);
        $res_franchise = $this->db->get('systemadmin_franchise');
        if ($res_master->num_rows() > 0 || $res_franchise->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    // Change Master Admin Password
    public function changeMasterAdminPassword($changeAdminUserPassword) {

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
            $this->db->update('systemadmin_master', $data);
            return TRUE;
        } else {
            return False;
        }
    }

    // Change Franchise Admin Password
    public function changeFranchiseAdminPassword($changeAdminUserPassword) {

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
            return False;
        }
    }

    // Get State For Master Customerlist for  Filter
    public function mastercustomerlist($masterid) {
        $this->db->select('state');
        $this->db->where('account_id', $masterid);
        $this->db->where('account_type', 1);
        $this->db->group_by('state');
        $customer = $this->db->get('accounts')->result();

        return $customer;
    }

    // Get Package List For Master Customerlist for  Filter
    public function masterpackagelist($masterid) {
        $this->db->select('accounts.package_id,packages.item_limit,packages.name');
        $this->db->where('accounts.account_id', $masterid);
        $this->db->where('account_type', 1);
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->group_by('accounts.package_id');
        $package = $this->db->get()->result();
        return $package;
    }

    // Get State For Franchise Customerlist for  Filter
    public function Franchisecustomerlist($masterid) {
        $this->db->select('state');
        $this->db->where('account_id', $masterid);
        $this->db->where('account_type', 2);
        $this->db->group_by('state');
        $customer = $this->db->get('accounts')->result();

        return $customer;
    }

    // Get Package List For Franchise Customerlist for  Filter
    public function Franchisepackagelist($masterid) {
        $this->db->select('accounts.package_id,packages.item_limit,packages.name');
        $this->db->where('accounts.account_id', $masterid);
        $this->db->where('account_type', 2);
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->group_by('accounts.package_id');
        $package = $this->db->get()->result();
        return $package;
    }

    // Get All Profile For Master Account
    public function Masterprofilelist($masterid) {

        $this->db->where('account_id', $masterid);
        $this->db->where('account_type', 1);
        $profileinfo = $this->db->get('profile')->result();
        return $profileinfo;
    }

    // Get All Profile For Franchise Account
    public function Franchiseprofilelist($franchiseid) {

        $this->db->where('account_id', $franchiseid);
        $this->db->where('account_type', 2);
        $profileinfo = $this->db->get('profile')->result();
        return $profileinfo;
    }

    // add profile for Account
    public function addProfile($owner, $category, $manu, $manufacturer, $field_name, $field_type, $field_values, $arrSessionData = array()) {

        if (($owner[0]) || ($category[0]) || ($manu[0]) || ($manufacturer[0]) || ($field_name)) {
            if ($field_name) {
                for ($i = 0; $i < count($field_name); $i++) {
                    $arr['name'][$i] = $field_name[$i];
                    $arr['type'][$i] = $field_type[$i];
                    $arr['values'][$i] = $field_values[$i];
            }}
            $profiledata = array(
                'profile_name' => $this->input->post('profile_name'),
                'owner' => (json_encode($owner) == 'null') ? 0 : json_encode($owner),
                'category' => (json_encode($category) == 'null') ? 0 : json_encode($category),
                'manu' => (json_encode($manu) == 'null') ? 0 : json_encode($manu),
                'manufacturer' => (json_encode($manufacturer) == 'null') ? 0 : json_encode($manufacturer),
                'custom_field' => (json_encode($field_name) == 'null') ? 0 : json_encode($arr)
            );

            if (array_key_exists('master_account_id', $arrSessionData)) {
                $profiledata['account_id'] = $arrSessionData->master_account_id;
                $profiledata['account_type'] = 1;
            } else {
                $profiledata['account_id'] = $arrSessionData->franchise_account_id;
                $profiledata['account_type'] = 2;
            }

            foreach ($profiledata as $key => $value) {
                if ($value == '[""]') {
                    unset($profiledata[$key]);
                }
            }

            $result = $this->db->insert('profile', $profiledata);
            if ($result) {
                return TRUE;
            }
        }

        return FALSE;
    }

    //Action For Edit Profile.
    //Action For Edit Profile.
    public function editProfile($editProfile, $profile_id) {

        if (isset($editProfile)) {
            $this->db->where('profile_id', $profile_id);
            $this->db->update('profile', $editProfile);
            return TRUE;
        } else {
            return False;
        }
    }

    // For Profile For Master
    public function inProfileListMaster($id) {

        $this->db->where('account_id', $id);
        $this->db->where('account_type', 1);
        $profileinfo = $this->db->get('profile')->result();
        return $profileinfo;
    }

    // For Profile For Master
    public function inProfileListFranchise($id) {

        $this->db->where('account_id', $id);
        $this->db->where('account_type', 2);
        $profileinfo = $this->db->get('profile')->result();
        return $profileinfo;
    }

    // Get System Summary for Master Dashboard
    public function masterSummary($account_id) {

        $this->db->where(array('account_id' => $account_id, 'account_type' => 1));
        $rs = $this->db->get('accounts')->result();
        $total = count($rs);

        $where = array('account_id' => $account_id, 'active' => 1, 'account_type' => 1);
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

    // Franchise Summary
    public function franchiseSummary($account_id) {

        $this->db->where(array('account_id' => $account_id, 'account_type' => 2));
        $rs = $this->db->get('accounts')->result();
        $total = count($rs);

        $where = array('account_id' => $account_id, 'active' => 1, 'account_type' => 2);
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

    // Get Recently Added Accounts for Master Dashboard
    public function getRecentAccountsForMaster($account_id) {

        $this->db->select('accounts.name AS company_name,accounts.package_id,packages.name,packages.item_limit');
        $this->db->where(array('accounts.account_id' => $account_id, 'accounts.account_type' => 1));
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->order_by('accounts.id desc');
        $recent = $this->db->get()->result();
        return $recent;
    }

    // Get Recently Added Accounts for Master Dashboard
    public function getRecentAccountsForFranchise($account_id) {

        $this->db->select('accounts.name AS company_name,accounts.package_id,packages.name,packages.item_limit');
        $this->db->where(array('accounts.account_id' => $account_id, 'accounts.account_type' => 2));
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->order_by('accounts.id desc');
        $recent = $this->db->get()->result();
        return $recent;
    }

    // Archive Master Admin
    public function archiveMasterAdmin($admin_id) {
        if (isset($admin_id)) {
            $this->db->where('id', $admin_id);
            $this->db->update('systemadmin_master', array('active' => 0));
            return TRUE;
        } else {
            return False;
        }
    }

    // Archive Franchise Admin
    public function archiveFranchiseAdmin($admin_id) {
        if (isset($admin_id)) {
            $this->db->where('id', $admin_id);
            $this->db->update('systemadmin_franchise', array('active' => 0));
            return TRUE;
        } else {
            return False;
        }
    }

    // Profile name
    public function checkProfile($profilename) {
        $arrPageData['arrSessionData'] = $this->session->userdata;

        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $account_type = 1;
//            $account_id = $arrPageData['arrSessionData']['objAdminUser']->master_account_id;
        } else {
            $account_type = 2;
//            $account_id = $arrPageData['arrSessionData']['objAdminUser']->franchise_account_id;
        }

        $this->db->select('profile_name');
        $this->db->where('account_type', $account_type);
//        $this->db->where('account_id', $account_id);
        $this->db->where('profile_name', $profilename);
        $res_master = $this->db->get('profile');

        if ($res_master->num_rows() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function customerArchive($customer_id) {

        if (isset($customer_id)) {
            $this->db->where('id', $customer_id);
            $this->db->update('accounts', array('archive' => 0, 'active' => 0));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_edit_customerdata($username) {

        $this->db->select('username');
        $this->db->where('username', $username);
        $res = $this->db->get('users');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    // Check EDIt Username
    public function edit_check_masterusername($username) {

        $this->db->select('username');
        $this->db->like('username', $username);

        $res = $this->db->get('users');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    // Edit Multiple Account
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

    //archive customer acc
    public function restoreCustomer($customer_id) {

        if (isset($customer_id)) {
            $this->db->where('id', $customer_id);
            $this->db->update('accounts', array('active' => 1, 'archive' => 1));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Get All Archive Master Admin USer

    public function getAllArchiveMaster($arrSessionData = array()) {

        if (isset($arrSessionData)) {
            $strSelectFields = 'systemadmins.id AS adminid,
			    systemadmins.username AS username,
			    systemadmins.firstname AS firstname,
			    systemadmins.lastname AS lastname,
			    systemadmins.nickname AS nickname,
                            systemadmins.active AS active,
			    photos.id AS photoid,
			    photos.title AS phototitle,
			    photos.path AS photouri';

            $this->db->select($strSelectFields);
            $this->db->from('systemadmin_master AS systemadmins');

            // joining on photos
            $this->db->join('photos', 'systemadmins.photo_id = photos.id', 'left');

            $this->db->where('systemadmins.master_account_id', $arrSessionData->master_account_id);
            $this->db->where('systemadmins.active', 0);

            // Do it
            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            if ($resQuery->num_rows != 0) {
                $arrAdmins = array();
                // If there are sysadmins, then load 

                foreach ($resQuery->result() as $objRow) {
                    $arrAdmins[] = $objRow;
                }

                $arrResult['results'] = $arrAdmins;
                $arrResult['booSuccess'] = true;
            } else {

                // If we didn't find rows,
                // then return false
                $arrResult['results'] = array();
                $arrResult['booSuccess'] = false;
            }

            return $arrResult;
        }
    }

    // Get All Archive franchise Admin USer

    public function getAllArchiveFranchises($arrSessionData = array()) {

        $strSelectFields = 'systemadmin_franchise.id AS adminid,
			    systemadmin_franchise.username AS username,
			    systemadmin_franchise.firstname AS firstname,
			    systemadmin_franchise.lastname AS lastname,
			    systemadmin_franchise.nickname AS nickname,
                            systemadmin_franchise.active AS active,
			    photos.id AS photoid,
			    photos.title AS phototitle,
			    photos.path AS photouri';

        $this->db->select($strSelectFields);
        $this->db->from('systemadmin_franchise');

        // joining on photos
        $this->db->join('photos', 'systemadmin_franchise.photo_id = photos.id', 'left');
        $this->db->where('systemadmin_franchise.franchise_account_id', $arrSessionData->franchise_account_id);
        $this->db->where('systemadmin_franchise.active', 0);


        // Do it
        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        if ($resQuery->num_rows != 0) {
            $arrAdmins = array();
            // If there are sysadmins, then load 

            foreach ($resQuery->result() as $objRow) {
                $arrAdmins[] = $objRow;
            }

            $arrResult['results'] = $arrAdmins;
            $arrResult['booSuccess'] = true;
        } else {

            // If we didn't find rows,
            // then return false
            $arrResult['results'] = array();
            $arrResult['booSuccess'] = false;
        }

        return $arrResult;
    }

}

?>