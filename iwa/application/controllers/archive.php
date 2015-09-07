<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Archive extends MY_Controller {

    public function index() {

        $this->archived_assets();
    }

//    public function archived_assets() {
//        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
//            $this->session->set_userdata('strReferral', '/items/addmultiple/');
//            redirect('users/login/', 'refresh');
//        }
//        $this->load->model('items_model');
//        $arrPageData = array();
//        $arrPageData['arrPageParameters']['strSection'] = get_class();
//        $arrPageData['arrPageParameters']['strPage'] = "Archive Asset";
//        $arrPageData['arrSessionData'] = $this->session->userdata;
//        $arrPageData['arrRecentlyDeletedItems'] = $this->items_model->getRecentlyDeleted($this->session->userdata('objSystemUser')->accountid);
//        $arrPageData['levels'] = $this->db->get('levels')->result();
//
//// load views
//        $this->load->view('common/header', $arrPageData);
////load the correct view
//        $this->load->view('items/archive_asset', $arrPageData);
//        $this->load->view('common/footer', $arrPageData);
//    }
    public function archived_assets() {

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
// load views
        $this->load->view('common/header', $arrPageData);
//load the correct view
        $this->load->view('items/archive_asset', $arrPageData);
        $this->load->view('common/footer', $arrPageData);
    }

    // get archived items 
    public function archived_items() {
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
        $col_sort = array("items.barcode", "items.serial_number", "categories.name", "items.item_manu", "items.manufacturer", "items.model", "items.quantity", "sites.name", "locations.name", "users.firstname", "users.lastname", "pat.pattest_name", "itemstatus.name", "items.purchase_date", "items.warranty_date", "items.replace_date", "items.value", "items.current_value", "suppliers.supplier_name", "item_condition.condition");
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
                itemstatus.name AS status_name,
                item_remove_reasons.reason,
                items.mark_deleted_date,
                items.mark_deleted_2_date,
                items_reason_link.payment,
                items_reason_link.net_gain_loss,
                actions.who_did_it AS logged_by,
                custom_fields_content.content,
                items.deleted_date
                from items left join items_categories_link on items.id = items_categories_link.item_id
                left join categories on items_categories_link.category_id = categories.id
                left join custom_fields_content on items.id = custom_fields_content.item_id
                left join itemstatus on items.status_id = itemstatus.id
                left join items_reason_link on items.id=items_reason_link.item_id
                left join item_remove_reasons on items_reason_link.reason_id=item_remove_reasons.id
                left join actions on items.id=actions.to_what
                left join users on actions.who_did_it = users.id
                left join item_manu on items.item_manu = item_manu.id
                left join owner on items.owner_now = owner.id
                left join photos on users.photo_id = photos.id
                left join item_condition on items.condition_now = item_condition.id
                left join photos AS photos2 on items.photo_id = photos2.id
                left join locations on items.location_now = locations.id
                left join sites on items.site = sites.id
                left join suppliers on items.supplier = suppliers.supplier_id
                left join pat on items.pattest_status = pat.id
                where items.active =0 AND items.account_id=" . $this->session->userdata('objSystemUser')->accountid;

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

        if (isset($_GET['iSortCol_0'])) {
            $query .= " GROUP BY items.id ORDER BY ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $query .= $col_sort[intval($_GET['iSortCol_' . $i])] . "
				 	" . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $query = substr_replace($query, "", -2);
            if ($query == "ORDER BY") {
                $query .= "";
            }
            if ($query == "GROUP BY") {
                $query .= "";
            }
        }

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $str_point = intval($_GET['iDisplayStart']);
            $lenght = intval($_GET['iDisplayLength']);
            $query_res = $query . " limit " . $str_point . "," . $lenght;
        } else {
            $query_res = $query;
        }
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

            $output['aaData'][] = array("DT_RowId" => $val['itemid'], '<input type="checkbox" class="multiComSelect" value="' . $val['itemid'] . '"><input type="hidden" id="category_id_' . $val['itemid'] . '" class="" value="' . $val['categoryid'] . '" >', '<a id="bcode" href="' . $view_users . '">' . $val['barcode'] . '</a>', $photo, $val['categoryname'], $val['item_manu_name'], $val['manufacturer'], $val['model'], $val['quantity'], $val['sitename'], $val['locationname'], $val['owner_name'], $val['supplier_name'], $val['statusname'], $val['condition_name'], $numberOfFaults, $val['serial_number'], $age_asset, $purchase_date, $warranty_date, $replace_date, $val['value'], $val['current_value'], $removal_date, $logged_by, $val['reason'], $val['status_name'], $val['payment'], $val['net_gain_loss'], '<span class="action-w"><a class="icon-with-text" href="' . $view_users . '" title="View"><i class="fa fa-eye franchises-i"></i></a>View</span>');
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

//        var_dump($output);

        echo json_encode($output);
        die;
    }

}