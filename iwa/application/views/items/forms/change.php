    <div class="form_row">
	<label for="user_id">New Owner</label>	
	<select name="user_id">
	    <option value="0">Unchanged</option>
	    <?php
		
		
		foreach ($arrUsers['results'] as $arrUser)
		{
		    if ($objItem->userid != $arrUser->userid)
		    {
			echo "<option ";
			echo 'value="'.$arrUser->userid.'" ';
			echo '>'.$arrUser->userfirstname." ".$arrUser->userlastname."</option>\r\n";
		    }
		}
	    ?>
	</select>
    </div>
    
    <div class="form_row">
	<label for="location_id">New Location</label>	
	<select name="location_id">
	    <option value="0">Unchanged</option>
	    <?php
		
		
		foreach ($arrLocations['results'] as $arrLocation)
		{
		    if ($objItem->locationid != $arrLocation->locationid)
		    {
			echo "<option ";
			echo 'value="'.$arrLocation->locationid.'" ';
			echo '>'.$arrLocation->locationname."</option>\r\n";
		    }
		}
	    ?>
	</select>
    </div>
    <div class="form_row">
	<label for="submit">Done?</label>
	<input class="button" type="submit" name="submit" value="Change" /> 
    </div>
    </form>