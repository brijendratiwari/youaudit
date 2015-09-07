<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Suppliers_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    public function getAll($account_id = NULL) {
     
        /* Get All records */
        if($account_id == NULL) {
            $this->db->where('account_id', $this->session->userdata('objSystemUser')->accountid);
        } else {
            $this->db->where('account_id', $account_id);
        }
        $query = $this->db->get('suppliers');
        if($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function add($data, $app_account_id = NULL) {
        if($app_account_id == NULL) {
            $data['account_id'] = $this->session->userdata('objSystemUser')->accountid;
        } else {
            $data['account_id'] = $app_account_id;
        }
        if($this->db->insert('suppliers', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function getOne($id) {
        $query = $this->db->get_where('suppliers', array('supplier_id' => $id));
        
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function edit($id, $data){
        $this->db->where('supplier_id', $id);
        if($this->db->update('suppliers', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        $query = $this->db->delete('suppliers', array('supplier_id' => $id));

        if($query == TRUE) {
            return true;
        } else {
            return false;
        }
    }

}
?>