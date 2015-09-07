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
    <li class="arrow"><a href="#" onclick="isaLocation_doScanner();"><img src="img/icon-scan.png" width="29" class="ico"> Scan</a></li>
    <li><input type="text" name="lookuplocation-barcode" id="lookuplocation-barcode" placeholder="QR Code Entry" /></li>
</ul>

<ul class="form">
    <li class="header">Search For Location</li>

    <li class="arrow">
        <label for="lookupitem-location_id">Location</label>
        <select name="lookupitem-location_id" id="lookupitem-location_id">
            <?php
                echo doBuildPullDown($arrPulldowns['arrLocations'], "Location", "location", -1);
            ?>
        </select>
    </li>
</ul>