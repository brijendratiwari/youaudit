    <h2>Delete Account: <?php echo $arrAccount['result'][0]->accountname; ?>?</h2>
    <?php echo form_open('admins/deleteaccount/'.$arrAccount['result'][0]->accountid.'/'); ?>
    <label for="safety">Are you sure?</label>
    <select name="safety">
	<option value=0 selected="selected">No</option>
	<option value=1>Yes</option>
    </select>
    
    <input type="submit" name="submit" value="Delete Account" />