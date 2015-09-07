    <h2>Delete <?php echo $arrAdmin['result'][0]->firstname." ".$arrAdmin['result'][0]->lastname; ?></h2>
    <ul>
	<li>Nickname: <?php echo $arrAdmin['result'][0]->nickname; ?></li>
	<li>Username/Email: <?php echo $arrAdmin['result'][0]->username; ?></li>
    </ul>
    <?php echo form_open('admins/deleteadmin/'.$arrAdmin['result'][0]->adminid.'/'); ?>
	<label for="safety">Are you sure?</label>
	<select name="safety">
	    <option value=0 selected="selected">No</option>
	    <option value=1>Yes</option>
	</select>
	
	<input type="submit" name="submit" value="Delete SysAdmin" />
	
	<p>Please note, the system will not allow the removal of all SysAdmins.</p>