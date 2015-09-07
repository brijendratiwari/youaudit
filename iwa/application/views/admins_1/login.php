    <h2>System Log-in</h2>

    <?php echo validation_errors(); ?>

    <?php echo form_open('admins/login/'); ?>
    <div class="form_row">
	<label for="username">Username/eMail</label> 
	<input type="input" name="username" />
    </div>
    <div class="form_row">
	<label for="password">Your Password</label>
	<input type="password" name="password" />
    </div>
    <div class="form_row">
        <label for="submit">Log-in?</label>
	<input class="button" type="submit" name="submit" value="Go" /> 
    </div>
    </form>