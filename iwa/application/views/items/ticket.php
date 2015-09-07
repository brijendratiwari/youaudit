<h2>Raise a Support Ticket</h2>

<p>You're raising a support ticket for item with the barcode <strong><?php echo $strBarcode; ?></strong>, which is a<?php
    $arrVowels = array('a','e','i','o','u','A','E','I','O','U');
    if (in_array(substr($strMake,0,1),$arrVowels))
    {
        echo 'n';
    }

?> <strong><?php echo $strMake." ".$strModel; ?></strong>.</p>
<p>You don't need to put this information in your message, or your name, as this will be automatically added to your support ticket.</p>

    <?php echo form_open('items/raiseticket/'.$intItemId.'/'); ?>
    <div class="form_row">
	<label for="message_title">Subject</label> 
	<input type="input" name="message_title" value="<?php echo $strMessageTitle; ?>" />
    </div>
    
    <div class="form_row">
	<label for="message_body">Description</label> 
        <textarea name="message_body"><?php echo $strMessageBody; ?></textarea>
        <span class="explanation">Give as much detail as possible.</span>
    </div>


    <div class="form_row">
	<label for="submit">All done?</label>
	<input class="button" type="submit" name="submit" value="Send" />
    </div>

    </form>