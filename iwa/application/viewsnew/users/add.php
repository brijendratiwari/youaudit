<div class="box">
    <div class="heading">
      	<h1>Add a User</h1>
        <div class="buttons">
            <a class="button" onclick="$('#add_user_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        
        <div class="tabs">
          <a href="#general_information">General Information</a>
          <a href="#user_photos">User Photo</a>
          <!--<a href="#upload_file">Upload File</a>-->
          <?php
            if (!$booSuppressPasswordChange)
            {
            ?>
          
          
          
                <a href="#user_password">User Password</a>
          <?php
            } ?>
          
        </div>
        <div class="content_main">
            <p>Use this form to add user to the account</p>
            <?php echo form_open_multipart('users/add/', array('id' => 'add_user_form')); ?>    
<div id="general_information" class="form_block">
            
      
    <div class="form_row">
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
    </div>
    <div class="form_row">
        <label for="username">Username/Email*<span class="form_help">Username must be unique.</span></label> 
	<input type="input" name="username" value="<?php echo $strUserName; ?>" />
        
	<?php echo form_error('username'); ?>
    </div>
    <div class="form_row">
    <script>
$(document).ready(function() {

	$('#password').keyup(function(){
		$('#passwordstrengthresult').html(checkStrength($('#password').val()))
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
        
	<label for="password">Password*</label>
	<input type="password" name="password" id="password" /> <span id="passwordstrengthresult"></span>
	<?php echo form_error('password'); ?>
    </div>
    