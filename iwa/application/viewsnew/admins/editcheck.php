<div class="box">
    <div class="heading">
      	<h1>Edit vehicle check</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_vehicle_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
            <p>Use this form to edit this vehicle check</p>
            <?php echo form_open('admins/editcheck/' . $objCheck->id, array('id'=>'edit_vehicle_form')); ?>

            <input type="hidden" name="id" value="<?php print $objCheck->id; ?>"/>
            <div class="form_row">
                <label for="make">Check name</label>
                <input type="input" name="check_name" value="<?php print $objCheck->check_name; ?>"/>
                <?php echo form_error('check_name'); ?>
            </div>

            <div class="form_row">
                <label for="model">Check short description (Displays on App)</label>
                <textarea name="check_long_description"><?php print $objCheck->check_short_description; ?></textarea>
                <?php echo form_error('check_long_description'); ?>
            </div>

            <div class="form_row">
                <label for="model">Check full description</label>
                <textarea name="check_short_description"><?php print $objCheck->check_long_description; ?></textarea>
                <?php echo form_error('check_short_description'); ?>
            </div>

        </div>
                

            
      </form>
    
        </div>
    </div>
</div>