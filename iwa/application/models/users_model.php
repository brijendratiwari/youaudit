<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function logIn($arrInput) {
        $this->db->where("level_id !=", 5);
        $resQuery = $this->db->get_where('users', $arrInput, 1);

        // Make sure we capture the SQL for debugging
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result();
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
    
    public function log_usercheck($arrInput) {
//        $this->db->where("level_id !=", 5);
        $resQuery = $this->db->get_where('users', $arrInput, 1);

        // Make sure we capture the SQL for debugging
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result();
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

    public function logInForApp($arrInput) {

        $resQuery = $this->db->get_where('users', $arrInput, 1);

        // Make sure we capture the SQL for debugging
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result();
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

// End of function

    public function getBasicCredentialsFor($intId = -1) {
        $arrResult = array('result' => array(), 'booSuccess' => false);

        if ($intId > 0) {
            $strSelectFields = 'users.id AS userid,
			    users.username AS username,
                            users.is_owner AS isowner,
                            owner.id AS owner_id,
			    users.password AS password,
			    users.firstname AS firstname,
			    users.lastname AS lastname,
			    users.nickname AS nickname,
			    users.active As active,
			    users.push_notification,
			    levels.name AS levelname,
			    levels.id AS levelid,
			    photos.id AS photoid,
                            photos.title AS phototitle,
                            photos.file_name AS photofilename,
                            photos.image_width AS photowidth,
                            photos.image_height AS photoheight,
                            photos.path AS photopath,
                            accounts.id AS accountid,
			    accounts.name AS accountname,
                            accounts.qr_refcode AS qrcode,
                            accounts.fleet AS fleet,
                            accounts.compliance AS compliance,
                            accounts.condition_module,
                            accounts.currency AS currency,
                            accounts.fleet_contact AS fleet_contact,
                            accounts.fleet_email AS fleet_email,
                            accounts.compliance_contact AS compliance_contact,
                            accounts.compliance_email AS compliance_email,
                            accounts.account_id AS team_id,
                            accounts.account_type AS team_type,
                            accounts.compliance_email AS compliance_email,
                            accounts.custom_count,
                            packages.name AS packagename,
                            packages.item_limit AS package_item_limit,
                               accounts.color AS accountcompliancecolor,
                accounts.filename AS accountcompliancefilename
                            ';
            $this->db->select($strSelectFields);
            // need to start by selecting from accounts
            $this->db->from('accounts');
            // join onto users
            $this->db->join('users', 'accounts.id = users.account_id', 'left');
            // joining on levels
            $this->db->join('levels', 'users.level_id = levels.id', 'left');
            // joining on photos
            $this->db->join('photos', 'users.photo_id = photos.id', 'left');
            // join onto users
            $this->db->join('packages', 'accounts.package_id = packages.id', 'left');
            // do the Query
            $this->db->join('owner', 'users.id = owner.is_user', 'left');
            $this->db->where('users.id', $intId);
            $this->db->limit(1);

            $resQuery = $this->db->get();
            // Make sure we capture the SQL for debugging
            $arrResult = array('query' => $this->db->last_query());

            if ($resQuery->num_rows == 1) {
                $arrResult['result'] = $resQuery->result();
                $arrResult['booSuccess'] = true;
            }
        }
        return $arrResult;
    }

    public function setThisOne($intId = -1, $arrInput = array()) {
        if ($intId > 0) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('users', $arrInput);
        } else {
            return false;
        }
    }

    public function setPhoto($intId = -1, $intPhotoId = -1) {
        if (($intId > 0) && ($intPhotoId > 0)) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('users', array('photo_id' => $intPhotoId));
        }
        return false;
    }

    public function hasPermission($intId = -1, $strPermissionName = "") {
        if (($intId > 0) && $strPermissionName != "") {
            $this->db->from('users');
            // joining on levels
            $this->db->join('levels', 'users.level_id = levels.id', 'left');
            // joining on linktable
            $this->db->join('levels_permissions_link', 'levels.id = levels_permissions_link.level_id', 'left');
            // joining on permissions
            $this->db->join('permissions', 'levels_permissions_link.permission_id = permissions.id', 'left');

            $this->db->where('users.id', (int) $intId);
            $this->db->where('permissions.name', $strPermissionName);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows == 1) {
                return true;
            }
        }
        return false;
    }

    public function getAllForAppPullDown($intAccountId = -1, $booActiveOnly = true) {
        // Run the query
        $this->db->select('users.id AS userid, CONCAT(users.firstname," ",users.lastname) AS username', false);
        $this->db->from('users');
        // join onto accounts
        $this->db->join('accounts', 'accounts.id = users.account_id', 'left');
        $this->db->where('users.account_id', (int) $intAccountId);
        if ($booActiveOnly) {
            $this->db->where('users.active', 1);
        }

        $this->db->order_by('users.lastname', 'ASC');
        $this->db->order_by('users.firstname', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrUsers = array();
            // If there are levels, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrUsers[] = $arrRow;
            }
            $arrResult['results'] = $arrUsers;
        }

        return $arrResult;
    }

    public function getAllForPullDown($intAccountId = -1, $booActiveOnly = true) {
        $this->db->select('users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname, users.username AS userusername');
        $this->db->from('users');
        // join onto accounts
        $this->db->join('accounts', 'accounts.id = users.account_id', 'left');
        $this->db->where('users.account_id', (int) $intAccountId);
        if ($booActiveOnly) {
            $this->db->where('users.active', 1);
        }

        $this->db->order_by('userlastname', 'ASC');
        $this->db->order_by('userfirstname', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrUsers = array();
            // If there are levels, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrUsers[] = $arrRow;
            }
            $arrResult['results'] = $arrUsers;
        }

        return $arrResult;
    }

    public function getAllForOwner($intAccountId = -1, $booActiveOnly = true) {
        $this->db->select('owner.id AS ownerid, owner.owner_name');
        $this->db->from('owner');
        // join onto accounts
        $this->db->join('accounts', 'accounts.id = owner.account_id', 'left');
        $this->db->where('owner.account_id', (int) $intAccountId);
        if ($booActiveOnly) {
            $this->db->where('owner.active', 1);
        }

        $this->db->order_by('owner_name', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrUsers = array();
            // If there are levels, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrUsers[] = $arrRow;
            }
            $arrResult['results'] = $arrUsers;
        }

        return $arrResult;
    }

    public function getAllForAccount($intAccountId = -1) {
        // Build the query
        if ($intAccountId > 0) {
            $strSelectFields = 'users.id AS userid,
				users.username AS username,
				users.firstname AS firstname,
				users.lastname AS lastname,
				users.nickname AS nickname,
				users.active As active,
				levels.name AS levelname,
				levels.id AS levelid,
				photos.id AS photoid,
				photos.title AS phototitle,
				photos.path AS photopath,
                                photos.file_name AS photofilename,
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


            $this->db->where('users.account_id', $intAccountId);
            $this->db->order_by('active', 'DESC');
            $this->db->order_by('lastname', 'ASC');
            $this->db->order_by('firstname', 'ASC');

            // Do it
            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrUsers = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrUsers[] = $arrRow;
                }
                $arrResult['results'] = $arrUsers;
            }

            return $arrResult;
        } else {
            return false;
        }

        return $arrResult;
    }

    public function getAll($intAccountId = -1) {
        // Build the query

        $strSelectFields = 'users.id AS userid,
			    users.username AS username,
			    users.firstname AS firstname,
			    users.lastname AS lastname,
			    users.nickname AS nickname,
			    users.active As active,
			    levels.name AS levelname,
			    levels.id AS levelid,
			    photos.id AS photoid,
			    photos.title AS phototitle,
			    photos.path AS photouri,
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

        if ($intAccountId > 0) {
            $this->db->where('users.account_id', $intAccountId);
        }

        // Do it
        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrUsers = array();
            // If there are users, then load 

            foreach ($resQuery->result() as $objRow) {

                $arrUsers[$objRow->accountid]['accountname'] = $objRow->accountname;
                $arrUsers[$objRow->accountid]['levels'][$objRow->levelid]['levelname'] = $objRow->levelname;
                $arrUsers[$objRow->accountid]['levels'][$objRow->levelid]['users'][] = $objRow;
            }

            $arrResult['results'] = $arrUsers;
            $arrResult['booSuccess'] = true;
        } else {

            // If we didn't find rows,
            // then return false
            $arrResult['results'] = array();
            $arrResult['booSuccess'] = false;
        }

        return $arrResult;
    }

    public function getOne($intId = -1, $intAccountId = -1) {

        // Run the query
        $this->db->select('users.id AS userid,
			    users.username AS username,
			    users.firstname AS firstname,
			    users.lastname AS lastname,
			    users.nickname AS nickname,
                            users.request_super_admin AS request_super_admin,
			    levels.name AS levelname,
			    levels.id AS levelid,
			    photos.id AS photoid,
				photos.title AS phototitle,
				photos.path AS photopath,
                                photos.file_name AS photofilename,
			    accounts.id AS accountid,
			    accounts.name AS accountname');
        $this->db->from('users');
        $this->db->join('levels', 'users.level_id = levels.id', 'left');
        $this->db->join('accounts', 'users.account_id = accounts.id', 'left');
        $this->db->join('photos', 'users.photo_id = photos.id', 'left');
        $this->db->where('users.id', $intId);
        $this->db->where('users.account_id', $intAccountId);

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

    public function getOneWithoutAccount($intId = -1) {
        // Run the query
        $this->db->select('users.id AS userid,
			    users.username AS username,
			    users.firstname AS firstname,
			    users.lastname AS lastname,
			    users.nickname AS nickname,
                            users.request_super_admin AS request_super_admin,
			    levels.name AS levelname,
			    levels.id AS levelid,
			    photos.id AS photoid,
				photos.title AS phototitle,
				photos.path AS photopath,
                                photos.file_name AS photofilename,
			    accounts.id AS accountid,
			    accounts.name AS accountname');
        $this->db->from('users');
        $this->db->join('levels', 'users.level_id = levels.id', 'left');
        $this->db->join('accounts', 'users.account_id = accounts.id', 'left');
        $this->db->join('photos', 'users.photo_id = photos.id', 'left');
        $this->db->where('users.id', $intId);


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

    public function createOnAccount($intAccount = -1, $arrInput) {
        if ($intAccount > 0) {
            $arrInput['account_id'] = $intAccount;
            $this->db->insert('users', $arrInput);
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function setOne($intId = -1) {
        $arrInput = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'level_id' => (int) $this->input->post('level_id'),
            'photo_id' => (int) $this->input->post('photo_id'),
            'account_id' => (int) $this->input->post('account_id')
        );
        if ($this->input->post('password') !== '') {
            $arrInput['password'] = $this->input->post('password');
        }

        if ($this->input->post('nickname') !== '') {
            $arrInput['nickname'] = $this->input->post('nickname');
        } else {
            $arrInput['nickname'] = $this->input->post('firstname');
        }

        if ($intId > 0) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('users', $arrInput);
        } else {
            $arrInput['username'] = $this->input->post('username');
            return $this->db->insert('users', $arrInput);
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
            return $this->db->update('users', $arrInput);
        } else {
            return false;
        }
    }

    public function delete($intId = -1) {
        if (($intId > 0) && ($this->doCheckUserHasNoActiveItems($intId))) {
            $arrInput = array('active' => 0);
            $this->db->where('id', (int) $intId);
            return $this->db->update('users', $arrInput);
        }
        return false;
    }

    public function doCheckUserHasNoActiveItems($intUserId = -1) {
        if ($intUserId > 0) {
            $this->db->select('items.owner_now');
            // we need to do a sub query, this

            $this->db->from('items');
            $this->db->where('items.active', 1);
            $this->db->where('items.owner_now', $intUserId);
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function reactivate($intId = -1) {
        if ($intId > 0) {
            $arrInput = array('active' => 1, 'archive' => 1);
            $this->db->where('id', (int) $intId);
            return $this->db->update('users', $arrInput);
        }
        return false;
    }

    public function removeSuperAdminFromAccount($intAccountId) {
        $arrInput = array('level_id' => 3);
        $this->db->where('account_id', (int) $intAccountId);
        $this->db->where('level_id', 4);
        $this->db->update('users', $arrInput);
    }

    public function createSuperAdminOnAccount($intUserId, $intAccountId) {
        $arrInput = array('level_id' => 4);
        $this->db->where('account_id', (int) $intAccountId);
        $this->db->where('level_id', 3);
        $this->db->where('id', (int) $intUserId);
        $this->db->update('users', $arrInput);
    }

    public function makeSuperAdmin($intUserId, $intAccountId) {
        if (($intUserId > 0) && ($intAccountId > 0)) {
            $arrInput = array('request_super_admin' => 0);
            $this->db->where('id', (int) $intUserId);
            $this->db->update('users', $arrInput);

            $this->removeSuperAdminFromAccount($intAccountId);
            $this->createSuperAdminOnAccount($intUserId, $intAccountId);
            return true;
        }
        return false;
    }

    public function requestSuperAdmin($intUserId) {
        if ($intUserId > 0) {
            $arrInput = array('request_super_admin' => 1);
            $this->db->where('id', (int) $intUserId);
            return $this->db->update('users', $arrInput);
        }
        return false;
    }

    public function getSuperAdminRequestFor($intAccountId) {
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
        $this->db->where('users.account_id', $intAccountId);

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

    public function getTotalNumberOfUsers($intAccountId) {
        $this->db->select("COUNT(*) AS total_users");
        $this->db->from('users');
        $this->db->where('users.account_id', $intAccountId);
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow) {
                $arrItemsData[] = $objRow;
            }
            return $arrItemsData;
        }
    }

    public function search($str = '', $account_id) {
        if ($str != '') {
            $str = preg_replace('/[^(\x20-\x7F)]*/', '', $str);
            $query = $this->db->query("SELECT * FROM users WHERE concat(firstname, ' ', lastname) LIKE '%" . $str . "%'");
//             print_r($this->db->last_query());
            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                return $result['id'];
            } else {
                return false;
            }
        }
    }

    public function getColumns($userid) {
        $this->load->model('customfields_model');
        $this->db->select('columns');
        $query = $this->db->get_where('users', array('id' => $userid));
        $data = $query->row();
        $raw_columns = $data->columns;
        //  var_dump($raw_columns);die;

        if (!$raw_columns) {
            /* if user has no columns defined, set json object manually TODO: Rework this, its pretty ugly */
            $raw_columns = '["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21"]';
        }
        $count = 22;
        foreach (json_decode($raw_columns) as $value) {
            /* Check if custom field. If it is, get custom field data, if not, grab column data */
            if (strpos($value, 'custom') !== false) {
                $explode_custom = explode('custom_', $value);
                $custom = $this->customfields_model->getField($explode_custom[1]);
                $custom->id = 'custom_' . $custom->id . '_' . $count;
                $custom->name = $custom->field_name;
                $custom->input_name = $custom->field_name;
                unset($custom->field_name, $custom->account_id);
                $columns[] = array(0 => $custom);
                $count++;
            } else {

                $query = $this->db->get_where('columns', array('id' => $value));
                if ($query->num_rows > 0) {
                    $columns[] = $query->result();
                }
            }
        }
        //  print_r($columns);die;
        return $columns;
    }

    public function getColumnsFilter($userid) {
        $this->load->model('customfields_model');
        $this->db->select('columns');
        $query = $this->db->get_where('users', array('id' => $userid));
        $data = $query->row();
        $raw_columns = $data->columns;
//        var_dump($data);die;

        if (!$raw_columns) {
            /* if user has no columns defined, set json object manually TODO: Rework this, its pretty ugly */
            $raw_columns = '["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21"]';
        }
        $count = 22;
        foreach (json_decode($raw_columns) as $value) {
            /* Check if custom field. If it is, get custom field data, if not, grab column data */
            if (strpos($value, 'custom') !== false) {
                $explode_custom = explode('custom_', $value);
                $custom = $this->customfields_model->getField($explode_custom[1]);
                $custom->id = 'custom_' . $custom->id . '_' . $count;
                $custom->name = $custom->field_name;
                $custom->input_name = $custom->field_name;
                unset($custom->field_name, $custom->account_id);
                $columns[] = array(0 => $custom);
                $count++;
            } else {

                $query = $this->db->get_where('columns', array('id' => $value));
                if ($query->num_rows > 0) {
                    $columns[] = $query->result();
                }
            }
        }

        return $columns;
    }

    public function saveColumns($columns) {
        $this->db->where('id', $this->session->userdata('objSystemUser')->userid);
        $this->db->update('users', array('columns' => $columns));
        return true;
    }

    public function getAllColumns() {
        $this->load->model('customfields_model');
        $query = $this->db->get('columns');
        $columns = $query->result();
        /* Get all custom fields to add to the column selection */
        $custom_fields = $this->customfields_model->getAll();
        $count = 22;
        foreach ($custom_fields as $field) {
            $field->name = $field->field_name;
            $field->input_name = $field->field_name;
            $field->id = 'custom_' . $field->id . '_' . $count;
            unset($field->field_name, $field->account_id);

            $columns[] = $field;
            $count++;
        }

        return $columns;
    }

    public function checkUserName($username) {
        $this->db->select("*");
        $this->db->from('users');
        $this->db->where('username', $username);
        $resQuery = $this->db->get();

        return ($resQuery->num_rows());
    }
    
    public function get_itemlist($intAccountId)
    {
        if (($intAccountId > 0)) {
            $this->db->select('
                        items.id AS itemid,
                        items.manufacturer,
                        items.item_manu,
                        items.model, 
                        items.serial_number, 
                        items.condition_now,
                        item_condition.condition AS condition_name,
                        items.barcode, 
                        items.photo_id,
                        items.owner_now, 
                        items.owner_since, 
                        items.site AS siteid,
                        items.location_now, 
                        items.location_since,
                        items.value,
                        items.current_value, 
                        items.notes,
                        items.status_id,
                        items.warranty_date,
                        items.purchase_date, 
                        items.replace_date,
                        items.added_date,
                        items.pattest_date, 
                        pat.pattest_name AS pat_status,
                        items.pattest_status,
                        items.mark_deleted, 
                        items.mark_deleted_2, 
                        items.mark_deleted_date,
                        items.mark_deleted_2_date, 
                        items.active,
                        items.deleted_date,
                        items.compliance_start,
                        items.quantity,
                        items.pdf_name,
                        photos.id AS itemphotoid,
                        photos.title AS itemphototitle,
                        photos.path AS photopath,
                        itemstatus.id AS itemstatusid,
                        itemstatus.name AS itemstatusname,
                        categories.id AS categoryid, 
                        categories.name AS categoryname, 
                        categories.default AS categorydefault, 
                        categories.icon AS categoryicon, 
                        categories.support_emails AS support_emails, 
                        categories.quantity_enabled,
                        users.id AS userid,
                        users.firstname AS userfirstname,
                        users.lastname AS userlastname, 
                        users.nickname AS usernickname,
                        locations.id AS locationid,
                        locations.name AS locationname,
                        sites.id AS siteid,
                        sites.name AS sitename,
                        items.supplier,
                        suppliers.supplier_title AS suppliers_title');
            $this->db->from('items');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('photos', 'photos.id = items.photo_id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('pat', 'items.pattest_status = pat.id', 'left');

            $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
            $this->db->where('items.active', 1);
            $this->db->where('items.account_id', $intAccountId);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            }
        }
    }

}

?>