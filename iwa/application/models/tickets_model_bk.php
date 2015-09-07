<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Tickets_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
    }
    
    public function ticketSubmission($item_id, $username, $description, $priority)
    {
        $data = array(
                    'item_id' => $item_id,
                    'user_id' => $username,
                    'date' => date("Y-m-d H:i:s"),
                    'description' => $description,
                    'priority' => $priority
                );
        $this->db->insert('tickets', $data);    
    }

    public function ticketSubmissionFleet($fleet_id, $username, $description , $priority)
    {	echo "in side model"; die;
        $data = array(
            'fleet_id' => $fleet_id,
            'user_id' => $username,
            'date' => date("Y-m-d H:i:s"),
            'description' => $description,
            'priority' => $priority
        );
        $this->db->insert('fleet_tickets', $data);
    }

    public function ticketFleetHistory($item_id) {
        $this->load->model('users_model');
        $this->db->where('fleet_id', $item_id);
        $query = $this->db->get('fleet_tickets');
        $itemHistory = $query->result_array();

        foreach ($itemHistory as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);

            $itemHistory[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        }

        return $itemHistory;
    }

    public function ticketHistory($item_id) {
        $this->load->model('users_model');
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('tickets');
        $itemHistory = $query->result_array();
        
        foreach ($itemHistory as $key => $value) {
            $user = $this->users_model->getOne($value['user_id'], $this->session->userdata('objSystemUser')->accountid);
            
            $itemHistory[$key]['username'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        }
        
        return $itemHistory;
    }
	
	function updateTicket($ticket_id,$data) {
		
		$this->db->where('id', $ticket_id);		
        $this->db->update('tickets', $data);
	} 
	// End of function
	
	function insertTicket($data) {
		$this->db->insert('tickets', $data);  
	}
    
}
?>
