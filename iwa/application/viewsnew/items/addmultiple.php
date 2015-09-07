<div class="box">
    <div class="heading">
        <h1>Add Items</h1>
        <div class="buttons">
            <a class="" onclick="$('#add_item_form').submit();"><img src="<?php base_url("youaudit/iwa/img/ui-icons/save.png");?> /></a>
        </div>
    </div>

        
        
        <div class="box_content">
           
            
            <div data-class="tabs">
              <!--<a href="#general_information">General Information</a>-->
<!--              <a href="#item_dates">Item Dates</a>-->
              <!--<a href="#item_ownership">Item Ownership</a>-->
<!--              <a href="#item_photo">Item Photo</a>-->
            </div>
        <div class="content_main">
             <p style="float: left">Use this form to add items to your audit records.  Certain fields will be auto-filled from the first item if you click "Add Another".</p>
             <p style="float: right">* Indicates required field</p>
        <?php echo form_open_multipart('items/addmultiple/', array('id'=>'add_item_form')); ?>
    
        <div class="form_row">
            <label for="add_another">Add Another</label> 
            <input type="checkbox" name="add_another" <?php if ($booAddAnother) { echo "checked=\"checked\""; } ?> value="1" />
        </div>