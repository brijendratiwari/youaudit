<div class="box">
    <div class="heading">
        <h1>Delete <?php echo $arrAdmin['result'][0]->firstname." ".$arrAdmin['result'][0]->lastname; ?></h1>
        <div class="buttons">
            <a class="button" onclick="$('#delete_sys_form').submit();">Save</a>
        </div>
    </div>  
    
    <div class="box_content">
        <div class="content_main">
            <p><strong>Note: </strong><em>When you delete the user, the user will actually be marked as inactive, the user will be unable to log-in to the system.</em></p>
            <p>You are deleting <strong><?php echo $arrAdmin['result'][0]->firstname." ".$arrAdmin['result'][0]->lastname; ?></strong></p>
            <p><em>Please note, the system will not allow the removal of all SysAdmins.</em></p>
    <?php echo form_open('admins/deleteadmin/'.$arrAdmin['result'][0]->adminid.'/', array('id' => 'delete_sys_admin')); ?>
            <div class="form_row">
        <label for="safety">Are you sure?</label>
        <select name="safety">
	    <option value="0" selected="selected">No</option>
	    <option value="1">Yes</option>
	</select>	
	
            </div>
        </div>
    </div>
</div>