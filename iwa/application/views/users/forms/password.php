    <div class="form_row">
        <label for="password">Current Password*</label> 
	<input type="password" name="password" value="" />
	<?php echo form_error('password'); ?>
    </div>
    <div class="form_row">
	<label for="newpassword1">New Password*</label> 
	<input type="password" name="newpassword1" value="" />
	<?php echo form_error('newpassword1'); ?>
    </div>
    <div class="form_row">
	<label for="newpassword2">New Password (again)*</label> 
	<input type="password" name="newpassword2" value="" />
        <?php echo form_error('newpassword2'); ?>
    </div>
    <div class="form_row">
        <label for="submit">All Done?</label>
	<input class="button" type="submit" name="submit" value="Change" /> 
    </div>
    </form>