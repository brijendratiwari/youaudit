<?php // echo '<pre>';var_dump($allData);echo '</pre>';
$logo = $this->session->userdata['theme_design']->logo;
//if (isset($this->session->userdata['theme_design']->logo)) {
//    $logo = $this->session->userdata['theme_design']->logo;
//}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/css/custom.css" rel="stylesheet" type="text/css">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/fonts/stylesheet.css" rel="stylesheet" type="text/css">
<!--DataTable-->
        <link rel="stylesheet" type="text/css" href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/datatable/media/css/jquery.dataTables.min.css">
        <script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/datatable/media/js/jquery.dataTables.min.js"></script>
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    </head>

    <body>
        <div class="main_container" style='width: 100%;'>
            <div class="border_top">
                <!--<img src="<?php // echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/report_files/images/border_bg.png"/>-->
                <img alt='Youaudit' src='<?php echo base_url('brochure/logo/' . $logo); ?>'>
            </div>
<!--            <div class="logo">
                <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/report_files/images/logo.png"/>
            </div>-->
<!--            <div class="insert1">
                <img width="200px" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>">
            </div>-->
<!--            <div class="insert2">
                <?php // echo $accountDetails['result'][0]->accountname; ?>
            </div>-->
            <div class="insert2">
                <?php echo $title; ?>
            </div>
<!--            <div class="insert3">
                <?php // echo $title; ?>
            </div>-->

            
            <div class="table1">
                <table class="tbl1 table table-striped table-bordered table-hover">
                    <?php echo $allData;?>
                </table>
            </div>
            
            <div class="border_top border_top1 footer_bg">
                <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/report_files/images/border_bg.png"/>
            </div>
        </div>
    </body>
</html>
<style>
    .item_list td
    {
        border: 1px solid #000000;
        padding: 0px;
    }
    .item_list th
    {
        border: 1px solid #000000;
    }
</style>
