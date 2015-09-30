<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Applications_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function searchItems($intAccountId = -1, $strManufacturer = "", $intSite = -1, $intUser = -1, $intLocation = -1, $intCategory = -1, $stritem_manu = "", $strbarcode = -1, $strfreetext = -1) {

        if ($intAccountId > 0) {

            if ($strfreetext != -1) {

                $this->db->distinct();
                $this->db->select('
                items.id AS itemid,
                        items.manufacturer, 
                        item_manu.item_manu_name AS item_manu,
                        items.model,
                        items.serial_number, 
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
                        items.current_value,
                        items.compliance_start,
                        item_manu.item_manu_name,
                        item_manu.doc as pdf_name,
                        items.account_id AS accountid ,
		categories.id AS categoryid,
                categories.name AS categoryname,
		owner.id AS ownerid,
                owner.owner_name AS owner_name, 
                owner.location_id AS owner_location,
                photos.id AS itemphotoid, 
                photos.title AS itemphototitle,
                photos.path AS itemphotopath,
		locations.id AS locationid,
                locations.name AS locationname,
                locations.site_id AS location_site_id,
                suppliers.supplier_id as supplier,
                suppliers.supplier_name,
                suppliers.supplier_title as suppliers_title,
                sites.id AS siteid, 
                sites.name AS sitename,
                item_condition.condition AS itemconditionname,
                itemstatus.id AS itemstatusid,
                itemstatus.name AS itemstatusname,audititems.item_id as audititem,audititems.audit_id');

                //suppliers.supplier_id as supplier,
                //suppliers.supplier_title as suppliers_title,


                $this->db->from('items');
                $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
                $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
                //             JOIN for Item condition
                $this->db->join('owner', 'items.owner_now = owner.id', 'left');
                $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');

                $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');
//                $this->db->join('users', 'items.owner_now = users.id', 'left');
                $this->db->join('photos', 'items.photo_id = photos.id', 'left');
                $this->db->join('locations', 'items.location_now = locations.id', 'left');
                $this->db->join('sites', 'items.site = sites.id', 'left');
                $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
                $this->db->join('suppliers', 'suppliers.supplier_id = items.supplier', 'left');
                $this->db->join('audititems', 'audititems.item_id = items.id', 'left');

                $this->db->where('items.account_id', $intAccountId);
                $this->db->where('items.active', 1);
                //$this->db->where('sites.archive', 1);

                if ($strbarcode != -1) {
                    $this->db->where('items.barcode', $strbarcode);
                }


                if ($intSite != -1) {
                    $this->db->where('sites.id', $intSite);
                }
                if ($intUser != -1) {
                    $this->db->where('owner.id', $intUser);
                }
                if ($intLocation != -1) {
                    $this->db->where('locations.id', $intLocation);
                }
                if ($strManufacturer != "") {
                    $this->db->where('items.manufacturer', $strManufacturer);
                }
                if ($stritem_manu != "") {
                    $this->db->where('items.item_manu', $stritem_manu);
                }

                // Add Category Filter             
                if ($intCategory != -1) {
                    $this->db->where('categories.id', $intCategory);
                }
                if ($this->session->userdata('is_supplier')) {
                    $this->db->where('items.supplier', $this->session->userdata('is_supplier'));
                }

                $this->db->or_like('sites.name', $strfreetext);
//                $this->db->or_like('users.username', $strfreetext);
                $this->db->or_like('owner.owner_name', $strfreetext);
                $this->db->or_like('locations.name', $strfreetext);
                $this->db->or_like('categories.name', $strfreetext);
                $this->db->or_like('items.manufacturer', $strfreetext);
                $this->db->or_like('items.item_manu', $strfreetext);

                $this->db->group_by('items.id');
                $resQuery = $this->db->get();
//                  echo $this->db->last_query();DIE;
//            die ($this->db->last_query());

                if ($resQuery->num_rows() > 0) {
                    $arrItemsData = array();
                    foreach ($resQuery->result() as $objRow) {
                        if ($objRow->accountid == $intAccountId) {

                            $arrItemsData[] = $objRow;
                        }
                    }

                    return $arrItemsData;
                } else {
                    return array();
                }
            } else {
                $this->db->select('
                items.id AS itemid,
                        items.manufacturer, 
                        items.model,
                         item_manu.item_manu_name AS item_manu,
                        items.serial_number, 
                        items.barcode, 
                        items.owner_now,
                        items.owner_since, 
                        items.site AS siteid,
                        items.location_now, 
                        items.location_since,
                        items.compliance_start,
                        items.value,
                        items.notes,
                        items.warranty_date,
                        items.purchase_date,
                        items.replace_date,
                        items.added_date,
                        items.pattest_date,
                        items.pattest_status,
                        items.quantity,
                        items.current_value,
                        item_manu.item_manu_name,
                        item_manu.doc as pdf_name,
		categories.id AS categoryid,
                categories.name AS categoryname,
			owner.id AS ownerid,
                owner.owner_name AS owner_name, 
                owner.location_id AS owner_location,
                photos.id AS itemphotoid, 
                photos.title AS itemphototitle,
                photos.path AS itemphotopath,
		locations.id AS locationid,
                locations.name AS locationname,
                 locations.site_id AS location_site_id,
                suppliers.supplier_id as supplier,
                suppliers.supplier_name,
                suppliers.supplier_title as suppliers_title,
                item_condition.condition AS itemconditionname,
                sites.id AS siteid, 
                sites.name AS sitename,
                itemstatus.id AS itemstatusid,
                itemstatus.name AS itemstatusname,audititems.item_id as audititem,audititems.audit_id');

                //suppliers.supplier_id as supplier,
                //   suppliers.supplier_title as suppliers_title,


                $this->db->from('items');
                $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
                $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
                //             JOIN for Item condition

                $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');
//           ***********************
                $this->db->join('owner', 'items.owner_now = owner.id', 'left');
                $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');

//                $this->db->join('users', 'items.owner_now = users.id', 'left');
                $this->db->join('photos', 'items.photo_id = photos.id', 'left');
                $this->db->join('locations', 'items.location_now = locations.id', 'left');
                $this->db->join('sites', 'items.site = sites.id', 'left');
                $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
                $this->db->join('suppliers', 'suppliers.supplier_id = items.supplier', 'left');
                $this->db->join('audititems', 'audititems.item_id = items.id', 'left');

                $this->db->where('items.account_id', $intAccountId);
                $this->db->where('items.active', 1);


                if ($strbarcode != -1) {
                    $this->db->where('items.barcode', $strbarcode);
                }
                if ($this->session->userdata('is_supplier')) {
                    $this->db->where('items.supplier', $this->session->userdata('is_supplier'));
                }

                if ($intSite != -1) {
                    $this->db->where('sites.id', $intSite);
                }
                if ($intUser != -1) {
                    $this->db->where('owner.id', $intUser);
                }
                if ($intLocation != -1) {
                    $this->db->where('locations.id', $intLocation);
                }
                if ($strManufacturer != "") {
                    $this->db->where('items.manufacturer', $strManufacturer);
                }
                if ($stritem_manu != -1) {
                    $this->db->where('item_manu.id', $stritem_manu);
                }

                // Add Category Filter             
                if ($intCategory != -1) {
                    $this->db->where('categories.id', $intCategory);
                }
                $this->db->group_by('items.id');
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
        }
        return false;
    }

    public function searchVehicles($intAccountId = -1, $strMake = "", $intSite = -1, $intUser = -1, $reg_no = null, $intLocation = -1) {

        if ($intAccountId > 0) {

            $this->db->select('
                fleet.fleet_id AS fleetid, fleet.make, fleet.model, fleet.barcode, fleet.reg_no');

            $this->db->from('fleet');
            $this->db->where('fleet.account_id', $intAccountId);
            $this->db->where('fleet.active', 1);

            if ($intSite != -1) {
                $this->db->where('fleet.site_now', $intSite);
            }
            if ($intUser != -1) {
                $this->db->where('fleet.owner_now', $intUser);
            }
            if ($intLocation != -1) {
                $this->db->where('fleet.location_now', $intLocation);
            }
            if ($strMake != -1) {
                $this->db->where('fleet.make', $strMake);
            }
            if ($reg_no != null) {
                $this->db->where('fleet.reg_no', $reg_no);
            }

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
        return false;
    }

    public function checkBarcodeUnique($strBarcode) {
        $this->db->select('items.id AS itemid');
        $this->db->from('items');
        $this->db->where('items.barcode', $strBarcode);
        $resQuery = $this->db->get();
        if ($resQuery->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

}

?>
