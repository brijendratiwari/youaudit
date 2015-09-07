<div class="box">
    <div class="heading">
        <h1>Import Items</h1>
        <div class="buttons">
        </div>
    </div>

    <div class="box_content">
        <div class="content_main">
            <p>This section allows you to upload a CSV file which contains item's data.</p>
            <p>Select a file to upload and fill out the fields below. This information is necessary for a successful import.</p>
            <div id="form">
                <?php echo form_open_multipart('admins/import/'); ?>
                <div style="margin-bottom: 10px;" class="col-md-12">
                    <div class="col-md-3"><label for="userfile">Select File</label></div>
                    <?php echo form_error('userfile'); ?>
                    <div class="col-md-3">
                    <span class="file-select">choose file <i class="fa fa-sort pull-right"></i></span>
                    <input type="file" style="opacity: 0" value="upload" name="userfile" class="item_photo">
                    </div>
                    <!--<input name="userfile" type="file"/>-->
                </div>
                <div style="margin-bottom: 10px;" class="col-md-12">
                    <div class="col-md-3"><label for="account_id">Select Account</label></div>
                    <div class="col-md-3" style="padding: 0px;"><select name="account_id" class="form-control">
                        <?php foreach ($accounts as $account) { ?>
                            <option value="<?php echo $account->customer_id; ?>"><?php echo $account->company_name; ?></option>
                        <?php } ?>
                    </select></div>
                </div>
                <div style="margin-bottom: 10px;" class="col-md-12">
                    <input name="submit" type="submit" class="btn btn-primary" value="Import"/> 
                </div>
            </div>
        </div> <!-- div: content_main -->
    </div> <!-- div:box_content -->
</div><!-- div:box -->