    <h2>Change User Credentials</h2>

    <?php echo form_open('admins/changecredentialsuser/'.$intUserId.'/'); ?>

	<label for="username">Username/Email*</label> 
	<input type="input" name="username" value="<?php echo $strUserName; ?>" />
	<?php echo form_error('username'); ?>
	<br />

	<label for="password">Password*</label>
	<input type="input" name="password" />
	<?php echo form_error('password'); ?>
	<br />
	
	<input type="submit" name="submit" value="Update Credentials" /> 

    </form>