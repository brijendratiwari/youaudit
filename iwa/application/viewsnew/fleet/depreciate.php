<div class="box">
    <div class="heading">
        <h1>Depreciate All Items</h1>
        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#depreciate_category_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">

            <p>Use this form to depreciate all items.  The depreciation rate set at category level will be applied to the current system value of all items within each category on your account.  These rates are listed below, for reference.</p>
            <p><strong>Note:</strong> <em>You will not be able to undo this process, all values will be updated.</em></p>



            <?php echo form_open('fleet/depreciate/', array('id' => 'depreciate_category_form')); ?>
            <div class="form_row col-md-6">
                <div class="col-md-3"><label for="rate" style="float:left; padding: 5px;">Rate of Depreciation: </label></div>
                <div class="col-md-3"><input type="text" class="form-control" name="rate" id="rate" /><span class="per_sign">%</span></div>
            </div>
            <style>
                #rate
                {
                    background-color: #fff;
                    background-image: none;
                    border-radius: 4px;
                    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
                    display: block;
                    height: 34px;
                    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
                    width: 85%;
                    float: left;
                }
                .per_sign
                {
                    float: right;
                    font-size: 19px;
                    padding-top: 7px;
                }
            </style>