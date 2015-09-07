    <h2>Change links</h2>
    <p>Use this form to change an item's owner and/or location.</p>
    <p>The item, <?php
        echo "<strong>".$objItem->manufacturer." ".$objItem->model."</strong> (".$objItem->barcode.")"; ?>, is presently
        recorded as being owned by <?php echo $objItem->userfirstname." ".$objItem->userlastname; ?> and stored in
        <?php echo $objItem->locationname; ?></p>
    <?php echo form_open('items/changelinks/'.$objItem->itemid.'/'); ?>