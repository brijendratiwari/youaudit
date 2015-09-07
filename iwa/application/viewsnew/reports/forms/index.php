  <div class="form_block">
      <div class="form_row">
	<label for="report_type">Report</label>
	<select id="select" name="report_type" class="form-control text_width">
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
      <script type="text/javascript">
          $(document).ready(function(){

              $('#select').change(function(){

                  switch(parseInt($(this).val())){
                      case 3:
                      case 4:
                      case 5:
                      case 7:
                          $('.date_row').hide();
                          break;
                      case 11:
                          $('.date_row').hide();
                          $('.location_row').show();
                          break;
                      default :
                          $('.date_row').show();
                          $('.location_row').hide();
                          break;
                  }
              });

              $('#select').trigger('change');
          })
      </script>
        <script>
            $(function() {
                    $( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
            });
        </script>
        <div class="form_row date_row">
            <label for="report_startdate">Start Date</label>
            <input type="input" name="report_startdate" value="<?php echo $strStartDate; ?>" class="datepicker form-control text_width" />
            <?php echo form_error('report_startdate'); ?>
        </div>

        <div class="form_row date_row">
            <label for="report_enddate">End Date</label>
            <input type="input" name="report_enddate" value="<?php echo $strEndDate; ?>" class="datepicker form-control text_width" />
            <?php echo form_error('report_enddate'); ?>
        </div>

      <div style="display: none;" class="form_row location_row form-control text_width">
          <label for="report_location">Location</label>
          <select id="select" name="report_location">
              <option value="0">All</option>
              <?php

              foreach ($arrLocations as $location)
              {
                  echo "<option value=\"".$location->locationid."\">".$location->locationname."</option>\r\n";
              }
              ?>

          </select>
          <?php echo form_error('report_location'); ?>
      </div>

        <button id="generate" type="submit" class="btn btn-primary">GENERATE</button>
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