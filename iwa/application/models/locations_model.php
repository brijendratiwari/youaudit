<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Locations_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getOne($intLocationId = -1, $intAccountId = -1) {
        if (($intAccountId > 0) && ($intLocationId > 0)) {
            // Run the query
            $this->db->select('locations.id AS locationid, locations.name AS locationname, locations.barcode AS locationbarcode');
            $this->db->from('locations');
            $this->db->where('locations.account_id', $intAccountId);
            $this->db->where('locations.id', $intLocationId);

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrLocations = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrLocations[] = $arrRow;
                }
                $arrResult['results'] = $arrLocations;
            }

            return $arrResult;
        } else {
            return false;
        }
    }

    public function getOneByBarcode($strBarcode = "", $intAccountId = -1) {
        if (($strBarcode != "") && ($intAccountId > 0)) {
            $this->db->select('locations.id AS id, locations.name AS name, locations.barcode AS barcode');
            $this->db->from('locations');
            $this->db->where('locations.account_id', $intAccountId);
            $this->db->where('locations.active', 1);
            $this->db->where('locations.barcode', $strBarcode);
            $resQuery = $this->db->get();

            //print_r($this->db->last_query());

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getAllItemsForLocation($intLocationId = -1, $intAccountId = -1, $booAllLoc = false) {
        if (($intLocationId > 0) && ($intAccountId > 0) || $booAllLoc) {
            $this->db->select('  
                        items.id AS itemid,
                        items.manufacturer, 
                        items.model,
                        items.serial_number, 
                        item_manu.item_manu_name AS item_manu,
                        items.barcode, 
                        items.owner_now,
                        items.owner_since, 
                        items.site AS siteid,
                        items.location_now, 
                        items.location_since,
                        items.value,
                        items.notes,
                        items.warranty_date,
                        items.purchase_date,
                        items.replace_date,
                        items.added_date,
                        items.pattest_date,
                        items.pattest_status,
                        items.quantity,
                        item_manu.doc AS pdf_name,
                        items.current_value,
                        photos.id AS itemphotoid,
                        photos.title AS itemphototitle,
                        photos.path AS itemphotopath,
                        itemstatus.id AS itemstatusid,
                        itemstatus.name AS itemstatusname,
                        categories.id AS categoryid, 
                        categories.name AS categoryname,
                        owner.id AS ownerid,
                        owner.owner_name AS owner_name, 
                        owner.location_id AS owner_location,
                        locations.id AS locationid,
                        locations.name AS locationname,
                        sites.id AS siteid, 
                        sites.name AS sitename,
                        items.supplier, 
                        item_condition.condition AS itemconditionname,
                        suppliers.supplier_title AS suppliers_title
');
            $this->db->from('items');
            $this->db->join('photos', 'photos.id = items.photo_id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id AND locations.account_id = ' . $intAccountId . ' ', 'left');
            $this->db->join('suppliers', 'suppliers.supplier_id = items.supplier', 'left');
                            $this->db->join('owner', 'items.owner_now = owner.id', 'left');
                $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');

//            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');

            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.active', 1);
            if (!$booAllLoc) {
                $this->db->where('items.location_now', $intLocationId);
            }
            $this->db->order_by('items.manufacturer', 'ASC');
            $this->db->order_by('items.model', 'ASC');
            $this->db->order_by('items.barcode', 'ASC');
            $resQuery = $this->db->get();
//            die($this->db->last_query());
            $arrItems = array();
            if ($resQuery->num_rows() > 0) {
                $arrItems = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrItems[] = $arrRow;
                }
                return $arrItems;
            } else {
                return $arrItems;
            }
        } else {
            return false;
        }
    }

    public function getAll($intAccountId = -1, $booActiveOnly = true) {
        if ($intAccountId > 0) {
            // Run the query
            $this->db->select('locations.id AS locationid,
				locations.name AS locationname,
				locations.site_id AS location_site_id,
				locations.barcode AS locationbarcode,
				locations.active AS locationactive');
            $this->db->from('locations');
            $this->db->where('locations.account_id', $intAccountId);
            if ($booActiveOnly) {
                $this->db->where('locations.active', 1);
            }
            $this->db->order_by('locationactive', 'DESC');
            $this->db->order_by('locationname', 'ASC');

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query(), 'results' => array());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrLocations = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrLocations[] = $arrRow;
                }
                $arrResult['results'] = $arrLocations;
            }

            return $arrResult;
        } else {
            return false;
        }
    }

    public function reactivateOne($intLocationId = -1) {
        if ($intLocationId > 0) {
            $this->db->where('id', $intLocationId);
            $arrInput = array('active' => 1);
            return $this->db->update('locations', $arrInput);
        }
        return false;
    }

    public function deleteOne($intLocationId = -1) {
        if (($intLocationId > 0) && ($this->doCheckLocationHasNoActiveItems($intLocationId))) {
            $this->db->where('id', $intLocationId);
            $arrInput = array('active' => 0);
            return $this->db->update('locations', $arrInput);
        }
        return false;
    }

    public function doCheckLocationNameIsUniqueOnAccount($strName, $intAccountId) {
        if (($strName != "") && ($intAccountId > 0)) {
            $this->db->where('account_id', $intAccountId);
            $this->db->where('name', $strName);
            $this->db->from('locations');
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    public function doCheckLocationBarcodeIsUnique($strName) {
        if ($strName != "") {
            $this->db->where('barcode', $strName);
            $this->db->from('locations');
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    public function doCheckLocationHasNoActiveItems($intLocationId = -1) {
        if ($intLocationId > 0) {
            $this->db->select('items_locations_link.date AS currentlocationdate,
			      items.id AS itemid,
			      locations.id AS locationid');
            // we need to do a sub query, this
            $this->db->from('( SELECT max(`date`) as most_recent_date_for_location FROM items_locations_link GROUP BY item_id ) q2 ');
            $this->db->join('items_locations_link', 'items_locations_link.date = most_recent_date_for_location', 'left');
            $this->db->join('items', 'items_locations_link.item_id = items.id', 'left');
            $this->db->join('locations', 'items_locations_link.location_id = locations.id', 'left');
            $this->db->where('locations.id', $intLocationId);
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

    public function editOne($intLocationId = -1, $arrInput = array()) {
        if ($intLocationId > 0) {
            $this->db->where('id', $intLocationId);
            return $this->db->update('locations', $arrInput);
        }
        return false;
    }

    public function addOne($arrInput = array()) {
        return $this->db->insert('locations', $arrInput);
    }

    public function addOneAndReturnId($arrInput = array()) {
        if ($this->db->insert('locations', $arrInput)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function check_qrExists($qr = '') {
        $query = $this->db->get_where('items', array('barcode' => $qr));
        return $query->num_rows();
    }

    public function check_reg($reg = '') {
        $this->db->like('name', $reg, 'both');
        $query = $this->db->get('locations');
        return $query->num_rows();
    }

    public function getVehicleLocation($reg_no) {
        $this->db->like('name', $reg_no, 'both');
        $query = $this->db->get('locations');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function search($str = '', $account_id) {

        if ($str != '') {
            $str = preg_replace('/[^(\x20-\x7F)]*/', '', $str);
            $this->db->like('name', $str, 'both');
            $this->db->like('account_id', $account_id);
            $query = $this->db->get('locations');
//              print_r($this->db->last_query());
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

    public function addLocationFromCsv($input_array, $account_id) {
        foreach ($input_array as $location) {

            $this->db->select('*');
            $this->db->from('locations');
            $this->db->where('locations.account_id', $account_id);
            $this->db->where('locations.barcode', $location["location_barcode"]);
            $this->db->or_where('locations.name', $location["location"]);

            $resQuery = $this->db->get();

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                
            } else {
                $arr_location = array(
                    'name' => $location["location"],
                    'barcode' => $location['location_barcode'],
                    'account_id' => $account_id
                );
                $this->db->insert('locations', $arr_location);
            }
        }
    }
    
    
      public function getsitebylocation($locationid = -1)
    {
        if ($locationid > 0)
        {
            // Run the query
            $this->db->select('*,sites.name as site_name,sites.id as site_id');
            $this->db->from('locations');
            $this->db->join('sites','locations.site_id=sites.id');
            $this->db->where('locations.id',$locationid );
            $this->db->where('locations.account_id', $this->session->userdata('objSystemUser')->accountid);
           

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query(), 'results' => array());

            // Let's check if there are any results
            if($resQuery->num_rows != 0)
            {
                $arrSites = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow)
                {
                    $arrSites[] = $arrRow;
                }
                $arrResult['results'] = $arrSites;
            }

            return $arrResult;
        }
        else
        {
            return array();
        }
    }

}

?>