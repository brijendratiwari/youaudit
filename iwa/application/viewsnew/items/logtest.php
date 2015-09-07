<div class="heading"><h1>Log a Test</h1>
    <div class="buttons">
        <a class="button" onclick="$('#add_logtest_form').submit();">Save</a>
    </div>
</div>

<div class="box_content">
    <div class="tabs">
        <a href="#general_information" class="active">Test Information</a>
    </div>
    
    <div class="content_main">
        <p>Use this form to add a record of an item being tested</p>
            
        <form action="https://www.iworkaudit.com/iwa/items/logTest" method="post" accept-charset="utf-8" id="add_logtest_form" enctype="multipart/form-data">    
        <div id="general_information" class="form_block">


            <script>
                $(function() {
                    $( ".datepicker" ).datepicker({ dateFormat: "yy/mm/dd" });
                    
                });
            </script>
            <div class="form_row">
                <label for="test_type">Test Type</label>
                <select name="test_type">
                    <?php foreach($testTypes as $testType) { ?>
                    <option value="<?php print $testType['test_type_id'];?>"><?php print $testType['test_type_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form_row">
                <label for="test_date">Date Tested</label> 
                <input type="input" name="test_date" value="" class="datepicker" id="dp1359563337102"/>
                <?php //echo form_error('item_make'); ?>
            </div>
            
            <div class="form_row">
                <label for="test_person">Name of Tester</label> 
                <input type="input" name="test_person" value=""/>
                <?php //echo form_error('item_make'); ?>
            </div>
            <div class="form_row">
                <label for="result">Test Outcome</label> 
                <select name="result">
                    <option value="1">Pass</option>
                    <option value="0">Failed</option>
                </select>
            </div>
            <div class="form_row">
                <label for="notes">Notes</label> 
                <input type="input" name="test_notes" value=""/>
                <input type="hidden" name="test_item_id" value="<?php print $item_id; ?>"/>
                <?php //echo form_error('item_make'); ?>
            </div>
            
                
            
        </div>
  
    

    </form>
    
</div>
</div>
