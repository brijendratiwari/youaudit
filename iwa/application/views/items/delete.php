    <h2>Delete Item <?php echo "(".$objItem->barcode.") ".$objItem->manufacturer." ".$objItem->model; ?>?</h2>
    <p>Use this form to process deleting an item.</p>
    <p><?php 
    if ($booSuperAdmin)
    {
    ?>The item, <strong><?php echo "(".$objItem->barcode.") ".$objItem->manufacturer." ".$objItem->model; ?></strong>, is already marked by another Admin on the account and so will be removed from the inventory.<?php
    }
    else 
    {
    ?>The item, <strong><?php echo "(".$objItem->barcode.") ".$objItem->manufacturer." ".$objItem->model; ?></strong>, will be marked as awaiting SuperAdmin approval.  Once a SuperAdmin approves deleting the item, it will be removed from the inventory.<?php
    }
    ?></p>
    <?php echo form_open('items/markdeleted/'.$intItemId.'/'); ?>