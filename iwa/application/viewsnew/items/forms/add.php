<script type="text/javascript">
    /* When Category is checked, see if category is a quantity category */
    $(function() {
        $('#category_id').change(function() {
            
           var url =   $('#base_url').val();
           var linkforcategory = url + "/iwa/categories/checkCategory/" + $('#category_id').val();
        /* Quantity category check */
            $.getJSON(linkforcategory, function (data) {
                if(data.quantity == 1) {
                    /*                    $('#item_quantity').append('<label for="item_quantity">Item Quantity</label>' +
                     '<input type="input" name="item_quantity"/>'
                     ).show();*/
                    $('#item_quantity').show();
                } else {
                    $('#quantity').val('')
                    $('#item_quantity').hide();
                }
            });

            /* Custom Fields call */
            $.getJSON(url+ "/iwa/categories/getCustomFields/" + $('#category_id').val(), function (data) {
                $('#custom_fields').empty();
                for (var i=0;i<data.length;i++) {
                    $('#custom_fields').append('<div class="form_row">' +
                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label>' +
                        '<input type="input" id="' + data[i].id + '" name="' + data[i].id + '"' +
                        '</div>');
                }
            });
        });
    });
</script>
<style>
    h4 {
        background: none repeat scroll 0 0 #e9e9e9;
        padding: 5px;
    }
</style>
<div id="general_information" class="form_block">
    <h4>General Information</h4>
    <div class="form_row">
	<label for="category_id">Category*</label>
    <select id="category_id" name="category_id">
	    <option value="0">Select</option>
	    <?php
		foreach ($arrCategories['results'] as $arrCategory)
		{
		    echo "<option ";
		    echo 'value="'.$arrCategory->categoryid.'" ';
		    if ($intCategoryId == $arrCategory->categoryid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrCategory->categoryname."</option>\r\n";
		}
	    ?>
	</select>
        <input type="hidden" id="base_url" name="base_url" value="<?= base_url(); ?>" />
	<?php echo form_error('category_id'); ?>
    </div>

    <div id="item_quantity" class="form_row" <?=($quantity_enabled ? '' : 'style="display: none;"')?>>
        <label for="item_quantity">Item Quantity</label>
        <input type="input" id="quantity" name="item_quantity" value="<?php echo $intQuantity; ?>" />
    </div>

    <div class="form_row">
	<label for="item_make">Make*</label> 
	<input type="input" name="item_make" value="<?php echo $strMake; ?>" />
        <input type="hidden" name="item_id" value="<?php echo $intitemId; ?>" />
	<?php echo form_error('item_make'); ?>
    </div>
    <div class="form_row">
	<label for="item_model">Model*</label> 
	<input type="input" name="item_model" value="<?php echo $strModel; ?>" />
	<?php echo form_error('item_model'); ?>
    </div>
    <div class="form_row">
	<label for="item_serial_number">Serial Number</label> 
	<input type="input" name="item_serial_number" value="<?php echo $strSerialNumber; ?>" />
    </div>
    <div class="form_row">
	<label for="item_barcode">Barcode*</label> 
	<input type="input" name="item_barcode" value="<?php echo $strBarcode; ?>" />
	<?php echo $barcodeMessages; ?>
    </div>
    <div class="form_row">
	<label for="item_value">Purchase Value (<?php echo $currency; ?>)</label> 
	<input type="input" name="item_value" value="<?php echo $strValue; ?>" />
	<?php echo form_error('item_value'); ?>
    </div>
    <div class="form_row">
	<label for="item_current_value">Current Value (<?php echo $currency; ?>)
            <span class="form_help">Leave blank for same as Purchase Value.</span></label> 
	<input type="input" name="item_current_value" value="<?php echo $strCurrentValue; ?>" />
	<?php echo form_error('item_current_value'); ?>
    </div>    
    <div class="form_row">
	<label for="status_id">Status*</label>	
	<select name="status_id">
	    <?php
		foreach ($arrItemStatuses['results'] as $arrStatus)
		{
		    echo "<option ";
		    echo 'value="'.$arrStatus->statusid.'" ';
		    if ($intItemStatusId == $arrStatus->statusid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrStatus->statusname."</option>\r\n";
		}
	    ?>
	</select>
	<?php echo form_error('status_id'); ?>
    </div>

    <div class="form_row">
        <label for="supplier_id">Supplier</label>
        <select name="supplier">
            <option value="">Please Select</option>
            <?php
            foreach ($arrSuppliers as $supplier)
            {
                echo "<option ";
                echo 'value="'.$supplier['supplier_id'].'" ';
                if ($supplier_id == $supplier['supplier_id'])
                {
                    echo 'selected="selected" ';
                }
                echo '>'.$supplier['supplier_title']."</option>\r\n";
            }
            ?>
        </select>
        <?php echo form_error('status_id'); ?>
    </div>

    <div class="form_row">
	<label for="item_notes">Notes
        <span class="form_help">Each new line will be displayed as bullet points.</span></label>
        <textarea name="item_notes"><?php echo $strNotes; ?></textarea>
        
    </div>
    <div class="form_row">
        <label for="item_notes">Document
        <span class="form_help">Should be in "pdf" format.</span></label>
            <input type="file" name="pdf_file" class="fileupload">
    </div>

    <div id="custom_fields">
    <?php
    if($arrPageParameters['strPage'] == 'Edit an Item') {
        if(count($arrCustomFields) > 0) {
            foreach($arrCustomFields as $custom_field) { ?>
                <div class="form_row">
                    <label for="<?=$custom_field->id?>"><?=$custom_field->field_name?></label>
                    <input type="text" name="<?=$custom_field->id?>" <?=(isset($custom_field->content) ? 'value="' . $custom_field->content . '"' : '')?>>
                </div>
            <?php } }
    }?>
    </div>
</div>

<div id="item_dates" class="form_block">
<br/><h4>Item Dates</h4>
    <script>
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>
    <div class="form_row">
	<label for="item_purchased">Purchase Date</label> 
	<input type="input" name="item_purchased" value="<?php echo $strPurchased; ?>" class="datepicker" />
	<?php echo form_error('item_purchased'); ?>
    </div>

    <div class="form_row">
	<label for="item_warranty">Warranty Expires</label> 
	<input type="input" name="item_warranty" value="<?php echo $strWarranty; ?>" class="datepicker" />
	<?php echo form_error('item_warranty'); ?>
    </div>
    
    <div class="form_row">
	<label for="item_replace">Replacement Date</label> 
	<input type="input" name="item_replace" value="<?php echo $strReplace; ?>"  class="datepicker" />
        <?php echo form_error('item_replace'); ?>
    </div>

    <div class="form_row">
	<label for="item_pattestdate">PAT Date</label> 
	<input type="input" name="item_pattestdate" value="<?php echo $strPatTestDate; ?>" class="datepicker" />
	<?php echo form_error('item_pattestdate'); ?>
    </div>
    <div class="form_row">
	<label for="item_patteststatus">PAT Status</label> 
        <select name="item_patteststatus">
            <option value="-1" <?php if ($intPatTestStatus === null) { echo "selected=\"selected\""; } ?>>Unknown</option>
            <option value="1" <?php if ($intPatTestStatus === "1") { echo "selected=\"selected\""; } ?>>Pass</option>
            <option value="0" <?php if ($intPatTestStatus === "0") { echo "selected=\"selected\""; } ?>>Fail</option>
            <option value="5" <?php if ($intPatTestStatus === "5") { echo "selected=\"selected\""; } ?>>Not Required</option>
        </select>
        <?php echo form_error('item_patteststatus'); ?>
    </div>

<!--    <div class="form_row">
        <label for="compliance_start">Compliance Check Start Date</label>
        <input type="input" name="compliance_start" value="<?php echo $strComplianceStart; ?>"  class="datepicker" />
        <?php echo form_error('compliance_start'); ?>
    </div>-->
</div>    
<div id="item_ownership" class="form_block">
    <h4>Item Ownership</h4>
    <div class="form_row">
        <p>At least one of the following must be selected.</p>
    </div>
    <div class="form_row">
	<label for="user_id">Owner</label>	
	<select name="user_id">
	    <option value="0">Not Set</option>
	    <?php
		
		
		foreach ($arrUsers['results'] as $arrUser)
		{
		    echo "<option ";
		    echo 'value="'.$arrUser->userid.'" ';
		    if ($intUserId == $arrUser->userid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrUser->userfirstname." ".$arrUser->userlastname."</option>\r\n";
		}
	    ?>
	</select>
        <?php echo form_error('user_id'); ?>
    </div>
    <div class="form_row">
	<label for="location_id">Location</label>	
	<select name="location_id">
	    <option value="0">Not Set</option>
	    <?php
		foreach ($arrLocations['results'] as $arrLocation)
		{
		    echo "<option ";
		    echo 'value="'.$arrLocation->locationid.'" ';
		    if ($intLocationId == $arrLocation->locationid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrLocation->locationname."</option>\r\n";
		}
	    ?>
	</select>
	<?php echo form_error('location_id'); ?>
    </div>
    <div class="form_row">
	<label for="site_id">Site</label>
	<select name="site_id">
	    <option value="0">Not Set</option>
	    <?php
		foreach ($arrSites['results'] as $arrSite)
		{
		    echo "<option ";
		    echo 'value="'.$arrSite->siteid.'" ';
		    if ($intSiteId == $arrSite->siteid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrSite->sitename."</option>\r\n";
		}
	    ?>
	</select>
        <?php echo form_error('site_id'); ?>
    </div>
</div>
<div id="item_photo" class="form_block">
    <h4>Item Photo</h4>
   <?php
   if ($booDisplayPhotoForm)
   {
   ?>
        
        <div class="form_row">
            <div class="form_field">
                <label for="photo_file">Item Picture</label>
                <input type="file" name="photo_file" size="20" class="upload"/>
            </div>

            <div class="form_field">
                <label for="photo_name">Picture Title</label>
                <input type="input" name="photo_name" value="" />
            </div>
        </div>
   <?php
   }
   else
   {
       if ($intPhotoId > 1)
       {
   ?>
    
    <div class="form_row">
            <label for="photo_name">Picture</label>
            <img src="<?php echo site_url('/images/viewhero/'.$intPhotoId); ?>" />
            <input type="hidden" value="<?php echo $intPhotoId; ?>" name="item_photo_id" />
    </div>
   <?php
       }
   }
   ?>
</div>   
    

    </form>
   <ul><?php
foreach ($pdf_number as $list) {
    ?><li>
                                                    <div  class="pdf_upload">
                                                        <a href='<?php echo site_url('/items/pdf_download/' . $list['s3_key']); ?>'><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/brochure/images/pdf.png'; ?>"   title='pdf' ></a>
                                                        <label for="nickname"><?php echo $list['file_name']; ?>    </label> 

                                                <?php if ($arrSessionData['objSystemUser']->levelid > 2) { ?>
                                                            <a class="delete" href='<?php echo site_url('/items/pdf_delete/' . $list['s3_key']); ?>'>
                                                                <img alt="Delete"  title="Delete" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/img/icons/16/erase.png'; ?>">
                                                            </a>

                                                        <?php } ?>
                                                    </div>
                                                </li>
    <?php
}
?></ul> 
</div>
</div>
</div>