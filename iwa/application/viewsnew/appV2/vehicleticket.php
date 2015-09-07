<script type="text/javascript">
    function isaVehicle_doSendTicket(intItemId)
    {
        isaLogging_doConsoleLog('isaVehicle_doSendTicket('+intItemId+')');
        isa_doShowWhizzer();
        objData = {
            username: strGlobalUsername,
            password: strGlobalPassword,
            mode: "submit",
            ticket_subject: $('input[name="ticket-subject"]').val(),
            ticket_message: $('textarea[name="ticket-message"]').val(),
            timestamp: Math.round(new Date().getTime() / 1000) //to make the query unique
        };


        $.ajax({
            type:        'POST',
            url:         strWebServiceURI + "vehicleticket/" +intItemId,
            dataType:    'json',
            data:        objData,
            timeout:     intShortTimeout,
            success:     function(data){
                isa_doHideWhizzer();
                isa_Dashboard();
                if (data.booError == false)
                {

                    navigator.notification.alert(
                        "Ticket Sent",
                        false,
                        strServiceName,
                        "OK");
                }
                else
                {
                    navigator.notification.alert(
                        "There was a problem sending the ticket",
                        false,
                        strServiceName,
                        "OK");
                }
            },
            error:       function(){
                isa_doHideWhizzer();
                navigator.notification.alert(
                    "There was a problem communicating to the Internet",
                    false,
                    strServiceName,
                    "OK");
            }
        });

    }
</script>

<ul class="profile">
                <li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $objItem->itemphotoid; ?>') no-repeat center center;"></li>
                <li class="clearfix" id="item_text_holder"><h2><?php echo $arrVehicle['make']." ".$arrVehicle['model']; ?></h2><p><?php echo $objItem->categoryname; ?><br /><?php echo $arrVehicle['barcode']; ?></p></li>
            </ul>
            
            <ul>
                <li><strong>Note:</strong> This information above, along with your name and contact information will be automatically included in the ticket sent.</li>
            </ul>
            
            <ul class="form">
                <li class="header">Ticket Details</li>
                <li><input type="text" name="ticket-subject" id="ticket-subject" placeholder="Subject" /></li>
                <li><textarea name="ticket-message" id="ticket-message" placeholder="Further information" ></textarea></li>
                <li>
                    <label>Select Vehicle Status</label>
                    <select id="item-status" name="item-status" placeholder="Item Status">

                        <option id="1" value="1" <?php echo ($objItem->itemstatusid == 1 ? 'selected="selected"' : ''); ?>>OK</option>
                        <option id="2" value="2" <?php echo ($objItem->itemstatusid == 2 ? 'selected="selected"' : ''); ?>>Damaged</option>
                        <option id="3" value="3" <?php echo ($objItem->itemstatusid == 3 ? 'selected="selected"' : ''); ?>>Faulty</option>
                        <option id="6" value="6" <?php echo ($objItem->itemstatusid == 6 ? 'selected="selected"' : ''); ?>>Missing</option>
                    </select>
                </li>
            </ul>
            
            <p><a href="#" class="green button" onclick="isaVehicle_doSendTicket(<?php echo $arrVehicle['fleet_id']; ?>);">Send</a></p>