<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<!--<script src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/magnific/jquery.magnific-popup.js"></script>
<link rel="stylesheet" href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/magnific/magnific-popup.css" type="text/css" />-->
<?php $this->load->helper('text'); ?>
<style>
    .panel-footer{
        background: #DFF2F9;
    }
    .tabs li.selected{
        background: #eee none repeat scroll 0 0 !important;
        transform: skewX(20deg);
        -webkit-transform: skewX(20deg);
        -moz-transform: skewX(20deg);
        -ms-transform: skewX(20deg);
    }
    .tabs li.selected a{
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
        color: #000 !important;
        display: block;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        transform: skewX(-20deg);
        -moz-transform: skewX(-20deg);
        -webkit-transform: skewX(-20deg);
        -ms-transform: skewX(-20deg);
    }
    .panel-body.blue-border li {
        float: left; width: 30%;display: flex;
    }
    .itemview
    {
        border-bottom: 1px solid #e5e5e5;
        height: 30px;
        line-height: 25px;
        margin-bottom: 15px;
        margin-top: 15px;
        padding: 7px;
    }
    .custom_val
    {
        float: right!important;
        width: 80%!important;
    }
    .tabview 
    {
        height: 0px;
    }
    .blue-border {}
    .input_fields_wrap {overflow-y:scroll; min-height:48px; max-height:210px; overflow-style:marquee-line !important}
    .input_fields_wrap div:empty { display:none}
    .input_pdf_wrap {overflow-y:scroll; min-height:48px; max-height:210px; overflow-style:marquee-line !important}
    .input_pdf_wrap div:empty { display:none}
    .catg
    {
        margin-bottom: 10px;
    }
    .backtxt
    {
        padding-top: 3px;
    }
    #fault_error
    {
        display: none;
    }
    .input_fields_wrap .file-select {
        width: 100%;
    }
    .input_fields_wrap .file-select .selectedpdf {
        font-size: 70%;
    }
    .input_pdf_wrap .selectedpdf {
        font-size: 66%;
    }
    .nopadding {
        padding: 0;
    }
</style>
<script>


    function Handlechange()
    {
        var fileinput = document.getElementById("item_photo");
        document.getElementById("select_file").innerHTML = '<span class="selectedpdf">' + fileinput.value.replace("C:\\fakepath\\", "") + '</span>';
    }
    function Handle_change(img_id)
    {
        var fileinput = document.getElementById("photo_file_" + img_id);
        document.getElementById("select_file" + img_id).innerHTML = '<span class="selectedpdf">' + fileinput.value.replace("C:\\fakepath\\", "") + '</span>';
    }

    function Handlefilechange()
    {
        var fileinput = document.getElementById('pdf');
        document.getElementById("select-pdf").innerHTML = '<span class="selectedpdf">' + fileinput.value.replace("C:\\fakepath\\", "") + '</span>';
    }
    function Handle_filechange(doc)
    {
        var fileinput = document.getElementById('pdf_file_' + doc);
        document.getElementById("select-pdf" + doc).innerHTML = '<span class="selectedpdf">' + fileinput.value.replace("C:\\fakepath\\", "") + '</span>';
    }
//    function goBack() {
////    base_url = $('#base_url').val();
////            window.location.href = base_url + "index.php/items/filter";
//        parent.history.back();
//        return false;
//    }

//    function showImg() {
//        $('.ui-lightbox-gallery').each(function() {
//            $(this).magnificPopup({
//                delegate: 'a',
//                type: 'image',
//                gallery: {
//                    enabled: true
//                }
//            });
//        });
//    }


    $(document).ready(function() {

        $("#qr_code").on("keyup blur", function() {

            var code = $("#asset_qrcode").val();
            var bar_code = $("#qr_code").val();
            var qrcode = code + bar_code;
            var base_url_str = $("#base_url").val();
            var current_qrcode = $('#current_barcode').val();
            if (current_qrcode != qrcode) {
                $.ajax({
                    type: "POST",
                    url: base_url_str + "items/checkQrcode",
                    data: {
                        'bar_code': qrcode
                    },
                    success: function(msg) {

                        // we need to check if the value is the same
                        if (msg == "1") {
                            //Receiving the result of search here
                            $("#save_item").attr('disabled', true);
                            $("#qrcodeerror").removeClass("hide");
                        } else {
                            $("#save_item").removeAttr('disabled');
                            $("#qrcodeerror").addClass("hide");
                        }
                    }

                });
            }
        });



        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID
        var x = 1;
        var y = 1;
        //initlal text box count

        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="" style="height:48px;"><span class="col-lg-12" style="padding-top:10px !important; padding:0px; ">\n\
    <span class="col-lg-6" style="padding: 0"><span class="file-select" id="select_file' + x + '">choose file <i class="fa fa-sort pull-right"></i></span><input class="item_photo col-lg-10" id="photo_file_' + x + '" type="file" name="photo_file_' + x + '" value="upload" onChange="Handle_change(' + x + ');" style="opacity: 0;"></span><button class="btn btn-primary btn-circle btn-xs remove_file" onclick="rem(' + x + ')" title="remove image" type="button"><i class="glyphicon glyphicon-minus"></i></button></span></div>');
//            $(wrapper).append(' <div> <input class="fileupload upload form-control" type="file" name="photo_file_' + x + '" size="20"><a href="#" class="remove_field">Remove</a></div>'); //add input box
            }
        });
        $(".add_pdf_button").click(function(e) { //on add input button click
            e.preventDefault();
            if (y < max_fields) { //max input box allowed
                y++; //text box increment
                $(".input_pdf_wrap").append('<div class="pdf_wrap_" style="height:48px;"><span class="col-lg-12" style="padding-top:10px !important;padding:0px;"><span class="col-lg-6" style="padding: 0"><span class="file-select" id="select-pdf' + y + '">choose file <i class="fa fa-sort pull-right"></i></span><input class="item_photo col-lg-10" id="pdf_file_' + y + '" type="file" name="pdf_file_' + y + '" value="upload" onChange="Handle_filechange(' + y + ');" style="opacity: 0"></span><button class="btn btn-primary btn-circle btn-xs remove_file" onclick="rem_pdf(' + y + ')" title="remove image" type="button"><i class="glyphicon glyphicon-minus"></i></button></span></div>');
//            $(wrapper).append(' <div> <input class="fileupload upload form-control" type="file" name="photo_file_' + x + '" size="20"><a href="#" class="remove_field">Remove</a></div>'); //add input box
            }
        });


        // add selectedd class on sub tab..
        $("body").find(".itemview ul li").click(function() {

            $(this).addClass('selected').siblings().removeClass('selected');

        })
    });
    $("body").on("click", "#reportfault", function()
    {
        var status = $('#itemstatus').val();
        if (status == '3')
        {
//            alert('Warning - Asset already has an Incident against this. Must be resolved before another fault can be logged');
            $('#fault_error').css('display', 'block');
            return false;
        }
    });
    function rem(ob)
    {
        $('#photo_file_' + ob).parentsUntil('div').remove();
        ob--;
    }

    function rem_pdf(ob)
    {
        $('#pdf_file_' + ob).parentsUntil('div').remove();
        ob--;
    }

</script>

<input type="hidden" id="itemstatus" value="<?php echo $objItem->status_id; ?>">
<input type="hidden" id="asset_qrcode" value="<?php echo $this->session->userdata('objSystemUser')->qrcode; ?>">
<div class="heading">
    <div class="">
        <?php if ($objItem->active != 0) { ?>
            <a data-toggle="modal" data-target="#add_similar_item" id="addsimilaritem" class="add_similar button icon-with-text round" id="add_item_button" data_item_id="<?php echo $arrItem->itemid; ?>"><i class="glyphicon glyphicon-arrow-up"></i>
                Add similar
            </a>
            <?php
//            if ($arrSessionData['objSystemUser']->levelid > 1) {
            ?>
            <a id="item_edit" class="button icon-with-text round"><i class="fa fa-fw">&#xf044;</i> Edit item</a>
            <div style="float: left; margin-left: 5px;">
                <button onclick="$('#itemedit').submit();" class="button update icon-with-text round" id="save_item" style="display: none;margin-top: 2px;">   <i class="fa fa-fw">&#xf0ab;</i> Save</button>
            </div>
            <?php
//            }
            if ($arrSessionData['objSystemUser']->userid == $objItem->userid) {
                ?>
                <a  data-toggle="modal" data-target="#change_owner_model" id="change_owner" class="button icon-with-text round">   <i class="fa fa-fw">&#xf007;</i> Change Owner or Location</a>
                <?php
            } else {
                ?>
        <!--                <a href="<?php echo site_url('/items/itsmine/' . $objItem->itemid . '/'); ?>" class="button icon-with-text round"> <i class="fa fa-fw">&#xf0f0;</i>I Have This Now</a> -->
                <?php ?>
                <a data-toggle="modal" data-target="#change_owner_model" id="change_owner" class="button icon-with-text round"> <i class="fa fa-fw">&#xf007;</i>  Change Owner or Location</a>
                <?php
            }
            //}
            ?>
            <a href="<?php echo site_url('/compliance/log/' . $objItem->itemid . '/'); ?>" class="button icon-with-text round"> <i class="fa fa-fw">&#xf15c;</i> Log Safety Check</a>

            <a data-toggle="modal" data-target="#report_fault" id="reportfault"  class="button icon-with-text round"> <i class="fa fa-fw">&#xf071;</i>Report Fault</a>
            <?php if ($checkItemReportFaults) {
                ?>
                <a data-toggle="modal" href="#fix_item" title="Fix item"  class="button icon-with-text round"><i class="fa fa-fw">&#xf0ad;</i>Fix Item</a>
                <?php
            }
            ?>
            <?php
            if ($arrSessionData['objSystemUser']->levelid > 2) {
                if (($objItem->mark_deleted == 0) && ($objItem->mark_deleted_2 == 0)) {
                    ?>  <a href="<?php echo site_url('/items/mark_deleted/' . $objItem->itemid); ?>" class="button icon-with-text round">  <i class="fa fa-fw">&#xf1f8;</i>Archive Item</a><?php
                }
                ?>

                <a data-toggle="modal" type="button" data-target="#condition_check" id="check_condition"class="button icon-with-text round">  <i class="fa fa-fw">&#xf058;</i>Condition Check</a>

                <?php
            }
        }
        ?>
        &nbsp;&nbsp;

        <a class="button icon-with-text round" target="blank" href="<?php echo site_url('items/itemPdf/PDF/' . $objItem->itemid) ?>" style="padding: 0">
            <i class="fa  fa-file-pdf-o"></i>
            Export to PDF</a>
    </div>
</div>

<?php // if ($objItem->active != 0) {    ?>

<div class="tabs itemview"><ul>

        <li class="selected">   <a href="#first_table">Item Details</a></li>
        <li>   <a href="#second_table">Item History</a></li>
        <li>   <a href="#fourth_table">Item Fault History</a></li>
        <?php if ($this->session->userdata('objSystemUser')->compliance == 1) { ?>
            <li>    <a href="#third_table">Safety History</a></li>
        <?php } else { ?>
            <li style="display: none;"><a href="#third_table">Safety History</a></li>
        <?php } ?>
        <li>  <a href="#pat_table">Electrical Test History</a>  </li>
        <?php if ($this->session->userdata('objSystemUser')->condition_module == 1) { ?>
            <li><a href="#condition_table">Condition History</a></li>
        <?php } else { ?>
            <li style="display: none;"><a href="#condition_table">Condition History</a></li>
        <?php } ?>
<!--<li class="pull-right backarrow back-btn"><a href="#" class="back" onclick="goBack();"><i class="fa fa-arrow-left"></i></a></li>-->
    </ul></div>
<?php // } else {    ?>

<!--    <div class="tabs tabview"><ul>

            <li><a href="#first_table" style="display: none;">Item Details</a></li>
            <li><a href="#second_table" style="display: none;">Item History</a></li>
            <li><a href="#fourth_table" style="display: none;">Item Fault History</a></li>
            <li><a href="#third_table" style="display: none;">Compliance History</a></li>
            <li><a href="#pat_table" style="display: none;">Item Pat History</a>  </li>
            <li><a href="#condition_table" style="display: none;">Condition History</a></li>
            <li class="pull-right back back-btn"><a href="#" class="back" onclick="goBack();"><i class="fa fa-arrow-left"></i></a></li>
        </ul></div>-->

<?php // }    ?>

<h1><?php
    if ($objItem->item_manu_name) {
        $item = ucfirst($objItem->item_manu_name) . ' / ';
    } else {
        $item = '';
    }
    if ($objItem->manufacturer) {
        $manufacturer = ucfirst($objItem->manufacturer) . ' / ';
    } else {
        $manufacturer = '';
    }
    if ($objItem->model) {
        $model = ucfirst($objItem->model) . ' / ';
    } else {
        $model = '';
    }
    echo $item . $manufacturer . $model . ucfirst($objItem->barcode);
    ?></h1>
<script type="text/javascript">
    /* When Category is checked, see if category is a quantity category */


    $(function() {

        $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
        $('#category_id').change(function() {

            var url = $('#base_url').val();
            var linkforcategory = url + "categories/checkCategory/" + $('#category_id').val();
            /* Quantity category check */
            $.getJSON(linkforcategory, function(data) {
                if (data.quantity == 1) {
                    /*                    $('#item_quantity').append('<label for="item_quantity">Item Quantity</label>' +
                     '<input type="input" name="item_quantity"/>'
                     ).show();*/
                    $('#item_quantity').show();
                } else {
                    $('#quantity').val('')
                    $('#item_quantity').hide();
                }
            });
            /* Custom Fields call */
            $.getJSON(url + "categories/getCustomFields/" + $('#category_id').val(), function(data) {
                $('#custom_field_div').empty();
                for (var i = 0; i < data.length; i++) {
                    if (data[i].field_value == 'text_type')
                    {
                        var str = '<div class="row catg"><div class="col-md-4">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-8">' +
                                '<input type="text" class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div>';
                    }
                    if (data[i].field_value == 'value_type')
                    {
                        var str = '<div class="row catg"><div class="col-md-4">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-8">' +
                                '<div class="input-group col-md-12"><div class="input-group-addon grpaddon">$</div><input type="number" min="0" class="form-control custom_val" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div></div>';
                    }
                    if (data[i].field_value == 'num')
                    {
                        var str = '<div class="row catg"><div class="col-md-4">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-8">' +
                                '<input type="number" min="0" class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div>';
                    }
                    if (data[i].field_value == 'date_type')
                    {
                        var str = '<div class="row catg"><div class="col-md-4">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-8">' +
                                '<input type="text" class="form-control dateval" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div>';
                    }
                    if (data[i].field_value == 'pick_list_type')
                    {
                        if (data[i].pick_values)
                        {
                            var temp = new Array();
                            var opt1 = new Array();
                            var list = data[i].pick_values;
                            var temp = list.split(',');
                            for (var j = 0; j < temp.length; j++) {
                                var opt = '<option value="' + temp[j] + '">' + temp[j] + '</option>';
                                opt1.push(opt);
                            }
                            var option = opt1.join('');
                        }
                        var str = '<div class="row catg"><div class="col-md-4">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-8">' +
                                '<select class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' + option +
                                '</select>' +
                                '</div></div>';
                    }
                    $('#custom_field_div').append(str);
                }

                $(".dateval").datepicker({dateFormat: "dd/mm/yy"});
            });
        });
        // code for condition_history

        $("#condition_check_form").validate({
            rules: {
                new_condition: "required",
                job_notes: "required"
            },
            messages: {
                new_condition: "Please Enter New Condition",
                job_notes: "Please Enter Job Note"
            }
        });
        var ownership = $("#ownership").DataTable({
            "ordering": true,
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5,
            "order": [[1, "desc"]],
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]}
            ]}
        );
//        var open_job = $("#open_job").DataTable({
//            "ordering": true,
//            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
//            "iDisplayLength": 5,
//            "bDestroy": true, //!!!--- for remove data table warning.
//            "aoColumnDefs": [
//                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [7]}
//            ]}
//        );
//        var fault_history = $("#fault_history").DataTable({
//            "ordering": true,
//            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
//            "iDisplayLength": 5,
//            "bDestroy": true, //!!!--- for remove data table warning.
//            "aoColumnDefs": [
//                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [9]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [10]}
//            ]}
//        var fixed_history = $("#fixed_history").DataTable({
//            "ordering": true,
//            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
//            "iDisplayLength": 5,
//            "bDestroy": true, //!!!--- for remove data table warning.
//            "aoColumnDefs": [
//                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [9]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [10]}
//            ]}
//        );
        var quantity = $("#quantity_change").DataTable({
        });
        $("#item_barcode_similar").on("keyup blur", function() {

            var bar_code = $("#item_barcode_similar").val();
            var base_url_str = $("#base_url").val();
            $.ajax({
                type: "POST",
                url: base_url_str + "items/checkQrcode",
                data: {
                    'bar_code': bar_code
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#similar_item").addClass('disabled');
                        $("#qrcodeerror_similar").removeClass("hide");
                    } else {
                        $("#similar_item").removeClass('disabled');
                        $("#qrcodeerror_similar").addClass("hide");
                    }
                }

            });
        });
// Add similar asset data
        $('body').on('click', '#addsimilaritem', function() {
            var base_url_str = $("#base_url").val();
            var item_id = $('#current_item').val();
            $.ajax({
                type: "POST",
                url: base_url_str + "items/getassetdata/" + item_id,
                success: function(data) {
                    var assetdata = $.parseJSON(data);
//                    var pre = $("#asset_qrcode").val();
//                    var bar_code = pre + assetdata[0].barcode;

                    $.getJSON(base_url_str + "categories/getCustomFields/" + assetdata[0].categoryid, function(data) {

                        $('#customfielddiv').empty();
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].field_value == 'text_type')
                            {
                                var str = '<div style="height:50px;"><div class="col-md-6">' +
                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                        '<input type="text" class="form-control" id="custom_' + data[i].id + '" name="custom_' + data[i].id + '">' +
                                        '</div></div>';
                            }
                            if (data[i].field_value == 'value_type')
                            {
                                var str = '<div style="height:50px;"><div class="col-md-6">' +
                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                        '<div class="input-group col-md-12"><div class="input-group-addon grpaddon">$</div><input type="number" min="0" class="form-control custom_val" id="custom_' + data[i].id + '" name="custom_' + data[i].id + '">' +
                                        '</div></div></div>';
                            }
                            if (data[i].field_value == 'num')
                            {
                                var str = '<div style="height:50px;"><div class="col-md-6">' +
                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                        '<input type="number" min="0" class="form-control" id="custom_' + data[i].id + '" name="custom_' + data[i].id + '">' +
                                        '</div></div>';
                            }
                            if (data[i].field_value == 'date_type')
                            {
                                var str = '<div style="height:50px;"><div class="col-md-6">' +
                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                        '<input type="text" class="form-control dateval" id="custom_' + data[i].id + '" name="custom_' + data[i].id + '">' +
                                        '</div></div>';
                            }
                            if (data[i].field_value == 'pick_list_type')
                            {
                                if (data[i].pick_values)
                                {
                                    var temp = new Array();
                                    var opt1 = new Array();
                                    var list = data[i].pick_values;
                                    var temp = list.split(',');
                                    for (var j = 0; j < temp.length; j++) {
                                        var opt = '<option value="' + temp[j] + '">' + temp[j] + '</option>';
                                        opt1.push(opt);
                                    }
                                    var option = opt1.join('');
                                }
                                var str = '<div style="height:50px;"><div class="col-md-6">' +
                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                        '<select class="form-control" id="custom_' + data[i].id + '" name="custom_' + data[i].id + '">' + option +
                                        '</select>' +
                                        '</div></div>';
                            }
                            if (data[i].id) {
                                $.getJSON(base_url_str + "categories/getCustomFieldContent/" + assetdata[0].categoryid + "/" + data[i].id + "/" + item_id, function(text) {
                                    $("#custom_" + text.custom_field_id).val(text.content);
                                });
                            }
                            $('#customfielddiv').append(str);
                        }

                        $(".dateval").datepicker({dateFormat: "dd/mm/yy"});
                    });

                    var org = assetdata[0].barcode + '/' + assetdata[0].categoryname + '/' + assetdata[0].item_manu_name + '/' + assetdata[0].manufacturer;
                    $('.get_original').html(org);
                    $('#item_quantity_similar').val(assetdata[0].quantity);
                    $('#owner_id_similar option[value="' + assetdata[0].owner_now + '"]').attr('selected', 'selected');
                    $('#site_id_similar option[value="' + assetdata[0].siteid + '"]').attr('selected', 'selected');
                    $('#location_id_similar option[value="' + assetdata[0].location_now + '"]').attr('selected', 'selected');
                    $('#supplier_similar option[value="' + assetdata[0].supplier + '"]').attr('selected', 'selected');
                    if (assetdata[0].purchase_date != '') {
                        var newdate = assetdata[0].purchase_date.split("-").reverse().join("/");
                        $('#item_purchased_similar').val(newdate);
                    }
                    $('#item_value_similar').val(assetdata[0].value);
                }

            });
        });
        $('#item_purchased').on('change', function() {

            d = $("#item_purchased").datepicker("getDate");
            $("#item_warranty").datepicker("setDate", new Date(d.getFullYear() + 1, d.getMonth(), d.getDate()));
            $("#item_replace").datepicker("setDate", new Date(d.getFullYear() + 3, d.getMonth(), d.getDate()));
        });
    });</script>

<input type="hidden" name="base_url" id="base_url" value="<?= base_url(); ?>">
<input type="hidden" id="current_item" value="<?php echo $this->uri->segment('3'); ?>">
<input type="hidden" id="current_barcode" value="<?php echo $objItem->barcode; ?>">
<!--<a class="btn btn-info" href="<?php echo base_url('/items/filter'); ?>">Back</a>-->
<!--<li class="pull-right back back-btn">-->
<a href="<?php echo base_url('items/filter'); ?>" class="backarrow back-btn"><i class="fa fa-arrow-left"></i></a>
<h5 class="backtxt">BACK</h5>
<!--</li>-->
<div class="alert alert-warning alert-dismissable" id="fault_error">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
    Warning - Asset already has an Incident against this. Must be resolved before another fault can be logged
</div>

<div id="first_table" class="content_main">
    <div class="row">
        <?php if ($objItem->active == 0) { ?>
            <h1 style="color:red;">Archived Asset</h1>
        <?php } ?>
        <h1>Item Details</h1>
    </div>
    <div class="row">
        <?php
        $attributes = array('id' => 'itemedit');
        echo form_open('items/edit/' . $objItem->itemid, $attributes);
        ?>
        <div class="col-lg-4">


<!--                <form enctype="multipart/form-data" id="itemedit" accept-charset="utf-8" method="post" action="<?php echo base_url('items/edit/' . $objItem->itemid); ?>">-->
            <div class="table-responsive" id="view_itemdetails">
                <table class="table">
                    <tbody>
                        <tr class="tb_header">
                            <td>Item Details</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Category*</td>
                            <td> <select name="category_id" id="category_id" class="form-control" disabled>
                                    <option value="">----SELECT----</option>
                                    <?php
                                    foreach ($arrCategories['results'] as $arrCategory) {
                                        echo "<option ";
                                        echo 'value="' . $arrCategory->categoryid . '" ';
                                        if ($arrCategory->categoryid == $objItem->categoryid) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $arrCategory->categoryname . "</option>";
                                    }
                                    ?>
                                </select><?php echo form_error('category_id'); ?>   </td>
                        </tr>

                        <tr>
                            <td>Item*</td>
                            <td> <select name="item_manu" id="item_manu" class="form-control" disabled>
                                    <!--<option value="0">----SELECT----</option>-->
                                    <?php foreach ($getitemmanu['list'] as $item) { ?>
                                        <option value="<?php echo $item['id']; ?>"<?php
                                        if ($item['id'] == $objItem->item_manu) {
                                            echo 'selected="selected"';
                                        }
                                        ?>><?php echo $item['item_manu_name']; ?></option>
                                            <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Manufacturer*</td>
                            <td>
                                <select name="item_make" id="item_make" class="form-control" disabled>
                                    <!--<option value="0">----SELECT----</option>-->
                                    <?php foreach ($arrManufaturer as $manufacturer) { ?>
                                        <option value="<?php echo $manufacturer['manufacturer_name']; ?>"<?php
                                        if ($manufacturer['manufacturer_name'] == $objItem->manufacturer) {
                                            echo 'selected="selected"';
                                        }
                                        ?>><?php echo $manufacturer['manufacturer_name']; ?></option>
                                            <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td><input type="text" name="item_model" id="model" class="form-control" value="<?php echo $objItem->model; ?> " disabled>

                            </td>
                        </tr>
                        <tr>
                            <td>Quantity</td>
                            <td><input type="text" name="item_quantity" id="quantity" class="form-control" value="<?php echo $objItem->quantity; ?>" disabled>

                            </td>
                        </tr>
                        <tr class="tb_header">
                            <td>Item Details</td>
                            <td></td>
                        </tr>
<!--                        <tr>
                            <td>QR Code</td>
                            <td><input placeholder="Enter QR Code" class="form-control" name="item_barcode" id="qr_code" value="<?php echo $objItem->barcode; ?>" readonly><?php echo form_error('item_barcode'); ?></td>

                        </tr>-->
                        <tr>
                            <td>QR Code</td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-addon grpaddon">
                                        <?php echo $this->session->userdata('objSystemUser')->qrcode; ?></div>
                                    <input placeholder="Enter QR Code" class="form-control barcss" name="item_barcode" id="qr_code" value="<?php
//                                    $barcode = explode($this->session->userdata('objSystemUser')->qrcode, $objItem->barcode);
//                                    echo $barcode[1];
                                    if (strpos($objItem->barcode, $this->session->userdata('objSystemUser')->qrcode) !== FALSE) {
                                        $barcode = explode($this->session->userdata('objSystemUser')->qrcode, $objItem->barcode);
                                        echo $barcode[1];
                                    } else {
                                        echo $objItem->barcode;
                                    }
                                    ?>" disabled="">            
                                    <!--</div>-->
                                    <div id="qrcodeerror" class="qrcode_error hide">QR Code Already Exist.</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Serial No</td>
                            <td><input placeholder="Enter Serial No" class="form-control" name="item_serial_number" value="<?php echo $objItem->serial_number; ?>" id="serial_no" disabled></td>
                        </tr>
                        <tr class="tb_header">
                            <td>Item Quality</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>  <select name="status_id" id="status_id" class="form-control" disabled>
                                    <option value="">----SELECT----</option>
                                    <?php
                                    foreach ($arrItemStatuses['results'] as $arrStatus) {
                                        echo "<option ";
                                        echo 'value="' . $arrStatus->statusid . '" ';
                                        if ($arrStatus->statusid == $objItem->itemstatusid) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $arrStatus->statusname . "</option>\r\n";
                                    }
                                    ?>
                                </select><?php echo form_error('status_id'); ?>   </td>
                        </tr>
                        <tr>
                            <td>Condition</td>
                            <td><select name="item_condition" id="item_condition" class="form-control" disabled>
                                    <option value="">----SELECT----</option>
                                    <?php foreach ($arrCondition as $con) { ?>
                                        <option value="<?php echo $con['id']; ?>" <?php
                                        if ($con['id'] == $objItem->condition_now) {
                                            echo 'selected="selected"';
                                        }
                                        ?>><?php echo $con['condition']; ?></option>
                                            <?php } ?>
                                </select></td>
                        </tr>
                        <tr class="tb_header">
                            <td>Ownership</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Owner*</td>
                            <?php $this->session->set_userdata('ownerName', $objItem->userid); ?>
                            <td> <select name="owner_id" id="owner_id" class="form-control" disabled>
                                    <option value="0">----SELECT----</option>
                                    <?php
                                    foreach ($arrOwners['results'] as $arrOwner) {
                                        echo "<option ";
                                        echo 'value="' . $arrOwner->ownerid . '" ';
                                        if ($objItem->userid == $arrOwner->ownerid) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $arrOwner->owner_name . "</option>\r\n";
                                    }
                                    ?>
                                </select><?php echo form_error('owner_id'); ?>  </td>
                        </tr>
                        <tr>
                            <td>Site*</td>
                            <td><select name="site_id" id="site_id" class="form-control multi_site_class" disabled>
                                    <option value="0">----SELECT----</option>
                                    <?php
                                    foreach ($arrSites['results'] as $arrSite) {
                                        echo "<option ";
                                        echo 'value="' . $arrSite->siteid . '" ';
                                        if ($arrSite->siteid == $objItem->siteid) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $arrSite->sitename . "</option>\r\n";
                                    }
                                    ?>
                                </select><?php echo form_error('site_id'); ?></td>
                        </tr>
                        <tr>
                            <td>Location*</td>
                            <td><select name="location_id" id="location_id" class="form-control multi_location_class" disabled>
                                    <option value="0">----SELECT----</option>
                                    <?php
                                    foreach ($arrLocations['results'] as $arrLocation) {
                                        echo "<option ";
                                        echo 'value="' . $arrLocation->locationid . '" ';
                                        if ($arrLocation->locationid == $objItem->locationid) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $arrLocation->locationname . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <?php echo form_error('location_id'); ?>  
                            </td>
                        </tr>
                        <tr>
                            <td>Supplier</td>
                            <td><select name="supplier" id="supplier" class="form-control" disabled>
                                    <option value="">----SELECT----</option>
                                    <?php
                                    foreach ($arrSuppliers as $supplier) {
                                        echo "<option ";
                                        echo 'value="' . $supplier['supplier_id'] . '" ';
                                        if ($supplier['supplier_id'] == $objItem->supplier) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $supplier['supplier_name'] . "</option>\r\n";
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr class="tb_header">
                            <td>Notes</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <textarea placeholder="Enter Notes" class="form-control" name="item_notes" value="<?php echo $objItem->notes; ?>" id="notes" cols="13" rows="3" disabled><?php echo $objItem->notes; ?></textarea></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="table-responsive" id="view_itemdetails">
                <table class="table" >
                    <tbody>
                        <tr class="tb_header">
                            <td>Item Dates</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Purchase Date</td>
                            <td><input placeholder="Enter Purchase Date" class="form-control col-lg-10 datepicker" name="item_purchased" id="item_purchased" value="<?php
                                if ($objItem->purchase_date != '') {
                                    if (strtotime($objItem->purchase_date) > 0) {
                                        echo date('d/m/y', strtotime($objItem->purchase_date));
                                    } else {
                                        echo '';
                                    }
                                } else {
                                    
                                }
                                ?>" type="text" disabled></td>

                        </tr>
                        <tr>
                            <td>Warranty Expiry</td>
                            <td><input placeholder="Enter Expiry Date" class="form-control col-lg-10 datepicker" name="item_warranty" id="item_warranty" type="text" value="<?php
                                if ($objItem->warranty_date != '') {
                                    echo date('d/m/y', strtotime($objItem->warranty_date));
                                } else {
                                    
                                }
                                ?>" disabled></td>
                        </tr>
                        <tr>
                            <td>Replacement Due</td>
                            <td><input placeholder="Enter Replacement Date" class="form-control col-lg-10 datepicker" name="item_replace" id="item_replace" value="<?php
                                if ($objItem->replace_date != '') {
                                    echo date('d/m/y', strtotime($objItem->replace_date));
                                } else {
                                    
                                }
                                ?>" type="text" disabled><?php echo form_error('item_replace'); ?></td>
                        </tr>
                        <tr>
                            <td>Age Of Asset</td>
                            <td><input class="form-control" name="asset_age" id="asset_age" type="text" value="<?php
                                if (isset($objItem->purchase_date)) {
                                    $date2 = date('d-m-Y', strtotime($objItem->purchase_date));
                                    $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                                    $diff = abs(strtotime($date2) - strtotime($date1));

                                    $years = floor($diff / (365 * 60 * 60 * 24));
                                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                                    echo $years . ' year ' . $months . ' month ';
                                }
                                ?>" disabled></td>
                        </tr>
                        <tr class="tb_header">
                            <td>Item Valuation</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Purchase Price</td>
                            <td><input placeholder="Enter Purchase Price" class="form-control" name="item_value" id="purchase_price" value="<?php echo $objItem->value; ?>" type="text" disabled></td>
                        </tr>
                        <tr>
                            <td>Current Value</td>
                            <td><input placeholder="Enter Current Value" class="form-control" name="item_current_value" value="<?php echo $objItem->current_value; ?>" id="current_value" type="text" disabled>
                            </td>
                        </tr>

                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive" id="view_itemdetails_fault">
                <table class="table" >
                    <tbody>
                        <tr class="tb_header_warning">
                            <td>Total Faults</td>
                            <td></td>
                        </tr>
                        <tr class="tb_font_warning">
                            <td>Total Faults</td>
                            <td><input class="form-control" name="total_fault" id="total_fault" type="text" value="<?= $numberOfFaults ?>" disabled></td>
                        </tr>
                        <tr class="tb_font_warning">
                            <td>Last Fault Date</td>
                            <td><input class="form-control col-lg-10 datepicker" name="fault_date" id="fault_date" type="text" value="<?php
                                if ($lastDateOfFaults != '') {
                                    echo date("d/m/Y h:i:s", strtotime($lastDateOfFaults));
                                } else {
                                    
                                }
                                ?>" disabled></td>
                        </tr>
                        <tr class="tb_font_warning">
                            <td>Last Safety Check</td>
                            <td><input class="form-control col-lg-10 datepicker" name="compliance_date" id="compliance_date" type="text" value="<?= $last_compliance_check; ?>" disabled></td>
                        </tr>
                        <tr class="tb_font_warning">
                            <td>Safety Result</td>
                            <td><input class="form-control" name="asset_age" id="asset_age" type="text" value="<?= $last_compliance_result; ?>" disabled></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive" id="view_itemdetails">
                <table class="table" >
                    <tbody>
                        <tr class="tb_header_success">
                            <td>Electrical Test</td>
                            <td></td>
                        </tr>
                        <tr class="tb_font_success">
                            <td>Electrical Test Date</td>
                            <td><input class="form-control col-lg-10 datepicker" placeholder="Enter Pat Date" name="item_pattestdate" id="test_date" type="text" value="<?php
                                if ($objItem->pattest_date != '') {
                                    echo date('d/m/Y', strtotime($objItem->pattest_date));
                                } else {
                                    
                                }
                                ?>" disabled></td>
                        </tr>
                        <tr class="tb_font_success">
                            <td>Electrical Test Result</td>
                            <!--set status value in session for testing result is updated or not ...-->
                            <?php $this->session->set_userdata('pattestStatus', $objItem->pattest_status); ?>
                            <td>  <select name="item_patteststatus" id="item_patteststatus" class="form-control" disabled>
                                    <option value="">----SELECT----</option>
                                    <option value="-1" <?php
                                    if ($objItem->pattest_status === "-1") {
                                        echo "selected=\"selected\"";
                                    }
                                    ?>>Unknown</option>
                                    <option value="1" <?php
                                    if ($objItem->pattest_status === "1") {
                                        echo "selected=\"selected\"";
                                    }
                                    ?>>Pass</option>
                                    <option value="0" <?php
                                    if ($objItem->pattest_status === "0") {
                                        echo "selected=\"selected\"";
                                    }
                                    ?>>Fail</option>
                                    <option value="5" <?php
                                    if ($objItem->pattest_status === "5") {
                                        echo "selected=\"selected\"";
                                    }
                                    ?>>Not Required</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="table"><tbody><tr class="tb_header">
                        <td>Items Custom Fields</td>
                        <td></td>
                    </tr></tbody></table>
            <div  id="custom_field_div">
                <?php
                foreach ($arrCustomFields as $custom_name) {
//                        var_dump($custom_name);
                    ?>

                    <div class="col-md-4">
                        <?php echo $custom_name->field_name; ?>
                    </div>
                    <div id="custom_field_id" class="col-md-8">
                        <?php if ($custom_name->field_value == 'text_type') { ?>
                            <input placeholder="Enter Asset Type" class="form-control" placeholder="Enter Content" disabled="" name="<?php echo $custom_name->id; ?>" id="asset_type" type="text" value="<?php
                            if (isset($custom_name->content)) {
                                echo $custom_name->content;
                            }
                            ?>" ></br>
                               <?php } elseif ($custom_name->field_value == 'value_type') { ?>
                            <div class="input-group"><div class="input-group-addon">$</div><input type="number" class="form-control" placeholder="Enter Content" disabled="" name="<?php echo $custom_name->id; ?>" id="asset_type" type="text" min="0" value="<?php
                                if (isset($custom_name->content)) {
                                    echo $custom_name->content;
                                }
                                ?>" ></div></br>
                            <?php } elseif ($custom_name->field_value == 'num') { ?>
                            <input class="form-control" placeholder="Enter Content" disabled="" name="<?php echo $custom_name->id; ?>" id="asset_type" type="number" min="0" value="<?php
                            if (isset($custom_name->content)) {
                                echo $custom_name->content;
                            }
                            ?>" ></br>
                               <?php } elseif ($custom_name->field_value == 'date_type') { ?>
                            <input placeholder="Enter Asset Type" class="form-control pickdate" placeholder="Enter Content" disabled="" name="<?php echo $custom_name->id; ?>" id="asset_type" type="text" value="<?php
                            if (isset($custom_name->content)) {
                                echo $custom_name->content;
                            }
                            ?>" ></br>
                                   <?php
                               } else {
                                   $arr = explode(',', $custom_name->pick_values)
                                   ?>
                            <select class="form-control" name="<?php echo $custom_name->id; ?>" disabled>
                                <?php foreach ($arr as $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php if ($value == $custom_name->content) echo 'selected=selected'; ?>><?php echo $value; ?></option>  
                                <?php } ?>

                            </select></br>
                        <?php } ?>
                    </div>

                    <?php
                }
                ?>

            </div>
            <?php echo form_close(); ?>
        </div>

        <!--</form>-->
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading" style='font-weight: bold'>
                    Photos
                </div>
                <div class="panel-body blue-border">
                    <div class="fileupload fileupload-new" data-provides="fileupload" style="margin: auto;float: none">

                        <div class="ui-lightbox-gallery">

                            <?php
                            if ($objItem->photo_id) {
                                if (strpos($objItem->photo_id, ',') != false) {
                                    $arr_image = explode(',', $objItem->photo_id);
                                    ?>

                                    <?php for ($i = 0; $i < count($arr_image); $i++) { ?>


                                        <a target='_top' title='' href='<?php echo base_url(); ?>/index.php/images/viewAsset/<?php echo $arr_image[$i] ?>' class=''><img width='23%' height="150" alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='<?php echo base_url(); ?>/index.php/images/viewAsset/<?php echo $arr_image[$i] ?>'></a>&nbsp;      
                                        <?php if ($arrSessionData['objSystemUser']->levelid > 2) { ?>
                                            <button class="delete btn-warning" delete_item_id="<?php echo $objItem->itemid; ?>" delete_id="<?php echo $arr_image[$i] ?>" href='<?php echo site_url('/items/delete_photo/' . $arr_image[$i]); ?>'>
                                                <i class="fa fa-times-circle"></i>
                                            </button>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>

                                    <a target='_top' title='' href='<?php echo base_url(); ?>/index.php/images/viewAsset/<?php echo $objItem->photo_id ?>' class=''><img width='23%' height="150" alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='<?php echo base_url(); ?>/index.php/images/viewAsset/<?php echo $objItem->photo_id; ?>'></a>&nbsp;         
                                    <?php if ($arrSessionData['objSystemUser']->levelid > 2) { ?>
                                        <button class="delete btn-warning" delete_item_id="<?php echo $objItem->itemid; ?>" delete_id="<?php echo $objItem->photo_id ?>" href='<?php echo site_url('/items/delete_photo/' . $objItem->photo_id); ?>'>
                                            <i class="fa fa-times-circle"></i>
                                        </button>  
                                    <?php } ?>

                                    <?php
                                }
                            }
                            ?>   </div>

                    </div>


                    <form enctype="multipart/form-data" class="blue-border" accept-charset="utf-8" method="post" action="<?php echo base_url('/items/photo_upload/' . $objItem->itemid); ?>">
                        <div class="panel-footer input_fields_wrap">

                            <span class="col-lg-12 nopadding">
                                <span class="col-lg-6" style="padding: 0"> 
                                    <span class="file-select" id="select_file">choose file <i class="fa fa-sort pull-right"></i></span>
                                    <input class="item_photo" id="item_photo" type="file" name="photo_file_1" value="upload" onChange="Handlechange();"   style="opacity: 0; width: 100%" id="image_file"> </span>
                                <span class="col-lg-4"><button type="button" title="add more image" class="btn btn-primary btn-circle btn-xs add_field_button"><i class="glyphicon glyphicon-plus"></i> Add More</button></span>
                                <span class="col-lg-2 pull-right" style="padding: 0;"><button  class="grad pic_button">UPLOAD</button></span>
                                <input type="hidden" name="pervious_image" value="<?php echo $objItem->photo_id; ?>">
                            </span>
                        </div>
                    </form>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" style='font-weight: bold'>
                        Pdf / Document 
                    </div>
                    <div class="panel-body blue-border">

                        <ul><?php
                            foreach ($pdf_number as $list) {
                                ?><li>
                                    <div  class="pdf_upload">
                                        <a href='<?php echo site_url('/items/pdf_download/' . $list['s3_key']); ?>'>
                                            <i class="fa fa-file-pdf-o"></i></a>
                                        <?php if ($arrSessionData['objSystemUser']->levelid > 2) { ?>
                                            <a class="delete" href='<?php echo site_url('/items/pdf_delete/' . $objItem->itemid . '/' . $list['s3_key']); ?>'>
                                                <i class="fa fa-times-circle"></i>
                                            </a>  
                                        <?php } ?>
                                        <label for="nickname"><?php echo $list['file_name']; ?>    </label>
                                    </div>
                                </li>
                                <?php
                            }
                            ?></ul>
                    </div>
                    <form enctype="multipart/form-data" class="blue-border" accept-charset="utf-8" method="post" action="<?php echo base_url('/items/pdf_upload'); ?>">
                        <div class="panel-footer input_pdf_wrap">
                            <span class="col-lg-12" style="padding: 0px;">
                                <span class="col-lg-6" style="padding: 0">
                                    <span class="file-select" id="select-pdf">choose file <i class="fa fa-sort pull-right"></i></span>
                                    <input class="item_photo" id="pdf" type="file" name="pdf_file_1" value="upload" style="opacity: 0; width: 100%;" onChange="Handlefilechange(this);"> </span>
                                <span class="col-lg-4">
                                    <button type="button" title="add more pdf" class="btn btn-primary btn-circle btn-xs add_pdf_button">
                                        <i class="glyphicon glyphicon-plus"></i> Add More</button>
                                </span>
                                <span class="col-md-2 pull-right" style="padding: 0">
                                    <button type="submit" class="pic_button grad">UPLOAD</button>
                                </span>
                            </span>
                        </div>
                        <input type="hidden" name="item_id" value="<?php echo $objItem->itemid; ?>" >
                        <input type="hidden" name="item_manu" value="<?php echo $objItem->itemmanu; ?>" >
                    </form>
                </div>

            </div>
        </div>
    </div></div>


<?php
if ($arrSessionData['objSystemUser']->levelid > 1) {
    ?>
    <div id="second_table" class="content_main">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4">
                    <h1>Ownership Changes</h1></div>

                <div class="col-md-4">
                    <h1>Audit / Stocktake History</h1>

                </div>
                <div class="col-md-4">
                    <h1>Changes To Quantity</h1>

                </div>
            </div></div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">
                    <div id="second_table_container" class="log_container">
                        <table id="ownership" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="left">Date</th>
                                    <th class="left">Owner</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
//                                foreach ($arrItemHistory as $strDate => $arrRecord) {
                                ?>
    <!--                                    <tr>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($strDate)); ?></td>
                                <?php
                                if (isset($arrRecord['audit'])) {
                                    ?>
                                                                                                                                                                                                                <td colspan="3"><em><strong>Item was marked as <?php
                                    if ($arrRecord['audit']->present == 1) {
                                        echo "present";
                                    } else {
                                        echo "missing";
                                    }

                                    echo " by " . $arrRecord['audit']->userfirstname . " " . $arrRecord['audit']->userlastname;
                                    echo " on an audit of location " . $arrRecord['audit']->name;
                                    ?></strong></em>
                                                                                                                                                                                                                </td>
                                    <?php
                                } else {
                                    ?>

                                                                                                                                                                                                                <td><?php
                                    if (isset($arrRecord['user'])) {
                                        echo $arrRecord['user']->owner_name;
                                    }
                                    ?></td>


                                    <?php
                                }
                                ?>
                                    </tr>-->
                                <?php
//                                }
                                ?>

                                <?php
                                foreach ($arrOwnerHistory as $arrRecord) {
                                    ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($arrRecord->date)); ?></td>


                                        <td><?php
                                            if (isset($arrRecord->owner_name)) {
                                                echo $arrRecord->owner_name;
                                            }
                                            ?></td>



                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="col-md-5">
                    <table id="audit_history" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="left">Date of Audit</th>
                                <th class="left">Time of Audit</th>
                                <th class="left">User Logged Audit</th>
                                <th class="left">Audit Result</th>
                                <th class="left">Location</th>
                                <th class="left">Site</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($arrItemHistory as $strDate => $arrRecord) {
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($strDate)); ?></td>
                                    <td><?php echo date('H:i:s', strtotime($strDate)); ?></td>
                                    <td><?php
                                        if (isset($arrRecord['user'])) {
                                            echo $arrRecord['user']->userfirstname . " " . $arrRecord['user']->userlastname;
                                        }
                                        ?></td>
                                    <?php
//                                    if (isset($arrRecord['audit'])) {
                                    ?>
                                    <td><em><strong><?php
                                                if ($arrRecord['audit']->present == 1) {
                                                    echo "present";
                                                } else {
                                                    echo "missing";
                                                }
                                                ?></strong></em>
                                    </td>
                                    <?php
//                                    } else {
                                    ?>


                                    <td><?php
                                        if (isset($arrRecord['location'])) {
                                            echo $arrRecord['location']->locationname;
                                        }
                                        ?></td>
                                    <td><?php
                                        if (isset($arrRecord['site'])) {
                                            echo $arrRecord['site']->sitename;
                                        }
                                        ?></td>

                                    <?php
//                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>

                        </tbody>

                    </table>
                </div>
                <div class="col-md-4">   
                    <table id="quantity_change" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="left">Date</th>
                                <th class="left">User</th>
                                <th class="left">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($arrItemLogHistory as $log) {
                                ?>
                                <tr>
                                    <td style="width: 100px;"><?php echo date('d-m-Y H:i:s', strtotime($log->date)); ?></td>
                                    <td style="width: 100px;"><?= $log->firstname ?> <?= $log->lastname ?></td>
                                    <td><?php echo $log->message; ?></td>

                                </tr>
                                <?php
                            }

//                            if (!$arrItemLogHistory) {
                            ?>
    <!--                                <tr>
    <td colspan="3">No Logs Found</td>
    </tr>-->
                            <? // }           ?>
                        </tbody>

                    </table>
                </div>



            </div></div></div>

    <?php
}


if ($arrSessionData['objSystemUser']->levelid > 1) {
    ?>
    <div id="fourth_table" class="content_main">


        <style>
            .modal-body{
                min-height: 200px;
                max-height: 595px;
                overflow-y: scroll;
            } 
            .qrcode_error
            {
                color: red;
                font-weight: bold;
            }
            .qrcodeerror
            {
                color: red;
                font-weight: bold;
            }
            td.eamil_conform div {
                height: 70px;
                overflow-x: hidden;
                overflow-y: scroll;
            }
            #fault_history .thumb
            {
                margin-left: 5px;
                width: 55px; 
                height: 40px;
            }
            #condition_history .thumb
            {
                margin-left: 5px;
                width: 55px;  
                height: 40px;
            }

        </style>
        <script>
    $(document).ready(function()
    {
        $(".pickdate").datepicker({dateFormat: "dd/mm/yy"});
        //            var fix_history = $("#fault_history").DataTable({
        //                "ordering": true,
        //                "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        //                "iDisplayLength": 10,
        //                "bDestroy": true, //!!!--- for remove data table warning.
        //                "aoColumnDefs": [
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
        //                    {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
        //                ]
        //            });
        var fix_history1 = $("#fixed_history").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
            ]
        });
        var open_history = $("#open_job").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [7]}
            ]}

        );
    });</script>
        <div class="row">
            <h1>Open Incident</h1>
        </div>


        <div class="row">
            <div class="col-lg-12">

                <div class="panel-body">

                    <div class="table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                            <table id="open_job" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Incident Type</th>
                                        <th>Stage</th>
                                        <th>Fault Date</th>
                                        <th>Logged By</th>
                                        <th>Incident Length</th>   
                                        <th>Incident Time</th>
                                        <th>Severity</th>
                                        <th>Order No</th>
                                       <!--<th>Photos</th>-->
                                        <th>Job Notes</th>
                                        <th>Incident Report</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="asset_body">

                                    <?php
//                                    var_dump($arrItemOpenTicketHistory);
                                    foreach ($arrItemOpenTicketHistory as $openticket) {
                                        ?>

                                        <tr>
                                            <td><?php echo $openticket['statusname']; ?></td>
                                            <td><?php echo $openticket['reason_code']; ?></td>
                                            <?php
//                                            echo $openticket['date'];
                                            if ($openticket['date']) {
                                                $arr_date = explode(' ', $openticket['date']);
//                                                    echo $arr_date[0]; 
                                            }
                                            ?>
                                            <td><?php echo date('d/m/Y', strtotime($arr_date[0])); ?></td>

                                            <td><?php echo $openticket['username']; ?></td>


                                            <td><?php
                                                $datetime1 = new DateTime($openticket['date']);
                                                $datetime2 = new DateTime('now');
                                                $interval = $datetime1->diff($datetime2);
                                                $daysCal = $interval->format('%d');
                                                $week = $daysCal / 7;
                                                echo $interval->format('%m month, ' . intval($week) . ' week and %d days');
                                                ?></td>
                                            <td><?php echo $arr_date[1]; ?></td>



                                            <?php
                                            if ($openticket['fix_date'] != '0000-00-00 00:00:00') {


                                                $date2 = date('d-m-Y', strtotime($openticket['date']));
                                                $date1 = date('d-m-Y', strtotime($openticket['fix_date']));


                                                $diff = abs(strtotime($date1) - strtotime($date2));
                                                $years = floor($diff / (365 * 60 * 60 * 24));
                                                $months = abs(floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)));

                                                $days = floor(($diff / 3600 / 24));
                                                $age_of_asset = $months . ' month ' . $days . ' day ';
                                            } else {
                                                $age_of_asset = "-";
                                            }
                                            ?>



                                            <td><?php echo $openticket['severity']; ?></td>

                                            <td><?php echo $openticket['order_no']; ?></td>
        <!--                                            <td>
                                            <?php
                                            $image_role = '';
                                            $url_contain = base_url();

                                            if ($openticket['photoid'] != '') {
                                                if (strpos($openticket['photoid'], ',') !== FALSE) {
                                                    $image_arr = explode(',', $openticket['photoid']);
                                                    if (is_array($image_arr)) {



                                                        $image_role = "<div class='ui-lightbox-gallery_$j'>";
                                                        foreach ($image_arr as $image_id) {
                                                            $image_role.= "<a target='_top' title='' href='$url_contain/index.php/images/viewHero/$image_id' class=''><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='$url_contain/index.php/images/viewList/$image_id'></a>&nbsp;";
                                                        }

                                                        $image_role .= "<script>$('.ui-lightbox-gallery_" . $j . "').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                                                        $image_role .= "</div>";
                                                    }
                                                } else {

                                                    $image_role = "<div class='image_single'>";
                                                    $photoid = $openticket['photoid'];
                                                    $image_role .= "<a title='' href='$url_contain/index.php/images/viewHero/$photoid' class='ui-lightbox'><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='$url_contain/index.php/images/viewList/$photoid'></a>";

                                                    $image_role .= "<script>$('.image_single').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                                                    $image_role .= "</div>";
                                                }
                                            } echo $image_role;
                                            $account_id = $this->session->userdata('objSystemUser')->accountid;
                                            $url = base_url() . 'faults/getPdfForOpenJob/' . $openticket[itemid] . '/' . $account_id . '/' . $openticket['id'];
                                            ?>

                                            </td>-->
                                            <td><?php echo $openticket['jobnote']; ?></td>
                                            <!--<td><a  href="<?php echo base_url("faults/getPdf/$openticket[id]/1"); ?>"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>-->
                                            <td><a  href="<?php echo $url; ?>"> <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>
                                            <td><span class="action-w"><a data-toggle="modal" actionmode="reportfault"  ticket_id = "<?php echo $openticket['id']; ?>"  id="itm_<?php echo $openticket['itemid']; ?>" account_id="<?php echo $this->session->userdata('objSystemUser')->accountid; ?>" data-val="<?php echo $openticket['ticket_action']; ?>" href="#view_fault" title="View Incident" class="viewfault" data_customer_id=''><i class="fa fa-eye franchises-i"></i></a>View Incident</span>
                                                <span class="action-w"><a  id="itm_<?php echo $openticket['itemid']; ?>" item_status="<?php echo $openticket['statusname']; ?>" ticket_id = "<?php echo $openticket['id']; ?>" account_id="<?php echo $this->session->userdata('objSystemUser')->accountid; ?>"  id="" data-toggle="modal" href="#update_fault" data_customer_id='' title="Update Fault" class="updatefault" data-val="<?php echo $openticket['ticket_action']; ?>"><i class="fa  fa-recycle franchises-i"></i></a>Update Incident</span>
                                                <span class="action-w"><a  ticket_id = "<?php echo $openticket['id']; ?>" id="itm_<?php echo $openticket['itemid']; ?>" account_id="<?php echo $this->session->userdata('objSystemUser')->accountid; ?>"  data-toggle="modal" href="#fix_item" title="Fix item" class="fixitem" data_customer_id='' data-val="<?php echo $openticket['ticket_action']; ?>"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Resolve Incident</span>

                                            </td>

                                        </tr>
                                        <?php
                                    }
                                    ?></tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h1>Resolve Incident Fixed Data</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="panel-body">

                    <div class="table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                            <table id="fixed_history" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Fault Date</th>
                                        <th>Fault Time</th>
                                        <th>Incident Type</th>
                                        <th>Severity</th>
                                        <th>Fault Logged By</th>
                                        <th>Order No</th>
                                        <th>Fix Date</th>
                                        <th>Fix Time</th>
                                        <th>Incident Length</th>
                                        <th>Fix Reason Code</th>
                                        <th>Fixed By</th>
                                        <th>Photos</th>
                                        <th>Incident Report</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody id="asset_body">

                                    <?php
//                                    var_dump($arrItemFixTicketHistory);die;
                                    foreach ($arrItemFixTicketHistory['itemFixedHistory'] as $ticket) {
                                        ?>

                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($ticket['fault_date'])); ?></td>
                                            <td><?php echo date('h:i:s', strtotime($ticket['fault_date'])); ?></td>
                                            <td><?php echo $ticket['statusname']; ?></td>
                                            <td><?php echo $ticket['severity']; ?></td>
                                            <td><?php echo $ticket['fault_by']; ?></td>
                                            <td><?php echo $ticket['order_no']; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($ticket['fix_date'])); ?></td>
                                            <td><?php echo date('h:i:s', strtotime($ticket['fix_date'])); ?></td>

                                            <?php
                                            if ($ticket['fix_date'] != '0000-00-00 00:00:00') {


                                                $date2 = date('d-m-Y', strtotime($ticket['date']));
                                                $date1 = date('d-m-Y', strtotime($ticket['fix_date']));


                                                $diff = abs(strtotime($date1) - strtotime($date2));
                                                $years = floor($diff / (365 * 60 * 60 * 24));
                                                $months = abs(floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24)));

                                                $days = floor(($diff / 3600 / 24));
                                                $age_of_asset = $months . ' month ' . $days . ' day ';
                                            } else {
                                                $age_of_asset = "-";
                                            }
                                            ?>

                                            <td><?php echo $age_of_asset; ?></td>

                                            <td><?php echo $ticket['fix_code']; ?></td>
                                            <td><?php echo $ticket['username']; ?></td>
                                            <?php
//                                            if ($ticket['date']) {
//                                                $arr_date = explode(' ', $ticket['date']);
////                                                    echo $arr_date[0]; 
//                                            }
//                                            
                                            ?>
                                            <?php
//                                            if ($ticket['fix_date'])
//                                                $arr_fixdate = explode(' ', $ticket['fix_date']);
////                                                    echo $arr_date[0]; 
//                                            
                                            ?>
                                            <!--<td>-->
                                            <?php
//                                                if (!empty($arr_fixdate[0])) {
//                                                    echo date('d/m/Y', strtotime($arr_fixdate[0]));
//                                                }
//                                                
                                            ?>
                                            <!--</td>-->
                                            <!--<td>-->
                                            <?php
//                                                if (!empty($arr_fixdate[1])) {
//                                                    echo $arr_fixdate[1];
//                                                }
//                                                
                                            ?>
                                            <!--</td>-->







                                            <td>
                                                <?php
                                                $image_role = '';
                                                $url_contain = base_url();

                                                if ($ticket['photoid'] != '') {
                                                    if (strpos($ticket['photoid'], ',') !== FALSE) {
                                                        $image_arr = explode(',', $ticket['photoid']);
                                                        if (is_array($image_arr)) {



                                                            $image_role = "<div class='ui-lightbox-gallery_$j'>";
                                                            foreach ($image_arr as $image_id) {
                                                                $image_role.= "<a target='_top' title='' href='$url_contain/index.php/images/viewHero/$image_id' class=''><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='$url_contain/index.php/images/viewList/$image_id'></a>&nbsp;";
                                                            }

                                                            $image_role .= "<script>$('.ui-lightbox-gallery_" . $j . "').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                                                            $image_role .= "</div>";
                                                        }
                                                    } else {

                                                        $image_role = "<div class='image_single'>";
                                                        $photoid = $ticket['photoid'];
                                                        $image_role .= "<a title='' href='$url_contain/index.php/images/viewHero/$photoid' class='ui-lightbox'><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='$url_contain/index.php/images/viewList/$photoid'></a>";

                                                        $image_role .= "<script>$('.image_single').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                                                        $image_role .= "</div>";
                                                    }
                                                } echo $image_role;
                                                ?>

                                            </td>
                                            <?php $account_id = $this->session->userdata('objSystemUser')->accountid; ?>
                                            <td><a  href="<?php echo base_url("faults/getPdfForFaultHistory/$ticket[itemid]/$account_id"); ?>"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>
                                            <td>
                                                <span class="action-w"><a data-toggle="modal" actionmode="reportfault"  ticket_id = "<?php echo $ticket['id']; ?>"  id="itm_<?php echo $ticket['itemid']; ?>" account_id="<?php echo $this->session->userdata('objSystemUser')->accountid; ?>"  href="#viewfaultFix" title="View Incident" class="viewfaultFix" data_customer_id=''><i class="fa fa-eye franchises-i"></i></a>View Incident</span>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?></tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </div>
        </div>
        <!--        <div class="row">
                    <h1>Resolve Incident Logged Data</h1>
                </div>
                <div class="row">
                    <div class="col-lg-12">
        
                        <div class="panel-body">
        
                            <div class="table-responsive">
                                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                                    <table id="fault_history" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Severity</th>
                                                <th>Fault Date</th>
                                                <th>Incident Time</th>
                                                <th>Incident Length</th>
                                                <th>Logged By</th>
                                                <th>Order No</th>
                                                <th>Photos</th>
                                                <th>Incident Report</th>
        
                                            </tr>
                                        </thead>
                                        <tbody id="asset_body">
        <?php // var_dump($arrItemFixTicketHistory['itemFaultHistory']);  ?>
        <?php
        foreach ($arrItemFixTicketHistory['itemFaultHistory'] as $ticketData) {
            foreach ($ticketData as $ticket) {
                ?>
                        
                                                                    <tr>
                                                                        <td><?php echo $ticket['severity']; ?></td>
                <?php
                if ($ticket['date']) {
                    $arr_date = explode(' ', $ticket['date']);
//                                                    echo $arr_date[0]; 
                }
                ?>
                                                                        <td><?php echo date('d/m/Y', strtotime($arr_date[0])); ?></td>
                                                                        <td><?php echo $arr_date[1]; ?></td>
                                                                        <td><?php
                $datetime1 = new DateTime($ticket['date']);
                $datetime2 = new DateTime('now');
                $interval = $datetime1->diff($datetime2);
                $daysCal = $interval->format('%d');
                $week = $daysCal / 7;
                echo $interval->format('%m month, ' . intval($week) . ' week and %d days');
                ?></td>
                        
                        
                                                                        <td><?php echo $ticket['username']; ?></td>
                        
                                                                        <td><?php echo $ticket['order_no']; ?></td>
                                                                        <td>
                <?php
                $image_role = '';
                $url_contain = base_url();

                if ($ticket['photoid'] != '') {
                    if (strpos($ticket['photoid'], ',') !== FALSE) {
                        $image_arr = explode(',', $ticket['photoid']);
                        if (is_array($image_arr)) {



                            $image_role = "<div class='ui-lightbox-gallery_$j'>";
                            foreach ($image_arr as $image_id) {
                                $image_role.= "<a target='_top' title='' href='$url_contain/index.php/images/viewHero/$image_id' class=''><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='$url_contain/index.php/images/viewList/$image_id'></a>&nbsp;";
                            }

                            $image_role .= "<script>$('.ui-lightbox-gallery_" . $j . "').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                            $image_role .= "</div>";
                        }
                    } else {

                        $image_role = "<div class='image_single'>";
                        $photoid = $ticket['photoid'];
                        $image_role .= "<a title='' href='$url_contain/index.php/images/viewHero/$photoid' class='ui-lightbox'><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='$url_contain/index.php/images/viewList/$photoid'></a>";

                        $image_role .= "<script>$('.image_single').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                        $image_role .= "</div>";
                    }
                } echo $image_role;
                ?>
                        
                                                                        </td>
                                                                        <td><a  href="<?php echo base_url("faults/getPdf/$ticket[id]"); ?>"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>
                        
                                                                    </tr>
                <?php
            }
        }
        ?></tbody>
                                    </table>
                                </div>
                                 /.table-responsive 
                            </div>
                        </div>
                    </div>
                </div>-->



        <script>
            $(document).ready(function() {


                $("#report_fault_form").validate({
                    rules: {
                        job_notes: "required",
                    },
                });
                var max_fields = 10; //maximum input boxes allowed
                var wrapper = $(); //Fields wrapper
                var add_button = $(".add_field_button"); //Add button ID
                var x = 1;
                var y = 1;
                //initlal text box count

                $("body").find("#img_button").click(function(e) { //on add input button click

                    e.preventDefault();
                    if (x < max_fields) { //max input box allowed
                        x++; //text box increment
                        $(".input_fields_wrap").append('<div> <input class="fileupload upload form-control" type="file" name="photo_file_' + x + '" size="20"><a href="#" class="remove_field">Remove</a></div>'); //add input box


                    }
                });
                $(".input_fields_wrap").on("click", ".remove_field", function(e) { //user click on remove text
                    e.preventDefault();
                    $(this).parent('div').remove();
                    x--;
                    total--;
                });
                $("body").find("#img_fix_button").click(function(e) { //on add input button click

                    e.preventDefault();
                    if (x < max_fields) { //max input box allowed
                        x++; //text box increment
                        $(".input_fix_wrap").append('<div> <input class="fileupload upload form-control" type="file" name="photo_file_' + x + '" size="20"><a href="#" class="remove_field">Remove</a></div>'); //add input box


                    }
                });
                $(".input_fix_wrap").on("click", ".remove_field", function(e) { //user click on remove text
                    e.preventDefault();
                    $(this).parent('div').remove();
                    x--;
                    total--;
                });
            });</script>

    </div>




    <div id="pat_table" class="content_main">
        <div class="row">
            <h1>Electrical Test History</h1>
        </div>

        <table class="list_table">
            <thead>
                <tr>
                    <th class="left">Electrical Test Date</th>
                    <th class="left">Electrical Test Status </th>
                    <th class="left">Logged By</th>
                </tr>
            </thead>
            <tbody>
                <?php
//    var_dump($arrPatHistory);

                foreach ($arrPatHistory["results"] as $pat_result) {
                    ?>
                    <tr>
                        <td><?php echo date('d/m/Y h:i:s', strtotime($pat_result->date)); ?></td>
                        <td><?php echo $pat_result->pattest_name; ?></td>
                        <td><?php echo $pat_result->firstname . ' ' . $pat_result->lastname ?></td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>

        </table>
    </div>



<?php }
?>

<div id="condition_table" class="content_main">

    <div class="row">
        <h1>Condition History</h1>
    </div>
    <?php
    if ($this->session->flashdata('success')) {
        ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
        <?php
    }
    if ($this->session->flashdata('error')) {
        ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
        <?php
    }
    ?>
    <?php // echo $this->view('users/assets/asset_sidemenu');                  ?>
    <div class="row">
        <div class="col-lg-12">

            <div class="panel-body">

                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="condition_history" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>PHOTO</th>
                                    <th>STAR RATINGS</th>
                                    <th>CONDITION</th>
                                    <th>CHECK/START DATE</th>
                                    <th>END DATE</th>
                                    <th>TIME IN CONDITION</th>
                                    <th>LOGGED BY</th>
                                    <th>NOTES</th>
                                </tr>
                            </thead>
                            <tbody id="asset_body"> 
                                <?php foreach ($conditionhistory as $asset) { ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $image_role = '';
                                            $url_contain = base_url();

                                            if ($asset['photo_id'] != '') {
                                                if (strpos($asset['photo_id'], ',') !== FALSE) {
                                                    $image_arr = explode(',', $asset['photo_id']);
                                                    if (is_array($image_arr)) {



                                                        $image_role = "<div class='ui-lightbox-gallery_$j'>";
                                                        foreach ($image_arr as $image_id) {
                                                            $image_role.= "<a target='_top' title='' href='$url_contain/index.php/images/viewHero/$image_id' class=''><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb' src='$url_contain/index.php/images/viewList/$image_id'></a>&nbsp;";
                                                        }

                                                        $image_role .= "<script>$('.ui-lightbox-gallery_" . $j . "').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                                                        $image_role .= "</div>";
                                                    }
                                                } else {

                                                    $image_role = "<div class='image_single'>";
                                                    $photoid = $asset['photo_id'];
                                                    $image_role .= "<a title='' href='$url_contain/index.php/images/viewHero/$photoid' class='ui-lightbox'><img width='75' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='$url_contain/index.php/images/viewList/$photoid'></a>";

                                                    $image_role .= "<script>$('.image_single').each(function() { // the containers for all your galleries
    $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled:true
        }
    });
}); </script>";
                                                    $image_role .= "</div>";
                                                }
                                            } echo $image_role;
                                            ?>
                                        </td>
                                        <td><?php
                                            $count = substr($asset['condition'], 0, 1);
                                            for ($i = 0; $i < $count; $i++) {
                                                echo '<i class="fa fa-star"></i>&nbsp';
                                            }
                                            ?></td>
                                        <td><?php echo $asset['condition']; ?></td>

                                        <td><?php
                                            echo date('d/m/y', strtotime($asset['date']));
                                            ?></td>
                                        <td><?php
                                            if ($asset['enddate'] == 'N/A') {
                                                echo $asset['enddate'];
                                            } else {
                                                echo date('d/m/y', strtotime($asset['enddate']));
                                            }
                                            ?></td>
                                        <td><?php
                                            $date2 = date('d-m-Y', strtotime($asset['date']));
                                            $date1 = date('d-m-Y H:i:s', strtotime($historylatest));

                                            $diff = abs(strtotime($date2) - strtotime($date1));

                                            $years = floor($diff / (365 * 60 * 60 * 24));
                                            $months = floor(($diff -
                                                    $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                            echo $years . ' year ' . $months . ' month ';
                                            ?></td>
                                        <td><?php echo $asset['firstname'] . ' ' . $asset['lastname']; ?></td>
                                        <td><?php echo $asset['notes']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <h1>Condition Guide Chart</h1>
    </div>
    <!-- Condition Guide Chart -->
    <div class="table-responsive">
        <table id="" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>STAR RATINGS</th>
                    <th>CONDITION</th>
                    <th>DESCRIPTION</th>
                    <th>MAINTENANCE/SERVICING</th>
                </tr>
            </thead>
            <tbody id="asset_body">
                <tr>
                    <td>5*****</td>
                    <td>New/Excellent</td>
                    <td>No Wear/Damage Little Use</td>
                    <td>No Maintenance Required/Under Warranty</td>
                </tr>
                <tr>
                    <td>4****</td>
                    <td>Very Good</td>
                    <td>No Faults.Little or No Wear.Low Usage</td>
                    <td>Minor Maintenance or Service</td>
                </tr>
                <tr>
                    <td>3***</td>
                    <td>Good</td>
                    <td>Operates Well.Minor Defects & Damage.Normal Usage</td>
                    <td>Normal Maintenance</td>
                </tr>
                <tr>
                    <td>2**</td>
                    <td>Fair/Operational</td>
                    <td>Fair Appearance/Faults Occuring.Works Ok</td>
                    <td>Major Maintenance - some parts needs replacing or repair</td>
                </tr>
                <tr>
                    <td>1*</td>
                    <td>Poor</td>
                    <td>Poor Appearance & Reliability</td>
                    <td>Requires Replacement or Significant Renewal</td>
                </tr>
                <tr>
                    <td>0</td>
                    <td>Parts Only-Unserviceable</td>
                    <td>Not Operational & Irrepairable</td>
                    <td>Scrap or Used for Parts</td>
                </tr>
            </tbody>
        </table>
    </div>      <!-- /.table-responsive -->

    <!-- Asset Info -->
    <div class="table-responsive">
        <table id="" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Current Status</th>
                    <th>Faulty</th>
                </tr>
            </thead>
            <tbody id="asset_body">
                <tr>
                    <td>Purchase Date</td>
                    <td><?php
                        if ($assetcondition[0]['purchase_date'] != "0000-00-00" && $assetcondition[0]['purchase_date'] != NULL) {
                            echo date('d/m/Y', strtotime($assetcondition[0]['purchase_date']));
                        } else {
                            echo "N/A";
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>Replacement Date</td>
                    <td><?php
                        if ($assetcondition[0]['replace_date'] != "0000-00-00" && $assetcondition[0]['replace_date'] != NULL) {
                            echo date('d/m/y', strtotime($assetcondition[0]['replace_date']));
                        } else {
                            echo "N/A";
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>Warranty Date</td>
                    <td><?php
                        if ($assetcondition[0]['warranty_date'] != "0000-00-00" && $assetcondition[0]['warranty_date'] != NULL) {
                            echo date('d/m/y', strtotime($assetcondition[0]['warranty_date']));
                        } else {
                            echo "N/A";
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td><?php
                        if ($assetcondition[0]['purchase_date'] != "0000-00-00" && $assetcondition[0]['purchase_date'] != NULL) {
                            $date2 = date('d-m-Y', strtotime($assetcondition[0]['purchase_date']));
                            $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                            $diff = abs(strtotime($date2) - strtotime($date1));

                            $years = floor($diff / (365 * 60 * 60 * 24));
                            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                            echo $years . ' year ' . $months . ' month ';
                        } else {
                            echo 'N/A';
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>Total Faults</td>
                    <td><?= $numberOfFaults; ?></td>
                </tr>
            </tbody>
        </table>
    </div>      <!-- /.table-responsive --> 

</div>
<?php if ($this->session->userdata('objSystemUser')->compliance == 1) {
    ?>
    <div id="third_table" class="content_main">
        <div class="row">
            <h1>Safety History</h1>
        </div>
        <div class="compliance_box_top">
            <div class="button_holder">
                <div id="export_csv">
                    <a class="button" id="exportCsvButton" href="#">Export as CSV</a>
                    <a class="button" id="exportPdfButton" href="#">Export as PDF</a>
                </div>
            </div>
            <div class="due_table_contents">
                    <!--<input id="goto_page" style="float: right;" type="number">-->
            </div>

        </div>
        <div id="history" class="form_block">

            <table id="history_table" class="list_table" frame="box" rules="all">
                <thead>
                    <tr>
                        <th data-export="false">Actions</th>
                        <th data-export="true">Safety Name</th>
                        <th data-export="true">Logged By</th>
                        <th data-export="true">Due Date</th>
                        <th data-export="true">Complete Date</th>
                        <th data-export="true">Complete Time</th>
                        <th data-export="true">Result</th>
                        <th data-export="true">No Of Task</th>
                        <th data-export="true">Tasks Failed</th>
                        <th data-export="true">Signature</th>

                        <th hidden=''></th>
                        <th hidden=''></th>
                        <th data-export="false">Doc</th>
                    </tr>
                    <tr>
                        <th>Actions</th>
                        <th>Safety Name</th>
                        <th>Logged By</th>
                        <th>Due Date</th>
                        <th>Complete Date</th>
                        <th>Complete Time</th>
                        <th>Result</th>
                        <th>No Of Task</th>
                        <th>Checks Failed</th>
                        <th></th>
                        <th></th>
                        <th hidden=""></th>
                        <th hidden=""></th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    foreach ($dueTests as $key => $value) {

                        $due_date = date('d/m/Y', strtotime($value['test_date'] . " +" . $value['test_days'] . " days")); // due date calculation
                        $missed = '';
                        if ($value['test_days'] > 0) {    //Missed Check Logic
                            switch ($value['test_days']) {
                                case 1: {    //Daily
                                        if (strtotime($due_date . ' +1 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 5: {    //Daily (Mon-Fri)
                                        if (strtotime($due_date . ' +1 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 7: {    //Weekly
                                        if (strtotime($due_date . ' +7 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 31: {    //Monthly
                                        if (strtotime($due_date . ' +31 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 90: {    //Quaterly
                                        if (strtotime($due_date . ' +31 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 121: {    //Tri-Annual
                                        if (strtotime($due_date . ' +45 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 182: {    //Six Monthly
                                        if (strtotime($due_date . ' +45 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 365: {    //Annual
                                        if (strtotime($due_date . ' +60 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 730: {    //2 Year
                                        if (strtotime($due_date . ' +60 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                                case 1095: {    //3 Year
                                        if (strtotime($due_date . ' +60 day') > strtotime('now')) {
                                            $missed = 'Missed';
                                        }
                                        break;
                                    }
                            }
//                            if($missed=='Missed')
//                                var_dump ($value['test_type']);
                        }
                        ?>
                        <tr>
                            <td><a href='javascript:showTasks(<?php print json_encode($value['tasks']); ?>,"<?php
                                if ($missed == '') {
                                    print 0;
                                } else {
                                    print 1;
                                }
                                ?>")'><img width="20px" src=<?php echo base_url("/img/icons/16/view.png"); ?>></a></td>
                            <td><?php print $value['test_type_name']; ?></td>
                            <td><?php print trim($value['test_person']); ?></td>
                            <td><?php echo ($due_date == '01/01/1970') ? '-' : $due_date; ?></td>
                            <td><?php (isset($value['test_date'])) ? print date('d/m/Y', strtotime($value['test_date']))  : print "Never Tested"; ?></td>
                            <td><?php
                                if (isset($value['test_date'])) {
                                    print date('h:i A', strtotime($value['test_date']));
                                } else {
                                    print "Never Tested";
                                }
                                ?></td>
                            <td><?php
                                if ($missed == '') {
                                    if ($value['result']) {
                                        $flag = TRUE;
                                        $failedTaskCount = 0;
                                        foreach ($value['tasks'] as $key1 => $value1) {
                                            if ($value1['result'] == 0) {
                                                $failedTaskCount++;
                                                $flag = FALSE;
//                                       break;
                                            }
                                        }
                                        if ($flag)
                                            print 'Pass';
                                        else
                                            print 'Fail';
                                    }else {
                                        print 'Fail';
                                    }
                                } else {
                                    print $missed;
                                }
                                ?></td>
                            <td><?php print $value['total_tasks']; ?></td>
                            <td></td>
                            <td><img width="50" title="Item Picture" src="<?= base_url() . '/' . $value['signature_details']->path; ?>" >
                            </td>
                            <td hidden=''><?php print json_encode($value['tasks']); ?></td>
                            <td hidden=''><?php echo $objItem->barcode; ?></td>
                            <td><a class="getPdf_link" href="#"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>
                        </tr>


                    <?php } ?>
                </tbody>
    <!--                <tfoot>
                <th>Actions</th>
                <th>Safety Name</th>
                <th>Logged By</th>
                <th>Due Date</th>
                <th>Complete Date</th>
                <th>Complete Time</th>
                <th>Result</th>
                <th>No Of Task</th>
                <th>Checks Failed</th>
                <th></th>
                <th></th>
                </tfoot>-->
            </table>
        </div>

        <form id="export_csv_form" hidden="" action="<?php echo base_url('/items/exporttocsv'); ?>" method="post">
            <input id="csv_table_content" name="allData">
            <input id="pdfTasks" hidden="" name="tasks">
            <input type="submit">
        </form>

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
                margin-bottom: 50px;
                min-height: 45px;
            }
            .due_table_contents{
                margin-top: 60px;
            }
            /*                .modal-body
                            {
                                min-height: 100px;
                                max-height: 595px;
                                overflow-y: scroll;
                            }*/
        </style>
        <form id="export_form" hidden="" action="<?php echo base_url('/compliance/exportToPdf'); ?>" method="post">
            <input id="exp_table_content" name="allData">
            <input name="filename" value="Compliance History">
            <input type="submit">
        </form>

        <form id="genPdf_form" action="<?php echo site_url('items/generateHistoryPdf'); ?>" method="post">
            <input id="pdfAllData" hidden="" name="allData">
            <input id="pdfTasks" hidden="" name="tasks">
        </form>
        <!--Compliance Task Modal-->
        <div class="modal fade" id="complianceTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Tasks</h4>
                    </div>
                    <div class="modal-body">
                        <table class="list_table">
                            <thead><tr><th>Task Name</th><th>Task Result</th><th>Notes</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

<?php } ?>

<!-- Condition Check Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="condition_check" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Condition Check</h4>
            </div>

            <form action="<?php echo base_url('items/con_history'); ?>" method="post" id="condition_check_form">
                <input type="hidden" name="asset_id" value="<?php echo $this->uri->segment('3'); ?>">
                <div class="modal-body">
                    <!-- Condition Check -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Condition Check</h4></label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Current Condition</label> </div>
                        <div class="col-md-6"><input class="form-control" name="current_condition" id="current_condition" value="<?php echo $objItem->condition_name; ?>" disabled> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>New Condition</label> </div>
                        <div class="col-md-6"><select name="new_condition" class="form-control"><option>----SELECT----</option>  
                                <?php foreach ($conditionlist as $condition) { ?>
                                    <option value="<?php echo $condition['id']; ?>"><?php echo $condition['condition']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12"></div>
                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                </div>
            </form>

        </div>
    </div>
    <!-- /.modal-dialog -->
</div>
<!--</div>-->
<!--</div>
</div>-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="report_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Report Fault</h4>
            </div>

            <form enctype="multipart/form-data" action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/raiseTicket" method="post" id="report_fault_form">
                <input type="hidden" name="report_item_id" id="report_item_id" value="" />
                <input type="hidden" name="report_ticket_id" id="report_ticket_id" value="" />
                <input type="hidden" name="mode" id="mode" value="reportFault" />
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Item</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" value="<?php echo $objItem->item_manu_name; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Manufacturer</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control"   value="<?php echo $objItem->manufacturer; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>QR CODE</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="serial_number" id="serial_number" value="<?php echo $objItem->barcode; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="categoryname" value="<?php echo $objItem->categoryname; ?>" id="categoryname">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Location</label> </div>
                        <div class="col-md-6"><input readonly class="form-control" name="locationname" id="locationname" value="<?php echo $objItem->locationname; ?>">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Severity</label> </div>
                        <div class="col-md-6">
                            <select class="form-control" name="severity" id="severity">
                                <option value="low">Low</option>
                                <option value="normal">Normal</option>
                                <option value="High">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Status</label> </div>
                        <div class="col-md-6">

                            <select name="itemstatusname" class="form-control">
                                <option value="2">Damaged</option>
                                <option selected="selected" value="3">Faulty</option>

                            </select>

                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Enter Order No</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter Order Number" class="form-control" name="order_no" id="order_no" value="" /></div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Photo</label>
                        </div>
                        <div class="col-md-6 input_fields_wrap"> </br>
                            <button class="btn btn-primary btn-circle btn-xs add_field_button" id="img_button" title="add more image" type="button"><i class="glyphicon glyphicon-plus"></i></button>    
                            <div><input class="fileupload upload form-control" type="file" name="photo_file_1" size="20"></div>

                        </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>

                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Job Notes</label>
                        </div>
                    </div>
                    <input type="hidden" name="report_item_id" value="<?php echo $objItem->itemid; ?>"/>
                    <input type="hidden" name="userid" value="<?php echo $arrSessionData['objSystemUser']->userid; ?>"/>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>

                </div>
            </form>
        </div>

    </div>
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="change_owner_model" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Change Owner And Location</h4>
            </div>
            <!--items/changelinks/'.$objItem->itemid-->
            <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/items/changelinks/<?php echo $objItem->itemid; ?>" method="post" id="report_fault_form">

                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Owner</label> </div>
                        <div class="col-md-6">  <select name="new_owner_id" id="new_owner_id" class="form-control">
                                <option value="0">----SELECT----</option>
                                <?php
                                foreach ($arrOwners['results'] as $arrOwner) {
                                    echo "<option ";
                                    echo 'value="' . $arrOwner->ownerid . '" ';
                                    if ($objItem->userid == $arrOwner->ownerid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrOwner->owner_name . "</option>\r\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Location</label> </div>
                        <div class="col-md-6">   <select name="new_location_id" id="new_location_id" class="form-control multi_location_class">

                                <option value="0">----SELECT----</option>
                                <?php
                                foreach ($arrLocations['results'] as $arrLocation) {
                                    echo "<option ";
                                    echo 'value="' . $arrLocation->locationid . '" ';
                                    if ($arrLocation->locationid == $objItem->locationid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrLocation->locationname . "</option>\r\n";
                                }
                                ?>
                            </select>
                            <input type="hidden"  value="<?php echo $objItem->locationid; ?>" name="updated_location_id" id="updated_location_id" class="form-control multi_location_class" readonly>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Site</label> </div>
                        <div class="col-md-6">  <select name="new_site_id" id="new_site_id" class="form-control multi_site_class">
                                <option value="0">----SELECT----</option>
                                <?php
                                foreach ($arrSites['results'] as $arrSite) {
                                    echo "<option ";
                                    echo 'value="' . $arrSite->siteid . '" ';
                                    if ($arrSite->siteid == $objItem->siteid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrSite->sitename . "</option>\r\n";
                                }
                                ?>
                            </select>
                            <input type="hidden" value="<?php echo $objItem->siteid; ?>" name="updated_site_id" id="updated_site_id" class="form-control multi_location_class" >
                        </div>
                    </div>







                    <input type="hidden" name="userid" value="<?php echo $arrSessionData['objSystemUser']->userid; ?>"/>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>

                </div>
            </form>
        </div>

    </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_similar_item" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Similar Item</h4>
                <div class="get_original"></div>
            </div>


            <div class="modal-body">
                <input type="hidden" id="asset_qrcode" value="<?php echo $this->session->userdata('objSystemUser')->qrcode; ?>">
                <form action="<?php echo base_url() . 'items/addSimilarItem/' ?>" method="POST" id="add_similaritem_form"  enctype="multipart/form-data">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Item ID</h4></label> </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Enter QR Code*</label> </div>
                        <div class="col-md-6">  <div class="input-group">
                                <div class="input-group-addon grpaddon">
                                    <?php echo $this->session->userdata('objSystemUser')->qrcode; ?></div>
                                <input placeholder="Enter QR Code" class="form-control barcss" name="item_barcode_similar" id="item_barcode_similar">
                            </div>
                            <div id="qrcodeerror_similar" class="qrcodeerror hide">QR Code Already Exist.</div>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Enter Serial No</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Serial No" class="form-control" name="item_serial_number_similar" id="item_serial_number_similar">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                    </div>
                    <!-- Quality -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Item  Details</h4></label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Quantity</label>
                        </div>
                        <div class="col-md-6"> 
                            <input placeholder="Enter Quantity" name="item_quantity_similar" id="item_quantity_similar" class="form-control">

                        </div>
                    </div> <!-- /.form-group -->

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Ownership</h4></label> </div>
                    </div> 
                    <!-- Ownership -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Owner*</label>
                        </div>
                        <div class="col-md-6"> 
                            <select name="owner_id_similar" id="owner_id_similar" class="form-control">
                                <option value="0">Not Set</option>
                                <?php
                                foreach ($arrOwners['results'] as $arrOwner) {
                                    echo "<option ";
                                    echo 'value="' . $arrOwner->ownerid . '" ';

                                    echo '>' . $arrOwner->owner_name . "</option>\r\n";
                                }
                                ?>
                            </select></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Site*</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="site_id_similar" id="site_id_similar" class="form-control multisiteclass">
                                <option value="0">Not Set</option>
                                <?php
                                foreach ($arrSites['results'] as $arrSite) {
                                    echo "<option ";
                                    echo 'value="' . $arrSite->siteid . '" ';
                                    if ($intSiteId == $arrSite->siteid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrSite->sitename . "</option>\r\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Location*</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="location_id_similar" id="location_id_similar" class="form-control multilocationclass">
                                <option value="0">Not Set</option>
                                <?php
                                foreach ($arrLocations['results'] as $arrLocation) {
                                    echo "<option ";
                                    echo 'value="' . $arrLocation->locationid . '" ';
                                    if ($intLocationId == $arrLocation->locationid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrLocation->locationname . "</option>\r\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Supplier</label>
                        </div>
                        <div class="col-md-6"><select name="supplier_similar" id="supplier_similar" class="form-control">
                                <option value="">Please Select</option>
                                <?php
                                foreach ($arrSuppliers as $supplier) {
                                    echo "<option ";
                                    echo 'value="' . $supplier['supplier_id'] . '" ';
                                    if ($supplier_id == $supplier['supplier_id']) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $supplier['supplier_name'] . "</option>\r\n";
                                }
                                ?>
                            </select></div>
                    </div> 
                    <div class="form-group col-md-12">
                    </div>


                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Condition</label>
                        </div>
                        <div class="col-md-6"><select name="item_condition_similar" id="item_condition_similar" class="form-control">
                                <option>----SELECT----</option>  
                                <?php
                                foreach ($arrCondition as $arrConn) {
                                    ?>
                                    <option value="<?php echo $arrConn['id']; ?>"><?php echo $arrConn['condition']; ?></option>                     
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Purchase Date</label>
                        </div>
                        <div class="col-md-6">
                            <input placeholder="Enter Purchase Date" class="form-control datepicker" name="item_purchased_similar" id="item_purchased_similar" type="text">
                        </div>
                    </div>



                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Purchase Price</label>
                        </div>

                        <div class="col-md-6">       
                            <input placeholder="Enter Purchase Price" class="form-control" name="item_value_similar" id="item_value_similar" type="text">
                        </div>
                        <input type="hidden" readonly name="itemID" id="itemID" value="<?php echo $objItem->itemid; ?>">
                    </div>

                    <div id="customfielddiv" class="form-group col-md-12"></div>


                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <a href="#" id="addmore">
                                Add Another Similar Item?</a> 
                        </div>
                        <div class="col-md-6">
                            <input type="checkbox" id="addanother" name="add_another">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <a id="addowner" href="#">
                                Add Item Same Ownership ?</a> 
                        </div>
                        <div class="col-md-6">
                            <input type="checkbox" id="add_owner" name="add_owner">
                        </div>
                    </div>


            </div>


            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="submit"  id="similar_item">Save</button>
            </div>
            </form>
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="fix_item" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Fix Item</h4>
            </div>

            <form enctype="multipart/form-data" action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/fixfault" method="post" id="fix_item_form">
                <div class="modal-body">
                    <!-- Fix Item -->

                    <input type="hidden" name="fix_item_id" id="fix_item_id" value="<?php echo itm_ . $checkItemReportFaults[0]['item_id'] ?>" />
                    <input type="hidden" name="fix_ticket_id" id="fix_ticket_id" value="<?php echo $checkItemReportFaults[0]['id'] ?>" />
                    <input type="hidden" name="mode" id="mode" value="fixFault" />
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Item Menu</label> </div>
                        <div class="col-md-6"><input readonly disabled="" class="form-control" value="<?php echo $objItem->item_manu_name; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Manufacturer</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control"  value="<?php echo $objItem->manufacturer; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>QR CODE</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="serial_number" id="serial_number" value="<?php echo $objItem->barcode; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="categoryname" id="categoryname" value="<?php echo $objItem->categoryname; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Location</label> </div>
                        <div class="col-md-6"><input readonly class="form-control" name="locationname" id="locationname" value="<?php echo $objItem->locationname; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Action</label> </div>
                        <div class="col-md-6">
                            <input readonly class="form-control" name="action" id="action" value="Fix" disabled="">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Status</label> </div>
                        <div class="col-md-6">
                            <input readonly class="form-control" name="status" id="status" value="OK" disabled="">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Fix Code</label> </div>
                        <div class="col-md-6"><select name="fix_code" id="fix_code" class="form-control">
                                <option value="">Select</option>
                                <option value="Repaired no parts">Repaired no parts</option>
                                <option value="Replaced Parts">Replaced Parts</option>
                                <option value="Reset System">Reset System</option>
                                <option value="Serviced">Serviced</option>
                                <option value="Found Asset">Found Asset</option>
                                <option value="Changed Consumables">Changed Consumables</option>								
                            </select>
                        </div></div> <!-- /.form-group -->
                    <!-- Job Notes -->
                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Photo</label>
                        </div>
                        <div class="col-md-6 input_fix_wrap"> </br>
                            <button class="btn btn-primary btn-circle btn-xs add_field_button" id="img_fix_button" title="add more image" type="button"><i class="glyphicon glyphicon-plus"></i></button>    
                            <div><input class="fileupload upload form-control" type="file" name="photo_file_1" size="20"></div>

                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal"  class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" name="view_fix" value="view" type="submit" id="fix_save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">View Fault</h4>
            </div>

            <div class="modal-body">
                <!-- Report Fault -->

                <div class="form-group col-md-12">
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Item Menu</label> </div>
                    <div class="col-md-6">  <input readonly class="form-control" value="<?php echo $objItem->item_manu_name; ?>" name="item_manu" id="vx_item_manu">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Manufacturer</label> </div>
                    <div class="col-md-6">  <input readonly class="form-control" name="manufacturer" id="v_manufacturer">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>QR CODE</label> </div>
                    <div class="col-md-6">  <input readonly class="form-control" name="serial_number" id="v_serial_number">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Category</label> </div>
                    <div class="col-md-6">  <input readonly class="form-control" name="categoryname" id="v_categoryname">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Location</label> </div>
                    <div class="col-md-6"><input readonly class="form-control" name="locationname" id="v_locationname">
                    </div>
                </div>

                <div class="form-group col-md-12">
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Severity</label> </div>
                    <div class="col-md-6">
                        <select class="form-control" name="severity" id="v_severity" disabled="">
                            <option value="low">Low<option>
                            <option value="normal">Normal<option>
                            <option value="High">High<option>
                            <option value="critical">Critical<option>
                        </select>
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Status</label> </div>
                    <div class="col-md-6"><input  class="form-control" name="itemstatusname" id="v_itemstatusname" disabled="">

                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Enter Order No</label> </div>
                    <div class="col-md-6"><input type="text" name="order_no" id="v_order_no" class="form-control" value="" disabled="" /></div></div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                </div>

                <div class="actionData">

                </div>     

                <!-- Job Notes -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Job Notes</label>   </div>
                    <div class="col-md-6 job_notes_div" >
                    </div>


                </div>
                <div class="form-group col-md-12 fault_photo">
                    <div class="col-md-6"><label>Photos</label>   </div>
                    <div class="col-md-6" id="photo_div"> </div>


                </div>

            </div>

            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>


<!-- Update Fault Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="update_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Update Fault</h4>
            </div>

            <form enctype="multipart/form-data" action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/fixfault" method="post" id="update_fault_form">
                <div class="modal-body">
                    <!-- Fix Item -->

                    <input type="hidden" name="update_item_id" id="update_item_id" value="" />
                    <input type="hidden" name="update_ticket_id" id="update_ticket_id" value="" />
                    <input type="hidden" name="mode" id="mode" value="updateFault" />

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Item</label> </div>
                        <div class="col-md-6"><input readonly class="form-control" name="item_manu" id="uu_item_manu" disabled="">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Manufacturer</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="manufacturer" id="uu_manufacturer" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>QR CODE</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="serial_number" id="uu_serial_number" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="categoryname" id="uu_categoryname" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Location</label> </div>
                        <div class="col-md-6"><input readonly class="form-control" name="locationname" id="uu_locationname" disabled>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Action</label> </div>
                        <div class="col-md-6"><select readonly="readonly" name="action" id="action" class="form-control" disabled>
                                <option value="0">Select</option>
                                <option selected="" value="Open job">Open job</option>
                                <option value="Fix">Fix</option>
                            </select> 
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Status</label> </div>
                        <div class="col-md-6"><select name="status" id="status" class="form-control">
                                <option value="2">Damaged</option>
                                <option value="3">Faulty</option>


                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Reason Code</label> </div>
                        <div class="col-md-6"><select name="reason_code" id="reason_code" class="form-control">
                                <option value="">Select</option>
                                <option value="Waiting for parts">Waiting for parts</option>
                                <option value="Need more time">Need more time</option>
                                <option value="Specialist Equipment Rqd">Specialist Equipment Rqd</option>
                                <option  value="H&S requirements">H&S requirements</option>	
                            </select>
                        </div></div> <!-- /.form-group -->
                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Photo</label>
                        </div>
                        <div class="col-md-6 input_fields_wrap1"> </br>
                            <button class="btn btn-primary btn-circle btn-xs add_field_button1" id="img_button1" title="add more image" type="button"><i class="glyphicon glyphicon-plus"></i></button>    
                            <div><input class="fileupload upload form-control" type="file" name="photo_file_1" size="20"></div>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Fix Item Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="fix_item" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Fix Item</h4>
            </div>

            <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/fixfault" method="post" id="fix_item_form">
                <div class="modal-body">
                    <!-- Fix Item -->

                    <input type="hidden" name="fix_item_id" id="fix_item_id" value="" />
                    <input type="hidden" name="fix_ticket_id" id="fix_ticket_id" value="" />
                    <input type="hidden" name="mode" id="mode" value="fixFault" />
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Item</label> </div>
                        <div class="col-md-6"><input readonly class="form-control" name="item_manu" id="u_item_manu">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Manufacturer</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="manufacturer" id="u_manufacturer">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>QR CODE</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="serial_number" id="u_serial_number">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="categoryname" id="u_categoryname">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Location</label> </div>
                        <div class="col-md-6"><input readonly class="form-control" name="locationname" id="u_locationname">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Action</label> </div>
                        <div class="col-md-6">
                            <input readonly class="form-control" name="action" id="action" value="Fix" disabled="">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Status</label> </div>
                        <div class="col-md-6">
                            <input readonly class="form-control" name="status" id="status_fix" disabled="" value="OK">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Fix Code</label> </div>
                        <div class="col-md-6"><select name="fix_code" id="fix_code" class="form-control">
                                <option value="">Select</option>
                                <option value="Repaired no parts">Repaired no parts</option>
                                <option value="Replaced Parts">Replaced Parts</option>
                                <option value="Reset System">Reset System</option>
                                <option value="Serviced">Serviced</option>
                                <option value="Found Asset">Found Asset</option>
                                <option value="Changed Consumables">Changed Consumables</option>								
                            </select>
                        </div></div> <!-- /.form-group -->
                    <!-- Job Notes -->
                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="fix_save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="viewfaultFix" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">View Fault</h4>
            </div>

            <div class="modal-body">
                <!-- Report Fault -->

                <div class="form-group col-md-12">
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Item</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="item_manu" id="v_item_manu">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Manufacturer</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="manufacturer" id="v_manufacturer">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>QR CODE</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="serial_number" id="v_serial_number">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Category</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="categoryname" id="v_categoryname">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Location</label> </div>
                    <div class="col-md-6"><input readonly="readonly" class="form-control" name="locationname" id="v_locationname">
                    </div>
                </div>

                <div class="form-group col-md-12">
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Severity</label> </div>
                    <div class="col-md-6">
                        <select class="form-control" name="severity" id="v_severity" disabled="">
                            <option value="low">Low<option>
                            <option value="normal">Normal<option>
                            <option value="High">High<option>
                            <option value="critical">Critical<option>
                        </select>
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Status</label> </div>
                    <div class="col-md-6"><input  class="form-control" name="itemstatusname" id="v_itemstatusname" disabled="">
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Enter Order No</label> </div>
                    <div class="col-md-6"><input type="text" name="order_no" id="v_order_no" class="form-control" value="" disabled="" /></div></div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                </div>
                <div class="actionData">

                </div> 
                <!-- Job Notes -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Job Notes</label>
                    </div>
                    <div class="col-md-6 job_notes_div1">
                    </div>
                </div>
                <div class="form-group col-md-12" >

                </div>
                <div class="form-group col-md-12 fault_photo2">
                    <div class="col-md-6"><label>Photos</label>   </div>
                    <div class="col-md-6" id="photo_div_resolve1"> </div>
                </div>
            </div>

            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(document).ready(function() {
        var base_url = $("#base_url").val();
        var site_server = '<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>';
        $('.ui-lightbox-gallery').each(function() { // the containers for all your galleries
            $(this).magnificPopup({
                delegate: 'a', // the selector for gallery item
                type: 'image',
                gallery: {
                    enabled: true
                },
            });
        });

        $("#update_fault_form").validate({
            rules: {
                reason_code: "required",
                job_notes: "required"

            },
            messages: {
                reason_code: "Please Select Reason Code",
                jobnote: "Please Enter Job Note"
            }
        });

        $("#fix_item_form").validate({
            rules: {
                action: {required: true},
                status: {required: true},
                fix_code: {required: true},
                job_notes: "required"
            },
            messages: {
                action: "Please Select Action",
                status: "Please Select Status",
                fix_code: "Please Select Fix Code",
                job_notes: "Please Enter Job Note"
            }
        });

        $(".updatefault").click(function() {

            var iId = $(this).attr('id');

            var account_id = $(this).attr('account_id');
            var ticket_id = $(this).attr('ticket_id');
            var type = 'Open Job';
            $("#update_item_id").val(iId);
            $("#update_ticket_id").val(ticket_id);
            console.log(ticket_id);

            console.log('updatefault');
            $.ajax({
                type: "POST",
                url: site_server + "/youaudit/iwa/faults/ajaxfetchItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id + "&type=" + type,
                success: function(data) {
                    $("#uu_item_manu").val(data.item_manu_name);
                    $("#uu_manufacturer").val(data.manufacturer);
                    $("#uu_serial_number").val(data.barcode);
                    $("#uu_categoryname").val(data.categoryname);
                    $("#uu_locationname").val(data.locationname);
                    $("#uu_itemstatusname").val(data.itemstatusname);

                    $("#update_fault #status").find('option').each(function(i, opt) {
                        if (opt.value == data.itemstatusid) {
                            $(opt).attr('selected', 'selected');
                        }

                    });

                    $("#update_fault #fix_code").find('option').each(function(i, opt) {
                        if (opt.value == data.fix_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#update_fault #action").find('option').each(function(i, opt) {

                        if (opt.value == data.ticket_action) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#update_fault #reason_code").find('option').each(function(i, opt) {

                        if (opt.value == data.reason_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });
                    $("#update_fault #job_notes").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax

        });

//##############################
// for update incident..
        // button for add image
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(); //Fields wrapper
        var add_button = $(".add_field_button1"); //Add button ID
        var x = 1;
        var y = 1;
        //initlal text box count

        $("body").find("#img_button1").click(function(e) { //on add input button click

            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(".input_fields_wrap1").append('<div> <input class="fileupload upload form-control" type="file" name="photo_file_' + x + '" size="20"><a href="#" class="remove_field1">Remove</a></div>'); //add input box


            }
        });
        $(".input_fields_wrap1").on("click", ".remove_field1", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
            total--;
        });
        $("body").find("#img_fix_button1").click(function(e) { //on add input button click

            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(".input_fix_wrap1").append('<div> <input class="fileupload upload form-control" type="file" name="photo_file_' + x + '" size="20"><a href="#" class="remove_field1">Remove</a></div>'); //add input box


            }
        });
        $(".input_fix_wrap1").on("click", ".remove_field1", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
            total--;
        });
//##############################
        $(".fixitem").click(function() {
            var iId = $(this).attr('id');

            var account_id = $(this).attr('account_id');
            var ticket_id = $(this).attr('ticket_id');
            var type = 'Open Job';
            $("#fix_item_id").attr("value", iId);
            $("#fix_ticket_id").attr("value", ticket_id);
            //alert(ticket_id);
            console.log('I am click fix iteam');
            $.ajax({
                type: "POST",
                url: site_server + "/youaudit/iwa/faults/ajaxfetchItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id + "&type=" + type,
                success: function(data) {
                    console.log('I am result');
                    $("#u_item_manu").val(data.item_manu_name);
                    $("#u_manufacturer").val(data.manufacturer);
                    $("#u_serial_number").val(data.barcode);
                    $("#u_categoryname").val(data.categoryname);
                    $("#u_locationname").val(data.locationname);
                    $("#status").val(data.itemstatusname);
                    $("#u_itemstatusname").val(data.fix_code);

                    $("#fix_item #status").find('option').each(function(i, opt) {
                        if (opt.value == data.itemstatusid) {
                            $(opt).attr('selected', 'selected');
                        }

                    });

                    $("#fix_item #fix_code").find('option').each(function(i, opt) {
                        if (opt.value == data.fix_code) {
                            $(opt).attr('selected', 'selected');
                        }
                    });

                    $("#fix_item #action").find('option').each(function(i, opt) {
                        if (opt.value == data.action) {
                            $(opt).attr('selected', 'selected');
                        }
                    });

                    $("#fix_item #fix_code").find('option').each(function(i, opt) {
                        if (opt.value == data.fix_code) {
                            $(opt).attr('selected', 'selected');
                        }
                    });

                    $("#fix_item #job_notes").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax


        });

        $(document).find(".viewfault").click(function() {

            var iId = $(this).attr('id');
            var account_id = $(this).attr('account_id');
            var type = $(this).attr('data-val');
            // Call ajax
            $.ajax({
                type: "POST",
                url: base_url + "faults/ajaxfetchItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id + "&type=" + type,
                success: function(data) {

                    $("#view_fault #v_item_manu").val(data.item_manu);
                    $("#view_fault #v_manufacturer").val(data.manufacturer);
                    $("#view_fault #v_serial_number").val(data.barcode);
                    $("#view_fault #v_categoryname").val(data.categoryname);
                    $("#view_fault #v_locationname").val(data.locationname);
                    $("#view_fault #v_itemstatusname").val(data.itemstatusname);
                    $("#view_fault #v_order_no").val(data.order_no);
                    $("#view_fault .actionData").html(data.actionData);
                    $("#view_fault  #v_status").find('option').each(function(i, opt) {
                        if (opt.value == data.itemstatusid) {
                            $(opt).attr('selected', 'selected');
                        }

                    });
                    $("#view_fault  #v_severity").find('option').each(function(i, opt) {
                        if (opt.value == data.severity) {
                            $(opt).attr('selected', 'selected');
                        }
                        else
                        {
                            $(opt).attr('selected', false);
                        }

                    });
                    $("#view_fault  #v_fix_code").find('option').each(function(i, opt) {
                        if (opt.value == data.fix_code) {
                            $(opt).attr('selected', 'selected');
                        }
                    });
                    $("#view_fault #v_action").find('option').each(function(i, opt) {

                        if (opt.value == data.ticket_action) {
                            $(opt).attr('selected', 'selected');
                        }
                    });
                    $("#view_fault #v_reason_code").find('option').each(function(i, opt) {

                        if (opt.value == data.reason_code) {
                            $(opt).attr('selected', 'selected');
                        }
                    });
                    if (data.allPhoto != null) {
                        var photoid = data.allPhoto.split(',');
                    }
                    else {
                        var photoid = data.photoid.split(',');
                    }
                    if (photoid.length != 0) {
                        $('.fault_photo').css('display', 'block');
                        $("#photo_div").empty();
                        for (var i = 0; i < photoid.length; i++) {
                            var img_div = '';
                            img_div += "<div style='float:left' class='ui-lightbox-gallery'>";
                            img_div += "<div class='imagesingle'>";
                            img_div += "<img width='65' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='" + base_url + "/index.php/images/viewList/" + photoid[i] + "'>";
                            img_div += "</div></div>";
                            $("#photo_div").append(img_div);
                        }
                    }
                    else
                    {
                        $('.fault_photo').css('display', 'none');
                    }


                    if (data.allNotes != null) {
                        var allNote = data.allNotes.split(',');
                    }
                    else {
                        var allNote = data.jobnote.split(',');
                    }

                    if (allNote.length != 0) {
                        $(".job_notes_div").empty();
                        var notes_div = '';
                        notes_div += "<ul>";
//                        notes_div += "<li>" + data.jobnote + "</li>";
                        for (var i = 0; i < allNote.length; i++) {
                            notes_div += "<li>" + allNote[i] + "</li>";
                        }
                        notes_div += "</ul>";
                        $(".job_notes_div").html(notes_div);
                    }
                    $("#view_fault #v_job_notes").val(data.jobnote);
                    $("#save_button").show();
                } // End of success
            }); // End of ajax

        });


        // fixed fault history..
        $(".viewfaultFix").click(function() {

            var iId = $(this).attr('id');
            var account_id = $(this).attr('account_id');
            var ticket_id = $(this).attr('ticket_id');
            var type = 'Fix';
            // Call ajax
            $.ajax({
                type: "POST",
                url: base_url + "faults/ajaxfetchItemForSingleItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id + "&type=" + type + "&ticket_id=" + ticket_id,
                success: function(data) {
                    console.log('I am result');
                    $("#viewfaultFix #v_item_manu").val(data.item_manu_name);
                    $("#viewfaultFix #v_manufacturer").val(data.manufacturer);
                    $("#viewfaultFix #v_serial_number").val(data.barcode);
                    $("#viewfaultFix #v_categoryname").val(data.categoryname);
                    $("#viewfaultFix #v_locationname").val(data.locationname);
                    $("#viewfaultFix #v_itemstatusname").val(data.itemstatusname);
                    $("#viewfaultFix #v_order_no").val(data.order_no);
                    $("#viewfaultFix .actionData").html(data.actionData);


                    $("#viewfaultFix  #v_status").find('option').each(function(i, opt) {
                        if (opt.value == data.itemstatusid) {
                            $(opt).attr('selected', 'selected');
                        }

                    });

                    $("#viewfaultFix  #v_severity").find('option').each(function(i, opt) {
                        if (opt.value == data.severity) {
                            $(opt).attr('selected', 'selected');
                        }
                        else
                        {
                            $(opt).attr('selected', false);
                        }

                    });

                    $("#viewfaultFix  #v_fix_code").find('option').each(function(i, opt) {
                        if (opt.value == data.fix_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#viewfaultFix #v_action").find('option').each(function(i, opt) {

                        if (opt.value == data.ticket_action) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#viewfaultFix #v_reason_code").find('option').each(function(i, opt) {

                        if (opt.value == data.reason_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });
                    if (data.allPhoto != null) {
                        var updatePhoto = data.allPhoto.split(',');
                    }
                    else {
                        var updatePhoto = data.photoid.split(',');
                    }
                    if (updatePhoto.length != 0) {
                        $('.fault_photo2').css('display', 'block');


                        $("#photo_div_resolve1").empty();
                        for (var i = 0; i < updatePhoto.length; i++) {
                            var img_div = '';
                            img_div += "<div style='float:left' class='ui-lightbox-gallery'>";
                            img_div += "<div class='image_single'>";
                            img_div += "<img width='65' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='" + base_url + "/index.php/images/viewList/" + updatePhoto[i] + "'>";
                            img_div += "</div></div>";
                            $("#photo_div_resolve1").append(img_div);
                        }
                    }
                    else
                    {
                        $('.fault_photo2').css('display', 'none');
                    }

//                    if (data.allNotes) {
//                        var jobnotes = data.allNotes.split(',');
//                        $(".job_notes_div").empty();
//                        var job_div = '';
//
//                        job_div += "<ul>";
//                        for (var i = 0; i < jobnotes.length; i++) {
//
//                            job_div += "<li>" + jobnotes[i] + "</li>";
//                        }
//
//                        job_div += "</ul>";
//                        console.log(job_div);
//                        $(".job_notes_div").html(job_div);
//                    }
                    var notes_div = '';
                    if (data.allNotes != "") {
                        var allNote = data.allNotes.split(',');
                        var noteDate = data.notesDate.split(',');
                        $(".job_notes_div").empty();

                        notes_div += "<ul>";
                        for (var i = 0; i < allNote.length; i++) {
                            notes_div += "<li style='list-style:none;padding:0;margin:0;'>" + noteDate[i] + " - " + allNote[i] + "</li>";

                        }
                        notes_div += "</ul>";
                    } else {
                        $(".job_notes_div1").empty();
                        notes_div += "<ul><li style='list-style:none;padding:0;margin:0;'>" + data.loggedByDate + " - " + data.jobnote + "</li></ul>";
                    }

                    $(".job_notes_div1").html(notes_div);

                    $("#viewfaultFix #v_job_notes1").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax

        }) // End of function 
//        ############################

        $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
// Code To Check Unique Barcode

        $("#item_barcode_similar").on("keyup blur", function() {
            var code = $("#asset_qrcode").val();
            var bar_code = $("#item_barcode_similar").val();
            var qrcode = code + bar_code;
            var base_url_str = $("#base_url").val();
            $.ajax({
                type: "POST",
                url: base_url_str + "items/checkQrcode",
                data: {
                    'bar_code': qrcode
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#similar_item").addClass('disabled');
                        $("#qrcodeerror_similar").removeClass("hide");
                    } else {
                        $("#similar_item").removeClass('disabled');
                        $("#qrcodeerror_similar").addClass("hide");
                    }
                }

            });
        });
        $("#add_similaritem_form").validate({
            rules: {
//                item_quantity_similar: {required: true},
                item_barcode_similar: "required",
//                item_serial_number_similar: "required",
//                status_id_similar: {required: true, min: 1},
                owner_id_similar: {required: true, min: 1},
                site_id_similar: {required: true, min: 1},
                location_id_similar: {required: true, min: 1},
//                supplier_similar: {required: true, min: 1},
//                item_value_similar: "required",
//                item_condition_similar: {required: true, min: 1},
//                item_purchased_similar: "required",
            },
            messages: {
//                item_quantity_similar: "Please Select Quantity",
                item_barcode_similar: "Please Enter QR_code",
//                item_serial_number_similar: "Please Enter Serial Number",
//                status_id_similar: "Please Enter Status",
//                item_condition_similar: "Please Select Condition",
                owner_id_similar: "Please Select Owner",
                site_id_similar: "Please Select Site",
                location_id_similar: "Please Select Location",
//                supplier_similar: "Please Select Supplier",
//                item_value_similar: "Please Enter Purchase Price",
//                item_purchased_similar: "Please Enter Purchase Date",
            },
        });
        $("#itemedit").validate({
            rules: {
                category_id: {required: true, min: 1},
//                item_model: {required: true},
//                item_quantity: {required: true},
//                item_barcode: "required",
//                item_serial_number: "required",
//                status_id: {required: true, min: 1},
//                item_condition: {required: true, min: 1},
                owner_id: {required: true, min: 1},
                site_id: {required: true, min: 1},
                location_id: {required: true, min: 1},
//                supplier: {required: true, min: 1},
//                item_patteststatus: {required: true, min: 1},
//                item_value: "required",
//                item_notes: "required",
//                item_pattestdate: "required",
            },
            messages: {
                category_id: "Please Select Category",
//                item_manu: "Please Select Manu",
//                item_model: "Please Select Model",
//                item_quantity: "Please Select Quantity",
//                item_barcode: "Please Enter QR_code",
//                item_serial_number: "Please Enter Serial Number",
//                status_id: "Please Enter Status",
//                item_condition: "Please Select Condition",
                owner_id: "Please Select Owner",
                site_id: "Please Select Site",
                location_id: "Please Select Location",
//                supplier: "Please Select Supplier",
//                item_value: "Please Enter Purchase Price",
//                item_current_value: "Please Enter Current Value",
//                item_notes: "Please Enter Notes",
//                item_pattestdate: "Please Enter PAT Test Date",
//                item_patteststatus: "Please Enter PAT Test Result"
            }
        });
<?php if ($this->uri->segment(2) == 'editItem') { ?>
            $('.update').css('display', 'block');
            $("#view_itemdetails input").removeAttr("disabled");
            $("#view_itemdetails textarea").removeAttr("disabled");
            $("#view_itemdetails select").removeAttr("disabled");
            $("#item_purchased").datepicker("option", "disabled", false);
            $("#item_warranty").datepicker("option", "disabled", false);
            $("#item_replace").datepicker("option", "disabled", false);
            $("#item_pattestdate").datepicker("option", "disabled", false);
            $("#custom_field_div input").removeAttr("disabled");
            //            $(".item_photo").removeAttr("disabled");
            //            $(".pic_button").removeAttr("disabled");
<?php }
?>

        //            Trigging Of Edit Button
        $("#item_edit").click(function() {

            $('.update').css('display', 'block');
            $("#view_itemdetails input").removeAttr("disabled");
            $("#view_itemdetails textarea").removeAttr("disabled");
            $("#view_itemdetails select").removeAttr("disabled");
            $("#custom_field_div input").removeAttr("disabled");
            $("#custom_field_div select").removeAttr("disabled");
            $("#item_purchased").datepicker("option", "disabled", false);
            $("#item_warranty").datepicker("option", "disabled", false);
            $("#item_replace").datepicker("option", "disabled", false);
            $("#item_pattestdate").datepicker("option", "disabled", false);
            $(".item_photo").removeAttr("disabled");
//            $(".pic_button").removeAttr("disabled");
//            $(".delete").removeAttr("disabled");
//            $(".add_field_button").removeAttr("disabled");
//            $(".add_pdf_button").removeAttr("disabled");
        });
        $('#chooseColumnsForm').trigger('reset');
        $('#history_table').find('td:empty').html('&nbsp;');
        var colCount = 0;
        var arr = [];
        $('#history_table thead tr:eq(0) th').each(function() {
            if ($(this).attr("hidden") || $(this).attr('data-export') == 'false') {

            } else {
                arr.push($(this).index());
            }
        });
        console.log(arr);
        var table = $('#history_table').DataTable({
            "pagingType": "full_numbers",
            "bSortCellsTop": true,
            "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
            "order": [[4, "desc"]],
            columnDefs: [
                {type: 'date-uk', targets: 3},
                {type: 'date-uk', targets: 4}
            ]

        });
        $("#history_table thead tr:eq(1) th").each(function(i) {
            if (i == 1 || i == 2 || i == 6) {
                var select = $('<select><option value="">Reset Filter</option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    if ($(this).val() != '')
                    {
                        console.log(table);
                        table.column(i)
                                .search('^' + $(this).val() + '$', true, false)
                                .draw();
                    }
                    else {
                        console.log(table);
                        $('.dataTables_length').prependTo('.dataTables_wrapper');
                        table.destroy();
                        table = $('#history_table').DataTable({
                            "pagingType": "full_numbers",
                            "bSortCellsTop": true,
                            "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                            "order": [[4, "asc"]],
                            columnDefs: [
                                {type: 'date-uk', targets: 3},
                                {type: 'date-uk', targets: 4}
                            ]

                        });
                    }
                });
                table.column(i).data().unique().sort().each(function(d, j) {
                    if (d != '&nbsp;')
                        select.append('<option value="' + d + '">' + d + '</option>');
                });
            }
            else
                $(this).html("&nbsp;");
        });
        setTimeout(function() {
            $('#history_table').wrap('<div style="width:100%;overflow-x:auto;min-height:300px;background:#fff;"/>');
        }, 1000);
        //  ------------------export---------
        $('#exportPdfButton').on('click', function(e) {

            var data = table
                    .data()
                    .map(function(row) {
                var rowArr = [];
                $.each(arr, function(i, v) {
                    rowArr.push(row[v]);
                });
                return '<td>' + rowArr.join('</td><td>') + '</td>';
            })
                    .join('</tr><tr>');
            data = '<tbody><tr>' + data + '</tr></tbody>';
            var cloneHead = [];
            var head = $('#history_table thead').clone();
            head.find('th[data-export="true"]').each(function(i) {
                console.log($(this).html());
                cloneHead.push($(this).html());
            });
            cloneHead = '<thead><tr><th>' + cloneHead.join('</th><th>') + '</th></tr></thead>';
            console.log(cloneHead);
            $('#exp_table_content').val(cloneHead + data);
            $('#export_form').submit();
        });
        // ----------CSV Export----------------
        $('#exportCsvButton').on('click', function(e) {
            var data1 = $("#history_table").dataTable()._('tr', {"filter": "applied"});
            var data = data1.map(function(row) {
                var rowArr = [];
                $.each(arr, function(i, v) {
                    rowArr.push(row[v]);
                });
                return rowArr.join(',');
            }).join('|');
            var cloneHead = [];
            var head = $('#history_table thead').clone();
            head.find('th[data-export="true"]').each(function(i) {
                cloneHead.push($(this).html());
            });
            cloneHead = cloneHead.join(',');
            //            alert(cloneHead+data);
            $('#csv_table_content').val(cloneHead + '|' + data);
            $('#export_csv_form').submit();
        });
        //        --------Clear Filter-----------
        $('#clearFilter').on('click', function() {
            $('.dataTable').find('tfoot th select option[value=""]').prop('selected', true);
            $('.dataTables_length').prependTo('.dataTables_wrapper');
            table.destroy();
            table = $('#history_table').DataTable({
                "pagingType": "full_numbers",
                "bSortCellsTop": true,
                "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                "order": [[4, "asc"]],
                columnDefs: [
                    {type: 'date-uk', targets: 3},
                    {type: 'date-uk', targets: 4}
                ]

            });
        });
        $('body').on("click", '.getPdf_link', function() {
            var row = $(this).parent('td').parent('tr');
            var rowData = table.row(row).data();
            rowData.shift(); 
            $('#genPdf_form input#pdfTasks').val(rowData[9]);
            rowData[9] = '';
            console.log(rowData);
            $('#genPdf_form input#pdfAllData').val(rowData);
            $('#genPdf_form').submit();
        });
        // Establish Link to Owner,Location and Site
        $('body').find('#owner_id').change(function() {
            var owner_id = this.value;
            if (owner_id != 0) {
                $.getJSON("<?php echo base_url('items/getlocationbyowner'); ?>" + '/' + owner_id, function(data) {

                    if (data.results.length != 0) {
                        $('.multi_location_class option[value="' + data.results[0].location_id + '"]').attr('selected', 'selected');
                        $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + data.results[0].location_id, function(site_data) {
                            if (site_data != null)
                            {
                                $('.multi_site_class option[value="' + site_data.results[0].site_id + '"]').attr('selected', 'selected');
                            }
                            else {
                                $('.multi_site_class option[value="0"]').attr('selected', 'selected');
                            }
                        });
                    }
                    else {
                        $('.multi_location_class option[value="0"]').attr('selected', 'selected');
                        $('.multi_site_class option[value="0"]').attr('selected', 'selected');
                    }
                });
            }
            else
            {
                $('.multi_location_class option[value="0"]').attr('selected', 'selected');
                $('.multi_site_class option[value="0"]').attr('selected', 'selected');
            }
        });

        // estblish link and site link

        $(document).find('.multi_site_class').change(function() {
            $(".multi_location_class").empty();
            var site_id = this.value;
            if (site_id != 0) {
                $.getJSON("<?php echo base_url('items/getownerbysite'); ?>" + '/' + site_id, function(data) {
                    if (data.results.length != 0) {
                        $('#owner_id option[value="' + data.results[0].id + '"]').attr('selected', 'selected');
                    }
                    else
                    {
                        $('#owner_id option[value="0"]').attr('selected', 'selected');
                    }
                });
                $.getJSON("<?php echo base_url('items/getlocationbysite'); ?>" + '/' + site_id, function(data) {
                    if (data.results.length != 0) {

                        var location_data = '';
                        for (var i = 0; i < data.results.length; i++) {
                            location_data += '<option value=' + data.results[i].id + '>' + data.results[i].name + '</option>';
                        }
                        $(".multi_location_class").append(location_data);
                    }
                    else {
                        $(".multi_location_class").append("<option value='0'>Not Set</option>");
                    }
                });
            }
            else {
                $.getJSON("<?php echo base_url('items/getalllocation'); ?>", function(data) {
                    if (data.results.length != 0) {

                        var location_data = '';
                        location_data += "<option value='0'>Not Set</option>";
                        for (var i = 0; i < data.results.length; i++) {
                            location_data += '<option value=' + data.results[i].locationid + '>' + data.results[i].locationname + '</option>';
                        }
                        $(".multi_location_class").append(location_data);
                    }
                    else {
                        $(".multi_location_class").append("<option value='0'>Not Set</option>");
                    }
                });
            }
        });
        // select site accroding to location for multi acc
        $(document).find('.multi_location_class').change(function() {


            var site_id = this.value;
            $.getJSON("<?php echo base_url('items/getownerbylocation'); ?>" + '/' + site_id, function(data) {
                if (data.results.length != 0) {
                    $('#owner_id option[value="' + data.results[0].id + '"]').attr('selected', 'selected');
                }
                else
                {
                    $('#owner_id option[value="0"]').attr('selected', 'selected');
                }
            });
            $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + site_id, function(data) {

                if (data.results.length != 0) {
                    $('.multi_site_class option[value="' + data.results[0].site_id + '"]').attr('selected', 'selected');
                }
                else {
                    $('.multi_site_class option[value="0"]').attr('selected', 'selected');
                }
            });
        });

        // Establish Link to Owner,Location and Site
        $('body').find('#owner_id_similar').change(function() {
            var owner_id = this.value;
            if (owner_id != 0) {
                $.getJSON("<?php echo base_url('items/getlocationbyowner'); ?>" + '/' + owner_id, function(data) {

                    if (data.results.length != 0) {
                        $('.multilocationclass option[value="' + data.results[0].location_id + '"]').attr('selected', 'selected');
                        $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + data.results[0].location_id, function(site_data) {
                            if (site_data != null)
                            {
                                $('.multisiteclass option[value="' + site_data.results[0].site_id + '"]').attr('selected', 'selected');
                            }
                            else {
                                $('.multisiteclass option[value="0"]').attr('selected', 'selected');
                            }
                        });
                    }
                    else {
                        $('.multilocationclass option[value="0"]').attr('selected', 'selected');
                        $('.multisiteclass option[value="0"]').attr('selected', 'selected');
                    }
                });
            }
            else
            {
                $('.multilocationclass option[value="0"]').attr('selected', 'selected');
                $('.multisiteclass option[value="0"]').attr('selected', 'selected');
            }
        });

        // estblish link and site link

        $(document).find('.multisiteclass').change(function() {
            $(".multilocationclass").empty();
            var site_id = this.value;
            if (site_id != 0) {
                $.getJSON("<?php echo base_url('items/getownerbysite'); ?>" + '/' + site_id, function(data) {
                    if (data.results.length != 0) {
                        $('#owner_id_similar option[value="' + data.results[0].id + '"]').attr('selected', 'selected');
                    }
                    else
                    {
//                        $('#owner_id_similar option[value="0"]').attr('selected', 'selected');
                    }
                });
                $.getJSON("<?php echo base_url('items/getlocationbysite'); ?>" + '/' + site_id, function(data) {
                    if (data.results.length != 0) {

                        var location_data = '';
                        for (var i = 0; i < data.results.length; i++) {
                            location_data += '<option value=' + data.results[i].id + '>' + data.results[i].name + '</option>';
                        }
                        $(".multilocationclass").append(location_data);
                    }
                    else {
                        $(".multilocationclass").append("<option value='0'>Not Set</option>");
                    }
                });
            }
            else {
                $.getJSON("<?php echo base_url('items/getalllocation'); ?>", function(data) {
                    if (data.results.length != 0) {

                        var location_data = '';
                        location_data += "<option value='0'>Not Set</option>";
                        for (var i = 0; i < data.results.length; i++) {
                            location_data += '<option value=' + data.results[i].locationid + '>' + data.results[i].locationname + '</option>';
                        }
                        $(".multilocationclass").append(location_data);
                    }
                    else {
                        $(".multilocationclass").append("<option value='0'>Not Set</option>");
                    }
                });
            }
        });
        // select site accroding to location for multi acc
        $(document).find('.multilocationclass').change(function() {


            var site_id = this.value;
            $.getJSON("<?php echo base_url('items/getownerbylocation'); ?>" + '/' + site_id, function(data) {
                if (data.results.length != 0) {
                    $('#owner_id_similar option[value="' + data.results[0].id + '"]').attr('selected', 'selected');
                }
                else
                {
//                    $('#owner_id_similar option[value="0"]').attr('selected', 'selected');
                }
            });
            $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + site_id, function(data) {

                if (data.results.length != 0) {
                    $('.multisiteclass option[value="' + data.results[0].site_id + '"]').attr('selected', 'selected');
                }
                else {
                    $('.multisiteclass option[value="0"]').attr('selected', 'selected');
                }
            });
        });

        // select site according to location for multi acc
        $(document).find('#new_owner_id').change(function() {


            $('#updated_site_id').empty();
            $('#updated_location_id').empty();
            var owner_id = this.value;
            $.getJSON("<?php echo base_url('items/getlocationbyowner'); ?>" + '/' + owner_id, function(data) {

                if (data.results.length != 0) {
                    $('#new_location_id option[value="' + data.results[0].location_id + '"]').attr('selected', 'selected');
                    $('#updated_location_id').attr('value', +data.results[0].location_id);
                    $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + data.results[0].location_id, function(site_data) {
                        if (data.results.length != 0)
                        {
                            $('.multi_site_class option[value="' + site_data.results[0].site_id + '"]').attr('selected', 'selected');
                            $('#updated_site_id').attr('value', +site_data.results[0].site_id);
                        }
                        else {
                            $('.multi_site_class option[value="0"]').attr('selected', 'selected');
                        }
                    });
                }
                else {
                    $('#new_location_id option[value="0"]').attr('selected', 'selected');
                }
            });
        });
        $(document).find('.delete').click(function() {


            var delete_id = $(this).attr('delete_id');
            var delete_item_id = $('.delete').attr('delete_item_id');
            var base_url_str = $("#base_url").val();
            $.ajax({
                type: "POST",
                url: base_url_str + "items/delete_photo/",
                data: {
                    'delete_id': delete_id,
                    'item_id': delete_item_id
                },
                success: function(msg) {
                    location.reload();
                }

            });
        });
    });
    function showTasks(jsonData, result)
    {
        $('#complianceTaskModal').find('tbody').html('');
        if (!$.isEmptyObject(jsonData)) {
            $.each(jsonData, function(k, v) {
                console.log(v['task_name']);
                console.log(v['result']);
                if ($.isNumeric(v['result'])) {
                    if (v['result'] == 1)
                        result = 'PASS';
                    else
                        result = 'FAIL';
                } else {
                    var result = v['result'];
                }

                $('#complianceTaskModal').find('tbody').append('<tr><td>' + v['task_name'] + '</td><td class="tResult">' + result + '</td><td class="tNotes">' + v['test_notes'] + '</td></tr>');
            });
            if (result == 1)
            {
                $('#complianceTaskModal').find('tbody tr td.tResult').html('Missed');
                $('#complianceTaskModal').find('tbody tr td.tNotes').html('');
            }
        }
        else {
            $('#complianceTaskModal').find('tbody').append('<tr><td colspan="3"><span>No Tasks.</span></td></tr>');
        }

        $('#complianceTaskModal').modal('show');
    }

</script>