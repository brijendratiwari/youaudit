
<div class="box">
    <div class="heading">
      	<h1><?php print $category_name; ?> - Tests</h1>
        
<?php 
        if ($arrSessionData['objSystemUser']->levelid > 2)
        {
?>
            <div class="buttons">
                <a href="<?php echo site_url('/categories/addtest/' . $cat_id); ?>" class="button">Add new test</a>
            </div>           
<?php                           
        }                
?>
    </div>
    
    <div class="box_content">
        <table class="list_table">
            <thead>
                <tr class="header-row">
                    <th class="left">Test Name</th>
                    <th class="left">Test Frequency</th>
                    <th class="right action">Actions</th>
                </tr>          
            </thead>
            <tbody>
                <?php foreach ($tests as $test) { ?>
                <tr>
                    <td><?php print $test['test_type_name']; ?></td>
                    <td><?php print $test['test_frequency']; ?></td>
                    <td class="right action">
                        <a href="<?php echo site_url('/categories/edittest/'.$test['test_type_id']); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                        <a href="<?php echo site_url('/categories/removetest/'.$test['test_type_id']); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                    </td>
                </tr>        
                <?php } ?>
            </tbody>
        </table>
    </div>
            
            
            
        </table>  
    </div>
</div>