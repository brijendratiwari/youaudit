<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Barry Crosby
 * Description: Accounts model class
 */

class Accounts_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function update($intAccountId = -1, $arrInput = array()) {
        if ($intAccountId > 0) {
            $this->db->where('id', (int) $intAccountId);
            return $this->db->update('accounts', $arrInput);
        } else {
            return false;
        }
    }

    public function setOne($intId = -1) {
        $this->load->model('master_model');
        $this->load->model('franchise_model');
        $this->load->model('admin_section_model');
        $this->load->model('categories_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;

        $arrInput = array(
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
            'active' => 1,
            'archive' => 1
        );
        $package = $this->getOnePackage($this->input->post('package_type'));
        if (array_key_exists('master_account_id', $arrPageData['arrSessionData']['objAdminUser'])) {
            $arrInput['account_id'] = $arrPageData['arrSessionData']['objAdminUser']->master_account_id;
            $arrInput['account_type'] = 1;
            $system_admin_name = $this->master_model->getSysAdminName($arrInput['account_id']);

            $mail_content = array(
                'sys_admin_name' => $system_admin_name[0]['sys_admin_name'],
                'account_type' => 'Master Account',
                'customer_name' => $this->input->post('company_name'),
                'package_type' => $package,
                'date_added' => date("Y-m-d H:i:s"),
            );
        } else {
            $arrInput['account_id'] = $arrPageData['arrSessionData']['objAdminUser']->franchise_account_id;
            $arrInput['account_type'] = 2;
            $system_admin_name = $this->franchise_model->getSysAdminName($arrInput['account_id']);

            $mail_content = array(
                'sys_admin_name' => $system_admin_name[0]['sys_franchise_name'],
                'account_type' => 'Franchises Account',
                'customer_name' => $this->input->post('company_name'),
                'package_type' => $package,
                'date_added' => date("Y-m-d H:i:s"),
            );
        }
        if ($intId > 0) {

            $this->db->where('id', (int) $intId);
            return $this->db->update('accounts', $arrInput);
        } else {
            $booSuccess = true;
            if ($this->db->insert('accounts', $arrInput)) {
                $intAccountId = $this->db->insert_id();
                $userid = $this->db->insert_id();
                if ($userid) {
                    if ($arrInput['add_owner'] != 0) {
                        $newOwner = array('owner_name' => $arrInput['firstname'] . ' ' . $arrInput['lastname'],
                            'account_id' => $intAccountId, 'active' => 1, 'archive' => 1, 'is_user' => $userid);
                        $this->db->insert('owner', $newOwner);
                    }
                }
                $this->sendMailConfirmation($mail_content);

                $profiles = $this->db->where('profile_id', $this->input->post('profile'))->get('profile')->result();

                if ($profiles[0]->custom_field) {
                    $fields = json_decode($profiles[0]->custom_field);
                    for ($i = 0; $i < count($fields->name); $i++) {
                        $custom_data = array('field_name' => $fields->name[$i], 'account_id' => $intAccountId, 'field_value' => $fields->type[$i], 'pick_values' => $fields->values[$i], 'profile' => 1);
                        $this->db->insert('custom_fields', $custom_data);
                        $id = $this->db->insert_id();
                        $ids[] = $id;
                    }
                }

                if ($profiles[0]->owner) {
                    $owners = json_decode($profiles[0]->owner);
                    $owners = array_filter($owners);
                    foreach ($owners as $owner) {
                        if ($this->admin_section_model->checkowner($owner, $intAccountId) == 0) {
                            $owner_data = array('owner_name' => $owner, 'account_id' => $intAccountId, 'active' => 1);
                            $this->db->insert('owner', $owner_data);
                        }
                    }
                }
                if ($profiles[0]->category) {
                    $categories = json_decode($profiles[0]->category);
                    $categories = array_filter($categories);
                    foreach ($categories as $category) {
                        if ($this->categories_model->doCheckCategoryNameIsUniqueOnAccount($category, $intAccountId)) {
                            $category_data = array('name' => $category, 'account_id' => $intAccountId, 'active' => 1);
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
                        if ($this->admin_section_model->checkitem($manu, $intAccountId) == 0) {
                            $manu_data = array('item_manu_name' => $manu, 'account_id' => $intAccountId);
                            $this->db->insert('item_manu', $manu_data);
                        }
                    }
                }

                if ($profiles[0]->manufacturer) {
                    $manufacturers = json_decode($profiles[0]->manufacturer);
                    $manufacturers = array_filter($manufacturers);
                    foreach ($manufacturers as $manufacturer) {
                        if ($this->admin_section_model->checkmanufacturer($manufacturer, $intAccountId) == 0) {
                            $manufacturer_data = array('manufacturer_name' => $manufacturer, 'account_id' => $intAccountId);
                            $this->db->insert('manufacturer_list', $manufacturer_data);
                        }
                    }
                }

                $arrUserName = explode(' ', $this->input->post('account_contactname'));
                $arrUserData = array('firstname' => $arrInput['firstname'],
                    'lastname' => $arrInput['lastname'],
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('contact_password'),
                    'nickname' => $this->input->post('contact_name'),
                    'level_id' => 4,
                    'account_id' => $intAccountId,
                    'active' => 1,
                );

                $this->injectDefaultCats($intAccountId);
                if ($this->setFirstUser($arrUserData)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

// send mail when customer is created
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
            
        } else {
            
        }
    }

    public function setFirstUser($arrInput = array()) {

        return $this->db->insert('users', $arrInput);
    }

//    public function getAll() {
//        // Run the query
//       
//        $this->db->select('accounts.id AS accountid,
//			    accounts.name AS accountname,
//			    accounts.city AS accountcity,
//			    accounts.package_id AS accountpackageid,
//			    accounts.active AS accountactive');
//        $this->db->from('accounts');
//        $this->db->where('accounts.test_account', 0);
//        if ($booActiveOnly) {
//            $this->db->where('accounts.active', 1);
//        }
//
//        $this->db->order_by('accountname', 'ASC');
//
//        $resQuery = $this->db->get();
//        $arrResult = array('query' => $this->db->last_query());
//
//        // Let's check if there are any results
//        if ($resQuery->num_rows != 0) {
//            $arrAccounts = array();
//            // If there are users, then load 
//            foreach ($resQuery->result() as $arrRow) {
//                $arrAccounts[] = $arrRow;
//            }
//            $arrResult['results'] = $arrAccounts;
//            $arrResult['booSuccess'] = true;
//        } else {
//            $arrResult['results'] = array();
//            $arrResult['booSuccess'] = false;
//        }
//        // If the previous process did not validate
//        // then return false.
//        return $arrResult;
//    }

    public function getAllAccountForMaster($arrSessionData = array()) {
        // Run the query

        $this->db->select('*,packages.name As package_name,item_limit,accounts.name As company_name,accounts.id As customer_id');
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->where('accounts.test_account', 0);
        $this->db->where('accounts.account_id', $arrSessionData->master_account_id);
        $this->db->where('accounts.account_type', 1);
        $this->db->where('accounts.archive', 1);
        if ($booActiveOnly) {
            $this->db->where('accounts.active', 1);
        }

        $this->db->order_by('accounts.name', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrAccounts = array();
            // If there are users, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrAccounts[] = $arrRow;
            }
            $arrResult['results'] = $arrAccounts;
            $arrResult['booSuccess'] = true;
        } else {
            $arrResult['results'] = array();
            $arrResult['booSuccess'] = false;
        }
        // If the previous process did not validate
        // then return false.

        return $arrResult;
    }

    public function getAllAccountForFranchise($arrSessionData = array()) {
        // Run the query

        $this->db->select('*,packages.name As package_name,accounts.name As company_name,accounts.id As customer_id');
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->where('accounts.test_account', 0);
        $this->db->where('accounts.account_id', $arrSessionData->franchise_account_id);
        $this->db->where('accounts.account_type', 2);
        $this->db->where('accounts.archive', 1);
        if ($booActiveOnly) {
            $this->db->where('accounts.active', 1);
        }

        $this->db->order_by('accounts.name', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrAccounts = array();
            // If there are users, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrAccounts[] = $arrRow;
            }
            $arrResult['results'] = $arrAccounts;
            $arrResult['booSuccess'] = true;
        } else {
            $arrResult['results'] = array();
            $arrResult['booSuccess'] = false;
        }
        // If the previous process did not validate
        // then return false.

        return $arrResult;
    }

    public function getOne($intId = -1) {
        // Run the query
        $this->db->select('accounts.id AS accountid,
			    accounts.name AS accountname,
			    accounts.address AS accountaddress,
			    accounts.city AS accountcity,
			    accounts.state AS accountstate,
			    accounts.postcode AS accountpostcode,
			    accounts.country AS accountcountry,
                            accounts.qr_refcode AS qr_refcode,
			    accounts.security_question AS accountsecurityquestion,
			    accounts.security_answer AS accountsecurityanswer,
			    accounts.contact_name AS accountcontactname,
			    accounts.contact_email AS accountcontactemail,
			    accounts.contact_number AS accountcontactnumber,
			    accounts.package_id AS accountpackageid,
			    packages.name AS accountpackagename,
			    accounts.active AS accountactive,
                accounts.fleet AS accountfleet,
                accounts.compliance AS accountcompliance,
                accounts.condition_module AS accountcondition,
                accounts.depereciation_module AS accountdepreciation,
                accounts.reporting_module AS accountreporting,
			    accounts.verified AS accountverified,
                accounts.currency AS currency,
                accounts.support_email AS accountsupportemail,
                accounts.fleet_contact AS accountfleetcontact,
                accounts.fleet_email AS accountfleetemail,
                accounts.compliance_contact AS accountcompliancecontact,
                accounts.compliance_email AS accountcomplianceemail,
                accounts.color AS accountcompliancecolor,
                accounts.filename AS accountcompliancefilename
                ');
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id = packages.id', 'left');
        $this->db->where('accounts.id', $intId);

        $resQuery = $this->db->get();
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

    public function deleteOne($intId = -1) {
        if ($intId > 0) {
            $arrInput = array('active' => 0);
            $this->db->where('id', (int) $intId);
            return $this->db->update('accounts', $arrInput);
        }
        return false;
    }

    public function reactivateOne($intId = -1) {
        if ($intId > 0) {
            $arrInput = array('active' => 1);
            $this->db->where('id', (int) $intId);
            return $this->db->update('accounts', $arrInput);
        }
        return false;
    }

    public function getSupportEmailAddress($intId) {
        $this->db->select('accounts.support_email AS support_email');
        $this->db->from('accounts');
        $this->db->where('id', (int) $intId);
        $resQuery = $this->db->get();
        if ($resQuery->num_rows == 1) {
            return $resQuery->row()->support_email;
        } else {
            return false;
        }
    }

    public function accountLimit($accountID) {
        
    }

    private function injectDefaultCats($accountID) {

        /* Select Default Categories from DB */
        $query = $this->db->get('default_categories');
        foreach ($query->result_array() as $row) {
            $this->db->set('account_id', $accountID);
            $this->db->set('active', '1');
            $this->db->set('default', '1');
            $this->db->set('name', $row['name']);
            $this->db->set('icon', $row['file_url']);

            $this->db->insert('categories');
        }
    }

    public function getCurrencySym($strCurrency) {
        if ($strCurrency == 'GBP') {
            return "&pound;";
        } elseif ($strCurrency == 'EUR') {
            return "&euro;";
        } elseif ($strCurrency == 'AUD') {
            return "&#36;";
        } else {
            return false;
        }
    }

    // get package name  
    public function getOnePackage($package_id) {
        $this->db->select('name');
        $this->db->where('id', $package_id);
        $package = $this->db->get('packages')->row();
        return $package->name;
    }

    // function for archive master acc
    public function getAllAccountForArchiveMaster($arrSessionData = array()) {
        // Run the query

        $this->db->select('*,packages.name As package_name,item_limit,accounts.name As company_name,accounts.id As customer_id');
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->where('accounts.test_account', 0);
        $this->db->where('accounts.account_id', $arrSessionData->master_account_id);
        $this->db->where('accounts.account_type', 1);
        $this->db->where('accounts.archive', 0);
        if ($booActiveOnly) {
            $this->db->where('accounts.active', 0);
        }

        $this->db->order_by('accounts.name', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrAccounts = array();
            // If there are users, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrAccounts[] = $arrRow;
            }
            $arrResult['results'] = $arrAccounts;
            $arrResult['booSuccess'] = true;
        } else {
            $arrResult['results'] = array();
            $arrResult['booSuccess'] = false;
        }
        // If the previous process did not validate
        // then return false.

        return $arrResult;
    }

    // function for archive franchise acc

    public function getAllAccountForArchiveFranchise($arrSessionData = array()) {
        // Run the query

        $this->db->select('*,packages.name As package_name,accounts.name As company_name,accounts.id As customer_id');
        $this->db->from('accounts');
        $this->db->join('packages', 'accounts.package_id=packages.id');
        $this->db->where('accounts.test_account', 0);
        $this->db->where('accounts.account_id', $arrSessionData->franchise_account_id);
        $this->db->where('accounts.account_type', 2);
        $this->db->where('accounts.archive', 0);
        if ($booActiveOnly) {
            $this->db->where('accounts.active', 0);
        }

        $this->db->order_by('accounts.name', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrAccounts = array();
            // If there are users, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrAccounts[] = $arrRow;
            }
            $arrResult['results'] = $arrAccounts;
            $arrResult['booSuccess'] = true;
        } else {
            $arrResult['results'] = array();
            $arrResult['booSuccess'] = false;
        }
        // If the previous process did not validate
        // then return false.

        return $arrResult;
    }

}

?>