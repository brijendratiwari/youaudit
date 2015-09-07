<div class="box">
    <div class="heading">
      	<h1>Create new Vehicle Inspection Record</h1>
        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#add_mot_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
    </div>
    <div class="box_content">
        
        <div class="content_main">
            <p>Use this form to create a Vehicle Inspection record for this vehicle</p>
            <?php echo form_open('fleet/newmot/' . $vehicle_id, array('id'=>'add_mot_form')); ?>
            
            <script>
             $(function() {
		$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>
            <div id="general_information" class="form_block">
                <input type="hidden" name="vehicle_id" value="<?php print (isset($arrFleetId)) ? $arrFleetId : ""; ?>"/> 
                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="mot_cert_no">Vehicle Inspection Certificate No</label></div> 
                    <div class="col-md-4"><input type="input" class="form-control" name="mot_cert_no"/></div>
                    <?php echo form_error('mot_cert_no'); ?>
                </div>

                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="mot_date">Vehicle Inspection Date</label></div> 
                    <div class="col-md-4"><input type="input" name="mot_date" class="datepicker form-control"/></div>
                    <?php echo form_error('mot_date'); ?>
                </div>
                
                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="mot_expiry_date">Vehicle Inspection Expiry Date</label></div> 
                    <div class="col-md-4"><input type="input" name="mot_expiry_date" class="datepicker form-control"/></div>
                    <?php echo form_error('mot_expiry_date'); ?>
                </div>
                
                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="mot_result">Vehicle Inspection Result</label></div> 
                    <div class="col-md-4"><select name="mot_result" class="form-control">
                        <option value="" selected="selected">Please Select</option>
                        <option value="1">Pass</option>
                        <option value="0">Fail</option>
                    </select></div>
                    <?php echo form_error('mot_result'); ?>
                </div>
               
                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="mot_notes">Vehicle Inspection Notes</label></div>
                    <div class="col-md-4"><textarea class="form-control" name="mot_notes"></textarea></div>
                </div>



                

                
                
            </div>

            </form>
    
        </div>
    </div>
</div>