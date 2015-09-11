<!--<script src="<?php echo base_url() . '/assets/js/bootstrap-formhelpers.min.js'; ?>"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.js"></script>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>

<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<style>
    .modal-body{
        min-height: 100px;
        max-height: 595px; 
        overflow-y: scroll;
    } 
    .glyphicon-chevron-up
    {
        cursor: pointer;
    }
    .glyphicon-chevron-down
    {
        cursor: pointer;
    }
    #contract_start
    {
        width: 200px;
    }
    #contract_end
    {
        width: 200px;
    }
    #contractstart
    {
        width: 200px;
    }
    #contractend
    {
        width: 200px;
    }
    .refnum_error
    {
        color: red;
        font-weight: bold;
    }
    .refnumerror {
        color: red;
        font-weight: bold;
    }
    #service_typeerr
    {
        display:none;
        color:red;
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

//        var url = $('#base_url').val();
        $(function() {
            $(".datepicker").datepicker({dateFormat: "mm/dd/yy"});
        });

        var rowCount = 1;

        $('.inc').on('click', function()
        {
            rowCount++;
            var recRow = '<tr id="row_' + rowCount + '"><td><input name="" class="form-control" type="text"/></td><td><input name="" class="form-control" type="text"/></td><td><input name="" type="text" class="form-control"/></td><td><input name="" class="form-control" type="password"/></td><td><select><option>A</option></select></td><td><select><option>A</option></select></td></tr>';
            $('#multiple_user tbody').append(recRow);
        });
        $('.dec').on('click', function()
        {
            var removeuser = $('#multiple_user tr:last').attr('id');
            var user = removeuser.split('_');
            $('#row_' + user[1]).remove();
        });

        $("#addsupplier").submit(function(event) {
            // var valDDL = $(this).val();  
            //event.preventDefault();
            var service_type = $('#service_type option:selected').val();
            if (service_type == "")
            {
                event.preventDefault();
                $('#service_typeerr').css('display', 'block');
            }
            else
            {
                $('#service_typeerr').css('display', 'none');
            }

        });

        $('#service_type').on('change', function() {
            var type = $(this).val();
            if (type == 'service')
            {
                $("#support").show();
                $("#service").show();
                $("#contract").show();
                $("#address").show();


                $("#addsupplier").validate({
                    rules: {
                        supplier_name: "required",
                        service_type: "required",
                        support_number: {
                            require_from_group: [1, ".js-product"]
                        },
                        support_email: {
                            require_from_group: [1, ".js-product"],
                            email: true
                        },
                        service_level: "required",
                        postcode: {required: true,
                            digits: true,
                            minlength: 4,
                            maxlength: 4}
                    },
                    messages: {
                        supplier_name: "Please Enter Company Name",
                        service_type: "Please Select Any Type",
                        contract_number: "Please Enter Support Number",
                        support_email: {
                            required: "Please Enter Support Email",
                            email: "Please Enter Valid Email Address"
                        },
                        service_level: "Please Select Any Service Level"
                    }
                });
            }

            if (type == 'supplier')
            {
                $("#support").show();
                $("#service").hide();
                $("#contract").show();
                $("#address").show();

                $("#addsupplier").validate({
                    rules: {
                        supplier_name: "required",
                        service_type: "required",
                        support_number: {
                            require_from_group: [1, ".js-product"]
                        },
                        support_email: {
                            require_from_group: [1, ".js-product"],
                            email: true
                        },
                        postcode: {required: true,
                            digits: true,
                            minlength: 4,
                            maxlength: 4}
                    },
                    messages: {
                        supplier_name: "Please Enter Company Name",
                        service_type: "Please Select Any Type",
                        support_number: "Please Enter Support Number",
                        support_email: {
                            required: "Please Enter Support Email",
                            email: "Please Enter Valid Email Address"
                        }

                    }
                });
            }
            if (type == 'customer')
            {
                $("#support").hide();
                $("#service").hide();
                $("#contract").show();
                $("#address").show();

                $("#addsupplier").validate({
                    rules: {
                        supplier_name: "required",
                        service_type: "required",
                        contract_number: "required",
                        contract_email: {required: true,
                            email: true},
                        postcode: {
                            digits: true,
                            minlength: 4,
                            maxlength: 4}
                    },
                    messages: {
                        supplier_name: "Please Enter Company Name",
                        service_type: "Please Select Any Type",
                        contract_number: "Please Enter Contact Number",
                        contract_email: {
                            required: "Please Enter Contact Email",
                            email: "Please Enter Valid Email Address"
                        }

                    }
                });
            }
        });

        var supplier = $("#admin_supplier").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnRowCallback": function(nRow, aData) {

                var $nRow = $(nRow); // cache the row wrapped up in jQuery
                tdhtm = $nRow.children()[16].innerHTML;

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
                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [9]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [10]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [11]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [12]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [13]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [14]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [15]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [16]}
            ]}
        );

        // script for edit user
        $("body").on("click", ".edit", function() {

            var supplier_id = $(this).attr("data_adminuser_id");
            var base_url = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url + "index.php/admin_section/getsupplierdata/" + supplier_id,
                success: function(data) {

                    var supplier = $.parseJSON(data);
                    if (supplier.contract_startdate > 0)
                    {

                        var start_date = convert_to_date(supplier.contract_startdate);
                        var end_date = convert_to_date(supplier.contract_enddate);

                        var start_date = supplier.contract_startdate;
                        var end_date = supplier.contract_enddate;

                    }
                    else
                    {
                        var start_date = '';
                        var end_date = '';
                    }

                    $('#editsupplier_name').val(supplier.supplier_name);
                    $('#refcode').val(supplier.ref_no);
                    $('#supportemail').val(supplier.support_email);
                    $('#supportnumber').val(supplier.support_number);
                    $("#servicetype option[value='" + supplier.type + "']").prop("selected", "selected");
                    $('#servicetype').attr('disabled', true);
                    $("#servicelevel option[value='" + supplier.service_level + "']").prop("selected", "selected");
                    $("#res option[value='" + supplier.response + "']").prop("selected", "selected");
                    $('#contractstart').val(start_date);
                    $('#contractend').val(end_date);
                    $('#contractname').val(supplier.contract_name);
                    $('#contracttitle').val(supplier.supplier_title);
                    $('#contractnumber').val(supplier.contract_no);
                    $('#contractemail').val(supplier.contract_email);
                    $('#contractadd').val(supplier.supplier_address);
                    $('#contractcity').val(supplier.supplier_city);
                    $("#contractstate option[value='" + supplier.supplier_state + "']").prop("selected", "selected");
                    $('#contractpcode').val(supplier.supplier_postcode);
                    $("#adminuser_id_1").attr("value", supplier_id);
                }
            });
        });

        $("#ref_code").on("keyup blur", function() {

            var ref_number = $("#ref_code").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkrefnumber",
                data: {
                    'ref_no': ref_number
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#refno_error").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#refno_error").addClass("hide");
                    }
                }

            });

        });
        $("#refcode").on("keyup blur", function() {

            var ref_number = $("#refcode").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkrefnumber",
                data: {
                    'ref_no': ref_number
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#savebutton").addClass('disabled');
                        $("#refnoerror").removeClass("hide");
                    } else {
                        $("#savebutton").removeClass('disabled');
                        $("#refnoerror").addClass("hide");
                    }
                }

            });

        });

    });
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this Supplier ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
    function convert_to_date(timestamp)
    {
        var jsDate = new Date(timestamp * 1000);
        var day = jsDate.getDate();
        var month = jsDate.getMonth();
        var year = jsDate.getFullYear();

        month += 1;
        month = ("0" + month).slice(-2);
        day = ("0" + day).slice(-2);
        var converted_date = month + '/' + day + '/' + year;
        return converted_date;
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> Supplier</h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li ><a data-toggle="" href="<?php echo base_url('admin_section/admin_user'); ?>">Users</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url("admin_section/admin_owner"); ?>">Owners</a>
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
                    <li class="active"><a data-toggle="" href="<?php echo base_url('admin_section/admin_supplier'); ?>">Suppliers/Customers</a>
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
        <h1 class="page-header"><?php
            $arrPageData['arrSessionData'] = $this->session->userdata;
            echo $arrPageData['arrSessionData']['objSystemUser']->accountname;
            ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-7">



                    <a  href="<?= base_url('admin_section/exportPDFForSupplier/CSV') ?>" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>CSV</b>
                    </a>


                    <a  href="<?= base_url('admin_section/exportPDFForSupplier/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>PDF</b></a>

                    <a class="button icon-with-text round" id="add_supplier_data" data-target="#add_supplier" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add <br>Supplier</b></a>

                    <!--                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button style="margin-left:300px;" class="btn btn-primary btn-xs" type="button" id="add_supplier_data" data-target="#add_supplier" data-toggle="modal"><i class="fa fa-plus-circle"></i>
                                                    <b>Add Supplier</b></button>-->

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
                    <table id="admin_supplier" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Type</th>
                                <th>Ref No</th>
                                <th>Support Email</th>
                                <th>Support Number</th>
                                <th>Service Level</th>
                                <th>Response</th>
                                <th>Contact Name</th>
                                <th>Contact Email</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Postcode</th>
                                <th>Contract Start Date</th>
                                <th>Contract End Date</th>
                                <th style="width: 8%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php foreach ($suppliers as $supplier) {
                                ?>
                                <tr>
                                    <td><?php echo $supplier['supplier_name']; ?></td>
                                    <td><?php echo $supplier['type']; ?></td>
                                    <td><?php echo $supplier['ref_no']; ?></td>
                                    <td><?php echo $supplier['support_email']; ?></td>
                                    <td><?php echo $supplier['support_number']; ?></td>
                                    <td><?php echo $supplier['service_level']; ?></td>
                                    <td><?php echo $supplier['response']; ?></td>
                                    <td><?php echo $supplier['contract_name']; ?></td>
                                    <td><?php echo $supplier['contract_email']; ?></td>
                                    <td><?php echo $supplier['contract_no']; ?></td>
                                    <td><?php echo $supplier['supplier_address']; ?></td>
                                    <td><?php echo $supplier['supplier_city']; ?></td>
                                    <td><?php echo $supplier['supplier_state']; ?></td>
                                    <td><?php echo $supplier['supplier_postcode']; ?></td>
                                    <td><?php
                                        if ($supplier['contract_startdate'] > 0) {
                                            echo date('d/m/Y', $supplier['contract_startdate']);
                                        } else {
                                            echo '';
                                        }
                                        ?></td>
                                    <td><?php
                                        if ($supplier['contract_enddate'] > 0) {
                                            echo date('d/m/Y', $supplier['contract_enddate']);
                                        } else {
                                            echo '';
                                        }
                                        ?></td>
                                    <?php
                                    if ($supplier['active'] == 1) {
                                        $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $supplier['supplier_id'] . '" href="' . base_url('admin_section/disableSupplier/' . $supplier['supplier_id']) . '" data_adminuser_id=' . $supplier['supplier_id'] . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                    } else {
                                        $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $supplier['supplier_id'] . '" href="' . base_url('admin_section/enableSupplier/' . $supplier['supplier_id']) . '" data_adminuser_id=' . $supplier['supplier_id'] . '  title="enable" class="enableadminuser"><i class="fa  fa-play franchises-i"></i></a>Enable</span>';
                                    }
                                    ?>
                                    <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $supplier['supplier_id']; ?>" href="#edit_supplier" title="Edit" data_adminuser_id="<?php echo $supplier['supplier_id']; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><?php echo $access_icon; ?><span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('admin_section/archiveSupplier/' . $supplier['supplier_id']); ?>"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span></td>
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
<!-- Modal For Add Supplier -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_supplier" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Supplier</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_supplier'); ?>" method="post" id="addsupplier">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="supplier_name" id="supplier_name" type="text"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Type :</label> </div>
                        <div class="col-md-6">       
                            <select name="service_type" id="service_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="service">Service/Maintenance</option>
                                <option value="supplier">Supplier</option>
                                <option value="customer">Customer</option>

                            </select>
                            <span id="service_typeerr">Please Select Service Type</span>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Ref No :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Ref Code" class="form-control" name="ref_code" id="ref_code">
                            <div id="refno_error" class="refnum_error hide">Ref Number Already Exist.</div>
                        </div>

                    </div>
                    <div id="support">
                        <!-- /.form-group -->
                        <div class="form-group col-md-12">
                            <div class="col-md-6">          <label>Support Email :</label>
                            </div>
                            <div class="col-md-6"><input placeholder="Enter Email" class="form-control js-product" name="support_email" id="support_email" type="email"> <div class="result"></div></div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6">      <label>Support Number:</label>
                            </div>
                            <div class="col-md-6">  <input placeholder="Enter Number" class="form-control js-product" name="support_number" id="support_number" type="text"></div>
                        </div> 
                        <div class="form-group col-md-12">
                        </div> 
                        <div class="form-group col-md-12">
                        </div>
                    </div>



                    <div id="service">
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Service Level : </label>
                            </div>
                            <div class="col-md-6">       
                                <select name="service_level" id="service_level" class="form-control">
                                    <option value="N/A">N/A</option>
                                    <option value="Mon-Fri 9-5">Mon-Fri 9-5</option>
                                    <option value="Mon-Fri 8-8">Mon-Fri 8-8</option>
                                    <option value="Mon-Sun 9-5">Mon-Sun 9-5</option>
                                    <option value="24/7/365">24/7/365</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Response : </label>
                            </div>
                            <div class="col-md-6">       
                                <select name="response" id="response" class="form-control">
                                    <option value="best_efforts">Best Efforts</option>
                                    <option value="days">7 Days</option>
                                    <option value="working_day">Next Working Day</option>
                                    <option value="next_day">Next Day</option>
                                    <option value="same_day">Same Days</option>
                                    <option value="8h">8 Hours</option>
                                    <option value="4h">4 Hours</option>
                                    <option value="1h">1 Hours</option>
                                </select>
                            </div>
                        </div> 

                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contract Start Date : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contract Start Date" class="form-control col-md-6 datepicker" name="contract_start" id="contract_start">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contract End Date : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contract End Date" class="form-control col-md-6 datepicker" name="contract_end" id="contract_end">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                        </div>
                        <div class="form-group col-md-12">
                        </div>
                    </div>





                    <div id="contract">
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Name : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contact Name " class="form-control" id="contact_name" name="contract_name">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Job Title : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contact Title " class="form-control" name="contract_title">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Number : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contact Number"  class="form-control" id="contact_number" name="contract_number">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Email : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="email" placeholder="Enter Contact Email" class="form-control" id="contact_email" name="contract_email">

                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                        </div>
                        <div class="form-group col-md-12">
                        </div>
                    </div>




                    <div id="address">
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Address : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" class="form-control" name="address" placeholder="Address">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>City : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" class="form-control" name="city" placeholder="City" >
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>State : </label>
                            </div>
                            <div class="col-md-6"> 
                                <select class="form-control" name="state">
                                    <option value="NSW">NSW</option>
                                    <option value="VIC">VIC</option>
                                    <option value="QLD">QLD</option>
                                    <option value="SA">SA</option>
                                    <option value="TAS">TAS</option>
                                    <option value="WA">WA</option>
                                    <option value="NT">NT</option>
                                    <option value="ACT">ACT</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Postcode : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" class="form-control" name="postcode" placeholder="Postcode">
                            </div>
                        </div>   

                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                </div></div>
        </form>
    </div>

</div>
<!-- /.modal-dialog -->

<!-- Modal For Edit Supplier -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_supplier" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Supplier</h4>
            </div>

            <form action="<?php echo base_url('admin_section/edit_supplier'); ?>" method="post" id="editsupplier">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="editsupplier_name" id="editsupplier_name" type="text"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Type :</label> </div>
                        <div class="col-md-6">       
                            <select name="editservice_type" id="servicetype" class="form-control">

                                <option value="service">Service/Maintenance</option>
                                <option value="supplier">Supplier</option>
                                <option value="customer">Customer</option>

                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Ref No :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Ref Code" class="form-control" name="editref_code" id="refcode">
                            <div class="refnumerror hide" id="refnoerror">Ref Number Already Exist.</div>
                        </div>

                    </div>
                    <div id="support">
                        <!-- /.form-group -->
                        <div class="form-group col-md-12">
                            <div class="col-md-6">          <label>Support Email :</label>
                            </div>
                            <div class="col-md-6"><input placeholder="Enter Email" class="form-control" name="editsupport_email" id="supportemail" type="email"> <div class="result"></div></div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6">      <label>Support Number:</label>
                            </div>
                            <div class="col-md-6">  <input placeholder="Enter Number" class="form-control" name="editsupport_number" id="supportnumber" type="text"></div>
                        </div> 
                        <div class="form-group col-md-12">
                        </div> 
                        <div class="form-group col-md-12">
                        </div>
                    </div>



                    <div id="service">
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Service Level : </label>
                            </div>
                            <div class="col-md-6">       
                                <select name="editservice_level" id="servicelevel" class="form-control">
                                    <option value="N/A">N/A</option>
                                    <option value="Mon-Fri 9-5">Mon-Fri 9-5</option>
                                    <option value="Mon-Fri 8-8">Mon-Fri 8-8</option>
                                    <option value="Mon-Sun 9-5">Mon-Sun 9-5</option>
                                    <option value="24/7/365">24/7/365</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Response : </label>
                            </div>
                            <div class="col-md-6">       
                                <select name="edit_response" id="res" class="form-control">
                                    <option value="best_efforts">Best Efforts</option>
                                    <option value="7 days">7 Days</option>
                                    <option value="Next Working Day">Next Working Day</option>
                                    <option value="Next Day">Next Day</option>
                                    <option value="Same Days">Same Days</option>
                                    <option value="8 Hours">8 Hours</option>
                                    <option value="4 Hours">4 Hours</option>
                                    <option value="1 Hours">1 Hours</option>
                                </select>
                            </div>
                        </div> 

                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contract Start Date : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contract Start Date" class="form-control col-md-6 datepicker" name="editcontract_start" id="contractstart">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contract End Date : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contract End Date" class="form-control col-md-6 datepicker" name="editcontract_end" id="contractend">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                        </div>
                        <div class="form-group col-md-12">
                        </div>
                    </div>





                    <div id="contract">
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Name : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contact Name " class="form-control" name="editcontract_name" id="contractname">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Job Title : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contact Title " class="form-control" name="editcontract_title" id="contracttitle">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Number : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contact Number"  class="form-control" name="editcontract_number" id="contractnumber">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Contact Email : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" placeholder="Enter Contact Email" class="form-control" name="editcontract_email" id="contractemail">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                        </div>
                        <div class="form-group col-md-12">
                        </div>
                    </div>




                    <div id="address">
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Address : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" class="form-control" name="edit_address" placeholder="Address" id="contractadd">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>City : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" class="form-control" name="edit_city" placeholder="City" id="contractcity">
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>State : </label>
                            </div>
                            <div class="col-md-6">       
                                <select class="form-control" name="edit_state" placeholder="State" id="contractstate">
                                    <option value="NSW">NSW</option>
                                    <option value="VIC">VIC</option>
                                    <option value="QLD">QLD</option>
                                    <option value="SA">SA</option>
                                    <option value="TAS">TAS</option>
                                    <option value="WA">WA</option>
                                    <option value="NT">NT</option>
                                    <option value="ACT">ACT</option>  
                                </select>
                            </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label>Postcode : </label>
                            </div>
                            <div class="col-md-6">       
                                <input type="text" class="form-control" name="edit_postcode" placeholder="Postcode" id="contractpcode">
                            </div>
                        </div>   

                    </div>
                    <input type="hidden" name="adminuser_id" id="adminuser_id_1"/>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="savebutton">Update</button>
                </div></div>
        </form>
    </div>

</div>
<!-- /.modal-dialog -->


