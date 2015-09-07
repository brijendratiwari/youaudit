<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fleet extends MY_Controller 
{

    public function index() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }
        $this->load->model('users_model');
        $this->load->model('accounts_model');
        
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Fleet.index");
        
        $this->load->model('fleet_model');
        // housekeeping
        $arrPageData = array();
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();


        if ($booPermission)
        {
            /* Load Vehicles and stats */
            $arrPageData['fleetList'] = $this->fleet_model->getFleetAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['fleet_no'] = $this->fleet_model->getNumVechicles($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['fleetTotalValue'] = $this->fleet_model->getTotalValue($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['fleetDueMot'] = $this->fleet_model->getDueMot($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['fleetDueTax'] = $this->fleet_model->getDueTax($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['fleetDueService'] = $this->fleet_model->getDueService($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['fleetDueInsurance'] = $this->fleet_model->getDueInsurance($this->session->userdata('objSystemUser')->accountid);


        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        // load views
        
        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/index', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }

    }
    
    public function view($fleet_id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }
        $this->load->model('users_model');
        $this->load->model('accounts_model');
        $this->load->model('tickets_model');

        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Fleet.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        if($booPermission) {
            $this->load->model('fleet_model');
            $arrPageData['vehicle'] = $this->fleet_model->getVehicle($fleet_id);
            $arrPageData['arrItemHistory'] = $this->fleet_model->getHistory($fleet_id);
            $arrPageData['arrTicketHistory'] = $this->tickets_model->ticketFleetHistory($fleet_id);
            $arrPageData['arrCheckHistory'] = $this->fleet_model->getCheckHistory($fleet_id);
            $arrPageData['vehicle_mot'] = $this->fleet_model->getMotHistory($fleet_id);
            $arrPageData['vehicle_service'] = $this->fleet_model->getServiceHistory($fleet_id);
            $arrPageData['vehicle_tax'] = $this->fleet_model->getTaxHistory($fleet_id);

            if (($arrPageData['vehicle']['mark_deleted'] != 0) || ($arrPageData['vehicle']['mark_deleted_2'] != 0))
            {
                $arrPageData['arrErrorMessages'][] = "This vehicle is marked as removed from the system, awaiting confirmation.";
            }
            
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";            
        }

        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/view', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }
    
    public function edit($fleet_id) {
        $this->load->model('locations_model');
        $this->load->model('sites_model');
        $this->load->model('users_model');
        $this->load->model('accounts_model');
        $this->load->helper('form');
	    $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }
        
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        
        if($booPermission) {
            $this->load->model('fleet_model');
            
            $arrPageData['vehicle'] = $this->fleet_model->getVehicle($fleet_id);
            $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrUsers'] = $this->users_model->getAll($this->session->userdata('objSystemUser')->accountid);
            $arrPageData['arrChecks'] = $this->fleet_model->getChecks();
            $arrPageData['arrVehicleChecks'] = $this->fleet_model->getChecksByVehicle($fleet_id);

            $vehicle_location = $this->locations_model->getVehicleLocation($arrPageData['vehicle']['reg_no']);
            $arrPageData['vehicle_qr'] = $vehicle_location['barcode'];

            if ($this->input->post()) {

                $this->form_validation->set_rules('make', 'Make', 'required');
                $this->form_validation->set_rules('model', 'Model', 'required');

                if($this->input->post('is_location') == TRUE) {

                }              
                if ($this->form_validation->run()) {
                    $checks = '';
                    $rows = count($this->input->post('checks'));
                    $count = 0;
                    foreach($this->input->post('checks') as $value) {
                        $count++;

                        $checks .= $value;
                        if($count == $rows) {
                            break;
                        } else {
                            $checks .= ',';
                        }

                    }
                    //print $checks;
                    //die();
                    $arrVehicleData = array(
                        'fleet_id' => $this->input->post('fleet_id'),
                        'make' => $this->input->post('make'),
                        'model' => $this->input->post('model'),
						'barcode' => $this->input->post('vehicle_barcode'),
                        'year' => $this->input->post('year'),
                        'engine_size' => $this->input->post('engine_size'),
                        'reg_no' => $this->input->post('reg_no'),
                        'vehicle_value' => $this->input->post('vehicle_value'),
                        'purchase_date' => $this->input->post('purchase_date'),
                        'insurance_expiration' => $this->input->post('insurance_expiration'),
                        'warranty_expiration' => $this->input->post('warranty_expiration'),
                        'tax_expiration' => $this->input->post('tax_expiration'),
                        'user_id' => $this->input->post('user_id'),
                        'site_id' => $this->input->post('site_id'),
                        'location_id' => $this->input->post('location_id'),
                        'notes' => $this->input->post('notes'),
                        'checks' => $checks
			        );


                    /* Check for vehicle being made location */
                    if($this->input->post('is_location') == TRUE) {
                        
                        
                        /* Remove location id from update array and add is_location flag */
                        unset($arrVehicleData['location_id']);
                        $arrVehicleData['is_location'] = 1;
                        if($this->check_qrExists($this->input->post('barcode'))) {
                            /* Add location */
                            $arrLocation = array(
                                'name' => $this->input->post('make') . " " . $this->input->post('model') . " - " . $this->input->post('reg_no'),
                                'account_id' => $this->session->userdata('objSystemUser')->accountid,
                                'barcode' => $this->input->post('barcode')
                                );
                            $this->locations_model->addOne($arrLocation);
                        }
                    }
                    $this->fleet_model->updateVehicle($this->input->post('fleet_id'), $arrVehicleData);
                    
                    
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The item was successfully updated')));

                    redirect('/fleet/view/'.$this->input->post('fleet_id'), 'refresh');
                }
                
            }

        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";              
        }
        
        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/edit', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }

    public function newcheck() {
        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/reports/');
            redirect('users/login/', 'refresh');
        }

        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if($booPermission) {
            $this->load->model('fleet_model');
            if ($this->input->post()) {
                $this->form_validation->set_rules('check_name', 'Check name', 'required');
                if ($this->form_validation->run()) {
                    $this->fleet_model->newCheck($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The new vehicle check was succesfully recorded')));
                    redirect('/fleet/checks/', 'refresh');
                }

            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/newcheck', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }

    public function newmot($fleet_id) {
        $this->load->model('users_model');
        $this->load->helper('form');
	    $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }
        
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();   
        $arrPageData['arrFleetId'] = $fleet_id;
        if($booPermission) {
            $this->load->model('fleet_model');
            if ($this->input->post()) {
                $this->form_validation->set_rules('mot_cert_no', 'MOT Cert No', 'required');
                $this->form_validation->set_rules('mot_date', 'MOT Date', 'required');               
                $this->form_validation->set_rules('mot_expiry_date', 'MOT Expiry Date', 'required');  
                if ($this->form_validation->run()) {
                    $this->fleet_model->newMot($this->input->post());                               
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The MOT record was succesfully recorded')));
                    redirect('/fleet/view/'.$this->input->post('vehicle_id'), 'refresh');
                }
                
            }           
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";              
        }
        
        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/newmot', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }
    
    public function newservice($fleet_id) {
        $this->load->model('users_model');
        $this->load->helper('form');
	    $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }
        
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();   
        $arrPageData['arrFleetId'] = $fleet_id;
        if($booPermission) {
            $this->load->model('fleet_model');
            if ($this->input->post()) {
                $this->form_validation->set_rules('service_date', 'Service Date', 'required');               
                $this->form_validation->set_rules('service_expiry_date', 'Service Date', 'required');  
                if ($this->form_validation->run()) {
                    $this->fleet_model->newService($this->input->post());                               
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The MOT record was succesfully recorded')));
                    redirect('/fleet/view/'.$this->input->post('vehicle_id'), 'refresh');
                }
                
            }           
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";              
        }
        
        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/newservice', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }    
    }
    
    public function newtax($fleet_id) {
        $this->load->model('users_model');
        $this->load->helper('form');
	    $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }
        
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();   
        $arrPageData['arrFleetId'] = $fleet_id;
        if($booPermission) {
            $this->load->model('fleet_model');
            if ($this->input->post()) {
                $this->form_validation->set_rules('tax_disc_no', 'Tax Disc No', 'required');
                $this->form_validation->set_rules('tax_date', 'Tax Date', 'required');               
                
                if ($this->form_validation->run()) {
                    $this->fleet_model->newTax($this->input->post());                               
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The Tax record was succesfully recorded')));
                    redirect('/fleet/view/'.$this->input->post('vehicle_id'), 'refresh');
                }
                
            }           
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";              
        }
        
        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/newtax', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }
    
    public function addvehicle() {
        $this->load->model('locations_model');
        $this->load->model('sites_model');
        $this->load->model('users_model');
        $this->load->model('accounts_model');
        $this->load->helper('form');
	    $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }
        $this->load->model('users_model');
        
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");
        $this->load->model('fleet_model');
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrPosted'] = $this->input->post();
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $arrPageData['arrChecks'] = $this->fleet_model->getChecks();
        
        $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['arrSites'] = $this->sites_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['arrUsers'] = $this->users_model->getAll($this->session->userdata('objSystemUser')->accountid);


        if ($booPermission)
        {
            if ($this->input->post()) {
                $this->form_validation->set_rules('make', 'Make', 'required');
                $this->form_validation->set_rules('model', 'Model', 'required');
                $this->form_validation->set_rules('reg_no', 'Reg No', 'required|callback_check_reg');
                $this->form_validation->set_rules('vehicle_barcode', 'Barcode', 'callback_check_BarcodeExists');
                
                if($this->input->post('is_location') == TRUE) {
                    $this->form_validation->set_rules('barcode', 'QR Code', 'required|callback_check_qrExists');
                }
                
                if ($this->form_validation->run()) {
                    
                    /* Check for vehicle being made location */
                    if($this->input->post('is_location') == TRUE) {

                        /* Add location */
                        $arrLocation = array(
                            'name' => $this->input->post('make') . " " . $this->input->post('model') . " - " . $this->input->post('reg_no'),
                            'account_id' => $this->session->userdata('objSystemUser')->accountid,
                            'barcode' => $this->input->post('barcode')
                            );
						
						if(!$this->locations_model->addOne($arrLocation)) {
							die('Failed to add location');
						}
                    }
                    
                    $vehicle_id = $this->fleet_model->addVehicle($this->input->post());
                    
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The item was successfully updated')));
                    redirect('/fleet/view/'.$vehicle_id, 'refresh');               
                } else {
                    $arrPageData['arrErrorMessages'][] = "Please check the form for errors.";
                }
                
            }
            
        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        // load views
        
        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/addvehicle', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }       
    }

    public function editCheck() {
        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/reports/');
            redirect('users/login/', 'refresh');
        }

        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if($booPermission) {
            $this->load->model('fleet_model');
            /* Get all vehicle checks */
//            $arrPageData['objCheck'] = $this->fleet_model->getCheck($check_id);

            if ($this->input->post()) {
                $this->form_validation->set_rules('edit_check_name', 'Check name', 'required');
                if ($this->form_validation->run()) {
                    $this->fleet_model->editCheck($this->input->post());
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The vehicle check was succesfully modified')));
                    redirect('/fleet/checks/', 'refresh');
                }

            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/editcheck', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }

    }

     public function deleteCheck($check_id) {
        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser')) {
            $this->session->set_userdata('strReferral', '/reports/');
            redirect('users/login/', 'refresh');
        }

        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if ($booPermission) {
            $this->load->model('fleet_model');
            /* Get all vehicle checks */
//            $arrPageData['objCheck'] = $this->fleet_model->getCheck($check_id);

            if ($check_id) {
                $res = $this->fleet_model->editCheckStatus($check_id, 0);
                if ($res) {
                           $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The vehicle check delete succesfully ')));
                    redirect('/fleet/checks/', 'refresh');
                }
            }
        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }
    }

    public function checks() {

        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/reports/');
            redirect('users/login/', 'refresh');
        }

        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if($booPermission) {
            $this->load->model('fleet_model');
            /* Get all vehicle checks */
            $arrPageData['arrChecks'] = $this->fleet_model->getChecks();

        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/checks', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }
    
    public function savevehicleorder() {
        
        $vehicle_order = json_decode($_POST['vehiclecheckid']);
        $this->load->model('fleet_model');
        $this->fleet_model->updateOrderChecks($vehicle_order);
     
        die;
    }
    
    public function markdeleted($intFleetId) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/fleet/markdeleted/'.$intFleetId."/");
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection']     = get_class();
        $arrPageData['arrPageParameters']['strPage']        = "Mark Deleted";
        $arrPageData['arrSessionData']                      = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages']                    = array();
        $arrPageData['arrUserMessages']                     = array();

        // load models
        $this->load->model('users_model');
        $this->load->model('locations_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Fleet.markDeleted");
        $booSuccess = false;
        if ($booPermission)
        {
            $arrPageData['intFleetId'] = $intFleetId;
            $this->load->model('fleet_model');
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            $mixItemResult = $this->fleet_model->getVehicle($intFleetId);

            if ($mixItemResult)
            {
                $booSuccess                         = true;
                $arrPageData['objItem']             = $mixItemResult;
                $arrPageData['booSuperAdmin']       = false;
                if ($this->session->userdata('objSystemUser')->levelid == 4)
                {
                    $arrPageData['booSuperAdmin']       = true;
                }

                /* Check if location has items */
                $location = $this->locations_model->getVehicleLocation($arrPageData['objItem']['reg_no']);

                if(!$this->locations_model->doCheckLocationHasNoActiveItems($location['id']) && $location) {

                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrErrorMessages' => array('This vehicle contains items. Please re-assign items to other locations before removing this vehicle.')));
                    redirect('fleet');
                    $booSuccess = false;
                }



                if ($this->input->post())
                {
                    
                    if ($this->input->post('safety') > 0)
                    {
                        $this->fleet_model->markDeleted(
                                        $intFleetId, 
                                        $this->session->userdata('objSystemUser')->accountid,
                                        $this->session->userdata('objSystemUser')->userid, 
                                        $arrPageData['booSuperAdmin']);
                        // Log it first
                        
                        $this->logThis("Vehicle Marked Deleted", "fleet", $intFleetId);
                        //die('test');
                        $this->session->set_userdata('booCourier', true);
                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Item marked deleted.')));
                        redirect('/fleet/', 'refresh');
                    }
                }

            }
            else
            {
                $arrPageData['arrErrorMessages'][]  = "System Error - Item Not Found";
                $arrPageData['strPageTitle']        = "Item Not Found";
                $arrPageData['strPageText']         = "The Item was not found in the database.";
            }

        }
        else	
        {
            $arrPageData['arrErrorMessages'][]      = "You do not have permission to do this.";
            $arrPageData['strPageTitle']            = "Security Check Point";
            $arrPageData['strPageText']             = "Your current user permissions do not allow this action on your account.";
        }

        // load views
        $this->load->view('common/header', 	$arrPageData);
        if ($booPermission && $booSuccess)
        {
            //load the correct view
            $this->load->view('fleet/delete', $arrPageData);
            $this->load->view('common/forms/safetycheck', $arrPageData);
        }
        else
        {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', 	$arrPageData);        
    }
    
    public function confirmDeleted() {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/fleet/confirmdeleted/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection']     = get_class();
        $arrPageData['arrPageParameters']['strPage']        = "Confirm Deleted";
        $arrPageData['arrSessionData']                      = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages']                    = array();
        $arrPageData['arrUserMessages']                     = array();

        // load models
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Items.markDeleted");
        $booSuccess = false;
        if ($booPermission)
        {

            $this->load->model('fleet_model');

            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');

            $mixItemsResult = $this->fleet_model->getAwaitingDeletion(

                                                                    $this->session->userdata('objSystemUser')->accountid,
                                                                    $this->session->userdata('objSystemUser')->userid,
                                                                    $this->session->userdata('objSystemUser')->levelid
                                                                    );


            if ($mixItemsResult)
            {
                // var_dump($mixItemsResult);
                // die();
                $booSuccess = true;
                $arrPageData['arrItemsAwaitingDeletion'] = $mixItemsResult['results'];

                if ($this->input->post())
                {
                    foreach($this->input->post('confirmed_deletions') as $intFleetDeleted)
                    {

                        $this->fleet_model->confirmDeletion((int)$intFleetDeleted,
                                                            $this->session->userdata('objSystemUser')->accountid,
                                                            $this->session->userdata('objSystemUser')->userid,
                                                            $this->session->userdata('objSystemUser')->levelid
                                                            );
                        $this->logThis("Fleet Confirmed Deleted", "fleet", (int)$intFleetDeleted);

                    }
                    $this->session->set_userdata('booCourier', true);
                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('Vehicle Confirmed Deleted.')));
                    redirect('/fleet/', 'refresh');

                }


            }
            else
            {
                $arrPageData['arrUserMessages'][]   = "There are no items awaiting deletion.";
                $arrPageData['strPageTitle']        = "Items Not Found";
                $arrPageData['strPageText']         = "There are no items awaiting deletion.";
            }

        }
        else
        {
            $arrPageData['arrErrorMessages'][]      = "You do not have permission to do this.";
            $arrPageData['strPageTitle']            = "Security Check Point";
            $arrPageData['strPageText']             = "Your current user permissions do not allow this action on your account.";
        }

        // load views
        $this->load->view('common/header', 	$arrPageData);
        if ($booPermission && $booSuccess)
        {
            //load the correct view
            $this->load->view('fleet/confirm_deleted', $arrPageData);
        }
        else
        {
            $this->load->view('common/system_message', $arrPageData);
        }
        $this->load->view('common/footer', 	$arrPageData);

    }

    public function depreciate() {
		if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
		{
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
		$booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Fleet.index");
		
                // helpers
			$this->load->helper('form');
			$this->load->library('form_validation');
                
                if ($booPermission)
		{
                    // models
			$this->load->model('fleet_model');
			$arrPageData['arrCategoriesData'] = array('results' => array());
			
			$mixFleetData = $this->fleet_model->getFleetAll($this->session->userdata('objSystemUser')->accountid);
	
			// did we find any?
			if ($mixFleetData && (count($mixFleetData) > 0))
			{
				$arrPageData['arrFleetData'] = $mixFleetData;
                                if ($this->input->post() && ($this->input->post('safety') == "1"))
                                {
                                    $intItemCounter = 0;
                                    foreach ($mixFleetData as $arrVehicle)
                                    {
                                        if ($this->input->post('rate') > 0)
                                        {
                                            $mixValue = false;
                                            if ($arrVehicle['current_value'] != null)
                                                {
                                                    $mixValue = $arrVehicle['current_value'];
                                                }
                                                else
                                                {
                                                    if ($arrVehicle['vehicle_value'] != null)
                                                    {
                                                        $mixValue = $arrVehicle['vehicle_value'];
                                                    }
                                                }
                                                //if we have a value...
                                                if ($mixValue && ($this->input->post('rate') > 0))
                                                {
                                                    $floRate = (100-$this->input->post('rate'))/100;
                                                    $mixValue = $mixValue * $floRate;
                                                    $this->fleet_model->depreciateThis($arrVehicle['fleet_id'], $mixValue);
                                                    $this->logThis("Depreciated item", "fleet", $arrVehicle['fleet_id']);
                                                    $intItemCounter++;
                                                }
                                            
                                        }
                                    }
                                    
                                     
                                    $this->session->set_userdata('booCourier', true);
                                    $this->session->set_userdata('arrCourier', array('arrUserMessages' => array($intItemCounter.' items successfully depreciated')));
                                    redirect('/fleet/index/', 'refresh');
                                }
                                else
                                {
                                    if ($this->input->post() && ($this->input->post('safety') == "0"))
                                    {
                                        $arrPageData['arrErrorMessages'][] = "You did not confirm the safety check.";
                                    }
                                }
                                
                                
                                
                        }
			else
			{
				$arrPageData['arrErrorMessages'][] = "Unable to find any categories.";
			}
                        
                        
                    
                }
                else
		{
			$arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
			$arrPageData['strPageTitle'] = "Security Check Point";
			$arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
		}
                // load views
		$this->load->view('common/header', 	$arrPageData);
		if ($booPermission )
		{
			//load the correct view
			$this->load->view('fleet/depreciate', $arrPageData);
			$this->load->view('common/forms/safetycheck', $arrPageData);
		}
		else
		{
			$this->load->view('common/system_message', $arrPageData);
		}
		$this->load->view('common/footer', 	$arrPageData);
    }
        
    public function raiseTicket($intId = -1) {

        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/items/raiseticket/'.$intId.'/');
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
                $this->load->model('fleet_model');
        $this->load->model('tickets_model');
        $this->load->model('users_model');
                $this->load->model('accounts_model');

                // helpers
                $this->load->helper('form');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        $booSuccess = false;

                $arrPageData['strMessageTitle']         = "";
                $arrPageData['strMessageBody'] 		= "";

                if ($intId > 0)
                {
                        $vehicleData = $this->fleet_model->getVehicle($intId);

                if ($vehicleData)
                {
                    $arrPageData['objItem'] = $vehicleData;
                    $booSuccess = true;


                    $arrPageData['strMake'] 		= $vehicleData['make'];
                    $arrPageData['strModel'] 		= $vehicleData['model'];

                    $arrPageData['intItemId'] 		= $intId;

                    // is there a submission?
                    if ($this->input->post())
                    {

                                                $strZenDeskDataCapture = "";
                                                $strZenDeskDataCapture .= "#requester ".$this->session->userdata('objSystemUser')->username." \r\n";
                                                $strZenDeskDataCapture .= "#tags iworkaudit ".$vehicleData['reg_no']." \r\n";
                                                $strZenDeskDataCapture .= "#problem \r\n";

                                                $strZenDeskDataCapture .= " -----------------------------------------------------\r\n";



                                                $strMessageBodyItemData = "\r\n -----------------------------------------------------\r\n";
                                                $strMessageBodyItemData .= "ACCOUNT NAME: ".$this->session->userdata('objSystemUser')->accountname."\r\n";
                                                $strMessageBodyItemData .= "SENDER: ".$this->session->userdata('objSystemUser')->firstname." ".$this->session->userdata('objSystemUser')->lastname."\r\n";

                                                $strMessageBodyItemData .= "MAKE & MODEL: ".$vehicleData['make']." ".$vehicleData['model']."\r\n";
                                                $strMessageBodyItemData .= "REG NO: ".$vehicleData['reg_no']."\r\n";


                        //okay try to build the email
                                                $this->load->library('email');
                                                $this->email->from("tickets@iworkaudit.com", "iWork Audit Ticket");
                                                $strSupportAddress = $this->accounts_model->getSupportEmailAddress($this->session->userdata('objSystemUser')->accountid);
                                                $this->email->to($strSupportAddress);
                                                $this->email->bcc('matt@bespokeinternet.com');
                                                $this->email->subject($vehicleData['make']." ".$vehicleData['model'].":".$this->input->post('message_title'));

                                                $strEmailContent = "";

                                                if (strpos($strSupportAddress,'zendesk.com'))
                                                {
                                                    $strEmailContent = $strZenDeskDataCapture;
                                                }

                                                $strEmailContent .= $this->input->post('message_body').$strMessageBodyItemData;

                                                $this->email->message($strEmailContent);

                                                if ($this->email->send())
                                                {
                                                        $this->tickets_model->ticketSubmissionFleet($intId, $this->session->userdata('objSystemUser')->userid, $this->input->post('message_body'));
                                                        $this->session->set_userdata('booCourier', true);
                                                        $this->session->set_userdata('arrCourier', array('arrUserMessages' => array('The ticket was successfully sent')));
                                                        redirect('/fleet/view/'.$intId, 'refresh');
                                                }
                                                else
                                                {
                                                        $arrPageData['arrErrorMessages'][] = "Unable to send ticket.";
                                                }



                                                $arrPageData['strMessageTitle']         = $this->input->post('message_title');
                        $arrPageData['strMessageBody'] 		= $this->input->post('message_body');

                    }
                }
                //if mixitemsdata
            }
            //if intItems


            if (!$booSuccess)
            {
                $arrPageData['arrErrorMessages'][] = "Item Not Found.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We couldn't find that item.";
            }

            // load views
            $this->load->view('common/header', 	$arrPageData);
            if ($booSuccess)
            {
                //load the correct view
                $this->load->view('fleet/ticket', $arrPageData);
            }
            else
            {
                $this->load->view('common/system_message', $arrPageData);
            }
            $this->load->view('common/footer', 	$arrPageData);
            }

            public function check_qrExists($arg) {
                $this->load->model('locations_model');
                $account_id = $this->session->userdata('objSystemUser')->accountid;
                $result = $this->locations_model->getOneByBarcode($arg, $account_id);

                if($result) {
                    $this->form_validation->set_message('check_qrExists', 'The QR code entered already exists, please use new QR code');
                    return FALSE;
                } else {
                    return TRUE;
                }
            }

    public function check_BarcodeExists($arg) {
        $this->load->model('locations_model');

        if($this->fleet_model->check_qrExists($arg) && $arg != '') {
            $this->form_validation->set_message('check_BarcodeExists', 'The QR code entered already exists, please use new QR code');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_reg($arg) {
        $this->load->model('fleet_model');
        if(!$this->fleet_model->checkReg($arg)) {
            $this->form_validation->set_message('check_reg', 'The Registration Number entered already exists, please check.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function reports() {

        $this->load->model('users_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/reports/');
            redirect('users/login/', 'refresh');
        }

        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        if($booPermission) {

        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/reports', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }

    public function generateReports()
    {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/reports/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Results";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();


        $this->load->model('users_model');
        $this->load->model('accounts_model');
        $this->load->model('fleet_model');
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");
        $booSuccess = false;
        if ($booPermission)
        {
            if ($this->input->post())
            {
                if ($this->input->post('report_type'))
                {
                    $this->load->model('reports_model');
                    $arrResults = array();

                    switch ($this->input->post('report_type'))
                    {
                        case 'all':
                            if (($this->input->post('report_startdate') != '')
                                && $this->input->post('report_enddate') != '')
                            {
                                $mixStartDate = $this->doFormatDate($this->input->post('report_startdate'));
                                $mixEndDate = $this->doFormatDate($this->input->post('report_enddate'));
                            }
                            else
                            {
                                $mixStartDate = false;
                                $mixEndDate = false;
                            }
                            $arrResults = $this->fleet_model->getAllReport(
                                $mixStartDate
                                , $mixEndDate
                                , $this->session->userdata('objSystemUser')->accountid
                            );
                            $arrFields = array(
                              array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                            , array('strName' => 'Reg No','strFieldReference' => 'reg_no')
                            , array('strName' => 'Make and Model','strFieldReference' => 'makemodel')
                            , array('strName' => 'Check Date','strFieldReference' => 'date_time')
                            , array('strName' => 'Check Name','strFieldReference' => 'check_name')
                            , array('strName' => 'Result','strFieldReference' => 'result')
                            , array('strName' => 'User','strFieldReference' => 'user_name')
                            );
                            $strPdfReference = "all/";
                            $strReportName = "All Checks";
                            if ($mixStartDate && $mixEndDate)
                            {
                                $strReportName .= " between ".$this->input->post('report_startdate')." and ".$this->input->post('report_enddate');
                                $strPdfReference = "PATFailures/".$this->doFormatDate($this->input->post('report_startdate'))."/".$this->doFormatDate($this->input->post('report_enddate'))."/";
                            }
                            //.$this->input->post('report_startdate')." and ".$this->input->post('report_enddate');
                            break;
                        case 'failed':

                            if (($this->input->post('report_startdate') != '')
                                && $this->input->post('report_enddate') != '')
                            {
                                $mixStartDate = $this->doFormatDate($this->input->post('report_startdate'));
                                $mixEndDate = $this->doFormatDate($this->input->post('report_enddate'));
                            }
                            else
                            {
                                $mixStartDate = false;
                                $mixEndDate = false;
                            }
                            $arrResults = $this->fleet_model->getFailedReport($mixStartDate, $mixEndDate, $this->session->userdata('objSystemUser')->accountid);


                            $arrFields = array(
                              array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                            , array('strName' => 'Reg No','strFieldReference' => 'reg_no')
                            , array('strName' => 'Make and Model','strFieldReference' => 'makemodel')
                            , array('strName' => 'Check Date','strFieldReference' => 'date_time')
                            , array('strName' => 'Check Name','strFieldReference' => 'check_name')
                            , array('strName' => 'Check Note','strFieldReference' => 'check_note')
                            , array('strName' => 'User','strFieldReference' => 'user_name')
                            );
                            $strPdfReference = "failed/";
                            $strReportName = "Failed vehicle checks";
                            if ($mixStartDate && $mixEndDate)
                            {
                                $strReportName .= " between ".$this->input->post('report_startdate')." and ".$this->input->post('report_enddate');
                                $strPdfReference = "failed/".$this->doFormatDate($this->input->post('report_startdate'))."/".$this->doFormatDate($this->input->post('report_enddate'))."/";
                            }
                            break;

                    }
                    if(isset($change_view)) {
                        $arrPageData['arrResults']      = $arrResults;
                    } else {

                        $arrPageData['arrResults']      = $arrResults['results'];
                    }

                    $arrPageData['arrReportFields'] = $arrFields;
                    $arrPageData['strPdfUrl']       = $strPdfReference;
                    $arrPageData['strReportName']   = $strReportName;
                    $booSuccess = true;
                    //print_r($arrResults);
                    //die();
                }
                else
                {
                    $arrPageData['arrErrorMessages'][] = "You didn't pick a report type to generate.";
                    $arrPageData['strPageTitle'] = "System Error";
                    $arrPageData['strPageText'] = "You didn't pick a report type to generate.";
                }
            }
            else
            {
                $arrPageData['arrErrorMessages'][] = "There was a problem generating the report.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We're sorry, but the report didn't generate correctly.";
            }
        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        // load views
        $this->load->view('common/header',              $arrPageData);
        if ($booPermission && $booSuccess)
        {
            //$this->load->view('common/system_message',  $arrPageData);
            //load the correct view
            if(isset($change_view)) {
                switch ($change_view) {
                    case 'fleetcompliance':
                        $this->load->view('reports/fleet',          $arrPageData);
                        break;

                    case 'compliancedue':
                        $this->load->view('reports/compliancedue',          $arrPageData);
                        break;

                    case 'compliancecomplete' :
                        $this->load->view('reports/compliancecomplete',          $arrPageData);
                        break;
                }
            } else {
                $this->load->view('fleet/fleetresults',          $arrPageData);
            }
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
        $this->load->view('common/footer',              $arrPageData);


    }

    public function viewcheck($check_id) {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/fleet/');
            redirect('users/login/', 'refresh');
        }
        $this->load->model('users_model');
        $this->load->model('accounts_model');

        //var_dump($arrPageData['currency']);
        //die();
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Fleet.index");

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "View Check";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        if($booPermission) {
            $this->load->model('fleet_model');
            $arrPageData['arrCompleteCheck'] = $this->fleet_model->getCompleteCheck($check_id);


        } else {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }
        //print "<pre>"; print_r($arrPageData); print "</pre>";
        if ($booPermission)
        {
            $this->load->view('common/header', 	$arrPageData);
            $this->load->view('fleet/viewcheck', $arrPageData);
            $this->load->view('common/footer', 	$arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
    }

    public function createPdf()
    {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
            $this->session->set_userdata('strReferral', '/reports/');
            redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Results PDF";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();



        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");
        $booSuccess = false;
        $booOutputHtml = false;

        $arrUriSegments = $this->uri->segment_array();
        //$strName = $arrUriSegments[count($arrUriSegments)];
        $strReportType = strtolower($arrUriSegments[3]);
        $arrParameters = array_slice($arrUriSegments, 3);


        if ($booPermission)
        {
            $this->load->model('reports_model');
            $this->load->model('fleet_model');
            $arrResults = array();
            switch ($strReportType)
            {
                case 'failed':

                    if (count($arrParameters)>0)
                    {
                        $mixStartDate   = $arrParameters[0];
                        $mixEndDate     = $arrParameters[1];
                    }
                    else
                    {
                        $mixStartDate = false;
                        $mixEndDate = false;
                    }

                    $arrResults = $this->fleet_model->getFailedReport(
                        $mixStartDate
                        , $mixEndDate
                        , $this->session->userdata('objSystemUser')->accountid
                    );

                    $arrFields = array(
                        array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                    , array('strName' => 'Reg No','strFieldReference' => 'reg_no')
                    , array('strName' => 'Make and Model','strFieldReference' => 'makemodel')
                    , array('strName' => 'Check Date','strFieldReference' => 'date_time')
                    , array('strName' => 'Check Name','strFieldReference' => 'check_name')
                    , array('strName' => 'Check Note','strFieldReference' => 'check_note')
                    , array('strName' => 'User','strFieldReference' => 'user_name')

                    );
                    $strPdfReference = "failed/";
                    $strReportName = "Failed Vehicle Checks";
                    //between ".$this->doFormatDateBack($arrParameters[0])." and ".$this->doFormatDateBack($arrParameters[1]);
                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($arrParameters[0])." and ".$this->doFormatDateBack($arrParameters[1]);
                    }

                    break;
                case 'all':

                    if (count($arrParameters)>0)
                    {
                        $mixStartDate   = $arrParameters[0];
                        $mixEndDate     = $arrParameters[1];
                    }
                    else
                    {
                        $mixStartDate = false;
                        $mixEndDate = false;
                    }

                    $arrResults = $this->fleet_model->getAllReport(
                        $mixStartDate
                        , $mixEndDate
                        , $this->session->userdata('objSystemUser')->accountid
                    );

                    $arrFields = array(
                        array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                    , array('strName' => 'Reg No','strFieldReference' => 'reg_no')
                    , array('strName' => 'Make and Model','strFieldReference' => 'makemodel')
                    , array('strName' => 'Check Date','strFieldReference' => 'date_time')
                    , array('strName' => 'Check Name','strFieldReference' => 'check_name')
                    , array('strName' => 'Result','strFieldReference' => 'result')
                    , array('strName' => 'User','strFieldReference' => 'user_name')
                    );
                    $strPdfReference = "all/";
                    $strReportName = "All Checks";
                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($arrParameters[0])." and ".$this->doFormatDateBack($arrParameters[1]);
                    }
                    break;


            }
            $booSuccess = true;
            //print_r($arrResults);
            //die();

        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        // load views

        $intParameterCount = count($arrParameters);
        if ($arrParameters[$intParameterCount - 1] == 'true')
        {
            $booOutputHtml = true;
        }



        if ($booPermission && $booSuccess)
        {
          $this->outputPdfFile($strReportName, $arrFields, $arrResults['results'], $booOutputHtml);

        }
        else
        {
            $this->load->view('common/header',              $arrPageData);
            $this->load->view('common/system_message',  $arrPageData);
            $this->load->view('common/footer',              $arrPageData);
        }



    }

    public function outputPdfFile($strReportName, $arrFields, $arrResults, $booOutputHtml = false)
    {
        $this->load->model('accounts_model');
        $currency = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"https://www.iworkaudit.com/includes/css/report.css\" /></head>";

        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>iWorkAudit Report</h1>";
        $strHtml .= "<h2>".$strReportName."</h2>";
        $strHtml .= "</td><td class=\"right\">";
        $strHtml .= "<img alt=\"iworkaudit\" src=\"https://www.iworkaudit.com/includes/img/logo.png\">";
        $strHtml .= "</td></tr></table>";



        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr>";

        foreach ($arrFields as $arrReportField)
        {
            $strHtml .= "<th>".$arrReportField['strName']."</th>";
        }

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        $arrTotals = array();
        foreach ($arrResults as $objItem)
        {

            $strHtml .= "<tr>";

            foreach ($arrFields as $arrReportField)
            {
                $strHtml .=  "<td>";
                if (array_key_exists('strConversion', $arrReportField))
                {
                    switch ($arrReportField['strConversion'])
                    {
                        case 'date':
                            $arrDate = explode('-', $objItem->{$arrReportField['strFieldReference']});
                            if (count($arrDate) >1) {
                                $strHtml .= $arrDate[2]."/".$arrDate[1]."/".$arrDate[0];
                            }
                            else
                            {
                                $strHtml .= "Unknown";
                            }
                            break;
                        case 'datetime':
                            $arrDateTime = explode(' ', $objItem->{$arrReportField['strFieldReference']});
                            $strTime = $arrDateTime[1];
                            $arrDate = explode('-', $arrDateTime[0]);
                            $strHtml .= $arrDate[2]."/".$arrDate[1]."/".$arrDate[0]." ".$strTime;
                            break;
                        case 'pat_result':
                            if ($objItem->{$arrReportField['strFieldReference']} === null)
                            {
                                $strHtml.="-";
                            }
                            else
                            {
                                if ($objItem->{$arrReportField['strFieldReference']} == 1)
                                {
                                    $strHtml.="Pass";
                                }
                                else
                                {
                                    $strHtml.="Fail";
                                }
                            }
                            break;
                        case 'price':
                            $strHtml .= $currency.$objItem->{$arrReportField['strFieldReference']};
                            break;
                    }
                }
                else
                {
                    $strHtml .=  $objItem[$arrReportField['strFieldReference']];
                }
                if (array_key_exists('arrFooter',$arrReportField)
                    && array_key_exists('booTotal',$arrReportField['arrFooter']))
                {
                    if (array_key_exists($arrReportField['strFieldReference'], $arrTotals))
                    {
                        $arrTotals[$arrReportField['strFieldReference']] += $objItem->{$arrReportField['strFieldReference']};
                    }
                    else
                    {
                        $arrTotals[$arrReportField['strFieldReference']] = $objItem->{$arrReportField['strFieldReference']};
                    }

                }

                $strHtml .=  "</td>";
            }

            $strHtml .= "</tr>";
        }
        $strHtml .= "</tbody>";

        $strHtml .= "<tfoot><tr>";

        foreach ($arrFields as $arrReportField)
        {
            if (array_key_exists('arrFooter',$arrReportField))
            {
                if (array_key_exists('booTotal',$arrReportField['arrFooter'])
                    && $arrReportField['arrFooter']['booTotal'])
                {
                    $strHtml .= "<td>";
                    if (array_key_exists('strConversion', $arrReportField)
                        && ($arrReportField['strConversion'] == "price"))
                    {
                        $strHtml .= "&pound;";
                    }
                    $strHtml .= $arrTotals[$arrReportField['strFieldReference']];
                    $strHtml .= "</td>";
                }
                else
                {
                    if (array_key_exists('booTotalLabel',$arrReportField['arrFooter'])
                        && $arrReportField['arrFooter']['booTotalLabel'])
                    {
                        $strHtml .= "<td";
                        if (array_key_exists('intColSpan',$arrReportField['arrFooter'])
                            && ($arrReportField['arrFooter']['intColSpan']>0))
                        {
                            $strHtml .= " colspan=\"".$arrReportField['arrFooter']['intColSpan']."\"";
                        }
                        $strHtml .= " class=\"right\">";
                        $strHtml .= "Totals</td>";
                    }
                }
            }
        }
        $strHtml .= "</tr></tfoot>";


        $strHtml .= "</table>";


        $strHtml .= "<p>Produced by ".$this->session->userdata('objSystemUser')->firstname." ".$this->session->userdata('objSystemUser')->lastname." (".$this->session->userdata('objSystemUser')->username.") on ".date('d/m/Y')."</p>";
        $strHtml .= "</div></body></html>";

        if (!$booOutputHtml)
        {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB','A4');
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("isareport_".date('Ymd_His').".pdf","D");
        }
        else
        {
            echo $strHtml;
            //die();
        }
    }

    public function doFormatDate($strDate)
    {
        if ($strDate != "")
        {
            $arrDate = explode('/', $strDate);
            return $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
        }
        return NULL;
    }

    public function doFormatDateBack($strDate)
    {
        if ($strDate != "")
        {
            $arrDate = explode('-', $strDate);
            return $arrDate[2]."/".$arrDate[1]."/".$arrDate[0];
        }
        return "";
    }


}