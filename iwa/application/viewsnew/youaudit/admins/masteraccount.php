<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        height: 595px;
        overflow-y: scroll;
    } 
    .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        height: 75px;
        overflow: auto !important;
    }
</style>
<script>

    $(document).ready(function() {

//       Update Username According to Email 
        $('#add_master #contact_email').on('blur keyup', function()
        {
            var contact_email = $('#add_master #contact_email').val();
            $('#contact_username').val(contact_email);
        });

//        **********************************************
        $("#masteraccount").validate({
            rules: {
                sys_admin_name: "required",
                company_name: "required",
                contact_name: {
                    required: true

                },
                contact_email: {
                    required: true,
                    email: true
                },
                first_name: "required",
                last_name: "required",
                contact_phone: {
                    required: true,
                    digits: true
                },
                contact_username: "required",
                contact_password: "required",
                confirm_password: {
                    required: true,
                    equalTo: "#contact_password"
                },
                pin_number: {
                    required: true,
                    digits: true,
                    rangelength: [6, 6],
                },
                account_limit: "required"
            },
            messages: {
                sys_admin_name: "Please Enter Name",
                company_name: "Please Enter Company Name",
                contact_name: "Please Enter Contact Name",
                contact_phone: "Please Enter Valid Number",
                contact_username: "Please Enter Username",
                contact_email: "Please Enter Email Address",
                first_name: "Please Enter First Name",
                last_name: "Please Enter Last Name",
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
        // validation for update password


        $("#edit_masteraccount").validate({
            rules: {
                edit_sys_admin_name: "required",
                edit_company_name: "required",
                edit_contact_name: {
                    required: true

                },
                edit_contact_email: {
                    required: true,
                    email: true
                },
                edit_contact_phone: {
                    required: true,
                    digits: true
                },
                edit_contact_username: "required",
                edit_account_limit: "required"
            },
            messages: {
                edit_sys_admin_name: "Please Enter Name",
                edit_company_name: "Please Enter Company Name",
                edit_contact_name: "Please Enter Contact Name",
                edit_contact_phone: "Please Enter Valid Number",
                edit_contact_username: "Please Enter Username",
                edit_contact_email: "Please Enter Email Address",
            }
        });
        // validation for update password
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

        $("#contact_username").on("keyup blur", function() {

            var username = $("#contact_username").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_username",
                data: {
                    'username': username
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#username_error").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#username_error").addClass("hide");
                    }
                }

            });

        });
        $("#sys_admin_name").on("keyup blur", function() {

            var sys_Admin_name = $("#sys_admin_name").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_sysAdminName",
                data: {
                    'sys_admin_name': sys_Admin_name
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#username_error1").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#username_error1").addClass("hide");
                    }
                }

            });

        });



        $("#masterAc").click(function() {
            $(".result").empty();
        })

        // script for get edit data for master acc 
        $("body").on("click", ".editmasteracc", function() {
            var base_url_str = $("#base_url").val();
            var master_id = $(this).attr("data_master_id");

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/get_edit_masterdata",
                data: {
                    'id': master_id
                },
                success: function(data) {
                    var masterdata = $.parseJSON(data);


                    $('#edit_master_id').val(masterdata.id);
                    $('#edit_sys_admin_name').val(masterdata.sys_admin_name);
                    $('#edit_company_name').val(masterdata.company_name);
                    $('#edit_contact_name').val(masterdata.contact_name);
                    $('#edit_contact_email').val(masterdata.email);
                    $('#edit_contact_phone').val(masterdata.phone);
                    $('#edit_contact_username').val(masterdata.username);
                    $('#edit_account_limit').val(masterdata.account_limit);
                    var report = masterdata.enable_report;
                    if (report == 1)
                    {
                        $('#edit_report_allow').prop('checked', true);
                    }
                    else
                    {
                        $('#edit_report_allow').prop('checked', false);
                    }
                }

            });
        });

        $("body").on("click", ".change_master_password", function() {
            var base_url_str = $("#base_url").val();
            var master_id = $(this).attr("data_master_id");
            var username = $(this).attr("data_user_name");
            var sys_admin_name = $(this).attr("data_sys_admin_name");
            $('#update_sys_admin_Name').val(sys_admin_name);
            $('#change_master_id').val(master_id);
            $('#masterusername').val(username);
            $(".result").empty();

        });

        $("#masterAc").click(function() {

            $(".result").empty();
        })


    });
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this Master Account ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
</script>
<script>
    $(document).ready(function () {
        $('body').find('.multiComSelect:checked').prop('checked', false);
        $('body').find('#selectAllchk').prop('checked', false);
        $('body').on('click', '.multiComSelect', function () {
            if ($('html').find('.multiComSelect:checked').length)
            {
                $('#multiComEditBtn').addClass('in').removeClass('hide');
                if ($('html').find('.multiComSelect:not(:checked)').length == 0)
                    $('#selectAllchk').prop('checked', true);
            } else {
                $('#multiComEditBtn').addClass('hide').removeClass('in');
                $('#selectAllchk').prop('checked', false);
            }
        });
        // Generate Reference Qr code for account

        $('body').on('click', '#selectAllchk', function () {
            if ($(this).is(':checked')) {

                $('.multiComSelect').prop('checked', true);
                $('#multiComEditBtn').addClass('in').removeClass('hide');
            } else {

                $('.multiComSelect').prop('checked', false);
                $('#multiComEditBtn').addClass('hide').removeClass('in');
            }
        });
        $('#multiComEditBtn').on('click', function () {
        
            var ids = [];
            var cat_ids = [];

            $('#master_datatable').find('input[type="checkbox"]:checked').each(function () {

                ids.push($(this).attr('value'));
            });
            console.log(ids); 
            $('#multiComIds').val(ids.join(','));
            $('#multiUserEditModal').modal('show');
        });


    });

</script>

<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header">Master Account</h1>
    </div>
    <div class="col-lg-9" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button class="btn btn-primary btn-xs icon-with-text" id="masterAc" type="button"  data-target="#add_master" data-toggle="modal"><i class="fa fa-plus"></i>
                    <b>Add Master Account</b></button> 

                <a style="margin-left:550px;" href="<?php echo base_url() . 'youaudit/youaudit_admins/exportPdfForMaster/PDF' ?>" class="btn btn-primary btn-xs icon-with-text" type="button" id="b1"><i class="fa  fa-file-pdf-o"></i>
                    <b> Export PDF</b></a>
                <a  class="btn btn-primary btn-xs icon-with-text" href="<?php echo base_url() . 'youaudit/youaudit_admins/exportPdfForMaster/CSV' ?>" type="button"><i class="fa fa-file-word-o"></i>
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
                    <table id="master_datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" title="Select ALL" id="selectAllchk"><button type="button" id="multiComEditBtn" class="btn btn-warning fade hide" data-toggle="modal" data-target="#multiUserEditModal" style="padding:0 5px;" >Edit</button></th>
                                <th>Admin Name</th>
                                <th>Company Name</th>
                                <th>Contact Name</th>
                                <th>Contact Email Address</th>
                                <th>Contact Phone</th>
                                <th>Contact Username</th>
                                <th>Account Limit</th>
                                <th>Total Value</th>
                                <th style="width: 20%;">Action</th>
                            </tr>
                            <tr style="display:none">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="master_body">
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>


<!-- Modal for add master acc -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_master" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Master Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/addmasterAccount' ?>" method="post" id="masteraccount">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>SYS Admin Name :</label> </div>
                        <div class="col-md-6"><input placeholder="Enter Sys Admin Name" class="form-control" name="sys_admin_name" id="sys_admin_name">
                            <div id="username_error1" class="username_error hide">Sys Admin Name Is Already Exist.</div>
                        </div>
                        <?php echo form_error('sys_Admin_name'); ?>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="company_name" id="company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Contact Name :</label> </div> 


                        <div class="col-md-6">  <input placeholder="Enter Contact Name" class="form-control" name="contact_name" id="contact_name"></div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact Email Address : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Email Address" class="form-control" name="contact_email" id="contact_email"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact First Name : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="first_name" id="first_name"></div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact Last Name : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name"></div>
                    </div>


                    <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>Contact Phone Number :</label></div>
                        <div class="col-md-6">   <input placeholder="Enter Phone Number" class="form-control" name="contact_phone" id="contact_phone"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Contact UserName :</label>

                        </div>
                        <div class="col-md-6"> 
                            <input placeholder="Enter Contact User Name" class="form-control" name="contact_username" id="contact_username">
                            <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="contact_password" id="contact_password" type="password">  <div class="result"></div></div>

                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="confirm_password" id="confirm_password" type="password"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>6 Digit Pin :</label>
                        </div>
                        <div class="col-md-6">       
                            <input placeholder="Enter 6 Digit Pin" class="form-control" name="pin_number" id="pin_number" type="password">
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Enable Report :</label>
                        </div>
                        <div class="col-md-6">       
                            <label class="checkbox-inline">
                                <input type="checkbox" name="report_allow">
                            </label>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Account Limit : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="number" name="account_limit" id="account_limit" min="0"  class="form-control">
                        </div>
                    </div> 

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
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

            <form action="<?php echo base_url() . 'youaudit/changeMasterUserPassword' ?>" method="POST" id="updatepassword">
                <div class="modal-body" style="height:300px;">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Sys Admin Name :</label> </div>
                        <div class="col-md-6"><input type="text"  class="form-control" disabled="" name="update_sys_admin_Name" id="update_sys_admin_Name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Password :</label> </div>
                        <div class="col-md-6">  <input type="password" placeholder="Enter New Password" class="form-control" name="new_password" id="new_password">
                            <div class="result"></div>
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Confirm Password :</label> </div> 

                        <div class="col-md-6">  <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirm_newpassword" id="confirm_newpassword"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Pin Number : </label> </div>

                        <div class="col-md-6">  <input type="password" placeholder="Enter Pin Number" class="form-control" name="update_pin_number" id="update_pin_number"></div>
                    </div> 
                    <input type="hidden" name="change_master_id" id="change_master_id" class="form-control">
                    <input type="hidden" name="masterusername" id="masterusername" class="form-control">


                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="changemasterpassword">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<!-- /.modal for edit master account-->


<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_add_master" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Master Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/editMasterAccount' ?>" method="post" id="edit_masteraccount">
                <div class="modal-body" style="height:300px;">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>SYS Admin Name :</label> </div>
                        <div class="col-md-6"><input placeholder="Enter Sys Admin Name" class="form-control" disabled="" name="edit_sys_admin_name" id="edit_sys_admin_name">
                            <div id="username_error1" class="username_error hide">Sys Admin Name Is Already Exist.</div>
                        </div>
                        <?php echo form_error('sys_Admin_name'); ?>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="edit_company_name" id="edit_company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Contact Name :</label> </div>  


                        <div class="col-md-6">  <input placeholder="Enter Contact Name" class="form-control" name="edit_contact_name" id="edit_contact_name"></div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact Email Address : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Email Address" class="form-control" name="edit_contact_email" id="edit_contact_email"></div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>Contact Phone Number :</label></div>
                        <div class="col-md-6">   <input placeholder="Enter Phone Number" class="form-control" name="edit_contact_phone" id="edit_contact_phone"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Contact UserName :</label>

                        </div>
                        <div class="col-md-6"> 
                            <input placeholder="Enter Contact User Name" disabled="" class="form-control" name="edit_contact_username" id="edit_contact_username">
                            <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>
                    </div> 



                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Enable Report :</label>
                        </div>
                        <div class="col-md-6">       
                            <label class="checkbox-inline">
                                <input type="checkbox" name="edit_report_allow" id="edit_report_allow">
                            </label>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Account Limit : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="number" name="edit_account_limit" id="edit_account_limit" min="0" class="form-control">
                        </div>
                    </div> 
                    <input type="hidden" name="edit_master_id" id="edit_master_id" class="form-control">
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_save_button">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>
<!--Edit multiple USer Credentials-->
<div class="modal fade" id="multiUserEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Multiple Account</h4>
            </div>
            <form action="<?php echo base_url('youaudit/youaudit_admins/editMultipleMasterAc'); ?>" method="post" id="edit_multipleuser_account">
                <div class="modal-body" style="height:100px; overflow: auto;">
                    <input hidden="" name="account_id" id="multiComIds">
                      
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Account Limit : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="number" name="multiple_account_limit" id="multiple_account_limit" min="0" class="form-control">
                        </div>
                    </div> 
                    
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button_system">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>




