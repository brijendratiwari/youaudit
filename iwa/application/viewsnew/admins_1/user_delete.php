    <h2>Delete <?php echo $arrUser['result'][0]->firstname." ".$arrUser['result'][0]->lastname; ?></h2>
    <ul>
	<li>Nickname: <?php echo $arrUser['result'][0]->nickname; ?></li>
	<li>Username/Email: <?php echo $arrUser['result'][0]->username; ?></li>
    </ul>
    <?php echo form_open('admins/deleteuser/'.$arrUser['result'][0]->userid.'/'); ?>
	<label for="safety">Are you sure?</label>
	<select name="safety">
	    <option value=0 selected="selected">No</option>
	    <option value=1>Yes</option>
	</select>
	
	<input type="submit" name="submit" value="Delete User" />