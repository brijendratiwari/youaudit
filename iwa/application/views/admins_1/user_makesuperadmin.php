    <h2>Make <?php echo $arrUser['result'][0]->firstname." ".$arrUser['result'][0]->lastname; ?> SuperAdmin of <?php
        echo $arrUser['result'][0]->accountname;
    ?>?</h2>
    <?php echo form_open('admins/makeSuperAdmin/'.$arrUser['result'][0]->userid.'/'); ?>
    <label for="safety">Are you sure?</label>
    <select name="safety">
	<option value=0 selected="selected">No</option>
	<option value=1>Yes</option>
    </select>
    
    <input type="submit" name="submit" value="Make SuperAdmin" />