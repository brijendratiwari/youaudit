    <h2><?php echo $arrUser['result'][0]->firstname." ".$arrUser['result'][0]->lastname; ?></h2>
    <ul>
	<li>Nickname: <?php echo $arrUser['result'][0]->nickname; ?></li>
	<li>Username/Email: <?php echo $arrUser['result'][0]->username; ?></li>
	<li>Level: <?php echo $arrUser['result'][0]->levelname; ?></li>
	<li>Username/Email: <?php echo $arrUser['result'][0]->username; ?></li>
	<li><a href="<?php echo site_url('/users/editone/'.$arrUser['result'][0]->userid); ?>">Edit this user</a></li>
    </ul>