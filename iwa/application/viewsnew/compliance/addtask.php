<script>
    $(document).ready(function() {
        $('#measurement_type').prop('disabled',true);
         $('body').on('change','#type_of_task',function(){
            
                if($(this).val() == 1){
                    $('#measurement_type').prop('disabled',false);
                }else{
                    $('#measurement_type').prop('disabled',true);
                    
                }
            });
    
    });
</script>

<div class="box">
    <div class="heading">
        <h1>Add New Task</h1>
        <div class="buttons">
            <a class="button" onclick="$('#add_new_task').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="tabs">
            <a href="#general_information" class="active">General Information</a>
        </div>

        <div class="content_main">
            <p>Use this form to add a Task</p>
            <?php echo form_open('compliance/addtask/', array('id' => 'add_new_task')); ?>

            <script>
                $(function() {
                    $(".datepicker").datepicker({dateFormat: "yy/mm/dd"});
                });
            </script>

            <div id="general_information" class="form_block">
                <!--<input type="hidden" name="id" value="<?php // print $id; ?>"/>-->              
                <div class="form_row">
                    <label for="test_type_name">Task Name</label> 
                    <input type="input" name="task_name"/>
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
                    <select name="measurement_type" id="measurement_type">
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