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

                <?php if(!empty($start_date) && !empty($end_date)) {
                $date_ex = explode('-', $start_date);
                $start_date_new = $date_ex[2] . "-" . $date_ex[1] . "-" . $date_ex[0];
                $date_ex = explode('-', $end_date);
                $end_date_new = $date_ex[2] . "-" . $date_ex[1] . "-" . $date_ex[0];                
                ?>
                <h2>Compliance checks completed between <?php echo $start_date_new; ?> and <?php echo $end_date_new; ?></h2>
                <?php } ?>
            

        <table class="list_table">
            <thead>
                <tr>
                <th>Date</th>
                <th>QR Code</th>
                <th>Serial No</th>
                <th>Manufacturer & Model</th>
                <th>Category</th>
                <th>Check Name</th>
                <th>Mandatory</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrResults as $test) { ?>
                
                <tr>
                    <?php $date_ex = explode('-', $test['test_date']); $test['test_date'] = $date_ex[2] . "-" . $date_ex[1] . "-" . $date_ex[0]; ?> 
                    <td><?php echo $test['test_date']; ?></td>
                    <td><a href="<?php echo "/iwa/items/view/" . $test['test_item_id']; ?>"><?php echo $test['barcode']; ?></a></td>
                    <td><?php echo $test['serial_number']; ?></td>
                    <td><?php echo $test['manufacturer'] . " " . $test['model']; ?></td>
                    <td><?php echo $test['name']; ?></td>
                    <td><?php echo $test['test_type_name']; ?></td>
                    <td><?php echo ($test['test_type_mandatory'] == 1) ? 'Yes' : 'No'; ?></td>

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