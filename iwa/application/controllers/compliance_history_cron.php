<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Compliance_history_cron extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('tests_model');
    }

    public function index() {
//        echo 'test';die;
        //----------email code
        $message = '<h1>Missed Status as on <i>'.date('d/m/Y',  strtotime('now')).'</i></h1><br>';
        $config['mailtype'] = 'html';
        $this->load->library('email', $config);

        $this->email->from('missed.cron@iworkaudit.com.au', 'Compliance Team');
        $this->email->to('mayank@ignisitsolutions.com');

        $this->email->subject('Missed Cron check');
        $this->email->message(base_url());

        $this->email->send();
        $this->load->model('tests_model');
        $users = array();
        $acid = $this->tests_model->getAcIds();

        foreach ($acid as $ukey => $uval) {
//                   echo '<pre>';var_dump($uval);echo '</pre>';
            if ($uval['account_id']) {
                $table = $this->getDues($uval['account_id'], 1);
                foreach ($table as $key1 => $value1) {
                    $tempData = explode('|', $value1);
                    $set = array('compliance_id'=>$tempData[0],'item_id'=>$tempData[1],'missed_on'=>$tempData[2],'account_id'=>$uval['account_id']);
//                    echo '<pre>';var_dump($set);echo '</pre>';
                    $missedChk = $this->db->where('compliance_id',$tempData[0])->where('missed_on',$tempData[2])->where('item_id',$tempData[1])->where('account_id',$uval['account_id'])->get('compliance_missed');
                    if($missedChk->num_rows()>0)
                        $this->db->where('compliance_id',$tempData[0])->where('item_id',$tempData[1])->where('missed_on',$tempData[2])->where('account_id',$uval['account_id'])->update('compliance_missed',$set);
                    else
                        $this->db->insert('compliance_missed',$set);
                        $message .= 'COMPLIANCE ID: '.$tempData[0].', ';
                        $message .= 'ITEM ID: '.$tempData[1].', ';
                        $message .= 'MISSED ON: '.$tempData[2].'<br>';
                }
            }
//                    
        }
        $this->load->library('email');

        $this->email->from("missed@iworkaudit.com", "Missed Compliance");
        $this->email->to('mayank@ignisitsolutions.com');

        $this->email->subject('Missed Cron Details.');
        $this->email->message($message);

        $this->email->send();   
        die;
    }

    public function getDues($ac_id, $filter = 1) {


        $end_date = date('Y-m-d',strtotime('now'));
        $start_date = '2012-01-01';

        $dueTests = $this->getDueTests($ac_id, array('start_date' => $start_date, 'end_date' => $end_date));

//        var_dump($dueTests);
//        $table = $this->load->view('compliance/missed_view', $arrPageData,true);
        $missedDaysArray = array(1 => 1, 7 => 7, 31 => 31, 90 => 31, 121 => 45, 182 => 45, 365 => 60, 730 => 60, 1095 => 60);
        $data = array();
        if (!empty($dueTests)) {
            foreach ($dueTests['dueMandatory'] as $key => $value) {
//                echo '<pre>';var_dump($value);echo '</pre>';
                foreach ($value['tests'] as $test) {
//                            var_dump($test);
                    $day_remain = '';
                    if (is_int($test['due_ts'])) {

                        $to = date('Y/m/d', $test['due_ts']);
//                                var_dump($to);
//                                $from = '2014/07/27'; 
                        $from = date('Y/m/d', time());
                        $d1 = (date_create($to));
                        $d2 = (date_create($from));
                        $diff = date_diff($d2, $d1);
                        $day_remain = $diff->format('%R%a ');
//                                var_dump($test['due_ts'].' <-> '.$day_remain.'<br>');
                        if ($test['test_type_frequency'] != '11') {    //for mon-fri daily
                            if ($day_remain < 0 && $diff->format('%a') == 1) {
                                $day_remain = '1';
                            } else {
                                if ($diff->format('%R%a') >= 0) {
                                    $day_remain = '1';
                                } else {
                                    if ($diff->format('%a') <= $missedDaysArray[$test['test_days']]) {
                                        $day_remain = '1';
                                    } else {
                                        $day_remain = '';
                                        $addDays = $missedDaysArray[(int) $test['test_days']] + 1;
                                        $missedOn = strtotime('+' . $addDays . ' days', $test['due_ts']);
                                    }
                                }
                            }
                        } else {
                            $mfDay = str_replace('/', '-', $from);
                            $mfDay = date('D', strtotime($mfDay));

                            $duefDay = str_replace('/', '-', $to);
                            $duefDay = date('D', strtotime($duefDay));
//                                    var_dump($mfDay);
                            if (($mfDay == 'Sat' || $mfDay == 'Sun') && ($duefDay == 'Sat' || $duefDay == 'Sun')) {
                                $day_remain = '1';
                            } else {

                                if ($mfDay == 'Mon') {
                                    if ($day_remain < 0 && $diff->format('%a') == 3) {
                                        $day_remain = '1';
                                    } else {
                                        if ($diff->format('%R%a') >= 0) {
                                            $day_remain = '1';
                                        } else {
                                            if ($diff->format('%a') <= $missedDaysArray[$test['test_days']]) {
                                                $day_remain = '1';
                                            } else {
                                                $day_remain = '';
                                                $addDays = $missedDaysArray[(int) $test['test_days']] + 1;
                                                $missedOn = strtotime('+' . $addDays . ' days', $test['due_ts']);
                                            }
                                        }
                                    }
                                } else {
                                    if ($day_remain < 0 && $diff->format('%a') == 1) {
                                        $day_remain = '1';
                                    } else {
                                        if ($diff->format('%R%a') >= 0) {
                                            $day_remain = '1';
                                        } else {
                                            if ($diff->format('%a') <= $missedDaysArray[$test['test_days']]) {
                                                $day_remain = '1';
                                            } else {
                                                $day_remain = '';
                                                $addDays = $missedDaysArray[(int) $test['test_days']] + 1;
                                                $missedOn = strtotime('+' . $addDays . ' days', $test['due_ts']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $day_remain = '1';
                    }
                    if ($day_remain == '') {
                        $data[] = $test['test_type_id'] . '|' . $value['item']->itemid . '|' . date('Y-m-d',  strtotime('1 day ago',$missedOn));
                        $current_ts = strtotime(($missedDaysArray[(int) $test['test_days']]+1).' days ago',  strtotime('now'));
//                        var_dump($test['test_days']);
                        while($missedOn < $current_ts){
//                            $temp = $this->isItemLastTestOn($value['item']->itemid,$test['test_type_id'],date('Y-m-d',$missedOn));
                            $data[] = $test['test_type_id'] . '|' . $value['item']->itemid . '|' . date('Y-m-d',$missedOn);
//                            var_dump(date('Y-m-d',$current_ts));
//                            echo '<br>';
//                            var_dump($test['test_type_id'] . '|' . $value['item']->itemid . '|' . date('Y-m-d',$missedOn));
//                            echo '<br>';
//                            var_dump($temp);
//                            echo '<br>';
                            $missedOn = strtotime('+' . (int) $test['test_days'] . ' days', $missedOn);
                        }
                    }
                }
            }
            if (!empty($data))
                $table = $data;
            else
                $table = '';
        }
        return $table;
    }

    public function setMissedCron() {
        $config['mailtype'] = 'html';
        $this->load->library('email', $config);
        $temp = $this->input->post('postData');
        echo '<br>end';
        die;
        $this->email->from("tickets@iworkaudit.com", "Missed Cron");
        $this->email->to('mayank@ignisitsolutions.com');

        $this->email->subject('Missed Cron check');
        $this->email->message($temp);

        $this->email->send();   
    }

    /* -----------------------------------Model Functions--------------------- */

    public function getDueTests($account_id, $filter = NULL) {
        $this->load->model('items_model');
        $this->load->model('users_model');
        $this->load->model('locations_model');
        $this->load->model('sites_model');
        if ($filter != NULL) {
            $start_ts = strtotime($filter['start_date']);
            $end_ts = strtotime($filter['end_date']);
        }
        $result = $this->getAll($account_id, array('limit' => 0));
        $items = $result['results'];
//        var_dump($items);
        foreach ($items as $key => $item) {
//             var_dump($item->itemid);
            /* Skip if compliance is not due to start yet */
            if (date('Y-m-d') < $item->compliance_start) {
                continue;
            }


            $owner_name = $item->userfirstname . " " . $item->userlastname;
            $item->owner = $owner_name;


            $item->location = $item->locationname;


            $item->site = $item->sitename;

            $tests = $this->getTestsByCat($item->categoryid, 1);

            foreach ($tests as $test) {
//                var_dump($test);
                $last_tested = $this->itemLastTest($item->itemid, $test['test_type_id']);
//                if ($last_tested) {
                if ($last_tested['last_tested']) {

                    if ($last_tested['due'])
                        $test['due_ts'] = strtotime($last_tested['due']);
                    else
                        $test['due_ts'] = strtotime("+" . $test['test_days'] . " days", strtotime($last_tested['last_tested']));


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
                } else {
                    $test['due_ts'] = strtotime($test['start_of_check']);

                    if ($test['test_type_mandatory'] == 1) {
                        $data['dueMandatory'][$key]['item'] = $item;
                        $data['dueMandatory'][$key]['tests'][] = $test;
                    } elseif ($test['test_type_mandatory'] == 0) {
                        $data['dueOptional'][$key]['item'] = $item;
                        $data['dueOptional'][$key]['tests'][] = $test;
                    }
                }
            }
        }
        return $data;
    }

    public function getTestsByCat($cat_id, $queryFilter = NULL) {
        $this->db->select("test_type.test_type_id, test_type.test_type_name, test_type.test_type_mandatory,test_type.test_type_frequency,test_type.start_of_check,test_type.test_type_category_id, test_freq.test_freq_id, test_freq.test_frequency, test_freq.test_days")->from('test_type')
                ->join('test_freq', 'test_type.test_type_frequency = test_freq.test_freq_id')
                ->where('test_type.test_type_category_id', $cat_id)
                ->where('test_type.test_type_active', 1);
        if ($queryFilter == 1)
            $this->db->where('test_freq.test_days !=', '0');
        if ($queryFilter == 0)
            $this->db->where('test_type.test_type_frequency', '10');
        $query = $this->db->get();
//         print "<pre>"; var_dump($cat_id); print "</pre>";
//        echo $this->db->last_query().';<br>';
        return $query->result_array();
    }

    private function isItemLastTestOn($item_id, $test_id,$date) {
        $res = $this->db->select('t.test_type')->from('test_compliances as tc')->where('tc.compliance_id', $test_id)->where('t.test_item_id', $item_id)->join('tests as t', 'tc.tests_id = t.test_id', 'left')->get();
        $ret = $res->result_array();
        echo $this->db->last_query().';<br>';
//        var_dump($ret);
        $dueSql = "SELECT * FROM item_compliance_dues WHERE compliance_id =  '" . $test_id . "' AND item_id =  '" . $item_id . "' LIMIT 1";
        if (!empty($ret)) {
            $tasks = array();
            foreach ($ret as $key1 => $value1) {
                $tasks[] = $value1['test_type'];
            }
            $test_id = implode(',', $tasks);
            $sql = "SELECT * FROM tests WHERE tests.test_type in  ('" . $test_id . "') AND tests.test_item_id =  '" . $item_id . "' AND tests.test_date = '$date' ORDER BY tests.test_date DESC LIMIT 1";
        } else {
            $sql = "SELECT * FROM tests WHERE tests.test_type =  '" . $test_id . "' AND tests.test_item_id =  '" . $item_id . "' AND tests.test_date = '$date' ORDER BY tests.test_date DESC LIMIT 1";
        }

        $query = $this->db->query($sql);
//        echo $this->db->last_query().';';
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

    public function getAll($intAccountId = -1, $arrPagination = array(), $arrFilters = array(), $arrOrder = array(), $booGetCount = false, $export = '') {
        if ($intAccountId > 0) {
            $this->db->select('
                items.id AS itemid, items.manufacturer, items.model, items.serial_number, items.barcode, items.owner_since AS currentownerdate, items.location_since AS currentlocationdate, items.site, items.value, items.status_id, items.compliance_start, items.quantity,
		categories.id AS categoryid, categories.name AS categoryname, categories.default AS categorydefault, categories.icon AS categoryicon,
		users.id AS userid, users.firstname AS userfirstname, users.lastname AS userlastname, users.nickname AS usernickname,
                photos.id AS userphotoid, photos.title AS userphototitle,
                photos2.id AS itemphotoid, photos2.title AS itemphototitle,
		locations.id AS locationid, locations.name AS locationname,
                sites.id AS siteid, sites.name AS sitename,
                pat.pattest_name AS pat_status,
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
            $this->db->join('pat', 'items.pattest_status = pat.id', 'left');

            $this->db->where('items.account_id', $intAccountId);
            $this->db->where('items.active', 1);

            $resQuery = $this->db->get();
//            echo $this->db->last_query();

            $arrItemsData = array();
            foreach ($resQuery->result() as $objRow) {
                $arrItemsData[] = $objRow;
            }
            return array('results' => $arrItemsData);
        }
        return false;
    }

}

?>