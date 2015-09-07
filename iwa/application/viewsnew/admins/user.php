    <h2><?php echo $arrUser['result'][0]->accountname." ".$arrUser['result'][0]->lastname; ?></h2>
    <ul>
	<li>Nickname: <?php echo $arrUser['result'][0]->nickname; ?></li>
	<li>Username/Email: <?php echo $arrUser['result'][0]->username; ?></li>
	<li>Account: <a href="<?php
	    echo site_url('/'.strtolower($arrPageParameters['strSection']).'/viewusers/'.$arrUser['result'][0]->accountid); ?>"><?php
	    echo $arrUser['result'][0]->accountname; ?></a></li>
	<li>Level: <?php echo $arrUser['result'][0]->levelname; ?></li>
	<li><a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/edituser/'.$arrUser['result'][0]->userid); ?>">Edit this user</a></li>
    </ul>