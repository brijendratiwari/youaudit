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
                username:{
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
            },
            messages: {
                edit_first_name: "First Name Required",
                edit_last_name: "Last Name Required",
            }
        });

        // validation for update password form
        $("#updatepassword").validate({
            rules: {
                confirm_newpassword: {
                    equalTo: "#new_password",
                },
                new_pin_number: {
     
                    digits: true,
                    rangelength: [6, 6],
                    require_from_group: [1, ".form-control"]
                },
                new_password: {
                    require_from_group: [1, ".form-control"]
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
            var contactname = $(this).attr("data_contact_name");
            var adminuser_id = $(this).attr("admin_acc_id");

            $("#edit_first_name").attr("value", firstname);
            $("#edit_last_name").attr("value", lastname);
            $("#edit_username").attr("value", username);
             $("#edit_contact_name").attr("value", contactname);
            $("#adminuser_id").attr("value", adminuser_id);


        });
// script for change password
        $("body").on("click", ".change_password", function () {
            $(".result").empty();
            var change_adminuser_id = $(this).attr("admin_acc_id");
         
            $("#adminuser_id_1").attr("value", change_adminuser_id);
        });

//
        $("#username").on("keyup blur", function () {

            var username = $("#username").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admins/checkMasterAdminUsername",
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
        });

        var master_table = $("#Adminuser").DataTable();

       $('#add_admin_user_form #last_name').on('keyup blur', function()
        {
            var first_name = $('#add_admin_user_form #first_name').val();
            var last_name = $('#add_admin_user_form #last_name').val();
            var contact_name = first_name + ' ' + last_name;
            $('#add_admin_user_form #contact_name').val(contact_name);
        });    
              

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

<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header">ADMIN USER</h1>
    </div>
    <div class="col-lg-9" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button class="btn btn-primary btn-xs icon-with-text" id="adminuser" type="button"  data-target="#add_admin_user_form" data-toggle="modal"><i class="fa fa-plus"></i>
                    <b>Add Admin User</b></button> 

                <a style="margin-left:550px;" href="<?php echo base_url("admins/exportAdminPdf/PDF") ?>" class="btn btn-primary btn-xs icon-with-text" type="button" id="b1"><i class="fa  fa-file-pdf-o"></i>
                    <b> Export PDF</b></a>
                <a  class="btn btn-primary btn-xs icon-with-text" href="<?php echo base_url("admins/exportAdminPdf/CSV") ?>" type="button"><i class="fa fa-file-word-o"></i>
                    <b>Export CSV</b></a>
            </div>
        </div>
    </div>

    <!-- /.col-lg-12 -->
</div>
<div class="row"><div class="col-lg-12">
        <h5 class="page-header"><?php echo strtoupper($this->session->userdata('ParentAccountName')); ?> /  ADMIN USER </h5>
    </div></div>
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
                    <table id="Adminuser" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">

                        <thead>
                            <tr>
                               
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="master_body">
                        <?php 
                        if(!empty($arrAdmins['results'])){
                        foreach($arrAdmins['results'] as $arrAdmin)
                        {
                        ?>
                            
                            <tr>
                                <td><?php echo $arrAdmin->firstname;?></td>
                                <td><?php echo $arrAdmin->lastname;?></td>
                                <td><?php echo $arrAdmin->username;?></td>
                                <td>
                                   
                                    <span class="action-w"><a data-toggle="modal" admin_acc_id="<?php echo $arrAdmin->adminid;?>" class="change_password"  href="#change_password_model" title="Change Password" alt="Edit" /><i class="glyphicon glyphicon-lock franchises-i"/></i></a>Password</span>
                                    
                                   <span class="action-w"><a class="edit" data-toggle="modal" data_firstname="<?php echo $arrAdmin->firstname;?>" data_lastname="<?php echo $arrAdmin->lastname;?>" data_contact_name="<?php echo $arrAdmin->nickname;?>" data_username="<?php echo $arrAdmin->username;?>" admin_acc_id="<?php echo $arrAdmin->adminid;?>"  href="#edit_admin_user_form" title="Edit" class="edit_admin_data"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span>
                                  
                             
                <?php if ($arrSessionData['objAdminUser']->id != $arrAdmin->adminid) { ?>
                            <span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('/admins/archiveAdmin/'.$arrAdmin->adminid.'/'); ?>" title="Archive" alt="Archive" /><i class="glyphicon glyphicon-remove-sign franchises-i"/></i></a>Archive</span>  
                <?php } ?>
                                 
                            
                
              
            </td>
                            </tr>
                            <?php 
                        }
                        }
?>
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

            <form action="<?php echo base_url('admins/create') ?>" method="post" id="add_admin_user_ac">
                <div class="modal-body" id="modal-body-spec">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="first_name" id="first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name">
                        </div></div> 
                    
                     <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Contact Name" class="form-control" name="contact_name" id="contact_name">
                        </div></div> <!-- /.form-group -->
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
                    <input type="hidden" name="masterid" id="masterac_id" value="<?php echo $master_account_id; ?>"/>
                    <input type="hidden" name="change_adminuser_id" id="change_adminuser_id" readonly=""/>

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

            <form action="<?php echo base_url('admins/changeAdminUserPassword') ?>" method="POST" id="updatepassword">
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
                    <input type="hidden" name="change_adminuser_id" id="adminuser_id_1" readonly=""/>
                    <input type="hidden" name="masterid" value="" readonly=""/>


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

            <form action="<?php echo base_url('admins/editAdminUser') ?>" method="post" id="edit_admin_user_ac">
                <div class="modal-body" style="height:250px">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="edit_first_name" id="edit_first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="edit_last_name" id="edit_last_name">
                        </div></div> 
                    
                     <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Contact Name" class="form-control" name="edit_contact_name" id="edit_contact_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Username :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter UserName" class="form-control" name="edit_username" id="edit_username" disabled="">
                            <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>
                        <input type="hidden" name="adminuser_id" id="adminuser_id" readonly/>
                        <input type="hidden" name="masterid" value="<?php echo $master_account_id; ?>" readonly=""/>
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

