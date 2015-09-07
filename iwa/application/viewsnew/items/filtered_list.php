<script>
    $(document).ready(function() {
        var user_admin = $("#filter_Datatable").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
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
                {"sClass": "eamil_conform aligncenter", "aTargets": [9]}
            ]}
        );
    });
</script>
<style>
    #filter_Datatable
    {
        background: #ffffff;
    }
    .error
    {
        color: bold;
        font-weight: bold;
    }
</style>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<?php if ($error) { ?>
    <h1 class="error"><?php echo $error;?></h1>
<?php } ?>
<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="filter_Datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>QR Code</th>
                                <th>Category</th>
                                <th>Item</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Quantity</th>
                                <th>Owner</th>
                                <th>Site</th>
                                <th>Location</th>
                                <th>Condition</th>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php foreach ($filterdata as $data) { ?>                               
                                <tr>
                                    <td><a href="<?php echo base_url('items/view/' . $data['itemid']); ?>"><?php echo $data['barcode']; ?></a></td>
                                    <td><?php echo $data['categoryname']; ?></td>
                                    <td><?php echo $data['item_manu']; ?></td>
                                    <td><?php echo $data['manufacturer']; ?></td>
                                    <td><?php echo $data['model']; ?></td>
                                    <td><?php echo $data['quantity']; ?></td>
                                    <td><?php echo $data['owner_name']; ?></td>
                                    <td><?php echo $data['sitename']; ?></td>
                                    <td><?php echo $data['locationname']; ?></td>
                                    <td><?php echo $data['condition_name']; ?></td>
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