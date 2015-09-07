            <ul class="profile">
                <li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $objItem->itemphotoid; ?>') no-repeat center center;"></li>
                <li class="clearfix" id="item_text_holder"><h2><?php echo $objItem->manufacturer." ".$objItem->model; ?></h2><p><?php echo $objItem->categoryname; ?><br /><?php echo $objItem->barcode; ?></p></li>
            </ul>
            
            <ul class="form">
                <li class="header">Item Image</li>
                <li class="arrow"><a onclick="isaCamera_replacePhoto();"><img src="img/icon-camera.png" width="29" class="ico"> Take Photo</a></li>
                <li>
                    <input type="hidden" value="" name="photo_item_image" />
                    <input type="hidden" name="photo_photo_present" value="false" />
                    <img id="photo_smallImage" src="" />
                </li>
            </ul>
            
            <p><a href="#" class="green button" onclick="isaItem_doUploadPhoto(<?php echo $objItem->itemid; ?>);">Save</a></p>