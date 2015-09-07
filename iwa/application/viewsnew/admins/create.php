<div class="box">
    <div class="heading"><h1>Create System Admin</h1></div>
    <div class="form_block">
    <?php echo form_open('admins/create/'); ?>
        <div class="form_row">
            <label for="firstname">First Name*</label> 
            <input type="input" name="firstname" value="<?php echo $strFirstName; ?>" />
            <?php echo form_error('firstname'); ?>
            <br />
        </div>
	
	<div class="form_row">
            <label for="lastname">Last Name*</label> 
            <input type="input" name="lastname" value="<?php echo $strLastName; ?>" />
            <?php echo form_error('lastname'); ?>
            <br />
        </div>
	
	<div class="form_row">
            <label for="nickname">Name to use</label> 
            <input type="input" name="nickname" value="<?php echo $strNickName; ?>" /><br />
        </div>
        
        <div class="form_row">
            <label for="username">Username/Email*</label> 
            <input type="input" name="username" value="<?php echo $strUserName; ?>" />
            <?php echo form_error('username'); ?>
            <br />
        </div>

	<div class="form_row">
            <label for="password">Password*</label>
            <input type="input" name="password" />
            <?php echo form_error('password'); ?>
            <br />
        </div>
	
	<div class="form_row">
            <input type="hidden" name="photo_id" value="1" />

            <input type="submit" name="submit" value="Create SysAdmin" /> 
        </div>
    </form>
</div>