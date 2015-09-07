<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class notifications_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
     
    public function fleetNotify($fleet, $arrAccount) {
        $ci =& get_instance();
        $ci->load->library('email');
        //print "<pre>"; print_r($fleet); print "</pre>";
        //print "<pre>"; print_r($arrAccount); print "</pre>";
        /* For each vehicle check if any flags are set... if so, then send email */
        $count = 0;
        foreach ($fleet as $key => $vehicle) {
           
                if(($vehicle['mot_due'] == 1) OR ($vehicle['tax_due'] == 1) OR ($vehicle['service_due'] == 1)) {
                    $count++;
                    $arrDueVehicles[$vehicle['fleet_id']] = $vehicle;
                } else {
                    continue;
                }
            
        }    
        /* Build Email */
        
        $msg = "" . $arrAccount['result'][0]->accountcontactname . ",";
        $msg .= "\n\nBelow is a list of vehicles which have compliance checks in the next 30 days:";
        foreach($arrDueVehicles as $vehicle) {
            //print "<pre>"; print_r($vehicle); print "</pre>";
            
            $msg .= "\n\n" . $vehicle['make'] . " " . $vehicle['model'] . " - " . $vehicle['reg_no'];
            if($vehicle['mot_due'] == 1) {
                $msg .= "\nMOT Due: " . $vehicle['mot_due_date'];
            }
            if($vehicle['service_due'] == 1) {
                $msg .= "\nService Due: " . $vehicle['service_due_date'];
            }
            if($vehicle['tax_due'] == 1) {
                $msg .= "\nTax Due: " . $vehicle['tax_due_date'];
            }
            
        }
        $msg .= "\n\nKind Regards,";
        $msg .= "\n\niWorkAudit Support";
        $ci->email->subject("Fleet Compliance Notification (Renewals)");
        $ci->email->from("support@iworkaudit.com", "iWorkAudit Support");
        $ci->email->to("support@iworkaudit.com", "iWorkAudit Support");
        $ci->email->message($msg);
        $ci->email->send();
        //echo $ci->email->print_debugger();

    }
    
}
?>