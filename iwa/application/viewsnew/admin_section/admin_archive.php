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


</style>


<script>
    $(document).ready(function() {
        var user_admin = $("#restore_user").DataTable({
            "ordering": true,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [4]}
            ]}
        );

        var user_owners = $("#owner_Datatable").DataTable({
            "ordering": true,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
            ]}
        );

        var category_table = $("#CATEGORY_DATATABLE").DataTable({
            "ordering": true,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
               
            ]}
        );

        var user_site = $("#site_Datatable").DataTable({
            "ordering": true,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
            ]}
        );

        var location = $("#restore_location").DataTable({
            "ordering": true,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
            ]}
        );

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
   if ($this->session->flashdata('arrCourier')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('arrCourier'); ?>
    </div>
    <?php
}
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>  Archive </h4>
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
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_sites'); ?>">Sites</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_location'); ?>">Location</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/customFields'); ?>">Custom Fields</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_supplier'); ?>">Suppliers/Customers</a>
                    </li>
                    <li class="active"><a data-toggle="" href="<?php echo base_url('admin_section/admin_archive'); ?>">Archive</a>
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
                    
                            <a  href="#" class="button icon-with-text round">
                                <i class="fa  fa-file-pdf-o"></i>
                                <b>Export to <br>CSV</b>
                            </a>


                            <a  href="#" target="blank" class="button icon-with-text round">
                                <i class="fa  fa-file-pdf-o"></i>
                                <b>Export to <br>PDF</b></a>

<!--                            <button  class="btn btn-primary btn-xs" type="button" id="b1" style="margin-left:40px;"><i class="fa  fa-file-pdf-o"></i>
                                <b> Export PDF</b></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button  class="btn btn-primary btn-xs" type="button"><i class="fa fa-file-word-o"></i>
                                <b>Export CSV</b></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->




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
            <!-- /.panel-body -->
            <!--        </div>
                </div>
            </div>-->
            <div class="row">

                <div class="col-lg-5">

                </div>
                <div class="col-lg-2">
                    <h1 class="page-header">Users</h1>
                </div>
                <div class="col-lg-5">

                </div>

            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel-body multiadd">

                        <div class="table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                                <table id="restore_user" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Username/Email Address</th>
                                            <th>Access Level</th>
                                            <th>Action</th>
                                            <th></th>

                                        </tr>

                                    </thead>
                                    <tbody id="Master_Customer_body">
                                        <?php
                                        if (!empty($user)) {
                                            foreach ($user as $val) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $val['firstname']; ?></td>
                                                    <td><?php echo $val['lastname']; ?></td>
                                                    <td><?php echo $val['username']; ?></td>
                                                    <td><?php echo $val['name']; ?></td>
                                                    <td></td>
                                                    <td><span class="action-w"><a href=" <?php echo base_url('admin_section/restoreUser/' . $val['id']); ?>"  title="Restore"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span></td></tr>
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
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Owners</h1>
                </div>
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Categories</h1>
                </div>
            </div>

            <div class="row">


                <div class="col-lg-5">
                    <div class="panel-body multiadd">
                        <div class="table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                                <table id="owner_Datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Owner Name</th>
                                            <th>Action</th>
                                        </tr>

                                    </thead>
                                    <tbody id="Master_Customer_body">
                                        <?php
                                        if (!empty($owner)) {
                                            foreach ($owner as $owner) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $owner->owner_name; ?></td>
                                                    <td><span class="action-w"><a href=" <?php echo base_url('admin_section/restoreOwner/' . $owner->id); ?>"  title="Restore"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span></td>

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

                <div class="col-lg-7">

                    <div class="panel-body multiadd">

                        <div class="table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                                <table id="CATEGORY_DATATABLE" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Category Name</th>

                                            <?php
                                            foreach ($custom_field as $header_name) {
                                                echo '<th>' . $header_name['field_name'] . '</th>';
                                            }
                                            ?>
                                            <th>Actions</th>

                                        </tr>

                                    </thead>
                                    <tbody id="Master_Customer_body">
                                        <?php
                                        if (!empty($get_category)) {
                                            foreach ($get_category as $category) {
                                                ?>
                                                <tr>
                                                    <td><input type="checkbox" id="<?php echo $category['id'] ?>"></td>
                                                    <td><?php echo $category['name']; ?></td>
                                                    <?php
                                                    foreach ($custom_field as $header_name) {

                                                        if (array_key_exists($header_name['field_name'], $category)) {

                                                            echo '<td> YES </td>';
                                                        } else {

                                                            echo '<td> NO </td>';
                                                        }
                                                    }
                                                    ?>
                                                    <td><span class="action-w"><a href=" <?php echo base_url('admin_section/restoreCategory/' . $category['id']); ?>"  title="Restore"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span></td>

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

            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Sites</h1>
                </div>
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Locations</h1>
                </div>
            </div>

            <div class="row">


                <div class="col-lg-5">
                    <div class="panel-body multiadd">
                        <div class="table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                                <table id="site_Datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Site Name</th>
                                            <th>Action</th>
                                        </tr>

                                    </thead>
                                    <tbody id="Master_Customer_body">
                                        <?php
                                        if (!empty($site)) {
                                            foreach ($site as $site) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $site->name; ?></td>
                                                    <td><span class="action-w"><a href=" <?php echo base_url('admin_section/restoreSite/' . $site->id); ?>"  title="Restore"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span></td>

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

                <div class="col-lg-7">

                    <div class="panel-body multiadd">

                        <div class="table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                                <table id="restore_location" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Location Name</th>

                                            <th>Location QR Code</th>
                                            <th>Site</th>

                                            <th>Actions</th>

                                        </tr>

                                    </thead>
                                    <tbody id="Master_Customer_body">
                                        <?php
                                        if (!empty($location)) {
                                            foreach ($location as $location) {
                                                ?>
                                                <tr>
                                                    <td><input type="checkbox" id="<?php echo $location->id ?>"></td>
                                                    <td><?php echo $location->name; ?></td>
                                                    <td><?php echo $location->barcode; ?></td>
                                                    <td><?php echo $location->url; ?></td>
                                                    <td><span class="action-w"><a href=" <?php echo base_url('admin_section/restoreLocation/' . $location->id); ?>"  title="Restore"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span></td>

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
        </div>
    </div>
</div>