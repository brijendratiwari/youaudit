
            <ul class="profile">
                <li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $objItem->itemphotoid; ?>') no-repeat center center;"></li>
                <li class="clearfix" id="item_text_holder"><h2><?php echo $objItem->manufacturer." ".$objItem->model; ?></h2><p><?php echo $objItem->categoryname; ?></p></li>
            </ul>
            
            <ul>
                <li>Please select status you wish item to be changed to</li>
            </ul>
            
            <ul class="form">
                <li>
                    <label>Select Item Status</label>
                    <select id="item-status" name="item-status" placeholder="Item Status">

                        <option id="1" value="1" <?php echo ($objItem->itemstatusid == 1 ? 'selected="selected"' : ''); ?>>OK</option>
                        <option id="2" value="2" <?php echo ($objItem->itemstatusid == 2 ? 'selected="selected"' : ''); ?>>Damaged</option>
                        <option id="3" value="3" <?php echo ($objItem->itemstatusid == 3 ? 'selected="selected"' : ''); ?>>Faulty</option>
                        <option id="6" value="6" <?php echo ($objItem->itemstatusid == 6 ? 'selected="selected"' : ''); ?>>Missing</option>
                    </select>
                </li>
            </ul>
            
            <p><a href="#" class="green button" onclick="isaItem_doChangeStatus(<?php echo $objItem->itemid; ?>);">Save</a></p>