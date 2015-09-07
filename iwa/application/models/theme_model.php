<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Theme_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_Theme($param) {
        // Theme inserted 
        $this->db->insert('theme', $param);
        // Record Active on website
        $this->update_status($param);
    }

    function select_Theme($id) {

        $this->db->select('*');

        $this->db->from('theme');
        $this->db->where('account_id', $id);
        $resQuery = $this->db->get();
        if ($resQuery->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_Theme($theme_data) {
        if ($theme_data['logo'] == NULL) {
            unset($theme_data['logo']);
        } else {
            if (isset($this->session->userdata['theme_design']->logo)) {
                $logo = $this->session->userdata['theme_design']->logo;
                if (file_exists("brochure/logo/" . $logo))
                    unlink("brochure/logo/" . $logo);
            }
        }
        if ($theme_data['favicon'] == NULL)
            unset($theme_data['favicon']);
        else {
            if (isset($this->session->userdata['theme_design']->favicon)) {
                $fav = $this->session->userdata['theme_design']->favicon;
                if (file_exists("brochure/logo/" . $fav))
                    unlink("brochure/logo/" . $fav);
            }
        }

        $this->db->where('account_id', $theme_data['account_id']);
        $this->db->update('theme', $theme_data);
        $this->update_status($theme_data);
    }

    function update_status($theme_data) {

        $sql = "UPDATE theme SET status = 1 WHERE account_id != " . $theme_data['account_id'];
        $this->db->query($sql);
    }

    function fetch_Theme() {
        $this->db->select('*');

        $this->db->from('theme');
        $this->db->where('status', 0);
        $resQuery = $this->db->get();
        $arrThemeData = array();
        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrThemeData[] = $objRow;
            }
            return $arrThemeData;
        } else {
            return array('results' => array());
        }
    }

    function insert_log($param) {
        if ($param['logo'] == NULL)
            $param['logo'] = "";
        if ($param['favicon'] == NULL)
            $param['favicon'] = "";

        $this->db->insert('theme_log', $param);
        return $this->db->insert_id();
        ;
    }

    //------------------ New Theme functon

    function select_Theme_user($id) {

        $this->db->select('*');

        $this->db->from('theme');
        $this->db->where('account_id', $id);
        $resQuery = $this->db->get();
        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrThemeData[] = $objRow;
            }
            return $arrThemeData;
        } else {
            return FALSE;
        }
    }

}

?>
