<div class="box">
    <div class="heading">
      	<h1>All Categories</h1>
<?php 
if ($arrSessionData['objSystemUser']->levelid > 2)
{
?>
        <div class="buttons">
            <?php
            
                if ($arrSessionData['objSystemUser']->levelid == 4)
                {
            ?>
                <a href="<?php echo site_url('/categories/depreciate/'); ?>" class="button">Apply Depreciation</a>
            <?php
                }
            
            ?>
            <a href="<?php echo site_url('/categories/addone/'); ?>" class="button">Add A Category</a>
        </div>
<?php
}            
?>
    </div>
    <div class="box_content">
        <div class="content_main">




    
     
    
    
    <?php 
    if (count($arrCategoriesData['results'])>0) 
    {
	?>
	    <table class="list_table">
                <thead>
                    <tr>
                        <th class="left">Name</th>
                       
                        <th class="right">Depreciation Rate (%)</th>
                        
                        <th class="right action">Actions</th>
                    </tr>
                </thead>
                <tbody>
	<?php
	foreach ($arrCategoriesData['results'] as $arrCategory)
	{
	   
	?>
                    <tr <?php if ($arrCategory->categoryactive == 0) { echo "class=\"inactive\"";} ?>>

                        <td><?php echo $arrCategory->categoryname; ?></td>
                        
                        <td class="right"><?php if ($arrCategory->categorydepreciationrate > 0)
                                                {
                                                    echo "<strong>";
                                                }   
                                                echo $arrCategory->categorydepreciationrate; 
                                                if ($arrCategory->categorydepreciationrate > 0)
                                                {
                                                    echo "</strong>";
                                                }  
                                                ?></td>
                        
                        <td class="right"><?php
                            if ($arrCategory->categoryactive != 0)
                            {
//                                if($arrCategory->categorydefault == 0) {
                                ?>
                                <a href="<?php echo site_url('/categories/editone/'.$arrCategory->categoryid.'/'); ?>"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                            <a href="<?php echo site_url('/categories/deleteone/'.$arrCategory->categoryid.'/'); ?>"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                                <?php
//                                } else {
                                ?>
                            <!--<p style="font-style: italic;">Default Category - cannot be altered</p>-->
                            
                           <?php
//                                }
                            }
                            else
                            {
                            ?>
                                <a href="<?php echo site_url('/categories/reactivateone/'.$arrCategory->categoryid.'/'); ?>"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
	<?php  
	}
	?>
                </tbody>
	</table>
    <?php
    }
    else
    {
	?>
	<p>You don't have any categories added at present.</p>
	<?php
    }
    ?>
        </div>
    </div>
</div>
