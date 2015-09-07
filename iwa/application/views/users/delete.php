    <h2>Delete a user</h2>
    <p>Use this form to delete a location for users.  The user will actually be marked as inactive, preventing users
    putting items in their ownership.  <strong>The user will also be unable to log-in to the system.</strong></p>
    <p><strong>Note:</strong> <em>You will not be able to delete users that have active items linked to them.</em></p>
    
    <p>You are deleting <strong><?php echo $strFirstName; ?> <?php echo $strLastName; ?></strong></p>
    <?php echo form_open('users/delete/'.$intUserId.'/'); ?>