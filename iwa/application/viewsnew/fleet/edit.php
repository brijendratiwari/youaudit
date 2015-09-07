<script>
             $(function() {
		$(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
	});
    </script>
<div class="box">
    <div class="heading">
      	<h1>Edit Vehicle</h1>
        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#edit_vehicle_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="tabs">
            <ul>
                <li><a href="#general_information" class="active">General Information</a></li>
                <li><a href="#dates">Dates</a></li>
                <li><a href="#item_ownership">Ownership</a></li>
                <li><a href="#item_checks">Checks</a></li>
            </ul>
        </div>
        
        <div class="content_main">
                <p>Use this form to change vehicle information and choose which checks are assigned to the vehicle.</p>
                <?php echo form_open('fleet/edit/' . $vehicle['fleet_id'], array('id'=>'edit_vehicle_form')); ?>

                <script>
                 $(function() {
                    $(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
            });
        </script>

                <div id="general_information" class="form_block">
                    <input type="hidden" name="fleet_id" value="<?php print $vehicle['fleet_id']; ?>"/>              
                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="make">Make</label></div> 
                        <div class="col-md-4"><input type="input" class="form-control" name="make" value="<?php print $vehicle['make']; ?>"/></div>
                        <?php echo form_error('make'); ?>
                    </div>

                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="model">Model</label></div> 
                        <div class="col-md-4"><input type="input" class="form-control" name="model" value="<?php print $vehicle['model']; ?>"/></div>
                        <?php echo form_error('model'); ?>
                    </div>

                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="model">QR Code</label></div>
                        <div class="col-md-4"><input type="input" class="form-control" name="vehicle_barcode" value="<?php print $vehicle['barcode']; ?>"/></div>
                        <?php echo form_error('vehicle_barcode'); ?>
                    </div>

                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="year">Year</label></div> 
                        <div class="col-md-4"><input type="input" class="form-control" name="year" value="<?php print $vehicle['year']; ?>"/></div>
                    </div>         
                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="engine_size">Engine Size</label></div> 
                        <div class="col-md-4"><input type="input" class="form-control" name="engine_size" value="<?php print $vehicle['engine_size']; ?>"/></div>
                    </div>

                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="reg_no">Registration No</label></div> 
                        <div class="col-md-4"><input type="input" class="form-control" name="reg_no" value="<?php print $vehicle['reg_no']; ?>"/></div>
                        <?php echo form_error('reg_no'); ?>
                    </div>

                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="vehicle_value">Vehicle Value (<?php echo $currency; ?>)</label></div> 
                        <div class="col-md-4"><input type="input" class="form-control" name="vehicle_value" value="<?php print $vehicle['vehicle_value']; ?>"/></div>
                    </div>                       
                    
                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="notes">Vehicle Notes</label></div>
                        <div class="col-md-4"><textarea class="form-control" name="notes"><?php print $vehicle['notes']; ?></textarea></div>
                    </div>
                </div>
                
                <?php
                /* Convert Dates */
                $purchase_date = date('d/m/Y', strtotime($vehicle['purchase_date']));
                $warranty_expiration = date('d/m/Y', strtotime($vehicle['warranty_expiration']));
                $insurance_expiration = date('d/m/Y', strtotime($vehicle['insurance_expiration']));
                $tax_expiration = date('d/m/Y', strtotime($vehicle['tax_expiration']));
                ?>
                <div id="dates" class="form_block" style="display: block;">
                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="purchase_date">Purchase Date</label></div> 
                        <div class="col-md-4"><input type="input" name="purchase_date" class="datepicker form-control" value="<?php print ($vehicle['purchase_date'] > 0) ? $purchase_date : ""; ?>"></div>
                    </div>
                    
                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="warranty_expiration">Warranty Expiration Date</label></div> 
                        <div class="col-md-4"><input type="input" name="warranty_expiration" class="datepicker form-control" value="<?php print ($vehicle['warranty_expiration'] > 0) ? $warranty_expiration : ""; ?>"></div>
                    </div>
                    
                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="insurance_date">Insurance Expiration Date</label></div> 
                        <div class="col-md-4"><input type="input" name="insurance_expiration" class="datepicker form-control" value="<?php print ($vehicle['insurance_expiration'] > 0) ? $insurance_expiration : ""; ?>"></div>
                    </div>
                    
                    <div class="form_row col-md-7">
                        <div class="col-md-3"><label for="tax_expiration">Tax Disc Expiration Date</label></div> 
                        <div class="col-md-4"><input type="input" name="tax_expiration" class="datepicker form-control" value="<?php print ($vehicle['tax_expiration'] > 0) ? $tax_expiration : ""; ?>"></div>
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
                        if ($vehicle['site_now'] == $arrSite->siteid) {
                            echo 'selected="selected" ';
                        }
                        echo '>'.$arrSite->sitename."</option>\r\n";
                    }
                ?>
                        </select></div>
                    </div>

                    <div class="form_row col-md-8">
                        <div class="col-md-4"><label for="is_location">Is this vehicle also going to be a location?</label></div> 
                        <div class="col-md-4"><input type="checkbox" name="is_location" value="1" <?php print ($vehicle['is_location'] == 1) ? "checked=checked" : ""; ?>/></div>
                    </div> 
                    
                    <div class="form_row col-md-8">
                        <div class="col-md-4"><label for="barcode">QR Code for location</label></div> 
                        <div class="col-md-4"><input type="text" class="form-control" name="barcode" <?php (isset($arrPosted['barcode'])) ? print "value=\"" . $arrPosted['barcode'] . "\"" : print "value=\"" . $vehicle_qr . "\""; ?>/></div>
                        <?php echo form_error('barcode'); ?>
                    </div>
                </div>

            <div id="item_checks" class="form_block" style="display: block;">

                <?php foreach ($arrChecks as $key => $checks) { ?>

                    <div class="form_row">
                        <label for="checks"><?=$checks['check_name']?></label>
                        <input type="checkbox" name="checks[]" value="<?=$checks['id']?>" <?php echo (in_array($checks[id], $arrVehicleChecks) ? 'checked="checked"' : ''); ?>/>
                    </div>
                <?php } ?>

            </div>
            
        </div>
                

            
      </form>
    
        </div>
    </div>
</div>