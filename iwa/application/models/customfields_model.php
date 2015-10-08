<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customfields_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getAll() {
        $query = $this->db->get_where('custom_fields', array('account_id' => $this->session->userdata('objSystemUser')->accountid));
        return $query->result();
    }

    public function checkDoesNotExist($field_name) {
        $query = $this->db->get_where('custom_fields', array('field_name' => $field_name, 'account_id' => $this->session->userdata('objSystemUser')->accountid));

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function addField($data) {
        $data['account_id'] = $this->session->userdata('objSystemUser')->accountid;

        if (!$this->db->insert('custom_fields', $data)) {
            return false;
        }

        return true;
    }

    public function getField($id) {
        $query = $this->db->get_where('custom_fields', array('id' => $id));
        if($query->num_rows() > 0){
        return $query->row();
        }
    }

    public function getFieldByAccountId($account_id) {
        $query = $this->db->get_where('custom_fields', array('account_id' => $account_id));
        return $query->result();
    }

    public function editField($id, $data) {
        $this->db->where('account_id', $this->session->userdata('objSystemUser')->accountid);
        $this->db->where('id', $id);
        if (!$this->db->update('custom_fields', $data)) {
            return false;
        }

        return true;
    }

    public function getCustomFieldsByItem($item_id) {

        /* Get item category */
        $this->load->model('items_model');
        $cat_id = $this->items_model->getCategoryFor($item_id);
        $this->db->select('*');
        $this->db->where(array('item_id' => $item_id, 'custom_fields_content.account_id' => $this->session->userdata('objSystemUser')->accountid, 'custom_fields_content.category_id' => $cat_id));
        $this->db->join('custom_fields_content', 'custom_fields.id = custom_fields_content.custom_field_id');
        $query = $this->db->get('custom_fields');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getCustomFieldsByItemForApp($item_id) {

        /* Get item category */
        $this->load->model('items_model');
        $cat_id = $this->items_model->getCategoryFor($item_id);
        $this->db->select('*');
//        $this->db->where(array('item_id' => $item_id, 'custom_fields_content.account_id' => 5, 'custom_fields_content.category_id' => $cat_id));
        $this->db->where(array('item_id' => $item_id, 'custom_fields_content.account_id' => $this->session->userdata('objAppUser')->accountid, 'custom_fields_content.category_id' => $cat_id));
        $this->db->join('custom_fields_content', 'custom_fields.id = custom_fields_content.custom_field_id');
        $query = $this->db->get('custom_fields');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function removeContentByItem($item_id) {
        $this->load->model('items_model');
        $cat_id = $this->items_model->getCategoryFor($item_id);

        $this->db->where('item_id', $item_id);
        $this->db->where('account_id', $this->session->userdata('objSystemUser')->accountid);
        $this->db->where('category_id', $cat_id);
        $this->db->delete('custom_fields_content');
        return true;
    }

    public function removeContentByItemForApp($item_id) {
        $this->load->model('items_model');
        $cat_id = $this->items_model->getCategoryFor($item_id);

        $this->db->where('item_id', $item_id);
        $this->db->where('account_id', $this->session->userdata('objAppUser')->accountid);
        $this->db->where('category_id', $cat_id);
        $this->db->delete('custom_fields_content');
        return true;
    }

    public function getsimilarCustomFields($item_id,$category_id) {
        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('custom_fields_content');
        return $query->result();
    }

    public function insertContentByItem($item_id, $content) {        
        $this->load->model('items_model');
        $cat_id = $this->items_model->getCategoryFor($item_id);
        foreach ($content as $k => $v) {
            if ($v == '') {
                continue;
            }
            $arrContent = array('custom_field_id' => $k,
                'account_id' => $this->session->userdata('objSystemUser')->accountid,
                'item_id' => $item_id,
                'content' => $v,
                'category_id' => $cat_id
            );


            $this->db->insert('custom_fields_content', $arrContent);
        }
    }

    public function insertContentByItemForApp($item_id, $content) {
        $this->load->model('items_model');
        $cat_id = $this->items_model->getCategoryFor($item_id);


        foreach ($content as $k => $v) {
            if ($v == '') {
                continue;
            }
            $arrContent = array('custom_field_id' => $k,
                'account_id' => $this->session->userdata('objAppUser')->accountid,
                'item_id' => $item_id,
                'content' => $v,
                'category_id' => $cat_id
            );

            if ($this->session->userdata('objAppUser')->accountid == '')
                return 'Account id invalid';

            $this->db->insert('custom_fields_content', $arrContent);
        }
    }

    public function insertContentByItemid($item_id, $data) {
        $this->load->model('items_model');
//        $cat_id = $this->items_model->getCategoryFor($item_id);

        $ids = explode(',', $item_id);
        $arr = $this->customfields_model->getFieldByAccountId($this->session->userdata('objSystemUser')->accountid);

        foreach ($ids as $itemid) {
            foreach ($arr as $customrecord) {
                if ($data['custom_' . $customrecord->id]) {
                    $arrContent = array(
                        'custom_field_id' => $customrecord->id,
                        'account_id' => $this->session->userdata('objSystemUser')->accountid,
                        'item_id' => $itemid,
                        'content' => $data['custom_' . $customrecord->id],
                        'category_id' => $this->items_model->getCategoryFor($itemid)
                    );
                    $this->db->insert('custom_fields_content', $arrContent);
                }
            }
        }
    }

    public function fieldHasContent($id) {
        $query = $this->db->get_where('custom_fields_content', array('custom_field_id' => $id));

        if ($query->num_rows() > 0) {

            return true;
        } else {
            return false;
        }
    }

    public function deleteField($field_id) {
        $this->db->where('id', $field_id);
        $this->db->delete('custom_fields');
        return true;
    }

    public function check_myrecord($item_id) {
        $this->db->select('item_id,content');
        $this->db->where(array('item_id' => $item_id, 'account_id' => 21, 'custom_field_id' => 2));
        $query = $this->db->get('custom_fields_content');
        $content = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $data) {
                $content = array(
                    'item_id' => $data->item_id,
                    'content' => $data->content);
            }
            return $content;
        } else {
            $content = array(
                'item_id' => $item_id,
                'content' => '');
            return $content;
        }
    }

    public function insertcontent() {
        $this->db->select('custom_fields_content.item_id,dummy.arkey,dummy.serial');
        $this->db->where(array('account_id' => 53, 'custom_field_id' => 33));
        $this->db->join('custom_fields_content', 'dummy.arkey = custom_fields_content.content');
        $query = $this->db->get('dummy');
        if ($query->num_rows() > 0) {
//            return $query->result();
//            foreach ($query->result() as $custom_content){
//                $data = array(
//                    'serial_number' => $custom_content->serial
//                );
//            $this->db->where('id', $custom_content->item_id);
//            $this->db->where('account_id', 53);
//            $this->db->update('items', $data);
//          
//            } 
        }
    }

    public function importcustomdata($data) {
        if ($data) {
            $this->db->insert('custom_fields_content', $data);
            return $this->db->insert_id();
        }
    }

}

?>