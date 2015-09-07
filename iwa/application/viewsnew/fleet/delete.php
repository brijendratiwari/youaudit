    <div class="heading">
        <h1>Remove <?php echo $objItem['make']." ".$objItem['model'].""; ?>?</h1>
        <div class="buttons">
                <a class="button icon-with-text round" onclick="$('#delete_item_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
    </div>    
    <div class="box_content">
        <div class="content_main">
            <p>Use this form to mark a vehicle as removed from the system.</p>
            <p><?php 
            if ($booSuperAdmin)
            {
                
            ?>The vehicle, <strong><?php echo $objItem['make']." ".$objItem['model']; ?></strong>, will be marked as awaiting another Admin's approval.  Once another Admin approves deleting the vehicle, it will be marked as removed from the inventory.<?php
            }
            else 
            {
            ?>The vehicle, <strong><?php echo $objItem['make']." ".$objItem['model']; ?></strong>, will be marked as awaiting SuperAdmin approval.  Once a SuperAdmin approves deleting the item, it will be marked as removed from the inventory.<?php
            }
            ?></p>
            <?php echo form_open('fleet/markdeleted/'. $objItem['fleet_id'] .'/', array('id'=>'delete_item_form')); ?>

            