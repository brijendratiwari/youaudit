<?php
class MY_Controller extends CI_Controller
{
    function __construct()
    {
     parent::__construct();


    }
    
    public function logThis($strAction, $strTable, $intObject)
        { 
            
            if (($strAction != '') && ($strTable != "") && ($intObject > 0))
            {
                $arrLogInformation = array();
                $arrLogInformation['action'] = $strAction;
                $arrLogInformation['table'] = $strTable;
                $arrLogInformation['to_what'] = $intObject;
                $arrLogInformation['who_did_it'] = $this->session->userdata('objSystemUser')->userid;
                $arrLogInformation['on_account'] = $this->session->userdata('objSystemUser')->accountid;
                $arrLogInformation['admin_present'] = 0;
                if ($this->session->userdata('booInheritedUser'))
                {
                    $arrLogInformation['admin_present'] = $this->session->userdata('objAdminUser')->id;
                }
                $this->load->model('actions_model');
                $this->actions_model->logOne($arrLogInformation);
            }
            return;
        }
}
?>
