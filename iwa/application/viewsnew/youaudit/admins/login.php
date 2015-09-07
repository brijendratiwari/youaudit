<?php
if ($this->session->flashdata('arrCourier')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('arrCourier'); ?>
    </div>
    <?php
}
if ($this->session->flashdata('success')) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php
}
?>
<h2>YouAudit System Log-in</h2>

<?php echo validation_errors(); ?>

   <form action="<?php echo base_url().'youaudit/login' ?>" id="loginform" method="POST">
<div class="form_row">
    <label for="username">Username/EMail</label> 
    <input class="form-control text_width" type="text" name="username" />
</div>
<div class="form_row">
    <label for="password">Your Password</label>
    <input class="form-control text_width" type="password" name="password" />
</div>


<div class="form_row">
    <label for="submit">Log-in?</label>
    <button class="btn btn-primary" type="submit"  name="submit" value="Login">Login</button> 
</div>
</form>


<style>
    .text_width{
        width:20%;
    }
</style>
   <script>

    $(document).ready(function () {
     $("#loginform").validate({
            rules: {
                password: "required",
                username: "required",
               
            },
              messages:{
                username: "Please Enter Valid Username ",
                password: "Please Enter Valid Password",
               
            }
        });


    });


</script>
