	<h2>Find Items</h2>
	<p>Use this form to find items</p>
	<?php echo form_open('items/find/'); ?>
	
	<div class="form_row">
	<label for="category_id">Category</label>	
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
	<label for="faculty_id">Faculty</label>	
	<select name="faculty_id">
	    <option value="-1">N/A</option>
            <option value="0">No Faculty</option>
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
	<?php echo form_error('faculty_id'); ?>
	</div>
	<div class="form_row">
	<label for="user_id">Owner</label>	
	<select name="user_id">
	    <option value="0">Select</option>
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
	</div>
	<div class="form_row">
	<label for="location_id">Location</label>	
	<select name="location_id">
	    <option value="0">Select</option>
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
	<label for="manufacturer">Manufacturer</label>	
	<select name="manufacturer">
	    <option value="-1">Select</option>
	    <?php
		foreach ($arrManufacturers as $arrManufacturer)
		{
		    echo "<option ";
		    echo 'value="'.$arrManufacturer.'" ';
		    if ($intLocationId == $arrLocation->locationid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrManufacturer."</option>\r\n";
		}
	    ?>
	</select>
	
	</div>
	
	<div class="form_row">
	    <label for="submit">Ready?</label>
	    <input class="button" type="submit" name="submit" value="Find Items" /> 
	</div>
    </form>