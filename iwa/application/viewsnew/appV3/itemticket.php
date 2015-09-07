            <ul class="profile">
                <li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $objItem->itemphotoid; ?>') no-repeat center center;"></li>
                <li class="clearfix" id="item_text_holder"><h2><?php echo $objItem->manufacturer." ".$objItem->model; ?></h2><p><?php echo $objItem->categoryname; ?><br /><?php echo $objItem->barcode; ?></p></li>
            </ul>
            
            <ul>
                <li><strong>Note:</strong> This information above, along with your name and contact information will be automatically included in the ticket sent.</li>
            </ul>
            
            <ul class="form">
                <li class="header">Ticket Details</li>
                <li><input type="text" name="ticket-subject" id="ticket-subject" placeholder="Subject" /></li>
                <li><textarea name="ticket-message" id="ticket-message" placeholder="Further information" ></textarea></li>
            </ul>
            
            <p><a href="#" class="green button" onclick="isaItem_doSendTicket(<?php echo $objItem->itemid; ?>);">Send</a></p>