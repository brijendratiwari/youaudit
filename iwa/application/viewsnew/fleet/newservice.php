<div class="box">
    <div class="heading">
      	<h1>Create new Service Record</h1>
        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#add_mot_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
    </div>
    <div class="box_content">

        <div class="content_main">
            <p>Use this form to create a Service record for this vehicle</p>
            <?php echo form_open('fleet/newservice/' . $vehicle_id, array('id'=>'add_mot_form')); ?>
            
            <script>
             $(function() {
		$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>
            <div id="general_information" class="form_block">
                <input type="hidden" name="vehicle_id" value="<?php print (isset($arrFleetId)) ? $arrFleetId : ""; ?>"/> 
                <div class="form_row col-md-7">
                    <div class="col-md-3"><label for="service_ref_no">Service Ref No</label></div> 
                    <div class="col-md-4"><input type="input" class="form-control" name="service_ref_no"/></div>
                    <?php echo form_error('service_ref_no'); ?>
                </div>

                <div class="form_row col-md-7">
                    <div class="col-md-3"><label for="service_date">Service Date</label></div> 
                    <div class="col-md-4"><input type="input" name="service_date" class="datepicker form-control"/></div>
                    <?php echo form_error('service_date'); ?>
                </div>

                <div class="form_row col-md-7">
                    <div class="col-md-3"><label for="service_expiry_date">Service Expiry Date</label></div> 
                    <div class="col-md-4"><input type="input" name="service_expiry_date" class="datepicker form-control"/></div>
                    <?php echo form_error('service_expiry_date'); ?>
                </div>
                
                <div class="form_row col-md-7">
                    <div class="col-md-3"><label for="service_notes">Service Notes</label></div> 
                    <div class="col-md-4"><textarea class="form-control" name="service_notes"></textarea></div>
                </div>



                

                
                
            </div>

            </form>
    
        </div>
    </div>
</div>