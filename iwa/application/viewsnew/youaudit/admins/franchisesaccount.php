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
            $('#contact_username_franchises').val(contact_email);
        });

//        **********************************************

        $("#masteraccount").validate({
            rules: {
                sys_franchise_name: "required",
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
                contact_username_franchises: "required",
                contact_password_franchise: "required",
                confirm_password: {
                    required: true,
                    equalTo: "#contact_password_franchise"
                },
                pin_number: {
                    required: true,
                    digits: true,
                    rangelength: [6, 6],
                },
                account_limit: "required"
            },
            messages: {
                sys_franchise_name: "Please Enter Name",
                company_name: "Please Enter Company Name",
                contact_name: "Please Enter Contact Name",
                contact_phone: "Please Enter Valid Number",
                contact_username_franchises: "Please Enter Username",
                contact_email: "Please Enter Email Address",
                first_name: "Please Enter First Name",
                last_name: "Please Enter Last Name",
                contact_password_franchise: {
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

        // validation for edit form
        $("#update_franchise_acc").validate({
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
        // check franchies username is present or not

        $("#contact_username_franchises").on("keyup blur", function() {

            var username = $("#contact_username_franchises").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_username_franchies",
                data: {
                    'username': username
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button_franchise").addClass('disabled');
                        $("#username_error_frenchise").removeClass("hide");
                    } else {
                        $("#save_button_franchise").removeClass('disabled');
                        $("#username_error_frenchise").addClass("hide");
                    }
                }

            });

        });


        // Check Franchises System Admin

        $("#sys_franchise_name").on("keyup blur", function() {

            var sys_Admin_name = $("#sys_franchise_name").val();
            var base_url_str = $("#base_url").val();


            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_sysAdminNameForFranchises",
                data: {
                    'sys_franchises_name': sys_Admin_name
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button_franchise").addClass('disabled');
                        $("#error_div_franchise").removeClass("hide");
                    } else {
                        $("#save_button_franchise").removeClass('disabled');
                        $("#error_div_franchise").addClass("hide");
                    }
                }

            });

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
        $("#franchiseAc").click(function() {
            $(".result").empty();
        })
        //get franchise edit data
        // script for get edit data for master acc 
        $("body").on("click", ".editfranchiseacc", function() {
            var base_url_str = $("#base_url").val();
            var franchise_id = $(this).attr("data_franchise_id");

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/get_edit_franchisedata",
                data: {
                    'id': franchise_id
                },
                success: function(data) {
                    var franchisedata = $.parseJSON(data);


                    $('#edit_franchise_id').val(franchisedata.id);
                    $('#edit_sys_franchise_name').val(franchisedata.sys_franchise_name);
                    $('#edit_company_name').val(franchisedata.company_name);
                    $('#edit_contact_name').val(franchisedata.contact_name);
                    $('#edit_contact_email').val(franchisedata.email);
                    $('#edit_contact_phone').val(franchisedata.phone);
                    $('#edit_contact_username_franchises').val(franchisedata.username);
                    $('#edit_account_limit').val(franchisedata.account_limit);

                }

            });
        });
        // get data for change password
        $("body").on("click", ".change_franchise_password", function() {
            var base_url_str = $("#base_url").val();
            var franchise_id = $(this).attr("data_franchise_id");
            var sys_admin_name = $(this).attr("data_system_franchise_name");
            var username = $(this).attr("data_user_name");

            $('#update_sys_franchise_Name').val(sys_admin_name);
            $('#change_franchise_id').val(franchise_id);
            $('#franchiseusername').val(username);

            $(".result").empty();

        });
        $("#franchiseAc").click(function() {

            $(".result").empty();
        });

    });
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this Franchise Account ?", function(result) {
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

            $('#franchise_datatable').find('input[type="checkbox"]:checked').each(function () {

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
        <h1 class="page-header">Franchise List</h1>
    </div>
    <div class="col-lg-9" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button class="btn btn-primary btn-xs icon-with-text" type="button"  data-target="#add_master" data-toggle="modal" id="franchiseAc"><i class="fa fa-plus"></i>
                    <b>Add Franchises</b></button> 

                <a style="margin-left:550px;" href="<?php echo base_url() . 'youaudit/youaudit_admins/exportPdfForFranchise/PDF' ?>" class="btn btn-primary btn-xs icon-with-text" type="button" id="b1"><i class="fa  fa-file-pdf-o"></i>
                    <b>Export PDF</b></a>
                <a  class="btn btn-primary btn-xs icon-with-text" href="<?php echo base_url() . 'youaudit/youaudit_admins/exportPdfForFranchise/CSV' ?>" type="button"><i class="fa fa-file-word-o"></i>
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

<!--<div class="row">
    <div class="col-lg-6">

    </div>

    <div class="col-lg-6">
        <div class="panel panel-default">

             /.panel-heading 
            <div class="panel-body">
                <button class="btn btn-primary btn-xs" type="button"><i class="fa fa-user"></i>
                    Add Customer Account</button>


                <button style="float: right" class="btn btn-primary btn-xs" type="button"><i class="fa  fa-file-pdf-o"></i>
                    Export PDF</button>
                <button style="float: right" class="btn btn-primary btn-xs" type="button"><i class="fa fa-file-word-o"></i>
                    Export CSV</button>
            </div>
        </div>
    </div>
</div>-->
<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">
            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="franchise_datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" title="Select ALL" id="selectAllchk"><button type="button" id="multiComEditBtn" class="btn btn-warning fade hide" data-toggle="modal" data-target="#multiUserEditModal" style="padding:0 5px;" >Edit</button></th>
                                <th>Franchise Name</th>
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
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.table-responsive -->
        </div>
    </div>
</div>


<!-- Modal for add master acc -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_master" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Franchise Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/addfranchiseAccount' ?>" method="post" id="masteraccount">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Sys Franchises Name :</label> </div>
                        <div class="col-md-6"><input placeholder="Enter Sys Franchises Name" class="form-control" name="sys_franchise_name" id="sys_franchise_name">
                            <div id="error_div_franchise" class="username_error hide">Franchise Name Is Already Exist.</div>
                        </div>
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
                            <input placeholder="Enter Contact User Name" class="form-control" name="contact_username_franchises" id="contact_username_franchises">
                            <div id="username_error_frenchise" class="username_error hide">Username Already Exist.</div>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Password :</label>

                        </div>

                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="contact_password_franchise" id="contact_password_franchise" type="password"><div class="result"></div></div>

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
                        <div class="col-md-6"> <label>Account Limit : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="number" name="account_limit" id="account_limit" min="0" class="form-control">
                        </div>
                    </div> 

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button_franchise">Save</button>
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

            <form action="<?php echo base_url() . 'youaudit/changeFranchisePassword' ?>" method="POST" id="updatepassword">
                <div class="modal-body" style="height:300px;">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Sys Franchise Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter Sys Franchise Name" class="form-control" name="update_sys_franchise_Name" id="update_sys_franchise_Name" disabled=""></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Password :</label> </div>
                        <div class="col-md-6">  <input type="password" placeholder="Enter New Password" class="form-control" name="new_password" id="new_password">
                            <div class="result"></div>  </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Confirm Password :</label> </div> 

                        <div class="col-md-6">  <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirm_newpassword" id="confirm_newpassword"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Pin Number : </label> </div>

                        <div class="col-md-6">  <input type="password" placeholder="Enter Pin Number" class="form-control" name="update_pin_number" id="update_pin_number"></div>
                    </div> 
                    <input type="hidden" name="change_franchise_id" id="change_franchise_id" class="form-control">
                    <input type="hidden" name="franchiseusername" id="franchiseusername" class="form-control">


                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="changefranchisepassword">update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>


<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_franchise_master" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Franchise Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/editFranchiseAccount' ?>" method="post" id="update_franchise_acc">
                <div class="modal-body" style="height:400px;">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Sys Franchises Name :</label> </div>
                        <div class="col-md-6"><input class="form-control" name="edit_sys_franchise_name" id="edit_sys_franchise_name" disabled="">
                            <div id="error_div_franchise" class="username_error hide">Franchise Name Is Already Exist.</div>
                        </div>
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
                            <input placeholder="Enter Contact User Name" disabled="" class="form-control" name="edit_contact_username_franchises" id="edit_contact_username_franchises">
                            <div id="username_error_frenchise" class="username_error hide">Username Already Exist.</div>
                        </div>
                    </div> 




                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Account Limit : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="number" name="edit_account_limit" id="edit_account_limit" min="0" class="form-control">
                        </div>
                    </div> 
                    <input type="hidden" name="edit_franchise_id" id="edit_franchise_id" class="form-control">
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_save_button_franchise">Update</button>
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
            <form action="<?php echo base_url('youaudit/youaudit_admins/editMultipleFranchiseAc'); ?>" method="post" id="edit_multipleuser_account">
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








