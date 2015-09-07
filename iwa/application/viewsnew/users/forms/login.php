<?php if($arrErrorMessages) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $arrErrorMessages[0]; ?>
    </div>
    <?php
}
?>
<form action="<?php echo base_url() . 'users/login/'; ?>" method="POST" id="loginform">
<div class="form_row">
	<label for="username">Username/eMail</label> 
	<input type="text" class="form-control text_width" name="username" />
        <?php echo form_error('username'); ?>
</div>
<div class="form_row">
	<label for="password">Your Password</label>
	<input type="password" class="form-control text_width" name="password" />
        <?php echo form_error('password'); ?>
</div>
<div class="form_row">
        <label for="submit">Log-in?</label>
        <button class="btn btn-primary" type="submit" name="submit" value="Go">Login</button> 
</div>
</form>
<script>
$(document).ready(function () {
        $("#loginform").validate({
            rules: {
                password: "required",
                username: "required",
                account_type: "required",
            },
            messages: {
                username: "Please Enter Valid Username ",
                password: "Please Enter Valid Password",
                account_type: "Please Select Account Type"
            }
        });


    });


</script>
<style>
    .text_width{
        width:20%;
    }
</style>