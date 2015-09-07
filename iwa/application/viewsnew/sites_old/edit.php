
<div class="box">
    <div class="heading">
      	<h1>Edit Site</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_site_form').submit();">Save</a>
        </div>
    </div>
    
    <div class="box_content">
        
        <div class="tabs">
            <a href="#general_information">Address</a>
            <a href="#geolocation">Geo Location</a>
        </div>
        <div class="content_main">
        <p>Use this form to edit site details</p>
        <?php echo form_open('sites/editone/'.$intSiteId.'/', array('id'=>'edit_site_form')); ?>
            <div id="general_information" class="form_block">
                <div class="form_row">
                    <label for="name">Site Name</label>
                    <input type="text" name="name" value="<?php echo $strName; ?>" />
                    <?php echo form_error('name'); ?>
                </div>

                <div class="form_row">
                    <label for="address1">Address 1</label>
                    <input type="input" name="address1" value="<?php echo (isset($siteData['address1']) ? $siteData['address1'] : ''); ?>" />
                    <?php echo form_error('address1'); ?>
                </div>
                
                <div class="form_row">
                    <label for="address2">Address 2</label>
                    <input type="input" name="address2" value="<?php echo (isset($siteData['address2']) ? $siteData['address2'] : ''); ?>" />
                    <?php echo form_error('address2'); ?>
                </div>
                
                <div class="form_row">
                    <label for="city">Town/City</label>
                    <input type="input" name="city" value="<?php echo (isset($siteData['city']) ? $siteData['city'] : ''); ?>" />
                    <?php echo form_error('city'); ?>
                </div>
                
                <div class="form_row">
                    <label for="city">County</label>
                    <input type="input" name="county" value="<?php echo (isset($siteData['county']) ? $siteData['county'] : ''); ?>" />
                    <?php echo form_error('county'); ?>
                </div>
                
                <div class="form_row">
                    <label for="city">Postcode</label>
                    <input type="input" name="postcode" value="<?php echo (isset($siteData['postcode']) ? $siteData['postcode'] : ''); ?>" />
                    <?php echo form_error('postcode'); ?>
                </div>
                

                


            </div>
        
            <div id="geolocation" class="form_block">
                <div class="form_row">
                    <label for="lat">Latitude</label>
                    <input type="input" name="lat" value="<?php echo (isset($siteData['lat']) ? $siteData['lat'] : ''); ?>" />
                    
                </div>
                <div class="form_row">
                    <label for="lon">Longitude</label>
                    <input type="input" name="lon" value="<?php echo (isset($siteData['lon']) ? $siteData['lon'] : ''); ?>" />
                    
                </div>
            </div>
       </div>
    </div>
</div>