<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <link href="https://www.iworkaudit.com/favicon.png" rel="shortcut icon" type="image/png" />
        <link href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/brochure/css/colorbox.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/brochure/css/colorpicker.css" rel="stylesheet" type="text/css" />
        <link href="https://www.iworkaudit.com/includes/css/reset.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/includes/css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" media="all" href="https://www.ischoolaudit.com/includes/css/jquery-ui.css" />

        <script type="text/javascript" src="https://www.ischoolaudit.com/includes/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
        <script type="text/javascript" src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/includes/js/gmap3.min.js"></script>
        <script type="text/javascript" src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/brochure/js/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/brochure/js/colorpicker.js"></script>
        <script type="text/javascript" src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/brochure/js/eye.js"></script>
        <script type="text/javascript" src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/brochure/js/utils.js"></script>
        <script type="text/javascript" src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/brochure/js/layout.js?ver=1.0.2"></script>

        <script type="text/javascript">
            $(function() {
                $('.googleMap').colorbox({innerWidth: "600px", innerHeight: "350px", inline: true, href: "#mymap"});
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
                $(".googleMap").click(function(event) {
                    event.preventDefault();
                    //var href = $( this ).attr( 'href' );
                    //var href_split = str.split('&');
                    var marker_opts = null;
                    var marker_opts_options = {
                        icon: new google.maps.MarkerImage(
                                "http://gmap3.net/skin/gmap/magicshow.png",
                                new google.maps.Size(32, 37, "px", "px")
                                )
                    };

                    var lat = $(this).attr('lat');
                    var lon = $(this).attr('lon');
                    var postcode = $(this).attr('postcode');

                    //$("#my_map").width("600px").height("350px").gmap3();
                    if ((lat && lon) || postcode) {
                        if (lat && lon != 0) {
                            marker_opts = {
                                latLng: [lat, lon],
                                options: marker_opts_options
                            }
                        } else {
                            marker_opts = {
                                address: postcode,
                                options: marker_opts_options
                            }
                        }
                    }
                    if (marker_opts) {
                        $('#mymap').gmap3({
                            map: {
                                options: {
                                    maxZoom: 14
                                }
                            },
                            marker: marker_opts
                        },
                        "autofit"
                                );
                    }

                });

                $("#activate-column-selector").colorbox({width: "40%", height: "80%", inline: true, href: "#columnselector", onClosed: function() {
                        $('#columnselector').hide();
                    }, onOpen: function() {
                        $('#columnselector').show();
                    }});
            });


        </script>
        <title><?php
            if (array_key_exists('strTitle', $arrPageParameters)) {
                echo $arrPageParameters['strTitle'];
            } else {
                echo $arrPageParameters['strPage'];
            }
            ?> | iWork Audit</title>
        <style>
            
            #breadcrumb li a {
                color: #<?php echo $arrSessionData['objSystemUser']->accountcompliancecolor;   ?>;
            }
            .box_content a{
                color: #<?php echo $arrSessionData['objSystemUser']->accountcompliancecolor;   ?>;
            }
            #menu li a {
    background: none repeat scroll 0 0 #<?php echo $arrSessionData['objSystemUser']->accountcompliancecolor;   ?>;
            }
            
        </style>
    </head>
    <body>
    
        <div id="wrapper">
            <div id="header">
<?php  if($arrSessionData['objSystemUser']->accountcompliancefilename != ''){ ?> 
                <img  class="img_replace" src="<?php echo '../../brochure/img/'.$arrSessionData['objSystemUser']->accountcompliancefilename;   ?>"  >

<?php }else{  ?>
<img  class="img_replace" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/iwa/brochure/img/logo5.png'; ?>" >
<?php } ?>
                <!--<a id="logo1" class="img_replace" href="<?php // echo 'brochure/img/'.$arrAccount['result'][0]->accountcompliancefilename;   ?> ">iWork Audit</a>-->
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
                                <li><a href="<?php echo site_url('/admins/deinherituser/'); ?>">Deinherit</a></li>
                                    <?php
                                } else {
                                    ?>
                                <li><a href="<?php echo site_url('/admins/logout/'); ?>">Logout</a></li>
                                    <?php
                                }
                            }

                            if (array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin']) {
                                ?>


                            <li>You're logged in as <a class="avatar" style="background: url('<?php echo site_url('/images/viewnewavatar/' . $arrSessionData['objSystemUser']->photoid); ?>') no-repeat;" href="#"><?php echo $arrSessionData['objSystemUser']->firstname . " " . $arrSessionData['objSystemUser']->lastname; ?></a></li>
                            <li><a href="<?php echo site_url('/users/logout/'); ?>">Logout</a></li>



                            <?php
                        }
                        ?>
                    </ul><?php
                    }

                    if ((array_key_exists('booUserLogin', $arrSessionData) && $arrSessionData['booUserLogin']) || (array_key_exists('booInheritedUser', $arrSessionData) && $arrSessionData['booInheritedUser'])) {

                        $this->load->view('common/usermenu');
                    } else {
                        if (array_key_exists('booAdminLogin', $arrSessionData) && $arrSessionData['booAdminLogin']) {

                            $this->load->view('admins/adminmenu');
                        }
                    }
                    ?></div>
            <div id="content">
                <ul id="breadcrumb" >
                    <li><a href="<?php echo site_url(); ?>">iWork Audit</a></li>
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
