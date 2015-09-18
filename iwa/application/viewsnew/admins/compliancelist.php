<?php // var_dump($allCompliances);  ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .DTTT_container{
        display: block;
    }
    .DTTT_container a{
        margin-left: 4px;
    }
    .compliance_box_top
    {
        min-height: 95px;
    }
    #compliance_list a.submit_b{
        font-size: 14px;
        font-weight: bold;
    }
    .modal-body .row
    {
        margin: 10px 0px;
    }
    .task_row > td {
        padding: 5px;
    }
    #newTasks_table{
        width: 100%;
    }
    #export_csv{
        min-width: 20%;
    }
    #export_csv a{
        float: left;
        margin-left: 5px;
    }
    .table.dataTable tfoot th{
        padding: 0px !important;
    }
    input#selectAllchk {
        margin-right: 5px;
        position: relative;
    }
    .bootbox .modal-dialog{
        width: 300px;
    }
</style>
<div class="heading">
    <h1>List of Safety Checks</h1>

</div>
<div class="box_content">
    <div class="ver_tabs" style="">
        <a class="" href="<?php echo base_url('admins/complianceChecks'); ?>" class="active"><span>Customer Template</span></a>
        <a class="active" href="<?php echo base_url('admins/compliancesList'); ?>"><span>List Of Safety Check </span></a>
    </div> 
    <div class="content_main">


        <div id="list" class="form_block">

            <table id="compliance_list" class="list_table">
                <thead>
                    <tr>
                        <th data-export="false">Select</th>
                        <th data-export="true">Safety Name</th>

                        <th data-export="true">Frequency</th>
                        <th data-export="true">Mandatory</th>
                        <th data-export="false">No of Checks/Tasks</th>


                        <th hidden=""></th>
                        <th hidden=""></th>
                        <th data-export="false" class="">Actions</th>
                        <th hidden=""></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="padding:8px;"><input type="checkbox" title="Select ALL" id="selectAllchk"><button type="button" id="multiComEditBtn" class="btn btn-warning fade hide" style="padding:0 5px;" onclick="multiComInit()">Edit</button></th>
                        <th>Safety Name</th>

                        <th>Frequency</th>
                        <th>Mandatory</th>
                        <th>No of Checks/Tasks</th>



                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ($allCompliances as $test) { ?>
                        <tr data-value="<?php print $test['cid']; ?>">
                            <td><input class="multiComSelect" type="checkbox" value="<?php print $test['cid']; ?>"></td>
                            <td><?php print $test['Compliance_check_name']; ?></td>

                            <td><?php print $test['freq_name']; ?> </td>
                            <td><?php print ($test['mandatory'] == 1) ? "Yes" : "No"; ?></td>
                            <td> <span style="float:left;"><?php print $test['total_tasks']; ?> </span><?php if ($test['total_tasks']) { ?><a class='submit_b' href="javascript:getAllTasks('<?php print $test['tasks']; ?>')">&nbsp;></a><!--form style="width:50px;float:left;" method="post" action="<?php echo base_url('compliance/listalltasks'); ?>"><input value="<?php print $test['tasks']; ?>" hidden="" name="tasks"><input class="submit_b" title="View Tasks" type="submit" value=">"></form--><?php } ?> </td>

                            <td hidden=""><?php print $test['cid']; ?></td>
                            <td hidden=""><?php print $test['tasks']; ?></td>
                            <td class="">

                                <a href="#complianceEditModal" class="edit_b" data-toggle="modal"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                                <a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-id="<?php echo $test['cid']; ?>"data-href="<?php echo site_url() . 'compliance/removeTemplate/' . $test['cid']; ?>"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/icons/16/erase.png" title="Remove" alt="Remove"></a>

                            </td>
                            <td hidden=""><?php print $test['frequency']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>
    <!--Compliance Edit Modal-->
    <div class="modal fade" id="complianceEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo base_url() ?>admins/editTemplateCompliance" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Edit Safety</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input name="compliance_check_id" hidden="">
                            <label class="col-md-4">Safety Name</label><input name="compliance_check_name" class="col-md-6">
                        </div>
                        <!--                        <div class="row">
                                                    <label class="col-md-4">Category</label>
                                                    <select class="col-md-6" name="category">
                        <?php foreach ($categories['results'] as $category) { ?>
                                                                    <option value="<?php print $category->categoryid; ?>" data-selector="<?php print $category->categoryname; ?>"><?php print $category->categoryname; ?></option>
                        <?php } ?>
                                                    </select>
                                                </div>-->
                        <div class="row">
                            <label class="col-md-4">Mandatory</label>
                            <select name="mandatory" class="col-md-6">
                                <option data-selector='Yes' value="1">Yes</option>
                                <option data-selector='No' value="0">No</option>
                            </select>
                        </div>
                        <!--                        <div class="row">
                                                    <label class="col-md-4">Active</label>
                                                    <select name="active" class="col-md-6">
                                                        <option data-selector='Yes' value="1">Yes</option>
                                                        <option data-selector='No' value="0">No</option>
                                                    </select>
                                                </div>-->
                        <div class="row">
                            <label class="col-md-4">Frequency</label>
                            <select name="frequency" class="col-md-6">
                                <?php foreach ($frequencies as $frequency) { ?>
                                    <option value="<?php print $frequency['test_freq_id']; ?>" data-selector="<?php print $frequency['test_frequency']; ?>"><?php print $frequency['test_frequency']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <!--                        <div class="row">
                                                    <label class="col-md-4">Manager of Check</label>
                                                    <select name="manager_of_check" class="col-md-6">
                                                        <option value="0">Not Set</option>
                        <?php foreach ($arrUsers['results'] as $arrUser) { ?>
                                                                        <option data-selector="<?php print $arrUser->userfirstname . " " . $arrUser->userlastname; ?>" value="<?php print $arrUser->userid; ?>" > <?php print $arrUser->userfirstname . " " . $arrUser->userlastname; ?></option>
                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <label class="col-md-4">Start Date of Check</label><input name="start_of_task" class="col-md-6 datepicker">
                                                </div>
                                                <div class="row">
                                                    <label class="col-md-4">Reminder</label>
                                                    <select name='reminder' class="col-md-6">
                                                        <option data-selector='Yes' value="1">Yes</option>
                                                        <option data-selector='No' value="0">No</option>
                                                    </select>
                                                </div>-->
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/add.jpg" title="add" alt="add" /><a href="javascript:add_row();" >Add Task</a>
                            </div>
                        </div>
                        <div class="row">
                            <!--<input hidden="" name="oldDeletedTasks" id="oldDeletedTasks">-->
                            <table id="newTasks_table">
                                <input id="task_details" name="task_details" hidden="true">
                                <tbody><tr><td colspan="3"><img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span></td></tr></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" onclick="return beforeTestEdit()" class="btn btn-warning">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="multiComplianceEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo base_url() ?>admins/editMultiTemplateCompliance" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Edit Multiple Safety</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input hidden="" name="compliances_id" id="multiComIds">

                        </div>
                        <div class="row">
                            <label class="col-md-4">Mandatory</label>
                            <select name="mandatory" class="col-md-6">
                                <option value="">-- Please Select --</option>
                                <option data-selector='Yes' value="1">Yes</option>
                                <option data-selector='No' value="0">No</option>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Frequency</label>
                            <select name="frequency" class="col-md-6">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($frequencies as $frequency) { ?>
                                    <option value="<?php print $frequency['test_freq_id']; ?>" data-selector="<?php print $frequency['test_frequency']; ?>"><?php print $frequency['test_frequency']; ?></option>
                                <?php } ?>
                            </select>
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
</div>
<script>
                            $(document).ready(function() {
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
                            });
                            function multiComInit() {

                            }
                            function beforeMultipleEdit()
                            {

                            }
</script>

<script>
    $('#compliance_list').find('td:empty').html('&nbsp;');
    var options = '';
<?php foreach ($allMeasurements as $key => $value) { ?>
        options += '<option value="<?php echo $value['id']; ?>"><?php echo $value['measurement_name']; ?></option>';
<?php } ?>
    $(document).ready(function() {
        var colCount = 0;
        var arr = [];
        $('#compliance_list thead tr th').each(function() {
            if ($(this).attr("hidden") || $(this).attr('data-export') == 'false') {

            } else {
                arr.push($(this).index());
            }
        });

//                arr.pop();
        console.log(arr);

        var table = $('#compliance_list').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
//                    "dom": 'T<"clear">lfrtip',
//                    "tableTools": {
//                        "aButtons": [{
//                                "sExtends": "csv",
//                                "mColumns": arr
//                            },
////                            {
////                                "sExtends": "pdf",
////                                "mColumns": arr
////                            }
//                        ],
//                        "sSwfPath": "<?php echo base_url(); ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
//                    }
        });


        exportButtonSetup();


        $('.dataTables_length').appendTo('.due_table_contents');
//        $('.dataTables_paginate').appendTo('.due_table_contents');
//            $('.dataTables_filter').remove();


        $("#compliance_list tfoot th").each(function(i) {
            if (i == 1) {
                var select = $('<select><option value="">Reset Filter</option></select>')
                        .appendTo($(this).empty())
                        .on('change', function() {
                    if ($(this).val() != '')
                    {
//                            console.log(table);
                        table.column(i)
                                .search('^' + $(this).val() + '$', true, false)
                                .draw();
                    }
                    else {
//                            console.log(table);
                        $('.dataTables_length').prependTo('.dataTables_wrapper');
                        table.destroy();
                        table = $('#compliance_list').DataTable({
                            "pagingType": "full_numbers",
                            "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
//                                "dom": 'T<"clear">lfrtip',
//                                "tableTools": {
//                                    "aButtons": [{
//                                            "sExtends": "csv",
//                                            "mColumns": arr
//                                        },
////                                        {
////                                            "sExtends": "pdf",
////                                            "mColumns": arr
////                                        }
//                                    ],
//                                    "sSwfPath": "<?php echo base_url(); ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
//                                }
                        });
                        exportButtonSetup();
                        $('.dataTables_length').appendTo('.due_table_contents');
                    }
                });

                table.column(i).data().unique().sort().each(function(d, j) {
                    if (d != '')
                        select.append('<option value="' + d + '">' + d + '</option>');
                });
            }
            else {
                if (i != 0)
                    $(this).html("&nbsp;");
            }
        });
//----------------Clear Filter--------------
        $('#clearFilter').on('click', function() {
            $('.dataTable').find('tfoot th select option[value=""]').prop('selected', true);
            $('.dataTables_length').prependTo('.dataTables_wrapper');
            table.destroy();
            table = $('#compliance_list').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
//                "dom": 'T<"clear">lfrtip',
//                "tableTools": {
//                    "aButtons": [{
//                            "sExtends": "csv",
//                            "mColumns": arr
//                        },
//    //                                        {
//    //                                            "sExtends": "pdf",
//    //                                            "mColumns": arr
//    //                                        }
//                    ],
//                    "sSwfPath": "<?php echo base_url(); ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
//                }
            });
            exportButtonSetup();
            $('.dataTables_length').appendTo('.due_table_contents');
        });


//-----------------Row Selector-------------
        $('body').on('click', '.multiComSelect', function() {
            $(this).parent('td').parent('tr').toggleClass('selected');
        });

//----------------MultiSelect Button Click-----------
        $('#multiComEditBtn').on('click', function() {
            var ids = [];
            $.each(table.rows('.selected').nodes(), function() {
                ids.push($(this).attr('data-value'));
            });
            console.log(ids);
            $('#multiComIds').val(ids.join(','));
            $('#multiComplianceEditModal').find('select option[value=""]').prop('selected', true);
            $('#multiComplianceEditModal').modal('show');
        });
//------------Select All check----------
        $('body').on('click', '#selectAllchk', function() {
            if ($(this).is(':checked')) {
                table.$('tr', {"filter": "applied"}).each(function() {
                    $(this).addClass('selected').find('.multiComSelect').prop('checked', true);
                });
                $('#multiComEditBtn').addClass('in').removeClass('hide');
            } else {
                table.$('tr', {"filter": "applied"}).each(function() {
                    $(this).removeClass('selected').find('.multiComSelect').prop('checked', false);
                });
                $('#multiComEditBtn').addClass('hide').removeClass('in');
            }
        });




        $('body').on('click', '.edit_b', function() {
            var row = $(this).parent('td').parent('tr');
            var rowData = table.row(row).data();
            console.log(rowData);
            var name = rowData[1];
            var id = rowData[5];
            var frequency = rowData[2];
            var mandatory = rowData[4];
            var tasks = rowData[6];
            $('input[name="compliance_check_id"]').val(id);
            $('input[name="compliance_check_name"]').val(name);
            $('select[name="mandatory"] option[data-selector="' + mandatory + '"]').prop('selected', true);
            $('select[name="frequency"] option[data-selector="' + frequency + '"]').prop('selected', true);
            $('#newTasks_table tbody').html('');
            $('#complianceEditModal').find('button[type="submit"]').prop('disabled', true);
            $('#newTasks_table tbody').html('<tr><td colspan="3"><img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span></td></tr>');

            $.ajax({
                url: "<?php echo base_url('compliance/listAllTasksJson'); ?>",
                data: {'tasks': tasks},
                type: 'post',
                success: function(data) {
//                        alert(JSON.stringify(data));
                    $('#newTasks_table tbody').html('');
                    data = JSON.parse(data);
//                        alert(data);
                    var tasks = '';
                    $('#complianceEditModal').find('button[type="submit"]').prop('disabled', false);
                    $.each(data, function(k, v) {

                        tasks += '<tr class="task_row" data-new="false"><td><input class="task_name_input" placeholder="Task Name"  data-id="' + v['id'] + '" type="text" value="' + v['task_name'] + '" /></td><td>Type: <select class="check_type"><option value="0">Standard</option><option value="1">Numerical</option></select></td> <td><select disabled="true" class="add_measurements"><option value="0">Select Measurement</option>' + options + '</select></td><td><img src="/img/icons/16/erase.png" data-task="' + v['id'] + '" class="remove_old_task" title="Remove" alt="Remove"></td></tr>';
                    });

                    $('#newTasks_table tbody').html(tasks);
                    var i = 0;
                    $.each(data, function(k, v) {
                        $('#newTasks_table').find('.task_row').eq(i).find('.check_type option[value="' + v['type_of_task'] + '"]').prop('selected', true);
                        $('#newTasks_table').find('.task_row').eq(i).find('.add_measurements option[value="' + v['measurement'] + '"]').prop('selected', true);
                        if (v['measurement'] > 0) {
                            $('#newTasks_table').find('.task_row').eq(i).find('.add_measurements').prop('disabled', false);
                        }
                        i++;
                    });
                },
                error: function(data) {
                    $('#complianceEditModal').find('button[type="submit"]').prop('disabled', false);
                    alert(JSON.stringify(data));
                }
            });
        });

        //-----Exporting pdf--------
        $('#exportPdfButton').on('click', function(e) {
            var data = table
                    .data()
                    .map(function(row) {
                //                    console.log(row);
                var rowArr = [];
                $.each(arr, function(i, v) {
                    rowArr.push(row[v]);
                });
                return '<td>' + rowArr.join('</td><td>') + '</td>';
            })
                    .join('</tr><tr>');
            data = '<tbody><tr>' + data + '</tr></tbody>';
            var cloneHead = [];
            var head = $('#compliance_list thead').clone();
            head.find('th[data-export="true"]').each(function(i) {
                console.log($(this).html());
                cloneHead.push($(this).html());
            });
            cloneHead = '<thead><tr><th>' + cloneHead.join('</th><th>') + '</th></tr></thead>';

            console.log(cloneHead);
            $('#exp_table_content').val(cloneHead + data);
            $('#export_form').submit();
        });
        //-----------------

        $('body').on('change', '.check_type', function() {

            if (+$(this).val()) {
                $(this).parent('td').next('td').children('select').prop('disabled', false);
            }
            else {
                $(this).parent('td').next('td').children('select').prop('disabled', true);
            }
        });

        setTimeout(function() {
            $('#compliance_list').wrap('<div style="width:100%;overflow-x:auto;min-height:300px;background:#fff;"/>');
        }, 1000);

//----------------------modal Task Remove Code------------------
        $('body').on('click', '.remove_task', function() {
            if (confirm('Are you sure?')) {
                $(this).parent().parent('tr').remove();
            } else {
                // Do nothing!
            }

        });
        $('body').on('click', '.remove_old_task', function() {
            if (confirm('Are you sure?')) {
                $(this).parent().parent('tr').html('<input hidden="" name="oldDeletedTask[]" value="' + $(this).attr('data-task') + '">').removeClass('task_row');
            } else {
                // Do nothing!
            }

        });


    });

    function add_row() {
        $('#newTasks_table tbody').append('<tr class="task_row" data-new="true" ><td><input class="task_name_input" placeholder="Task Name"  data-id="0" type="text" /></td><td>Type: <select class="check_type"><option value="0">Standard</option><option value="1">Numerical</option></select></td> <td><select disabled="true" class="add_measurements"><option value="0">Select Measurement</option>' + options + '</select></td><td><img src="/img/icons/16/erase.png" class="remove_task" title="Remove" alt="Remove"></td></tr>');
    }

    function beforeTestEdit() {
        var tasksArr = [], tasksStr;
        $.each($(document).find('.task_row'), function() {
            var on_status = $(this).attr('data-new');
            var taskName = $(this).find('.task_name_input').val();
            var taskId = $(this).find('.task_name_input').attr('data-id');
            var taskType = $(this).find('select.check_type').val();
            var taskMeasure = $(this).find('select.add_measurements').val();
            console.log(on_status + '|' + taskName + '|' + taskId + '|' + taskType + '|' + taskMeasure);
            var temp = on_status + '|' + taskName + '|' + taskId + '|' + taskType + '|' + taskMeasure;
            tasksArr.push(temp);
        });
        console.log(tasksArr);
        tasksStr = tasksArr.join(',');
        $('#task_details').val(tasksStr);
    }

    function exportButtonSetup() {
        $(document).find(".DTTT_container").prependTo('#export_csv');
        $(document).find(".DTTT_button.DTTT_button_pdf").addClass('button').text('Export to ' + $(document).find(".DTTT_button.DTTT_button_pdf").text());
        setTimeout(function() {
            var width = $('.DTTT_container').outerWidth() / 2;
            $(document).find(".DTTT_button.DTTT_button_pdf div").css({'left': width + 'px', 'margin-left': '4px'});
        }, 2000);
        $(document).find(".DTTT_button.DTTT_button_csv").addClass('button').text('Export to ' + $(document).find(".DTTT_button.DTTT_button_csv").text());
    }
</script>
<script>
    $(function() {
        $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
    });

//        -----------All Task Listing-------------
    function getAllTasks(tasks) {
        $('#allTasksModal').modal('show');
        $('#allTasksModal').find('tbody').html('<tr><td colspan="3"><img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span></td></tr>');
        $.ajax({
            url: "<?php echo base_url('compliance/listAllTasksJson'); ?>",
            data: {'tasks': tasks},
            type: 'post',
            success: function(data) {

                data = JSON.parse(data);

                $('#allTasksModal').find('tbody').html('');
                $.each(data, function(k, v) {
                    var type = '', mn = '';
                    if (data[k]['type_of_task'] == '1') {
                        type = 'Numerical';
                    } else {
                        type = 'Standard';
                    }
                    if (data[k]['measurement_name']) {
                        mn = data[k]['measurement_name'];
                    } else {
                        mn = 'NA';
                    }
                    $('#allTasksModal').find('tbody').append('<tr><td><strong>' + data[k]['task_name'] + '</strong></td><td>' + type + '</td><td>' + mn + '</td></tr>');
                });


//             '<tr><td><strong><?php // print $value['task_name'];   ?></strong></td><td><?php // ($value['type_of_task'])?print('Numerical'):print('Standard');   ?></td><td><?php // ($value['measurement_name']!='')?print($value['measurement_name']):print('NA');   ?></td></tr>'
            },
            error: function(data) {
//             alert(JSON.stringify(data));
            }
        });
    }


    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');
        bootbox.confirm("Are you sure?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
</script>




<!--All Tasks Modal--> 

<div class="modal fade" id="allTasksModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Checks/Tasks List</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <table class="list_table">
                        <thead>
                            <tr>
                                <th class="left">Task/Check Name</th>
                                <th class="left">Type of Task</th>
                                <th class="left">Measurement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan='3'><img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span></td></tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>