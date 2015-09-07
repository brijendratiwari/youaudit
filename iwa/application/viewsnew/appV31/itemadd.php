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
            <ul class="form">
                <li class="header">Item</li>
                <li class="information">You can select an existing Manufacturer, or type in a new one.</li>
                <li class="arrow">
                    <label for="manufacturer_id">Manufacturer</label>
                    <select name="manufacturer_id" id="manufacturer_id" onChange="isaAddItem_updateManufacturer();">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrManufacturers'], "Manufacturer", "manufacturer", -1);
                        ?>
                    </select>
                </li>
                <li><input type="text" name="item_make" value="" placeholder="Manufacturer *" /></li>
                <li><input type="text" name="item_model" value="" placeholder="Model *" /></li>
            </ul>   
            <ul class="form">
                <li class="header">Item Identifiers</li>
                <li><input type="text" name="item_serial_number" value="" placeholder="Serial Number" /></li>
                <li class="arrow"><a href="#" onclick="isaScanner_doFormScanner('item_serial_number');"><img src="img/icon-scan.png" width="29" class="ico"> Serial Number Scan</a></li>
                <li>
                    <input type="text" name="item_barcode" value="" placeholder="Barcode*" />
                    
                </li>
                <li class="arrow"><a href="#" onclick="isaScanner_doFormScanner('item_barcode');"><img src="img/icon-scan.png" width="29" class="ico"> QR Scan</a></li>
            </ul>

            <ul class="form">
                <li class="header">Further Details</li>
                <li class="arrow">
                    <label for="item_category_id">Category*</label>
                    <select name="item_category_id" id="item_category_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrCategories'], "Category", "category", -1);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="item_status_id">Status*</label>
                    <select name="item_status_id" id="item_status_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrItemStatuses'], "Status", "status", 1);
                        ?>
                    </select>
                </li>
                
                <li>
                    <label for="item_notes">Notes</label>
                    <textarea name="item_notes" placeholder="Notes"></textarea>
                </li>
                <li><input type="text" name="item_value" value="" placeholder="Value (<?php echo $currency; ?>)" /></li>
            </ul>
            
            <ul class="form">
                <li class="header">Item Image</li>
                <li class="arrow"><a href="#" onclick="isaCamera_replacePhoto();"><img src="img/icon-camera.png" width="29" class="ico"> Take Photo</a></li>
                <li>
                    <input type="hidden" value="" name="photo_item_image" />
                    <input type="hidden" name="photo_photo_present" value="false" />
                    <img id="photo_smallImage" src="" />
                </li>
            </ul>

            <ul class="form">
                <li class="header">Item Ownership</li>
                <li class="information">At least one of the following must be selected</li>
           
                
                <li class="arrow">
                    <label for="ownership_site_id">Site</label>
                    <select name="ownership_site_id" id="ownership_site_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrSites'], "Site", "site", -1);
                        ?>
                    </select>
                </li>
                <li class="arrow">
                    <label for="ownership_user_id">User</label>
                    <select name="ownership_user_id"  id="ownership_user_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrUsers'], "User", "user", -1);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="ownership_location_id">Location</label>
                    <select name="ownership_location_id" id="ownership_location_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrLocations'], "Location", "location", -1);
                        ?>
                    </select>
                </li>
            </ul>
            


            <ul class="form">
                <li class="header">Dates</li>
                <li>
                    <label for="item_purchased">Purchase Date</label>
                    <input type="date" name="item_purchased" value="" max="" />
                </li>
                
                <li>
                    <label for="item_warranty">Warranty Expiry Date</label>
                    <input type="date" name="item_warranty" value="" />
                </li>
            </ul>
            
            <ul class="form">
                <li class="header">PAT</li>
                <li><input type="checkbox" name="item_patrequired" value="true" title="PAT Required" checked="checked" /></li>
            </ul>

            
            <ul class="form">
                <li class="header">Finish</li>
                <li><input type="checkbox" name="repeat_add" value="true" title="Add another similar item?" checked="checked" /></li>
            </ul>





            <p><a href="#" class="green button" onclick="isaAddItem_doProcess();">Save</a></p>