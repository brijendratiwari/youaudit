<div class="box">
    	<div class="heading">
            <h1><?php echo $strReportName ?></h1>  
            <?php 
                if (count($arrResults) > 0)
                {
            ?>
                <div class="buttons">

                    <a target="_blank" href="<?php echo site_url('reports/createPdf/'.$strPdfUrl); ?>" class="button">Download as a PDF</a>
                </div>
            <?php
                }
            ?>
        </div>
    <div class="box_content">
        <div class="content_main">
    <?php 
        if (count($arrResults) > 0)
        {
    ?>

                <h2>Vehicles with compliance items between <?php echo date('d/m/Y', strtotime($start_date)); ?> and <?php echo date('d/m/Y', strtotime($end_date)); ?></h2>
            

        <table class="list_table">
            <thead>
                <tr>
                <th>Registration No</th>
                <th>Make & Model</th>
                <th>MOT Due Date</th>
                <th>Tax Due Date</th>
                <th>Service Due Date</th>
                <th>Insurance Expiry Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrResults as $vehicle) { ?>
                <tr>
                    <td><a href="<?php echo "/iwa/fleet/view/" . $vehicle['fleet_id']; ?>"><?php echo $vehicle['reg_no']; ?></a></td>
                    <td><?php echo $vehicle['make'] . " " . $vehicle['model']; ?></td>
                    <td><?php echo $vehicle['mot_due_date']; ?></td>
                    <td><?php echo $vehicle['tax_due_date']; ?></td>
                    <td><?php echo $vehicle['service_due_date']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($vehicle['insurance_expiration'])); ?></td>
                </tr>
                <?php } ?>
            </tbody>        
        </table>
    <?php
        }
        else
        {
    ?>
            <p>Report was empty</p>
    <?php 
        } 
    ?>
            </div>
        </div>
    </div>