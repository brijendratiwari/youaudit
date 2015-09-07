    <div class="heading">
        <h1>Edit System Admin</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_user').submit();">Save</a>
        </div>
    </div>    

    <?php echo form_open('admins/edit/'.$intAdminId.'/', array('id'=>'edit_user')); ?>

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
        
        
        <label for="username">Username/Email*</label> 
	<input type="input" name="username" value="<?php echo $strUserName; ?>" />
	<?php echo form_error('username'); ?>
	<br />

	<label for="password">Password*</label>
	<input type="input" name="password" />
	<?php echo form_error('password'); ?>
	

    </form>