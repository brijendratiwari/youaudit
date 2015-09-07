    <h2>Add a User</h2>

    <?php echo form_open_multipart('users/add/'); ?>
    
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
    <div class="form_row">
	<label for="username">Username/Email*</label> 
	<input type="input" name="username" value="<?php echo $strUserName; ?>" />
        <span class="explanation">Username must be unique.</span>
	<?php echo form_error('username'); ?>
    </div>
    <div class="form_row">
	<label for="password">Password*</label>
	<input type="password" name="password" />
	<?php echo form_error('password'); ?>
    </div>