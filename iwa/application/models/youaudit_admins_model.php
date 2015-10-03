<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Youaudit_Admins_Model extends CI_Model {

    // Check Username In Database For Master Acc.
    public function checkUsername($username) {
        $this->db->select('username');
        $this->db->where('username', $username);
        $res_master = $this->db->get('master_ac');
        $this->db->select('username');
        $this->db->where('username', $username);
        $res_franchise = $this->db->get('franchise_ac');
        if ($res_master->num_rows() > 0 || $res_franchise->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    // Check Sys Admain Name In Database For Master Acc.
    public function checkSysAdminName($sys_admin_name) {

        $this->db->select('sys_admin_name');
        $this->db->where('sys_admin_name', $sys_admin_name);
        $res = $this->db->get('master_ac');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    // Check Sys Admain Name In Database For Franchise Acc.

    public function checkSysFranchiseName($sys_name) {
        $this->db->select('sys_franchise_name');
        $this->db->where('sys_franchise_name', $sys_name);
        $res = $this->db->get('franchise_ac');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    // Check Username In Database for Franchise Acc.

    public function checkUsernameFranchise($username) {
        $this->db->select('username');
        $this->db->where('username', $username);
        $res_master = $this->db->get('master_ac');
        $this->db->select('username');
        $this->db->where('username', $username);
        $res_franchise = $this->db->get('franchise_ac');
        if ($res_master->num_rows() > 0 || $res_franchise->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    // Youaudit User Login    
    public function logIn($arrInput) {

        $resQuery = $this->db->get_where('systemadmins', $arrInput);

        // Make sure we capture the SQL for debugging
        $arrResult = array('query' => $this->db->last_query());

        // Let's check if there are any results
        if ($resQuery->num_rows == 1) {
            $arrResult['result'] = $resQuery->result_array();
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

    //check Pin Number
    public function pincodeAuthentication($arrInput) {
        $this->db->select('*');
        $this->db->where('username', $arrInput['username']);
        $this->db->where('pin_number', $arrInput['pin_number']);
        $res = $this->db->get('systemadmins');
        $arrResult['result'] = $res->result_array();

        if ($res->num_rows() > 0) {
            $arrResult['result'] = $res->result_array();
            $arrResult['success'] = TRUE;
            return $arrResult;
        } else {
            return False;
        }
    }

    // Add Master Acc.

    public function addMasterAccount($arrMasterAcc) {

        if (isset($arrMasterAcc)) {

            $add_master = array(
                'sys_admin_name' => $arrMasterAcc['sys_admin_name'],
                'company_name' => $arrMasterAcc['company_name'],
                'contact_name' => $arrMasterAcc['contact_name'],
                'email' => $arrMasterAcc['email'],
                'phone' => $arrMasterAcc['phone'],
                'username' => $arrMasterAcc['username'],
                'password' => $arrMasterAcc['password'],
                'pin_number' => $arrMasterAcc['pin_number'],
                'account_limit' => $arrMasterAcc['account_limit'],
                'active' => 1
            );


            $this->db->insert('master_ac', $add_master);
            $id = $this->db->insert_id();
            if ($id) {
                $data = array(
                    'firstname' => $arrMasterAcc['firstname'],
                    'lastname' => $arrMasterAcc['lastname'],
                    'username' => $arrMasterAcc['username'],
                    'nickname' => $arrMasterAcc['contact_name'],
                    'password' => $arrMasterAcc['password'],
                    'pin_number' => $arrMasterAcc['pin_number'],
                    'master_account_id' => $id,
                    'active' => 1
                );

                $this->db->insert('systemadmin_master', $data);
            }
            return 1;
        } else {
            return FALSE;
        }
    }

    // Edit Master Acc
    public function editMasterAccount($editMasterAcc) {

        if (isset($editMasterAcc)) {

            $data = array(
                'company_name' => $editMasterAcc['company_name'],
                'contact_name' => $editMasterAcc['contact_name'],
                'email' => $editMasterAcc['email'],
                'phone' => $editMasterAcc['phone'],
                'account_limit' => $editMasterAcc['account_limit'],
                'enable_report' => $editMasterAcc['enable_report']
            );

            $this->db->where('id', $editMasterAcc['master_id']);
            $this->db->update('master_ac', $data);
            return 1;
        } else {
            return False;
        }
    }

    //Add Franchies Acc.
    public function addFranchiseAccount($arrFranchiseAcc) {

        $add_master = array(
            'sys_franchise_name' => $arrFranchiseAcc['sys_franchise_name'],
            'company_name' => $arrFranchiseAcc['company_name'],
            'contact_name' => $arrFranchiseAcc['contact_name'],
            'email' => $arrFranchiseAcc['email'],
            'phone' => $arrFranchiseAcc['phone'],
            'username' => $arrFranchiseAcc['username'],
            'password' => $arrFranchiseAcc['password'],
            'pin_number' => $arrFranchiseAcc['pin_number'],
            'account_limit' => $arrFranchiseAcc['account_limit'],
            'active' => 1
        );

        if (isset($arrFranchiseAcc)) {

            $this->db->insert('franchise_ac', $add_master);
            $id = $this->db->insert_id();
            if ($id) {
                $data = array(
                    'firstname' => $arrFranchiseAcc['firstname'],
                    'lastname' => $arrFranchiseAcc['lastname'],
                    'username' => $arrFranchiseAcc['username'],
                    'nickname' => $arrFranchiseAcc['contact_name'],
                    'password' => $arrFranchiseAcc['password'],
                    'pin_number' => $arrFranchiseAcc['pin_number'],
                    'franchise_account_id' => $id,
                    'active' => 1
                );
                $this->db->insert('systemadmin_franchise', $data);
            }
            return 1;
        } else {
            return FALSE;
        }
    }

    // Add News
    public function setNews($news) {
        if (isset($news)) {
            $this->db->insert('latest_news', $news);
            return true;
        } else {
            return false;
        }
    }

    // Get News
    public function getLastNews() {
        $this->db->select('news_text,create_date');
        $this->db->order_by("id", "desc");
        $this->db->limit(1);
        $result = $this->db->get('latest_news');
        $news = $result->result_array();
        return $news;
    }

    // Add System Account
    public function addSystemAccount($arrSysetmAc) {
        if (isset($arrSysetmAc)) {
            $this->db->insert('systemadmins', $arrSysetmAc);
            return true;
        } else {
            return false;
        }
    }

    //Edit System Acc
    public function editSystemAccount($arrEditSysetmAc) {
        if (isset($arrEditSysetmAc)) {
            $data = array(
                'firstname' => $arrEditSysetmAc['firstname'],
                'lastname' => $arrEditSysetmAc['lastname'],
            );

            $this->db->where('id', $arrEditSysetmAc['system_id']);
            $this->db->update('systemadmins', $data);
            return true;
        } else {
            return false;
        }
    }

    // Check System Account Username
    public function checkSystemAdminUsername($username) {

        $this->db->select('username');
        $this->db->where('username', $username);
        $res = $this->db->get('systemadmins');

        if ($res->num_rows() > 0) {
            return "1";
        } else {
            return "0";
        }
    }

    public function disableMasterAccount($master_id) {

        if (isset($master_id)) {
            $this->db->where('id', $master_id);
            $this->db->update('master_ac', array('active' => 0));
            $this->db->where('master_account_id', $master_id);
            $this->db->update('systemadmin_master', array('active' => 0));
            return 1;
        } else {
            return FALSE;
        }
    }

    public function enableMasterAccount($master_id) {

        if (isset($master_id)) {
            $this->db->where('id', $master_id);
            $this->db->update('master_ac', array('active' => 1));
            $this->db->where('master_account_id', $master_id);
            $this->db->update('systemadmin_master', array('active' => 1));
            return 1;
        } else {
            return FALSE;
        }
    }

    public function get_edit_masterdata($master_id) {
        if (isset($master_id)) {

            $this->db->select('*');
            $this->db->from('master_ac');
            $this->db->where('id', $master_id);
            $res = $this->db->get();
            $result = $res->result_array();
            return $result[0];
        } else {
            return FALSE;
        }
    }

    public function get_edit_franchisedata($franchise_id) {
        if (isset($franchise_id)) {

            $this->db->select('*');
            $this->db->from('franchise_ac');
            $this->db->where('id', $franchise_id);
            $res = $this->db->get();
            $result = $res->result_array();
            return $result[0];
        } else {
            return FALSE;
        }
    }

    public function changeMasterUserPassword($changePassword) {
        if (isset($changePassword['master_account_id'])) {

            $data = array(
                'password' => $changePassword['new_password'],
                'pin_number' => $changePassword['new_pin_number'],
            );

            foreach ($data as $key => $value) {
                if ($value == '') {
                    unset($data[$key]);
                }
            }

            $this->db->where('id', $changePassword['master_account_id']);
            $this->db->update('master_ac', $data);
            $this->db->where('username', $changePassword['username']);
            $this->db->update('systemadmin_master', $data);
            return True;
        } else {
            return False;
        }
    }

    public function disableFranchiseAccount($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('franchise_ac', array('active' => 0));
            $this->db->where('id', $id);
            $this->db->update('systemadmin_franchise', array('active' => 0));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function enableFranchiseAccount($franchise_id) {

        if (isset($franchise_id)) {
            $this->db->where('id', $franchise_id);
            $this->db->update('franchise_ac', array('active' => 1));
            $this->db->where('id', $franchise_id);
            $this->db->update('systemadmin_franchise', array('active' => 1));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Edit Freanchise Acc
    public function editFranchiseAccount($editFranchiseAcc) {

        if (isset($editFranchiseAcc)) {

            $data = array(
                'company_name' => $editFranchiseAcc['company_name'],
                'contact_name' => $editFranchiseAcc['contact_name'],
                'email' => $editFranchiseAcc['email'],
                'phone' => $editFranchiseAcc['phone'],
                'account_limit' => $editFranchiseAcc['account_limit'],
            );

            $this->db->where('id', $editFranchiseAcc['franchise_id']);
            $this->db->update('franchise_ac', $data);
            return 1;
        } else {
            return False;
        }
    }

    public function changeFranchisePassword($changePassword) {
        if (isset($changePassword['franchise_account_id'])) {

            $data = array(
                'password' => $changePassword['new_password'],
                'pin_number' => $changePassword['new_pin_number'],
            );

            foreach ($data as $key => $value) {
                if ($value == '') {
                    unset($data[$key]);
                }
            }

            $this->db->where('id', $changePassword['franchise_account_id']);
            $this->db->update('franchise_ac', $data);
            $this->db->where('username', $changePassword['username']);
            $this->db->update('systemadmin_franchise', $data);
            return 1;
        } else {
            return False;
        }
    }

    public function disableSystemAccount($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('systemadmins', array('active' => 0));
            return 1;
        } else {
            return FALSE;
        }
    }

    public function enableSystemAccount($franchise_id) {

        if (isset($franchise_id)) {
            $this->db->where('id', $franchise_id);
            $this->db->update('systemadmins', array('active' => 1));
            return 1;
        } else {
            return FALSE;
        }
    }

    public function changeSystemAdminPassword($changePassword) {
        if (isset($changePassword['system_account_id'])) {

            $data = array(
                'password' => $changePassword['new_password'],
                'pin_number' => $changePassword['new_pin_number'],
            );

            foreach ($data as $key => $value) {
                if ($value == '') {
                    unset($data[$key]);
                }
            }

            $this->db->where('id', $changePassword['system_account_id']);
            $this->db->update('systemadmins', $data);
            return 1;
        } else {
            return False;
        }
    }

    public function get_masterdata() {

        $master = array();
        $this->db->select('master_ac.id,master_ac.sys_admin_name');
        $this->db->from('master_ac');
        $this->db->where(array('active' => 1, 'archive' => 1));
        $rs = $this->db->get()->result();

        foreach ($rs as $key => $value) {
            $master[$value->id][] = $value;
            $this->db->select('count(active) as enabled');
            $this->db->where(array('account_id' => $value->id, 'account_type' => 1, 'active' => 1));
            $this->db->group_by('account_id');
            $result = $this->db->get('accounts')->result();
            if (count($result) > 0) {
                $master[$value->id][] = $result[0];
            } else {
                $master[$value->id][] = 0;
            }
            $this->db->select('account_id as master,count(account_id) as total');
            $this->db->where(array('account_id' => $value->id, 'account_type' => 1, 'active' => 1));
            $this->db->group_by('account_id');
            $result = $this->db->get('accounts')->result();
            if (count($result) > 0) {
                $master[$value->id][] = $result[0];
            }
        }
//        var_dump($master);die;
        $masterData = array();

        foreach ($master as $formate_data) {
            if (isset($formate_data[1]->enabled)) {
                $enabled = $formate_data[1]->enabled;
            } else {
                $enabled = 0;
            }
            if (isset($formate_data[2]->total)) {
                $total = $formate_data[2]->total;
            } else {
                $total = 0;
            }
            $disabled = $total - $enabled;

            $masterData[] = array(
                'id' => $formate_data[0]->id,
                'sys_admin_name' => $formate_data[0]->sys_admin_name,
                'type' => 'master',
                'master_account_id' => $formate_data[2]->master,
                'total' => $total,
                'enabled' => $enabled,
                'disabled' => $disabled
            );
        }

        return $masterData;
    }

// get franchise data for dashboard 'system summary' 
    public function get_franchisedata() {
        $franchise = array();
        $this->db->select('franchise_ac.id,franchise_ac.sys_franchise_name');
        $this->db->where(array('active' => 1, 'archive' => 1));
        $this->db->from('franchise_ac');
        $res = $this->db->get()->result();

        foreach ($res as $key => $value) {
            $franchise[$value->id][] = $value;
            $this->db->select('count(active) as fenabled');
            $this->db->where(array('account_id' => $value->id, 'account_type' => 2, 'active' => 1));
            $this->db->group_by('account_id');
            $result = $this->db->get('accounts')->result();
            if (count($result) > 0) {
                $franchise[$value->id][] = $result[0];
            } else {
                $franchise[$value->id][] = 0;
            }
            $this->db->select('account_id as franchise,count(account_id) as ftotal');
            $this->db->where(array('account_id' => $value->id, 'account_type' => 2, 'active' => 1));
            $this->db->group_by('account_id');
            $result = $this->db->get('accounts')->result();
            if (count($result) > 0) {
                $franchise[$value->id][] = $result[0];
            }
        }
        $franchiseData = array();

        foreach ($franchise as $formate_data) {
            if (isset($formate_data[1]->fenabled)) {

                $enabled = $formate_data[1]->fenabled;
            } else {
                $enabled = 0;
            }
            if (isset($formate_data[2]->ftotal)) {
                $total = $formate_data[2]->ftotal;
            } else {
                $total = 0;
            }

            $disabled = $total - $enabled;

            $franchiseData[] = array(
                'id' => $formate_data[0]->id,
                'sys_admin_name' => $formate_data[0]->sys_franchise_name,
                'type' => 'franchise',
                'franchise_account_id' => $formate_data[0]->franchise,
                'total' => $total,
                'enabled' => $enabled,
                'disabled' => $disabled
            );
        }
        return $franchiseData;
    }

    // get master data for dashboard 'recently added accounts'
    public function recent_masteraccounts() {
        $macc = array();
        $this->db->select('accounts.id,master_ac.id,master_ac.sys_admin_name,accounts.name AS company_name,accounts.create_date,packages.name,packages.item_limit');
        $this->db->where('accounts.account_type', 1);
        $this->db->from('master_ac');
        $this->db->join('accounts', 'master_ac.id=accounts.account_id');
        $this->db->join('packages', 'accounts.package_id = packages.id');
        $this->db->order_by('accounts.id desc');
        $result_master = $this->db->get()->result();

        $master_account = array();
        foreach ($result_master as $record) {
            $master_account[] = array(
                'id' => $record->id,
                'sys_admin_name' => $record->sys_admin_name,
                'type' => 'master',
                'company' => $record->company_name,
                'created' => $record->create_date,
                'package' => $record->name . '/' . $record->item_limit
            );
        }
        return $master_account;
    }

    // get franchise data for dashboard 'recently added accounts'
    public function recent_franchiseaccounts() {
        $facc = array();
        $this->db->select('accounts.id,franchise_ac.id,franchise_ac.sys_franchise_name,accounts.name AS company_name,accounts.create_date,packages.name,packages.item_limit');
        $this->db->where('accounts.account_type', 2);

        $this->db->from('franchise_ac');
        $this->db->join('accounts', 'franchise_ac.id=accounts.account_id');
        $this->db->join('packages', 'accounts.package_id=packages.id');

        $this->db->order_by('accounts.id desc');
        $result_franchise = $this->db->get()->result();

        $franchise_account = array();
        foreach ($result_franchise as $record) {

            $franchise_account[] = array(
                'id' => $record->id,
                'sys_admin_name' => $record->sys_franchise_name,
                'type' => 'franchise',
                'company' => $record->company_name,
                'created' => $record->create_date,
                'package' => $record->name . '/' . $record->item_limit
            );
        }
        return $franchise_account;
    }

    public function exportPdfForMaster($export) {

        if ($export != '') {
            $query = "select master_ac.sys_admin_name,master_ac.company_name,master_ac.contact_name,master_ac.email,master_ac.phone,master_ac.username,master_ac.account_limit,master_ac.active,sum(accounts.annual_value) As total_amount 
                  FROM master_ac left join systemadmin_master on master_ac.username=systemadmin_master.username left join accounts on master_ac.id=accounts.account_id where accounts.account_type=1 GROUP BY master_ac.id
HAVING ( COUNT(accounts.annual_value) >= 1)";
            $res = $this->db->query($query);
        }


        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Admin Name', 'strFieldReference' => 'sys_admin_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Company Name', 'strFieldReference' => 'company_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Name', 'strFieldReference' => 'contact_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Email Address', 'strFieldReference' => 'email', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Phone', 'strFieldReference' => 'phone', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Username', 'strFieldReference' => 'username', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Account Limit', 'strFieldReference' => 'account_limit', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Total Value', 'strFieldReference' => 'total_amount', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array());
        }
    }

    // Action For Genrate Franchise PDF/CSV
    public function exportPdfForFranchise($export) {
        if ($export != '') {
            $query = "select franchise_ac.sys_franchise_name,franchise_ac.company_name,franchise_ac.contact_name,franchise_ac.email,franchise_ac.phone,franchise_ac.username,franchise_ac.account_limit,franchise_ac.active,sum(accounts.annual_value) As total_amount 
                  FROM franchise_ac inner join systemadmin_franchise on franchise_ac.username=systemadmin_franchise.username left join accounts on franchise_ac.id=accounts.account_id where accounts.account_type=2 GROUP BY franchise_ac.id
HAVING ( COUNT(accounts.annual_value) >= 1)";
        }
        $res = $this->db->query($query);

        if ($export == 'CSV') {

            $this->load->dbutil();
            $this->load->helper('download');
            force_download(date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($res));
        } elseif ($export == 'PDF') {
            $arrFields = array(
                array('strName' => 'Admin Name', 'strFieldReference' => 'sys_franchise_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Company Name', 'strFieldReference' => 'company_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Name', 'strFieldReference' => 'contact_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Email Address', 'strFieldReference' => 'email', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Phone', 'strFieldReference' => 'phone', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Contact Username', 'strFieldReference' => 'username', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Account Limit', 'strFieldReference' => 'account_limit', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Total Value', 'strFieldReference' => 'total_amount', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $res->result_array());
        }
    }

    // Html Function For PDf
    public function outputPdfFile($strReportName, $arrFields, $arrResults, $booOutputHtml = false) {


        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"includes/css/report.css\" /></head>";

        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>YouAudit Report</h1>";
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
                        case 'pat_result':
                            if ($objItem->{$arrReportField['strFieldReference']} === null) {
                                $strHtml.="-";
                            } else {
                                if ($objItem->{$arrReportField['strFieldReference']} == 1) {
                                    $strHtml.="Pass";
                                } else {
                                    $strHtml.="Fail";
                                }
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


        $strHtml .= "<p>Produced by " . $arrPageData['arrSessionData']['YouAuditSystemAdmin']['firstname'] . " " . $arrPageData['arrSessionData']['YouAuditSystemAdmin']['lastname'] . " (" . $arrPageData['arrSessionData']['YouAuditSystemAdmin']['username'] . ") on " . date('d/m/Y') . "</p>";
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

    // function to add package
    public function addpackage() {
        $arrPageData['arrSessionData'] = $this->session->userdata;

        if ($this->input->post('enable_compliance') == "on") {
            $compliance = 1;
        } else {
            $compliance = 0;
        }
        if ($this->input->post('enable_fleet') == "on") {
            $fleet = 1;
        } else {
            $fleet = 0;
        }
        if ($this->input->post('enable_condition') == "on") {
            $condition = 1;
        } else {
            $condition = 0;
        }
        if ($this->input->post('enable_depreciation') == "on") {
            $depreciation = 1;
        } else {
            $depreciation = 0;
        }
        if ($this->input->post('enable_reporting') == "on") {
            $reporting = 1;
        } else {
            $reporting = 0;
        }

        $sitedata = array('name' => $this->input->post('package_name'),
            'item_limit' => $this->input->post('package_asset'),
            'package_annual'=>$this->input->post('package_annual'),
            'compliance_module' => $compliance,
            'fleet_module' => $fleet,
            'conditionmodule' => $condition,
            'depreciation' => $depreciation,
            'reporting' => $reporting,
        );
        if (!$this->input->post('enable_package') == false) {
            $sitedata['enable'] = 1;
        } else {
            $sitedata['enable'] = 0;
        }
        $this->db->insert('packages', $sitedata);
        $id = $this->db->insert_id();
        if ($id) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Action For Edit Package
    public function editPackage($editPackage) {

        if (isset($editPackage)) {
            $edit_package = array('name' => $editPackage['packagename'],
                'item_limit' => $editPackage['editpackage_asset']);
            if ($this->input->post('editenable_package')) {
                $edit_package['enable'] = 1;
            } else {
                $edit_package['enable'] = 0;
            }
            if ($this->input->post('editenable_package')) {
                $this->db->where('id', $editPackage['adminuser_id']);
                $this->db->update('packages', $edit_package);
                return $this->db->affected_rows();
            } else {
                $res = $this->db->where('package_id', $editPackage['adminuser_id'])->get('accounts');
                if ($res->num_rows() > 0) {
                    return FALSE;
                } else {
                    $this->db->where('id', $editPackage['adminuser_id']);
                    $this->db->update('packages', $edit_package);
                    return $this->db->affected_rows();
                }
            }
        } else {
            return False;
        }
    }

    // get package list

    public function package_list() {

        $this->db->select('*');
        $res = $this->db->get('packages');
        $packageinfo = $res->result();
        return $packageinfo;
    }

// get package name

    public function package_name($pkg_id) {

        $this->db->select('*');
        $this->db->where('id', $pkg_id);
        $res = $this->db->get('packages');
        $packageinfo = $res->result();
        return $packageinfo;
    }

    // archive system admin

    public function archiveSystemAccount($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('systemadmins', array('archive' => 0));
            return TRUE;
        } else {
            return FALSE;
        }
    }

// Archive System Admin User
    public function archive_admin($adminid) {

        if (isset($adminid)) {
            $this->db->set(array('archive' => 0, 'active' => 0));
            $this->db->where('id', $adminid);
            $this->db->update('systemadmins');
            return 1;
        } else {
            return False;
        }
    }

// Archive System Admin User
    public function archive_Master($masterid) {

        if (isset($masterid)) {
            $this->db->set(array('archive' => 0, 'active' => 0));
            $this->db->where('id', $masterid);
            $this->db->update('master_ac');
            return 1;
        } else {
            return False;
        }
    }

    // Archive System Admin User
    public function archive_Franchise($franchiseid) {

        if (isset($franchiseid)) {
            $this->db->set(array('archive' => 0, 'active' => 0));
            $this->db->where('id', $franchiseid);
            $this->db->update('franchise_ac');
            return 1;
        } else {
            return False;
        }
    }

    // restoreSystem master User
    public function restoreMaster($masterid) {

        if (isset($masterid)) {
            $this->db->set(array('archive' => 1, 'active' => 1));
            $this->db->where('id', $masterid);
            $this->db->update('master_ac');
            return TRUE;
        } else {
            return False;
        }
    }

    // restore System franchise User
    public function restoreFranchise($franchiseid) {

        if (isset($franchiseid)) {
            $this->db->set(array('archive' => 1, 'active' => 1));
            $this->db->where('id', $franchiseid);
            $this->db->update('franchise_ac');
            return TRUE;
        } else {
            return False;
        }
    }

    // restore system admin

    public function restoreSystemAccount($id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('systemadmins', array('archive' => 1));
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
