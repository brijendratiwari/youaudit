<div class="heading">
    <h1>Welcome, <?php echo $arrSessionData['objSystemUser']->nickname; ?></h1>
    <div class="buttons">
    </div>
    <div class="text-right">
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
</div>
