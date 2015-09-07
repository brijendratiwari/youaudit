    <h2>All Faculties</h2>    
    
    <?php 
            
                    
                   
                        if ($arrSessionData['objSystemUser']->levelid > 2)
                        {
                            ?><p class="right"><a href="<?php echo site_url('/faculties/addone/'); ?>" class="button">Add A Faculty</a></p><?php
                        }
                        
                    
?>
    
    <?php 
    if (count($arrFacultiesData['results'])>0) 
    {
	?>
	    <table class="half-wide">
		<tr class="header-row">
		    
		    <td>Name</td>
		    <td>Actions</td>
		</tr>
	<?php
	foreach ($arrFacultiesData['results'] as $arrFaculty)
	{
	   
	?>
		<tr <?php if ($arrFaculty->facultyactive == 0) { echo "class=\"inactive\"";} ?>>
		   
		    <td><?php echo $arrFaculty->facultyname; ?></td>
		    <td><?php
			if ($arrFaculty->facultyactive != 0)
			{
			    ?>
			    <a href="<?php echo site_url('/faculties/editone/'.$arrFaculty->facultyid.'/'); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                            <a href="<?php echo site_url('/faculties/deleteone/'.$arrFaculty->facultyid.'/'); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
			    <?php
			}
			else
			{
			?>
			    <a href="<?php echo site_url('/faculties/reactivateone/'.$arrFaculty->facultyid.'/'); ?>"><img src="/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
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