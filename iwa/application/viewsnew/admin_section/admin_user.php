<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        min-height: 200px;
        max-height: 595px; 
        overflow-y: scroll;
    } 
    .qty {
        width: 40px;
        height: 25px;
        text-align: center;
    }
    input.qtyplus { width:25px; height:25px;}
    input.qtyminus { width:25px; height:25px;}
    .username_error
    {
        color: red;
        font-weight: bold;
    }
    .username_err
    {
        color: red;
        font-weight: bold;
    }
    .supplier_username_error
    {
        color: red;
        font-weight: bold;
    }
    .username_err1
    {
        color: red;
        font-weight: bold;
    }
    .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        min-height: 75px;
        overflow: auto !important;
    }
</style>
<script>
    $(document).ready(function() {
        $('#multiple_user').on('click', function()
        {
            $('.qty').attr('value', 1);
            $('input[name=quantity]').val(1);
            $('.multiple').remove();
        });


// This button will increment the value
        $('.qtyplus').click(function(e) {
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            fieldName = $(this).attr('field');
            // Get its current value
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());
            $('.qty').attr('value', currentVal);
            // If is not undefined
            if (!isNaN(currentVal)) {
                // Increment
                $('input[name=' + fieldName + ']').val(currentVal + 1);
                var rowCount = currentVal + 1;
                $('.qty').attr('value', rowCount);

                var recRow = '<tr id="row_' + rowCount + '" class="multiple"><input type="hidden" name="users" value="' + rowCount + '"><td><input name="first_name' + rowCount + '" class="form-control" type="text" required/></td><td><input name="last_name' + rowCount + '" class="form-control" type="text" required/></td><td><input name="user_name' + rowCount + '" id="username' + rowCount + '" type="text" class="form-control" required/><div id="username_err' + rowCount + '" class="username_err hide">Username Is Already Exist.</div></td><td><input name="mpassword' + rowCount + '" class="form-control" type="password" required/></td><td><select name="level' + rowCount + '" class="form-control" id="multiple_access" required><option value="">-----select-----</option><option value="1">User</option><option value="2">Manager</option><option value="3">Admin</option><option value="4">Superadmin</option><option value="5">AppOnly</option></select></td><td><select name="add_owner' + rowCount + '" class="form-control" name="add_owner" id="add_owner"><option value="1">Yes</option><option value="0">No</option></select></td><td><input type="checkbox" name="multiple-notify' + rowCount + '" data-size="mini" class="form-control" checked></td></tr>';
                $('#multiple_user tbody').append(recRow);
                $("[name='multiple-notify" + rowCount + "']").bootstrapSwitch();
                $("#username" + rowCount).on("keyup blur", function() {

                    var username = $("#username" + rowCount).val();
                    var base_url_str = $("#base_url").val();

                    $.ajax({
                        type: "POST",
                        url: base_url_str + "admin_section/checkUsername",
                        data: {
                            'username': username
                        },
                        success: function(msg) {

                            // we need to check if the value is the same
                            if (msg == "1") {
                                //Receiving the result of search here
                                $("#save").addClass('disabled');
                                $("#username_err" + rowCount).removeClass("hide");
                            } else {
                                $("#save").removeClass('disabled');
                                $("#username_err" + rowCount).addClass("hide");
                            }
                        }

                    });

                });
            } else {
                // Otherwise put a 0 there
                $('input[name=' + fieldName + ']').val(1);
            }

        });
        // This button will decrement the value till 1
        $(".qtyminus").click(function(e) {
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            fieldName = $(this).attr('field');
            // Get its current value
            var currentVal = parseInt($('input[name=' + fieldName + ']').val());

            // If it isn't undefined or its greater than 1
            if (!isNaN(currentVal) && currentVal > 1) {
                // Decrement one
                $('input[name=' + fieldName + ']').val(currentVal - 1);
                var removeuser = currentVal - 1;
                $('.qty').attr('value', removeuser);
                $('#row_' + currentVal).remove();
            } else {
                // Otherwise put a 1 there
                $('input[name=' + fieldName + ']').val(1);
            }
        });

        $("#add_user_account").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                username: {
                    required: true,
                    email: true
                },
                contact_password: "required",
                confirm_password: {
                    required: true,
                    equalTo: "#contact_password"
                },
                access_level: {required: true, min: 1},
            },
            messages: {
                first_name: "Please Enter First Name",
                last_name: "Please Enter Last Name",
                username: {
                    required: "Please Enter UserName",
                    email: "Please Enter Valid Email Address"
                },
                contact_password: {
                    required: "Please Enter Password",
                },
                confirm_password: {
                    required: "Please Enter Confirm Password",
                    equalTo: "Password Is Not Matching"
                },
                access_level: {min: "Please Select Any Level"},
            }
        });

        $("#edit_user_account").validate({
            rules: {
                edit_first_name: "required",
                edit_last_name: "required",
                edit_username: {
                    required: true,
                    email: true
                },
                edit_access_level: {required: true, min: 1},
            },
            messages: {
                edit_first_name: "Please Enter First Name",
                edit_last_name: "Please Enter Last Name",
                edit_username: {
                    required: "Please Enter UserName",
                    email: "Please Enter Valid Email Address"
                },
                edit_access_level: {min: "Please Select Any Level"},
            }
        });

        $("#updatepassword").validate({
            rules: {
                new_password: "required",
                confirm_newpassword: {
                    required: true,
                    equalTo: "#new_password"
                },
            },
            messages: {
                new_password: {
                    required: "Please Enter Password",
                },
                confirm_newpassword: {
                    equalTo: "Password Is Not Matching"
                },
            }
        });

        $("#supplier_user").validate({
            rules: {
                supplier_first_name: "required",
                supplier_last_name: "required",
                supplier_username: {
                    required: true,
                    email: true
                },
                supplier_password: "required",
                supplier_confirm_password: {
                    required: true,
                    equalTo: "#supplier_password"
                },
                supplier_id: {required: true, min: 1}
            },
            messages: {
                supplier_first_name: "Please Enter First Name",
                supplier_last_name: "Please Enter Last Name",
                supplier_username: {
                    required: "Please Enter UserName",
                    email: "Please Enter Valid Email Address"
                },
                supplier_password: {
                    required: "Please Enter Password"
                },
                supplier_confirm_password: {
                    required: "Please Enter Confirm Password",
                    equalTo: "Password Is Not Matching"
                },
                supplier_id: {min: "Please Select Any Supplier"}
            }
        });

        // script for user change password
        $("body").on("click", ".change_password_model", function() {
            $(".result").empty();
            var change_adminuser_id = $(this).attr("data_adminuser_id");

            $("#adminuser_id_1").attr("value", change_adminuser_id);
        });

        // script for edit user
        var editUserName = '';
        $("body").on("click", ".edit", function() {

            var firstname = $(this).attr("data_firstname");
            var lastname = $(this).attr("data_lastname");
            var username = $(this).attr("data_username");
            editUserName = $(this).attr("data_username");
            var access = $(this).attr("data_access");
            var owner = $(this).attr("data_owner");
            var adminuser_id = $(this).attr("data_adminuser_id");
            var notify = $(this).attr("data_notify");

            $("#edit_first_name").attr("value", firstname);
            $("#edit_last_name").attr("value", lastname);
            $("#edit_access_level option[value='" + access + "']").prop("selected", "selected");
            $("#edit_username").attr("value", username);
            $("#add_owner option[value='" + owner + "']").prop("selected", "selected");
            $("#adminuser_id").attr("value", adminuser_id);
            if (notify == 0)
            {
                $('#edit_notify').bootstrapSwitch('state', false);
            }
            else {

                $('#edit_notify').bootstrapSwitch('state', true);
            }

        });
        var user_admin = $("#User_Admin_Datatable").DataTable({
            "ordering": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
            "bSortCellsTop": true,
//            "scrollY": "200px",
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnRowCallback": function(nRow, aData) {

                var $nRow = $(nRow); // cache the row wrapped up in jQuery
                tdhtm = $nRow.children()[7].innerHTML;

                if (tdhtm.search("enable") != -1) {
                    $nRow.css("background-color", "#f2b4b4");
                }

                return nRow;
            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [7]}
            ]}
        );
        $("#User_Admin_Datatable thead .access th").each(function(i) {
            if (i == 4) {

                var select = $('<select class="select_option"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    user_admin.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                user_admin.column(i).data().unique().sort().each(function(d, j) {
                    select.append('<option id="level" value="' + d + '">' + d + '</option>');
                });
            }
            if (i == 5) {

                var select = $('<select class="owner"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    user_admin.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                user_admin.column(i).data().unique().sort().each(function(d, j) {
                    select.append('<option id="level" value="' + d + '">' + d + '</option>');
                });
            }
        });
        var user_supplier = $("#User_Supplier_Datatable").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.

            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [4]}
            ],
            "fnRowCallback": function(nRow, aData) {

                var $Row = $(nRow); // cache the row wrapped up in jQuery

                supp = $Row.children()[4].innerHTML;

                if (supp.search("enable") != -1) {
                    $Row.css("background-color", "#f2b4b4");
                }

                return nRow;
            },
        }
        );
        $("#user_name1").on("keyup blur", function() {

            var username = $("#user_name1").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkUsername",
                data: {
                    'username': username
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save").addClass('disabled');
                        $("#username_err1").removeClass("hide");
                    } else {
                        $("#save").removeClass('disabled');
                        $("#username_err1").addClass("hide");
                    }
                }

            });

        });
        $("#username").on("keyup blur", function() {

            var username = $("#username").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkUsername",
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

        $("#supplier_username").on("keyup blur", function() {

            var username = $("#supplier_username").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkUsername",
                data: {
                    'username': username
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#supplier_button_system").addClass('disabled');
                        $("#supplier_username_error").removeClass("hide");
                    } else {
                        $("#supplier_button_system").removeClass('disabled');
                        $("#supplier_username_error").addClass("hide");
                    }
                }

            });

        });


        // script for edit user
        $("body").on("click", ".edit_supplier", function() {

            var firstname = $(this).attr("data_firstname");
            var lastname = $(this).attr("data_lastname");
            var username = $(this).attr("data_username");
            var supplier_id = $(this).attr("data_suppliername");

            var adminuser_id = $(this).attr("data_adminuser_id");

            $("#edit_supplier_first_name").attr("value", firstname);
            $("#edit_supplier_last_name").attr("value", lastname);
            $("#edit_supplier_id option[value='" + supplier_id + "']").prop("selected", "selected");
            $("#edit_supplier_username").attr("value", username);
            $("#supplieradminuser_id").attr("value", adminuser_id);

        });

//  Adding Switch for Push notification
        $("[name='my-checkbox']").bootstrapSwitch();
        $("[name='notification']").bootstrapSwitch();
        $("[name='multiple-notify1']").bootstrapSwitch();
//  Adding Switch for Push notification
//        $('#edit_notify').bootstrapSwitch();



// check username on edit user information....
        $("#edit_username").on("keyup blur", function() {

            var username = $(this).val();
            var base_url_str = $("#base_url").val();
            if (editUserName != username) {

                $.ajax({
                    type: "POST",
                    url: base_url_str + "admin_section/checkUsername",
                    data: {
                        'username': username
                    },
                    success: function(msg) {

                        // we need to check if the value is the same
                        if (msg == "1") {
                            //Receiving the result of search here
                            $("#edit_button_system").addClass('disabled');
                            $("#edit_username_error").removeClass("hide");
                        } else {
                            $("#edit_button_system").removeClass('disabled');
                            $("#edit_username_error").addClass("hide");
                        }
                    }

                });
            } else {
                $("#edit_username_error").addClass("hide");
                $("#edit_button_system").removeClass('disabled');
            }

        });



    });
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this User ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('body').find('.multiComSelect:checked').prop('checked', false);
        $('body').find('#selectAllchk').prop('checked', false);
        $('body').on('click', '.multiComSelect', function() {
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

        $('body').on('click', '#selectAllchk', function() {
            if ($(this).is(':checked')) {

                $('.multiComSelect').prop('checked', true);
                $('#multiComEditBtn').addClass('in').removeClass('hide');
            } else {

                $('.multiComSelect').prop('checked', false);
                $('#multiComEditBtn').addClass('hide').removeClass('in');
            }
        });
        $('#multiComEditBtn').on('click', function() {
            var ids = [];
            var cat_ids = [];

            $('#User_Admin_Datatable').find('input[type="checkbox"]:checked').each(function() {

                ids.push($(this).attr('value'));
            });
            console.log(ids);
            $('#multiComIds').val(ids.join(','));
            $('#multiUserEditModal').modal('show');
        });


    });

</script>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Users</h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li class="active" ><a data-toggle="" href="<?php echo base_url('admin_section/admin_user'); ?>">Users</a>
                    </li>
                    <li ><a data-toggle="" href="<?php echo base_url("admin_section/admin_owner"); ?>">Owners</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url("admin_section/admin_categories"); ?>">Categories</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/'); ?>">Items</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_sites'); ?>">Sites</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_location'); ?>">Location</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/customFields'); ?>">Custom Fields</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_supplier'); ?>">Suppliers/Customers</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_archive'); ?>">Archive</a>
                    </li>
                    <?php
                    if ($arrSessionData['objSystemUser']->levelid == 3 || $arrSessionData['objSystemUser']->levelid == 4) {
                        ?>
                        <li><a data-toggle="" href="<?php echo base_url('admin_section/data_import'); ?>">Data Import</a>
                        </li>
                    <?php } ?>
                </ul>

                <!-- Tab panes -->

            </div>
        </div>
    </div>
</div>
<!-- /.panel-body -->
<!--        </div>
    </div>
</div>-->
<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header"><?php echo $customer_data; ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-7">




                    <a  href="<?= base_url('admin_section/exportPDFForUser/CSV') ?>" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>CSV</b>
                    </a>


                    <a  href="<?= base_url('admin_section/exportPDFForUser/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>PDF</b></a>

                    <a class="button icon-with-text round" id="create_customer_account" data-target="#add_user" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add <br>User</b></a>

                    <a class="button icon-with-text round" id="multiple_user" data-target="#add_multiple_user_model" data-toggle="modal"><i class="fa fa-plus"></i><b>Add Multiple<br>User</b></a>     

                    <a class="button icon-with-text round" id="create_customer_account" data-target="#add_supplier_user" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add <br>Supplier User </b></a>

                </div>
                <div class="text-right col-md-5">
                    <span class ="com-name"><?= $arrSessionData['objSystemUser']->accountname; ?>
                        <!--<img src="<?= base_url('/img/circle-red.png'); ?>" width="60" />-->
                    </span>
                    <?php
                    $logo = 'logo.png';
                    if (isset($this->session->userdata['theme_design']->logo) && $this->session->userdata['theme_design']->logo != '') {

                        $logo = $this->session->userdata['theme_design']->logo;
                    }
                    ?>

                    <div class="logocls">
                        <img  alt="iSchool"  class="imgreplace" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>"  >

                    </div>
                </div>
            </div>
        </div>

        <!-- /.col-lg-12 -->
    </div>
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
                    <table id="User_Admin_Datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username/Email Address</th>
                                <th>Access Level</th>
                                <th>Add To Owner List</th>
                                <th>Notification</th>
                                <th>Actions</th>
                            </tr>
                            <tr class="access">
                                <th class="left"><input type="checkbox" title="Select ALL" id="selectAllchk"><button type="button" id="multiComEditBtn" class="btn btn-warning fade hide" data-toggle="modal" data-target="#multiUserEditModal" style="padding:0 5px;" >Edit</button></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php foreach ($users as $val) { ?>                               
                                <tr>
                                    <td><input type="checkbox" class="multiComSelect" value=<?php echo $val['id']; ?>><input class="" type="hidden" id="category_id_<?php echo $val[id]; ?>;" value=""></td>
                                    <td><?php echo $val['firstname']; ?></td>
                                    <td><?php echo $val['lastname']; ?></td>
                                    <td><?php echo $val['username']; ?></td>
                                    <td><?php echo $val['name']; ?></td>
                                    <td><?php
                                        if ($val['is_owner'] == 1) {
                                            echo 'Yes';
                                        } else {
                                            echo 'No';
                                        };
                                        ?></td>
                                    <td><?php
                                        if ($val['push_notification'] == 1) {
                                            echo 'Yes';
                                        } else {
                                            echo 'No';
                                        };
                                        ?></td>
                                    <?php
                                    if ($val['active'] == 1) {
                                        $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('admin_section/disableUser/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                    } else {
                                        $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('admin_section/enableUser/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="fa  fa-play franchises-i"></i></a>Enable</span>';
                                    }
                                    ?>
                                    <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $val['id']; ?>" href="#edit_user" title="Edit" data_notify="<?php echo $val['push_notification'] ?>" data_firstname="<?php echo $val['firstname']; ?>" data_lastname=" <?php echo $val['lastname']; ?>" data_username="<?php echo $val['username']; ?>" data_owner="<?php echo $val['is_owner']; ?>" data_adminuser_id="<?php echo $val['id']; ?>"  data_access="<?php echo $val['levelid']; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><span class="action-w"><a data-toggle="modal"   id="changepassword_id_<?php echo $val['id']; ?>" href="#change_password_model" data_adminuser_id="<?php echo $val['id']; ?>" class="change_password_model"  title="Password"><i class="fa fa-lock franchises-i"></i></a>Password</span><span class="action-w"><a data-toggle="modal"   id="userlogs_id_<?php echo $val['id']; ?>" href="<?php echo base_url('reports/createPdf/userActivity/' . $val['id']); ?>" data_adminuser_id="<?php echo $val['id']; ?>" class="userlog"  title="User Logs"><i class="fa fa-file-pdf-o franchises-i"></i></a>User Logs</span><?php echo $access_icon; ?><span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('admin_section/archiveUser/' . $val['id']); ?>"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">Supplier Access Users</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="User_Supplier_Datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username/Email Address</th>
                                <th>Supplier</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php foreach ($supplier_users as $supplier) { ?>                               
                                <tr>

                                    <td><?php echo $supplier['firstname']; ?></td>
                                    <td><?php echo $supplier['lastname']; ?></td>
                                    <td><?php echo $supplier['username']; ?></td>
                                    <td><?php echo $supplier['supplier_name']; ?></td>

                                    <?php
                                    if ($supplier['active'] == 1) {
                                        $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $supplier['id'] . '" href="' . base_url('admin_section/disableUser/' . $supplier['id']) . '" data_adminuser_id=' . $supplier['id'] . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                    } else {
                                        $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $supplier['id'] . '" href="' . base_url('admin_section/enableUser/' . $supplier['id']) . '" data_adminuser_id=' . $supplier['id'] . '  title="enable" class="enableadminuser"><i class="fa  fa-play franchises-i"></i></a>Enable</span>';
                                    }
                                    ?>
                                    <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $supplier['id']; ?>" href="#edit_supplier_user" title="Edit" data_firstname="<?php echo $supplier['firstname']; ?>" data_lastname=" <?php echo $supplier['lastname']; ?>" data_username="<?php echo $supplier['username']; ?>" data_suppliername="<?php echo $supplier['supplierid']; ?>"  data_adminuser_id="<?php echo $supplier['id']; ?>"  class="edit_supplier"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><span class="action-w"><a data-toggle="modal"   id="changepassword_id_<?php echo $supplier['id']; ?>" href="#change_password_model" data_adminuser_id="<?php echo $supplier['id']; ?>" class="change_password_model"  title="Password"><i class="fa fa-lock franchises-i"></i></a>Password</span><span class="action-w"><a data-toggle="modal"   id="userlogs_id_<?php echo $val['id']; ?>" href="<?php echo base_url('reports/createPdf/userActivity/' . $val['id']); ?>" data_adminuser_id="<?php echo $supplier['id']; ?>" class="userlog"  title="User Logs"><i class="fa fa-file-pdf-o franchises-i"></i></a>User Logs</span><?php echo $access_icon; ?><span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('admin_section/archiveUser/' . $supplier['id']); ?>"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Modal For Add User -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_user" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add User</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_user'); ?>" method="post" id="add_user_account">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>First Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="first_name" id="first_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>UserName / Email Address :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter UserName" class="form-control" name="username" id="username">
                            <div id="username_error" class="username_error hide">Username Is Already Exist.</div> 
                        </div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Enter Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="contact_password" id="contact_password" type="password"> <div class="result"></div></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="confirm_password" id="confirm_password" type="password"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Access Level : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="access_level" id="access_level" class="form-control">
                                <option value="">-----select------</option>
                                <?php foreach ($access_level as $level) { ?>
                                    <option value="<?php echo $level->id; ?>"><?php echo $level->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add To Owner List : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="add_owner" id="add_owner" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Push Notification : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="checkbox" name="my-checkbox" data-size="mini" class="form-control" checked>
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

<!-- Model For Change Password -->

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="change_password_model" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Change Password</h4>
            </div>

            <form action="<?php echo base_url('admin_section/edit_user'); ?>" method="POST" id="updatepassword">
                <div class="modal-body">


                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Password :</label> </div>
                        <div class="col-md-6">  <input type="password" placeholder="Enter New Password" class="form-control" name="new_password" id="new_password"><div class="result"></div>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Confirm Password :</label> </div> 

                        <div class="col-md-6">  <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirm_newpassword" id="confirm_newpassword"></div>
                    </div> <!-- /.form-group -->


                    <input type="hidden" name="adminuser_id" id="adminuser_id_1"/>
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



<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_user" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit User</h4>
            </div>

            <form action="<?php echo base_url('admin_section/editUser'); ?>" method="post" id="edit_user_account">
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
                        <div class="col-md-6">         <label>Username :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter UserName" class="form-control" name="edit_username" id="edit_username">
                            <div id="edit_username_error" class="username_error hide">Username Is Already Exist.</div> 
                        </div>

                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Access Level : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_access_level" id="edit_access_level" class="form-control">
                                <option>-----select------</option>
                                <?php foreach ($access_level as $level) { ?>
                                    <option value="<?php echo $level->id; ?>"><?php echo $level->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add To Owner List : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_add_owner" id="add_owner" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Push Notification : </label>
                        </div>
                        <div class="col-md-6" id="ch">       
                            <input type="checkbox" checked="" data-size="mini" class="form-control myCheckbox" name="my-checkbox" id="edit_notify">
                        </div>
                    </div>

                    <input type="hidden" name="adminuser_id" id="adminuser_id"/>

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

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_multiple_user_model" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Multiple User</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_multipleusers'); ?>" method="POST" id="multiple_users">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Add Multiple User :</label> </div> 

                        <div class="col-md-6">  
                            <!--<input type="Number" class="form-control" name="number_of_rows" value="1" id="number_of_rows">-->
                            <!--<div class="input-group"><input id="users" type="text" data-min="1" class="form-control bfh-number"></div>-->
                            <input type='button' value='-' class='qtyminus' field='quantity' />
                            <input type='text' name='quantity' class='qty' disabled/>
                            <input type='button' value='+' class='qtyplus' field='quantity' />
                        </div>
                    </div> <!-- /.form-group -->

                    <input type="hidden" name="users" value="1">
                    <table class="table table-striped table-bordered table-hover" id="multiple_user">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username / Email</th>
                                <th>Password</th>
                                <th>Access Level</th>
                                <th>Add to Owner List</th>
                                <th>Push Notification</th>
                            </tr>
                        <tbody>
                            <tr id="row_1">
                                <td><input type="text" name="first_name1" class="form-control" required></td>
                                <td><input type="text" name="last_name1" class="form-control" required></td>
                                <td><input type="text" id="user_name1" name="user_name1" class="form-control" required>
                                    <div id="username_err1" class="username_err1 hide">Username Is Already Exist.</div></td>
                                <td><input type="password" name="mpassword1" class="form-control" required></td>
                                <td><select class="form-control" name="level1" id="multiple_access" required><option value="">-----select------</option>
                                        <?php foreach ($access_level as $level) { ?>
                                            <option value="<?php echo $level->id; ?>"><?php echo $level->name; ?></option>
                                        <?php } ?></select></td>
                                <td><select name="add_owner1" id="add_owner" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select></td>
                                <td><input type="checkbox" name="multiple-notify1" data-size="mini" class="form-control" checked></td>
                            </tr>
                        </tbody>
                        </thead>
                    </table>



                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save">Save</button>
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
                <h4 id="myModalLabel" class="modal-title">Edit Multiple User</h4>
            </div>
            <form action="<?php echo base_url('admin_section/editMultipleUser'); ?>" method="post" id="edit_multipleuser_account">
                <div class="modal-body">
                    <input hidden="" name="user_id" id="multiComIds">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Access Level : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_access_level" id="edit_access_level" class="form-control">
                                <option value="-1">-----select------</option>
                                <?php foreach ($access_level as $level) { ?>
                                    <option value="<?php echo $level->id; ?>"><?php echo $level->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Push Notification : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="checkbox" checked="" data-size="mini" class="form-control myCheckbox" name="notification">
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
<!--add supplier-->


<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_supplier_user" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Supplier User</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_supplier_user'); ?>" method="post" id="supplier_user">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>First Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="supplier_first_name" id="first_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="supplier_last_name" id="last_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>UserName / Email Address :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter UserName" class="form-control" name="supplier_username" id="supplier_username">
                            <div id="supplier_username_error" class="supplier_username_error hide">Username Is Already Exist.</div> 
                        </div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Enter Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="supplier_password" id="supplier_password" type="password"> <div class="result"></div></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="supplier_confirm_password" id="confirm_password" type="password"></div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Supplier</label>
                        </div>
                        <div class="col-md-6"><select name="supplier_id" id="supplier_id" class="form-control">
                                <option value="">Please Select</option>
                                <?php
                                foreach ($arrSuppliers as $supplier) {
                                    echo "<option ";
                                    echo 'value="' . $supplier['supplier_id'] . '" ';
                                    if ($supplier_id == $supplier['supplier_id']) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $supplier['supplier_name'] . "</option>\r\n";
                                }
                                ?>
                            </select></div>
                    </div> 

                </div>



                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="supplier_button_system">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>



<!--Mdel for edit supplier user-->

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_supplier_user" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Supplier User</h4>
            </div>

            <form action="<?php echo base_url('admin_section/editSupplierUser'); ?>" method="post" id="edit_user_account">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="edit_supplier_first_name" id="edit_supplier_first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="edit_supplier_last_name" id="edit_supplier_last_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Username :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter UserName" class="form-control" name="edit_username" id="edit_supplier_username" disabled="">

                        </div>

                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Supplier</label>
                        </div>
                        <div class="col-md-6"><select name="edit_supplier_id" id="edit_supplier_id" class="form-control">
                                <option value="">Please Select</option>
                                <?php
                                foreach ($arrSuppliers as $supplier) {
                                    echo "<option ";
                                    echo 'value="' . $supplier['supplier_id'] . '" ';
                                    if ($supplier_id == $supplier['supplier_id']) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $supplier['supplier_name'] . "</option>\r\n";
                                }
                                ?>
                            </select></div>
                    </div> 

                    <input type="hidden" name="supplieradminuser_id" id="supplieradminuser_id"/>
                    <input type="hidden" name="supplier_user_id" id="supplier_user_id"/>

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