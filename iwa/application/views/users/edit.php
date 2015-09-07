    <h2>Edit User</h2>

    
    <?php echo form_open_multipart('users/edit/'.$intUserId.'/'); ?>
    
    <div class="form_row">
	<label for="level_id">Level*</label>	
	<select name="level_id">
	    <option value="0">Select</option>
	    <?php
		foreach ($arrLevels['results'] as $arrLevel)
		{
		    echo "<option ";
		    echo 'value="'.$arrLevel->levelid.'" ';
		    if ($intLevelId == $arrLevel->levelid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrLevel->levelname."</option>\r\n";
		}
	    ?>
	</select>
	<?php echo form_error('level_id'); ?>
    </div>