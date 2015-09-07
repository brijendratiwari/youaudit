<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        /*height: 495px;*/
        overflow-y: scroll;
    } 
    
    .bootbox .modal-dialog{
        width: 300px;
    }
    
   .bootbox .modal-body{
       
        overflow: auto !important;
    }
</style>
<script>

    $(document).ready(function () {
        $("#systemaccount").validate({
            rules: {
                first_name:"required",
                last_name: "required",
                username:{
                    required: true,
                    email: true
                },
                user_password: "required",
                confirm_password: {
                    required: true,
                    equalTo: "#user_password"
                },
                 pin_number:  {
                    required: true,
                    digits: true,
                    rangelength: [6, 6],
                },
                
            },
            messages: {
                first_name:"First Name Required",
                last_name:"Last Name Required",
                username:{
                    required: "Please Enter Username",
                    email: "Enter Valid Emails"
                },
                  contact_password: {
                    required: "Please Enter Password",
                },
                confirm_password: {
                    required: "Please Enter Confirm Password",
                    equalTo: "Password Is Not Matching"
                },
                pin_number: {
                    required: "Please provide a pin number",
                    digits: "Please Enter Only Digits",
                    rangelength: "Please Enter Only 6 Digits"
                },
            }
        });
        
        //Validation For Edit System USer 
        
         $("#edit_systemaccount").validate({
            rules: {
                edit_first_name:"required",
                edit_last_name: "required",
            },
            messages: {
                edit_first_name:"First Name Required",
                edit_last_name:"Last Name Required",
            }
        });

            $("#username").on("blur", function () {

            var username = $("#username").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_username_systemadmin",
                data: {
                    'username': username
                },
                success: function (msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button_system").addClass('disabled');
                        $("#username_error").removeClass("hide");
                    } else {
                        $("#save_button_system").removeClass('disabled');
                        $("#username_error").addClass("hide");
                    }
                }

            });

        });
        
        // validation for update password form
        $("#updatepassword").validate({
            rules: {
                
              
                confirm_newpassword: {
                  
                    equalTo: "#new_password"
                },
                update_pin_number: {
                    
                    digits: true,
                    rangelength: [6, 6],
                },
              
            },
            messages: {
                 
                
                
                 confirm_newpassword: {
                   
                    equalTo: "Password Is Not Matching"
                },
               update_pin_number: {
                  
                    digits: "Please Enter Only Digits",
                    rangelength: "Please Enter Only 6 Digits"
                },
            }
        });
        // Get Edit Admin User
         // script for edit adminuser
        $("body").on("click", ".edit_system_user", function () {

            var firstname = $(this).attr("data_firstname");
            var lastname = $(this).attr("data_lastname");
           
            var system_id = $(this).attr("data_system_userid");
            var username = $(this).attr("data_username");
            
            $("#edit_first_name").attr("value", firstname);
            $("#edit_last_name").attr("value", lastname);
            $("#edit_username").attr("value", username);
            
            $("#system_id").attr("value", system_id);
            $("#edit_access_level").val(access);
         
        });
        
        // get id for changepassword
         $("body").on("click", ".change_system_user_password", function () {
            
            var system_id = $(this).attr("data_system_userid");
            $("#system_id_password").attr("value", system_id);
              $(".result").empty();
        });
         $("#addsysadmin").click(function () {
    
            $(".result").empty();
        })
        

    });

function deleteTemplate(editObj){
            var url = $(editObj).attr('data-href');
            
            bootbox.confirm("Do you want to archive this system admin user ?", function(result) {
                if (result) {
                    window.location.href=url;
                } else {
                    // Do nothing!
                }
            });
        }
</script>

<div class="row">
    <div class="col-lg-4">
        <h1 class="page-header">SYSTEM ADMIN USERS</h1>
    </div>
    <div class="col-lg-8" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button class="btn btn-primary btn-xs icon-with-text" type="button" id="addsysadmin"  data-target="#add_sys_admin" data-toggle="modal"><i class="fa fa-plus"></i>
                    <b>Add SYS Admin</b></button> 


            </div>
        </div>
    </div>

    <!-- /.col-lg-12 -->
</div>
<?php
if ($this->session->flashdata('success')) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php
}

if ($this->session->flashdata('error')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">
            <div class="table-responsive">
                 <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                <table id="systemAdmin_datatable" class="table table-bordered" width="100%" cellspacing="0">
               
                    <thead>
                        <tr>
                          
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="master_body">
                      

                    </tbody>
                </table>
                 </div>
            </div>
            <!-- /.table-responsive -->
        </div>
    </div>
</div>


<!-- Modal for add master acc -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_sys_admin" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add System Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/addSystemAccount' ?>" method="post" id="systemaccount">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="first_name" id="first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Username :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter UserName" class="form-control" name="username" id="username">
                         <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>
                         
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Password : </label> </div>

                        <div class="col-md-6">  <input type="password" placeholder="Enter Password" class="form-control" name="user_password" id="user_password"> <div class="result"></div></div>
                       
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>Confirm Password :</label></div>
                        <div class="col-md-6">   <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirm_password" id="confirm_password"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>6 Digit Pin :</label>

                        </div>
                        <div class="col-md-6"> 
                            <input type="password" placeholder="Enter 6 Digit Pin" class="form-control" name="pin_number" id="pin_number">
                        </div>
                    </div> 
                    
                 </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button_system">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="changepassword" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Change Password</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/changeSystemAdminPassword' ?>" method="POST" id="updatepassword">
                <div class="modal-body">

                  
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Password :</label> </div>
                        <div class="col-md-6">  <input type="password" placeholder="Enter New Password" class="form-control" name="new_password" id="new_password">  <div class="result"></div>
                        </div>
                      
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Confirm Password :</label> </div> 

                        <div class="col-md-6">  <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirm_newpassword" id="confirm_newpassword"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Pin Number : </label> </div>

                        <div class="col-md-6">  <input type="password" placeholder="Enter Pin Number" class="form-control" name="update_pin_number" id="update_pin_number"></div>
                    </div> 
                         <input type="hidden" name="system_id_password" id="system_id_password">
                 
                 
                 </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="changesystempassword">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_system_admin" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit System Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/editSystemAccount' ?>" method="post" id="edit_systemaccount">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="edit_first_name" id="edit_first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="edit_last_name" id="edit_last_name">
                        </div></div> <!-- /.form-group -->
                     <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Username :</label> </div>
                        <div class="col-md-6">  <input type="text"  class="form-control" name="edit_username" id="edit_username">
                        </div></div>
                     
                        <input type="hidden" name="system_id" id="system_id">
                     
                       
                 </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button_system">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>