<ul id="menu">
	<li><a href="<?php echo site_url();	?>">Your Dashboard</a></li>
	<li><a href="<?php echo site_url('/items/filter/');?>">Tracked Items</a></li>
<?php
if ($arrSessionData['objSystemUser']->levelid > 1) {
?>
	<li><a href="<?php echo site_url('/locations/viewall/'); ?>">Locations</a></li>
	<?php if ($arrSessionData['objSystemUser']->levelid > 2) { ?>
	<li><a href="<?php echo site_url('/users/viewall/'); ?>">Users</a></li>
	<li><a href="<?php echo site_url('/faculties/viewall/'); ?>">Faculties</a></li>
	<li><a href="<?php echo site_url('/categories/viewall/'); ?>">Categories</a></li>
        
        
            <li><a href="<?php echo site_url('/reports/'); ?>">Reports</a></li>
         
        
	<li><a href="<?php echo site_url('/account/edit/'); ?>">Edit account details</a></li>
	<?php } ?>
<?php } ?>
        
        
        
        
        
<?php
/*
if ($arrSessionData['objSystemUser']->levelid > 1)
{
?>
	<li>About <?php echo $arrSessionData['objSystemUser']->accountname; ?>
		<ul class="menu first">
			<li><a href="<?php
				 echo site_url('/locations/viewall/');
					?>">Locations &gt;&gt;</a>
					<ul class="menu">
						<li><a href="<?php
						echo site_url('/locations/viewall/');
						?>">View All</a></li>
						<li><a href="<?php
						echo site_url('/locations/addone/');
						?>">Add a new location</a></li>
					</ul></li>
		<?php
		if ($arrSessionData['objSystemUser']->levelid > 2)
		{
		?>
			<li><a href="<?php
				 echo site_url('/users/viewall/');
					?>">Users &gt;&gt;</a>
					<ul class="menu">
						<li><a href="<?php
						echo site_url('/users/viewall/');
						?>">View All</a></li>
						<li><a href="<?php
						echo site_url('/users/add/');
						?>">Add a new user</a></li>
					</ul></li>
			
		
			<li><a href="<?php
				 echo site_url('/faculties/viewall/');
					?>">Faculties &gt;&gt;</a>
					<ul class="menu">
						<li><a href="<?php
						echo site_url('/faculties/viewall/');
						?>">View All</a></li>
						<li><a href="<?php
						echo site_url('/faculties/addone/');
						?>">Add a new faculty</a></li>
					</ul></li>
                                        
                        <li><a href="<?php
				 echo site_url('/categories/viewall/');
					?>">Categories &gt;&gt;</a>
					<ul class="menu">
						<li><a href="<?php
						echo site_url('/categories/viewall/');
						?>">View All</a></li>
						<li><a href="<?php
						echo site_url('/categories/addone/');
						?>">Add a new category</a></li>
					</ul></li>

                        <li><a href="<?php
				 echo site_url('/account/edit/');
					?>">Edit account details</a></li>
		<?php
		}
		?>
		</ul>
	</li>
<?php
}
 
 */
?>
	
</ul>