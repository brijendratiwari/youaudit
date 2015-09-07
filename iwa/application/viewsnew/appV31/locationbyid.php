<ul class="data">
    <li><p>Toggle items to confirm they are present at this location.</p></li>
</ul>
<input type="hidden" name="locationdata_location_id" id="locationdata_location_id" value="<?php echo $arrLocation['locationid']; ?>" />
<input type="hidden" name="locationdata_location_name" id="locationdata_location_name" value="<?php echo $arrLocation['locationname']; ?>" />
<ul id="location_data_contents_holder">
<?php

foreach ($arrLocation['arrItems'] as $objItem)
{
?>
    <li id="location_audit_item_<?php echo $objItem->itemid; ?>" style="background: #EF8686 url('img/icon-cross-shim.png') center right no-repeat">
        <a style="padding-right:35px;" onclick="isaLocation_doToggleAsPresent('<?php echo $objItem->itemid; ?>');"><?php 
        
        echo $objItem->itembarcode.": ".$objItem->manufacturer." ".$objItem->model; ?></a></li><?php                
}
?>
</ul>
            
            
<p><a href="#" class="green button" onclick="isaLocation_doAudit();">Complete Audit</a></p>