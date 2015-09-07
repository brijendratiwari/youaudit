<ul id="menu">
    <li>System Admininstrators
	<ul class="menu first">
		<li><a href="<?php
			echo site_url('/admins/viewadmins/');
			?>">View</a></li>
		<li><a href="<?php
			echo site_url('/admins/create/');
			?>">Create</a></li>
	</ul></li>
    
    <li>Accounts
	<ul class="menu first">
		<li><a href="<?php
			echo site_url('/admins/viewaccounts');
			?>">View</a></li>
		<li><a href="<?php
			echo site_url('/admins/createaccount/');
			?>">Create</a></li>
	</ul></li>
    
    <li>Users
	<ul class="menu first">
		<li><a href="<?php
			echo site_url('/admins/viewusers/');
			?>">View</a></li>
		<li><a href="<?php
			echo site_url('/admins/createuser/');
			?>">Create</a></li>
	</ul></li>
</ul>