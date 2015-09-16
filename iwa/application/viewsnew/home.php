<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script>
    $(document).ready(function() {
//        if ($('#similar_data').val() != '')
//        {
//            setTimeout(function() {
//                $('#addsimilaritem').trigger('click');
//            }, 2000);
//        }

        $('#additem').on('click', function() {
            $('#status_id option[value=1]').attr('selected', 'selected');
            $('#item_condition option[value=1]').attr('selected', 'selected');
            $('#item_patteststatus option[value=5]').attr('selected', 'selected');
            $("#item_purchased").val('');
            $("#add_warranty_date").val('');
            $("#item_replace").val('');
        });
        $('#item_purchased').on('change', function() {

            d = $("#item_purchased").datepicker("getDate");
            $("#add_warranty_date").datepicker("setDate", new Date(d.getFullYear() + 1, d.getMonth(), d.getDate()));
            $("#item_replace").datepicker("setDate", new Date(d.getFullYear() + 3, d.getMonth(), d.getDate()));
        });
        var patstatus = $('#item_patteststatus option:selected').val();
        if (patstatus == '-1' || patstatus == '5')
        {
            $('#patTestDate').css('display', 'none');
        }
        else
        {
            $('#patTestDate').css('display', 'block');
        }
// Pat Test result date
        $('#item_patteststatus').on('change', function()
        {
            var patstatus = $('#item_patteststatus option:selected').val();
            if (patstatus == '-1' || patstatus == '5')
            {
                $('#patTestDate').css('display', 'none');
            }
            else
            {
                $('#patTestDate').css('display', 'block');
            }
        });

        $('#category_id').change(function() {
            /* Quantity category check */
            $.getJSON("/youaudit/iwa/categories/checkCategory/" + $('#category_id').val(), function(data) {
                if (data.quantity == 1) {
                    $("#quan").empty();
                    str = ('<div class="col-md-6">' +
                            '<label>' + "Quality" + '</label>' +
                            '</div>' +
                            '<div class="col-md-6">' + '<input type="input" placeholder="Enter Quantity" class="form-control" id="item_quantity" name="item_quantity"' +
                            '</div>');
                    $("#quan").append(str);
                }
                else
                {
                    $("#quan").empty();
                }
            });
            /* Custom Fields call */
            $.getJSON("/youaudit/iwa/categories/getCustomFields/" + $('#category_id').val(), function(data) {
                $('#custom_fields').empty();
                for (var i = 0; i < data.length; i++) {
                    if (data[i].field_value == 'text_type')
                    {
                        var txt = '<div class="row"><div class="col-md-6">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                '<input type="text" class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div>';
                    }
                    if (data[i].field_value == 'value_type')
                    {
                        var txt = '<div class="row"><div class="col-md-6">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                '<div class="input-group col-md-12"><div class="input-group-addon grpaddon">$</div><input type="text" class="form-control custom_val" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div></div>';
                    }
                    if (data[i].field_value == 'date_type')
                    {
                        var txt = '<div class="row"><div class="col-md-6">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
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
                        var txt = '<div class="row"><div class="col-md-6">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                '<select class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' + option +
                                '</select>' +
                                '</div></div>';
                    }
                    $('#custom_fields').append(txt);
                }
                $(".dateval").datepicker({dateFormat: "dd/mm/yy"});
            });
        });

        $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
        // Validation On Add Item 
        $("#add_item_form").validate({
            rules: {
                category_id: {required: true, min: 1},
                item_barcode: "required",
                owner_id: {required: true, min: 1},
                site_id: {required: true, min: 1},
                location_id: {required: true, min: 1}
            },
            messages: {
                category_id: "Please Select Category",
                item_barcode: "Please Enter QR_code",
                owner_id: "Please Select Owner",
                site_id: "Please Select Site",
                location_id: "Please Select Location"
            }
        });

        $("#change_owner_form").validate({
            rules: {
                item_id: {required: true, min: 1},
            },
            messages: {
                item_id: "Please Select Item",
            }
        });
        $("#fix_item_form").validate({
            rules: {
                fix_item_id: {required: true, min: 1},
            },
            messages: {
                fix_item_id: "Please Select Item",
            }
        });

        // Validation On Add Similar Item 
        $("#add_similaritem_form").validate({
            rules: {
                item_id_similar: {required: true, min: 1},
                item_barcode_similar: "required",
                owner_id_similar: {required: true, min: 1},
                site_id_similar: {required: true, min: 1},
                location_id_similar: {required: true, min: 1}
            },
            messages: {
                item_id_similar: "Please Enter QR_code",
                item_barcode_similar: "Please Enter QR_code",
                owner_id_similar: "Please Select Owner",
                site_id_similar: "Please Select Site",
                location_id_similar: "Please Select Location"
            }
        });
        // code for condition_history

        $("#condition_check_form").validate({
            rules: {
                asset_id: {required: true, min: 1},
                new_condition: {required: true, min: 1},
                job_notes: "required"
            },
            messages: {
                asset_id: "Please Select New Condition",
                new_condition: "Please Select New Condition",
                job_notes: "Please Enter Job Note"
            }
        });

        // estblish link and site link

        $(document).find('.multi_site_class').change(function() {
            $(".multi_location_class").empty();
            var site_id = this.value;
            if (site_id != 0) {
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
            $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + site_id, function(data) {

                if (data.results.length != 0) {
                    $('.multi_site_class option[value="' + data.results[0].site_id + '"]').attr('selected', 'selected');
                }
                else {
                    $('.multi_site_class option[value="0"]').attr('selected', 'selected');
                }
            });
        });

        // Code To Check Unique Barcode

        $("#item_barcode").on("keyup blur", function() {

            var code = $("#asset_qrcode").val();
            var bar_code = $("#item_barcode").val();
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
                        $("#save_button").addClass('disabled');
                        $("#qrcode_error").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#qrcode_error").addClass("hide");
                    }
                }

            });
        });

        $('.backpage').on('click', function()
        {
            $('#search_box').css('display', 'none');
            $('#app_icons').css('display', 'block');
        });
        $('.back_page').on('click', function()
        {
            $('#search_results').css('display', 'none');
            $('#search_box').css('display', 'block');
        });
        $('#audit_location').on('click', function()
        {
            $('.barcode').css('display', 'none');
            $('.categorys').css('display', 'none');
            $('.itemmanus').css('display', 'none');
            $('.manufacturers').css('display', 'none');
            $('#app_icons').css('display', 'none');
            $('#search_box').css('display', 'block');
        });
        
        $('#change_owner').on('click', function()
        {
            $('.barcode').css('display', 'none');
            $('.categorys').css('display', 'none');
            $('.itemmanus').css('display', 'none');
            $('.manufacturers').css('display', 'none');
            $('#app_icons').css('display', 'none');
            $('#search_box').css('display', 'block');
        });

        $('.search_item').on('click', function()
        {
            var barcode = $('#search_barcode').val();
            var category_id = $('#search_category option:selected').val();
            var item_id = $('#search_manu option:selected').val();
            var manufacturer_id = $('#search_manufacturer option:selected').val();
            var site_id = $('#search_site option:selected').val();
            var location_id = $('#search_location option:selected').val();

            $.ajax({
                type: "POST",
                url: base_url_str + "welcome/get_searchResults",
                data: {
                    'bar_code': barcode,
                    'category_id': category_id,
                    'manu_id': item_id,
                    'manufacturer_id': manufacturer_id,
                    'site_id': site_id,
                    'location_id': location_id
                },
                success: function(data) {
                    $('#search_box').css('display', 'none');
                    $('#search_results').css('display', 'block');
                    var searchdata = $.parseJSON(data);
                    if (searchdata.length > 0) {
                        for (var k = 0; k < searchdata.length; k++)
                        {
                            if (searchdata[k].item_manu != null)
                            {
                                var manu = searchdata[k].item_manu;
                            }
                            else
                            {
                                var manu = '';
                            }
                            if (searchdata[k].manufacturer != null)
                            {
                                var manufacturer = searchdata[k].manufacturer;
                            }
                            else
                            {
                                var manufacturer = '';
                            }
                            if (searchdata[k].model != null)
                            {
                                var model = searchdata[k].model;
                            }
                            else
                            {
                                var model = '';
                            }

                            $('#search_results #resdata').append('<div class="list-group-item"><a href="' + base_url_str + 'items/view/' + searchdata[k].itemid + '">' + searchdata[k].barcode + ':' + manu + ' ' + manufacturer + ' ' + model + ' ' + searchdata[k].locationname + '</div>');
                        }
                    }
                    else
                    {
                        $('#search_results #resdata').append('<div class="list-group-item">No Result Found</div>');
                    }
                }
            });
        });

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

        // Add similar asset data
        $('body').on('click', '#addsimilaritem', function() {
            var base_url_str = $("#base_url").val();
            $('#item_id_similar').on('change', function()
            {
                var item_id = this.value;
                $.ajax({
                    type: "POST",
                    url: base_url_str + "items/getassetdata/" + item_id,
                    success: function(data) {
                        var assetdata = $.parseJSON(data);
                        var pre = $("#asset_qrcode").val();
                        var bar_code = pre + assetdata[0].barcode;
                        if (assetdata[0].item_manu_name != null) {
                            var item_manu = assetdata[0].item_manu_name + '/';
                        }
                        else
                        {
                            var item_manu = '';
                        }
                        var org = bar_code + '/' + assetdata[0].categoryname + '/' + item_manu + assetdata[0].manufacturer;
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
        });

        // select site accroding to location for multi acc
        $(document).find('#new_owner_id').change(function() {

//            $('.multi_location_class').empty();
//            $('.multi_site_class').empty();
//            $('#updated_site_id').empty();
//            $('#updated_location_id').empty();
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
        $("#report_fault_form").validate({
            rules: {
                report_item_id: "required",
                job_notes: "required",
            }
        });

        // Report Fault Data

        var base_url_str = $("#base_url").val();
        $('body').on('change', '#report_item_id', function() {

            var item_id = this.value;
            $.ajax({
                type: "POST",
                url: base_url_str + "items/getassetdata/" + item_id,
                success: function(data) {
                    var assetdata = $.parseJSON(data);
                    var pre = $("#asset_qrcode").val();
                    var bar_code = pre + assetdata[0].barcode;

                    $('#report_item').val(assetdata[0].item_manu_name);
                    $('#report_manufacturer').val(assetdata[0].item_manufacturer);
                    $('#report_serialno').val(bar_code);
                    $('#report_category').val(assetdata[0].categoryname);
                    $('#report_location').val(assetdata[0].locationname);
                }
            });
        });

        $('#check_condition').on('click', function()
        {
            $('#app_icons').css('display', 'none');
            $('#search_box').css('display', 'block');
            $('.barcode').css('display','block');
            $('.categorys').css('display','block');
            $('.itemmanus').css('display','block');
            $('.manufacturers').css('display','block');
        });

        $('#ownership').on('click', function()
        {
            $('#app_icons').css('display', 'none');
            $('#search_box').css('display', 'block');
        });

        $('#report_fault').on('click', function()
        {
            $('#app_icons').css('display', 'none');
            $('#search_box').css('display', 'block');
        });

        $('#addsimilaritem').on('click', function()
        {
            $('#app_icons').css('display', 'none');
            $('#search_box').css('display', 'block');
        });

        $('#addsimilaritem').on('click', function()
        {
            $('#app_icons').css('display', 'none');
            $('#search_box').css('display', 'block');
        });

        var site_server = '<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>';
        var base_url = $("#base_url").val();
        $('body').on('change', '#fix_item_id', function() {

            var iId = this.value;
            var account_id = <?php echo $this->session->userdata('objSystemUser')->accountid; ?>;
//            var ticket_id = $(this).attr('ticket_id');
            var type = '';
            $("#fix_item_id").attr("value", iId);
//            $("#fix_ticket_id").attr("value", ticket_id);
            //alert(ticket_id);
            console.log('I am click fix item');
            $.ajax({
                type: "POST",
                url: site_server + "/youaudit/iwa/faults/ajaxfetchItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id + "&type=" + 'Open Job',
                success: function(data) {
                    if (data != null) {
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
                    } // end of if data exists
                    else
                    {
                        $.ajax({
                            type: "POST",
                            url: site_server + "/youaudit/iwa/faults/ajaxfetchItem",
                            dataType: 'json',
                            data: "&id=" + iId + "&account_id=" + account_id + "&type=" + 'Fix',
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
                    }
                } // End of success
            }); // End of ajax
        });

    });
</script>
<!--<input type="hidden" id="similar_data" value="<?php echo $this->session->flashdata('item'); ?>">-->

<input type="hidden" id="asset_qrcode" value="<?php echo $this->session->userdata('objSystemUser')->qrcode; ?>">
<div id="page-wrapper" style="min-height: 573px;">

    <?php // var_dump($arrSessionData['objSystemUser']->levelid);die;   ?>
    <div style="margin-bottom: 20px;margin-top: 15px;" class="row">
        <!-- WEB APP -->
        <div class="col-lg-3 pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">WEB APP</div> 
                <div class="panel-body" id="app_icons">
                    <div class="col-md-12">
                        <div class="col-md-6"><a id="audit_location" class="button icon-with-text round"><i class="fa fa-fw">&#xf044;</i><b>Audit Location</b></a> </div>
                        <div class="col-md-6"><a id="change_owner" class="button icon-with-text round"><i class="fa fa-thumbs-o-up"></i><b>Condition By Location</b></a></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6"><a type="button" id="check_condition" class="button icon-with-text round">  <i class="fa fa-thumbs-o-up"></i><b>Condition Check</b></a></div>
                        <div class="col-md-6"><a type="button" id="ownership" class="button icon-with-text round"><i class="fa fa-users"></i><b>Ownership</b></a></div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6"><a id="report_fault" class="button icon-with-text round"><i class="fa fa-fw">&#xf071;</i><b>Report Fault</b></a></div>
                        <div class="col-md-6"><a id="addsimilaritem" class="add_similar button icon-with-text round" data_item_id="<?php echo $arrItem->itemid; ?>"><i class="fa  fa-plus-circle"></i><b>Add similar Item</b></a></div></div>
                    <div class="col-md-12"><div class="col-md-6"><a href="#" data-toggle="modal" data-target="#add_item" class="button icon-with-text round" id="additem"><i class="fa  fa-plus-circle"></i><b>Add New</b></a></div></div>                    
                </div>
                <div class="panel-body" id="search_box">
                    <div class="list-group">
                        <div class="list-group-item barcode">
                            Qr Code
                            <span class="pull-right text-muted small"><input name="search_qrcode" class="opt" id="search_barcode">  
                            </span>
                        </div>
                        <div class="list-group-item categorys">
                            Category
                            <span class="pull-right text-muted small">
                                <select name="search_category" class="opt" id="search_category">
                                    <option value="">Select Category</option>
                                    <?php
                                    foreach ($arrCategories['results'] as $arrCategory) {
                                        echo "<option ";
                                        echo 'value="' . $arrCategory->categoryid . '" ';
                                        if ($intCategoryId == $arrCategory->categoryid) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $arrCategory->categoryname . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                            </span>
                        </div>
                        <div class="list-group-item itemmanus">
                            Item
                            <span class="pull-right text-muted small">
                                <select name="search_item" class="opt" id="search_manu">
                                    <option value="">Select Item</option>
                                    <?php foreach ($arrItemManu['list'] as $item) { ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['item_manu_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </span>
                        </div>
                        <div class="list-group-item manufacturers">
                            Manufacturer
                            <span class="pull-right text-muted small">
                                <select name="search_manufacturer" class="opt" id="search_manufacturer">
                                    <option value="">Select Manufacturer</option>
                                    <?php foreach ($arrManufaturer as $manufacturer) { ?>
                                        <option value="<?php echo $manufacturer['manufacturer_name']; ?>"><?php echo $manufacturer['manufacturer_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </span>
                        </div>
                        <div class="list-group-item site">
                            Site
                            <span class="pull-right text-muted small">
                                <select name="search_site" class="opt" id="search_site">
                                    <option value="">Select Site</option>
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
                            </span>  
                        </div>
                        <div class="list-group-item location">
                            Location
                            <span class="pull-right text-muted small">
                                <select name="search_location" class="opt" id="search_location">
                                    <option value="">Select Location</option>
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
                            </span>
                        </div>
                        <div class="list-group-item" style="height: 40px;">
                            <span class="pull-left text-muted small"><a class="btn backpage"><i class="fa fa-backward"></i>Back</a></span>
                            <span class="pull-right text-muted small"><a class="btn search_item"><i class="fa fa-search"></i>Search</a>
                            </span>
                        </div>

                    </div>
                </div>

                <div class="panel-body" id="search_results">
                    <div class="list-group" style="margin-bottom: 0px;">
                        <div class="list-group-item searchhead" style="height: 40px;">Search Results</div>
                    </div>
                    <div class="list-group" id="resdata">

                    </div>
                    <div class="list-group">
                        <div class="list-group-item" style="height: 40px;">
                            <span class="pull-left text-muted small"><a class="btn back_page"><i class="fa fa-backward"></i>Back</a></span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="panel panel-default">
                <div class="panel-heading">SHORTCUTS</div> 
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="col-md-6"><a class="button icon-with-text round" href="<?php echo base_url('items/confirmdeleted'); ?>">
                                <i class="fa  fa-trash-o"></i>
                                <b>Confirm Deletions</b></a></div>
                        <div class="col-md-6"><a id="item_edit" href="<?php echo base_url('compliance'); ?>" class="button icon-with-text round"><i class="fa fa-fw">&#xf044;</i><b>Safety Check</b></a></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6"><a href="<?php echo base_url('faults'); ?>" title="Fix item" class="button icon-with-text round"><i class="fa fa-gear"></i><b>Fix Item</b></a></div>
                        <div class="col-md-6"><a href="<?php echo base_url('faults'); ?>" id="item_add" class="button icon-with-text round"><i class="fa fa-fw">&#xf071;</i><b>Current Faults</b></a></div>
                    </div>                    
                </div>
            </div>

        </div>
        <div class="col-lg-9 pull-right">
            <!-- Account Summary -->
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Account Summary</div> 
                    <div class="panel-body">
                        <div class="list-group">
                            <div class="list-group-item">
                                Account Package
                                <span class="pull-right text-muted small"><em><?php echo $arrAccountDetails['result'][0]->accountpackagename; ?></em>
                                </span>
                            </div>
                            <div class="list-group-item">
                                Total Asset
                                <span class="pull-right text-muted small"><em><?php echo $arrTotalItemsOnAccount[0]->total_items; ?></em>
                                </span>
                            </div>
                            <div class="list-group-item">
                                Asset Remaining
                                <span class="pull-right text-muted small"><em><?= $intItemsRemainingOnAccount; ?></em>
                                </span>
                            </div>
                            <div class="list-group-item">
                                Total Users
                                <span class="pull-right text-muted small"><em><?php echo $arrTotalUsersOnAccount[0]->total_users; ?></em>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- My Profile -->
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">My Profile</div> 
                    <div class="panel-body">
                        <div class="list-group">
                            <div class="list-group-item">
                                Welcome <?php echo $arrSessionData['objSystemUser']->nickname; ?>

                            </div>
                            <div class="list-group-item">
                                User Type
                                <span class="pull-right text-muted small"><em><?= $arruserbasiccredential['result'][0]->levelname; ?></em>
                                </span>
                            </div>

                            <div class="list-group-item">

                                <span class=" text-muted small"> <a href="<?= base_url('users/editMe') ?>">
                                        <i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;<b>Edit Profile</b></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Latest News -->
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Latest News : 
                    </div>
                    <div class="panel-body">
                        <p><?php
                            if (isset($latest_news[0])) {
                                $this->load->helper('text');
                                echo $latest_news[0]['news_text'];
//                        echo word_wrap($latest_news['news_text'], 50);
                            }
                            ?></p>
                    </div>
                    <div class="panel-footer">
                        <?php
                        if (isset($latest_news)) {

                            $this->load->helper('date');
                            $current_time = gmt_to_local($latest_news[0]['create_date']);

                            $datestring = "%d-%m-%Y %h:%i:%s %a";
                            echo mdate($datestring, $latest_news[0]['create_date']);
                        }
                        ?>

                    </div>
                </div>
            </div>



            <!--<div style="margin-bottom: 20px;margin-top: 15px;" class="row">-->
            <!-- SHORTCUTS -->
            <!--        <div class="col-lg-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">SHORTCUTS</div> 
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="col-md-6"><a class="button icon-with-text round" href="http://dev-iis.com/youaudit/iwa/items/confirmdeleted">
                                            <i class="fa  fa-trash-o"></i>
                                            <b>Confirm Deletions</b></a></div>
                                    <div class="col-md-6"><a id="item_edit" class="button icon-with-text round"><i class="fa fa-fw">&#xf044;</i><b>Safety Check</b></a></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6"><a data-toggle="modal" href="#fix_item" title="Fix item"  class="button icon-with-text round"><i class="fa fa-fw">&#xf0ad;</i><b>Fix Item</b></a></div>
                                    <div class="col-md-6"><a data-toggle="modal" data-target="#report_fault" id="item_add"  class="button icon-with-text round"><i class="fa fa-fw">&#xf071;</i><b>Current Faults</b></a></div>
                                </div>                    
                            </div>
                        </div>
            
                    </div>-->
            <!-- Last Assets Added -->
            <div style="width:100%; clear: both">
                <div class="col-lg-4">
                    <h3 class="utitle">Last Assets Added</h3>

                    <div class="table-responsive multiadd">
                        <table class="table tb table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($arrNewestItems && (count($arrNewestItems) > 0)) {
                                    foreach ($arrNewestItems as $objItem) {
                                        $strUrl = '/items/view/' . $objItem->id;
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo base_url($strUrl); ?>"><?php echo $objItem->barcode; ?></a></td>
                                            <td> <?php echo $objItem->item_manu; ?></td>
                                            <td> <?php echo $objItem->categoryname; ?></td>
                                            <td> <?php echo $objItem->locationname; ?></td>
                                            <td> <?php echo 'Remaining adding'; ?></td>

                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>  

                </div>
                <!-- Common Items -->
                <div class="col-lg-4">
                    <h3 class="utitle">Common Items</h3>
                    <?php
                    if ($arrCommonItems && (count($arrCommonItems) > 0)) {
                        ?>
                        <div class="table-responsive multiadd">
                            <table class="table tb table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Item</th>
                                        <th>Manufacturer</th>
                                        <th>Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($arrCommonItems as $objItem) {
                                        $strUrl = '/items/filter/fr_manufacturer_exact/' . $objItem->manufacturer;
                                        $strUrl .='/fr_model_exact/' . $objItem->model;
                                        ?>
                                        <tr>

                                            <td><?php echo $objItem->category_name; ?></td>
                                            <td><?php echo $objItem->item_manu; ?></td>
                                            <td><?php echo $objItem->manufacturer; ?></td>
                                            <td><?php echo $objItem->count; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>  
                        <?php
                    }
                    ?>
                </div>
                <!-- Recently Archived Assets -->
                <div class="col-lg-4">
                    <h3 class="utitle">Recently Archived Assets</h3>

                    <div class="table-responsive multiadd">
                        <table class="table tb table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <?php
                            if ($arrSessionData['objSystemUser']->levelid >= 2) {
                                if (!empty($arrRecentlyDeletedItems)) {
                                    ?>
                                    <tbody>
                                        <?php
                                        foreach ($arrRecentlyDeletedItems as $objItem) {
                                            $strUrl = '/items/view/' . $objItem->id;
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo base_url($strUrl);
                                            ?>"><?php echo $objItem->barcode; ?></a></td>

                                                <td><?php echo $objItem->item_manu; ?></td>
                                                <td><?php echo $objItem->category_name; ?></td>
                                                <td><?php echo $objItem->location_name; ?></td>
                                                <td><?php echo $objItem->item_manu; ?></td>
                                                <td><?php echo $objItem->status_name; ?></td>
                                                <td><?php echo $objItem->status_name; ?></td>
                                                <td><?php
                                                    if ($arrSessionData['objSystemUser']->levelid == 4) {
                                                        echo date('d/m/Y', strtotime($objItem->mark_deleted_2_date));
                                                    } else {
                                                        echo date('d/m/Y', strtotime($objItem->mark_deleted_date));
                                                    }
                                                    ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                    <?php
                                }
                            }
                            ?>
                        </table>
                    </div>  

                </div>
            </div>
            <!--</div>-->

            <!--    <div class="row">
                    <div class="col-lg-3">
                    </div>
                </div>-->
            <!--<div class="row">-->
            <!--        <div class="col-lg-3">
                    </div>-->
            <!-- Compliance Checks -->
            <div style="width:100%; clear: both; display:inline-block;margin: 20px 0;">
                <div class="col-lg-4">
                    <h3 class="utitle">Current Faults</h3>

                    <div class="table-responsive multiadd">
                        <table class="table tb table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($currentFaults['results']) > 0) {
                                    $current_faults = array_slice($currentFaults['results'], 0, 5);
                                    foreach ($current_faults as $fault) {
                                        $strUrl = '/items/view/' . $fault->itemid;
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo base_url($strUrl); ?>"><?php echo $fault->barcode; ?></a></td>
                                            <td><?php echo $fault->item_manu; ?></td>
                                            <td><?php echo $fault->categoryname; ?></td>
                                            <td><?php echo $fault->locationname; ?></td>
                                            <td><?php echo $fault->ownername; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>  

                </div>
                <!-- Missing Assets -->
                <div class="col-lg-4">
                    <h3 class="utitle">Missing Assets</h3>

                    <div class="table-responsive multiadd">
                        <table class="table tb table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($arrAllMissingItem As $missingItem) {
                                    $strUrl = '/items/view/' . $missingItem['itemid'];
                                    ?>
                                    <tr>
                                        <td><a href="<?php echo base_url($strUrl); ?>"><?php echo $missingItem['barcode']; ?></a></td>
                                        <td><?php echo $missingItem['item_manu']; ?></td>
                                        <td><?php echo $missingItem['categoryname']; ?></td>
                                        <td><?php echo $missingItem['locationname']; ?></td>
                                        <td><?php echo $missingItem['firstname'] . " " . $missingItem['lastname']; ?></td>

                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>  

                </div>
                <!-- Missing Assets -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Report Favourites</div> 
                        <div class="panel-body">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <a href="#">Depreciation Schedule</a>
                                    <span class="pull-right text-muted small"><a href="#" class="reportbtn"><i class="fa  fa-file-pdf-o"></i></a><a href="#"  class="reportbtn"><i class="fa fa-file-excel-o"></i></a>                
                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <a href="#">Asset Register</a>
                                    <span class="pull-right text-muted small"><a href="#" class="reportbtn"><i class="fa  fa-file-pdf-o"></i></a><a href="#" class="reportbtn"><i class="fa fa-file-excel-o"></i></a>

                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <a href="#">Assets Added</a>
                                    <span class="pull-right text-muted small"><a href="#" class="reportbtn"><i class="fa  fa-file-pdf-o"></i></a><a href="#" class="reportbtn"><i class="fa fa-file-excel-o"></i></a>
                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <a href="#">Current Faults</a>
                                    <span class="pull-right text-muted small"><a href="#" class="reportbtn"><i class="fa  fa-file-pdf-o"></i></a><a href="#" class="reportbtn"><i class="fa fa-file-excel-o"></i></a>
                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <a href="#">Checks Missed</a>
                                    <span class="pull-right text-muted small"><a href="#" class="reportbtn"><i class="fa  fa-file-pdf-o"></i></a><a href="#" class="reportbtn"><i class="fa fa-file-excel-o"></i></a>
                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <a href="#">Missing Assets & Equipment</a>
                                    <span class="pull-right text-muted small"><a href="#" class="reportbtn"><i class="fa  fa-file-pdf-o"></i></a><a href="#" class="reportbtn"><i class="fa fa-file-excel-o"></i></a>
                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <a href="#">Checks Failed</a>
                                    <span class="pull-right text-muted small"><a href="#" class="reportbtn"><i class="fa  fa-file-pdf-o"></i></a><a href="#" class="reportbtn"><i class="fa fa-file-excel-o"></i></a>
                                    </span>
                                </div>
                                <div class="list-group-item small">
                                    Click On Report Link to go to Report Preview<br>
                                    Click On PDF or CSV to download Report as is
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--</div>-->
        </div>
        <!--  Modal For Add Items  -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_item" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                        <h4 id="myModalLabel" class="modal-title">Add Item</h4>
                    </div>

                    <form action="<?php echo base_url() . 'items/addmultiple' ?>" method="POST" id="add_item_form"  enctype="multipart/form-data">
                        <div class="modal-body">
                            <!-- Item Details -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label><h4>Item Details</h4></label> </div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Category*</label> </div>
                                <div class="col-md-6">  <select name="category_id" id="category_id" class="form-control">
                                        <option value="0">Select</option>
                                        <?php
                                        foreach ($arrCategories['results'] as $arrCategory) {
                                            echo "<option ";
                                            echo 'value="' . $arrCategory->categoryid . '" ';
                                            if ($intCategoryId == $arrCategory->categoryid) {
                                                echo 'selected="selected" ';
                                            }
                                            echo '>' . $arrCategory->categoryname . "</option>\r\n";
                                        }
                                        ?>
                                    </select>
                                </div></div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6">         <label>Item*</label> </div> 
                                <div class="col-md-6">  <select name="manu" id="manu" class="form-control">
                                        <option value="0">Select</option>
                                        <?php foreach ($arrItemManu['list'] as $item) { ?>
                                            <option value="<?php echo $item['id']; ?>"><?php echo $item['item_manu_name']; ?></option>
                                        <?php } ?>
                                    </select></div>

                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>OR Type New/Item</label> </div>
                                <div class="col-md-6"><input placeholder="Enter new item" class="form-control" name="new_item" id="new_item"></div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6">        <label>Manufacturer*</label> </div>

                                <div class="col-md-6"> <select name="manufacturer" id="manufacturer" class="form-control">
                                        <option value="0">Select</option>
                                        <?php foreach ($arrManufaturer as $manufacturer) { ?>
                                            <option value="<?php echo $manufacturer['manufacturer_name']; ?>"><?php echo $manufacturer['manufacturer_name']; ?></option>
                                        <?php } ?>

                                    </select> </div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>OR Type New Manufacturer</label> </div>
                                <div class="col-md-6"><input placeholder="Enter manufacturer" class="form-control" name="item_make" id="item_make"></div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6">            <label>Model</label></div>
                                <div class="col-md-6">
                                    <input placeholder="Enter Model" name="item_model" id="item_model" class="form-control">
                                </div>
                            </div> 

                            <div class="form-group col-md-12"><div class="col-md-6"> 
                                    <label> Quantity</label>
                                </div>
                                <div class="col-md-6"><input type="input" placeholder="Enter Quantity" class="form-control" id="item_quantity" name="item_quantity" value="1">
                                </div>
                            </div>
                            <!-- Item ID -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label><h4>Item ID</h4></label> </div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Enter QR Code*</label> </div>
                                <div class="col-md-6">  
                                    <div class="input-group">
                                        <div class="input-group-addon grpaddon">
                                            <?php echo $this->session->userdata('objSystemUser')->qrcode; ?></div>
                                        <input placeholder="Enter QR Code" class="form-control barcss" name="item_barcode" id="item_barcode">            
                                    </div>
                                    <div id="qrcode_error" class="qrcode_error hide">QR Code Already Exist.</div>
                                </div>
                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Enter Serial No</label> </div>
                                <div class="col-md-6">  <input placeholder="Enter Serial No" class="form-control" name="item_serial_number" id="item_serial_number">
                                </div>
                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                            </div>
                            <!-- Quality -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label><h4>Quality</h4></label></div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Status</label> </div>
                                <div class="col-md-6">  <select name="status_id" id="status_id" class="form-control">
                                        <?php
                                        foreach ($arrItemStatuses['results'] as $arrStatus) {
                                            echo "<option ";
                                            echo 'value="' . $arrStatus->statusid . '" ';
                                            echo '>' . $arrStatus->statusname . "</option>\r\n";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6">          <label>Condition</label>
                                </div>
                                <div class="col-md-6"><select name="item_condition" id="item_condition" class="form-control">
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
                            </div>
                            <!-- Ownership -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label><h4>Ownership</h4></label></div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6">      <label>Owner*</label>
                                </div>
                                <div class="col-md-6"> 
                                    <select name="owner_id" id="owner_id" class="form-control">
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
                                <div class="col-md-6"> <label>Location*</label>
                                </div>
                                <div class="col-md-6">       
                                    <select name="location_id" id="location_id" class="form-control multi_location_class">
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
                                <div class="col-md-6"> <label>Site*</label>
                                </div>
                                <div class="col-md-6">       
                                    <select name="site_id" id="site_id" class="form-control multi_site_class">
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
                                <div class="col-md-6"><label>Supplier</label>
                                </div>
                                <div class="col-md-6"><select name="supplier" id="supplier" class="form-control">
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
                                <div class="col-md-6">
                                    <label><h4>Item Dates</h4></label>
                                </div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label>Purchase Date</label>
                                </div>
                                <div class="col-md-6">
                                    <input placeholder="Enter Purchase Date" class="form-control datepicker" name="item_purchased" id="item_purchased" type="text">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label>Warranty Expiry</label>
                                </div>
                                <div class="col-md-6">
                                    <input placeholder="Enter Expiry Date" class="form-control datepicker" name="add_warranty_date" id="add_warranty_date" type="text">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label>Replacement Due</label>
                                </div>
                                <div class="col-md-6">
                                    <input placeholder="Enter Replacement Date" class="form-control datepicker" name="item_replace" id="item_replace" type="text">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label><h4>Valuation</h4></label>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Purchase Price</label>
                                </div>

                                <div class="col-md-6">       
                                    <input placeholder="Enter Purchase Price" class="form-control" name="item_value" id="item_value" type="text">
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6"> <label> Current Value </label>
                                </div>
                                <div class="col-md-6">       
                                    <input placeholder="Enter Current Value" class="form-control" name="item_current_value" id="item_current_value" type="text">

                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <label><h4>Custom Fields</h4></label>
                                </div>
                            </div>
                            <div class="form-group col-md-12" id="custom_fields">

                            </div> 


                            <div class="form-group col-md-12">
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Notes</label>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-12"><textarea placeholder="Enter Notes" class="form-control" name="item_notes" id="item_notes" cols="10" rows="2"></textarea>  
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6"> <label>Documents</label>
                                </div>
                                <div class="col-md-6">       
                                    Choose File <input class="fileupload" name="pdf_file" id="pdf_file" type="file" value="UPLOAD">  
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6"> <label>Photo</label>
                                </div>
                                <div class="col-md-6 input_fields_wrap"> </br>
                                    <button class="btn btn-primary btn-circle btn-xs add_field_button" title="add more image" type="button"><i class="glyphicon glyphicon-plus"></i></button>    
                                    <div><input class="fileupload upload form-contorl" type="file" name="photo_file_1" size="20"></div>

                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6"> <label>PAT Test Result</label>
                                </div>
                                <div class="col-md-6">    
                                    <select name="item_patteststatus" id="item_patteststatus" class="form-control">
                                        <option value="-1">Unknown</option>
                                        <option value="1">Pass</option>
                                        <option value="0">Fail</option>
                                        <option value="5">Not Required</option>
                                    </select>                </div>
                            </div>
                            <div class="form-group col-md-12" id="patTestDate">
                                <div class="col-md-6"> <label>PAT Test Date</label>
                                </div>
                                <div class="col-md-6">       
                                    <input placeholder="Enter PAT Test Date" class="form-control datepicker" name="item_pattestdate" id="item_pattestdate" type="text">  
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                            </div>


                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <a href="#" id="addmore">
                                        Add Another Similar Item?</a> 
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" id="add_another" name="add_another">
                                </div>
                            </div>
                            <!--                    <div class="form-group col-md-12">
                                                    <div class="col-md-6">
                                                        <a href="#" id="addmore">
                                                            Add Item Same Ownership?</a> 
                                                    </div>
                                                    <div class="col-md-6"></div>
                                                </div>-->

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

        <!-- Model For Add Similar Item -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_similar_item" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                        <h4 id="myModalLabel" class="modal-title">Add Similar Item</h4>
                        <div class="get_original"></div>
                    </div>

                    <form action="<?php echo base_url() . 'items/addSimilarItem/' ?>" method="POST" id="add_similaritem_form"  enctype="multipart/form-data">
                        <div class="modal-body">

<!--                <form action="<?php echo base_url() . 'items/addSimilarItem/' ?>" method="POST" id="add_similaritem_form"  enctype="multipart/form-data">-->

                            <div class="form-group col-md-12">
                                <div class="col-md-5"><label><h4>Select QR Code</h4></label> </div>
                                <div class="col-md-7"> 
                                    <select name="item_id_similar" id="item_id_similar" class="form-control">
                                        <option value="0">Select</option>
                                        <?php foreach ($assetlist as $asset) { ?>
                                            <option value="<?php echo $asset->itemid; ?>"><?php echo $asset->barcode; ?></option>
                                        <?php } ?>
                                    </select></div>

                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-5">  <label>Enter QR Code*</label> </div>
                                <div class="col-md-7">  
                                    <div class="input-group">
                                        <div class="input-group-addon grpaddon">
                                            <?php echo $this->session->userdata('objSystemUser')->qrcode; ?></div>
                                        <input placeholder="Enter QR Code" class="form-control barcss" name="item_barcode_similar" id="item_barcode_similar"></div>
                                    <div id="qrcodeerror_similar" class="qrcodeerror hide">QR Code Already Exist.</div>
                                </div>
                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                                <div class="col-md-5">  <label>Enter Serial No</label> </div>
                                <div class="col-md-7">  <input placeholder="Enter Serial No" class="form-control" name="item_serial_number_similar" id="item_serial_number_similar">
                                </div>
                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                            </div>
                            <!-- Quality -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label><h4>Item  Details</h4></label> </div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-5">
                                    <label>Quantity</label>
                                </div>
                                <div class="col-md-7"> 
                                    <input placeholder="Enter Quantity" name="item_quantity_similar" id="item_quantity_similar" class="form-control">

                                </div>
                            </div> <!-- /.form-group -->

                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label><h4>Ownership</h4></label> </div>
                            </div> 
                            <!-- Ownership -->
                            <div class="form-group col-md-12">
                                <div class="col-md-5">      <label>Owner*</label>
                                </div>
                                <div class="col-md-7"> 
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
                                <div class="col-md-5"> <label>Location*</label>
                                </div>
                                <div class="col-md-7">       
                                    <select name="location_id_similar" id="location_id_similar" class="form-control multi_location_class">
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
                                <div class="col-md-5"> <label>Site*</label>
                                </div>
                                <div class="col-md-7">       
                                    <select name="site_id_similar" id="site_id_similar" class="form-control multi_site_class">
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
                                <div class="col-md-5"><label>Supplier</label>
                                </div>
                                <div class="col-md-7"><select name="supplier_similar" id="supplier_similar" class="form-control">
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
                                <div class="col-md-5">          <label>Condition</label>
                                </div>
                                <div class="col-md-7"><select name="item_condition_similar" id="item_condition_similar" class="form-control">
                                        <option>----SELECT----</option>  
                                        <?php
                                        foreach ($arrCondition as $arrConn) {
                                            ?>
                                            <option value="<?php echo $arrConn['id']; ?>" <?php
                                            if ($arrConn['id'] == 1) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $arrConn['condition']; ?></option>                     
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-5">
                                    <label>Purchase Date</label>
                                </div>
                                <div class="col-md-7">
                                    <input placeholder="Enter Purchase Date" class="form-control datepicker" name="item_purchased_similar" id="item_purchased_similar" type="text">
                                </div>
                            </div>



                            <div class="form-group col-md-12">
                                <div class="col-md-5"><label>Purchase Price</label>
                                </div>

                                <div class="col-md-7">       
                                    <input placeholder="Enter Purchase Price" class="form-control" name="item_value_similar" id="item_value_similar" type="text">
                                </div>
                                <input type="hidden" readonly="" name="itemID" id="itemID">
                            </div>





                            <div class="form-group col-md-12">
                                <div class="col-md-6">
                                    <a href="#" id="addmore">
                                        Add Another Similar Item?</a> 
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" id="add_another" name="add_another">
                                </div>
                            </div>

                        </div>


                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                            <button class="btn btn-primary" type="submit" id="similar_item">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Condition Check Model -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="condition_check" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                        <h4 id="myModalLabel" class="modal-title">Condition Check</h4>
                    </div>

                    <form action="<?php echo base_url('items/con_history'); ?>" method="post" id="condition_check_form">
                        <div class="modal-body">
                            <!-- Condition Check -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label><h4>Condition Check</h4></label> </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Choose Item</label> </div>
                                <div class="col-md-6"><select name="asset_id" class="form-control"><option value="">----SELECT----</option>  
                                        <?php foreach ($assetlist as $asset) { ?>
                                            <option value="<?php echo $asset->itemid; ?>"><?php echo $asset->barcode; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>New Condition</label> </div>
                                <div class="col-md-6"><select name="new_condition" class="form-control"><option value="">----SELECT----</option>  
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

        <!-- Change Location Modal -->
<!--        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="change_owner_model" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                        <h4 id="myModalLabel" class="modal-title">Change Owner And Location</h4>
                    </div>
                    items/changelinks/'.$objItem->itemid
                    <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/items/changelinks" method="post" id="change_owner_form">

                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Choose Item</label> </div>
                                <div class="col-md-6">  <select name="item_id" class="form-control"><option value="">----SELECT----</option>  
                                        <?php foreach ($assetlist as $asset) { ?>
                                            <option value="<?php echo $asset->itemid; ?>"><?php echo $asset->barcode; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>New Owner</label> </div>
                                <div class="col-md-6">  <select name="new_owner_id" id="new_owner_id" class="form-control">
                                        <option value="0">----SELECT----</option>
                                        <?php
                                        foreach ($arrOwners['results'] as $arrOwner) {
                                            echo "<option ";
                                            echo 'value="' . $arrOwner->ownerid . '" ';
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
                                            echo '>' . $arrLocation->locationname . "</option>\r\n";
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="updated_location_id" id="updated_location_id" class="form-control multi_location_class" readonly>
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
                                            echo '>' . $arrSite->sitename . "</option>\r\n";
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" name="updated_site_id" id="updated_site_id" class="form-control multi_location_class" >
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
        </div>-->
        <!-- Report fault Model -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="report_fault" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                        <h4 id="myModalLabel" class="modal-title">Report Fault</h4>
                    </div>

                    <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/raiseTicket" method="post" id="report_fault_form">
                        <input type="hidden" name="report_item_id" id="report_item_id" value="" />
                        <input type="hidden" name="report_ticket_id" id="report_ticket_id" value="" />
                        <input type="hidden" name="mode" id="mode" value="reportFault" />
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Choose Item</label> </div>
                                <div class="col-md-6">  <select name="report_item_id" id="report_item_id" class="form-control"><option value="">----SELECT----</option>  
                                        <?php foreach ($assetlist as $asset) { ?>
                                            <option value="<?php echo $asset->itemid; ?>"><?php echo $asset->barcode; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Item</label> </div>
                                <div class="col-md-6">  <input readonly="readonly" class="form-control" id="report_item">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Manufacturer</label> </div>
                                <div class="col-md-6">  <input readonly="readonly" class="form-control"  id="report_manufacturer">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>QR CODE</label> </div>
                                <div class="col-md-6">  <input readonly="readonly" class="form-control" name="serial_number" id="report_serialno">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Category</label> </div>
                                <div class="col-md-6">  <input readonly="readonly" class="form-control" name="categoryname" id="report_category">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Location</label> </div>
                                <div class="col-md-6"><input readonly="readonly" class="form-control" name="locationname" id="report_location">
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
                                        <option value="6">Missing</option>
                                    </select>

                                </div>
                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Enter Order No</label> </div>
                                <div class="col-md-6"><input type="text" placeholder="Enter Order Number" class="form-control" name="order_no" id="order_no" value="" /></div></div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                            </div>

                            <!-- Job Notes -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Job Notes</label>
                                </div>
                            </div>
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

<!--                        <input type="hidden" name="fix_item_id" id="fix_item_id" value="" />
                        <input type="hidden" name="fix_ticket_id" id="fix_ticket_id" value="" />-->
                            <input type="hidden" name="mode" id="mode" value="fixFault" />
                            <div class="form-group col-md-12">
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Choose Item</label> </div>
                                <div class="col-md-6"><select name="fix_item_id" id="fix_item_id" class="form-control"><option value="">----SELECT----</option>  
                                        <?php foreach ($faultdata['results'] as $asset) { ?>
                                            <option value="<?php echo $asset->itemid; ?>"><?php echo $asset->barcode; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Item Menu</label> </div>
                                <div class="col-md-6"><input readonly="readonly" class="form-control" name="item_manu" id="u_item_manu">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Manufacturer</label> </div>
                                <div class="col-md-6">  <input readonly="readonly" class="form-control" name="manufacturer" id="u_manufacturer">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>QR CODE</label> </div>
                                <div class="col-md-6">  <input readonly="readonly" class="form-control" name="serial_number" id="u_serial_number">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6">  <label>Category</label> </div>
                                <div class="col-md-6">  <input readonly="readonly" class="form-control" name="categoryname" id="u_categoryname">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Location</label> </div>
                                <div class="col-md-6"><input readonly="readonly" class="form-control" name="locationname" id="u_locationname">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>

                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Action</label> </div>
                                <div class="col-md-6">
                                    <input readonly="readonly" class="form-control" name="action" id="action" value="Fix" disabled="">
                                </div>
                            </div> <!-- /.form-group -->
                            <div class="form-group col-md-12">
                                <div class="col-md-6"><label>Status</label> </div>
                                <div class="col-md-6">
                                    <input readonly="readonly" class="form-control" name="status" id="status" disabled="">
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
        <style>
            .table > thead > tr > th {
                color: #ffffff !important;
                font-weight: bolder;
                text-align: center;
                vertical-align: bottom;
            }
            .tbl
            {
                border-radius: 5px;
                border: 1px solid #000000;
                padding: 0px;
                float: left;
                margin-bottom: 10px;
                margin-right: 20px;
            }
            .utitle
            {
                margin-top: 0px; 
                margin-bottom: 20px; 
                text-align: center;  
            }
            .multiadd{
                min-height: 70px;
                max-height: 270px;
                height:auto;
                overflow-y: scroll;
            } 
            #add_item .modal-body{
                height: 595px;
                overflow-y: scroll;
            } 
            .custom_val
            {
                float: right!important;
                width: 80%!important;
            }
            .modal-body .row
            {
                margin: 10px 0px;
            }
            #add_similar_item .modal-body
            {
                height: 595px;
                overflow-y: scroll;
            }
            #condition_check .modal-body
            {
                height: 390px;
                overflow-y: scroll;  
            }
            #change_owner_model .modal-body
            {
                height: 390px;
                overflow-y: scroll;  
            }
            #report_fault_form .modal-body {
                height: 230px;
                overflow-y: scroll;
            }
            #report_fault .modal-body,#fix_item .modal-body
            {
                height: 565px;
                overflow-y: scroll;
            }
            .reportbtn
            {
                padding: 3px;
                font-size: 15px!important;
            }
            #search_box
            {
                display: none;
            }
            .opt
            {
                border-color: #adadad;
                border-radius: 9px;
                text-align: center;
                width: 160px!important;
                padding: 2px;
                color: #404040;
            }
            .opt option{
                width: 40px;
            }
            #search_results
            {
                display: none;
            }
            #resdata
            {
                min-height: 40px;
                max-height: 198px;
                overflow-y: auto;
                margin-bottom: 0px;
            }
            .searchhead
            {
                background: #37a9c3;
                color: #fff;
                font-weight: bold;
                text-align: left;
                font-size: 14px;
            }
        </style>

    </div>