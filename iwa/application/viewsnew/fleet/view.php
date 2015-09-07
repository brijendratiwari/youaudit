
<div class="heading">
    <h1>Fleet Management</h1>
    <div class="buttons">
        <a href="<?= base_url('/fleet/addvehicle'); ?>" class="button">Add A Vehicle</a>
    </div>           
</div>
<div id="content">
    <!--<ul id="breadcrumb">
        <li><a href="https://www.iworkaudit.com/iwa/">iSchool Audit</a></li>
        <li><a href="https://www.iworkaudit.com/iwa/items">Items</a></li><li>View item</li>
    </ul>-->
    
    <div class="box">
        <div class="heading">
            <h1><?php print $vehicle['make'] . " " . $vehicle['model']; ?> - <?php print $vehicle['reg_no']; ?></h1>
           
               <div class="buttons">
                <a class="button icon-with-text round" href="<?php echo site_url('/fleet/edit/'.$vehicle['fleet_id']);  ?>"><i class="fa fa-edit"></i><b>Edit Vehicle</b></a>
                <a class="button icon-with-text round" href="<?php  echo site_url('/fleet/markdeleted/'.$vehicle['fleet_id']);  ?>"><i class="fa fa-close"></i><b>Remove Vehicle</b></a>
                <a class="button icon-with-text round" href="<?=site_url('fleet/raiseticket/' . $vehicle['fleet_id'])?>"><i class="fa fa-arrow-up"></i><b>Raise a Support Ticket</b></a>
                <a class="button icon-with-text round" href="<?php  echo site_url('/fleet/newmot/'.$vehicle['fleet_id']); ?>"><i class="fa fa-expand"></i><b>New Vehicle Inspection Record</b></a>
                <a class="button icon-with-text round" href="<?php  echo site_url('/fleet/newservice/'.$vehicle['fleet_id']);  ?>"><i class="fa fa-legal"></i><b>New Service Record</b></a>
                
                
            </div>
        </div>
          
            <div class="box_content">
                <div class="tabs">
                    <ul>
                        <li><a href="#first_table" class="active">Vehicle Details</a></li>
                        <li><a href="#fifth_table">Vehicle History</a></li>
                        <li><a href="#sixth_table">Vehicle Fault History</a></li>
                        <li><a href="#second_table">Vehicle Inspection</a></li>
                        <li><a href="#third_table">Service History</a></li>
                        <li><a href="#fourth_table">Vehicle Check History</a></li>
                    </ul>
         
                <!--<a href="#fourth_table">Tax History</a>-->
                
                </div>

                <div id="first_table" class="content_main">
                    <table class="list_table" id="history_table">
                        <thead>
                            <tr>
                                <th class="left">Make &amp; Model</th>
                                <th class="left"><?php print $vehicle['make'] . " " . $vehicle['model']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>QR Code</strong></td>
                                <td><?php print $vehicle['barcode']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Engine Size</strong></td>
                                <td><?php print $vehicle['engine_size']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Plate Number</strong></td>
                                <td><?php print $vehicle['reg_no']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Owner</strong></td>
                                <td><?php print $vehicle['owner']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Site</strong></td>
                                <td><?php print $vehicle['site']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Purchase Date</strong></td>
                                <?php
                                if(!empty($vehicle['purchase_date'])) {
                                    $date_split = explode("-",$vehicle['purchase_date']);
                                    $purchase_date_newformat = $date_split[2] . "/" . $date_split[1] . "/" . $date_split[0];
                                }
                                ?>
                                <td><?php print ($purchase_date_newformat > 0) ? $purchase_date_newformat : ""; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Purchase Value (<?php echo $currency; ?>) </strong></td>
                                <td><?php print $currency.$vehicle['vehicle_value']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Depreciated Value (<?php echo $currency; ?>)</strong></td>
                                <td><?php print $currency.$vehicle['current_value']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Insurance Renewal Date</strong></td>
                                <?php     
                                if(!empty($vehicle['insurance_expiration'])) {
                                    $date_split = explode("-",$vehicle['insurance_expiration']);
                                    $insurance_date_newformat = $date_split[2] . "/" . $date_split[1] . "/" . $date_split[0];
                                }
                                ?>
                                <td><?php print ($insurance_date_newformat > 0) ? $insurance_date_newformat : ""; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Warranty Expiration Date</strong></td>
                                <?php     
                                if(!empty($vehicle['warranty_expiration'])) {
                                    $date_split = explode("-",$vehicle['warranty_expiration']);
                                    $warranty_date_newformat = $date_split[2] . "/" . $date_split[1] . "/" . $date_split[0];
                                }
                                ?>
                                <td><?php print ($warranty_date_newformat > 0) ? $warranty_date_newformat : ""; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Rego</strong></td>
  
                                <td><?php print ($vehicle['tax_expiration'] > 0) ? date('d/m/Y', strtotime($vehicle['tax_expiration'])) : ""; ?></td>
                            </tr>
                            <tr>
                                <?php  
                                if(!empty($vehicle['mot_due_date'])) {
                                    $date_split = explode("-",$vehicle['mot_due_date']);
                                    $mot_date_newformat = $date_split[2] . "-" . $date_split[1] . "-" . $date_split[0];
                                }
                                ?>
                                <td><strong>Vehicle Inspection Date</strong></td>
                                <td><?php print (strtotime($vehicle['mot_due_date']) > 0) ? $vehicle['mot_due_date'] : "N/A"; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Next Service Due</strong></td>
                                <td><?php print (isset($vehicle['service_due_date'])) ? $vehicle['service_due_date'] : "N/A"; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Notes</strong></td>
                                <td><?php print (isset($vehicle['notes'])) ? $vehicle['notes'] : "N/A"; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div id="second_table" class="content_main">
                    <?php foreach ($vehicle_mot as $mot) { ?>
                    <table class="list_table" id="history_table">
                        <thead>
                            <tr>
                                <th class="left">Manufacturer &amp; Model</th>
                                <th class="left"><?php print $vehicle['make'] . " " . $vehicle['model']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Vehicle Inspection Certificate No</strong></td>
                                <td><?php print $mot['mot_cert_no']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Vehicle Inspection Date</strong></td>
                                <td><?php 
                                $date_split = explode("-",$mot['mot_date']);
                                $mot_date_newformat = $date_split[2] . "/" . $date_split[1] . "/" . $date_split[0];
                                print $mot_date_newformat; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Vehicle Inspection Expiry Date</strong></td>
                                <td><?php 
                                if($mot['mot_expiry_date'] != NULL) {
                                    $date_split = explode("-",$mot['mot_expiry_date']);
                                    $mot_expiry_date_newformat = $date_split[2] . "/" . $date_split[1] . "/" . $date_split[0];
                                    print $mot_expiry_date_newformat; 
                                } else {
                                    print "Not Available";
                                }
                                    ?></td>
                            </tr>
                            <tr>
                                <td><strong>Vehicle Inspection Result</strong></td>
                                <td><?php print ($mot['mot_result'] == 1) ? "Pass" : "Fail"; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Vehicle Inspection Notes</strong></td>
                                <td><?php print $mot['mot_notes']; ?></td>
                            </tr>

                        </tbody>
                    </table>
                    <?php } ?>
                </div>
                
                <div id="third_table" class="content_main">
                    <?php foreach ($vehicle_service as $service) { ?>
                    <table class="list_table">
                        <thead>
                            <tr>
                                <th class="left" style="width: 644px;">Manufacturer &amp; Model</th>
                                <th class="left"><?php print $vehicle['make'] . " " . $vehicle['model']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Service Reference No</strong></td>
                                <td><?php print $service['service_ref_no']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Service Date</strong></td>
                                <td><?php 
                                $date_split = explode("-",$service['service_date']);
                                $service_date_newformat = $date_split[2] . "/" . $date_split[1] . "/" . $date_split[0];
                                print $service_date_newformat; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Service Expiry Date</strong></td>
                                <td><?php 
                                $date_split = explode("-",$service['service_expiry_date']);
                                $service_expiry_date_newformat = $date_split[2] . "/" . $date_split[1] . "/" . $date_split[0];
                                print $service_expiry_date_newformat; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Service Notes</strong></td>
                                <td><?php print $service['service_notes']; ?></td>
                            </tr>

                        </tbody>
                    </table>
                    <?php } ?>
                </div>
         
                
            <div id="fifth_table" class="content_main">

                
                <table class="list_table">
                    <thead>
                        <tr>
                            <th class="left">Date</th>
                            <th class="left">User</th>
                            <th class="left">Location</th>
                            <th class="left">Site</th>
                            <th class="left">Value after depreciation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php


                        foreach ($arrItemHistory as $strDate=>$arrRecord)
                        {
                        ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($strDate)); print (!empty($arrRecord['depreciation'])) ? " (Depreciation record)" : "" ?></td>
                            <td><?php if (isset($arrRecord['user']) || isset($arrRecord['depreciation']))
                                        {
                                            echo $arrRecord['user']->userfirstname." ".$arrRecord['user']->userlastname;
                                            echo $arrRecord['depreciation']['firstname'] . " " . $arrRecord['depreciation']['lastname'];
                                        }
                                            ?></td>   
                            <td><?php if (isset($arrRecord['location']))
                                        {
                                            echo $arrRecord['location']->locationname; 
                                        }
                                            ?></td>
                            <td><?php if (isset($arrRecord['site']))
                                        {
                                            echo $arrRecord['site']->sitename; 
                                        }
                                            ?></td>
                            <td><?php if (isset($arrRecord['depreciation']))
                                        {
                                            echo $currency . $arrRecord['depreciation']['value']; 
                                        }
                                            ?></td>
                        </tr>
                        <?php
                        }
                        ?>

                    </tbody>

                </table>
            </div>

                <div id="fourth_table" class="content_main">


                    <table class="list_table">
                        <thead>
                        <tr>
                            <th class="left">Date</th>
                            <th class="left">User</th>
                            <th class="left">Results</th>
                            <th class="right action">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php


                        foreach ($arrCheckHistory as $key => $arrRecord)
                        {

                            ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i:s', $arrRecord['date_time']); ?></td>
                                <td><?=$arrRecord['username']?></td>
                                <td>
                                    <?php echo ($arrRecord['num_passed'] > 0 ? $arrRecord['num_passed'] . ' passed. ' : ''); ?>
                                    <?php echo ($arrRecord['num_failed'] > 0 ? $arrRecord['num_failed'] . ' failed.' : ''); ?>
                                </td>
                                <td class="right action">
                                    <a href="<?php echo site_url('fleet/viewcheck/' . $arrRecord['log_id'] . ''); ?>"><img src="/img/icons/16/view.png" title="View Item" alt="View Item"></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                        </tbody>

                    </table>
                </div>

                <div id="sixth_table" class="content_main">


                    <table class="list_table">
                        <thead>
                        <tr>
                            <th class="left">Date</th>
                            <th class="left">User</th>
                            <th class="left">Reported issue</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php


                        foreach ($arrTicketHistory as $ticket)
                        {
                            ?>
                            <tr>
                                <td style="width: 120px;"><?php echo date('d/m/Y H:i:s', strtotime($ticket['date'])); ?></td>
                                <td style="width: 100px;"><?php echo $ticket['username']; ?></td>
                                <td><?php echo $ticket['description']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>

                        </tbody>

                    </table>
                </div>
                
            </div>
    </div>
</div>