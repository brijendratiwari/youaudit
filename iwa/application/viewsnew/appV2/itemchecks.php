<ul class="data">
    <li><p>Click on each compliance check to change status of the result.</p></li>
</ul>

<input type="hidden" name="item_id" id="item_id" value="<?php echo $item_id; ?>" />
<ul id="location_data_contents_holder">
<?php

foreach ($checks as $check) {

?>
    <li id="location_audit_item_<?php echo $check->test_id; ?>" style="background: #EF8686 url('img/icon-cross-shim.png') center right no-repeat">
        <a id="<?php echo $check->test_id; ?>" style="padding-right:35px;" class="failed" onclick="isaItem_doCheckToggleAsPresent('<?php echo $check->test_id; ?>');"><?php
        echo $check->test_name.": ".$check->test_description; ?></a></li>
        <li id="linenotes_<?php echo $check->test_id; ?>">Please enter note: <input id="notes_<?php echo $check->test_id; ?>" style="width: 400px;"/></li>
<?php
}
?>
</ul>
            
            
<p><a href="#" class="green button" onclick="isaItem_doChecks();">Complete Audit</a></p>