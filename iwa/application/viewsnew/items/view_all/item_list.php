<style>
    /*#item_table tfoot, #item_table thead {
        display: none;
    }*/
    #item_table thead tr th, #item_table tbody tr td, .list_table thead tr th, .list_table tfoot tr th {
        text-align: center !important;
        max-width: 130px !important;
        min-width: 150px !important;
        width: 150px !important;
    }
    .list_table.tb.dataTable input {
        max-width: 100% !important;
        min-width: 100% !important;
        width: 100% !important;
    }
    .list_table thead tr th:last-child, .list_table thead tr td:last-child, #item_table thead tr th:last-child, #item_table tbody tr td:last-child, .list_table tfoot tr th:last-child {
        min-width: 230px !important;
    }
    .list_table thead tr th { 
        color:#ffffff !important
    }
    #item_table tfoot { display: none}
    #item_table thead tr th { padding: 0!important; margin: 0!important; max-height: 0!important; min-height: 0!important;}
    #columnselect .modal-body
    {
        height: 320px;
        overflow-y: scroll;
    }
    .qrcode_error
    {
        color: red;
        font-weight: bold;
    }
    .manu_error
    {
        color: red;
        font-weight: bold;
        float: right;
        padding-right: 5%;
    }
    .qrcodeerror
    {
        color: red;
        font-weight: bold;
    }
    #item_table select{
        color: black;
    }
    .modal-body .row
    {
        margin: 10px 0px;
    }
    #add_item .modal-dialog
    {
        width: 600px;
    }
    #add_item .modal-body{
        height: 595px;
        overflow-y: scroll;
    } 
    #remove_item .modal-body
    {
        height: 510px;
        overflow-y: scroll;   
    }
    #add_similar_item .modal-body,#multiComplianceEditModal .modal-body
    {
        height: 595px;
        overflow-y: scroll;
    }
    .error
    {
        color: red;
    }
    .dataTable tfoot select
    {
        width: auto!important;
    }
    .get_original
    {
        font-size: 14px;
        font-family: Verdana;
        font-weight: bold;
    }
    .custom_val
    {
        float: right!important;
        width: 80%!important;
    }
    .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        min-height: 75px;
        overflow: auto !important;
    }
    .fileupload { padding:0; margin-bottom:10px}
    #item_table td a 
    {
        text-decoration: none;
        font-weight: bold;
        font-size: 12px!important;
    }
    /*    table.dataTable tbody th, table.dataTable tbody td { padding: 0 20px !important; width: 200px !important;}*/
</style>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<script>
    $(document).ready(function() {

        var level = '<?php echo $arrSessionData['objSystemUser']->levelid; ?>';
        if (level == 1)
        {
            $('#additem').css('top', '0px');
        }

        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID
        var x = 1;
        //initlal text box count

        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment
                $(wrapper).append(' <div> <input class="fileupload upload form-control" type="file" name="photo_file_' + x + '" size="20"><a href="#" class="remove_field">Remove</a></div>'); //add input box
            }
        });
        $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
            total--;
        });
        var url = $('#item-url').val();
        var base_url_str = $("#base_url").val();
        var num_of_th = $('#num_of_th').val();
        var numofth = num_of_th.split(',');
//        alert(numofth.length);
        var aryJSONColTable = [];
        for (var k = 0; k < numofth.length; k++) {
//       console.log(k+"count")
            if (k != 0) {
//                aryJSONColTable.push({
//                    "bSortable": true,
//                    "aTargets": [k]
//                });
//            }
//            else if (k > 20)
//            {
                aryJSONColTable.push({
                    "sClass": "eamil_conform aligncenter",
                    "bSortable": true,
                    "aTargets": [k]
                });
            }
            else
            {
                aryJSONColTable.push({
                    "sClass": "eamil_conform aligncenter",
                    "bSortable": false,
                    "aTargets": [k],
                });
            }
        }
//console.log(aryJSONColTable);
        var item_table = $("#item_table").DataTable({
            "oLanguage": {
                "sProcessing": "<div align='center'><img src='<?php echo base_url('./img/ajax-loader.gif'); ?>'</div>"},
            "ordering": true,
            "bProcessing": true,
            "bServerSide": true,
            "stateSave": true,
            "bSortCellsTop": true,
//              "bFilter": false,
            "sAjaxSource": url,
            "bDeferRender": true,
            "aLengthMenu": [[20, 50, 100, 250, 500, -1], [20, 50, 100, 250, 500, "All"]],
            "iDisplayLength": 20,
            "sScrollX": "100%",
            "sScrollY": "570px",
            "bScrollCollapse": false,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnDrawCallback": function() {
                var api = this.api();
                $(api.column(7).footer()).html(
                        api.column(7, {page: 'current'}).data().sum()
                        );
                $(api.column(20).footer()).html(
                        api.column(20, {page: 'current'}).data().sum()
                        );
                $(api.column(21).footer()).html(
                        api.column(21, {page: 'current'}).data().sum()
                        );

            },
            "aoColumnDefs":
                    aryJSONColTable
        });

        for (var k = 0; k < numofth.length; k++) {
            var column = item_table.column(k);
            column.visible(true);
        }

        $("body").on("keyup", "#filter_barcode", function() {
            item_table.column(1)
                    .search(this.value)
                    .draw();
        });
        for (var m = 22; m < numofth.length; m++) {
            $("body").on("keyup", "#" + m, function() {
                var ind = this.id;
                var val = this.value;

                item_table.column(ind)
                        .search(val)
                        .draw();
            });
        }
//        for (var m = 22; m < numofth.length; m++) {
            $("body").on("change", "#22", function() {
                var ind = this.id;
                var val = this.value;

                item_table.column(ind)
                        .search(val)
                        .draw();
            });
//        }

        $("body").on("change", "#filtercategoryname", function() {

            item_table.column(3)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filteritem_manu", function() {
            item_table.column(4)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filtermanufacturer", function() {
            item_table.column(5)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filtersitename", function() {
            item_table.column(8)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filterlocationid", function() {
            item_table.column(9)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filteruserid", function() {
            item_table.column(10)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filtersupplier", function() {
            item_table.column(11)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filteritemstatusid", function() {
            item_table.column(12)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filterconstatus", function() {
            item_table.column(13)
                    .search(this.value)
                    .draw();
        });
        // Show Hide Column

        var total = $('#total').val();
        if (total != '') {
            var sub = total.split(',');
            if (sub.length > 0) {
                var hiddencolumns = new Array(sub);
            }
        }
        else
        {
            var hiddencolumns = new Array();
        }

        if (hiddencolumns.length > 0) {
            for (var j = 0; j < hiddencolumns.length; j++)
            {
                var column = item_table.column(hiddencolumns[j]);
                column.visible(false);
            }
        }

//        if ($("#similar_data").val())
//        {
//            alert('Add Similar Data');
//            setTimeout(function() {
        $(document).ajaxStop(function() {
            if ($("#similar_data").val())
            {
                $("body").find("#addsimilaritem").trigger("click");
            }
        });

//            }, 2000);
//        }

// Add similar asset data
        $('body').on('click', '#addsimilaritem', function() {

            if ($('#similar_data').val() != '') {
                var item_id = $('#similar_data').val();
            } else {
                var item_id = $(this).attr('data_item_id');
            }
//            $('#itemID').val(item_id);
            if ($('#owner_data').val() != '') {
                var owner = $('#owner_data').val();
            }

            $.ajax({
                type: "POST",
                url: base_url_str + "items/getassetdata/" + item_id,
                success: function(data) {
                    var assetdata = $.parseJSON(data);

//                    $.getJSON(base_url_str + "categories/getCustomFields/" + assetdata[0].categoryid, function(data) {
//
//                        $('#custom_fielddiv').empty();
//                        for (var i = 0; i < data.length; i++) {
//                            if (data[i].field_value == 'text_type')
//                            {
//                                var str = '<div class="row catg col-md-12"><div class="col-md-5">' +
//                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-7">' +
//                                        '<input type="text" class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' +
//                                        '</div></div>';
//                            }
//                            if (data[i].field_value == 'value_type')
//                            {
//                                var str = '<div class="row catg col-md-12"><div class="col-md-5">' +
//                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-7">' +
//                                        '<div class="input-group col-md-12"><div class="input-group-addon grpaddon">$</div><input type="number" min="0" class="form-control custom_val" id="' + data[i].id + '" name="' + data[i].id + '">' +
//                                        '</div></div></div>';
//                            }
//                            if (data[i].field_value == 'num')
//                            {
//                                var str = '<div class="row catg col-md-12"><div class="col-md-5">' +
//                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-7">' +
//                                        '<input type="number" min="0" class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' +
//                                        '</div></div>';
//                            }
//                            if (data[i].field_value == 'date_type')
//                            {
//                                var str = '<div class="row catg col-md-12"><div class="col-md-5">' +
//                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-7">' +
//                                        '<input type="text" class="form-control dateval" id="' + data[i].id + '" name="' + data[i].id + '">' +
//                                        '</div></div>';
//                            }
//                            if (data[i].field_value == 'pick_list_type')
//                            {
//                                if (data[i].pick_values)
//                                {
//                                    var temp = new Array();
//                                    var opt1 = new Array();
//                                    var list = data[i].pick_values;
//                                    var temp = list.split(',');
//                                    for (var j = 0; j < temp.length; j++) {
//                                        var opt = '<option value="' + temp[j] + '">' + temp[j] + '</option>';
//                                        opt1.push(opt);
//                                    }
//                                    var option = opt1.join('');
//                                }
//                                var str = '<div class="row catg col-md-12"><div class="col-md-5">' +
//                                        '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-7">' +
//                                        '<select class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' + option +
//                                        '</select>' +
//                                        '</div></div>';
//                            }
//                            $('#custom_fielddiv').append(str);
//                        }
//
//                        $(".dateval").datepicker({dateFormat: "dd/mm/yy"});
//                    });

//                    var pre = $("#asset_qrcode").val();
//                    var bar_code = pre + assetdata[0].barcode;
                    if (assetdata[0].item_manu_name != null) {
                        var item_manu = assetdata[0].item_manu_name + '/';
                    }
                    else
                    {
                        var item_manu = '';
                    }
                    var org = assetdata[0].barcode + '/' + assetdata[0].categoryname + '/' + item_manu + assetdata[0].manufacturer;
                    $('.get_original').html(org);
                    $('#item_quantity_similar').val(assetdata[0].quantity);
                    if (owner) {
                        $('#owner_id_similar option[value="' + assetdata[0].owner_now + '"]').attr('selected', 'selected');
                    }
                    else
                    {
                        if (assetdata[0].owner_now != '')
                        {
                            $('#owner_id_similar option[value="' + assetdata[0].owner_now + '"]').attr('selected', 'selected');
                        }
                        else
                        {
                            $('#owner_id_similar option[value="0"]').attr('selected', 'selected');
                        }
                    }
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

        // Remove asset data
        $('body').on('click', '#removeitem', function() {

            var item_id = $(this).attr('data_item_id');

            $.ajax({
                type: "POST",
                url: base_url_str + "items/getassetdata/" + item_id,
                success: function(data) {
                    var archivedata = $.parseJSON(data);
                    $('#purchaseprice').val(archivedata[0].value);
                    $('#currentvalue').val(archivedata[0].current_value);
                    if (archivedata[0].replace_date != null) {
                        var newdate = archivedata[0].replace_date.split("-").reverse().join("/");
                        $('#replacementdate').val(newdate);
                    }
                    if (archivedata[0].current_value >= 1) {
                        $('#payment_asset').attr('data-currentvalue', archivedata[0].current_value);
                    }
                    else
                    {
                        $('#payment_asset').attr('data-currentvalue', archivedata[0].value);
                    }

                }

            });

        });

        $('body').on('click', '#clearfilter', function(e) {
            e.preventDefault();
            bootbox.confirm("Do you want to reset this table?", function(result) {
                if (result) {
                    localStorage.removeItem('DataTables_/youaudit/iwa/items/filter');
                    item_table.destroy();
                    localStorage.clear();
                    window.location = window.location;
                    $('#item_table select option[value=""]').prop('selected', true);
                    $('#filter_barcode').val('');
                } else {
                    // Do nothing!
                }
            });
        });
        $('#activate-column-selector').on('click', function()
        {
            localStorage.removeItem('DataTables_/youaudit/iwa/items/filter');
            item_table.destroy();
            localStorage.clear();
            window.location = window.location;
        });
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
                                '<div class="input-group col-md-12"><div class="input-group-addon grpaddon">$</div><input type="number" min="0" class="form-control custom_val" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div></div>';
                    }
                    if (data[i].field_value == 'num')
                    {
                        var txt = '<div class="row"><div class="col-md-6">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label></div><div class="col-md-6">' +
                                '<input type="number" min="0" class="form-control" id="' + data[i].id + '" name="' + data[i].id + '">' +
                                '</div></div>';
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
//                item_model: {required: true},
//                item_quantity: {required: true},
                item_barcode: "required",
//                item_serial_number: "required",
//                status_id: {required: true, min: 1},
//                item_condition: {required: true, min: 1},
                owner_id: {required: true, min: 1},
                site_id: {required: true, min: 1},
                location_id: {required: true, min: 1},
//                supplier: {required: true, min: 1},
//                item_patteststatus: {required: true, min: 1},
//                item_purchased: "required",
//                add_warranty_date: "required",
//                item_replace: "required",
//                item_value: "required",
//                asset_type: "required",
//                drill_bits: "required",
//                accessories: "required",
//                item_notes: "required",
//                item_pattestdate: "required",
            },
            messages: {
                category_id: "Please Select Category",
//                item_model: "Please Select Model",
//                item_quantity: "Please Select Quantity",
                item_barcode: "Please Enter QR_code",
//                item_serial_number: "Please Enter Serial Number",
//                status_id: "Please Enter Status",
//                item_condition: "Please Select Condition",
                owner_id: "Please Select Owner",
                site_id: "Please Select Site",
                location_id: "Please Select Location",
//                supplier: "Please Select Supplier",
//                item_purchased: "Please Enter Purchase Date",
//                add_warranty_date: "Please Enter Expiry Date",
//                item_replace: "Please Enter Replacement Date",
//                item_value: "Please Enter Purchase Price",
//                item_current_value: "Please Enter Current Value",
//                asset_type: "Please Enter Asset Type",
//                drill_bits: "Please Enter Drill Bits",
//                accessories: "Please Enter Accessories",
//                item_notes: "Please Enter Notes",
//                item_pattestdate: "Please Enter PAT Test Date",
//                item_patteststatus: "Please Enter PAT Test Result"
            }
        });
        // Validation On Add Similar Item 
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
                item_barcode_similar: "Please Enter QR_code",
                owner_id_similar: "Please Select Owner",
                site_id_similar: "Please Select Site",
                location_id_similar: "Please Select Location",

            },
        });

        $("#remove_item_form").validate({
            rules: {
                reason: {required: true, min: 1},
                itemstatus: {required: true, min: 1}
            },
            messages: {
                reason: "Please Select Reason",
                itemstatus: "Please Select Removal Method"
            }
        });
//        if (!$('#addanother').is(':checked'))
//        {
//        $('#similar_data').val('');
//        $('#addanother').removeAttr('checked');
//        }

        // Script For Change Password
        $("body").on("click", ".add_similar", function() {
            $(".result").empty();
            if ($('#similar_data').val() != '')
            {
                var item_id = $('#similar_data').val();
                $('#addanother').attr('checked', true);
            }
            else {
                var item_id = $(this).attr("data_item_id");
                $('#addanother').removeAttr('checked');
            }
            $("#itemID").attr("value", item_id);
        });

        $("body").on("click", ".remove_item", function() {
            $(".result").empty();
            var item_id = $(this).attr("data_item_id");
            $("#archiveitemID").attr("value", item_id);
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

        $("#new_item").on("keyup blur", function() {

            var itemmanu = $("#new_item").val();
            var base_url_str = $("#base_url").val();
            $.ajax({
                type: "POST",
                url: base_url_str + "items/check_itemmanu",
                data: {
                    'item_manu': itemmanu
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#manu_error").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#manu_error").addClass("hide");
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
        // Multiple Checked
        $('body').find('.multiComSelect:checked').prop('checked', false);
        $('body').find('#selectAllchk').prop('checked', false);
        $('body').on('click', '.multiComSelect', function() {
            if ($('html').find('.multiComSelect:checked').length)
            {
                $('#multiComEditBtn').addClass('in').removeClass('hide');
                if ($('html').find('.multiComSelect:not(:checked)').length == 0)
                    $('#selectAllchk').prop('checked', true);
            } else {
                $('#multiComEditBtn').addClass('hide').removeClass('in');
                $('#selectAllchk').prop('checked', false);
            }
        });
        $('body').on('click', '#selectAllchk', function() {
            if ($(this).is(':checked')) {

                $('.multiComSelect').prop('checked', true);
                $('#multiComEditBtn').addClass('in').removeClass('hide');
            }
            else {

                $('.multiComSelect').prop('checked', false);
                $('#multiComEditBtn').addClass('hide').removeClass('in');
            }
        });
        $('#multiComEditBtn').on('click', function() {

            var ids = [];
            var cat_ids = [];
            $('#item_table').find('input[type="checkbox"]:checked').each(function() {
                ids.push($(this).attr('value'));
                cat_ids.push($('#category_id_' + $(this).attr('value')).attr('value'));
            });
            console.log(ids);
            console.log(cat_ids);
            var category_ids = (unique(cat_ids));
            showCustomeField(category_ids);
            $('#multiComIds').val(ids.join(','));
            $('#multiComplianceEditModal').find('select option[value=""]').prop('selected', true);
            $('#multiComplianceEditModal').find('#itemwarranty').val('');
            $('#multiComplianceEditModal').modal('show');
        });
        function showCustomeField(cat_id) {

            $('#custom_header').html('');
            $('#custom_header').html('<img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span>');
            $.ajax({
                url: '<?php echo base_url('items/getCustomFields'); ?>',
                type: 'post',
                data: {cat_ids: cat_id},
                success: function(data) {
                    $('#custom_header').html('');
                    data = JSON.parse(data);
                    $.each(data, function(k, v) {

                        //                        var html_content = '<div class="row col-md-12"><div class="col-md-4"><label>' + v['name'] + '</label></div><div class="col-md-8"><input type="text" class="form-control" name="custom_' + v['id'] + '" "></div></div>';

                        if (v['type'] == 'text_type')
                        {
                            var html_content = '<div class="row col-md-12"><div class="col-md-4">' +
                                    '<label>' + v['name'] + '</label></div><div class="col-md-8">' +
                                    '<input type="text" class="form-control" name="custom_' + v['id'] + '">' +
                                    '</div></div>';
                        }
                        if (v['type'] == 'value_type')
                        {
                            var html_content = '<div class="row col-md-12"><div class="col-md-4">' +
                                    '<label>' + v['name'] + '</label></div><div class="col-md-8">' +
                                    '<div class="input-group col-md-12"><div class="input-group-addon grpaddon">$</div><input type="number" min="0" class="form-control custom_val" name="custom_' + v['id'] + '">' +
                                    '</div></div></div></div>';
                        }
                        if (v['type'] == 'date_type')
                        {
                            var html_content = '<div class="row col-md-12"><div class="col-md-4">' +
                                    '<label>' + v['name'] + '</label></div><div class="col-md-8">' +
                                    '<input type="text" class="form-control dval" name="custom_' + v['id'] + '">' +
                                    '</div></div></div>';
                        }
                        if (v['type'] == 'pick_list_type')
                        {
                            if (v['value'])
                            {
                                var temp = new Array();
                                var opt1 = new Array();
                                var list = v['value'];
                                var temp = list.split(',');
                                opt1.push('<option value="">--Please Select--</option>');
                                for (var j = 0; j < temp.length; j++) {
                                   var opt = '<option value="' + temp[j] + '">' + temp[j] + '</option>';
                                    opt1.push(opt);
                                }
                                var option = opt1.join('');
                                console.log(option);
                            }
                            var html_content = '<div class="row col-md-12"><div class="col-md-4">' +
                                    '<label>' + v['name'] + '</label></div><div class="col-md-8">' +
                                    '<select class="form-control" name="custom_' + v['id'] + '">' + option +
                                    '</select>' +
                                    '</div></div></div>';
                        }
                        if (v['type'] == 'num')
                        {
                            var html_content = '<div class="row col-md-12"><div class="col-md-4">' +
                                    '<label>' + v['name'] + '</label></div><div class="col-md-8">' +
                                    '<input type="number" class="form-control" min="0" name="custom_' + v['id'] + '">' +
                                    '</div></div>';
                        }

                        $(html_content).appendTo('#custom_header');
                    });
                    $(".dval").datepicker({dateFormat: "dd/mm/yy"});
                },
                error: function(data) {
                }
            });
        }
        function unique(array) {
            return array.filter(function(el, index, arr) {
                return index == arr.indexOf(el);
            });
        }

        //-----Exporting pdf--------

        // column array for csv
        var arr = [];
        var showcol = $('#assetfilter').val();
        if (showcol != '') {
            var sub = showcol.split(',');
            if (sub.length > 0) {
                for (var i = 0; i < sub.length; i++) {
                    if (sub[i] != 2) {
                        arr.push(sub[i]);
                    }
                }

            }
        }
        console.log(arr);
        // column array for pdf
        var pdfarr = [];
        var showcol = $('#assetfilter').val();
        if (showcol != '') {
            var sub = showcol.split(',');
            if (sub.length > 0) {
                for (var i = 0; i < sub.length; i++) {
                    pdfarr.push(sub[i]);
                }

            }
        }
        console.log(pdfarr);
        $('#exportPdfButton').on('click', function(e) {
            var data1 = $("#item_table").dataTable()._('tr', {"filter": "applied"});
            var data = data1.map(function(row) {
                var rowArr = [];
                $.each(pdfarr, function(i, v) {
                    rowArr.push(row[v]);
                });
                return '<td>' + rowArr.join('</td><td>') + '</td>';
            })
                    .join('</tr><tr>');
            data = '<tbody><tr>' + data + '</tr></tbody>';
            var cloneHead = [];
            var cloneFoot = [];
            var head = $('#item_table thead').clone();
            var foot = $('#item_table tfoot').clone();
            head.find('th[data-export="true"]').each(function(i) {
                console.log($(this).html());
                cloneHead.push($(this).html());
            });
            foot.find('th[data-export="true"]').each(function(j) {
                console.log($(this).html());
                cloneFoot.push($(this).html());
            });
            cloneHead = '<thead style="background-color: #00aeef;"><tr><th>' + cloneHead.join('</th><th>') + '</th></tr></thead>';
            cloneFoot = '<tfoot><tr><th>Summary- TOTAL / COUNT' + cloneFoot.join('</th><th>') + '</th></tr></tfoot>';
            console.log(cloneHead);

            $('#exp_table_content').val(cloneHead + data + cloneFoot);
            $('#export_form').submit();
        });
        // ----------CSV Export----------------        
        $('#exportCsvButton').on('click', function(e) {
            var data1 = $("#item_table").dataTable()._('tr', {"filter": "applied"});

            var data = data1.map(function(row) {
                var rowArr = [];
                $.each(arr, function(i, v) {
                    rowArr.push(row[v]);
                });
                return rowArr.join(',');
            }).join('|');

            var cloneHead = [];
            var cloneFoot = [];
            var head = $('#item_table thead').clone();
            var foot = $('#item_table tfoot').clone();
            head.find('th[data-export="true"]').each(function(i) {
                cloneHead.push($(this).html());
            });
            foot.find('th[data-export="true"]').each(function(j) {
                cloneFoot.push($(this).html());
            });
            cloneHead = cloneHead.join(',');
            cloneFoot = cloneFoot.join(',');
            // remove photo th
            var heads = cloneHead.split(',');
            var reshead = [];
            var thCount = 0;
            $.each(heads, function(i, v) {
                if (thCount != 1) {
                    reshead.push(heads[i]);
                }
                thCount++;
            });
            var foots = cloneFoot.split(',');
            var resfoot = [];
            $.each(foots, function(j, v) {
                if (heads[j] != 'Photo') {
                    if (j == '0') {
                        foots[j] = 'Summary- TOTAL / COUNT = '+data1.length+'';
                    }
                    resfoot.push(foots[j]);
                }
            });
            $('#csv_table_content').val(reshead + '|' + data + '|' + resfoot);
            $('#export_csv_form').submit();
        });

         // Establish Link to Owner,Location and Site
        $('body').find('#owner_id').change(function() {
//            $(".multi_location_class").empty();
//            $(".multi_site_class").empty(); 
            var owner_id = this.value;
            if (owner_id != 0) {
                $.getJSON("<?php echo base_url('items/getlocationbyowner'); ?>" + '/' + owner_id, function(data) {

                    if (data.results.length != 0) {
                        $('.multi_location_class option[value="' + data.results[0].location_id + '"]').attr('selected', 'selected');
//                    $('#updated_location_id').attr('value', +data.results[0].location_id);
                        $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + data.results[0].location_id, function(site_data) {
                           if (site_data!= null)
                            {
                                $('.multi_site_class option[value="' + site_data.results[0].site_id + '"]').attr('selected', 'selected');
//                            $('#updated_site_id').attr('value', +site_data.results[0].site_id);
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


        // establish link and site link

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
        
        $(document).find("#item_table").find("tfoot").addClass("pp");

    });
    $(function() {
        $('#payment_asset').on('keyup blur', function() {
            var payment = $(this).val();
//            var current_value = $(this).attr('data-currentvalue');
            var current_value = $('body').find('#currentvalue').val();

            var numericRegex = /[(0-9)+.?(0-9)*]+/igm;
            //        console.log(numericRegex.test(payment));
            //        console.log(payment+' - '+current_value);

            if (payment.match(numericRegex) != '' && payment != '') {
                var net_gain_loss = (current_value - payment).toFixed(2);
                $('#remove_asset').val(net_gain_loss);
            }
        });
    });
</script>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<input type="hidden" id="similar_data" value="<?php echo $this->session->flashdata('item'); ?>">
<input type="hidden" id="owner_data" value="<?php echo $this->session->flashdata('ownership'); ?>">
<div class="row">
    <h1>Asset Equipment Register</h1>
</div>
<input type="hidden" id="asset_qrcode" value="<?php echo $this->session->userdata('objSystemUser')->qrcode; ?>">
<div class="heading">

    <div class="col-md-12" style="margin-top: 10px;">
        <div class="col-md-7">
            <?php
            if ($arrSessionData['objSystemUser']->levelid > 2) {
                ?>
                <div class="icon-nav">
    <!--                <form  id="csvform" action="<?= site_url($_SERVER['REDIRECT_QUERY_STRING']) ?>" method="post">
                        <input  type="hidden" value="ExportResultsasCSV" name="csvfile">
                    --> 
                    <a href="#" id="exportCsvButton" class="button icon-with-text round" type="button" style="padding:0">
                        <i class="fa  fa-file-o"></i>
                        <b>Export to <br />CSV</b>
                    </a>
                    <!--
                                    </form>-->
                </div>
                <div class="icon-nav">

                    <a class="button icon-with-text round" id="exportPdfButton" href="#" style="padding: 0">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to PDF</b></a>

                </div> <?php } ?>
<!--            <a class="" id="exportCsvButton" href="#"> <img src="<?= base_url('/img/ui-icons/csv-icon.png'); ?>" alt="..."/></a>
<a class="" id="exportPdfButton" href="#"> <img src="<?= base_url('/img/ui-icons/pdf-icon.png'); ?>" alt="..."/></a>-->
            <?php
            if ($arrSessionData['objSystemUser']->levelid > 1) {
                ?>

                <a id="clearfilter" class="button icon-with-text round">
                    <i class="glyphicon glyphicon-repeat" ></i>
                    <b>Clear Filter</b></a>
                <!--                <a id="activate-column-selector" href="#" class="button icon-with-text round">
                                    <i class="glyphicon glyphicon-th-list" style="transform: rotate(91deg)"></i>
                                    <b>Show/Hide Columns</b>
                                </a>-->
                <a class="button icon-with-text round" data-target="#columnselect" data-toggle="modal">
                    <i class="glyphicon glyphicon-th-list" style="transform: rotate(91deg)"></i>
                    <b>Show/Hide Columns</b>
                </a>
                <?php
                if ($arrSessionData['objSystemUser']->levelid > 2) {
                    ?>
                    <a href="<?php echo site_url('/items/confirmdeleted/'); ?>" class="button icon-with-text round">
                        <i class="fa  fa-trash-o"></i>
                        <b>Confirm Deletions</b></a>
                    <?php
                }
                ?>

                <?php
            }
            ?>
            <a href="#" data-toggle="modal" data-target="#add_item" class="btn btn-primary icon-with-text round" id="additem" style="top: -20px;position: relative">
                <i class="fa  fa-plus-circle"></i>
                <b>Add Items</b></a>
        </div>
        <div class="text-right col-md-5">
            <span class ="com-name"><?= $arrSessionData['objSystemUser']->accountname; ?>
                <!--<img src="<?= base_url('/img/circle-red.png'); ?>" width="60" />-->
            </span>
            <?php
            $logo = 'logo.png';
            if (isset($this->session->userdata['theme_design']->logo) && $this->session->userdata['theme_design']->logo != '') {

                $logo = $this->session->userdata['theme_design']->logo;
            }
            ?>

            <div class="logocls">
                <img  alt="iSchool"  class="imgreplace" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>"  >

            </div>
        </div>
    </div>

</div>

<div class="row" style="overflow-x: auto;">
    <input type="hidden" id="item-url" value="<?php echo base_url('/items/show_items'); ?>">
    <table class="list_table tb" id="item_table">
        <thead>

            <tr> <th>Select</th>
                <?php
                foreach ($arrColumns as $column) {
                    
                    $cnt = 22;
//                    var_dump($column);
                    ?>
                    <th class="left" data-export="true"><?php echo $column->name; ?></th>

<?php } ?>
                <th class="" data-export="false" style="text-align: center;width: 160px;float: left;padding-left: 10px;height: 35px;">Actions</th>

            </tr>


            <tr> 
                <th class="left" data-export="false"><input id="selectAllchk" type="checkbox" title="Select ALL">
                    <br><button id="multiComEditBtn" class="btn btn-warning fade hide" style="padding:0 5px;" type="button">Edit</button></th>
                <?php
                foreach ($arrColumns as $column) {
                    ?>
                    <?php if ($column->input_name == "barcode") { ?>
                        <th><input type="text" name="filter_barcode" id="filter_barcode"></th>
                    <?php } elseif ($column->input_name == "photoid") { ?>
                        <th></th>
    <?php } elseif ($column->input_name == "categoryname") { ?>
                        <th><select id="filtercategoryname">
                                <option value=""></option>
                                <?php
                                foreach ($arrCategories['results'] as $arrCategory) {
                                    echo '<option value="' . $arrCategory->categoryname . '">' . $arrCategory->categoryname . '</option>';
                                }
                                ?>
                            </select>
                        </th>
    <?php } elseif ($column->input_name == "item_manu") { ?>
                        <th><select id="filteritem_manu">
                                <option value=""></option>
                                <?php
                                foreach ($arrItemManu['list'] as $arrManu) {
                                    echo '<option value="' . $arrManu['item_manu_name'] . '">' . $arrManu['item_manu_name'] . '</option>';
                                    echo '>' . $arrManu['item_manu_name'] . "</option>\r\n";
                                }
                                ?>
                            </select></th>
    <?php } elseif ($column->input_name == "manufacturer") { ?>
                        <th><select id="filtermanufacturer">
                                <option value=""></option>
                                <?php
                                foreach ($arrManufacturers as $arrManufacturer) {
                                    echo '<option value="' . $arrManufacturer . '">' . $arrManufacturer . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "model") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "quantity") { ?>
                        <th></th>
    <?php } elseif ($column->input_name == "sitename") { ?>
                        <th><select id="filtersitename">
                                <option value=""></option>
                                <?php
                                foreach ($arrSites['results'] as $arrSite) {
                                    echo '<option value="' . $arrSite->sitename . '">' . $arrSite->sitename . '</option>';
                                }
                                ?>
                            </select></th>
    <?php } elseif ($column->input_name == "locationname") { ?>
                        <th><select id="filterlocationid">
                                <option value=""></option>
                                <?php
                                foreach ($arrLocations['results'] as $arrLocation) {
                                    echo '<option value="' . $arrLocation->locationname . '">' . $arrLocation->locationname . '</option>';
                                }
                                ?>
                            </select></th>
    <?php } elseif ($column->input_name == "owner") { ?>
                        <th><select id="filteruserid">
                                <option value=""></option>
                                <?php
                                foreach ($arrOwners['results'] as $arrOwner) {
                                    echo '<option value="' . $arrOwner->owner_name . '">' . $arrOwner->owner_name . '</option>';
                                }
                                ?>
                            </select></th>
    <?php } elseif ($column->input_name == "supplier") { ?>
                        <th>
                            <select id="filtersupplier">
                                <option value=""></option>
                                <?php
                                foreach ($arrSuppliers as $supplier) {
                                    echo '<option value="' . $supplier['supplier_name'] . '">' . $supplier['supplier_name'] . '</option>';
                                }
                                ?>
                            </select> 
                        </th>
    <?php } elseif ($column->input_name == "statusname") { ?>
                        <th><select id="filteritemstatusid">
                                <option value=""></option>
                                <?php
                                foreach ($arrItemStatuses['results'] as $arrItemStatus) {
                                    echo '<option value="' . $arrItemStatus->statusname . '">' . $arrItemStatus->statusname . '</option>';
                                }
                                ?>
                            </select></th>
    <?php } elseif ($column->input_name == "condition_name") { ?>
                        <th><select id="filterconstatus">
                                <option value=""></option>
                                <?php
                                foreach ($arrCondition as $arrCon) {
                                    echo '<option value="' . $arrCon['condition'] . '">' . $arrCon['condition'] . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "total_faults") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "serial_number") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "asset_age") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "purchase_date") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "warranty_expiry") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "replacement_due") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "value") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "current_value") { ?>
                        <th></th>
    <?php } else { ?>

                        <th>
                            <?php
                            if ($column->field_value == "pick_list_type") {
                                if ($column->pick_values != '' && strpos($column->pick_values, ',')) {
                                    $pick_values = explode(',', $column->pick_values);
                                    ?>
                                    <select id="<?php echo $cnt; ?>" name="filter_custom" class="filter_custom severity">
                                        <option value=""></option>
                                        <?php for ($k = 0; $k < count($pick_values); $k++) { ?>
                                            <option value="<?php echo $pick_values[$k]; ?>"><?php echo $pick_values[$k]; ?></option>
                                    <?php } ?>
                                    </select> 
            <?php } else { ?>
                                    <select id="<?php echo $cnt; ?>" name="filter_custom" class="filter_custom severity">
                                        <option value=""></option>
                                        <option value="<?php echo $column->pick_values; ?>"><?php echo $column->pick_values; ?></option>
                                    </select> 
                                    <?php
                                }
                            } else {
                                ?>
                                <input type="text" id="<?php echo $cnt; ?>" name="filter_custom" class="filter_custom">   
                        <?php } ?>  
                        </th> <?php
                        $cnt++;
                    }
                    ?>
<?php } ?>
                <th></th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
        <th>Total/Count</th> 
        <?php
        foreach ($arrColumns as $column) {
            ?>
            <th data-export="true"></th>          
            <?php
        }
        ?>
        <th></th>
        </tfoot>
    </table>
</div> 
<input type="hidden" id="num_of_th" value="<?php
$data = array();
$data[] = '0';
for ($i = 0; $i < count($arrUserColumns); $i++) {
    if (strpos($arrUserColumns[$i][0]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrUserColumns[$i][0]->id);
        $data[] = $explode_custom[2];
    } else {
        $data[] = $arrUserColumns[$i][0]->id;
    }
}
$data[] = $explode_custom[2] + 1;
$ids1 = array();
foreach ($data as $elem1) {
    $ids1[] = $elem1;
}
echo implode(',', $ids1);
?>">


<input type="hidden" id="assetfilter" value="<?php
$data = array();

for ($i = 0; $i < count($arrUserColumns); $i++) {
    if (strpos($arrUserColumns[$i][0]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrUserColumns[$i][0]->id);
        $data[] = $explode_custom[2];
    } else {
        $data[] = $arrUserColumns[$i][0]->id;
    }
}

$ids = array();
foreach ($data as $elem) {
    $ids[] = $elem;
}

echo implode(',', $ids);
?>">

<input type="hidden" id="total" value="<?php
$data = array();
for ($i = 0; $i < count($arrUserColumns); $i++) {
    if (strpos($arrUserColumns[$i][0]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrUserColumns[$i][0]->id);
        $data[] = $explode_custom[2];
    } else {
        $data[] = $arrUserColumns[$i][0]->id;
    }
}

$ids1 = array();
foreach ($data as $elem1) {
    $ids1[] = $elem1;
}

$remain = array();
for ($i = 0; $i < count($arrColumns); $i++) {
    if (strpos($arrColumns[$i]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrColumns[$i]->id);
        $remain[] = $explode_custom[2];
    } else {
        $remain[] = $arrColumns[$i]->id;
    }
}
$ids2 = array();
foreach ($remain as $elem2) {
    $ids2[] = $elem2;
}

$arr1 = array_diff($ids2, $ids1);
if (!empty($arr1)) {
    echo implode(',', $arr1);
}
?>">
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="columnselect" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Choose Columns</h4>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('items/filter/') ?>" method="post">


                    <div class="col-md-12">
                        <?php
//                        var_dump($arrColumns);
                        $arr = intval(count($arrColumns) / 2);
                        for ($i = 0; $i < $arr; $i++) {
                            ?>
                            <div class="col-md-6">
                                <div class="col-md-2"><input type="checkbox" name="columns[]" value="<?= $arrColumns[$i]->id; ?>" <?php
                                    foreach ($arrUserColumnsFilter as $usercolumn) {
                                        print ($usercolumn[0]->id == $arrColumns[$i]->id ? 'checked' : '');
                                    };
                                    ?> /></div>
                                <div class="col-md-10"><?= $arrColumns[$i]->name; ?></div>
                            </div>
<?php } for ($i = $arr; $i < count($arrColumns); $i++) { ?>

                            <div class="col-md-6">
                                <div class="col-md-2"><input type="checkbox" name="columns[]" value="<?= $arrColumns[$i]->id; ?>" <?php
                                    foreach ($arrUserColumnsFilter as $usercolumn) {
                                        print ($usercolumn[0]->id == $arrColumns[$i]->id ? 'checked' : '');
                                    };
                                    ?> /></div>
                                <div class="col-md-10"><?= $arrColumns[$i]->name; ?></div> 
                            </div>
<?php } ?>
                    </div>


        <!--<input class="button" type="submit" value="Apply columns" style="width: 100%;"/>-->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input class="btn btn-warning" type="submit" value="Apply columns"/>
            </div>
            </form>
        </div>
    </div>
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
                        <div id="manu_error" class="manu_error hide">Item Manu Name Already Exist.</div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Manufacturer*</label> </div>

                        <div class="col-md-6"> 

                            <select name="manufacturer" id="manufacturer" class="form-control">
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
                        <?php // var_dump($arrLocations); ?>
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
                        <div class="col-md-6 input_fields_wrap"> 
                            <button class="btn btn-primary btn-circle btn-xs add_field_button" title="add more image" type="button"><i class="glyphicon glyphicon-plus"></i></button>    
                            <div><input class="fileupload upload form-control" type="file" name="photo_file_1" size="20"></div>

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
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <a href="#" id="addowner">
                                Add Item Same Ownership ?</a> 
                        </div>
                        <div class="col-md-6">
                            <input type="checkbox" id="add_ownership" name="add_ownership">
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
                        <div class="col-md-6"><label><h4>Item ID</h4></label> </div>

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
                        <input type="hidden" readonly name="itemID" id="itemID">
                    </div>

                    <div id="custom_fielddiv"></div>



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
                    <button class="btn btn-primary" type="submit" id="similar_item">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<!--<div id="columnselector" style="display: none;">
    <form action="<?= site_url('items/filter/') ?>" method="post">

<?php foreach ($arrColumns as $column) { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <div class="form_row">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <label for="item_make"><?= $column->name ?></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <input type="checkbox" name="columns[]" value="<?= $column->id ?>" />


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <hr style="margin: 0;"/>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
<?php } ?>


        <input class="button" type="submit" value="Apply columns" style="width: 100%;"/>
    </form>
</div>-->

<!-- Multiple Edit Item Model -->
<div class="modal fade" id="multiComplianceEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url() ?>items/editmultiitem" method="post" id="editmultiitem">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Edit Multiple Items</h4>
                </div>
                <div class="modal-body">

                    <input hidden="" name="items_id" id="multiComIds">
                    <div class="row col-md-12">

                        <div class="col-md-4"><label>Category</label></div>
                        <div class="col-md-8"><select class="form-control" name="category">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($arrCategories['results'] as $category) { ?>
                                    <option value="<?php print $category->categoryid; ?>" data-selector="<?php print $category->categoryname; ?>"><?php print $category->categoryname; ?></option>
<?php } ?>
                            </select></div>
                    </div> 
                    <div class="row col-md-12">

                        <div class="col-md-4"><label>Item</label></div>
                        <div class="col-md-8"><select class="form-control" name="item_manu">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($arrItemManu['list'] as $itemManu) {
                                    ?>
                                    <option value="<?php print $itemManu['id']; ?>" data-selector="<?php print $itemManu['item_manu_name']; ?>"><?php print $itemManu['item_manu_name']; ?></option>
<?php } ?>
                            </select></div>
                    </div> 

                    <div class="row col-md-12">
                        <div class="col-md-4">        <label>Manufacturer*</label> </div>

                        <div class="col-md-8"> <select name="manufacturer" id="manufacturer" class="form-control">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($arrManufaturer as $manufacturer) { ?>
                                    <option value="<?php echo $manufacturer['manufacturer_name']; ?>"><?php echo $manufacturer['manufacturer_name']; ?></option>
<?php } ?>

                            </select> </div>
                    </div> 
                    <div class="row col-md-12">
                        <div class="col-md-4">            <label>Model</label></div>
                        <div class="col-md-8">
                            <input placeholder="Enter Model" name="item_model" id="item_model" class="form-control">
                        </div>
                    </div> 



                    <div class="row col-md-12">

                        <div class="col-md-4"><label>Owner</label></div>
                        <div class="col-md-8"><select class="form-control" name="user">
                                <option value="">-- Please Select --</option>
                                <?php
                                foreach ($arrOwners['results'] as $arrOwner) {
                                    echo "<option ";
                                    echo 'value="' . $arrOwner->ownerid . '" ';
                                    echo '>' . $arrOwner->owner_name . "</option>\r\n";
                                }
                                ?>
                            </select></div>
                    </div>  <div class="row col-md-12">

                        <div class="col-md-4"><label>Site</label></div>
                        <div class="col-md-8"><select class="form-control multi_site_class" name="site" id="multi_site">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($arrSites['results'] as $site) { ?>
                                    <option value="<?php print $site->siteid; ?>" data-selector="<?php print $site->sitename; ?>"><?php print $site->sitename; ?></option>
<?php } ?>
                            </select></div>
                    </div> 

                    <div class="row col-md-12">

                        <div class="col-md-4"><label>Location</label></div>
                        <div class="col-md-8"><select class="form-control multi_location_class" name="location" id="multi_location">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($arrLocations['results'] as $location) { ?>
                                    <option value="<?php print $location->locationid; ?>" data-selector="<?php print $location->locationname; ?>"><?php print $location->locationname; ?></option>
<?php } ?>
                            </select></div>
                    </div> 
                    <div class="row col-md-12">

                        <div class="col-md-4"><label>Status</label></div>
                        <div class="col-md-8"><select class="form-control" name="status" id="multi_status_id">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($arrItemStatuses['results'] as $status) { ?>
                                    <option value="<?php print $status->statusid; ?>" data-selector="<?php print $status->statusname; ?>"><?php print $status->statusname; ?></option>
<?php } ?>
                            </select></div>
                    </div> 
                    <div class="row col-md-12">
                        <div class="col-md-4">          <label>Condition</label>
                        </div>
                        <div class="col-md-8"><select name="item_condition" id="multi_item_condition" class="form-control">
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
                    <div class="row col-md-12">

                        <div class="col-md-4"><label>Supplier</label></div>
                        <div class="col-md-8"><select class="form-control" name="supplier">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($arrSuppliers as $supplier) { ?>
                                    <option value="<?php print $supplier['supplier_id']; ?>" data-selector="<?php print $supplier['supplier_name']; ?>"><?php print $supplier['supplier_name']; ?></option>
<?php } ?>
                            </select></div>
                    </div> 

                    <div class="row col-md-12">

                        <label class="col-md-4">Warranty Date</label>
                        <div class="col-md-8"><input type="input" name="item_warranty" id="item_warranty" value="" class="datepicker form-control" /></div>

                    </div> 

                    <div id="custom_header">
                        <img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span>

                    </div>   

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return beforeMultipleEdit()" class="btn btn-warning">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Model For Remove Asset -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="remove_item" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Archive Asset</h4>
            </div>

            <form action="<?php echo base_url() . 'items/markDeleted/' ?>" method="POST" id="remove_item_form"  enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Show Purchase Price</label> </div>
                        <div class="col-md-7">  <input class="form-control" id="purchaseprice" value="">
                        </div>
                    </div>  <!-- .form-group  -->


                    <div class="form-group col-md-12">
                        <div class="col-md-5">
                            <label>Show Current Value</label>
                        </div>
                        <div class="col-md-7"> 
                            <input class="form-control" id="currentvalue" value="">

                        </div>
                    </div>  <!-- .form-group --> 

                    <div class="form-group col-md-12">
                        <div class="col-md-5">
                            <label>Show Replacement Date</label>
                        </div>
                        <div class="col-md-7"> 
                            <input class="form-control" id="replacementdate" value="">

                        </div>
                    </div>  <!-- .form-group --> 

                    <!--                    <div class="form-group col-md-12">
                                            <div class="col-md-5">
                                                <label>Show Asset Age</label>
                                            </div>
                                            <div class="col-md-7"> 
                                                <input class="form-control" id="assetage" value="">
                    
                                            </div>
                                        </div> -->
                    <!-- .form-group --> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Complete Form Below</h4></label> </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-5"> <label>Why is asset being removed? Reason Code</label>
                        </div>
                        <div class="col-md-7">       
                            <select class="form-control" name="reason">
                                <option value="-1">Select</option>
                                <?php
                                foreach ($RemoveItemReasons['results'] as $ItemReason) {
                                    echo "<option value=\"" . $ItemReason->reasonid . "\">" . $ItemReason->reason . "</option>\r\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-5"> <label>How was asset removed? Reason Code</label>
                        </div>
                        <div class="col-md-7">       
                            <select class="form-control" name='itemstatus'>
                                <option value="-1">Select</option>
                                <?php
                                foreach ($arrReasons['results'] as $arrStatus) {
                                    echo "<option value=\"" . $arrStatus->statusid . "\">" . $arrStatus->statusname . "</option>\r\n";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" readonly name="archiveitemID" id="archiveitemID">
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-5">
                            <label>Payment / Income for Removed Asset? Sold / Scrap / Recycled etc</label>
                        </div>
                        <div class="col-md-7"> 
                            <input class="form-control" id="payment_asset" data-currentvalue="" value="">
                        </div>
                    </div>  <!-- .form-group --> 

                    <div class="form-group col-md-12">
                        <div class="col-md-5">
                            <label>Net Income of Removed Asset</label>
                        </div>
                        <div class="col-md-7"> 
                            <input class="form-control" id="remove_asset" value="" readonly='true'>
                        </div>
                    </div> <!-- .form-group -->
                    <input hidden='' name='safety' value='1'>
                </div>


                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="similar_item">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- .modal-dialog --> 
</div>

<form id="export_form" class="hider" hidden="" action="<?php echo base_url('/items/exportToPdf'); ?>" method="post">
    <input id="exp_table_content" name="allData">
    <input name="filename" value="Itemlist">
    <input type="submit">
</form>
<form id="export_csv_form" class="hider" hidden="" action="<?php echo base_url('/items/exporttocsv'); ?>" method="post">
    <input id="csv_table_content" name="allData">
    <input name="filename" value="Itemlist">
    <input type="submit">
</form>



