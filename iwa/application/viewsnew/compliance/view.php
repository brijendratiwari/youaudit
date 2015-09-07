<div class="heading">
    <h1>Compliance Check View</h1>
    <div class="buttons">
        <a href="https://www.iworkaudit.com/iwa/fleet/addvehicle" class="button">Add A Vehicle</a>
    </div>           
</div>
<div id="content">
    <!--<ul id="breadcrumb">
        <li><a href="https://www.iworkaudit.com/iwa/">iSchool Audit</a></li>
        <li><a href="https://www.iworkaudit.com/iwa/items">Items</a></li><li>View item</li>
    </ul>-->
    
    <div class="box">
        <div class="heading">
            <h1><?php //print ?></h1>
           
            <div class="buttons">
                <a href="https://www.iworkaudit.com/iwa/compliance/edit/<?php print $test['test_type_id']; ?>" class="button">Edit Compliance Check</a>            
            </div>
        </div>
          
        <div class="box_content">

                <table class="list_table">
                    <thead>
                        <tr>
                            <th class="left"><?php print $test['test_type_name']; ?></th>
                            <th class="left"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Check Name</strong></td>
                            <td><?php print $test['test_type_name']; ?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Category</strong></td>
                            <td><?php print $test['cat_name']; ?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Mandatory</strong></td>
                            <td><?php print ($test['mandatory'] == 1) ? "Yes": "No"; ?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Frequency</strong></td>
                            <td><?php print $test['test_frequency']; ?></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Notes/Instructions</strong></td>
                            <td><?php print $test['test_type_notes']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>