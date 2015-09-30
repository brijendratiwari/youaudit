<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tests_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*     * **************************
     * Get All Historical Tests for Item
     * ************************** */

    public function getTestList($itemId) {
        $query = $this->db->query("
            SELECT items_categories_link.category_id, test_type.test_type_name, test_type.test_type_frequency, test_type.test_type_id, 
            test_freq.test_freq_id, test_freq.test_frequency, test_freq.test_days
            FROM tests
            Inner Join items_categories_link ON tests.test_item_id = items_categories_link.item_id
            Inner Join test_type ON tests.test_type = test_type.test_type_id AND items_categories_link.category_id = test_type.test_type_category_id
            Inner Join test_freq ON test_type.test_type_frequency = test_freq.test_freq_id
            WHERE tests.test_item_id = '" . $itemId . "'
            GROUP BY test_type.test_type_id");

        return $query->result_array();
    }

    public function getTestResults($itemId, $testType) {
        $query = $this->db->query("
            SELECT items_categories_link.category_id, tests.test_type, tests.test_date, tests.test_item_id, tests.test_notes, 
            tests.test_id, tests.test_person, tests.result, test_type.test_type_name, test_type.test_type_frequency
            FROM tests
            Inner Join items_categories_link ON tests.test_item_id = items_categories_link.item_id
            Inner Join test_type ON tests.test_type = test_type.test_type_id AND 
            items_categories_link.category_id = test_type.test_type_category_id
            WHERE tests.test_item_id = '" . $itemId . "' AND tests.test_type = '" . $testType . "' ORDER BY tests.test_type ASC, tests.test_date ASC");

        return $query->result_array();
    }

    public function logTest($data) {
        $date_explode = explode('/', $data['test_date']);
        $test_date_new = $date_explode[2] . "-" . $date_explode[1] . "-" . $date_explode[0];

        $data['test_date'] = $test_date_new;
        $data['test_person'] = $this->session->userdata('objSystemUser')->firstname . " " . $this->session->userdata('objSystemUser')->lastname;
        $this->db->insert('tests', $data);
        return true;
    }

    public function testTypes($cat_id) {
        $query = $this->db->get_where('test_type', array('test_type_category_id' => $cat_id));
        return $query->result_array();
    }

    public function getTestsByCat($cat_id, $queryFilter = NULL) {
        $this->db->select("test_type.test_type_id, test_type.test_type_name, test_type.test_type_mandatory,test_type.test_type_frequency,test_type.start_of_check,test_type.test_type_category_id, test_freq.test_freq_id, test_freq.test_frequency, test_freq.test_days")->from('test_type')
                ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
                ->where('test_type.test_type_category_id', $cat_id)
                ->where('test_type.test_type_active', 1)
                ->where('test_type.archieved', 0);
        if ($queryFilter == 1)
            $this->db->where('test_freq.test_days !=', '0');
        if ($queryFilter == 0)
            $this->db->where('test_type.test_type_frequency', '10');
        $query = $this->db->get();
//         print "<pre>"; var_dump($cat_id); print "</pre>";
//        echo $this->db->last_query().';<br>';
        return $query->result_array();
    }

    public function testOwnerCheck($account_id, $test_type_id) {
        $query = $this->db->get_where('test_type', array('test_type_account_id' => $account_id, 'test_type_id' => $test_type_id));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getTest($test_id) {
        $query = $this->db->query("SELECT test_type.test_type_id, test_type.test_type_account_id,
              test_type.test_type_name, test_type.test_type_description, test_type.test_type_notes, test_type.test_type_notify, test_type.test_type_frequency, test_freq.test_freq_id, test_freq.test_frequency, test_type.test_type_mandatory, test_freq.test_days, categories.name AS cat_name
              FROM test_type
              Inner Join categories ON test_type.test_type_category_id = categories.id
              Inner Join test_freq ON test_type.test_type_frequency = test_freq.test_freq_id
              WHERE test_type.test_type_id = " . $test_id . "");

        return $query->row_array();
    }

    public function getTestFreqs() {
        $this->db->order_by('test_days', 'ASC');
        $query = $this->db->get('test_freq');
        return $query->result_array();
    }

    public function saveTest($test_id, $data) {
        unset($data['id']);
        $this->db->where('test_type_id', $test_id);
        $this->db->update('test_type', $data);
        return true;
    }

    public function addTest($cat_id, $data) {
        $data['test_type_account_id'] = $this->session->userdata('objSystemUser')->accountid;
        unset($data['id']);
        $this->db->insert('test_type', $data);
        return true;
    }

    public function removeTest($test_id) {
        $this->db->query("UPDATE test_type SET test_type_active = '0' WHERE test_type_id = '" . $test_id . "'");
        return true;
    }

    public function removeTask($test_id) {
        $query = $this->db->query("SELECT * FROM `compliance_template` where tasks LIKE '%" . $test_id . "%'; ");
        $query = $query->result_array();
        foreach ($query as $key => $value) {
            $tasks = explode(',', $value['tasks']);
            foreach ($tasks as $key1 => $value1) {
                if ($value1 == $test_id) {
                    unset($tasks[$key1]);
                }
            }
            $taskStr = implode(',', $tasks);
            $this->db->where('id', $value['id'])->update('compliance_template', array('tasks' => $taskStr));
        }
        $this->db->where('id', $test_id)->update("tasks", array('archive' => 1));
        return true;
    }

    public function removeTemplate($temp_id) {
        $this->db->query("delete from compliance_template WHERE id = '" . $temp_id . "'");
        return true;
    }

    public function getNeverTested($account_id) {
        $query = $this->db->query("SELECT items.id, items.manufacturer, items.model
             FROM items
             Left Outer Join tests ON items.id = tests.test_item_id
             WHERE tests.test_item_id IS NULL AND items.account_id = " . $account_id);
        return $query->result_array();
    }

    public function getDueTests($account_id, $filter = NULL) {
        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('locations_model');
        $this->load->model('sites_model');

        $visibility = array(10 => 0, 6 => 1, 11 => 1, 2 => 5, 3 => 6, 1 => 7, 7 => 31, 9 => 31, 5 => 31, 4 => 31, 12 => 60, 13 => 60);
        if ($filter != NULL) {
            $start_ts = strtotime($filter['start_date']);
            $end_ts = strtotime($filter['end_date']);
        }
        $result = $this->items_model->getAll($account_id, array('limit' => 0));
        $items = $result['results'];

        foreach ($items as $key => $item) {
            /* Skip if compliance is not due to start yet */
            if (date('Y-m-d') < $item->compliance_start) {
                continue;
            }


            $owner_name = $item->userfirstname . " " . $item->userlastname;
            $item->owner = $item->owner_name;

            $item->location = $item->locationname;

            $item->site = $item->sitename;

            $tests = $this->getTestsByCat($item->categoryid, 1);
//            var_dump($filter);
            foreach ($tests as $test) {
//                var_dump($test);
                $last_tested = $this->itemLastTest($item->itemid, $test['test_type_id']);
                $test['manager_id'] = $this->getManagerOfCheckID($test['test_type_id']);
                $test['manager'] = $this->getManagerOfCheck($test['test_type_id']);
//                if ($last_tested) {
                if ($last_tested['last_tested']) {

                    if ($last_tested['due'])
                        $test['due_ts'] = strtotime($last_tested['due']);
                    else
                        $test['due_ts'] = strtotime("+" . $test['test_days'] . " days", strtotime($last_tested['last_tested']));
//                    $test['due_ts'] = strtotime("+" . $test['test_days'] . " days", strtotime($last_tested));

                    if ($test['due_ts'] <= strtotime("+" . $visibility[$test['test_freq_id']] . " days", strtotime('now'))) {
//                        echo '<br>passed from here in visibility</br>';

                        if ($filter == NULL) {
                            if (($test['due_ts'] < strtotime("now")) && ($test['test_type_mandatory'] == 1)) {
                                $data['dueMandatory'][$key]['item'] = $item;
                                $data['dueMandatory'][$key]['tests'][] = $test;
                            } elseif (($test['due_ts'] < strtotime("now")) && ($test['test_type_mandatory'] == 0)) {

                                $data['dueOptional'][$key]['item'] = $item;
                                $data['dueOptional'][$key]['tests'][] = $test;
                            }
                        } else {
                            if (($test['due_ts'] > $start_ts ) && ($test['due_ts'] < $end_ts) && ($test['test_type_mandatory'] == 1)) {

                                $data['dueMandatory'][$key]['item'] = $item;
                                $data['dueMandatory'][$key]['tests'][] = $test;
                            } elseif (($test['due_ts'] > $start_ts ) && ($test['due_ts'] < $end_ts) && ($test['test_type_mandatory'] == 0)) {

                                $data['dueOptional'][$key]['item'] = $item;
                                $data['dueOptional'][$key]['tests'][] = $test;
                            }
                        }
                    }
//                    echo '</pre>';
                } else {
//                    if($last_tested['due'])
                    $test['due_ts'] = strtotime($test['start_of_check']);
                    if ($test['due_ts'] <= strtotime("+" . $visibility[$test['test_freq_id']] . " days", strtotime('now'))) {
                        if (($test['due_ts'] < $end_ts) && ($test['test_type_mandatory'] == 1)) {
                            $data['dueMandatory'][$key]['item'] = $item;
                            $data['dueMandatory'][$key]['tests'][] = $test;
                        } elseif ($test['test_type_mandatory'] == 0) {
                            $data['dueOptional'][$key]['item'] = $item;
                            $data['dueOptional'][$key]['tests'][] = $test;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getAdhocDueTests($account_id, $filter = NULL) {
        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('locations_model');
        $this->load->model('sites_model');
        if ($filter != NULL) {
            $start_ts = strtotime($filter['start_date']);
            $end_ts = strtotime($filter['end_date']);
        }
        $result = $this->items_model->getAll($account_id, array('limit' => 0));
        $items = $result['results'];
//        var_dump($items);
        foreach ($items as $key => $item) {
            /* Skip if compliance is not due to start yet */
            if (date('Y-m-d') < $item->compliance_start) {
                continue;
            }

//            $owner_id = $this->items_model->whoOwnsThis($item->itemid);
//            $owner_details = $this->users_model->getOne($owner_id, $this->session->userdata('objSystemUser')->accountid);
//            $owner_name = $owner_details['result'][0]->firstname . " " . $owner_details['result'][0]->lastname;
//            $item->owner = $owner_name;
            $owner_name = $item->userfirstname . " " . $item->userlastname;
            $item->owner = $item->owner_name;

//            $location_id = $this->items_model->whereIsThis($item->itemid);
//            $location_details = $this->locations_model->getOne($location_id, $this->session->userdata('objSystemUser')->accountid);
//            $location_name = $location_details['results'][0]->locationname;
//            $item->location = $location_name;
            $item->location = $item->locationname;

//            $site_id = $this->items_model->whichFacultyIsThis($item->itemid);
//            $site_details = $this->sites_model->getOne($site_id, $this->session->userdata('objSystemUser')->accountid);
//            $site_name = $site_details['results'][0]->sitename;
//            $item->site = $site_name;
            $item->site = $item->sitename;

            $tests = $this->getTestsByCat($item->categoryid, 0);
            foreach ($tests as $test) {
                $data['dueMandatory'][$key]['item'] = $item;
                $data['dueMandatory'][$key]['tests'][] = $test;
            }
        }
        return $data;
    }

    public function getUpcomingTests($account_id) {
        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('locations_model');
        $this->load->model('sites_model');
        $result = $this->items_model->getAll($account_id, array('limit' => 0));
        $items = $result['results'];

        foreach ($items as $key => $item) {

            /* Skip if compliance is not due to start yet */
            if (date('Y-m-d') < $item->compliance_start) {
                continue;
            }

            $owner_id = $this->items_model->whoOwnsThis($item->itemid);
            $owner_details = $this->users_model->getOne($owner_id, $this->session->userdata('objSystemUser')->accountid);
            $owner_name = $owner_details['result'][0]->firstname . " " . $owner_details['result'][0]->lastname;
            $item->owner = $owner_name;

            $location_id = $this->items_model->whereIsThis($item->itemid);
            $location_details = $this->locations_model->getOne($location_id, $this->session->userdata('objSystemUser')->accountid);
            $location_name = $location_details['results'][0]->locationname;
            $item->location = $location_name;

            $site_id = $this->items_model->whichFacultyIsThis($item->itemid);
            $site_details = $this->sites_model->getOne($site_id, $this->session->userdata('objSystemUser')->accountid);
            $site_name = $site_details['results'][0]->sitename;
            $item->site = $site_name;

            $tests = $this->getTestsByCat($item->categoryid);

            foreach ($tests as $test) {
                /* Skip tests under 8 days, otherwise they will show up constantly */
                /* if($test['test_days'] < 8) {
                  continue;
                  } */

                if ($last_tested = $this->itemLastTest($item->itemid, $test['test_type_id'])) {

                    $test['due_ts'] = strtotime("+" . $test['test_days'] . " days", strtotime($last_tested));

                    if (($test['due_ts'] > strtotime("now")) && (strtotime("now") > strtotime("-7 days", $test['due_ts'])) && ($test['test_type_mandatory'] == 1)) {

                        $data['dueMandatory'][$key]['item'] = $item;
                        $data['dueMandatory'][$key]['tests'][] = $test;
                    } elseif (($test['due_ts'] > strtotime("now")) && (strtotime("now") > strtotime("-7 days", $test['due_ts'])) && ($test['test_type_mandatory'] == 0)) {

                        $data['dueOptional'][$key]['item'] = $item;
                        $data['dueOptional'][$key]['tests'][] = $test;
                    }
                }
            }
        }

        return $data;
    }

    public function getTestFreqInfo($test_type, $account_id) {
        $query = $this->db->query("SELECT test_type.test_type_account_id, test_type.test_type_frequency, 
             test_type.test_type_name, test_type.test_type_description, test_freq.test_frequency, test_freq.test_days
             FROM test_freq
             Inner Join test_type ON test_type.test_type_frequency = test_freq.test_freq_id
             WHERE test_type.test_type_account_id =  " . $account_id . " AND test_type.test_type_id = " . $test_type);
        return $query->row_array();
    }

    private function getMandatoryChecks() {
        $query = $this->db->get_where('test_type', array('test_type_account_id' => $this->session->userdata('objSystemUser')->accountid, 'test_type_mandatory' => 1));
        $mandatory_checks = $query->result_array();
        foreach ($mandatory_checks as $check) {
            
        }
    }

    /* collects all stats for Compliance Snapshot */

    public function getComplianceStats() {

        /* Get Mandatory checks overdue */
        $mandatory = $this->getMandatoryChecks();

        return $data;
    }

    public function getAllTests($filter = NULL) {
        $sql = "SELECT test_type.test_type_id, test_type.test_type_name AS test_name, test_type.test_type_category_id AS test_cat,
             test_type.test_type_mandatory AS test_mandatory, categories.name AS cat_name, test_freq.test_frequency AS freq FROM test_type
             Inner Join categories ON test_type.test_type_category_id = categories.id
             Inner Join test_freq ON test_type.test_type_frequency = test_freq.test_freq_id
             WHERE test_type.test_type_account_id = " . $this->session->userdata('objSystemUser')->accountid;

        if ($filter) {
            if ($filter['filter_cat']) {
                $sql .= " AND test_type.test_type_category_id = " . $filter['filter_cat'];
            }
        }
        $query = $this->db->query($sql);
        return $query->result_array();
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

    public function itemLastDueDate($item_id, $test_id) {
        $query = $this->db->where('test_item_id', $item_id)->where('test_type', $test_id)->order_by('test_date', 'desc')->group_by('test_date')->limit(2)->get('tests');
        $data = $query->result_array();
        if (empty($data)) {
            return false;
        } else {
            if (count($data) == 2)
                return $data[1]['test_date'];
            else
                return $data[0]['test_date'];
        }
    }

    public function getComplianceHistory($item_id = NULL) {

        if ($item_id == NULL) {
            $sql = "SELECT tests.test_id, tests.test_type, tests.test_date, tests.test_item_id, tests.test_notes,
                   tests.test_person, tests.result, test_type.test_type_name, test_type.test_type_description,  
                   test_type.test_type_mandatory, test_type.test_type_frequency, items.barcode,items.barcode,items.account_id, items.manufacturer, items.model, items.serial_number, categories.name, categories.id as cat_id 
                   FROM tests 
                   Inner Join test_type ON tests.test_type = test_type.test_type_id
                   Inner Join items ON tests.test_item_id = items.id
                   Inner Join categories ON test_type.test_type_category_id = categories.id
                   ORDER BY tests.test_date DESC";
        } else {
            $sql = "SELECT tests.test_id,
                    tests.test_type,
                    tests.test_date, 
                    tests.test_item_id, 
                    tests.test_notes,
                    test_freq.test_days,
                   tests.test_person,
                   tests.result,
                   test_type.test_type_name,
                   test_type.test_type_description, 
                   test_type.test_type_frequency,
                   items.barcode,
                   items.manufacturer,
                   items.model, 
                   items.serial_number
                
                   FROM tests 
                   Inner Join test_type ON tests.test_type = test_type.test_type_id
                   Inner Join test_freq ON test_type.test_type_frequency = test_freq.test_freq_id
                   Inner Join items ON tests.test_item_id = items.id
                 
                   WHERE tests.test_item_id = '" . $item_id . "' ORDER BY tests.test_date DESC";
        }

        $query = $this->db->query($sql);
        $data = $query->result_array();

        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

    public function getComplianceHistoryFiltered($start_date = NULL, $end_date = NULL, $item_id = NULL) {
        if ($this->session->userdata('objSystemUser')->accountid) {
            $acid = $this->session->userdata('objSystemUser')->accountid;
        } else {
            $acid = $this->session->userdata('objAppUser')->accountid;
        }

        if ($item_id == NULL) {
            $query = $this->db->query("(SELECT `tests`.`test_id`,`tests`.`test_type`, `tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) INNER JOIN `test_type` ON `tests`.`test_type` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE  `items`.`account_id`='$acid' AND `tests`.`test_date` >= '$end_date' AND `tests`.`test_date` <= '$start_date' GROUP BY `tests`.`test_date` ORDER BY `tests`.`test_date` desc) UNION (SELECT `tests`.`test_id`, `tests`.`test_type`, `tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) LEFT JOIN `test_compliances` as tc ON `tests`.`test_id` = `tc`.`tests_id` LEFT JOIN `test_type` ON `tc`.`compliance_id` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE `test_type`.`test_type_account_id` = '$acid' AND `items`.`account_id` = '$acid' AND `tests`.`test_date` <= '$start_date' AND tests.test_date >= '$end_date' GROUP BY `tests`.`test_date` ORDER BY `tests`.`test_date` desc )");
//            echo $this->db->last_query().';<br>';
        } else {
            $query = $this->db->query("(SELECT `tests`.`test_id`, `tests`.`test_type`,`tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) INNER JOIN `test_type` ON `tests`.`test_type` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE  `items`.`account_id`='$acid' AND `tests`.`test_item_id`='$item_id' AND `test_type`.`test_type_account_id` = '$acid' GROUP BY `tests`.`test_date` ORDER BY `tests`.`test_date` desc) UNION (SELECT `tests`.`test_id`, `tests`.`test_type`, `tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) LEFT JOIN `test_compliances` as tc ON `tests`.`test_id` = `tc`.`tests_id` LEFT JOIN `test_type` ON `tc`.`compliance_id` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE  `tests`.`test_item_id`='$item_id' AND `test_type`.`test_type_account_id` = '$acid' AND `items`.`account_id` = '$acid' GROUP BY `tests`.`test_date` ORDER BY `tests`.`test_date` desc )");
//            echo $this->db->last_query().';<br>';
        }
        $data = $query->result_array();
//        echo $this->db->last_query();
        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

    public function getComplianceHistoryFilteredForApp($start_date = NULL, $end_date = NULL, $item_id = NULL) {
        if ($this->session->userdata('objAppUser')->accountid) {
            $acid = $this->session->userdata('objAppUser')->accountid;
        } else {
            $acid = $this->session->userdata('objSystemUser')->accountid;
        }
//        $acid = 1;
        if ($item_id == NULL) {
            $query = $this->db->query("(SELECT `tests`.`test_id`, `tests`.`test_type`, `tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) INNER JOIN `test_type` ON `tests`.`test_type` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE `tests`.`test_id` < 3574 AND `items`.`account_id`='$acid' AND `tests`.`test_date` >= '$end_date' AND `tests`.`test_date` <= '$start_date' ORDER BY `tests`.`test_date` desc) UNION (SELECT `tests`.`test_id`, `tests`.`test_type`, `tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) LEFT JOIN `test_compliances` as tc ON `tests`.`test_id` = `tc`.`tests_id` LEFT JOIN `test_type` ON `tc`.`compliance_id` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE `tests`.`test_id` >= 3574 AND `test_type`.`test_type_account_id` = '$acid' AND `items`.`account_id` = '$acid' AND `tests`.`test_date` <= '$start_date' AND tests.test_date >= '$end_date' GROUP BY `tests`.`test_date` ORDER BY `tests`.`test_date` desc )");
//            echo $this->db->last_query().';<br>';
        } else {
            $query = $this->db->query("(SELECT `tests`.`test_id`, `tests`.`test_type`, `tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) INNER JOIN `test_type` ON `tests`.`test_type` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE `tests`.`test_id` < 3574 AND `items`.`account_id`='$acid' AND `tests`.`test_item_id`='$item_id' AND `test_type`.`test_type_account_id` = '$acid' ORDER BY `tests`.`test_date` desc) UNION (SELECT `tests`.`test_id`, `tests`.`test_type`, `tests`.`test_date`, `tests`.`due_on` as due_date, `tests`.`test_item_id`, `tests`.`test_notes`, `tests`.`test_person`, `tests`.`result`, `items`.`barcode`, `items`.`manufacturer`, `items`.`model`, `items`.`serial_number` FROM (`tests`) LEFT JOIN `test_compliances` as tc ON `tests`.`test_id` = `tc`.`tests_id` LEFT JOIN `test_type` ON `tc`.`compliance_id` = `test_type`.`test_type_id` JOIN `items` ON `tests`.`test_item_id` = `items`.`id` WHERE `tests`.`test_id` >= 3574 AND `tests`.`test_item_id`='$item_id' AND `test_type`.`test_type_account_id` = '$acid' AND `items`.`account_id` = '$acid' GROUP BY `tests`.`test_date` ORDER BY `tests`.`test_date` desc )");
//            echo $this->db->last_query().';<br>';
        }
        $data = $query->result_array();
//        echo $this->db->last_query();
        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

//    public function getComplianceHistoryFiltered($start_date = NULL, $end_date = NULL,$item_id = NULL) {
//        if($item_id == NULL){
//            $query = $this->db->select('tests.test_id, tests.test_type, tests.test_date, tests.due_on as due_date, tests.test_item_id, tests.test_notes, tests.test_person, tests.result, test_type.test_type_id,test_type.test_type_name, test_type.test_type_description, test_type.test_type_mandatory, test_type.test_type_frequency, test_type.test_type_notes,test_freq.test_days, items.barcode, items.manufacturer, items.model, items.serial_number, categories.name, categories.id as cat_id')
//                    ->from('tests')
//                    ->join('test_compliances as tc' , 'tests.test_id = tc.tests_id','left')
//                    ->join('test_type', 'tc.compliance_id = test_type.test_type_id','left')
//                    ->join('items', 'tests.test_item_id = items.id')
//                    ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
//                    ->join('categories', 'test_type.test_type_category_id = categories.id')
////                    ->where('test_type.test_type_active', 1)
//                    ->where('test_type.test_type_account_id', $this->session->userdata('objSystemUser')->accountid)
//                    ->where("tests.test_date <= '$start_date' AND tests.test_date >= '$end_date'")
//                    ->order_by('tests.test_date', 'desc')
//                    ->group_by('tests.test_date')
//                    ->get();
//        }
//        else{
//            $query = $this->db->select('tests.test_id, tests.test_type, tests.test_date, tests.test_item_id, tests.test_notes, tests.test_person, tests.result, test_type.test_type_name, test_type.test_type_description, test_type.test_type_mandatory, test_type.test_type_frequency, test_type.test_type_notes,test_freq.test_days, items.barcode, items.manufacturer, items.model, items.serial_number, categories.name, categories.id as cat_id')
//                    ->from('tests')
//                    ->join('test_compliances as tc' , 'tests.test_id = tc.tests_id','left')
//                    ->join('test_type', 'tc.compliance_id = test_type.test_type_id','left')
//                    ->join('items', 'tests.test_item_id = items.id')
//                    ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
//                    ->join('categories', 'test_type.test_type_category_id = categories.id')
////                    ->where('test_type.test_type_active', 1)
//                    ->where('test_type.test_type_account_id', $this->session->userdata('objSystemUser')->accountid)
//                    ->where("tests.test_item_id",$item_id)
//                    ->order_by('tests.test_date', 'desc')
//                    ->group_by('tests.test_date')->get();
//        }
//        $data = $query->result_array();
////        echo $this->db->last_query();
//        if (empty($data)) {
//            return false;
//        } else {
//            return $data;
//        }
//    }


    public function getMissedHistory($filter = array()) {

        $query = $this->db->select('cm.id, cm.compliance_id, cm.missed_on, cm.item_id, test_type.test_type_name, test_type.test_type_description, test_type.test_type_mandatory, test_type.test_type_frequency, test_type.test_type_notes,test_freq.test_days, items.barcode, items.manufacturer, items.model, items.serial_number, categories.name, categories.id as cat_id')
                ->from('compliance_missed as cm')
                ->join('test_type', 'cm.compliance_id = test_type.test_type_id', 'left')
                ->join('items', 'cm.item_id = items.id')
                ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
                ->join('categories', 'test_type.test_type_category_id = categories.id')
                ->where('cm.account_id', $this->session->userdata('objSystemUser')->accountid)
                ->get();
//        echo $this->db->last_query().';<br>';
        $query = $query->result_array();
        if (!empty($query))
            return $query;
        else
            return false;
//        }
    }

    public function getComplianceChecks($data) {

        $checks = explode(',', $data);
        $check_info = array();
        if ($checks) {
            foreach ($checks as $check) {
                if ($check > 0) {
                    $check_info[] = $this->getTest($check);
                }
            }
        }

        return $check_info;
    }

    public function getLocation($item_id = NULL) {
        $sql = "SELECT loc.name as locationname
                   FROM items as i Inner Join locations as loc 
                   ON i.location_now = loc.id
                   WHERE i.id = '" . $item_id . "'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if (empty($data)) {
            return '';
        } else {
            return $data[0]['locationname'];
        }
    }

    public function getLocationforHistory($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT test_location as locationname
                FROM tests_history
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
//        echo $this->db->last_query().';<br>';
        if (empty($data)) {
            return '';
        } else {
//            var_dump($data[0]['locationname']);
            if ($data[0]['locationname'] != '0')
                return $data[0]['locationname'];
            else
                return '';
        }
    }

    public function getComplianceNameforHistory($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT test_compliance_name
                FROM tests_history
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
//        echo $this->db->last_query().';<br>';
        if (empty($data)) {
            return '';
        } else {

            if ($data[0]['test_compliance_name'] != '0')
                return $data[0]['test_compliance_name'];
            else
                return '';
        }
    }

    public function getComplianceSignatureforHistory($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT signature
                FROM tests_history
                
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
//        echo $this->db->last_query().';<br>';
        if (empty($data)) {
            return '';
        } else {

            if ($data[0]['signature'] != '0')
                return $data[0]['signature'];
            else
                return '';
        }
    }

    public function getComplianceNameforHistoryForApp($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT test_compliance_name
                FROM tests_history
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objAppUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
//        echo $this->db->last_query().';<br>';
        if (empty($data)) {
            return '';
        } else {

            if ($data[0]['test_compliance_name'] != '0')
                return $data[0]['test_compliance_name'];
            else
                return '';
        }
    }

    public function getCategoryforHistory($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT test_category as name
                FROM tests_history
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if (empty($data)) {
            return '';
        } else {
//            return $data[0]['name'];
            if ($data[0]['name'] != '0')
                return $data[0]['name'];
            else
                return '';
        }
    }

    public function getOwnerName($item_id = NULL) {
        $sql = "SELECT u.owner_name
                   FROM items as i Inner Join owner as u 
                   ON i.owner_now = u.id
                   WHERE i.id = '" . $item_id . "'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if (empty($data)) {
            return '';
        } else {
//            var_dump($data);
            return $data[0]['owner_name'];
        }
    }

    public function getOwnerNameforHistory($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT test_owner as ownername
                FROM tests_history
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
//        echo $this->db->last_query().';<br>';
        if (empty($data)) {
            return '';
        } else {
            if ($data[0]['ownername'] != '0')
                return $data[0]['ownername'];
            else
                return '';
        }
    }

    public function getSiteNameforHistory($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT test_site as sitename 
                FROM tests_history
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
        $data = $query->result_array();
//        echo $this->db->last_query().';<br>';
        if (empty($data)) {
            return '';
        } else {
            if ($data[0]['sitename'] != '0')
                return $data[0]['sitename'];
            else
                return '';
        }
    }

    public function getManagerforHistory($item_id = NULL, $test_date = NULL) {
        $sql = "SELECT test_manager as manager
                FROM tests_history
                WHERE test_item_id = '" . $item_id . "'
                AND test_date = '" . $test_date . "'
                AND account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' LIMIT 1";
        $query = $this->db->query($sql);
//        echo $this->db->last_query().'<br>';
        $data = $query->result_array();
        if (empty($data)) {
            return '';
        } else {
//            var_dump($data);
            if ($data[0]['manager'] != '0')
                return $data[0]['manager'];
            else
                return '';
        }
    }

    public function getSiteName($item_id = NULL) {
        $sql = "SELECT s.name
                   FROM items as i Inner Join sites as s 
                   ON i.site = s.id
                   WHERE i.id = '" . $item_id . "'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if (empty($data)) {
            return '';
        } else {
            return $data[0]['name'];
        }
    }

    public function getComplianceName($freq = NULL) {
        $sql = "SELECT test_frequency
                   FROM test_freq
                   WHERE test_freq_id = '" . $freq . "'";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        if (empty($data)) {
            return '';
        } else {
            return $data[0]['test_frequency'];
        }
    }

    public function getTaskCount($td = NULL, $filter = NULL) {
        $res = $this->db->where('test_date', $td)->join('tasks', 'tests.test_type = tasks.id')->get('tests');
        $result = $res->result_array();
        for ($j = 0; $j < count($result); $j++) {
            if ($result[$j]['measurement'] != 0) {
                $res = $this->db->select('measurement_name')->where('id', $result[$j]['measurement'])->get('measurements')->row();
                $result[$j]['measurement_name'] = $res->measurement_name;
            } else {
                $result[$j]['measurement_name'] = 0;
            }
        }

//        Add this commented where condition in above line it changed in YouAudit Work        
//      where('test_id >=', '3574')->
        if ($filter == NULL) {
            if (empty($result))
                return 1;
            else
                return count($result);
        }else {
            return $result;
        }
    }

//        public function getTaskCount($cat_id = NULL)
//        {
//            $sql = "SELECT count('test_type_category_id') as task_count
//                   FROM test_type
//                   WHERE test_type_category_id = '" . $cat_id . "'";
//            $query = $this->db->query($sql);
//            $data = $query->result_array();
//            if(empty($data)) {
//                return false;
//            } else {
//                return $data[0]['task_count'];
//            }
//        }

    public function outputHistoryPdfFile($allData, $tasks) {
//        var_dump($allData);
        $this->load->model('accounts_model');
        $booOutputHtml = false;
        $data['tasks'] = $tasks;
        $data['allData'] = explode(',', $allData);  
        $data['accountDetails'] = $this->accounts_model->getOne($this->session->userdata('objSystemUser')->accountid);
//        var_dump($data['allData'][0]);
//            $data['allData'] = $allData;
        $temp = preg_replace('/<\/?pre[^>]*>/', '', preg_replace('/<\/?a[^>]*>/', '', $data['allData'][1]));
        $strHtml = $this->load->view('compliance/historyreport', $data, true);
//            echo $strHtml;die;
        if (!$booOutputHtml) {
            $this->load->library('mpdf');

            $mpdf = new Pdf('en-GB', 'A4');
//            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->SetHTMLHeader('<img src="' . base_url() . '../includes/report_files/images/border_bg.png"/>');
            $mpdf->SetHTMLFooter('<img src="' . base_url() . '../includes/report_files/images/border_bg.png"/>');
            $mpdf->WriteHTML($strHtml);
//            echo 'asd';die;
            $mpdf->Output("isareport_" . $temp . "_" . date('Ymd') . ".pdf", "D");
        } else {
            echo $strHtml;
            die();
        }
    }

    public function outputHistoryPdfFileItems($allData, $tasks) {
        $this->load->model('accounts_model');

        $booOutputHtml = false;
        $data['tasks'] = json_decode($tasks);
        $data['allData'] = explode(',', $allData);
        $item_id = $data['tasks'][0]->test_item_id;

        $data['dueTests'] = $this->getComplianceHistoryFiltered(NULL, NULL, $item_id);

        $data['manufacturer'] = $data['dueTests'][0]['manufacturer'];
        $data['model'] = $data['dueTests'][0]['model'];
        $data['location_name'] = $this->getLocation($item_id);
        $data['owner_name'] = $this->getOwnerName($item_id);
        $data['site_name'] = $this->getSiteName($item_id);
        $this->db->select('item_manu.item_manu_name,categories.name');
        $this->db->from('items');
        $this->db->join('items_categories_link', 'items.id=items_categories_link.item_id', 'left');
        $this->db->join('categories', 'items_categories_link.category_id=categories.id', 'left');
        $this->db->join('item_manu', 'items.item_manu=item_manu.id', 'left');
        $detail = $this->db->get();
        if ($detail->num_rows() > 0) {
            $safety_data = $detail->row();
            $data['item_manu_name'] = $safety_data->item_manu_name;
            $data['category_name'] = $safety_data->name;
        }
        if ($data['allData'][6]) {
            $this->db->select('users.firstname,users.lastname');
            $this->db->from('test_type');
            $this->db->where('test_type_id', $data['allData'][6]);
            $this->db->join('users', 'test_type.manager_of_check=users.id', 'left');
            $manager_check = $this->db->get();
            if ($manager_check->num_rows() > 0) {
                $check = $manager_check->row();
                $data['manager'] = $check->firstname . '' . $check->lastname;
            }
        }

        for ($i = 0; $i < count($data['tasks']); $i++) {
            $data['tasklist'][$i] = $data['tasks'][$i];
            if ($data['tasks'][$i]->measurement > 0) {
                $ms = $this->db->select('measurement_name')->where('id', $data['tasks'][$i]->measurement)->get('measurements');
                if ($ms->num_rows() > 0) {
                    $measure = $ms->row();
                    $measurement = $measure->measurement_name;
                    $data['tasklist'][$i]->measurement_name = $measurement;
                }
            }
        }
//        var_dump($data);
//        die;
        $data['accountDetails'] = $this->accounts_model->getOne($this->session->userdata('objSystemUser')->accountid);

//            $data['allData'] = $allData;
        $temp = preg_replace('/<\/?pre[^>]*>/', '', preg_replace('/<\/?a[^>]*>/', '', $data['allData'][9]));

        $strHtml = $this->load->view('compliance/historyreportitems', $data, true);
//            echo $strHtml;die; 
        if (!$booOutputHtml) {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB', 'A4');
//            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->SetHTMLHeader('<img src="' . base_url() . '../includes/report_files/images/border_bg.png"/>');
            $mpdf->SetHTMLFooter('<img src="' . base_url() . '../includes/report_files/images/border_bg.png"/>');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("isareport_" . $temp . "_" . date('Ymd') . ".pdf", "D");
        } else {
            echo $strHtml;
            die();
        }
    }

    public function exportPdfFile($allData, $filename = "isareport") {
        $this->load->model('accounts_model');
        $booOutputHtml = false;
        $data['allData'] = $allData;
        $data['title'] = $filename;
//        $data['accountDetails'] = $this->accounts_model->getOne($this->session->userdata('objSystemUser')->accountid);
        $strHtml = $this->load->view('compliance/exporttopdf', $data, true);
//            echo $strHtml;die;
        if (!$booOutputHtml) {
            $this->load->library('Mpdf');
            $mpdf = ini_set('memory_limit', '1280M');
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

    public function getAllMeasurements() {
        $res = $this->db->get('measurements');
        $data = $res->result_array();
        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
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

    public function getManagerOfCheckID($id) {
        $res = $this->db->select('users.id')->where('test_type_id', $id)->join('users', 'test_type.manager_of_check = users.id')->limit(1)->get('test_type');
        $data = $res->result_array();
        if (empty($data)) {
            return false;
        } else {
            return $data[0]['id'];
        }
    }

    public function updateCompliance($data) {
//            var_dump($data);die;
        $idArr = array();
        $arr = $data['task_details'];
        $delTask = $data['oldDeletedTask'];
        $arr = explode(',', $arr);
        if ($data['start_of_task'] == '') {
            $data['start_of_task'] = date('Y-m-d', strtotime('now'));
        }
        foreach ($arr as $key => $value) {
            $newArr = explode('|', $value);
            if (!empty($newArr)) {
                if ($newArr[0] == 'true') {

                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'account_id' => $this->session->userdata('objSystemUser')->accountid);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'account_id' => $this->session->userdata('objSystemUser')->accountid);
                    $this->db->insert('tasks', $temp);
                    $id = $this->db->insert_id();
                    $idArr[] = $id;
                }
//                else {
//                    $idArr[] = $newArr[2];
//                }

                if ($newArr[0] == 'false') {

                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4]);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '');
                    $this->db->where('id', $newArr[2])->update('tasks', $temp);
//                    $id = $this->db->insert_id();
//                    $idArr[] = $id;
                }
            }
        }
        foreach ($delTask as $key => $value) {
            $this->db->where('compliance_id', $data['compliance_check_id'])->where('task_id', $value)->delete('compliance_tasks');
        }
        if ($idArr[0] != '') {
            foreach ($idArr as $key => $value) {
                $this->db->insert('compliance_tasks', array('compliance_id' => $data['compliance_check_id'], 'task_id' => $value));
            }
        }
        $test_act = $this->db->select('test_type_active')->where('test_type_id', $data['compliance_check_id'])->limit(1)->get('test_type');
        $test_act = $test_act->result_array();
        $dateInput = explode('/', $data['start_of_task']);
        $dateOutput = $dateInput[2] . '-' . $dateInput[1] . '-' . $dateInput[0];
        if ($data['active'] == '1' && $test_act[0]['test_type_active'] == '0') {
            $dateOutput = date('Y-m-d', strtotime('now'));
            $this->db->where('compliance_id', $data['compliance_check_id'])->where('account_id', $this->session->userdata('objSystemUser')->accountid)->update('item_compliance_dues', array('due_date' => $dateOutput));
        }
        $set = array('test_type_name' => $data['compliance_check_name'], 'test_type_category_id' => $data['category'], 'test_type_mandatory' => $data['mandatory'], 'test_type_frequency' => $data['frequency'], 'test_type_active' => $data['active'], 'test_type_notify' => $data['reminder'], 'manager_of_check' => $data['manager_of_check'], 'start_of_check' => $dateOutput);
        $te = $this->db->where('test_type_id', $data['compliance_check_id'])->update('test_type', $set);

        return $te;
    }

    public function updateMultiCompliance($data) {
        if ($data['compliances_id'] != '') {
            $ids = explode(',', $data['compliances_id']);
            $set = array('test_type_category_id' => $data['category'], 'test_type_frequency' => $data['frequency'], 'test_type_active' => $data['active'], 'manager_of_check' => $data['manager_of_check'], 'test_type_mandatory' => $data['mandatory'], 'test_type_notify' => $data['reminder']);
            foreach ($set as $key => $value) {
                if ($value == '') {
                    unset($set[$key]);
                }
            }
            $this->db->where_in('test_type_id', $ids)->update('test_type', $set);
        }
        return true;
    }

    public function addComplianceTest($data) {
        $idArr = array();
        $arr = $data['task_details'];
        $arr = explode(',', $arr);
        if ($data['start_of_check'] == '') {
            $data['start_of_check'] = date('Y-m-d', strtotime('now'));
        }
        foreach ($arr as $key => $value) {
            $newArr = explode('|', $value);
            if (!empty($newArr)) {
                if ($newArr[0] == 'true') {

                    if ($newArr[3] == 1)
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'account_id' => $this->session->userdata('objSystemUser')->accountid);
                    else
                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'account_id' => $this->session->userdata('objSystemUser')->accountid);

                    $this->db->insert('tasks', $temp);
                    $id = $this->db->insert_id();
                    $idArr[] = $id;
                }
                else {
                    $idArr[] = $newArr[2];
                }
            }
        }

        $dateInput = explode('/', $data['start_of_check']);
        $dateOutput = $dateInput[2] . '-' . $dateInput[1] . '-' . $dateInput[0];
        $set = array('test_type_name' => $data['Compliance_check_name'], 'test_type_category_id' => $data['category'], 'test_type_mandatory' => $data['mandatory'], 'test_type_frequency' => $data['frequency'], 'manager_of_check' => $data['manager_of_check'], 'start_of_check' => $dateOutput, 'test_type_notify' => $data['reminder'], 'alert' => $data['alert'], 'test_type_account_id' => $this->session->userdata('objSystemUser')->accountid);
        $this->db->insert('test_type', $set);
        $check_id = $this->db->insert_id();
        foreach ($idArr as $key => $value) {
            $this->db->insert('compliance_tasks', array('compliance_id' => $check_id, 'task_id' => $value));
        }
        return true;
    }

    public function getAllTasks() {
        $sql = "SELECT tasks.id, tasks.task_name, tasks.type_of_task, measurements.measurement_name, measurements.id as mid  FROM tasks LEFT JOIN measurements ON tasks.measurement = measurements.id WHERE (tasks.account_id = '" . $this->session->userdata('objSystemUser')->accountid . "' OR tasks.account_id = '0') AND tasks.archive = '0' AND tasks.template_task = '0' ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAllTasksAdmins() {
        $sql = "SELECT tasks.id, tasks.task_name, tasks.type_of_task, measurements.measurement_name, measurements.id as mid  FROM tasks LEFT JOIN measurements ON tasks.measurement = measurements.id WHERE tasks.account_id = '0' and tasks.template_task = '1' and tasks.archive = '0'";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function insertTask($data) {
        $this->db->insert('tasks', $data);
    }

    public function updateTask($id, $data) {

        $this->db->where('id', $id);
        $this->db->update('tasks', $data);
        return true;
    }

    public function getTask($test_id, $ret = false) {
        $query = $this->db->query("SELECT t.id,t.task_name,t.type_of_task,t.measurement,m.measurement_name
             From tasks as t
             Left Join measurements as m ON t.measurement = m.id
              WHERE t.id = " . $test_id);

        if ($query->num_rows == 1) {
            if ($ret) {
                
            }
            else
                return $query->row_array();
        } else {
            return false;
        }
    }

    public function getAllCompliances($ac_id, $archieved) {
        $this->db->select('c.test_type_id, c.test_type_name,c.test_type_mandatory as mandatory,tf.test_frequency as frequency,cat.name as category,cat.id as cat_id,u.firstname,u.lastname,c.test_type_notify as reminder,c.start_of_check,c.test_type_active as active')->from('test_type as c')->join('users as u', 'c.manager_of_check = u.id', 'left')->join('categories as cat', 'c.test_type_category_id = cat.id', 'left')->join('test_freq as tf', 'c.test_type_frequency = tf.test_freq_id', 'left')->where('c.test_type_account_id', $ac_id);
        if ($archieved == 0) {
            $this->db->where('c.archieved', $archieved);
        }
        $query = $this->db->order_by('test_type_id', 'desc')->get();
//        echo $this->db->last_query().';<br>';
        if ($query->num_rows > 0) {
            $query = $query->result_array();
            foreach ($query as $key => $value) {
                $temp = $this->db->select('task_id')->where('compliance_id', $value['test_type_id'])->get('compliance_tasks');
                $temp = $temp->result_array();
//                echo $this->db->last_query().';<br>';
                $tasks = array();
                foreach ($temp as $key1 => $value1) {
                    $tasks[] = $value1['task_id'];
                }
                $query[$key]['tasks'] = implode(',', $tasks);
                $query[$key]['total_tasks'] = count($tasks);
            }
            return $query;
        } else {
            return false;
        }
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
            if (count($ret) > 1 || $ret[0] != FALSE)
                return $ret;
            else
                return array(0 => array('id' => $com_id, 'task_name' => $fil[0]['test_type_name'], 'type_of_task' => '0', 'measurement' => '0', 'measurement_name' => null));
        }else {
            return array(0 => array('id' => $com_id, 'task_name' => $fil[0]['test_type_name'], 'type_of_task' => '0', 'measurement' => '0', 'measurement_name' => null));
        }
    }

    public function getComplianceTaskDetails($com_id, $item_id) {
//        var_dump($com_id);
        $ret = array();
        $res = $this->db->select('task_id')->where('compliance_id', $com_id)->get('compliance_tasks');
        $data = $res->result_array();
        foreach ($data as $key => $value) {
            $ret[] = $this->getTask($value['task_id']);
        }
        if (empty($data)) {
            $ret[] = $this->getTask($com_id);
        }
//        var_dump($ret);
        foreach ($ret as $key => $value) {
            $query = $this->db->where('test_item_id', $item_id)->where('test_type', $value['id'])->order_by('test_date', 'desc')->limit(1)->get('tests');
            $query = $query->result_array();
            $ret[$key]['task_results'] = '';
            if (!empty($query)) {
                $ret[$key]['task_results'] = $query[0];
            }
        }
        return $ret;
    }

    public function getOneItem($intItemId = -1, $intAccountId = -1) {
//        var_dump($intItemId,$intAccountId);
        if (($intItemId > 0) && ($intAccountId > 0)) {
            $this->db->select('
                items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id, items.compliance_start, items.quantity,
		categories.id AS categoryid, categories.name AS categoryname, categories.default AS categorydefault, categories.icon AS categoryicon,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,owner.owner_name,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename');
            // we need to do a sub query, this
            $this->db->from('items');
            $this->db->join('items_categories_link', 'items.id = items_categories_link.item_id', 'left');
            $this->db->join('categories', 'items_categories_link.category_id = categories.id', 'left');
            $this->db->join('users', 'items.owner_now = users.id', 'left');
            $this->db->join('owner', 'items.owner_now = owner.id', 'left');
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

    public function recordCheck($data) {
//        print_r($data);die;
//        var_dump((int)$data['item_id'],(int)$this->session->userdata('objSystemUser')->accountid);
        $set = array();
        $other = array();
        $failed = array();
        $chekced = array();
        $due_set = array();
//        $this->load->model('items_model');
        $item_data = $this->getOneItem((int) $data['item_id'], (int) $this->session->userdata('objSystemUser')->accountid);
        $manager = $this->getManagerOfCheck((int) $data['compliance_check_id']);
//        var_dump($item_data,$manager);die;
//        var_dump($data['due_date']);
        if ($data['test_freq'] == '0') {
            $data['due_date'] = date('Y-m-d', strtotime('now'));
            $due_on = $data['due_date'];
        } else {
            $temp1 = explode('/', $data['due_date']);
            $due_on = $temp1[2] . '-' . $temp1[1] . '-' . $temp1[0];
            if ($data['due_date'] == 'Now') {
                $data['due_date'] = date('Y-m-d', strtotime('+' . $data['test_freq'] . ' days'));
            } else {
                $temp = explode('/', $data['due_date']);
                $data['due_date'] = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
                //            var_dump($data['due_date']);
                if (strtotime($temp1[2] . '-' . $temp1[1] . '-' . $temp1[0]) < strtotime($data['due_date'])) {
                    $data['due_date'] = date('Y-m-d', strtotime('+' . $data['test_freq'] . ' days', strtotime($temp1[2] . '-' . $temp1[1] . '-' . $temp1[0])));
                    //                var_dump($data['due_date']);
                } else {
                    //                var_dump($data['due_date']);
                    $data['due_date'] = date('Y-m-d', strtotime('+' . $data['test_freq'] . ' days', strtotime($data['due_date'])));
                    //                var_dump($data['due_date']);
                }
            }
        }
//        var_dump($data);die;
        if ($data['test_freq_id'] == '11') {
            $day = date('D', strtotime($data['due_date']));
            if ($day == 'Sat' || $day == 'Sun') {
                $data['due_date'] = date('Y-m-d', strtotime('next Monday', strtotime($data['due_date'])));
            }
        }
        if ($data['test_freq_id'] == '10') {
            $data['due_date'] = date('Y-m-d', strtotime('now'));
        }
        $set['test_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $set['test_item_id'] = (int) $data['item_id'];
        $set['due_on'] = $due_on;

        $other['compliance_id'] = (int) $data['compliance_check_id'];

        $due_set['item_id'] = (int) $data['item_id'];
        $due_set['cat_id'] = (int) $data['cat_id'];
        $due_set['due_date'] = $data['due_date'];
        $due_set['compliance_id'] = (int) $data['compliance_check_id'];
        $due_set['account_id'] = $this->session->userdata('objSystemUser')->accountid;

        $set['test_notify'] = 0;
        $set['test_person'] = $this->session->userdata('objSystemUser')->firstname . " " . $this->session->userdata('objSystemUser')->lastname;
//        var_dump($due_set);die;
        $passed = explode(',', $data['passedChecks']);
        $temp = explode(',', $data['failedChecks']);
        $temp2 = explode(',', $data['measureChecks']);
        if ($passed[0] != '') {
            foreach ($passed as $key => $value) {
                $set['test_type'] = (int) $value;
                $set['result'] = 1;
                $set['test_notes'] = '';
//----------record for history----------

                $setHistory = array('test_type' => $set['test_type'], 'test_date' => $set['test_date'], 'test_item_id' => $data['item_id'], 'test_notes' => $set['test_notes'], 'test_person' => $set['test_person'], 'test_category' => $item_data[0]->categoryname, 'test_owner' => $item_data[0]->userfirstname . ' ' . $item_data[0]->userlastname, 'test_location' => $item_data[0]->locationname, 'test_site' => $item_data[0]->sitename, 'test_manager' => $manager, 'test_compliance_name' => $data['compliance_check_name'], 'result' => $set['result'], 'test_notify' => 0, 'due_on' => $due_on, 'account_id' => $this->session->userdata('objSystemUser')->accountid);

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

                $setHistory = array('test_type' => $set['test_type'], 'test_date' => $set['test_date'], 'test_item_id' => $data['item_id'], 'test_notes' => $set['test_notes'], 'test_person' => $set['test_person'], 'test_category' => $item_data[0]->categoryname, 'test_owner' => $item_data[0]->userfirstname . ' ' . $item_data[0]->userlastname, 'test_location' => $item_data[0]->locationname, 'test_site' => $item_data[0]->sitename, 'test_manager' => $manager, 'test_compliance_name' => $data['compliance_check_name'], 'result' => $set['result'], 'test_notify' => 0, 'due_on' => $due_on, 'account_id' => $this->session->userdata('objSystemUser')->accountid);

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

                $setHistory = array('test_type' => $set['test_type'], 'test_date' => $set['test_date'], 'test_item_id' => $data['item_id'], 'test_notes' => $set['test_notes'], 'test_person' => $set['test_person'], 'test_category' => $item_data[0]->categoryname, 'test_owner' => $item_data[0]->userfirstname . ' ' . $item_data[0]->userlastname, 'test_location' => $item_data[0]->locationname, 'test_site' => $item_data[0]->sitename, 'test_manager' => $manager, 'test_compliance_name' => $data['compliance_check_name'], 'result' => $set['result'], 'test_notify' => 0, 'due_on' => $due_on, 'account_id' => $this->session->userdata('objSystemUser')->accountid);

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

    public function fetchEmailAddOfCategory($cat_id) {

        if ($cat_id > 0) {
            $this->db->select('categories.support_emails,test_type.alert');
            $this->db->from('test_type');
            $this->db->join('categories', 'test_type.test_type_category_id = categories.id', 'left');
            $this->db->where('test_type.test_type_id', $cat_id);
            $resQuery = $this->db->get();
            if ($resQuery->num_rows() > 0) {

                $cat_email = $resQuery->result();
                if ($cat_email[0]->alert == 1) {
                    if ($cat_email[0]->support_emails != NULL || $cat_email[0]->support_emails != "") {

                        return $cat_email[0]->support_emails;
                    } else {
                        return $this->session->userdata('objSystemUser')->compliance_email;
                    }
                } else {
                    return FALSE;
                }
            } else {

                return FALSE;
            }
        }
    }

    // Templates functions--------
    public function getAllTemplates() {
        $this->db->select('ct.id as cid,ct.Compliance_check_name,ct.mandatory,ct.frequency,f.test_frequency as freq_name,ct.tasks')->from('compliance_template as ct')->join('test_freq as f', 'ct.frequency = f.test_freq_id');

//     Add Condition For Categorized Template according to Master and Frenchises   
        $this->db->where('ct.admin_id', $this->session->userdata('objSystemUser')->team_id);
        $this->db->where('ct.account_type', $this->session->userdata('objSystemUser')->team_type);

        $query = $this->db->order_by('cid', 'desc')->get();

        if ($query->num_rows > 0) {
            $query = $query->result_array();
            foreach ($query as $key => $value) {
                $tasks = array();
                $tasks = explode(',', $value['tasks']);

                $query[$key]['total_tasks'] = count($tasks);
            }

            return $query;
        } else {
            return false;
        }
    }

    public function addModifiedTemplate($data) {
        $idArr = array();
        $arr = $data['task_details'];
        $delTask = $data['oldDeletedTask'];
        $arr = explode(',', $arr);
        foreach ($arr as $key => $value) {
            $newArr = explode('|', $value);
            if (!empty($newArr)) {
//                if ($newArr[0] == 'true') {

                if ($newArr[3] == 1)
                    $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4], 'account_id' => $this->session->userdata('objSystemUser')->accountid);
                else
                    $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '', 'account_id' => $this->session->userdata('objSystemUser')->accountid);
                $this->db->insert('tasks', $temp);
                $id = $this->db->insert_id();
                $idArr[] = $id;
//                }
//                else {
//                    $idArr[] = $newArr[2];
//                }
//                if ($newArr[0] == 'false') {
//
//                    if ($newArr[3] == 1)
//                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => $newArr[4]);
//                    else
//                        $temp = array('task_name' => $newArr[1], 'type_of_task' => $newArr[3], 'Measurement' => '');
//                    $this->db->where('id', $newArr[2])->update('tasks', $temp);
////                    $id = $this->db->insert_id();
//                    $idArr[] = $newArr[2];
//                }
            }
        }
        foreach ($delTask as $key => $value) {
//            $this->db->where('id', $data['compliance_check_id'])->update('compliance_template',array('tasks'=>''));
        }

        $dateInput = explode('/', $data['start_of_task']);
        $dateOutput = $dateInput[2] . '-' . $dateInput[1] . '-' . $dateInput[0];
        $set = array('test_type_name' => $data['compliance_check_name'], 'test_type_category_id' => $data['category'], 'test_type_mandatory' => $data['mandatory'], 'test_type_frequency' => $data['frequency'], 'manager_of_check' => $data['manager_of_check'], 'start_of_check' => $dateOutput, 'test_type_notify' => $data['reminder'], 'alert' => $data['alert'], 'test_type_account_id' => $this->session->userdata('objSystemUser')->accountid);
        $this->db->insert('test_type', $set);
        $check_id = $this->db->insert_id();
        foreach ($idArr as $key => $value) {
            $this->db->insert('compliance_tasks', array('compliance_id' => $check_id, 'task_id' => $value));
        }
    }

    public function updateMultiTemplates($data) {
        if ($data['compliances_id'] != '') {
            $ids = explode(',', $data['compliances_id']);
            $set = array('frequency' => $data['frequency'], 'mandatory' => $data['mandatory']);
            foreach ($set as $key => $value) {
                if ($value == '') {
                    unset($set[$key]);
                }
            }
            $this->db->where_in('id', $ids)->update('compliance_template', $set);
        }
        return true;
    }

    public function getAcIds() {

        $this->db->select('account_id');
        $this->db->from('users');
        $this->db->group_by('account_id');

        $resQuery = $this->db->get();

        // Let's check if there are any results
        $arrItemsData = array();

        if ($resQuery->num_rows > 0) {

            foreach ($resQuery->result_array() as $objRow) {
                $arrItemsData[] = $objRow;
            }
        } else {
            // If we didn't find rows,
            // then return false
            $arrItemsData[] = FALSE;
        }
        return $arrItemsData;
    }

    public function getComplianceHistoryReport($com_id = NULL, $start_date = NULL, $end_date = NULL, $item_id = array()) {
        $query = $this->db->select('tests.test_id, tests.test_type, tests.test_date, tests.due_on as due_date, tests.test_item_id, tests.test_notes, tests.test_person, tests.result, test_type.test_type_id,test_type.test_type_name, test_type.test_type_description, test_type.test_type_mandatory, test_type.test_type_frequency, test_type.test_type_notes,test_freq.test_days,test_freq.test_frequency as frequency_name, items.barcode, items.manufacturer, items.model, items.serial_number')
                ->from('tests')
                ->join('test_compliances as tc', 'tests.test_id = tc.tests_id', 'left')
                ->join('test_type', 'tc.compliance_id = test_type.test_type_id', 'left')
                ->join('items', 'tests.test_item_id = items.id')
                ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
//                    ->join('categories', 'test_type.test_type_category_id = categories.id')
//                    ->where('test_type.test_type_active', 1)
                ->where('test_type.test_type_account_id', $this->session->userdata('objSystemUser')->accountid)
                ->where_in("tests.test_item_id", $item_id)
                ->where("tc.compliance_id", $com_id)
                ->where("tests.test_date <= '$start_date' AND tests.test_date >= '$end_date'")
                ->order_by('tests.test_date', 'desc')
                ->group_by('tests.test_date')
                ->get();


        $data = $query->result_array();
//        echo $this->db->last_query(); 
        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

}

?>