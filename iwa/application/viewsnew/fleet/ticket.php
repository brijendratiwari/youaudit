<div class="box">
    <div class="heading">
      	<h1>Raise a Support Ticket</h1>
        <div class="buttons">
        	<a class="button icon-with-text round" onclick="$('#item_ticket_form').submit();"><i class="fa fa-mail-forward"></i>Send</a>
        </div>
    </div>

    <div class="box_content">
        <div class="content_main">

        <p>You're raising a support ticket for this vehicle, which is a<?php
            $arrVowels = array('a','e','i','o','u','A','E','I','O','U');
            if (in_array(substr($strMake,0,1),$arrVowels))
            {
                echo 'n';
            }

        ?> <strong><?php echo $strMake." ".$strModel; ?></strong>.</p>
        <p>You don't need to put this information in your message, or your name, as this will be automatically added to your support ticket.</p>
        
    <?php echo form_open('fleet/raiseticket/'.$intItemId.'/', array('id' => 'item_ticket_form')); ?>
    <div class="form_block">

        <div class="form_row col-md-7">
            <div class="col-md-3"><label for="message_title">Subject</label></div> 
            <div class="col-md-4"><input type="input" class="form-control" name="message_title" value="<?php echo $strMessageTitle; ?>" /></div>
        </div>
    
        <div class="form_row col-md-7">
            <div class="col-md-3"><label for="message_body">Description
            <span class="form_help">Give as much detail as possible.</span>
            </label></div> 
            <div class="col-md-4"><textarea class="form-control" name="message_body"><?php echo $strMessageBody; ?></textarea></div>
            
        </div>
    </div>
    </form>
    
        </div>
    </div>
</div>