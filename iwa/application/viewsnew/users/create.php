    <h2>Create User</h2>

    <?php echo validation_errors(); ?>

    <?php echo form_open('users/createone/'); ?>

	<label for="firstname">First Name*</label> 
	<input type="input" name="firstname" value="" /><br />
	
	<label for="firstname">Last Name*</label> 
	<input type="input" name="lastname" value="" /><br />
	
	<label for="nickname">Name to use</label> 
	<input type="input" name="nickname" value="" /><br />
	
	<label for="username">Username/Email*</label> 
	<input type="input" name="username" value="" /><br />

	<label for="password">Password*</label>
	<input type="input" name="password" /><br />
	
	<label for="level">Level*</label>	
	<select name="level">
	    <?php
		foreach ($arrLevels['results'] as $arrLevel)
		{
		    echo "<option ";
		    echo 'value="'.$arrLevel->levelid.'">'.$arrLevel->levelname."</option>\r\n";
		}
	    ?>
	</select>
	
	<input type="hidden" name="account_id" value="1" />
	<input type="hidden" name="photo_id" value="1" />
	
	<input type="submit" name="submit" value="Create User" /> 

    </form>