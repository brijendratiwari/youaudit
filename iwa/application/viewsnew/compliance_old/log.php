<div class="box">
    <div class="heading">
      	<h1>Log Compliance Check</h1>
        <div class="buttons">
            <a class="button" onclick="$('#log_check_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="tabs">
          <a href="#general_information" class="active">General Information</a>
        </div>
        
        <div class="content_main">
                <p>Use this form to log compliance check for this item (item make - Model) </p>
                <?php echo form_open('compliance/log/' . $id, array('id'=>'log_check_form')); ?>

                <script>
                 $(function() {
                    $(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
            });
        </script>

                <div id="general_information" class="form_block">
                    <input type="hidden" name="test_item_id" value="<?php print $id; ?>"/>                 

                    <div class="form_row">
                        <label for="test_type">Compliance Check</label> 
                        <select name="test_type">
                            <option value="">-- Please Select --</option>
                            <?php foreach ($checks as $check) { ?>

                            <option value="<?php echo $check['test_type_id']; ?>"><?php echo $check['test_type_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div> 
                    
                    <div class="form_row">
                        <label for="test_date">Check Date</label> 
                        <input type="input" name="test_date" class="datepicker" value="<?php print ($check['check_date'] > 0) ? $check['check_date'] : ""; ?>">
                    </div>  
                    
                    <div class="form_row">
                        <label for="result">Result</label> 
                        <select name="result">
                            <option value="1">-- Please Select --</option>
                            <option value=1">Pass</option>
                            <option value="0">Fail</option>
                        </select>
                    </div>  
                    
                    <div class="form_row">
                        <label for="test_notes">Notes/Instructions</label> 
                        <textarea name="test_notes"></textarea>
                    </div> 
                </div>
        </div>
                

            
      </form>
    
        </div>
    </div>
</div>