<div class="box">
    <div class="heading">
        <h1>Current SysAdmins</h1>
        <div class="buttons">
            <a href="<?php echo site_url('/admins/create/'); ?>" class="button">Create SysAdmin</a>
        </div>
    </div>
    
    <table class="list_table">
        <thead>
            <tr>
                <th class="left">Username</th>
                <th class="left">Email</th>
                <th class="right action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($arrAdmins['results'])>0) {
                foreach ($arrAdmins['results'] as $arrAdmin) {
                    
            ?>
            <tr>
            <td><?php print $arrAdmin->firstname . " " . $arrAdmin->lastname;?></td>
            <td><?php print $arrAdmin->username; ?></td>
            <td class="right">
                <a href="<?php echo site_url('/admins/edit/'.$arrAdmin->adminid.'/'); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                <?php if ($arrSessionData['objAdminUser']->id != $arrAdmin->adminid) { ?>
                <a href="<?php echo site_url('/admins/deleteAdmin/'.$arrAdmin->adminid.'/'); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                <?php } ?>
            </td>
            </tr>
            <?
                    
                }
            }
            ?>
        </tbody>
    </table>

</div>