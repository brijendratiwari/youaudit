<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class apiV4 extends MY_Controller {

    private function checkSession($strUsername, $strPassword) {

        $arrOutput = array();
        if ($this->session->userdata('booAppUserLogin')) {
            $arrOutput['booError'] = false;
        } else {
            if ($this->getUserObject($strUsername, $strPassword)) {
                $arrOutput['booError'] = false;
            } else {
                $arrOutput['booError'] = true;
                $arrOutput['booSession'] = true;
            }
        }
        return $arrOutput;
    }

    private function getUserObject($strUsername, $strPassword) {
        $this->load->model('users_model');
        $this->load->library('form_validation');

        $arrInput = array(
            'username' => $strUsername,
            'password' => $strPassword,
            'active' => 1
        );


        //does the record exist?
        $arrLoginData = $this->users_model->logInForApp($arrInput);

        if ($arrLoginData['booSuccess']) {
            $arrUserData = $this->users_model->getBasicCredentialsFor($arrLoginData['result'][0]->id);
            $is_supplier = $this->users_model->isUser_Supplier($arrUserData['result'][0]->userid);
            $objUser = $arrUserData['result'][0];

            $this->session->set_userdata('booAppUserLogin', TRUE);
            $this->session->set_userdata('objAppUser', $objUser);
            $this->session->set_userdata('is_supplier', $is_supplier);
            return $objUser;
        } else {
            return false;
        }
    }

    private function getPullDowns() {
        $arrOutput = array();
        if ($this->session->userdata('booAppUserLogin')) {
            $this->load->model('categories_model');
            $this->load->model('itemstatus_model');
            $this->load->model('sites_model');
            $this->load->model('users_model');
            $this->load->model('locations_model');
            $this->load->model('fleet_model');
            $this->load->model('admin_section_model');

            $intAccountId = $this->session->userdata('objAppUser')->accountid;
//            $intAccountId = 5;

            $arrCategories = $this->categories_model->getAll($intAccountId);
            $arrSites = $this->sites_model->getAll($intAccountId);
            $arrItemStatuses = $this->itemstatus_model->getAll();
            $arrUsers = $this->users_model->getAllForAppPullDown($intAccountId);
//            $arrOwners = $this->admin_section_model->ownerlist($intAccountId);
            $arrLocations = $this->locations_model->getAll($intAccountId);
            $arrMakes = $this->fleet_model->getAll($intAccountId);
            $arrItemManu = $this->admin_section_model->getItem_Manu($intAccountId);

            if ((count($arrCategories) > 0) && (count($arrItemStatuses) > 0) && (count($arrSites) > 0)) {
                $arrOutput['arrCategories'] = $arrCategories['results'];
                $arrOutput['arrSites'] = $arrSites['results'];
                $arrOutput['arrItemStatuses'] = $arrItemStatuses['results'];
                $arrOutput['arrUsers'] = $arrUsers['results'];
//                $arrOutput['arrOwners'] = $arrOwners;
                $arrOutput['arrLocations'] = $arrLocations['results'];
                $arrOutput['arrMakes'] = $arrMakes['results'];
                $arrOutput['$arrItemManu'] = $arrItemManu;
            }
        }
        return $arrOutput;
    }

    private function getManufacturersPullDown() {
        $arrOutput = array();

        if ($this->session->userdata('booAppUserLogin')) {
            $this->load->model('items_model');

            $intAccountId = $this->session->userdata('objAppUser')->accountid;
            $arrManufacturers = $this->items_model->listManufacturers($intAccountId);
            #var_dump($arrManufacturers);

            foreach ($arrManufacturers as $strManufacturer) {
                $objManufacturer = new stdClass;
                $objManufacturer->manufacturerid = $strManufacturer;
                $objManufacturer->manufacturername = $strManufacturer;
                $arrOutput[] = $objManufacturer;
            }
        }
        return $arrOutput;
    }

    private function getSearchResults() {
        $arrOutput = array();

        if ($this->session->userdata('booAppUserLogin')) {
//                ($this->input->post('manufacturer') != '') || ($this->input->post('user_id') > -1) || ($this->input->post('location_id') > -1) || ($this->input->post('site_id') > -1)
            $this->load->model('applications_model');
            $arrOutput = $this->applications_model->searchItems(
                    $this->session->userdata('objAppUser')->accountid
                    , $this->input->post('manufacturer')
                    , (int) $this->input->post('site_id')
                    , (int) $this->input->post('owner_id')
                    , (int) $this->input->post('location_id')
                    , (int) $this->input->post('category_id')
                    , (int) $this->input->post('item_manu')
                    , $this->input->post('barcode_id')
                    , $this->input->post('freetext')
            );
        }
        return $arrOutput;
    }

    public function getFaultsSearchResults() {
        $arrOutput = array();

        if ($this->session->userdata('booAppUserLogin')) {
//                ($this->input->post('manufacturer') != '') || ($this->input->post('user_id') > -1) || ($this->input->post('location_id') > -1) || ($this->input->post('site_id') > -1 || ($this->input->post('item_manu') != ''))
            $this->load->model('tickets_model');
            $arrOutput = $this->tickets_model->searchfaults(
                    $this->session->userdata('objAppUser')->accountid
                    , $this->input->post('manufacturer')
                    , (int) $this->input->post('site_id')
                    , (int) $this->input->post('owner_id')
                    , (int) $this->input->post('location_id')
                    , (int) $this->input->post('category_id')
                    , (int) $this->input->post('item_manu')
                    , $this->input->post('barcode_id')
                    , $this->input->post('freetext')
            );
        }
        return $arrOutput;
    }

    private function getVehicleSearchResults() {

        $arrOutput = array();
        if ($this->session->userdata('booAppUserLogin') && (
                ($this->input->post('make') != '') || ($this->input->post('user_id') > -1) || ($this->input->post('location_id') > -1) || ($this->input->post('site_id') > -1) || ($this->input->post('reg_no') > -1)
                )) {
            $this->load->model('applications_model');
            $arrOutput = $this->applications_model->searchVehicles(
                    $this->session->userdata('objAppUser')->accountid
                    , $this->input->post('make')
                    , (int) $this->input->post('site_id')
                    , (int) $this->input->post('user_id')
                    , $this->input->post('reg_no')
                    , (int) $this->input->post('location_id'));
        }

        return $arrOutput;
    }

    private function buildEmail($objItem, $priority_level) {
        $arrOutput = array('strZenDeskDataCapture' => '', 'strMessageBodyItemData' => '');
        $date = array();
        if ($this->session->userdata('booAppUserLogin')) {

            $priorities = array(1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical');

            if (strtotime($objItem->warranty_date) > 0) {

                $date['warranty_date'] = (date("d/m/Y", strtotime($objItem->warranty_date)));
            } else {
                $date['warranty_date'] = "N/A";
            }



            $strZenDeskDataCapture = "";
            $strZenDeskDataCapture .= "#requester " . $this->session->userdata('objAppUser')->username . " \r\n";
            $strZenDeskDataCapture .= "#tags ischoolaudit " . $objItem->barcode . " \r\n";
            $strZenDeskDataCapture .= "#problem \r\n";

            $strZenDeskDataCapture .= " -----------------------------------------------------\r\n";

            $arrOutput['strZenDeskDataCapture'] = $strZenDeskDataCapture;

            $strMessageBodyItemData = "\r\n -----------------------------------------------------\r\n";
            $strMessageBodyItemData .= "ACCOUNT NAME: " . $this->session->userdata('objAppUser')->accountname . "\r\n";
            $strMessageBodyItemData .= "SENDER: " . $this->session->userdata('objAppUser')->firstname . " " . $this->session->userdata('objAppUser')->lastname . "\r\n";

            $strMessageBodyItemData .= "MAKE & MODEL: " . $objItem->manufacturer . " " . $objItem->model . "\r\n";
            $strMessageBodyItemData .= "LOCATION: " . $objItem->locationname . "\r\n";
            $strMessageBodyItemData .= "BARCODE: " . $objItem->barcode . "\r\n";
            $strMessageBodyItemData .= "SERIAL NUMBER: " . $objItem->serial_number . "\r\n";
            $strMessageBodyItemData .= "WARRANTY DATE: " . $date['warranty_date'] . "\r\n";
            $strMessageBodyItemData .= "PRIORITY LEVEL: " . $priorities[$priority_level] . "\r\n";

            $arrOutput['strMessageBodyItemData'] = $strMessageBodyItemData;
        }

        return $arrOutput;
    }

    private function photoUpload($intItemId) {
        $booOutput = false;

        if ($this->session->userdata('booAppUserLogin')) {
            $this->load->model('items_model');
            $this->load->model('photos_model');
            $this->load->helper('file');

            $strFileName = "mobile-app-" . $this->session->userdata('objAppUser')->accountid . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";

            $intPhotoId = -1;

            if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data')))) {
                $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                $arrImageData = array(
                    'file_name' => $strFileName,
                    'image_width' => $arrImageSizeData[0],
                    'image_height' => $arrImageSizeData[1],
                    'file_type' => $arrImageSizeData['mime']
                );
                $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "item/default");
            }

            if ($intPhotoId > 0) {
                $this->items_model->setPhoto($intItemId, $intPhotoId);
                //$this->logThisForAppUser("Changed Item Photo on App", "items", $intItemId);
                $booOutput = true;
            }
        }

        return $booOutput;
    }

    public function logout() {

        $this->session->unset_userdata(array('booAppUserLogin', 'objAppUser'));
        $this->session->sess_destroy();

        $arrOutput['booSuccess'] = true;

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrOutput));
    }

    public function login() {
//        echo
//        $arrOutput = array('test'=>'test');
//        $this->output
//                ->set_content_type('application/json')
//                ->set_output(json_encode($arrOutput));
//        die;
        // load models
        $this->load->library('form_validation');

        $arrOutput = array();

        if (($this->input->post('username') != '') && ($this->input->post('password') != '')) {

            $objUser = $this->getUserObject($this->input->post('username'), $this->input->post('password'));
            if ($objUser) {
                foreach ($objUser as $strKey => $strValue) {
                    $arrOutput['arrUser'][$strKey] = $strValue;
                }
                $arrOutput['booError'] = false;
                $arrOutput['booLogin'] = true;
            } else {
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Username/Password incorrect";
                $arrOutput['booLogin'] = false;
            }
        } else {
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "Username/Password missing";
            $arrOutput['booLogin'] = false;
        }


        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrOutput));
    }

    public function dashboard() {
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        $arrData = array();
        if (!$arrOutput['booError']) {
            $this->load->model('items_model');
            $arrData['intTotalItemsOnAccount'] = $this->items_model->countNumberForAccount($this->session->userdata('objAppUser')->accountid, true);
            $arrOutput['strHtml'] = $this->load->view('appV2/dashboard', $arrData, true);
            $data['Manage Items'] = array('Look up item', 'PAT Results', 'Add An Item');
            $data['Manage Locations'] = array('Look up location');
            $data['Manage Suppliers'] = array('Supplier List');
            $data['Manage Vehicles'] = array('Look up vehicle', 'Check vehicle');
            $data['Application Settings '] = array('Log Out & Forget Me');
        }


        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
    }

    public function location($strBarcode) {

        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if ((!$arrOutput['booError']) && ($strBarcode != "")) {
            $this->load->model('locations_model');
            $this->load->model('customfields_model');
            $this->load->model('categories_model');
            $this->load->model('audits_model');

            $mixLocationData = $this->locations_model->getOneByBarcode($strBarcode, $this->session->userdata('objAppUser')->accountid);
            if ($mixLocationData) {

                $intLocationId = $mixLocationData[0]->id;
                foreach ($mixLocationData[0] as $strKey => $strValue) {
                    $arrData['arrLocation'][$strKey] = $strValue;
                }

                $arrData['arrLocation']['arrItems'] = $this->locations_model->getAllItemsForLocation($intLocationId, $this->session->userdata('objAppUser')->accountid);

                foreach ($arrData['arrLocation']['arrItems'] as $key => $value) {
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->purchase_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->purchase_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->purchase_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->purchase_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->replace_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->replace_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->replace_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->replace_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->warranty_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->warranty_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->warranty_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->warranty_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->pattest_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->pattest_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->pattest_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->pattest_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->owner_since) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->owner_since = (date("d/m/Y h:i:s", strtotime($arrData['arrLocation']['arrItems'][$key]->owner_since)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->owner_since = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->location_since) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->location_since = (date("d/m/Y h:i:s", strtotime($arrData['arrLocation']['arrItems'][$key]->location_since)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->location_since = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->added_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->added_date = (date("d/m/Y h:i:s", strtotime($arrData['arrLocation']['arrItems'][$key]->added_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->added_date = "N/A";
                    }




// Displaying Item's Custom fields Details ************************************************

                    $arrData_content['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp($arrData['arrLocation']['arrItems'][$key]->categoryid);
                    $arrData_content['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItemForApp($arrData['arrLocation']['arrItems'][$key]->itemid);

                    if ($arrData_content['arrCustomFields']) {
                        if ($arrData_content['arrCustomFieldsContent']) {
                            foreach ($arrData_content['arrCustomFields'] as $key1 => $value1) {
                                foreach ($arrData_content['arrCustomFieldsContent'] as $k => $v) {

                                    if ($v->custom_field_id == $value1->id) {

                                        $arrData_content['arrCustomFields'][$key1]->content = $v->content;
                                    }
                                }
                            }

                            $arrData['arrLocation']['arrItems'][$key]->custom_field = $arrData_content['arrCustomFields'];
                        } else {

                            $arrData['arrLocation']['arrItems'][$key]->custom_field = $arrData_content['arrCustomFields'];
                        }
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->custom_field = array();
                    }


                    $location_audit_details = $this->audits_model->getLastAuditForLocation($arrData['arrLocation']['arrItems'][$key]->location_now);

                    if (strtotime($location_audit_details['date']) > 0) {
                        $arrData['arrLocation']['arrItems'][$key]->lastlocationauditdate = date('d/m/Y', strtotime($location_audit_details['date']));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->lastlocationauditdate = 'N/A';
                    }


//   Get Total Present and Missing Item in Last Audit of Location

                    $Present_count = $this->audits_model->getCountPresentDetailsOfLastAudit($location_audit_details['audit_id']);
                    $missing_count = $this->audits_model->getCountMissingDetailsOfLastAudit($location_audit_details['audit_id']);
                    $arrData['arrLocation']['arrItems'][$key]->lastauditpresentitemcount = $Present_count['Total_present'];
                    $arrData['arrLocation']['arrItems'][$key]->lastauditmissingitemcount = $missing_count['Total_missing'];

//***********************************************************************************
                    //            CAlculation of item condition Histry

                    $historylatest = $this->items_model->get_maxcondition($arrData['arrLocation']['arrItems'][$key]->itemid);
                    $date2 = date('d-m-Y', time());
                    $date1 = date('d-m-Y H:i:s', strtotime($historylatest));

                    $diff = abs(strtotime($date2) - strtotime($date1));

                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff -
                            $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));


                    $arrData['arrLocation']['arrItems'][$key]->timeincondition = $years . ' year ' . $months . ' month ';
                }

                $success = 'YES';
                $arrData['success'] = $success;
            } else {
                $success = 'NO';
                $arrData['success'] = $success;
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Barcode " . $strBarcode . " not found";
            }
        } else {
            $success = 'NO';
            $arrData['success'] = $success;
            $arrOutput['booError'] = true;
            $arrData['strError'] = "No Barcode";
        }

        if (!$arrOutput['booError']) {
            $arrOutput['strHtml'] = $this->load->view('appV2/location', $arrData, true);
            $arrOutput['strHeader'] = $this->load->view('appV2/headers/location', $arrData, true);
        }

//        var_dump($arrData);
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function locationbyid($strBarcode) {

        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if ((!$arrOutput['booError']) && ($strBarcode != "")) {
            $this->load->model('locations_model');
            $this->load->model('categories_model');
            $this->load->model('customfields_model');
            $mixLocationData = $this->locations_model->getOne($strBarcode, $this->session->userdata('objAppUser')->accountid);

            if ($mixLocationData) {
                $intLocationId = $mixLocationData[0]->id;
                //print "<pre>"; print_r($mixLocationData); print "</pre>";

                foreach ($mixLocationData['results'][0] as $strKey => $strValue) {
                    $arrData['arrLocation'][$strKey] = $strValue;
                }

                $arrData['arrLocation']['arrItems'] = $this->locations_model->getAllItemsForLocation($strBarcode, $this->session->userdata('objAppUser')->accountid);

                foreach ($arrData['arrLocation']['arrItems'] as $key => $value) {
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->purchase_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->purchase_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->purchase_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->purchase_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->replace_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->replace_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->replace_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->replace_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->warranty_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->warranty_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->warranty_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->warranty_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->pattest_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->pattest_date = (date("d/m/Y", strtotime($arrData['arrLocation']['arrItems'][$key]->pattest_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->pattest_date = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->owner_since) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->owner_since = (date("d/m/Y h:i:s", strtotime($arrData['arrLocation']['arrItems'][$key]->owner_since)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->owner_since = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->location_since) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->location_since = (date("d/m/Y h:i:s", strtotime($arrData['arrLocation']['arrItems'][$key]->location_since)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->location_since = "N/A";
                    }
                    if (strtotime($arrData['arrLocation']['arrItems'][$key]->added_date) > 0) {

                        $arrData['arrLocation']['arrItems'][$key]->added_date = (date("d/m/Y h:i:s", strtotime($arrData['arrLocation']['arrItems'][$key]->added_date)));
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->added_date = "N/A";
                    }




// Displaying Item's Custom fields Details ************************************************

                    $arrData_content['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp($arrData['arrLocation']['arrItems'][$key]->categoryid);
                    $arrData_content['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItemForApp($arrData['arrLocation']['arrItems'][$key]->itemid);

                    if ($arrData_content['arrCustomFields']) {
                        if ($arrData_content['arrCustomFieldsContent']) {
                            foreach ($arrData_content['arrCustomFields'] as $key1 => $value1) {
                                foreach ($arrData_content['arrCustomFieldsContent'] as $k => $v) {

                                    if ($v->custom_field_id == $value1->id) {

                                        $arrData_content['arrCustomFields'][$key1]->content = $v->content;
                                    }
                                }
                            }

                            $arrData['arrLocation']['arrItems'][$key]->custom_field = $arrData_content['arrCustomFields'];
                        } else {

                            $arrData['arrLocation']['arrItems'][$key]->custom_field = $arrData_content['arrCustomFields'];
                        }
                    } else {
                        $arrData['arrLocation']['arrItems'][$key]->custom_field = array();
                    }

//***********************************************************************************
                }

                $arrOutput['arrLocation']['arrItems'] = $arrData['arrLocation']['arrItems'];
            } else {
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Barcode " . $strBarcode . " not found";
            }
        } else {
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Barcode";
        }

        if (!$arrOutput['booError']) {
            $arrOutput['strHtml'] = $this->load->view('appV2/locationbyid', $arrData, true);
            $arrOutput['strHeader'] = $this->load->view('appV2/headers/location', $arrData, true);
        }


        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function itemLookup() {

        $arrData = array();
        $this->load->model('items_model');
        $this->load->model('categories_model');
        $this->load->model('tickets_model');
        $this->load->model('audits_model');
        $this->load->model('customfields_model');



        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
//        $arrOutput = $this->checkSession("joe.makepeace@accessarea.co.uk","33ba5969ccb997e7b9894f45dd340df2");
        if (!$arrOutput['booError']) {
            if ($this->input->post('mode') == "search") {
//            if (TRUE) {

                $arrData['arrResults'] = $this->getSearchResults();
//                var_dump(   $arrData['arrResults'] );die; 
                // change Format Of date 
                foreach ($arrData['arrResults'] as $key => $value) {
                    if (strtotime($arrData['arrResults'][$key]->purchase_date) > 0) {

                        $date2 = date('d-m-Y', strtotime($arrData['arrResults'][$key]->purchase_date));
                        $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                        $diff = abs(strtotime($date2) - strtotime($date1));

                        $years = floor($diff / (365 * 60 * 60 * 24));
                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                        $arrData['arrResults'][$key]->age_of_assets = $years . ' year ' . $months . ' month ';

                        $arrData['arrResults'][$key]->purchase_date = (date("d/m/Y", strtotime($arrData['arrResults'][$key]->purchase_date)));
                    } else {
                        $arrData['arrResults'][$key]->purchase_date = "N/A";
                    }
                    if (strtotime($arrData['arrResults'][$key]->warranty_date) > 0) {
                        $arrData['arrResults'][$key]->warranty_date = (date("d/m/Y", strtotime($arrData['arrResults'][$key]->warranty_date)));
                    } else {
                        $arrData['arrResults'][$key]->warranty_date = "N/A";
                    }
                    if (strtotime($arrData['arrResults'][$key]->replace_date) > 0) {
                        $arrData['arrResults'][$key]->replace_date = (date("d/m/Y", strtotime($arrData['arrResults'][$key]->replace_date)));
                    } else {
                        $arrData['arrResults'][$key]->replace_date = "N/A";
                    }
                    if (strtotime($arrData['arrResults'][$key]->pattest_date) > 0) {

                        $arrData['arrResults'][$key]->pattest_date = (date("d/m/Y", strtotime($arrData['arrResults'][$key]->pattest_date)));
                    } else {
                        $arrData['arrResults'][$key]->pattest_date = "N/A";
                    }
                    if (strtotime($arrData['arrResults'][$key]->location_since) > 0) {
                        $arrData['arrResults'][$key]->location_since = (date("d/m/Y h:i:s", strtotime($arrData['arrResults'][$key]->location_since)));
                    } else {
                        $arrData['arrResults'][$key]->location_since = "N/A";
                    }
                    if (strtotime($arrData['arrResults'][$key]->added_date) > 0) {

                        $arrData['arrResults'][$key]->added_date = (date("d/m/Y h:i:s", strtotime($arrData['arrResults'][$key]->added_date)));
                    } else {
                        $arrData['arrResults'][$key]->added_date = "N/A";
                    }


// Displaying Item's Custom fields Details ************************************************

                    $arrData_content['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp($arrData['arrResults'][$key]->categoryid);
                    $arrData_content['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItemForApp($arrData['arrResults'][$key]->itemid);

                    if ($arrData_content['arrCustomFields']) {
                        if ($arrData_content['arrCustomFieldsContent']) {
                            foreach ($arrData_content['arrCustomFields'] as $key1 => $value1) {
                                foreach ($arrData_content['arrCustomFieldsContent'] as $k => $v) {

                                    if ($v->custom_field_id == $value1->id) {

                                        $arrData_content['arrCustomFields'][$key1]->content = $v->content;
                                    }
                                }
                            }

                            $arrData['arrResults'][$key]->custom_field = $arrData_content['arrCustomFields'];
                        } else {

                            $arrData['arrResults'][$key]->custom_field = $arrData_content['arrCustomFields'];
                        }
                    } else {
                        $arrData['arrResults'][$key]->custom_field = array();
                    }

//***********************************************************************************
//                    Add New Fields according to New system(YouAudit)
//                    Fetch Last location Audit Date
                    $location_audit_details = $this->audits_model->getLastAuditForLocation($arrData['arrResults'][$key]->locationid);

//            CAlculation of item condition Histry

                    $historylatest = $this->items_model->get_maxcondition($arrData['arrResults'][$key]->itemid);
                    $date2 = date('d-m-Y', time());
                    $date1 = date('d-m-Y H:i:s', strtotime($historylatest));

                    $diff = abs(strtotime($date2) - strtotime($date1));

                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff -
                            $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));


                    $arrData['arrResults'][$key]->timeincondition = $years . ' year ' . $months . ' month ';
                    if (strtotime($this->tickets_model->lastDateOfFaults($arrData['arrResults'][$key]->itemid)) > 0) {
                        $arrData['arrResults'][$key]->lastfault = date('d/m/Y', strtotime($this->tickets_model->lastDateOfFaults($arrData['arrResults'][$key]->itemid)));
                    } else {
                        $arrData['arrResults'][$key]->lastfault = 'N/A';
                    }
                    $arrData['arrResults'][$key]->lastcompliancecheck = "";

                    $arrData['arrResults'][$key]->complianceresult = "";
                    if (strtotime($location_audit_details['date']) > 0) {
                        $arrData['arrResults'][$key]->lastlocationauditdate = date('d/m/Y', strtotime($location_audit_details['date']));
                    } else {
                        $arrData['arrResults'][$key]->lastlocationauditdate = 'N/A';
                    }

//   Get Total Present and Missing Item in Last Audit of Location

                    $Present_count = $this->audits_model->getCountPresentDetailsOfLastAudit($location_audit_details['audit_id']);
                    $missing_count = $this->audits_model->getCountMissingDetailsOfLastAudit($location_audit_details['audit_id']);
                    $arrData['arrResults'][$key]->lastauditpresentitemcount = $Present_count['Total_present'];
                    $arrData['arrResults'][$key]->lastauditmissingitemcount = $missing_count['Total_missing'];
                }
//                var_dump($arrData);
//                die;

                $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemresults', $arrData, true);
                $arrOutput['strHtml'] = $this->load->view('appV2/itemresults', $arrData, true);
            } else {
                $arrData['arrPulldowns'] = $this->getPullDowns();
                $arrData['arrPulldowns']['arrManufacturers'] = $this->getManufacturersPullDown();
                $arrOutput['strHtml'] = $this->load->view('appV2/itemlookup', $arrData, true);
            }
        }

//        var_dump($arrData);die;

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function locationLookup() {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            if ($this->input->post('mode') == "search") {
                $arrData['arrResults'] = $this->getSearchResults();
                $arrOutput['strHeader'] = $this->load->view('appV2/headers/locationresults', $arrData, true);
                $arrOutput['strHtml'] = $this->load->view('appV2/locationresults', $arrData, true);
            } else {
                $arrData['arrPulldowns'] = $this->getPullDowns();
                $arrData['arrPulldowns']['arrManufacturers'] = $this->getManufacturersPullDown();
                $arrOutput['strHtml'] = $this->load->view('appV2/locationlookup', $arrData, true);
            }
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function vehicleLookup() {
        // load model

        $this->load->model('fleet_model');
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            if ($this->input->post('mode') == "search") {

//                $arrData['arrResults'] = $this->getVehicleSearchResults();
                // fetch more information for vehicle

                foreach ($this->getVehicleSearchResults() as $vehicle) {
                    $mixItemsData = $this->fleet_model->getVehicle($vehicle->fleetid);
                    $arrData['arrResults'][] = $mixItemsData;
                }

                $arrOutput['strHeader'] = $this->load->view('appV2/headers/vehicleresults', $arrData, true);
                $arrOutput['strHtml'] = $this->load->view('appV2/vehicleresults', $arrData, true);
            } else {
                $arrData['arrPulldowns'] = $this->getPullDowns();
                $arrData['arrPulldowns']['arrManufacturers'] = $this->getManufacturersPullDown();
                $arrOutput['strHtml'] = $this->load->view('appV2/vehiclelookup', $arrData, true);
            }
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function item($strBarcode) {


        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if ((!$arrOutput['booError']) && ($strBarcode != "")) {

            $this->load->model('items_model');
            $this->load->model('customfields_model');
            $this->load->model('categories_model');
            $mixItemsData = $this->items_model->basicGetOneByBarcode($strBarcode, $this->session->userdata('objAppUser')->accountid);
            if ($mixItemsData) {
                foreach ($mixItemsData[0] as $strKey => $strValue) {
                    $arrData['arrItem'][$strKey] = $strValue;
                }

                if (strtotime($arrData['arrItem']['purchase_date']) > 0) {

                    $date2 = date('d-m-Y', strtotime($arrData['arrItem']['purchase_date']));
                    $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                    $diff = abs(strtotime($date2) - strtotime($date1));

                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                    $arrData['arrItem']['age_of_assets'] = $years . ' year ' . $months . ' month ';


                    $arrData['arrItem']['purchase_date'] = (date("d/m/Y", strtotime($arrData['arrItem']['purchase_date'])));
                } else {
                    $arrData['arrItem']['purchase_date'] = 'N/A';
                }
                if (strtotime($arrData['arrItem']['warranty_date']) > 0) {
                    $arrData['arrItem']['warranty_date'] = (date("d/m/Y", strtotime($arrData['arrItem']['warranty_date'])));
                } else {
                    $arrData['arrItem']['warranty_date'] = 'N/A';
                }
                if (strtotime($arrData['arrItem']['replace_date']) > 0) {
                    $arrData['arrItem']['replace_date'] = (date("d/m/Y", strtotime($arrData['arrItem']['replace_date'])));
                } else {
                    $arrData['arrItem']['replace_date'] = 'N/A';
                }
                if (strtotime($arrData['arrItem']['added_date']) > 0) {
                    $arrData['arrItem']['added_date'] = (date("d/m/Y", strtotime($arrData['arrItem']['added_date'])));
                } else {
                    $arrData['arrItem']['added_date'] = 'N/A';
                }
                if (strtotime($arrData['arrItem']['location_since']) > 0) {
                    $arrData['arrItem']['location_since'] = (date("d/m/Y", strtotime($arrData['arrItem']['location_since'])));
                } else {
                    $arrData['arrItem']['location_since'] = 'N/A';
                }
                if (strtotime($arrData['arrItem']['pattest_date']) > 0) {
                    $arrData['arrItem']['pattest_date'] = (date("d/m/Y", strtotime($arrData['arrItem']['pattest_date'])));
                } else {
                    $arrData['arrItem']['pattest_date'] = 'N/A';
                }
                if (strtotime($arrData['arrItem']['owner_since']) > 0) {
                    $arrData['arrItem']['owner_since'] = (date("d/m/Y", strtotime($arrData['arrItem']['owner_since'])));
                } else {
                    $arrData['arrItem']['owner_since'] = 'N/A';
                }

                if ($arrData['arrItem']['supplier']) {
                    $arrData['arrItem']['supplier'] = $arrData['arrItem']['supplier'];
                } else {
                    $arrData['arrItem']['supplier'] = "";
                }

                if ($arrData['arrItem']['supplier_name']) {
                    $arrData['arrItem']['supplier_name'] = $arrData['arrItem']['supplier_name'];
                } else {
                    $arrData['arrItem']['supplier_name'] = "";
                }

// Displaying Item's Custom fields Details ************************************************
//                $arrData_custom = $this->categories_model->getCustomFieldsForApp($mixItemsData[0]->categoryid);
//                $arrData_content['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItemForApp($mixItemsData[0]->itemid);
//                if ($arrData_custom && $arrData_content['arrCustomFieldsContent']) {
//                    foreach ($arrData_custom as $key => $value) {
//                        foreach ($arrData_content['arrCustomFieldsContent'] as $k => $v) {
//                            if ($v->custom_field_id == $value->id) {
//                                $arrData_custom[$key]->content = $v->content;
//                            } else {
//                                $arrData_custom[$key]->content = '';
//                            }
//                        }
//                    }
//                    $arrData['arrItem']['custom_field'] = $arrData_custom;
//                }
//***********************************************************************************
                // Displaying Item's Custom fields Details ************************************************

                $arrData_content['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp($mixItemsData[0]->categoryid);
                $arrData_content['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItemForApp($mixItemsData[0]->itemid);

                if ($arrData_content['arrCustomFields']) {
                    if ($arrData_content['arrCustomFieldsContent']) {
                        foreach ($arrData_content['arrCustomFields'] as $key1 => $value1) {
                            foreach ($arrData_content['arrCustomFieldsContent'] as $k => $v) {

                                if ($v->custom_field_id == $value1->id) {

                                    $arrData_content['arrCustomFields'][$key1]->content = $v->content;
                                }
                            }
                        }

                        $arrData['arrItem']['custom_field'] = $arrData_content['arrCustomFields'];
                    } else {

                        $arrData['arrItem']['custom_field'] = $arrData_content['arrCustomFields'];
                    }
                } else {
                    $arrData['arrItem']['custom_field'] = array();
                }

//***********************************************************************************
//                    Add New Fields according to New system(YouAudit)
                //            CAlculation of item condition Histry

                $historylatest = $this->items_model->get_maxcondition($mixItemsData[0]->itemid);
                $date2 = date('d-m-Y', time());
                $date1 = date('d-m-Y H:i:s', strtotime($historylatest));

                $diff = abs(strtotime($date2) - strtotime($date1));

                $years = floor($diff / (365 * 60 * 60 * 24));
                $months = floor(($diff -
                        $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $arrData['arrItem']['timeincondition'] = $years . ' year ' . $months . ' month ';
                $arrData['arrItem']['lastfault'] = "";
                $arrData['arrItem']['lastcompliancecheck'] = "";
                $arrData['arrItem']['complianceresult'] = "";



                $success = 'YES';
                $arrData['success'] = $success;
            } else {
                $success = 'NO';
                $arrData['success'] = $success;
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Barcode " . $strBarcode . " not found";
            }
        } else {
            $success = 'NO';
            $arrData['success'] = $success;
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Barcode";
        }

        if (!$arrOutput['booError']) {
            $booSearch = false;
            if ($this->input->post('search') != 'false') {
                $booSearch = true;
            }
            $arrData['booSearch'] = $booSearch;
            $arrOutput['booSearch'] = $booSearch;
            $arrOutput['strHtml'] = $this->load->view('appV2/item', $arrData, true);
            $arrOutput['strHeader'] = $this->load->view('appV2/headers/item', $arrData, true);
        }


        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function vehicle($strFleetID) {
        if (strlen($strFleetID) > 3) {
            //$this->vehicleqr($strFleetID);
            redirect('/appV2/vehicleqr/' . $strFleetID);
        }
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if ((!$arrOutput['booError']) && ($strFleetID != "")) {

            $this->load->model('fleet_model');
            $mixItemsData = $this->fleet_model->getVehicle($strFleetID);

            if ($mixItemsData) {
                foreach ($mixItemsData as $strKey => $strValue) {
                    $arrData['arrVehicle'][$strKey] = $strValue;
                }
            } else {
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Barcode " . $strBarcode . " not found";
            }
        } else {
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Barcode";
        }

        if (!$arrOutput['booError']) {
            $booSearch = false;
            if ($this->input->post('search') != 'false') {
                $booSearch = true;
            }
            $arrData['booSearch'] = $booSearch;
            $arrOutput['booSearch'] = $booSearch;
            $arrOutput['strHtml'] = $this->load->view('appV2/vehicle', $arrData, true);
            $arrOutput['strHeader'] = $this->load->view('appV2/headers/vehicle', $arrData, true);
        }

        //print "<pre>"; print_r($arrData); print "</pre>";
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                ->set_output(json_encode($arrOutput));
    }

    public function vehicleqr($strFleetQR) {
        $arrData = array();

        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if ((!$arrOutput['booError']) && ($strFleetQR != "")) {

            $this->load->model('fleet_model');
            $mixItemsData = $this->fleet_model->getVehicle($strFleetQR, NULL, TRUE);

            if ($mixItemsData) {
                foreach ($mixItemsData as $strKey => $strValue) {
                    $arrData['arrVehicle'][$strKey] = $strValue;
                }
                if (!$arrData['arrVehicle']['Message']) {
                    $success = 'YES';
                    $arrData['success'] = $success;
                } else {
                    $success = 'NO';
                    $arrData['success'] = $success;
                }
            } else {
                $success = 'NO';
                $arrData['success'] = $success;
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Barcode " . $strBarcode . " not found";
            }
        } else {
            $success = 'NO';
            $arrData['success'] = $success;
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Barcode";
        }

        if (!$arrOutput['booError']) {
            $booSearch = false;
            if ($this->input->post('search') != 'false') {
                $booSearch = true;
            }
            $arrData['booSearch'] = $booSearch;
            $arrOutput['booSearch'] = $booSearch;
            $arrOutput['strHtml'] = $this->load->view('appV2/vehicle', $arrData, true);
            $arrOutput['strHeader'] = $this->load->view('appV2/headers/vehicle', $arrData, true);
        }

        //print "<pre>"; print_r($arrData); print "</pre>";
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                ->set_output(json_encode($arrOutput));
    }

    public function itemAdd() {
        $this->load->model('accounts_model');
        $this->load->model('categories_model');
        $this->load->model('customfields_model');
        $this->load->model('admin_section_model');
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
//     ********************* TESTING ******************************   
//                           $this->load->model('items_model');
//
//        $mixItemsData = $this->items_model->basicGetOneByBarcode('f44R9998', 1);
//        foreach ($mixItemsData[0] as $strKey => $strValue) {
//            
//                            $arrData['arrItem'][$strKey] = $strValue;
//                          
//        }
//        
//                    var_dump($arrData);    
//                    die;
//***************************************************************************


        if (!$arrOutput['booError']) {
            if ($this->input->post('mode') == "submit") {

//                $barcode = $this->session->userdata('objAppUser')->qrcode . trim($this->input->post('item_barcode'));
                $barcode = trim($this->input->post('item_barcode'));
                $this->load->model('applications_model');
                if ($this->applications_model->checkBarcodeUnique($barcode)) {
                    $this->load->model('items_model');
                    $this->load->model('photos_model');
                    $this->load->helper('file');
                    $strPurchaseDate = null;
                    $strWarrantyDate = null;
                    if ($this->input->post('purchase_date') != "") {
                        $arrPurchaseDate = explode('/', $this->input->post('purchase_date'));

                        $strPurchaseDate = $arrPurchaseDate[0] . "-" . $arrPurchaseDate[1] . "-" . $arrPurchaseDate[2];


                        if ($this->input->post('warranty_date') == "") {
                            if (checkdate($arrPurchaseDate[1], $arrPurchaseDate[2], ($arrPurchaseDate[0] + 1))) {
                                $strWarrantyDate = ($arrPurchaseDate[0] + 1) . "/" . $arrPurchaseDate[1] . "/" . $arrPurchaseDate[2];
                            } else {
                                $strWarrantyDate = ($arrPurchaseDate[0] + 1) . "/" . $arrPurchaseDate[1] . "/" . ($arrPurchaseDate[2] - 1);
                            }
                        } else {
                            $arrWarrantyDate = explode('/', $this->input->post('warranty_date'));
                            $strWarrantyDate = ($arrWarrantyDate[0]) . "-" . $arrWarrantyDate[1] . "-" . $arrWarrantyDate[2];
                        }
                    } else {
                        if ($this->input->post('warranty_date') != "") {
                            $arrWarrantyDate = explode('/', $this->input->post('warranty_date'));
                            $strWarrantyDate = ($arrWarrantyDate[0]) . "-" . $arrWarrantyDate[1] . "-" . $arrWarrantyDate[2];
                        }
                    }


                    $intPhotoId = -1;
                    if ($this->input->post('photo_present') == "true") {
                        $this->load->helper('file');
                        $strFileName = "mobile-app-" . $this->session->userdata('objAppUser')->accountid . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";


                        if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data')))) {

                            $this->load->model('photos_model');

                            $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                            $arrImageData = array(
                                'file_name' => $strFileName,
                                'image_width' => $arrImageSizeData[0],
                                'image_height' => $arrImageSizeData[1],
                                'file_type' => $arrImageSizeData['mime']
                            );
                            $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "item/default");
                            $arrData['photo_details'] = $this->photos_model->getOne($intPhotoId);
                        }
                    }

                    //                    Add Quanitity Fields
                    if ($this->input->post('item_quantity')) {
                        $qnt_item = (int) $this->input->post('item_quantity');
                    } else {
                        $qnt_item = 1;
                    }

//                    Adding Item Manufecture And Item Manu

                    if ($this->input->post('item_make') != -1) {
                        $manufacturer = trim($this->input->post('item_make'));

                        if ($manufacturer != '') {
                            $data = array(
                                'manufacturer_name' => $manufacturer,
                                'account_id' => $this->session->userdata('objAppUser')->accountid,
                            );

                            $this->admin_section_model->addManufacturer($data);
                        }
                    } else {
                        $manufacturer = trim($this->input->post('manufacturer'));
                    }
                    if ($this->input->post('item_manu') != '') {

                        $data = array(
                            'item_manu_name' => trim($this->input->post('item_manu')),
                            'account_id' => $this->session->userdata('objAppUser')->accountid,
                        );
                        $item_manu = $this->admin_section_model->addItem_Manu($data);
                    } else {
                        $item_manu = trim($this->input->post('manu'));
                    }



                    $arrItemData = array(
                        'barcode' => $barcode,
                        'serial_number' => trim($this->input->post('item_serial_number')),
                        'manufacturer' => $manufacturer,
                        'item_manu' => $item_manu,
                        'model' => trim($this->input->post('item_model')),
                        'site' => (int) $this->input->post('site_id'),
                        'account_id' => (int) $this->session->userdata('objAppUser')->accountid,
                        'value' => (float) $this->input->post('item_value'),
                        'notes' => $this->input->post('item_notes'),
                        'status_id' => (int) $this->input->post('status_id'),
                        'purchase_date' => $this->doFormatDate($this->input->post('purchase_date')),
                        'warranty_date' => $this->doFormatDate($this->input->post('warranty_date')),
                        'replace_date' => $this->doFormatDate($this->input->post('item_replace')),
                        'current_value' => ($this->input->post('item_current_value')),
                        'value' => ($this->input->post('item_value')),
                        'quantity' => $qnt_item,
                        'condition_now' => trim($this->input->post('item_condition')),
                        'condition_since' => date('Y-m-d H:i:s'),
                        'owner_now' => (int) $this->input->post('owner_id'),
                            //'replace_date' =>$this->doFormatDate($this->input->post('item_replace'))
                    );

                    if ($this->input->post('item_patrequired') < 0) {
                        $arrItemData['pattest_status'] = -1;
                    } else {
                        $arrItemData['pattest_status'] = 5;
                    }

                    $arrOutput['arrInput'] = $arrItemData;
                    $arrOutput['arrExtra'] = array('user_id' => (int) $this->input->post('user_id'),
                        'location_id' => (int) $this->input->post('location_id'),
                        'category_id' => (int) $this->input->post('category_id'),
                        'site_id' => (int) $this->input->post('site_id'));

                    $mixNewItemId = $this->items_model->addOne($arrItemData, (int) $this->input->post('category_id'), (int) $this->input->post('location_id'), (int) $this->input->post('user_id'), (int) $this->input->post('site_id'));

                    $mixItemsData = $this->items_model->basicGetOneByBarcode($barcode, $this->session->userdata('objAppUser')->accountid);
                    if ($mixItemsData) {
                        foreach ($mixItemsData[0] as $strKey => $strValue) {
                            $arrData['arrItem'][$strKey] = $strValue;
                            if ($strKey == 'purchase_date') {
                                $arrData['arrItem'][$strKey] = date("d/m/Y", strtotime($strValue));
                                ;
                            }
                            $arrData['arrItem']['itemphotoid'] = $arrData['photo_details']->id;
                            $arrData['arrItem']['itemphotopath'] = $arrData['photo_details']->path;
                            $arrData['arrItem']['itemphototitle'] = $arrData['photo_details']->title;
                        }
                    }

                    //$mixNewItemId = false;
                    if ($mixNewItemId) {
                        $arrOutput['booSuccess'] = true;
                        $arrOutput['intItemId'] = (int) $mixNewItemId;
                        $arrOutput['strBarcode'] = $this->input->post('item_barcode');
                        $arrOutput['strMake'] = $this->input->post('item_make');
                        $arrOutput['strModel'] = $this->input->post('item_model');
                        if ($intPhotoId > 0) {
                            $this->items_model->setPhoto($mixNewItemId, $intPhotoId);
                            $arrOutput['intPhotoId'] = $intPhotoId;
                        }

                        if ($this->input->post('item_condition')) {
                            $this->items_model->logConditionHistoryForApp($mixNewItemId, $this->input->post('item_condition'));
                        }


//*************************************************************************************************************                        
                        /* Handle custom field data IF category has NOT changed. Otherwise ignore this! */

                        $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp((int) $this->input->post('category_id'));

                        /* Enter new data by going through the POST input and extracting those fields that match the custom fields */
                        foreach ($this->input->post() as $k => $v) {
                            foreach ($arrPageData['arrCustomFields'] as $field) {
                                if ($k == $field->id) {
                                    $custom_data[$field->id] = $v;
                                }
                            }
                        }

                        if ($custom_data) {
                            $this->customfields_model->insertContentByItemForApp($mixNewItemId, $custom_data);
                        }


//*************************************************************************************************************                        
                        //$this->logThisForAppUser("Added item on App", "items", $mixNewItemId);
                        // Displaying Item's Custom fields Details ************************************************

                        $arrData_content['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp($mixItemsData[0]->categoryid);
                        $arrData_content['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItemForApp($mixItemsData[0]->itemid);

                        if ($arrData_content['arrCustomFields']) {

                            if ($arrData_content['arrCustomFieldsContent']) {
                                foreach ($arrData_content['arrCustomFields'] as $key1 => $value1) {
                                    foreach ($arrData_content['arrCustomFieldsContent'] as $k => $v) {

                                        if ($v->custom_field_id == $value1->id) {

                                            $arrData_content['arrCustomFields'][$key1]->content = $v->content;
                                        }
                                    }
                                }

                                $arrData['arrItem']['custom_field'] = $arrData_content['arrCustomFields'];
                            } else {

                                $arrData['arrItem']['custom_field'] = $arrData_content['arrCustomFields'];
                            }
                        } else {
                            $arrData['arrItem']['custom_field'] = array();
                        }

//***********************************************************************************
                    }
                    $arrData['Message'] = 'Item Insert Successfully';
                } else {
                    $arrData['Message'] = 'The QRCode already exists';

                    $arrData['booError'] = TRUE;
                    $arrData['strError'] = "The barcode already exists";
                }
            } else {
                $arrData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objAppUser')->currency);
                $arrData['arrPulldowns'] = $this->getPullDowns();
                $arrData['arrPulldowns']['arrManufacturers'] = $this->getManufacturersPullDown();
                $arrOutput['strHtml'] = $this->load->view('appV2/itemadd', $arrData, true);
                $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemadd', $arrData, true);
            }
        }



        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function itemChangeStatus($intItemId) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");


        if ((!$arrOutput['booError']) && ($intItemId != "")) {

            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);

            if ($mixItemsData) {
                //check posted form mode
                if ($this->input->post('mode') == "submit") {
                    if (($this->input->post('status_id') > 0)) {
                        $intUserId = $this->input->post('user_id');
                        if ($intUserId == -1) {
                            $this->items_model->clearCurrentUser($intItemId);
                        } else {
                            $this->items_model->linkThisToUser($intItemId, $intUserId);
                        }

                        $intLocationId = $this->input->post('location_id');
                        if ($intLocationId == -1) {
                            $this->items_model->clearCurrentLocation($intItemId);
                        } else {
                            $this->items_model->linkThisToLocation($intItemId, $intLocationId);
                        }

                        $intSiteId = $this->input->post('site_id');
                        if ($intSiteId == -1) {
                            $this->items_model->clearCurrentSite($intItemId);
                        } else {
                            $this->items_model->linkThisToSite($intItemId, $intSiteId);
                        }
                        // Log it first
                        //$this->logThisForAppUser("Changed Item Owner/Location/Site on App", "items", $intItemId);
                        $arrOutput['booError'] = false;
                        $arrOutput['strMessage'] = "U" . $intUserId . "L" . $intLocationId . "S" . $intSiteId . "I" . $intItemId;
                    } else {
                        $arrOutput['booError'] = true;
                    }
                } else {
                    $arrData['objItem'] = $mixItemsData[0];
                    $arrOutput['strHtml'] = $this->load->view('appV2/itemchangestatus', $arrData, true);
                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemchangestatus', $arrData, true);
                }
            } else {
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Item not found";
            }
        } else {
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Item Id";
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                ->set_output(json_encode($arrOutput));
    }

    public function itemOwnership($intItemId) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");


        if ((!$arrOutput['booError']) && ($intItemId != "")) {

            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);
            if ($mixItemsData) {
                //check posted form mode
                if ($this->input->post('mode') == "submit") {
                    if (($this->input->post('user_id') > 0) || ($this->input->post('location_id') > 0) || ($this->input->post('site_id') > 0)) {
                        $intUserId = $this->input->post('user_id');
                        if ($intUserId == -1) {
                            $this->items_model->clearCurrentUser($intItemId);
                        } else {
                            $this->items_model->linkThisToUser($intItemId, $intUserId);
                        }

                        $intLocationId = $this->input->post('location_id');
                        if ($intLocationId == -1) {
                            $this->items_model->clearCurrentLocation($intItemId);
                        } else {
                            $this->items_model->linkThisToLocation($intItemId, $intLocationId);
                        }

                        $intSiteId = $this->input->post('site_id');
                        if ($intSiteId == -1) {
                            $this->items_model->clearCurrentSite($intItemId);
                        } else {
                            $this->items_model->linkThisToSite($intItemId, $intSiteId);
                        }


                        if ($this->input->post('owner_id') == -1) {
                            $this->items_model->clearCurrentUser($intItemId);
                        } else {
                            $this->items_model->update_owner($intItemId, $this->input->post('owner_id'));
                        }
                        // Log it first
                        //$this->logThisForAppUser("Changed Item Owner/Location/Site on App", "items", $intItemId);
                        $arrOutput['booError'] = false;
                        $arrOutput['strMessage'] = "U" . $intUserId . "L" . $intLocationId . "S" . $intSiteId . "I" . $intItemId;
                    } else {
                        $success = 'NO';
                        $arrData['success'] = $success;
                        $arrOutput['booError'] = true;
                    }
                } else {
                    $arrData['objItem'] = $mixItemsData[0];
                    $arrData['arrPulldowns'] = $this->getPullDowns();
                    $arrOutput['strHtml'] = $this->load->view('appV2/itemownership', $arrData, true);
                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemownership', $arrData, true);
                }
            } else {
                $success = 'NO';
                $arrData['success'] = $success;
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Item not found";
            }
        } else {
            $success = 'NO';
            $arrData['success'] = $success;
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Item Id";
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function itemTicket($intItemId) {
        $this->load->model('tickets_model');
        $this->load->model('users_model');
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));

        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {

            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);
            $arrData['objItem'] = $mixItemsData[0];
            $reportFault = $this->tickets_model->checkReportFault($intItemId);

            if (!$reportFault) {


                if ($mixItemsData) {
                    /* if category has support user ID, get user email */
                    if ($mixItemsData[0]->support_emails) {
                        $user_data = $this->users_model->getOneWithoutAccount($mixItemsData[0]->category_user_id);
                        $category_support = $mixItemsData[0]->support_emails;
                    }

                    if ($this->input->post('mode') == "submit") {

                        $this->load->model('accounts_model');


                        //Add Multiple Photo
                        $intPhotoId = -1;
                        if ($this->input->post('photo_present') == "true") {
                            $this->load->helper('file');
                            $count = trim((int) $this->input->post('photo_count'));
                            for ($i = 1; $i <= $count; $i++) {
                                $strFileName = "mobile-app-" . $i . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";
                                if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data_' . $i)))) {

                                    $this->load->model('photos_model');

                                    $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                                    $arrImageData = array(
                                        'file_name' => $strFileName,
                                        'image_width' => $arrImageSizeData[0],
                                        'image_height' => $arrImageSizeData[1],
                                        'file_type' => $arrImageSizeData['mime']
                                    );
                                    $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "item/default");
                                    $arrimage_id[] = $intPhotoId;
                                    $arrData['photo_details'][] = $this->photos_model->getOne($intPhotoId);
                                }
                            }
                        }
                        $photo_ids = '';
                        if (is_array($arrimage_id)) {
                            if (!empty($arrimage_id)) {
                                $photo_ids = implode(',', $arrimage_id);
                            }
                        }


                        $data = array(
                            'item_id' => $intItemId,
                            'user_id' => $this->session->userdata('objAppUser')->userid,
                            'severity' => $this->input->post("severity"),
                            'order_no' => $this->input->post("order_no"),
                            'jobnote' => $this->input->post("job_notes"),
                            'date' => date("Y-m-d H:i:s"),
                            'ticket_action' => "Open Job",
                            'status' => (int) $this->input->post("itemstatusname"),
                        );

                        if ($photo_ids != '') {
                            $data['photoid'] = $photo_ids;
                        }


                        $arrEmailData = $this->buildEmail($arrData['objItem'], $this->input->post("severity"));

                        $this->tickets_model->insertTicket($data);
                        //okay try to build the email
                        $this->load->library('email');
                        $this->email->from("nathan@accessarea.co.uk", "iWork Audit Ticket");

                        if ($category_support) {

                            $this->email->to('deepika@ignisitsolutions.com');
//                        $this->email->to($category_support);
                        } else {
//                        $this->email->to($this->accounts_model->getSupportEmailAddress($this->session->userdata('objAppUser')->accountid));
                            $this->email->to('deepika@ignisitsolutions.com');
                        }

                        $this->email->subject($mixItemsData[0]->manufacturer . " " . $mixItemsData[0]->model . ":" . $this->input->post('ticket_subject'));
                        $this->email->message($arrEmailData['strZenDeskDataCapture'] . $this->input->post('ticket_message') . $arrEmailData['strMessageBodyItemData']);
                        if (!$this->email->send()) {
                            $arrOutput['booError'] = true;
                            $arrOutput['strError'] = 'Message failed to send';
                        }
                    } else {


//                        $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemticket', $arrData, true);
//                        $arrOutput['strHtml'] = $this->load->view('appV2/itemticket', $arrData, true);
                    }
                    $arrData['message'] = 'Ticket Sent';
                } else {
                    $arrOutput['booError'] = true;
                }
            } else {
                $arrData['message'] = ' Please fix the existing fault for this item before reporting a new fault!';
                $arrData['booError'] = true;
            }
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
    }

    public function itemFixTicket($intItemId) {
        $this->load->model('tickets_model');
        $this->load->model('users_model');
        $arrData = array();

        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));

        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {

            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);

            $arrData['objItem'] = $mixItemsData[0];

            if ($mixItemsData) {
                /* if category has support user ID, get user email */
                if ($mixItemsData[0]->support_emails) {
                    $user_data = $this->users_model->getOneWithoutAccount($mixItemsData[0]->category_user_id);
                    $category_support = $mixItemsData[0]->support_emails;
                }
                $arrData['itemFaultsTicket'] = $this->tickets_model->getFaultTicketHistory($intItemId);

//                var_dump($arrData['itemFaultsTicket'][0]['photoid']);
                if ($this->input->post('mode') == "submit") {
                    $this->load->model('accounts_model');

                    //Add Multiple Photo
                    $intPhotoId = -1;
                    if ($this->input->post('photo_present') == "true") {
                        $this->load->helper('file');
                        $count = trim((int) $this->input->post('photo_count'));
                        for ($i = 1; $i <= $count; $i++) {
                            $strFileName = "mobile-app-" . $i . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";
                            if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data_' . $i)))) {

                                $this->load->model('photos_model');

                                $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                                $arrImageData = array(
                                    'file_name' => $strFileName,
                                    'image_width' => $arrImageSizeData[0],
                                    'image_height' => $arrImageSizeData[1],
                                    'file_type' => $arrImageSizeData['mime']
                                );
                                $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "item/default");
                                $arrimage_id[] = $intPhotoId;
                                $arrData['photo_details'][] = $this->photos_model->getOne($intPhotoId);
                            }
                        }
                    }
                    $all_photo = '';

                    if ($arrData['itemFaultsTicket'][0]['photoid']) {
                        $previous_photos = $arrData['itemFaultsTicket'][0]['photoid'];
                        if (strpos($previous_photos, ',') != FALSE) {
                            $pre = explode(',', $previous_photos);
                        } else {
                            $pre = $previous_photos;
                        }
//                        var_dump($pre);
//                        var_dump($arrimage_id);
                        if (is_array($arrimage_id)) {
                            if (!empty($arrimage_id)) {
                                $allphoto = array_merge_recursive($pre, $arrimage_id);
                                $all_photo = implode(',', $allphoto);
                            }
                        } else {
                            $all_photo = $previous_photos;
                        }
//                        var_dump($all_photo);
                    } else {
                        if (is_array($arrimage_id)) {
                            if (!empty($arrimage_id)) {
                                $all_photo = implode(',', $arrimage_id);
                            }
                        }
                    }



                    if (trim($this->input->post('report_action')) == 'Fix') {

                        $data = array(
                            'fix_item_id' => $intItemId,
                            'job_notes' => $this->input->post('job_notes'),
                            'status' => 1,
                            'fix_code' => $this->input->post('fix_code'),
                            'ticket_id' => $this->input->post('fix_ticket_id'),
                            'fix_date' => date("Y-m-d H:i:s"),
                        );

                        if ($all_photo != '') {
                            $data['photoid'] = $all_photo;
                        }
                        $this->tickets_model->fixStatus($data);
                    } else {
                        $data = array(
                            'reason_code' => $this->input->post('reason_code'),
                            'jobnote' => $this->input->post('job_notes'),
                            'fix_date' => date("Y-m-d H:i:s")
                        );

                        if ($all_photo != '') {
                            $data['photoid'] = $all_photo;
                        }
                        $this->tickets_model->updateTicket($this->input->post('fix_ticket_id'), $data);
                    }
                    //okay try to build the email
                    $this->load->library('email');
                    $this->email->from("nathan@accessarea.co.uk", "iWork Audit Ticket");

                    if ($category_support) {
                        $this->email->to('anjali@ignisitsolutions.com');

//                        $this->email->to('dharmendra@ignisitsolutions.com');
//                        $this->email->to($category_support);
                    } else {
                        $this->email->to('anjali@ignisitsolutions.com');

//                        $this->email->to($this->accounts_model->getSupportEmailAddress($this->session->userdata('objAppUser')->accountid));
//                        $this->email->to('dharmendra@ignisitsolutions.com');
                    }

                    $strMessageBodyItemData = "\r\n -----------------------------------------------------\r\n";
                    $strMessageBodyItemData .= "DATE TIME LOGGED: " . date("Y-m-d H:i:s") . "\r\n";
                    $strMessageBodyItemData .= "USER LOGGED: " . $this->session->userdata('objAppUser')->firstname . " " . $this->session->userdata('objAppUser')->lastname . "\r\n";
                    $strMessageBodyItemData .= "ACCOUNT NAME: " . $this->session->userdata('objAppUser')->accountname . "\r\n";
                    $strMessageBodyItemData .= "MAKE & MODEL: " . $arrData['objItem']->manufacturer . " " . $arrData['objItem']->model . "\r\n";
                    $strMessageBodyItemData .= "LOCATION: " . $arrData['objItem']->locationname . "\r\n";
                    $strMessageBodyItemData .= "SITE: " . $arrData['objItem']->sitename . "\r\n";
                    $strMessageBodyItemData .= "BARCODE: " . $arrData['objItem']->barcode . "\r\n";
                    $strMessageBodyItemData .= "ITEM / MANU: " . $arrData['objItem']->item_manu . "\r\n";
                    $strMessageBodyItemData .= "SERIAL NUMBER: " . $arrData['objItem']->serial_number . "\r\n";
                    $strMessageBodyItemData .= "WARRANTY DATE: " . $arrData['objItem']->warranty_date . "\r\n";
                    $strMessageBodyItemData .= "ACTION :  FIX  \r\n";
                    $strMessageBodyItemData .= "STATUS: OK \r\n";
                    $strMessageBodyItemData .= "FIX CODE: " . $this->input->post('report_action') . "\r\n";
                    $strMessageBodyItemData .= "JOB NOTES: " . $this->input->post('job_notes') . "\r\n";

                    $arrOutput['strMessageBodyItemData'] = $strMessageBodyItemData;


                    $this->email->subject($mixItemsData[0]->manufacturer . " " . $mixItemsData[0]->model . ": FIX/UPDATE REPORT");
                    $this->email->message($arrOutput['strMessageBodyItemData']);
                    if (!$this->email->send()) {
                        $arrOutput['booError'] = true;
                        $arrOutput['strError'] = 'Message failed to send';
                    }
                } else {

                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemticket', $arrData, true);
                    $arrOutput['strHtml'] = $this->load->view('appV2/itemticket', $arrData, true);
                }
                $arrData['message'] = 'Ticket Sent';
            } else {
                $arrOutput['booError'] = true;
            }
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
    }

    public function currentItemsFaults() {
        $this->load->model('tickets_model');
        $this->load->model('users_model');
        $this->load->model('photos_model');
        $arrData = array();

        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));

        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            $current_Faults = $this->tickets_model->getCurrentFaults($this->session->userdata('objAppUser')->accountid);

            foreach ($current_Faults["results"] as $key => $value) {
                $this->load->model('items_model');
                $mixItemsData = $this->items_model->basicGetOne($current_Faults["results"][$key]->itemid, $this->session->userdata('objAppUser')->accountid);
                if ($value->dt) {
                    $mixItemsData[0]->fault_date = str_replace('-', '/', date('d/m/Y', strtotime($value->dt)));
                }
                if ($mixItemsData[0]->photopath) {
                    $mixItemsData[0]->itemphotopath = $mixItemsData[0]->photopath;
                }

                if ($mixItemsData[0]->item_manu_name) {
                    $mixItemsData[0]->item_manu = $mixItemsData[0]->item_manu_name;
                }

                $itemdata['Item_data'] = $mixItemsData[0];
                $current_Faults["results"][$key]->item_details = $itemdata['Item_data'];
                $get_time = $this->date_difference(time(), strtotime($value->dt));
                $current_Faults["results"][$key]->total_time = $get_time;
                $fix_time = $this->date_difference(time(), strtotime($value->fix_date));
                $current_Faults["results"][$key]->fix_time = $fix_time;
                $current_Faults["results"][$key]->dt = str_replace('-', '/', date('d/m/Y', strtotime($value->dt)));
                if (strtotime($value->fix_date) > 0) {
                    $current_Faults["results"][$key]->fix_date = str_replace('-', '/', date('d/m/Y', strtotime($value->fix_date)));
                } else {
                    $current_Faults["results"][$key]->fix_date = "";
                }

                $item_id = $current_Faults["results"][$key]->itemid;

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
                $current_Faults["results"][$key]->jobnote = $notesarray;

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
//                var_dump($photoarray);
//                for ($k = 0; $k < count($photo_array); $k++) {
                if (strpos($photo_array, ',') !== false) {
                    $ids_arr = explode(',', $photoarray);
                    foreach ($ids_arr as $id_val) {
                        $photo_details[] = $this->photos_model->getOne($id_val);
                    }
                    $current_Faults["results"][$key]->photo_details = $photo_details;
                } else {
                    $photo_details = $this->photos_model->getOne($photoarray);
                    $current_Faults["results"][$key]->photo_details = $photo_details;
                }
//                }
            }


            $arrData['Current_Faults'] = $current_Faults["results"];
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
    }

    function date_difference($date1timestamp, $date2timestamp) {
        $all = round(($date1timestamp - $date2timestamp) / 60);
        $d = floor($all / 1440);
        $h = floor(($all - $d * 1440) / 60);
        $m = $all - ($d * 1440) - ($h * 60);
//Since you need just hours and mins
        return $h . ':' . $m;
    }

    public function faultsLookup() {

        $arrData = array();

        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
//        $arrOutput = $this->checkSession("joe.makepeace@accessarea.co.uk","33ba5969ccb997e7b9894f45dd340df2");
        if (!$arrOutput['booError']) {
            if ($this->input->post('mode') == "search") {
//            if (TRUE) {
                $this->load->model('users_model');
                $this->load->model('photos_model');
                $this->load->model('items_model');

                $arrData['arrResults'] = $this->getFaultsSearchResults();
                foreach ($arrData['arrResults'] as $key => $value) {
                    $mixItemsData = $this->items_model->basicGetOne($value->itemid, $this->session->userdata('objAppUser')->accountid);

                    $arrData["arrResults"][$key]->item_detail = $mixItemsData[0];
                    $get_time = $this->date_difference(time(), strtotime($value->dt));
                    $arrData["arrResults"][$key]->total_time = $get_time;

                    $user = $this->users_model->getOne($value->user_id, $this->session->userdata('objAppUser')->accountid);
                    $arrData["arrResults"][$key]->username = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
                    $arrData["arrResults"][$key]->firstname = $user['result'][0]->firstname;
                    $arrData["arrResults"][$key]->lastname = $user['result'][0]->lastname;

                    if (strpos($value->photoid, ',') !== false) {
                        $ids_arr = explode(',', $value['photoid']);
                        foreach ($ids_arr as $id_val) {
                            $photo_details[] = $this->photos_model->getOne($id_val);
                        }
                        $arrData["arrResults"][$key]->photo_details = $photo_details;
                    } else {
                        $photo_details = $this->photos_model->getOne($value->photoid);
                        $arrData["arrResults"][$key]->photo_details = $photo_details;
                    }
                }

                $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemresults', $arrData, true);
                $arrOutput['strHtml'] = $this->load->view('appV2/itemresults', $arrData, true);
            } else {
                $arrData['arrPulldowns'] = $this->getPullDowns();
                $arrData['arrPulldowns']['arrManufacturers'] = $this->getManufacturersPullDown();
                $arrOutput['strHtml'] = $this->load->view('appV2/itemlookup', $arrData, true);
            }
        }



        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function vehicleTicket($intItemId) {
        $this->load->model('tickets_model');
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));

        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {

            $this->load->model('fleet_model');
            $vehicleData = $this->fleet_model->getVehicle($intItemId);
            $arrData['objItem'] = $vehicleData;
            $arrData['arrVehicle'] = $vehicleData;

            if ($vehicleData) {

                if ($this->input->post('mode') == "submit") {

                    $level = $this->input->post('ticket_priority');
                    $priorities = array(1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical');

                    $this->load->model('accounts_model');

                    $strZenDeskDataCapture = "";
                    $strZenDeskDataCapture .= "#requester " . $this->session->userdata('objAppUser')->username . " \r\n";
                    $strZenDeskDataCapture .= "#tags iworkaudit " . $vehicleData['reg_no'] . " \r\n";
                    $strZenDeskDataCapture .= "#problem \r\n";

                    $strZenDeskDataCapture .= " -----------------------------------------------------\r\n";



                    $strMessageBodyItemData = "\r\n -----------------------------------------------------\r\n";
                    $strMessageBodyItemData .= "ACCOUNT NAME: " . $this->session->userdata('objAppUser')->accountname . "\r\n";
                    $strMessageBodyItemData .= "SENDER: " . $this->session->userdata('objAppUser')->firstname . " " . $this->session->userdata('objAppUser')->lastname . "\r\n";

                    $strMessageBodyItemData .= "MAKE & MODEL: " . $vehicleData['make'] . " " . $vehicleData['model'] . "\r\n";
                    $strMessageBodyItemData .= "REG NO: " . $vehicleData['reg_no'] . "\r\n";
                    $strMessageBodyItemData .= "PRIORITY LEVEL: " . $priorities[$level] . "\r\n";

                    //okay try to build the email
                    $this->load->library('email');
                    $this->email->from("tickets@iworkaudit.com", "iWork Audit Ticket");
                    $strSupportAddress = $this->accounts_model->getSupportEmailAddress($this->session->userdata('objAppUser')->accountid);
                    $this->email->to($strSupportAddress);
//                          $this->email->to('dharmendra@ignisitsolutions.com');
                    $this->email->bcc('matt@bespokeinternet.com');
                    $this->email->subject($vehicleData['make'] . " " . $vehicleData['model'] . ":" . $this->input->post('message_title'));

                    $strEmailContent = "";

                    if (strpos($strSupportAddress, 'zendesk.com')) {
                        $strEmailContent = $strZenDeskDataCapture;
                    }

                    $strEmailContent .= $this->input->post('message_body') . $strMessageBodyItemData;

                    $this->email->message($strEmailContent);

                    if ($this->email->send()) {
                        $this->tickets_model->ticketSubmissionFleet($intItemId, $this->session->userdata('objAppUser')->userid, $this->input->post('message_body'), $this->input->post('ticket_priority'));
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The ticket was successfully sent')));
                        redirect('/fleet/view/' . $intItemId, 'refresh');
                    } else {
                        $arrPageData['arrErrorMessages'][] = "Unable to send ticket.";
                    }

                    $arrPageData['strMessageTitle'] = $this->input->post('message_title');
                    $arrPageData['strMessageBody'] = $this->input->post('message_body');

                    if (!$this->email->send()) {
                        $arrOutput['booError'] = true;
                        $arrData['strError'] = "Message failed to send.";
                    }
                    $arrData['message'] = 'Ticket Sent';
                } else {
                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/vehicleticket', $arrData, true);
                    $arrOutput['strHtml'] = $this->load->view('appV2/vehicleticket', $arrData, true);
                }
            } else {
                $arrOutput['booError'] = true;
            }
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
    }

    public function itemDoChangeStatus($intItemId) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);
            $arrData['objItem'] = $mixItemsData[0];

            if ($mixItemsData) {

                if ($this->input->post('mode') == "submit") {
                    $this->load->model('accounts_model');

                    $this->items_model->changeItemStatus($intItemId, $this->input->post('item_status'));
                } else {
                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemticket', $arrData, true);
                    $arrOutput['strHtml'] = $this->load->view('appV2/itemticket', $arrData, true);
                }
            } else {
                $arrOutput['booError'] = true;
            }
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                ->set_output(json_encode($arrOutput));
    }

    public function itemPhoto($intItemId) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);
            $arrData['objItem'] = $mixItemsData[0];

            if ($mixItemsData) {

                if ($this->input->post('mode') == "submit") {
                    $arrOutput['booError'] = true;
                    if ($this->photoUpload($intItemId)) {
                        $arrOutput['booError'] = false;
                    }
                } else {
                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemphoto', $arrData, true);
                    $arrOutput['strHtml'] = $this->load->view('appV2/itemphoto', $arrData, true);
                }
            } else {
                $arrOutput['booError'] = true;
            }
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function vehicleOwnership($intItemId) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");


        if ((!$arrOutput['booError']) && ($intItemId != "")) {

            $this->load->model('items_model');
            $this->load->model('fleet_model');
            $mixItemsData = $this->fleet_model->getVehicle($intItemId, TRUE);

            if ($mixItemsData) {
                //check posted form mode
                if ($this->input->post('mode') == "submit") {
                    if (($this->input->post('user_id') > 0) || ($this->input->post('location_id') > 0) || ($this->input->post('site_id') > 0)) {
                        $intUserId = $this->input->post('user_id');
                        if ($intUserId == -1) {
                            $this->fleet_model->clearCurrentUser($intItemId);
                        } else {
                            $this->fleet_model->linkThisToUser($intItemId, $intUserId);
                        }

                        $intLocationId = $this->input->post('location_id');
                        if ($intLocationId == -1) {
                            $this->fleet_model->clearCurrentLocation($intItemId);
                        } else {
                            $this->fleet_model->linkThisToLocation($intItemId, $intLocationId);
                        }

                        $intSiteId = $this->input->post('site_id');
                        if ($intSiteId == -1) {
                            $this->fleet_model->clearCurrentSite($intItemId);
                        } else {
                            $this->fleet_model->linkThisToSite($intItemId, $intSiteId);
                        }
                        // Log it first
                        //$this->logThisForAppUser("Changed Item Owner/Location/Site on App", "items", $intItemId);
                        $arrOutput['booError'] = false;
                        $arrOutput['strMessage'] = "U" . $intUserId . "L" . $intLocationId . "S" . $intSiteId . "I" . $intItemId;
                        $success = 'YES';
                        $arrData['success'] = $success;
                    } else {
                        $success = 'NO';
                        $arrData['success'] = $success;
                        $arrOutput['booError'] = true;
                    }
                } else {
                    $success = 'NO';
                    $arrData['success'] = $success;
                    $arrData['objItem'] = $mixItemsData;
                    //print "<pre>"; print_r($arrData['objItem']); print "</pre>";
                    $arrData['arrPulldowns'] = $this->getPullDowns();
                    //print "<pre>"; print_r($arrData['arrPulldowns']); print "</pre>";
                    $arrOutput['strHtml'] = $this->load->view('appV2/vehicleownership', $arrData, true);
                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/vehicleownership', $arrData, true);
                }
            } else {
                $success = 'NO';
                $arrData['success'] = $success;
                $arrOutput['booError'] = true;
                $arrOutput['strError'] = "Item not found";
            }
        } else {
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Item Id";
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                ->set_output(json_encode($arrOutput));
    }

    public function itemCopy($intItemId) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            $this->load->model('items_model');
            $this->load->model('photos_model');
            $this->load->model('categories_model');
            $this->load->model('customfields_model');

            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);
            $arrData['objItem'] = $mixItemsData[0];

            if ($mixItemsData) {


                $this->load->model('applications_model');
                if ($this->input->post('mode') == "submit") {
                    if ($this->input->post('item_barcode') != "") {
                        if ($this->applications_model->checkBarcodeUnique(trim($this->input->post('item_barcode')))) {
                            $objItemToCopy = $mixItemsData[0];
                            // insert photo when add similar from app       
                            $intPhotoId = -1;
                            if ($this->input->post('photo_present') == "true") {
                                $this->load->helper('file');
                                $strFileName = "mobile-app-" . $this->session->userdata('objAppUser')->accountid . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";


                                if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data')))) {
                                    $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                                    $arrImageData = array(
                                        'file_name' => $strFileName,
                                        'image_width' => $arrImageSizeData[0],
                                        'image_height' => $arrImageSizeData[1],
                                        'file_type' => $arrImageSizeData['mime']
                                    );
                                    $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "item/default");
                                    $arrData['photo_details'] = $this->photos_model->getOne($intPhotoId);
                                }
                            } else {
                                $intPhotoId = $objItemToCopy->photo_id;
                                $arrData['photo_details'] = $this->photos_model->getOne($intPhotoId);
                            }

                            // Add Items Quantity

                            if ($this->input->post('item_quantity')) {
                                $qnt_item = (int) $this->input->post('item_quantity');
                            } else {
                                $qnt_item = 1;
                            }

                            if ($qnt_item == 0) {
                                $qnt_item = 1;
                            }
                            $arrItemData = array(
                                'barcode' => $this->session->userdata('objAppUser')->qrcode . $this->input->post('item_barcode'),
                                'serial_number' => $this->input->post('item_serial_number'),
                                'item_manu' => $objItemToCopy->item_manu,
                                'manufacturer' => $objItemToCopy->manufacturer,
                                'model' => $objItemToCopy->model,
                                'site' => (int) $objItemToCopy->siteid,
                                'notes' => $objItemToCopy->notes,
                                'account_id' => (int) $this->session->userdata('objAppUser')->accountid,
                                'value' => (float) ($this->input->post('item_value')),
                                'purchase_date' => $this->doFormatDate($this->input->post('purchase_date')),
                                'warranty_date' => $objItemToCopy->warranty_date,
                                'pdf_name' => $objItemToCopy->pdf_name,
                                'quantity' => $qnt_item,
                                'condition_now' => trim($this->input->post('item_condition')),
                                'condition_since' => date('Y-m-d H:i:s'),
                                'owner_now' => (int) $this->input->post('owner_now'),
                            );
                            if ($objItemToCopy->pattest_status == 5) {
                                $arrItemData['pattest_status'] = $objItemToCopy->pattest_status;
                            }
                            $mixNewItemId = $this->items_model->addOne($arrItemData, (int) $objItemToCopy->categoryid, (int) $this->input->post('location_id'), (int) $this->input->post('user_id'), (int) $this->input->post('site_id'));
                            $mixNewItemsData = $this->items_model->basicGetOneByBarcode($this->input->post('item_barcode'), $this->session->userdata('objAppUser')->accountid);
                            if ($mixNewItemId) {
                                foreach ($mixNewItemsData[0] as $strKey => $strValue) {
                                    $arrData['arrItem'][$strKey] = $strValue;
                                    if ($strKey == 'purchase_date') {
                                        $arrData['arrItem'][$strKey] = date("d/m/Y", strtotime($strValue));
                                        ;
                                    }
                                    $arrData['arrItem']['itemphotoid'] = $arrData['photo_details']->id;
                                    $arrData['arrItem']['itemphotopath'] = $arrData['photo_details']->path;
                                    $arrData['arrItem']['itemphototitle'] = $arrData['photo_details']->title;
                                }


                                $arrOutput['booSuccess'] = true;
                                $arrData['strError'] = "Item Add Successfully";
                                $arrOutput['intItemId'] = (int) $mixNewItemId;
                                $arrOutput['strBarcode'] = $this->input->post('item_barcode');
                                $arrOutput['strMake'] = $objItemToCopy->model;
                                $arrOutput['strModel'] = $objItemToCopy->manufacturer;
                                if ($intPhotoId > 0) {
                                    $this->items_model->setPhoto($mixNewItemId, $intPhotoId);
                                }


                                if ($this->input->post('item_condition')) {
                                    $this->items_model->logConditionHistoryForApp($mixNewItemId, $this->input->post('item_condition'));
                                }

                                //$this->logThisForAppUser("Added Copied item on App", "items", $mixNewItemId);
                                //*************************************************************************************************************                        
                                /* Handle custom field data IF category has NOT changed. Otherwise ignore this! */

                                $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp((int) $objItemToCopy->categoryid);

                                /* Enter new data by going through the POST input and extracting those fields that match the custom fields */
                                foreach ($this->input->post() as $k => $v) {
                                    foreach ($arrPageData['arrCustomFields'] as $field) {
                                        if ($k == $field->id) {
                                            $custom_data[$field->id] = $v;
                                        }
                                    }
                                }

                                if ($custom_data) {
                                    $this->customfields_model->insertContentByItemForApp($mixNewItemId, $custom_data);
                                }


//*************************************************************************************************************   
                                // Displaying Item's Custom fields Details ************************************************

                                $arrData_content['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp($mixNewItemsData[0]->categoryid);
                                $arrData_content['arrCustomFieldsContent'] = $this->customfields_model->getCustomFieldsByItemForApp($mixNewItemId);

                                if ($arrData_content['arrCustomFields']) {

                                    if ($arrData_content['arrCustomFieldsContent']) {
                                        foreach ($arrData_content['arrCustomFields'] as $key1 => $value1) {
                                            foreach ($arrData_content['arrCustomFieldsContent'] as $k => $v) {

                                                if ($v->custom_field_id == $value1->id) {

                                                    $arrData_content['arrCustomFields'][$key1]->content = $v->content;
                                                }
                                            }
                                        }

                                        $arrData['arrItem']['custom_field'] = $arrData_content['arrCustomFields'];
                                    } else {

                                        $arrData['arrItem']['custom_field'] = $arrData_content['arrCustomFields'];
                                    }
                                } else {
                                    $arrData['arrItem']['custom_field'] = array();
                                }
                            } else {
                                $arrOutput['booError'] = true;
                                $arrOutput['strError'] = "Failed to copy";
                            }
                        } else {
                            $arrData['strError'] = "The barcode already exists";

                            $arrOutput['booError'] = true;
                            $arrOutput['strError'] = "The barcode already exists";
                        }
                    } else {
                        $arrOutput['booError'] = true;
                        $arrOutput['strError'] = "Enter a Barcode";
                    }
                } else {
                    $arrData['objItem'] = $mixItemsData[0];
                    $arrData['arrPulldowns'] = $this->getPullDowns();

                    $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemcopy', $arrData, true);
                    $arrOutput['strHtml'] = $this->load->view('appV2/itemcopy', $arrData, true);
                }
            } else {
                $arrOutput['booError'] = true;
            }
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function itemPat($strBarcode = "") {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            if ($strBarcode != "") {
                #barcode - get Item
                $this->load->model('items_model');
                $mixItemsData = $this->items_model->basicGetOneByBarcode($strBarcode, $this->session->userdata('objAppUser')->accountid);
                if (strtotime($mixItemsData[0]->pattest_date) > 0) {
                    $mixItemsData[0]->pattest_date = (date("d/m/Y", strtotime($mixItemsData[0]->pattest_date)));
                } else {
                    $mixItemsData[0]->pattest_date = "N/A";
                }
                $arrData['objItem'] = $mixItemsData[0];

                if ($mixItemsData) {

                    if ($this->input->post('mode') == "submit") {
                        $arrTestDate = explode('-', $this->input->post('pattest_date'));
                        $strTestDate = $arrTestDate[0] . "/" . $arrTestDate[1] . "/" . $arrTestDate[2];
                        $pat_date = $this->doFormatDate($this->input->post('pattest_date'));
                        if ($this->items_model->editPATResult($mixItemsData[0]->itemid, $pat_date, $this->input->post('pattest_status'))) {
//                            $pat_status = array(0 => 'fail',1=> 'pass' , -1 => 'unknown');
                            $this->items_model->linkThisToPat($mixItemsData[0]->itemid, $this->input->post('pattest_status'), $this->session->userdata('objAppUser')->userid);
                            //$this->logThisForAppUser("Updated PAT Result on App", "items", (int)$mixItemsData[0]->itemid);
                        }
                        $success = 'YES';
                        $arrData['success'] = $success;
                    } else {
                        $success = 'NO';
                        $arrData['success'] = $success;
                        $arrOutput['strHeader'] = $this->load->view('appV2/headers/itempatresult', $arrData, true);
                        $arrOutput['strHtml'] = $this->load->view('appV2/itempatresult', $arrData, true);
                    }
                } else {
                    $success = 'NO';
                    $arrData['success'] = $success;
                    $arrOutput['booError'] = true;
                }
            } else {
                # no barcode, show lookup
                $arrOutput['strHeader'] = $this->load->view('appV2/headers/itempatlookup', $arrData, true);
                $arrOutput['strHtml'] = $this->load->view('appV2/itempatlookup', $arrData, true);
            }
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function locationAudit($intLocationId) {
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError'] && ($intLocationId > 0)) {
            $arrAuditInformation = array();
            $arrAuditInformation['location_id'] = $intLocationId;
            $arrAuditInformation['account_id'] = $this->session->userdata('objAppUser')->accountid;
            $arrAuditInformation['user_id'] = $this->session->userdata('objAppUser')->userid;
            $arrAuditInformation['completed'] = date("Y-m-d H:i:s");

            $this->load->model('audits_model');
            $this->load->model('items_model');
            $intAuditId = $this->audits_model->logOne($arrAuditInformation);

            $arrPresentItems = explode(",", $this->input->post('items_present'));
            $arrMissingItems = explode(",", $this->input->post('items_missing'));

            foreach ($arrPresentItems as $intItemPresent) {
                $arrAuditItem = array('item_id' => (int) $intItemPresent, 'audit_id' => $intAuditId, 'present' => 1);
                $this->audits_model->addItemToAudit($arrAuditItem);
                $item_status = $this->items_model->getItemStatus((int) $intItemPresent);
                if ($item_status == 6) {
                    $this->items_model->setItemStatus((int) $intItemPresent, 1); //1 = OK and not missing
                }
            }
            foreach ($arrMissingItems as $intItemMissing) {
                $arrAuditItem = array('item_id' => (int) $intItemMissing, 'audit_id' => $intAuditId, 'present' => 0);
                $this->audits_model->addItemToAudit($arrAuditItem);
                $this->items_model->setItemStatus((int) $intItemMissing, 6); //6 = Missing Status
            }
            //$this->logThisForAppUser("Audited location on App", "locations", $intLocationId);
            $arrOutput['booError'] = false;
            $arrOutput['strLocationName'] = $this->input->post('locationname');
        } else {
            $arrOutput['booError'] = true;
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrOutput));
    }

    public function locationAuditCondition($intLocationId) {
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError'] && ($intLocationId > 0)) {
            $arrAuditInformation = array();
            $arrAuditInformation['location_id'] = $intLocationId;
            $arrAuditInformation['account_id'] = $this->session->userdata('objAppUser')->accountid;
            $arrAuditInformation['user_id'] = $this->session->userdata('objAppUser')->userid;
            $arrAuditInformation['completed'] = date("Y-m-d H:i:s");

            $this->load->model('audits_model');
            $this->load->model('items_model');
            $intAuditId = $this->audits_model->logOne($arrAuditInformation);
//            var_dump($this->input->post('items_condition'));
            $arrConditionItems = explode(",", $this->input->post('items_condition'));
            $arrPresentItems = explode(",", $this->input->post('items_present'));
            $arrMissingItems = explode(",", $this->input->post('items_missing'));

//            Audit New Condition
            foreach ($arrConditionItems as $intItemCondition) {

                $item_details = explode('|', $intItemCondition);
                $item_id = (int) $item_details[1];
                $condition_id = (int) $item_details[0];
                $this->items_model->auditCondition_logForApp($item_id, $condition_id);
            }

            foreach ($arrPresentItems as $intItemPresent) {
                $arrAuditItem = array('item_id' => (int) $intItemPresent, 'audit_id' => $intAuditId, 'present' => 1);
                $this->audits_model->addItemToAudit($arrAuditItem);
                $item_status = $this->items_model->getItemStatus((int) $intItemPresent);
                if ($item_status == 6) {
                    $this->items_model->setItemStatus((int) $intItemPresent, 1); //1 = OK and not missing
                }
            }
            foreach ($arrMissingItems as $intItemMissing) {
                $arrAuditItem = array('item_id' => (int) $intItemMissing, 'audit_id' => $intAuditId, 'present' => 0);
                $this->audits_model->addItemToAudit($arrAuditItem);
                $this->items_model->setItemStatus((int) $intItemMissing, 6); //6 = Missing Status
            }
            //$this->logThisForAppUser("Audited location on App", "locations", $intLocationId);
            $arrOutput['booError'] = false;
            $arrOutput['Message'] = 'Audit Successful';
            $arrOutput['strLocationName'] = $this->input->post('locationname');
        } else {
            $arrOutput['booError'] = true;
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrOutput));
    }

    public function viewUserHero($intId = -1) {


        $this->load->model('photos_model');

        $objImage = $this->photos_model->getOne($intId);

        $arrConfig['image_library'] = 'gd2';
        $arrConfig['source_image'] = './uploads/' . $objImage->file_name;
        $arrConfig['create_thumb'] = true;
        $arrConfig['maintain_ratio'] = true;
        $arrConfig['dynamic_output'] = true;
        $arrConfig['width'] = 62;
        $arrConfig['height'] = 62;

        $this->load->library('image_lib', $arrConfig);

        $this->image_lib->resize();
    }

    public function vehicleChecks($vehicleID) {

        $this->load->library('email');
        $this->load->model('fleet_model');
        $this->load->model('users_model');
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        $vehicle = $this->fleet_model->getVehicle($vehicleID);

        if (!$arrOutput['booError'] && ($vehicleID > 0)) {
            $arrData['vehicle_id'] = $vehicleID;
            $arrData['checks'] = $this->fleet_model->getCheckDetailsByVehicle($vehicleID);
            $arrOutput['strHtml'] = $this->load->view('appV2/vehiclechecks', $arrData, true);
            if ($this->input->post('failed') || $this->input->post('passed')) {
                $this->fleet_model->logChecks($vehicleID, $this->input->post());

                if ($this->input->post('failed')) {


                    $data = $this->input->post('failed');
                    $failed = rtrim($data, ',');
                    $arrFailed = explode(',', $failed);
                    foreach ($arrFailed as $record) {

                        $check_data = $this->fleet_model->getCheck($record);

                        $note_for_check = $this->input->post('notes_' . $record);


                        $this->email->from($this->session->userdata('objAppUser')->fleet_email, $this->session->userdata('objAppUser')->fleet_contact);
                        $this->email->to($this->session->userdata('objAppUser')->fleet_email, $this->session->userdata('objAppUser')->fleet_contact);
//                        $this->email->to('dharmendra@ignisitsolutions.com');
                        $this->email->subject('Vehicle has failed a compliance check');
                        $this->email->message($this->session->userdata('objAppUser')->compliance_contact . ',

                    A user has submitted a vehicle which has failed one or more compliance checks.

                    Barcode: ' . $vehicle['barcode'] . '
                    Registration number: ' . ($vehicle['reg_no'] ? $vehicle['reg_no'] : 'N/A') . '
                    Make & Model: ' . $vehicle['make'] . ' ' . $vehicle['model'] . '
                    Owner: ' . ($vehicle['owner'] ? $vehicle['owner'] : 'N/A') . '
                    Site: ' . ($vehicle['site'] ? $vehicle['site'] : 'N/A') . '
                    Location: ' . ($vehicle['name'] ? $vehicle['name'] : 'N/A') . '
                    Compliance Check Name:  ' . $check_data->check_name . '
                    Notes:  ' . $note_for_check . '
                    ');
                        $this->email->send();
                    }
                }
            }
        } else {
            
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function vehiclechecksqr($qr) {
        $this->load->library('email');
        $this->load->model('fleet_model');

        $arrData = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        $vehicle = $this->fleet_model->getVehicle($qr, NULL, TRUE);
        if (!$arrData['booError'] && (strlen($qr) > 0)) {
            $arrData['qr'] = $qr;
            $arrData['vehicle_id'] = $vehicle['fleet_id'];
            $arrData['checks'] = $this->fleet_model->getCheckDetailsByVehicle($qr, TRUE);

            if (!$vehicle['Message']) {
                $success = 'YES';
                $arrData['success'] = $success;
            } else {
                $success = 'NO';
                $arrData['success'] = $success;
            }

            $arrOutput['strHtml'] = $this->load->view('appV2/vehiclechecks', $arrData, true);
            if ($this->input->post('failed') || $this->input->post('passed')) {
                $this->fleet_model->logChecks($vehicleID, $this->input->post());

                if ($this->input->post('failed')) {

                    $this->email->from($this->session->userdata('objAppUser')->fleet_email, $this->session->userdata('objAppUser')->fleet_contact);
                    $this->email->to($this->session->userdata('objAppUser')->fleet_email, $this->session->userdata('objAppUser')->fleet_contact);
                    $this->email->subject('Vehicle has failed a compliance check');
                    $this->email->message($this->session->userdata('objAppUser')->compliance_contact . ',

                    A user has submitted a vehicle which has failed one or more compliance checks.

                    Barcode: ' . $vehicle['barcode'] . '
                    Registration number: ' . ($vehicle['reg_no'] ? $vehicle['reg_no'] : 'N/A') . '
                    Make & Model: ' . $vehicle['make'] . ' ' . $vehicle['model'] . '
                    Owner: ' . ($vehicle['owner'] ? $vehicle['owner'] : 'N/A') . '
                    Site: ' . ($vehicle['site'] ? $vehicle['site'] : 'N/A') . '
                    ');
                    $this->email->send();
                }
            }
        } else {
            $success = 'NO';
            $arrData['success'] = $success;
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function complianceChecks($itemID) {
        $this->load->model('items_model');
        $this->load->model('photos_model');
        $this->load->model('tests_model');
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        $item = $this->items_model->getOne($itemID, $this->session->userdata('objAppUser')->accountid);
//        $item = $this->items_model->getOne($itemID, 5);
//        var_dump($item[0]->categoryemail);

        if (!$arrOutput['booError'] && ($itemID > 0)) {
            $arrData['item_id'] = $itemID;

//            $arrData['checks'] = $this->items_model->getChecksByItem1($itemID);
            $arrData['checks'] = $this->items_model->getChecksByItemDues($itemID);
            $arrOutput['strHtml'] = $this->load->view('appV2/itemchecks', $arrData, true);
            if ($this->input->post()) {

//                Set Digital Signature

                $intPhotoId = 1;
                if ($this->input->post('photo_present') == "true") {
                    $this->load->helper('file');
                    $strFileName = "mobile-app-" . $this->session->userdata('objAppUser')->accountid . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";


                    if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data')))) {

                        $this->load->model('photos_model');

                        $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                        $arrImageData = array(
                            'file_name' => $strFileName,
                            'image_width' => $arrImageSizeData[0],
                            'image_height' => $arrImageSizeData[1],
                            'file_type' => $arrImageSizeData['mime']
                        );
                        $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "Compliance/default");
                        $arrData['photo_details'] = $this->photos_model->getOne($intPhotoId);
                    }
                }

//      **************************************************************************************          


                $arr = array();
                $arr = json_decode($this->input->post('compliance_arr'));
                $arrData['result'] = $arr;
                foreach ($arr->compliance as $data) {
                    $this->items_model->recordCheck($data, $itemID, $intPhotoId);
                    sleep(1);
                }


                /*                 * ***********mail Data coding *************** */

                foreach ($arr->compliance as $data) {
                    if ($data->failedChecks) {
                        $str_array[] = $data->failedChecks;
                    }
                }
                $temp_string = implode(',', $str_array);
                $temp = explode(',', $temp_string);

                if ($temp[0] != '') {
                    foreach ($temp as $key => $value) {
                        $failed[] = explode('|', $value);
                    }

                    foreach ($failed as $value1) {
                        $test_type_id = (int) $value1[0];
                        $test_notes = $value1[1];
                        foreach ($arrData['checks'] as $compliance) {
                            foreach ($compliance["compliance"]['tasks'] as $task_details) {
                                if ($task_details['id'] == $test_type_id) {
                                    $task_name[] = array(
                                        'compliance_name' => $compliance["compliance"]["test_type_name"],
                                        'task_name' => $task_details['task_name'],
                                        'task_notes' => $test_notes
                                    );
                                }
                            }
                        }
                    }
                    $unique_arr = array_unique($task_name, SORT_REGULAR);
                    foreach ($unique_arr as $email_content) {
                        $compliance_val[] = 'Compliance Name: ' . $email_content['compliance_name'] . "\r\n" . 'Failed Task: ' . $email_content['task_name'] . "\r\n" . ' Notes For Failed Task: ' . $email_content['task_notes'] . "\r\n";
                    }

                    $compliance_str = implode('', $compliance_val);
                }


                if ($temp[0] != '') {


                    /* Then send email */
                    $this->load->library('email');
                    $this->email->from($this->session->userdata('objAppUser')->compliance_email, $this->session->userdata('objAppUser')->compliance_contact);
//                    if (isset($item[0]->categoryemail)) {
//                        $this->email->to($item[0]->categoryemail);
//                        $this->email->cc($this->session->userdata('objAppUser')->compliance_email, $this->session->userdata('objAppUser')->compliance_contact);
//                    } else {
//                        $this->email->to($this->session->userdata('objAppUser')->compliance_email, $this->session->userdata('objAppUser')->compliance_contact);
//                    }
                    if (isset($item[0]->categoryemail)) {

                        $this->email->to($item[0]->categoryemail);
                    } else {
                        $this->email->to($this->session->userdata('objAppUser')->compliance_email, $this->session->userdata('objAppUser')->compliance_contact);
                    }

//                    $this->email->to('dharmendra@ignisitsolutions.com');
                    $this->email->cc($this->session->userdata('objAppUser')->compliance_email);


                    $this->email->subject('Item has failed a compliance check');
                    $this->email->message($this->session->userdata('objAppUser')->compliance_contact . ',


                    A user has submitted an item which has failed one or more compliance checks.

                    Manufacturer Model: ' . $item[0]->manufacturer . ' ' . $item[0]->model . ',
                    Category: ' . $item[0]->categoryname . ',
                    QR Code: ' . $item[0]->barcode . ',
                    Site: ' . $item[0]->sitename . ',
                    Location: ' . $item[0]->locationname . ',
                    Logged Check: ' . $this->session->userdata('objAppUser')->firstname . " " . $this->session->userdata('objAppUser')->lastname . ',
                    Date time check completed : ' . date("d-m-Y") . ',
                    ' . $compliance_str . '
               
                    ');
                    $this->email->send();
                }
            }
        } else {
            
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function complianceChecksdue($location_id = -1) {
        $this->load->model('items_model');
        $this->load->model('tests_model');
        $this->load->model('users_model');
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        $arrData = array();
        if (!$arrOutput['booError']) {


            $end_date = date('Y-m-d', strtotime('+7 days'));
            $start_date = date('Y-m-d', strtotime('7 days ago'));

//            $Output = $this->tests_model->getDueTests(5, array('start_date' => $start_date, 'end_date' => $end_date));
            $Output = $this->tests_model->getDueTests($this->session->userdata('objAppUser')->accountid, array('start_date' => $start_date, 'end_date' => $end_date));
            $arrData1['dueTest'] = $Output['dueMandatory'];

            foreach ($arrData1['dueTest'] as $item_record) {


                $arrData['item_details'][] = array(
                    'item_details' => $item_record["item"],
                    'Checks' => $this->items_model->getChecksByItemDues($item_record["item"]->itemid)
                );
            }
        } else {
            
        }
        foreach ($arrData['item_details'] as $key => $value) {
            if ((int) $location_id != -1) {
                if ($value["item_details"]->locationid != (int) $location_id) {
                    unset($arrData['item_details'][$key]);
                }
            }
        }
        $arrData['item_details'] = array_values($arrData['item_details']);
//        var_dump($arrData);
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function supplierlist() {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {

            $this->load->model('suppliers_model');
            $val = $this->suppliers_model->getAll($this->session->userdata('objAppUser')->accountid);

            if ($val == 0)
                $arrData['arrSuppliers'] = array();
            else
                $arrData['arrSuppliers'] = $val;

            foreach ($arrData['arrSuppliers'] as $key => $value) {
                if ($arrData['arrSuppliers'][$key]['supplier_website'] != "") {
                    if (strpos($arrData['arrSuppliers'][$key]['supplier_website'], 'http://') !== 0) {
                        $arrData['arrSuppliers'][$key]['supplier_website'] = 'http://' . $arrData['arrSuppliers'][$key]['supplier_website'];
                    } else {
                        $arrData['arrSuppliers'][$key]['supplier_website'] = $arrData['arrSuppliers'][$key]['supplier_website'];
                    }
                }
            }

            $arrOutput['strHeader'] = $this->load->view('appV2/headers/supplierlist', $arrData, true);
            $arrOutput['strHtml'] = $this->load->view('appV2/supplierlist', $arrData, true);
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function getsupplier($strBarcode) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        if (!$arrOutput['booError'] && ($strBarcode != "")) {

            $this->load->model('suppliers_model');
            $this->load->model('items_model');

            $mixItemsData = $this->items_model->basicGetOneByBarcode($strBarcode, $this->session->userdata('objAppUser')->accountid);
            $arrData['objSupplier'] = $this->suppliers_model->getOne($mixItemsData[0]->supplier);

            $arrOutput['strHeader'] = $this->load->view('appV2/headers/supplierview', $arrData, true);
            $arrOutput['strHtml'] = $this->load->view('appV2/supplierview', $arrData, true);
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//            ->set_output(json_encode($arrOutput));
    }

    public function item_lookup_param() {
        $this->load->model('itemstatus_model');
        $this->load->model('suppliers_model');
        $this->load->model('categories_model');
        $this->load->model('admin_section_model');
        $this->load->model('tickets_model');
        $this->load->model('items_model');
        $this->load->model('tests_model');
        $arrData = array();

//      TEsting *************************************************
//        $end_date = date('Y-m-d', strtotime('+7 days'));
//        $start_date = date('Y-m-d', strtotime('7 days ago'));
//        $Output = $this->tests_model->getDueTests(1, array('start_date' => $start_date, 'end_date' => $end_date));
//
//        foreach ($Output['dueMandatory'] as $id_array) {
//            $total_tests_arr[] = $id_array["tests"];
//        }
//        $count_check_due = 0;
//        foreach ($total_tests_arr as $record) {
//            foreach ($record as $count_manager) {
//                if ($count_manager["manager_id"] == "37") {
//                    $count_check_due++;
//                }
//            }
//        }
//        ********************************************************



        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            if ($this->input->post('mode') == "search") {
                $arrData['arrResults'] = $this->getSearchResults();
                $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemresults', $arrData, true);
                $arrOutput['strHtml'] = $this->load->view('appV2/itemresults', $arrData, true);
            } else {
                $arrData['arrPulldowns'] = $this->getPullDowns();
                $arrData['arrPulldowns']['arrManufacturers'] = $this->getManufacturersPullDown();
                $arrOutput['strHtml'] = $this->load->view('appV2/itemlookup', $arrData, true);
            }

            $data_array = array();
            foreach ($arrData as $objarr) {

                foreach ($objarr['arrManufacturers'] as $manufacture) {
                    $data_array['manufacturer'][] = array(
                        'name' => $manufacture->manufacturername
                    );
                }
                foreach ($objarr['arrSites'] as $sites) {
                    $data_array['sites'][] = array(
                        'id' => $sites->siteid,
                        'name' => $sites->sitename
                    );
                }
                foreach ($objarr['arrUsers'] as $users) {
                    $data_array['users'][] = array(
                        'id' => $users->userid,
                        'name' => $users->username
                    );
                }
                foreach ($objarr['arrLocations'] as $location) {
                    $data_array['locations'][] = array(
                        'id' => $location->locationid,
                        'name' => $location->locationname,
                        'barcode' => $location->locationbarcode,
                        'location_site_id' => $location->location_site_id
                    );
                }
            }
            $arr = array();
            $arr = $this->getPullDowns();
            foreach ($arr['arrCategories'] as $categories) {

                if ((int) $categories->quantity == 0) {
                    $bool = FALSE;
                } else {
                    $bool = TRUE;
                }
                $custom_fields = $this->categories_model->getCustomFieldsForApp($categories->categoryid);
                $data_array['categories'][] = array(
                    'id' => $categories->categoryid,
                    'name' => $categories->categoryname,
                    'quantity_enabled' => $bool,
                    'custom_fields' => $custom_fields,
                );
            }
            $intAccountId = $this->session->userdata('objAppUser')->accountid;
            $arrMakes = $this->fleet_model->getAll($intAccountId);

            $data_array['arrMakes'] = $arrMakes['results'];

            $arrItemStatuses = $this->itemstatus_model->getAll();
            $data_array['arrItemStatuses'] = $arrItemStatuses['results'];
            if ($this->suppliers_model->getAll($this->session->userdata('objAppUser')->accountid)) {
                $data_array['arrSuppliers'] = $this->suppliers_model->getAll($this->session->userdata('objAppUser')->accountid);
            } else {
                $data_array['arrSuppliers'] = array();
            }

            $data_array['arrItemManu'] = $this->admin_section_model->getItem_Manu($this->session->userdata('objAppUser')->accountid);
            $data_array['arrManufaturer'] = $this->admin_section_model->getManufacturer($this->session->userdata('objAppUser')->accountid);
            $data_array['arrCondition'] = $this->items_model->get_condition();
            $current_Faults = $this->tickets_model->getCurrentFaults($this->session->userdata('objAppUser')->accountid);

            if ($this->session->userdata('objAppUser')->push_notification == 1) {
                $data_array['countOfCurrentFaults'] = count($current_Faults['results']);
            } else {
                $data_array['countOfCurrentFaults'] = -1;
            }
            $data_array['arrOwnerList'] = $this->admin_section_model->ownerlist($intAccountId);

//        Count Of Compliance Due

            $end_date = date('Y-m-d', strtotime('+7 days'));
            $start_date = date('Y-m-d', strtotime('7 days ago'));
            $Output = $this->tests_model->getDueTests($this->session->userdata('objAppUser')->accountid, array('start_date' => $start_date, 'end_date' => $end_date));

            foreach ($Output['dueMandatory'] as $id_array) {
                $total_tests_arr[] = $id_array["tests"];
            }
            $count_check_due = 0;
            foreach ($total_tests_arr as $record) {
                foreach ($record as $count_manager) {
                    if ($count_manager["manager_id"] == (string) $this->session->userdata('objAppUser')->userid) {
                        $count_check_due++;
                    }
                }
            }
            if ($this->session->userdata('objAppUser')->push_notification == 1) {
                $data_array['CountDueTest'] = $count_check_due;
            } else {
                $data_array['CountDueTest'] = -1;
            }
            $data_array['compliance_status'] = array('PASS', 'FAIL', 'MISSED');
            $data_array['item_qrcode'] = $this->session->userdata('objAppUser')->qrcode;
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data_array));
    }

    public function doFormatDate($strDate) {
        if ($strDate != "") {
            $arrDate = explode('/', $strDate);
            return $arrDate[2] . "-" . $arrDate[1] . "-" . $arrDate[0];
        }
        return NULL;
    }

    public function itemUpdate($itemid) {

        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {
            if ($this->input->post()) {

                $this->load->model('items_model');
                $this->load->model('photos_model');
                $this->load->model('categories_model');
                $this->load->model('customfields_model');
                $this->load->model('applications_model');
                $this->load->helper('file');
                $intPhotoId = '-1';
                $success = 1;
                if ($this->input->post('photo_present') == "true") {
                    $this->load->helper('file');
                    $strFileName = "mobile-app-" . $this->session->userdata('objAppUser')->accountid . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";


                    if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data')))) {
                        $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                        $arrImageData = array(
                            'file_name' => $strFileName,
                            'image_width' => $arrImageSizeData[0],
                            'image_height' => $arrImageSizeData[1],
                            'file_type' => $arrImageSizeData['mime']
                        );
                        $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "item/default");
//                            $arrData['photo_details'] = $this->photos_model->getOne($intPhotoId);
                    }
                }


                $mixItemsData = $this->items_model->basicGetOne($itemid, $this->session->userdata('objAppUser')->accountid);
                if ($mixItemsData[0]->barcode == trim($this->input->post('item_barcode'))) {
                    $mixNewItemId = $this->items_model->updateOne($itemid, $intPhotoId, '');
                } else {
                    if ($this->applications_model->checkBarcodeUnique(trim($this->input->post('item_barcode')))) {

                        $mixNewItemId = $this->items_model->updateOne($itemid, $intPhotoId, trim($this->input->post('item_barcode')));
                    } else {

                        $success = 0;
                    }
                }

                if ($success) {

                    /* If item is in a category with item quanties enabled, check to see if quantity has changed. If so, log the change */
                    if ($this->input->post('item_quantity') != $mixItemsData[0]->quantity) {
                        if ($this->input->post('item_quantity') > $mixItemsData[0]->quantity) {
                            $message = "Item quantity increased from " . $mixItemsData[0]->quantity . " to " . $this->input->post('item_quantity');
                        } elseif ($this->input->post('item_quantity') < $mixItemsData[0]->quantity) {
                            $message = "Item quantity reduce from " . $mixItemsData[0]->quantity . " to " . ($this->input->post('item_quantity') == '' ? '0' : $this->input->post('item_quantity'));
                        } else {
                            $message = "Item quantity removed";
                        }
                        $this->items_model->insertLogForApp($itemid, $message);
                    }

//  ******************************************************************************************************************************
//  ******************************************************************************************************************************          
                    /* Load custom fields */

                    $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp((int) $this->input->post('category_id'));


                    /* Handle custom field data IF category has NOT changed. Otherwise ignore this! */

                    if ($arrPageData['intCategoryId'] != $this->input->post('category_id')) {
                        $arrPageData['arrCustomFields'] = $this->categories_model->getCustomFieldsForApp($this->input->post('category_id'));
                    }
                    /* Remove previous data */
                    $this->customfields_model->removeContentByItemForApp($itemid);

                    /* Enter new data by going through the POST input and extracting those fields that match the custom fields */


                    foreach ($this->input->post() as $k => $v) {
                        if (is_int($k)) {
                            for ($i = 0; $i < count($arrPageData['arrCustomFields']); $i++) {
                                if ($k == $arrPageData['arrCustomFields'][$i]->id) {
                                    $custom_data[$k] = $v;
                                }
                            }
                        }
                    }



                    if ($custom_data) {
                        $this->customfields_model->insertContentByItemForApp($itemid, $custom_data);
                    }

                    $arrData['Message'] = 'Item Update Successfully';
//  ******************************************************************************************************************************          
                } else {
                    $arrData['Message'] = 'The QRCode already exists';

                    $arrData['booError'] = TRUE;
                    $arrData['strError'] = "The barcode already exists";
                }
            } else {
                $arrData['Message'] = 'Item Couldnot update Successfully';
            }
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    // View Doc Icon 
    public function itemViewDoc($strBarcode) {


        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if ((!$arrOutput['booError']) && ($strBarcode != "")) {
            $this->load->model('items_model');
            $mixItemsData = $this->items_model->getDocByBarcode($strBarcode, $this->session->userdata('objAppUser')->accountid);
            if ($mixItemsData) {
                foreach ($mixItemsData[0] as $strKey => $strValue) {
                    $arrData['arrItem'][$strKey] = $strValue;
                }
                $success = 'YES';
                $arrData['success'] = $success;
            } else {
                $success = 'NO';
                $arrData['success'] = $success;
                $arrOutput['booError'] = true;
                $arrData['strError'] = "Barcode " . $strBarcode . " not found";
            }
        } else {
            $success = 'NO';
            $arrData['success'] = $success;
            $arrOutput['booError'] = true;
            $arrData['strError'] = "No Barcode";
        }

        if (!$arrOutput['booError']) {
            $booSearch = false;
            if ($this->input->post('search') != 'false') {
                $booSearch = true;
            }
            $arrData['booSearch'] = $booSearch;
            $arrOutput['booSearch'] = $booSearch;
            $arrOutput['strHtml'] = $this->load->view('appV2/item', $arrData, true);
            $arrOutput['strHeader'] = $this->load->view('appV2/headers/item', $arrData, true);
        }


        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

    public function itemHistory($intItemId) {
        $this->load->model('tickets_model');
        $this->load->model('users_model');
        $arrData = array();

        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));

        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");
        if (!$arrOutput['booError']) {

            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);
//            $mixItemsData = $this->items_model->basicGetOne($intItemId, 5);
            $arrData['objItem'] = $mixItemsData[0];

            if ($mixItemsData) {
                // load models
                $this->load->model('items_model');
                $this->load->model('tests_model');
                $this->load->model('photos_model');

//                $mixItemsHistoryData = $this->items_model->getHistory($intItemId, $this->session->userdata('objAppUser')->accountid);
                $mixItemsHistoryData = $this->items_model->getHistory($intItemId);
                $mixItemsTicketHistory = $this->tickets_model->ticketHistory($intItemId);
                foreach ($mixItemsTicketHistory as $key => $value) {
                    $mixItemsTicketHistory[$key]['dt'] = date('d/m/Y', strtotime($mixItemsTicketHistory[$key]['date']));
                    $photo_ids = $mixItemsTicketHistory[$key]['photoid'];
                    if (strpos($photo_ids, ',') !== false) {
                        $ids_arr = explode(',', $photo_ids);
                        foreach ($ids_arr as $id_val) {
                            $photo_details = $this->photos_model->getOne($id_val);
                            $mixItemsTicketHistory[$key]['photo_details'][] = $photo_details;
                        }
                    } else {
                        $photo_details = $this->photos_model->getOne($photo_ids);
                        $mixItemsTicketHistory[$key]['photo_details'] = $photo_details;
                    }
                }


                $arrData['arrItemTicketHistory'] = array_slice($mixItemsTicketHistory, 0, 5);

                // condition history

                $arrPageData['assetcondition'] = $this->items_model->checkasset_condition($intItemId, $this->session->userdata('objAppUser')->accountid);
                if ($arrPageData['assetcondition']) {
                    for ($k = 0; $k < count($arrPageData['assetcondition']); $k++) {
                        $photoids = $arrPageData['assetcondition'][$k]['photo_id'];
                        if (strpos($photoids, ',') !== false) {
                            $idsarr = explode(',', $photoids);
                            foreach ($idsarr as $idval) {
                                $photodetails = $this->photos_model->getOne($idval);
                                $arrPageData['assetcondition'][$k]['photodetails'][] = $photodetails;
                            }
                        } else {
                            $photodetails = $this->photos_model->getOne($photoids);
                            $arrPageData['assetcondition'][$k]['photodetails'] = $photodetails;
                        }
                        $arrPageData['history'][$k]['current_date'] = date('d/m/Y', strtotime($arrPageData['assetcondition'][$k]['date']));
                    }
                }


                if (count($arrPageData['assetcondition']) > 1) {
                    for ($i = 0; $i < count($arrPageData['assetcondition']) - 1; $i++) {

                        $arrPageData['history'][0]['enddate'] = 'N/A';
                        $arrPageData['history'][$i + 1]['enddate'] = $arrPageData['assetcondition'][$i]['date'];
                    }
                } else {
                    $arrPageData['history'][0]['enddate'] = 'N/A';
                }
                $arrData['conditionhistory'] = array_replace_recursive($arrPageData['assetcondition'], $arrPageData['history']);


//                $arrPageData['arrItemCompliance'] = $this->tests_model->getComplianceHistory($intItemId);
                $arrPageData['dueTests'] = $this->tests_model->getComplianceHistoryFilteredForApp(NULL, NULL, $intItemId);
                $arrPageData['dueTests'] = array_slice($arrPageData['dueTests'], 0, 5);
                foreach ($arrPageData['dueTests'] as $key => $value) {
                    $arrPageData['dueTests'][$key]['test_date'] = date('d/m/Y', strtotime($arrPageData['dueTests'][$key]['test_date']));
                    $arrPageData['dueTests'][$key]['location_name'] = $this->tests_model->getLocation($value['test_item_id']);
                    $arrPageData['dueTests'][$key]['due_date'] = date('d/m/Y', strtotime($arrPageData['dueTests'][$key]['due_date']));
                    $arrPageData['dueTests'][$key]['owner_name'] = $this->tests_model->getOwnerName($value['test_item_id']);
                    $arrPageData['dueTests'][$key]['site_name'] = $this->tests_model->getSiteName($value['test_item_id']);
                    $arrPageData['dueTests'][$key]['total_tasks'] = $this->tests_model->getTaskCount($value['test_date']);
                    $arrPageData['dueTests'][$key]['tasks'] = $this->tests_model->getTaskCount($value['test_date'], 'details');
                    $arrPageData['dueTests'][$key]['test_type_name'] = $this->tests_model->getComplianceNameforHistoryForApp($value['test_item_id'], $value['test_date']);
                }

                $arrData['arrItemComplianceHistory'] = array_reverse($arrPageData['dueTests']);
                $arrData['arrItemComplianceHistory'] = array_slice($arrData['arrItemComplianceHistory'], 0, 5);
            } else {
                $arrOutput['booError'] = true;
            }
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
    }

    public function logItemCondition($intItemId) {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));

        if (!$arrOutput['booError']) {

            $this->load->model('items_model');
            $mixItemsData = $this->items_model->basicGetOne($intItemId, $this->session->userdata('objAppUser')->accountid);
//            $mixItemsData = $this->items_model->basicGetOne($intItemId, 5);
            $arrData['objItem'] = $mixItemsData[0];

            if ($mixItemsData) {
                if ($this->input->post('mode') == "submit") {

                    //Add Multiple Photo
                    $intPhotoId = -1;
                    if ($this->input->post('photo_present') == "true") {
                        $this->load->helper('file');
                        $count = trim((int) $this->input->post('photo_count'));
                        for ($i = 1; $i <= $count; $i++) {
                            $strFileName = "mobile-app-" . $i . "-" . $this->session->userdata('objAppUser')->userid . "-" . date('Ymd-Hisu') . ".jpg";
                            if (write_file('./uploads/' . $strFileName, base64_decode($this->input->post('item_image_data_' . $i)))) {

                                $this->load->model('photos_model');

                                $arrImageSizeData = getimagesize('./uploads/' . $strFileName);
                                $arrImageData = array(
                                    'file_name' => $strFileName,
                                    'image_width' => $arrImageSizeData[0],
                                    'image_height' => $arrImageSizeData[1],
                                    'file_type' => $arrImageSizeData['mime']
                                );
                                $intPhotoId = $this->photos_model->setOne($arrImageData, "Mobile App Image", "item/default");
                                $arrimage_id[] = $intPhotoId;
                                $arrData['photo_details'][] = $this->photos_model->getOne($intPhotoId);
                            }
                        }
                    }
                    $photo_ids = '';
                    if (is_array($arrimage_id)) {
                        if (!empty($arrimage_id)) {
                            $photo_ids = implode(',', $arrimage_id);
                        }
                    }

                    $condition_data = array(
                        'item_id' => $intItemId,
                        'condition_id' => trim((int) $this->input->post('new_condition_id')),
                        'notes' => trim($this->input->post('job_notes')),
                        'date' => date('Y-m-d H:i:s'),
                        'logged_by' => $this->session->userdata('objAppUser')->userid,
                    );


                    if ($photo_ids != '') {
                        $condition_data['photo_id'] = $photo_ids;
                    }

                    $result = $this->items_model->condition_logForApp($intItemId, $condition_data);
                    if ($result) {
                        $arrData['message'] = 'Condition Logged Successfully';
                        $arrData['booError'] = FALSE;
                    } else {
                        $arrData['message'] = 'Condition Could Not be Logged';
                        $arrData['booError'] = true;
                    }
                }
            } else {
                $arrOutput['booError'] = true;
            }
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
    }

    // change multiitem ownership

    public function multiitemOwnership() {
        $arrData = array();
        $arrOutput = $this->checkSession($this->input->post('username'), $this->input->post('password'));
        //$arrOutput = $this->checkSession("Barry@test.com","2e9fcf8e3df4d415c96bcf288d5ca4ba");


        if ((!$arrOutput['booError'])) {

            $this->load->model('items_model');
            $itemstring = $this->input->post('item_id');

            $intItemId = explode(',', $itemstring);

            for ($i = 0; $i < count($intItemId); $i++) {
                $mixItemsData = $this->items_model->basicGetOne($intItemId[$i], $this->session->userdata('objAppUser')->accountid);
                if ($mixItemsData) {
                    //check posted form mode
                    if ($this->input->post('mode') == "submit") {
                        if (($this->input->post('user_id') > 0) || ($this->input->post('location_id') > 0) || ($this->input->post('site_id') > 0)) {
                            $intUserId = $this->input->post('user_id');
                            if ($intUserId > 0) {
                                $this->items_model->linkThisToUser($intItemId[$i], $intUserId);
                            }

                            $intLocationId = $this->input->post('location_id');
                            if ($intLocationId > 0) {
                                $this->items_model->linkThisToLocation($intItemId[$i], $intLocationId);
                            }

                            $intSiteId = $this->input->post('site_id');
                            if ($intSiteId > 0) {
                                $this->items_model->linkThisToSite($intItemId[$i], $intSiteId);
                            }

                            if ($this->input->post('owner_id') > 0) {
                                $this->items_model->update_owner($intItemId[$i], $this->input->post('owner_id'));
                            }
                            // Log it first
                            //$this->logThisForAppUser("Changed Item Owner/Location/Site on App", "items", $intItemId);
                            $arrOutput['booError'] = false;
                            $success = 'YES';
                            $arrData['success'] = $success;

//                        $arrOutput['strMessage'] = "U" . $intUserId . "L" . $intLocationId . "S" . $intSiteId . "I" . $intItemId[$i];
                        } else {
                            $success = 'NO';
                            $arrData['success'] = $success;
                            $arrOutput['booError'] = true;
                        }
                    } else {
                        $arrData['objItem'] = $mixItemsData[0];
                        $arrData['arrPulldowns'] = $this->getPullDowns();
                        $arrOutput['strHtml'] = $this->load->view('appV2/itemownership', $arrData, true);
                        $arrOutput['strHeader'] = $this->load->view('appV2/headers/itemownership', $arrData, true);
                    }
                } else {
                    $success = 'NO';
                    $arrData['success'] = $success;
                    $arrOutput['booError'] = true;
                    $arrOutput['strError'] = "Item not found";
                }
            }
        } else {
            $success = 'NO';
            $arrData['success'] = $success;
            $arrOutput['booError'] = true;
            $arrOutput['strError'] = "No Item Id";
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($arrData));
//                   ->set_output(json_encode($arrOutput));
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
