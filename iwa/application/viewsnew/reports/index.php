   <div class="box">
    	<div class="heading">
            <h1>Reports</h1>
               <div class="text-right col-md-5 col-sm-3 pull-right">
                <span class="com-name">                     <?= $arrSessionData['objSystemUser']->accountname; ?>
                    <!--<img src="<?= base_url('/img/circle-red.png'); ?>" width="60" />-->
                </span>
                   <?php
            $logo = 'logo.png';
            if (isset($this->session->userdata['theme_design']->logo) && $this->session->userdata['theme_design']->logo != '') {

                $logo = $this->session->userdata['theme_design']->logo;
            }
            ?>

            <div class="logocls">
                <img  alt="iSchool"  class="imgreplace" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>"  >

            </div>
            </div>
            <!--<div class="buttons">-->
            <!--<a class="btn btn-success btn-sm" onclick="$('#report').submit();">Generate</a>-->
        <!--</div>-->
        </div>
        <div class="box_content">
            <div class="content_main">

            <?php echo form_open('reports/generate/', array('id'=>'report')); ?>
