<div class="box">
    <div class="heading">
      	<h1>Depreciate All Items</h1>
        <div class="buttons">
            <a class="button" onclick="$('#depreciate_category_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">

    <p>Use this form to depreciate all items.  The depreciation rate set at category level will be applied to the current system value of all items within each category on your account.  These rates are listed below, for reference.</p>
    <p><strong>Note:</strong> <em>You will not be able to undo this process, all values will be updated.</em></p>
    
    
    
    <?php echo form_open('fleet/depreciate/', array('id'=>'depreciate_category_form')); ?>
    <div class="form_row col-md-6">
        <div class="col-md-3"><label for="rate" style="float:left; padding: 5px;">Rate of Depreciation: </label></div>
        <div class="col-md-3"><input type="text" class="form-control" name="rate" id="rate" /> %</div>
    </div>