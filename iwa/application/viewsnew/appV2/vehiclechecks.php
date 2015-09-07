<ul class="data">
    <li><p>Click on each check to change status of the result.</p></li>
</ul>
<input type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo $vehicle_id; ?>" />
<ul id="location_data_contents_holder">
<?php

foreach ($checks as $check) {

?>
    <li id="location_audit_item_<?php echo $check->id; ?>" style="background: #EF8686 url('img/icon-cross-shim.png') center right no-repeat">
        <a id="<?php echo $check->id; ?>" style="padding-right:35px;" class="failed" onclick="isaVehicle_doToggleAsPresent('<?php echo $check->id; ?>');"><?php
        echo $check->check_name.": ".$check->check_short_description; ?></a></li>
        <li id="linenotes_<?php echo $check->id; ?>">Please enter note: <input id="notes_<?php echo $check->id; ?>" style="height: 30px; font-size: 15px; width: 96%;"/></li>
<?php
}
?>
</ul>
            
            
<p><a href="#" class="green button" onclick="isaVehicle_doChecks();">Complete Audit</a></p>