<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta name="description=" content="<?php echo meta_description; ?>"/>
    <link href="<?php echo path_relative; ?>favicon.png" rel="shortcut icon" type="image/png" />

    <link href="brochure/css/reset.css" rel="stylesheet" type="text/css" />
    <link href="brochure/css/style.css" rel="stylesheet" type="text/css" />
    <link href="brochure/css/colorbox.css" rel="stylesheet" type="text/css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="//dnn506yrbagrg.cloudfront.net/pages/scripts/0014/3563.js?<?php echo floor(time()/3600); ?>" async="true"></script>
    <script type="text/javascript" src="brochure/js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="brochure/js/cufon-yui.js"></script>
    <script type="text/javascript" src="brochure/js/titillium_400.font.js"></script>
    <script type="text/javascript" src="brochure/js/titillium-bold_700.font.js"></script>
    <script type="text/javascript" src="brochure/js/titillium-xbold_400.font.js"></script>
    <script type="text/javascript">
        Cufon.replace('#header .contact .telephone', { fontFamily:'titillium', hover: true});
        Cufon.replace('#banner .title', { fontFamily:'titillium-bold', hover: true});
        Cufon.replace('.banner-ad h2.title', { fontFamily:'titillium-xbold', hover: true});
        Cufon.replace('.banner-ad h3.title', { fontFamily:'titillium-bold', hover: true});
        Cufon.replace('#banner_reseller .title', { fontFamily:'titillium-bold', hover: true});
        Cufon.replace('#packages .package .title', { fontFamily:'titillium-xbold', hover: true});
        Cufon.replace('#packages .package .price strong', { fontFamily:'titillium-xbold', hover: true});
        Cufon.replace('#content h3', { fontFamily:'titillium-bold', hover: true});
        Cufon.replace('legend', { fontFamily:'titillium-bold', hover: true});
        Cufon.replace('#footer .telephone', { fontFamily:'titillium', hover: true});
        Cufon.replace('#footer .email', { fontFamily:'titillium', hover: true});
        Cufon.replace('.client-quotes .quote', { fontFamily:'titillium', hover: true});
        Cufon.replace('li.tel', { fontFamily:'titillium-xbold', hover: true});
        Cufon.replace('.reseller_address h4', { fontFamily:'titillium-bold', hover: true});


        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-3619863-85', 'iworkaudit.com.au');
        ga('send', 'pageview');


        $(function() {
            $('.colorbox').colorbox({iframe:true, innerWidth:800, height:"98%"});
        });
    </script>