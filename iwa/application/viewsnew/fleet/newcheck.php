<div class="box">
    <div class="heading">
      	<h1>Create new vehicle check</h1>
        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#add_check_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
    </div>
    <div class="box_content">
        
        <div class="content_main">
            <p>Use this form to create a check for the vehicles</p>
            <?php echo form_open('fleet/newcheck', array('id'=>'add_check_form')); ?>
            
            <script>
             $(function() {
		$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>
            <div id="general_information" class="form_block">
                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="check_name">Check name</label></div>
                    <div class="col-md-4"><input type="input" class="form-control" name="check_name"/></div>
                    <?php echo form_error('check_name'); ?>
                </div>

                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="check_long_description">Check short description (for the app)</label></div>
                    <div class="col-md-4"><textarea name="check_short_description" class="form-control"></textarea></div>
                    <?php echo form_error('check_short_description'); ?>
                </div>

                <div class="form_row col-md-8">
                    <div class="col-md-4"><label for="check_long_description">Check long description</label></div>
                    <div class="col-md-4"><textarea name="check_long_description" class="form-control"></textarea></div>
                    <?php echo form_error('check_long_description'); ?>
                </div>



                

                
                
            </div>

            </form>
    
        </div>
    </div>
</div>