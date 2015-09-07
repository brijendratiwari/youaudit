    <h2>System Pin-Number</h2>
    
    <?php echo validation_errors(); ?>
    <form action="<?php echo base_url()."admins/pinCheck/$account_type";?>" method="POST" id="pin_form" >
    
   
    <div class="form_row">
	<label for="password">Your Pin Number</label>
        <input class="form-control text_width " placeholder="Enter Pin Number" type="password" name="pin_number" id="pin_number" />
       <input type="hidden" name="username" value="<?php echo $this->session->userdata('AdminUserName');?>">
    </div>
         
    <div class="form_row">
    
        <button class="btn btn-success btn-md" type="submit" name="submit" >Continue</button>
    </div>
        <div class="form_row">
         
    </div>
    </form>
<script>

    $(document).ready(function () {
        $("#pin_form").validate({
            rules: {
                pin_number: {
                    required: true,
                    digits: true,
                    rangelength: [6, 6],
                },
               
            },
            messages: {
               
                pin_number:{
                    required: "Please provide a pin number",
                    digits: "Please Enter Only Digits",
                    rangelength: "Please Enter Only 6 Digits"
                },
                
            }
        });


    });


</script>
<style>
    .text_width{
        width:20%;
    }
</style>

