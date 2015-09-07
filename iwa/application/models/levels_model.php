<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Levels_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
     
    public function getAll()
    {
        // Run the query
	$this->db->select('levels.id AS levelid, levels.name AS levelname');
        $this->db->from('levels');
	$this->db->order_by('levelid', 'ASC');
	
	$resQuery = $this->db->get();
	$arrResult = array('query' => $this->db->last_query());
	
        // Let's check if there are any results
        if($resQuery->num_rows != 0)
        {
	    $arrLevels = array();
            // If there are levels, then load 
            foreach ($resQuery->result() as $arrRow)
	    {
		$arrLevels[] = $arrRow;
	    }
            $arrResult['results'] = $arrLevels;
        }
        
        return $arrResult;
    }
    
}
?>