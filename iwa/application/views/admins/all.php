<h2>Current SysAdmins</h2>
    <ul><?php
    if (count($arrAdmins['results'])>0) 
    {
	foreach ($arrAdmins['results'] as $arrAdmin)
	{
	    echo "<li>".$arrAdmin->firstname." ".$arrAdmin->lastname." ";
	    echo "[<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/viewadmin/'.$arrAdmin->adminid);
	    echo "\">View</a> | ";
	    echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/edit/'.$arrAdmin->adminid);
	    echo "\">Edit</a> | ";
	    echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/changecredentials/'.$arrAdmin->adminid);
	    echo "\">Change Credentials</a>";
	    if ($arrSessionData['objAdminUser']->id != $arrAdmin->adminid)
	    {
		echo " | ";
		echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/deleteadmin/'.$arrAdmin->adminid);
		echo "\">Delete</a>";
	    }
	    echo "]</li>";
	}
    }
	?>
	<li>Create SysAdmin
	<?php
	    echo "[<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/create/');
	    echo "\">link</a>]";
	?>
	</li>
    </ul>