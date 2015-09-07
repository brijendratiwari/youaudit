    <h2>All Locations</h2>    
    
     <?php 
            
                    
                   
                        if ($arrSessionData['objSystemUser']->levelid > 2)
                        {
                            ?><p class="right"><a href="<?php echo site_url('/locations/addone/'); ?>" class="button">Add A Location</a></p><?php
                        }
                        
                    
?>
    
    
    <?php 
    if (count($arrLocationsData['results'])>0) 
    {
	?>
	    <table class="half-wide">
		<tr class="header-row">
		    <td>Barcode</td>
		    <td>Name</td>
		    <td>Actions</td>
		</tr>
	<?php
	foreach ($arrLocationsData['results'] as $arrLocation)
	{
	   
	?>
		<tr <?php if ($arrLocation->locationactive == 0) { echo "class=\"inactive\"";} ?>>
		    <td><?php echo $arrLocation->locationbarcode; ?></td>
		    <td><?php echo $arrLocation->locationname; ?></td>
		    <td><?php
			if ($arrLocation->locationactive != 0)
			{
			    ?>
			    <a href="<?php echo site_url('/locations/editone/'.$arrLocation->locationid.'/'); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
			<a href="<?php echo site_url('/locations/deleteone/'.$arrLocation->locationid.'/'); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
			    <?php
			}
			else
			{
			?>
			    <a href="<?php echo site_url('/locations/reactivateone/'.$arrLocation->locationid.'/'); ?>"><img src="/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
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
	<p>You don't have any locations added at present.</p>
	<?php
    }
    ?>