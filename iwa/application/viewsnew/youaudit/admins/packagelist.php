<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<?php $this->load->helper('text'); ?>
<style>
    .modal-body{
        /*height: 495px;*/
        overflow-y: scroll;
    } 
</style>
<script>
    $(document).ready(function() {

        var packages = $("#youaudit_package").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [3]}
            ]}
        );

        $("#add_package_form").validate({
            rules: {
                package_name: "required",
                package_asset: "required"
            },
            messages: {
                package_name: "Please Enter package Name",
                package_asset: "Please Enter package Asset Limit"
            }
        });

        $("#edit_package_form").validate({
            rules: {
                editpackage_name: "required",
                editpackage_asset: "required"
            },
            messages: {
                editpackage_name: "Please Enter Package Name",
                editpackage_asset: "Please Enter Package Asset Limit"
            }
        });

        // script for edit user
        $("body").on("click", ".edit", function() {

            var packagename = $(this).attr("data_packagename");
            var packageasset = $(this).attr("data_itemlimit");
            var enable = $(this).attr("data_enable");
            var adminuser_id = $(this).attr("data_adminuser_id");

            $("#editpackage_name").attr("value", packagename);
            $("#editpackageasset").attr("value", packageasset);
            if (enable == 1)
            {
                $('#editenablepackage').prop('checked', true);
            }
            else
            {
                $('#editenablepackage').prop('checked', false);
            }
            $("#adminuser_id_1").attr("value", adminuser_id);


        });
    });
</script>
<div class="row">
    <div class="col-lg-4">
        <h1 class="page-header">Packages</h1>
    </div>
    <div class="col-lg-8" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button class="btn btn-primary btn-xs icon-with-text" type="button" id="addpackage"  data-target="#add_package" data-toggle="modal"><i class="fa fa-plus"></i>
                    <b>Add package</b></button> 


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
                    <table id="youaudit_package" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Package name</th>
                                <th>Number Of Asset</th>
                                <th>Enable Package</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php foreach ($packages as $package) {
                                ?>
                                <tr>
                                    <td><?php echo ellipsize($package->name, 50); ?></td>
                                    <td><?php echo $package->item_limit; ?></td>
                                    <td><?php
                                        if ($package->enable == 1) {
                                            echo 'Yes';
                                        } else {
                                            echo 'No';
                                        }
                                        ?></td>
                                    <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $package->id; ?>" href="#edit_package" title="Edit" data_packagename="<?php echo $package->name; ?>" data_itemlimit="<?php echo $package->item_limit; ?>" data_enable="<?php echo $package->enable; ?>" data_adminuser_id="<?php echo $package->id; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span></td>
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


<!-- Modal For Add package -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_package" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Package</h4>
            </div>

            <form action="<?php echo base_url('youaudit/add_package'); ?>" method="post" id="add_package_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Package Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Package Name" class="form-control" name="package_name" id="packagename">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>No Of Asset :</label> </div>
                        <div class="col-md-6">  <input class="form-control" name="package_asset" id="packageasset" value="1000">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Annual Value :</label> </div>
                        <div class="col-md-6">  <input class="form-control" name="package_annual" id="packageannual" value="1000">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Compliance Module :</label> </div>
                        <div class="col-md-6">  <label class="checkbox-inline"><input type="checkbox" name="enable_compliance" id="enable_compliance"></label>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Fleet Module :</label> </div>
                        <div class="col-md-6">  <label class="checkbox-inline"><input type="checkbox" name="enable_fleet" id="enable_fleet"></label>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Condition Module :</label> </div>
                        <div class="col-md-6">  <label class="checkbox-inline"><input type="checkbox" name="enable_condition" id="enable_condition"></label>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Depreciation Module :</label> </div>
                        <div class="col-md-6">  <label class="checkbox-inline"><input type="checkbox" name="enable_depreciation" id="enable_depreciation"></label>
                        </div>
                    </div> <!-- /.form-group -->
<div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Reporting Module :</label> </div>
                        <div class="col-md-6">  <label class="checkbox-inline"><input type="checkbox" name="enable_reporting" id="enable_reporting"></label>
                        </div>
                    </div> <!-- /.form-group -->
                    
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Enable Package :</label> </div>
                        <div class="col-md-6">  <label class="checkbox-inline"><input type="checkbox" name="enable_package" id="enablepackage"></label>
                        </div>
                    </div> <!-- /.form-group -->
                    <!--<input type="hidden" name="sites" value="1">-->
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

<!-- Modal For Edit Package -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_package" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Package</h4>
            </div>

            <form action="<?php echo base_url('youaudit/edit_package'); ?>" method="post" id="edit_package_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Package Name :</label> </div>
                        <div class="col-md-6">  <input class="form-control" name="editpackage_name" id="editpackage_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>No Of Asset :</label> </div>
                        <div class="col-md-6">  <input class="form-control" name="editpackage_asset" id="editpackageasset">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Enable Package :</label> </div>
                        <div class="col-md-6"> <label class="checkbox-inline"><input type="checkbox" name="editenable_package" id="editenablepackage"></label>
                        </div>
                    </div> <!-- /.form-group -->
                    <input type="hidden" name="adminuser_id" id="adminuser_id_1"/>
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


