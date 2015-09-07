<h2>By Category</h2>


    <?php 
    if (count($arrItemsData['results'])>0) 
    {
	foreach ($arrItemsData['results'] as $arrCategory)
	{
	    ?>
	    <h3><?php echo $arrCategory['categoryname']; ?></h3>
	    <table border=1>
		<tr>
		    <td>Barcode</td>
		    <td>Manufacturer</td>
		    <td>Model</td>
		    <td>Serial Number</td>
		    <td>Actions</td>
		</tr>
		<?php
		foreach ($arrCategory['items'] as $arrItem)
		{
		?>
		<tr>
		    <td><?php echo $arrItem->barcode; ?></td>
		    <td><?php echo $arrItem->manufacturer; ?></td>
		    <td><?php echo $arrItem->model; ?></td>
		    <td><?php echo $arrItem->serial_number; ?></td>
		    <td><a href="<?php echo site_url('/items/view/'.$arrItem->itemid.'/'); ?>">View</a></td>
		</tr>
		<?php  
		}
		?>
	    </table>
	    <?php
	}
   
    }
    else
    {
	?>
	<p>You don't have any items associated with you at present.</p>
	<?php
    }
    ?>