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

            <ul class="profile">
                <li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $objItem->itemphotoid; ?>') no-repeat center center;"></li>
                <li class="clearfix" id="item_text_holder"><h2><?php echo $objItem->manufacturer." ".$objItem->model; ?></h2><p><?php echo $objItem->categoryname; ?></p></li>
            </ul>
            
            <ul>
                <li>At least one of these must be selected</li>
            </ul>
            
            <ul class="form">
                <li class="header">Vehicle Ownership</li>
                <li class="arrow">
                    <label for="ownership_site_id">Site</label>
                    <select name="ownership_site_id" id="ownership_site_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrSites'], "Site", "site", $objItem['site_now']);
                        ?>
                    </select>
                </li>
                <li class="arrow">
                    <label for="ownership_user_id">User</label>
                    <select name="ownership_user_id"  id="ownership_user_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrUsers'], "User", "user", $objItem['owner_now']);
                        ?>
                    </select>
                </li>
                
                <li class="arrow">
                    <label for="ownership_location_id">Location</label>
                    <select name="ownership_location_id" id="ownership_location_id">
                        <?php
                            echo doBuildPullDown($arrPulldowns['arrLocations'], "Location", "location", $objItem['location_now']);
                        ?>
                    </select>
                </li>
            </ul>
            
            <p><a href="#" class="green button" onclick="isaVehicle_doChangeOwnership(<?php echo $objItem['fleet_id']; ?>);">Save</a></p>