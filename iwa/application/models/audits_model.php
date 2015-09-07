<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Audits_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getLastAuditForLocation($intId) {
        $this->db->select('audits.completed as date, users.firstname, users.lastname , audits.id as audit_id');
        $this->db->from('audits');
        $this->db->join('users', 'audits.user_id = users.id', 'left');
        $this->db->where('audits.location_id', $intId);
        $this->db->order_by('audits.completed DESC');
        $this->db->limit(1);
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            $objRow = $resQuery->result();
            return array('date' => $objRow[0]->date, 'user' => $objRow[0]->firstname . " " . $objRow[0]->lastname , 'audit_id' => $objRow[0]->audit_id);
        }
        return false;
    }

    public function logOne($arrInput) {
        $this->db->insert('audits', $arrInput);
        return $this->db->insert_id();
    }

    public function addItemToAudit($arrInput) {
        $this->db->insert('audititems', $arrInput);
    }

    public function getCountPresentDetailsOfLastAudit($audit_id) {
        $this->db->select('count(audit_id) AS present_item');
        $this->db->from('audititems');
        $this->db->where('audit_id', $audit_id);
        $this->db->where('present', 1);
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            $objRow = $resQuery->result();
            return array('Total_present' => $objRow[0]->present_item);
        }
        return false;
    }

    public function getCountMissingDetailsOfLastAudit($audit_id) {
        $this->db->select('count(audit_id) AS missing_item');
        $this->db->from('audititems');
        $this->db->where('audit_id', $audit_id);
        $this->db->where('present', 0);
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            $objRow = $resQuery->result();
            return array('Total_missing' => $objRow[0]->missing_item);
        }
        return false;
    }

}

?>