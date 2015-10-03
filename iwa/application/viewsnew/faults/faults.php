<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<?php $this->load->helper('text'); ?>
<style>
    th {
    text-align: center;
    }
    #open_jobs thead tr th, #open_jobs tbody tr td, .list_table thead tr th, .list_table tfoot tr th {
        text-align: center !important;
        max-width: 150px !important;
        min-width: 150px !important;
        width: 150px !important;
    }
    #open_jobs.tb.dataTable input {
        max-width: 100% !important;
        min-width: 100% !important;
        width: 100% !important;
    }
	.fault-action, #asset_body tr td:last-child {
        max-width: 300px !important;
        min-width: 300px !important;
        width: 300px !important;
        text-align: center !important;
    }
    /*.dataTables_scrollHeadInner table thead tr:last-child { display:none}*/
    #open_jobs thead tr th { padding: 0!important; margin: 0!important; max-height: 0!important; min-height: 0!important;}



    .modal-body{
        min-height: 350px;
        max-height: 595px;
        overflow-y: scroll;
    } 
    .faults
    {
        border-bottom: 1px solid #e5e5e5;
        line-height: 25px;
        margin-bottom: 15px;
        padding-left: 10px;
    }
    .fault_photo
    {
        display: none;
    }
    #open_jobs select
    {
        border-radius: unset;
        width: 81px;
        padding: 0px;
        border-color: #686868;
    }
    #open_jobs thead tr th, #open_jobs tbody tr td, .list_table thead tr th, .list_table tfoot tr th { min-width:220px; text-align:center;}

    #fix_jobs select
    {
        border-radius: unset;
        width: 81px;
        padding: 0px;
        border-color: #686868;
    }


    #fix_jobs tbody {
        height: 230px;
        overflow-y: auto;
        width: 100%;
    }
</style>
<script type="text/javascript">
    $(document).ready(function()
    {
        var site_server = '<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>';
        var base_url = $("#base_url").val();
        $(".viewfault").click(function() {

            var iId = $(this).attr('id');
            var account_id = $(this).attr('account_id');
            var ticket_id = $(this).attr('ticket_id');
            var type = 'Open Job';
            // Call ajax
            $.ajax({
                type: "POST",
                url: base_url + "faults/ajaxfetchItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id + "&type=" + type,
                success: function(data) {
                    console.log('I am result');
                    $("#view_fault #v_item_manu").val(data.item_manu_name);
                    $("#view_fault #v_manufacturer").val(data.manufacturer);
                    $("#view_fault #v_serial_number").val(data.barcode);
                    $("#view_fault #v_categoryname").val(data.categoryname);
                    $("#view_fault #v_locationname").val(data.locationname);
                    $("#view_fault #v_itemstatusname").val(data.itemstatusname);
                    $("#view_fault #v_order_no").val(data.order_no);
                    $("#view_fault #loggedBy_div").val(data.loggedBy);
                    $("#view_fault #loggedByDate_div").val(data.loggedByDate);
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
                            img_div += "<div class='image_single'>";
                            img_div += "<img width='65' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='" + base_url + "/index.php/images/viewList/" + photoid[i] + "'>";
                            img_div += "</div></div>";
                            $("#photo_div").append(img_div);
                        }
                    }
                    else
                    {
                        $('.fault_photo').css('display', 'none');
                    }

                     var notes_div = '';
                    if (data.allNotes != "") {
                        var allNote = data.allNotes.split(',');
                        var noteDate = data.notesDate.split(',');
                            $(".job_notes_div").empty();
                       
                        notes_div += "<ul>";
                        for (var i = 0; i < allNote.length; i++) {
                            notes_div += "<li style='list-style:none;padding:0;margin:0;'>"+noteDate[i]+ " - "+ allNote[i] + "</li>";

                        }
                        notes_div += "</ul>";
                    }else{
                    $(".job_notes_div").empty();
                       notes_div += "<ul><li style='list-style:none;padding:0;margin:0;'>" +data.loggedByDate+ " - "+ data.jobnote + "</li></ul>";
                    }
                  
                        $(".job_notes_div").html(notes_div);


                    $("#view_fault #v_job_notes").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax

        }) // End of function

        $(".reportfault").click(function() {

            var iId = $(this).attr('id');
            var account_id = $(this).attr('account_id');
            var ticket_id = $(this).attr('ticket_id');
            $("#report_item_id").val(iId);
            $("#report_ticket_id").val(ticket_id);

            // Call ajax
            $.ajax({
                type: "POST",
                url: site_server + "/youaudit/iwa/faults/ajaxfetchItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id,
                success: function(data) {
                    console.log('I am result');
                    $("#report_fault #item_manu").val(data.item_manu_name);
                    $("#report_fault #manufacturer").val(data.manufacturer);
                    $("#report_fault #serial_number").val(data.serial_number);
                    $("#report_fault #categoryname").val(data.categoryname);
                    $("#report_fault #locationname").val(data.locationname);
                    $("#report_fault #itemstatusname").val(data.itemstatusname);

                    $("#report_fault  #status").find('option').each(function(i, opt) {
                        if (opt.value == data.itemstatusid) {
                            $(opt).attr('selected', 'selected');
                        }

                    });

                    $("#report_fault  #fix_code").find('option').each(function(i, opt) {
                        if (opt.value == data.fix_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#report_fault #action").find('option').each(function(i, opt) {

                        if (opt.value == data.ticket_action) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#report_fault #reason_code").find('option').each(function(i, opt) {

                        if (opt.value == data.reason_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });
                    $("#report_fault #job_notes").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax

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
                     if (data.allPhoto != null) {
                        var updatePhoto = data.allPhoto.split(',');
                    }
                    else {
                        var updatePhoto = data.photoid.split(',');
                    }
                                      if (updatePhoto.length != 0) {
                        $('.fault_photo1').css('display', 'block');


                        $("#photo_div_update").empty();
                        for (var i = 0; i < updatePhoto.length; i++) {
                            var img_div = '';
                            img_div += "<div style='float:left' class='ui-lightbox-gallery'>";
                            img_div += "<div class='image_single'>";
                            img_div += "<img width='65' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='" + base_url + "/index.php/images/viewList/" + updatePhoto[i] + "'>";
                            img_div += "</div></div>";
                            $("#photo_div_update").append(img_div);
                        }
                    }
                    else
                    {
                        $('.fault_photo1').css('display', 'none');
                    }
                    
                    
                    $("#update_fault #job_notes").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax

        });


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
                        if (data.allPhoto != null) {
                        var updatePhoto = data.allPhoto.split(',');
                    }
                    else {
                        var updatePhoto = data.photoid.split(',');
                    }
                                      if (updatePhoto.length != 0) {
                        $('.fault_photo2').css('display', 'block');


                        $("#photo_div_resolve").empty();
                        for (var i = 0; i < updatePhoto.length; i++) {
                            var img_div = '';
                            img_div += "<div style='float:left' class='ui-lightbox-gallery'>";
                            img_div += "<div class='image_single'>";
                            img_div += "<img width='65' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='" + base_url + "/index.php/images/viewList/" + updatePhoto[i] + "'>";
                            img_div += "</div></div>";
                            $("#photo_div_resolve").append(img_div);
                        }
                    }
                    else
                    {
                        $('.fault_photo2').css('display', 'none');
                    }

                    $("#fix_item #job_notes").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax


        });

        $("#report_fault_form").validate({
            rules: {
                fix_code: "required",
                status: "required",
                order: {required: true},
                job_notes: "required"
            },
            messages: {
                fix_code: "Please Enter Severity",
                status: "Please Enter Status",
                order: "Please Select order",
                job_notes: "Please Enter Job Note"
            }
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
        $("#resolve_multiplefault").validate({
            rules: {
                multiple_fix_code: {required: true},
                       },
            messages: {
                multiple_fix_code: "Please Select Fix Code",
            }
        });

        var open_job = $("#open_jobs").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 20,
            "bSortCellsTop": true,
            "sScrollX": "100%",
            "bScrollCollapse": false,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnDrawCallback": function() {
                var api = this.api();
                var rowCount = $('#open_jobs tbody tr').length;

                $(api.column(3).footer()).html(
                        rowCount
                        );


            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter","bSortable": false, "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter","bSortable": false, "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [9]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [10]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [11]},
                {"sClass": "eamil_conform aligncenter", "aTargets": 12},
                {"sClass": "eamil_conform aligncenter", "aTargets": [13]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [14]},
                {"sClass": "eamil_conform aligncenter","aTargets": [15]},
                {"sClass": "eamil_conform aligncenter",  "aTargets": [16]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [17]},
            ]}

        );
      //$(".dataTable thead tr:eq(1) th:eq(0)").html('<input id="selectAll" type="checkbox" title="Select ALL"><button id="multiComEditBtn" class="btn btn-warning fade hide" style="padding:0 5px;" type="button">Edit</button>');
        $(".dataTable thead tr:eq(1) th").each(function(i) {
            if (i == 4) {

                var select = $('<select class="categorylist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 5) {

                var select = $('<select class="itemmanu"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 6) {

                var select = $('<select class="manufacturer"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 7) {

                var select = $('<select class="sitelist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 8) {

                var select = $('<select class="locations"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 9) {

                var select = $('<select class="ownerlist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 10) {

                var select = $('<select class="statuslist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 11) {

                var select = $('<select class="actionlist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 14) {

                var select = $('<select class="severity"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    open_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                open_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
        });

//        var fix_job = $("#fix_jobs").DataTable({
//            "ordering": true,
//            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
//            "iDisplayLength": 10,
//            "bSortCellsTop": true,
//            "bDestroy": true, //!!!--- for remove data table warning.
//            "fnDrawCallback": function() {
//                var api = this.api();
//                var rowCount = $('#fix_jobs tbody tr').length;
//
//                $(api.column(3).footer()).html(
//                        rowCount
//                        );
//
//
//            },
//            "aoColumnDefs": [
//                {"sClass": "eamil_conform aligncenter","aTargets": [0]},
//                {"sClass": "eamil_conform aligncenter","aTargets": [1]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
//                {"sClass": "eamil_conform aligncenter","aTargets": [5]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [9]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [10]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [11]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": 12},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [13]},
//                {"sClass": "eamil_conform aligncenter","aTargets": [14]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [15]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [16]},
//                {"sClass": "eamil_conform aligncenter", "aTargets": [17]},
//            ]}
//
//        );
        
//        $("#fix_jobs thead tr:eq(1) th").each(function(i) {
//
//            if (i == 2) {
//
//                var select = $('<select class="categorylist"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 3) {
//
//                var select = $('<select class="itemmanu"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 4) {
//
//                var select = $('<select class="manufacturer"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 6) {
//
//                var select = $('<select class="sitelist"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 7) {
//
//                var select = $('<select class="locations"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 8) {
//
//                var select = $('<select class="ownerlist"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 9) {
//
//                var select = $('<select class="statuslist"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 10) {
//
//                var select = $('<select class="actionlist"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//            if (i == 13) {
//
//                var select = $('<select class="severity"><option value=""></option></select>')
//                        .appendTo($(this).empty())
//                        .on('change', function() {
//                    var val = $(this).val();
//                    fix_job.column(i)
//                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
//                            .draw();
//                });
//                fix_job.column(i).data().unique().sort().each(function(d, j) {
//                    if (d != "") {
//                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
//                    }
//                });
//            }
//        });
        // Multiple Checked For Open Jobs
        $('body').find('.multiComSelect:checked').prop('checked', false);
        $('body').find('#selectAll').prop('checked', false);
        $('body').on('click', '.multiComSelect', function() {
            if ($('html').find('.multiComSelect:checked').length)
            {
                $('#multiComEditBtn').addClass('in').removeClass('hide');
                if ($('html').find('.multiComSelect:not(:checked)').length == 0)
                    $('#selectAll').prop('checked', true);
            } else {
                $('#multiComEditBtn').addClass('hide').removeClass('in');
                $('#selectAll').prop('checked', false);
            }
        });

        $('body').on('click', '#selectAll', function() {
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

            $('#open_jobs').find('input[type="checkbox"]:checked').each(function() {

                ids.push($(this).attr('value'));
            });
            console.log(ids);
            $('#multiComIds').val(ids.join(','));
            $('#multiUserEditModal').modal('show');
        });


        // Multiple Checked
        $('body').find('.multiResolveSelect:checked').prop('checked', false);
        $('body').find('#resolveAll').prop('checked', false);
        $('body').on('click', '.multiResolveSelect', function() {
            if ($('html').find('.multiResolveSelect:checked').length)
            {
                $('#multiResolveBtn').addClass('in').removeClass('hide');
                if ($('html').find('.multiResolveSelect:not(:checked)').length == 0)
                    $('#resolveAll').prop('checked', true);
            } else {
                $('#multiResolveBtn').addClass('hide').removeClass('in');
                $('#resolveAll').prop('checked', false);
            }
        });

        $('body').on('click', '#resolveAll', function() {
            if ($(this).is(':checked')) {
                $('.multiResolveSelect').prop('checked', true);
                $('#multiResolveBtn').addClass('in').removeClass('hide');
            }
            else {
                $('.multiResolveSelect').prop('checked', false);
                $('#multiResolveBtn').addClass('hide').removeClass('in');
            }
        });
        $('#multiResolveBtn').on('click', function() {

            var ids = [];
            var cat_ids = [];

            $('#open_jobs').find('input[type="checkbox"]:checked').each(function() {

                ids.push($(this).attr('value'));
            });
            console.log(ids);
            $('#multiResolveIds').val(ids.join(','));
            $('#multiResolveIncident').modal('show');
        });


    // button for add image
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

    });
</script>


<div class="row">
    <h1>Incidents - Current Open Jobs</h1>
</div>

<div class="row">
    <div style="margin-top: 10px;" class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-md-7">
                    <a href="<?= base_url('faults/exportPDFForFaults/CSV') ?>" type="button" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to CSV</b>
                    </a>

                    <a  href="<?= base_url('faults/exportPDFForFaults/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to PDF</b></a>

                    <a class="button icon-with-text round" href="<?php echo site_url('/faults/'); ?>" id="clearfilter">
                        <i class="glyphicon glyphicon-repeat"></i>
                        <b>Clear Filter</b></a>
                </div
                ><div class="col-md-5 text-right">
                    <span class="com-name">                     <?= $arrSessionData['objSystemUser']->accountname; ?>
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
    </div>
</div>
<div id="current_fault">
    <div class="row">
        <div class="col-lg-12">

            <div class="panel-body">

                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper"  style=" overflow:hidden">
                        <table id="open_jobs" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">

                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Resolve Multiple Assets</th>
                                    <th>QR Code</th>
                                    <th>Photos</th>
                                    <th>Category</th>
                                    <th>Item</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Site</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                    <th>Incident Type</th>
                                    <th>Stage</th>
                                    <th>Fault Date </th>
                                    <th>Incident Length </th>
                                    <th>Severity</th>
                                    <th>Order No</th>
                                    <th>Job Notes</th>
                                    <th class="fault-action">Actions</th>
                                </tr>
                                <tr>
                                    <th>
                                        <input id="selectAll" type="checkbox" title="Select ALL">
                                        <button id="multiComEditBtn" class="btn btn-warning fade hide" style="padding:0 5px;" type="button">Edit</button>
                                    </th>
                                    <th>
                                        <input id="resolveAll" type="checkbox" title="Select ALL">
                                        <button id="multiResolveBtn" class="btn btn-warning fade hide" type="button">Resolve</button>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th> 
                                </tr>
                            </thead>


                            <tbody id="asset_body">
                                <?php
                                if (!empty($current_job)) {

                                    foreach ($current_job as $value) {
                                        //echo "<pre>"; print_R($value);// die;
                                        if ($value->ticket_id > 0) {
                                            $ticket_id = $value->ticket_id;
                                        } else {
                                            $ticket_id = 0;
                                        }
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" value="<?php echo $ticket_id; ?>" class="multiComSelect"><input class="" type="hidden" id="customer_id_<?php echo $ticket_id; ?>" value=""></td>
                                            <td><input type="checkbox" value="<?php echo $ticket_id; ?>" class="multiResolveSelect"><input class="" type="hidden" id="customer_id_<?php echo $ticket_id; ?>" value=""></td>
                                            <td><a href="<?php echo base_url('items/view/' . $value->itemid); ?>"><?php echo $value->barcode; ?></td>
                                            <td>
                                                <?php
                                                
                                                 $image_role = '';
                                                $url_contain = base_url();

                                                if ($value->photoid != '') {
                                                    if (strpos($value->photoid, ',') !== FALSE) {
                                                        $image_arr = explode(',',$value->photoid);
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
                                                        $photoid =$value->photoid;
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
                                            <td><?php echo $value->categoryname; ?></td>
                                            <td><?php echo $value->item_manu_name; ?></td>
                                            <td><?php echo $value->manufacturer; ?></td>
                                            <td><?php echo $value->model; ?></td>
                                            <td><?php echo $value->sitename; ?></td>
                                            <td><?php echo $value->locationname; ?></td>
                                            <td><?php echo $value->owner_name; ?></td>
                                            <td><?php echo $value->statusname; ?></td>
                                            <td><?php echo $value->ticket_action; ?></td>
                                            <?php
                                            if ($value->dt) {
                                                $arr_date = explode(' ', $value->dt);
//                                                    echo $arr_date[0]; 
                                            }
                                            ?>
                                            <td><?php echo date('d/m/Y', strtotime($arr_date[0])); ?></td>

                                            <td><?php
                                                if (isset($value->dt)) {
                                                    $date2 = date('d-m-Y', strtotime($value->dt));
                                                    $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                                                    $diff = abs(strtotime($date2) - strtotime($date1));

                                                    $days = floor($diff / 3600 / 24);
                                                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                                                    echo $months . ' month ' . $days . ' days ';
                                                }
                                                ?></td>
                                            <td><?php echo $value->severity ?></td>
                                            <td><?php echo $value->order_no; ?></td>
                                            <td><?php echo ellipsize($value->jobnote, 50); ?></td>

                                            <td class="fault-action"><span class="action-w"><a  id="itm_<?php echo $value->itemid ?>" item_status="<?php echo $value->statusname; ?>" ticket_id = "<?php echo $ticket_id ?>" account_id="<?php echo $value->account_id ?>"  id="" data-toggle="modal" href="#update_fault" data_customer_id='' title="Update Fault" class="updatefault" data-val="<?php echo $value->ticket_action; ?>"><i class="fa  fa-recycle franchises-i"></i></a>Update Incident</span><span class="action-w"><a  ticket_id = "<?php echo $ticket_id ?>" id="itm_<?php echo $value->itemid ?>" account_id="<?php echo $value->account_id ?>"  data-toggle="modal" href="#fix_item" title="Fix item" class="fixitem" data_customer_id='' data-val="<?php echo $value->ticket_action; ?>"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Resolve Incident</span><span class="action-w"><a data-toggle="modal" actionmode="reportfault"  ticket_id = "<?php echo $ticket_id ?>"  id="itm_<?php echo $value->itemid ?>" account_id="<?php echo $value->account_id ?>" data-val="<?php echo $value->ticket_action; ?>" href="#view_fault" title="View Fault" class="viewfault" data_customer_id=''><i class="fa fa-eye franchises-i"></i></a>View Incident</span></td>
                                        </tr>
                                        <?php
                                    } // End of foreach
                                } // End of if
                                ?>	

                            </tbody>
                            <tfoot><tr><th colspan="3">Totals / Count</th>
                                    <th></th>
                                    <th colspan="13"></th>
                                </tr></tfoot>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>  
    </div> 
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="report_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Report Fault</h4>
            </div>

            <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/fixfault" method="post" id="report_fault_form">
                <input type="hidden" name="report_item_id" id="report_item_id" value="" />
                <input type="hidden" name="report_ticket_id" id="report_ticket_id" value="" />
                <input type="hidden" name="mode" id="mode" value="reportFault" />
                <div class="modal-body">
                    <!-- Report Fault -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Report Fault</h4></label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Item Menu</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="item_manu" id="item_manu">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Manufacturer</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="manufacturer" id="manufacturer">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>QR CODE</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="serial_number" id="serial_number">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category</label> </div>
                        <div class="col-md-6">  <input readonly class="form-control" name="categoryname" id="categoryname">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Location</label> </div>
                        <div class="col-md-6"><input readonly class="form-control" name="locationname" id="locationname">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Severity</label> </div>
                        <div class="col-md-6">
                            <select class="form-control" name="severity" id="severity">
                                <option value="Low">Low<option>
                                <option value="Normal">Normal<option>
                                <option value="High">High<option>
                                <option value="Critical">Critical<option>
                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Status</label> </div>
                        <div class="col-md-6"><input  class="form-control" name="itemstatusname" id="itemstatusname">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Enter Order No</label> </div>
                        <div class="col-md-6"><input type="text" name="order_no" class="form-control" id="order_no" value="" /></div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                    </div>

                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" style="display:none;" type="submit" id="save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>
<!-- View Fault Model -->
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
                    <div class="col-md-6">  <label>Item</label> </div>
                    <div class="col-md-6">  <input readonly class="form-control" name="item_manu" id="v_item_manu">
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
                                <div class="form-group col-md-12 fault_loggedBy">
                    <div class="col-md-6"><label>Incident Logged By</label>   </div>
                    <div class="col-md-6" > <input type="text"  id="loggedBy_div" class="form-control" value="" disabled="" /></div>


                </div> 
                <div class="form-group col-md-12 fault_loggedByDate">
                    <div class="col-md-6"><label>Incident Logged Date</label>   </div>
                    <div class="col-md-6" > <input type="text"  id="loggedByDate_div" class="form-control" value="" disabled="" /></div>


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
                    <div class="col-md-6 job_notes_div">
                    </div>
                </div>
                <div class="form-group col-md-12" >

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
<!-- Fix Item Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="fix_item" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Fix Item</h4>
            </div>

            <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/fixfault" method="post" id="fix_item_form" enctype="multipart/form-data">
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
                       <div class="form-group col-md-12 fault_photo2">
                    <div class="col-md-6"><label>Photos</label>   </div>
                    <div class="col-md-6" id="photo_div_resolve"> </div>
                </div>
                       <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Photo</label>
                        </div>
                        <div class="col-md-6 input_fields_wrap"> </br>
                            <button class="btn btn-primary btn-circle btn-xs add_field_button" id="img_button" title="add more image" type="button"><i class="glyphicon glyphicon-plus"></i></button>    
                            <div><input class="fileupload upload form-control" type="file" name="photo_file_1" size="20"></div>

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
<!-- Update Fault Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="update_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Update Incident</h4>
            </div>

            <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/fixfault" method="post" id="update_fault_form" enctype="multipart/form-data">
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
                 <div class="form-group col-md-12 fault_photo1">
                    <div class="col-md-6"><label>Photos</label>   </div>
                    <div class="col-md-6" id="photo_div_update"> </div>
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
<!--Edit multiple USer Credentials-->
<div class="modal fade" id="multiUserEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Update Multiple Jobs</h4>
            </div>
            <form action="<?php echo base_url('faults/editMultipleFaults'); ?>" method="post" id="edit_multiplefault">
                <div class="modal-body" style="min-height:100px; overflow: auto;">
                    <input hidden="" name="ticket_id" id="multiComIds">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Reason Code</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_reason_code" id="multiple_reason_code" class="form-control">
                                <option value="">Select</option>
                                <option value="Waiting for parts">Waiting for parts</option>
                                <option value="Need more time">Need more time</option>
                                <option value="Specialist Equipment Rqd">Specialist Equipment Rqd</option>
                                <option  value="H&S requirements">H&S requirements</option>	
                            </select>
                        </div>
                    </div> 
                    
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Job Note</label>
                        </div>
                        <div class="col-md-6">       
                            <input class="form-control" name="multiple_job_note">
                        </div>
                    </div> 

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button_system">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Resolve multiple incident-->
<div class="modal fade" id="multiResolveIncident" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Resolve Multiple Incidents</h4>
            </div>
            <form action="<?php echo base_url('faults/resolveMultipleIncidents'); ?>" method="post" id="resolve_multiplefault">
                <div class="modal-body" style="min-height:100px; overflow: auto;">
                    <input hidden="" name="ticket_id" id="multiResolveIds">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Fix Code</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_fix_code" id="multiple_fix_code" class="form-control">
                                <option value="" selected="selected">Select</option>
                                <option value="Repaired no parts">Repaired no parts</option>
                                <option value="Replaced Parts">Replaced Parts</option>
                                <option value="Reset System">Reset System</option>
                                <option value="Serviced">Serviced</option>
                                <option value="Found Asset">Found Asset</option>
                                <option value="Changed Consumables">Changed Consumables</option>		
                            </select>
                        </div>
                    </div> 
                    
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Job Note</label>
                        </div>
                        <div class="col-md-6">       
                            <input class="form-control" name="multiple_job_note">
                        </div>
                    </div> 

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button_system">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
