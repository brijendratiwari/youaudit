<div class="form_block">    
    <div class="form_row">
	<label for="user_id">New Owner</label>	
	<select name="user_id" class="form-control text_width">
	    <option value="0">Unchanged</option>
	    <?php
		
		
		foreach ($arrUsers['results'] as $arrUser)
		{
		    /*if ($objItem->userid != $arrUser->userid)
		    {
			echo "<option ";
			echo 'value="'.$arrUser->userid.'" ';
			echo '>'.$arrUser->userfirstname." ".$arrUser->userlastname."</option>\r\n";
		    }*/
			echo "<option ";
			echo 'value="'.$arrUser->userid.'" ';
                        echo ($objItem->userid == $arrUser->userid) ? "selected" : "";
			echo '>'.$arrUser->userfirstname." ".$arrUser->userlastname."</option>\r\n";                   

		}
	    ?>
	</select>
    </div>
    
    <div class="form_row">
	<label for="location_id">New Location</label>	
        <select name="location_id" id="location_id" class="form-control text_width">
	    <option value="0">Unchanged</option>
	    <?php
		
		
		/*foreach ($arrLocations['results'] as $arrLocation)
		{
		    if ($objItem->locationid != $arrLocation->locationid)
		    {
			echo "<option ";
			echo 'value="'.$arrLocation->locationid.'" ';
			echo '>'.$arrLocation->locationname."</option>\r\n";
		    }
		}*/
                foreach ($arrLocations['results'] as $arrLocation)
		{

			echo "<option ";
			echo 'value="'.$arrLocation->locationid.'" ';
                        echo ($objItem->locationid == $arrLocation->locationid) ? "selected" : "";
			echo '>'.$arrLocation->locationname."</option>\r\n";
		}
	    ?>
	</select>
    </div>
    
    <div class="form_row">
	<label for="site_id">New Site</label>	
	<select name="site_id" id="site_id" class="form-control text_width">
	    <option value="0">Unchanged</option>
	    <?php
		
		
		foreach ($arrSites['results'] as $arrSite)
		{
		    /*if ($objItem->siteid != $arrSite->siteid)
		    {
			echo "<option ";
			echo 'value="'.$arrSite->siteid.'" ';
			echo '>'.$arrSite->sitename."</option>\r\n";
		    }*/
                    
			echo "<option ";
			echo 'value="'.$arrSite->siteid.'" ';
                        echo ($objItem->siteid == $arrSite->siteid) ? "selected" : "";
			echo '>'.$arrSite->sitename."</option>\r\n";
		}
	    ?>
	</select>
    </div>
</div>
    </form>
    </div>
    </div>
</div>
<style>
    .text_width{
        width:20%;
    }
</style>