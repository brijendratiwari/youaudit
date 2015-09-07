<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Reports_model extends CI_Model
{
    function __construct(){
        parent::__construct();
    }
    
    function getPatFailures($mixStartDate, $mixEndDate, $intAccountId)
    {
        $this->db->select('
                items.id AS itemid,items.manufacturer, items.model , CONCAT(items.manufacturer, " ", items.model) AS itemname, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id, items.pattest_date, items.pattest_status,
		categories.id AS categoryid, categories.name AS categoryname,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                itemstatus.name AS statusname', FALSE);
	    
        $this->db->from('items');
        $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
        $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
        $this->db->join('users', 'items.owner_now = users.id', 'left');

        $this->db->join('locations', 'items.location_now = locations.id', 'left');
        $this->db->join('sites', 'items.site = sites.id', 'left');
        $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');

        $this->db->where('items.account_id', $intAccountId);
        if ($mixStartDate && $mixEndDate)
        {
            $this->db->where('items.pattest_date BETWEEN FROM_UNIXTIME('.strtotime($mixStartDate).') AND FROM_UNIXTIME('.strtotime($mixEndDate).')');
        }
        //$this->db->where('items.pattest_date BETWEEN FROM_UNIXTIME('.strtotime($strStartDate).') AND FROM_UNIXTIME('.strtotime($strEndDate).')');
        $this->db->where('items.pattest_status = 0');
        
        $this->db->order_by('items.pattest_date ASC');
        $this->db->order_by('items.manufacturer ASC');
        $this->db->order_by('items.model ASC');
        
        $resQuery = $this->db->get();
        
        if ($resQuery->num_rows() > 0)
        {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow)
            {
                $arrItemsData[] = $objRow;
            }
            return array('results'=>$arrItemsData,'query'=>$this->db->last_query());
        }
        else
        {
            return array('results'=> array(),'query'=>$this->db->last_query());
        }
            
    }
    
    function getPatDue($mixStartDate, $mixEndDate, $intAccountId)
    {
        $this->db->select('
                items.id AS itemid,items.manufacturer, items.model , CONCAT(items.manufacturer, " ", items.model) AS itemname, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id, items.pattest_date, items.pattest_status,
                DATE_ADD(items.pattest_date, INTERVAL 1 YEAR) AS pattestdue_date,
		categories.id AS categoryid, categories.name AS categoryname,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                itemstatus.name AS statusname', FALSE);
	    
        $this->db->from('items');
        $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
        $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
        $this->db->join('users', 'items.owner_now = users.id', 'left');

        $this->db->join('locations', 'items.location_now = locations.id', 'left');
        $this->db->join('sites', 'items.site = sites.id', 'left');
        $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');

        $this->db->where('items.account_id', $intAccountId);
        if ($mixStartDate && $mixEndDate)
        {
            $this->db->where('items.pattest_date BETWEEN FROM_UNIXTIME('.strtotime("-1 year", strtotime($mixStartDate)).') AND FROM_UNIXTIME('.strtotime("-1 year", strtotime($mixEndDate)).')');
        }
        else
        {
            $this->db->where('(items.pattest_status != 5 OR items.pattest_status IS NULL)');
            
        }
        //$this->db->where('items.pattest_status = 0');
        
        $this->db->order_by('items.pattest_date ASC');
        $this->db->order_by('items.manufacturer ASC');
        $this->db->order_by('items.model ASC');
        
        
        
        $resQuery = $this->db->get();
        
        //echo $this->db->last_query();
        
        if ($resQuery->num_rows() > 0)
        {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow)
            {
                $arrItemsData[] = $objRow;
            }
            return array('results'=>$arrItemsData,'query'=>$this->db->last_query());
        }
        else
        {
            return array('results'=> array(),'query'=>$this->db->last_query());
        }
            
    }
    
    function getRemovedItems($mixStartDate, $mixEndDate, $intAccountId)
    {
        $this->db->select('
                items.id AS itemid,items.manufacturer, items.model , CONCAT(items.manufacturer, " ", items.model) AS itemname, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id, items.mark_deleted, items.mark_deleted_2, items.deleted_date,
		categories.id AS categoryid, categories.name AS categoryname,
		admin.id AS adminid, admin.firstname AS adminfirstname, admin.lastname AS adminlastname, admin.nickname AS adminnickname, admin.username AS adminusername,
                superadmin.id AS superadminid, superadmin.firstname AS superadminfirstname, superadmin.lastname AS superadminlastname, superadmin.nickname AS superadminnickname, superadmin.username AS superadminusername,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                itemstatus.name AS statusname', FALSE);
        $this->db->from('items');
        $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
        $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
        $this->db->join('users AS admin', 'items.mark_deleted = admin.id', 'left');
        $this->db->join('users AS superadmin', 'items.mark_deleted_2 = superadmin.id', 'left');
        $this->db->join('locations', 'items.location_now = locations.id', 'left');
        $this->db->join('sites', 'items.site = sites.id', 'left');
        $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
        
        $this->db->where('items.account_id', $intAccountId);
        $this->db->where('items.active', 0);
        if ($mixStartDate && $mixEndDate)
        {
            $this->db->where('items.deleted_date BETWEEN FROM_UNIXTIME('.strtotime($mixStartDate).') AND FROM_UNIXTIME('.strtotime($mixEndDate).')');
        }
        $this->db->order_by('items.deleted_date DESC');
        
        $resQuery = $this->db->get();
        //print_r($this->db->last_query());
        //die();
        
        if ($resQuery->num_rows() > 0)
        {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow)
            {
                $arrItemsData[] = $objRow;
            }
            
            
            return array('results'=>$arrItemsData,'query'=>$this->db->last_query());
        }
        else
        {
            return array('results'=> array(),'query'=>$this->db->last_query());
        }
    }
    
    function getTotalValue($intAccountId)
    {
        $this->db->select('
                SUM(items.value) as categorytotalvalue,
                SUM(items.current_value) as categorytotalcurrentvalue,
                (SUM(items.value)-SUM(items.current_value)) as categorytotaldepreciation,
                categories.name as categoryname,
                COUNT(DISTINCT items.barcode) as categorytotalitems', FALSE);
	    
        $this->db->from('categories');
        $this->db->join('items_categories_link', 'items_categories_link.category_id = categories.id', 'left');
        $this->db->join('items', 'items.id = items_categories_link.item_id', 'left');
        

        $this->db->where('items.account_id', $intAccountId);
        $this->db->where('items.active', 1);
        //$this->db->where('items.pattest_date BETWEEN FROM_UNIXTIME('.strtotime("-1 year", strtotime($strStartDate)).') AND FROM_UNIXTIME('.strtotime("-1 year", strtotime($strEndDate)).')');
        //$this->db->where('items.pattest_status = 0');
        $this->db->group_by('categoryname');
        $this->db->order_by('categoryname ASC');
        //$this->db->order_by('items.manufacturer ASC');
        //$this->db->order_by('items.model ASC');
        
        $resQuery = $this->db->get();
        if ($resQuery->num_rows() > 0)
        {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow)
            {
                $arrItemsData[] = $objRow;
            }
            return array('results'=>$arrItemsData,'query'=>$this->db->last_query());
        }
        else
        {
            return array('results'=> array(),'query'=>$this->db->last_query());
        }
            
    }
    
    function getUserTotalValue($intAccountId)
    {
        $this->db->select('
                SUM(items.value) as usertotalvalue,
                CONCAT(users.firstname, " ",users.lastname) as userfullname,
                users.username as username,
                COUNT(DISTINCT items.barcode) as usertotalitems', FALSE);
	    
        $this->db->from('users');
        
        $this->db->join('items', 'items.owner_now = users.id', 'left');
        

        $this->db->where('items.account_id', $intAccountId);
        //$this->db->where('items.pattest_date BETWEEN FROM_UNIXTIME('.strtotime("-1 year", strtotime($strStartDate)).') AND FROM_UNIXTIME('.strtotime("-1 year", strtotime($strEndDate)).')');
        //$this->db->where('items.pattest_status = 0');
        $this->db->group_by('users.id');
        $this->db->order_by('users.lastname ASC');
        $this->db->order_by('users.firstname ASC');
        //$this->db->order_by('items.model ASC');
        
        $resQuery = $this->db->get();
        
        if ($resQuery->num_rows() > 0)
        {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow)
            {
                $arrItemsData[] = $objRow;
            }
            return array('results'=>$arrItemsData,'query'=>$this->db->last_query());
        }
        else
        {
            return array('results'=> array(),'query'=>$this->db->last_query());
        }
            
    }
    
    function getLocationTotalValue($intAccountId)
    {
        $this->db->select('
                SUM(items.value) as locationtotalvalue,
                locations.name as locationname,
                COUNT(DISTINCT items.barcode) as locationtotalitems', FALSE);
	    
        $this->db->from('locations');
        
        $this->db->join('items', 'items.location_now = locations.id', 'left');
        

        $this->db->where('items.account_id', $intAccountId);
        //$this->db->where('items.pattest_date BETWEEN FROM_UNIXTIME('.strtotime("-1 year", strtotime($strStartDate)).') AND FROM_UNIXTIME('.strtotime("-1 year", strtotime($strEndDate)).')');
        //$this->db->where('items.pattest_status = 0');
        $this->db->group_by('locations.id');
        $this->db->order_by('locations.name ASC');
        //$this->db->order_by('items.model ASC');
        
        $resQuery = $this->db->get();
        
        if ($resQuery->num_rows() > 0)
        {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow)
            {
                $arrItemsData[] = $objRow;
            }
            return array('results'=>$arrItemsData,'query'=>$this->db->last_query());
        }
        else
        {
            return array('results'=> array(),'query'=>$this->db->last_query());
        }
            
    }
    
    function getSiteTotalValue($intAccountId)
    {
        $this->db->select('
                SUM(items.value) as sitetotalvalue,
                sites.name as sitename,
                COUNT(DISTINCT items.barcode) as sitetotalitems', FALSE);
	    
        $this->db->from('sites');
        
        $this->db->join('items', 'items.site = sites.id', 'left');
        

        $this->db->where('items.account_id', $intAccountId);
        //$this->db->where('items.pattest_date BETWEEN FROM_UNIXTIME('.strtotime("-1 year", strtotime($strStartDate)).') AND FROM_UNIXTIME('.strtotime("-1 year", strtotime($strEndDate)).')');
        //$this->db->where('items.pattest_status = 0');
        $this->db->group_by('sites.id');
        $this->db->order_by('sites.name ASC');
        
        //$this->db->order_by('items.model ASC');
        
        $resQuery = $this->db->get();
        
        if ($resQuery->num_rows() > 0)
        {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow)
            {
                $arrItemsData[] = $objRow;
            }
            return array('results'=>$arrItemsData,'query'=>$this->db->last_query());
        }
        else
        {
            return array('results'=> array(),'query'=>$this->db->last_query());
        }
            
    }
    
    public function getFleetCompliance($start_date = NULL, $end_date = NULL) {
        $this->load->model('fleet_model');
        $fleet = $this->fleet_model->getFleetAll($this->session->userdata('objSystemUser')->accountid);
        if($start_date != NULL && $end_date != NULL) {
            $start_ts = strtotime($start_date);
            $end_ts   = strtotime($end_date);
        }

        foreach($fleet as $id => $vehicle) {
            $flag = 0;
            
            if(empty($start_date) && empty($end_date)) {
               
                $start_ts = strtotime("now");
                $end_ts = strtotime("+30 days", $start_ts);

                if($vehicle['mot_renewal_timestamp'] > $start_ts && $vehicle['mot_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['tax_expiration'] > $start_ts && $strtotime($vehicle['tax_expiration']) < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['service_renewal_timestamp'] > $start_ts && $vehicle['service_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if(strtotime($vehicle['insurance_expiration']) > $start_ts && strtotime($vehicle['insurance_expiration']) < $end_ts) {
                    $flag = 1;
                }
            } else {
                
                if($vehicle['mot_renewal_timestamp'] > $start_ts && $vehicle['mot_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['tax_expiration'] > $start_ts && strtotime($vehicle['tax_expiration']) < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['service_renewal_timestamp'] > $start_ts && $vehicle['service_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if(strtotime($vehicle['insurance_expiration']) > $start_ts && strtotime($vehicle['insurance_expiration']) < $end_ts) {
                    $flag = 1;
                }
            }

            if($flag == 0) {
                unset($fleet[$id]);
            }
        }
        
        
        return $fleet;  
    }
    
    public function getComplianceDue($start_date = NULL, $end_date = NULL, $mandFilter = NULL) {
        $this->load->model('tests_model');
        
        $filter['start_date'] = $start_date;
        $filter['end_date'] = $end_date;

        
        if($filter != NULL) {
            $result = $this->tests_model->getDueTests($this->session->userdata('objSystemUser')->accountid, $filter);
        } else {
            $result = $this->tests_model->getDueTests($this->session->userdata('objSystemUser')->accountid);
        }
        /* Check for optional/mandatory filter */
        if($mandFilter == 'mandatory') {
            unset($result['dueOptional']);
        }
        
        if($mandFilter == 'optional') {
            unset($result['dueMandatory']);
        }
        
        /*if($start_date != NULL && $end_date != NULL) {
            $start_ts = strtotime($start_date);
            $end_ts   = strtotime($end_date);
        }

        foreach($fleet as $id => $vehicle) {
            $flag = 0;
            
            if(empty($start_date) && empty($end_date)) {
                $start_ts = strtotime("now");
                $end_ts = strtotime("+30 days", $start_ts);

                if($vehicle['mot_renewal_timestamp'] > $start_ts && $vehicle['mot_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['tax_renewal_timestamp'] > $start_ts && $vehicle['tax_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['service_renewal_timestamp'] > $start_ts && $vehicle['service_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if(strtotime($vehicle['insurance_expiration']) > $start_ts && strtotime($vehicle['insurance_expiration']) < $end_ts) {
                    $flag = 1;
                }
            } else {
                if($vehicle['mot_renewal_timestamp'] > $start_ts && $vehicle['mot_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['tax_renewal_timestamp'] > $start_ts && $vehicle['tax_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if($vehicle['service_renewal_timestamp'] > $start_ts && $vehicle['service_renewal_timestamp'] < $end_ts) {
                    $flag = 1;
                }
                if(strtotime($vehicle['insurance_expiration']) > $start_ts && strtotime($vehicle['insurance_expiration']) < $end_ts) {
                    $flag = 1;
                }
            }
            
            if($flag == 0) {
                unset($fleet[$id]);
            }
        }
        */

        return $result;  
    }
    
    public function getComplianceComplete($start_date = NULL, $end_date = NULL) {
        $this->load->model('tests_model');
        $results = $this->tests_model->getComplianceHistory();
        
        if($start_date != NULL && $end_date != NULL) {
            foreach ($results as $key => $value) {

                if(strtotime($value['test_date']) < strtotime($start_date) || strtotime($value['test_date']) > strtotime($end_date)) {

                    unset($results[$key]);              
                }
            }
        }
        
        return $results;  
    }

    public function getMissingItems($start_date, $end_date, $sqlDate = false){

        if(!$sqlDate){
            $start_date = explode('/',$start_date);
            $start_date = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
            $end_date = explode('/',$end_date);
            $end_date = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];
        }
        $audits_in_date =
            $this->db->where('completed >=', $start_date)
                ->where('completed <=', $end_date)
                ->where('account_id', $this->session->userdata['objSystemUser']->accountid)
                ->get('audits')
                ->result_array();



        $all_missing_items = array();
        foreach($audits_in_date as $key => $audit){

            $missing_items =
                $this->db
//                    ->select('DATE_FORMAT(audits.completed, "%d %b %Y")')
                ->select('*')

                ->join('audits','audititems.audit_id = audits.id')
                ->join('items','audititems.item_id = items.id AND items.account_id = '.$this->session->userdata['objSystemUser']->accountid)
                ->join('locations','locations.id = audits.location_id AND items.account_id = '.$this->session->userdata['objSystemUser']->accountid)
                ->where('audititems.audit_id', $audit['id'])
                ->where('audititems.present', 0)
                ->get('audititems');


            foreach ($missing_items->result() as $row)
            {
                $all_missing_items[] = $row;
            }


        }

        foreach($all_missing_items as $key => $result){

            $newdate = explode('-', substr($result->completed,0,10));
            $all_missing_items[$key]->completed = $newdate[2].'/'.$newdate[1].'/'.$newdate[0];
        }


        return $all_missing_items;

    }
}
?>