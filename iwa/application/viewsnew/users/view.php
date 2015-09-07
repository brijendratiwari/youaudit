<div class="box">
    <div class="heading">
      	<h1><?php echo $objUser->firstname; ?> <?php echo $objUser->lastname; ?></h1>
        <?php
        if ($arrSessionData['objSystemUser']->levelid > 2)
        {
        ?>
        <div class="buttons">
            <a href="<?php echo site_url('/users/edit/'.$objUser->userid); ?>" class="button">Edit User</a>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="box_content">

        <table class="list_table">
            <tbody>
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
            </tbody>
        </table>
    </div>