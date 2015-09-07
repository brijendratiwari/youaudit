    <h2>Edit System Admin</h2>

    <?php echo form_open('admins/edit/'.$intAdminId.'/'); ?>

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
	
	<input type="hidden" name="photo_id" value="1" />
	
	<input type="submit" name="submit" value="Update SysAdmin" /> 

    </form>