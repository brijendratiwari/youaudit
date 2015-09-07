<?php //print "<pre>"; print_r($arrResults); print "</pre>"; //die(); ?>
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
            $date_ex = explode('-', $start_date);
            $start_date = $date_ex[2] . "-" . $date_ex[1] . "-" . $date_ex[0];
            $date_ex = explode('-', $end_date);
            $end_date = $date_ex[2] . "-" . $date_ex[1] . "-" . $date_ex[0];
    ?>

                <?php if(!empty($start_date) && !empty($end_date)) { ?>
                    <h2>Compliance items due between <?php echo $start_date; ?> and <?php echo $end_date; ?></h2>
                <?php } else { ?>
                    <h2>Compliance items due</h2>
                <?php } ?>

        <table class="list_table">
            <thead>
                <tr>
                <th>Barcode</th>
                <th>Make & Model</th>
                <th>Category</th>
                <th>Compliance Check</th>
                <th>Check Due</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arrResults['dueMandatory'] as $key => $container) { ?>
                        <?php foreach ($container['tests'] as $test) { ?>
                <tr>
                    <td><a href="<?php echo "/iwa/items/view/" . $container['item']->itemid; ?>"><?php echo $container['item']->barcode; ?></a></td>
                    <td><?php echo $container['item']->manufacturer . " " . $container['item']->model; ?></td>
                    <td><?php echo $container['item']->categoryname; ?></td>
                    <td><?php echo $test['test_type_name']; ?></td>
                    <td><?php echo date('d/m/Y', $test['due_ts']); ?></td>
                </tr>
                <?php }
                } ?>
                
                <?php foreach ($arrResults['dueOptional'] as $key => $container) { ?>
                        <?php foreach ($container['tests'] as $test) { ?>
                <tr>
                    <td><a href="<?php echo "/iwa/items/view/" . $container['item']->itemid; ?>"><?php echo $container['item']->barcode; ?></a></td>
                    <td><?php echo $container['item']->manufacturer . " " . $container['item']->model; ?></td>
                    <td><?php echo $container['item']->categoryname; ?></td>
                    <td><?php echo $test['test_type_name']; ?></td>
                    <td><?php echo ($test['due_ts'] != 'Now') ? date('d/m/Y', $test['due_ts']) : 'Now'; ?></td>
                </tr>
                <?php }
                } ?>
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