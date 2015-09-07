<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Categories_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getOne($intCategoryId = -1, $intAccountId = -1) {
        if (($intAccountId > 0) && ($intCategoryId > 0)) {
            // Run the query
            $this->db->select('categories.id AS facultyid, categories.name AS categoryname, categories.default AS categorydefault, categories.icon AS categoryicon, categories.depreciation_rate AS categorydepreciationrate, categories.support_emails AS support_emails, categories.custom_fields AS custom_fields, categories.quantity_enabled');
            $this->db->from('categories');
            $this->db->where('categories.account_id', $intAccountId);
            $this->db->where('categories.id', $intCategoryId);

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrCategories = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrCategories[] = $arrRow;
                }
                $arrResult['results'] = $arrCategories;
            }

            return $arrResult;
        } else {
            return false;
        }
    }

    public function getAll($intAccountId = -1, $booActiveOnly = true) {
        // Run the query
        $this->db->select('categories.id AS categoryid, categories.name AS categoryname, categories.active AS categoryactive, categories.default AS categorydefault, categories.icon AS categoryicon, categories.depreciation_rate AS categorydepreciationrate, categories.support_emails AS support_emails,categories.quantity_enabled AS quantity');
        $this->db->from('categories');

        if ($booActiveOnly) {
            $this->db->where('active', 1);
        }

        $this->db->where('account_id', $intAccountId);
        $this->db->order_by('categoryname', 'ASC');

        $resQuery = $this->db->get();
        $arrResult = array('query' => $this->db->last_query(), 'results' => array());

        // Let's check if there are any results
        if ($resQuery->num_rows != 0) {
            $arrCategories = array();
            // If there are levels, then load 
            foreach ($resQuery->result() as $arrRow) {
                $arrCategories[] = $arrRow;
            }
            $arrResult['results'] = $arrCategories;
        }


        return $arrResult;
    }

    public function editOne($intCategoryId = -1, $arrInput = array()) {
        if ($intCategoryId > 0) {
            $this->db->where('id', $intCategoryId);
            return $this->db->update('categories', $arrInput);
        }
        return false;
    }

    public function addOne($arrInput = array()) {
        return $this->db->insert('categories', $arrInput);
    }

    public function addOneAndReturnId($arrInput = array()) {
        if ($this->db->insert('categories', $arrInput)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function reactivateOne($intCategoryId = -1) {
        if ($intCategoryId > 0) {
            $this->db->where('id', $intCategoryId);
            $arrInput = array('active' => 1);
            return $this->db->update('categories', $arrInput);
        }
        return false;
    }

    public function deleteOne($intCategoryId = -1) {
        
        if (($intCategoryId > 0) && ($this->doCheckCategoryHasNoActiveItems($intCategoryId))) {
            $this->db->where('id', $intCategoryId);
            $arrInput = array('active' => 0,'archive'=>0);
            $this->db->update('categories', $arrInput);
            return TRUE;
        }
        return false;
    }

    public function doCheckCategoryHasNoActiveItems($intCategoryId = -1) {
        if ($intCategoryId > 0) {
            $this->db->select('items_categories_link.item_id AS itemid,
			      items.active AS itemactive');
            // we need to do a sub query, this
            $this->db->from('items_categories_link');
            $this->db->join('items', 'items_categories_link.item_id = items.id', 'left');
            $this->db->where('items_categories_link.category_id', $intCategoryId);
            $this->db->where('items.active', 1);
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function doCheckCategoryNameIsUniqueOnAccount($strName, $intAccountId) {
        if (($strName != "") && ($intAccountId > 0)) {
            $this->db->where('account_id', $intAccountId);
            $this->db->where('name', $strName);
            $this->db->from('categories');
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    public function getAllItemsForCategoryDepreciation($intCategoryId, $intAccountId) {
        $this->db->select('items.id AS itemid, items.value, items.current_value');
        $this->db->from('items');
        $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
        $this->db->where('items.account_id', $intAccountId);
        $this->db->where('items.active', 1);
        $this->db->where('items_categories_link.category_id', $intCategoryId);

        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow) {
                $arrItemsData[] = $objRow;
            }
            return $arrItemsData;
        } else {
            return array();
        }
    }

    public function depreciateThis($intItemId, $floValue) {
        $this->db->where('id', $intItemId);
        return $this->db->update('items', array('current_value' => $floValue));
    }

    public function string_to_ascii($string) {
        $ascii = array();

        for ($i = 0; $i < strlen($string); $i++) {
            $ascii[] = ord($string[$i]);
        }

        return($ascii);
    }

    public function search($str = '', $account_id) {
        if ($str != '') {

            $str = preg_replace('/[^(\x20-\x7F)]*/', '', $str);
            $this->db->like('name', $str);
            $this->db->where('account_id', $account_id);
            $query = $this->db->get('categories');
//               print_r($this->db->last_query());
            if ($query->num_rows() > 0) {

                $result = $query->row_array();
                return $result['id'];
            } else {

                return false;
            }
        } else {
            return false;
        }
    }

    public function addCategoriesFromCsv($input_array, $account_id) {
        foreach ($input_array as $category_name) {
            $this->db->select('*');
            $this->db->from('categories');
            $this->db->where('categories.account_id', $account_id);
            $this->db->where('categories.name', $category_name);

            $resQuery = $this->db->get();
            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
              
            } else {
                $arrCategoryData = array(
                    'name' => $category_name,
                    'account_id' => $account_id,
                    'default' => 0,
                );
            $this->db->insert('categories', $arrCategoryData);
            
            }
        }
    }

    public function setCustomFields($cat_id, $data) {
        $arrUpdate = array('custom_fields' => json_encode($data));
        $this->db->where('id', $cat_id);
        $this->db->update('categories', $arrUpdate);
    }

    public function getCustomFields($cat_id) {
        $this->db->select('custom_fields');
        $this->db->where('id', $cat_id);
        $query = $this->db->get('categories');
        $result = $query->row();
        $arrCustomFields = json_decode($result->custom_fields);
        if (!$arrCustomFields) {
            return false;
        }
        $this->db->where_in('id', $arrCustomFields);
        $query = $this->db->get('custom_fields');
        return $query->result();
    }
    public function getCustomFieldsForApp($cat_id) {
        $this->db->select('custom_fields');
        $this->db->where('id', $cat_id);
        $query = $this->db->get('categories');
        $result = $query->row();
        $arrCustomFields = json_decode($result->custom_fields);
        if (!$arrCustomFields) {
            return array();
        }
        $this->db->where_in('id', $arrCustomFields);
        $query = $this->db->get('custom_fields');
        return $query->result();
    }

}

?>