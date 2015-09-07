 <h2>Barcode Search</h2>
    <p>Use this form to search for an item</p>
    <?php echo form_open('items/findByBarcode/'); ?>
    <div class="form_row">
	<label for="barcode">Barcode*</label>	
	<input name="barcode" type="text" value="<?php echo $strBarcode; ?>" />
    </div>
    <div class="form_row">
	<label for="submit">Ready?</label>
	<input class="button" type="submit" name="submit" value="Search" /> 
    </div>
    </form>