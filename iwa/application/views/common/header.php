<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 
<head>
<link href="https://www.ischoolaudit.com/favicon.png" rel="shortcut icon" type="image/png" />
  
    <title><?php
            if (array_key_exists('strTitle', $arrPageParameters))
            {
                echo $arrPageParameters['strTitle'];
            }
            else
            {
                echo $arrPageParameters['strPage'];
            }
            ?> | iSchool Audit</title>
    <link rel="stylesheet" type="text/css" media="all" href="https://www.ischoolaudit.com/includes/css/isa/style.css" />
    <link rel="stylesheet" type="text/css" media="all" href="https://www.ischoolaudit.com/includes/css/isa/jquery-ui.css" />
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
</head>
<body>
    <?php 
    
    if (array_key_exists('booAdminLogin', $arrSessionData) && $arrSessionData['booAdminLogin']) { ?>
    <div id="sys_admin_bar" class="user_identification_bar">
        <p>Hi there, <?php echo $arrSessionData['objAdminUser']->nickname; ?>! <a href="<?php
        
        echo site_url('/admins/logout/');
        
        ?>">Logout</a>
        <?php
        if (array_key_exists('booInheritedUser', $arrSessionData) && $arrSessionData['booInheritedUser'])
        {
            ?>
            <br />
            You have inherited the profile of <?php echo $arrSessionData['objInheritedUser']->firstname." ";
                            echo $arrSessionData['objInheritedUser']->lastname." ";
                            echo "(".$arrSessionData['objInheritedUser']->accountname.", ";
                            echo $arrSessionData['objInheritedUser']->levelname.")";
                            ?> <a href="<?php
                    echo site_url('/admins/deinherituser/');
                    ?>">Deinherit</a>
            <?php
        }
        ?>
        </p>
    </div>
    <?php } ?>
    <?php if (array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin']) { ?>
    <div id="sys_user_bar" class="user_identification_bar" <?php 
        if ($arrSessionData['objSystemUser']->photoid > 1)
        {
            echo "style=\"background: #1a1a1a url('".site_url('/images/viewavatar/'.$arrSessionData['objSystemUser']->photoid)."') no-repeat top right;
                            padding-right: 50px;\"";
        }
        ?>>
        <p>You're logged in as <?php echo $arrSessionData['objSystemUser']->firstname." ".$arrSessionData['objSystemUser']->lastname; ?> - <a href="<?php
        
        echo site_url('/users/logout/');
        
        ?>">Logout</a>
        </p>
    </div>
    <?php } ?>
    
    <div id="logo_bar">
        <a href="<?php echo site_url(); ?>"><img src="http://www.ischoolaudit.com/img/ischoolaudit_logo.png" alt="iSchoolaudit logo" /></a>
    </div>
    
    <?php if ((array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin'])
                ||(array_key_exists('booInheritedUser', $arrSessionData) && $arrSessionData['booInheritedUser']))
    {
        
        $this->load->view('common/usermenu');
    
    }
    else
    {
        if (array_key_exists('booAdminLogin', $arrSessionData) && $arrSessionData['booAdminLogin']) {
        
            $this->load->view('admins/adminmenu');
        }
    
    } ?>
    
    
    

    <div id="breadcrumb"><p><a href="<?php
        echo site_url();
        ?>">iSchool Audit</a> &gt; <?php 
            if (array_key_exists('strPage', $arrPageParameters))
            {
                // print the section, if exists (not for welcome page)
                if (array_key_exists('strSection', $arrPageParameters))
                {
                    echo '<a href="'.site_url('/'.strtolower($arrPageParameters['strSection']).'/').'">';
                    echo $arrPageParameters['strSection']."</a> &gt; ";
                    
                    // print the sub section, if exists (not for most pages)
                    if (array_key_exists('strSubSection', $arrPageParameters))
                    {
                        echo '<a href="'.site_url('/'.strtolower($arrPageParameters['strSection']).'/view'.strtolower($arrPageParameters['strSubSection']).'/').'">';
                        echo $arrPageParameters['strSubSection']."</a> &gt; ";
                    }
                }
                
                // all pages should have a page set, but avoid PHP errors
                if (array_key_exists('strPage', $arrPageParameters))
                {
                        echo $arrPageParameters['strPage'].' &gt;';
                }
            }
        ?></p></div>
    
    <?php
    // if we have errors set by this controller function, OR some have been set by a referral
    if (
                (count($arrErrorMessages) > 0)
                ||
                (array_key_exists('booCourier', $arrSessionData)
                    && $arrSessionData['booCourier']
                    && array_key_exists('arrErrorMessages', $arrSessionData['arrCourier'])
                    )
        )
    { ?>
    <div id="sys_error_messages" >
        <ul>
            <?php
                if (
                    array_key_exists('arrCourier', $arrSessionData)
                    &&
                    array_key_exists('arrErrorMessages', $arrSessionData['arrCourier'])
                    )
                {
                    $arrSessionErrorMessages = $arrSessionData['arrCourier']['arrErrorMessages'];
                    foreach ($arrSessionErrorMessages as $strMessage)
                    {
                ?>
                    <li><?php echo $strMessage; ?></li>
                <?php
                    }
                } ?>
            
            <?php
            foreach ($arrErrorMessages as $strMessage)
            { ?>
            <li><?php echo $strMessage; ?></li>
            <?php
            } ?>
        </ul>
    </div>
    <?php } ?>
    
    <?php
    if (
                (count($arrUserMessages) > 0)
                ||
                (array_key_exists('booCourier', $arrSessionData)
                    && $arrSessionData['booCourier']
                    && array_key_exists('arrUserMessages', $arrSessionData['arrCourier'])
                    )
        )
    {
    ?>
    <div id="sys_user_messages" >
        <ul>
            <?php
                if (
                    array_key_exists('arrCourier', $arrSessionData)
                    &&
                    array_key_exists('arrUserMessages', $arrSessionData['arrCourier'])
                    )
                {
                    $arrSessionUserMessages = $arrSessionData['arrCourier']['arrUserMessages'];
                    foreach ($arrSessionUserMessages as $strMessage)
                    {
                ?>
                    <li><?php echo $strMessage; ?></li>
                <?php
                    }
                } ?>
            <?php
            foreach ($arrUserMessages as $strMessage)
            { ?>
            <li><?php echo $strMessage; ?></li>
            <?php
            } ?>
        </ul>
    </div>
    <?php } ?>
    <div id="page_content">
    