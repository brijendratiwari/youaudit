<div class="box">
    <div class="heading">
      	<h1>Create new Tax Record</h1>
        <div class="buttons">
            <a class="button" onclick="$('#add_tax_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">

        
        <div class="content_main">
            <p>Use this form to create a Tax record for this vehicle</p>
            <?php echo form_open('fleet/newtax/' . $vehicle_id, array('id'=>'add_tax_form')); ?>
            
            <script>
             $(function() {
		$(".datepicker").datepicker({ dateFormat: "yy/mm/dd" });
	});
    </script>
            <div id="general_information" class="form_block">
                <input type="hidden" name="vehicle_id" value="<?php print (isset($arrFleetId)) ? $arrFleetId : ""; ?>"/> 
                <div class="form_row">
                    <label for="tax_disc_no">Tax Disc No</label> 
                    <input type="input" name="tax_disc_no"/>
                    <?php echo form_error('tax_disc_no'); ?>
                </div>

                <div class="form_row">
                    <label for="tax_date">Tax Date</label> 
                    <input type="input" name="tax_date" class="datepicker"/>
                    <?php echo form_error('tax_date'); ?>
                </div>
               
                <div class="form_row">
                    <label for="tax_notes">Tax Notes</label> 
                    <textarea name="tax_notes"></textarea>
                </div>



                

                
                
            </div>

            </form>
    
        </div>
    </div>
</div>