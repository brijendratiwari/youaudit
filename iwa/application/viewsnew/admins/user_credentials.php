<div class="heading"><h1>Change Credentials for <?php echo $strFirstName; ?> <?php echo $strLastName; ?>, <?php echo $strAccountName; ?></h1>
    <div class="buttons">
        <a class="button" onclick="$('#edit_user').submit();">Save</a>
    </div>
</div>    

<div class="box_content">
   <?php echo form_open('admins/changecredentialsuser/'.$intUserId.'/', array('id'=>'edit_user')); ?>
    <div class="form_block">
        <div class="form_row">
            <label for="username">Username/Email*</label> 
            <input type="input" name="username" value="<?php echo $strUserName; ?>" />
            <?php echo form_error('username'); ?>
	</div>
        <div class="form_row">
	<label for="password">Password*</label>
	<input type="input" name="password" />
	<?php echo form_error('password'); ?>
	</div>
    </div>
    </form>
</div>