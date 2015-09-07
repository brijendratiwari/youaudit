<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    #modal-body-spec{

        overflow-y: scroll; 
    } 
      .bootbox .modal-dialog{
        width: 300px;
    }
</style>
<script>
    $(document).ready(function () {
        
          $("#last_name").on("blur", function () {
              var firstname = $("#first_name").val();
              var lastname = $("#last_name").val();
            
              $("#contact_name").val(firstname+' '+lastname);
              });
              
        $("#add_admin_user_ac").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                username: {
                    required: true,
                    email: true
                },
                user_password: "required",
                contact_name:"required",
                confirm_password: {
                    required: true,
                    equalTo: "#user_password"
                },
                pin_number: {
                    required: true,
                    digits: true,
                    rangelength: [6, 6],
                },
            },
            messages: {
                first_name: "First Name Required",
                last_name: "Last Name Required",
                 username: {
                    required: "Please Enter Username",
                    email: "Enter Valid Emails"
                },
                contact_name:"Contact Name Required",
                contact_password: {
                    required: "Please Enter Password",
                },
                confirm_password: {
                    required: "Please Enter Confirm Password",
                    equalTo: "Password Is Not Matching"
                },
                pin_number: {
                    digits: "Please Enter Only Digits",
                    rangelength: "Please Enter Only 6 Digits"
                },
            }
        });
        
        //validation for edit user
        $("#edit_admin_user_ac").validate({
            rules: {
                edit_first_name: "required",
                edit_last_name: "required",
                edit_contact_name:"required",
             
            },
            messages: {
                 edit_first_name: "First Name Required",
                edit_last_name: "Last Name Required",
                edit_contact_name:"Contact Name Required",
            }
        });

        // validation for update password form
        $("#updatepassword").validate({
            rules: {
                confirm_newpassword: {
                    equalTo: "#new_password"
                },
                new_pin_number: {
                    digits: true,
                    rangelength: [6, 6],
                    
                },
                
            },
            messages: {
                confirm_newpassword: {
                    required: "Please Enter Confirm Password",
                    equalTo: "Password Is Not Matching"
                },
                new_pin_number: {
                    required: "Please provide a pin number",
                    digits: "Please Enter Only Digits",
                    rangelength: "Please Enter Only 6 Digits"
                },
            }
        });

        // script for edit adminuser
        $("body").on("click", ".edit", function () {

            var firstname = $(this).attr("data_firstname");
            var lastname = $(this).attr("data_lastname");
            var username = $(this).attr("data_username");
            var adminuser_id = $(this).attr("data_adminuser_id");
            var contact = $(this).attr("data_contactname");

            $("#edit_first_name").attr("value", firstname);
            $("#edit_last_name").attr("value", lastname);
            $("#edit_username").attr("value", username);
            $("#edit_contact_name").attr("value", contact);
            $("#adminuser_id").attr("value", adminuser_id);


        });
// script for change password
        $("body").on("click", ".change_password_model", function () {
              
            var change_adminuser_id = $(this).attr("data_adminuser_id");
            $("#change_adminuser_id").attr("value", change_adminuser_id);
             $(".result").empty();
        });
     
// script for check username
  $("#username").on("blur", function () {

            var username = $("#username").val();
            var base_url_str = $("#base_url").val();
             
            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/franchise_admins/check_franchiseAdminUsername",
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
       
    
     $("#adminuser").click(function () {
            
            $(".result").empty();
        })


    });
    
       function deleteTemplate(editObj){
            var url = $(editObj).attr('data-href');
            
            bootbox.confirm("Do you want to archive this user ?", function(result) {
                if (result) {
                    window.location.href=url;
                } else {
                    // Do nothing!
                }
            });
        }
</script>

<BR>
<div class="panel panel-default">
    <div class="panel-heading">
        <b><?php echo strtoupper($account_name); ?> / Admin User</b>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-pills">
            <li ><a data-toggle="" href="<?php echo base_url("youaudit/franchise_customerlist/$masterid"); ?>">Customer List</a>
            </li>
            <li class="active"><a data-toggle="" href="#profile-pills">Admin Users</a>
            </li>
              <li><a data-toggle="" href="<?php echo base_url("youaudit/franchise_admins/complianceChecksForFranchise/$masterid"); ?>">Compliance Templates</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/franchise_profiles/$masterid"); ?>">Profiles</a>
            </li>
              <li ><a data-toggle="" href="<?php echo base_url("youaudit/franchise_admins/restorecustomer/$masterid"); ?>">    Archive Account
</a>
            </li>
        </ul>

        <!-- Tab panes -->

    </div>
    <!-- /.panel-body -->
</div>
<div class="row">
    <div class="col-lg-3">
        <h3 class="page-header">ADMIN USER</h3>
    </div>
    <div class="col-lg-9" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button class="btn btn-primary btn-xs icon-with-text" id="adminuser" type="button"  data-target="#add_admin_user_form" data-toggle="modal"><i class="fa fa-plus"></i>
                    <b>Add Admin User</b></button> 

                <a href='<?php echo base_url("youaudit/franchise_admins/exportFranchiseAdminUser/PDF/$masterid");?>' style="margin-left:550px;" class="btn btn-primary btn-xs icon-with-text" type="button" id="b1"><i class="fa  fa-file-pdf-o"></i>
                    <b> Export PDF</b></a>
                <a href='<?php echo base_url("youaudit/franchise_admins/exportFranchiseAdminUser/CSV/$masterid");?>'  class="btn btn-primary btn-xs icon-with-text" type="button"><i class="fa fa-file-word-o"></i>
                    <b>Export CSV</b></a>
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
                    <table id="franchiseadminuser_datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">

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

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_admin_user_form" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add User</h4>
            </div>

            <form action="<?php echo base_url('youaudit/addFranchiesAdminUser') ?>" method="post" id="add_admin_user_ac">
                <div class="modal-body" id="modal-body-spec">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="first_name" id="first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name">
                        </div></div> <!-- /.form-group -->
                        <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name :</label> </div>
                        <div class="col-md-6">  <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Enter Contact Name">
                        </div></div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Username :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter UserName" class="form-control" name="username" id="username">
                            <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Password : </label> </div>

                        <div class="col-md-6">  <input type="password" placeholder="Enter Password" class="form-control" name="user_password" id="user_password"><div class="result"></div></div>
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
                    <input type="hidden" name="masterid" id="masterac_id" value="<?php echo $masterid; ?>"/>

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

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="change_password_model" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Change Password</h4>
            </div>

            <form action="<?php echo base_url('youaudit/changeFranchiseAdminUserPassword') ?>" method="POST" id="updatepassword">
                <div class="modal-body" style="height:250px">


                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Password :</label> </div>
                        <div class="col-md-6">  <input type="password" placeholder="Enter New Password" class="form-control" name="new_password" id="new_password"><div class="result"></div>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Confirm Password :</label> </div> 

                        <div class="col-md-6">  <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirm_newpassword" id="confirm_newpassword"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>New Pin Number : </label> </div>

                        <div class="col-md-6">  <input type="password" placeholder="Enter Pin Number" class="form-control" name="new_pin_number" id="update_pin_number"></div>
                    </div> 
                    <input type="hidden" name="change_adminuser_id" id="change_adminuser_id"/>
                    <input type="hidden" name="masterid" value="<?php echo $masterid; ?>"/>


                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="changepassword">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_admin_user_form" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit User</h4>
            </div>

            <form action="<?php echo base_url('youaudit/editFranchiseAdminUser') ?>" method="post" id="edit_admin_user_ac">
                <div class="modal-body" style="height:250px">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="edit_first_name" id="edit_first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="edit_last_name" id="edit_last_name">
                        </div></div> <!-- /.form-group -->
                        <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name :</label> </div>
                        <div class="col-md-6">  <input type="text" id="edit_contact_name" name="edit_contact_name" class="form-control" placeholder="Enter Contact Name">
                        </div></div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Username :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter UserName" class="form-control" disabled="" name="edit_username" id="edit_username">
                            <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>
                        <input type="hidden" name="adminuser_id" id="adminuser_id"/>
                        <input type="hidden" name="masterid" value="<?php echo $masterid; ?>"/>
                    </div> 
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
