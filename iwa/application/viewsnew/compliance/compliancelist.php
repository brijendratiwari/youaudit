<?php // print "<pre>"; print_r($allCompliances); print "</pre>"; ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/brochure/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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
        min-height: 55px;
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
        min-width: 40%;
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
    <h1>List of Compliance Checks</h1>
    <div class="buttons">
        
        
    </div>
</div>
<div class="box_content">
    <div class="ver_tabs">
        <a class="" href="<?php  echo base_url('compliance');  ?>" class="active"><span>Compliance Checks Due</span></a>
      <a class="" href="<?php  echo base_url('compliance/complianceshistory');  ?>"><span>Compliance History</span></a>
      <a class="active" href="#"><span>List of Compliance Checks</span></a>
      <a class="" href="<?php  echo base_url('compliance/compliancesadmin');  ?>"><span>Compliance Admin</span></a>
      <a class="" href="<?php echo base_url('compliance/adhoc'); ?>"><span>Complete Adhoc Checks</span></a>
      <a class="" href="<?php echo base_url('compliance/templates'); ?>"><span>Templates</span></a>
      <a class="" href="<?php echo base_url('compliance/report'); ?>"><span>Report</span></a>
    </div>
    <div class="content_main">
        <div class="compliance_box_top">
            <div class="due_filter">
                
            </div>
            
            

            <div class="button_holder">
                <div id="export_csv">
                    <a class="button" id="exportCsvButton" href="#">Export as CSV</a>
                    <a class="button" id="exportPdfButton" href="#">Export as PDF</a>
                    <a href="javascript:void(0)" id="clearFilter" class="button">Clear Filter</a>
                    <a class="button" href="<?php  echo base_url('compliance/compliancesadmin');  ?>" ><!--img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/add.jpg" title="add" alt="add" /--> Add Check</a>
                </div>
                <div class="buttons">
                    
                        <!--<a href="#" class="button">Confirm</a>-->
                </div>
            </div>
            <div class="due_table_contents">
                <!--<input id="goto_page" style="float: right;" type="number">-->
            </div>
        </div>

        <div id="list" class="form_block">

            <table id="compliance_list" class="list_table">
                <thead>
                    <tr>
                        <th data-export="false">Select</th>
                        <th data-export="true">Compliance Name</th>
                        <th data-export="true">Category</th>
                        <th data-export="true">Frequency</th>
                        <th data-export="true">Mandatory</th>
                        <th data-export="false">No of Checks/Tasks</th>
                        <th data-export="true">Manager of Check</th>
                        <th data-export="true">Reminder</th>
                        <th data-export="true">Start Date</th>
                        <th data-export="true">Active</th>
                        <th hidden="">Active</th>
                        <th data-export="false" class="">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="padding:8px;"><input type="checkbox" title="Select ALL" id="selectAllchk"><button type="button" id="multiComEditBtn" class="btn btn-warning fade hide" style="padding:0 5px;" onclick="multiComInit()">Edit</button></th>
                        <th>Compliance Name</th>
                        <th>Category</th>
                        <th>Frequency</th>
                        <th>Mandatory</th>
                        <th>No of Checks/Tasks</th>
                        <th>Manager of Check</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach ( $allCompliances as $test) { ?>
                    <tr data-value="<?php print $test['test_type_id']; ?>">
                       <td><input class="multiComSelect" type="checkbox" value="<?php print $test['test_type_id']; ?>"></td>
                       <td><?php print $test['test_type_name']; ?></td>
                       <td><?php print $test['category']; ?> </td>
                       <td><?php print $test['frequency']; ?> </td>
                       <td><?php print ($test['mandatory'] == 1) ? "Yes" : "No"; ?></td>
                       <td> <span style="float:left;"><?php print $test['total_tasks']; ?> </span><?php if($test['total_tasks']){ ?><a class='submit_b' href="javascript:getAllTasks('<?php print $test['tasks']; ?>')">&nbsp;></a><!--form style="width:50px;float:left;" method="post" action="<?php  echo base_url('compliance/listalltasks'); ?>"><input value="<?php print $test['tasks']; ?>" hidden="" name="tasks"><input class="submit_b" title="View Tasks" type="submit" value=">"></form--><?php }?> </td>
                       <td><?php print $test['firstname'].' '.$test['lastname']; ?> </td>
                       <td><?php print ($test['reminder'] == 1) ? "Yes" : "No"; ?></td>
                       <td><?php if($test['start_of_check']!=''){print date('d/m/Y', strtotime($test['start_of_check']));}else{echo '';}?></td>
                       <td><?php print ($test['active'] == 1) ? "Yes" : "No"; ?></td>
                       <td hidden=""><?php print $test['test_type_id']; ?></td>
                       <td class="">
                            <!--<a href="<?php echo base_url('')?>/compliance/view/<?php print $test['test_type_id']; ?>"><img src="/img/icons/16/view.png" title="View Vehicle" alt="View Vehicle"></a>-->
                            <a href="#complianceEditModal" class="edit_b" data-toggle="modal"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                            <a href="javascript:void(0)" class="archieve_compliance" data-href="<?php echo base_url();?>compliance/archieve/<?php print $test['test_type_id']; ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                       </td>
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
                <form action="<?php echo base_url()?>compliance/editcompliance" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Edit Compliance</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input name="compliance_check_id" hidden="">
                            <label class="col-md-4">Compliance Name</label><input name="compliance_check_name" class="col-md-6">
                        </div>
                        <div class="row">
                            <label class="col-md-4">Category</label>
                            <select class="col-md-6" name="category">
                                <?php foreach ($categories['results'] as $category) { ?>
                                    <option value="<?php print $category->categoryid; ?>" data-selector="<?php print $category->categoryname; ?>"><?php print $category->categoryname; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Mandatory</label>
                            <select name="mandatory" class="col-md-6">
                                <option data-selector='Yes' value="1">Yes</option>
                                <option data-selector='No' value="0">No</option>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Active</label>
                            <select name="active" class="col-md-6">
                                <option data-selector='Yes' value="1">Yes</option>
                                <option data-selector='No' value="0">No</option>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Frequency</label>
                            <select name="frequency" class="col-md-6">
                                    <?php foreach ($frequencies as $frequency) { ?>
                                        <option value="<?php print $frequency['test_freq_id']; ?>" data-selector="<?php print $frequency['test_frequency']; ?>"><?php print $frequency['test_frequency']; ?></option>
                                    <?php } ?>
                            </select>
                        </div>
                        <div class="row">
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
                        </div>
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
                <form action="<?php echo base_url()?>compliance/editmulticompliance" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Edit Multiple Compliance</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input hidden="" name="compliances_id" id="multiComIds">
                            <label class="col-md-4">Category</label>
                            <select class="col-md-6" name="category">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($categories['results'] as $category) { ?>
                                    <option value="<?php print $category->categoryid; ?>" data-selector="<?php print $category->categoryname; ?>"><?php print $category->categoryname; ?></option>
                                <?php } ?>
                            </select>
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
                        <div class="row">
                            <label class="col-md-4">Manager of Check</label>
                            <select name="manager_of_check" class="col-md-6">
                                <option value="">-- Please Select --</option>
                                    <?php foreach ($arrUsers['results'] as $arrUser) { ?>
                                        <option data-selector="<?php print $arrUser->userfirstname . " " . $arrUser->userlastname; ?>" value="<?php print $arrUser->userid; ?>" > <?php print $arrUser->userfirstname . " " . $arrUser->userlastname; ?></option>
                                    <?php } ?>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Active</label>
                            <select name="active" class="col-md-6">
                                <option value="">-- Please Select --</option>
                                <option data-selector='Yes' value="1">Yes</option>
                                <option data-selector='No' value="0">No</option>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Reminder</label>
                            <select name='reminder' class="col-md-6">
                                <option value="">-- Please Select --</option>
                                <option data-selector='Yes' value="1">Yes</option>
                                <option data-selector='No' value="0">No</option>
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
    <script>
        $(document).ready(function(){
            $('body').find('.multiComSelect:checked').prop('checked',false);
            $('body').find('#selectAllchk').prop('checked',false);
            $('body').on('click','.multiComSelect',function(){
                if($('html').find('.multiComSelect:checked').length)
                {
                    $('#multiComEditBtn').addClass('in').removeClass('hide');
                    if($('html').find('.multiComSelect:not(:checked)').length == 0)
                        $('#selectAllchk').prop('checked',true);
                }else{
                    $('#multiComEditBtn').addClass('hide').removeClass('in');
                    $('#selectAllchk').prop('checked',false);
                }
            }); 
        });
        function multiComInit(){
            
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
            <?php }?>
        $(document).ready(function() {
                var colCount =0;
                var arr = [];
                $('#compliance_list thead tr th').each(function() {
                    if($(this).attr("hidden") || $(this).attr('data-export')=='false'){
                        
                    }else{
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
//                        "sSwfPath": "<?php echo base_url();   ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
//                    }
                });
            
            
                exportButtonSetup();
                

            $('.dataTables_length').appendTo('.due_table_contents');
            $('.dataTables_filter').appendTo('.due_table_contents');
//            $('.dataTables_filter').remove();


            $("#compliance_list tfoot th").each( function ( i ) {
              if(i>=1 && i!=5 && i<7){
                var select = $('<select><option value="">Reset Filter</option></select>')
                    .appendTo( $(this).empty() )
                    .on( 'change', function () {
                        if($(this).val()!='')
                        {
//                            console.log(table);
                            table.column( i )
                            .search( '^'+$(this).val()+'$', true, false )
                            .draw();
                        }
                        else{
//                            console.log(table);
                            $('.dataTables_length').prependTo('.dataTables_wrapper');
                            $('.dataTables_filter').prependTo('.dataTables_wrapper');
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
//                                    "sSwfPath": "<?php echo base_url();   ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
//                                }
                            });
                            exportButtonSetup();
                            $('.dataTables_length').appendTo('.due_table_contents');
                            $('.dataTables_filter').appendTo('.due_table_contents');
                        }
                    } );

                table.column( i ).data().unique().sort().each( function ( d, j ) {
                    if( d != '')
                        select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
            }
            else{
                    if(i!=0)
                        $(this).html("&nbsp;");
                }
            } );
//----------------Clear Filter--------------
        $('#clearFilter').on('click',function(){
            $('.dataTable').find('tfoot th select option[value=""]').prop('selected',true);
            $('.dataTables_length').prependTo('.dataTables_wrapper');
            $('.dataTables_filter').prependTo('.dataTables_wrapper');
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
//                    "sSwfPath": "<?php echo base_url();   ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
//                }
            });
            exportButtonSetup();
            $('.dataTables_length').appendTo('.due_table_contents');
            $('.dataTables_filter').appendTo('.due_table_contents');
        });
        
        
//-----------------Row Selector-------------
            $('body').on('click','.multiComSelect',function(){
                $(this).parent('td').parent('tr').toggleClass('selected');
            });
            
//----------------MultiSelect Button Click-----------
            $('#multiComEditBtn').on('click',function(){
                var ids = [];
                $.each(table.rows('.selected').nodes(),function(){
                    ids.push($(this).attr('data-value'));
                });
                console.log(ids);
                $('#multiComIds').val(ids.join(','));
                $('#multiComplianceEditModal').find('select option[value=""]').prop('selected',true);
                $('#multiComplianceEditModal').modal('show');
            });
//------------Select All check----------
            $('body').on('click','#selectAllchk',function(){
                if($(this).is(':checked')){
                    table.$('tr', {"filter":"applied"}).each(function(){
                        $(this).addClass('selected').find('.multiComSelect').prop('checked',true);
                    });
                    $('#multiComEditBtn').addClass('in').removeClass('hide');
                }else{
                    table.$('tr', {"filter":"applied"}).each(function(){
                        $(this).removeClass('selected').find('.multiComSelect').prop('checked',false);
                    });
                    $('#multiComEditBtn').addClass('hide').removeClass('in');
                }
            });
            
            
            
            
            $('body').on('click','.edit_b',function(){
                var row = $(this).parent('td').parent('tr');
                var rowData = table.row( row ).data();
                console.log(rowData);
                var name = rowData[1];
                var id = rowData[10];
                var category = rowData[2];
                var frequency = rowData[3];
                var mandatory = rowData[4];
                var manager = rowData[6];
                var reminder = rowData[7];
                var start_date = rowData[8];
                var active = rowData[9];
                $('input[name="compliance_check_id"]').val(id);
                $('input[name="compliance_check_name"]').val(name);
                $('select[name="category"] option[data-selector="'+category+'"]').prop('selected',true);
                $('select[name="mandatory"] option[data-selector="'+mandatory+'"]').prop('selected',true);
                $('select[name="active"] option[data-selector="'+active+'"]').prop('selected',true);
                $('select[name="frequency"] option[data-selector="'+frequency+'"]').prop('selected',true);
                $('select[name="manager_of_check"] option[data-selector="'+manager+'"]').prop('selected',true);
                $('select[name="reminder"] option[data-selector="'+reminder+'"]').prop('selected',true);
                $('input[name="start_of_task"]').val(start_date);
                $('#newTasks_table tbody').html('');
                $('#complianceEditModal').find('button[type="submit"]').prop('disabled',true);
                $('#newTasks_table tbody').html('<tr><td colspan="3"><img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span></td></tr>');
                $.ajax({
                    url:"<?php echo base_url('compliance/getTasks');?>",
                    type:'post',
                    data:{'complianceId':id},
                    success:function(data){
//                        alert(JSON.stringify(data));
                        $('#newTasks_table tbody').html('');
                        data = JSON.parse(data);
                        var tasks = '';
                        $('#complianceEditModal').find('button[type="submit"]').prop('disabled',false);
                        $.each(data,function(k,v){
                            
                            tasks += '<tr class="task_row" data-new="false"><td><input class="task_name_input" placeholder="Task Name"  data-id="'+v['id']+'" type="text" value="'+v['task_name']+'" /></td><td>Type: <select class="check_type"><option value="0">Standard</option><option value="1">Numerical</option></select></td> <td><select disabled="true" class="add_measurements"><option value="0">Select Measurement</option>'+options+'</select></td><td><img src="/img/icons/16/erase.png" data-task="'+v['id']+'" class="remove_old_task" title="Remove" alt="Remove"></td></tr>';
                        });
                        
                        $('#newTasks_table tbody').html(tasks);
                        var i = 0;
                        $.each(data,function(k,v){
                           $('#newTasks_table').find('.task_row').eq(i).find('.check_type option[value="'+v['type_of_task']+'"]').prop('selected',true);
                           $('#newTasks_table').find('.task_row').eq(i).find('.add_measurements option[value="'+v['measurement']+'"]').prop('selected',true);
                           if(v['measurement'] > 0){
                                $('#newTasks_table').find('.task_row').eq(i).find('.add_measurements').prop('disabled',false);
                            }
                           i++;
                        });
                    },
                    error:function(data){
                        $('#complianceEditModal').find('button[type="submit"]').prop('disabled',false);
                        alert(JSON.stringify(data));
                    }
                });
            });
            
            //-----Exporting pdf--------
                $('#exportPdfButton').on( 'click', function (e) {
                    var data1 = $("#compliance_list").dataTable()._('tr', {"filter": "applied"});
                    var data = data1.map( function (row) {
        //                    console.log(row);
                            var rowArr = [];
                            $.each(arr,function(i,v){
                                rowArr.push(row[v]);
                            });
                            return '<td>'+rowArr.join('</td><td>')+'</td>';
                        } )
                        .join( '</tr><tr>' );
                    data = '<tbody><tr>'+data+'</tr></tbody>';
                    var cloneHead = [];
                    var head = $('#compliance_list thead').clone();
                    head.find('th[data-export="true"]').each(function(i){
                        console.log($(this).html());
                            cloneHead.push($(this).html());
                    });
                    cloneHead = '<thead><tr><th>'+cloneHead.join('</th><th>')+'</th></tr></thead>';

                    console.log(cloneHead);
                    $('#exp_table_content').val(cloneHead+data);
                    $('#export_form').submit();
                } );
                //-----------------
                
                // ----------CSV Export----------------        
        $('#exportCsvButton').on( 'click', function (e) {
             var data1 = $("#compliance_list").dataTable()._('tr', {"filter": "applied"});

            var data = data1.map( function (row) {
//                    console.log(row);
                    var rowArr = [];
                    $.each(arr,function(i,v){
                        rowArr.push(row[v]);
                    });
                    return rowArr.join(',');
                } ).join( '|' );
//                console.log(data);
            var cloneHead = [];
            var head = $('#compliance_list thead').clone();
            head.find('th[data-export="true"]').each(function(i){
//                console.log($(this).html());
                    cloneHead.push($(this).html());
            });
            cloneHead = cloneHead.join(',');
            
//            alert(cloneHead+data);
            $('#csv_table_content').val(cloneHead+'|'+data);
            $('#export_csv_form').submit();
        } );
//  ----------------------              
            
            $('body').on('change','.check_type',function(){

                    if(+$(this).val()){
                        $(this).parent('td').next('td').children('select').prop('disabled',false);
                    }
                    else{
                        $(this).parent('td').next('td').children('select').prop('disabled',true);
                    }
                });

            setTimeout(function(){$('#compliance_list').wrap('<div style="width:100%;overflow-x:auto;min-height:300px;background:#fff;"/>');},1000);
            
//----------------------modal Task Remove Code------------------
            $('body').on('click','.remove_task',function(){
                if($(document).find('#newTasks_table').find('.task_row').length > 1){
                    var thisrow = $(this);
                    bootbox.confirm("Are you sure?", function(result) {
                        if (result) {
                            thisrow.parent().parent('tr').remove();
                        } else {
                            // Do nothing!
                        }
                    });
                }else{
                    bootbox.alert('Atleast one check is required.');
                }
                
            });
            $('body').on('click','.remove_old_task',function(){
                if($(document).find('#newTasks_table').find('.task_row').length > 1){
                    var thisrow = $(this);
                    bootbox.confirm("Are you sure?", function(result) {
                        if (result) {
                            thisrow.parent().parent('tr').html('<input hidden="" name="oldDeletedTask[]" value="'+thisrow.attr('data-task')+'">').removeClass('task_row');
                        } else {
                            // Do nothing!
                        }
                    }); 
                }else{
                    bootbox.alert('Atleast one check is required.');
                }
            });
//----------------------modal archieve Code------------------
            $('body').on('click','.archieve_compliance',function(){
                var path = $(this).attr('data-href');
                bootbox.confirm("Are you sure?", function(result) {
                    if (result) {
                        window.location.href = path;
                    } else {
                        // Do nothing!
                    }
                });
                
            });
        
    });
  
    function add_row() {
                    $('#newTasks_table tbody').append('<tr class="task_row" data-new="true" ><td><input class="task_name_input" placeholder="Task Name"  data-id="0" type="text" /></td><td>Type: <select class="check_type"><option value="0">Standard</option><option value="1">Numerical</option></select></td> <td><select disabled="true" class="add_measurements"><option value="0">Select Measurement</option>'+options+'</select></td><td><img src="/img/icons/16/erase.png" class="remove_task" title="Remove" alt="Remove"></td></tr>');
        }
        
    function beforeTestEdit(){
        var tasksArr = [],tasksStr;
        $.each($(document).find('.task_row'),function(){
            var on_status = $(this).attr('data-new');
            var taskName = $(this).find('.task_name_input').val();
            var taskId = $(this).find('.task_name_input').attr('data-id');
            var taskType = $(this).find('select.check_type').val();
            var taskMeasure = $(this).find('select.add_measurements').val();
            console.log(on_status+'|'+taskName+'|'+taskId+'|'+taskType+'|'+taskMeasure);
            var temp = on_status+'|'+taskName+'|'+taskId+'|'+taskType+'|'+taskMeasure;
            tasksArr.push(temp);
        });
        console.log(tasksArr);
        tasksStr = tasksArr.join(',');
        $('#task_details').val(tasksStr);
    }
    
    function exportButtonSetup(){
        $(document).find(".DTTT_container").prependTo('#export_csv');
        $(document).find(".DTTT_button.DTTT_button_pdf").addClass('button').text('Export to '+ $(document).find(".DTTT_button.DTTT_button_pdf").text());
        setTimeout(function(){
            var width = $('.DTTT_container').outerWidth()/2;
            $(document).find(".DTTT_button.DTTT_button_pdf div").css({'left':width+'px','margin-left':'4px'});
        },2000);
        $(document).find(".DTTT_button.DTTT_button_csv").addClass('button').text('Export to '+ $(document).find(".DTTT_button.DTTT_button_csv").text());
    }
    </script>
    <script>
        $(function() {
            $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
        });
        
//        -----------All Task Listing-------------
function getAllTasks(tasks){
    $('#allTasksModal').modal('show');
    $('#allTasksModal').find('tbody').html('<tr><td colspan="3"><img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span></td></tr>');
    $.ajax({
        url:"<?php echo base_url('compliance/listAllTasksJson');?>",
        data:{'tasks':tasks},
        type:'post',
        success:function(data){
//             alert(JSON.stringify(data));
             data = JSON.parse(data);
             $('#allTasksModal').find('tbody').html('');
             $.each(data,function(k,v){
                 var type='',mn='';
                if(data[k]['type_of_task']=='1'){type = 'Numerical';}else{ type = 'Standard';}
                if(data[k]['measurement_name']){mn = data[k]['measurement_name'];}else{ mn = 'NA';}
                $('#allTasksModal').find('tbody').append('<tr><td><strong>'+data[k]['task_name']+'</strong></td><td>'+type+'</td><td>'+mn+'</td></tr>');
             });
//             '<tr><td><strong><?php // print $value['task_name']; ?></strong></td><td><?php // ($value['type_of_task'])?print('Numerical'):print('Standard'); ?></td><td><?php // ($value['measurement_name']!='')?print($value['measurement_name']):print('NA'); ?></td></tr>'
        },
        error:function(data){
             alert(JSON.stringify(data));
        }
    });
}
    </script>
    
<form id="export_form" hidden="" action="<?php echo base_url('/compliance/exportToPdf');?>" method="post">
    <input id="exp_table_content" name="allData">
    <input name="filename" value="Compliance List">
    <input type="submit">
</form>
    
<form id="export_csv_form" hidden="" action="<?php echo base_url('/compliance/exporttocsv');?>" method="post">
    <input id="csv_table_content" name="allData">
    <input name="filename" value="Compliance List">
    <input type="submit">
</form>

    
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

<!--Prompt modal-->
<div id="prompt" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Are You Sure?</h4>
            <input hidden="" id="delete_flag" value="false">
          </div>
        <div class="modal-footer">
          <button onclick="setConfirmFlag(false)" type="button" class="btn btn-default" data-dismiss="modal">No</button>
          <button onclick="setConfirmFlag(true)" type="button" data-dismiss="modal" class="btn btn-warning">Yes</button>
        </div>
    </div>
  </div>
</div>

<script>
    function setConfirmFlag(flag){
        $('#delete_flag').val(flag);
    }
    
    function areYouSure(){
        var temp = $('#delete_flag').val();
        return temp;
    }
</script>

