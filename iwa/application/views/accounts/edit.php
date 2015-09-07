    <h2>Edit Account</h2>
    <p>Use this form to update your account details.</p>

    <?php echo form_open('account/edit/'); ?>
    <h3>General Information</h3>
     <div class="form_row">
        <label for="account_name">Name*</label> 
	<input type="input" name="account_name" value="<?php echo $strAccountName; ?>" />
	<?php echo form_error('account_name'); ?>
     </div>
      <div class="form_row">
	<label for="account_address">Address*</label> 
	<input type="input" name="account_address" value="<?php echo $strAccountAddress; ?>" />
	<?php echo form_error('account_address'); ?>
      </div>
     <div class="form_row">
	<label for="account_city">City*</label> 
	<input type="input" name="account_city" value="<?php echo $strAccountCity; ?>" />
        <?php echo form_error('account_city'); ?>
     </div>
      <div class="form_row">
        <label for="account_county">County</label> 
	<input type="input" name="account_county" value="<?php echo $strAccountCounty; ?>" />
      </div>
     <div class="form_row">
        <label for="account_postcode">Post Code*</label> 
	<input type="input" name="account_postcode" value="<?php echo $strAccountPostCode; ?>" />
        <?php echo form_error('account_postcode'); ?>
     </div>
    <div class="form_row">
        <label for="account_country">Country</label> 
	<input type="input" name="account_country" value="<?php echo $strAccountCountry; ?>" />
    </div>
    <h3>Account Verification</h3>
    <div class="form_row">
        <label for="account_securityquestion">Security Question*</label> 
	<input type="input" name="account_securityquestion" value="<?php echo $strAccountSecurityQuestion; ?>" />
        <?php echo form_error('account_securityquestion'); ?>
    </div>
    <div class="form_row">
        <label for="account_securityanswer">Security Answer*</label> 
	<input type="input" name="account_securityanswer" value="<?php echo $strAccountSecurityAnswer; ?>" />
        <?php echo form_error('account_securityanswer'); ?>
    </div>
    <h3>Contact Details</h3>
     <div class="form_row">
        <label for="account_contactname">Contact Name*</label> 
	<input type="input" name="account_contactname" value="<?php echo $strAccountContactName; ?>" />
	<?php echo form_error('account_name'); ?>
     </div>
     <div class="form_row">
	<label for="account_contactemail">Contact Email Address*</label> 
	<input type="input" name="account_contactemail" value="<?php echo $strAccountContactEmail; ?>" />
	<?php echo form_error('account_contactemail'); ?>
     </div>
     <div class="form_row">
	<label for="account_contactnumber">Contact Number*</label> 
	<input type="input" name="account_contactnumber" value="<?php echo $strAccountContactNumber; ?>" />
        <?php echo form_error('account_contactnumber'); ?>
     </div>
     <div class="form_row">
        <label for="submit">All done?</label>
        <input class="button" type="submit" name="submit" value="Update Account" /> 
     </div>
    </form>