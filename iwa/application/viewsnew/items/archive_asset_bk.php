<?php // var_dump($arrRecentlyDeletedItems);            ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script>
    $(document).ready(function() {
        var archive_asset = $("#archiveasset_Datatable").DataTable({
            "ordering": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
//            "scrollY": "200px",
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]}

            ]
        }
        );
    });
</script>
<style>
    #Archiveasset_Datatable
    {
        background: #ffffff;
    }
</style>
<div class="box">
    <div class="heading">
        <div class="col-md-4 text-left">
            <h1>Archived/Removed Asset Register</h1>
        </div>
        <div class="col-md-8 text-right">
            <span class="com-name">                     <?= $arrSessionData['objSystemUser']->accountname; ?>
                <!--<img src="<?= base_url('/img/circle-red.png'); ?>" width="60" /></span>-->
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

    <!--    <div class="box_content">
            <div class="content_main row">-->
    <div class="row">
        <div class="col-lg-12">

            <div class="panel-body">

                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="Archiveasset_Datatable" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Date</th>
                                </tr>

                            </thead>
                            <?php
                            if ($arrSessionData['objSystemUser']->levelid >= 2) {
                                if (!empty($arrRecentlyDeletedItems)) {
                                    ?>
                                    <tbody>
                                        <?php
                                        foreach ($arrRecentlyDeletedItems as $objItem) {
                                            $strUrl = '/items/view/' . $objItem->id;
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo base_url($strUrl);
                                            ?>"><?php echo $objItem->barcode; ?></a></td>

                                                <td><?php echo $objItem->item_manu_name; ?></td>
                                                <td><?php echo $objItem->category_name; ?></td>
                                                <td><?php echo $objItem->location_name; ?></td>
                                                <td><?php echo $objItem->owner_name; ?></td>
                                                <td><?php echo $objItem->status_name; ?></td>
                                                <td><?php echo $objItem->status_name; ?></td>
                                                <td><?php                                                        
//                                                    if ($arrSessionData['objSystemUser']->levelid == 4) {
                                                        if ($objItem->mark_deleted_2_date != NULL) {
                                                            echo date('d/m/Y', strtotime($objItem->mark_deleted_2_date));
                                                        } 
                                                        elseif ($objItem->mark_deleted_date != NULL) {
                                                            echo date('d/m/Y', strtotime($objItem->mark_deleted_date));
                                                        } else {
                                                            echo 'N/A';
                                                        }
                                                    ?></td>
                                            </tr>
                                                    <?php
                                                }
                                                ?>
                                    </tbody>
                                        <?php
                                    }
                                }
                                ?>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>


    <!--        </div>
        </div>-->
</div>