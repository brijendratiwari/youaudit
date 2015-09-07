<div class="heading">
    <h1>List of Compliance Checks/Tasks</h1>
<!--    <div class="buttons">
        <a href="<?php echo base_url('')?>/fleet/addvehicle" class="button">Add A Vehicle</a>
    </div>           -->
</div>
<div id="content">
    <!--<ul id="breadcrumb">
        <li><a href="<?php echo base_url('')?>/">iSchool Audit</a></li>
        <li><a href="<?php echo base_url('')?>/items">Items</a></li><li>View item</li>
    </ul>-->
    
    <div class="box">
        <div class="heading">
            <h1><?php //print ?></h1>
           
<!--            <div class="buttons">
                <a href="<?php echo base_url('')?>/compliance/edit/<?php print $test['test_type_id']; ?>" class="button">Edit Compliance Check</a>
            </div>-->
        </div>
          
        <div class="box_content">

                <table class="list_table">
                    <thead>
                        <tr>
                            <th class="left">Task/Check Name</th>
                            <th class="left">Type of Task</th>
                            <th class="left">Measurement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($taskDetails as $key => $value) { ?>
                        <tr>
                            <td><strong><?php print $value['task_name']; ?></strong></td>
                            <td><?php ($value['type_of_task'])?print('Numerical'):print('Standard'); ?></td>
                            <td><?php ($value['measurement_name']!='')?print($value['measurement_name']):print('NA'); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>