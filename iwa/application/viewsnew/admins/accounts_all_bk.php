<div class="box">
    <div class="heading"><h1>Current Accounts</h1>
        <div class="buttons">
            <!--<a href="https://dev.ictracker.co.uk/iwa/index.php/admins/createaccount" class="button">Add Account</a>-->
           <a href="<?php echo site_url('/admins/createaccount/'); ?>" class="button">Add Account</a>
        </div>
    </div>


    <table class="list_table">
        <thead>
            <tr>
                <th class="left">Name</th>
                <th class="left">Location</th>
                <th class="right action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($arrAccounts['results'])>0) {
                foreach ($arrAccounts['results'] as $arrAccount) {
            ?>
            <tr>
            <td><?php
            if ($arrAccount->accountactive != 0) {
                print $arrAccount->accountname;
            } else {
                print $arrAccount->accountname . " (Marked as deleted)";
            }
            ?>
            </td>
            <td><?php print $arrAccount->accountcity; ?></td>
            <td class="right">
                <a href="<?php echo site_url('/admins/viewusers/'.$arrAccount->accountid.'/'); ?>"><img src="/img/icons/16/users.png" title="View Users" alt="View Users" /></a>
                <a href="<?php echo site_url('/admins/editaccount/'.$arrAccount->accountid.'/'); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                <?php if ($arrAccount->accountactive != 0) { ?>
                <a href="<?php echo site_url('/admins/deleteaccount/'.$arrAccount->accountid.'/'); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                <?php } else { ?>
                <a href="<?php echo site_url('/admins/reactivateaccount/'.$arrAccount->accountid.'/'); ?>"><img src="/img/icons/16/refresh.png" title="Refresh" alt="Refresh" /></a>
                <?php } ?>
            </td>
            </tr>
            <?php
            
                }
            }
            ?>
        </tbody>
    </table>
</div>