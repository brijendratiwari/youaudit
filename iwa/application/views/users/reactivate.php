    <h2>Reactivate a user</h2>
    <p>Use this form to reactivate a user.  <strong>The user will then be able to log-in to the system.</strong></p>
    
    
    <p>You are reactivating the account belonging to <strong><?php echo $strFirstName; ?> <?php echo $strLastName; ?></strong></p>
    <?php echo form_open('users/reactivate/'.$intUserId.'/'); ?>