
<?php
if ($this->session->flashdata('arrCourier')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('arrCourier'); ?>
    </div>
    <?php
}
?>
<h2>System Log-in</h2>

<?php echo validation_errors(); ?>
<form action="<?php echo base_url() . 'admins/login/'; ?>" method="POST" id="loginform">
    <div class="form_row">
        <label for="username">Username/eMail</label> 
        <input class="form-control text_width" type="text" name="username" />
    </div>
    <div class="form_row">
        <label for="password">Your Password</label>
        <input class="form-control text_width" type="password" name="password" />
    </div>
<!--    <div class="form_row">
        <label for="type">Select Account Type</label>
        <select name="account_type" class="form-control text_width" id="account_type">
            <option value="">------SELECT-----</option>
            <option value="1">Master Account</option>
            <option value="2">Franchise Account</option>
        </select>
    </div>-->
    <div class="form_row">
        <label for="submit">Log-in?</label>
        <button class="btn btn-warning btn-md" type="submit" value="submit" name="submit" >  Log-in &nbsp; <i class="glyphicon  glyphicon-play-circle"></i></button> 
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