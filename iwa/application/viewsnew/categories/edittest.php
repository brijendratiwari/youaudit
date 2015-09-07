<div class="box">
    <div class="heading">
      	<h1>Edit Test</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_test_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">   
        <div class="content_main">
            <p>Use this form to edit a test</p>
            <?php echo form_open('categories/edittest/' . $test_id, array('id'=>'edit_test_form')); ?> 
            <div class="form_row">
                <label for="test_type_name">Test Name</label>
                <input type="text" name="test_type_name" value="<?php echo $test['test_type_name']; ?>" />
                <?php echo form_error('name'); ?>
            </div>
            
            <div class="form_row">
                <label for="test_type_description">Test Description</label>
                <input type="text" name="test_type_description" value="<?php echo $test['test_type_description']; ?>" />
                <?php echo form_error('name'); ?>
            </div>
            
        <div class="form_row">
            <label for="test_frequency">Frequency</label>
            <select name="test_type_frequency">
                <?php foreach($test_freqs as $freqs) { ?>
                <option value="<?php print $freqs['test_freq_id']; ?>"><?php print $freqs['test_frequency']; ?></option>
                <?php } ?>
            </select>
            <?php echo form_error('name'); ?>
        </div>
            
        <div class="form_row">
            <label for="test_notify">Test Notifications</label>
            <input type="checkbox" name="test_type_notify" id="test_type_notify" value="1"  <?php print ($test['test_type_notify'] == 1) ? " checked=checked" : "ddd"?>/>
            <?php echo form_error('name'); ?>
        </div>
            
            