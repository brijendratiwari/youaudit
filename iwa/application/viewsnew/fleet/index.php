
<div class="row">
    <h1>Fleet Management</h1>
</div>
<div class="heading">
    
     <div class="col-md-12">
         <div class="col-md-7">
    <a href="<?php echo site_url('fleet/checks'); ?>" class="button icon-with-text round"><i class="fa fa-check"></i><b>Vehicle Checks</b></a>
    <a href="<?php echo site_url('fleet/reports'); ?>" class="button icon-with-text round"><i class="fa  fa-file-text"></i><b>Vehicle Check Reports</b></a>
    
    <a href="<?php echo site_url('fleet/depreciate'); ?>" class="button icon-with-text round"><i class="fa fa-sort-numeric-desc"></i><b>Depreciation</b></a> 
   
    <a href="<?php echo site_url('fleet/addvehicle'); ?>" class="button icon-with-text round"><i class="fa fa-automobile"></i><b>Add A Vehicle</b></a>
    <?php
    if ($arrSessionData['objSystemUser']->levelid > 2) {
        ?>
    <a href="<?php echo site_url('/fleet/confirmdeleted/'); ?>" class="button icon-with-text round"><i class="fa fa-close"></i><b>Confirm Deletions</b></a>
        <?php
    }
    ?>
         </div>
    <div class="text-right col-md-5">
        <span class="com-name">                     <?= $arrSessionData['objSystemUser']->accountname; ?>
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
<div class="box_content"> 
    <div class="content_module">
        <div id="fleetsnapshot">
            <table class="list_table" style="width: 500px; margin: auto;">
                <thead>
                <th colspan="4"><h2>Fleet Snapshot</h2></th>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Service Due</strong></td>
                        <td><?php print $fleetDueService; ?></td>
                        <td><strong>Insurance Due</strong></td>
                        <td><?php print $fleetDueInsurance; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Vehicle Inspection</strong></td>
                        <td><?php print $fleetDueMot; ?></td>
                        <td><strong>Tax Due</strong></td>
                        <td><?php print $fleetDueTax; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Total Vehicles</strong></td>
                        <td><?php print $fleet_no; ?></td>
                        <td><strong>Total Value</strong></td>
                        <td><?php echo $currency; ?><?php print round($fleetTotalValue['totalvalue'], 0); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
          
    </div>
    <table class="list_table">
        <thead>
            <tr class="header-row">
                <th class="left">Make</th>
                <th class="left">Model</th>
                <th class="left">Plate number</th>
                <th class="left">Vehicle Inspection Date</th> 
                <th class="left">Service Due Date</th>
                <th class="left">Rego</th>
                <th class="left">Insurance Due</th>
                <th class="left">Owner</th>
                <th class="left">Site</th>
                <th class="right action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fleetList as $key => $vehicle) {
                ?>
                <tr>
                    <td><?php print $vehicle['make']; ?></td>
                    <td><?php print $vehicle['model']; ?></td>
                    <td><?php print $vehicle['reg_no']; ?></td>
                    <td <?php print (strtotime("now") >= $vehicle['mot_renewal_notice'] && strtotime($vehicle['mot_due_date']) > 0) ? "style=\"color: red;\"" : ""; ?>><?php print (strtotime($vehicle['mot_due_date']) > 0) ? date('d/m/Y', strtotime($vehicle['mot_due_date'])) : 'N/A'; ?></td>
                    <td <?php print (strtotime("now") >= $vehicle['service_renewal_notice'] && strtotime($vehicle['service_due_date'] > 0)) ? "style=\"color: red;\"" : ""; ?>><?php print (strtotime($vehicle['service_due_date']) > 0) ? date('d/m/Y', strtotime($vehicle['service_due_date'])) : 'N/A'; ?></td>
                    <td <?php print (strtotime("now") >= $vehicle['tax_renewal_notice'] && strtotime($vehicle['tax_expiration']) > 0) ? "style=\"color: red;\"" : ""; ?>><?php print (strtotime($vehicle['tax_expiration']) > 0) ? date('d/m/Y', strtotime($vehicle['tax_expiration'])) : "N/A"; ?></td>
                    <td <?php print (strtotime("now") >= strtotime($vehicle['insurance_expiration']) && strtotime($vehicle['insurance_expiration']) > 0) ? "style=\"color: red;\"" : ""; ?>><?php print (strtotime($vehicle['insurance_expiration']) > 0) ? date('d/m/Y', strtotime($vehicle['insurance_expiration'])) : "N/A"; ?></td>
                    <td><?php print $vehicle['owner']; ?></td>
                    <td><?php print $vehicle['site']; ?></td>
                    <td class="right action">
                        <a href="<?php echo site_url('fleet/view/' . $vehicle['fleet_id']); ?>"><img src="<?= base_url('/img/icons/16/view.png'); ?>" title="View Vehicle" alt="View Vehicle"></a>
                        <a href="<?php echo site_url('fleet/edit/' . $vehicle['fleet_id']); ?>"><img src="<?= base_url('/img/icons/16/modify.png'); ?>" title="Edit" alt="Edit"></a>
                        <a href="<?php echo site_url('fleet/markdeleted/' . $vehicle['fleet_id']); ?>"><img src="<?= base_url('/img/icons/16/erase.png'); ?>" title="Delete" alt="Delete"></a>

                    </td>
                </tr>
<?php } ?>
        </tbody>
    </table>
</div>
