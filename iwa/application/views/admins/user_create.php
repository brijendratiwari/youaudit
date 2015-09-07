    <h2>Create User</h2>

    <?php echo form_open('admins/createuser/'); ?>
    
    <label for="account_id">Account*</label>	
	<select name="account_id">
	    <option value="0">Select</option>
	    <?php
		foreach ($arrAccounts['results'] as $arrAccount)
		{
		    echo "<option ";
		    echo 'value="'.$arrAccount->accountid.'" ';
		    if ($intAccountId == $arrAccount->accountid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrAccount->accountname."</option>\r\n";
		}
	    ?>
	</select>
	<?php echo form_error('account_id'); ?>
	<br />
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
	
	<label for="username">Username/Email*</label> 
	<input type="input" name="username" value="<?php echo $strUserName; ?>" />
	<?php echo form_error('username'); ?>
	<br />

	<label for="password">Password*</label>
	<input type="input" name="password" />
	<?php echo form_error('password'); ?>
	<br />
    
	<input type="hidden" name="photo_id" value="1" />
	
	<input type="submit" name="submit" value="Create User" /> 

    </form>