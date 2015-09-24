<div class="row col-lg-12">
    <?php $logo = 'logo.png'; ?>
    <div class='logo_cls'><img alt='Youaudit' src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/brochure/logo/logo.png"></div>
</div>
<div class="row">
<div class="col-md-12">
    <table class="table table-bordered table-responsive" id="#item_table">
        <thead>
        <th>QR Code</th>
        <th>Manufacturer</th>
        <th>Model</th>
        <th>Category</th>
        <th>Item</th>
        <th>Location</th>
        <th>Site</th>
        <th>Owner</th>
        <th>Severity</th>
        <th>Order No</th>
        <th>Fault Fixed By</th>
        </thead>
        <tr>
            <td><?php echo $historyData->barcode; ?></td>
            <td><?php echo $historyData->manufacturer; ?></td>
            <td><?php echo $historyData->model; ?></td>
            <td><?php echo $historyData->categoryname; ?></td>
            <td><?php echo $historyData->item_manu_name; ?></td>
            <td><?php echo $historyData->locationname; ?></td>
            <td><?php echo $historyData->sitename; ?></td>
            <td><?php echo $historyData->userfirstname.' '.$historyData->userlastname; ?></td>
            <td><?php echo $historyData->severity; ?></td>
            <td><?php echo $historyData->order_no; ?></td>
            <td><?php echo $historyData->loggedBy; ?></td>
        </tr>
    </table>
</div>
    <div class="col-md-12">&nbsp;</div>
<div class="col-md-12">
    <div class="col-md-12"><h4 style="color:#00aeef;">Incident Timeline</h4></div>
    <table class="table table-responsive" id="item_table">
        <thead>
        <th>Date</th>
        <th>Time</th>
        <th>Event</th>
        <th>Logged By</th>
        <th>Code</th>
        <th>Notes</th>
    </thead>
    <?php foreach($allJobNotes as $val){ ?>  
    <tr>
            <td><?php echo date('Y/m/d',strtotime($val['date'])); ?></td>
            <td><?php echo date('h:i:s',strtotime($val['date']));; ?></td>
            <td><?php echo $val['action']; ?></td>
            <td><?php echo $val['firstname']." ".$val['lastname']; ?></td>
            <td><?php  if($val['fix_code'] != ""){
                echo $val['fix_code']; 
             }else{ 
             echo $val['reason_code']; } ?>
            </td>
            <td><?php echo $val['jobnote']; ?></td>
        
        </tr>
    <?php } ?> 
    </table>
</div>
</div>

<style>

    #item_table td {
        
        padding:5px;
    }
    
</style>
