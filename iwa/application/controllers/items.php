<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends MY_Controller {

    public function index() {

        $this->filter();
    }

    public function itsMine($intItemId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/itsmine/' . $intItemId . '/');
            redirect('users/login/', 'refresh');
        }

        if ($intItemId > 0) {
// load models
            $this->load->model('items_model');

//            if (!$this->items_model->hasSite($intItemId)) {
            $this->items_model->linkThisToUser($intItemId, $this->session->userdata('objSystemUser')->userid);
//                $this->items_model->clearCurrentSite($intItemId);
// Log it first
            $this->logThis("Changed Item Owner/Location", "items", $intItemId);
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('You now own the item.')));
            redirect('/items/view/' . $intItemId);
//            } else {
//                $this->session->set_userdata('booCourier', true);
//                $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Unable to grant ownership on a site item')));
//                redirect('/items/filter/fr_userid_exact/' . $this->session->userdata('objSystemUser')->userid, 'refresh');
//            }
//        } else {
//            $this->session->set_userdata('booCourier', true);
//            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('Invalid Item ID.')));
//            redirect('/items/filter/fr_userid_exact/' . $this->session->userdata('objSystemUser')->userid, 'refresh');
//        }
        }
    }

//    // Function to change Location
//    public function changeLocation() {
//        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
//            redirect('users/login/', 'refresh');
//        }
//        $item_id = $this->input->post('item_id');        var_dump($_POST);die;
//        if ($item_id) {
//            $this->changeLinks($item_id);
//        }
//    }

    public function changeLinks($intItemId = '') {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/changelinks/' . $intItemId . '/');
            redirect('users/login/', 'refresh');
        }

// housekeeping

        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $booSuccess = false;

// helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$intItemId) {
            $intItemId = $this->input->post('item_id');
        }
        if ($intItemId > 0) {
            $this->load->model('items_model');

// load models
            $this->load->model('users_model');
            $this->load->model('locations_model');
            $this->load->model('sites_model');
            $this->load->model('admin_section_model');
            if ($this->input->post() && (($this->input->post('new_owner_id') != 0) || ($this->input->post('new_location_id') != 0) || ($this->input->post('new_site_id') != 0))) {
                if ($this->input->post('user_id') != 0) {
                    $this->items_model->linkThisToUser($intItemId, $this->input->post('user_id'));
                }
                if ($this->input->post('new_location_id') != 0) {
                    $this->items_model->linkThisToLocation($intItemId, $this->input->post('new_location_id'));
                }
                if ($this->input->post('new_site_id') != 0) {
                    $this->items_model->linkThisToSite($intItemId, $this->input->post('new_site_id'));
                }
                if ($this->input->post('new_owner_id') != 0) {
                    $this->items_model->linkThisToOwner($this->input->post('new_owner_id'), $this->input->post('new_location_id'));
                    $this->items_model->update_owner($intItemId, $this->input->post('new_owner_id'));
                    $this->items_model->linkToOwner($intItemId, $this->input->post('new_owner_id'));
                }

                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('owner and location changed successfully')));
                redirect('/items/view/' . $intItemId, 'refresh');
            }
        } else {
            // load views
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('owner and location could not change successfully')));
            redirect('/items/view/' . $intItemId, 'refresh');
        }
    }

    public function changeOwner($intItemId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/changeowner/' . $intItemId . '/');
            redirect('users/login/', 'refresh');
        }

// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Change ownership";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $booSuccess = false;

// helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

        if ($intItemId > 0) {
// load models
            $this->load->model('users_model');
            $this->load->model('items_model');
            $mixItemsData = $this->items_model->getOne($intItemId, $this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);

            if ($mixItemsData && (count($mixItemsData) == 1)) {
                $arrPageData['objItem'] = $mixItemsData[0];
                $booSuccess = true;
                if ($this->input->post()) {
//okay test the data
                    $this->form_validation->set_rules('user_id', 'New Owner', 'required|is_natural_no_zero');
                    if ($this->form_validation->run()) {
                        $this->items_model->linkThisToUser($intItemId, $this->input->post('user_id'));
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Item owner changed.')));
                        redirect('/items/filter/fr_userid_exact/' . $this->session->userdata('objSystemUser')->userid, 'refresh');
                    }
                }
            } else {
                $arrPageData['arrErrorMessages'][] = "Unable to find the item.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "The Item was not found.";
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "Unable to find the item.";
            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "Invalid Item ID requested.";
        }
// load views
        $this->load->view('common/header', $arrPageData);
//load the correct view
        if ($booSuccess) {
            $this->load->view('items/changeowner', $arrPageData);
            $this->load->view('items/forms/changeowner', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

// get all items 
    public function show_items() {
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
        $col_sort = array("", "items.barcode", "items.serial_number", "categories.name", "items.item_manu", "items.manufacturer", "items.model", "items.quantity", "sites.name", "locations.name", "users.firstname", "suppliers.supplier_name", "itemstatus.name", "item_condition.condition", "", "items.serial_number", "", "items.purchase_date", "items.warranty_date", "items.replace_date", "items.value", "items.current_value");
        $query = "select
                items.id AS itemid, items.manufacturer,items.item_manu ,items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.current_value, items.purchase_date,items.status_id, items.compliance_start, items.quantity,items.warranty_date,items.replace_date,
		categories.id AS categoryid, categories.name AS categoryname, categories.default AS categorydefault, categories.icon AS categoryicon,item_condition.condition AS condition_name,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                owner.id as owner_id, owner.owner_name,
                photos.id AS userphotoid, photos.title AS userphototitle,
                photos2.id AS itemphotoid,
                photos2.path AS itemphotopath,
                photos2.title AS itemphototitle,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                pat.pattest_name AS pat_status,
                itemstatus.name AS statusname,
                suppliers.supplier_name,
                item_manu.item_manu_name,
                custom_fields_content.content
                from items left join items_categories_link on items.id = items_categories_link.item_id
                left join categories on items_categories_link.category_id = categories.id
                left join custom_fields_content on items.id = custom_fields_content.item_id
                left join users on items.owner_now = users.id
                left join item_manu on items.item_manu = item_manu.id
                left join owner on items.owner_now = owner.id
                left join photos on users.photo_id = photos.id
                left join item_condition on items.condition_now = item_condition.id
                left join photos AS photos2 on items.photo_id = photos2.id
                left join locations on items.location_now = locations.id
                left join sites on items.site = sites.id
                left join suppliers on items.supplier = suppliers.supplier_id
                left join itemstatus on items.status_id = itemstatus.id
                left join pat on items.pattest_status = pat.id
                where items.active =1 AND items.account_id=" . $this->session->userdata('objSystemUser')->accountid;


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
                          OR custom_fields_content.content REGEXP '$words'
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
            $query .=" and ( manufacturer REGEXP '$words'
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
//
//        echo $col_sort.'<br>';
//        echo $_GET['iSortCol_0'].'<br>';
//        echo $col_sort[$_GET['iSortCol_0']].'<br>';
//         $order_by = "id";
//        $temp = 'asc';
//         if (isset($_GET['iSortCol_0'])) {
//              $query .= " GROUP BY items.id ORDER BY ";
//            $index = $_GET['iSortCol_0'];
//            $temp = $_GET['sSortDir_0'] === 'asc' ? 'asc' : 'desc';
//            $order_by = $col_sort[$index];
//            $query .= $order_by;
//        }
//        echo $_GET['iSortCol_0'];
        if (isset($_GET['iSortCol_0'])) {
//            echo $_GET['iSortCol_0'];
            $query .= " GROUP BY items.id  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
//                echo $_GET['bSortable_' . intval($_GET['iSortCol_' . $i])];
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    if (!empty($col_sort[intval($_GET['iSortCol_' . $i])])) {
                        $query .= " ORDER BY ";
                        $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]);
                    }
                }
//                echo $query;
            }

//            $query = substr_replace($query, "", -2);
//            if ($query == "ORDER BY") {
//                $query .= "";
//            }
//            if ($query == "GROUP BY") {
//                $query .= "";
//            }
        }
//
//        echo $_GET['iDisplayStart'];
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
            $photo = '<img title="Item Picture" width="60" height="50" src="' . base_url($val['itemphotopath']) . '">';
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
//            var_dump($custom_fields);die;
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

            if ($arrPageData['arrSessionData']['objSystemUser']->levelid > 1) {
                $edit_icon = '<span class="action-w"><a href="' . $edit . '" class="icon-with-text" title="Edit"><i class="fa fa-edit franchises-i"></i></a>Edit</span>';
            }
            if ($arrPageData['arrSessionData']['objSystemUser']->levelid > 2) {
                if (($objItem->mark_deleted == 0) && ($objItem->mark_deleted_2 == 0)) {
                    $remove_item = '<span class="action-w"><a data-toggle="modal" data-target="#remove_item" id="removeitem" class="remove_item icon-with-text" href="#" title="Remove Item" data_item_id="' . $val['itemid'] . '"><i class="fa fa-plus franchises-i"></i></a>Archive Item</span>';
                }
            }

            $output['aaData'][] = array("DT_RowId" => $val['itemid'], '<input type="checkbox" class="multiComSelect" value="' . $val['itemid'] . '"><input type="hidden" id="category_id_' . $val['itemid'] . '" class="" value="' . $val['categoryid'] . '" >', '<a id="bcode" href="' . $view_users . '">' . $val['barcode'] . '</a>', $photo, $val['categoryname'], $val['item_manu_name'], $val['manufacturer'], $val['model'], $val['quantity'], $val['sitename'], $val['locationname'], $val['owner_name'], $val['supplier_name'], $val['statusname'], $val['condition_name'], $numberOfFaults, $val['serial_number'], $age_asset, $purchase_date, $warranty_date, $replace_date, $val['value'], $val['current_value'], '<span class="action-w"><a data-toggle="modal" data-target="#add_similar_item" id="addsimilaritem" class="add_similar icon-with-text" href="#" title="Add similar" data_item_id="' . $val['itemid'] . '"><i class="fa fa-plus franchises-i"></i></a>Add Similar</span><span class="action-w"><a class="icon-with-text" href="' . $view_users . '" title="View"><i class="fa fa-eye franchises-i"></i></a>View</span>' . $edit_icon . $remove_item);
            foreach (array_reverse($arrCustomfield) as $col) {

                if (isset($val[$col->field_name])) {
                    $col_value = $val[$col->field_name];
                } else {
                    $col_value = 'N/A';
                }

                array_splice($output['aaData'][$count], 23, 0, $col_value);
            }

            $count++;
        }

//        var_dump($output);die;

        echo json_encode($output);
        die;
    }

// get filtered items
    public function filter_item($bar_code) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/filter/');
            redirect('users/login/', 'refresh');
        }
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
        $res = $this->items_model->filter_search(trim($bar_code));
        $arrPageData['filterdata'] = $res;
        if (empty($res)) {
            $arrPageData['error'] = "No Result Found for Your Search";
        }
        $this->load->view('common/header', $arrPageData);
//load the correct view
        $this->load->view('items/filtered_list', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    public function filter() {

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
        $arrPageData['arrReasons'] = $this->itemstatus_model->getAllThatImplyInactive();
        $arrPageData['RemoveItemReasons'] = $this->itemstatus_model->getAllReasons();

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

        $mixItemsData = $this->items_model->getAll($this->session->userdata('objSystemUser')->accountid);

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
// load views
        $this->load->view('common/header', $arrPageData);
//load the correct view
        $this->load->view('items/view_all/item_list', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

// item export PDF
    public function exportToPdf() {
        $this->load->model('items_model');
        $allData = $this->input->post('allData');

        $filename = $this->input->post('filename');
        $this->load->model('tests_model');
        $this->items_model->exportPdfFile($allData, $filename);
    }

    public function exporttocsv() {
        $output = array();

        $filename = $this->input->post('filename');
        $allData = explode('|', $this->input->post('allData'));

        foreach ($allData as $key => $value) {
            $output[] = preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', preg_replace('/<\/?[a-zA-Z]*[^>]*>/', '', $value));
        }
        foreach ($output as $key => $value) {
            $output[$key] = explode(',', $value);
        }

        $this->load->helper('csv');
        getcsv($output, "$filename.csv");
    }

    function date_difference($date1timestamp, $date2timestamp) {
        $all = round(($date1timestamp - $date2timestamp) / 60);
        $d = floor($all / 1440);
        $h = floor(($all - $d * 1440) / 60);
        $m = $all - ($d * 1440) - ($h * 60);
//Since you need just hours and mins
        return $h . ':' . $m;
    }

    public function view($intItemId = -1) {

        $this->load->library('s3');
        $data = array();
        $this->load->model('items_model');
        $item_manu = $this->items_model->get_item_manu($intItemId);
        $bucket_content = $this->s3->getBucket('smartaudit', 'youaudit/' . $item_manu);
        foreach ($bucket_content as $key => $value) {
// ignore s3 "folders"
            if (preg_match("/\/$/", $key))
                continue;

// explode the path into an array
            $file_path = explode('/', $key);
            $file_name = end($file_path);
            $file_folder = substr($key, 0, (strlen($file_name) * -1) + 1);
            $file_folder = prev($file_path);

            $s3_url = "https://smartaudit.s3.amazonaws.com/{$key}";
//            if ($file_folder == $intItemId) {
//                echo $key;
            $data[$key] = array(
                'file_name' => $file_name,
                's3_key' => $key,
                'file_folder' => $file_folder,
                'file_size' => $value['size'],
                'created_on' => date('Y-m-d H:i:s', $value['time']),
                's3_link' => $s3_url,
                'md5_hash' => $value['hash']);
//            }
        }

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/view/' . $intItemId . '/');
            redirect('users/login/', 'refresh');
        }

// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View item";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $arrPageData['pdf_number'] = $data;

        $booSuccess = false;

        if ($intItemId > 0) {
// load models
            $this->load->model('users_model');

            $this->load->model('tests_model');
            $this->load->model('accounts_model');
            $this->load->model('tickets_model');
            $this->load->model('customfields_model');
            $this->load->model('categories_model');
            $this->load->model('sites_model');
            $this->load->model('locations_model');
            $this->load->model('itemstatus_model');
            $this->load->model('suppliers_model');
            $this->load->model('admin_section_model');
            $this->load->model('photos_model');
            $arrPageData['arrCategories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAll();
            $arrPageData['arrSuppliers'] = $this->suppliers_model->getAll();
            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);

// For Owner Dropdown List
            $arrPageData['arrOwners'] = $this->users_model->getAllForOwner($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['getitemmanu'] = $this->admin_section_model->getItem_Manu($arrPageData['arrSessionData']['objSystemUser']->accountid);
            $arrPageData['arrManufaturer'] = $this->admin_section_model->getManufacturer($arrPageData['arrSessionData']['objSystemUser']->accountid);
            $arrPageData['arrCondition'] = $this->items_model->get_condition();
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objSystemUser')->accountid);

// is there a submission?
            if ($mixItemsData && (count($mixItemsData) == 1)) {

                /* Calculate Total purchase and total current value */
                $mixItemsData[0]->total_value = $mixItemsData[0]->value * $mixItemsData[0]->quantity;
                $mixItemsData[0]->total_current_value = $mixItemsData[0]->current_value * $mixItemsData[0]->quantity;

                $arrPageData['objItem'] = $mixItemsData[0];
                if ($mixItemsData[0]->active == 0) {
                    $arrPageData['arrErrorMessages'][] = "This item was removed from the system on " . date("d/m/Y", strtotime($mixItemsData[0]->deleted_date)) . ".";
                } else {
                    if (($mixItemsData[0]->mark_deleted != 0) || ($mixItemsData[0]->mark_deleted_2 != 0)) {
                        $arrPageData['arrErrorMessages'][] = "This item is marked as removed from the system, awaiting confirmation.";
                    }
                }

                $arrNotesList = explode('<br />', nl2br($mixItemsData[0]->notes));
                $strNotesListHtml = "";
                foreach ($arrNotesList as $strNote) {
                    $strNotesListHtml .= "<li>" . $strNote . "</li>\r\n";
                }

                $arrPageData['strItemNotesList'] = $strNotesListHtml;

                if ($this->session->userdata('objSystemUser')->levelid > 1) {
                    $mixItemsHistoryData = $this->items_model->getHistory($intItemId, $this->session->userdata('objSystemUser')->accountid);
                    $mixItemsTicketHistory = $this->tickets_model->ticketHistory($intItemId);
                    $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
                    $arrPageData['arrItemHistory'] = $mixItemsHistoryData;
                    // get owner history data
                    $owner_history = $this->items_model->ownerHistory($intItemId, $this->session->userdata('objSystemUser')->accountid);
                    $arrPageData['arrOwnerHistory'] = $owner_history;
                    // get audit history
                    $audit_history = $this->items_model->auditHistory($intItemId, $this->session->userdata('objSystemUser')->accountid);
                    $arrPageData['arrAuditHistory'] = $audit_history;

                    $arrPageData['arrItemTicketHistory'] = $mixItemsTicketHistory;
                    $arrPageData['arrItemLogHistory'] = $this->items_model->getLog($intItemId);
                    $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFields($mixItemsData[0]->categoryid);
                    $arrPageData['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItem($intItemId);
                    $arrPageData['arrPatHistory'] = $this->items_model->getPatHistory($intItemId);
                    $arrPageData['numberOfFaults'] = count($mixItemsTicketHistory);
                    $arrPageData['lastDateOfFaults'] = $this->tickets_model->lastDateOfFaults($intItemId);
                    $arrPageData['arrItemFixTicketHistory'] = $this->tickets_model->ticketFixHistory($intItemId);
                    $arrPageData['arrItemOpenTicketHistory'] = $this->tickets_model->ticketOpenHistory($intItemId);
                    $get_time = $this->date_difference(time(), strtotime($arrPageData['lastDateOfFaults']));


// condition history

                    $arrPageData['assetcondition'] = $this->items_model->checkasset_condition($intItemId, $this->session->userdata('objSystemUser')->accountid);

                    if (count($arrPageData['assetcondition']) > 1) {
                        for ($i = 0; $i < count($arrPageData['assetcondition']) - 1; $i++) {
                            $arrPageData['history'][0]['enddate'] = 'N/A';
                            $arrPageData['history'][$i + 1]['enddate'] = $arrPageData['assetcondition'][$i]['date'];
                        }
                    } else {
                        $arrPageData['history'][0]['enddate'] = 'N/A';
                    }
                    $arrPageData['conditionhistory'] = array_replace_recursive($arrPageData['assetcondition'], $arrPageData['history']);
                    $arrPageData['conditionlist'] = $this->items_model->get_condition();
                    $arrPageData['historycount'] = $this->items_model->count_condition($intItemId);
                    $arrPageData['historylatest'] = $this->items_model->get_maxcondition($intItemId);

//                    Check Items has Report Faults ?
                    $arrPageData['checkItemReportFaults'] = $this->tickets_model->checkReportFault($intItemId);



                    if ($arrPageData['arrCustomFields'] && $arrPageData['arrCustomFieldsContent']) {
                        foreach ($arrPageData['arrCustomFields'] as $key => $value) {
                            foreach ($arrPageData['arrCustomFieldsContent'] as $k => $v) {
                                if ($v->custom_field_id == $value->id) {
                                    $arrPageData['arrCustomFields'][$key]->content = $v->content;
                                }
                            }
                        }
                    }
                    $arrPageData['priorities'] = array(1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical');
                }

                $arrPageData['arrItemCompliance'] = $this->tests_model->getComplianceHistory($intItemId);

                /* Compliance History Page For particular Item  */
                $this->session->set_userdata('comHistory_chk', $filter);
                $arrPageData['neverTested'] = $this->tests_model->getNeverTested($this->session->userdata('objSystemUser')->accountid);
                $arrPageData['dueTests'] = $this->tests_model->getComplianceHistoryFiltered(NULL, NULL, $intItemId);


                foreach ($arrPageData['dueTests'] as $key => $value) {
                    $arrPageData['dueTests'][$key]['location_name'] = $this->tests_model->getLocation($value['test_item_id']);
                    $arrPageData['dueTests'][$key]['owner_name'] = $this->tests_model->getOwnerName($value['test_item_id']);
                    $arrPageData['dueTests'][$key]['site_name'] = $this->tests_model->getSiteName($value['test_item_id']);
//                $arrPageData['dueTests'][$key]['tasks'] = $this->tests_model->getComplianceTaskDetails($value['test_type'],$value['test_item_id']);
                    $arrPageData['dueTests'][$key]['total_tasks'] = $this->tests_model->getTaskCount($value['test_date']);
                    $arrPageData['dueTests'][$key]['tasks'] = $this->tests_model->getTaskCount($value['test_date'], 'details');
                    $arrPageData['dueTests'][$key]['test_type_name'] = $this->tests_model->getComplianceNameforHistory($value['test_item_id'], $value['test_date']);
                    $arrPageData['dueTests'][$key]['test_type_signature'] = $this->tests_model->getComplianceSignatureforHistory($value['test_item_id'], $value['test_date']);
                    $arrPageData['dueTests'][$key]['signature_details'] = $this->photos_model->getOne($arrPageData['dueTests'][$key]['test_type_signature']);
                }
                $booSuccess = true;
            } else {
                $arrPageData['arrErrorMessages'][] = "Unable to find the item.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "The Item was not found.";
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "Unable to find the item.";
            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "Invalid Item ID requested.";
        }
        foreach ($arrPageData['dueTests'] as $compliance_history) {
            $current_audit = $compliance_history['test_date'];
            if ($compliance_history['result']) {
                $compliance_result = 'Pass';
            } else {

                $compliance_result = 'Fail';
            }
        }
        $arrPageData['last_compliance_check'] = $current_audit;
        $arrPageData['last_compliance_result'] = $compliance_result;
// load views
        $this->load->view('common/header', $arrPageData);
//load the correct view
        if ($booSuccess) {
            $this->load->view('items/view', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

// to add new condition on asset
    public function con_history() {
        $this->load->model('items_model');
        $item = $this->input->post('asset_id');
        $response = $this->items_model->condition_log($item);
        if ($response) {
            $this->session->set_flashdata('success', 'Condition Added Successfully');
            redirect("items/view" . '/' . $item, "refresh");
        } else {
            $this->session->set_flashdata('error', 'Condition Could Not Be Added');
            redirect("items/view" . '/' . $item, "refresh");
        }
    }

    public function getCustomFields() {
        $this->load->model('categories_model');
        $category_ids_arr = ($_POST['cat_ids']);
        $arrPageData = array();
        $custom_fields = array();
        foreach ($category_ids_arr as $catids) {

            $arrPageData[] = $this->categories_model->getCustomFields($catids);
        }
        foreach ($arrPageData as $custom_arr) {
            if ($custom_arr) {
                foreach ($custom_arr as $arr_custm) {
                    $custom_fields[] = array(
                        'id' => $arr_custm->id,
                        'account_id' => $arr_custm->account_id,
                        'name' => $arr_custm->field_name,
                        'type' => $arr_custm->field_value,
                        'value' => $arr_custm->pick_values
                    );
                }
            }
        }
        $temp_array = array();
        foreach ($custom_fields as $v) {
            if (!isset($temp_array[$v['id']]))
                $temp_array[$v['id']] = $v;
        }
        echo json_encode($temp_array);
        die;
    }

    public function mark_deleted($intItemId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/mark_deleted/' . $intItemId . "/");
            redirect('users/login/', 'refresh');
        }

// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Mark Deleted";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

// load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.markDeleted");
        $booSuccess = false;
        if ($booPermission) {
            $arrPageData['intItemId'] = $intItemId;
            $this->load->model('items_model');
            $this->load->model('itemstatus_model');
// helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            $mixItemResult = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objSystemUser')->accountid);

            if ($mixItemResult) {
                $booSuccess = true;
                $arrPageData['objItem'] = $mixItemResult[0];
                $arrPageData['booSuperAdmin'] = false;
                $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAllThatImplyInactive();
                $arrPageData['arrRemoveReasons'] = $this->itemstatus_model->getAllReasons();
                if ($this->session->userdata('objSystemUser')->levelid == 4) {
                    $arrPageData['booSuperAdmin'] = true;
                }
                if ($this->input->post()) {
                    if (($this->input->post('itemstatus') > 0) && ($this->input->post('safety') > 0)) {
                        $this->items_model->markDeleted(
                                $intItemId, $this->session->userdata('objSystemUser')->accountid, $this->input->post('itemstatus'), $this->session->userdata('objSystemUser')->userid, $arrPageData['booSuperAdmin']);
// Log it first
                        $this->logThis("Item Marked Deleted", "items", $intItemId);
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Item marked deleted.')));
                        redirect('/items/view/' . $intItemId, 'refresh');
                    } else {
                        $this->session->set_flashdata('error', 'Please Choose Reason Code for why and how asset is removed');
                        redirect('/items/mark_deleted/' . $intItemId, 'refresh');
                    }
                }
            } else {
                $arrPageData['arrErrorMessages'][] = "System Error - Item Not Found";
                $arrPageData['strPageTitle'] = "Item Not Found";
                $arrPageData['strPageText'] = "The Item was not found in the database.";
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

// load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booSuccess) {
//load the correct view
            $this->load->view('items/delete', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

//    public function markDeleted($intItemId = -1) {
//        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
//            $this->session->set_userdata('strReferral', '/items/markdeleted/' . $intItemId);
//            redirect('users/login/', 'refresh');
//        }
//
//        // housekeeping
//        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();
//        $arrPageData['arrPageParameters']['strPage'] = "Mark Deleted";
//        $arrPageData['arrSessionData'] = $this->session->userdata;
//        $this->session->set_userdata('booCourier', false);
//        $this->session->set_userdata('arrCourier', array());
//        $arrPageData['arrErrorMessages'] = array();
//        $arrPageData['arrUserMessages'] = array();
//
//        // load models
//        $this->load->model('users_model');
//        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.markDeleted");
//        $booSuccess = false;
//        if ($booPermission) {
//            $arrPageData['intItemId'] = $intItemId;
//            $this->load->model('items_model');
//            $this->load->model('itemstatus_model');
//            // helpers
//            $this->load->helper('form');
//            $this->load->library('form_validation');
//
//            $mixItemResult = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objSystemUser')->accountid);
//
//            if ($mixItemResult) {
//                $booSuccess = true;
//                $arrPageData['objItem'] = $mixItemResult[0];
//                $arrPageData['booSuperAdmin'] = false;
//                $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAllThatImplyInactive();
//                $arrPageData['arrRemoveReasons'] = $this->itemstatus_model->getAllReasons();
//
//                if ($this->session->userdata('objSystemUser')->levelid == 4) {
//                    $arrPageData['booSuperAdmin'] = true;
//                }
//                if ($this->input->post()) {
//                    if (($this->input->post('itemstatus') > 0) && ($this->input->post('safety') > 0)) {
//                        $this->items_model->markDeleted(
//                                $intItemId, $this->session->userdata('objSystemUser')->accountid, $this->input->post('itemstatus'), $this->session->userdata('objSystemUser')->userid, $arrPageData['booSuperAdmin'], $this->input->post('reason'), $this->input->post('payment'), $this->input->post('net_gain_loss'), $this->input->post('itemstatus'));
//                        // Log it first
//                        $this->logThis("Item Marked Deleted", "items", $intItemId);
//                        $this->session->set_userdata('booCourier', true);
//                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Item marked deleted.')));
//                        redirect('/items/view/' . $intItemId, 'refresh');
//                    } else {
//                        $this->session->set_flashdata('error', 'Complete Form Below to Archive the Asset and remove from Tracked Items & Active System. It will be marked as awaiting another Admin approval before it is archived');
//                        redirect('/items/markdeleted/' . $intItemId, 'refresh');
//                    }
//                }
//            } else {
//                $arrPageData['arrErrorMessages'][] = "System Error - Item Not Found";
//                $arrPageData['strPageTitle'] = "Item Not Found";
//                $arrPageData['strPageText'] = "The Item was not found in the database.";
//            }
//        } else {
//            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
//            $arrPageData['strPageTitle'] = "Security Check Point";
//            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
//        }
//
//        // load views
//        $this->load->view('common/header', $arrPageData);
//        if ($booPermission && $booSuccess) {
//            //load the correct view
//            $this->load->view('items/delete', $arrPageData);
////            $this->load->view('common/forms/safetycheck', $arrPageData);
//        } else {
//            $this->load->view('common/system_message', $arrPageData);
//        }
//        $this->load->view('common/footer', $arrPageData);
//    }

    public function markDeleted() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/markdeleted/' . $intItemId);
            redirect('users/login/', 'refresh');
        }
        $intItemId = $this->input->post('archiveitemID');
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Mark Deleted";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.markDeleted");
        $booSuccess = false;
        if ($booPermission) {
            $arrPageData['intItemId'] = $intItemId;
            $this->load->model('items_model');
            $this->load->model('itemstatus_model');
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            $booSuccess = true;
            $arrPageData['booSuperAdmin'] = false;

            if ($this->session->userdata('objSystemUser')->levelid == 4) {
                $arrPageData['booSuperAdmin'] = true;
            }
            if ($this->input->post()) {
                if (($this->input->post('itemstatus') > 0) && ($this->input->post('safety') > 0)) {
                    $this->items_model->markDeleted(
                            $intItemId, $this->session->userdata('objSystemUser')->accountid, $this->input->post('itemstatus'), $this->session->userdata('objSystemUser')->userid, $arrPageData['booSuperAdmin'], $this->input->post('reason'), $this->input->post('payment'), $this->input->post('net_gain_loss'), $this->input->post('itemstatus'));
                    // Log it first
                    $this->logThis("Item Marked Deleted", "items", $intItemId);
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Item marked deleted.')));
                    redirect('/items/view/' . $intItemId, 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'Complete Form Below to Archive the Asset and remove from Tracked Items & Active System. It will be marked as awaiting another Admin approval before it is archived');
                    redirect('/items/filter/', 'refresh');
                }
            } else {
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Item was not found in the database.')));
                redirect('/items/filter/', 'refresh');
            }
        } else {
            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('You do not have permission to do this.')));
            redirect('/items/filter/', 'refresh');
        }
    }

    public function confirmDeleted() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/confirmdeleted/');
            redirect('users/login/', 'refresh');
        }

// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Confirm Deleted";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

// load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.markDeleted");
        $booSuccess = false;
        if ($booPermission) {

            $this->load->model('items_model');

// helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            $mixItemsResult = $this->items_model->getAwaitingDeletion(
                    $this->session->userdata('objSystemUser')->accountid, $this->session->userdata('objSystemUser')->userid, $this->session->userdata('objSystemUser')->levelid
            );

            if ($mixItemsResult) {
// var_dump($mixItemsResult);
// die();
                $booSuccess = true;
                $arrPageData['arrItemsAwaitingDeletion'] = $mixItemsResult['results'];

                if ($this->input->post()) {
                    foreach ($this->input->post('confirmed_deletions') as $intItemDeleted) {
                        $this->items_model->confirmDeletion((int) $intItemDeleted, $this->session->userdata('objSystemUser')->accountid, $this->session->userdata('objSystemUser')->userid, $this->session->userdata('objSystemUser')->levelid
                        );
                        $this->logThis("Item Confirmed Deleted", "items", (int) $intItemDeleted);
                    }
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Items Confirmed Deleted.')));
                    redirect('/items/', 'refresh');
                }
            } else {
                $arrPageData['arrUserMessages'][] = "There are no items awaiting deletion.";
                $arrPageData['strPageTitle'] = "Items Not Found";
                $arrPageData['strPageText'] = "There are no items awaiting deletion.";
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

// load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booSuccess) {
//load the correct view
            $this->load->view('items/confirm_deleted', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function addMultiple() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/addmultiple/');
            redirect('users/login/', 'refresh');
        }

// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Add Multiple Items";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

// load models
        $this->load->model('users_model');
        $this->load->model('items_model');
        $this->load->model('accounts_model');
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);

        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.addOne");
        $intNoItems = $this->items_model->countNumberForAccount($this->session->userdata('objSystemUser')->accountid, true);
        $booAccountLimit = false;

        if ($intNoItems >= $this->session->userdata('objSystemUser')->package_item_limit) {
            $booAccountLimit = true;
            $booPermission = false;
        }
        /* check if number of items is at limit, if it is, set flag */
        if (!$booAccountLimit) {
            if ($booPermission) {
                $this->load->model('categories_model');
                $this->load->model('sites_model');
                $this->load->model('tickets_model');
                $this->load->model('items_model');
                $this->load->model('locations_model');
                $this->load->model('suppliers_model');
                $this->load->model('photos_model');
                $this->load->model('itemstatus_model');
                $this->load->model('admin_section_model');
                $this->load->model('customfields_model');
// helpers
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

                $arrPageData['booAddAnother'] = true;
                $arrPageData['arrCategories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
                $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
                $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
                $arrPageData['arrSuppliers'] = $this->suppliers_model->getAll();
                $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);
                $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAll();
                $arrPageData['intCategoryId'] = 0;
                $arrPageData['intSiteId'] = 0;
                $arrPageData['intLocationId'] = 0;
                $arrPageData['intUserId'] = 0;
                $arrPageData['strMake'] = "";
                $arrPageData['strModel'] = "";
                $arrPageData['strSerialNumber'] = "";
                $arrPageData['strBarcode'] = "";
                $arrPageData['strValue'] = "0.00";
                $arrPageData['strCurrentValue'] = "";
                $arrPageData['strNotes'] = "";
                $arrPageData['intItemStatusId'] = 1;
                $arrPageData['strPurchased'] = "";
                $arrPageData['strWarranty'] = "";
                $arrPageData['strReplace'] = "";
                $arrPageData['strPatTestDate'] = "";
                $arrPageData['intPatTestStatus'] = "";

                $arrPageData['booDisplayPhotoForm'] = true;
                $booAdvanceToNextOne = false;

// is there a submission?
                if ($this->input->post()) {
                    $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFields($this->input->post('category_id'));
//okay test the data
                    $this->form_validation->set_rules('category_id', 'Category', 'required|is_natural_no_zero');
                    $this->form_validation->set_rules('status_id', 'Status', 'required|is_natural_no_zero');
//                    $this->form_validation->set_rules('user_id', 'Owner', 'required|is_natural_no_zero');
                    $this->form_validation->set_rules('item_make', 'Make', 'trim|required');
                    $this->form_validation->set_rules('item_model', 'Model', 'trim|required');
//                    $this->form_validation->set_rules('item_barcode', 'Barcode', 'trim|required');
//                    $this->form_validation->set_rules('item_barcode', 'Barcode', 'trim|required|is_unique[items.barcode]');
                    $this->form_validation->set_rules('location_id', 'Location', 'callback_ownershipset_check');
                    $this->form_validation->set_rules('site_id', 'Site', 'callback_ownershipset_check');
//                    $this->form_validation->set_rules('user_id', 'Owner', 'callback_ownershipset_check');
                    $this->form_validation->set_rules('item_purchased', 'Purchase Date', 'callback_isvaliddate');
                    $this->form_validation->set_rules('item_warranty', 'Warranty Expires', 'callback_isvaliddate');
                    $this->form_validation->set_rules('item_replace', 'Replacement Date', 'callback_isvaliddate');
                    $this->form_validation->set_rules('item_pattestdate', 'PAT Date', 'callback_isvaliddate');
                    $this->form_validation->set_rules('item_patteststatus', 'PAT Status', 'callback_patstatusset_check[' . $this->input->post('item_pattestdate') . ']');

                    if (TRUE) {
                        $bool = $this->items_model->checkBarcodeForItem($this->input->post('item_barcode'));

                        if (!$bool) {
                            $strCurrentValue = $this->input->post('item_current_value');
                            if ($this->input->post('item_current_value') == "") {
                                $strCurrentValue = $this->input->post('item_value');
                            }

                            if ($this->input->post('item_make') != '') {
                                $manufacturer = $this->input->post('item_make');
                                if ($manufacturer != '') {
                                    $data = array(
                                        'manufacturer_name' => $manufacturer,
                                        'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                                    );

                                    $this->admin_section_model->addManufacturer($data);
                                }
                            } else {
                                $manufacturer = $this->input->post('manufacturer');
                            }
                            if ($this->input->post('new_item') != '') {
                                $item_new_manu = $this->input->post('new_item');
                                if ($item_new_manu != '') {
                                    $data = array(
                                        'item_manu_name' => $item_new_manu,
                                        'account_id' => $arrPageData['arrSessionData']['objSystemUser']->accountid,
                                    );

                                    $item_manu = $this->admin_section_model->addItem_Manu($data);
                                }
                            } else {
                                $item_manu = $this->input->post('manu');
                            }

                            $arrItemData = array(
                                'barcode' => $this->session->userdata('objSystemUser')->qrcode . $this->input->post('item_barcode'),
                                'serial_number' => $this->input->post('item_serial_number'),
                                'manufacturer' => $manufacturer,
                                'item_manu' => $item_manu,
                                'model' => $this->input->post('item_model'),
                                'site' => $this->input->post('site_id'),
                                'account_id' => $this->session->userdata('objSystemUser')->accountid,
                                'value' => $this->input->post('item_value'),
                                'current_value' => $strCurrentValue,
                                'notes' => $this->input->post('item_notes'),
                                'status_id' => $this->input->post('status_id'),
                                'owner_now' => $this->input->post('owner_id'),
                                'owner_since' => date('Y-m-d H:i:s'),
                                'condition_now' => $this->input->post('item_condition'),
                                'condition_since' => date('Y-m-d H:i:s'),
                                'purchase_date' => $this->doFormatDate($this->input->post('item_purchased')),
                                'warranty_date' => $this->doFormatDate($this->input->post('add_warranty_date')),
                                'replace_date' => $this->doFormatDate($this->input->post('item_replace')),
                                'pattest_date' => $this->doFormatDate($this->input->post('item_pattestdate')),
                                'pattest_status' => $this->input->post('item_patteststatus'),
                                'quantity' => $this->input->post('item_quantity'),
                                'compliance_start' => $this->doFormatDate($this->input->post('compliance_start')),
                                'supplier' => $this->input->post('supplier')
                            );

                            $mixNewItemId = $this->items_model->addOne($arrItemData, $this->input->post('category_id'), $this->input->post('location_id'), $this->session->userdata('objSystemUser')->userid, $this->input->post('site_id'));
                            if ($mixNewItemId) {
// entry in ticket table if item is faults/damaged
                                if ($this->input->post('status_id') == 2 || $this->input->post('status_id') == 3) {
                                    $data = array(
                                        'item_id' => $mixNewItemId,
                                        'user_id' => $this->session->userdata('objSystemUser')->userid,
                                        'date' => date("Y-m-d H:i:s"),
                                        'ticket_action' => "Open Job",
                                        'status' => $this->input->post('status_id'),
                                    );
                                    $this->tickets_model->insertTicket($data);
                                }
// Log it first
                                $this->logThis("Added Item", "items", $mixNewItemId);

//                                Add Condition 
                                if ($this->input->post('item_condition')) {
                                    $this->items_model->logConditionHistory($mixNewItemId, $this->input->post('item_condition'));
                                }
// Add Pat History
                                if ($this->input->post('item_patteststatus')) {

                                    $this->items_model->linkThisToPat($mixNewItemId, $this->input->post('item_patteststatus'), $this->session->userdata('objSystemUser')->userid);
                                }


                                $intPhotoError = 0;

                                if (array_key_exists('photo_file_1', $_FILES) && ($_FILES['photo_file_1']['size'] > 0)) {
                                    $arrConfig['upload_path'] = './uploads/';
                                    $arrConfig['allowed_types'] = 'gif|jpg|png';
                                    $arrConfig['max_size'] = '0';
                                    $arrConfig['max_width'] = '0';
                                    $arrConfig['max_height'] = '0';

// load helper
                                    $this->load->library('upload', $arrConfig);

// photo upload done
                                    for ($i = 1; $i < count($_FILES); $i++) {
                                        if ($this->upload->do_upload('photo_file_' . $i)) {
                                            $strPhotoTitle = "Item Picture";
                                            if ($this->input->post('photo_title') != "") {
                                                $strPhotoTitle = $this->input->post('photo_title');
                                            }
                                            $intPhotoId[] = $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "item/default");

                                            $arrPageData['intPhotoId'] = $intPhotoId;
                                        } else {


                                            $intPhotoError = 1;
                                        }
                                    }
                                    $intPhotoId = implode(',', $intPhotoId);

                                    $this->items_model->setPhoto($mixNewItemId, $intPhotoId);
                                } else {
                                    if ($this->input->post('item_photo_id') != '') {
                                        $this->items_model->setPhoto($mixNewItemId, $this->input->post('item_photo_id'));
                                        $arrPageData['intPhotoId'] = $this->input->post('item_photo_id');
                                    }
                                }
                                if (array_key_exists('pdf_file', $_FILES) && ($_FILES['pdf_file']['size'] > 0)) {
                                    $pdfObj = $_FILES['pdf_file'];
                                    $file = $pdfObj["tmp_name"];

                                    $ext = end((explode(".", $pdfObj['name'])));

// Remove space From Pdf Name
                                    $strFileName = preg_replace('/[^a-zA-Z0-9]+/', '', $pdfObj['name']);

                                    $strFileName = $strFileName . time() . '.' . $ext;
//        $strFileName = time().$_FILES['file']['name'];

                                    $item_id = $mixNewItemId;
                                    $item_manu = $this->items_model->get_item_manu($item_id);
                                    if ($item_manu) {
                                        $itemID = $this->items_model->get_item_id($item_manu);
                                    }
                                    foreach ($itemID as $item_val) {
                                        $item_id = $item_val['id'];
                                        $this->items_model->update_file($item_id, $strFileName);
                                    }
                                    $this->load->library('s3');
                                    $this->s3->putObjectFile($file, "smartaudit", 'youaudit/' . $item_manu . '/' . $strFileName, S3::ACL_PUBLIC_READ);
                                } else {
                                    
                                }

                                $item_id_var = (int) $this->input->post('item_id');

                                $boolcheck = ($item_id_var > 1);

                                if ($boolcheck == 1) {

                                    $barcodePrevioysItem = $this->items_model->getBarcodeForId($item_id_var, $this->session->userdata('objSystemUser')->accountid);
                                    $doc_name = $this->items_model->getDocByBarcode($barcodePrevioysItem[0]->barcode, $this->session->userdata('objSystemUser')->accountid);

                                    if ($doc_name) {
                                        $pre_itemid = $doc_name[0]->itemid;
                                        $pdf_name = $doc_name[0]->pdf_name;

                                        $this->load->library('s3');
                                        if ($this->s3->copyObject('smartaudit', 'youaudit/' . $pre_itemid . '/' . $pdf_name, 'smartaudit', 'youaudit/' . $mixNewItemId . '/' . $pdf_name, S3::ACL_PUBLIC_READ)) {
                                            $this->items_model->update_file($mixNewItemId, $pdf_name);
                                        } else {
//                                            echo "Failed to copy file";
//                                            die;
                                        }
                                    }
                                }

                                /* Handle custom field data IF category has NOT changed. Otherwise ignore this! */

                                /* Enter new data by going through the POST input and extracting those fields that match the custom fields */
                                foreach ($this->input->post() as $k => $v) {

                                    foreach ($arrPageData['arrCustomFields'] as $field) {
                                        if ($k == $field->id) {
                                            $custom_data[$field->id] = $v;
                                        }
                                    }
                                }

                                if ($custom_data) {
                                    $this->customfields_model->insertContentByItem($mixNewItemId, $custom_data);
                                }


                                $this->session->set_userdata('booCourier', true);
                                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The item was successfully added')));

                                if ($intPhotoError > 0) {
                                    $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('The photo failed to upload')));
                                }
//                                var_dump($this->input->post('add_another'));die;
                                if ($this->input->post('add_another') != false) {
//                                    $mixItemsData['similar'] = $this->items_model->basicGetOne($mixNewItemId, $this->session->userdata('objSystemUser')->accountid);     
                                    $this->session->set_flashdata('item', $mixNewItemId);
                                    redirect('/items/filter/', 'refresh');
                                }

                                if ($this->input->post('add_ownership') != false) {
                                    $this->session->set_flashdata('ownership', '1');
                                    redirect('/items/filter/', 'refresh');
                                } else {
                                    redirect('/items/filter/', 'refresh');
                                    $booAdvanceToNextOne = true;
                                    $arrPageData['arrSessionData'] = $this->session->userdata;
                                    $this->session->set_userdata('booCourier', false);
                                    $this->session->set_userdata('arrCourier', array());
                                }
                            } else {
                                $arrPageData['arrErrorMessages'][] = "Unable to add the item.";
                            }
                        } else {
                            $arrPageData['barcodeMessages'] = "BarCode Should Be Unique";
                            $arrPageData['arrErrorMessages'][] = "Please check the form for errors.";
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('BarCode Should Be Unique')));
                        }
                    } else {
                        $arrPageData['arrErrorMessages'][] = "Please check the form for errors.";
                    }

                    if ($mixNewItemId) {
                        $arrPageData['intitemId'] = $mixNewItemId;
                    } else {
                        $arrPageData['intitemId'] = 1;
                    }

                    $arrPageData['intCategoryId'] = $this->input->post('category_id');
                    $arrPageData['intSiteId'] = $this->input->post('site_id');
                    $arrPageData['intLocationId'] = $this->input->post('location_id');
                    $arrPageData['intUserId'] = $this->input->post('user_id');
                    $arrPageData['strMake'] = $this->input->post('item_make');
                    $arrPageData['strModel'] = $this->input->post('item_model');
                    $arrPageData['strSerialNumber'] = $this->input->post('item_serial_number');
                    $arrPageData['strBarcode'] = $this->input->post('item_barcode');
                    $arrPageData['strValue'] = $this->input->post('item_value');
                    $arrPageData['strCurrentValue'] = $this->input->post('item_current_value');
                    $arrPageData['strNotes'] = $this->input->post('item_notes');
                    $arrPageData['intItemStatusId'] = $this->input->post('status_id');
                    $arrPageData['strPurchased'] = $this->input->post('item_purchased');
                    $arrPageData['strWarranty'] = $this->input->post('item_warranty');
                    $arrPageData['strReplace'] = $this->input->post('item_replace');
                    $arrPageData['strPatTestDate'] = $this->input->post('item_pattestdate');
                    $arrPageData['intPatTestStatus'] = $this->input->post('item_patteststatus');

                    if ($booAdvanceToNextOne) {
                        $arrPageData['strSerialNumber'] = "";
                        $arrPageData['strBarcode'] = "";
                        $arrPageData['booDisplayPhotoForm'] = false;

                        if (!array_key_exists('intPhotoId', $arrPageData)) {
                            $arrPageData['intPhotoId'] = 1;
                        }
                    }
                }
            } else {
                $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
                $arrPageData['strPageTitle'] = "Security Check Point";
                $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "Account Limit.";
            $arrPageData['strPageTitle'] = "Account Limit";
            $arrPageData['strPageText'] = "You have reached your account limit, please upgrade your account to add more.";
        }

// load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission) {
//load the correct view
            $this->load->view('items/addmultiple', $arrPageData);
            $this->load->view('items/forms/add', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function addOne() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/addone/');
            redirect('users/login/', 'refresh');
        }

// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Add an Item";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

// load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.addOne");

        if ($booPermission) {
            $this->load->model('categories_model');
            $this->load->model('sites_model');
            $this->load->model('items_model');
            $this->load->model('locations_model');
            $this->load->model('photos_model');
            $this->load->model('itemstatus_model');
            $this->load->model('accounts_model');
// helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
            $arrPageData['arrCategories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAll();
            $arrPageData['intCategoryId'] = 0;
            $arrPageData['intSiteId'] = 0;
            $arrPageData['intLocationId'] = 0;
            $arrPageData['intUserId'] = 0;
            $arrPageData['strMake'] = "";
            $arrPageData['strModel'] = "";
            $arrPageData['strSerialNumber'] = "";
            $arrPageData['strBarcode'] = "";
            $arrPageData['strValue'] = "0.00";
            $arrPageData['strNotes'] = "";
            $arrPageData['intItemStatusId'] = 1;
            $arrPageData['strPurchased'] = "";
            $arrPageData['strWarranty'] = "";
            $arrPageData['strReplace'] = "";
            $arrPageData['strPatTestDate'] = "";
            $arrPageData['intPatTestStatus'] = "";

            $arrPageData['booDisplayPhotoForm'] = true;

// is there a submission?
            if ($this->input->post('submit')) {
//okay test the data
                $this->form_validation->set_rules('category_id', 'Category', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('status_id', 'Status', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('user_id', 'Owner', 'required|is_natural_no_zero');
//                $this->form_validation->set_rules('item_make', 'Make', 'trim|required');
                $this->form_validation->set_rules('item_make', 'Make', 'required');
                $this->form_validation->set_rules('item_model', 'Model', 'trim|required');
                $this->form_validation->set_rules('item_barcode', 'Barcode', 'trim|required|is_unique[items.barcode]');
                $this->form_validation->set_rules('location_id', 'Location', 'callback_siteset_check[' . $this->input->post('site_id') . ']');
                $this->form_validation->set_rules('user_id', 'Owner', 'callback_siteset_check[' . $this->input->post('site_id') . ']');
                $this->form_validation->set_rules('item_purchased', 'Purchase Date', 'callback_isvaliddate');
                $this->form_validation->set_rules('item_warranty', 'Warranty Expires', 'callback_isvaliddate');
                $this->form_validation->set_rules('item_replace', 'Replacement Date', 'callback_isvaliddate');
                $this->form_validation->set_rules('item_pattestdate', 'PAT Date', 'callback_isvaliddate');
                $this->form_validation->set_rules('item_patteststatus', 'PAT Status', 'callback_patstatusset_check[' . $this->input->post('item_pattestdate') . ']');
                echo validation_errors();
                if ($this->form_validation->run()) {
                    $arrItemData = array(
                        'barcode' => $this->input->post('item_barcode'),
                        'serial_number' => $this->input->post('item_serial_number'),
                        'manufacturer' => $this->input->post('item_make'),
                        'model' => $this->input->post('item_model'),
                        'site' => $this->input->post('site_id'),
                        'account_id' => $this->session->userdata('objSystemUser')->accountid,
                        'value' => $this->input->post('item_value'),
                        'notes' => $this->input->post('item_notes'),
                        'status_id' => $this->input->post('status_id'),
                        'purchase_date' => $this->doFormatDate($this->input->post('item_purchased')),
                        'warranty_date' => $this->doFormatDate($this->input->post('item_warranty')),
                        'replace_date' => $this->doFormatDate($this->input->post('item_replace')),
                        'pattest_date' => $this->doFormatDate($this->input->post('item_pattestdate')),
                        'pattest_status' => $this->input->post('item_patteststatus')
                    );




                    $mixNewItemId = $this->items_model->addOne($arrItemData, $this->input->post('category_id'), $this->input->post('location_id'), $this->input->post('user_id'), $this->input->post('site_id'));
                    if ($mixNewItemId) {
// Log it first
                        $this->logThis("Added Item", "items", $mixNewItemId);
                        $intPhotoError = 0;

                        if (array_key_exists('photo_file', $_FILES) && ($_FILES['photo_file']['size'] > 0)) {
                            $arrConfig['upload_path'] = './uploads/';
                            $arrConfig['allowed_types'] = 'gif|jpg|png';
                            $arrConfig['max_size'] = '0';
                            $arrConfig['max_width'] = '0';
                            $arrConfig['max_height'] = '0';

// load helper
                            $this->load->library('upload', $arrConfig);

// photo upload done
                            if ($this->upload->do_upload('photo_file')) {
                                $strPhotoTitle = "Item Picture";
                                if ($this->input->post('photo_title') != "") {
                                    $strPhotoTitle = $this->input->post('photo_title');
                                }
                                $this->items_model->setPhoto($mixNewItemId, $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "item/default"));
                            } else {

                                $intPhotoError = 1;
                            }
                        }

// Pat Status History Link 
                        if ($this->input->post('item_patteststatus')) {
//                            echo "SDfsdfsfs";
//                            die;
                            $this->items_model->linkThisToPat($mixNewItemId, $this->input->post('item_patteststatus'), $this->input->post('user_id'));
                        }

                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The item was successfully added')));



                        if ($intPhotoError > 0) {
                            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('The photo failed to upload')));
                        }

                        redirect('/items/index/', 'refresh');
                    } else {
                        $arrPageData['arrErrorMessages'][] = "Unable to add the item.";
                    }
                } else {
                    $arrPageData['arrErrorMessages'][] = "Please check the form for errors.";
                }


                $arrPageData['intCategoryId'] = $this->input->post('category_id');
                $arrPageData['intSiteId'] = $this->input->post('site_id');
                $arrPageData['intLocationId'] = $this->input->post('location_id');
                $arrPageData['intUserId'] = $this->input->post('user_id');
                $arrPageData['strMake'] = $this->input->post('item_make');
                $arrPageData['strModel'] = $this->input->post('item_model');
                $arrPageData['strSerialNumber'] = $this->input->post('item_serial_number');
                $arrPageData['strBarcode'] = $this->input->post('item_barcode');
                $arrPageData['strValue'] = $this->input->post('item_value');
                $arrPageData['strNotes'] = $this->input->post('item_notes');
                $arrPageData['intItemStatusId'] = $this->input->post('status_id');
                $arrPageData['strPurchased'] = $this->input->post('item_purchased');
                $arrPageData['strWarranty'] = $this->input->post('item_warranty');
                $arrPageData['strReplace'] = $this->input->post('item_replace');
                $arrPageData['strPatTestDate'] = $this->input->post('item_pattestdate');
                $arrPageData['intPatTestStatus'] = $this->input->post('item_patteststatus');
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

// load views
        $this->load->view('common/header', $arrPageData);
        if ($booPermission) {
//load the correct view
            $this->load->view('items/add', $arrPageData);
            $this->load->view('items/forms/add', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function raiseTicket($intId = -1) {


        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/raiseticket/' . $intId . '/');
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

// helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        $booSuccess = false;

        $arrPageData['strMessageTitle'] = "";
        $arrPageData['strMessageBody'] = "";

        if ($intId > 0) {
            $mixItemsData = $this->items_model->basicGetOne($intId, $this->session->userdata('objSystemUser')->accountid);

            if ($mixItemsData) {
                $arrPageData['objItem'] = $mixItemsData[0];
                $booSuccess = true;

                /* if category has support user ID, get user email */
                if ($category_support) {
                    $user_data = $this->users_model->getOneWithoutAccount($mixItemsData[0]->category_user_id);
                    $category_support = $mixItemsData[0]->support_emails;
                }

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
                    $strMessageBodyItemData .= "PRIORITY LEVEL: " . $priorities[$priority_level] . "\r\n";


//okay try to build the email
                    $this->load->library('email');
                    $this->email->from("tickets@iworkaudit.com", "iWork Audit Ticket");
                    $strSupportAddress = $this->accounts_model->getSupportEmailAddress($this->session->userdata('objSystemUser')->accountid);

                    /* If category user is set */
                    if ($category_support) {

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

                    if ($this->email->send()) {
                        $this->tickets_model->ticketSubmission($intId, $this->session->userdata('objSystemUser')->userid, $this->input->post('message_body'), $priority_level);
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The ticket was successfully sent')));
                        redirect('/items/view/' . $intId, 'refresh');
                    } else {
                        $arrPageData['arrErrorMessages'][] = "Unable to send ticket.";
                    }



                    $arrPageData['strMessageTitle'] = $this->input->post('message_title');
                    $arrPageData['strMessageBody'] = $this->input->post('message_body');
                }
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
            $this->load->view('items/ticket', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function edit($intId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/editone/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        $this->load->library('s3');
        $data = array();
        $bucket_content = $this->s3->getBucket('smartaudit', 'youaudit/' . $intId);
        foreach ($bucket_content as $key => $value) {
// ignore s3 "folders"
            if (preg_match("/\/$/", $key))
                continue;

// explode the path into an array
            $file_path = explode('/', $key);
            $file_name = end($file_path);
            $file_folder = substr($key, 0, (strlen($file_name) * -1) + 1);
            $file_folder = prev($file_path);

            $s3_url = "https://smartaudit.s3.amazonaws.com/{$key}";
//            if ($file_folder == $intItemId) {
//                echo $key;
            $data[$key] = array(
                'file_name' => $file_name,
                's3_key' => $key,
                'file_folder' => $file_folder,
                'file_size' => $value['size'],
                'created_on' => date('Y-m-d H:i:s', $value['time']),
                's3_link' => $s3_url,
                'md5_hash' => $value['hash']);
//            }
        }


// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit an Item";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $arrPageData['pdf_number'] = $data;
// load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.editOne");
        $booSuccess = false;
        if ($booPermission) {
            $this->load->model('suppliers_model');
            $this->load->model('categories_model');
            $this->load->model('sites_model');
            $this->load->model('tickets_model');
            $this->load->model('locations_model');
            $this->load->model('items_model');
            $this->load->model('photos_model');
            $this->load->model('itemstatus_model');
            $this->load->model('accounts_model');
            $this->load->model('customfields_model');

// helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
            if ($intId > 0) {
                $mixItemsData = $this->items_model->basicGetOne($intId, $this->session->userdata('objSystemUser')->accountid);

                if ($mixItemsData) {
                    $arrPageData['objItem'] = $mixItemsData[0];
                    $booSuccess = true;
                    $arrPageData['arrCategories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
                    $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
                    $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
                    $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAll();
                    $arrPageData['arrSuppliers'] = $this->suppliers_model->getAll();
                    $arrPageData['arrUsers'] = $this->users_model->getAllForPullDown($this->session->userdata('objSystemUser')->accountid);
                    $arrPageData['intCategoryId'] = $mixItemsData[0]->categoryid;
                    $arrPageData['intLocationId'] = $mixItemsData[0]->locationid;
                    $arrPageData['intUserId'] = $mixItemsData[0]->userid;
                    $arrPageData['intSiteId'] = $mixItemsData[0]->siteid;
                    $arrPageData['strMake'] = htmlentities($mixItemsData[0]->manufacturer);
                    $arrPageData['strModel'] = htmlentities($mixItemsData[0]->model);
                    $arrPageData['strSerialNumber'] = $mixItemsData[0]->serial_number;
                    $arrPageData['strValue'] = $mixItemsData[0]->value;
                    $arrPageData['strCurrentValue'] = $mixItemsData[0]->current_value;
                    $arrPageData['strBarcode'] = $mixItemsData[0]->barcode;
                    $arrPageData['strNotes'] = $mixItemsData[0]->notes;
                    $arrPageData['intItemStatusId'] = $mixItemsData[0]->itemstatusid;
                    $arrPageData['strPurchased'] = $this->doFormatDateBack($mixItemsData[0]->purchase_date);
                    $arrPageData['strWarranty'] = $this->doFormatDateBack($mixItemsData[0]->warranty_date);
                    $arrPageData['strReplace'] = $this->doFormatDateBack($mixItemsData[0]->replace_date);
                    $arrPageData['strComplianceStart'] = $this->doFormatDateBack($mixItemsData[0]->compliance_start);
                    $arrPageData['strPatTestDate'] = $this->doFormatDateBack($mixItemsData[0]->pattest_date);
                    $arrPageData['intPatTestStatus'] = $mixItemsData[0]->pattest_status;
                    $arrPageData['supplier_id'] = $mixItemsData[0]->supplier;
                    $arrPageData['intQuantity'] = $mixItemsData[0]->quantity;
                    $arrPageData['quantity_enabled'] = $mixItemsData[0]->quantity_enabled;
                    $arrPageData['intItemId'] = $intId;
                    $arrPageData['booDisplayPhotoForm'] = true;

                    /* Load custom fields */

                    $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFields($mixItemsData[0]->categoryid);

                    $arrPageData['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItem($intId);


                    if ($arrPageData['arrCustomFields'] && $arrPageData['arrCustomFieldsContent']) {
                        foreach ($arrPageData['arrCustomFields'] as $key => $value) {
                            foreach ($arrPageData['arrCustomFieldsContent'] as $k => $v) {
                                if ($v->custom_field_id == $value->id) {
                                    $arrPageData['arrCustomFields'][$key]->content = $v->content;
                                }
                            }
                        }
                    }

// is there a submission?
                    if ($this->input->post()) {

//okay test the data
                        $this->form_validation->set_rules('category_id', 'Category', 'required|is_natural_no_zero');
//                        $this->form_validation->set_rules('status_id', 'Status', 'required|is_natural_no_zero');
                        $this->form_validation->set_rules('item_make', 'Manufacturer', 'trim');
                        $this->form_validation->set_rules('item_model', 'Model', 'trim');
//                        if ($this->input->post('item_barcode') != $mixItemsData[0]->barcode) {
//                            $this->form_validation->set_rules('item_barcode', 'Barcode', 'trim|required|is_unique[items.barcode]');
//                        }
                        $this->form_validation->set_rules('location_id', 'Location', 'callback_ownershipset_check');
                        $this->form_validation->set_rules('owner_id', 'Owner', 'callback_ownershipset_check');
                        $this->form_validation->set_rules('site_id', 'Site', 'callback_ownershipset_check');
//                        $this->form_validation->set_rules('item_purchased', 'Purchase Date', 'callback_isvaliddate');
//                        $this->form_validation->set_rules('item_warranty', 'Warranty Expires', 'callback_isvaliddate');
//                        $this->form_validation->set_rules('item_replace', 'Replacement Date', 'callback_isvaliddate');
//                        $this->form_validation->set_rules('item_pattestdate', 'PAT Date', 'callback_isvaliddate');
//                        $this->form_validation->set_rules('item_patteststatus', 'PAT Status', 'callback_patstatusset_check[' . $this->input->post('item_pattestdate') . ']');

                        if ($this->form_validation->run()) {
                            $strCurrentValue = $this->input->post('item_current_value');
                            if ($this->input->post('item_current_value') == "") {
                                $strCurrentValue = $this->input->post('item_value');
                            }
                            if ($this->input->post('item_pattestdate')) {
                                $pat_date = $this->input->post('item_pattestdate');
                            }
                            if ($this->input->post('item_pattestdate') == NULL) {
                                $pat_date = date('d/m/Y');
                            }
                            if ($this->input->post('item_quantity') == NULL) {
                                $quantity = 1;
                            } else {
                                $quantity = $this->input->post('item_quantity');
                            }
                            if ($this->input->post('status_id') == NULL) {
                                $status = 1;
                            } else {
                                $status = $this->input->post('status_id');
                            }
                            if ($this->input->post('item_condition') == NULL) {
                                $condition = 1;
                            } else {
                                $condition = $this->input->post('item_condition');
                            }


                            $arrItemData = array(
                                'serial_number' => $this->input->post('item_serial_number'),
                                'barcode' => $this->session->userdata('objSystemUser')->qrcode . $this->input->post('item_barcode'),
                                'manufacturer' => $this->input->post('item_make'),
                                'model' => $this->input->post('item_model'),
                                'supplier' => $this->input->post('supplier'),
                                'value' => $this->input->post('item_value'),
                                'current_value' => $strCurrentValue,
                                'notes' => $this->input->post('item_notes'),
                                'status_id' => $status,
                                'purchase_date' => $this->doFormatDate($this->input->post('item_purchased')),
                                'warranty_date' => $this->doFormatDate($this->input->post('item_warranty')),
                                'replace_date' => $this->doFormatDate($this->input->post('item_replace')),
                                'pattest_date' => $this->doFormatDate($pat_date),
                                'compliance_start' => $this->doFormatDate($this->input->post('compliance_start')),
                                'quantity' => $quantity,
                                'item_manu' => $this->input->post('item_manu'),
//                                'owner_now' => $this->input->post('owner_id'),
                                'owner_since' => date('Y-m-d H:i:s'),
                                'condition_now' => $condition,
                                'condition_since' => date('Y-m-d H:i:s'),
                            );

                            if ($this->input->post('owner_id') > 0 && $this->input->post('owner_id') != $this->session->userdata('ownerName')) {
                                $arrItemData['owner_now'] = $this->input->post('owner_id');
                            }

//                            if ($this->input->post('item_patteststatus')) {
//                                echo $this->input->post('item_patteststatus')."if";die;
                            if ($this->session->userdata('pattestStatus') != $this->input->post('item_patteststatus')) {
                                $arrItemData['pattest_status'] = $this->input->post('item_patteststatus');
                            }
//                            } else {
//                                echo $this->input->post('item_patteststatus')."else";die;
//                                $arrItemData['pattest_status'] = 5;
//                                $arrItemData['pattest_status'] = null;
//                                $arrItemData['pattest_date'] = null;
//                            }
//                            if ($this->input->post('item_barcode') != $mixItemsData[0]->barcode) {
//                                $arrItemData['barcode'] = $this->input->post('item_barcode');
//                            }


                            if ($this->items_model->editOne($arrItemData, $intId, $this->input->post('category_id'), $this->input->post('location_id'), $this->session->userdata('objSystemUser')->userid, $this->input->post('site_id'))) {

// entry in ticket table if item is faults/damaged
                                if ($this->input->post('status_id') == 2 || $this->input->post('status_id') == 3) {
                                    $data = array(
                                        'item_id' => $intId,
                                        'user_id' => $this->session->userdata('objSystemUser')->userid,
                                        'date' => date("Y-m-d H:i:s"),
                                        'ticket_action' => "Open Job",
                                        'status' => $this->input->post('status_id'),
                                    );
                                    $this->tickets_model->insertTicket($data);
                                }

// Log it first
                                $this->logThis("Edited Item", "items", $intId);
                                $intPhotoError = 0;


//                                Add Condition 
                                $condition = $this->db->select('condition_id')->where('item_id', $intId)->order_by('id', 'desc')->limit(1)->get('item_condition_history_link')->row();

                                if ($condition->condition_id != $this->input->post('item_condition')) {
                                    $this->items_model->logConditionHistory($intId, $this->input->post('item_condition'));
                                }

// Add Pat History
//                                if ($this->input->post('item_patteststatus')) {
//                                    echo $this->session->userdata('pattestStatus')."post".$this->input->post('item_patteststatus');die;
                                if ($this->session->userdata('pattestStatus') != $this->input->post('item_patteststatus')) {
                                    $this->session->unset_userdata('pattestStatus');
                                    $this->items_model->linkThisToPat($intId, $this->input->post('item_patteststatus'), $this->input->post('owner_id'));
                                }
//                                     $this->session->unset_userdata('pattestStatus');
//                                    }

                                if (array_key_exists('photo_file', $_FILES) && ($_FILES['photo_file']['size'] > 0)) {
                                    $arrConfig['upload_path'] = './uploads/';
                                    $arrConfig['allowed_types'] = 'gif|jpg|png|jpeg';
                                    $arrConfig['max_size'] = '0';
                                    $arrConfig['max_width'] = '0';
                                    $arrConfig['max_height'] = '0';

// load helper
                                    $this->load->library('upload', $arrConfig);

// photo upload done
                                    if ($this->upload->do_upload('photo_file')) {
                                        $strPhotoTitle = "Item Picture";
                                        if ($this->input->post('photo_title') != "") {
                                            $strPhotoTitle = $this->input->post('photo_title');
                                        }
                                        $this->items_model->setPhoto($intId, $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "item/default"));
                                    } else {


                                        $intPhotoError = 1;
                                    }
                                }

                                /* Handle custom field data IF category has NOT changed. Otherwise ignore this! */

                                if ($arrPageData['intCategoryId'] != $this->input->post('category_id')) {
                                    $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFields($this->input->post('category_id'));
                                }
                                /* Remove previous data */
                                $this->customfields_model->removeContentByItem($intId);

                                /* Enter new data by going through the POST input and extracting those fields that match the custom fields */
                                foreach ($this->input->post() as $k => $v) {
                                    foreach ($arrPageData['arrCustomFields'] as $field) {
                                        if ($k == $field->id) {
                                            $custom_data[$field->id] = $v;
                                        }
                                    }
                                }

                                if ($custom_data) {
                                    $this->customfields_model->insertContentByItem($intId, $custom_data);
                                }

                                /* If item is in a category with item quanties enabled, check to see if quantity has changed. If so, log the change */
                                if ($this->input->post('item_quantity') != $mixItemsData[0]->quantity) {
                                    if ($this->input->post('item_quantity') > $mixItemsData[0]->quantity) {
                                        $message = "Item quantity increased from " . $mixItemsData[0]->quantity . " to " . $this->input->post('item_quantity');
                                    } elseif ($this->input->post('item_quantity') < $mixItemsData[0]->quantity) {
                                        $message = "Item quantity reduce from " . $mixItemsData[0]->quantity . " to " . ($this->input->post('item_quantity') == '' ? '0' : $this->input->post('item_quantity'));
                                    } else {
                                        $message = "Item quantity removed";
                                    }
                                    $this->items_model->insertLog($intId, $message);
                                }

                                $this->session->set_userdata('booCourier', true);
                                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The item was successfully updated')));
                                redirect('/items/filter', 'refresh');
                            } else {

                                $arrPageData['arrErrorMessages'][] = "Unable to update the item.";
                                redirect('/items/view/' . $intId, 'refresh');
                            }
                        } else {
                            $this->session->set_userdata('booCourier', true);
//                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The item was Could not successfully updated')));

                            $arrPageData['arrErrorMessages'][] = "Please check the form for errors.";
                        }

                        $arrPageData['intCategoryId'] = $this->input->post('category_id');
                        $arrPageData['intSiteId'] = $this->input->post('site_id');
                        $arrPageData['intLocationId'] = $this->input->post('location_id');
                        $arrPageData['intUserId'] = $this->input->post('user_id');
                        $arrPageData['strMake'] = $this->input->post('item_make');
                        $arrPageData['strModel'] = $this->input->post('item_model');
                        $arrPageData['strSerialNumber'] = $this->input->post('serial_number');
//                        $arrPageData['strBarcode'] = $this->input->post('item_barcode');
                        $arrPageData['strValue'] = $this->input->post('item_value');
                        $arrPageData['strCurrentValue'] = $this->input->post('item_current_value');
                        $arrPageData['strNotes'] = $this->input->post('item_notes');
                        $arrPageData['intItemStatusId'] = $this->input->post('status_id');
                        $arrPageData['strPurchased'] = $this->input->post('item_purchased');
                        $arrPageData['strWarranty'] = $this->input->post('item_warranty');
                        $arrPageData['strReplace'] = $this->input->post('item_replace');
                        $arrPageData['strPatTestDate'] = $this->input->post('item_pattestdate');
                        $arrPageData['intPatTestStatus'] = $this->input->post('item_patteststatus');
                    }
                }
//if mixitemsdata
            }
//if intItems
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        if (!$booSuccess) {
            $arrPageData['arrErrorMessages'][] = "Item Not Found.";
            $arrPageData['strPageTitle'] = "System Error";
            $arrPageData['strPageText'] = "We couldn't find that item.";
        }

// load views
//        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booSuccess) {
//            $this->load->view('common/header', 	$arrPageData);
//            $this->load->view('items/view/'.$intId, $arrPageData);
//            $this->load->view('common/footer', 	$arrPageData);
//            echo 'asd';die;
            $this->view($intId);
//            redirect('/items/view/' . $intId);
//load the correct view
//            $this->load->view('items/edit', $arrPageData);
//            $this->load->view('items/forms/add', $arrPageData);
        } else {
            $this->view($intId);
//            redirect('/items/view/' . $intId);
//            $this->load->view('common/header', 	$arrPageData);
//            $this->load->view('items/view/'.$intId, $arrPageData);
//            $this->load->view('common/footer', 	$arrPageData);
//            $this->load->view('common/system_message', $arrPageData);
        }
//        $this->load->view('common/footer', $arrPageData);
    }

    public function siteSet_check($intCheck, $intSiteId) {
        if (($intCheck == 0) && ($intSiteId == 0)) {
            $this->form_validation->set_message('siteset_check', 'This field must be set if the item doesn\'t belong to a site.');
            return false;
        } else {
            if (($intCheck > 0) && ($intSiteId > 0)) {
                $this->form_validation->set_message('siteset_check', 'This field must be blank if the site is set.');
                return false;
            } else {
                return true;
            }
        }
    }

    public function ownershipSet_check($intCheck) {
        if (($this->input->post('owner_id') == 0) && ($this->input->post('site_id') == 0) && ($this->input->post('location_id') == 0)) {
            $this->form_validation->set_message('ownershipset_check', 'At least one must be set.');
            return false;
        } else {

            return true;
        }
    }

    public function patstatusSet_check($intCheck, $strDate) {

        if ((($intCheck > -1) && ($intCheck < 5)) && ($strDate == "")) {
            $this->form_validation->set_message('patstatusset_check', 'This status cannot be set without a date.');
            return false;
        } else {
            if (($strDate == "") && (($intCheck > -1) && ($intCheck < 5))) {
                $this->form_validation->set_message('patstatusset_check', 'This status must be set if a date is supplied for the test.');
                return false;
            } else {
                return true;
            }
        }
    }

    public function isValidDate($strDate) {
        if ($strDate != "") {
            $arrDate = explode('/', $strDate);
            if ((count($arrDate) != 3) || (!checkdate($arrDate[1], $arrDate[0], $arrDate[2]))) {
                $this->form_validation->set_message('isvaliddate_check', 'This is an invalid date.');

                return false;
            }
        }
        return true;
    }

    public function doFormatDate($strDate) {
        if ($strDate != "") {
            $arrDate = explode('/', $strDate);
            return $arrDate[2] . "-" . $arrDate[1] . "-" . $arrDate[0];
        }
        return NULL;
    }

    public function doFormatDateBack($strDate) {
        if ($strDate != "") {
            $arrDate = explode('-', $strDate);
            return $arrDate[2] . "/" . $arrDate[1] . "/" . $arrDate[0];
        }
        return "";
    }

    public function exportPDF() {
        $this->load->model('items_model');


//        $arrUrl = $this->uri->uri_to_assoc();
        $arrUrl = array('items' => 'filter');

        $arrFilters = array();
        $arrPagination = array('page' => 1, 'limit' => 10);
        $arrOrder = array();
        $arrPageData['strOrderField'] = "FALSE";
        $arrPageData['strOrderDirection'] = "FALSE";
        $arrPageData['mixPageLimit'] = 10;
        $arrPageData['intPagesAvailable'] = 0;

        foreach ($arrUrl as $strKey => $mixValue) {
            $mixValue = urldecode($mixValue);
            if (substr($strKey, 0, 2) == "fr") {
                $arrFilters[substr($strKey, 3)] = $mixValue;
                $arrFilterInfo = explode('_', substr($strKey, 3));
                $arrPageData['arrFilters']['str' . $arrFilterInfo[0]] = $mixValue;
            }
            if (substr($strKey, 0, 2) == "pg") {
                if (substr($strKey, 3) == 'limit') {
                    $arrPagination['limit'] = $mixValue;
                    $arrPageData['mixPageLimit'] = $mixValue;
                }
                if (substr($strKey, 3) == 'page') {
                    $arrPagination['page'] = $mixValue;
                    $arrPageData['mixPageNumber'] = $mixValue;
                }
            }
            if (substr($strKey, 0, 2) == "or") {
                $arrOrder = array(substr($strKey, 3), $mixValue);
                $arrPageData['strOrderField'] = substr($strKey, 3);
                $arrPageData['strOrderDirection'] = $mixValue;
            }
        }


        $arrPageData['arrItemsData'] = array('results' => array());

        $mixItemsData = $this->items_model->getAll($this->session->userdata('objSystemUser')->accountid, array(), $arrFilters, $arrOrder, false, 'PDF');

        echo "<pre>";
        var_dump($mixItemsData);
        echo "</pre>";
        die("here");
    }

    public function exportCSV() {
        $this->load->model('items_model');


        $arrUrl = $this->uri->uri_to_assoc();


        $arrFilters = array();
        $arrPagination = array('page' => 1, 'limit' => 10);
        $arrOrder = array();
        $arrPageData['strOrderField'] = "FALSE";
        $arrPageData['strOrderDirection'] = "FALSE";
        $arrPageData['mixPageLimit'] = 10;
        $arrPageData['intPagesAvailable'] = 0;

        foreach ($arrUrl as $strKey => $mixValue) {
            $mixValue = urldecode($mixValue);
            if (substr($strKey, 0, 2) == "fr") {
                $arrFilters[substr($strKey, 3)] = $mixValue;
                $arrFilterInfo = explode('_', substr($strKey, 3));
                $arrPageData['arrFilters']['str' . $arrFilterInfo[0]] = $mixValue;
            }
            if (substr($strKey, 0, 2) == "pg") {
                if (substr($strKey, 3) == 'limit') {
                    $arrPagination['limit'] = $mixValue;
                    $arrPageData['mixPageLimit'] = $mixValue;
                }
                if (substr($strKey, 3) == 'page') {
                    $arrPagination['page'] = $mixValue;
                    $arrPageData['mixPageNumber'] = $mixValue;
                }
            }
            if (substr($strKey, 0, 2) == "or") {
                $arrOrder = array(substr($strKey, 3), $mixValue);
                $arrPageData['strOrderField'] = substr($strKey, 3);
                $arrPageData['strOrderDirection'] = $mixValue;
            }
        }


        $arrPageData['arrItemsData'] = array('results' => array());

        $mixItemsData = $this->items_model->getAll($this->session->userdata('objSystemUser')->accountid, array(), $arrFilters, $arrOrder, false, 'CSV');


        echo "<pre>";
        var_dump($mixItemsData);
        echo "</pre>";
        die("here");


        if ($type == 'CSV') {
            echo $this->db->last_query();
        }
        if ($type == 'PDF') {
            
        }
    }

    public function pdf_upload() {
        if (array_key_exists('pdf_file_1', $_FILES) && ($_FILES['pdf_file_1']['size'] > 0)) {

// photo upload done
            for ($i = 1; $i <= count($_FILES); $i++) {
                $file = $_FILES["pdf_file_" . $i]["tmp_name"];

                $ext = end((explode(".", $_FILES['pdf_file_' . $i]['name'])));

// Remove space From Pdf Name
                $strFileName = preg_replace('/[^a-zA-Z0-9]+/', '', $_FILES['pdf_file_' . $i]['name']);


                $strFileName = $strFileName . time() . '.' . $ext;


                $this->load->model('items_model');
                $item_id = $_POST['item_id'];
                $item_manu = $this->items_model->get_item_manu($item_id);
                if ($item_manu) {
                    $itemID = $this->items_model->get_item_id($item_manu);
                }

                $this->items_model->update_file($item_manu, $strFileName);

                $this->load->library('s3');
                $this->s3->putObjectFile($file, "smartaudit", 'youaudit/' . $item_manu . '/' . $strFileName, S3::ACL_PUBLIC_READ);
            }
        }
        if ($this->s3->putObjectFile($file, "smartaudit", 'youaudit/' . $item_manu . '/' . $strFileName, S3::ACL_PUBLIC_READ)) {
            redirect('items/view/' . $item_id, 'refresh');
        } else {
            redirect('items/view/' . $item_id, 'refresh');
        }
    }

    public function pdf_download($foldername, $itemid, $filename) {


        $pdf_url = 'https://smartaudit.s3.amazonaws.com/' . $foldername . '/' . $itemid . '/' . $filename;


        header('Content-Type: application/pdf');
        header('Content-Disposition:attachment;filename=' . $filename);
        readfile($pdf_url);
    }

    public function pdf_delete($item_id = '', $foldername = '', $itemid = '', $filename = '') {
        $this->load->library('s3');
        $this->load->model('items_model');

        $pdf_url = 'http://smartaudit.s3.amazonaws.com/' . $foldername . '/' . $itemid . '/' . $filename;
        $i = 0;
        if ($this->s3->deleteObject('smartaudit', $foldername . '/' . $itemid . '/' . $filename)) {
            $i = 1;
        } else {
            echo 'no file found';
        }
        if ($i) {
            $this->items_model->delete_file($itemid, $filename);
        }

        redirect('items/view/' . $item_id, 'refresh');
    }

    public function generateHistoryPdf() {

        $allData = $this->input->post('allData');
        $tasks = $this->input->post('tasks');
//        var_dump($allData);die; 
        $this->load->model('tests_model');
        $this->tests_model->outputHistoryPdfFileItems($allData, $tasks);
    }

    public function editMultiItem() {
        $this->load->model('items_model');

        if ($this->input->post()) {
            $this->items_model->updateMultipleItems($this->input->post());
        }
        redirect('items/filter/', 'refresh');
    }

    public function import_excel() {
        if (array_key_exists('file', $_FILES) && ($_FILES['file']['size'] > 0)) {
            $arrConfig['upload_path'] = './excel_file/';
            $arrConfig['allowed_types'] = '*';

// Initialize variable
            $level_id = '';
// load model
            $this->load->model('items_model');

// load library
            $this->load->library('excel');
            $this->load->library('upload', $arrConfig);

// file retrive code 
            if ($this->upload->do_upload('file')) {
                if (file_exists("./excel_file/" . $_FILES['file']['name'])) {
                    $path = "./excel_file/" . $_FILES['file']['name'];
                    $ext = explode('.', $_FILES['file']['name']);
                    if ($ext[1] == 'xlsx') {
                        $objReader = new PHPExcel_Reader_Excel2007();
                    } else {
                        $objReader = new PHPExcel_Reader_Excel5();
                    }
                    try {
                        $objPHPExcel = $objReader->load($path);
                    } catch (Exception $e) {
                        
                    }
                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    foreach ($sheetData as $record) {
                        $this->items_model->insert_record($record);
                    }
                }
            } else {
                
            }
        }
    }

    public function photo_upload($intId) {
        $intPhotoError = 0;
        $this->load->model('items_model');
        $this->load->model('photos_model');
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

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
                    if ($this->input->post('photo_title') != "") {
                        $strPhotoTitle = $this->input->post('photo_title');
                    }
                    $intPhotoId[] = $this->photos_model->setOne($this->upload->data(), $strPhotoTitle, "item/default");

                    $arrPageData['intPhotoId'] = $intPhotoId;
                } else {


                    $intPhotoError = 1;
                }
            }
            $intPhotoId = implode(',', $intPhotoId);
            if ($this->input->post('pervious_image') && ($this->input->post('pervious_image') != '1')) {
                $update_photo = $this->input->post('pervious_image') . ',' . $intPhotoId;
            } else {
                $update_photo = $intPhotoId;
            }
            $this->items_model->setPhoto($intId, $update_photo);
        } else {
            if (!$this->input->post('pervious_image')) {
                $this->items_model->setPhoto($intId, 1);
            }
        }
        redirect('items/view/' . $intId, 'refresh');
    }

// Edit Item
    public function editItem($itemID) {
        if ($itemID > 0) {
            $this->view($itemID);
        } else {
            $this->filter();
        }
    }

// Add Similar Item
    public function addSimilarItem() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/addmultiple/');
            redirect('users/login/', 'refresh');
        }

// housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Add Multiple Items";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if ($this->input->post()) {
            if ($this->input->post('item_id_similar')) {
                $previous_itemid = $this->input->post('item_id_similar');
            } else {
                $previous_itemid = $this->input->post('itemID');
            }
//            var_dump($previous_itemid);die;
            $this->load->model('items_model');
            $bool = $this->items_model->checkBarcodeForItem($this->input->post('item_barcode_similar'));

            if (!$bool) {

                $item_details = $this->items_model->basicGetOne($previous_itemid, $this->session->userdata('objSystemUser')->accountid);

                if ($this->input->post('item_quantity_similar') != '') {
                    $quantity = $this->input->post('item_quantity_similar');
                } else {
                    $quantity = $item_details[0]->quantity;
                }

                if ($this->input->post('owner_id_similar') != '') {
                    $owner = $this->input->post('owner_id_similar');
                } else {
                    $owner = $item_details[0]->owner_now;
                }

                if ($this->input->post('site_id_similar') != '') {
                    $site = $this->input->post('site_id_similar');
                } else {
                    $site = $item_details[0]->siteid;
                }

                if ($this->input->post('location_id_similar') != '') {
                    $location = $this->input->post('location_id_similar');
                } else {
                    $location = $item_details[0]->location_now;
                }

                if ($this->input->post('supplier_similar') != '') {
                    $supplier = $this->input->post('supplier_similar');
                } else {
                    $supplier = $item_details[0]->supplier;
                }

                if ($this->input->post('item_value_similar') != '') {
                    $purchase_price = $this->input->post('item_value_similar');
                } else {
                    $purchase_price = $item_details[0]->value;
                }

                if ($this->input->post('item_condition_similar') != '') {
                    $condition = $this->input->post('item_condition_similar');
                } else {
                    $condition = $item_details[0]->condition_now;
                }

                $arrItemData = array(
                    'barcode' => $this->session->userdata('objSystemUser')->qrcode . $this->input->post('item_barcode_similar'),
                    'serial_number' => $this->input->post('item_serial_number_similar'),
                    'manufacturer' => $item_details[0]->manufacturer,
                    'item_manu' => $item_details[0]->item_manu,
                    'model' => $item_details[0]->model,
                    'site' => $this->input->post('site_id_similar'),
                    'account_id' => $this->session->userdata('objSystemUser')->accountid,
                    'value' => $purchase_price,
                    'current_value' => $item_details[0]->current_value,
                    'notes' => $item_details[0]->notes,
                    'status_id' => $item_details[0]->status_id,
                    'purchase_date' => $this->doFormatDate($this->input->post('item_purchased_similar')),
                    'warranty_date' => $item_details[0]->warranty_date,
                    'replace_date' => $item_details[0]->replace_date,
                    'pattest_date' => $item_details[0]->pattest_date,
                    'pattest_status' => $item_details[0]->pattest_status,
                    'quantity' => $quantity,
                    'compliance_start' => $item_details[0]->compliance_start,
                    'supplier' => $this->input->post('supplier_similar'),
                    'photo_id' => $item_details[0]->photo_id,
                    'owner_now' => $owner,
                    'owner_since' => date('Y-m-d H:i:s'),
                    'condition_now' => $condition,
                    'condition_since' => date('Y-m-d H:i:s'),
                );




                $mixNewItemId = $this->items_model->addOne($arrItemData, $item_details[0]->categoryid, $this->input->post('location_id_similar'), $this->session->userdata('objSystemUser')->userid, $this->input->post('site_id_similar'));

                foreach ($this->input->post() as $key => $value) {

                    if (strpos($key, 'custom_') !== FALSE) {
                        $cus_field = explode('custom_', $key);
                        $arr[$cus_field[1]] = $value;
                    }
                } 
//                var_dump($arr);
//                die;

                
                
                $this->load->model('categories_model');
                $this->load->model('customfields_model');
                if ($arr) {
                    $this->customfields_model->insertContentByItem($mixNewItemId, $arr);
                }
//                $arrPageData['arrCustomFields'] = $this->customfields_model->getsimilarCustomFields($previous_itemid, $item_details[0]->categoryid);
//
//                foreach ($arrPageData['arrCustomFields'] as $field) {
//                    $custom_data[$field->custom_field_id] = $field->content;
//                }
//
//                if ($custom_data) {
//                    $this->customfields_model->insertContentByItem($mixNewItemId, $custom_data);
//                }


                $this->load->model('tickets_model');
// entry in ticket table if item is faults/damaged
                if ($this->input->post('status_id') == 2 || $this->input->post('status_id') == 3) {
                    $data = array(
                        'item_id' => $mixNewItemId,
                        'user_id' => $this->session->userdata('objSystemUser')->userid,
                        'date' => date("Y-m-d H:i:s"),
                        'ticket_action' => "Open Job",
                        'status' => $item_details[0]->status_id,
                    );
                    $this->tickets_model->insertTicket($data);
                }

                if ($this->input->post('item_condition_similar')) {
                    $this->items_model->logConditionHistory($mixNewItemId, $this->input->post('item_condition_similar'));
                }


                $pre_itemid = $this->input->post('itemID');
                $this->session->set_userdata('booCourier', true);
                $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The item added successfully')));
                if ($this->input->post('add_another') != FALSE) {
                    $this->session->set_flashdata('item', $mixNewItemId);
                    redirect('/items/filter', 'refresh');
                } else {
                    redirect('/items/filter', 'refresh');
                }
                if ($this->input->post('add_owner') != false) {
                    $this->session->set_flashdata('ownership', true);
                    redirect('/items/filter', 'refresh');
                } else {
                    redirect('/items/filter', 'refresh');
                }
            }
        } else {
            $this->session->set_userdata('booCourier', true);
            $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('The item Could Not added successfully')));
            redirect('/items/filter', 'refresh');
        }
    }

// check uniqueness of qrcode
    public function checkQrcode() {

        $this->load->model('items_model');
        $res = $this->items_model->check_qrcode(trim($this->input->post('bar_code')));
        echo $res;
        die;
    }

    // check uniqueness of item manu
    public function check_itemmanu() {

        $this->load->model('items_model');
        $res = $this->items_model->check_manu(trim($this->input->post('item_manu')));
        echo $res;
        die;
    }

    public function itemPdf($param, $intID) {
        $this->load->model('items_model');
        $this->load->model('tickets_model');
        $this->load->model('customfields_model');
        $this->load->model('categories_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $mixItemsTicketHistory = $this->tickets_model->ticketHistory($intID);
        $mixItemsData = $this->items_model->basicGetOne($intID, $this->session->userdata('objSystemUser')->accountid);
        $arrPageData['numberOfFaults'] = count($mixItemsTicketHistory);
        $arrPageData['lastDateOfFaults'] = $this->tickets_model->lastDateOfFaults($intID);
        $arrPageData['objItem'] = $mixItemsData[0];

        $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFields($mixItemsData[0]->categoryid);
        $arrPageData['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItem($intID);
        if ($arrPageData['arrCustomFields'] && $arrPageData['arrCustomFieldsContent']) {
            foreach ($arrPageData['arrCustomFields'] as $key => $value) {
                foreach ($arrPageData['arrCustomFieldsContent'] as $k => $v) {
                    if ($v->custom_field_id == $value->id) {
                        $arrPageData['arrCustomFields'][$key]->content = $v->content;
                    }
                }
            }
        }
        if ($mixItemsData[0]->photo_id) {
            if (strpos($mixItemsData[0]->photo_id, ',') != false) {
                $photos = explode(',', $mixItemsData[0]->photo_id);
                $pics = array();
                foreach ($photos as $value) {
                    $rs = $this->db->select('path')->where('id', $value)->get('photos')->row();
                    $pics[] = $rs->path;
                }
                $arrPageData['itemPics'] = implode(',', $pics);
            } else {
                $res = $this->db->select('path')->where('id', $mixItemsData[0]->photo_id)->get('photos')->row();
                $arrPageData['itemPics'] = $res->path;
            }
        }
        $strHtml .='<link rel="stylesheet" type="text/css" media="all" href="http://dev-iis.com/youaudit/iwa/brochure/css/bootstrap.min.css" />';
        $strHtml .='<link rel="stylesheet" type="text/css" media="all" href="http://dev-iis.com/youaudit/iwa/includes/css/style.css" />';
        $strHtml .='<link rel="stylesheet" type="text/css" media="all" href="http://dev-iis.com/youaudit/includes/css/sub_style.css" />';
        $strHtml .='<link rel="stylesheet" type="text/css" media="all" href="http://dev-iis.com/youaudit/brochure/css/validation/core.css" />';

        $strHtml .= $this->load->view('items/viewitem', $arrPageData, TRUE);

//        echo $strHtml;
//        die;
        $this->load->library('Mpdf');

        $mpdf = new Pdf('en-GB', 'A4-L');

//        $mpdf->SetDisplayMode(90);
//        $mpdf->SetDisplayMode('fullpage','two');
        $mpdf->AddPage('L', // L - landscape, P - portrait
                '', '', '', '', 30, // margin_left
                50, // margin right
                23, // margin top
                55, // margin bottom
                18, // margin header
                12); // margin footer
        $mpdf->setFooter('{PAGENO} of {nb}');
        $mpdf->WriteHTML($strHtml);
        $mpdf->Output("YouAudit_" . date('Ymd_His') . ".pdf", "D");
    }

// get location by site

    function getlocationbysite($site_id) {
        if ($site_id) {
            $this->load->model('sites_model');
            $location = $this->sites_model->getlocationbysite($site_id);
            if (!empty($location)) {
                echo json_encode($location);
            }
        }
    }

    function getownerbysite($site_id) {
        if ($site_id) {
            $this->load->model('sites_model');
            $location = $this->sites_model->getownerbysite($site_id);
            if (!empty($location)) {
                echo json_encode($location);
            }
        }
    }

    function getownerbylocation($location_id) {
        if ($location_id) {
            $this->load->model('locations_model');
            $location = $this->sites_model->getownerbylocation($location_id);
            if (!empty($location)) {
                echo json_encode($location);
            }
        }
    }

    function getsitebylocation($location_id) {
        if ($location_id) {
            $this->load->model('locations_model');
            $site = $this->locations_model->getsitebylocation($location_id);

            if (!empty($site)) {
                echo json_encode($site);
            }
        }
    }

    function getlocationbyowner($owner_id) {
        if ($owner_id) {
            $this->load->model('admin_section_model');
            $location = $this->admin_section_model->getlocationbyowner($owner_id);
            if (!empty($location)) {
                echo json_encode($location);
            }
        }
    }

    function getalllocation() {
        $this->load->model('locations_model');
        $locations = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
        echo json_encode($locations);
    }

    function delete_photo() {

        if ($this->input->post()) {
            $this->load->model('items_model');
            $arrPageData['arrSessionData'] = $this->session->userdata;
            $mixItemsData = $this->items_model->basicGetOne($this->input->post('item_id'), $this->session->userdata('objSystemUser')->accountid);
            $photo_ids = explode(',', $mixItemsData[0]->photo_id);
            if (($key = array_search($this->input->post('delete_id'), $photo_ids)) !== false) {
                unset($photo_ids[$key]);
            }
            $new_photo_id = implode(',', $photo_ids);
            $result = $this->items_model->setPhoto($this->input->post('item_id'), $new_photo_id);
            if ($result) {
                echo $result;
            }
        }
    }

    public function getassetdata($itemid) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/items/addmultiple/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('items_model');
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $mixItemsData = $this->items_model->basicGetOne($itemid, $this->session->userdata('objSystemUser')->accountid);
        echo json_encode($mixItemsData);
        die;
    }

// search asset
    public function search_asset() {

        $this->load->model('items_model');
        $res = $this->items_model->item_search(trim($this->input->post('bar_code')));
        echo $res;
        die;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
