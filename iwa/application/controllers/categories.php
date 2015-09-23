<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories extends MY_Controller {

    public function index() {
        $this->viewAll();
    }

    public function depreciate() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/depreciate/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Depreciate";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.depreciate");

        // helpers
        $this->load->helper('form');
        $this->load->library('form_validation');

        if ($booPermission) {
            // models
            $this->load->model('categories_model');
            $arrPageData['arrCategoriesData'] = array('results' => array());

            $mixCategoriesData = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid, true);

            // did we find any?
            if ($mixCategoriesData && (count($mixCategoriesData['results']) > 0)) {
                $arrPageData['arrCategoriesData'] = $mixCategoriesData;
                if ($this->input->post() && ($this->input->post('safety') == "1")) {
                    $intItemCounter = 0;
                    foreach ($mixCategoriesData['results'] as $objCategory) {
                        if ($objCategory->categorydepreciationrate > 0) {
                            $arrItemsToDepreciate = $this->categories_model->getAllItemsForCategoryDepreciation(
                                    $objCategory->categoryid, $this->session->userdata('objSystemUser')->accountid);
                            foreach ($arrItemsToDepreciate as $objItem) {
                                $mixValue = false;
                                if ($objItem->current_value != null) {
                                    $mixValue = $objItem->current_value;
                                } else {
                                    if ($objItem->value != null) {
                                        $mixValue = $objItem->value;
                                    }
                                }
                                //if we have a value...
                                if ($mixValue && ($objCategory->categorydepreciationrate > 0)) {
                                    $floRate = (100 - $objCategory->categorydepreciationrate) / 100;
                                    $mixValue = $mixValue * $floRate;
                                    $this->categories_model->depreciateThis($objItem->itemid, $mixValue);
                                    $this->logThis("Depreciated item", "items", $objItem->itemid);
                                    $intItemCounter++;
                                }
                            }
                        }
                    }


                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array($intItemCounter . ' items successfully depreciated')));
                    redirect('/categories/index/', 'refresh');
                } else {
                    if ($this->input->post() && ($this->input->post('safety') == "0")) {
                        $arrPageData['arrErrorMessages'][] = "You did not confirm the safety check.";
                    }
                }
            } else {
                $arrPageData['arrErrorMessages'][] = "Unable to find any categories.";
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
            $this->load->view('categories/depreciate', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function editOne($intId) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/edit/' . $intId . '/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Edit";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.editOne");
        $booSuccess = false;

        if ($booPermission) {
            // models
            $this->load->model('categories_model');
            $this->load->model('customfields_model');
            $this->load->model('users_model');
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            $arrUsers = $this->users_model->getAllForAccount($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrCategoryData'] = array('results' => array());
            $arrPageData['strName'] = "";
            $arrPageData['strDepreciationRate'] = "0.00";
            $arrPageData['intCategoryId'] = $intId;
            $arrPageData['arrUsers'] = $arrUsers['results'];
            $arrPageData['arrCustomFields'] = $this->customfields_model->getAll();

            $mixCategoriesData = $this->categories_model->getOne($intId, $this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrCategoryCustomFields'] = json_decode($mixCategoriesData['results'][0]->custom_fields);
            // did we find any?
            if ($mixCategoriesData && (count($mixCategoriesData['results']) == 1)) {
//				if($mixCategoriesData['results'][0]->categorydefault == 0) {
                $arrPageData['arrCategoryData'] = $mixCategoriesData;
                $booSuccess = true;
                $arrPageData['strName'] = $mixCategoriesData['results'][0]->categoryname;
                $arrPageData['strDepreciationRate'] = $mixCategoriesData['results'][0]->categorydepreciationrate;
                $arrPageData['intUserID'] = $mixCategoriesData['results'][0]->support_user_id;
                $arrPageData['intQuantityEnabled'] = $mixCategoriesData['results'][0]->quantity_enabled;
                $arrPageData['strSupportEmails'] = $mixCategoriesData['results'][0]->support_emails;

                // is there a submission?
                if ($this->input->post()) {

                    if ($this->input->post('name') != $mixCategoriesData['results'][0]->categoryname) {
                        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_checkCategoryName');
                    }
                    $this->form_validation->set_rules('depreciation_rate', 'Depreciation Value', 'trim|required|callback_checkDepreciationValue');
                    if ($this->form_validation->run()) {

                        $arrCategoryData = array(
                            'name' => $this->input->post('name'),
                            'depreciation_rate' => $this->input->post('depreciation_rate'),
                            'support_emails' => str_replace(' ', '', $this->input->post('support_emails')),
                            'quantity_enabled' => $this->input->post('quantity_enabled')
                        );
                        if ($this->categories_model->editOne($intId, $arrCategoryData)) {
                            // Log it first

                            $this->logThis("Edited category", "categories", $intId);
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The category was successfully updated')));

                            /* Then handle custom field updates */
                            $this->categories_model->setCustomFields($intId, $this->input->post('customfields'));
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The category was successfully updated')));
                            redirect('/categories/index/', 'refresh');
                        } else {
                            $arrPageData['arrErrorMessages'][] = "Unable to edit the category.";
                        }
                    }
                    $arrPageData['strName'] = $this->input->post('name');
                }
//                } else {
//                    $arrPageData['arrErrorMessages'][] = "Unable to find the category.";
//                    $arrPageData['strPageTitle'] = "System Error";
//                    $arrPageData['strPageText'] = "You cannot alter default categories.";
//                }
            } else {
                $arrPageData['arrErrorMessages'][] = "Unable to find the category.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "The category search was not valid.";
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
            $this->load->view('categories/edit', $arrPageData);
            $this->load->view('categories/forms/add', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function viewAll() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/viewall/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View All";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.viewAll");

        if ($booPermission) {
            // models
            $this->load->model('categories_model');
            $arrPageData['arrCategoriesData'] = array('results' => array());

            $mixCategoriesData = $this->categories_model->getAll($this->session->userdata('objSystemUser')->accountid, false);

            // did we find any?
            if ($mixCategoriesData && (count($mixCategoriesData['results']) > 0)) {
                $arrPageData['arrCategoriesData'] = $mixCategoriesData;
            } else {
                $arrPageData['arrErrorMessages'][] = "Unable to find any categories.";
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
            $this->load->view('categories/viewall', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function addOne() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/addone/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Add a Category";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.addOne");
        $arrUsers = $this->users_model->getAllForAccount($this->session->userdata('objSystemUser')->accountid);

        if ($booPermission) {
            // models
            $this->load->model('categories_model');
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            $arrPageData['strName'] = "";
            $arrPageData['strDepreciationRate'] = "0.00";
            $arrPageData['arrUsers'] = $arrUsers['results'];

            // is there a submission?
            if ($this->input->post()) {
                $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_checkCategoryName');
                $this->form_validation->set_rules('depreciation_rate', 'Depreciation Value', 'trim|required|callback_checkDepreciationValue');
                if ($this->form_validation->run()) {
                    $arrCategoryData = array(
                        'name' => $this->input->post('name'),
                        'account_id' => $this->session->userdata('objSystemUser')->accountid,
                        'depreciation_rate' => $this->input->post('depreciation_rate'),
                        'support_emails' => str_replace(' ', '', $this->input->post('support_emails')),
                        'quantity_enabled' => $this->input->post('quantity_enabled')
                    );

                    $mixSuccess = $this->categories_model->addOneAndReturnId($arrCategoryData);

                    if ($mixSuccess) {
                        // Log it first

                        $this->logThis("Added category", "categories", $mixSuccess);

                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The category was successfully added')));
                        redirect('/categories/index/', 'refresh');
                    } else {
                        $arrPageData['arrErrorMessages'][] = "Unable to add the category.";
                    }
                }
                $arrPageData['strName'] = $this->input->post('name');
                $arrPageData['strDepreciationRate'] = $this->input->post('depreciation_rate');
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }


        $this->load->view('common/header', $arrPageData);
        if ($booPermission) {
            //load the correct view
            $this->load->view('categories/add', $arrPageData);
            $this->load->view('categories/forms/add', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function deleteOne($intCategoryId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/deleteone/' . $intCategoryId . '/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Delete a Category";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.deleteOne");
        $booNoErrors = true;
        $arrPageData['intCategoryId'] = $intCategoryId;

        if ($booPermission) {
            // models
            $this->load->model('categories_model');
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            $mixCategoriesData = $this->categories_model->getOne($intCategoryId, $this->session->userdata('objSystemUser')->accountid);

            // did we find any?
            if ($mixCategoriesData && (count($mixCategoriesData['results']) == 1)) {
                // Check if updated
                if ($mixCategoriesData['results'][0]->categorydefault == 0) {
                    if ($this->input->post() && ($this->input->post('safety') == "1")) {
                        if ($this->categories_model->deleteOne($intCategoryId)) {
                            // Yes	
                            // Log it first

                            $this->logThis("Deactivated category", "categories", $intCategoryId);
                            // We need to set some user messages before redirect
                            $this->session->set_userdata('booCourier', true);
                            $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Category Deleted')));
                            redirect('categories/viewall/', 'refresh');
                        } else {
                            $arrPageData['arrErrorMessages'][] = "Category could not be deleted";
                            $arrPageData['strPageTitle'] = "Oooops!";
                            $arrPageData['strPageText'] = "You cannot delete this category, perhaps it has active items linked to it?";
                            $booNoErrors = false;
                        }
                    }
                } else {
                    $arrPageData['strPageTitle'] = "Oooops!";
                    $arrPageData['strPageText'] = "You cannot delete default categories";
                    $booNoErrors = false;
                }
                $arrPageData['strName'] = $mixCategoriesData['results'][0]->categoryname;
            } else {
                $arrPageData['arrErrorMessages'][] = "Category could not be found.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve category information.";
                $booNoErrors = false;
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booNoErrors) {
            //load the correct view
            $this->load->view('categories/delete', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function reactivateOne($intCategoryId = -1) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/categories/reactivateone/' . $intCategoryId . '/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Reactivate a Category";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Categories.reactivateOne");
        $booNoErrors = true;
        $arrPageData['intCategoryId'] = $intCategoryId;

        if ($booPermission) {
            // models
            $this->load->model('categories_model');
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            $mixCategoriesData = $this->categories_model->getOne($intCategoryId, $this->session->userdata('objSystemUser')->accountid);

            // did we find any?
            if ($mixCategoriesData && (count($mixCategoriesData['results']) == 1)) {
                // Check if updated
                if ($this->input->post() && ($this->input->post('safety') == "1")) {
                    if ($this->categories_model->reactivateOne($intCategoryId)) {
                        // Yes	
                        // Log it first

                        $this->logThis("Reactivated category", "categories", $intCategoryId);
                        // We need to set some user messages before redirect
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Category Reactivated')));
                        redirect('categories/viewall/', 'refresh');
                    } else {
                        $arrPageData['arrErrorMessages'][] = "Category could not be reactivated";
                        $arrPageData['strPageTitle'] = "System Error";
                        $arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to reactivate the category.";
                        $booNoErrors = false;
                    }
                }

                $arrPageData['strName'] = $mixCategoriesData['results'][0]->categoryname;
            } else {
                $arrPageData['arrErrorMessages'][] = "Category could not be found.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "There was an error, the system encountered an error while trying to retrieve category information.";
                $booNoErrors = false;
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        $this->load->view('common/header', $arrPageData);
        if ($booPermission && $booNoErrors) {
            //load the correct view
            $this->load->view('categories/reactivate', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        } else {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', $arrPageData);
    }

    public function checkCategoryName($strName) {

        // models
        $this->load->model('categories_model');
        if ($this->categories_model->doCheckCategoryNameIsUniqueOnAccount($strName, $this->session->userdata('objSystemUser')->accountid)) {
            return true;
        } else {
            $this->form_validation->set_message('checkCategoryName', 'There is already a category called that.');
            return false;
        }
    }

    public function checkDepreciationValue($strDepreciationValue) {
        if (ereg('^[0-9]{1,2}\.[0-9]{1,2}$', $strDepreciationValue)) {
            return true;
        } else {
            $this->form_validation->set_message('checkDepreciationValue', 'The depreciation value is not formatted correctly (xx.xx).');
            return false;
        }
    }

    public function checkCategory($cat_id) {
        $this->load->model('categories_model');
        $category = $this->categories_model->getOne($cat_id, $this->session->userdata('objSystemUser')->accountid);
        print json_encode(array('quantity' => $category['results'][0]->quantity_enabled));
    }

    public function getCustomFields($cat_id) {
        $this->load->model('categories_model');
        $customfields = $this->categories_model->getCustomFields($cat_id);
        if ($customfields) {
            print json_encode($customfields);
        } else {
            print '';
        }
    }

    public function getCustomFieldContent($cat_id, $custom_id, $item_id) {
        $this->load->model('categories_model');
        $custom = $this->categories_model->getCustomField_content($cat_id, $custom_id, $item_id);
        if ($custom) {
            print json_encode($custom);
        } else {
            print '';
        }
    }

}