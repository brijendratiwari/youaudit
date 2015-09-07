<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        min-height: 10px;
        max-height: 595px; 
        overflow-y: scroll;
    } 
    .bootbox .modal-dialog{
        width: 300px;
    }
    #field_values
    {
        display: none;
    }
    #edit_field_values
    {
        display: none;
    }
</style>
<script>
    $(document).ready(function() {

        var custom_fields = $("#Admin_Category").DataTable({
            "ordering": true,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]}
            ]}
        );
        $("#edit_custom_form").validate({
            rules: {
                edit_custom_name: "required"
            },
            messages: {
                edit_custom_name: "Please Enter Custom Field Name"

            }
        });

        $("#add_custom_form").validate({
            rules: {
                field_name: "required"
            },
            messages: {
                field_name: "Please Enter Custom Field Name"

            }
        });

        $("body").on("change", "#field_type", function()
        {
            var type = $('#field_type option:selected').val();
            if (type == 'pick_list_type')
            {
                $('#field_values').css('display', 'block');
            }
            else
            {
                $('#field_values').css('display', 'none');
            }
        });
        $("body").on("change", "#edit_field_type", function()
        {
            var type = $('#edit_field_type option:selected').val();
            if (type == 'pick_list_type')
            {
                $('#edit_field_values').css('display', 'block');
            }
            else
            {
                $('#edit_field_values').css('display', 'none');
            }
        });
        $("body").on("click", ".edit", function() {

            var customname = $(this).attr("data_custom");
            var custom_type = $(this).attr("data_val");
            var adminuser_id = $(this).attr("data_adminuser_id");
            var pick_val = $(this).attr("data_pick");

            var obj = new Array();
            var stuffArray = pick_val.split(",");

            $.each(stuffArray, function(index, value) {
                obj.push(value);
            });
            var arr = obj.join('\n');

            $("#edit_custom_name").attr("value", customname);
            $("#pick_value").attr("value", arr);
            $("#edit_field_type option[value='" + custom_type + "']").attr("selected", "selected");
            $("#custom_id").attr("value", adminuser_id);
            var type = $('#edit_field_type option:selected').val();
            if (type == 'pick_list_type')
            {
                $('#edit_field_values').css('display', 'block');
            }
            else
            {
                $('#edit_field_values').css('display', 'none');
            }

        });

        $("body").on("click", ".del", function() {

            var url = $(this).attr('data-href');
            bootbox.confirm("Are you sure?", function(result) {
                if (result) {
                    window.location.href = url;
                } else {
                    // Do nothing!
                }
            });
        });
    });
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
                <h4>  Custom Fields  </h4>
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
                    <li  class="active"><a data-toggle="" href="<?php echo base_url('admin_section/customFields'); ?>">Custom Fields</a>
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

<!--<button class="btn btn-primary btn-xs" type="button" id="add_siter_data" data-target="#add_custom" data-toggle="modal"><i class="fa fa-plus-circle"></i>
            <b>Add Custom Field</b></button>-->

                    <a class="button icon-with-text round" id="customfield_data" data-target="#add_custom" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add Custom<br>Field</b></a>

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

<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">
            <h1>All Custom Fields</h1>

            <div class="col-md-3" style="padding-bottom: 20px;">
                <form class="form-inline">
                    <div class="form-group row">
                        <div class="input-group">
                            <div class="input-group-addon"><span style="padding-right: 33px;">Custom Field</span></div>
                            <input type="number" class="form-control" id="exampleInputAmount" min="0" value="<?php echo count($arrCustomFields); ?>" disabled>

                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group">
                            <div class="input-group-addon">Custom Field Limit</div>
                            <input type="number" class="form-control" id="exampleInputAmount" min="0" value="<?php echo $arrSessionData['objSystemUser']->custom_count; ?>" disabled>

                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="Admin_Category" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Field Name</th>
                                <th>Custom Field Type</th>
                                <th>Pick List Selection</th>
                                <th>Actions</th>
                            </tr>

                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php
                            foreach ($arrCustomFields as $field) {
                                $custom = array('text_type' => 'Text', 'pick_list_type' => 'Pick List', 'value_type' => '$ value', 'date_type' => 'Date', 'num' => 'Number');
                                ?>
                                <tr>
                                    <td><?= $field->field_name ?></td>
                                    <td><?= $custom[$field->field_value]; ?></td>
                                    <td><?php
                                        if ($field->pick_values) {
                                            echo $field->pick_values;
                                        } else {
                                            echo '';
                                        }
                                        ?></td>
                                    <td>
                                        <span class="action-w"><a  data-toggle="modal" id="edit_adminuser_id_<?php echo $field->id; ?>" href="#edit_site" title="Edit" data_custom="<?php echo $field->field_name; ?>" data_val="<?php echo $field->field_value; ?>" data_pick="<?php echo $field->pick_values; ?>" data_adminuser_id="<?php echo $field->id; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i" style="padding-left: 5px;display: flex!important;"></i></a>Edit</span><span class="action-w"><a href="#" data-toggle="modal" class="del"  data-href="<?php echo base_url('admin_section/delete/' . $field->id); ?>"  title="Delete"><i class="glyphicon glyphicon-remove-sign franchises-i" style="padding-left: 5px;display: flex!important;"></i></a>Archive</span>

                                    </td>
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
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_site" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Custom Field</h4>
            </div>

            <form action="<?php echo base_url('admin_section/edit'); ?>" method="post" id="edit_custom_form">
                <div class="modal-body modbody">
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Custom Field Name :</label> </div>
                        <div class="col-md-7">  <input placeholder="Enter Site Name" class="form-control" name="edit_custom_name" id="edit_custom_name">
                        </div>
                    </div> <!-- /.form-group -->

                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Custom Field Type :</label> </div>
                        <div class="col-md-7">  <select class="form-control" name="field_type" id="edit_field_type">
                                <option value="text_type">Text</option>
                                <option value="pick_list_type">Pick List</option>
                                <option value="value_type">$ value</option>
                                <option value="date_type">Date</option>
                                <option value="num">Number</option>
                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12" id="edit_field_values">
                        <div class="col-md-5">  <label>pick list values :</label> </div>
<!--                        <div class="col-md-7">  <input class="form-control" name="field_values" id="pick_value" placeholder="Enter Comma Separated Values">
                        </div>-->
                        <div class="col-md-7"> <textarea class="form-control" name="field_values" id="pick_value" placeholder="Each Line Creates a New Value in List"></textarea>
                        </div>
                    </div> <!-- /.form-group -->

                    <input type="hidden" name="custom_id" id="custom_id"/>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->


</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_custom" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Custom Field</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add'); ?>" method="post" id="add_custom_form">
                <div class="modal-body modbody">
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Custom Field Name :</label> </div>
                        <div class="col-md-7">  <input placeholder="Enter Custom Field Name" class="form-control" name="field_name" id="field_name">
                        </div>
                    </div> <!-- /.form-group -->

                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Custom Field Type :</label> </div>
                        <div class="col-md-7">  <select class="form-control" name="field_type" id="field_type">
                                <option value="text_type">Text</option>
                                <option value="pick_list_type">Pick List</option>
                                <option value="value_type">$ value</option>
                                <option value="date_type">Date</option>
                                <option value="num">Number</option>
                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12" id="field_values">
                        <div class="col-md-5">  <label>pick list values :</label> </div>
<!--                        <div class="col-md-7">  <input class="form-control" name="field_values" placeholder="Enter Comma Separated Values">
                        </div>-->
                        <div class="col-md-7">  <textarea class="form-control" name="field_values" placeholder="Each Line Creates a New Value in List"></textarea>
                        </div>
                    </div> <!-- /.form-group -->

                    <input type="hidden" name="sites" value="1">
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


