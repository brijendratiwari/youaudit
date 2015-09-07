    <h2>User Details</h2>
    
    <?php
        if ($arrSessionData['objSystemUser']->levelid > 2)
                        {
                            ?><p class="right"><a href="<?php echo site_url('/users/edit/'.$objUser->userid); ?>" class="button">Edit User</a></p><?php
                        }
    ?>
    
    <table class="half-wide">
	<tr class="header-row">
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
            
	</tr>
        <tr>
	    <td><strong>Name</strong></td>
	    <td><strong><?php echo $objUser->firstname; ?> <?php echo $objUser->lastname; ?></strong></td>
            
	</tr>
        <tr>
	    <td><strong>Level</strong></td>
	    <td><em><?php echo $objUser->levelname; ?></em></td>
            
	</tr>
        <tr>
            <td><strong>Profile Picture</strong></td>
            <td><?php if ($objUser->photoid > 1)
                {
                    echo "<img src=\"".site_url('/images/viewhero/'.$objUser->photoid)."\" title=\"".$objUser->phototitle."\" />";
                }
                ?></td>
            
        </tr>
        
        <?php
        if (($arrSessionData['objSystemUser']->levelid > 2) && ($objUser->request_super_admin == 1))
        {
        ?>
        <tr>
            <td><strong>Super Admin Request</strong></td>
            <td><em>User has requested to be changed to account Super Admin</em></td>
            
        </tr>
        <?php
        }
        ?>
    </table>