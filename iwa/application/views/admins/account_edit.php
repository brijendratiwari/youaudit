    <h2>Edit Account</h2>

    <?php echo form_open('admins/editaccount/'.$intAccountId.'/'); ?>
    <h3>General Information</h3>
	<label for="account_name">Name*</label> 
	<input type="input" name="account_name" value="<?php echo $strAccountName; ?>" />
	<?php echo form_error('account_name'); ?>
	<br />
	
	<label for="account_address">Address*</label> 
	<input type="input" name="account_address" value="<?php echo $strAccountAddress; ?>" />
	<?php echo form_error('account_address'); ?>
	<br />
	
	<label for="account_city">City*</label> 
	<input type="input" name="account_city" value="<?php echo $strAccountCity; ?>" />
        <?php echo form_error('account_city'); ?><br />
        
        <label for="account_county">County</label> 
	<input type="input" name="account_county" value="<?php echo $strAccountCounty; ?>" />
        <br />
        
        <label for="account_postcode">Post Code*</label> 
	<input type="input" name="account_postcode" value="<?php echo $strAccountPostCode; ?>" />
        <?php echo form_error('account_postcode'); ?><br />
        
        <label for="account_country">Country</label> 
	<input type="input" name="account_country" value="<?php echo $strAccountCountry; ?>" />
        
        <hr />
    <h3>Account Verification</h3>   
        <label for="account_securityquestion">Security Question*</label> 
	<input type="input" name="account_securityquestion" value="<?php echo $strAccountSecurityQuestion; ?>" />
        <?php echo form_error('account_securityquestion'); ?><br />
        
        <label for="account_securityanswer">Security Answer*</label> 
	<input type="input" name="account_securityanswer" value="<?php echo $strAccountSecurityAnswer; ?>" />
        <?php echo form_error('account_securityanswer'); ?><br />
        
        <hr />
    <h3>Contact Details</h3> 
        <label for="account_contactname">Contact Name*</label> 
	<input type="input" name="account_contactname" value="<?php echo $strAccountContactName; ?>" />
	<?php echo form_error('account_name'); ?>
	<br />
	
	<label for="account_contactemail">Contact Email Address*</label> 
	<input type="input" name="account_contactemail" value="<?php echo $strAccountContactEmail; ?>" />
	<?php echo form_error('account_contactemail'); ?>
	<br />
	
	<label for="account_contactnumber">Contact Number*</label> 
	<input type="input" name="account_contactnumber" value="<?php echo $strAccountContactNumber; ?>" />
        <?php echo form_error('account_contactnumber'); ?><br />
        
        <hr />
    <h3>Account Settings</h3>
        <label for="account_packageid">Package*</label>	
	<select name="account_packageid">
	    <option value="0">Select</option>
	    <?php
		foreach ($arrPackages['results'] as $arrPackage)
		{
		    echo "<option ";
		    echo 'value="'.$arrPackage->packageid.'" ';
		    if ($intAccountPackageId == $arrPackage->packageid)
		    {
			echo 'selected="selected" ';
		    }
		    echo '>'.$arrPackage->packagename."</option>\r\n";
		}
	    ?>
	</select>
	<?php echo form_error('package_id'); ?>
	<br />
        <label for="account_verified">Verified*</label>	
	<select name="account_verified">
	    <option value="0" <?php
                    if ($intAccountVerified == 0)
		    {
			echo 'selected="selected" ';
		    }
                    ?>>No</option>
            <option value="1" <?php
                    if ($intAccountVerified == 1)
		    {
			echo 'selected="selected" ';
		    }
                    ?>>Yes</option>
        </select>
	<?php echo form_error('account_verified'); ?>
	<br />

	
	<input type="submit" name="submit" value="Update Account" /> 

    </form>