<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<div class="box">
    <div class="heading">
        <h1>Welcome, <?php echo $arrSessionData['objAdminUser']->nickname; ?></h1>
        <div class="buttons">
        </div>
    </div>


</div>
<style>
    .modal-body{
        height: 595px;
        overflow-y: scroll;
    } 

    .acc_detail{
        min-height: 70px;
        max-height: 250px;
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
</style>
<script>

    $(document).ready(function() {


        $("#last_name").on("blur", function() {
            var firstname = $("#first_name").val();
            var lastname = $("#last_name").val();

            $("#contact_name").val(firstname + ' ' + lastname);
        });
        $("#username").on("blur", function() {
            var username = $("#username").val();

            $("#support_email").val(username);
        });

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

        $("#create_customer_account").click(function() {

            $(".result").empty();
        })
        // Generate Reference Qr code for account
//       $("body").on("click", "#create_customer_account", function () {
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

    });


</script>

<div class="row">
    <div class="col-lg-6">

        <h1 class="page-header">Dashboard</h1>

    </div>


    <div class="col-lg-6" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <button class="btn btn-primary btn-xs icon-with-text" type="button" id="create_customer_account" data-target="#add_customer_ac" data-toggle="modal"><i class="fa fa-user"></i>
                    <b>Create Customer AC</b></button> 
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
?>
<?php
if ($this->session->flashdata('error')) {
    ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php
}
if ($this->session->flashdata('arrCourier')) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('arrCourier'); ?>
    </div>
    <?php
}
?> 
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                SYSTEM SUMMARY
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="summary" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr> 
                                    <td>Number Of Live Account</td>
                                    <td><?php
                                        if (isset($summary[0]['live'])) {
                                            echo $summary[0]['live'];
                                        }
                                        ?></td>
                                </tr>
                                <tr> 
                                    <td>Number Of Disabled Account</td>
                                    <td><?php
                                        if (isset($summary[0]['disable'])) {
                                            echo $summary[0]['disable'];
                                        }
                                        ?></td>
                                </tr>
                            </tbody>
                        </table></div>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Recently Added Accounts
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Package Type</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($accounts as $acc) {
    ?>
                                <tr>
                                    <td><?php echo $acc->company_name; ?></td>
                                    <td><?php echo $acc->name . ' ' . $acc->item_limit; ?></td>
                                </tr>
<?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
</div>
<div class="row">
    <div class="col-lg-6 acc_detail">
        <div class="panel panel-default">
            <div class="panel-heading">
                CUSTOMERS NEAR ASSET LIMIT
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="acc_detail" class="table table-striped table-bordered table-hover">
                        <thead>

                            <tr>
                                <th>Customer Name</th>
                                <th>Package Type</th>
                                <th>Assets Used</th>
                                <th>Asset Left</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($arrAccounts['results'] As $customer_detail) {     
//     if((($customer_detail->item_limit - $customer_detail->noOfAsset)*10/$customer_detail->item_limit)==5){
    ?>

                                <tr> 
                                    <td><?php echo $customer_detail->company_name; ?></td>
                                    <td><?php echo $customer_detail->package_name; ?></td>
                                    <td><?php echo $customer_detail->noOfAsset; ?></td>
                                    <td><?php echo ($customer_detail->item_limit - $customer_detail->noOfAsset); ?></td>

                                </tr>
    <?php
//}

     }
?>

                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
</div>
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


                        <div class="col-md-6"> <select class="form-control" name="company_state" id="company_state"> 
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
                        <div class="col-md-6"> <label>Compliance Modules: </label>
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
                    <button class="btn btn-primary" type="submit" id="save_button" value="1" name="accountbydshboard">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>