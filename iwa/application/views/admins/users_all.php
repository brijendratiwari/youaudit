<h2>Current Users</h2>


    <ul><?php 
    if (count($arrUsers['results'])>0) 
    {
	foreach ($arrUsers['results'] as $intAccountId => $arrAccount)
	{
	    ?>
	    <li><?php echo $arrAccount['accountname']; ?> [<a href="<?php
				echo site_url('/'.strtolower($arrPageParameters['strSection']).'/createuser/'.$intAccountId);
		?>">Create User</a> | <a href="<?php
				echo site_url('/'.strtolower($arrPageParameters['strSection']).'/viewaccount/'.$intAccountId);
		?>">View Account</a>]
	    <ul><?php
	    foreach ($arrAccount['levels'] as $intLevelId => $arrLevel)
	    {
	    ?>
		<li><?php echo $arrLevel['levelname']; ?>s [<a href="<?php
				echo site_url('/'.strtolower($arrPageParameters['strSection']).'/createuser/'.$intAccountId.'/'.$intLevelId);
		?>">Create User</a>]
		<ul>
		<?php
		foreach ($arrLevel['users'] as $arrUser)
		{
		    echo "<li>".$arrUser->firstname." ".$arrUser->lastname." ";
		    
		    if ($arrUser->active != 0)
		    {
			echo "[<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/viewuser/'.$arrUser->userid);
			echo "\">View</a> | ";
			echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/edituser/'.$arrUser->userid);
			echo "\">Edit</a> | ";
			echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/changecredentialsuser/'.$arrUser->userid);
			echo "\">Change Credentials</a> | ";
			echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/deleteuser/'.$arrUser->userid);
			echo "\">Delete</a> | ";
			echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/inherituser/'.$arrUser->userid);
			echo "\">Inherit</a>";
			echo "]</li>";
		    }
		    else
		    {
			echo "(Marked as Deleted) ";
			echo "[<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/reactivateuser/'.$arrUser->userid);
			echo "\">Reactivate</a>";
			echo "]";
		    }
		}
		?></ul></li>
	    <?php
	    }
	    ?>
	    </ul></li>
	    <?php
	}
    }
	?>
    </ul>