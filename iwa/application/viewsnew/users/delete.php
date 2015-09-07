<div class="box">
    <div class="heading">
      	<h1>Delete a User</h1>
        <div class="buttons">
            <a class="button" onclick="$('#delete_user_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">



    <p>Use this form to delete a user.  The user will actually be marked as inactive, preventing users
    putting items in their ownership.  <strong>The user will also be unable to log-in to the system.</strong></p>
    <p><strong>Note:</strong> <em>You will not be able to delete users that have active items linked to them.</em></p>
    
    <p>You are deleting <strong><?php echo $strFirstName; ?> <?php echo $strLastName; ?></strong></p>
    <?php echo form_open('users/delete/'.$intUserId.'/', array('id'=>'delete_user_form')); ?>