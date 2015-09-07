    <div class="heading">
        <h1>Edit User</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_user').submit();">Save</a>
        </div>
    </div>    

    <?php echo form_open('admins/edituser/'.$intUserId.'/', array('id'=>'edit_user')); ?>

	<label for="firstname">First Name*</label> 
	<input type="input" name="firstname" value="<?php echo $strFirstName; ?>" />
	<?php echo form_error('firstname'); ?>
	<br />
	
	<label for="lastname">Last Name*</label> 
	<input type="input" name="lastname" value="<?php echo $strLastName; ?>" />
	<?php echo form_error('lastname'); ?>
	<br />
	
	<label for="nickname">Name to use</label> 
	<input type="input" name="nickname" value="<?php echo $strNickName; ?>" /><br />
        
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
	<br />
	
	<input type="hidden" name="photo_id" value="1" />
        
        <input type="hidden" name="account_id" value="<?php echo $intAccountId; ?>" />
	
	<input type="submit" name="submit" value="Update User" /> 

    </form>