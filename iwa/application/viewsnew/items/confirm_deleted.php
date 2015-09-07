<div class="box">
    <div class="heading">
      	<h1>Confirm Item Removals</h1>
        <div class="buttons">
            <a class="button" onclick="$('#item_removals').submit();">Confirm</a>
        </div>
    </div>
    
    <div class="box_content">

        <div class="content_main">
            
            <?php echo form_open('items/confirmdeleted/', array('id'=>'item_removals')); ?>

            <table class="list_table">
                <thead>
                    <tr>
                        
                        <th class="left">
                            Barcode
                        </th>
                        <th class="left">
                            Manufacturer and Model
                        </th>
                        <th class="left">
                            Category
                        </th>
                        <th class="left">
                            Reason
                        </th>
                        <th class="left">
                            Removed by
                        </th>
                        <th class="left">
                            Removal Date
                        </th>
                        <th class="right">
                            Check to confirm
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                <?php
                foreach($arrItemsAwaitingDeletion as $objPendingItem)
                {
                ?>
                    <tr>
                        
                        <td><?php echo $objPendingItem->barcode; ?></td>
                        <td><a href="<?php 
                                echo site_url('/items/view/'.$objPendingItem->itemid.'/'); 
                                    ?>"><?php 
                                        echo $objPendingItem->manufacturer." ".$objPendingItem->model; 
                                            ?></a></td>
                        <td><?php echo $objPendingItem->categoryname; ?></td>
                        <td><?php echo $objPendingItem->statusname; ?></td>
                        <td><?php echo $objPendingItem->userfirstname." ".$objPendingItem->userlastname; ?></td>
                        <td><?php if ($objPendingItem->level_id == 4) 
                                    {
                                        echo date('d/m/Y (H:i:s)', strtotime($objPendingItem->mark_deleted_2_date));
                                    }
                                  else
                                    {
                                        echo date('d/m/Y (H:i:s)', strtotime($objPendingItem->mark_deleted_date));
                                    }
                        
                            ?></td>
                        <td class="right"><input type="checkbox" name="confirmed_deletions[]" value="<?php echo $objPendingItem->itemid; ?>" /></td>
                    </tr>
                <?php
                }
                ?>
                
                </tbody>
            </table>
            
            
        </div>
    </div>
</div>
        