<!-- TODO: Refine this method further. All JS calls are hardcoded in App, is this the future? - Matt -->
<script type="text/javascript">
    function isaSuppliers_showList()
    {
        isaLogging_doConsoleLog('isaSuppliers_showList() Started');



        isa_doShowWhizzer();
        objData = {
            username: strGlobalUsername,
            password: strGlobalPassword,
            timestamp: Math.round(new Date().getTime() / 1000) //to make the query unique
        };

        $.ajax({
            type:        'POST',
            url:         strWebServiceURI + "supplierlist",
            dataType:    'json',
            data:        objData,
            timeout:     intShortTimeout,
            success:     function(data)
            {
                if (data.booError == false)
                {
                    isaLogging_doConsoleLog('isaPat_showLookUpForm() - gotData');

                    $("#window").html(data.strHtml);
                    $("#header").html(data.strHeader);
                    isa_doHideWhizzer();
                    isa_showMainScreen();
                    //isa_loadHeader('location');
                }
                else
                {
                    isaLogging_doConsoleLog('isaSupplier_showList() - PHP Error');
                    isa_doHideWhizzer();
                    navigator.notification.alert(
                        "Sorry, I couldn't access the information",
                        false,
                        "ischoolaudit",
                        "OK");
                }
            },
            error:       function(xhr)
            {
                isaLogging_doConsoleLog('isaPat_showLookUpForm() - TX Error');
                isa_doHideWhizzer();
                navigator.notification.alert(
                    "There was a problem communicating to the Internet: " + xhr.responseText,
                    false,
                    "iworkaudit",
                    "OK");
            }
        });
    }

    function isaSuppliers_getSupplier(intSupplierID)
    {
        //console.log(intSupplierID);
        isaLogging_doConsoleLog('isaSuppliers_getSupplier('+intSupplierID+')');
        isa_doShowWhizzer();
        //booSearch = typeof booSearch !== 'undefined' ? booSearch : false;
        //booSearch = ((booSearch != null) ? booSearch : false);
        objData = {
            username: strGlobalUsername,
            password: strGlobalPassword,
            timestamp: Math.round(new Date().getTime() / 1000) //to make the query unique
        };

        //isaLogging_doConsoleLog('isaItem_getItem('+strItemBarcode+','+booSearch+')');
        $.ajax({
            type:        'POST',
            url:         strWebServiceURI + "getsupplier/" +intSupplierID,
            dataType:    'json',
            data:        objData,
            timeout:     intShortTimeout,
            success:     function(data){

                if (data.booError == false)
                {
                    isaLogging_doConsoleLog('isaSuppliers_getSupplier() - gotData');

                    $("#window").html(data.strHtml);
                    $("#header").html(data.strHeader);
                    isa_doHideWhizzer();
                    isa_showMainScreen();
                    //isa_loadHeader('location');
                }
                else
                {
                    isaLogging_doConsoleLog('Supplier - PHP Error');
                    isa_doHideWhizzer();
                    navigator.notification.alert(
                        "Sorry, I couldn't find that item",
                        false,
                        "ischoolaudit",
                        "OK");
                }
            },
            error:       function(){

                isaLogging_doConsoleLog('Item - TX Error');
                isa_doHideWhizzer();
                isaErrors_transmissionError();
            }
        });
    }
</script>
<img class="logo" src="img/ischool-logo.png" width="200" />
    
    <ul class="profile">
		<?php if($this->session->userdata('objAppUser')->photoid == '') { ?>
        <li class="picture" style="background:url('https://www.iworkaudit.com/iwa/uploads/blank_avatar_male.jpg');");"></li>
        <?php } else { ?>
<li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $this->session->userdata('objAppUser')->photoid; ?>');"></li>
        <?php } ?>
        <li class="clearfix"><h2>Hi, <?php echo $this->session->userdata('objAppUser')->firstname; ?></h2><p><?php echo $this->session->userdata('objAppUser')->accountname; ?><br/><?php echo $this->session->userdata('objAppUser')->levelname;?></p></li>
    </ul>
    
    <?php if ($intTotalItemsOnAccount > 0)
    {
        ?>
    
    <ul>
        <li class="header">Manage Items</li>
        <li class="arrow"><a href="#" onclick="isaItem_showLookUpForm();"><img src="img/icon-search.png" width="29" class="ico"> Look up item</a></li>
        <?php if ($this->session->userdata('objAppUser')->levelid > 1) {?>
        <li class="arrow"><a href="#" onclick="isaPat_showLookUpForm();"><img src="img/icon-pat.png" width="29" class="ico"> PAT Results</a></li>
        <li class="arrow" id="add-item-link"><a href="#" onclick="isaAddItem_showForm();"><img src="img/icon-add.png" width="29" class="ico"> Add An Item</a></li>
        <?php } ?>
    </ul>
    <?php if ($this->session->userdata('objAppUser')->levelid > 1) {?>
    <ul>
        <li class="header">Manage Locations</li>
        <li class="arrow"><a href="#" onclick="isaLocation_showLookUpForm();"><img src="img/icon-search.png" width="29" class="ico"> Look up location</a></li>
    </ul>
    <?php } ?>

        <?php if ($this->session->userdata('objAppUser')->levelid > 1) {?>
        <ul>
            <li class="header">Manage Suppliers</li>
            <li class="arrow"><a href="#" onclick="isaSuppliers_showList();"><img src="img/icon-search.png" width="29" class="ico"> Supplier List</a></li>
        </ul>
    <?php } ?>


    <?php
    } else {
    ?>
    <ul><li>Your account has no items added yet.  Add at least one item using the website before using the App.</li></ul>
    <?php
    }
    ?>
    <?php if($this->session->userdata('objAppUser')->fleet == 1) { ?>
    <ul>
        <li class="header">Manage Vehicles</li>
        <li class="arrow"><a href="#" onclick="isaVehicle_showLookUpForm();"><img src="img/icon-search.png" width="29" class="ico">Look up vehicle</a></li>
        <li class="arrow"><a href="#" onclick="isaVehicle_doScannerChecks();"><img src="img/icon-vehicle.png" width="29" class="ico">Check vehicle</a></li>
    </ul>
    <?php } ?>
    <ul>
        <li class="header">Application Settings</li>
        <li class="arrow"><a href="#" onclick="isaLogin_doLogoutAndForgetMe();"><img src="img/icon-problem.png" width="29" class="ico">Log Out &amp; Forget Me</a></li>
    </ul>
    <p style="text-align:right;">Version 3.1</p>
    