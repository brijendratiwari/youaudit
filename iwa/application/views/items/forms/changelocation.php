    <div class="form_row">
	<label for="user_id">New Owner</label>	
	<select name="user_id">
	    <option value="0">Select</option>
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
	<label for="submit">Done?</label>
	<input class="button" type="submit" name="submit" value="Change Owner" /> 
    </div>
    </form>