<div class="box">
    <div class="heading">
        <h1>Add Custom Field</h1>
        <div class="buttons">
            <a class="button" onclick="$('#customfields').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
            <p>Use this form to add a new custom field</p>
            <?php echo form_open('customfields/add/', array('id' => 'customfields')); ?>

            <div class="form_row">
                <label for="field_name">Field Name*</label>
                <input type="text" name="field_name" value="">
                <?php echo form_error('field_name'); ?>
            </div>

            </form>
        </div>
    </div>
</div>