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
                <li class="arrow"><a href="#" onclick="isaItem_doScanner();"><img src="img/icon-scan.png" width="29" class="ico"> Scan</a></li>
                <li><input type="text" name="lookupitem-barcode" id="lookupitem-barcode" placeholder="QR Code Entry" /></li>
            </ul>
            
            <ul class="form">
                <li class="header">Search For Item</li>
                <li class="arrow">
                    <label for="lookupitem-manufacturer">Manufacturer</label>
                    <select name="lookupitem-manufacturer" id="lookupitem-manufacturer">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrManufacturers'], "Manufacturer", "manufacturer", -1);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="lookupitem-site_id">Site</label>
                    <select name="lookupitem-site_id" id="lookupitem-site_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrSites'], "Site", "site", -1);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="lookupitem-user_id">User</label>
                    <select name="lookupitem-user_id"  id="lookupitem-user_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrUsers'], "User", "user", -1);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="lookupitem-location_id">Location</label>
                    <select name="lookupitem-location_id" id="lookupitem-location_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrLocations'], "Location", "location", -1);
                        ?>
                    </select>
                </li>
            </ul>