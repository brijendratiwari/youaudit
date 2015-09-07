<h3>General Information</h3>    
<div class="form_row">
	<label for="category_id">Category*</label>	
	<select name="category_id">
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
	<?php echo form_error('category_id'); ?>
    </div>
    <div class="form_row">
	<label for="item_make">Make*</label> 
	<input type="input" name="item_make" value="<?php echo $strMake; ?>" />
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
	<?php echo form_error('item_barcode'); ?>
    </div>
    <div class="form_row">
	<label for="item_value">Value (&pound;)</label> 
	<input type="input" name="item_value" value="<?php echo $strValue; ?>" />
	<?php echo form_error('item_value'); ?>
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
	<label for="item_notes">Notes</label> 
        <textarea name="item_notes"><?php echo $strNotes; ?></textarea>
        <span class="explanation">Each new line will be displayed as bullet points.</span>
    </div>

<h3>Item Dates</h3>

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
<h3>PAT Information</h3>
    <div class="form_row">
	<label for="item_pattestdate">PAT Date</label> 
	<input type="input" name="item_pattestdate" value="<?php echo $strPatTestDate; ?>" class="datepicker" />
	<?php echo form_error('item_pattestdate'); ?>
    </div>
    <div class="form_row">
	<label for="item_patteststatus">PAT Status</label> 
        <select name="item_patteststatus">
            <option value="" <?php if ($intPatTestStatus == "") { echo "checked=\"checked\""; } ?>>N/A</option>
            <option value="1" <?php if ($intPatTestStatus == "1") { echo "checked=\"checked\""; } ?>>Pass</option>
            <option value="0" <?php if ($intPatTestStatus == "0") { echo "checked=\"checked\""; } ?>>Fail</option>
        </select>
    </div>
    
<h3>Item ownership</h3>
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
	<label for="faculty_id">Faculty</label>
	<select name="faculty_id">
	    <option value="0">Not Set</option>
	    <?php
		foreach ($arrFaculties['results'] as $arrFaculty)
		{
		    echo "<option ";
		    echo 'value="'.$arrFaculty->facultyid.'" ';
		    if ($intFacultyId == $arrFaculty->facultyid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrFaculty->facultyname."</option>\r\n";
		}
	    ?>
	</select>
    </div>

   <?php
   if ($booDisplayPhotoForm)
   {
   ?>
        <h3>Item photo</h3>
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
    <h3>Item photo</h3>
    <div class="form_row">
            <label for="photo_name">Picture</label>
            <img src="<?php echo site_url('/images/viewhero/'.$intPhotoId); ?>" />
            <input type="hidden" value="<?php echo $intPhotoId; ?>" name="item_photo_id" />
    </div>
   <?php
       }
   }
   ?>
    
    <div class="form_row">
	<label for="submit">All done?</label>
	<input class="button" type="submit" name="submit" value="Save" />
    </div>

    </form>