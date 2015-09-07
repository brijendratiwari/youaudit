<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
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
    .page-header2 {
        border-bottom: medium solid #ddd;
        margin: 15px;
    }
    .bootbox .modal-body{
        min-height: 75px;
        overflow: auto !important;
    }
</style>
<script>
    $(document).ready(function() {
        $(".manuitem").addClass('hidden');
        $("#edit_button").click(function() {

            $(".manuitem").removeClass('hidden');
            $(".itemmanu").addClass('hidden');

            $("#add_item_button").prop('disabled', false);
            $(".upload_doc").prop('disabled', false);

            $("#add_manufacturer_button").prop('disabled', false);
        });

        /* Define two custom functions (asc and desc) for string sorting */
        jQuery.fn.dataTableExt.oSort['string-case-asc'] = function(x, y) {
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        };

        jQuery.fn.dataTableExt.oSort['string-case-desc'] = function(x, y) {
            return ((x < y) ? 1 : ((x > y) ? -1 : 0));
        };
        $("#ITEM_Datatable").DataTable({
            "ordering": true,
            "bFilter": true,
            "bSort": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
            "scrollY": "585px",
            "aaSorting": [[0, 'asc', 'desc']],
            "aoColumns": [
                {"sType": 'string-case', "sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
            ]
        });
        $("#MANUFACTURE_Datatable").DataTable({
            "ordering": true,
            "bFilter": true,
            "bSort": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
            "scrollY": "585px",
            "aaSorting": [[0, 'asc', 'desc']],
            "aoColumns": [
                {"sType": 'string-case'},
                null
            ]
        });
    });

    function deleteitem(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to delete this item ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
    function deletemanufacturer(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to delete this manufacturer ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
</script>
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
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>  ITEM </h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li ><a data-toggle="" href="<?php echo base_url('admin_section/admin_user'); ?>">Users</a>
                    </li>
                    <li ><a data-toggle="" href="<?php echo base_url("admin_section/admin_owner"); ?>">Owners</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url("admin_section/admin_categories"); ?>">Categories</a>
                    </li>
                    <li class="active"><a data-toggle="" href="<?php echo base_url('admin_section/'); ?>">Items</a>
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
                <div class="col-lg-12"> 
                    <div class="col-lg-7">
                        <a  href="<?= base_url('admin_section/exportPDFForItem/CSV') ?>" class="button icon-with-text round">
                            <i class="fa  fa-file-pdf-o"></i>
                            <b>Export to CSV</b>
                        </a>
                        <a  href="<?= base_url('admin_section/exportPDFForItem/PDF') ?>" target="blank" class="button icon-with-text round">
                            <i class="fa  fa-file-pdf-o"></i>
                            <b>Export to PDF</b></a>
                        <a  class="button icon-with-text round" id="edit_button"><i class="fa fa-edit"></i><b>Edit</b></a>
                        <a  class="button icon-with-text round" onclick="$('#item_manufacturer').submit();"><i class="fa fa-arrow-circle-down"></i><b>Save</b></a>

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
        </div>
    </div>
</div>

<div class="row">
    <form enctype="multipart/form-data" action="<?php echo base_url() . 'admin_section/editItems_Manu' ?>" method="POST" id="item_manufacturer">
        <div class="col-lg-6"  style="border-right: solid #ddd">
            <h3 class="page-header page-header2" align="center">Items</h3>
            <div class="col-md-12">

                <div class="panel-body" style="width: 100%;">
                    <div class="table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                            <table id="ITEM_Datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Doc</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($getlist)) {
                                        foreach ($getlist['list'] as $val) {
                                            ?>

                                            <tr>
                                                <td><span class="itemmanu"><?php echo strtoupper($val['item_manu_name']); ?></span>
                                                    <input type="hidden" name="item_id[]" value="<?php echo $val['id']; ?>">
                                                    <input class="form-control item manuitem" name='item_name[]' id="item_<?php echo $val['id']; ?>" value="<?php echo $val['item_manu_name']; ?>"></td>
                                                <td>  
                                                    <span class="col-lg-12"><span class="col-lg-8" style="padding: 0">
                                                            <span class="file-select">choose file <i class="fa fa-sort pull-right"></i></span>
                                                            <input class="item_photo upload_doc" type="file" name="pdf_file[]" value="upload" style="opacity: 0" disabled> </span>

                                                    </span>
                                                </td>                   
                                                <td><span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteitem(this)" data-href="<?php echo base_url('admin_section/archiveItem/' . $val['id']); ?>"  title="Archive"><i class="glyphicon glyphicon-remove franchises-i"></i></a>Delete</span></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </div>


            <div class="clearfix"></div>

        </div>
        <div class="col-lg-6">
            <h3 class="page-header page-header2" align="center">Manufacturers</h3>


            <div class="col-md-12">

                <div class="panel-body">

                    <div class="table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">

                            <table id="MANUFACTURE_Datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Manufacturer</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($getmanufacturer)) {
                                        foreach ($getmanufacturer as $val) {
                                            ?>
                                            <tr>
                                                <td><span class="itemmanu"><?php echo strtoupper($val['manufacturer_name']); ?></span>
                                                    <input type="hidden" name="manufacturer_id[]" value="<?php echo $val['id']; ?>">
                                                    <input class="form-control item manuitem"  name="manufacturer_name[]" id="manufacturer_<?php echo $val['id']; ?>" value="<?php echo $val['manufacturer_name']; ?>">
                                                </td>
                                                <td><span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deletemanufacturer(this)" data-href="<?php echo base_url('admin_section/archiveManufacturer/' . $val['id']); ?>"  title="Archive"><i class="glyphicon glyphicon-remove franchises-i"></i></a>Delete</span></td>

                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>

                            </table>

                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-lg-12" style="margin-top: 10px">
        <div class="col-lg-6" style="border-right: solid #ddd">
            <form action="<?php echo base_url() . 'admin_section/addItemsManu' ?>" method="post" id="add_multiowner_form">  
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9"> Add Multiple New Items</div>
                        </div></div>


                    <div class="panel-body">

                        <div class="form-group col-md-12">
                            <div class="item_input_fields_wrap">
                                <textarea  cols="50" rowsa="8"  class="form-control" name="item_name" id="item_name" required style="width: 653px; height: 198px;"></textarea>

                            </div>
                            <input type="hidden" name="row_count" id="row_count" value="1">
                            <input type="hidden" name="account_id" value="<?php echo $user_data['customer_account_id']; ?>">
                        </div>
                    </div> 

                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary" type="submit" id="item_save_button">Submit</button>

                </div>
            </form>
        </div>
        <div class="col-lg-6" style="margin-top: 10px">  
            <form action="<?php echo base_url() . 'admin_section/addManufacturer' ?>" method="post" id="add_multiowner_form">
                <div class="panel panel-primary">

                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9">  Add Multiple New Manufacturers</div>
                        </div></div>
                    <div class="panel-body multiadd">

                        <div class="form-group col-md-12">
                            <div class="manufacture_input_fields_wrap">

                                <textarea  cols="50" rowsa="8"  class="form-control" name="manufacture_name" id="manufacture_name" required style="width: 653px; height: 198px;"></textarea>
                            </div>
                            <input type="hidden" name="row_count_manufacture" id="row_count_manufacture" value="1">
                            <input type="hidden" name="account_id" value="<?php echo $user_data['customer_account_id']; ?>">

                        </div>
                    </div>  
                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary" type="submit" id="save_button">Submit</button>

                </div>
            </form>    </div>  
    </div>
</div>


