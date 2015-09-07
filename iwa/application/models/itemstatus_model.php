<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItemStatus_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    public function getStatus($status_id){


        $this->db->where('id', $status_id);
        $query = $this->db->get('itemstatus');

        $row = $query->row_array();

        return isset($row['name']) != '' ? $row['name'] : '';



    }
    
    public function getAll($booIncludeImpliesInactive = false)
    {
        
            // Run the query
            $this->db->select('itemstatus.id AS statusid, itemstatus.name AS statusname');
            $this->db->from('itemstatus');
            if (!$booIncludeImpliesInactive)
            {
                $this->db->where('implies_inactive', 0);
            }
            
            $this->db->order_by('statusname', 'ASC');

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if($resQuery->num_rows != 0)
            {
                $arrFaculties = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow)
                {
                    $arrFaculties[] = $arrRow;
                }
                $arrResult['results'] = $arrFaculties;
            }

            return $arrResult;
        
    }
    
    public function getAllThatImplyInactive()
    {
        
            // Run the query
            $this->db->select('itemstatus.id AS statusid, itemstatus.name AS statusname');
            $this->db->from('itemstatus');
            $this->db->where('implies_inactive', 1);
            
            
            $this->db->order_by('statusname', 'ASC');

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if($resQuery->num_rows != 0)
            {
                $arrFaculties = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow)
                {
                    $arrFaculties[] = $arrRow;
                }
                $arrResult['results'] = $arrFaculties;
            }

            return $arrResult;
        
    }
    
    public function getAllReasons()
    {
    
            // Run the query
            $this->db->select('id AS reasonid, reason');
            $this->db->from('item_remove_reasons');
            $this->db->order_by('reason', 'ASC');
            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());
            // Let's check if there are any results
            if($resQuery->num_rows != 0)
            {
                $arrFaculties = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow)
                {
                    $arrFaculties[] = $arrRow;
                }
                $arrResult['results'] = $arrFaculties;
            }
            return $arrResult;
    }

    
}
?>