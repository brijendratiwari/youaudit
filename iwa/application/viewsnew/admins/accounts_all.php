<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        height: 595px;
        overflow-y: scroll;
    } 
    .qrcode_error
    {
        color: red;
        font-weight: bold;
    }
    .qrcode_limit
    {
        color: red;
        font-weight: bold;
    }
    .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        height: 75px;
        overflow-y: auto !important;
    }
</style>
<script>

    $(document).ready(function() {
        $("#add_customer_account").validate({
            rules: {
                company_name: "required",
                comapany_address: "required",
                company_city: "required",
                company_state: "required",
                contact_name: "required",
                contact_phone: {
//                    required: true,
                    digits: true
                },
                company_postcode:
                        {
                            required: true,
                            digits: true
                        },
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
                support_email: {
                    required: true,
                    email: true
                },
                qr_refcode: {
                    required: true
//                    rangelength: [3, 4]
                },
                package_type: {
                    required: true,
                    min: 1
                },
                annual_value: {
                    required: true,
                    digits: true
                },
            },
            messages: {
                company_name: "Please Enter Company Name",
                comapany_address: "Please Enter Comapany Address",
                company_city: "Please Enter Company City",
                company_state: "Please Enter Company State",
                company_postcode: {
                    required: "Please Enter Company Postcode",
                    digits: "Please Enter Valid Postal Code"
                },
                first_name: "Please Enter First Name",
                last_name: "Please Enter Last Name",
                username: {
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
                support_email:
                        {
                            required: "Please Enter Email",
                            email: "Please Enter Valid Email"
                        },
                qr_refcode: {
                    required: "Please Enter QR Code"
//                    rangelength: "Please enter a value between 3 and 4 characters long."
                },
                annual_value: {
                    required: "Please Enter Amount",
                    digits: "Please Enter Valid Amount"
                },
            }
        });

        // validation for Edit form 
        $("#edit_customer_account_form").validate({
            rules: {
                edit_company_name: "required",
                edit_comapany_address: "required",
                edit_company_city: "required",
                edit_company_state: "required",
                edit_company_phone: {
                    required: true,
                    digits: true
                },
                edit_contact_name: "required",
                edit_company_postcode:
                        {
                            required: true,
                            digits: true,
                        },
                edit_first_name: "required",
                edit_last_name: "required",
                edit_username: "required",
                edit_confirm_password: {
                    equalTo: "#edit_contact_password"
                },
                edit_support_email: {
                    required: true,
                    email: true
                },
                edit_annual_value: {
                    required: true,
                    digits: true,
                },
            },
            messages: {
                edit_company_name: "Please Enter Company Name",
                edit_comapany_address: "Please Enter Comapany Address",
                edit_company_city: "Please Enter Company City",
                edit_company_state: "Please Enter Company State",
                edit_company_postcode: {
                    required: "Please Enter Company Postcode",
                    digits: "Please Enter Valid Postal Code"
                },
                edit_first_name: "Please Enter First Name",
                edit_last_name: "Please Enter Last Name",
                edit_username: "Please Enter UserName",
                edit_confirm_password: {
                    equalTo: "Password Is Not Matching"
                },
                edit_support_email:
                        {
                            required: "Please Enter Email",
                            email: "Please Enter Valid Email"
                        },
                edit_package_type: {
                    required: true,
                    min: 1
                },
                edit_annual_value: {
                    required: "Please Enter Amount",
                    digits: "Please Enter Valid Amount",
                },
            }
        });


        $("#username").on("keyup blur", function() {

            var username = $("#username").val();


            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admins/check_masterusername",
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


        $("#edit_contact_username").on("blur", function() {

            var username = $("#edit_contact_username").val();
            var checkname = $("#check_username").val();

            var base_url_str = $("#base_url").val();
            if (username != checkname) {
                $.ajax({
                    type: "POST",
                    url: base_url_str + "admins/edit_check_masterusername",
                    data: {
                        'username': username
                    },
                    success: function(msg) {

                        // we need to check if the value is the same
                        if (msg == "1") {
                            //Receiving the result of search here
                            $("#edit_button").addClass('disabled');
                            $("#edit_username_error").removeClass("hide");
                        } else {
                            $("#edit_button").removeClass('disabled');
                            $("#edit_username_error").addClass("hide");
                        }
                    }

                });
            }
        });


// Generate Reference Qr code for account
//        $("body").on("click", "#create_customer_account", function () {
//            var base_url_str = $("#base_url").val();
//            $.ajax({
//                type: "POST",
//                url: base_url_str + "admins/generateRandomString",
//                success: function (msg) {
//                    $('#qr_refcode').attr('value', msg);
//                    $('#qr_refcode_hidden').attr('value', msg);
//
////                    alert(msg);
//                }
//
//            });
//        });

        $("body").on("click", ".edit_customer_data", function() {

            $(".result").empty();
            var base_url_str = $("#base_url").val();
            var customer_id = $(this).attr("customer_acc_id");

            $.ajax({
                type: "POST",
                url: base_url_str + "admins/get_edit_customerdata/" + customer_id,
                success: function(data) {
                    var customerdata = $.parseJSON(data);


                    $('#edit_customer_id').val(customerdata.id);
                    $('#check_username').val(customerdata.contact_email);
                    $('#edit_company_name').val(customerdata.name);
                    $('#edit_comapany_address').val(customerdata.address);
                    $('#edit_company_city').val(customerdata.city);
                    $('#edit_company_state').val(customerdata.state);
                    $('#edit_company_postcode').val(customerdata.postcode);
                    $('#edit_first_name').val(customerdata.firstname);
                    $('#edit_last_name').val(customerdata.lastname);
                    $('#edit_contact_username').val(customerdata.contact_email);
                    $('#edit_contact_name').val(customerdata.contact_name);
                    $('#edit_contact_phone').val(customerdata.contact_number);
                    $('#edit_verify_package').val(customerdata.verified);
                    $('#edit_annual_value').val(customerdata.annual_value);
                    $('#edit_support_email').val(customerdata.support_email);
                    $('#edit_qr_refcode').val(customerdata.qr_refcode);
                    $('#edit_qr_refcode_hidden').val(customerdata.qr_refcode);
                    $('#edit_compliance_module').val(customerdata.compliance);
                    $('#edit_fleet_module').val(customerdata.fleet);
                    $('#edit_condition_module').val(customerdata.condition_module);
                    $('#edit_depreciation_module').val(customerdata.depereciation_module);
                    $('#edit_reporting_module').val(customerdata.reporting_module);
                    $('#edit_profile').val(customerdata.profile);
                    $('#edit_package_type').val(customerdata.package_id);
                    $('#edit_profile').attr('disabled', true);
                    $('#edit_contact_username').val(customerdata.contact_email);
                    $('#edit_package_type').val(customerdata.package_id);
                    $('#edit_add_owner').val(customerdata.add_owner);
                }

            });
        });

        $("#create_customer_account").click(function() {

            $(".result").empty();
        });

        var mcustomer_table = $("#MasterCustomer_Datatable").DataTable({
//        "oLanguage": {
            // "sProcessing": "<div align='center'><img src='" + base_url_str + "/assets/img/ajax-loader.gif'></div>"},
            "ordering": true,
            //"bProcessing": true,
            //"bServerSide": true,
            //"sAjaxSource": base_url_str + "admins/viewAccounts/" + account_id, "bDeferRender": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnDrawCallback": function() {
                var api = this.api();
                $(api.column(7).footer()).html(
                        api.column(7, {page: 'current'}).data().sum()
                        );
                $(api.column(8).footer()).html(
                        api.column(8, {page: 'current'}).data().sum()
                        );
                $(api.column(15).footer()).html(
                        api.column(15, {page: 'current'}).data().sum()
                        );


            },
            "fnRowCallback": function(nRow, aData) {

                var $nRow = $(nRow); // cache the row wrapped up in jQuery
                tdhtm = $nRow.children()[16].innerHTML;

                if (tdhtm.search("enable") != -1) {
                    $nRow.css("background-color", "#f2b4b4");
                }

                return nRow;
            },
            "aoColumnDefs": [
                {"bSortable": true, "aTargets": [0]},
                {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [10]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [11]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [12]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [13]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [14]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [15]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [16]}
            ]}
        );
        $("body").on("change", "#states", function() {
            mcustomer_table.column(3)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#acc_package", function() {
            mcustomer_table.column(6)
                    .search(this.value)
                    .draw();
        });


        $("#last_name").on("blur", function() {
            var firstname = $("#first_name").val();
            var lastname = $("#last_name").val();

            $("#contact_name").val(firstname + ' ' + lastname);
        });
        $("#username").on("blur", function() {
            var username = $("#username").val();

            $("#support_email").val(username);
        });

        $("body").on("change", "#package_type", function() {

            var base_url = $("#base_url").val();
            var package_id = $("#package_type option:selected").val();
            if (package_id != '') {
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/youaudit/youaudit_admins/package_limit/" + package_id,
                    success: function(data) {
                        var package = $.parseJSON(data);
                        $('#annual_value').val(package[0].item_limit);
                        $(document).find('#compliance_module option[value="' + package[0].compliance_module + '"]').prop('selected', true);
                        $(document).find('#fleet_module option[value="' + package[0].fleet_module + '"]').prop('selected', true);
                        $(document).find('#condition_module option[value="' + package[0].conditionmodule + '"]').prop('selected', true);
                        $(document).find('#depreciation_module option[value="' + package[0].depreciation + '"]').prop('selected', true);
                        $(document).find('#reporting_module option[value="' + package[0].reporting + '"]').prop('selected', true);
                    }
                });
            }
        });

        $("body").on("change", "#edit_package_type", function() {

            var base_url = $("#base_url").val();
            var package_id = $("#edit_package_type option:selected").val();
            if (package_id != '') {
                $.ajax({
                    type: "POST",
                    url: base_url + "index.php/youaudit/youaudit_admins/package_limit/" + package_id,
                    success: function(data) {
                        var package = $.parseJSON(data);
                        $('#edit_annual_value').val(package[0].item_limit);
                        $(document).find('#edit_compliance_module option[value="' + package[0].compliance_module + '"]').prop('selected', true);
                        $(document).find('#edit_fleet_module option[value="' + package[0].fleet_module + '"]').prop('selected', true);
                        $(document).find('#edit_condition_module option[value="' + package[0].conditionmodule + '"]').prop('selected', true);
                        $(document).find('#edit_depreciation_module option[value="' + package[0].depreciation + '"]').prop('selected', true);
                        $(document).find('#edit_reporting_module option[value="' + package[0].reporting + '"]').prop('selected', true);
                    }
                });
            }
        });
    });

    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this customer account?", function(result) {
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

            $('#MasterCustomer_Datatable').find('input[type="checkbox"]:checked').each(function() {

                ids.push($(this).attr('value'));
            });
            console.log(ids);
            $('#multiComIds').val(ids.join(','));
            $('#multiUserEditModal').modal('show');
        });

// Code To Check Unique Barcode

        $("#qr_refcode_hidden").on("keyup blur", function() {
            var node = $(this);
            node.val(node.val().replace(/[^A-Za-z]/g, ''));
            var bar_code = $("#qr_refcode_hidden").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/franchise_admins/checkQrcode",
                data: {
                    'bar_code': bar_code
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#qrcode_error").removeClass("hide");
                        $("#qrcode_limit").addClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#qrcode_error").addClass("hide");
                        if (bar_code.length == 3 || bar_code.length == 4 || bar_code.length == 0)
                        {
                            $("#qrcode_limit").addClass("hide");
                            $("#save_button").removeClass('disabled');
                        }
                        if ((bar_code.length < 3 && bar_code.length > 0) || bar_code.length > 4)
                        {
                            $("#qrcode_limit").removeClass("hide");
                            $("#save_button").addClass('disabled');
                        }
                    }
                }

            });
        });

    });

</script>

<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header">CUSTOMER LIST</h1>
    </div>
    <div class="col-lg-9" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button class="btn btn-primary btn-xs icon-with-text" type="button" id="create_customer_account" data-target="#add_customer_ac" data-toggle="modal"><i class="fa fa-plus"></i>
                    <b>Create Customer AC</b></button> 

                <a style="margin-left:500px;" href="<?php echo base_url('admins/exportCustomerPdf/PDF'); ?>" class="btn btn-primary btn-xs icon-with-text" type="button" id="b1"><i class="fa  fa-file-pdf-o"></i>
                    <b> Export PDF</b></a>
                <a  class="btn btn-primary btn-xs icon-with-text" href="<?php echo base_url('admins/exportCustomerPdf/CSV'); ?>" type="button"><i class="fa fa-file-word-o"></i>
                    <b>Export CSV</b></a>
            </div>
        </div>
    </div>

    <!-- /.col-lg-12 -->
</div>


<?php
if ($this->session->flashdata('error')) {
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php
}
?>  

<div class="row">
    <div class="col-lg-12">
        <h5 class="page-header"><?php echo strtoupper($this->session->userdata('ParentAccountName')); ?> / CUSTOMER LIST</h5>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">
            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-customerlist">
                    <table id="MasterCustomer_Datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Customer Name</th>
                                <th>City</th>
                                <th>State</th>
                                <th>PostCode</th>
                                <th>QR Ref Code</th>
                                <th>Account Package</th>
                                <th>Annual Value</th>
                                <th>No of Assets</th>
                                <th>Safety</th>
                                <th>Fleet</th>
                                <th>Condition</th>
                                <th>Depreciation</th>
                                <th>Reporting</th>
                                <th>AC Created Date</th>
                                <th>No of User</th>
                                <th style='width:50%; text-align: center;'>Actions</th>
                            </tr>
                            <tr class="access"> <th class="left"><input type="checkbox" title="Select ALL" id="selectAllchk"><button type="button" id="multiComEditBtn" class="btn btn-warning fade hide" data-toggle="modal" data-target="#multiUserEditModal" style="padding:0 5px;" >Edit</button></th>
                                <th></th>
                                <th></th>
                                <th><select id="states">
                                        <option value=""></option>
                                        <?php
                                        foreach ($options as $opt) {
                                            echo '<option value="' . $opt->state . '">' . $opt->state . '</option>';
                                        }
                                        ?>
                                    </select>  </th>
                                <th></th>
                                <th></th>
                                <th><select id="acc_package">
                                        <option value=""></option>
                                        <?php
                                        foreach ($packages as $pkg) {
                                            echo '<option value="' . $pkg->name . '">' . $pkg->name . '</option>';
                                        }
                                        ?>
                                    </select></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th></tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php
                            foreach ($arrAccounts['results'] As $customer_detail) {
                                ?>
                                <tr>

                                    <td><input type="checkbox" class="multiComSelect" value='<?php echo $customer_detail->customer_id; ?>'><input class="" type="hidden" id="customer_id_<?php echo $customer_detail->customer_id; ?>" value=""></td>
                                    <td><?php echo $customer_detail->company_name; ?></td>
                                    <td><?php echo $customer_detail->city; ?></td>
                                    <td><?php echo $customer_detail->state; ?></td>
                                    <td><?php echo $customer_detail->postcode; ?></td>
                                    <td><?php echo $customer_detail->qr_refcode; ?></td>
                                    <td><?php echo $customer_detail->package_name; ?></td>
                                    <td><?php echo $customer_detail->annual_value; ?></td>
                                    <td><?= $customer_detail->noOfAsset; ?></td>
                                    <td><?php echo ($customer_detail->compliance == 1) ? 'YES' : 'NO'; ?></td>
                                    <td><?php echo ($customer_detail->fleet == 1) ? 'YES' : 'NO'; ?></td>
                                    <td><?php echo ($customer_detail->condition_module == 1) ? 'YES' : 'NO'; ?></td>
                                    <td><?php echo ($customer_detail->depereciation_module == 1) ? 'YES' : 'NO'; ?></td>
                                    <td><?php echo ($customer_detail->reporting_module == 1) ? 'YES' : 'NO'; ?></td>
                                    <td><?php echo date('d/m/Y', $customer_detail->create_date) ?></td>
                                    <td><?= $customer_detail->noOfUser; ?></td>
                                    <td>
                                        <?php
                                        if ($customer_detail->active == 1) {
                                            $active = '<span class="action-w"><a  id="disableuser_id_' . $customer_detail->customer_id . '" href="' . base_url('admins/disableCustomer/' . $customer_detail->customer_id) . '" data_adminuser_id=' . $customer_detail->customer_id . '  title="Disable" class="disableadminuser"><i class="glyphicon glyphicon-pause franchises-i"></i></a>Disable</span>';
                                        } else {
                                            $active = '<span class="action-w"><a  id="enableuser_id_' . $customer_detail->customer_id . '" href="' . base_url('admins/enableCustomer/' . $customer_detail->customer_id) . '" data_adminuser_id=' . $customer_detail->customer_id . '  title="enable" class="enableadminuser"><i class="glyphicon glyphicon-play franchises-i"></i></a>Enable</span>';
                                        }
                                        ?>
                                        <?php echo $active; ?>
                                        <span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('/admins/customerArchive/' . $customer_detail->customer_id . '/'); ?>" title="Archive" class="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span>
                                        <span class="action-w">
                                            <a data-toggle="modal" customer_acc_id="<?php echo $customer_detail->customer_id; ?>" href="#edit_customer_ac" title="Edit" class="edit_customer_data"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span>
                                        <span class="action-w"><a href="<?php echo base_url('/admins/viewusers/' . $customer_detail->customer_id . '/'); ?>" title="View Users" alt="View Users" /><i class="glyphicon glyphicon-download franchises-i"/></i></a>Users</span>

                                    </td>
                                </tr>

                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <th>Total</th>
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
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                        </tfoot>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>

        </div>
    </div>
</div>


<!-- Modal for add master acc -->
<!-- Modal for add master acc -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_customer_ac" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Create Account</h4>
            </div>

            <form action="<?php echo base_url() . 'admins/createAccount' ?>" method="post" id="add_customer_account">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>General Information</label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="company_name" id="company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Address :</label> </div> 
                        <div class="col-md-6">  <input placeholder="Enter Address" class="form-control" name="comapany_address" id="comapany_address"></div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>City : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Company City" class="form-control" name="company_city" id="company_city"></div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>State :</label></div>
                        <div class="col-md-6"> <select class="form-control" name="company_state"> 
                                <option value="NSW">NSW</option>
                                <option value="VIC">VIC</option>
                                <option value="QLD">QLD</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="WA">WA</option>
                                <option value="NT">NT</option>
                                <option value="ACT">ACT</option>
                            </select></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Postcode :</label>
                        </div>
                        <div class="col-md-6"> 
                            <input type="text" placeholder="Enter Company Postcode" class="form-control" name="company_postcode" id="company_postcode">
                        </div>
                    </div> 

                    <!--  Add First User  -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Add First User</label> </div>
                    </div> 
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
                            <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>

                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Contact Name" class="form-control" name="contact_name" id="contact_name">

                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Number:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Phone Number" class="form-control" name="contact_phone" id="contact_phone">

                        </div>

                    </div><!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Enter Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="contact_password" id="contact_password" type="password"><div class="result"></div></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="confirm_password" id="confirm_password" type="password"></div>
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
                        <div class="col-md-6"><label>Master Support Email :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Master Support Email" class="form-control" name="support_email" id="support_email" type="text"></div>
                    </div> 
                    <!-- Add Custom field -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Number of Custom Field :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter No of Cutom Fields" class="form-control" name="custom_count" id="custom_count" type="text"></div>
                    </div> 
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Ref Code</label>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>QR Ref Code:</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control"  name="qr_refcode" id="qr_refcode_hidden" type="text">
                            <div id="qrcode_error" class="qrcode_error hide">QR Code Already Exist.</div>
                            <div id="qrcode_limit" class="qrcode_limit hide">Please enter a value between 3 and 4 characters long.</div>
                            <!--<input class="form-control"  name="qr_refcode" id="qr_refcode" type="hidden" readonly="">-->
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Account Package</label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Package:</label>
                        </div>

                        <div class="col-md-6">       
                            <select name="package_type" id="package_type" class="form-control">
                                <option value="">Please Select Package</option>
                                <?php
                                foreach ($customer_package as $val) {
                                    ?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>  
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Verified: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="verify_package" id="verify_package" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Annual Value: </label>
                        </div>
                        <div class="col-md-6">       
                            <input placeholder="Enter Annual Value" class="form-control" name="annual_value" id="annual_value" type="text"> 
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Additional Modules</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Safety Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="compliance_module" id="compliance_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Fleet Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="fleet_module" id="fleet_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Condition Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="condition_module" id="condition_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Depreciation Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="depreciation_module" id="depreciation_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Reporting Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="reporting_module" id="reporting_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add Profile: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="profile" id="profile" class="form-control">
                                <option value="0">None</option>
                                <?php foreach ($profilelist as $pro) { ?>
                                    <option value="<?php echo $pro->profile_id; ?>"><?php echo $pro->profile_name; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>




                    <input type="hidden" name="masterid" id="master_account_id" value="<?php echo $masterid; ?>" readonly=""/>
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



<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_customer_ac" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Account</h4>
            </div>

            <form action="<?php echo base_url() . 'admins/editCustomer' ?>" method="post" id="edit_customer_account_form">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>General Information</label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="edit_company_name" id="edit_company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Address :</label> </div> 
                        <div class="col-md-6">  <input placeholder="Enter Address" class="form-control" name="edit_comapany_address" id="edit_comapany_address"></div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>City : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Company City" class="form-control" name="edit_company_city" id="edit_company_city"></div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>State :</label></div>

                        <div class="col-md-6"> <select class="form-control" name="edit_company_state" id="edit_company_state"> 
                                <option value="NSW">NSW</option>
                                <option value="VIC">VIC</option>
                                <option value="QLD">QLD</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="WA">WA</option>
                                <option value="NT">NT</option>
                                <option value="ACT">ACT</option>
                            </select></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Postcode :</label>
                        </div>
                        <div class="col-md-6"> 
                            <input type="text" placeholder="Enter Company Postcode" class="form-control" name="edit_company_postcode" id="edit_company_postcode">
                        </div>
                    </div> 

                    <!--  Add First User  -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Add First User</label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>First Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="edit_first_name" id="edit_first_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="edit_last_name" id="edit_last_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>UserName / Email Address :</label> </div>
                        <div class="col-md-6"> 
                            <input placeholder="Enter UserName" class="form-control" name="edit_contact_username" id="edit_contact_username" type="text">
                            <input name="check_username" id="check_username" type="hidden">
                            <div id="edit_username_error" class="username_error hide">Username Already Exist.</div>
                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Contact Name" class="form-control" name="edit_contact_name" id="edit_contact_name">

                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Number:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Phone Number" class="form-control" name="edit_contact_phone" id="edit_contact_phone">

                        </div>

                    </div><!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Enter Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="edit_contact_password" id="edit_contact_password" type="password"><div class="result"></div></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="edit_confirm_password" id="edit_confirm_password" type="password"></div>
                    </div> 


                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add To Owner List : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_add_owner" id="edit_add_owner" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Master Support Email :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Master Support Email" class="form-control" name="edit_support_email" id="edit_support_email" type="text"></div>
                    </div> 
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Ref Code</label>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>QR Ref Code:</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" disabled=""  name="edit_qr_refcode" id="edit_qr_refcode" type="text">
                            <input class="form-control"   name="edit_qr_refcode_hidden" id="edit_qr_refcode_hidden" type="hidden" readonly="">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Account Package</label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Package:</label>
                        </div>

                        <div class="col-md-6">       
                            <select name="edit_package_type" id="edit_package_type" class="form-control">
                                <option value="">Please Select Package</option>
                                <?php
                                foreach ($customer_package as $val) {
                                    ?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>  
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Verified: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_verify_package" id="edit_verify_package" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Annual Value: </label>
                        </div>
                        <div class="col-md-6">       
                            <input placeholder="Enter Annual Value" class="form-control" name="edit_annual_value" id="edit_annual_value" type="text"> 
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Additional Modules</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Safety Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_compliance_module" id="edit_compliance_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Fleet Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_fleet_module" id="edit_fleet_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Condition Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_condition_module" id="edit_condition_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Depreciation Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_depreciation_module" id="edit_depreciation_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Reporting Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_reporting_module" id="edit_reporting_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add Profile: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_profile" id="edit_profile" class="form-control">

                                <option value="0">None</option>
                                <?php foreach ($profilelist as $pro) { ?>
                                    <option value="<?php echo $pro->profile_id; ?>"><?php echo $pro->profile_name; ?></option>

                                <?php } ?>
                            </select>
                        </div>

                        <input type="hidden" id="edit_customer_id" name="edit_customer_id" readonly="">
                    </div>


                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button">Update</button>
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
            <form action="<?php echo base_url('admins/editMultipleAccount'); ?>" method="post" id="edit_multipleuser_account">
                <div class="modal-body" style="height:325px;">
                    <input hidden="" name="account_id" id="multiComIds">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Package:</label>
                        </div>

                        <div class="col-md-6">       
                            <select name="multiple_package_type" id="multiple_package_type" class="form-control">
                                <?php
                                foreach ($customer_package as $val) {
                                    ?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>  
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>



                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Safety Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_compliance_module" id="multiple_compliance_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Fleet Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_fleet_module" id="multiple_fleet_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Condition Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_condition_module" id="multiple_condition_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Depreciation Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_depreciation_module" id="multiple_depreciation_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Reporting Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_reporting_module" id="multiple_reporting_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
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



















