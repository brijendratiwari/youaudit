<h2>Welcome to iSchool Audit</h2>

<ul>
    <li>System Admininstrators
	<ul>
		<li>View [<a href="<?php
			echo site_url('/admins/viewadmins/');
			?>">link</a>]</li>
		<li>Create [<a href="<?php
			echo site_url('/admins/create/');
			?>">link</a>]</li>
	</ul></li>
    
    <li>Accounts
	<ul>
		<li>View [<a href="<?php
			echo site_url('/admins/viewaccounts');
			?>">link</a>]</li>
		<li>Create [<a href="<?php
			echo site_url('/admins/createaccount/');
			?>">link</a>]</li>
	</ul></li>
    
    <li>SuperAdmin Requests
<?php	
    if (count($arrSuperAdminRequests['results']) > 0)
    {
        echo "<ul>";
        foreach ($arrSuperAdminRequests['results'] as $objSuperAdminRequest)
        {
            echo "<li>".$objSuperAdminRequest->firstname." ".$objSuperAdminRequest->lastname." [<a href=\"";
            echo site_url('/admins/makesuperadmin/'.$objSuperAdminRequest->userid);
	    echo "\">link</a>]</li>";
        }
	echo "</ul></li>";
    }
    else
    {
        echo "<ul><li>There are no SuperAdmin Requests</li></ul></li>";
    }
?>
</ul>