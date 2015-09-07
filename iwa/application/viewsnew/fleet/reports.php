<div class="box">
    <div class="heading">
        <h1>Fleet Reports</h1>
        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#report').submit();"><i class="fa fa-arrow-circle-up"></i>Generate</a>
        </div>
    </div>
    <div class="box_content">
        <?php echo form_open('fleet/generatereports/', array('id'=>'report')); ?>
        <div class="form_block">
            <div class="form_row col-md-6">
                <div class="col-md-2"><label for="report_type">Report</label></div>
                <div class="col-md-4"><select name="report_type" class="form-control">
                    <option value="-1">Select</option>
                    <option value="all">All checks</option>
                    <option value="failed">Failed checks</option>
                </select></div>
            </div>

            <script>
                $(function() {
                    $( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
                });
            </script>
            <div class="form_row col-md-6">
                <div class="col-md-2"><label for="report_startdate">Start Date</label></div>
                <div class="col-md-4"><input type="input" name="report_startdate" value="<?php echo $strStartDate; ?>" class="datepicker form-control" /></div>
                <?php echo form_error('report_startdate'); ?>
            </div>

            <div class="form_row col-md-6">
                <div class="col-md-2"><label for="report_enddate">End Date</label></div>
                <div class="col-md-4"><input type="input" name="report_enddate" value="<?php echo $strEndDate; ?>" class="datepicker form-control" /></div>
                <?php echo form_error('report_enddate'); ?>
            </div>


        </div>
        </form>

