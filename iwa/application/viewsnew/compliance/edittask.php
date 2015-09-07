<script>
  $(document).ready(function() {
            $("#type_of_task option[value=<?php echo $test["type_of_task"];  ?>]").attr('selected','selected'); 
            $("#measurement_type option[value=<?php echo $test["measurement"];  ?>]").attr('selected','selected'); 
            
            if(<?php echo $test["type_of_task"];  ?>)
            {
                $("#measurement_type").prop('disabled',false);
            }
            
    $('body').on('change','#type_of_task',function(){

        if(+$(this).val()){
            $('#measurement_type').prop('disabled',false);
        }
        else{
            $('#measurement_type').prop('disabled',true);
        }
    });
  });
</script>
<div class="box">
    <div class="heading">
      	<h1>Edit Task Check</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_vehicle_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="tabs">
          <a href="#general_information" class="active">General Information</a>
        </div>
        
        <div class="content_main">
                <p>Use this form to Edit Task details</p>
                <?php echo form_open('compliance/edittask/'. $id, array('id'=>'edit_vehicle_form')); ?>

                <script>
                 $(function() {
                    $(".datepicker").datepicker({ dateFormat: "yy/mm/dd" });
            });
        </script>

                <div id="general_information" class="form_block">
                    <input type="hidden" name="id" value="<?php print $id; ?>"/>              
                    <div class="form_row">
                        <label for="test_type_name">Check Name</label> 
                        <input type="input" name="test_type_name" value="<?php print $test['task_name']; ?>"/>
                        <?php echo form_error('checkname'); ?>
                    </div>
                 <div class="form_row">
                    <label for="test_type_category_id">Type Of Task</label> 
                    <select name="type_of_task" id="type_of_task">
                        <option value="">-- Please Select --</option>
                        <option value="0">Standard</option>
                        <option value="1">Numerical</option>
                    </select>
                </div>         

                <div class="form_row">
                    <label for="Measurement_type">Measurement</label> 
                    <select disabled="true" name="measurement_type" id="measurement_type">
                        <option value="">-- Please Select --</option>
                        <?php foreach ($allMeasurements as $key => $value) { ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['measurement_name']; ?></option>
                        <?php } ?>
                    </select>
                </div> 
                   
                </div>
        </div>
                

            
      </form>
    
        </div>
    </div>
</div>