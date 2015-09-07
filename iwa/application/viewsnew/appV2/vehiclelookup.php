<script>
    function isaVehicle_doSearch()
    {

        if ($("#lookupvehicle-barcode").val() != "")
        {

            isaVehicle_getVehicle($("#lookupvehicle-barcode").val());
        }
        else
        {

            var selected_make = -1;
            var selected_make_id = $('#lookupvehicle-make').val();
            for(var i=0;i<makes_obj.length;i++) {
                if(makes_obj[i].id == selected_make_id)
                    selected_make = makes_obj[i].name;
            }

            if (($('select[name="lookupvehicle-user_id"]').val() != -1)
                || ($('select[name="lookupvehicle-site_id"]').val() != -1)
                || ($('select[name="lookupvehicle-location_id"]').val() != -1)
                || ($('select[name="lookupvehicle-make"]').val() != -1)
                || ($('select[name="lookupvehicle-reg_no"]').val() != -1))
            {

                /* searching... */
                isa_doShowWhizzer();
                //make = $('#lookupvehicle-make);
                objData = {
                    username: strGlobalUsername,
                    password: strGlobalPassword,
                    mode: "search",
                    user_id: $('select[name="lookupvehicle-user_id"]').val(),
                    site_id: $('select[name="lookupvehicle-site_id"]').val(),
                    location_id: $('select[name="lookupvehicle-location_id"]').val(),
                    //make: $('select[name="lookupvehicle-make"]').val(),
                    make: selected_make,
                    reg_no: $('input[name="lookupvehicle-reg_no"]').val(),
                    timestamp: Math.round(new Date().getTime() / 1000) //to make the query unique
                };

                isaLogging_doConsoleLog(strWebServiceURI + "vehiclelookup");
                isaLogging_doConsoleLog(objData);
                $.ajax({
                    type:        'POST',
                    url:         strWebServiceURI + "vehiclelookup",
                    dataType:    'json',
                    data:        objData,
                    timeout:     intShortTimeout,
                    success:     function(data)
                    {
                        if (data.booError == false)
                        {
                            isaLogging_doConsoleLog('isaVehicle_showLookUpForm() - gotData');
                            strSearchResultHtml = data.strHtml;
                            strSearchResultHeader = data.strHeader;
                            $("#window").html(data.strHtml);
                            $("#header").html(data.strHeader);
                            isa_doHideWhizzer();
                            isa_showMainScreen();
                        }
                        else
                        {
                            isaLogging_doConsoleLog('Item - PHP Error');
                            isa_doHideWhizzer();
                            navigator.notification.alert(
                                "Sorry, I couldn't access the information",
                                false,
                                strServiceName,
                                "OK");
                        }
                    },
                    error:       function(e)
                    {
                        isaLogging_doConsoleLog(e);
                        isaLogging_doConsoleLog('Item - TX Error');
                        isa_doHideWhizzer();
                        navigator.notification.alert(
                            "There was a problem communicating to the Internet",
                            false,
                            strServiceName,
                            "OK");
                    }
                });
            }
        }
    }
</script>

<?php
    function doBuildPullDown($arrData, $strFieldName, $strFieldReference, $intSelected, $booRequired = false)
    {

        $strOutput = "<option value='-1'";
        if ($intSelected == -1)
        {
            $strOutput .= " selected='selected'";
        }
        $strOutput .= ">Select ".$strFieldName."</option>";
        if ($arrData)
        {
            foreach ($arrData as $objDataItem)
            {
                $strOutput .= "<option ";
                if ($objDataItem->{$strFieldReference."id"} == $intSelected)
                {
                    $strOutput .= "selected='selected' ";
                }
                $strOutput .= "value='".$objDataItem->{$strFieldReference."id"}."'>";
                $strOutput .= $objDataItem->{$strFieldReference."name"};
                $strOutput .= "</option>";
            }
        }
        return $strOutput;
    }
?>
            
            
            <ul>
                <li class="header">QR Code</li>
                <li class="arrow"><a href="#" onclick="isaVehicle_doScanner();"><img src="img/icon-scan.png" width="29" class="ico"> Scan</a></li>
                <li><input type="text" name="lookupvehicle-barcode" id="lookupvehicle-barcode" placeholder="QR Code Entry" /></li>
            </ul>
            
            <ul class="form">
                <li class="header">Search For Vehicle</li>
                <li class="arrow">
                    <label for="lookupvehicle-make">Make</label>
                    <select name="lookupvehicle-make" id="lookupvehicle-make">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrMakes'], "Make", "make", -1);
                        ?>
                    </select>
                    <script type="text/javascript">
                        <?php
                        $makes_obj = array();
                        foreach($arrPulldowns['arrMakes'] as $make)

                            $makes_obj[] = array(
                                'id' => $make->makeid,
                                'name' => $make->makename
                            );
                        $makes_obj = json_encode($makes_obj);

                        ?>
                        var makes_obj = <?php echo $makes_obj ?>;
                    </script>
                </li>
                <li>
                    <label for="lookupvehicle-reg_no">Registration Number</label>
                    <input type="text" name="lookupvehicle-reg_no" id="lookupvehicle-reg_no" placeholder="Reg No"/>
                </li>
                <li class="arrow">
                    <label for="lookupitem-site_id">Site</label>
                    <select name="lookupvehicle-site_id" id="lookupvehicle-site_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrSites'], "Site", "site", -1);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="lookupitem-user_id">User</label>
                    <select name="lookupvehicle-user_id"  id="lookupvehicle-user_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrUsers'], "User", "user", -1);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="lookupitem-location_id">Location</label>
                    <select name="lookupvehicle-location_id" id="lookupvehicle-location_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrLocations'], "Location", "location", -1);
                        ?>
                    </select>
                </li>
            </ul>