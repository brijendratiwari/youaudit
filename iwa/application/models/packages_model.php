<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Packages_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
     
    public function getAll()
    {
        // Run the query
	$this->db->select('packages.id AS packageid, packages.name AS packagename');
        $this->db->from('packages');
	$this->db->order_by('packageid', 'ASC');
	
	$resQuery = $this->db->get();
	$arrResult = array('query' => $this->db->last_query());
	
        // Let's check if there are any results
        if($resQuery->num_rows != 0)
        {
	    $arrPackages = array();
            // If there are levels, then load 
            foreach ($resQuery->result() as $arrRow)
	    {
		$arrPackages[] = $arrRow;
	    }
            $arrResult['results'] = $arrPackages;
        }
        
        return $arrResult;
    }
    
}
?>