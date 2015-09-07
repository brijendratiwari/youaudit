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
	<label for="nickname">Name to use</label> 
	<input type="input" name="nickname" value="<?php echo $strNickName; ?>" />
        <span class="explanation">If left blank, first name will be used.</span>
    </div>
<h3>Profile Picture</h3>
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

}                                           ?>Profile Picture</label>
            <input type="file" name="photo_file" size="20" class="upload"/>
            <span class="explanation">File must be less than 1 Mb and ideally square.</span>
        </div>
        
        <div class="form_field">
            <label for="photo_name">Picture Title</label>
            <input type="input" name="photo_name" value="" />
        </div>
        
    </div>
<?php
if (!$booSuppressPasswordChange)
{
?>
<h3>Password</h3>
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
	<input type="password" name="newpassword1" value="" />
	<?php echo form_error('newpassword1'); ?>
    </div>
    <div class="form_row">
	<label for="newpassword2">New Password (again)*</label> 
	<input type="password" name="newpassword2" value="" />
        <?php echo form_error('newpassword2'); ?>
    </div>
<?php
}
?>
 <h3>Finished?</h3>
    <div class="form_row">
	<label for="submit">All done?</label>   
	<input class="button" type="submit" name="submit" value="Save" /> 
    </div>
    </form>