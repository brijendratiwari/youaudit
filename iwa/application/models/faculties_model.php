<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faculties_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function getOne($intFacultyId = -1, $intAccountId = -1)
    {
	if (($intAccountId > 0) && ($intFacultyId > 0))
	{
	    // Run the query
	    $this->db->select('faculties.id AS facultyid, faculties.name AS facultyname');
	    $this->db->from('faculties');
	    $this->db->where('faculties.account_id', $intAccountId);
	    $this->db->where('faculties.id', $intFacultyId);
	    
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
	else
	{
	    return false;
	}
    }
    
    public function doCheckFacultyNameIsUniqueOnAccount($strName, $intAccountId)
    {
        if (($strName != "") && ($intAccountId>0))
        {
            $this->db->where('account_id', $intAccountId);
            $this->db->where('name', $strName);
            $this->db->from('faculties');
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0)
	    {
		return false;
	    }
	    else
	    {
		return true;
	    }
        }
        
        return false;
    }
    
    public function getAll($intAccount = -1, $booActiveOnly = true)
    {
        if ($intAccount > 0)
        {
            // Run the query
            $this->db->select('faculties.id AS facultyid, faculties.name AS facultyname, faculties.active AS facultyactive');
            $this->db->from('faculties');
            if ($booActiveOnly)
            {
                $this->db->where('active', 1);
            }
            $this->db->where('account_id', $intAccount);
            $this->db->order_by('facultyname', 'ASC');

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query(), 'results' => array());

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
        else
        {
            return array();
        }
    }
    
    public function reactivateOne($intFacultyId = -1)
    {
	if ($intFacultyId > 0)
	{
	    $this->db->where('id', $intFacultyId);
	    $arrInput = array('active'=>1);
	    return $this->db->update('faculties', $arrInput);
	}
	return false;
    }
    
    public function deleteOne($intFacultyId = -1)
    {
	if (($intFacultyId > 0) && ($this->doCheckFacultyHasNoActiveItems($intFacultyId)))
	{
	    $this->db->where('id', $intFacultyId);
	    $arrInput = array('active'=>0);
	    return $this->db->update('faculties', $arrInput);
	}
	return false;
    }
    
    public function doCheckFacultyHasNoActiveItems($intFacultyId = -1)
    {
	if ($intFacultyId > 0)
	{
	    $this->db->select('items.id AS itemid,
			      items.faculty AS facultyid');
	    // we need to do a sub query, this
	    $this->db->from('items');
	    
	    $this->db->where('items.faculty', $intFacultyId);
	    $this->db->where('items.active', 1);
	    $resQuery = $this->db->get();
	    if ($resQuery->num_rows() > 0)
	    {
		return false;
	    }
	    else
	    {
		return true;
	    }
	}
	else
	{
	    return false;
	}
    }
    
    public function editOne($intFacultyId = -1, $arrInput = array())
    {
	if ($intFacultyId > 0)
	{
	    $this->db->where('id', $intFacultyId);
	    return $this->db->update('faculties', $arrInput);
	}
	return false;
    }
    
    public function addOne($arrInput = array())
    {
	return $this->db->insert('faculties', $arrInput);
    }
    
    public function addOneAndReturnId($arrInput = array())
    {
	if ($this->db->insert('faculties', $arrInput))
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;
        }
    }
    
}
?>