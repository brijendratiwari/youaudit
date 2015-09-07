<?php echo form_open('users/login/'); ?>
<div class="form_row">
	<label for="username">Username/eMail</label> 
	<input type="input" name="username" />
        <?php echo form_error('username'); ?>
</div>
<div class="form_row">
	<label for="password">Your Password</label>
	<input type="password" name="password" />
        <?php echo form_error('password'); ?>
</div>
<div class="form_row">
        <label for="submit">Log-in?</label>
	<input class="button" type="submit" name="submit" value="Go" /> 
</div>
</form>