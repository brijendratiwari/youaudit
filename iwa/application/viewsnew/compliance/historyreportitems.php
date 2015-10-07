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
        <div class="main_container">
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
                            <?php }
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
                                    <td class="width2"><?php $temp = explode(' ', $value['result']);
                                echo $temp[0];
                                ?></td>		
                                    <td  class="width3"><?php $temp = explode(' ', $value['result']);
                                echo $temp[1];
                                ?></td>
                                    <td><?php echo $value['test_notes']; ?></td>
                                </tr>
        <?php }
    }
?>
                </table>
            </div>
            <div class="qr" ><b>Signature:</b><?php echo $allData[8]; ?></div>

            <div class="border_top border_top1 footer_bg">
                <img src="/youaudit/includes/report_files/images/border_bg.png"/>
            </div>
        </div>
    </body>
</html>
