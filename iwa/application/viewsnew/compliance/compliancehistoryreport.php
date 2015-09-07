<?php // print "<pre>"; print_r($allCompliances); print_r($items); print "</pre>"; ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/chosen_v1.1.0/chosen.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/chosen_v1.1.0/chosen.jquery.min.js"></script>
<div class="heading">
    <h1>Compliance Report</h1>
    <div class="buttons">
        
    </div>
</div>
<style>
    .DTTT_container{
        display: block;
    }
    .DTTT_container a{
        margin-left: 4px;
    }
    #export_csv{
        min-width: 40%;
    }
    #export_csv a{
        float: left;
        margin-left: 5px;
    }
    .compliance_box_top {
        min-height: 220px;
    }
    .box_content{
        overflow: visible !important;
    }
    select{
        min-width: 160px;
    }
    input.datepicker{
        border: 1px solid rgb(170, 170, 170);
        border-radius: 5px;
        box-shadow: 0 0 3px rgb(255, 255, 255) inset, 0 1px 1px rgba(0, 0, 0, 0.1);
    }
</style>
<div class="box_content">
    <div class="ver_tabs">
        <a class="" href="<?php  echo base_url('/compliance');  ?>"><span>Compliance Checks Due</span></a>
      <a class="" href="<?php  echo base_url('compliance/complianceshistory');  ?>"><span>Compliance History</span></a>
      <a class="" href="<?php  echo base_url('/compliance/complianceslist');  ?>"><span>List of Compliance Checks</span></a>
      <a class="" href="<?php  echo base_url('/compliance/compliancesadmin');  ?>"><span>Compliance Admin</span></a>
      <a class="" href="<?php echo base_url('compliance/adhoc'); ?>"><span>Complete Adhoc Checks</span></a>
      <a class="" href="<?php echo base_url('compliance/templates'); ?>"><span>Templates</span></a>
      <a class="active" href="#"><span>Report</span></a>
    </div>
    <div class="content_main">
        <div id="history" class="form_block">
            <div class="box_content">
                <form action="<?php  echo base_url('index.php/compliance/getreport'); ?>" method="post" onsubmit="return validateForm()">
                    <table class="list_table">
                        <tbody>
                            <tr>
                                <td ><strong>Check Name: </strong></td>
                                <td >
                                    <select name="check_name">
                                        <option selected="true" value="0" data-category="0">Please Select</option>
                                        <?php foreach ($allCompliances as $key => $value) { ?>
                                        <option data-category="<?php echo $value['cat_id'];?>" value="<?php echo $value['test_type_id'];?>"><?php echo $value['test_type_name'];?></option>
                                    <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td ><strong>Barcode: </strong></td>
                                <td >
                                    <select name="item_barcode">
                                        <option selected="true" value="0" data-category="0">All Assets</option>
                                        <?php foreach ($items as $key => $value) { ?>
                                            <option class="dynamic-option"  disabled="true" data-category="<?php echo $value->categoryid;?>" value="<?php echo $value->itemid;?>"><?php echo $value->barcode;?></option>
                                    <?php } ?>

                                    </select>
                                    <img class="ajax-loader fade hide" width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>">
                                    <input hidden="" name="item_selected">
                                </td>

                            </tr>
                            <tr>
                                <td ><strong>Manufacturer: </strong></td>
                                <td >
                                    <select name="manufacturer">
                                        <option selected="true" value="0" data-category="0">All Assets</option>
                                        <?php foreach ($items as $key => $value) { ?>
                                            <option class="dynamic-option" disabled="true" data-category="<?php echo $value->categoryid;?>" value="<?php echo $value->itemid;?>" data-html="<?php echo $value->manufacturer;?>"><?php echo $value->manufacturer;?></option>
                                        <?php } ?>
                                    </select>
                                    <img class="ajax-loader fade hide" width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>">
                                    <input hidden="" name="manufacturer_items">
                                </td>

                            </tr>
                            <tr>
                                <td><strong>From: </strong></td>
                                <td>
                                    <input name="from_date" class="datepicker">
                                </td>
                            </tr>
                            <tr>
                                <td><strong>To: </strong></td>
                                <td>
                                    <input name="to_date" class="datepicker">
                                </td>

                            </tr>
                            <tr><td colspan="2" >
                                    <input class="button" type="submit" value="Get Report">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('select[name="manufacturer"] option').each(function() {
            $(this).prevAll('option[data-html="' + $(this).html() + '"]').removeClass('dynamic-option');
          });
        $('select option[value="0"]').prop('selected',true);
        $('select[name="check_name"]').on('change',function(){
            $('.ajax-loader').toggleClass('hide').toggleClass('in');
            var cat_id = +$(this).find('option[value="'+$(this).val()+'"]').attr('data-category');
            $('select[name="item_barcode"] option.dynamic-option').attr('disabled',true).removeClass('current');
            $('select[name="item_barcode"] option[value="0"]').prop('selected',true);
            $('select[name="item_barcode"] option.dynamic-option').each(function(){
                if( +$(this).attr('data-category') == cat_id ){
                    $(this).removeAttr('disabled').addClass('current');
                }
            });
            
            $('select[name="manufacturer"] option.dynamic-option').attr('disabled',true).removeClass('current');
            $('select[name="manufacturer"] option[value="0"]').prop('selected',true);
            $('select[name="manufacturer"] option.dynamic-option').each(function(){
                if( +$(this).attr('data-category') == cat_id ){
                    $(this).removeAttr('disabled').addClass('current');
                }
            });
            setTimeout(function(){$('.ajax-loader').toggleClass('hide').toggleClass('in');},1000);
            $("select").trigger("chosen:updated");
        });
    });
    
    
    function validateForm()
    {
        var mfr_items = [];
        var selected_items = [];
        if($('select[name="item_barcode"]').val() == '0')
        {
            $('select[name="item_barcode"] option.current').each(function(){
                    selected_items.push($(this).val());
            });
        }
        var mfr = $('select[name="manufacturer"]').find('option[value="'+$('select[name="manufacturer"]').val()+'"]').attr('data-html');
        $('select[name="manufacturer"] option').each(function(){
            if( $(this).attr('data-html') == mfr ){
                mfr_items.push($(this).val());
            }
        });

        $('input[name="manufacturer_items"]').val(mfr_items.join(','));
        $('input[name="item_selected"]').val(selected_items.join(','));
//        return false;
        if($('select[name="check_name"]').val() == '0')
        {
            return false;
        }
        if($('.datepicker').val() == '')
        {
            return false;
        }
    }
</script>
<script>
    $(function() {
        $(".datepicker").datepicker({dateFormat: "dd/mm/yy", maxDate: new Date() });
        $("select").chosen({no_results_text: "Oops, nothing found!",display_disabled_options:false});
    });
</script>