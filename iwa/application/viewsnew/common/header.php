<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php
        $fav = 'favicon.png';
        if (isset($this->session->userdata['theme_design']->favicon)) {
            $fav = $this->session->userdata['theme_design']->favicon;
        }
        ?>
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $fav; ?>" rel="shortcut icon" type="image/png" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/colorbox.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/colorpicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/smartpaginator.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/includes/css/reset.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/includes/css/style.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/farbtastic.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/jquery-ui-1.10.4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/css/bootstrap-switch.css" type="text/css" rel="stylesheet">


        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/includes/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/includes/js/jquery-ui.min.js"></script>

        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/colorpicker.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/eye.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/utils.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/layout.js?ver=1.0.2"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/farbtastic.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/smartpaginator.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/bootstrap-switch.js"></script>
        <!--  magnific popup css -->
        <link rel="stylesheet" href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/magnific/magnific-popup.css" type="text/css" />

        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/bootstrap-tokenfield.js" charset="UTF-8"></script>   
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/scrollspy.js" charset="UTF-8"></script>   
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/affix.js" charset="UTF-8"></script>   
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/typeahead.bundle.js" charset="UTF-8"></script>   
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/js/docs.min.js" charset="UTF-8"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/jquery.validate.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/additional-methods.min_1.js"></script>
        <script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/custom.js"></script>
        <!-- magnific popup js -->
        <script src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/magnific/jquery.magnific-popup.js"></script>
        <!-- Load Font -->
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />


        <!--DataTable-->
        <link rel="stylesheet" type="text/css" href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/datatable/media/css/jquery.dataTables.min.css">
        <script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/datatable/media/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/datatable/date-uk.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/datatable/extensions/TableTools/js/dataTables.tableTools.js"></script>
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/css/validation/screen.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/bootstrap/js/bootstrap.min.js"></script>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">

    <script type="text/javascript">
        $(function() {
            // tabs for homepage featured tabs
            $('.tabs').each(function() {
                // For each set of tabs, we want to keep track of
                // which tab is active and it's associated content
                var $active, $content, $links = $(this).find('a');

                // If the location.hash matches one of the links, use that as the active tab.
                // If no match is found, use the first link as the initial active tab.
                $active = $($links.filter('[href="' + location.hash + '"]')[0] || $links[0]);
                $active.addClass('active');
                $content = $($active.attr('href'));

                // Hide the remaining content
                $links.not($active).each(function() {
                    $($(this).attr('href')).hide();
                });

                // Bind the click event handler
                $(this).on('click', 'a', function(e) {
                    // Make the old tab inactive.
                    $active.removeClass('active');
                    $content.hide();

                    // Update the variables with the new link and content
                    $active = $(this);
                    $content = $($(this).attr('href'));

                    // Make the tab active.
                    $active.addClass('active');
                    $content.show();

                    // Prevent the anchor's default click action
                    e.preventDefault();
                });
            });
            $("#activate-column-selector").colorbox({width: "40%", inline: true, href: "#columnselector", onClosed: function() {
                    $('#columnselector').hide();
                }, onOpen: function() {
                    $('#columnselector').show();
                }});

            // Find Asset Search Result

            $('#srchitem').on('click', function() {
//                var code = $("#assetqrcode").val();
                var bar_code = $("#asset_search").val();
//                var qrcode = code + bar_code;
                if (bar_code != '')
                {

                    var base_url_str = $("#base_url").val();

                    $.ajax({
                        type: "POST",
                        url: base_url_str + "items/search_asset",
                        data: {
                            'bar_code': bar_code
                        },
                        success: function(res) {
//                            alert(res);
                            // we need to check if the value is the same
                            if (res) {
                                $('#searcherror').css('display', 'none');
                                $('#search_error').css('display', 'none');
                                window.location.href = base_url_str + 'items/view/' + res;
                                //Receiving the result of search here
                            }
                            else
                            {
                                show_filteritem(bar_code);

//                                $('#searcherror').css('display', 'none');
//                                $('#search_error').css('display', 'block');
                            }
                        }

                    });
                }
                else
                {
                    $('#searcherror').css('display', 'block');
                }
            });
            function show_filteritem(barcode)
            {
                var base_url_str = $("#base_url").val();
                window.location.href = base_url_str + 'items/filter_item/' + barcode;
            }
        });


    </script>
    <title><?php
        if (array_key_exists('strTitle', $arrPageParameters)) {
            echo $arrPageParameters['strTitle'];
        } else {
            echo $arrPageParameters['strPage'];
        }
        ?> | YouAudit Ltd</title>
    <style>
<?php
if (isset($this->session->userdata['theme_design']->color)) {
    ?>
            #breadcrumb li a,.box_content a {
                color: <?php echo $this->session->userdata['theme_design']->color; ?>;
            }
            /*            .box_content a{
                            color: <?php echo $this->session->userdata['theme_design']->color; ?>;
                        }*/
            #menu li a {
                background: none repeat scroll 0 0 <?php echo $this->session->userdata['theme_design']->color; ?>;
            }
            #menu li {
                background-color:<?php echo $this->session->userdata['theme_design']->color; ?>!important;
            }
            .panel-heading,.utitle,.tb_header,.tabs ul li,.list_table thead th,table.dataTable thead .sorting_asc
            {
                background-color:<?php echo $this->session->userdata['theme_design']->color; ?>!important;  
            }
            #item_table th, .table th
            {
                background-color:<?php echo $this->session->userdata['theme_design']->color; ?>!important;    
            }
            .icon-with-text i
            {
                border: 1px solid <?php echo $this->session->userdata['theme_design']->color; ?>!important;
                color:<?php echo $this->session->userdata['theme_design']->color; ?>!important;
            }
            .action-w .franchises-i
            {
                color:<?php echo $this->session->userdata['theme_design']->color; ?>!important;   
                border: 1px solid <?php echo $this->session->userdata['theme_design']->color; ?>!important;
            }
            .nav > li > a
            {
                color:  <?php echo $this->session->userdata['theme_design']->color; ?>!important; 
            }
            #menu li:hover, #menu li.selected
            {
                background: #eee none repeat scroll 0 0!important;
            }
            .nav > li > a
            {
                background-color:<?php echo $this->session->userdata['theme_design']->color; ?>!important; 
                color: #ffffff!important;
            }
                                                            
            .nav-pills > li.active a,.nav-pills > li  a:hover
            {
                background: #eee none repeat scroll 0 0!important; 
                color: #000000!important;
            }
                                                            
            .logo_cls {
                float: left;
                margin: 20px;
                max-height: 250px;
                max-width: 300px;
            }
            .img_replace {
                width: 100%;
                width: 175px \9;
            }
                                
            #srchitem
            {
                height: 34px;
            }
                                                                                    
            /*            .img_replace {                 
                            display: block;
                            text-indent: -99999px;

                        }*/
            #menu {
                bottom: 0;
                float: left;
                position: static;
                width: 100%;
            }
            #header {
                background: url("../img/header-bg.png") repeat-x scroll center bottom rgba(0, 0, 0, 0);
                float: left;
                height: auto;
                padding: 0 15px;
                position: static;
                width: 100%;
            }
            table.dataTable thead .sorting,
            table.dataTable thead .sorting_desc
            {
                background-color: <?php echo $this->session->userdata['theme_design']->color; ?>!important;
            }
            #third_table a.button, input.button,.button_holder a.button, input.button,.ver_tabs a,div#history_table_wrapper.dataTables_wrapper div div table#history_table.list_table.dataTable thead tr th
            {
                background-color: <?php echo $this->session->userdata['theme_design']->color; ?>!important;
                color: #ffffff!important;
            }
            .multiadd,.blue-border
            {
                border: 1px solid <?php echo $this->session->userdata['theme_design']->color; ?>;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover
            {
                border: 1px solid <?php echo $this->session->userdata['theme_design']->color; ?> !important;
                color: <?php echo $this->session->userdata['theme_design']->color; ?> !important;
            }
            #view_itemdetails .table tr,#acc_details .table tr
            {
                box-shadow: 0 -1px 0 0 <?php echo $this->session->userdata['theme_design']->color; ?> inset!important;
            }
            .modal-header { 
                background-color: <?php echo $this->session->userdata['theme_design']->color; ?>!important;
            }
            .modal-footer
            {
                background-color: <?php echo $this->session->userdata['theme_design']->color; ?>!important;  
            }
            .backarrow
            {
                background-color: <?php echo $this->session->userdata['theme_design']->color; ?>!important;
                padding: 5px 16px 5px 16px;
                border-radius: 4px !important;
                font-size: 22px;
                transform: none !important;
                color: #ffffff;
            }
                
            /*            #header {                           
                            background: url("../img/header-bg.png") repeat-x scroll center bottom rgba(0, 0, 0, 0);
                            height: 145px;
                            padding: 0 15px;
                            position: relative;
                        }*/


    <?php
}
?>


        .error_outer p {
            background: none repeat scroll 0 0 red;
            border-radius: 8px;
            color: #fff;
            font-size: 20px;
            margin: 0 auto;
            padding: 14px 20px;
            width: 753px;
        }
        #search_error
        {
            color: #CC0000;
            display: none;
        }
        #searcherror
        {
            color: #CC0000;
            display: none;
        }
        #asset_search
        {
            width: 210px;
            float: left;
        }
    </style>
</head>
<body>

    <div id="wrapper">
        <div id="header">
            <?php
            $logo = 'logo.png';
//            if (isset($this->session->userdata['theme_design']->logo) && $this->session->userdata['theme_design']->logo != '') {
//
//                $logo = $this->session->userdata['theme_design']->logo;
//            }
            ?>

            <div class="logo_cls">
                <img  alt="iSchool"  class="img_replace" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>"  >
            </div>

            <!-- search asset according to QRCode starts here --> 
            <?php if ((array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin']) || (array_key_exists('booInheritedUser', $arrSessionData) && $arrSessionData['booInheritedUser'])) { ?>
                <!-- search icon --> 
                <!--<input type="hidden" id="assetqrcode" value="<?php // echo $this->session->userdata('objSystemUser')->qrcode;            ?>">-->
                <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                <div class="input-group custom-search-form"  style="float: right;margin-top: 60px!important;width: 250px;">
                    <input type="text" id="asset_search" placeholder="Enter QRCode To Search Asset.." name="asset_qrcode" class="form-control">
    <!--                    <span id="search_error">QRCode you entered is wrong</span>-->
                    <span class="input-group-btn" style="width:40px;float:right;">
                        <button type="button" class="btn btn-default" id="srchitem">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                    <span id="search_error">QRCode you entered is wrong</span>
                    <span id="searcherror">Please Enter QRCode</span>
                </div>
            <?php } ?>
            <!-- search asset according to QRCode ends here --> 

            <?php
            if (array_key_exists('YouAuditSystemAdmin', $this->session->userdata)) {
                $data = $this->session->userdata('YouAuditSystemAdmin');
                ?>
                <ul id="login"> <li>You're logged in as <?php echo $data['firstname']; ?></li>
                    <li><a href="<?php echo base_url('youaudit/logout/'); ?>">Logout</a></li>
                </ul>
                <?php
            }
            ?> 
            <?php
            if (
                    (array_key_exists('booAdminLogin', $arrSessionData) && $arrSessionData['booAdminLogin']) ||
                    (array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin'])
            ) {
                ?><ul id="login">

                    <?php
                    if (array_key_exists('booAdminLogin', $arrSessionData) && $arrSessionData['booAdminLogin']) {
                        ?>
                        <li>You're logged in as <?php echo $arrSessionData['objAdminUser']->nickname; ?></li>

                        <?php
                        if (array_key_exists('booInheritedUser', $arrSessionData) && $arrSessionData['booInheritedUser']) {
                            ?>
                            <li>You've inherited the profile of <?php
                                echo $arrSessionData['objInheritedUser']->firstname . " ";
                                echo $arrSessionData['objInheritedUser']->lastname . " ";
                                echo "(" . $arrSessionData['objInheritedUser']->accountname . ", ";
                                echo $arrSessionData['objInheritedUser']->levelname . ")";
                                ?></li> 
                            <li><a href="<?php echo base_url('/admins/deinherituser/'); ?>">Deinherit</a></li>
                            <?php
                        } else {
                            ?>
                            <li><a href="<?php echo base_url('/admins/logout/'); ?>">Logout</a></li>
                            <?php
                        }
                    }

                    if (array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin']) {
                        ?>


                        <li> <img src="<?= base_url('/img/account-icon.png'); ?>" alt="..."/>You're logged in as <a class="avatar" style="background: url('<?php echo site_url('/images/viewnewavatar/' . $arrSessionData['objSystemUser']->photoid); ?>') no-repeat;" href="#"><?php echo $arrSessionData['objSystemUser']->firstname . " " . $arrSessionData['objSystemUser']->lastname; ?></a></li>
                        <li><a href="<?php echo base_url('/users/logout/'); ?>">Logout</a></li>



                        <?php
                    }
                    ?>
                </ul><?php
            }

            if ((array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin']) || (array_key_exists('booInheritedUser', $arrSessionData) && $arrSessionData['booInheritedUser'])) {

                $this->load->view('common/usermenu');
            } elseif (array_key_exists('booAdminLogin', $arrSessionData) && $arrSessionData['booAdminLogin']) {

                $this->load->view('admins/adminmenu');
            } elseif (array_key_exists('YouAuditSystemAdmin', $this->session->userdata)) {

                echo $this->view('youaudit/admins/topmenu');
            }
            ?></div>
        <div id="content">
            <ul id="breadcrumb" >
                <li><a href="<?php echo site_url(); ?>">YouAudit</a></li>
                <?php
                if (array_key_exists('strPage', $arrPageParameters)) {
                    // print the section, if exists (not for welcome page)
                    if (array_key_exists('strSection', $arrPageParameters)) {
                        echo '<li><a href="' . site_url('/' . strtolower($arrPageParameters['strSection']) . '/') . '">';
                        echo $arrPageParameters['strSection'] . "</a></li>";

                        // print the sub section, if exists (not for most pages)
                        if (array_key_exists('strSubSection', $arrPageParameters)) {
                            echo '<li><a href="' . site_url('/' . strtolower($arrPageParameters['strSection']) . '/view' . strtolower($arrPageParameters['strSubSection']) . '/') . '">';
                            echo $arrPageParameters['strSubSection'] . "</a></li>";
                        }
                    }
                    if (array_key_exists('strSectionYouaudit', $arrPageParameters)) {
                        echo '<li><a href="' . site_url('/' . strtolower($arrPageParameters['strSectionYouauditdashboard']) . '/') . '">';
                        echo $arrPageParameters['strSectionYouaudit'] . "</a></li>";
                    }

                    // all pages should have a page set, but avoid PHP errors
                    if (array_key_exists('strPage', $arrPageParameters)) {
                        echo "<li>" . $arrPageParameters['strPage'] . '</li>';
                    }
                }
                ?></ul>

            <?php
            // if we have errors set by this controller function, OR some have been set by a referral
            if (
                    (count($arrErrorMessages) > 0) ||
                    (array_key_exists('booCourier', $arrSessionData) && $arrSessionData['booCourier'] && array_key_exists('arrErrorMessages', $arrSessionData['arrCourier'])
                    )
            ) {
                ?>
                <div id="system_message" >

                    <?php
                    if (
                            array_key_exists('arrCourier', $arrSessionData) &&
                            array_key_exists('arrErrorMessages', $arrSessionData['arrCourier'])
                    ) {
                        $arrSessionErrorMessages = $arrSessionData['arrCourier']['arrErrorMessages'];
                        foreach ($arrSessionErrorMessages as $strMessage) {
                            ?>
                            <div class="error"><?php echo $strMessage; ?></div>
                            <?php
                        }
                    }
                    ?>

                    <?php
                    foreach ($arrErrorMessages as $strMessage) {
                        ?>
                        <div class="error"><?php echo $strMessage; ?></div>
                    <?php }
                    ?>

                </div>
            <?php } ?>

            <?php
            if (
                    (count($arrUserMessages) > 0) ||
                    (array_key_exists('booCourier', $arrSessionData) && $arrSessionData['booCourier'] && array_key_exists('arrUserMessages', $arrSessionData['arrCourier'])
                    )
            ) {
                ?>
                <div id="system_message" >

                    <?php
                    if (
                            array_key_exists('arrCourier', $arrSessionData) &&
                            array_key_exists('arrUserMessages', $arrSessionData['arrCourier'])
                    ) {
                        $arrSessionUserMessages = $arrSessionData['arrCourier']['arrUserMessages'];
                        foreach ($arrSessionUserMessages as $strMessage) {
                            ?>
                            <div class="success"><?php echo $strMessage; ?></div>
                            <?php
                        }
                    }
                    ?>
                    <?php
                    foreach ($arrUserMessages as $strMessage) {
                        ?>
                        <div class="success"><?php echo $strMessage; ?></div>
                    <?php }
                    ?>

                </div>
            <?php } ?>
            <div class="box">
