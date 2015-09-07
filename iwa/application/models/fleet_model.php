<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fleet_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getNumVechicles($account_id) {
        $query = $this->db->get_where('fleet', array('active' => 1, 'account_id' => $account_id));
        return $query->num_rows();
    }

    public function getTotalValue($account_id) {
        $this->db->select('SUM(current_value) AS totalvalue');
        $query = $this->db->get_where('fleet', array('active' => 1, 'account_id' => $account_id));
        return $query->row_array();
    }

    public function getLeasedOwned($account_id) {
        $query = $this->db->get_where('fleet', array('active' => 1, 'account_id' => $account_id, 'leased' => 1));
        $data['leased'] = $query->num_rows();
        $query = $this->db->get_where('fleet', array('active' => 1, 'account_id' => $account_id, 'leased' => 0));
        $data['not_leased'] = $query->num_rows();
        return $data;
    }

    public function getDueMot($account_id) {
        $fleet = $this->getFleetAll($account_id);
        $count = 0;
        foreach ($fleet as $key => $vehicle) {
            $last_mot = $this->getLastMot($vehicle['fleet_id']);
            $renewal_timestamp = strtotime($last_mot['mot_expiry_date']);
            $renewal_notice_timestamp = strtotime("-7 days", $renewal_timestamp);
            if (strtotime("now") >= $renewal_timestamp) {
                $count++;
            }
        }

        return $count;
    }

    public function getDueService($account_id) {
        $fleet = $this->getFleetAll($account_id);
        $count = 0;
        foreach ($fleet as $key => $vehicle) {
            $last_service = $this->getLastService($vehicle['fleet_id']);
            $renewal_timestamp = strtotime($last_service['service_expiry_date']);
            $renewal_notice_timestamp = strtotime("-7 days", $renewal_timestamp);
            if (strtotime("now") >= $renewal_timestamp) {
                $count++;
            }
        }

        return $count;
    }

    public function getDueTax($account_id) {
        $fleet = $this->getFleetAll($account_id);
        $count = 0;
        foreach ($fleet as $key => $vehicle) {
            $renewal_timestamp = strtotime($vehicle['tax_expiration']);
            $renewal_notice_timestamp = strtotime("-30 days", $renewal_timestamp);
            if (strtotime("now") >= $renewal_notice_timestamp) {
                $count++;
            }
        }

        return $count;
    }

    public function getDueInsurance($account_id) {

        $fleet = $this->getFleetAll($account_id);
        $count = 0;
        foreach ($fleet as $key => $vehicle) {
            $renewal_timestamp = strtotime($vehicle['insurance_expiration']);
            $renewal_notice_timestamp = strtotime("-30 days", $renewal_timestamp);
            if (strtotime("now") >= $renewal_notice_timestamp) {
                $count++;
            }
        }

        return $count;
    }

    public function getFleetAll($account_id) {
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $this->db->where('active', 1);
        $this->db->where('account_id', $account_id);

        $query = $this->db->get('fleet');
        $results = $query->result_array();

        /* Calculate all renewal dates and add to array */
        foreach ($results as $key => $vehicle) {

            $mot = $this->getLastMot($vehicle['fleet_id']);


            $tax = $this->getLastTax($vehicle['fleet_id']);
            $service = $this->getLastService($vehicle['fleet_id']);
            $owner_id = $this->whoOwnsThis($vehicle['fleet_id']);
            $site_id = $this->whichSiteIsThis($vehicle['fleet_id']);

            $user = $this->users_model->getOne($owner_id, $this->session->userdata('objSystemUser')->accountid);
            $site = $this->sites_model->getOne($site_id, $this->session->userdata('objSystemUser')->accountid);

            /* Insurance first */

            /* $results[$key]['insurance_renewal_timestamp'] = strtotime("+1 year", strtotime($vehicle['insurance_date']));
              $results[$key]['insurance_renewal_notice'] = strtotime("-30 days", $results[$key]['insurance_renewal_timestamp']);
              $results[$key]['insurance_due_date'] = date("d/m/Y", $results[$key]['insurance_renewal_timestamp']);
             */
            /* ... then MOT */
            $results[$key]['mot_renewal_notice'] = strtotime("-30 days", strtotime($mot['mot_expiry_date']));
            $results[$key]['mot_due_date'] = date("d-m-Y", strtotime($mot['mot_expiry_date']));

            /* ... then service */

            if ($service) {
                $results[$key]['service_renewal_notice'] = strtotime("-30 days", strtotime($service['service_expiry_date']));
                $results[$key]['service_due_date'] = date("d-m-Y", strtotime($service['service_expiry_date']));
            }


            /* .... leave the worst to last, tax! "The only things certain in life are death and taxes" - Benjamin Franklin */

            $results[$key]['tax_renewal_notice'] = strtotime("-30 days", strtotime($vehicle['tax_expiration']));
            $results[$key]['tax_due_date'] = date("d-m-Y", strtotime($vehicle['tax_expiration']));

            /* ..... add site and owner info */

            if (!empty($user['result'])) {
                $results[$key]['owner'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
            } else {
                $results[$key]['owner'] = '';
            }
            if (!empty($site['results'])) {
                $results[$key]['site'] = $site['results'][0]->sitename;
            } else {
                $results[$key]['site'] = '';
            }
        }

        return $results;
    }

    public function getAll($account_id) {
        $this->db->select('fleet_id AS makeid, make AS makename');
        $this->db->group_by('make');
        $query = $this->db->get_where('fleet', array('account_id' => $account_id));
        $arrData['results'] = $query->result();
        return $arrData;
    }

    public function getVehicle($vehicle_id, $option_account = NULL, $qr = FALSE) {

        $this->load->model('users_model');
        $this->load->model('sites_model');
        $this->db->select(
                'fleet.fleet_id,
            fleet.make,
            fleet.model,
            fleet.barcode,
            fleet.year,
            fleet.engine_size,
            fleet.reg_no,
            fleet.is_location,
            fleet.vehicle_value,
            fleet.current_value,
            fleet.last_service,
            fleet.active,
            fleet.tax_expiration,
            fleet.insurance_expiration,
            fleet.warranty_expiration AS warrantyexpiration,
            fleet.purchase_date AS purchasedate,
            fleet.notes,
            sites.name,
            locations.name,
            users.firstname,
            users.lastname,
            mark_deleted,
            mark_deleted_2,
            mark_deleted_date,
            mark_deleted_2_date,
            deleted_date'
        );
        $this->db->join('sites', 'sites.id = fleet.site_now', 'left');
        $this->db->join('locations', 'locations.id = fleet.location_now', 'left');
        $this->db->join('users', 'users.id = fleet.owner_now', 'left');

        if ($qr == FALSE) {

            if ($option_account != NULL) {
                if ($this->session->userdata('objAppUser')->accountid) {
                    $query = $this->db->get_where('fleet', array('fleet_id' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objAppUser')->accountid));
                } else {
                    $query = $this->db->get_where('fleet', array('fleet_id' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objAppUser')->accountid));
                }
            } else {
                if ($this->session->userdata('objAppUser')->accountid) {
                    $query = $this->db->get_where('fleet', array('fleet_id' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objAppUser')->accountid));
                } else {
                    $query = $this->db->get_where('fleet', array('fleet_id' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objSystemUser')->accountid));
                }
            }
        } else {
            if ($option_account != NULL) {
                if ($this->session->userdata('objAppUser')->accountid) {
                    $query = $this->db->get_where('fleet', array('fleet_id' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objAppUser')->accountid));
                } else {
                    $query = $this->db->get_where('fleet', array('fleet_id' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objAppUser')->accountid));
                }
            } else {

                if ($this->session->userdata('objAppUser')->accountid) {
                    $query = $this->db->get_where('fleet', array('fleet.barcode' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objAppUser')->accountid));
                } else {
                    $query = $this->db->get_where('fleet', array('fleet.barcode' => $vehicle_id, 'fleet.account_id' => $this->session->userdata('objSystemUser')->accountid));
                }
            }
        }

        $vehicle = $query->row_array();
        $mot = $this->getLastMot($vehicle['fleet_id']);
        $service = $this->getLastService($vehicle['fleet_id']);
        $tax = $this->getLastTax($vehicle['fleet_id']);
        /* Calculate all renewal dates and add to array */

        /* Insurance first */
        if (!empty($vehicle['insurance_expiration'])) {
            $vehicle['insurance_renewal_notice'] = strtotime("-30 days", strtotime($vehicle['insurance_expiration']));
            $vehicle['insurance_due_date'] = date("d/m/Y", strtotime($vehicle['insurance_expiration']));
        }

        /* ... then MOT */

        if (!empty($mot['mot_date'])) {
            $vehicle['mot_renewal_notice'] = strtotime("-30 days", strtotime($vehicle['mot_expiry_date']));
            $vehicle['mot_due_date'] = date("d/m/Y", strtotime($vehicle['mot_expiry_date']));
        }
        /* .... leave the worst to last, tax! "The only things certain in life are death and taxes" - Benjamin Franklin */
        if (!empty($tax['tax_date'])) {
            $vehicle['tax_renewal_notice'] = strtotime("-30 days", strtotime($vehicle['tax_expiration']));
            $vehicle['tax_due_date'] = date("d/m/Y", strtotime($vehicle['tax_expiration']));
        }

        if (!empty($service['service_date'])) {
            $vehicle['service_renewal_timestamp'] = strtotime("+1 year", strtotime($service['service_date']));
            $vehicle['service_renewal_notice'] = strtotime("-30 days", $vehicle['service_renewal_timestamp']);
            $vehicle['service_due_date'] = date("d/m/Y", $vehicle['service_renewal_timestamp']);
        }

        $owner_id = $this->whoOwnsThis($vehicle['fleet_id']);
        $site_id = $this->whichSiteIsThis($vehicle['fleet_id']);
        $location_id = $this->whereIsThis($vehicle['fleet_id']);
        $vehicle['owner_now'] = $owner_id;
        $vehicle['site_now'] = $site_id;
        $vehicle['location_now'] = $location_id;
        $vehicle['purchase_date'] = date("Y-m-d", strtotime($vehicle['purchasedate']));

        $vehicle['warranty_expiration'] = date("Y-m-d", strtotime($vehicle['warrantyexpiration']));

        $user = $this->users_model->getOne($owner_id, $this->session->userdata('objSystemUser')->accountid);
        $site = $this->sites_model->getOne($site_id, $this->session->userdata('objSystemUser')->accountid);

        if ($option_account != NULL) {
            $user = $this->users_model->getOne($owner_id, $this->session->userdata('objAppUser')->accountid);
            $site = $this->sites_model->getOne($site_id, $this->session->userdata('objAppUser')->accountid);
        }
        $vehicle['owner'] = $user['result'][0]->firstname . " " . $user['result'][0]->lastname;
        $vehicle['site'] = $site['results'][0]->sitename;
        if (!$vehicle['owner_now']) {
            $vehicle['Message'] = 'BarCode is Not Matching';
            return $vehicle;
        } else {
            return $vehicle;
        }
    }

    public function getMotHistory($vehicle_id) {
        $this->db->order_by("mot_date", "desc");

        $query = $this->db->get_where('fleet_mot', array('vehicle_id' => $vehicle_id));
        return $query->result_array();
    }

    public function getLastMot($vehicle_id) {
        $this->db->order_by("mot_date", "desc");
        $this->db->where('vehicle_id', $vehicle_id);
        $this->db->where('mot_result', 1);
        $query = $this->db->get('fleet_mot');
        if ($query->row_array()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function getServiceHistory($vehicle_id) {
        $this->db->order_by("service_date", "desc");
        $query = $this->db->get_where('fleet_service', array('vehicle_id' => $vehicle_id));
        return $query->result_array();
    }

    public function getLastService($vehicle_id) {
        $this->db->order_by("service_date", "desc");
        $this->db->where('vehicle_id', $vehicle_id);
        $query = $this->db->get('fleet_service');
        if ($query->row_array()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function getTaxHistory($vehicle_id) {
        $this->db->order_by("tax_date", "desc");
        $query = $this->db->get_where('fleet_tax', array('vehicle_id' => $vehicle_id));
        return $query->result_array();
    }

    public function getLastTax($vehicle_id) {
        $this->db->order_by("tax_date", "desc");
        $this->db->where('vehicle_id', $vehicle_id);
        $query = $this->db->get('fleet_tax');
        if ($query->row_array()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function addVehicle($data) {


        $checks = '';
        $rows = count($data['checks']);
        $count = 0;
        foreach ($data['checks'] as $value) {
            $count++;

            $checks .= $value;
            if ($count == $rows) {
                break;
            } else {
                $checks .= ',';
            }
        }
        $ts = strtotime(str_replace('/', '.', $data['warranty_expiration']));
        if ($ts > 0) {
            $warranty_expiration = date('Y-m-d', $ts);
        }
        $ts = strtotime(str_replace('/', '.', $data['insurance_expiration']));
        if ($ts > 0) {
            $insurance_expiration = date('Y-m-d', $ts);
        }
        $ts = strtotime(str_replace('/', '.', $data['date_of_purchase']));
        if ($ts > 0) {
            $purchase_date = date('Y-m-d', $ts);
        }
        $ts = strtotime(str_replace('/', '.', $data['tax_expiration']));
        if ($ts > 0) {
            $tax_expiration = date('Y-m-d', $ts);
        }

        $checks = trim($checks, ',');

        /* Insert Basic Details */
        $data_insert = array('make' => $data['make'],
            'model' => $data['model'],
            'barcode' => $data['vehicle_barcode'],
            'year' => $data['year'],
            'vehicle_value' => str_replace('£', '', $data['vehicle_value']),
            'engine_size' => $data['engine_size'],
            'reg_no' => $data['reg_no'],
            'is_location' => $this->input->post('is_location'),
            'insurance_expiration' => $insurance_expiration,
            'warranty_expiration' => $warranty_expiration,
            'tax_expiration' => $tax_expiration,
            'purchase_date' => $purchase_date,
            'account_id' => $this->session->userdata('objSystemUser')->accountid,
            'notes' => $data['notes'],
            'checks' => $checks
        );
        $this->db->set($data_insert);
        $this->db->insert('fleet');
        $vehicle_id = $this->db->insert_id();

        if (isset($data['user_id']) || isset($data['site_id']) || isset($data['location_id'])) {

            if (($data['user_id'] > 0) && ($this->whoOwnsThis($vehicle_id) != $data['user_id'])) {

                $this->linkThisToUser($vehicle_id, $data['user_id']);
            } else {

                if ($data['user_id'] == 0) {

                    $this->clearCurrentUser($vehicle_id);
                }
            }


            if (($data['site_id'] > 0) && ($this->whichSiteIsThis($vehicle_id) != $data['site_id'])) {

                $this->linkThisToSite($vehicle_id, $data['site_id']);
            } else {

                if ($data['site_id'] == 0) {

                    $this->clearCurrentSite($vehicle_id);
                }
            }

            if (($data['location_id'] > 0) && ($this->whereIsThis($vehicle_id) != $data['location_id'])) {

                $this->linkThisToLocation($vehicle_id, $data['location_id']);
            } else {

                if ($data['location_id'] == 0) {

                    $this->clearCurrentLocation($vehicle_id);
                }
            }
        }

        return $vehicle_id;
    }

    public function newMot($data) {

        $ex = explode('/', $data['mot_date']);
        $data['mot_date'] = $ex[2] . "-" . $ex[1] . "-" . $ex[0];

        $ex = explode('/', $data['mot_expiry_date']);
        $data['mot_expiry_date'] = $ex[2] . "-" . $ex[1] . "-" . $ex[0];

        $data_insert = array("mot_date" => $data['mot_date'],
            "mot_expiry_date" => $data['mot_expiry_date'],
            "vehicle_id" => $data['vehicle_id'],
            "mot_result" => $data['mot_result'],
            "mot_cert_no" => $data['mot_cert_no'],
            "mot_notes" => $data['mot_notes']
        );
        $this->db->set($data_insert);
        $this->db->insert('fleet_mot');
        return true;
    }

    public function newTax($data) {
        $data_insert = array("tax_date" => $data['tax_date'],
            "vehicle_id" => $data['vehicle_id'],
            "tax_disc_no" => $data['tax_disc_no'],
            "tax_notes" => $data['tax_notes']
        );
        $this->db->set($data_insert);
        $this->db->insert('fleet_tax');
        return true;
    }

    public function newService($data) {
        $ex = explode('/', $data['service_date']);
        $data['service_date'] = $ex[2] . "-" . $ex[1] . "-" . $ex[0];

        $ex = explode('/', $data['service_expiry_date']);
        $data['service_expiry_date'] = $ex[2] . "-" . $ex[1] . "-" . $ex[0];

        $data_insert = array("service_date" => $data['service_date'],
            "service_expiry_date" => $data['service_expiry_date'],
            "vehicle_id" => $data['vehicle_id'],
            "service_ref_no" => $data['service_ref_no'],
            "service_notes" => $data['service_notes']
        );

        $this->db->set($data_insert);
        $this->db->insert('fleet_service');
        return true;
    }

    public function updateVehicle($vehicle_id, $vehicle) {
        /* Check if ownership fields are set */

        if (isset($vehicle['user_id']) || isset($vehicle['site_id']) || isset($vehicle['location_id'])) {

            if (($vehicle['user_id'] > 0) && ($this->whoOwnsThis($vehicle_id) != $vehicle['user_id'])) {

                $this->linkThisToUser($vehicle_id, $vehicle['user_id']);
            } else {

                if ($vehicle['user_id'] == 0) {

                    $this->clearCurrentUser($vehicle_id);
                }
            }


            if (($vehicle['site_id'] > 0) && ($this->whichSiteIsThis($vehicle_id) != $vehicle['site_id'])) {

                $this->linkThisToSite($vehicle_id, $vehicle['site_id']);
            } else {

                if ($vehicle['site_id'] == 0) {

                    $this->clearCurrentSite($vehicle_id);
                }
            }

            if (($vehicle['location_id'] > 0) && ($this->whereIsThis($vehicle_id) != $vehicle['location_id'])) {

                $this->linkThisToLocation($vehicle_id, $vehicle['location_id']);
            } else {

                if ($vehicle['location_id'] == 0) {

                    $this->clearCurrentLocation($vehicle_id);
                }
            }
        }

        /* update fleet table first first */
        if ($vehicle['warranty_expiration'] != null) {
            $ts = strtotime(str_replace('/', '.', $vehicle['warranty_expiration']));
            $warranty_expiration = date('Y-m-d', $ts);
        }

        if ($vehicle['insurance_expiration'] != null) {
            $ts = strtotime(str_replace('/', '.', $vehicle['insurance_expiration']));
            $insurance_expiration = date('Y-m-d', $ts);
        }

        if ($vehicle['purchase_date'] != null) {
            $ts = strtotime(str_replace('/', '.', $vehicle['purchase_date']));
            $purchase_date = date('Y-m-d', $ts);
        }

        if ($vehicle['tax_expiration'] != null) {
            $ts = strtotime(str_replace('/', '.', $vehicle['tax_expiration']));
            $tax_expiration = date('Y-m-d', $ts);
        }

        $upate_array = array(
            'barcode' => $vehicle['barcode'],
            'make' => $vehicle['make'],
            'model' => $vehicle['model'],
            'year' => $vehicle['year'],
            'vehicle_value' => str_replace('£', '', $vehicle['vehicle_value']),
            'engine_size' => $vehicle['engine_size'],
            'reg_no' => $vehicle['reg_no'],
            'purchase_date' => $purchase_date,
            'warranty_expiration' => $warranty_expiration,
            'insurance_expiration' => $insurance_expiration,
            'tax_expiration' => $tax_expiration,
            'is_location' => $vehicle['is_location'],
            'notes' => $vehicle['notes'],
            'checks' => $vehicle['checks']
        );


        $this->db->where('fleet_id', $vehicle_id);
        $this->db->update('fleet', $upate_array);
        return true;
    }

    public function getHistory($intFleetId = -1) {

        $arrFleetHistory = array();
        if (($intFleetId > 0)) {

            $arrFleetUserHistory = $this->getUserHistory($intFleetId);
            $arrFleetSiteHistory = $this->getSiteHistory($intFleetId);
            $arrFleetLocationHistory = $this->getLocationHistory($intFleetId);
            $arrFleetDepreciationHistory = $this->getDepreciationHIstory($intFleetId);

            //$arrFleetSiteHistory = $this->getSiteHistory($intFleetId);

            foreach ($arrFleetUserHistory as $objHistory) {
                $arrFleetHistory[$objHistory->date]['user'] = $objHistory;
            }
            foreach ($arrFleetLocationHistory as $objHistory) {
                $arrFleetHistory[$objHistory->date]['location'] = $objHistory;
            }
            foreach ($arrFleetSiteHistory as $objHistory) {
                $arrFleetHistory[$objHistory->date]['site'] = $objHistory;
            }
            foreach ($arrFleetDepreciationHistory as $objDepreciation) {
                $arrFleetHistory[$objDepreciation['date']]['depreciation'] = $objDepreciation;
            }

            ksort($arrFleetHistory);
            $arrFleetHistory = array_reverse($arrFleetHistory);
        }
        return $arrFleetHistory;
    }

    public function getUserHistory($intFleetId) {
        $arrResults = array();
        $this->db->select('fleet_users_link.date,
                            users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname');
        $this->db->from('fleet_users_link');
        $this->db->join('users', 'fleet_users_link.user_id = users.id', 'left');
        $this->db->where('fleet_users_link.item_id', $intFleetId);
        $this->db->order_by('fleet_users_link.date ASC');
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrResults[] = $objRow;
            }
        }
        return $arrResults;
    }

    public function getDepreciationHIstory($intFleetId) {
        $this->db->select('fleet_depreciation.fleet_id, fleet_depreciation.value, fleet_depreciation.date, fleet_depreciation.user_id, users.firstname, users.lastname');
        $this->db->from('fleet_depreciation');
        $this->db->join('users', 'fleet_depreciation.user_id = users.id');
        $this->db->where('fleet_id', $intFleetId);
        $this->db->order_by('date DESC');
        $resQuery = $this->db->get();
        return $resQuery->result_array();
    }

    public function getLocationHistory($intFleetId) {
        $arrResults = array();
        $this->db->select('items_locations_link.date,
                            locations.id AS locationid, locations.name AS locationname');
        $this->db->from('items_locations_link');
        $this->db->join('locations', 'items_locations_link.location_id = locations.id', 'left');
        $this->db->where('items_locations_link.item_id', $intFleetId);
        $this->db->order_by('items_locations_link.date ASC');
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrResults[] = $objRow;
            }
        }
        return $arrResults;
    }

    public function getSiteHistory($intFleetId) {
        $arrResults = array();
        $this->db->select('fleet_sites_link.date,
                            sites.id AS siteid, sites.name AS sitename');
        $this->db->from('fleet_sites_link');
        $this->db->join('sites', 'fleet_sites_link.site_id = sites.id', 'left');
        $this->db->where('fleet_sites_link.fleet_id', $intFleetId);
        $this->db->order_by('fleet_sites_link.date ASC');
        $resQuery = $this->db->get();

        if ($resQuery->num_rows() > 0) {
            foreach ($resQuery->result() as $objRow) {
                $arrResults[] = $objRow;
            }
        }
        return $arrResults;
    }

    public function linkThisToUser($intFleetId = -1, $intUserId = -1) {
        if (($intFleetId > 0) && ($intUserId > 0)) {
            $this->db->insert('fleet_users_link', array('item_id' => $intFleetId, 'user_id' => $intUserId, 'date' => date('Y-m-d H:i:s')));
            $this->db->where('fleet_id', $intFleetId);
            $this->db->update('fleet', array('owner_now' => $intUserId, 'owner_since' => date('Y-m-d H:i:s')));
//        print $this->db->last_query();
        }
    }

    public function linkThisToSite($intFleetId = -1, $intSiteId = -1) {
        if (($intFleetId > 0) && ($intSiteId > 0)) {
            //die($intFleetId . " " . $intSiteId);
            $this->db->insert('fleet_sites_link', array('fleet_id' => $intFleetId, 'site_id' => $intSiteId, 'date' => date('Y-m-d H:i:s')));
            $this->db->where('fleet_id', $intFleetId);
            $this->db->update('fleet', array('site_now' => $intSiteId, 'site_since' => date('Y-m-d H:i:s')));
        }
    }

    private function whoOwnsThis($intFleetId = -1) {
        if ($intFleetId > 0) {
            $this->db->select('fleet_users_link.date,
			      fleet.fleet_id AS fleetid,
			      users.id AS userid');
            // we need to do a sub query, this
            $this->db->from('( 
				SELECT 
				    max(`date`) as most_recent_date 
				FROM 
				    fleet_users_link 
				GROUP BY  
				    item_id 
				) q2');
            $this->db->join('fleet_users_link', 'fleet_users_link.date = most_recent_date');
            $this->db->join('fleet', 'fleet_users_link.item_id = fleet.fleet_id', 'left');
            $this->db->join('users', 'fleet_users_link.user_id = users.id', 'left');
            $this->db->where('fleet_users_link.item_id', $intFleetId);
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return $resQuery->row()->userid;
            }
        }
        return false;
    }

    private function whichSiteIsThis($intFleetId = -1) {
        if ($intFleetId > 0) {
            $this->db->select('fleet_sites_link.date,
			      fleet.fleet_id AS fleetid,
			      sites.id AS siteid');
            // we need to do a sub query, this
            $this->db->from('( 
				SELECT 
				    max(`date`) as most_recent_date 
				FROM 
				    fleet_sites_link 
				GROUP BY  
				    fleet_id 
				) q2');
            $this->db->join('fleet_sites_link', 'fleet_sites_link.date = most_recent_date');
            $this->db->join('fleet', 'fleet_sites_link.fleet_id = fleet.fleet_id', 'left');
            $this->db->join('sites', 'fleet_sites_link.site_id = sites.id', 'left');
            $this->db->where('fleet_sites_link.fleet_id', $intFleetId);
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return $resQuery->row()->siteid;
            }
        }
        return false;
    }

    public function checkReg($reg) {
        $reg = str_replace(" ", "", $reg);
        $query = $this->db->query("SELECT reg_no FROM (`fleet`) WHERE REPLACE(reg_no, ' ', '') = '" . $reg . "'");

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getRenewals($account_id) {
        $fleet = $this->getFleetAll($account_id);

        foreach ($fleet as $key => $vehicle) {
            /* Check MOT */
            if (strtotime("now") >= $vehicle['mot_renewal_notice']) {
                $mot_date = date("Y-m-d H:i:s", $vehicle['mot_renewal_notice']);
                $fleet[$key]['mot_due'] = 1;
            } else {
                $fleet[$key]['mot_due'] = 0;
            }

            /* Check Tax */
            if (strtotime("now") >= $vehicle['tax_renewal_notice']) {
                $tax_date = date("Y-m-d H:i:s", $vehicle['tax_renewal_notice']);
                $fleet[$key]['tax_due'] = 1;
            } else {
                $fleet[$key]['tax_due'] = 0;
            }

            /* Check Service */
            if (strtotime("now") >= $vehicle['mot_renewal_notice']) {
                $service_date = date("Y-m-d H:i:s", $vehicle['service_renewal_notice']);
                $fleet[$key]['service_due'] = 1;
            } else {

                $fleet[$key]['service_due'] = 0;
            }
        }
        return $fleet;
    }

    public function remove($vehicle_id) {
        $this->db->where('account_id', $this->session->userdata('objSystemUser')->accountid);
        $this->db->where('fleet_id', $vehicle_id);
        $this->db->update('fleet', array('active' => 0));
        return true;
    }

    public function isLocation($fleet_id) {
        $this->load->model('items_model');
        $this->items_model->getFleetItemID($fleet_id);
    }

    public function clearCurrentSite($intId) {
        $this->db->where('fleet_id', $intId);
        $this->db->update('fleet', array('site_now' => 0, 'site_since' => date('Y-m-d H:i:s')));
    }

    public function clearCurrentUser($intId) {
        $this->db->where('fleet_id', $intId);
        $this->db->update('fleet', array('owner_now' => 0, 'owner_since' => date('Y-m-d H:i:s')));
    }

    public function clearCurrentLocation($intId) {
        $this->db->where('fleet_id', $intId);
        $this->db->update('fleet', array('location_now' => 0, 'location_since' => date('Y-m-d H:i:s')));
    }

    public function markDeleted($intFleetId, $intAccountId, $intUserId, $booIsSuperAdmin) {
        $strDate = date('Y-m-d H:i:s');
        $arrData = array();
        if ($booIsSuperAdmin) {
            $arrData['mark_deleted_2'] = (int) $intUserId;
            $arrData['mark_deleted_2_date'] = $strDate;
        } else {
            $arrData['mark_deleted'] = (int) $intUserId;
            $arrData['mark_deleted_date'] = $strDate;
        }
        $this->db->where('fleet_id', (int) $intFleetId);
        $this->db->where('account_id', (int) $intAccountId);
        $this->db->update('fleet', $arrData);
    }

    public function getAwaitingDeletion($intAccountId, $intUserId, $intLevelId) {
        if ($intAccountId > 0) {
            $this->db->select('
                fleet.fleet_id AS fleetid, fleet.reg_no, fleet.make, fleet.model, fleet.owner_since AS currentownerdate, fleet.location_since AS currentlocationdate, fleet.site_now, fleet.current_value, fleet.mark_deleted_date, fleet.mark_deleted_2_date,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname, users.level_id');

            $this->db->from('fleet');

            if ($intLevelId == 4) {
                $this->db->join('users', 'fleet.mark_deleted = users.id', 'left');
            } else {
                $this->db->join('users', 'fleet.mark_deleted_2 = users.id', 'left');
            }

            $this->db->where('fleet.account_id', $intAccountId);

            //isn't already deleted
            $this->db->where('fleet.active', 1);

            //check which user deleted them
            if ($intLevelId == 4) {
                // superadmin enquiry, therefore items where a standard admin has marked
                $this->db->where('fleet.mark_deleted !=', 0);
                $this->db->where('fleet.mark_deleted !=', $intUserId);
                // awaiting a superuser mark too
                $this->db->where('fleet.mark_deleted_2', 0);

                $this->db->order_by('fleet.mark_deleted DESC');
            } else {
                // standard admin enquiry, therefore only items which a superuser has marked
                $this->db->where('fleet.mark_deleted_2 !=', 0);
                $this->db->where('fleet.mark_deleted_2 !=', $intUserId);
                // awaiting admin mark too
                $this->db->where('fleet.mark_deleted', 0);

                $this->db->order_by('fleet.mark_deleted_2 DESC');
            }




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

    public function confirmDeletion($intFleetId, $intAccountId, $intUserId, $intLevelId) {

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
        $this->db->where('fleet_id', (int) $intFleetId);
        $this->db->where('account_id', (int) $intAccountId);
        $this->db->update('fleet', $arrData);
    }

    public function whereIsThis($intFleetId = -1) {
        if ($intFleetId > 0) {
            $this->db->select('fleet_locations_link.date,
			      fleet.fleet_id AS fleetid,
			      locations.id AS locationid');
            // we need to do a sub query, this
            $this->db->from('( 
				SELECT 
				    max(`date`) as most_recent_date 
				FROM 
				    fleet_locations_link 
				GROUP BY  
				    item_id 
				) q2');
            $this->db->join('fleet_locations_link', 'fleet_locations_link.date = most_recent_date');
            $this->db->join('fleet', 'fleet_locations_link.item_id = fleet.fleet_id', 'left');
            $this->db->join('locations', 'fleet_locations_link.location_id = locations.id', 'left');
            $this->db->where('fleet_locations_link.item_id', $intFleetId);
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {
                return $resQuery->row()->locationid;
            }
        }
        return false;
    }

    public function linkThisToLocation($intFleetId = -1, $intLocationId = -1) {
        if (($intFleetId > 0) && ($intLocationId > 0)) {
            $this->db->insert('fleet_locations_link', array('item_id' => $intFleetId, 'location_id' => $intLocationId, 'date' => date('Y-m-d H:i:s')));
            $this->db->where('fleet_id', $intFleetId);
            $this->db->update('fleet', array('location_now' => $intLocationId, 'location_since' => date('Y-m-d H:i:s')));
        }
    }

    public function depreciateThis($intItemId, $floValue) {

        $this->db->where('fleet_id', $intItemId);

        /* Record in vehicle history */
        $this->db->insert('fleet_depreciation', array('fleet_id' => $intItemId, 'value' => $floValue, 'date' => date('Y-m-d', strtotime('now')), 'user_id' => $this->session->userdata('objSystemUser')->userid));
        return $this->db->update('fleet', array('current_value' => $floValue));
    }

    public function getChecks() {

        $this->db->select('*');
        $this->db->from('vehicle_checks');
        $data = array('active' => 1, 'account_id' => $this->session->userdata('objSystemUser')->accountid, 'default' => 0);
        $this->db->where($data);
        $this->db->order_by("order_id", "asc");
        $resQuery = $this->db->get();
        $checks = $resQuery->result_array();
        return $checks;
    }

    public function updateOrderChecks($vehicle_order) {

        for ($i = 0; $i <= count($vehicle_order); $i++) {
            $data = array(
                'order_id' => $i + 1,
            );
            $this->db->where('id', $vehicle_order[$i]);
            $this->db->update('vehicle_checks', $data);
        }
    }

    public function getDefaultChecks() {

        $query = $this->db->get_where('vehicle_checks', array('default' => 1, 'active' => 1));

        return $query->result_array();
    }

    public function getCheck($check_id) {
        $query = $this->db->get_where('vehicle_checks', array('id' => $check_id));
        return $query->row();
    }

    public function newDefaultCheck($data) {
        $data['default'] = 1;
        $data['account_id'] = 0;
        $this->db->insert('vehicle_checks', $data);
        return true;
    }

    public function newCheck($data) {
        $data['account_id'] = $this->session->userdata('objSystemUser')->accountid;
        $this->db->insert('vehicle_checks', $data);
        return true;
    }

    public function editCheck($data) {

        $edit_data = array(
            'check_name' => $data['edit_check_name'],
            'check_short_description' => $data['check_short_description'],
            'check_long_description' => $data['check_long_description'],
        );
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id', $id);
        $this->db->update('vehicle_checks', $edit_data);
        return true;
    }

    public function editCheckStatus($id, $status) {
        $this->db->where('id', $id);
        $data = array('active' => $status);
        $this->db->update('vehicle_checks', $data);
        return true;
    }

    public function getDeletedChecks() {
        $this->db->order_by('default', 'DESC');
        $query = $this->db->get_where('vehicle_checks', array('active' => 0));

        return $query->result_array();
    }

    public function getChecksByVehicle($vehicle_id, $qr = FALSE) {
        $this->db->select('checks');
        if ($qr == FALSE) {
            $query = $this->db->get_where('fleet', array('fleet_id' => $vehicle_id));
        } else {
            $query = $this->db->get_where('fleet', array('barcode' => $vehicle_id));
        }
        if ($query->num_rows() > 0) {
            $vehicle = $query->row();
            return explode(',', $vehicle->checks);
        } else {
            return false;
        }
    }

    public function getCheckDetailsByVehicle($vehicleID, $qr = FALSE) {
        if ($qr == FALSE) {
            $checks = $this->getChecksByVehicle($vehicleID);
        } else {
            $checks = $this->getChecksByVehicle($vehicleID, TRUE);
        }

        foreach ($checks as $check) {

            $arrChecks[$check] = $this->getCheck($check);
        }
        $sortArray = array();

        foreach ($arrChecks as $person) {
            foreach ($person as $key => $value) {

                if (!isset($sortArray[$key])) {
                    $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
            }
        }

        $orderby = "order_id"; //change this to whatever key you want from the array

        array_multisort($sortArray[$orderby], SORT_ASC, $arrChecks);

        $finalArrChecks = array();
        foreach ($arrChecks as $key => $value) {
            $finalArrChecks[$value->id] = $value;
        }

        return $finalArrChecks;
    }

    public function listMakes($intAccountId = -1) {
        if ($intAccountId > 0) {
            $this->db->select('fleet_id, make');
            $this->db->distinct();
            $this->db->from('fleet');
            $this->db->where('account_id', $intAccountId);
            $this->db->where('active', 1);
            $this->db->order_by('make', 'ASC');
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

    public function logChecks($vehicleID, $data) {
        $passed = rtrim($data['passed'], ',');
        $failed = rtrim($data['failed'], ',');

        $arrFailed = explode(',', $failed);

        $insert_data = array(
            'vehicle_id' => $vehicleID,
            'passed_checks' => $passed,
            'failed_checks' => $failed,
            'date_time' => time(),
            'user_id' => $this->session->userdata('objAppUser')->userid,
            'account_id' => $this->session->userdata('objAppUser')->accountid
        );
        $this->db->insert('vehicle_checks_log', $insert_data);
        $log_id = $this->db->insert_id();

        foreach ($arrFailed as $check) {
            $log_insert = array(
                'log_id' => $log_id,
                'check_id' => $check,
                'message' => $data['notes_' . $check]
            );
            $this->db->insert('vehicle_checks_notes', $log_insert);
        }
    }

    /**
     * Get all checks
     */
    public function getAllReport($start = NULL, $end = NULL, $account_id) {
        if ($start || $end) {

            $this->db->select('vehicle_checks_log.log_id, vehicle_checks_log.vehicle_id, vehicle_checks_log.date_time, vehicle_checks_log.user_id, vehicle_checks_log.failed_checks, vehicle_checks_log.passed_checks, fleet.make, fleet.model, fleet.barcode, fleet.reg_no');
            $this->db->join('fleet', 'vehicle_checks_log.vehicle_id = fleet.fleet_id', 'left');
            $this->db->order_by('date_time', 'desc');
            $query = $this->db->get_where('vehicle_checks_log', array('vehicle_checks_log.account_id' => $account_id, 'date_time >=' => strtotime($start), 'date_time <=' => strtotime($end) + 24 * 60 * 60));
        } else {
            $this->db->select('vehicle_checks_log.log_id, vehicle_checks_log.vehicle_id, vehicle_checks_log.date_time, vehicle_checks_log.user_id, vehicle_checks_log.failed_checks, vehicle_checks_log.passed_checks, fleet.make, fleet.model, fleet.barcode, fleet.reg_no');
            $this->db->join('fleet', 'vehicle_checks_log.vehicle_id = fleet.fleet_id', 'left');
            $this->db->order_by('date_time', 'desc');
            $query = $this->db->get_where('vehicle_checks_log', array('vehicle_checks_log.account_id' => $account_id));
        }
        $arrChecks = array();
        foreach ($query->result_array() as $check) {
            //print "<pre>"; print_r($check); print "</pre>";
            $arrFailedChecks = explode(',', $check['failed_checks']);
            $arrPassedChecks = explode(',', $check['passed_checks']);
            foreach ($arrPassedChecks as $passed_check) {

                $check_detail = $this->getCheck($passed_check);
                $check_submit = $check;
                $check_submit['check_name'] = $check_detail->check_name;
                $check_submit['check_description'] = $check_detail->check_description;
                $check_submit['makemodel'] = $check['make'] . ' ' . $check['model'];
                $check_user = $this->users_model->getone($check['user_id'], $account_id);
                $check_submit['user_name'] = $check_user['result'][0]->firstname . ' ' . $check_user['result'][0]->lastname;

                $objNote = $this->getNote($check['log_id'], $passed_check);
                $check_submit['check_note'] = $objNote->message;
                unset($check_submit['failed_checks']);
                unset($check_submit['passed_checks']);
                unset($check_submit['user_id']);
                $check_submit['date_time'] = date('d/m/Y H:i', $check_submit['date_time']);
                $check_submit['result'] = 'Pass';
                array_push($arrChecks, $check_submit);
            }

            foreach ($arrFailedChecks as $failed_check) {

                $check_detail = $this->getCheck($failed_check);
                $check_submit = $check;
                $check_submit['check_name'] = $check_detail->check_name;
                $check_submit['check_description'] = $check_detail->check_description;
                $check_submit['makemodel'] = $check['make'] . ' ' . $check['model'];
                $check_user = $this->users_model->getone($check['user_id'], $account_id);
                $check_submit['user_name'] = $check_user['result'][0]->firstname . ' ' . $check_user['result'][0]->lastname;

                $objNote = $this->getNote($check['log_id'], $failed_check);
                $check_submit['check_note'] = $objNote->message;
                unset($check_submit['failed_checks']);
                unset($check_submit['passed_checks']);
                unset($check_submit['user_id']);
                $check_submit['date_time'] = date('d/m/Y H:i', $check_submit['date_time']);
                $check_submit['result'] = 'Fail';
                array_push($arrChecks, $check_submit);
            }
        }
        $data['results'] = $arrChecks;
        return $data;
    }

    /**
     * Get checks which have failed between dates (if supplied)
     */
    public function getFailedReport($start = NULL, $end = NULL, $account_id) {
        if ($start || $end) {

            $this->db->select('vehicle_checks_log.log_id, vehicle_checks_log.vehicle_id, vehicle_checks_log.date_time, vehicle_checks_log.user_id, vehicle_checks_log.failed_checks, vehicle_checks_log.passed_checks, fleet.make, fleet.model, fleet.barcode, fleet.reg_no');
            $this->db->join('fleet', 'vehicle_checks_log.vehicle_id = fleet.fleet_id', 'left');
            $query = $this->db->get_where('vehicle_checks_log', array('vehicle_checks_log.account_id' => $account_id, 'failed_checks !=' => 'NULL', 'date_time >=' => strtotime($start), 'date_time <=' => strtotime($end) + 24 * 60 * 60));
        } else {
            $this->db->select('vehicle_checks_log.log_id, vehicle_checks_log.vehicle_id, vehicle_checks_log.date_time, vehicle_checks_log.user_id, vehicle_checks_log.failed_checks, vehicle_checks_log.passed_checks, fleet.make, fleet.model, fleet.barcode, fleet.reg_no');
            $this->db->join('fleet', 'vehicle_checks_log.vehicle_id = fleet.fleet_id', 'left');
            $query = $this->db->get_where('vehicle_checks_log', array('vehicle_checks_log.account_id' => $account_id, 'failed_checks !=' => 'NULL'));
        }
        $arrChecks = array();
        foreach ($query->result_array() as $check) {
            //print "<pre>"; print_r($check); print "</pre>";
            $arrFailedChecks = explode(',', $check['failed_checks']);
            foreach ($arrFailedChecks as $failed_check) {

                $check_detail = $this->getCheck($failed_check);
                $check_submit = $check;
                $check_submit['check_name'] = $check_detail->check_name;
                $check_submit['check_description'] = $check_detail->check_description;
                $check_submit['makemodel'] = $check['make'] . ' ' . $check['model'];
                $check_user = $this->users_model->getone($check['user_id'], $account_id);
                $check_submit['user_name'] = $check_user['result'][0]->firstname . ' ' . $check_user['result'][0]->lastname;

                $objNote = $this->getNote($check['log_id'], $failed_check);
                $check_submit['check_note'] = $objNote->message;
                unset($check_submit['failed_checks']);
                unset($check_submit['passed_checks']);
                unset($check_submit['user_id']);
                $check_submit['date_time'] = date('d/m/Y H:i', $check_submit['date_time']);
                array_push($arrChecks, $check_submit);
            }
        }
        $data['results'] = $arrChecks;
        return $data;
    }

    private function getNote($log_id, $check_id) {

        $query = $this->db->get_where('vehicle_checks_notes', array('log_id' => $log_id, 'check_id' => $check_id));
        return $query->row();
    }

    public function getCheckHistory($vehicle_id) {
        $this->load->model('users_model');
        $this->db->order_by("date_time", "desc");
        $query = $this->db->get_where('vehicle_checks_log', array('vehicle_id' => $vehicle_id));
        $results = $query->result_array();

        foreach ($results as $key => $check) {
            // CHECKS IF THERE'S ACTUALLY ANYTHING IN THE FIELD
            if ($check['passed_checks'] != '') {
                $arrPassed = explode(',', trim($check['passed_checks'], ','));
                $results[$key]['num_passed'] = count($arrPassed);
            } else {
                $results[$key]['num_passed'] = '';
            }

            // CHECKS IF THERE'S ACTUALLY ANYTHING IN THE FIELD
            if ($check['failed_checks'] != '') {
                $arrFailed = explode(',', trim($check['failed_checks'], ','));
                $results[$key]['num_failed'] = count($arrFailed);
            } else {
                $results[$key]['num_failed'] = '';
            }

            $check_user = $this->users_model->getone($check['user_id'], $this->session->userdata('objSystemUser')->accountid);
            $results[$key]['username'] = $check_user['result'][0]->firstname . ' ' . $check_user['result'][0]->lastname;
        }

        return $results;
    }

    public function getCompleteCheck($log_id) {
        $this->load->model('users_model');
        $this->db->select('vehicle_checks_log.log_id, vehicle_checks_log.vehicle_id, vehicle_checks_log.date_time, vehicle_checks_log.user_id, vehicle_checks_log.failed_checks, vehicle_checks_log.passed_checks, fleet.make, fleet.model, fleet.barcode, fleet.reg_no');
        $this->db->join('fleet', 'vehicle_checks_log.vehicle_id = fleet.fleet_id', 'left');
        $query = $this->db->get_where('vehicle_checks_log', array('log_id' => $log_id));

        $arrChecks = array();
        foreach ($query->result_array() as $check) {
            //print "<pre>"; print_r($check); print "</pre>";
            $arrFailedChecks = explode(',', $check['failed_checks']);
            $arrPassedChecks = explode(',', $check['passed_checks']);
            foreach ($arrPassedChecks as $passed_check) {
                $check_detail = $this->getCheck($passed_check);
                $check_submit = $check;
                $check_submit['check_name'] = $check_detail->check_name;
                $check_submit['check_description'] = $check_detail->check_description;
                $check_submit['makemodel'] = $check['make'] . ' ' . $check['model'];
                $check_user = $this->users_model->getone($check['user_id'], $this->session->userdata('objSystemUser')->accountid);
                $check_submit['user_name'] = $check_user['result'][0]->firstname . ' ' . $check_user['result'][0]->lastname;

                $objNote = $this->getNote($check['log_id'], $passed_check);
                $check_submit['check_note'] = $objNote->message;
                unset($check_submit['failed_checks']);
                unset($check_submit['passed_checks']);
                unset($check_submit['user_id']);
                $check_submit['date_time'] = date('d/m/Y H:i', $check_submit['date_time']);
                $check_submit['result'] = 'Pass';
                array_push($arrChecks, $check_submit);
            }

            foreach ($arrFailedChecks as $failed_check) {

                $check_detail = $this->getCheck($failed_check);
                $check_submit = $check;
                $check_submit['check_name'] = $check_detail->check_name;
                $check_submit['check_description'] = $check_detail->check_description;
                $check_submit['makemodel'] = $check['make'] . ' ' . $check['model'];
                $check_user = $this->users_model->getone($check['user_id'], $this->session->userdata('objSystemUser')->accountid);
                $check_submit['user_name'] = $check_user['result'][0]->firstname . ' ' . $check_user['result'][0]->lastname;

                $objNote = $this->getNote($check['log_id'], $failed_check);
                $check_submit['check_note'] = $objNote->message;
                unset($check_submit['failed_checks']);
                unset($check_submit['passed_checks']);
                unset($check_submit['user_id']);
                $check_submit['date_time'] = date('d/m/Y H:i', $check_submit['date_time']);
                $check_submit['result'] = 'Fail';
                array_push($arrChecks, $check_submit);
            }
        }
        $data['results'] = $arrChecks;
        return $data;
    }

    public function check_qrExists($qr) {
        $query = $this->db->get_where('fleet', array('barcode' => $qr));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>