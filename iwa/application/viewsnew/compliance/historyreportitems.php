<?php
$logo = 'logo.png';
if (isset($this->session->userdata['theme_design']->logo)) {
    $logo = $this->session->userdata['theme_design']->logo;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Safety History</title>
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/css/custom.css" rel="stylesheet" type="text/css">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/fonts/stylesheet.css" rel="stylesheet" type="text/css">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <!--                <div class="main_container">
                            <div class="border_top">
                                <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/report_files/images/border_bg.png"/>
                            </div>
                            <div class="insert1"> 
                                <img width="200px" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>">
                            </div>
                            <div class="insert2">
        <?php echo $accountDetails['result'][0]->accountname; ?>
                            </div>
                            <div class="content">
                                <div class="address">
                                    <div><?= $accountDetails['result'][0]->accountaddress; ?></div>
                                    <div><?= $accountDetails['result'][0]->accountcity . ' , ' . $accountDetails['result'][0]->accountcounty . ' , ' . $accountDetails['result'][0]->accountpostcode; ?></div>
                                    <div ><b>Phone:</b><?= $accountDetails['result'][0]->accountcontactnumber; ?></div>
                                    <div ><b>Email:</b><?= $accountDetails['result'][0]->accountcontactemail; ?></div>
                                    <div class="qr" ><b>QR CODE:</b><?php echo preg_replace('/<\/?pre[^>]*>/', '', preg_replace('/<\/?a[^>]*>/', '', $allData[10])); ?></div>
                                </div>
                                <div class="right">
                                    <div class="compliance">
                                        Safety Report
                                    </div>
                
                
                                    <div  class="insert"><?php echo $allData[2]; ?>    </div>
                                    <div  class="compliance_name"><b>Safety Name:</b><?php echo $allData[0]; ?></div>
                                    <div  style="    font-family: 'calibriregular';"><b>Logged By:</b><?php echo $allData[1]; ?></div>
                                    <div  style="    font-family: 'calibriregular';" ><b>Due Date:</b><?php echo $allData[3]; ?></div>
                                    <div   style="    font-family: 'calibriregular';"><b>Owner:</b><?php echo $allData[1]; ?></div>
                                </div>   </div>
                
                            <div class="result">
                                <div class="overall">
                                    Overall Result 
                                    <span>
        <?php if ($allData[5] == 'Pass') { ?>
                                                                                                                                                                        <img class="icons" src="/youaudit/includes/report_files/images/right.png"/>
        <?php } if ($allData[5] == 'Fail') { ?>
                                                                                                                                                                        <img class="icons" src="/youaudit/includes/report_files/images/cross.png"/>
        <?php } ?>
                                    </span>
                                </div>
                            </div>
                            <div class="table1">
                                <span style="    font-family: 'calibribold';">Tasks</span>
                                <table class="tbl1 table table-striped table-bordered table-hover">
                                    <tr class="header" style="color:white">
                                        <th style="color:black">TASK NAME</th>
                                        <th style="color:black">RESULT</th>		
                                        <th style="color:black">NOTES</th>
                                    </tr>
        <?php
        $temp = json_decode($tasks, TRUE);

        if (!empty($temp))
            foreach ($temp as $key => $value) {
                if (strlen($value['result']) == 1) {
                    ?>
                                                                                                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                                                                                                            <td valign="top" width="43%" >
                                                                                                                                                                                                                                                                                                                                            <p align="center">
                    <?php echo $value['task_name']; ?>
                                                                                                                                                                                                                                                                                                                                            </p>
                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                            <td valign="top" width="18%" >
                                                                                                                                                                                                                                                                                                                                            <p align="center">
                    <?php
                    if ($value['result'] == 1)
                        $result = 'Pass';
                    else
                        $result = 'Fail';
                    echo $result;
                    ?>
                                                                                                                                                                                                                                                                                                                                            </p>
                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                            <td valign="top" width="38%" >
                                                                                                                                                                                                                                                                                                                                            <p align="center">
                    <?php echo $value['test_notes']; ?>
                                                                                                                                                                                                                                                                                                                                            </p>
                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                            </tr>
                    <?php
                }
            }
        ?>
                                </table>
                            </div>
                            <div class="table1">
                                <span style="    font-family: 'calibribold';">Measurements</span>
                                <table class="tbl1 tbl2 table table-striped table-bordered table-hover">
                
                                    <tr class="header" style="color:white">
                                        <th style="color:black">TASK NAME</th>
                                        <th style="color:black">Value</th>		
                                        <th style="color:black">Type</th>
                                        <th style="color:black">NOTES</th>
                                    </tr>
        <?php
        $temp = json_decode($tasks, TRUE);
        if (!empty($temp))
            foreach ($temp as $key => $value) {
//                        var_dump(strlen(($value['result']),$value['result']));
                if (strlen($value['result']) > 1) {
                    ?>
                                                                                                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                                                                                                            <td class="width1"><?php echo $value['task_name']; ?></td>
                                                                                                                                                                                                                                                                                                                                            <td class="width2"><?php
                    $temp = explode(' ', $value['result']);
                    echo $temp[0];
                    ?></td>		
                                                                                                                                                                                                                                                                                                                                            <td  class="width3"><?php
                    $temp = explode(' ', $value['result']);
                    echo $temp[1];
                    ?></td>
                                                                                                                                                                                                                                                                                                                                            <td><?php echo $value['test_notes']; ?></td>
                                                                                                                                                                                                                                                                                                                                            </tr>
                    <?php
                }
            }
        ?>
                                </table>
                            </div>
                            <div class="qr" ><b>Signature:</b><?php echo $allData[8]; ?></div>
                
                            <div class="border_top border_top1 footer_bg">
                                <img src="/youaudit/includes/report_files/images/border_bg.png"/>
                            </div>
                        </div>
                    </body>-->

        <table>
            <tr>
                <td><h1>Safety Check Report</h1></td>
            </tr>
            <tr>
                <td>
                    <div><?php echo $accountDetails['result'][0]->accountname; ?></div>
                    <div><?php echo $accountDetails['result'][0]->accountaddress . ',' . $accountDetails['result'][0]->accountcity . ',' . $accountDetails['result'][0]->accountcounty . ',' . $accountDetails['result'][0]->accountpostcode; ?></div>
                    <div><b>Phone:</b><?php echo $accountDetails['result'][0]->accountcontactnumber . ',' . $accountDetails['result'][0]->accountcontactemail; ?></div>

                </td>
                <td class="right">
                    <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>">
                </td>
            </tr>
        </table>
        <div>&nbsp;</div>
        <table class="report">
            <thead>
                <tr style="background-color:#00AEEF;color:white;">
                    <th style="padding:10px;border-right: 1px solid #ffffff;">QR Code</th>
                    <th style="border-right: 1px solid #ffffff;">Manufacturer</th>
                    <th style="border-right: 1px solid #ffffff;">Model</th>
                    <th style="border-right: 1px solid #ffffff;">Category</th>
                    <th style="border-right: 1px solid #ffffff;">Item</th>
                    <th style="border-right: 1px solid #ffffff;">Location</th>
                    <th style="border-right: 1px solid #ffffff;">Site</th>
                    <th style="border-right: 1px solid #ffffff;">Owner</th>
                    <th style="border-right: 1px solid #ffffff;">Date Check Completed</th>
                    <th style="border-right: 1px solid #ffffff;">Date Check Due</th>
                    <th style="border-right: 1px solid #ffffff;">Check Logged By</th>
                    <th>Manager of Check</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:10px;"><?php echo $allData[10]; ?></td>
                    <td><?php echo $manufacturer; ?></td>
                    <td><?php echo $model; ?></td>
                    <td><?php echo $category_name; ?></td>
                    <td><?php echo $item_manu_name; ?></td>
                    <td><?php echo $location_name; ?></td>
                    <td><?php echo $site_name; ?></td>
                    <td><?php echo $owner_name; ?></td>
                    <td><?php echo $allData[3]; ?></td>
                    <td><?php echo $allData[2]; ?></td>
                    <td><?php echo $allData[1]; ?></td>
                    <td><?php echo $manager; ?></td>
                </tr>
            </tbody>
        </table>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div><h1 style="color:#00aeef;">Safety Check <?php echo $allData[0]; ?></h1></div>
        <table class="report">
            <thead>
                <tr style="background-color:#00AEEF;color:white;">
                    <th style="padding:10px;width: 30%;border-right: 1px solid #ffffff;">Safety Check Questions</th>
                    <th style="width: 10%;border-right: 1px solid #ffffff;text-align: center;">Pass</th>
                    <th style="width: 10%;border-right: 1px solid #ffffff;text-align: center;">Fail</th>
                    <th style="width: 60%;">Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($tasklist as $task) {
                    if ($task->type_of_task == 0) {
                        if (strpos($task->result, 'Day') != FALSE) {
                            $task_result = rtrim($task->result, "Day");
                        } else {
                            $task_result = $task->result;
                        }
                        ?> 
                        <tr>
                            <td style="padding:10px;"><?php echo $task->task_name; ?></td>
                            <td><?php
                                if ($task_result > 0) {
                                    echo '<img src="' . base_url('brochure/images/right.png') . '">';
                                } else {
                                    echo '<img src="' . base_url('brochure/images/uncheck.png') . '">';
                                }
                                ?></td>
                            <td><?php
                                if ($task_result == 0) {
                                    echo '<img src="' . base_url('brochure/images/right.png') . '">';
                                } else {
                                    echo '<img src="' . base_url('brochure/images/uncheck.png') . '">';
                                }
                                ?></td>
                            <td><?php echo $task->test_notes; ?></td>
                        </tr>
                        <?php
                        $total[] = $task->result;
                        if ($task_result > 0) {
                            $pass[] = $task->result;
                        } else {
                            $fail[] = $task->result;
                        }
                    }
                }
                ?>
                <tr><td><b>Count Number of Questions/Tasks</b></td>
                    <td><?php echo '<b>' . count($total) . '</b>'; ?></td>
                </tr>
                <tr><td><b>Count Number of Tasks=Pass</b></td>
                    <td><?php echo '<b>' . count($pass) . '</b>'; ?></td>
                </tr>
                <tr><td><b>Count Number of Tasks=Fail</b></td>
                    <td><?php echo '<b>' . count($fail) . '</b>'; ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="background-color:#000000;"><td style="width:40%;color:#ffffff;padding:10px;">Overall Result</td>
                    <td style="width:40%;padding:10px;color:#ffffff;" colspan="2"><?php
                        if (count($pass) > count($fail)) {
                            echo 'Pass';
                        } else {
                            echo "Fail";
                        }
                        ?></td>
                    <td style="width:60%;padding:10px;"></td></tr>
            </tfoot>
        </table>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div><h1 style='color:#00aeef;'>Numerical Results</h1></div>
        <table class="report">
            <thead>
                <tr style="background-color:#00AEEF;color:white;">
                    <th style="padding:10px;width: 40%;border-right: 1px solid #ffffff;">Safety Check Questions</th>
                    <th style="padding:10px;width: 40%;border-right: 1px solid #ffffff;">Result</th>
                    <th style="padding:10px;width: 40%;">Value Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($tasklist as $task) {
                    if ($task->type_of_task == 1) {
                        ?>
                        <tr>
                            <td style="padding:10px;"><?php echo $task->task_name; ?></td>
                            <td><?php
                                echo $task->result;
                                ?></td>
                            <td><?php echo $task->measurement_name;
                                ?></td>
                        </tr>
                        <?php
                        $ntotal[] = $task->result;
                        if ($task->result > 20) {
                            $npass[] = $task->result;
                        } else {
                            $nfail[] = $task->result;
                        }
                    }
                }
                ?>
                <tr><td><b>Count Number of Questions/Tasks</b></td>
                    <td><?php echo '<b>' . count($ntotal) . '</b>'; ?></td>
                </tr>
                <tr><td><b>Count Number of Tasks=Pass</b></td>
                    <td><?php echo '<b>' . count($npass) . '</b>'; ?></td>
                </tr>
                <tr><td><b>Count Number of Tasks=Fail</b></td>
                    <td><?php echo '<b>' . count($nfail) . '</b>'; ?></td>
                </tr>
                <tr style="background-color:#000000;"><td style="width:60%;color:#ffffff;padding:10px;">Overall Result</td>
                    <td style="width:40%;padding:10px;color:#ffffff;" colspan="2"><?php
                        if (count($npass) > count($nfail)) {
                            echo 'Pass';
                        } else {
                            echo "Fail";
                        }
                        ?></td>
                    <td style="width:60%;padding:10px;"></td></tr>
            </tbody>
        </table>
</html>
