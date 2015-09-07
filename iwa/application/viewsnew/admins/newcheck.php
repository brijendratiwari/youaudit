<div class="box">
    <div class="heading">
      	<h1>Create new default vehicle check</h1>
        <div class="buttons">
            <a class="button" onclick="$('#add_check_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        
        <div class="content_main">
            <p>Use this form to create a new default check for vehicles which applies across <strong>ALL</strong> accounts.</p>
            <?php echo form_open('admins/newcheck', array('id'=>'add_check_form')); ?>
            
            <script>
             $(function() {
		$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>
            <div id="general_information" class="form_block">
                <div class="form_row">
                    <label for="check_name">Check name</label>
                    <input type="input" name="check_name"/>
                    <?php echo form_error('check_name'); ?>
                </div>

                <div class="form_row">
                    <label for="check_short_description">Short Description (for app)</label>
                    <textarea name="check_short_description"></textarea>
                    <?php echo form_error('check_short_description'); ?>
                </div>

                <div class="form_row">
                    <label for="check_long_description">Long Description</label>
                    <textarea name="check_long_description"></textarea>
                    <?php echo form_error('check_long_description'); ?>
                </div>


                

                
                
            </div>

            </form>
    
        </div>
    </div>
</div>