<div class="heading"><h1>Make <?php echo $arrUser['result'][0]->firstname." ".$arrUser['result'][0]->lastname; ?> SuperAdmin of <?php
        echo $arrUser['result'][0]->accountname;
    ?>?</h1>
        <div class="buttons">
            <a class="button" onclick="$('#make_superadmin').submit();">Save</a>
        </div>
    </div>    

<div class="box_content">
        <div class="content_main">
    <?php echo form_open('admins/makeSuperAdmin/'.$arrUser['result'][0]->userid.'/',array('id' => 'make_superadmin')); ?>
    <div class="form_row">
            <label for="safety">Are you sure?</label>
    <select name="safety">
	<option value=0 selected="selected">No</option>
	<option value=1>Yes</option>
    </select>
    </div>
    
 </div>
        </div>