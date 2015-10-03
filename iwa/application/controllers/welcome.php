<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public static $count_routine = 0;

    public function index() {

        $mydata = array();
        $this->load->model('theme_model');
//        $mydata = $this->theme_model->fetch_Theme();
//        $this->session->set_userdata('theme_design', $mydata[0]);
        $this->session->set_userdata('strReferral', '/welcome/index/');

        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        if ($this->session->userdata('booAdminLogin') && (!$this->session->userdata('booInheritedUser'))) {
            redirect('admins/index/', 'refresh');
        }

        if (!$this->session->userdata('booUserLogin') && (!$this->session->userdata('booInheritedUser'))) {
            $arrPageData['arrPageParameters']['strPage'] = "System Login";
        } else {
            $arrPageData['arrPageParameters']['strPage'] = "Dashboard";
        }
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $arrPageData['strPageTitle'] = "Welcome to YouAudit";

        /* Check for quick search */

        if ($this->input->post('qr')) {
            redirect('items/filter/fr_barcode_start/' . $this->input->post('qr'));
        }
        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');

        // load models
        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('accounts_model');
        $this->load->model('youaudit_admins_model');
        $this->load->model('tickets_model');
        $this->load->model('categories_model');
        $this->load->model('admin_section_model');
        $this->load->model('itemstatus_model');
        $this->load->model('locations_model');
        $this->load->model('sites_model');
        $this->load->model('suppliers_model');

        if ($this->session->userdata('booUserLogin') || $this->session->userdata('booInheritedUser')) {
            $arrPageData['intUserItemCount'] = $this->items_model->countNumberForUser($this->session->userdata('objSystemUser')->userid, $this->session->userdata('objSystemUser')->accountid);

            $arrPageData['intTotalItemCount'] = $this->items_model->countNumberForAccount($this->session->userdata('objSystemUser')->accountid, true);

            $arrPageData['arrNewestItems'] = $this->items_model->getFiveNewestItemsFor($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['arrCommonItems'] = $this->items_model->getCommonItemsFor($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['arrSuperAdminRequest'] = $this->users_model->getSuperAdminRequestFor($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['arrTotalItemsOnAccount'] = $this->items_model->getTotalNumberOfItems($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['arrTotalUsersOnAccount'] = $this->users_model->getTotalNumberOfUsers($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['arrAccountDetails'] = $this->accounts_model->getOne($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['arrRecentlyDeletedItems'] = $this->items_model->getRecentlyDeleted($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['intItemsRemainingOnAccount'] = $this->session->userdata('objSystemUser')->package_item_limit - $arrPageData['intTotalItemCount'];
            $arrPageData['arruserbasiccredential'] = $this->users_model->getOneWithoutAccount($this->session->userdata('objSystemUser')->userid);
            $arrPageData['arrAllMissingItem'] = $this->items_model->getAllMissingItems($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['latest_news'] = $this->youaudit_admins_model->getLastNews();
            $arrPageData['currentFaults'] = $this->tickets_model->getCurrentFaults($this->session->userdata('objSystemUser')->accountid);

            $arrPageData['arrCategories'] = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrItemManu'] = $this->admin_section_model->getItem_Manu($arrPageData['arrSessionData']['objSystemUser']->accountid);
            $arrPageData['arrManufaturer'] = $this->admin_section_model->getManufacturer($arrPageData['arrSessionData']['objSystemUser']->accountid);
            $arrPageData['arrItemStatuses'] = $this->itemstatus_model->getAll();
            $arrPageData['arrCondition'] = $this->items_model->get_condition();
            $arrPageData['arrOwners'] = $this->users_model->getAllForOwner($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrSuppliers'] = $this->suppliers_model->getAll();
            $arrPageData['conditionlist'] = $this->items_model->get_condition();
            $arrPageData['assetlist'] = $this->users_model->get_itemlist($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['faultdata'] = $this->tickets_model->getAllFaultItems($this->session->userdata('objSystemUser')->accountid);
        }
        // load views

        if ((!$this->session->userdata('booUserLogin')) && (!$this->session->userdata('booInheritedUser'))) {
            $this->load->view('common/header', $arrPageData);
            $this->load->view('users/forms/login', $arrPageData);
        } else {
            $this->session->unset_userdata('theme_design');
            $mydata = $this->theme_model->select_Theme_user($this->session->userdata('objSystemUser')->accountid);
            if ($mydata)
                $this->session->set_userdata('theme_design', $mydata[0]);
            $logo = 'logo.png';
            if (isset($this->session->userdata['theme_design']->logo)) {
                $logo = $this->session->userdata['theme_design']->logo;
            }
            $this->load->view('common/header', $arrPageData);
            $this->load->view('welcome', $arrPageData);
            $this->load->view('home', $arrPageData);
        }

        $this->load->view('common/footer', $arrPageData);
    }

    public function get_searchResults() {
        $this->load->model('items_model');
        $result = $this->items_model->search_Items(
                $this->session->userdata('objSystemUser')->accountid
                , $this->input->post('manufacturer_id')
                , (int) $this->input->post('site_id')
                , (int) $this->input->post('location_id')
                , (int) $this->input->post('category_id')
                , (int) $this->input->post('manu_id')
                , $this->input->post('bar_code')
        );
        echo json_encode($result);
        die;
    }

    public function get_image_properties($path = '', $return = FALSE) {
        // For now we require GD but we should
        // find a way to determine this using IM or NetPBM

        if (!file_exists($path)) {
            $this->set_error('imglib_invalid_path');
            return FALSE;
        }

        $vals = @getimagesize($path);

        $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

        $mime = (isset($types[$vals['2']])) ? 'image/' . $types[$vals['2']] : 'image/jpg';

        if ($return == TRUE) {
            $v['width'] = $vals['0'];
            $v['height'] = $vals['1'];
            $v['image_type'] = $vals['2'];
            $v['size_str'] = $vals['3'];
            $v['mime_type'] = $mime;

            return $v;
        }

        $this->orig_width = $vals['0'];
        $this->orig_height = $vals['1'];
        $this->image_type = $vals['2'];
        $this->size_str = $vals['3'];
        $this->mime_type = $mime;

        return TRUE;
    }

    public function test() {
        echo phpinfo();
    }

    public function reminderMail() {


        $date = date('Y-m-d H:i:s');
        $this->load->library('email');
        $this->email->from("tickets@iworkaudit.com", "Cron Check Mail");
        $this->email->to('dharmendra@ignisitsolutions.com');
        $this->email->subject('Cron check');
        $this->email->message('Yor Cron run on' . $date);
//                     $this->email->send(); 

        $this->load->model('emails_model');
        $contact_email = $this->emails_model->reminderMaling();
        $this->mailroutine($contact_email);
    }

    function mailroutine($customer_data) {

        $new_file = $_SERVER['DOCUMENT_ROOT'] . '/iwa/excel_file/isareport_' . date('Ymd_His') . '.pdf';
        $curr_count = (int) $this->count_routine;
        $total_count = count($customer_data);

        if ($total_count > $curr_count) {
            if ((int) $customer_data[$this->count_routine]['account_id'] > 0) {


                $table = $this->getDues($customer_data[$this->count_routine]['account_id'], $customer_data[$this->count_routine]['id']);

                $this->load->library('Mpdf');
                $mpdf = new Pdf('en-GB', 'A4');
                $mpdf->setFooter('{PAGENO} of {nb}');
                $mpdf->WriteHTML($table);
                $mpdf->Output($new_file, "F");

                $this->load->library('email');
                $this->email->from("tickets@iworkaudit.com", "Due Compliance");
//                        $this->email->to('dharmendra@ignisitsolutions.com');
                $this->email->to($customer_data[$this->count_routine]['username']);
                $this->email->subject('Due Compliance List');
                $this->email->message('Due Compliance list is attached with pdf (Testing) ');
                $this->email->attach($new_file);

                $this->email->send();

                $this->count_routine = $this->count_routine + 1;
                $this->email->clear(TRUE);
                $this->mailroutine($customer_data);
            } else {
                $this->count_routine = $this->count_routine + 1;
                $this->mailroutine($customer_data);
            }
        }
    }

    public function getDues($ac_id, $manager_id, $filter = 1) {

        // housekeeping
        $this->load->model('users_model');
        $this->load->model('tests_model');

        if ($filter != NULL) {
            switch ($filter) {
                case '1': {
                        $end_date = date('Y-m-d', strtotime('+7 days'));
                        $start_date = date('Y-m-d', strtotime('7 days ago'));
                        $this->session->set_userdata('checksDue_chk', 1);
                        break;
                    }
                default : {
                        $end_date = date('Y-m-d', strtotime('+7 days'));
                        $start_date = date('Y-m-d', strtotime('7 days ago'));
                        $this->session->set_userdata('checksDue_chk', 1);
                        break;
                    }
            }
        }
        $arrPageData = array();
        $arrPageData['dueTests'] = $this->tests_model->getDueTests($ac_id, array('start_date' => $start_date, 'end_date' => $end_date));
        $arrPageData['manager_of_check'] = $manager_id;

        // load views
        $table = $this->load->view('compliance/due_reminder', $arrPageData, true);
        return $table;
    }

    public function setCron() {
        $config['mailtype'] = 'html';
        $this->load->library('email', $config);
        $temp = $this->input->post('html');
        $temp = $temp . '<br>______________x______________';
        $this->email->from("tickets@iworkaudit.com", "Due Compliance");
        $this->email->to('mayank@ignisitsolutions.com');

        $this->email->subject('Cron check');
        $this->email->message($temp);

        $this->email->send();
    }
    
  // get item name ....
  public function getItemManu($item_manu,$account_id){
      $this->load->model('items_model');
     $res = $this->items_model->db->select('item_manu_name')->from('item_manu')->where(array('id'=>$item_manu,'account_id'=>$account_id))->get();
    $data = $res->result_array();
     return $data[0]['item_manu_name'];
  }    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
