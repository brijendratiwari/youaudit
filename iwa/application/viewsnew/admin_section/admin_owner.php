<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<?php $this->load->helper('text'); ?>
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
    .multiadd{

        height: 550px !important;
        overflow-y: auto !important;
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
// Validation On Add Owner
//        $("#add_owner_form").validate({
//            rules: {
//                owner_name: "required",
//            },
//            messages: {
//                owner_name: "Please Enter Owner Name",
//            }
//        });
// Validation On Edit Owner
        $("#edit_owner_form").validate({
            rules: {
                edit_owner_name: "required",
            },
            messages: {
                edit_owner_name: "Please Enter Owner Name",
            }
        });


        var max_fields = 100; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment

                $(wrapper).append('<div class="col-md-8"><input type="hidden" name="owners" value="' + x + '"><input placeholder="Enter Owner Name" class="form-control" name="owner_name_' + x + '" id="owner_name_' + x + '" required><a href="#" class="remove_field">Remove</a></div>'); //add input box
            }
            $("#remove").css("display", "block");
        });

        $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });

        var user_owners = $("#User_owners").DataTable({
            "ordering": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
            "bSortCellsTop": true,
            "scrollY": "auto",
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnRowCallback": function(nRow, aData) {

                var $nRow = $(nRow); // cache the row wrapped up in jQuery
                tdhtm = $nRow.children()[2].innerHTML;

                if (tdhtm.search("enable") != -1) {
                    $nRow.css("background-color", "#f2b4b4");
                }

                return nRow;
            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
            ]}
        );

        $("#add_owner_form").validate({
            rules: {
                owner_name: "required",
            },
            messages: {
                owner_name: "Please Enter Owner Name",
            }
        });

        $("#edit_owner_form").validate({
            rules: {
                edit_owner_name: "required"
            },
            messages: {
                edit_owner_name: "Please Enter Owner Name"

            }
        });

        // script for edit user
        $("body").on("click", ".edit", function() {

            var ownername = $(this).attr("data_ownername");
            var adminuser_id = $(this).attr("data_adminuser_id");
            var location_id = $(this).attr("data_location_id");

            $("#ownername").attr("value", ownername);
            $("#edit_location_id").attr("value", location_id);
            $("#adminuser_id_1").attr("value", adminuser_id);


        });
    });
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this Owner ?", function(result) {
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
                <h4>  Owners  </h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li ><a data-toggle="" href="<?php echo base_url('admin_section/admin_user'); ?>">Users</a>
                    </li>
                    <li class="active"><a data-toggle="" href="<?php echo base_url("admin_section/admin_owner"); ?>">Owners</a>
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




                    <a  href="<?= base_url('admin_section/exportPDFForOwner/CSV') ?>" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>CSV</b>
                    </a>


                    <a  href="<?= base_url('admin_section/exportPDFForOwner/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>PDF</b></a>

                    <a  class="button icon-with-text round" id="add_owner_data" data-target="#add_owner" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add <br>Owner</b></a>


<!--                            <button class="btn btn-primary btn-xs" style="margin-left:300px;" type="button" id="add_owner_data" data-target="#add_owner" data-toggle="modal"><i class="fa fa-plus-circle"></i>
                                <b>Add Owner</b></button>-->



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

<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="User_owners" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>

                                <th>Owner Name</th>
                                <th>Location Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php foreach ($owners as $owner) {
                                ?>
                                <tr>

                                    <td><?php echo ellipsize($owner['owner_name'], 50); ?></td>
                                    <td><?php echo ellipsize($owner['name'], 50); ?></td>
                                    <?php
                                    if ($owner['active'] == 1) {
                                        $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $owner['id'] . '" href="' . base_url('admin_section/disableOwner/' . $owner['id']) . '" data_adminuser_id=' . $owner['id'] . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                    } else {
                                        $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $owner['id'] . '" href="' . base_url('admin_section/enableOwner/' . $owner['id']) . '" data_adminuser_id=' . $owner['id'] . '  title="edit" class="enableadminuser"><i class="fa  fa-play franchises-i"></i></a>Enable</span>';
                                    }
                                    ?>
                                    <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $owner['id']; ?>" href="#edit_owner" title="Edit" data_location_id="<?php echo $owner['location_id']; ?>" data_ownername="<?php echo $owner['owner_name']; ?>" data_adminuser_id="<?php echo $owner['id']; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><?php echo $access_icon; ?><span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('admin_section/archiveOwner/' . $owner['id']); ?>"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span></td>
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
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_owner" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Owner</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_owner'); ?>" method="post" id="add_owner_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Owner Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Owner Name" class="form-control" name="owner_name" id="owner_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Location :</label> </div>
                        <div class="col-md-6">  <select name="location_id" id="location_id" class="form-control">
                                <option value="">---select---</option>
                                <?php
                                foreach ($location as $location_detail) {
                                    ?>
                                    <option value="<?php echo $location_detail->locationid ?>"><?php echo $location_detail->locationname ?></option>
                                    <?php
                                }
                                ?>
                            </select>
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

<!-- Model For Edit Owner -->

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_owner" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Owner</h4>
            </div>

            <form action="<?php echo base_url('admin_section/editOwner'); ?>" method="post" id="edit_owner_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Owner Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Owner Name" class="form-control" name="edit_owner_name" id="ownername">
                        </div>
                    </div> <!-- /.form-group -->

                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Location :</label> </div>
                        <div class="col-md-6">  <select name="edit_location_id" id="edit_location_id" class="form-control">

                                <option value="0">---select---</option>
                                <?php
                                foreach ($location as $edit_location) {
                                    ?>


                                    <option value="<?php echo $edit_location->locationid ?>"><?php echo $edit_location->locationname ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <input type="hidden" name="adminuser_id" id="adminuser_id_1"/>

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


<div class="row">

    <div class="col-lg-8">  <form action="<?php echo base_url('admin_section/add_multipleowners'); ?>" method="post" id="add_multiowner_form">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-12">  Add Multiple New Owners</div>
                    </div></div>

                <div class="panel-body multiadd">

                    <div class="form-group col-md-12">
                        <div class="col-md-4">  <label>Owner :</label> </div>
                        <div class="col-md-8">  
                            <div class="input_fields_wrap">

                                <textarea style="height: 119px; width: 456px;" cols="50" rows="8" type="hidden" name="owners" value="1" required=""></textarea>
                            </div>


                        </div></div>
                    <div class="form-group col-md-12">
                        <div class="col-md-4">  <label>Location :</label> </div>
                        <div class="col-md-8">  <select name="multi_location_id" id="multi_location_id" class="form-control">
                                <option value="">---select---</option>
                                <?php
                                foreach ($location as $multi_location) {
                                    ?>
                                    <option value="<?php echo $multi_location->locationid ?>"><?php echo $multi_location->locationname ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>  
                </div>  


            </div>
            <div class="panel-footer">
                <button class="btn btn-primary" type="submit" id="save_button">Submit</button>

            </div>
        </form></div>    

</div>




<!-- /.col-lg-12 -->
</div>


</div>
</div>
</div>



<!-- Modal For Add User -->
