    <h2><?php echo $arrAdmin['result'][0]->firstname." ".$arrAdmin['result'][0]->lastname; ?></h2>
    <ul>
	<li>Nickname: <?php echo $arrAdmin['result'][0]->nickname; ?></li>
	<li>Username/Email: <?php echo $arrAdmin['result'][0]->username; ?></li>
	<li><a href="<?php
	    echo site_url('/'.strtolower($arrPageParameters['strSection']).'/edit/'.$arrAdmin['result'][0]->adminid);
	    ?>">Edit <?php
	    if ($arrAdmin['result'][0]->adminid == $arrSessionData['objAdminUser']->id)
	    {
	    ?>your profile<?php
	    }
	    else
	    {
	    ?>this SysAdmin<?php
	    }
	    ?></a></li>
    </ul>