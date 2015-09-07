<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Actions_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function logOne($arrInput) {
        return $this->db->insert('actions', $arrInput);
    }

    public function getAllForUser($intUserId, $intAccountId) {
        $this->db->select('
                actions.action, actions.table, 
                actions.who_did_it, actions.on_account, actions.admin_present, actions.when, 
                actions.to_what                
                ');

        $this->db->from('actions');
        $this->db->where('actions.on_account', $intAccountId);
        $this->db->where('actions.who_did_it', $intUserId);
        $this->db->order_by('actions.id DESC');

        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow) {
                $arrItemsData[] = $objRow;
            }
            return array('results' => $arrItemsData, 'query' => $this->db->last_query());
        } else {
            return array('results' => array(), 'query' => $this->db->last_query());
        }
    }

    public function getObjectName($strTableName, $intObjectId) {
 
        if ($strTableName == 'fleet') {
            $this->db->from($strTableName);
            $this->db->where('fleet_id', $intObjectId);
            $resQuery = $this->db->get();
        } else {
            if ($strTableName == 'theme_log') {
                $this->db->from($strTableName);
                $this->db->where('id', $intObjectId);
                $resQuery = $this->db->get();
            } else {
                $this->db->from($strTableName);
                $this->db->where('id', $intObjectId);
                $resQuery = $this->db->get();
            }
        }

        if ($resQuery->num_rows() == 1) {
            $arrRow = $resQuery->result();

            $strReturn = "";

            switch ($strTableName) {
                case 'items':
                    $strReturn = "(" . $arrRow[0]->barcode . ") " . $arrRow[0]->manufacturer . " " . $arrRow[0]->model;
                    break;
                case 'users':
                    $strReturn = $arrRow[0]->firstname . " " . $arrRow[0]->lastname . " (" . $arrRow[0]->username . ")";
                    break;
                case 'theme_log':
                    $strReturn = "Logo->" . $arrRow[0]->logo ." Favicon-> ".$arrRow[0]->favicon. "  Color-> " . $arrRow[0]->color;
                    break;

                default:
                    $strReturn = $arrRow[0]->name;
                    break;
            }

            return $strReturn;
        }
        return false;
    }

}

?>