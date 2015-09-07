<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */

class Items_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getBarcodeForId($intItemId = -1, $intAccountId = -1) {
        if (($intItemId > 0) && ($intAccountId > 0)) {
            $this->db->select('items.barcode');
            $this->db->from('items');
            $this->db->where('items.id', $intItemId);
            $this->db->where('items.account_id', $intAccountId);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            }
        }
        return false;
    }

    public function basicGetOne($intItemId = -1, $intAccountId = -1) {
        if (($intItemId > 0) && ($intAccountId > 0)) {
            $this->db->select('
                        items.id AS itemid,
                        items.manufacturer,
                        items.item_manu,
                        items.model, 
                        items.serial_number, 
                        items.condition_now,
                        item_condition.condition AS condition_name,
                        items.barcode, 
                        items.photo_id,
                        items.owner_now, 
                        items.owner_since, 
                        items.site AS siteid,
                        items.location_now, 
                        items.location_since,
                        items.value,
                        items.current_value, 
                        items.notes,
                        items.status_id,
                        items.warranty_date,
                        items.purchase_date, 
                        items.replace_date,
                        items.added_date,
                        items.pattest_date, 
                        pat.pattest_name AS pat_status,
                        items.pattest_status,
                        items.mark_deleted, 
                        items.mark_deleted_2, 
                        items.mark_deleted_date,
                        items.mark_deleted_2_date, 
                        items.active,
                        items.deleted_date,
                        items.compliance_start,
                        items.quantity,
                        item_manu.doc as pdf_name,
                         photos.id AS itemphotoid,
                        photos.title AS itemphototitle,
                        photos.path AS photopath,
                        itemstatus.id AS itemstatusid,
                        itemstatus.name AS itemstatusname,
                        categories.id AS categoryid, 
                        categories.name AS categoryname, 
                        categories.default AS categorydefault, 
                        categories.icon AS categoryicon, 
                        categories.support_emails AS support_emails, 
                        categories.quantity_enabled,
                        owner.id AS userid,
                        item_manu.item_manu_name,
                        locations.id AS locationid,
                        locations.name AS locationname,
                        sites.id AS siteid,
                        sites.name AS sitename,
                        items.supplier,
                        suppliers.supplier_id AS supplier,
                        suppliers.supplier_name,
                        suppliers.supplier_title AS suppliers_title');
            $this->db->from('items');
//            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');
            $this->db->join('photos', 'photos.id = items.photo_id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('pat', 'items.pattest_status = pat.id', 'left');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');
            $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
            $this->db->where('items.id', $intItemId);
            $this->db->where('items.account_id', $intAccountId);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            }
        } else {
            return false;
        }
    }

    public function getTotalNumberOfItems($intAccountId = -1) {
        if ($intAccountId > 0) {
            $this->db->select('COUNT(*) AS total_items');
            $this->db->from('items');
            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.active', 1);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function basicGetOneByBarcode($strBarcode = "", $intAccountId = -1) {
        if (($strBarcode != "") && ($intAccountId > 0)) {
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
                         item_manu.doc as pdf_name,
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
                        suppliers.supplier_id AS supplier,
                        suppliers.supplier_name,
                        suppliers.supplier_title AS suppliers_title');
            $this->db->from('items');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');

//            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('photos', 'photos.id = items.photo_id', 'left');
//             JOIN for Item condition
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');

            $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');
//           ***********************
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('suppliers', 'suppliers.supplier_id = items.supplier', 'left');
            $this->db->where('items.barcode', $strBarcode);
            $this->db->where('items.account_id', $intAccountId);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getOneFromBarcode($strBarcode = "", $intAccountId = -1) {
        if (($strBarcode != "") && ($intAccountId > 0)) {
            $this->db->select('
                        items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode, items.owner_now, items.owner_since, items.site, items.location_now, items.location_since, items.value, items.quantity
                        categories.id AS categoryid, categories.name AS categoryname,
                        users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                        locations.id AS locationid, locations.name AS locationname,
                        photos.id AS itemphotoid, photos.title AS itemphototitle,
                        sites.id AS siteid, sites.name AS sitename');
            $this->db->from('items');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('photos', 'photos.id = items.photo_id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->where('items.barcode', $strBarcode);
            $this->db->where('items.account_id', $intAccountId);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            }
        } else {
            return false;
        }
    }

    public function getHistory($intItemId = -1) {
        $arrItemHistory = array();
        if (($intItemId > 0)) {

            $arrItemUserHistory = $this->getUserHistory($intItemId);
            $arrItemLocationHistory = $this->getLocationHistory($intItemId);
            $arrItemFacultyHistory = $this->getFacultyHistory($intItemId);
            $arrItemAuditHistory = $this->getAuditHistory($intItemId);

            foreach ($arrItemUserHistory as $objHistory) {
                $arrItemHistory[$objHistory->date]['user'] = $objHistory;
            }
            foreach ($arrItemLocationHistory as $objHistory) {
                $arrItemHistory[$objHistory->date]['location'] = $objHistory;
            }
            foreach ($arrItemFacultyHistory as $objHistory) {
                $arrItemHistory[$objHistory->date]['site'] = $objHistory;
            }
            foreach ($arrItemAuditHistory as $objHistory) {
                $arrItemHistory[$objHistory->date]['audit'] = $objHistory;
            }
            ksort($arrItemHistory);
            $arrItemHistory = array_reverse($arrItemHistory);
        }

        return $arrItemHistory;
    }

    public function getAuditHistory($intItemId) {
        $arrResults = array();
        $this->db->select('audits.completed as date, audititems.present,
                            users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                            locations.name');
        $this->db->from('audititems');
        $this->db->join('audits', 'audititems.audit_id = audits.id', 'left');
        $this->db->join('users', 'audits.user_id = users.id', 'left');
        $this->db->join('locations', 'audits.location_id = locations.id', 'left');
        $this->db->where('audititems.item_id', $intItemId);
        $this->db->order_by('audits.completed ASC');
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrResults[] = $objRow;
            }
        }

        return $arrResults;
    }

    public function getUserHistory($intItemId) {
        $arrResults = array();
        $this->db->select('items_users_link.date,owner.owner_name,
                            users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname');
        $this->db->from('items_users_link');
        $this->db->join('users', 'items_users_link.user_id = users.id', 'left');
        $this->db->join('items', 'items_users_link.item_id = items.id', 'left');
        $this->db->join('owner', 'items.owner_now = owner.id', 'left');

        $this->db->where('items_users_link.item_id', $intItemId);
        $this->db->order_by('items_users_link.date ASC');
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrResults[] = $objRow;
            }
        }
        return $arrResults;
    }

    public function getLocationHistory($intItemId) {
        $arrResults = array();
        $this->db->select('items_locations_link.date,
                            locations.id AS locationid, locations.name AS locationname');
        $this->db->from('items_locations_link');
        $this->db->join('locations', 'items_locations_link.location_id = locations.id', 'left');
        $this->db->where('items_locations_link.item_id', $intItemId);
        $this->db->order_by('items_locations_link.date ASC');
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrResults[] = $objRow;
            }
        }
        return $arrResults;
    }

    public function getFacultyHistory($intItemId) {
        $arrResults = array();
        $this->db->select('items_sites_link.date,
                            sites.id AS siteid, sites.name AS sitename');
        $this->db->from('items_sites_link');
        $this->db->join('sites', 'items_sites_link.site_id = sites.id', 'left');
        $this->db->where('items_sites_link.item_id', $intItemId);
        $this->db->order_by('items_sites_link.date ASC');
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrResults[] = $objRow;
            }
        }
        return $arrResults;
    }

    /* public function getOne($intItemId = -1, $intAccountId = -1)
      {

      if (($intItemId >0) && ($intAccountId >0))
      {

      $this->db->select('items_users_link.date,
      items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode,
      categories.id AS categoryid, categories.name AS categoryname,
      users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
      locations.id AS locationid, locations.name AS locationname');
      // we need to do a sub query, this
      $this->db->from('(
      ( 	SELECT
      max(`date`) as most_recent_date_for_user
      FROM
      items_users_link
      WHERE
      item_id = '.$intItemId.'
      ) q2,
      (  	SELECT
      max(`date`) as most_recent_date_for_location
      FROM
      items_locations_link
      WHERE
      item_id = '.$intItemId.'
      ) q3
      )');
      $this->db->join('items_users_link', 'items_users_link.date = most_recent_date_for_user', 'left');
      $this->db->join('items_locations_link', 'items_locations_link.date = most_recent_date_for_location', 'left');
      $this->db->join('items', 'items_users_link.item_id = items.id', 'left');
      $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
      $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
      $this->db->join('users', 'items_users_link.user_id = users.id', 'left');
      $this->db->join('locations', 'items_locations_link.location_id = locations.id', 'left');
      $this->db->where('items_users_link.item_id', $intItemId);
      $this->db->where('users.account_id', $intAccountId);
      $resQuery = $this->db->get();
      //print $this->db->last_query();
      if ($resQuery->num_rows() > 0)
      {
      return $resQuery->result();
      }
      }
      return false;
      } */

    public function getOne($intItemId = -1, $intAccountId = -1) {
        if (($intItemId > 0) && ($intAccountId > 0)) {
            $this->db->select('items.owner_since AS \'date\',
			      items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode,
			      categories.id AS categoryid, categories.name AS categoryname,categories.support_emails AS categoryemail,
			      users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
			      locations.id AS locationid, locations.name AS locationname,
                              sites.id AS siteid, sites.name AS sitename');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->where('items.id', $intItemId);
            $this->db->where('users.account_id', $intAccountId);

            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            }
        }
        return false;
    }

    public function getAllForAccount($intAccountId = -1, $strMode = "ByCategory", $strOrder = "Asc", $intPage = 1, $intLimit = 10) {
        if ($intAccountId > 0) {
            switch ($strMode) {
                case "ByBarcode":
                    return $this->getAllForAccountBy($intAccountId, "barcode", $strOrder, $intPage, $intLimit);
                    break;
                case "ByCategory":
                    return $this->getAllForAccountBy($intAccountId, "category", $strOrder, $intPage, $intLimit);
                    break;
                case "ByLocation":
                    return $this->getAllForAccountBy($intAccountId, "location", $strOrder, $intPage, $intLimit);
                    break;
                case "ByManufacturer":
                    return $this->getAllForAccountBy($intAccountId, "manufacturer", $strOrder, $intPage, $intLimit);
                    break;
                case "ByUser":
                    return $this->getAllForAccountBy($intAccountId, "user", $strOrder, $intPage, $intLimit);
                    break;
                case "ByFaculty":
                    return $this->getAllForAccountBy($intAccountId, "site", $strOrder, $intPage, $intLimit);
                    break;
                case "ByValue":
                    return $this->getAllForAccountBy($intAccountId, "value", $strOrder, $intPage, $intLimit);
                    break;
                default:
                    return false;
            }
        }
        return false;
    }

    public function getAllForAccountBy($intAccountId = -1, $strMode, $strOrder, $intPage, $intLimit) {
        if ($intAccountId > 0) {
            $this->db->select('
                items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id,
		categories.id AS categoryid, categories.name AS categoryname,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                photos.id AS userphotoid, photos.title AS userphototitle,
                photos2.id AS itemphotoid, photos2.title AS itemphototitle,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                itemstatus.name AS statusname');

            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('photos', 'users.photo_id = photos.id', 'left');
            $this->db->join('photos AS photos2', 'items.photo_id = photos2.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->where('items.account_id', $intAccountId);

            $strOrderDirection = "ASC";
            if ($strOrder == "Desc") {
                $strOrderDirection = "DESC";
            }

            switch ($strMode) {
                case "barcode":
                    $this->db->order_by('items.barcode ' . $strOrderDirection);
                    break;
                case "category":
                    $this->db->order_by('categories.name ' . $strOrderDirection . ', items.manufacturer ASC, items.model ASC, items.barcode ASC');
                    break;
                case "location":
                    $this->db->order_by('locations.name ' . $strOrderDirection . ', categories.name ASC, items.manufacturer ASC, items.model ASC');
                    break;
                case "manufacturer":
                    $this->db->order_by('items.manufacturer ' . $strOrderDirection . ', items.model ASC, items.barcode ASC');
                    break;
                case "user":
                    $this->db->order_by('users.lastname ' . $strOrderDirection . ', users.firstname, items.manufacturer ASC, items.model ASC, items.barcode ASC');
                    break;
                case "site":
                    $this->db->order_by('sites.name ' . $strOrderDirection . ', items.manufacturer ASC, items.model ASC, items.barcode ASC');
                    break;
                case "value":
                    $this->db->order_by('items.value ' . $strOrderDirection . ', items.manufacturer ASC, items.model ASC, items.barcode ASC');
                    break;
            }

            switch ($intPage) {
                case 1:
                    $this->db->limit($intLimit, 0);
                    break;
                default:
                    $this->db->limit($intLimit, (($intPage - 1) * $intLimit));
            }

            $resQuery = $this->db->get();
            /*
              print_r($this->db->last_query());
              die(); */
            if ($resQuery->num_rows() > 0) {
                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[] = $objRow;
                }
                return array('results' => $arrItemsData);
            } else {
                return array();
            }
        }
        return false;
    }

    public function getFiveNewestItemsFor($intAccountId = -1) {
        if ($intAccountId > 0) {
            $this->db->select('items.id AS id,items.item_manu, items.barcode AS barcode, items.manufacturer AS manufacturer, items.model AS model, 
                                    categories.name AS categoryname,locations.name as locationname,
                                    photos2.id AS itemphotoid, photos2.title AS itemphototitle');
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('photos AS photos2', 'items.photo_id = photos2.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');

            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.active', 1);

            $this->db->order_by('items.added_date', 'DESC');
            $this->db->order_by('items.id', 'DESC');
            $this->db->limit(5, 0);

            $resQuery = $this->db->get();
            $intCount = 0;

            if ($resQuery->num_rows() > 0) {
                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[] = $objRow;
                }
                return $arrItemsData;
            }
            return array();
        }
        return false;
    }

    public function countNumberForAccount($intAccountId = -1, $booActive = false) {
        if ($intAccountId > 0) {
            $this->db->select('COUNT(*) AS number_of_items');
            $this->db->from('items');
            $this->db->where('items.account_id', $intAccountId);

            if ($booActive) {
                $this->db->where('items.active', 1);
            }
            $resQuery = $this->db->get();
            $intCount = 0;

            if ($resQuery->num_rows() > 0) {
                foreach ($resQuery->result() as $objRow) {
                    $intCount = $objRow->number_of_items;
                }
            }
            return $intCount;
        }
        return false;
    }

    public function countNumberForUser($intUserId = -1, $intAccountId = -1) {
        $intCount = 0;
        if (($intUserId > 0) && ($intAccountId > 0)) {
            $this->db->select('COUNT(*) AS number_of_items');
            $this->db->from('items');
            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.owner_now', $intUserId);
            $resQuery = $this->db->get();


            if ($resQuery->num_rows() > 0) {
                foreach ($resQuery->result() as $objRow) {
                    $intCount = $objRow->number_of_items;
                }
            }
            return $intCount;
        }
        return false;
    }

    public function getAll($intAccountId = -1) {
        if ($intAccountId > 0) {

            $this->db->select('
                items.id AS itemid, items.manufacturer,items.item_manu ,items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.current_value, items.purchase_date,items.status_id, items.compliance_start, items.quantity,
		categories.id AS categoryid, categories.name AS categoryname, categories.default AS categorydefault, categories.icon AS categoryicon,item_condition.condition AS condition_name,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,owner.owner_name,
                photos.id AS userphotoid, photos.title AS userphototitle,
                photos2.id AS itemphotoid, photos2.title AS itemphototitle,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                pat.pattest_name AS pat_status,
                itemstatus.name AS statusname,
                suppliers.supplier_name,
                suppliers.supplier_title AS suppliers_title');


            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');
            $this->db->join('photos', 'users.photo_id = photos.id', 'left');
            $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');

            $this->db->join('photos AS photos2', 'items.photo_id = photos2.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
//            $this->db->join('items_pat_link', 'items.pattest_status = items_pat_link.pattest_status', 'left');
            $this->db->join('pat', 'items.pattest_status = pat.id', 'left');

            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.active', 1);

            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {

                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[] = $objRow;
                }
                return array('results' => $arrItemsData);
            } else {
                return array();
            }
        }
        return false;
    }

    public function getTableAndFieldForFilterField($strField) {
        switch ($strField) {
            case "barcode":
                return "items.barcode";
            case "serial":
                return "items.serial_number";
            case "current_value":
                return "items.current_value";
            case "manufacturer":
                return "items.manufacturer";
            case "manu_model":
                return "items.manufacturer";
            case "model":
                return "items.model";
            case "value":
                return "items.value";
            case "categoryname":
                return "categories.name";
            case "sitename":
                return "sites.name";
            case "locationid":
                return "locations.id";
            case "locationname":
                return "locations.id";
            case "userid":
                return "users.id";
            case "owner":
                return "users.id";
            case "itemstatusid":
                return "items.status_id";
            case "statusname":
                return "items.status_id";
            case "photoid":
                return "items.photo_id";
            case "pat_status":
                return "pat.pattest_name";
            case "patid":
                return "items.pattest_status";
            case "conid":
                return "items.condition_now";
            case "item_manu":
                return "items.item_manu";
            case "itemmanuname":
                return "items.item_manu";
            case "condition_name":
                return "items.condition_now";
            default:
                return false;
        }
    }

    public function getAllForAccountRestrictedBy($intAccountId = -1, $arrRestrictBy = array()) {
        if ($intAccountId > 0) {
            $this->db->select('
			      items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value,
			      categories.id AS categoryid, categories.name AS categoryname,
                              itemstatus.name AS statusname,
			      users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                              photos.id AS userphotoid, photos.title AS userphototitle,
                                photos2.id AS itemphotoid, photos2.title AS itemphototitle,
			      locations.id AS locationid, locations.name AS locationname,
                            sites.id AS siteid, sites.name AS sitename');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('photos', 'users.photo_id = photos.id', 'left');
            $this->db->join('photos AS photos2', 'items.photo_id = photos2.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');

            $this->db->where('items.account_id', $intAccountId);

            if (array_key_exists('user_id', $arrRestrictBy) && ($arrRestrictBy['user_id'] > 0)) {
                $this->db->where('users.id', $arrRestrictBy['user_id']);
            }
            if (array_key_exists('category_id', $arrRestrictBy) && ($arrRestrictBy['category_id'] > 0)) {
                $this->db->where('categories.id', $arrRestrictBy['category_id']);
            }
            if (array_key_exists('location_id', $arrRestrictBy) && ($arrRestrictBy['location_id'] > 0)) {
                $this->db->where('locations.id', $arrRestrictBy['location_id']);
            }
            if (array_key_exists('site_id', $arrRestrictBy) && ($arrRestrictBy['site_id'] != "-1")) {
                $this->db->where('items.site', $arrRestrictBy['site_id']);
            }
            if (array_key_exists('manufacturer', $arrRestrictBy) && ($arrRestrictBy['manufacturer'] != "-1")) {
                $this->db->where('items.manufacturer', $arrRestrictBy['manufacturer']);
            }

            $this->db->order_by('items.barcode ASC');

            $resQuery = $this->db->get();

            /* print_r($this->db->last_query());
              die(); */
            if ($resQuery->num_rows() > 0) {
                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[] = $objRow;
                }
                return array('results' => $arrItemsData);
            } else {
                return array();
            }
        }
        return false;
    }

    public function getAllForAccountByCategory($intAccountId = -1) {
        if ($intAccountId > 0) {
            $this->db->select('items_users_link.date,
			      items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode,
			      categories.id AS categoryid, categories.name AS categoryname,
			      users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname');
            // we need to do a sub query, this
            $this->db->from('( 
				SELECT 
				    max(`date`) as most_recent_date 
				FROM 
				    items_users_link 
				GROUP BY  
				    item_id 
				) q2');
            $this->db->join('items_users_link', 'items_users_link.date = most_recent_date');
            $this->db->join('items', 'items_users_link.item_id = items.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items_users_link.user_id = users.id', 'left');
            $this->db->where('users.account_id', $intAccountId);
            $this->db->group_by(array("categories.id", "items.manufacturer", "items.model"));
            $this->db->order_by('categories.name ASC, items.manufacturer ASC, items.model ASC, items.barcode ASC');
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[$objRow->categoryid]['categoryname'] = $objRow->categoryname;
                    $arrItemsData[$objRow->categoryid]['items'][] = $objRow;
                }
                return array('results' => $arrItemsData);
            } else {
                return array();
            }
        }
        return false;
    }

    public function getAllForThisUser($intUserId = -1) {
        if ($intUserId > 0) {
            $this->db->select('items_users_link.date,
			      items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode,
			      categories.id AS categoryid, categories.name AS categoryname');
            // we need to do a sub query, this
            $this->db->from('( 
				SELECT 
				    max(`date`) as most_recent_date 
				FROM 
				    items_users_link 
				GROUP BY  
				    item_id 
				) q2');
            $this->db->join('items_users_link', 'items_users_link.date = most_recent_date');
            $this->db->join('items', 'items_users_link.item_id = items.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->where('user_id', $intUserId);
            $this->db->group_by(array("categories.id", "items.manufacturer", "items.model"));
            $this->db->order_by('categories.name ASC, items.manufacturer ASC, items.model ASC, items.barcode ASC');

            // Do it
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[] = $objRow;
                }
                return array('results' => $arrItemsData);
            } else {
                return array();
            }
        }
        return false;
    }

    public function editOne($arrInput = array(), $intId = -1, $intCategoryId = -1, $intLocationId = -1, $intUserId = -1, $intFacultyId = -1) {
        if (($intId > 0) && ($intCategoryId > 0) && (($intLocationId > 0) || ($intFacultyId > 0) || ($intUserId > 0))) {

            $this->db->where('id', $intId);
            $this->db->update('items', $arrInput);

            if (($intLocationId > 0) && ($this->whereIsThis($intId) != $intLocationId)) {
                $this->linkThisToLocation($intId, $intLocationId);
            } else {
                if ($intLocationId == 0) {
                    $this->clearCurrentLocation($intId);
                }
            }

            if (($intUserId > 0) && ($this->whoOwnsThis($intId) != $intUserId)) {
                $this->linkThisToUser($intId, $intUserId);
            } else {
                if ($intUserId == 0) {
                    $this->clearCurrentUser($intId);
                }
            }

            if (($intFacultyId > 0) && ($this->whichFacultyIsThis($intId) != $intFacultyId)) {
                $this->linkThisToSite($intId, $intFacultyId);
            } else {
                if ($intFacultyId == 0) {
                    $this->clearCurrentSite($intId);
                }
            }

            $this->linkThisToCategory($intId, $intCategoryId);
            if ($arrInput['owner_now'] > 0) {
                $this->linkToOwner($intId, $arrInput['owner_now']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function hasSite($intId) {
        $this->db->select('items.site');
        $this->db->from('items');
        $this->db->where('id', $intId);

        $resQuery = $this->db->get();

        if (($resQuery->num_rows() > 0) && ($resQuery->row()->site > 0)) {
            return true;
        }
        return false;
    }

    public function clearCurrentSite($intId) {
        $this->db->where('id', $intId);
        $this->db->update('items', array('site' => 0, 'site_since' => date('Y-m-d H:i:s')));
    }

    public function clearCurrentUser($intId) {
        $this->db->where('id', $intId);
        $this->db->update('items', array('owner_now' => 0, 'owner_since' => date('Y-m-d H:i:s')));
    }

    public function clearCurrentLocation($intId) {
        $this->db->where('id', $intId);
        $this->db->update('items', array('location_now' => 0, 'location_since' => date('Y-m-d H:i:s')));
    }

    public function addOne($arrInput = array(), $intCategoryId = -1, $intLocationId = -1, $intUserId = -1, $intFacultyId = -1) {

        if ((count($arrInput) > 0) && ($intCategoryId > 0) && ((($intLocationId > 0) || ($intUserId > 0)) || ($intFacultyId > 0))) {

            $strDate = date('Y-m-d H:i:s');

            if ($arrInput['pattest_status'] == -1) {
                $arrInput['pattest_status'] = null;
            }

            $arrInput['added_date'] = $strDate;
            $this->db->insert('items', $arrInput);
            $intItemId = $this->db->insert_id();
            $this->linkThisToCategory($intItemId, $intCategoryId);
            if ($intLocationId > 0) {
                $this->linkThisToLocation($intItemId, $intLocationId);
            }
            if ($intUserId > 0) {
                $this->linkThisToUser($intItemId, $intUserId);
            }
            if ($intFacultyId > 0) {
                $this->linkThisToSite($intItemId, $intFacultyId);
            }
            if ($arrInput['owner_now'] > 0) {
                $this->linkToOwner($intItemId, $arrInput['owner_now']);
            }
            return $intItemId;
        }

        return false;
    }

//    Add Condition With assets
    public function linkToOwner($intItemId = -1, $owner_id = -1) {
        $this->db->insert('item_owner_history_link', array('item_id' => $intItemId, 'owner_id' => $owner_id, 'date' => date('Y-m-d H:i:s'), 'logged_by' => $this->session->userdata('objSystemUser')->userid));
    }

//    Add Condition With assets
    public function linkThisToCondition($intItemId = -1, $condition_id = -1) {
        $this->db->insert('item_condition_history_link', array('item_id' => $intItemId, 'condition_id' => $condition_id, 'date' => date('Y-m-d H:i:s'), 'logged_by' => $this->session->userdata('objSystemUser')->userid));
    }

    public function linkThisToLocation($intItemId = -1, $intLocationId = -1) {

        if (($intItemId > 0) && ($intLocationId > 0)) {
            $this->db->insert('items_locations_link', array('item_id' => $intItemId, 'location_id' => $intLocationId, 'date' => date('Y-m-d H:i:s'), 'logged_by' => $this->session->userdata('objSystemUser')->userid));
            $this->db->where('id', $intItemId);
            $this->db->update('items', array('location_now' => $intLocationId, 'location_since' => date('Y-m-d H:i:s')));
        }
    }

    public function getCategoryFor($intItemId = -1) {
        if ($intItemId > 0) {
            // is this id linked?
            $resQuery = $this->db->get_where('items_categories_link', array('item_id' => $intItemId));
            if ($resQuery->num_rows != 0) {
                //return the id
                return $resQuery->row()->category_id;
            }
        }
        return false;
    }

    public function linkThisToCategory($intItemId = -1, $intCategoryId = -1) {
        //check whether already in a category
        $mixCurrentCategory = $this->getCategoryFor($intItemId);
        if ($mixCurrentCategory) {
            // they are, so unlink them
            $this->unlinkThisFromCategories($intItemId);
        }
        //create the new relationship
        $this->db->insert('items_categories_link', array('item_id' => $intItemId, 'category_id' => $intCategoryId));
    }

    public function unlinkThisFromCategories($intItemId = -1) {
        if ($intItemId > 0) {
            // UNLINK
            $this->db->delete('items_categories_link', array('item_id' => $intItemId));
        }
    }

    public function linkThisToUser($intItemId = -1, $intUserId = -1) {
        if (($intItemId > 0) && ($intUserId > 0)) {
            $this->db->insert('items_users_link', array('item_id' => $intItemId, 'user_id' => $intUserId, 'date' => date('Y-m-d H:i:s')));
//            $this->db->where('id', $intItemId);
//            $this->db->update('items', array('owner_now' => $intUserId, 'owner_since' => date('Y-m-d H:i:s')));
        }
    }

    public function linkThisToSite($intItemId = -1, $intFacultyId = -1) {
        if (($intItemId > 0) && ($intFacultyId > 0)) {
            $this->db->insert('items_sites_link', array('item_id' => $intItemId, 'site_id' => $intFacultyId, 'date' => date('Y-m-d H:i:s')));
            $this->db->where('id', $intItemId);
            $this->db->update('items', array('site' => $intFacultyId, 'site_since' => date('Y-m-d H:i:s')));
        }
    }

    public function whichFacultyIsThis($intItemId = -1) {


        if ($intItemId > 0) {
            $query = $this->db->query("SELECT max(`date`) as most_recent_date FROM items_sites_link  WHERE `items_sites_link`.`item_id` = " . $intItemId);
//       $arr = array();
            $arr = $query->row_array();
//        var_dump($arr);die;
            $this->db->select('items_sites_link.date,
			      items.id AS itemid,
			      sites.id AS siteid');
            // we need to do a sub query, this
            $this->db->from(' items_sites_link');
//            $this->db->join('items_sites_link', 'items_sites_link.date = '.$arr['most_recent_date']);
            $this->db->join('items', 'items_sites_link.item_id = items.id', 'left');
            $this->db->join('sites', 'items_sites_link.site_id = sites.id', 'left');
            $this->db->where('items_sites_link.item_id', $intItemId);
            $this->db->where('items_sites_link.date', $arr['most_recent_date']);
            $resQuery = $this->db->get();
//            print_r($this->db->last_query());die;
//            var_dump($resQuery->row()->siteid);die;
            if ($resQuery->num_rows() > 0) {
                return $resQuery->row()->siteid;
            }
        }
        return false;
    }

    public function whoOwnsThis($intItemId = -1) {


        if ($intItemId > 0) {

            $query = $this->db->query("SELECT max(`date`) as most_recent_date FROM items_users_link Where `items_users_link`.`item_id` = " . $intItemId);

            $arr = $query->row_array();
            $this->db->select('items_users_link.date,
			      items.id AS itemid,
			      users.id AS userid');
            // we need to do a sub query, this
            $this->db->from('items_users_link ');
//            $this->db->join('items_users_link', 'items_users_link.date = most_recent_date');
            $this->db->join('items', 'items_users_link.item_id = items.id', 'left');
            $this->db->join('users', 'items_users_link.user_id = users.id', 'left');
            $this->db->where('items_users_link.item_id', $intItemId);
            $this->db->where('items_users_link.date', $arr['most_recent_date']);
            $resQuery = $this->db->get();
//             print_r($this->db->last_query());die;
//            var_dump($resQuery->row()->userid);die;
            if ($resQuery->num_rows() > 0) {
                return $resQuery->row()->userid;
            }
        }
        return false;
    }

    public function whereIsThis($intItemId = -1) {


        if ($intItemId > 0) {
            $query = $this->db->query("SELECT 
				    max(`date`) as most_recent_date 
				FROM 
				    items_locations_link  Where `items_locations_link`.`item_id` = " . $intItemId);
            $arr = $query->row_array();


            $this->db->select('items_locations_link.date,
			      items.id AS itemid,
			      locations.id AS locationid');
            // we need to do a sub query, this
            $this->db->from('items_locations_link');
//            $this->db->join('items_locations_link', 'items_locations_link.date = most_recent_date');
            $this->db->join('items', 'items_locations_link.item_id = items.id', 'left');
            $this->db->join('locations', 'items_locations_link.location_id = locations.id', 'left');
            $this->db->where('items_locations_link.item_id', $intItemId);
            $this->db->where('items_locations_link.date', $arr['most_recent_date']);
            $resQuery = $this->db->get();
//             var_dump($resQuery->row()->locationid);die;
            if ($resQuery->num_rows() > 0) {
                return $resQuery->row()->locationid;
            }
        }
        return false;
    }

    public function editPATResult($intItemId = -1, $strPATDate = null, $intPATResult = null) {
        if (($intItemId >= 0) && ($strPATDate != null) && ($intPATResult != null)) {
            $this->db->where('id', (int) $intItemId);
            $this->db->update('items', array('pattest_date' => $strPATDate, 'pattest_status' => $intPATResult));
            return true;
        } else {
            return false;
        }
    }

    public function linkThisToPat($intItemId = -1, $intPatId = NULL, $user_id = -1) {

        $this->db->insert('items_pat_link', array('item_id' => $intItemId, 'pattest_status' => (int) $intPatId, 'user_id' => $user_id, 'date' => date('Y-m-d H:i:s')));
    }

    public function getPatHistory($item_id) {
        $this->db->select('*');
        $this->db->from('items_pat_link');
        $this->db->join('users', 'items_pat_link.user_id = users.id', 'left');
        $this->db->join('pat', 'items_pat_link.pattest_status = pat.id', 'left');
        $this->db->where('items_pat_link.item_id', $item_id);
        $resQuery = $this->db->get();
        if ($resQuery->num_rows() > 0) {
            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow) {

                $arrItemsData[] = $objRow;
            }
            return array('results' => $arrItemsData);
        } else {
            return array();
        }
    }

    public function setPhoto($intId = -1, $intPhotoId = '') {
        if (($intId > 0)) {
            $this->db->where('id', (int) $intId);
            return $this->db->update('items', array('photo_id' => $intPhotoId));
        }
        return false;
    }

    public function listManufacturers($intAccountId = -1) {
        if ($intAccountId > 0) {
            $this->db->select('manufacturer');
            $this->db->distinct();
            $this->db->from('items');
            $this->db->where('account_id', $intAccountId);
            $this->db->where('active', 1);
            $this->db->order_by('manufacturer', 'ASC');
            $resQuery = $this->db->get();

            //print_r($this->db->last_query());
            //die();

            if ($resQuery->num_rows() > 0) {
                $arrManufacturers = array();

                foreach ($resQuery->result() as $objRow) {
                    $arrManufacturers[] = $objRow->manufacturer;
                }

                return $arrManufacturers;
            }
        }
        return false;
    }

    public function getCommonItemsFor($intAccountId) {
        if ($intAccountId > 0) {

            $resQuery = $this->db->query("SELECT 
                                                    CONCAT(items.manufacturer,' ',items.model) as itemname,
                                                    COUNT(*) as count,
                                                    items.manufacturer,
                                                    items.model,
                                                    items.item_manu,
                                                    categories.name AS category_name
                                            FROM 
                                                    items
                                                   LEFT JOIN items_categories_link
                                                    ON items.id = items_categories_link.item_id
                                                   LEFT JOIN categories
                                                    ON items_categories_link.category_id = categories.id
                                                    
                                            WHERE
                                                    items.account_id=" . $intAccountId . " AND items.deleted_date IS NULL
                                            GROUP BY 
                                                    itemname 
                                            ORDER BY 
                                                    count DESC, itemname ASC
                                            LIMIT
                                                    5");

            if ($resQuery->num_rows() > 0) {
                $arrManufacturers = array();

                foreach ($resQuery->result() as $objRow) {
                    $arrManufacturers[] = $objRow;
                }

                return $arrManufacturers;
            }
        }
        return false;
    }

//    public function markDeleted($intItemId, $intAccountId, $intStatusId, $intUserId, $booIsSuperAdmin) {
//        $strDate = date('Y-m-d H:i:s');
//        $arrData = array(
//            'active' => 0,
//            'deleted_date' => $strDate,
//            'status_id' => (int) $intStatusId
//        );
//        if ($booIsSuperAdmin) {
//            $arrData['mark_deleted_2'] = (int) $intUserId;
//            $arrData['mark_deleted_2_date'] = $strDate;
//        } else {
//            $arrData['mark_deleted'] = (int) $intUserId;
//            $arrData['mark_deleted_date'] = $strDate;
//        }
//        $this->db->where('id', (int) $intItemId);
//        $this->db->where('account_id', (int) $intAccountId);
//        $this->db->update('items', $arrData);
//    }

    public function markDeleted($intItemId, $intAccountId, $intStatusId, $intUserId, $booIsSuperAdmin, $reason, $payment, $net, $status) {
        $strDate = date('Y-m-d H:i:s');
        $arrData = array(
            'active' => 0,
            'deleted_date' => $strDate,
            'status_id' => (int) $intStatusId
        );
        if ($booIsSuperAdmin) {
            $arrData['mark_deleted_2'] = (int) $intUserId;
            $arrData['mark_deleted_2_date'] = $strDate;
        } else {
            $arrData['mark_deleted'] = (int) $intUserId;
            $arrData['mark_deleted_date'] = $strDate;
        }
        $this->db->where('id', (int) $intItemId);
        $this->db->where('account_id', (int) $intAccountId);
        $this->db->update('items', $arrData);
        $check = $this->db->where('item_id', $intItemId)->get('items_reason_link');
        if ($check->num_rows() > 0)
            $this->db->where('item_id', $intItemId)->update('items_reason_link', array('item_id' => $intItemId, 'reason_id' => $reason, 'payment' => $payment, 'net_gain_loss' => $net, 'method' => $status));
        else
            $this->db->insert('items_reason_link', array('item_id' => $intItemId, 'reason_id' => $reason, 'payment' => $payment, 'net_gain_loss' => $net, 'method' => $status));
    }

    public function getAwaitingDeletion($intAccountId, $intUserId, $intLevelId) {
        if ($intAccountId > 0) {
            $this->db->select('
                items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id, items.mark_deleted_date, items.mark_deleted_2_date,
		categories.id AS categoryid, categories.name AS categoryname,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname, users.level_id,
                
                photos2.id AS itemphotoid, photos2.title AS itemphototitle,
		
                
                itemstatus.name AS statusname');

            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            if ($intLevelId == 4) {
                $this->db->join('users', 'items.mark_deleted = users.id', 'left');
            } else {
                $this->db->join('users', 'items.mark_deleted_2 = users.id', 'left');
            }
            $this->db->join('photos AS photos2', 'items.photo_id = photos2.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->where('items.account_id', $intAccountId);

            //isn't already deleted
            $this->db->where('items.active', 1);

            //check which user deleted them
            if ($intLevelId == 4) {
                // superadmin enquiry, therefore items where a standard admin has marked
                $this->db->where('items.mark_deleted !=', 0);
                $this->db->where('items.mark_deleted !=', $intUserId);
                // awaiting a superuser mark too
                $this->db->where('items.mark_deleted_2', 0);

                $this->db->order_by('items.mark_deleted DESC');
            } else {
                // standard admin enquiry, therefore only items which a superuser has marked
                $this->db->where('items.mark_deleted_2 !=', 0);
                $this->db->where('items.mark_deleted_2 !=', $intUserId);
                // awaiting admin mark too
                $this->db->where('items.mark_deleted', 0);

                $this->db->order_by('items.mark_deleted_2 DESC');
            }

            $resQuery = $this->db->get();
            /*
              print_r($this->db->last_query());
              die(); */
            if ($resQuery->num_rows() > 0) {
                $arrItemsData = array();
                foreach ($resQuery->result() as $objRow) {
                    $arrItemsData[] = $objRow;
                }
                return array('results' => $arrItemsData);
            } else {
                return array();
            }
        }
        return false;
    }

    public function confirmDeletion($intItemId, $intAccountId, $intUserId, $intLevelId) {
        $strDate = date('Y-m-d H:i:s');
        $arrData = array(
            'active' => 0,
            'deleted_date' => $strDate
        );
        if ($intLevelId == 4) {
            $arrData['mark_deleted_2'] = (int) $intUserId;
            $arrData['mark_deleted_2_date'] = $strDate;
        } else {
            $arrData['mark_deleted'] = (int) $intUserId;
            $arrData['mark_deleted_date'] = $strDate;
        }
        $this->db->where('id', (int) $intItemId);
        $this->db->where('account_id', (int) $intAccountId);
        $this->db->update('items', $arrData);
    }

    public function getRecentlyDeleted($intAccountId) {
        if ($intAccountId > 0) {
            $this->db->select('items.id, items.barcode,item_manu.item_manu_name,owner.owner_name, items.manufacturer, items.model, items.mark_deleted_date,items.mark_deleted_2_date,
                                itemstatus.name AS status_name,categories.name AS category_name,locations.name AS location_name
                                ');
            $this->db->from('items');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');
            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.active', 0);
            $this->db->order_by('items.deleted_date', 'DESC');
            $this->db->limit(5, 0);

            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                $arrManufacturers = array();

                foreach ($resQuery->result() as $objRow) {
                    $arrManufacturers[] = $objRow;
                }

                return $arrManufacturers;
            }
        }
        return false;
    }

    public function import($data, $account_id) {
        $this->load->library('parseCSV');
        $this->load->model('categories_model');
        $this->load->model('locations_model');
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $file_path = $data['full_path'];
        $csv_data = $this->parsecsv->parse1($file_path, 1);
//        $csv_data = $this->pasecsv->data;
        //print "<pre>"; print_r($csv_data)r; print "</pre>";
        $categories = array();
        $locations = array();
//       $users = array();
        $sites = array();
        foreach ($csv_data as $record) {
            $categories[] = $record['category'];
            $locations[] = array(
                'location_barcode' => $record['location_barcode'],
                'location' => $record['location']);
//            $users[] = $record['user'];
            $sites[] = $record['faculty'];
        }

        $this->categories_model->addCategoriesFromCsv(array_unique($categories), $account_id);
        $this->locations_model->addLocationFromCsv(($locations), $account_id);
//        $this->users_model->addUserFromCsv(array_unique($users) , $account_id);
        $this->sites_model->addSiteFromCsv(array_unique($sites), $account_id);

        foreach ($csv_data as $csv_row) {

            $category_id = $this->categories_model->search($csv_row['category'], $account_id);
            $location_id = $this->locations_model->search($csv_row['location'], $account_id);
            $user_id = $this->users_model->search($csv_row['user'], $account_id);
            $site_id = $this->sites_model->search($csv_row['faculty'], $account_id);

            if (!empty($csv_row['purchase_date'])) {
                $ex = explode('/', $csv_row['purchase_date']);
                $csv_row['purchase_date'] = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
            } else {
                $csv_row['purchase_date'] = NULL;
            }

            if (!empty($csv_row['warranty_date'])) {
                $ex = explode('/', $csv_row['warranty_date']);
                $csv_row['warranty_date'] = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
            } else {
                $csv_row['warranty_date'] = NULL;
            }

            if (!empty($csv_row['replacement_date'])) {
                $ex = explode('/', $csv_row['replacement_date']);
                $csv_row['replacement_date'] = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
            } else {
                $csv_row['replacement_date'] = NULL;
            }

            if (empty($csv_row['pat_date'])) {
                $csv_row['pat_date'] = NULL;
            }

            $arrItemData = array(
                'barcode' => $csv_row['barcode'],
                'serial_number' => $csv_row['serial'],
                'manufacturer' => $csv_row['make'],
                'model' => $csv_row['model'],
                'site' => $site_id,
                'account_id' => $account_id,
                'value' => $csv_row['purchase_value'],
                'current_value' => $csv_row['current_value'],
                'notes' => $csv_row['notes'],
                'status_id' => 1,
                'purchase_date' => $csv_row['purchase_date'],
                'warranty_date' => $csv_row['warranty_date'],
                'replace_date' => $csv_row['replacement_date'],
                'pattest_date' => $csv_row['pat_date'],
                'pattest_status' => 1
            );
//             print "<pre>"; print_r($arrItemData); print "</pre>";
//              print "<pre>"; print_r($category_id); print "</pre>";
//              print "<pre>"; print_r($location_id); print "</pre>";
//              print "<pre>"; print_r($user_id); print "</pre>";
//              print "<pre>"; print_r($faculty_id); print "</pre>"; 


            if ($category_id != '' || $category_id != NULL) {
                if (!$this->addOne_item($arrItemData, $category_id, $location_id, $user_id, $site_id)) {
                    die("Error adding item = " . $csv_row['barcode']);
                }
            }
        }

        return true;
    }

    /* Adding item from CSV file  */

    public function addOne_item($arrInput = array(), $intCategoryId = -1, $intLocationId = -1, $intUserId = -1, $intFacultyId = -1) {

        if ((count($arrInput) > 0) && ($intCategoryId > 0)) {

            $strDate = date('Y-m-d H:i:s');

            if ($arrInput['pattest_status'] == -1) {
                $arrInput['pattest_status'] = null;
            }

            $arrInput['added_date'] = $strDate;

            /* Check barcode is Present Or not */
            $this->db->select('*');
            $this->db->from('items');
            $this->db->where('items.barcode', $arrInput['barcode']);

            $resQuery = $this->db->get();
            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                return TRUE;
            } else {
                $this->db->insert('items', $arrInput);
                $intItemId = $this->db->insert_id();
                $this->linkThisToCategory($intItemId, $intCategoryId);
            }

            if ($intLocationId > 0) {
                $this->linkThisToLocation($intItemId, $intLocationId);
            }
            if ($intUserId > 0) {
                $this->linkThisToUser($intItemId, $intUserId);
            }
            if ($intFacultyId > 0) {
                $this->linkThisToSite($intItemId, $intFacultyId);
            }
            return $intItemId;
        }

        return false;
    }

    /* public function getCheckDetailsByItem($itemID) {
      $checks = $this->getChecksByItem($itemID);

      foreach($checks as $check) {

      $arrChecks[$check] = $this->getCheck($check);
      }
      return $arrChecks;
      } */

//    public function getChecksByItem($item_id) {
//        $item_cat = $this->getItemCategory($item_id);
//        $this->db->select('test_type_id AS test_id, test_type_account_id AS test_account_id, test_type_name AS test_name, test_type_frequency AS test_frequency, test_type_category_id AS test_category_id, test_type_description AS test_description, test_type_active AS test_active, test_type_mandatory AS test_mandatory');
//        $query = $this->db->get_where('test_type', array('test_type_account_id' => $this->session->userdata('objAppUser')->accountid, 'test_type_category_id' => $item_cat->category_id));
//
//        if ($query->num_rows() > 0) {
//            return $query->result();
//        } else {
//            return false;
//        }
//    }
    public function getChecksByItemDues($item_id) {
        $data = array();
        $sorted = array();
        $sorted_adhocs = array();
        $sorted_dues = array();
        $filter = true;
        $end_date = strtotime('+7 days');
        $start_date = strtotime('60 days ago');

        $item_cat = $this->getItemCategory($item_id);
        $item_details = $this->basicGetOne($item_id, $this->session->userdata('objAppUser')->accountid);
//        $item_details = $this->basicGetOne($item_id,5);
//        $tests = $this->getTestsByCat($item_cat->category_id);

        $today_date = date('d/m/Y');
        $daylen = (60 * 60) * 24;
        $testing_days = array(0 => 0, 1 => 1, 7 => 7, 31 => 31, 90 => 31, 121 => 45, 182 => 45, 365 => 60, 730 => 60, 1095 => 60);
        $visibility = array(10 => 0, 6 => 1, 11 => 1, 2 => 5, 3 => 6, 1 => 7, 7 => 31, 9 => 31, 5 => 31, 4 => 31, 12 => 60, 13 => 60); //  frequancyId => prior days visible
        $tests = $this->getTestsByCat((int) $item_cat->category_id);
        foreach ($tests as $key => $test) {
            $last_tested = $this->itemLastTest($item_id, $test['test_type_id']);
            $tests[$key]['manufacturer'] = $item_details[0]->manufacturer;
            $tests[$key]['item_manu'] = $item_details[0]->item_manu;
            $tests[$key]['model'] = $item_details[0]->model;
            $tests[$key]['barcode'] = $item_details[0]->barcode;
            $tests[$key]['locationname'] = $item_details[0]->locationname;

            $tasks = $this->getComplianceTasks($test['test_type_id']);
            if ($last_tested['last_tested']) {

                $tests[$key]['manager_of_check'] = $test['manager_of_check'];

                if ($last_tested['due'])
                    $tests[$key]['due_ts'] = strtotime($last_tested['due']);
                else
                    $tests[$key]['due_ts'] = strtotime("+" . $test['test_days'] . " days", strtotime($last_tested['last_tested']));
                if (($test['test_freq_id'] == 10) || ($tests[$key]['due_ts'] <= strtotime("+" . $visibility[$test['test_freq_id']] . " days", strtotime('now')))) {
                    $duedate_with_test_days = date('Y-m-d', strtotime('+' . $testing_days[$tests[$key]['test_days']] . ' days', $tests[$key]['due_ts']));
                    $current_timestamp = strtotime('today');
                    $dueupto = strtotime('+' . $testing_days[$tests[$key]['test_days']] . ' days', $tests[$key]['due_ts']);
                    $due_date_timestamp = strtotime($duedate_with_test_days);
                    $tests[$key]['due_date'] = date('d/m/Y', $tests[$key]['due_ts']);
                    if ((($current_timestamp > $tests[$key]['due_ts']) && ($current_timestamp <= $due_date_timestamp)) && ($tests[$key]['test_days'] != 0)) {
                        $tests[$key]['overdue'] = TRUE;
                    } else {
                        $tests[$key]['overdue'] = FALSE;
                    }
                    if ($tests[$key]['test_days'] == 0) {
                        $tests[$key]['adhoc'] = TRUE;
                    } else {
                        $tests[$key]['adhoc'] = FALSE;
                    }

                    $tests[$key]['tasks'] = $tasks;
//                    var_dump($tests[$key]);
//                    if (($current_timestamp <= $due_date_timestamp) && ($tests[$key]['test_days'] != 0)) {
                    if (($test['test_freq_id'] == 10) || ($current_timestamp <= $due_date_timestamp)) {
                        $data[] = array('compliance' => $tests[$key]);
                    } else {
//                           echo 'if';
                        $day_diff = ($current_timestamp - $due_date_timestamp) / $daylen;
                        $total_days = $daylen * (int) $tests[$key]['test_days'];
                        $temp = $tests[$key];
//                               $temp['due_date1'] = date('d-m-Y',$temp['due_ts']);
                        $temp['due_date1'] = date('d-m-Y', $current_timestamp - $total_days);

                        while (($current_timestamp) >= strtotime($temp['due_date1'])) {
                            if (strtotime($temp['due_date1']) < strtotime('today')) {
                                $temp['overdue'] = TRUE;
                            } else {
                                $temp['overdue'] = FALSE;
                            }

                            if (($current_timestamp >= $temp['due_ts']) && ($temp['due_ts'] <= ($current_timestamp + $total_days))) {
                                $temp['due_date'] = date('d/m/Y', strtotime($temp['due_date1']));
//                                       echo 'due_date formate'.$temp['due_date'];
                                $data[] = array('compliance' => $temp);
                            }
                            $temp['due_date1'] = date('d-m-Y', strtotime($temp['due_date1']) + $total_days);
                        }


//                              $data[] = array('compliance'=>$tests[$key]);
                    }
                }
            } else {
                $tests[$key]['due_ts'] = strtotime($test['start_of_check']);
                $tests[$key]['manager_of_check'] = $test['manager_of_check'];

                if (($test['test_freq_id'] == 10) || ($tests[$key]['due_ts'] <= strtotime("+" . $visibility[$test['test_freq_id']] . " days", strtotime('now')))) {

                    $duedate_with_test_days = date('Y-m-d', strtotime('+' . $testing_days[$tests[$key]['test_days']] . ' days', $tests[$key]['due_ts']));

                    $current_timestamp = strtotime('today');
                    $due_date_timestamp = strtotime($duedate_with_test_days);

                    $tests[$key]['due_date'] = date('d/m/Y', $tests[$key]['due_ts']);

                    if ((($current_timestamp > $tests[$key]['due_ts']) && ($current_timestamp <= $due_date_timestamp)) && ($tests[$key]['test_days'] != 0)) {
                        $tests[$key]['overdue'] = TRUE;
                    } else {
                        $tests[$key]['overdue'] = FALSE;
                    }
                    if ($tests[$key]['test_days'] == 0) {
                        $tests[$key]['adhoc'] = TRUE;
                    } else {
                        $tests[$key]['adhoc'] = FALSE;
                    }


                    $tests[$key]['tasks'] = $tasks;
                    if (($test['test_freq_id'] == 10) || ($current_timestamp <= $due_date_timestamp)) {
                        $data[] = array('compliance' => $tests[$key]);
                    } else {
//                            echo 'else';

                        $day_diff = ($current_timestamp - $due_date_timestamp) / $daylen;
                        $total_days = $daylen * (int) $tests[$key]['test_days'];
                        $temp = $tests[$key];
                        $temp['due_date1'] = date('d-m-Y', $current_timestamp - $total_days);

                        while (($current_timestamp) >= strtotime($temp['due_date1'])) {

                            if (strtotime($temp['due_date1']) < strtotime('today')) {
                                $temp['overdue'] = TRUE;
                            } else {
                                $temp['overdue'] = FALSE;
                            }

                            if (($current_timestamp >= $temp['due_ts']) && ($temp['due_ts'] <= $current_timestamp + $total_days)) {
                                $temp['due_date'] = date('d/m/Y', strtotime($temp['due_date1']));

                                $data[] = array('compliance' => $temp);
                            }
                            $temp['due_date1'] = date('d-m-Y', strtotime($temp['due_date1']) + $total_days);
                        }
                    }
                }
            }
        }

        foreach ($data as $key => $value) {

            if ($value['compliance']['overdue']) {
                array_push($sorted, $value);
            }
            if ($value['compliance']['adhoc']) {
                array_push($sorted_adhocs, $value);
            }
            if ($value['compliance']['adhoc'] == FALSE && $value['compliance']['overdue'] == FALSE) {
                array_push($sorted_dues, $value);
            }
        }
//        var_dump($data);
//        die;   

        foreach ($sorted_dues as $key => $value) {
            array_push($sorted, $value);
        }
        foreach ($sorted_adhocs as $key => $value) {
            array_push($sorted, $value);
        }
//        var_dump($sorted);
//        die;
        return $sorted;
    }

    public function getChecksByItem1($item_id) {
        $data = array();
        $sorted = array();
        $sorted_adhocs = array();
        $sorted_dues = array();
        $item_cat = $this->getItemCategory($item_id);
//        var_dump($item_cat->category_id);
        $tests = $this->getTestsByCat($item_cat->category_id);
//        var_dump($tests);
        $today_date = date('d/m/Y');

        $testing_days = array(0 => 0, 1 => 1, 7 => 7, 31 => 31, 90 => 31, 121 => 45, 182 => 45, 365 => 60, 730 => 60, 1095 => 60);
        foreach ($tests as $key => $value) {

            /* calculate Due Date  */

            $last_tested = $this->itemLastTest($item_id, $tests[$key]['test_type_id']);
            if ($last_tested['last_tested']) {
                if ($last_tested['due'])
                    $tests[$key]['due_date'] = date('d/m/Y', strtotime($last_tested['due']));
                else
                    $tests[$key]['due_date'] = date('d/m/Y', strtotime("+" . $tests[$key]['test_days'] . " days", strtotime($last_tested['last_tested'])));
            }else {
                $tests[$key]['due_date'] = date('d/m/Y', strtotime($tests[$key]['start_of_check']));
            }

            if ($tests[$key]['due_date']) {
                $formatted_test_days = $testing_days[$tests[$key]['test_days']];

                $adding_day = "+" . $formatted_test_days . " days";
                $formate_date = str_replace('/', '-', $tests[$key]['due_date']);
                $enddate = strtotime($formate_date);

                $duedate_with_test_days = date('d-m-Y', $enddate);
//               echo 'due_date = ' .$duedate_with_test_days . '<br>';

                $current_timestamp = strtotime('now');
                $due_date_timestamp = strtotime($duedate_with_test_days);

                echo 'due_date = ' . $tests[$key]['due_date'] . '<br>';
//                var_dump($due_date_timestamp);
//                echo 'id '.$tests[$key]['test_type_id'].'<br>';

                if (($current_timestamp >= $due_date_timestamp) && ($tests[$key]['test_days'] != 0)) {
//                    echo 'overdue <br>';
                    $tests[$key]['overdue'] = TRUE;
                } else {
//                     echo 'Not overdue <br>';
                    $tests[$key]['overdue'] = FALSE;
                }
            }
            if ($tests[$key]['test_days'] == 0) {
                $tests[$key]['adhoc'] = TRUE;
            } else {
                $tests[$key]['adhoc'] = FALSE;
            }

//            print "<pre>"; var_dump($value); print "</pre>";
            $tasks = $this->getComplianceTasks($value['test_type_id']);
//            var_dump($tasks);
            $tests[$key]['tasks'] = $tasks;
            $data[]['compliance'] = $tests[$key];
        }
        foreach ($data as $key => $value) {
            if ($value['compliance']['overdue']) {
                array_push($sorted, $value);
            }
            if ($value['compliance']['adhoc']) {
                array_push($sorted_adhocs, $value);
            }
            if ($value['compliance']['adhoc'] == FALSE && $value['compliance']['overdue'] == FALSE) {
                array_push($sorted_dues, $value);
            }
        }
//        var_dump($data);

        foreach ($sorted_dues as $key => $value) {
            array_push($sorted, $value);
        }
        foreach ($sorted_adhocs as $key => $value) {
            array_push($sorted, $value);
        }
//        var_dump($sorted);
//        die;
        return $sorted;
    }

    public function getChecksByItem($item_id) {
        $data = array();
        $item_cat = $this->getItemCategory($item_id);
//        var_dump($item_cat->category_id);
        $tests = $this->getTestsByCat($item_cat->category_id);
//        var_dump($tests);
        foreach ($tests as $key => $value) {

            /* calculate Due Date  */

            $last_tested = $this->itemLastTest($item_id, $tests[$key]['test_type_id']);
            if ($last_tested['last_tested']) {
                if ($last_tested['due'])
                    $tests[$key]['due_date'] = date('d/m/Y', strtotime($last_tested['due']));
                else
                    $tests[$key]['due_date'] = date('d/m/Y', strtotime("+" . $tests[$key]['test_days'] . " days", strtotime($last_tested['last_tested'])));
            }else {
                $tests[$key]['due_date'] = date('d/m/Y', strtotime($tests[$key]['start_of_check']));
            }


//            print "<pre>"; var_dump($value); print "</pre>";
            $tasks = $this->getComplianceTasks($value['test_type_id']);
//            var_dump($tasks);
            $tests[$key]['tasks'] = $tasks;
            $data[]['compliance'] = $tests[$key];
        }
//        var_dump($data);die;
        return $data;
    }

    private function itemLastTest($item_id, $test_id) {
        $res = $this->db->select('t.test_type')->from('test_compliances as tc')->where('tc.compliance_id', $test_id)->where('t.test_item_id', $item_id)->join('tests as t', 'tc.tests_id = t.test_id', 'left')->get();
        $ret = $res->result_array();
        $dueSql = "SELECT * FROM item_compliance_dues WHERE compliance_id =  '" . $test_id . "' AND item_id =  '" . $item_id . "' LIMIT 1";
        if (!empty($ret)) {
            $tasks = array();
            foreach ($ret as $key1 => $value1) {
                $tasks[] = $value1['test_type'];
            }
            $test_id = implode(',', $tasks);
            $sql = "SELECT * FROM tests WHERE tests.test_type in  ('" . $test_id . "') AND tests.test_item_id =  '" . $item_id . "' ORDER BY tests.test_date DESC LIMIT 1";
        } else {
            $sql = "SELECT * FROM tests WHERE tests.test_type =  '" . $test_id . "' AND tests.test_item_id =  '" . $item_id . "' AND tests.result = '1' ORDER BY tests.test_date DESC LIMIT 1";
        }

        $query = $this->db->query($sql);
        $query1 = $this->db->query($dueSql);
        $data = $query->row_array();
        $dataDue = $query1->row_array();
        if (empty($data)) {
            return false;
        } else {
//            var_dump(array('last_tested'=>$data['test_date'],'due'=>$dataDue['due_date']));
            return array('last_tested' => $data['test_date'], 'due' => $dataDue['due_date']);
//            return $data['test_date'];
        }
    }

    public function getTestsByCat($cat_id) {
//        var_dump($cat_id);
        $this->db->select("test_type.test_type_id, test_type.test_type_name, test_type.test_type_mandatory,test_type.test_type_frequency,test_type.start_of_check,test_type.test_type_category_id,test_type.manager_of_check, test_freq.test_freq_id, test_freq.test_frequency, test_freq.test_days")->from('test_type')
                ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
                ->where('test_type.test_type_category_id', $cat_id)
                ->where('test_type.test_type_active', 1)
                ->where('test_type.archieved', 0)
                ->group_by('test_type.test_type_id');
//        if($queryFilter == 1)
//            $this->db->where('test_freq.test_days !=','0');
//        if($queryFilter == 0)
//            $this->db->where('test_type.test_type_frequency','10');
        $query = $this->db->get();
//         print "<pre>"; var_dump($cat_id); print "</pre>";
//        echo $this->db->last_query().';<br>';
        return $query->result_array();
    }

    public function getTestsByCatOld($cat_id, $queryFilter = NULL) {
        $this->db->select("test_type.test_type_id, test_type.test_type_name, test_type.test_type_account_id, test_type.test_type_mandatory,test_type.test_type_frequency,test_type.start_of_check,test_type.test_type_category_id, test_freq.test_freq_id, test_freq.test_frequency, test_freq.test_days")->from('test_type')
                ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
                ->where('test_type.test_type_category_id', $cat_id)
                ->where('test_type.test_type_active', 1)
                ->where('test_type.archieved', 0);

        $query = $this->db->get();
//        echo $this->db->last_query().';<br>';
        return $query->result_array();
    }

    public function getComplianceTasks($com_id) {
        $ret = array();

        $fil = $this->db->where('test_type_id', $com_id)->get('test_type');
        $fil = $fil->result_array();
//        var_dump($fil);
        if ($fil[0]['start_of_check'] != NULL) {
            $res = $this->db->select('task_id')->where('compliance_id', $com_id)->get('compliance_tasks');
            $data = $res->result_array();
            foreach ($data as $key => $value) {
                $ret[] = $this->getTask($value['task_id']);
            }
            if (empty($data)) {
                $ret[] = $this->getTask($com_id);
            }
//            var_dump($com_id,$ret);
            return $ret;
        } else {
            return array(0 => array('id' => $com_id, 'task_name' => $fil[0]['test_type_name'], 'type_of_task' => '0', 'measurement' => '0', 'measurement_name' => null));
        }
    }

    public function getTask($test_id, $ret = false) {
        $query = $this->db->query("SELECT t.id,t.task_name,t.type_of_task,t.measurement,m.measurement_name
             From tasks as t
             Left Join measurements as m ON t.measurement = m.id
              WHERE t.id = " . $test_id . "");

        if ($query->num_rows == 1) {
            if ($ret) {
                
            }
            else
                return $query->row_array();
        } else {
            return false;
        }
    }

    public function getCheck($check_id) {
        $query = $this->db->get_where('vehicle_checks', array('id' => $check_id));
        return $query->row();
    }

    private function getItemCategory($item_id) {
        $this->db->select('category_id');
        $query = $this->db->get_where('items_categories_link', array('item_id' => $item_id));
        return $query->row();
    }

    public function logChecks($itemID, $data) {
        $this->load->model('users_model');
        $passed = rtrim($data['passed'], ',');
        $failed = rtrim($data['failed'], ',');

        $arrFailed = explode(',', $failed);
        $arrPassed = explode(',', $passed);
        $user = $this->users_model->getOne($this->session->userdata('objAppUser')->userid, $this->session->userdata('objAppUser')->accountid);


        foreach ($arrPassed as $pass) {
            $insert_data = array(
                'result' => 1,
                'test_person' => $user['result'][0]->firstname . ' ' . $user['result'][0]->lastname,
                'test_item_id' => $itemID,
                'test_notes' => $data['notes_' . $pass],
                'test_type' => $pass,
                'test_date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('tests', $insert_data);
        }

        foreach ($arrFailed as $fail) {
            $insert_data = array(
                'result' => 0,
                'test_person' => $user['result'][0]->firstname . ' ' . $user['result'][0]->lastname,
                'test_item_id' => $itemID,
                'test_notes' => $data['notes_' . $fail],
                'test_type' => $fail,
                'test_date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('tests', $insert_data);
        }
    }

    public function changeItemStatus($item_id, $status_id) {
        $data = array(
            'status_id' => $status_id
        );
        $this->db->where('id', $item_id);
        $this->db->update('items', $data);
    }

    public function outputPdfFile($strReportName, $arrFields, $arrResults, $booOutputHtml = false) {
        $this->load->model('accounts_model');
        $currency = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"includes/css/report.css\" /></head>";
//        $strHtml = "<html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"https://www.ischoolaudit.com/includes/css/report.css\" /></head>";

        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>IWorkAudit Report</h1>";
        $strHtml .= "<h2>" . $strReportName . "</h2>";
        $strHtml .= "</td><td class=\"right\">";

        $logo = 'logo.png';
        if (isset($this->session->userdata['theme_design']->logo)) {
            $logo = $this->session->userdata['theme_design']->logo;
        }
//        $strHtml .= "<img alt=\"ischoolaudit\" src=\"https://www.ischoolaudit.com/includes/img/logo_ictracker.png\">";
        $strHtml .= "<img alt=\"ictracker\" src='brochure/logo/" . $logo . "'>";

        $strHtml .= "</td></tr></table>";



        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
        $strHtml .= "<tr>";

        foreach ($arrFields as $arrReportField) {
            $strHtml .= "<th>" . $arrReportField['strName'] . "</th>";
        }

        $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        $arrTotals = array();
        foreach ($arrResults as $objItem) {

            $strHtml .= "<tr>";

            foreach ($arrFields as $arrReportField) {
                $strHtml .= "<td>";
                if (array_key_exists('strConversion', $arrReportField)) {
                    switch ($arrReportField['strConversion']) {
                        case 'date':
                            $arrDate = explode('-', $objItem->{$arrReportField['strFieldReference']});
                            if (count($arrDate) > 1) {
                                $strHtml .= $arrDate[2] . "/" . $arrDate[1] . "/" . $arrDate[0];
                            } else {
                                $strHtml .= "Unknown";
                            }
                            break;
                        case 'datetime':
                            $arrDateTime = explode(' ', $objItem->{$arrReportField['strFieldReference']});
                            $strTime = $arrDateTime[1];
                            $arrDate = explode('-', $arrDateTime[0]);
                            $strHtml .= $arrDate[2] . "/" . $arrDate[1] . "/" . $arrDate[0] . " " . $strTime;
                            break;
                        case 'pat_result':
                            if ($objItem->{$arrReportField['strFieldReference']} === null) {
                                $strHtml.="-";
                            } else {
                                if ($objItem->{$arrReportField['strFieldReference']} == 1) {
                                    $strHtml.="Pass";
                                } else {
                                    $strHtml.="Fail";
                                }
                            }
                            break;
                        case 'price':
                            $strHtml .= $currency . $objItem->{$arrReportField['strFieldReference']};
                            break;
                    }
                } else {
                    $strHtml .= $objItem[$arrReportField['strFieldReference']];
                }
                if (array_key_exists('arrFooter', $arrReportField) && array_key_exists('booTotal', $arrReportField['arrFooter'])) {
                    if (array_key_exists($arrReportField['strFieldReference'], $arrTotals)) {
                        $arrTotals[$arrReportField['strFieldReference']] += $objItem[$arrReportField['strFieldReference']];
                    } else {
                        $arrTotals[$arrReportField['strFieldReference']] = $objItem[$arrReportField['strFieldReference']];
                    }
                }

                $strHtml .= "</td>";
            }

            $strHtml .= "</tr>";
        }
        $strHtml .= "</tbody>";

        $strHtml .= "<tfoot><tr>";

        foreach ($arrFields as $arrReportField) {
            if (array_key_exists('arrFooter', $arrReportField)) {
                if (array_key_exists('booTotal', $arrReportField['arrFooter']) && $arrReportField['arrFooter']['booTotal']) {
                    $strHtml .= "<td>";
                    if (array_key_exists('strConversion', $arrReportField) && ($arrReportField['strConversion'] == "price")) {
                        $strHtml .= $currency;
                    }
                    $strHtml .= $arrTotals[$arrReportField['strFieldReference']];
                    $strHtml .= "</td>";
                } else {
                    if (array_key_exists('booTotalLabel', $arrReportField['arrFooter']) && $arrReportField['arrFooter']['booTotalLabel']) {
                        $strHtml .= "<td";
                        if (array_key_exists('intColSpan', $arrReportField['arrFooter']) && ($arrReportField['arrFooter']['intColSpan'] > 0)) {
                            $strHtml .= " colspan=\"" . $arrReportField['arrFooter']['intColSpan'] . "\"";
                        }
                        $strHtml .= " class=\"right\">";
                        $strHtml .= "Totals</td>";
                    }
                }
            }
        }
        $strHtml .= "</tr></tfoot>";


        $strHtml .= "</table>";


        $strHtml .= "<p>Produced by " . $this->session->userdata('objSystemUser')->firstname . " " . $this->session->userdata('objSystemUser')->lastname . " (" . $this->session->userdata('objSystemUser')->username . ") on " . date('d/m/Y') . "</p>";
        $strHtml .= "</div></body></html>";

        if (!$booOutputHtml) {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB', 'A4');
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("IworkAudit_" . date('Ymd_His') . ".pdf", "D");
        } else {
            echo $strHtml;
            die();
        }
    }

    public function getItemStatus($item_id) {
        $query = $this->db->query("SELECT status_id FROM items WHERE id = $item_id");
        $row = $query->row();
        return $row->status_id;
    }

    public function setItemStatus($item_id, $status_id = 1) {
        $this->db->query("UPDATE items SET status_id = " . (int) $status_id . " WHERE id = " . (int) $item_id);
        return true;
    }

    public function insertLog($intID, $message) {
        $data = array('user_id' => $this->session->userdata('objSystemUser')->userid,
            'account_id' => $this->session->userdata('objSystemUser')->accountid,
            'item_id' => $intID,
            'message' => $message,
            'date' => date('Y-m-d H:i:s')
        );

        $this->db->insert('item_log', $data);
    }

    public function insertLogForApp($intID, $message) {
        $data = array('user_id' => $this->session->userdata('objAppUser')->userid,
            'account_id' => $this->session->userdata('objAppUser')->accountid,
            'item_id' => $intID,
            'message' => $message,
            'date' => date('Y-m-d H:i:s')
        );

        $this->db->insert('item_log', $data);
    }

    public function getLog($intID) {
        $query = $this->db->query("SELECT item_log.message, item_log.date, users.firstname, users.lastname
FROM item_log Inner Join users ON item_log.user_id = users.id
WHERE item_log.item_id = '" . $intID . "' AND item_log.account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' ORDER BY item_log.id DESC");

        return $query->result();
    }

    public function update_file($itemid, $filename) {

        $this->db->select('doc');
        $this->db->where('id', $itemid);
        $query_res = $this->db->get('item_manu');
        $check = '';
        foreach ($query_res->result_array() as $row) {
            $check = $row['doc'];
        }

        if ($check != '') {
            $this->db->query("UPDATE item_manu SET doc =CONCAT(doc ,'," . $filename . "') WHERE id =" . (int) $itemid);
        } else {
            $this->db->query("UPDATE item_manu SET doc = '" . $filename . "' WHERE id =" . (int) $itemid);
        }
    }

// Delete pdf file from database which is shown on apple devices
    public function delete_file($id, $filename) {

        $this->db->select('doc');
        $this->db->where('id', $id);
        $query = $this->db->get('item_manu');
        $pdf_name = explode(',', $query->row()->doc);
        $doc_arr = array();

        foreach ($pdf_name as $docs) {
            if ($docs != $filename) {
                $doc_arr[] = $docs;
            }
        }

        $doc_string = implode(',', $doc_arr);
//        echo $doc_string;

        if ($doc_string) {
            $this->db->query("UPDATE item_manu SET doc ='" . $doc_string . "' WHERE id =" . (int) $id);
        } else {
            $this->db->query("UPDATE item_manu SET doc ='" . NULL . "' WHERE id =" . (int) $id);
        }
        return TRUE;
    }

    public function updateOne($item_id, $intPhotoId, $barcode) {
        if ($intPhotoId > 0) {
            $this->load->model('items_model');
//          Add Quanitity Fields
            if ($this->input->post('item_quantity')) {
                $qnt_item = (int) $this->input->post('item_quantity');
            } else {
                $qnt_item = 1;
            }
            $arrItemData = array(
                'serial_number' => trim($this->input->post('item_serial_number')),
                'manufacturer' => trim($this->input->post('manufacturer')),
                'item_manu' => trim($this->input->post('manu')),
                'value' => (float) $this->input->post('purchase_value'),
                'notes' => $this->input->post('item_notes'),
                'purchase_date' => $this->doFormatDate($this->input->post('purchase_date')),
                'warranty_date' => $this->doFormatDate($this->input->post('warranty_date')),
                'replace_date' => $this->doFormatDate($this->input->post('item_replace')),
                'current_value' => $this->doFormatDate($this->input->post('item_value')),
                'purchase_date' => $this->doFormatDate($this->input->post('purchased')),
                'photo_id' => (int) $intPhotoId,
                'supplier' => $this->input->post('item_supplier'),
                'pattest_date' => $this->doFormatDate($this->input->post('pattest_date')),
                'pattest_status' => $this->input->post('pattest_status'),
                'status_id' => $this->input->post('status_id'),
                'site' => $this->input->post('site_id'),
                'site_since' => date('Y-m-d H:i:s'),
                'owner_now' => $this->input->post('owner_id'),
                'owner_since' => date('Y-m-d H:i:s'),
                'location_now' => ($this->input->post('location_id')),
                'quantity' => $qnt_item,
                'condition_now' => trim($this->input->post('item_condition')),
                'condition_since' => date('Y-m-d H:i:s'),
            );
        } else {

            //          Add Quanitity Fields
            if ($this->input->post('item_quantity')) {
                $qnt_item = (int) $this->input->post('item_quantity');
            } else {
                $qnt_item = 1;
            }

            $arrItemData = array(
                'serial_number' => trim($this->input->post('item_serial_number')),
                'value' => (float) $this->input->post('purchase_value'),
                'manufacturer' => trim($this->input->post('manufacturer')),
                'item_manu' => trim($this->input->post('manu')),
                'notes' => $this->input->post('item_notes'),
                'purchase_date' => $this->doFormatDate($this->input->post('purchase_date')),
                'warranty_date' => $this->doFormatDate($this->input->post('warranty_date')),
                'replace_date' => $this->doFormatDate($this->input->post('item_replace')),
                'current_value' => $this->doFormatDate($this->input->post('item_value')),
                'purchase_date' => $this->doFormatDate($this->input->post('purchased')),
                'supplier' => $this->input->post('item_supplier'),
                'pattest_date' => $this->doFormatDate($this->input->post('pattest_date')),
                'pattest_status' => $this->input->post('pattest_status'),
                'status_id' => $this->input->post('status_id'),
                'site' => $this->input->post('site_id'),
                'site_since' => date('Y-m-d H:i:s'),
                'owner_now' => $this->input->post('owner_id'),
                'owner_since' => date('Y-m-d H:i:s'),
                'location_now' => ($this->input->post('location_id')),
                'quantity' => $qnt_item,
                'condition_now' => trim($this->input->post('item_condition')),
                'condition_since' => date('Y-m-d H:i:s'),
            );
        }
        if ($barcode != '') {
            $arrItemData['barcode'] = trim($this->input->post('item_barcode'));
        }

        $this->db->where('id', $item_id);
        $this->db->update('items', $arrItemData);
        if ($this->db->affected_rows() > 0) {
            if ($this->input->post('category_id') > 0) {
                $this->db->set('category_id', ($this->input->post('category_id')));
                $this->db->where('item_id', $item_id);
                $this->db->update('items_categories_link');
            }

            if ($this->input->post('site_id') > 0) {
                $this->db->set('site_id', ($this->input->post('site_id')));
                $this->db->set('date', (date('Y-m-d H:i:s')));
                $this->db->where('item_id', $item_id);
                $this->db->update('items_sites_link');
            }
            if ($this->input->post('user_id') > 0) {
                $this->db->set('user_id', ($this->input->post('user_id')));
                $this->db->set('date', (date('Y-m-d H:i:s')));
                $this->db->where('item_id', $item_id);
                $this->db->update('items_users_link');
            }
            if ($this->input->post('location_id') > 0) {
                $this->db->set('location_id', ($this->input->post('location_id')));
                $this->db->set('date', (date('Y-m-d H:i:s')));
                $this->db->where('item_id', $item_id);
                $this->db->update('items_locations_link');
            }

//            Log Condition if chNGED
            $condition = $this->db->select('condition_id')->where('item_id', $item_id)->order_by('id', 'desc')->limit(1)->get('item_condition_history_link')->row();

            if ($condition->condition_id != $this->input->post('item_condition')) {
//            if ($this->input->post('item_condition')) {
                $this->logConditionHistoryForApp($item_id, $this->input->post('item_condition'));
//            }
            }
        }
    }

    public function doFormatDate($strDate) {
        if ($strDate != "") {
            $arrDate = explode('/', $strDate);
            return $arrDate[2] . "-" . $arrDate[1] . "-" . $arrDate[0];
        }
        return NULL;
    }

    public function getDocByBarcode($strBarcode = "", $intAccountId = -1) {
        if (($strBarcode != "") && ($intAccountId > 0)) {
            $this->db->select('
items.id AS itemid,
  item_manu.id as item_manu_id,
  item_manu.doc as pdf_name,


');
            $this->db->from('items');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');

//            $this->db->join('users', 'items.owner_now = users.id', 'left');
//            $this->db->join('photos', 'photos.id = items.photo_id', 'left');
//            $this->db->join('locations', 'items.location_now = locations.id', 'left');
//            $this->db->join('sites', 'items.site = sites.id', 'left');
//            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
//            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
//            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
//            $this->db->join('suppliers', 'suppliers.supplier_id = items.supplier', 'left');
            $this->db->where('items.barcode', $strBarcode);
            $this->db->where('items.account_id', $intAccountId);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getAllPatStatus() {
//        $this->db->select('*');
//            $this->db->from('pat');
//            $resQuery = $this->db->get();
//         $pattest_status = array();
//            if ($resQuery->num_rows() > 0) {
//                foreach ($resQuery->result() as $objRow) {
//                    $pattest_status['id'] = $objRow->id; 
//                    $pattest_status['name'] = $objRow->pattest_name; 
//                }
//            return $pattest_status;
//            }else{
//                return $pattest_status;
//            }
        $query = $this->db->get('pat');
        $columns = $query->result();
        return $columns;
    }

    public function getOneItem($intItemId = -1, $intAccountId = -1) {
//        var_dump($intItemId,$intAccountId);
        if (($intItemId > 0) && ($intAccountId > 0)) {
            $this->db->select('
                items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id, items.compliance_start, items.quantity,
		categories.id AS categoryid, categories.name AS categoryname, categories.default AS categorydefault, categories.icon AS categoryicon,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');

            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.id', $intItemId);

            $resQuery = $this->db->get();
//            echo $this->db->last_query();
            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            }
        }
        return false;
    }

    public function getManagerOfCheck($id) {
        $res = $this->db->select('users.firstname,users.lastname')->where('test_type_id', $id)->join('users', 'test_type.manager_of_check = users.id')->limit(1)->get('test_type');
        $data = $res->result_array();
        if (empty($data)) {
            return false;
        } else {
            return $data[0]['firstname'] . ' ' . $data[0]['lastname'];
        }
    }

//    public function recordCheckBackup($data, $itemID) {
//        $set = array();
//        $other = array();
//        $failed = array();
//        $chekced = array();
//        $due_set = array();
//        $temp1 = explode('/', $data->due_date);
//        $due_on = $temp1[2].'-'.$temp1[1].'-'.$temp1[0];
//        if($data->due_date == 'Now'){
//            $data->due_date = date('Y-m-d',strtotime ('+'.$data->test_freq.' days'));
//        }else{
//            $temp = explode('/', $data->due_date);
//            $data->due_date = $temp[2].'-'.$temp[1].'-'.$temp[0];
//            if(strtotime($temp1[2].'-'.$temp1[1].'-'.$temp1[0]) < strtotime($data->due_date))
//            {
//                $data->due_date = date('Y-m-d',strtotime ('+'.$data->test_freq.' days',  strtotime($temp1[2].'-'.$temp1[1].'-'.$temp1[0])));
//            }else{
//                $data->due_date = date('Y-m-d',strtotime ('+'.$data->test_freq.' days',  strtotime($data->due_date)));
//            }
//        }
////        if ($data->due_date == 'Now')
////            $data->due_date = date('Y-m-d', strtotime('+' . $data->test_freq . ' days'));
////        else {
////            $temp = explode('/', $data->due_date);
////            $data->due_date = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
////            $data->due_date = date('Y-m-d', strtotime('+' . $data->test_freq . ' days', strtotime($data->due_date)));
////        }
//        if ($data->test_freq_id == '11') {
//            $day = date('D', strtotime($data->due_date));
//            if ($day == 'Sat' || $day == 'Sun') {
//                $data->due_date = date('Y-m-d', strtotime('next Monday', strtotime($data->due_date)));
//            }
//        }
//        if ($data->test_freq_id == '10') {
//            $data->due_date = date('Y-m-d', strtotime('now'));
//        }
//        $set['test_date'] = date('Y-m-d h:i:s', strtotime('now'));
//        $set['test_item_id'] = (int) $itemID;
//        $set['due_on'] = $due_on;
//
//        $other['compliance_id'] = (int) $data->compliance_check_id;
//
//        $due_set['item_id'] = (int) $itemID;
//        $due_set['cat_id'] = (int) $data->test_type_category_id;
//        $due_set['due_date'] = $data->due_date;
//        $due_set['account_id'] = $this->session->userdata('objAppUser')->accountid;
//        $due_set['compliance_id'] = (int) $data->compliance_check_id;
//
//        $set['test_notify'] = 0;
//        $set['test_person'] = $this->session->userdata('objAppUser')->firstname . " " . $this->session->userdata('objAppUser')->lastname;
//
//        $passed = explode(',', $data->passedChecks);
//        $temp = explode(',', $data->failedChecks);
//        $temp2 = explode(',', $data->measureChecks);
//        if ($passed[0] != '') {
//            foreach ($passed as $key => $value) {
//                $set['test_type'] = (int) $value;
//                $set['result'] = 1;
//                $set['test_notes'] = '';
//                $this->db->insert('tests', $set);
//                $other['tests_id'] = $this->db->insert_id();
//                $this->db->insert('test_compliances', $other);
////               var_dump($set);
//            }
//        }
//        if ($temp[0] != '') {
//            foreach ($temp as $key => $value) {
//                $failed[] = explode('|', $value);
//            }
//            foreach ($failed as $key => $value) {
//                $set['test_type'] = (int) $value[0];
//                $set['result'] = 0;
//                $set['test_notes'] = $value[1];
//                $this->db->insert('tests', $set);
//                $other['tests_id'] = $this->db->insert_id();
//                $this->db->insert('test_compliances', $other);
////                var_dump($set);
//            }
//        }
//        if ($temp2[0] != '') {
//            foreach ($temp2 as $key => $value) {
//                $chekced[] = explode('|', $value);
//            }
//            foreach ($chekced as $key => $value) {
//                $set['test_type'] = (int) $value[0];
//                $set['result'] = $value[1];
//                $set['test_notes'] = $value[2];
//                $this->db->insert('tests', $set);
//                $other['tests_id'] = $this->db->insert_id();
//                $this->db->insert('test_compliances', $other);
////                var_dump($set);
//            }
//        }
//
//        $res = $this->db->where('compliance_id', $due_set['compliance_id'])->where('item_id', $due_set['item_id'])->limit(1)->get('item_compliance_dues');
//
//        if ($res->num_rows() > 0) {
//            $this->db->where('compliance_id', $due_set['compliance_id'])->where('item_id', $due_set['item_id'])->update('item_compliance_dues', $due_set);
//        } else {
//            $this->db->insert('item_compliance_dues', $due_set);
//        }
//        return true;
//    }
    public function recordCheck($data, $itemID, $photo_id = '') {
//        return 'hello';die;
        $set = array();
        $other = array();
        $failed = array();
        $chekced = array();
        $due_set = array();
//        var_dump($data,$itemID);die;
        $item_data = $this->getOneItem((int) $itemID, (int) $this->session->userdata('objAppUser')->accountid);
//        $item_data = $this->getOneItem((int)$itemID,23);
        $manager = $this->getManagerOfCheck((int) $data->compliance_check_id);
//        return array(0=>$item_data,1=>$manager);die;

        $temp1 = explode('/', $data->due_date);
        $due_on = $temp1[2] . '-' . $temp1[1] . '-' . $temp1[0];
        if ($data->due_date == 'Now') {
            $data->due_date = date('Y-m-d', strtotime('+' . $data->test_freq . ' days'));
        } else {
            $temp = explode('/', $data->due_date);
            $data->due_date = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
            if (strtotime($temp1[2] . '-' . $temp1[1] . '-' . $temp1[0]) < strtotime($data->due_date)) {
                $data->due_date = date('Y-m-d', strtotime('+' . $data->test_freq . ' days', strtotime($temp1[2] . '-' . $temp1[1] . '-' . $temp1[0])));
            } else {
                $data->due_date = date('Y-m-d', strtotime('+' . $data->test_freq . ' days', strtotime($data->due_date)));
            }
        }
//        if ($data->due_date == 'Now')
//            $data->due_date = date('Y-m-d', strtotime('+' . $data->test_freq . ' days'));
//        else {
//            $temp = explode('/', $data->due_date);
//            $data->due_date = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
//            $data->due_date = date('Y-m-d', strtotime('+' . $data->test_freq . ' days', strtotime($data->due_date)));
//        }
        if ($data->test_freq_id == '11') {
            $day = date('D', strtotime($data->due_date));
            if ($day == 'Sat' || $day == 'Sun') {
                $data->due_date = date('Y-m-d', strtotime('next Monday', strtotime($data->due_date)));
            }
        }
        if ($data->test_freq_id == '10') {
            $data->due_date = date('Y-m-d', strtotime('now'));
        }
        $set['test_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $set['test_item_id'] = (int) $itemID;
        $set['due_on'] = $due_on;

        $other['compliance_id'] = (int) $data->compliance_check_id;

        $due_set['item_id'] = (int) $itemID;
        $due_set['cat_id'] = (int) $data->test_type_category_id;
        $due_set['due_date'] = $data->due_date;
        $due_set['account_id'] = $this->session->userdata('objAppUser')->accountid;
        $due_set['compliance_id'] = (int) $data->compliance_check_id;

        $set['test_notify'] = 0;
        $set['test_person'] = $this->session->userdata('objAppUser')->firstname . " " . $this->session->userdata('objAppUser')->lastname;

//        var_dump($data->passedChecks);
//        var_dump($data->failedChecks);

        $passed = explode(',', $data->passedChecks);
        $temp = explode(',', $data->failedChecks);
        $temp2 = explode(',', $data->measureChecks);

//        var_dump($passed);
        if ($passed[0] != '') {
            foreach ($passed as $key => $value) {
                $set['test_type'] = (int) $value;
                $set['result'] = 1;
                $set['test_notes'] = '';
//----------record for history----------

                $setHistory = array('test_type' => $set['test_type'], 'test_date' => $set['test_date'], 'test_item_id' => $itemID, 'test_notes' => $set['test_notes'], 'test_person' => $set['test_person'], 'test_category' => $item_data[0]->categoryname, 'test_owner' => $item_data[0]->userfirstname . ' ' . $item_data[0]->userlastname, 'test_location' => $item_data[0]->locationname, 'test_site' => $item_data[0]->sitename, 'test_manager' => $manager, 'test_compliance_name' => $data->compliance_check_name, 'result' => $set['result'], 'test_notify' => 0, 'due_on' => $due_on, 'account_id' => $this->session->userdata('objAppUser')->accountid);
                $setHistory['signature'] = $photo_id;

                $this->db->insert('tests_history', $setHistory);
//--------------------------------------        
                $this->db->insert('tests', $set);
                $other['tests_id'] = $this->db->insert_id();
                $this->db->insert('test_compliances', $other);
//               var_dump($set);
            }
        }
        if ($temp[0] != '') {
            foreach ($temp as $key => $value) {
                $failed[] = explode('|', $value);
            }
            foreach ($failed as $key => $value) {
                $set['test_type'] = (int) $value[0];
                $set['result'] = 0;
                $set['test_notes'] = $value[1];


//----------record for history----------

                $setHistory = array('test_type' => $set['test_type'], 'test_date' => $set['test_date'], 'test_item_id' => $itemID, 'test_notes' => $set['test_notes'], 'test_person' => $set['test_person'], 'test_category' => $item_data[0]->categoryname, 'test_owner' => $item_data[0]->userfirstname . ' ' . $item_data[0]->userlastname, 'test_location' => $item_data[0]->locationname, 'test_site' => $item_data[0]->sitename, 'test_manager' => $manager, 'test_compliance_name' => $data->compliance_check_name, 'result' => $set['result'], 'test_notify' => 0, 'due_on' => $due_on, 'account_id' => $this->session->userdata('objAppUser')->accountid);
                $setHistory['signature'] = $photo_id;

                $this->db->insert('tests_history', $setHistory);
//--------------------------------------        
                $this->db->insert('tests', $set);
                $other['tests_id'] = $this->db->insert_id();
                $this->db->insert('test_compliances', $other);
//                var_dump($set);
            }
        }
        if ($temp2[0] != '') {
            foreach ($temp2 as $key => $value) {
                $chekced[] = explode('|', $value);
            }
            foreach ($chekced as $key => $value) {
                $set['test_type'] = (int) $value[0];
                $set['result'] = $value[1];
                $set['test_notes'] = $value[2];

//----------record for history----------

                $setHistory = array('test_type' => $set['test_type'], 'test_date' => $set['test_date'], 'test_item_id' => $itemID, 'test_notes' => $set['test_notes'], 'test_person' => $set['test_person'], 'test_category' => $item_data[0]->categoryname, 'test_owner' => $item_data[0]->userfirstname . ' ' . $item_data[0]->userlastname, 'test_location' => $item_data[0]->locationname, 'test_site' => $item_data[0]->sitename, 'test_manager' => $manager, 'test_compliance_name' => $data->compliance_check_name, 'result' => $set['result'], 'test_notify' => 0, 'due_on' => $due_on, 'account_id' => $this->session->userdata('objAppUser')->accountid);

                $setHistory['signature'] = $photo_id;
                $this->db->insert('tests_history', $setHistory);
//--------------------------------------        
                $this->db->insert('tests', $set);
                $other['tests_id'] = $this->db->insert_id();
                $this->db->insert('test_compliances', $other);
//                var_dump($set);
            }
        }

        $res = $this->db->where('compliance_id', $due_set['compliance_id'])->where('item_id', $due_set['item_id'])->limit(1)->get('item_compliance_dues');

        if ($res->num_rows() > 0) {
            $this->db->where('compliance_id', $due_set['compliance_id'])->where('item_id', $due_set['item_id'])->update('item_compliance_dues', $due_set);
        } else {
            $this->db->insert('item_compliance_dues', $due_set);
        }
        return true;
    }

    public function updateMultipleItems($data) {
        $this->load->model('customfields_model');
        $arrdata = array();

        if ($data['items_id'] != '') {
            if (strpos($data['items_id'], 'on') !== FALSE) {
                $data['items_id'] = str_replace('on,', '', $data['items_id']);
            }
            $ids = explode(',', $data['items_id']);
            if ($data['item_warranty']) {


                $arrDate = explode('/', $data['item_warranty']);
                $warrnty = $arrDate[2] . "-" . $arrDate[1] . "-" . $arrDate[0];
            }

            $update_list = array(
                'owner_now' => $data['user'],
                'owner_since' => date('Y-m-d H:i:s'),
                'location_now' => $data['location'],
                'status_id' => $data['status'],
                'warranty_date' => $warrnty,
                'supplier' => $data['supplier'],
                'site' => $data['site'],
                'item_manu' => $data['item_manu'],
                'status_id' => $data['status'],
                'condition_now' => $data['item_condition'],
                'condition_since' => date('Y-m-d H:i:s'),
                'manufacturer' => $data['manufacturer'],
                'model' => $data['item_model'],
            );

            foreach ($update_list as $key => $value) {
                if ($value == '') {
                    unset($update_list[$key]);
                }
            }
            if (empty($update_list)) {
                
            } else {
                $this->db->where_in('id', $ids)->update('items', $update_list);
            }

            if ($data['category']) {
                $update_category = array(
                    'category_id' => $data['category']
                );
                $this->db->where_in('item_id', $ids)->update('items_categories_link', $update_category);
            }
            if ($data['location']) {
                $update_location = array(
                    'location_id' => $data['location'],
                    'date' => date('Y-m-d H:i:s'),
                );
                $this->db->where_in('item_id', $ids)->update('items_locations_link', $update_location);
            }
            if ($data['site']) {
                $update_location = array(
                    'site_id' => $data['site'],
                    'date' => date('Y-m-d H:i:s'),
                );
                $this->db->where_in('item_id', $ids)->update('items_sites_link', $update_location);
            }
            if ($data['item_condition']) {
                for ($j = 0; $j < count($ids); $j++) {
                    $update_condition = array(
                        'item_id' => $ids[$j],
                        'condition_id' => $data['item_condition'],
                        'date' => $update_list['condition_since'],
                        'logged_by' => $this->session->userdata('objSystemUser')->userid
                    );
                    $this->db->insert('item_condition_history_link', $update_condition);
                }
            }


            $this->customfields_model->insertContentByItemid($data['items_id'], $data);


            return TRUE;
        }
    }

    public function checkBarcodeForItem($strBarcode = "") {
        if (($strBarcode != "")) {
            $this->db->select('*');
            $this->db->from('items');
            $this->db->where('items.barcode', $strBarcode);
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function insert_record() {

        $this->db->select('arkey,serial_number');
        $this->db->from('dummy_table');
//              $this->db->join('dummy_table', 'items.id = dummy_table.id', 'left');
//            $this->db->where('items.account_id', 21);
        $resQuery = $this->db->get();
        if ($resQuery->num_rows() > 0) {
            $arrItemsData = array();
            $count = 1;
            foreach ($resQuery->result() as $objRow) {

                if ($objRow->arkey) {
                    $this->db->select('*');
//                    $this->db->from('custom_fields_content');
//                    $this->db->where(array('custom_fields_content.account_id' => 21, 'custom_fields_content.custom_field_id' => 2, 'custom_fields_content.content' => $objRow->arkey));
                    $this->db->from('items');
                    $this->db->where(array('account_id' => 21, 'serial_number' => $objRow->serial_number));
                    $resQuery1 = $this->db->get();

//                    echo 'serial= ' . ($objRow->serial_number) . '<br>';
                    if (TRUE) {
                        if ($resQuery1->num_rows() > 0) {
                            $arrItemsData1 = array();
                            foreach ($resQuery1->result() as $itemsid) {
                                echo 'itemid = ' . ($itemsid->id) . '<br>';
                                echo 'serial = ' . ($itemsid->serial_number) . '<br>';

//                        if (isset($objRow->arkey)) {
//                            $data = array(
//                                'content' => $objRow->arkey,
//                            );
//                            $this->db->where('custom_fields_content.custom_field_id', 2);
//                            $this->db->where('custom_fields_content.item_id', $itemsid->id);
////                            $this->db->where('id', $itemsid->item_id);
//                            $this->db->update('custom_fields_content', $data);
////                        }
//                            }
                            }
                        }
                    }
//                    $arrItemsData[] = $objRow;
                }
//            var_dump($arrItemsData1);
            }
        }
    }

    public function getitemid($accountid) {
        $this->db->select('id');
        $this->db->from('items');


        $this->db->where('items.account_id', $accountid);
        $resQuery = $this->db->get();
        $arrItemsData = array();
        foreach ($resQuery->result() as $objRow) {
            $arrItemsData[] = $objRow;
        }
        return $resQuery->result();
    }

    public function import_new($data, $account_id) {
        $this->load->library('parseCSV');
        $this->load->model('categories_model');
        $this->load->model('locations_model');
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $file_path = $data['full_path'];
        $csv_data = $this->parsecsv->parse1($file_path, 1);

        foreach ($csv_data as $csv_row) {

            $arrInput = array(
                'arkey' => $csv_row['arkey'],
                'serial' => $csv_row['serial']
            );

            $this->db->insert('dummy', $arrInput);
        }
        die;
    }

// End of function

    public function basicGetOneWithTicket($intItemId = -1, $intAccountId = -1, $inttype = '') {
        if (($intItemId > 0) && ($intAccountId > 0)) {
            $this->db->select('
                        items.id AS itemid,
                        items.manufacturer,
                        items.item_manu,
                        items.model, 
                        items.serial_number, 
                        items.barcode, 
                        items.owner_now, 
                        items.owner_since, 
                        items.site AS siteid,
                        items.location_now, 
                        items.location_since,
                        items.value,
                        items.current_value, 
                        items.notes,
                        items.warranty_date,
                        items.purchase_date, 
                        items.replace_date,
                        items.added_date,
                        items.pattest_date, 
                        items.pattest_status,
                        items.mark_deleted, 
                        items.mark_deleted_2, 
                        items.mark_deleted_date,
                        items.mark_deleted_2_date, 
                        items.active,
                        items.deleted_date,
                        items.compliance_start,
                        items.quantity,
                       item_manu.doc as pdf_name,
                        photos.id AS itemphotoid,
                        photos.title AS itemphototitle,
                        itemstatus.id AS itemstatusid,
                        itemstatus.name AS itemstatusname,
                        categories.id AS categoryid, 
                        categories.name AS categoryname, 
                        categories.default AS categorydefault, 
                        categories.icon AS categoryicon, 
                        categories.support_emails AS support_emails, 
                        categories.quantity_enabled,
                        users.id AS userid,
                        users.firstname AS userfirstname,
                        users.lastname AS userlastname, 
                        users.nickname AS usernickname,
                        locations.id AS locationid,
                        locations.name AS locationname,
                        sites.id AS siteid,
                        item_manu.item_manu_name,
                        sites.name AS sitename,
                        items.supplier,
                        suppliers.supplier_name,
                        suppliers.supplier_title AS suppliers_title,tickets.id as ticket_id,tickets.fix_code,tickets.reason_code,tickets.order_no,tickets.jobnote,tickets.severity,tickets.ticket_action,tickets.date as dt,tickets.order_no,tickets.photoid');
            $this->db->from('items');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');
            $this->db->join('photos', 'photos.id = items.photo_id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('suppliers', 'items.supplier = suppliers.supplier_id', 'left');
            $this->db->join('tickets', 'tickets.item_id = items.id', 'left');
            $this->db->where('items.id', $intItemId);
            $this->db->where('items.account_id', $intAccountId);
            if ($inttype == 'Open Job') {
                $this->db->where('tickets.ticket_action', "Open Job");
            } else {
                $this->db->where('tickets.ticket_action', "Fix");
            }
            $this->db->order_by('tickets.id', 'DESC');

            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {
                return $resQuery->result();
            }
        } else {
            return false;
        }
    }

// End of function

    function updateStatus($stausId, $itemId) {
        $data = array("status_id" => $stausId);
        $this->db->where('id', $itemId);
        $this->db->update('items', $data);
        echo $this->db->last_query();
        die;
    }

// End of function
// get condition history of asset 

    public function checkasset_condition($intItemId = -1, $intAccountId = -1) {
        if (($intItemId > 0) && ($intAccountId > 0)) {
            $this->db->select('items.condition_now,items.barcode,item_condition_history_link.date,users.firstname,users.lastname,items.purchase_date,item_condition_history_link.notes,item_condition_history_link.photo_id,items.warranty_date,items.replace_date,item_condition.condition');
            $this->db->from('items');
            $this->db->join('item_condition_history_link', 'items.id=item_condition_history_link.item_id');
            $this->db->join('item_condition', 'item_condition_history_link.condition_id=item_condition.id');
            $this->db->join('users', 'item_condition_history_link.logged_by = users.id', 'left');

            $this->db->join('photos', 'items.photo_id = photos.id', 'left');

//            $this->db->join('photos', 'item_condition_history_link.photo_id = photos.id');
            $this->db->where('items.id', $intItemId);
            $this->db->where('items.account_id', $intAccountId);
            $this->db->order_by('item_condition_history_link.date desc');
            $resQuery = $this->db->get();

            if ($resQuery->num_rows() > 0) {

                $result = $resQuery->result_array();

                return $result;
            }
        } else {
            return false;
        }
    }

    //    Fetch All Condition List
    public function get_condition() {
        $res = $this->db->get('item_condition');
        if ($res->num_rows() > 0) {
            $result = $res->result_array();
            return $result;
        }
    }

    // get number of conditions in condition history
    public function count_condition($asset_id) {
        $con = $this->db->where('item_id', $asset_id)->get('item_condition_history_link');
        if ($con->num_rows() > 0) {
            $history = $con->result();
            return count($history);
        }
    }

// get latest date of asset condition
    public function get_maxcondition($item) {
        $latest_con = $this->db->select('max(date) as max_date')->where('item_id', $item)->get('item_condition_history_link')->result();
        return $latest_con[0]->max_date;
    }

// update new condition in condition history
    public function condition_log($item) {

        $new_condition = $this->input->post('new_condition');
        $job_note = $this->input->post('job_notes');
        $arrConditionHistory = array('item_id' => $item,
            'condition_id' => $new_condition,
            'notes' => $job_note,
            'date' => date('Y-m-d H:i:s'),
            'photo_id' => 1,
            'logged_by' => $this->session->userdata('objSystemUser')->userid);
        $this->db->insert('item_condition_history_link', $arrConditionHistory);
        $id = $this->db->insert_id();
        if ($id) {
            $setdata = array('condition_now' => $new_condition, 'condition_since' => date('Y-m-d H:i:s'));
            $this->db->set($setdata)->where('id', $item)->update('items');
            return $id;
        } else {
            return FALSE;
        }
    }

// update new condition in condition history
    public function auditCondition_logForApp($item_id, $condition_id) {

        $arrConditionHistory = array('item_id' => $item_id,
            'condition_id' => $condition_id,
            'date' => date('Y-m-d H:i:s'),
            'logged_by' => $this->session->userdata('objAppUser')->userid);
        $this->db->insert('item_condition_history_link', $arrConditionHistory);
        $id = $this->db->insert_id();
        if ($id) {
            $setdata = array('condition_now' => $condition_id, 'condition_since' => date('Y-m-d H:i:s'));
            $this->db->set($setdata)->where('id', $item_id)->update('items');
            return $id;
        } else {
            return FALSE;
        }
    }

// update new condition in condition history
    public function condition_logForApp($item_id, $data) {

        $this->db->insert('item_condition_history_link', $data);
        $id = $this->db->insert_id();
        if ($id) {
            $setdata = array('condition_now' => $data['condition_id'], 'condition_since' => date('Y-m-d H:i:s'));
            $this->db->set($setdata)->where('id', $item_id)->update('items');
            return $id;
        } else {
            return FALSE;
        }
    }

    public function logConditionHistory($item_id, $condition_id) {
        $arrConditionHistory = array('item_id' => $item_id,
            'condition_id' => $condition_id,
            'date' => date('Y-m-d H:i:s'),
            'logged_by' => $this->session->userdata('objSystemUser')->userid);
        $this->db->insert('item_condition_history_link', $arrConditionHistory);
    }

    public function logConditionHistoryForApp($item_id, $condition_id) {
        $arrConditionHistory = array('item_id' => $item_id,
            'condition_id' => $condition_id,
            'date' => date('Y-m-d H:i:s'),
            'logged_by' => $this->session->userdata('objAppUser')->userid);
        $this->db->insert('item_condition_history_link', $arrConditionHistory);
    }

    // check unique qrcode
    public function check_qrcode($qr_code) {

        $this->db->select('barcode');
        $this->db->where('barcode', $qr_code);
        $res = $this->db->get('items');

        if ($res->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    // check unique manu
    public function check_manu($manu) {

        $this->db->select('item_manu_name');
        $this->db->where('item_manu_name', $manu);
        $res = $this->db->get('item_manu');

        if ($res->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getAllMissingItems($account_id) {

        $this->db->select('items.id as itemid,
                items.barcode,
                items.item_manu,
                users.firstname,
                users.lastname,
		categories.id AS categoryid,
                categories.name AS categoryname,
                locations.name AS locationname,
                ');
        // we need to do a sub query, this
        $this->db->from('items');
        $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
        $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
        $this->db->join('users', 'items.owner_now = users.id', 'left');
        $this->db->join('locations', 'items.location_now = locations.id', 'left');
        $this->db->where('items.account_id', $account_id);
        $this->db->where('items.status_id', 6);

        $resQuery = $this->db->get();

//            echo $this->db->last_query();
        if ($resQuery->num_rows() > 0) {
            return $resQuery->result_array();
        }

        return false;
    }

    public function exportPdfFile($allData, $filename = "isareport") {
        $this->load->model('accounts_model');
        $booOutputHtml = false;
        $data['allData'] = $allData;
        $data['title'] = $filename;

//        $data['accountDetails'] = $this->accounts_model->getOne($this->session->userdata('objSystemUser')->accountid);
        $strHtml = $this->load->view('items/exporttopdf', $data, true);
//            echo $strHtml;die;
        if (!$booOutputHtml) {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB', 'A4');
            $mpdf->setHeader($filename);
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("$filename.pdf", "D");
        } else {
            echo $strHtml;
            die();
        }
    }

    // search id of qrcode
    public function item_search($qr_code) {

        $this->db->select('id');
//        $this->db->like('barcode', $qr_code); 
        $this->db->where('barcode', $qr_code);
        $res = $this->db->get('items');

        if ($res->num_rows() > 0) {
            $rs = $res->row();
            return $rs->id;
        } else {
            return false;
        }
    }

    public function filter_search($barcode) {
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $query = "select
                items.id AS itemid, items.manufacturer,items.item_manu ,items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.current_value, items.purchase_date,items.status_id, items.compliance_start, items.quantity,items.warranty_date,items.replace_date,
		categories.id AS categoryid, categories.name AS categoryname, categories.default AS categorydefault, categories.icon AS categoryicon,item_condition.condition AS condition_name,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                owner.id as owner_id, owner.owner_name,
                photos.id AS userphotoid, photos.title AS userphototitle,
                photos2.id AS itemphotoid,
                photos2.path AS itemphotopath,
                photos2.title AS itemphototitle,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                pat.pattest_name AS pat_status,
                itemstatus.name AS statusname,
                suppliers.supplier_name
                from items left join items_categories_link on items.id = items_categories_link.item_id
                left join categories on items_categories_link.category_id = categories.id
                left join users on items.owner_now = users.id
                left join owner on items.owner_now = owner.id
                left join photos on users.photo_id = photos.id
                left join item_condition on items.condition_now = item_condition.id
                left join photos AS photos2 on items.photo_id = photos2.id
                left join locations on items.location_now = locations.id
                left join sites on items.site = sites.id
                left join suppliers on items.supplier = suppliers.supplier_id
                left join itemstatus on items.status_id = itemstatus.id
                left join pat on items.pattest_status = pat.id
                where items.active =1 AND items.barcode like '%$barcode%' AND items.account_id=" . $this->session->userdata('objSystemUser')->accountid;
        $res = $this->db->query($query);
        $result = $res->result_array();
        return $result;
    }

    public function linkThisToOwner($owner_id, $location_id) {
        if ($owner_id) {
            $this->db->where('id', $owner_id);
            $this->db->update('owner', array('location_id' => $location_id));
        }
    }

    // Update Owner of Item

    public function update_owner($intItem, $intOwner) {
        if (($intItem) && ($intOwner)) {
            $this->db->where('id', $intItem);
            $this->db->update('items', array('owner_now' => $intOwner, 'owner_since' => date('Y-m-d H:i:s')));
        }
    }

    public function get_item_manu($item_id = '') {
        if ($item_id) {
            $this->db->select('item_manu');
            $this->db->where('id', $item_id);
            $res = $this->db->get('items');

            if ($res->num_rows() > 0) {
                $rs = $res->row();
                return $rs->item_manu;
            } else {
                return false;
            }
        }
    }

    public function get_item_id($item_manu = '') {
        if ($item_manu) {
            $this->db->select('id');
            $this->db->where('item_manu', $item_manu);
            $res = $this->db->get('items');

            if ($res->num_rows() > 0) {
                $rs = $res->result_array();
                return $rs;
            } else {
                return false;
            }
        }
    }

    public function ownerHistory($itemid, $account_id) {
        $this->db->select('item_owner_history_link.date,owner.owner_name');
        $this->db->from('item_owner_history_link');
        $this->db->join('owner', 'item_owner_history_link.owner_id=owner.id', 'left');
        $this->db->where('item_owner_history_link.item_id', $itemid);
        $this->db->where('owner.account_id', $account_id);
        $this->db->order_by('item_owner_history_link.id desc');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return FALSE;
        }
    }

    public function auditHistory($itemid, $account_id) {
        $this->db->select('locations.name as locationname,sites.name as sitename,items_locations_link.date,items_locations_link.logged_by,users.firstname,users.lastname');
        $this->db->from('audits');
        $this->db->join('items_locations_link', 'items_locations_link.location_id=audits.location_id', 'left');
        $this->db->join('locations', 'items_locations_link.location_id=locations.id', 'left');
        $this->db->join('sites', 'locations.site_id=sites.id', 'left');
        $this->db->join('users', 'audits.user_id=users.id', 'left');
        $this->db->where('items_locations_link.item_id', $itemid);
        $this->db->where('audits.account_id', $account_id);
        $this->db->order_by('audits.id desc');
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return FALSE;
        }
    }

    public function search_Items($intAccountId, $strManufacturer, $intSite, $intLocation, $intCategory, $stritem_manu, $strbarcode) {
        if ($intAccountId > 0) {

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
                        item_manu.doc as pdf_name,
		categories.id AS categoryid,
                categories.name AS categoryname,
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
                itemstatus.name AS itemstatusname');

            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            //             JOIN for Item condition

            $this->db->join('item_condition', 'items.condition_now = item_condition.id', 'left');

            $this->db->join('item_manu', 'items.item_manu = item_manu.id', 'left');

            $this->db->join('photos', 'items.photo_id = photos.id', 'left');
            $this->db->join('locations', 'items.location_now = locations.id', 'left');
            $this->db->join('sites', 'items.site = sites.id', 'left');
            $this->db->join('itemstatus', 'items.status_id = itemstatus.id', 'left');
            $this->db->join('suppliers', 'suppliers.supplier_id = items.supplier', 'left');

            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.active', 1);

            if ($strbarcode != "") {
                $this->db->where('items.barcode', $strbarcode);
            }

            if ($intSite != "") {
                $this->db->where('sites.id', $intSite);
            }

            if ($intLocation != "") {
                $this->db->where('locations.id', $intLocation);
            }
            if ($strManufacturer != "") {
                $this->db->where('items.manufacturer', $strManufacturer);
            }
            if ($stritem_manu != "") {
                $this->db->where('item_manu.id', $stritem_manu);
            }

            // Add Category Filter             
            if ($intCategory != "") {
                $this->db->where('categories.id', $intCategory);
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

}

?>