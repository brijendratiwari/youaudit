<div class="box">
    <div class="heading">
      	<h1>Request Super Admin Status</h1>
        <div class="buttons">
            <a class="button" onclick="$('#request_superadmin_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
           <div class="content_main">

    <p>Use this form to request that your user be made the <strong>Super Admin</strong> on this account.  The Super Admin user must agree to all "deletions" of items.</p>
    <p><strong>Note:</strong> <em>Only one Super Admin per Account - the existing Super Admin will be made a normal Admin user.</em></p>
    
    <p>You are requesting that this user account, in the name of <strong><?php echo $strFirstName; ?> <?php echo $strLastName; ?></strong>, be made <em>Super Admin</em> for <strong><?php echo $arrSessionData['objSystemUser']->accountname; ?></strong></p>
    <?php echo form_open('users/becomesuperadmin/', array('id'=>'request_superadmin_form')); ?>