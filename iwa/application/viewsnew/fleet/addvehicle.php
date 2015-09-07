<script type="text/javascript">
$(function () {
$('.checkall').on('click', function () {

$(this).closest('fieldset').find(':checkbox').prop('checked', this.checked);

});
});
</script>

<div class="box">
    <div class="heading">
      	<h1>Add a Vehicle</h1>
        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#add_vehicle_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="tabs">
            <ul>
                <li><a href="#general_information" class="active">General Information</a></li>
                <li><a href="#vehicle_dates">Vehicle Dates</a></li>
                <li><a href="#item_ownership">Ownership</a></li>
                <li><a href="#item_checks">Checks</a></li>
            </ul>   
        </div>
        
        <div class="content_main">
            <p>Use this form to add user to the account</p>

            <?php echo form_open('fleet/addvehicle/', array('id'=>'add_vehicle_form')); ?>
            
            <script>
             $(function() {
		$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>

            <div id="general_information" class="form_block">

                <div class="form_row col-md-6">
                    <div class="col-md-2"><label for="make">Make</label></div> 
                    <div class="col-md-4"><input type="input" class="form-control" name="make" <?php (isset($arrPosted['make'])) ? print "value=\"" . $arrPosted['make'] . "\"" : print ""; ?>/></div>
                    <?php echo form_error('make'); ?>
                </div>
                
                <div class="form_row col-md-6">
                     <div class="col-md-2"><label for="model">Model</label></div>
                     <div class="col-md-4"><input type="input" class="form-control" name="model" <?php (isset($arrPosted['model'])) ? print "value=\"" . $arrPosted['model'] . "\"" : print ""; ?>/></div>
                    <?php echo form_error('model'); ?>
                </div>

                <div class="form_row col-md-6">
                     <div class="col-md-2"><label for="model">QR Code</label></div>
                     <div class="col-md-4"><input type="input" class="form-control" name="vehicle_barcode" <?php (isset($arrPosted['vehicle_barcode'])) ? print "value=\"" . $arrPosted['vehicle_barcode'] . "\"" : print ""; ?>/></div>
                    <?php echo form_error('vehicle_barcode'); ?>
                </div>
                <div class="form_row col-md-6">
                     <div class="col-md-2"><label for="year">Year</label></div> 
                     <div class="col-md-4"><input type="input" class="form-control" name="year" <?php (isset($arrPosted['year'])) ? print "value=\"" . $arrPosted['year'] . "\"" : print ""; ?>/></div>
                </div>            
                <div class="form_row col-md-6">
                    <div class="col-md-2"><label for="engine_size">Engine Size</label></div> 
                    <div class="col-md-4"><input type="input" class="form-control" name="engine_size" <?php (isset($arrPosted['engine_size'])) ? print "value=\"" . $arrPosted['engine_size'] . "\"" : print ""; ?>/></div>
                </div>
                
                <div class="form_row col-md-6">
                    <div class="col-md-2"><label for="reg_no">Registration No</label></div> 
                    <div class="col-md-4"><input type="input" class="form-control" name="reg_no" <?php (isset($arrPosted['reg_no'])) ? print "value=\"" . $arrPosted['reg_no'] . "\"" : print ""; ?>/></div>
                    <?php echo form_error('reg_no'); ?>
                </div>
                
                
                <div class="form_row col-md-6">
                    <div class="col-md-2"><label for="vehicle_value">Vehicle Value (<?php echo $currency; ?>)</label></div> 
                    <div class="col-md-4"><input type="input" class="form-control" name="vehicle_value" <?php (isset($arrPosted['vehicle_value'])) ? print "value=\"" . $arrPosted['vehicle_value'] . "\"" : print ""; ?>/></div>
                </div> 
            </div>
    
            <div id="vehicle_dates" class="form_block" style="display: block;">
                <div class="form_row col-md-6">
                    <div class="col-md-3"><label for="date_of_purchase">Date of Purchase</label></div> 
                    <div class="col-md-3"><input type="input" name="date_of_purchase" class="datepicker form-control" <?php (isset($arrPosted['date_of_purchase'])) ? print "value=\"" . date('d/m/Y', strtotime($arrPosted['date_of_purchase'])) . "\"" : print ""; ?>/></div>
                </div>  

                <div class="form_row col-md-6">
                    <div class="col-md-3"><label for="warranty_expiration">Warranty Expiration Date</label></div> 
                    <div class="col-md-3"><input type="input" name="warranty_expiration" class="datepicker form-control" <?php (isset($arrPosted['warranty_expiration'])) ? print "value=\"" . date('d/m/Y', strtotime($arrPosted['warranty_expiration'])) . "\"" : print ""; ?>/></div>
                </div>
                
                <div class="form_row col-md-6">
                    <div class="col-md-3"><label for="insurance_expiration">Insurance Expiration Date</label></div> 
                    <div class="col-md-3"><input type="input" name="insurance_expiration" class="datepicker form-control" <?php (isset($arrPosted['insurance_expiration'])) ? print "value=\"" . date('d/m/Y', strtotime($arrPosted['insurance_expiration'])) . "\"" : print ""; ?>/></div>
                </div>
                
                <div class="form_row col-md-6">
                    <div class="col-md-3"><label for="tax_expiration">Tax Expiration Date</label></div> 
                    <div class="col-md-3"><input type="input" name="tax_expiration" class="datepicker form-control" <?php (isset($arrPosted['tax_expiration'])) ? print "value=\"" . date('d/m/Y', strtotime($arrPosted['tax_expiration'])) . "\"" : print ""; ?>/></div>
                </div>
            </div>

                <div id="item_ownership" class="form_block" style="display: block;">

                    <div class="form_row col-md-8">
                        <div class="col-md-4"><label for="user_id">Owner</label></div>	
                        <div class="col-md-4"><select name="user_id" class="form-control">
                            <option value="0">Not Set</option>
                    <?php foreach ($arrUsers['results'] as $key => $value)
                    {
                        foreach($value['levels'] as $key1 => $level) {
                            foreach($level['users'] as $user) {
                                echo "<option ";
                                echo 'value="'.$user->userid.'" ';
                                if ($vehicle['owner_now'] == $user->userid)
                                {
                                    echo 'selected="selected" ';
                                }
                                echo '>'.$user->firstname." ".$user->lastname."</option>\r\n";
                            }
                        }
                    } ?>
                        </select></div>
                    </div>

                    <div class="form_row col-md-8">
                        <div class="col-md-4"><label for="site_id">Site</label></div>
                        <div class="col-md-4"><select name="site_id" class="form-control">
                            <option value="0">Not Set</option>
                <?php
                    foreach ($arrSites['results'] as $arrSite)
                    {
                        echo "<option ";
                        echo 'value="'.$arrSite->siteid.'" ';
                        if ($site_id == $arrSite->siteid) {
                            echo 'selected="selected" ';
                        }
                        echo '>'.$arrSite->sitename."</option>\r\n";
                    }
                ?>
                        </select></div>
                    </div>
                    <!--<div class="form_row">
                        <label for="location_id">Location</label>	
                        <select name="location_id">
                            <option value="0">Not Set</option>
                <?php
                    /*foreach ($arrLocations['results'] as $arrLocation)
                    {
                        echo "<option ";
                        echo 'value="'.$arrLocation->locationid.'" ';
                        if ($site_id == $arrLocation->locationid) {
                            echo 'selected="selected" ';
                        }
                        echo '>'.$arrLocation->locationname."</option>\r\n";
                    }*/
                ?>
                        </select>
                    </div>-->
                    
                    <div class="form_row col-md-8">
                        <div class="col-md-4"><label for="is_location">Is this vehicle also going to be a location?</label></div> 
                        <div class="col-md-4"><input type="checkbox" name="is_location" value="1" onclick=""/></div>
                    </div> 
                    
                    <div class="form_row col-md-8">
                        <div class="col-md-4"><label for="barcode">QR Code for location</label></div> 
                        <div class="col-md-4"><input type="text" class="form-control" name="barcode" <?php (isset($arrPosted['barcode'])) ? print "value=\"" . $arrPosted['barcode'] . "\"" : print ""; ?>/></div>
                        <?php echo form_error('barcode'); ?>
                    </div>
                </div>

                <div id="item_checks" class="form_block" style="display: block;">
                    <fieldset>
                        <div class="col-md-12"><input type="checkbox" class="checkall"> Check all</div>
                    <?php foreach ($arrChecks as $key => $checks) { ?>

                        <div class="form_row col-md-6">
                            <div class="col-md-3"><label for="checks"><?=$checks['check_name']?></label></div>
                            <div class="col-md-3"><input type="checkbox" name="checks[]" value="<?=$checks['id']?>" <?php echo (in_array($checks[id], $arrVehicleChecks) ? 'checked="checked"' : ''); ?>/></div>
                        </div>
                    <?php } ?>
                        </fieldset>

                </div>
            

            </div>
                

            
            </form>
    
        </div>
    </div>
</div>