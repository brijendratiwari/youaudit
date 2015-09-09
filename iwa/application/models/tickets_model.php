<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Tickets_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function ticketSubmission($item_id, $username, $description, $priority) {
        $data = array(
            'item_id' => $item_id,
            'user_id' => $username,
            'date' => date("Y-m-d H:i:s"),
            'description' => $description,
            'priority' => $priority
        );
        $this->db->insert('tickets', $data);
    }

    public function ticketSubmissionFleet($fleet_id, $username, $description, $priority) {
        echo "in side model";
        die;
        $data = array(
            'fleet_id' => $fleet_id,
            'user_id' => $username,
            'date' => date("Y-m-d H:i:s"),
            'description' => $description,
            'priority' => $priority
        );
        $this->db->insert('fleet_tickets', $data);
    }

    public function ticketFleetHistory($item_id) {
        $this->load->model('users_model');
        $this->db->where('fleet_id', $item_id);
        $query = $this->db->get('fleet_tickets');
        $itemHistory = $query->result_array();

        foreach ($itemHistory as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);

            $itemHistory[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        }

        return $itemHistory;
    }

    public function ticketHistory($item_id) {
//        MAX(date) As fix_Date ,MIN(date) As Fault_Date
        $this->load->model('users_model');
        $this->db->select('tickets.id,tickets.photoid,tickets.severity,tickets.jobnote,tickets.order_no,tickets.fix_code,tickets.reason_code,tickets.ticket_action,tickets.user_id,tickets.date,tickets.fix_date,itemstatus.name AS statusname,tickets.item_id as itemid
');
        $this->db->join('items', 'items.id = tickets.item_id', 'left');

        $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');

        $this->db->where('tickets.item_id', $item_id);
        //c$this->db->group_by("tickets.item_id"); 
        $query = $this->db->get('tickets');
        $itemHistory = $query->result_array();

        foreach ($itemHistory as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);

            $itemHistory[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
            $itemHistory[$key]['firstname'] = $user['result'][0]->firstname;
            $itemHistory[$key]['lastname'] = $user['result'][0]->lastname;
        }

        return $itemHistory;
    }

    function updateTicket($ticket_id, $data) {


        if ($data) {
            $update_ticket = array(
                'reason_code' => $data['reason_code'],
                'jobnote' => $data['jobnote'],
                'status' => $data['status'],
                'fix_date' => $data['fix_date'],
                'photoid' => $data['photoid']
            );
            $this->db->where('id', $ticket_id);
            $this->db->update('tickets', $update_ticket);
            $this->db->where('id', $data['fix_item_id']);
            $this->db->update('items', array('status_id' => $data['status']));

            return TRUE;
        }
    }

    // End of function

    function insertTicket($data) {

        $this->db->where('id', $data['item_id']);
        $this->db->update('items', array('status_id' => $data['status']));
        $this->db->insert('tickets', $data);
        return $this->db->insert_id();
    }

    function fixStatus($data) {
        if ($data) {

            $open_history = $this->getTicketData($data['ticket_id']);
            $history = array(
                'item_id' => $open_history->item_id,
                'user_id' => $open_history->user_id,
                'date' => $open_history->date,
                'status' => $open_history->status,
                'severity' => $open_history->severity,
                'jobnote' => $open_history->jobnote,
                'jobnote' => $open_history->jobnote,
                'order_no' => $open_history->order_no,
                'reason_code' => $open_history->reason_code,
                'reason_code' => $open_history->reason_code,
                'photoid' => $open_history->photoid
            );
            foreach ($history as $key => $value) {
                if ($value = '') {
                    unset($history[$key]);
                }
            }
            $this->db->insert('tickets_history', $history);
            $this->db->where('id', $data['fix_item_id']);
            $result = $this->db->update('items', array("status_id" => $data['status']));
            if ($result) {
                $this->db->where('id', $data['ticket_id']);
                $this->db->update('tickets', array("fix_code" => $data['fix_code'], "status" => $data['status'], "jobnote" => $data['job_notes'], "ticket_action" => "Fix", "fix_date" => $data['fix_date'], "photoid" => $data['photoid']));
                return TRUE;
            } else {
                return FALSE;
            }
        } // End of function
    }

    // Added date 10 Feb 2015

    public function getAllFaultItems($intAccountId = -1, $export = '') {
        if ($intAccountId > 0) {
            if ($export == '') {
                $this->db->select('
                items.id AS itemid,
                items.item_manu, 
                items.manufacturer, 
                items.account_id, 
                items.model,
                items.barcode, 
                items.site, 
                owner.owner_name,
                item_manu.item_manu_name,
                categories.name AS categoryname,
                locations.name AS locationname,
                sites.name AS sitename,
                pat.pattest_name AS pat_status,
                itemstatus.name AS statusname,
                users.firstname,users.lastname,
                tickets.description,
                tickets.severity,
                tickets.id as ticket_id,
                tickets.fix_code,
                tickets.reason_code,
                tickets.order_no,
                tickets.jobnote,
                tickets.date as dt,
                tickets.ticket_action');
            } else {
                $this->db->select('
                items.item_manu, 
                items.manufacturer, 
                items.model,
                items.barcode, 
                 owner.owner_name,
                 item_manu.item_manu_name,
                categories.name AS categoryname,
                locations.name AS locationname,
                sites.name AS sitename,
                itemstatus.name AS statusname,
                users.firstname,users.lastname,
                tickets.severity,
                tickets.order_no,
                tickets.jobnote,
                tickets.date as dt,
                tickets.ticket_action');
            }

            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('tickets', 'tickets.item_id = items.id', 'LEFT');
            $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('pat', 'items.pattest_status = pat.id', 'left');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');
            $where = "(items.account_id = $intAccountId  AND (tickets.ticket_action='Open Job' OR tickets.status='2' OR tickets.status='3'))";
            $this->db->where($where);
//            $this->db->group_by('items.barcode');
            $this->db->order_by('tickets.id', 'DESC');

            $resQuery = $this->db->get();

            if ($export == 'CSV') {

                $this->load->dbutil();
                $this->load->helper('download');
                force_download('Youaudit_' . date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($resQuery));
            } elseif ($export == 'PDF') {
                $arrFields = array(
                    array('strName' => 'QR Code', 'strFieldReference' => 'barcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Category', 'strFieldReference' => 'categoryname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Item', 'strFieldReference' => 'item_manu_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Model', 'strFieldReference' => 'model', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Site', 'strFieldReference' => 'sitename', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Location', 'strFieldReference' => 'locationname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Owner', 'strConversion' => 'user', 'strFieldReference' => 'firstname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Status', 'strFieldReference' => 'statusname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Action', 'strFieldReference' => 'ticket_action', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Date Default Reported', 'strFieldReference' => 'dt', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Fault Time', 'strConversion' => 'fault_time', 'strFieldReference' => 'dt', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Severity', 'strFieldReference' => 'severity', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Order No', 'strFieldReference' => 'order_no', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Job Notes', 'strFieldReference' => 'jobnote', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
                );

                $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $resQuery->result_array());
            }
            if ($resQuery->num_rows() > 0) {

                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {

                    $arrItemsData[] = $objRow;
                }

                return array('results' => $arrItemsData);
            } else {

                return array();
            }
        }

        return false;
    }

    public function getCurrentFaults($intAccountId = -1) {
        if ($intAccountId > 0) {

            $this->db->select('
                items.id AS itemid,
                items.item_manu, 
                items.manufacturer, 
                items.account_id, 
                items.model,
                items.barcode, 
                items.site, 
                categories.name AS categoryname,
                locations.name AS locationname,
                sites.name AS sitename,
                pat.pattest_name AS pat_status,
                itemstatus.name AS statusname,
                tickets.description,
                tickets.severity,
                tickets.id as ticket_id,
                tickets.fix_code,
                tickets.reason_code,
                tickets.order_no,
                tickets.jobnote,
                tickets.user_id,
                users.firstname,
                users.lastname,
                tickets.fix_date,
                tickets.photoid,
                tickets.date as dt,
                tickets.ticket_action');


            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');


            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('tickets', 'tickets.item_id = items.id', 'left');
            $this->db->join('users', 'tickets.user_id =users.id', 'left');
            $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('pat', 'items.pattest_status = pat.id', 'left');

            $this->db->where('items.active', 1);
            $this->db->where('tickets.ticket_action', 'Open Job');
            $this->db->where('items.account_id', $intAccountId);
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {

                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[] = $objRow;
                }

                return array('results' => $arrItemsData);
            } else {
                //echo $this->db->last_query(); die;
                return array();
            }
        }

        return false;
    }

    public function lastDateOfFaults($itemId) {
        if ($itemId > 0) {
            $this->db->select('max(tickets.date) AS faultDate');
            $this->db->where('tickets.item_id', $itemId);
            $this->db->limit(1);
            $query = $this->db->get('tickets');
            $lastFaultsDate = $query->result_array();
            return $lastFaultsDate[0]['faultDate'];
        }
    }

    public function checkReportFault($intItemId) {
        if ($intItemId > 0) {
            $this->db->select('*');
            $this->db->where('tickets.item_id', $intItemId);
            $this->db->where('tickets.ticket_action', 'Open Job');
            $query = $this->db->get('tickets');
            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {
                return FALSE;
            }
        }
    }

    public function getFaultTicketHistory($item_id) {
//        MAX(date) As fix_Date ,MIN(date) As Fault_Date
        $this->load->model('users_model');
        $this->load->model('photos_model');
        $this->db->select('tickets.id as ticket_id,tickets.severity,tickets.jobnote,tickets.status,tickets.order_no,tickets.fix_code,tickets.reason_code,tickets.ticket_action,tickets.user_id,tickets.date as dt,tickets.fix_date,tickets.photoid');
        $this->db->where('tickets.item_id', $item_id);
        $this->db->where('tickets.ticket_action', 'Open Job');
        $query = $this->db->get('tickets');
        $itemHistory = $query->result_array();

        foreach ($itemHistory as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objAppUser')->accountid);
            $itemHistory[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
            $itemHistory[$key]['firstname'] = $user['result'][0]->firstname;
            $itemHistory[$key]['lastname'] = $user['result'][0]->lastname;
//            if($value['status']==2){
//                $itemHistory[$key]['status']='Damaged';
//            }
//            if($value['status']==3){
//                $itemHistory[$key]['status']='Faulty';
//            }
//            if($value['status']==6){
//                $itemHistory[$key]['status']='Missing';
//            }
            switch ($value['status']) {
                case "2":
                    $itemHistory[$key]['status'] = 'Damaged';
                    break;
                case "3":
                    $itemHistory[$key]['status'] = 'Faulty';
                    break;
                case "6":
                    $itemHistory[$key]['status'] = 'Missing';
                    break;
                default:
                    $itemHistory[$key]['status'] = '';
            }

            $photo_details = '';
            $photoarray = '';
            $photo_array = '';
            $res = $this->db->select('photoid')->where('item_id', $item_id)->order_by('id')->get('tickets')->result();
            if ($res) {
                for ($j = 0; $j < count($res); $j++) {
                    if ($res[$j]->photoid != '') {
                        if (strpos($res[$j]->photoid, ',') !== false) {
                            $idsarr = explode(',', $res[$j]->photoid);
                            foreach ($idsarr as $idval) {
                                $photo_array[] = $idval;
                            }
                        } else {
                            $photo_array[] = $res[$j]->photoid;
                        }
                    }
                }
            }

            $photoarray = implode(',', $photo_array);

            if (strpos($photoarray, ',') !== false) {
                $ids_arr = explode(',', $photoarray);
                foreach ($ids_arr as $id_val) {
                    $photo_details[] = $this->photos_model->getOne($id_val);
                }
                $itemHistory[$key]['photo_details'] = $photo_details;
            } else {
                $photo_details = $this->photos_model->getOne($photoarray);
                $itemHistory[$key]['photo_details'] = $photo_details;
            }

            $notes_array = '';
            $notesarray = '';
            $jobnotes = $this->db->select('jobnote')->where('item_id', $item_id)->order_by('id')->get('tickets')->result();
            if ($jobnotes) {
                for ($s = 0; $s < count($jobnotes); $s++) {
                    if ($jobnotes[$s]->jobnote != '') {
                        if (strpos($jobnotes[$s]->jobnote, ',') !== false) {
                            $jobarr = explode(',', $jobnotes[$s]->jobnote);
                            foreach ($jobarr as $jobval) {
                                $notes_array[] = $jobval;
                            }
                        } else {
                            $notes_array[] = $jobnotes[$s]->jobnote;
                        }

//                            $notes_array[] = $jobnotes[$s]->jobnote;
                    }
                }
            }

            $notesarray = implode(',', $notes_array);
            $itemHistory[$key]['jobnote'] = $notesarray;
            
//            if (strtotime($value['dt'] > 0)) {
            $itemHistory[$key]['dt'] = date('d/m/Y', strtotime($value['dt']));
//            } else {
//                $itemHistory[$key]['dt'] = "";
//            }
            if ($value['fix_date'] != '0000-00-00 00:00:00') {
                $itemHistory[$key]['fix_date'] = date('d/m/Y', strtotime($value['fix_date']));
            } else {
                $itemHistory[$key]['fix_date'] = "";
            }
        }
        if (!empty($itemHistory)) {

            return $itemHistory;
        } else {
            return array();
        }
    }

    public function searchfaults($intAccountId = -1, $strManufacturer = "", $intSite = -1, $intUser = -1, $intLocation = -1, $intCategory = -1, $stritem_manu = -1, $strbarcode = -1, $strfreetext = -1) {

        if ($intAccountId > 0) {

            if ($strfreetext != -1) {

                $resQuery = $this->db->query("
              SELECT `items`.`id` AS itemid, `items`.`item_manu`, `item_manu`.`item_manu_name` as item_name, `items`.`manufacturer`, `items`.`account_id`, `items`.`model`, `items`.`barcode`, `items`.`site`, `categories`.`name` AS categoryname, `locations`.`name` AS locationname, `sites`.`name` AS sitename, `pat`.`pattest_name` AS pat_status, `itemstatus`.`name` AS statusname, `tickets`.`description`, `tickets`.`severity`, `tickets`.`id` as ticket_id, `tickets`.`fix_code`,`tickets`.`user_id`, `tickets`.`reason_code`,`tickets`.`photoid`, `tickets`.`order_no`,`tickets`.`fix_date`, `tickets`.`jobnote`, `tickets`.`date` as dt, `tickets`.`ticket_action`FROM (`items`)
LEFT JOIN `items_categories_link` ON `items`.`id` = `items_categories_link`.`item_id`
LEFT JOIN `categories` ON `items_categories_link`.`category_id` = `categories`.`id`
LEFT JOIN `owner` ON `items`.`owner_now` = `owner`.`id`
LEFT JOIN `item_manu` ON `item_manu`.`id` = `items`.`item_manu`
LEFT JOIN `users` ON `items`.`owner_now` = `users`.`id`
LEFT JOIN `locations` ON `items`.`location_now` = `locations`.`id`
LEFT JOIN `sites` ON `items`.`site` = `sites`.`id`
LEFT JOIN `tickets` ON `tickets`.`item_id` = `items`.`id`
LEFT JOIN `suppliers` ON `items`.`supplier` = `suppliers`.`supplier_id`
LEFT JOIN `itemstatus` ON `items`.`status_id` = `itemstatus`.`id`
LEFT JOIN `pat` ON `items`.`pattest_status` = `pat`.`id`
WHERE `items`.`active` =  1
AND `tickets`.`ticket_action` =  'Open Job'
AND `items`.`account_id` =  '$intAccountId' AND
(`sites`.`name` LIKE '%$strfreetext%'
OR `locations`.`name` LIKE '%$strfreetext%'
OR `item_manu`.`item_manu_name` LIKE '%$strfreetext%'
OR `categories`.`name` LIKE '%$strfreetext%')");



                if ($resQuery->num_rows() > 0) {
                    $arrItemsData = array();
                    foreach ($resQuery->result() as $objRow) {
                        $arrItemsData[] = $objRow;
                    }
                    return $arrItemsData;
                } else {
                    return array();
                }
            } else {

                $this->db->select('
                items.id AS itemid,
                items.item_manu, 
                item_manu.item_manu_name as item_name,
                items.manufacturer, 
                items.account_id, 
                items.model,
                items.barcode, 
                items.site, 
                categories.name AS categoryname,
                locations.name AS locationname,
                sites.name AS sitename,
                pat.pattest_name AS pat_status,
                itemstatus.name AS statusname,
                tickets.description,
                tickets.severity,
                tickets.id as ticket_id,
                tickets.fix_code,
                tickets.fix_date,
              
                tickets.photoid,
                tickets.user_id,
                tickets.reason_code,
                tickets.order_no,
                tickets.jobnote,
                tickets.date as dt,
                tickets.ticket_action');



                $this->db->from('items');
                $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
                $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
                $this->db->join('owner', 'items.owner_now = owner.id', 'left');
                $this->db->join('item_manu', 'item_manu.id = items.item_manu', 'left');

                $this->db->join('users', 'items.owner_now = users.id', 'left');
                $this->db->join('locations', 'items.location_now = locations.id', 'left');
                $this->db->join('sites', 'items.site = sites.id', 'left');
                $this->db->join('tickets', 'tickets.item_id = items.id', 'left');
                $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
                $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
                $this->db->join('pat', 'items.pattest_status = pat.id', 'left');

                $this->db->where('items.active', 1);
                $this->db->where('tickets.ticket_action', 'Open Job');
                $this->db->where('items.account_id', $intAccountId);

                if ($strbarcode != -1) {
                    $this->db->where('items.barcode', $strbarcode);
                }

                if ($intSite != -1) {
                    $this->db->where('sites.id', $intSite);
                }
                if ($intUser != -1) {
//                $this->db->where('users.id', $intUser);
                    $this->db->where('owner.id', $intUser);
                }
                if ($intLocation != -1) {
                    $this->db->where('locations.id', $intLocation);
                }
                if ($strManufacturer != "") {
                    $this->db->where('items.manufacturer', $strManufacturer);
                }
                if ($stritem_manu != -1) {
                    $this->db->where('items.item_manu', $stritem_manu);
                }
                // Add Category Filter             
                if ($intCategory != -1) {
                    $this->db->where('categories.id', $intCategory);
                }

                $resQuery = $this->db->get();

//            die ($this->db->last_query());

                if ($resQuery->num_rows() > 0) {
                    $arrItemsData = array();
                    foreach ($resQuery->result() as $objRow) {
                        $arrItemsData[] = $objRow;
                    }
                    return $arrItemsData;
                } else {
                    return array();
                }
            }
        }
        return '';
    }

    public function getPdf($ticket_id) {
        if ($ticket_id) {

            $this->load->model('users_model');
            $this->db->select('tickets.id,tickets.severity,tickets.jobnote,tickets.order_no,tickets.fix_code,tickets.reason_code,tickets.ticket_action,tickets.user_id,tickets.date,tickets.fix_date,itemstatus.name AS statusname,items.barcode,item_manu.item_manu_name,items.manufacturer,items.model,categories.name AS categoryname,  locations.name AS locationname, sites.name AS sitename,users.firstname AS userfirstname,users.lastname AS userlastname,users.nickname AS usernickname,tickets.photoid as photo_id');
            $this->db->join('items', 'items.id = tickets.item_id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');
            $this->db->where('tickets.id', $ticket_id);

            //c$this->db->group_by("tickets.item_id"); 
            $query = $this->db->get('tickets');
            $ticketdata = $query->result_array();
            foreach ($ticketdata as $key => $value) {
                $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);
                $ticketdata[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;

                if ($value['fix_date'] != '0000-00-00 00:00:00') {


                    $date2 = date('d-m-Y', strtotime($value['date']));
                    $date1 = date('d-m-Y', strtotime($value['fix_date']));

                    $diff = abs(strtotime($date1) - strtotime($date2));
                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = abs(floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)));

                    $days = floor(($diff / 3600 / 24));
                    $ticketdata[$key]['total'] = $months . ' month ' . $days . ' day ';
                } else {
                    $ticketdata[$key]['total'] = "-";
                }
            }
//            var_dump($ticketdata);die;

            $arrFields = array(
                array('strName' => 'Qr Code', 'strFieldReference' => 'barcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Category', 'strFieldReference' => 'categoryname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Item', 'strFieldReference' => 'item_manu_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Model', 'strFieldReference' => 'model', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Site', 'strFieldReference' => 'sitename', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Location', 'strFieldReference' => 'locationname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Owner', 'strFieldReference' => 'userfirstname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Severity', 'strFieldReference' => 'severity', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Status', 'strFieldReference' => 'statusname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Fault Date', 'strFieldReference' => 'date', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Fix Date', 'strFieldReference' => 'fix_date', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Tatal Time', 'strFieldReference' => 'total', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Logged By', 'strFieldReference' => 'username', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Reason Code', 'strFieldReference' => 'reason_code', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Order No', 'strFieldReference' => 'order_no', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Fix Code', 'strFieldReference' => 'fix_code', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Job Notes', 'strFieldReference' => 'jobnote', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                array('strName' => 'Photo', 'strFieldReference' => 'photo_id', 'strConversion' => 'img', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            );

            $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $ticketdata);
        }
    }

    // Html Function For PDf
    public function outputPdfFile($strReportName, $arrFields, $arrResults, $booOutputHtml = false) {

        $arrPageData['arrSessionData'] = $this->session->userdata;

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

            $strHtml .= "<th style='color:white'>" . $arrReportField['strName'] . "</th>";
        }

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        $arrTotals = array();
        foreach ($arrResults as $objItem) {

            $strHtml .= "<tr>";
            $arrPageData['arrSessionData'] = $this->session->userdata;

            foreach ($arrFields as $arrReportField) {
                $strHtml .= "<td style='height:50px:'>";
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
                        case 'img':
                            if ($objItem['photo_id']) {
                                if (strpos($objItem['photo_id'], ',')) {
                                    $photos = explode(',', $objItem['photo_id']);
                                    for ($i = 0; $i < count($photos); $i++) {
                                        $image = $this->db->select('path')->where('id', $photos[$i])->get('photos')->row();
                                        $strHtml .= "<img width='20' height='20' src='" . base_url() . $image->path . "'>";
                                    }
                                } else {
                                    $image = $this->db->select('path')->where('id', $objItem['photo_id'])->get('photos')->row();
                                    $strHtml .= "<img width='20' height='20' src='" . base_url() . $image->path . "'>";
                                }
                            }
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


        $strHtml .= "<p>Produced by " . $arrPageData['arrSessionData']["objSystemUser"]->firstname . " " . $arrPageData['arrSessionData']["objSystemUser"]->lastname . " (" . $arrPageData['arrSessionData']["objSystemUser"]->username . ") on " . date('d/m/Y') . "</p>";
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

    public function getAllFaultPdf() {


        $this->load->model('users_model');
        $this->db->select('tickets.id,tickets.severity,tickets.jobnote,tickets.order_no,tickets.fix_code,tickets.reason_code,tickets.ticket_action,tickets.user_id,tickets.date,tickets.fix_date,itemstatus.name AS statusname,items.barcode,items.item_manu,items.manufacturer,items.model,categories.name AS categoryname,  locations.name AS locationname, sites.name AS sitename,users.firstname AS userfirstname,users.lastname AS userlastname,users.nickname AS usernickname');
        $this->db->join('items', 'items.id = tickets.item_id', 'left');
        $this->db->join('sites', 'items.site = sites.id', 'left');
        $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
        $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
        $this->db->join('locations', 'items.location_now = locations.id', 'left');
        $this->db->join('users', 'items.owner_now = users.id', 'left');
        $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');


        //c$this->db->group_by("tickets.item_id"); 
        $query = $this->db->get('tickets');
        $ticketdata = $query->result_array();
        foreach ($ticketdata as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);
            $ticketdata[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;

            if ($value['fix_date'] != '0000-00-00 00:00:00') {


                $date2 = date('d-m-Y', strtotime($value['date']));
                $date1 = date('d-m-Y', strtotime($value['fix_date']));


                $diff = abs(strtotime($date1) - strtotime($date2));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $months = abs(floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)));

                $days = floor(($diff / 3600 / 24));
                $ticketdata[$key]['total'] = $months . ' month ' . $days . ' day ';
            } else {
                $ticketdata[$key]['total'] = "-";
            }
        }


        $arrFields = array(
            array('strName' => 'Qr Code', 'strFieldReference' => 'barcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Category', 'strFieldReference' => 'categoryname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Item/Manu', 'strFieldReference' => 'item_manu', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Model', 'strFieldReference' => 'model', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Site', 'strFieldReference' => 'sitename', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Location', 'strFieldReference' => 'locationname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Owner', 'strFieldReference' => 'userfirstname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Severity', 'strFieldReference' => 'severity', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Status', 'strFieldReference' => 'statusname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Fault Date', 'strFieldReference' => 'date', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Fix Date', 'strFieldReference' => 'fix_date', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Tatal Time', 'strFieldReference' => 'total', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Logged By', 'strFieldReference' => 'username', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Reason Code', 'strFieldReference' => 'reason_code', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Order No', 'strFieldReference' => 'order_no', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Fix Code', 'strFieldReference' => 'fix_code', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Job Notes', 'strFieldReference' => 'jobnote', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
            array('strName' => 'Photo', 'strFieldReference' => 'photo', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
        );

        $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $ticketdata);
    }

    // Edit Multiple Master account
    public function editMultiple_Faults() {
        $data = $this->input->post();
        if ($data['ticket_id'] != '') {
            if (strpos($data['ticket_id'], 'on') !== FALSE) {
                $data['ticket_id'] = str_replace('on,', '', $data['ticket_id']);
            }
            $ids = explode(',', $data['ticket_id']);

            $update_list = array(
                'reason_code' => $this->input->post('multiple_reason_code'),
                'jobnote' => $this->input->post('multiple_job_note')
            );

            foreach ($update_list as $key => $value) {
                if ($value == '') {
                    unset($update_list[$key]);
                }
            }
            if (empty($update_list)) {
                
            } else {

                $this->db->where_in('id', $ids)->update('tickets', $update_list);
            }
            return TRUE;
        }
    }

    public function ticketFixHistory($item_id) {
//        MAX(date) As fix_Date ,MIN(date) As Fault_Date
        $this->load->model('users_model');
        $this->db->select('tickets.id,tickets.photoid,tickets.severity,tickets.jobnote,tickets.order_no,tickets.fix_code,tickets.reason_code,tickets.ticket_action,tickets.user_id,tickets.date,tickets.fix_date,itemstatus.name AS statusname,tickets.item_id as itemid
');
        $this->db->join('items', 'items.id = tickets.item_id', 'left');
        $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');

        $this->db->where('tickets.item_id', $item_id);
        $this->db->where('tickets.ticket_action', "fix");
        //c$this->db->group_by("tickets.item_id"); 
        $query = $this->db->get('tickets');
        $itemHistory = $query->result_array();


        foreach ($itemHistory as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);

            $itemHistory[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        }

        return $itemHistory;
    }

    public function ticketOpenHistory($item_id) {
//        MAX(date) As fix_Date ,MIN(date) As Fault_Date
        $this->load->model('users_model');
        $this->db->select('tickets.id,tickets.photoid,tickets.severity,tickets.jobnote,tickets.order_no,tickets.fix_code,tickets.reason_code,tickets.ticket_action,tickets.user_id,tickets.date,tickets.fix_date,itemstatus.name AS statusname,tickets.item_id as itemid
');
        $this->db->join('items', 'items.id = tickets.item_id', 'left');
        $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');

        $this->db->where('tickets.item_id', $item_id);
        $this->db->where('tickets.ticket_action', "Open Job");
        //c$this->db->group_by("tickets.item_id"); 
        $query = $this->db->get('tickets');
        $itemHistory = $query->result_array();


        foreach ($itemHistory as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);

            $itemHistory[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        }

        return $itemHistory;
    }

    public function setPhoto($intId = -1, $intPhotoId = '') {


        if (($intId > 0)) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('tickets', array('photoid' => $intPhotoId));
        }
        return false;
    }

    public function getAllFixItems($intAccountId = -1, $export = '') {
        if ($intAccountId > 0) {
            if ($export == '') {
                $this->db->select('
                items.id AS itemid,
                items.item_manu, 
                items.manufacturer, 
                items.account_id, 
                items.model,
                items.barcode, 
                items.site, 
                owner.owner_name,
                item_manu.item_manu_name,
                categories.name AS categoryname,
                locations.name AS locationname,
                sites.name AS sitename,
                pat.pattest_name AS pat_status,
                itemstatus.name AS statusname,
                users.firstname,users.lastname,
                tickets.description,
                tickets.severity,
               tickets.id as ticket_id,
                tickets.fix_code,
                tickets.reason_code,
                tickets.order_no,
                tickets.jobnote,
                tickets.date as dt,
                tickets.ticket_action');
            } else {
                $this->db->select('
                items.item_manu, 
                items.manufacturer, 
                items.model,
                items.barcode, 
                 owner.owner_name,
                 item_manu.item_manu_name,
                categories.name AS categoryname,
                locations.name AS locationname,
                sites.name AS sitename,
                itemstatus.name AS statusname,
                users.firstname,users.lastname,
                tickets.severity,
                tickets.order_no,
                tickets.jobnote,
                tickets.date as dt,
                tickets.ticket_action');
            }

            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('tickets', 'tickets.item_id = items.id', 'LEFT');
            $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('pat', 'items.pattest_status = pat.id', 'left');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');
            $where = "(items.account_id = $intAccountId AND tickets.ticket_action='Fix')";

            $this->db->where($where);
//             $this->db->group_by('items.barcode');
            $this->db->order_by('tickets.date', 'DESC');


            $resQuery = $this->db->get();
            if ($export == 'CSV') {

                $this->load->dbutil();
                $this->load->helper('download');
                force_download('Youaudit_' . date('d/m/Y Gis') . '.csv', $this->dbutil->csv_from_result($resQuery));
            } elseif ($export == 'PDF') {
                $arrFields = array(
                    array('strName' => 'QR Code', 'strFieldReference' => 'barcode', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Category', 'strFieldReference' => 'categoryname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Item', 'strFieldReference' => 'item_manu_name', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Model', 'strFieldReference' => 'model', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Site', 'strFieldReference' => 'sitename', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Location', 'strFieldReference' => 'locationname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Owner', 'strConversion' => 'user', 'strFieldReference' => 'firstname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Status', 'strFieldReference' => 'statusname', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Action', 'strFieldReference' => 'ticket_action', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Date Default Reported', 'strFieldReference' => 'dt', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Fault Time', 'strConversion' => 'fault_time', 'strFieldReference' => 'dt', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Severity', 'strFieldReference' => 'severity', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Order No', 'strFieldReference' => 'order_no', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0)),
                    array('strName' => 'Job Notes', 'strFieldReference' => 'jobnote', 'arrFooter' => array('booTotal' => false, 'booTotalLabel' => false, 'intColSpan' => 0))
                );

                $this->outputPdfFile(date('d/m/Y Gis') . '.pdf', $arrFields, $resQuery->result_array());
            }
            if ($resQuery->num_rows() > 0) {

                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {

                    $arrItemsData[] = $objRow;
                }

                return array('results' => $arrItemsData);
            } else {

                return array();
            }
        }

        return false;
    }

    public function getTicketData($ticket_id = -1) {
        if ($ticket_id) {

            $this->db->select('*');
            $this->db->from('tickets');
            $this->db->where('id', $ticket_id);
            $query = $this->db->get();

            $ret = $query->row();

            return $ret;
        }
    }

    public function getAllJob($item_id = -1, $intAccountType = '') {
        if ($item_id) {

            $this->db->select('*');
            if ($intAccountType == 'Open Job') {
                $this->db->from('tickets_history');
            } else {
                $this->db->from('tickets');
                $this->db->where('ticket_action', 'Fix');
            }
            $this->db->where('item_id', $item_id);
            $query = $this->db->get();
            $res = $query->result_array();
            return $res;
        }
    }

}

?>
