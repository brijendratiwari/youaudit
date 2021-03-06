<?php
    function doParseValue($strValue)
    {
        if (($strValue == null) || ($strValue == ""))
        {
            return "Not Available";
        }
        else
        {
            return $strValue;
        }
    }
    function doParseDate($strDate)
    {
        if (($strDate != NULL) && ($strDate != ""))
        {
            
            return date("d/m/Y", strtotime($strDate));
           
        }
        else
        {
            return 'Not Available';
        }
    }
?>

<ul class="profile">
    <li class="picture" style="background:url('https://www.iworkaudit.com/iwa/appversionthree/viewUserHero/<?php echo $arrVehicle['itemphotoid']; ?>') no-repeat center center;"></li>
    <li class="clearfix" id="item_text_holder"><h2><?php echo $arrVehicle['make']." ".$arrVehicle['model']; ?></h2><p><?php echo $arrVehicle['categoryname']; ?></p></li>
</ul>
<ul class="field" id="item_data_holder">
    <li class="header">Vehicle Overview</li>
    <li><h3>Barcode</h3><?php echo $arrVehicle['barcode']; ?></li>
    <li><h3>Registration No</h3><?php echo $arrVehicle['reg_no']; ?></li>
    <li><h3>Engine Size</h3><?php echo $arrVehicle['engine_size']; ?></li>
    <li><h3>Vehicle Value</h3><?php echo $arrVehicle['current_value']; ?></li>
    <li><h3>Current User</h3><?php echo $arrVehicle['firstname'] . ' ' . $arrVehicle['lastname'] ?></li>
    <li><h3>Current Site</h3><?php echo $arrVehicle['site'] ?></li>
    <li><h3>Current Location</h3><?php echo $arrVehicle['name'] ?></li>
    <li><h3>Next service due</h3><?php echo $arrVehicle['service_due_date'] ?></li>
    <li><h3>MOT Test Date</h3><?php echo $arrVehicle['mot_due_date'] ?></li>
</ul>
<ul class="field" id="item_data_notes_holder">
    <li class="header">Vehicle Notes</li>
    <?php
        if ($arrVehicle['notes'] == "")
        {
    ?>
    <li><em>No notes</em></li>
    <?php
        }
        else
        {
                ?><li><?php echo $arrVehicle['notes']; ?></li><?php
        }
    ?>
</ul>

<ul class="field" id="item_data_dates_holder">
    <li class="header">Vehicle Dates</li>
    <li><h3>Purchased</h3><?php echo doParseDate($arrVehicle['purchase_date']);?></li>
    <li><h3>Warranty</h3><?php echo doParseDate($arrVehicle['warranty_date']);?></li>
</ul>
<ul id="item_data_options_holder">
    <li class="header">Vehicle Options</li>
    <li class="arrow"><a href="#" onclick="isaVehicle_getVehicleChecks(<?php echo $arrVehicle['fleet_id']; ?>);"><img src="img/icon-bloke.png" width="29" class="ico">Complete Vehicle Checks</a></li>
    <li class="arrow"><a href="#" onclick="isaVehicle_showOwnershipForm(<?php echo $arrVehicle['itemid']; ?>);"><img src="img/icon-bloke.png" width="29" class="ico"> Change Vehicle Ownership</a></li>
    <li class="arrow"><a href="#" onclick="isaVehicle_showTicketForm(<?php echo $arrVehicle['itemid']; ?>);"><img src="img/icon-problem.png" width="29" class="ico"> Report a Problem</a></li>
</ul>