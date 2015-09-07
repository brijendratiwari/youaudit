    
    <h3>Standard Reports</h3>
    <div class="form_row">
	<label for="report_type">Report</label>
	<select name="report_type">
	    <option value="-1">Select</option>
	    <?php
		foreach ($arrReports as $arrReport)
		{
		    echo "<option value=\"".$arrReport['id']."\">".$arrReport['name']."</option>\r\n";
		}
	    ?>
	</select>
	<?php echo form_error('report_type'); ?>
    </div>
    
    

    <script>
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>
    <div class="form_row">
	<label for="report_startdate">Start Date</label> 
	<input type="input" name="report_startdate" value="<?php echo $strStartDate; ?>" class="datepicker" />
	<?php echo form_error('report_startdate'); ?>
    </div>

    <div class="form_row">
	<label for="report_enddate">End Date</label> 
	<input type="input" name="report_enddate" value="<?php echo $strEndDate; ?>" class="datepicker" />
	<?php echo form_error('report_enddate'); ?>
    </div>

    <div class="form_row">
	<label for="submit">Done?</label>
	<input class="button" type="submit" name="submit" value="Generate" />
    </div>

    </form>