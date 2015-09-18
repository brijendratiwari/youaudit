<ul id="menu">
    <li<?php if ($arrPageParameters['strTab'] == 'Dashboard') {
            echo " class=\"selected\"";
        }?>><a href="<?php
			echo site_url('/admins/index');
			?>">Dashboard</a></li>
    
    <li<?php if ($arrPageParameters['strTab'] == 'Accounts') {
            echo " class=\"selected\"";
        }?>><a href="<?php
			echo site_url('/admins/viewaccounts');
			?>">Customer List</a></li>

    <li<?php if ($arrPageParameters['strTab'] == 'Administrators') {
            echo " class=\"selected\"";
        }?>><a href="<?php
			echo site_url('/admins/viewadmins/');
			?>">Admin Users</a></li>

    <li<?php if ($arrPageParameters['strTab'] == 'Import Data') {
        echo " class=\"selected\"";
    }?>><a href="<?php
        echo site_url('/admins/import');
        ?>">Import Data</a></li>

    <li<?php if ($arrPageParameters['strTab'] == 'Vehicle Checks') {
        echo " class=\"selected\"";
    }?>><a href="<?php
        echo site_url('/admins/vehicleChecks');
        ?>">Vehicle Checks</a></li>
    
    <li<?php if ($arrPageParameters['strTab'] == 'Compliance Checks') {
        echo " class=\"selected\"";
    }?>><a href="<?php
        echo site_url('/admins/complianceChecks');
        ?>">Safety Templates</a></li>
      <li<?php if ($arrPageParameters['strTab'] == 'Profile') {
        echo " class=\"selected\"";
    }?>><a href="<?php
        echo base_url('/admins/profiles');
        ?>">Profiles</a></li>
     
      <li<?php if ($arrPageParameters['strTab'] == 'Archive') {
        echo " class=\"selected\"";
    }?>><a href="<?php
        echo base_url('/admins/viewarchive');
        ?>">Archive Account</a></li>

</ul>