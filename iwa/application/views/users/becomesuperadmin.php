    <h2>Request Super Admin Status</h2>
    <p>Use this form to request that your user be made the <strong>Super Admin</strong> on this account.  The Super Admin user must agree to all "deletions" of items.</p>
    <p><strong>Note:</strong> <em>Only one Super Admin per Account - the existing Super Admin will be made a normal Admin user.</em></p>
    
    <p>You are requesting that this user account, in the name of <strong><?php echo $strFirstName; ?> <?php echo $strLastName; ?></strong>, be made <em>Super Admin</em> for <strong><?php echo $arrSessionData['objSystemUser']->accountname; ?></strong></p>
    <?php echo form_open('users/becomesuperadmin/'); ?>