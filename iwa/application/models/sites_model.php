<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sites_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getOne($intSiteId = -1, $intAccountId = -1) {
        if (($intAccountId > 0) && ($intSiteId > 0)) {
            // Run the query
            $this->db->select('sites.id AS siteid, sites.name AS sitename');
            $this->db->from('sites');
            $this->db->where('sites.account_id', $intAccountId);
            $this->db->where('sites.id', $intSiteId);

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrSites = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrSites[] = $arrRow;
                }
                $arrResult['results'] = $arrSites;
            }

            return $arrResult;
        } else {
            return false;
        }
    }

    public function doCheckSiteNameIsUniqueOnAccount($strName, $intAccountId) {
        if (($strName != "") && ($intAccountId > 0)) {
            $this->db->where('account_id', $intAccountId);
            $this->db->where('name', $strName);
            $this->db->from('sites');
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    public function getAll($intAccount = -1, $booActiveOnly = true) {
        if ($intAccount > 0) {
            // Run the query
            $this->db->select('sites.id AS siteid, sites.name AS sitename, sites.active AS siteactive');
            $this->db->from('sites');
            if ($booActiveOnly) {
                $this->db->where('active', 1);
            }
            $this->db->where('account_id', $intAccount);
            $this->db->order_by('sitename', 'ASC');

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query(), 'results' => array());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrSites = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrSites[] = $arrRow;
                }
                $arrResult['results'] = $arrSites;
            }

            return $arrResult;
        } else {
            return array();
        }
    }

    public function reactivateOne($intSiteId = -1) {
        if ($intSiteId > 0) {
            $this->db->where('id', $intSiteId);
            $arrInput = array('active' => 1);
            return $this->db->update('sites', $arrInput);
        }
        return false;
    }

    public function deleteOne($intSiteId = -1) {
        if (($intSiteId > 0) && ($this->doCheckSiteHasNoActiveItems($intSiteId))) {
            $this->db->where('id', $intSiteId);
            $arrInput = array('active' => 0);
            return $this->db->update('sites', $arrInput);
        }
        return false;
    }

    public function doCheckSiteHasNoActiveItems($intSiteId = -1) {
        if ($intSiteId > 0) {
            $this->db->select('items.id AS itemid,
			      items.site AS siteid');
            // we need to do a sub query, this
            $this->db->from('items');

            $this->db->where('items.site', $intSiteId);
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

    public function editOne($intSiteId = -1, $arrInput = array()) {
        if ($intSiteId > 0) {
            $this->db->where('id', $intSiteId);
            return $this->db->update('sites', $arrInput);
        }
        return false;
    }

    public function addOne($arrInput = array()) {
        return $this->db->insert('sites', $arrInput);
    }

    public function addOneAndReturnId($arrInput = array()) {
        if ($this->db->insert('sites', $arrInput)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function search($str = '', $account_id) {
        if ($str != '') {
            $str = preg_replace('/[^(\x20-\x7F)]*/', '', $str);
            $this->db->like('name', $str, 'both');
            $this->db->where('account_id', $account_id);
            $query = $this->db->get('sites');
            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                return $result['id'];
            } else {
                return false;
            }
        }
    }

    public function addSiteFromCsv($input_array, $account_id) {

        foreach ($input_array as $sites) {
            // Run the query
            $this->db->select('*');
            $this->db->from('sites');
            $this->db->where('sites.account_id', $account_id);
            $this->db->where('sites.name', $sites);

            $resQuery = $this->db->get();
            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                
            } else {
                $arr_site = array(
                    'name' => $sites,
                    'account_id' => $account_id
                );
                $this->db->insert('sites', $arr_site);
            }
        }
    }

    public function getlocationbysite($siteid = -1) {
        if ($siteid > 0) {
            // Run the query
            $this->db->select('*');
            $this->db->from('locations');
            $this->db->where('active', 1);
            $this->db->where('site_id', $siteid);
            $this->db->where('account_id', $this->session->userdata('objSystemUser')->accountid);


            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query(), 'results' => array());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrSites = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrSites[] = $arrRow;
                }
                $arrResult['results'] = $arrSites;
            }

            return $arrResult;
        } else {
            return array();
        }
    }

    public function getownerbysite($siteid = -1) {
        if ($siteid > 0) {
            // Run the query
            $this->db->select('owner.*');
            $this->db->from('owner');
            $this->db->where('owner.active', 1);
            $this->db->where('locations.site_id', $siteid);
            $this->db->where('owner.account_id', $this->session->userdata('objSystemUser')->accountid);
            $this->db->join('locations', 'locations.id=owner.location_id');
            $this->db->join('sites', 'sites.id=locations.site_id');

            $resQuery = $this->db->get();
            $arrResult = array('query' => $this->db->last_query(), 'results' => array());

            // Let's check if there are any results
            if ($resQuery->num_rows != 0) {
                $arrSites = array();
                // If there are levels, then load 
                foreach ($resQuery->result() as $arrRow) {
                    $arrSites[] = $arrRow;
                }
                $arrResult['results'] = $arrSites;
            }

            return $arrResult;
        } else {
            return array();
        }
    }

    public function getownerbylocation($locationid = -1) {
        if ($locationid > 0) {
            // Run the query
            $this->db->select('owner.*');
            $this->db->from('owner');
            $this->db->where('active', 1);
            $this->db->where('location_id', $locationid);
            $this->db->where('account_id', $this->session->userdata('objSystemUser')->accountid);

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
            return array();
        }
    }

}

?>