    <div class="form_row">
	<label for="name">Faculty Name*</label>
	<input type="text" name="name" value="<?php echo $strName; ?>" />
	<?php echo form_error('name'); ?>
    </div>
    
    <div class="form_row">
	<label for="submit">Done?</label>
	<input class="button" type="submit" name="submit" value="Go" />
    </div>

    </form>