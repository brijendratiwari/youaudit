<div class="box">
    <div class="heading">
      	<h1>Raise a Support Ticket</h1>
        <div class="buttons">
        	<a class="button" onclick="$('#item_ticket_form').submit();">Send</a>
        </div>
    </div>

    <div class="box_content">
        <div class="content_main">

        <p>You're raising a support ticket for item with the barcode <strong><?php echo $strBarcode; ?></strong>, which is a<?php
            $arrVowels = array('a','e','i','o','u','A','E','I','O','U');
            if (in_array(substr($strMake,0,1),$arrVowels))
            {
                echo 'n';
            }

        ?> <strong><?php echo $strMake." ".$strModel; ?></strong>.</p>
        <p>You don't need to put this information in your message, or your name, as this will be automatically added to your support ticket.</p>
        
    <?php echo form_open('items/raiseticket/'.$intItemId.'/', array('id' => 'item_ticket_form')); ?>
    <div class="form_block">

        <div class="form_row">
            <label for="message_title">Subject</label> 
            <input type="input" name="message_title" value="<?php echo $strMessageTitle; ?>" />
        </div>
    
        <div class="form_row">
            <label for="message_body">Description
            <span class="form_help">Give as much detail as possible.</span>
            </label> 
            <textarea name="message_body"><?php echo $strMessageBody; ?></textarea>
        </div>

        <div class="form_row">
            <label for="ticket_priority">Priority Label</label>
            <select name="ticket_priority">
                <option value="1">Low</option>
                <option value="2">Medium</option>
                <option value="3">High</option>
                <option value="4">Critical</option>
            </select>
        </div>

    </div>
    </form>
    
        </div>
    </div>
</div>