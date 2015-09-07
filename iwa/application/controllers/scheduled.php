<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scheduled extends MY_Controller {

	/********************************************
	 * Run Scheduled Taks
	 *
	 * 
	 ********************************************/	
    public function notify() {
    
        $this->load->model('fleet_model');
        $this->load->model('items_model');
        $this->load->model('emails_model');
        $this->load->model('notifications_model');
        $this->load->model('accounts_model');
        
        /* Get fleet renewals */
        $fleet_renewals = $this->fleet_model->getRenewals($this->session->userdata('objSystemUser')->accountid);
        $arrAccount = $this->accounts_model->getOne($this->session->userdata('objSystemUser')->accountid);
        //print "<pre>"; print_r($fleet_renewals); print "</pre>";
        /* Send Fleet Email to account contact */
        $this->notifications_model->fleetNotify($fleet_renewals, $arrAccount);
        
        /* Get compliance renewals */
    }
    
    
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */