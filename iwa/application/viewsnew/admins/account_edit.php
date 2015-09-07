<div class="box">
    <div class="heading">
        <h1>Edit Account</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_account').submit();">Save</a>
        </div>
            
    </div>
    <div class="tabs">
        <a href="#general_information" class="active">General Information</a>
        <a href="#account_verification" class="active">Account Verification</a>
        <a href="#contact_details" class="active">Contact Details</a>
        <a href="#account_settings" class="active">Account Settings</a>
    </div>
    
    
    <?php echo form_open('admins/editaccount/'.$intAccountId.'/', array('id'=>'edit_account')); ?>
    <div id="general_information" class="form_block">
    <h3>General Information</h3>
    <div class="form_row">
	<label for="account_name">Name*</label> 
	<input type="input" name="account_name" value="<?php echo $strAccountName; ?>" />
	<?php echo form_error('account_name'); ?>
	<br />
    </div>
	
    <div class="form_row">
	<label for="account_address">Address*</label> 
	<input type="input" name="account_address" value="<?php echo $strAccountAddress; ?>" />
	<?php echo form_error('account_address'); ?>
	<br />
    </div>
	
    <div class="form_row">
	<label for="account_city">City*</label> 
	<input type="input" name="account_city" value="<?php echo $strAccountCity; ?>" />
        <?php echo form_error('account_city'); ?><br />
    </div>
        
    <div class="form_row">
        <label for="account_county">County</label> 
	<input type="input" name="account_county" value="<?php echo $strAccountCounty; ?>" />
        <br />
    </div>
    
    <div class="form_row">     
        <label for="account_postcode">Post Code*</label> 
	<input type="input" name="account_postcode" value="<?php echo $strAccountPostCode; ?>" />
        <?php echo form_error('account_postcode'); ?><br />
    </div>
    
    <div class="form_row">
        <label for="account_country">Country</label> 
	<input type="input" name="account_country" value="<?php echo $strAccountCountry; ?>" />
    </div> 
  
    </div>
    
    <div id="account_verification" class="form_block" style="display: none;">
    <h3>Account Verification</h3>
    <div class="form_row">
        <label for="account_securityquestion">Security Question*</label> 
	<input type="input" name="account_securityquestion" value="<?php echo $strAccountSecurityQuestion; ?>" />
        <?php echo form_error('account_securityquestion'); ?><br />
    </div>
    
    <div class="form_row">
        <label for="account_securityanswer">Security Answer*</label> 
	<input type="input" name="account_securityanswer" value="<?php echo $strAccountSecurityAnswer; ?>" />
        <?php echo form_error('account_securityanswer'); ?><br />
    </div>
        
        
    </div>
    <div id="contact_details" class="form_block" style="display: none;">
    <h3>Contact Details</h3> 
    <div class="form_row">
        <label for="account_contactname">Contact Name*</label> 
	<input type="input" name="account_contactname" value="<?php echo $strAccountContactName; ?>" />
	<?php echo form_error('account_name'); ?>
	<br />
    </div>
	
    <div class="form_row">
	<label for="account_contactemail">Contact Email Address*</label> 
	<input type="input" name="account_contactemail" value="<?php echo $strAccountContactEmail; ?>" />
	<?php echo form_error('account_contactemail'); ?>
	<br />
    </div>
	
    <div class="form_row">
	<label for="account_contactnumber">Contact Number*</label> 
	<input type="input" name="account_contactnumber" value="<?php echo $strAccountContactNumber; ?>" />
        <?php echo form_error('account_contactnumber'); ?><br />
    </div>
        
    
    </div>
    <div id="account_settings" class="form_block" style="display: none;">
    <h3>Account Settings</h3>
    <div class="form_row">
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
    </div>
    <div class="form_row">
        <label for="fleet_module">Fleet Module</label>	
        <input type="checkbox" name="fleet_module" value="1" <?php print ($intAccountFleet == 1) ? "checked=\"checked\"" : ""; ?>/>
	<?php echo form_error('fleet_module'); ?>
	<br />
    </div>
    <div class="form_row">
        <label for="compliance_module">Compliance Module</label>	
        <input type="checkbox" name="compliance_module" value="1" <?php print ($intAccountCompliance == 1) ? "checked=\"checked\"" : ""; ?>/>
	<?php echo form_error('compliance_module'); ?>
	<br />
    </div>
    <div class="form_row">
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
    </div>
    <div class="form_row">
	<label for="account_supportaddress">Support Email</label> 
	<input type="input" name="account_supportaddress" value="<?php echo $strAccountSupportAddress; ?>" />
        <?php echo form_error('account_supportaddress'); ?><br />
    </div>

            <div class="form_row">
                <label for="account_fleetcontact">Fleet Contact Name</label>
                <input type="input" name="account_fleetcontact" value="<?php echo $strAccountFleetContact; ?>" />
                <?php echo form_error('account_fleetcontact'); ?>
            </div>

            <div class="form_row">
                <label for="account_fleetemail">Fleet Email Address</label>
                <input type="input" name="account_fleetemail" value="<?php echo $strAccountFleetEmail; ?>" />
                <?php echo form_error('account_fleetemail'); ?>
            </div>


            <div class="form_row">
                <label for="account_compliancecontact">Compliance Contact Name</label>
                <input type="input" name="account_compliancecontact" value="<?php echo $strAccountComplianceContact; ?>" />
                <?php echo form_error('account_compliancecontact'); ?>
            </div>

            <div class="form_row">
                <label for="account_complianceemail">Compliance Email Address</label>
                <input type="input" name="account_complianceemail" value="<?php echo $strAccountComplianceEmail; ?>" />
                <?php echo form_error('account_complianceemail'); ?>
            </div>

    </div>

    </div>


    </form>
</div>