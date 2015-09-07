 
    <div class="form_row">
	<label for="firstname">First Name*</label> 
	<input type="input" name="firstname" value="<?php echo $strFirstName; ?>" />
	<?php echo form_error('firstname'); ?>
	
    </div>
    <div class="form_row">
	<label for="lastname">Last Name*</label> 
	<input type="input" name="lastname" value="<?php echo $strLastName; ?>" />
	<?php echo form_error('lastname'); ?>
    </div>
	
    <div class="form_row">
	<label for="nickname">Name to use
        <span class="form_help">If left blank, first name will be used.</span>
        </label> 
	<input type="input" name="nickname" value="<?php echo $strNickName; ?>" />
        
    </div>
 </div>
<div id="user_photos" class="form_block">

<?php if ($intPhotoId != -1)
{
?>
    <div class="form_row">
        <span class="form-label">Current Profile Picture</span>
       
            <?php if ($intPhotoId > 1)
                {
                    echo "<img src=\"".site_url('/images/viewlist/'.$intPhotoId)."\" title=\"".$strPhotoTitle."\" />";
                }
                else
                {
                    echo "Not set";
                }    
                
                ?>    
           </div>
<?php
}
?>
    <div class="form_row">
        <div class="form_field">
            <label for="photo_file"><?php 
if ($intPhotoId != -1)
{
                                    ?>Update <?php 

                                    }                                           ?>Profile Picture <span class="form_help">
                                        File must be less than 1 Mb and ideally square.
                                    </span></label>
            <input type="file" name="photo_file" size="20" class="upload"/>
        </div>
    </div>
       <div class="form_row"> 
        <div class="form_field">
            <label for="photo_name">Picture Title</label>
            <input type="input" name="photo_name" value="" />
        </div>
  
    </div>
</div>


<?php
if (!$booSuppressPasswordChange)
{
?>


 
<div id="user_password" class="form_block">
<script>
$(document).ready(function() {

	$('#newpassword1').keyup(function(){
		$('#passwordstrengthresult').html(checkStrength($('#newpassword1').val()))
	})	
	
	function checkStrength(password){
    
	//initial strength
    var strength = 0
	
    //if the password length is less than 6, return message.
    if (password.length < 6) { 
		$('#passwordstrengthresult').removeClass()
		$('#passwordstrengthresult').addClass('short')
		return 'Too short' 
	}
    
    //length is ok, lets continue.
	
	//if length is 8 characters or more, increase strength value
	if (password.length > 7) strength += 1
	
	//if password contains both lower and uppercase characters, increase strength value
	if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1
	
	//if it has numbers and characters, increase strength value
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1 
	
	//if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1
	
	//if it has two special characters, increase strength value
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
	
	//now we have calculated strength value, we can return messages
	
	//if value is less than 2
	if (strength < 2 ) {
		$('#passwordstrengthresult').removeClass()
		$('#passwordstrengthresult').addClass('weak')
		return 'Weak'			
	} else if (strength == 2 ) {
		$('#passwordstrengthresult').removeClass()
		$('#passwordstrengthresult').addClass('good')
		return 'Good'		
	} else {
		$('#passwordstrengthresult').removeClass()
		$('#passwordstrengthresult').addClass('strong')
		return 'Strong'
	}
}
});
</script>
    <?php
    if (!$booSuppressCurrentPassword)
    {
    ?>
<span class="explanation">Leave these blank if you don't intend to change your password.</span>
    <div class="form_row">
        
        <label for="password">Current Password*</label> 
	<input type="password" name="password" value="" />
	<?php echo form_error('password'); ?>
    </div>
    <?php
    }
    ?>
    <div class="form_row">
	<label for="newpassword1">New Password*</label> 
	<input type="password" name="newpassword1" id="newpassword1"/> <span id="passwordstrengthresult"></span>
	<?php echo form_error('newpassword1'); ?>
    </div>
    <div class="form_row">
	<label for="newpassword2">New Password (again)*</label> 
	<input type="password" name="newpassword2" value="" />
        <?php echo form_error('newpassword2'); ?>
    </div>
</div>
<?php
}
?>

    </form>
    
</div>
</div>
</div>