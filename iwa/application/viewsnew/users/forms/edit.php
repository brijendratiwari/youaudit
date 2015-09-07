 
<div class="form_row">
    <label for="firstname">First Name*</label> 
    <input type="text" class="form-control text_width" name="firstname" value="<?php echo $strFirstName; ?>" />
    <?php echo form_error('firstname'); ?>

</div>
<div class="form_row">
    <label for="lastname">Last Name*</label> 
    <input type="text" class="form-control text_width" name="lastname" value="<?php echo $strLastName; ?>" />
    <?php echo form_error('lastname'); ?>
</div>

<div class="form_row">
    <label for="nickname">Name to use
        <span class="form_help">If left blank, first name will be used.</span>
    </label> 
    <input type="text" class="form-control text_width" name="nickname" value="<?php echo $strNickName; ?>" />

</div>
</div>
<div id="user_photos" class="form_block">

    <?php
    if ($intPhotoId != -1) {
        ?>
        <div class="form_row">
            <div class="form_field">
            
            <label for="photo_name">Current Profile Picture</label>
            <?php
            if ($intPhotoId > 1) {
                ?>
      
            <?
                echo "<img src=\"" . base_url('/images/viewhero/' . $intPhotoId) . "\" title=\"" . $strPhotoTitle . "\" />";
            } else {
                echo "Not set";
            }
            ?>    
        </div>
        </div>
            <?php
        }
        ?>
    <div class="form_row">
        <div class="form_field">
            <label for="photo_file"><?php
        if ($intPhotoId != -1) {
            ?>Update <?php }
        ?>Profile Picture <span class="form_help">
                    File must be less than 1 Mb and ideally square.
                </span></label>
            <input type="file" class="item_photo upload" name="photo_file"/>
            
        </div>
    </div>
    <div class="form_row"> 
        <div class="form_field">
            <label for="photo_name">Picture Title</label>
            <input type="text" class="form-control text_width" name="photo_name" value="" />
        </div>

    </div>
</div>


<?php
if (!$booSuppressPasswordChange) {
    ?>



    <div id="user_password" class="form_block">
        <script>
            $(document).ready(function() {

                $('#newpassword1').keyup(function() {
                    $('#passwordstrengthresult').html(checkStrength($('#newpassword1').val()))
                })

                function checkStrength(password) {

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
                    if (password.length > 7)
                        strength += 1

                    //if password contains both lower and uppercase characters, increase strength value
                    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
                        strength += 1

                    //if it has numbers and characters, increase strength value
                    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))
                        strength += 1

                    //if it has one special character, increase strength value
                    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))
                        strength += 1

                    //if it has two special characters, increase strength value
                    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/))
                        strength += 1

                    //now we have calculated strength value, we can return messages

                    //if value is less than 2
                    if (strength < 2) {
                        $('#passwordstrengthresult').removeClass()
                        $('#passwordstrengthresult').addClass('weak')
                        return 'Weak'
                    } else if (strength == 2) {
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
    if (!$booSuppressCurrentPassword) {
        ?>
            <span class="explanation">Leave these blank if you don't intend to change your password.</span>
            <div class="form_row">

                <label for="password">Current Password*</label> 
                <input class="form-control text_width" type="password" name="password" value="" />
            <?php echo form_error('password'); ?>
            </div>
        <?php
    }
    ?>
        <div class="form_row">
            <label for="newpassword1">New Password*</label> 
            <input class="form-control text_width" type="password" name="newpassword1" id="newpassword1"/> <span id="passwordstrengthresult"></span>
        <?php echo form_error('newpassword1'); ?>
        </div>
        <div class="form_row">
            <label for="newpassword2">New Password (again)*</label> 
            <input class="form-control text_width" type="password" name="newpassword2" value="" />
    <?php echo form_error('newpassword2'); ?>
        </div>
    </div>
    <?php
}
?>

</form>
<!--<div id="upload_file">
<?php 
if($_SERVER['PATH_INFO'] == '/users/add'){
echo form_open_multipart('users/upload_userfile/', array('id' => 'user_file')); ?>    
       <div class="form_row">
              <label for="upload_file">Example Excel File:</label>
     <a href="<?php echo site_url('/users/downloadExcelFomatFile'); ?>">Demo Excel File &nbsp;<?php  echo "<img src=\"" . base_url('/brochure/img/excel.jpg') . "\"  />";  ?></a>
       </div>
    <div class="form_row"> 
        <div class="form_field">
            <label for="upload_file">Upload File</label>
            <input type="file" name="file" size="20" class="upload" />
        </div>
    </div> 
    <div class="form_field">
            <input type="submit" value="Upload" class="button" />
    </div>
</form>
<?php       }    ?>
</div>-->
</div>
</div>
</div>
<style>
    .text_width{
        width:20%;
    }
</style>