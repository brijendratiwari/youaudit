<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<?php $this->load->helper('text'); ?>
<style>
    th {
        text-align: center;
    }
    #fix_jobs thead tr th, #fix_jobs tbody tr td, .list_table thead tr th, .list_table tfoot tr th {
        text-align: center !important;
        max-width: 150px !important;
        min-width: 150px !important;
        width: 150px !important;
    }
    #fix_jobs.tb.dataTable input {
        max-width: 100% !important;
        min-width: 100% !important;
        width: 100% !important;
    }
    /*	.fault-action, #asset_body tr td:last-child {
            max-width: 300px !important;
            min-width: 300px !important;
            width: 300px !important;
        }*/
    /*.dataTables_scrollHeadInner table thead tr:last-child { display:none}*/

    /*#open_jobs thead tr th { padding: 0!important; margin: 0!important; max-height: 0!important; min-height: 0!important;}*/




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
            var type = 'Fix';
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
                    if (data.allPhoto) {
                        $('.fault_photo').css('display', 'block');

                        var photoid = data.allPhoto.split(',');
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
                            notes_div += "<li style='list-style:none;'>"+noteDate[i]+ " - "+ allNote[i] + "</li>";

                        }
                        notes_div += "</ul>";
                    }else{
                    $(".job_notes_div").empty();
                       notes_div += "<ul><li style='list-style:none;'>" +data.loggedByDate+ " - "+ data.jobnote + "</li></ul>";
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
            var type = 'Fix';
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


        $(".fixitem").click(function() {
            var iId = $(this).attr('id');

            var account_id = $(this).attr('account_id');
            var ticket_id = $(this).attr('ticket_id');
            var type = 'Fix';
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

        var open_job = $("#open_jobs").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bSortCellsTop": true,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnDrawCallback": function() {
                var api = this.api();
                var rowCount = $('#open_jobs tbody tr').length;

                $(api.column(3).footer()).html(
                        rowCount
                        );


            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
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
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [14]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [15]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [16]},
            ]}

        );

        $("#open_jobs thead tr:eq(1) th").each(function(i) {
            if (i == 2) {

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
            if (i == 3) {

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
            if (i == 4) {

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
            if (i == 6) {

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
            if (i == 7) {

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
            if (i == 8) {

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
            if (i == 9) {

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
            if (i == 10) {

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
            if (i == 13) {

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

        var fix_job = $("#fix_jobs").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bSortCellsTop": true,
            "sScrollX": "100%",
            "bScrollCollapse": false,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnDrawCallback": function() {
                var api = this.api();
                var rowCount = $('#fix_jobs tbody tr').length;

                $(api.column(2).footer()).html(
                        rowCount
                        );


            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [9]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [10]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [11]},
                {"sClass": "eamil_conform aligncenter", "aTargets": 12},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [13]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [14]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [15]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [16]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [17]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [18]}
            ]}

        );
        // $("#fix_jobs thead tr:eq(1) th:first").html(<input id="selectAll1" type="checkbox" title="Select ALL">
        //                                       <button id="multiComEditBtn1" class="btn btn-warning fade hide" style="padding:0 5px;" type="button">Edit</button>);
        $(".dataTable thead tr:eq(1) th").each(function(i) {
            if (i == 1) {

                var select = $('<select class="categorylist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 2) {

                var select = $('<select class="itemmanu"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 3) {

                var select = $('<select class="manufacturer"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 5) {

                var select = $('<select class="sitelist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 6) {

                var select = $('<select class="locations"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 7) {

                var select = $('<select class="ownerlist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
            if (i == 8) {

                var select = $('<select class="statuslist"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
//            if (i == 9) {
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
            if (i == 11) {

                var select = $('<select class="severity"><option value=""></option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    var val = $(this).val();
                    fix_job.column(i)
                            .search(val ? '^' + $(this).val() + '$' : val, true, false)
                            .draw();
                });
                fix_job.column(i).data().unique().sort().each(function(d, j) {
                    if (d != "") {
                        select.append('<option id="level" value="' + d + '">' + d + '</option>');
                    }
                });
            }
        });
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
        $('body').find('.multiComSelect1:checked').prop('checked', false);
        $('body').find('#selectAll1').prop('checked', false);
        $('body').on('click', '.multiComSelect1', function() {
            if ($('html').find('.multiComSelect1:checked').length)
            {
                $('#multiComEditBtn1').addClass('in').removeClass('hide');
                if ($('html').find('.multiComSelect1:not(:checked)').length == 0)
                    $('#selectAll1').prop('checked', true);
            } else {
                $('#multiComEditBtn1').addClass('hide').removeClass('in');
                $('#selectAll1').prop('checked', false);
            }
        });

        $('body').on('click', '#selectAll1', function() {
            if ($(this).is(':checked')) {
                $('.multiComSelect1').prop('checked', true);
                $('#multiComEditBtn1').addClass('in').removeClass('hide');
            }
            else {
                $('.multiComSelect1').prop('checked', false);
                $('#multiComEditBtn1').addClass('hide').removeClass('in');
            }
        });
        $('#multiComEditBtn1').on('click', function() {

            var ids = [];
            var cat_ids = [];

            $('#fix_jobs').find('input[type="checkbox"]:checked').each(function() {

                ids.push($(this).attr('value'));
            });
            console.log(ids);
            $('#multiComIds').val(ids.join(','));
            $('#multiUserEditModal').modal('show');
        });

    }
    );
</script>


<div class="row">
    <h1>Fault History</h1>
</div>

<div class="row">
    <div style="margin-top: 10px;" class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-md-7">
                    <a href="<?= base_url('faults/exportPDFForFixFaults/CSV') ?>" type="button" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to CSV</b>
                    </a>

                    <a  href="<?= base_url('faults/exportPDFForFixFaults/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to PDF</b></a>

                    <a class="button icon-with-text round" href="<?php echo site_url('/faults/'); ?>" id="clearfilter">
                        <i class="glyphicon glyphicon-repeat"></i>
                        <b>Clear Filter</b></a>
                </div
                <div class="col-md-5 text-right">
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



<div id="fixed_fault">
    <div class="row">
        <div class="col-lg-12">

            <div class="panel-body">

                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="fix_jobs" class="table table-striped table-bordered table-hover table-fixed" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Category</th>
                                    <th>Item</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Site</th>
                                    <th>Location</th>
                                    <th>Owner</th>
                                    <th>Incident Type</th>
                                    <th>Fault Date</th>
                                    <th>Incident Length</th>
                                    <th>Severity</th>
                                    <th>Fix Date</th>
                                    <th>Order No</th>
                                    <th>Job Notes</th>
                                    <th>Fault Logged By</th>
                                    <th>Fix Logged By</th>
                                    <th>Fix Reason Code</th>
                                    <th class="fault-action">Actions</th>
                                </tr>

                                <tr>
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
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>


                            <tbody id="asset_body">
                                <?php
//                                var_dump($fixed_job);
                                if (!empty($fixed_job)) {

                                    foreach ($fixed_job as $value) {
                                        //echo "<pre>"; print_R($value);// die;
                                        if ($value->ticket_id > 0) {
                                            $ticket_id = $value->ticket_id;
                                        } else {
                                            $ticket_id = 0;
                                        }
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo base_url('items/view/' . $value->itemid); ?>"><?php echo $value->barcode; ?></td>
                                            <td><?php echo $value->categoryname ?></td>
                                            <td><?php echo $value->item_manu_name ?></td>
                                            <td><?php echo $value->manufacturer ?></td>
                                            <td><?php echo $value->model ?></td>
                                            <td><?php echo $value->sitename ?></td>
                                            <td><?php echo $value->locationname ?></td>
                                            <td><?php echo $value->owner_name; ?></td>
                                            <td><?php echo $value->statusname ?></td>
                                            <!--<td><?php echo $value->ticket_action; ?></td>-->

                                            <td><?php echo date('d/m/Y', strtotime($value->dt)); ?></td>
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
                                            <td><?php echo  date('d/m/Y', strtotime($value->fix_date)); ?></td>
                                            <td><?php echo $value->order_no; ?></td>
                                            <td><?php echo ellipsize($value->jobnote, 50); ?></td>
                                            <td><?php echo Faults::getUserData($value->fault_by);?></td>
                                            <td><?php echo Faults::getUserData($value->fixed_by);?></td>
                                            <td><?php echo $value->fix_code; ?></td>
                                            <td><span class="action-w"><a data-toggle="modal" actionmode="reportfault"  ticket_id = "<?php echo $ticket_id ?>"  id="itm_<?php echo $value->itemid ?>" account_id="<?php echo $value->account_id ?>" href="#view_fault" title="View Fault" class="viewfault" data_customer_id=''><i class="fa fa-eye franchises-i"></i></a>View Incident</span></td>
                                        </tr>
                                        <?php
                                    } // End of foreach
                                } // End of if
                                ?>	

                            </tbody>

                            <tfoot><tr><th colspan="3">Totals / Count</th>
                                    <th></th>
                                    <th colspan="12"></th>
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
                        <div class="col-md-6">  <input readonly="readonly" class="form-control" name="item_manu" id="item_manu">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Manufacturer</label> </div>
                        <div class="col-md-6">  <input readonly="readonly" class="form-control" name="manufacturer" id="manufacturer">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>QR CODE</label> </div>
                        <div class="col-md-6">  <input readonly="readonly" class="form-control" name="serial_number" id="serial_number">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category</label> </div>
                        <div class="col-md-6">  <input readonly="readonly" class="form-control" name="categoryname" id="categoryname">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Location</label> </div>
                        <div class="col-md-6"><input readonly="readonly" class="form-control" name="locationname" id="locationname">
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
<!-- Update Fault Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="update_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Update Fault</h4>
            </div>

            <form action="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/iwa/faults/fixfault" method="post" id="update_fault_form">
                <div class="modal-body">
                    <!-- Fix Item -->

                    <input type="hidden" name="update_item_id" id="update_item_id" value="" />
                    <input type="hidden" name="update_ticket_id" id="update_ticket_id" value="" />
                    <input type="hidden" name="mode" id="mode" value="updateFault" />

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Item</label> </div>
                        <div class="col-md-6"><input readonly="readonly" class="form-control" name="item_manu" id="uu_item_manu" disabled="">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Manufacturer</label> </div>
                        <div class="col-md-6">  <input readonly="readonly" class="form-control" name="manufacturer" id="uu_manufacturer" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>QR CODE</label> </div>
                        <div class="col-md-6">  <input readonly="readonly" class="form-control" name="serial_number" id="uu_serial_number" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category</label> </div>
                        <div class="col-md-6">  <input readonly="readonly" class="form-control" name="categoryname" id="uu_categoryname" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Location</label> </div>
                        <div class="col-md-6"><input readonly="readonly" class="form-control" name="locationname" id="uu_locationname" disabled>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Action</label> </div>
                        <div class="col-md-6"><select readonly="readonly" name="action" id="action" class="form-control" disabled>
                                <option value="0">Select</option>
                                <option value="Open job">Open job</option>
                                <option value="Fix">Fix</option>
                            </select> 
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Status</label> </div>
                        <div class="col-md-6"><select name="status" id="status" class="form-control">
                                <option value="2">Damaged</option>
                                <option value="3">Faulty</option>
                                <option value="6">Missing</option>

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
                <h4 id="myModalLabel" class="modal-title">Edit Multiple Fault</h4>
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

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button_system">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
