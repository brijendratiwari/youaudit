<div class="box">
    <div class="heading">
      	<h1>Reactivate a User</h1>
        <div class="buttons">
            <a class="button" onclick="$('#reactivate_user_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">


    <p>Use this form to reactivate a user.  <strong>The user will then be able to log-in to the system.</strong></p>
    
    
    <p>You are reactivating the account belonging to <strong><?php echo $strFirstName; ?> <?php echo $strLastName; ?></strong></p>
    <?php echo form_open('users/reactivate/'.$intUserId.'/', array('id'=>'reactivate_user_form')); ?>