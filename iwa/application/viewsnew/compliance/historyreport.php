<?php
// echo "<pre>";var_dump($allData);echo "</pre><pre>";var_dump($tasks);echo "</pre>";
$logo = 'logo.png';
if (isset($this->session->userdata['theme_design']->logo)) {
    $logo = $this->session->userdata['theme_design']->logo;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Compliance History</title>
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/css/custom.css" rel="stylesheet" type="text/css">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/fonts/stylesheet.css" rel="stylesheet" type="text/css">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    </head>

    <body>


        <div class="main_container">
            <!--            <div class="border_top">
                            <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/images/border_bg.png"/>
                        </div>-->
            <!--            <div class="logo">
                            <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/images/logo.png"/>
                        </div>-->
            <div class="insert1">
                <img width="200px" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>">
            </div>
            <div class="insert2">
<?php echo $accountDetails['result'][0]->accountname; ?>
            </div>
            <div class="content">
                <div class="address">
                    <div> <?php echo $accountDetails['result'][0]->accountaddress; ?></div>
                    <div> <?php echo $accountDetails['result'][0]->accountcity . ', ' . $accountDetails['result'][0]->accountcounty . ', ' . $accountDetails['result'][0]->accountpostcode; ?></div>
                    <div ><b>Phone:</b> <?php echo $accountDetails['result'][0]->accountcontactnumber; ?></div>
                    <div ><b>Email:</b> <?php echo $accountDetails['result'][0]->accountcontactemail; ?></div>
                    <div class="qr" ><b>QR CODE:</b> <?php echo preg_replace('/<\/?pre[^>]*>/', '', preg_replace('/<\/?a[^>]*>/', '', $allData[1])); ?></div>
                    <div ><b>Manufacturer:</b> <?php echo $allData[2]; ?></div>
                    <div ><b>Model:</b> <?php echo $allData[3]; ?></div>
                    <div ><b>Category:</b> <?php echo $allData[4]; ?></div>
                    <div ><b>Location:</b> <?php echo $allData[6]; ?></div>
                    <div ><b>Site Name:</b> <?php echo $allData[7]; ?></div>


                </div>
                <div class="right">
                    <div class="compliance">
                        Compliance Report
                    </div>
                    <div  class="insert"><?php echo $allData[11]; ?>
                    </div>
                    <div  class="compliance_name"><b>Compliance Name:</b><?php echo $allData[8]; ?></div>
                    <div  style="    font-family: 'calibriregular';"><b>Logged By:</b><?php echo $allData[9]; ?></div>
                    <!--<div  style="    font-family: 'calibriregular';" ><b>Model:</b><?php echo $allData[10]; ?></div>-->
                    <div  style="    font-family: 'calibriregular';" ><b>Due Date:</b><?php echo $allData[10]; ?></div>
                    <div   style="    font-family: 'calibriregular';"><b>Owner:</b><?php echo $allData[5]; ?></div>
                    <!--<div  style="    font-family: 'calibriregular';" ><b>Model:</b>[Model Name]</div>-->
                </div>
            </div>
            <div class="result">
                <div class="overall">
                    Overall Result
                    <span >
                        <?php if ($allData[13] == 'Pass') { ?>
                            <img class="icons" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/report_files/images/right.png"/>
                        <?php } if ($allData[13] == 'Fail') { ?>
                            <img class="icons" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/report_files/images/cross.png"/>
<?php } if ($allData[12] == 'Missed') { ?>
                            <img class="icons" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/report_files/images/minus.png"/>
    <!--                        <a href=""><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/images/minus.png"/></a>-->
<?php } ?>
                    </span>
                </div>
            </div>
            <div class="table1">
                <span style="font-family: 'calibribold';">Tasks</span>
                <table class="tbl1 table table-striped table-bordered">
                    <tr class="header" style="color:white">
                        <th style="color:black">TASK NAME</th>
                        <th style="color:black">RESULT</th>		
                        <th style="color:black">NOTES</th>
                    </tr>
                    <?php
                    $temp = json_decode($tasks, TRUE);
//                    var_dump($temp);
                    if (!empty($temp))
                        foreach ($temp as $key => $value) {
//                            var_dump(strlen(($value['result']),$value['result']));
                            if (isset($value['result'])) {
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
            <?php }
        }else {
            if ((int) $value['type_of_task'] == 0) { ?>
                                    <tr>
                                        <td valign="top" width="43%" >
                                            <p align="center">
                <?php echo $value['task_name']; ?>
                                            </p>
                                        </td>
                                        <td valign="top" width="18%" >
                                            <p align="center">
                                                Missed
                                            </p>
                                        </td>
                                        <td valign="top" width="38%" >
                                            <p align="center">
                                                Missed
                                            </p>
                                        </td>
                                    </tr>
            <?php }
        }
    } ?>
                </table>
            </div>
            <div class="table1">
                <span style="    font-family: 'calibribold';">Measurements</span>
                <table class="tbl1 tbl2 table table-striped table-bordered">

                    <tr class="header" style="color:white">
                        <th style="color:black">TASK NAME</th>
                        <th style="color:black">Value</th>		
                        <th style="color:black">Type</th>
                        <th style="color:black">NOTES</th>
                    </tr>
                    <?php
                    $temp = json_decode($tasks, TRUE);
//                    var_dump($temp);
                    if (!empty($temp))
                        foreach ($temp as $key => $value) {
//                        var_dump(strlen(($value['result']),$value['result']));
                            if (isset($value['result'])) {
                                if (strlen($value['result']) > 1) {
                                    ?>
                                    <tr>
                                        <td class="width1"><?php echo $value['task_name']; ?></td>
                                        <td class="width2"><?php $temp = explode(' ', $value['result']);
                    echo $temp[0]; ?></td>		
                                        <td  class="width3"><?php $temp = explode(' ', $value['result']);
                    echo $temp[1]; ?></td>
                                        <td><?php echo $value['test_notes']; ?></td>
                                    </tr>
                                <?php }
                            } else {
                                if ((int) $value['type_of_task'] == 1) { ?>
                                    <tr>
                                        <td class="width1"><?php echo $value['task_name']; ?></td>
                                        <td class="width2">Missed</td>		
                                        <td  class="width3">Missed</td>
                                        <td>Missed</td>
                                    </tr>
            <?php }
        }
    } ?>
<!--                    <tr>
                        <td>% of Gas Available</td>
                        <td>65</td>  
                        <td>%</td>
                        <td>[Insert Notes]</td>
                    </tr>
                    <tr>
                        <td>Time Taken</td>
                        <td>2</td>		
                        <td>hrs</td>
                        <td>[Insert Notes]</td>

                    </tr>
                    <tr>
                        <td>[Insert Task]</td>
                        <td>[Value]</td>
                        <td>[Value]</td>
                        <td>[Insert Notes]</td>

                    </tr>
                    <tr>
                        <td>[Insert Task]</td>
                        <td>[Value]</td>
                        <td>[Value]</td>
                        <td>[Insert Notes]</td>

                    </tr>
                    <tr>
                        <td>[Insert Task]</td>
                        <td>[Value]</td>

                        <td>[Value]</td>
                        <td>[Insert Notes]</td>

                    </tr>-->

                </table>
            </div>
            <div class="border_top border_top1 footer_bg">

<!--  <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/images/border_bg.png"/>-->
            </div>
        </div>
    </body>
</html>
