    <div class="heading">
        <h1>Remove <?php echo $objItem->barcode." (".$objItem->manufacturer." ".$objItem->model.")"; ?>?</h1>
        <div class="buttons">
                <a style="display: block;" class="button update icon-with-text round" onclick="$('#delete_item_form').submit();"><i class="fa fa-fw">&#xf0ab;</i>Save</a>
        </div>
        
        
    </div>    
    <div class="box_content">
        <div class="content_main">
            <p>Use this form to mark an item as removed from the system.</p>
            <p><?php 
            if ($booSuperAdmin)
            {
            ?>The item, <strong><?php echo $objItem->barcode." (".$objItem->manufacturer." ".$objItem->model.")"; ?></strong>, will be marked as awaiting another Admin's approval.  Once another Admin approves deleting the item, it will be marked as removed from the inventory.<?php
            }
            else 
            {
            ?>The item, <strong><?php echo $objItem->barcode." (".$objItem->manufacturer." ".$objItem->model.")"; ?></strong>, will be marked as awaiting SuperAdmin approval.  Once a SuperAdmin approves deleting the item, it will be marked as removed from the inventory.<?php
            }
            ?></p>
            <?php echo form_open('items/markdeleted/'.$intItemId.'/', array('id'=>'delete_item_form')); ?>
            <div class="form_row col-md-6">
                <div class="col-md-3"><label for="itemstatus">Reason</label></div>
                <div class="col-md-3"><select name="itemstatus" class="form-control">
                    <option value="-1">Select</option>
                    <?php
                    
                    
                    foreach ($arrItemStatuses['results'] as $arrStatus)
                    {
                        echo "<option value=\"".$arrStatus->statusid."\">".$arrStatus->statusname."</option>\r\n";
                    }
                    ?>
                </select></div>
            </div>
         