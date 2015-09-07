<div class="box">
    <div class="heading">
      	<h1>Add Compliance Check</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_vehicle_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="tabs">
          <a href="#general_information" class="active">General Information</a>
        </div>
        
        <div class="content_main">
                <p>Use this form to add a compliance check to the account</p>
                <?php echo form_open('compliance/add/' . $id, array('id'=>'edit_vehicle_form')); ?>

                <script>
                 $(function() {
                    $(".datepicker").datepicker({ dateFormat: "yy/mm/dd" });
            });
        </script>

                <div id="general_information" class="form_block">
                    <input type="hidden" name="id" value="<?php print $id; ?>"/>              
                    <div class="form_row">
                        <label for="test_type_name">Check Name</label> 
                        <input type="input" name="test_type_name"/>
                        <?php echo form_error('checkname'); ?>
                    </div>

                    <div class="form_row">
                        <label for="test_type_category_id">Category</label> 
                        <select name="test_type_category_id">
                            <option value="">-- Please Select --</option>
                            <?php foreach($categories['results'] as $category) { ?>
                            <option value="<?php print $category->categoryid; ?>"><?php print $category->categoryname; ?></option>
                            <?php } ?>
                        </select>
                    </div>         

                    <div class="form_row">
                        <label for="test_type_mandatory">Mandatory</label> 
                        <select name="test_type_mandatory">
                            <option value="">-- Please Select --</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div> 

                    <div class="form_row">
                        <label for="test_type_frequency">Frequency</label> 
                        <select name="test_type_frequency">
                            <option value="">-- Please Select --</option>
                            <?php foreach($frequencies as $frequency) { ?>
                            <option value="<?php print $frequency['test_freq_id']; ?>"><?php print $frequency['test_frequency']; ?></option>
                            <?php } ?>
                        </select>
                    </div>  
                    
                    <div class="form_row">
                        <label for="test_type_notes">Notes/Instructions</label> 
                        <textarea name="test_type_notes"></textarea>
                    </div> 
                </div>
        </div>
                

            
      </form>
    
        </div>
    </div>
</div>