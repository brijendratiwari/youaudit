    <h2>All Categories</h2>    
    
     <?php 
            
                    
                   
                        if ($arrSessionData['objSystemUser']->levelid > 2)
                        {
                            ?><p class="right"><a href="<?php echo site_url('/categories/addone/'); ?>" class="button">Add A Category</a></p><?php
                        }
                        
                    
?>
    
    
    <?php 
    if (count($arrCategoriesData['results'])>0) 
    {
	?>
	    <table class="half-wide">
		<tr class="header-row">
		    
		    <td>Name</td>
		    <td>Actions</td>
		</tr>
	<?php
	foreach ($arrCategoriesData['results'] as $arrCategory)
	{
	   
	?>
		<tr <?php if ($arrCategory->categoryactive == 0) { echo "class=\"inactive\"";} ?>>
		   
		    <td><?php echo $arrCategory->categoryname; ?></td>
		    <td><?php
			if ($arrCategory->categoryactive != 0)
			{
			    ?>
			    <a href="<?php echo site_url('/categories/editone/'.$arrCategory->categoryid.'/'); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
			<a href="<?php echo site_url('/categories/deleteone/'.$arrCategory->categoryid.'/'); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
			    <?php
			}
			else
			{
			?>
			    <a href="<?php echo site_url('/categories/reactivateone/'.$arrCategory->categoryid.'/'); ?>"><img src="/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
			<?php
			}
			?>
		    </td>
		</tr>
	<?php  
	}
	?>
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