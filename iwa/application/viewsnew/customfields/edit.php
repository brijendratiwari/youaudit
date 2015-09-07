<div class="box">
    <div class="heading">
        <h1>Edit a Custom Field</h1>
        <div class="buttons">
            <a class="button" onclick="$('#customfields').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
            <p>Use this form to edit custom field</p>
            <?php echo form_open('customfields/edit/' . $custom_field->id, array('id' => 'customfields')); ?>
            <div class="form_row">
                <label for="name">Field Name*</label>
                <input type="text" name="field_name" value="<?=$custom_field->field_name?>">
                <?php echo form_error('field_name'); ?>
            </div>

            </form>
        </div>
    </div>
</div>