    <div class="form_row">
	<label for="name">Location Name*</label>
	<input type="text" name="name" value="<?php echo $strName; ?>" />
	<?php echo form_error('name'); ?>
    </div>
    <div class="form_row">
	<label for="barcode">Location Barcode</label>
	<input type="text" name="barcode" value="<?php echo $strBarcode; ?>" />
    <?php echo form_error('barcode'); ?>
    </div>
    <div class="form_row">
	<label for="submit">Done?</label>
	<input class="button" type="submit" name="submit" value="Go" />
    </div>

    </form>