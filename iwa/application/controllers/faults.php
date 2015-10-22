<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Faults extends MY_Controller {

    public function index() {
        $this->filter();
    }

    public function filter() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "All Fault";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $this->load->model('tickets_model');
        $fullItemsData = $this->tickets_model->getAllFaultItems($this->session->userdata('objSystemUser')->accountid);

        $arrPageData['current_job'] = $fullItemsData['results'];

        $arrPageData['fullItemsData'] = $fullItemsData['results'];
        // load views
        $this->load->view('common/header', $arrPageData);
        //load the correct view
        $this->load->view('faults/faults', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function faulthistory() {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }

   
        
        
// housekeeping
        $arrPageData = array();
         $arrPageData['arrPageParameters']['strSection'] = 'Fault History';
        $arrPageData['arrPageParameters']['strPage'] = "All Fault";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

// set some defaults
        $arrPageData['intCategoryId'] = 0;
        $arrPageData['intLocationId'] = 0;
        $arrPageData['intUserId'] = 0;
        $arrPageData['intSiteId'] = -1;
//
//        //models
        $this->load->model('items_model');
        $this->load->model('itemstatus_model');
        $this->load->model('users_model');
        $this->load->model('categories_model');
        $this->load->model('sites_model');
        $this->load->model('locations_model');
        $this->load->model('accounts_model');
        $this->load->model('suppliers_model');
        $this->load->model('customfields_model');
        $this->load->model('admin_section_model');


//        /* Save column data */
        if ($this->input->post('columns')) {

            $this->users_model->saveColumns(json_encode($this->input->post('columns')));
        }
//
//        $arrPageData['export_uri'] = $this->uri->uri_string();
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);
// For Owner Dropdown List
        $arrPageData['arrOwners'] = $this->users_model->getAllForOwner($this->session->userdata('objSystemUser')->accountid);

        $arrPageData['arrCategories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['arrManufacturers'] = $this->items_model->listManufacturers($this->session->userdata('objSystemUser')->accountid);
//
        $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAll();
        $arrPageData['arrUserColumns'] = $this->users_model->getColumns($this->session->userdata('objSystemUser')->userid);
        $arrPageData['arrUserColumnsFilter'] = $this->users_model->getColumnsFilter($this->session->userdata('objSystemUser')->userid);
        $arrPageData['arrColumns'] = $this->users_model->getAllColumns();

        $arrPageData['arrPatStatus'] = $this->items_model->getAllPatStatus();
        $arrPageData['arrSuppliers'] = $this->suppliers_model->getAll();

        $arrPageData['arrCustomfield'] = $this->customfields_model->getFieldByAccountId($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['arrItemManu'] = $this->admin_section_model->getItem_Manu($arrPageData['arrSessionData']['objSystemUser']->accountid);
//
        $arrPageData['arrManufaturer'] = $this->admin_section_model->getManufacturer($arrPageData['arrSessionData']['objSystemUser']->accountid);
        $arrPageData['arrCondition'] = $this->items_model->get_condition();

        $mixItemsData = $this->items_model->getAll($this->session->userdata('objSystemUser')->accountid, $arrPagination, $arrFilters, $arrOrder);

        if (isset($mixItemsData['results'])) {
            /* Add custom fields to each item */
            foreach ($mixItemsData['results'] as $item) {
                $custom_fields = $this->customfields_model->getCustomFieldsByItem($item->itemid);

                if ($custom_fields) {

                    foreach ($custom_fields as $custom_field) {
                        $item->{$custom_field->field_name} = $custom_field->content;
                    }
                }
            }
        }




//        $this->load->model('tickets_model');
//        $fullItemsData = $this->tickets_model->getAllFixItems($this->session->userdata('objSystemUser')->accountid);




        $arrPageData['fixed_job'] = $fullItemsData['results'];
        $this->load->view('common/header', $arrPageData);
        $this->load->view('faults/fault_history', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    function ajaxfetchItem() {
        $intItemId = $this->input->post('id');
        $intAccountId = $this->input->post('account_id');
        if ($this->input->post('type')) {
            $intAccountType = $this->input->post('type');
        }
        if (strpos($intItemId, '_') != FALSE) {
            $explodeArr = explode("_", $intItemId);
            $item_id = $explodeArr[1];
        } else {
            $item_id = $this->input->post('id');
        }
        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('tickets_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Change ownership";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        //echo "<pre>"; print_R($explodeArr); die;
        //echo $explodeArr[1]."===============".$intAccountId;
        $fullItemsData = $this->items_model->basicGetOneWithTicket($item_id, $intAccountId, $intAccountType);
        $user = $this->users_model->getOne($fullItemsData[0]->user_id, $intAccountId);
        $loggedName = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;

//        $all_job_notes = $this->tickets_model->getAllJob($item_id,$intAccountType);      
        $all_job_notes = $this->tickets_model->getAllJobData($fullItemsData[0]->ticket_id);
//   $jobnote = '';
        $allJob = array();
        $actionData = '';
//            $allJob [] = $history['jobnote'];
//            $jobNoteDate [] = date('d/m/Y', strtotime($history['date']));

               foreach ($all_job_notes as $history) {
//            $allJob [] = $history['jobnote'];
//            $jobNoteDate [] = date('d/m/Y', strtotime($history['date']));
    
              if ($history['fix_code'] != '') {
        $code= '<div class="col-md-4">&nbsp</div><div class="col-md-8"> Fix Reason Code [ '.$history['fix_code'].' ]</div>';
    }else{
        $code = '<div class="col-md-4">&nbsp</div><div class="col-md-8"> Reason Code [ '.$history['reason_code'].' ]</div>';
    }
            $actionData .= '<div class="col-md-4">'.date('d/m/Y', strtotime($history['date'])).'</div>'
                    .$code.'
                    <div class="col-md-8"> '.  $history['action'] .' By ['.$history['firstname'] . ' ' . $history['lastname'].']</div>
                    <div class="col-md-4">&nbsp</div>
                    <div class="col-md-8"> '.$history['jobnote'].'</div><div class="col-md-12">&nbsp</div>';
       
           //            echo $jobnote;
            
            //            echo $jobnote;
        }
//        echo $actionData ;die;
        $all_notes = implode(',', $allJob);
        $notesDate = implode(',', $jobNoteDate);
//        $all_photos=  implode(',', $photoid);
        $fullItemsData[0]->allNotes = $all_notes;
        $fullItemsData[0]->notesDate = $notesDate;
        $fullItemsData[0]->actionData = $actionData;
//        $fullItemsData[0]->allPhoto=$all_photos;
        $fullItemsData[0]->loggedBy = $loggedName;
        $fullItemsData[0]->loggedByDate = date('d/m/Y', strtotime($fullItemsData[0]->dt));

        $array = json_encode($fullItemsData[0]);
        echo $array;
        die;
    }

    function ajaxfetchItemForSingleItem() {
        $intItemId = $this->input->post('id');
        $ticket_id = $this->input->post('ticket_id');
        $intAccountId = $this->input->post('account_id');
        if ($this->input->post('type')) {
            $intAccountType = $this->input->post('type');
        }
        if (strpos($intItemId, '_') != FALSE) {
            $explodeArr = explode("_", $intItemId);
            $item_id = $explodeArr[1];
        } else {
            $item_id = $this->input->post('id');
        }
        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('tickets_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Change ownership";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        //echo "<pre>"; print_R($explodeArr); die;
        //echo $explodeArr[1]."===============".$intAccountId;
        $fullItemsData = $this->items_model->basicGetOneWithTicket($item_id, $intAccountId, $intAccountType, $ticket_id);
        $user = $this->users_model->getOne($fullItemsData[0]->user_id, $intAccountId);
        $loggedName = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;

//        $all_job_notes = $this->tickets_model->getAllJob($item_id,$intAccountType);      
        $all_job_notes = $this->tickets_model->getAllJobData($fullItemsData[0]->ticket_id);
//   $jobnote = '';
        $allJob = array();
        $actionData = '';
//        var_dump($all_job_notes);die;
        foreach ($all_job_notes as $history) {
//            $allJob [] = $history['jobnote'];
//            $jobNoteDate [] = date('d/m/Y', strtotime($history['date']));
    if ($history['fix_code'] != '') {
        $code= '<div class="col-md-4">&nbsp</div><div class="col-md-8"> Fix Reason Code [ '.$history['fix_code'].' ]</div>';
    }else{
        $code = '<div class="col-md-4">&nbsp</div><div class="col-md-8"> Reason Code [ '.$history['reason_code'].' ]</div>';
    }
            $actionData .= '<div class="col-md-4">'.date('d/m/Y', strtotime($history['date'])).'</div>'
                    .$code.'
                    <div class="col-md-8"> '.  $history['action'] .' By ['.$history['firstname'] . ' ' . $history['lastname'].']</div>
                    <div class="col-md-4">&nbsp</div>
                    <div class="col-md-8"> '.$history['jobnote'].'</div><div class="col-md-12">&nbsp</div>';
       
           //            echo $jobnote;
        }
        
        
        
        $all_notes = implode(',', $allJob);
        $notesDate = implode(',', $jobNoteDate);
//        $all_photos=  implode(',', $photoid);
        $fullItemsData[0]->allNotes = $all_notes;
        $fullItemsData[0]->notesDate = $notesDate;
        $fullItemsData[0]->actionData = $actionData;
//        $fullItemsData[0]->allPhoto=$all_photos;
        $fullItemsData[0]->loggedBy = $loggedName;
        $fullItemsData[0]->loggedByDate = date('d/m/Y', strtotime($fullItemsData[0]->dt));

        $array = json_encode($fullItemsData[0]);
        echo $array;
        die;
    }

    ##########################################################################
    // get pdf for fault history with all incident 

    function getPdfForFaultHistory($item_id = FALSE, $intAccountId = FALSE) {

        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('tickets_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Change ownership";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();


        $fullItemsData = $this->items_model->basicGetOneWithTicket($item_id, $intAccountId, "Fix");
        $user = $this->users_model->getOne($fullItemsData[0]->user_id, $intAccountId);
        $loggedName = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        $all_job_notes = $this->tickets_model->getAllJobData($fullItemsData[0]->ticket_id);
        $previousHistory = $this->tickets_model->getFaultHistoryByItem($fullItemsData[0]->itemid);
        $allJob = array();
        $actionData = '';

        $all_notes = implode(',', $allJob);
        $notesDate = implode(',', $jobNoteDate);
        $fullItemsData[0]->allNotes = $all_notes;
        $fullItemsData[0]->notesDate = $notesDate;
        $fullItemsData[0]->actionData = $actionData;
//        $fullItemsData[0]->allPhoto=$all_photos;
        $fullItemsData[0]->loggedBy = $loggedName;
        $fullItemsData[0]->loggedByDate = date('d/m/Y', strtotime($fullItemsData[0]->dt));





        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head></head>";

        $strHtml .= "<body>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>Incident Report</h1>";
        $strHtml .= "</td><td class=\"right\">";

        $logo = 'logo.png';
        if (isset($this->session->userdata['theme_design']->logo)) {
            $logo = $this->session->userdata['theme_design']->logo;
        }
        $strHtml .= "<img alt=\"ictracker\" src='http://" . $_SERVER['HTTP_HOST'] . "/youaudit/iwa/brochure/logo/logo.png'>";

        $strHtml .= "</td></tr></table>";

        $strHtml .= "<div>&nbsp;</div>";

        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";

        $strHtml .= "<th style='padding:10px;'>QR Code</th><th>Manufacturer</th><th>Model</th><th>Category</th><th>Item</th><th>Location</th><th>Site</th><th>Owner</th><th>Severity</th><th>Order No</th><th>Fault Fixed By</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";

        $strHtml .= "<tr>";

        $strHtml .= "<td style='padding:10px;'>" . $fullItemsData[0]->barcode . "</td><td>" . $fullItemsData[0]->manufacturer . "</td><td>" . $fullItemsData[0]->model . "</td><td>" . $fullItemsData[0]->categoryname . "</td><td>" . $fullItemsData[0]->item_manu_name . "</td><td>" . $fullItemsData[0]->locationname . "</td><td>" . $fullItemsData[0]->sitename . "</td><td>" . $fullItemsData[0]->userfirstname . " " . $fullItemsData[0]->userlastname . "</td><td>" . $fullItemsData[0]->severity . "</td><td>" . $fullItemsData[0]->order_no . "</td><td>" . $fullItemsData[0]->loggedBy . "</td>";


        $strHtml .= "</tr>";

        $strHtml .= "</tbody></table>";

        //#############################################################
//        safety check data

        $strHtml .= "<div>&nbsp;</div>";

        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";
        if ($fullItemsData[0]->test_compliance_name != '') {

            $safetyCheck = '< ' . $fullItemsData[0]->test_compliance_name . ' >';
        } else {
            $safetyCheck = '< SAFETY CHECKS DUE >';
        }
        $strHtml .= "<th style='padding:10px;width:800px'>Safety Check " . $safetyCheck . "</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($previousHistory as $safetyData) {
            $strHtml .= "<tr>";

            $strHtml .= "<td style='padding:5px;'>" . $safetyData['fault_type'] . "</td><td>" . $safetyData['severity'] . "</td>";


            $strHtml .= "</tr>";
        }

        $strHtml .= "</tbody></table>";
        //#############################################################

        $strHtml .= "<div>&nbsp;</div>";
        $strHtml .= "<div>&nbsp;</div>";

        $strHtml .= "<div><h1 style='color:#00aeef;'>Incident Timeline</h1></div>";
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";

        $strHtml .= "<th style='padding:10px;'>Date</th><th>Time</th><th>Event</th><th>Logged By</th><th>Code</th><th>Notes</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($all_job_notes as $val) {
            $strHtml .= "<tr>";

            $strHtml .= "<td style='padding:10px;'>" . date('Y/m/d', strtotime($val['date'])) . "</td><td>" . date('h:i:s', strtotime($val['date'])) . "</td><td>" . $val['action'] . "</td><td>" . $val['firstname'] . " " . $val['lastname'] . "</td>";
            $strHtml .= "<td>";
            if ($val['fix_code'] != "") {

                $strHtml .= $val['fix_code'];
            } else {
                $strHtml .= $val['reason_code'];
            }
            $strHtml .= "</td>";
            $strHtml .= "<td>" . $val['jobnote'] . "</td>";

            $strHtml .= "</tr>";
        }
        $strHtml .= "</tbody></table>";
        //#############################################

        $strHtml .= "<div><h1 style='color:#00aeef;'>Photo Images</h1></div>";

        //##############################################
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<tbody>";
        $strHtml .= "<tr>";
        $photoIds = explode(",", $fullItemsData[0]->photoid);
        $strHtml .= "<td style='padding:10px;'><div style='width:1000px;height:200px;'>";
        foreach ($photoIds as $photo_id) {
            $faultPhoto = $this->getPhotoPath($photo_id);
            $strHtml .= "<img style='margin-left:7px;' width='300' height='200' alt=\"ictracker\" src='" . base_url($faultPhoto) . "'>";
        }
        $strHtml .= "</div></td></tr>";
        $strHtml .= "</tbody></table>";
        //#############################################
        $strHtml .= "<div>&nbsp;</div>";
        $strHtml .= "<div>&nbsp;</div>";



        $strHtml .= "<div><h1 style='color:#00aeef;'>History - Previous Faults</h1></div>";
        //##############################################
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";

        $strHtml .= "<th style='padding:10px;'>Date</th><th>Time</th><th>Type Of Fault</th><th>Code</th><th>Logged By</th><th>Fix Time</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($previousHistory as $val1) {

            $date2 = date('d-m-Y', strtotime($val1['fix_date']));
            $date1 = date('d-m-Y H:i:s', strtotime($val1['date']));


            $diff = abs(strtotime($date2) - strtotime($date1));

            $days = floor($diff / 3600 / 24);
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

            $total_time = $months . ' month ' . $days . ' days ';


            $strHtml .= "<tr>";

            $strHtml .= "<td style='padding:10px;'>" . date('Y/m/d', strtotime($val1['date'])) . "</td><td>" . date('h:i:s', strtotime($val1['date'])) . "</td><td>" . $val1['fault_type'] . "</td><td>" . $val1['fix_code'] . "</td><td>" . $val['firstname'] . " " . $val['lastname'] . "</td><td>" . $total_time . "</td>";
            $strHtml .= "<td>";


            $strHtml .= "</tr>";
        }
        $strHtml .= "</tbody></table>";
        //############################################# 



        $strHtml .= "</body></html>";

//      echo $strHtml;die;  
        $this->load->library('Mpdf');
        $mpdf = new Pdf('en-GB', 'A4');
        // $mpdf->setFooter('{PAGENO} of {nb}');
        $mpdf->WriteHTML($strHtml);
        $mpdf->Output("YouAudit_" . date('Ymd_His') . ".pdf", "D");
    }

    ##########################################################################

    public function raiseTicket($intId = -1) {


        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {

            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Raise a Support Ticket";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $this->load->model('items_model');
        $this->load->model('accounts_model');
        $this->load->model('tickets_model');
        $this->load->model('photos_model');

        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        $booSuccess = false;

        $arrPageData['strMessageTitle'] = "";
        $arrPageData['strMessageBody'] = "";
        $intId = $this->input->post('report_item_id');
        if ($intId > 0) {
            $mixItemsData = $this->items_model->basicGetOne($intId, $this->session->userdata('objSystemUser')->accountid);
            //                    Check Items has Report Faults ?
            $reportFault = $this->tickets_model->checkReportFault($intId);

            if (!$reportFault) {
                if ($mixItemsData) {
                    $arrPageData['objItem'] = $mixItemsData[0];
                    $booSuccess = true;

                    /* if category has support user ID, get user email */

                    $user_data = $this->users_model->getOneWithoutAccount($mixItemsData[0]->category_user_id);
                    $category_support = $mixItemsData[0]->support_emails;



                    $arrPageData['strMake'] = $mixItemsData[0]->manufacturer;
                    $arrPageData['strModel'] = $mixItemsData[0]->model;
                    $arrPageData['strSerialNumber'] = $mixItemsData[0]->serial_number;
                    $arrPageData['strBarcode'] = $mixItemsData[0]->barcode;

                    $arrPageData['intItemId'] = $intId;

                    // is there a submission?
                    if ($this->input->post()) {

                        /* Priority Level array */
                        $priorities = array(1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical');
                        $priority_level = $this->input->post('ticket_priority');
                        $data = array(
                            'item_id' => $this->input->post('report_item_id'),
                            'user_id' => $this->input->post("userid"),
                            'severity' => $this->input->post("severity"),
                            'order_no' => $this->input->post("order_no"),
                            'jobnote' => $this->input->post("job_notes"),
                            'date' => date("Y-m-d H:i:s"),
                            'ticket_action' => "Open Job",
                            'status' => $this->input->post("itemstatusname"),
                        );




                        $strZenDeskDataCapture = "";
                        $strZenDeskDataCapture .= "#requester " . $this->session->userdata('objSystemUser')->username . " \r\n";
                        $strZenDeskDataCapture .= "#tags iworkaudit " . $mixItemsData[0]->barcode . " \r\n";
                        $strZenDeskDataCapture .= "#problem \r\n";
                        $strZenDeskDataCapture .= " -----------------------------------------------------\r\n";

                        $strMessageBodyItemData = "\r\n -----------------------------------------------------\r\n";
                        $strMessageBodyItemData .= "ACCOUNT NAME: " . $this->session->userdata('objSystemUser')->accountname . "\r\n";
                        $strMessageBodyItemData .= "SENDER: " . $this->session->userdata('objSystemUser')->firstname . " " . $this->session->userdata('objSystemUser')->lastname . "\r\n";

                        $strMessageBodyItemData .= "MAKE & MODEL: " . $mixItemsData[0]->manufacturer . " " . $mixItemsData[0]->model . "\r\n";
                        $strMessageBodyItemData .= "BARCODE: " . $mixItemsData[0]->barcode . "\r\n";
                        $strMessageBodyItemData .= "SERIAL NUMBER: " . $mixItemsData[0]->serial_number . "\r\n";
                        $strMessageBodyItemData .= "WARRANTY DATE: " . $mixItemsData[0]->warranty_date . "\r\n";
                        $strMessageBodyItemData .= "LOCATION: " . $mixItemsData[0]->locationname . "\r\n";
                        $strMessageBodyItemData .= "PRIORITY LEVEL: " . $this->input->post("severity") . "\r\n";


                        //okay try to build the email
                        $this->load->library('email');
                        $this->email->from("tickets@iworkaudit.com", "iWork Audit Ticket");
                        $strSupportAddress = $this->accounts_model->getSupportEmailAddress($this->session->userdata('objSystemUser')->accountid);

                        /* If category user is set */
                        if ($category_support != '' || $category_support != NULL) {
                            $this->email->to($category_support);
                        } else {

                            $this->email->to($strSupportAddress);
                        }
//                    echo $strSupportAddress;
                        $this->email->subject($mixItemsData[0]->manufacturer . " " . $mixItemsData[0]->model . ":" . $this->input->post('message_title'));

                        $strEmailContent = "";

                        if (strpos($strSupportAddress, 'zendesk.com')) {
                            $strEmailContent = $strZenDeskDataCapture;
                        }

                        $strEmailContent .= $this->input->post('message_body') . $strMessageBodyItemData;

                        $this->email->message($strEmailContent);
//                        if ($this->email->send()) {

                        $last_id = $this->tickets_model->insertTicket($data);

                        if ($last_id > 0) {

                            if (array_key_exists('photo_file_1', $_FILES) && ($_FILES['photo_file_1']['size'] > 0)) {
                                $arrConfig['upload_path'] = './uploads/';
                                $arrConfig['allowed_types'] = 'gif|jpg|png';
                                $arrConfig['max_size'] = '0';
                                $arrConfig['max_width'] = '0';
                                $arrConfig['max_height'] = '0';

// load helper
                                $this->load->library('upload', $arrConfig);

// photo upload done

                                for ($i = 1; $i <= count($_FILES); $i++) {
                                    if ($this->upload->do_upload('photo_file_' . $i)) {
                                        $strPhotoTitle = "Item Picture";

                                        $intPhotoId[] = $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "item/default");

//                                            $arrPageData['intPhotoId'] = $intPhotoId;
                                    } else {


                                        $intPhotoError = 1;
                                    }
                                }

                                $photoid = implode(',', $intPhotoId);

                                $this->tickets_model->setPhoto($last_id, $photoid);
                            }
                        }
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The ticket was successfully sent')));
                        redirect('/items/view/' . $intId, 'refresh');
//                        } else {
//                            $arrPageData['arrErrorMessages'][] = "Unable to send ticket.";
//                        }



                        $arrPageData['strMessageTitle'] = $this->input->post('message_title');
                        $arrPageData['strMessageBody'] = $this->input->post('message_body');
                    }
                }
            } else {
                $booSuccess = FALSE;
            }
            //if mixitemsdata
        }
        //if intItems

        if (!$booSuccess) {
            $arrPageData['arrErrorMessages'][] = "Item Not Found.";
            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We couldn't find that item.";
        }

        // load views
        $this->load->view('common/header', $arrPageData);
        if ($booSuccess) {
            //load the correct view

            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The ticket was successfully sent')));
            redirect('/items/view/' . $intId, 'refresh');
        } else {
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Unable to send ticket')));
            redirect('/items/view/' . $intId, 'refresh');
        }
        $this->load->view('common/footer', $arrPageData);
    }

// End of function

    function fixfault() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/raiseticket/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Raise a Support Ticket";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $item_manu = $this->input->post('item_manu');
        $manufacturer = $this->input->post('manufacturer');
        $serial_number = $this->input->post('serial_number');
        $categoryname = $this->input->post('categoryname');

        $locationname = $this->input->post('locationname');
        $action = $this->input->post('action');
        $status = $this->input->post('status');


        $job_notes = $this->input->post('job_notes');

        $data = array(
            "ticket_action" => $action,
            "jobnote" => $job_notes,
        );

        $mode = $this->input->post('mode');
        $this->load->model('items_model');
        $this->load->model('tickets_model');
        $this->load->model('photos_model');


        if ($mode == "fixFault") {
            if ($this->input->post()) {
                $fix_item_id = $this->input->post('fix_item_id');
                if (strpos($fix_item_id, '_')) {
                    $item_iId = explode("_", $fix_item_id);
                    $item_iId = $item_iId[1];
                } else {
                    $item_iId = $fix_item_id;
                }

                $data = array(
                    'fix_item_id' => $item_iId,
                    'job_notes' => $this->input->post('job_notes'),
                    'status' => 1,
                    'fix_code' => $this->input->post('fix_code'),
                    'ticket_id' => $this->input->post('fix_ticket_id'),
                    'fix_date' => date("Y-m-d H:i:s"),
                );

                $result = $this->tickets_model->fixStatus($data);


                if ($result) {

                    if (array_key_exists('photo_file_1', $_FILES) && ($_FILES['photo_file_1']['size'] > 0)) {
                        $arrConfig['upload_path'] = './uploads/';
                        $arrConfig['allowed_types'] = 'gif|jpg|png';
                        $arrConfig['max_size'] = '0';
                        $arrConfig['max_width'] = '0';
                        $arrConfig['max_height'] = '0';

// load helper
                        $this->load->library('upload', $arrConfig);

// photo upload done

                        for ($i = 1; $i <= count($_FILES); $i++) {
                            if ($this->upload->do_upload('photo_file_' . $i)) {
                                $strPhotoTitle = "Item Picture";

                                $intPhotoId[] = $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "item/default");

//                                            $arrPageData['intPhotoId'] = $intPhotoId;
                            } else {


                                $intPhotoError = 1;
                            }
                        }

                        $photoid = implode(',', $intPhotoId);

                        $this->tickets_model->setPhoto($data['ticket_id'], $photoid);
                    }
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Fault fix successfully')));
                    if ($this->input->post('view_fix')) {
                        redirect('/items/view/' . $data['fix_item_id'], 'refresh');
                    } else {
                        redirect('/faults/filter', 'refresh');
                    }
                }
            } else {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Fault Not fix ')));
                if ($this->input->post('view_fix')) {
                    redirect('/items/view/' . $data['fix_item_id'], 'refresh');
                } else {
                    redirect('/faults/filter', 'refresh');
                }
            }
        } else if ($mode == "updateFault") {
            $update_item_id = $this->input->post('update_item_id');
            $item_iId = explode("_", $update_item_id);
            if ($this->input->post()) {
                $data = array(
                    'reason_code' => $this->input->post('reason_code'),
                    'jobnote' => $this->input->post('job_notes'),
                    'status' => $this->input->post('status'),
                    'tickets_action' => 'Open Job',
                    'fix_item_id' => $item_iId[1],
                );





                $ticket_id = $this->input->post("update_ticket_id");
                $result = $this->tickets_model->updateTicket($ticket_id, $data);
                if ($result) {
                    $historyData = array(
                        'reason_code' => $this->input->post('reason_code'),
                        'jobnote' => $this->input->post('job_notes'),
                        'status' => $this->input->post('status'),
                        'ticket_action' => 'Open Job',
                        'item_id' => $item_iId[1],
                        'date' => date("Y-m-d H:i:s"),
                    );
                    $historyData['ticket_id'] = $ticket_id;
                    $historyData['action'] = "Update Incident";
                    $historyData["user_id"] = $this->session->userdata('objSystemUser')->userid;
                    $result1 = $this->tickets_model->addUpdateHistory($historyData);
                    if ($result1) {
                        if (array_key_exists('photo_file_1', $_FILES) && ($_FILES['photo_file_1']['size'] > 0)) {
                            $arrConfig['upload_path'] = './uploads/';
                            $arrConfig['allowed_types'] = 'gif|jpg|png';
                            $arrConfig['max_size'] = '0';
                            $arrConfig['max_width'] = '0';
                            $arrConfig['max_height'] = '0';

// load helper
                            $this->load->library('upload', $arrConfig);

// photo upload done

                            for ($i = 1; $i <= count($_FILES); $i++) {
                                if ($this->upload->do_upload('photo_file_' . $i)) {
                                    $strPhotoTitle = "Item Picture";

                                    $intPhotoId[] = $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "item/default");

//                                            $arrPageData['intPhotoId'] = $intPhotoId;
                                } else {


                                    $intPhotoError = 1;
                                }
                            }

                            $photoid = implode(',', $intPhotoId);


                            $this->tickets_model->setPhoto($historyData['ticket_id'], $photoid);
                        }

                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Fault Update successfully')));
                        redirect('/faults/filter', 'refresh');
                    }
                } else {
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Fault could not updated ')));
                    redirect('/faults/filter', 'refresh');
                }
            } else {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Fault could not updated ')));
                redirect('/faults/filter', 'refresh');
            }
        } else if ($mode == "reportFault") {
            $report_item_id = $this->input->post('report_item_id');
            $iId = explode("_", $report_item_id);
            $iteam_id = $iId[1];
            $id = $this->input->post("report_ticket_id");
            $data["order_no"] = $this->input->post('order_no');
//            $data["order_no"] = $this->input->post("order_no");

            if ($id > 0) {
                // Update Ticket
                $this->tickets_model->updateTicket($id, $data);
            } else {
                // Insert Ticket
                $data["item_id"] = $iteam_id;
                $data["user_id"] = $this->session->userdata('objSystemUser')->userid;
                $data["date"] = date("Y-m-d");
                $this->tickets_model->insertTicket($data);
            }
        }
    }

//
    //
        function reportFault() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/raiseticket/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Raise a Support Ticket";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if ($this->input->post()) {
            $this->load->model('tickets_model');
            $data = array(
                'item_id' => $this->input->post('report_item_id'),
                'user_id' => $this->input->post("userid"),
                'severity' => $this->input->post("severity"),
                'order_no' => $this->input->post("order_no"),
                'jobnote' => $this->input->post("job_notes"),
                'date' => date("Y-m-d H:i:s"),
                'ticket_action' => "Open Job",
            );

            $result = $this->tickets_model->insertTicket($data);
            if ($result) {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Report fault successfully')));
                redirect('/items/view/' . $data['item_id'], 'refresh');
            } else {
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Report fault is not  successfully')));
                redirect('/items/view/' . $data['item_id'], 'refresh');
            }
        }
    }

    public function getPdf($fault_id, $pdfName = '') {
        if ($fault_id) {
            $this->load->model('tickets_model');
            $result = $this->tickets_model->getPdf($fault_id, $pdfName);
            echo "<pre>";

            echo "</pre>";
            die("here");
        }
    }

    public function getAllFaultPdf() {

        $this->load->model('tickets_model');
        $result = $this->tickets_model->getAllFaultPdf();
        echo "<pre>";

        echo "</pre>";
        die("here");
    }

    //     Export PDF For Faults
    public function exportPDFForFaults($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "All Fault";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('tickets_model');
        $fullItemsData = $this->tickets_model->getAllFaultItems($this->session->userdata('objSystemUser')->accountid, $type);



        echo "<pre>";
        var_dump($fullItemsData);
        echo "</pre>";
        die("here");
    }

    public function exportPDFForFixFaults($type = '') {

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "All Fault";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('tickets_model');
        $fullItemsData = $this->tickets_model->getAllFixItems($this->session->userdata('objSystemUser')->accountid, $type);


        echo "<pre>";
        var_dump($fullItemsData);
        echo "</pre>";
        die("here");
    }

    public function editMultipleFaults() {
        $this->load->model('tickets_model');
        if ($this->input->post()) {

            $result = $this->tickets_model->editMultiple_Faults();

            if ($result) {
                $this->session->set_flashdata('success', 'Fault Updated Successfully');
                redirect("faults/", "refresh");
            } else {
                $this->session->set_flashdata('error', 'Fault Could Not Be Updated.');
                redirect("faults/", "refresh");
            }
        }
    }

    public function getUserData($user_id) {

        $faultBy = $this->db->select('firstname,lastname')
                ->from('users')
                ->where('id', $user_id)
                ->get()
                ->result_array();

        return $faultBy[0]['firstname'] . ' ' . $faultBy[0]['lastname'];
    }

    public function resolveMultipleIncidents() {

//     var_dump($_POST);die;
        $tickets_Id = explode(',', $this->input->post('ticket_id'));
        $ticketData = array("user_id" => $this->session->userdata('objSystemUser')->userid,
            "fix_code" => $this->input->post('multiple_fix_code'),
            "status" => 1,
            "jobnote" => $this->input->post('multiple_job_note'),
            "ticket_action" => "Fix",
            "fix_date" => date("Y-m-d H:i:s")
        );

        $this->load->model('tickets_model');
        $response = $this->tickets_model->fixedMultipleFault($tickets_Id, $ticketData);
        if ($response) {
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Fault fix successfully')));
            redirect('/faults/filter');
        }
    }

    public function getPhotoPath($photo_id) {

        $res = $this->db->select('path')->from('photos')->where('id', $photo_id)->get();
        if ($res->num_rows() > 0) {
            $photos = $res->result_array();
            return $photos[0]['path'];
        } else {
            return FALSE;
        }
    }

    ##########################################################################
    // get pdf for fault history with all incident 

    public function getPdfForOpenJob($item_id = FALSE, $intAccountId = FALSE, $ticket_id = FALSE) {

        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('tickets_model');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Change ownership";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();


        $fullItemsData = $this->items_model->openGetOneWithTicket($item_id, $intAccountId, $ticket_id);
        $user = $this->users_model->getOne($fullItemsData[0]->user_id, $intAccountId);
        $loggedName = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        $all_job_notes = $this->tickets_model->getOpenJobData($ticket_id);
//        var_dump($all_job_notes);die;
        $previousHistory = $this->tickets_model->getFaultHistoryByItem($fullItemsData[0]->itemid);
        $allJob = array();
        $actionData = '';

        $all_notes = implode(',', $allJob);
        $notesDate = implode(',', $jobNoteDate);
        $fullItemsData[0]->allNotes = $all_notes;
        $fullItemsData[0]->notesDate = $notesDate;
        $fullItemsData[0]->actionData = $actionData;
//        $fullItemsData[0]->allPhoto=$all_photos;
        $fullItemsData[0]->loggedBy = $loggedName;
        $fullItemsData[0]->loggedByDate = date('d/m/Y', strtotime($fullItemsData[0]->dt));





        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head></head>";

        $strHtml .= "<body>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>Incident Report</h1>";
        $strHtml .= "</td><td class=\"right\">";

        $logo = 'logo.png';
        if (isset($this->session->userdata['theme_design']->logo)) {
            $logo = $this->session->userdata['theme_design']->logo;
        }
        $strHtml .= "<img alt=\"ictracker\" src='http://" . $_SERVER['HTTP_HOST'] . "/youaudit/iwa/brochure/logo/logo.png'>";

        $strHtml .= "</td></tr></table>";

        $strHtml .= "<div>&nbsp;</div>";

        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";

        $strHtml .= "<th style='padding:10px;'>QR Code</th><th>Manufacturer</th><th>Model</th><th>Category</th><th>Item</th><th>Location</th><th>Site</th><th>Owner</th><th>Severity</th><th>Order No</th><th>Fault Logged By</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";

        $strHtml .= "<tr>";

        $strHtml .= "<td style='padding:10px;'>" . $fullItemsData[0]->barcode . "</td><td>" . $fullItemsData[0]->manufacturer . "</td><td>" . $fullItemsData[0]->model . "</td><td>" . $fullItemsData[0]->categoryname . "</td><td>" . $fullItemsData[0]->item_manu_name . "</td><td>" . $fullItemsData[0]->locationname . "</td><td>" . $fullItemsData[0]->sitename . "</td><td>" . $fullItemsData[0]->userfirstname . " " . $fullItemsData[0]->userlastname . "</td><td>" . $fullItemsData[0]->severity . "</td><td>" . $fullItemsData[0]->order_no . "</td><td>" . $fullItemsData[0]->loggedBy . "</td>";


        $strHtml .= "</tr>";

        $strHtml .= "</tbody></table>";

        //#############################################################
//        safety check data

        $strHtml .= "<div>&nbsp;</div>";

        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";
        if ($fullItemsData[0]->test_compliance_name != '') {

            $safetyCheck = '< ' . $fullItemsData[0]->test_compliance_name . ' >';
        } else {
            $safetyCheck = '< SAFETY CHECKS DUE >';
        }
        $strHtml .= "<th style='padding:10px;width:800px;'>Safety Check " . $safetyCheck . "</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($previousHistory as $safetyData) {
            $strHtml .= "<tr>";

            $strHtml .= "<td style='padding:5px;'>" . $safetyData['fault_type'] . "</td><td>" . $safetyData['severity'] . "</td>";


            $strHtml .= "</tr>";
        }

        $strHtml .= "</tbody></table>";
        //#############################################################



        $strHtml .= "<div>&nbsp;</div>";
        $strHtml .= "<div>&nbsp;</div>";

        $strHtml .= "<div><h1 style='color:#00aeef;'>Incident Timeline</h1></div>";
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";

        $strHtml .= "<th style='padding:10px;'>Date</th><th>Time</th><th>Event</th><th>Logged By</th><th>Code</th><th>Notes</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($all_job_notes as $val) {
            $strHtml .= "<tr>";

            $strHtml .= "<td style='padding:10px;'>" . date('Y/m/d', strtotime($val['date'])) . "</td><td>" . date('h:i:s', strtotime($val['date'])) . "</td><td>" . $val['action'] . "</td><td>" . $val['firstname'] . " " . $val['lastname'] . "</td>";
            $strHtml .= "<td>";
            if ($val['fix_code'] != "") {

                $strHtml .= $val['fix_code'];
            } else {
                $strHtml .= $val['reason_code'];
            }
            $strHtml .= "</td>";
            $strHtml .= "<td>" . $val['jobnote'] . "</td>";

            $strHtml .= "</tr>";
        }
        $strHtml .= "</tbody></table>";
        //#############################################

        $strHtml .= "<div><h1 style='color:#00aeef;'>Photo Images</h1></div>";

        //##############################################
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<tbody>";
        $strHtml .= "<tr>";
        $photoIds = explode(",", $fullItemsData[0]->photoid);
        $strHtml .= "<td style='padding:10px;'><div style='width:1000px;height:200px;'>";
        foreach ($photoIds as $photo_id) {
            $faultPhoto = $this->getPhotoPath($photo_id);
            $strHtml .= "<img style='margin-left:7px;' width='300' height='200' alt=\"ictracker\" src='" . base_url($faultPhoto) . "'>";
        }
        $strHtml .= "</div></td></tr>";
        $strHtml .= "</tbody></table>";
        //#############################################
        $strHtml .= "<div>&nbsp;</div>";
        $strHtml .= "<div>&nbsp;</div>";



        $strHtml .= "<div><h1 style='color:#00aeef;'>History - Previous Faults</h1></div>";
        //##############################################
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr style='background-color:#00AEEF;color:white;'>";

        $strHtml .= "<th style='padding:10px;'>Date</th><th>Time</th><th>Type Of Fault</th><th>Code</th><th>Logged By</th><th>Fix Time</th>";

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($previousHistory as $val1) {

            $date2 = date('d-m-Y', strtotime($val1['fix_date']));
            $date1 = date('d-m-Y H:i:s', strtotime($val1['date']));

            $diff = abs(strtotime($date2) - strtotime($date1));

            $days = floor($diff / 3600 / 24);
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

            $total_time = $months . ' month ' . $days . ' days ';


            $strHtml .= "<tr>";

            $strHtml .= "<td style='padding:10px;'>" . date('Y/m/d', strtotime($val1['date'])) . "</td><td>" . date('h:i:s', strtotime($val1['date'])) . "</td><td>" . $val1['fault_type'] . "</td><td>" . $val1['fix_code'] . "</td><td>" . $val['firstname'] . " " . $val['lastname'] . "</td><td>" . $total_time . "</td>";
            $strHtml .= "<td>";


            $strHtml .= "</tr>";
        }
        $strHtml .= "</tbody></table>";
        //############################################# 



        $strHtml .= "</body></html>";

//      echo $strHtml;die;  
        $this->load->library('Mpdf');
        $mpdf = new Pdf('en-GB', 'A4');
        // $mpdf->setFooter('{PAGENO} of {nb}');
        $mpdf->WriteHTML($strHtml);
        $mpdf->Output("YouAudit_" . date('Ymd_His') . ".pdf", "D");
    }

    ##########################################################################
    // method for ajax calling on fault hiistory tab.....
    // get archived items 
    public function faultHisyoryAjaxData() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Filter Items";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
//models
        $this->load->model('items_model');
        $this->load->model('customfields_model');
        $this->load->model('users_model');
        $this->load->model('tickets_model');
        $sLimit = "";
        $lenght = 20;
        $str_point = 0;
        $col_sort = array("items.barcode", "", "categories.name", "items.item_manu", "items.manufacturer", "items.model", "items.quantity", "sites.name", "locations.name", "owner.owner_name", "suppliers.supplier_name", "itemstatus.name", "item_condition.condition", "", "items.serial_number", "", "items.purchase_date", "items.warranty_date", "items.replace_date", "items.value", "items.current_value", "", "", "", "", "", "", "", "", "tickets.date", "", "tickets.severity", "tickets.fix_date", "tickets.order_no", "", "users.firstname", "users.firstname", "tickets.fix_code", "");


        $query = "SELECT items.current_value, items.serial_number,items.purchase_date,items.quantity,items.warranty_date,items.replace_date,item_condition.condition AS condition_name,suppliers.supplier_name,`items`.`id` AS itemid, `items`.`item_manu`, `items`.`manufacturer`, `items`.`account_id`, items.value,`items`.`model`, `items`.`barcode`, `items`.`site`, photos2.path AS itemphotopath,`owner`.`owner_name`, `item_manu`.`item_manu_name`, `categories`.`name` AS categoryname, `locations`.`name` AS locationname, `sites`.`name` AS sitename, `pat`.`pattest_name` AS pat_status, `itemstatus`.`name` AS statusname, `users`.`firstname`, `users`.`lastname`, `tickets`.`user_id` as fixed_by, `tickets`.`description`, `tickets`.`severity`, `tickets`.`id` as ticket_id, `tickets`.`fix_code`, `tickets`.`reason_code`, `tickets`.`order_no`, `tickets`.`jobnote`, `tickets`.`date` as dt, `tickets`.`fix_date`, `tickets`.`ticket_action`, `tickets_history`.`user_id` as fault_by FROM (`items`) LEFT JOIN `items_categories_link` ON `items`.`id` = `items_categories_link`.`item_id` LEFT JOIN `categories` ON `items_categories_link`.`category_id` = `categories`.`id` LEFT JOIN `users` ON `items`.`owner_now` = `users`.`id` LEFT JOIN `owner` ON `items`.`owner_now` = `owner`.`id` LEFT JOIN `locations` ON `items`.`location_now` = `locations`.`id` LEFT JOIN `sites` ON `items`.`site` = `sites`.`id` LEFT JOIN `tickets` ON `tickets`.`item_id` = `items`.`id` LEFT JOIN `tickets_history` ON `tickets`.`id` = `tickets_history`.`ticket_id` LEFT JOIN `suppliers` ON `items`.`supplier` = `suppliers`.`supplier_id` LEFT JOIN `itemstatus` ON `items`.`status_id` = `itemstatus`.`id` LEFT JOIN `pat` ON `items`.`pattest_status` = `pat`.`id` LEFT JOIN `item_manu` ON `items`.`item_manu` = `item_manu`.`id` 
            left join photos on users.photo_id = photos.id
             left join item_condition on items.condition_now = item_condition.id
             left join custom_fields_content on items.id = custom_fields_content.item_id
               left join photos AS photos2 on items.photo_id = photos2.id WHERE (items.account_id = " . $this->session->userdata('objSystemUser')->accountid . " AND tickets.ticket_action='Fix' AND tickets_history.action = 'Fixed')
                  ";



        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $words = $_GET['sSearch'];
            $query .=" AND ( items.barcode REGEXP '$words'
                          OR categories.name REGEXP '$words'
                          OR items.item_manu REGEXP '$words'
                          OR items.manufacturer REGEXP '$words'
                          OR items.model REGEXP '$words'
                          OR items.quantity REGEXP '$words'
                          OR sites.name REGEXP '$words'
                          OR locations.name REGEXP '$words'
                          OR owner.owner_name REGEXP '$words'
                          OR pat.pattest_name REGEXP '$words'
                          OR itemstatus.name REGEXP '$words' 
                          OR items.value REGEXP '$words'
                          OR items.current_value REGEXP '$words'
                          OR suppliers.supplier_name REGEXP '$words' 
                          OR itemstatus.name REGEXP '$words'
                          OR item_condition.condition REGEXP '$words' 
                          OR items.serial_number REGEXP '$words' 
                          OR tickets.fix_code REGEXP '$words' 
                          OR tickets.date REGEXP '$words' 
                          OR tickets.severity REGEXP '$words' 
                          OR tickets.fix_date REGEXP '$words' 
                          OR tickets.order_no REGEXP '$words' 
                          OR users.firstname REGEXP '$words' 
) ";
        } else {
            $query .="";
        }

        if (isset($_GET['sSearch_1']) && $_GET['sSearch_1'] != "") {

            $words = $_GET['sSearch_1'];
            $query .=" and ( items.barcode REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_3']) && $_GET['sSearch_3'] != "") {

            $words = $_GET['sSearch_3'];
            $query .=" and ( categories.name REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_4']) && $_GET['sSearch_4'] != "") {

            $words = $_GET['sSearch_4'];
            $query .=" and ( item_manu.item_manu_name REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_5']) && $_GET['sSearch_5'] != "") {

            $words = $_GET['sSearch_5'];
            $query .=" and ( items.manufacturer REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_8']) && $_GET['sSearch_8'] != "") {

            $words = $_GET['sSearch_8'];
            $query .=" and ( sites.name REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_9']) && $_GET['sSearch_9'] != "") {

            $words = $_GET['sSearch_9'];
            $query .=" and ( locations.name REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_10']) && $_GET['sSearch_10'] != "") {

            $words = $_GET['sSearch_10'];
            $query .=" and ( owner.owner_name REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_11']) && $_GET['sSearch_11'] != "") {

            $words = $_GET['sSearch_11'];
            $query .=" and ( suppliers.supplier_name REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_12']) && $_GET['sSearch_12'] != "") {

            $words = $_GET['sSearch_12'];
            $query .=" and ( itemstatus.name REGEXP '$words'
                         ) ";
        }
        if (isset($_GET['sSearch_13']) && $_GET['sSearch_13'] != "") {

            $words = $_GET['sSearch_13'];
            $query .=" and ( item_condition.condition REGEXP '$words'
                         ) ";
        }

        for ($k = 22; $k < $_GET['iColumns']; $k++) {
            if (isset($_GET['sSearch_' . $k]) && $_GET['sSearch_' . $k] != "") {

                $words = $_GET['sSearch_' . $k];
                $query .=" and ( custom_fields_content.content REGEXP '$words'
                         ) ";
            }
        }
        $order_by = " tickets.date ";
        $temp = " DESC ";
        if (isset($_GET['iSortCol_0'])) {
            $index = $_GET['iSortCol_0'];
            $temp = $_GET['sSortDir_0'] === 'asc' ? 'asc' : 'desc';
            $order_by = $col_sort[$index];
        }

        $query .= " Group by tickets_history.ticket_id ORDER BY $order_by " . " " . " $temp";


        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
//        echo $query_res;die;
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
        $arrCustomfield = $this->customfields_model->getFieldByAccountId($this->session->userdata('objSystemUser')->accountid);
        foreach ($arrCustomfield as $column_name) {
            $arr_column[] = $column_name->field_name;
        }

        $arr_custom_columns = implode(',', $arr_column);

        $count = 0;
        foreach ($result as $val) {
            $view_users = base_url('items/view/' . $val['itemid']);
            $edit = base_url('items/editItem/' . $val['itemid']);
            $photo = '<img title="Item Picture" width="80" src="' . base_url($val['itemphotopath']) . '">';
            $image_role = "<div class='image_single'>";
            $photoid = $val['itemphotopath'];
            $image_role .= '<a title="" href="' . base_url($val['itemphotopath']) . '" class="ui-lightbox"><img  alt="Gallery Image"   src="' . base_url() . '/images/viewList/' . $val['itemphotoid'] . '"></a>';

            $image_role .= "<script>$('.image_single').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";

            // get name 


            $image_role .= "</div>";
            if ($val['purchase_date'] != "0000-00-00" && $val['purchase_date'] != NULL) {
                $date2 = date('d-m-Y', strtotime($val['purchase_date']));
                $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                $diff = abs(strtotime($date2) - strtotime($date1));

                $years = floor($diff / (365 * 60 * 60 * 24));
                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                $age_asset = $years . ' year ' . $months . ' month ';
            } else {
                $age_asset = 'N/A';
            }
            $custom_fields = $this->customfields_model->getCustomFieldsByItem($val['itemid']);
            foreach ($arrCustomfield as $col) {
                if ($custom_fields) {
                    foreach ($custom_fields as $custom_field) {
                        if ($col->field_name == $custom_field->field_name) {
                            $val[$col->field_name] = $custom_field->content;
                        }
                    }
                } else {

                    $val[$col->field_name] = 'N/A';
                }
            }
            if ($val['purchase_date'] != "0000-00-00" && $val['purchase_date'] != NULL) {
                $purchase_date = date('d-m-Y', strtotime($val['purchase_date']));
            } else {
                $purchase_date = "N/A";
            }
            if ($val['warranty_date'] != "0000-00-00" && $val['warranty_date'] != NULL) {
                $warranty_date = date('d-m-Y', strtotime($val['warranty_date']));
            } else {
                $warranty_date = "N/A";
            }
            if ($val['replace_date'] != "0000-00-00" && $val['replace_date'] != NULL) {
                $replace_date = date('d-m-Y', strtotime($val['replace_date']));
            } else {
                $replace_date = "N/A";
            }
// get total faults
            $mixItemsTicketHistory = $this->tickets_model->ticketHistory($val['itemid']);
            $numberOfFaults = count($mixItemsTicketHistory);

            if ($val['deleted_date'] != NULL) {
                $removal_date = date('d/m/Y', strtotime($val['deleted_date']));
            } else {
                $removal_date = 'N/A';
            }

            if ($val['userfirstname']) {
                $logged_by = $val['userfirstname'] . '' . $val['userlastname'];
            }

//            $confirm_by = $this->getUser($val['mark_deleted']);
            // calculate incident length...
            if (isset($val['dt'])) {
                $date2 = date('d-m-Y', strtotime($val['dt']));
                $date1 = date('d-m-Y H:i:s', strtotime($val['fix_date']));

                $diff = abs(strtotime($date2) - strtotime($date1));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $days = floor($diff / 3600 / 24);
                $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                $incident_length = $months . ' month ' . $days . ' days ';
            } else {
                $incident_length = 'N/A';
            }
            $account_id = $this->session->userdata('objSystemUser')->accountid;
            if ($val['ticket_id'] > 0) {
                $ticket_id = $val['ticket_id'];
            } else {
                $ticket_id = 0;
            }
            $output['aaData'][] = array("DT_RowId" => $val['itemid'], '<a id="bcode" href="' . $view_users . '">' . $val['barcode'] . '</a>', $photo, $val['categoryname'], $val['item_manu_name'], $val['manufacturer'], $val['model'], $val['quantity'], $val['sitename'], $val['locationname'], $val['owner_name'], $val['supplier_name'], $val['statusname'], $val['condition_name'], $numberOfFaults, $val['serial_number'], $age_asset, $purchase_date, $warranty_date, $replace_date, $val['value'], $val['current_value'], $val['severity'], date('d/m/Y', strtotime($val['dt'])), $incident_length, $val['severity'], date('d/m/Y', strtotime($val['fix_date'])), $val['order_no'], $val['jobnote'], $this->getUserData($val['fault_by']), $this->getUserData($val['fixed_by']), $val['reason_code'],
                '<span class="action-w"><a data-toggle="modal" actionmode="reportfault"  ticket_id = "' . $ticket_id . '"  id="itm_' . $val['itemid'] . '" account_id="' . $account_id . '" href="#view_fault" title="View Fault" class="viewfault" data_customer_id=""><i class="fa fa-eye franchises-i"></i></a>View Incident</span><span><a  href="' . base_url("faults/getPdfForFaultHistory/" . $val['itemid'] . '/' . $account_id) . '"><img src="http://' . $_SERVER['HTTP_HOST'] . '/youaudit/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></span>');
//            var_dump($output);die;
            foreach (array_reverse($arrCustomfield) as $col) {
//  echo $col->field_name.'<br>';
                if (isset($val[$col->field_name])) {
//                    echo  $val[$col->field_name].'<br>';
                    $col_value = $val[$col->field_name];
                } else {
                    $col_value = 'N/A';
                }
//var_dump($output['aaData'][$count]);die;
                array_splice($output['aaData'][$count], 22, 0, $col_value);
            }
            $count++;
        }



        echo json_encode($output);
        die;
    }

}

/* End of file faults.php */
/* Location: ./application/controllers/faults.php */
