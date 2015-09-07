    <h2>Add Multiple Items</h2>
    <p>Use this form to add multiple similar items.  Certain fields will be auto-filled from the first item.</p>
    <?php echo form_open_multipart('items/addmultiple/'); ?>
    <div class="form_row">
	<label for="add_another">Add Another</label> 
	<input type="checkbox" name="add_another" <?php if ($booAddAnother) { echo "checked=\"checked\""; } ?> value="1" />
    </div>