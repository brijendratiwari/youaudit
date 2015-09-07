<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Emails_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function reminderMaling() {

        $this->db->select('users.id,users.username,users.firstname,users.lastname,users.account_id');
        $this->db->from('test_type');
        $this->db->join('users', 'test_type.manager_of_check = users.id', 'left');
        $this->db->where('test_type.test_type_notify', 1);

        $resQuery = $this->db->get();

        // Let's check if there are any results
        $arrItemsData = array();

        if ($resQuery->num_rows > 0) {

            foreach ($resQuery->result_array() as $objRow) {
                $arrItemsData[] = $objRow;
            }

        } else {
            // If we didn't find rows,
            // then return false
            $arrItemsData[] = FALSE;
        }
//        var_dump($arrItemsData);
        return $arrItemsData;
    }
    

}
?>