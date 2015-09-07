<h2>Current Accounts</h2>


    <ul><?php 
    if (count($arrAccounts['results'])>0) 
    {
	foreach ($arrAccounts['results'] as $arrAccount)
	{
	    
		    echo "<li>".$arrAccount->accountname.", ".$arrAccount->accountcity." ";
		    
		    if ($arrAccount->accountactive != 0)
		    {
			echo "[<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/viewaccount/'.$arrAccount->accountid);
			echo "\">View</a> | ";
			echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/editaccount/'.$arrAccount->accountid);
			echo "\">Edit</a> | ";
			echo "<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/deleteaccount/'.$arrAccount->accountid);
			echo "\">Delete</a>";
			echo "]</li>";
		    }
		    else
		    {
			echo "(Marked as Deleted) ";
			echo "[<a href=\"".site_url('/'.strtolower($arrPageParameters['strSection']).'/reactivateaccount/'.$arrAccount->accountid);
			echo "\">Reactivate</a>";
			echo "]";
		    }
		
		    echo "</li>";
	    
	}
    }
    ?>
    </ul>