<!--<script src="<?php echo base_url() . '/assets/js/bootstrap-formhelpers.min.js'; ?>"></script>-->
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
        min-height: 70px;
        max-height: 300px;
        overflow-y: scroll;
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

        var user_sites = $("#User_sites").DataTable({
            "ordering": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
            "bSortCellsTop": true,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnRowCallback": function(nRow, aData) {

                var $nRow = $(nRow); // cache the row wrapped up in jQuery
                tdhtm = $nRow.children()[1].innerHTML;

                if (tdhtm.search("enable") != -1) {
                    $nRow.css("background-color", "#f2b4b4");
                }

                return nRow;
            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]}
            ]}
        );

        $("#add_site_form").validate({
            rules: {
                site_name: "required"
            },
            messages: {
                site_name: "Please Enter Site Name"

            }
        });

        $("#edit_site_form").validate({
            rules: {
                edit_site_name: "required"
            },
            messages: {
                edit_site_name: "Please Enter Site Name"

            }
        });

        // script for edit user
        $("body").on("click", ".edit", function() {

            var sitename = $(this).attr("data_sitename");
            var adminuser_id = $(this).attr("data_adminuser_id");

            $("#edit_site_name").attr("value", sitename);
            $("#adminuser_id_1").attr("value", adminuser_id);


        });


    });
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this Site ?", function(result) {
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
                <h4> Sites </h4>
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
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/'); ?>">Items</a>
                    </li>
                    <li class="active"><a data-toggle="" href="<?php echo base_url('admin_section/admin_sites'); ?>">Sites</a>
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
                <div class="col-lg-7" >




                    <a  href="<?= base_url('admin_section/exportPDFForSite/CSV') ?>" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>CSV</b>
                    </a>


                    <a  href="<?= base_url('admin_section/exportPDFForSite/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>PDF</b></a>

                    <a class="button icon-with-text round" id="add_site_data" data-target="#add_site" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add <br>Site</b></a>

                    <!--                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button style="margin-left:300px;" class="btn btn-primary btn-xs" type="button" id="add_site_data" data-target="#add_site" data-toggle="modal"><i class="fa fa-plus-circle"></i>
                                        <b>Add Site</b></button>-->



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
    <!-- /.panel-body -->
    <!--        </div>
        </div>
    </div>-->
    <div class="row">
        <div class="col-lg-12">

            <div class="panel-body">

                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="User_sites" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Site/Building Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="Master_Customer_body">
                                <?php foreach ($sites as $site) {
                                    ?>
                                    <tr>
                                        <td><?php echo ellipsize($site->name, 50); ?></td>
                                        <?php
                                        if ($site->active == 1) {
                                            $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $site->id . '" href="' . base_url('admin_section/disableSite/' . $site->id) . '" data_adminuser_id=' . $site->id . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                        } else {
                                            $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $site->id . '" href="' . base_url('admin_section/enableSite/' . $site->id) . '" data_adminuser_id=' . $site->id . '  title="enable" class="enableadminuser"><i class="fa  fa-play franchises-i"></i></a>Enable</span>';
                                        }
                                        ?>
                                        <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $site->id; ?>" href="#edit_site" title="Edit" data_sitename="<?php echo $site->name; ?>" data_adminuser_id="<?php echo $site->id; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><?php echo $access_icon; ?><span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('admin_section/archiveSite/' . $site->id); ?>"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span></td>
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
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_site" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Site</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_site'); ?>" method="post" id="add_site_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Site Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Site Name" class="form-control" name="site_name" id="site_name">
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

<!-- Model For Edit Owner -->

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_site" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Site</h4>
            </div>

            <form action="<?php echo base_url('admin_section/editSite'); ?>" method="post" id="edit_site_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Site Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Site Name" class="form-control" name="edit_site_name" id="edit_site_name">
                        </div>
                    </div> <!-- /.form-group -->
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

    <div class="col-lg-4">  <form action="<?php echo base_url('admin_section/add_multiplesites'); ?>" method="post" id="add_multisite_form">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-9">  Add Multiple New Sites/Buildings</div>
                    </div></div>

                <div class="panel-body multiadd">

                    <div class="form-group col-md-12">
                        <div class="input_fields_wrap">

                            <textarea cols="50" rows="8" placeholder="Enter Site Name" class="form-control" name="site_name" id="site_name" required></textarea>
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
