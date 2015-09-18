<?php // print "<pre>"; print_r($missedTests); print "</pre>"; ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<div class="heading">
    <h1>Safety History</h1>
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
</style>
<div class="box_content">
    <div class="ver_tabs">
        <a class="" href="<?php  echo base_url('index.php/compliance');  ?>"><span>Safety Checks Due</span></a>
      <a class="active" href="#"><span>Safety History</a>
      <a class="" href="<?php  echo base_url('index.php/compliance/complianceslist');  ?>"><span>List of Safety Checks</span></a>
      <a class="" href="<?php  echo base_url('index.php/compliance/compliancesadmin');  ?>"><span>Safety Admin</span></a>
      <a class="" href="<?php echo base_url('compliance/adhoc'); ?>"><span>Complete Adhoc Checks</span></a>
      <a class="" href="<?php echo base_url('compliance/templates'); ?>"><span>Templates</span></a>
      <a class="" href="<?php echo base_url('compliance/report'); ?>"><span>Report</span></a> 
    </div>
    <div class="content_main">
        <div class="compliance_box_top">
            <div class="due_filter">
                <h3>History For Last</h3>
                <input class="next_due_check" name="filter" value="1" type="checkbox">&nbsp;<span>7 Days</span><br>
                <input class="next_due_check" name="filter" value="2" type="checkbox">&nbsp;<span>Month</span><br>
                <input class="next_due_check" name="filter" value="3" type="checkbox">&nbsp;<span>Quarter</span><br>
                <input class="next_due_check" name="filter" value="4" type="checkbox">&nbsp;<span>Six Month</span><br>
                <input class="next_due_check" name="filter" value="5" type="checkbox">&nbsp;<span>Year</span><br>
                <input class="next_due_check" name="filter" value="6" type="checkbox">&nbsp;<span>All</span>
            </div>
            
            

            <div class="button_holder">
                <div id="export_csv">
                    <a class="button" id="exportCsvButton" href="#">Export as CSV</a>
                    <a class="button" id="exportPdfButton" href="#">Export as PDF</a>
                    <a href="javascript:void(0)" id="clearFilter" class="button">Clear Filter</a>
                    <a href="#chooseColumnModal" data-toggle="modal" class="button">Choose Columns</a>
                </div>
                <div class="buttons">
                        
                        <!--<a href="#" class="button">Save View</a>-->
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
                        <!--style="padding:4px 25px;"-->
                        <th data-export="false">Actions</th>
                        <th  data-export="true">QR Code</th>
                        <th data-export="true">Manufacturer</th>
                        <th data-export="true">Model</th>
                        <th data-export="true">Category</th>
                        <th data-export="true">Owner</th>
                        <th data-export="true">Location</th>
                        <th data-export="true">Site</th>
                        <th data-export="true">Safety Name</th>
                        <th data-export="true">Logged By</th>
                        <th data-export="true">Due Date</th>
                        <th data-export="true">Complete Date</th>
                        <th data-export="true">Complete Time</th>
                        <th data-export="true">Result</th>
                        <th data-export="true">No Of Task</th>
                        <th data-export="true">Tasks Failed</th>
                        <!--<th data-export="true">Notes</th>-->
                        <th class="hider" hidden=''></th>
                        <th data-export='true'>Manager</th>
                        <th data-export="false">Doc</th>
                    </tr>
                   
                </thead>
                <tfoot>
                    <th>Actions</th>
                    <th>QR Code</th>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Category</th>
                    <th>Owner</th>
                    <th>Location</th>
                    <th>Site</th>
                    <th data-thSel="true">Safety Name</th>
                    <th>Logged By</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <!--<th></th>-->
                    <th></th>
                </tfoot>
                <tbody>
                
                    <?php foreach($dueTests as $key => $value) {
//                        echo $value['account_id'].','; 
                        
//        var_dump(date('H:i', strtotime($value['test_date'])));
                        if($value['due_date'])
                            $due_date = date('d/m/Y', strtotime($value['due_date'])); // due date calculation
                        else
                            $due_date = '';
                     ?>
                    <tr>
                        <?php // echo '<pre>';var_dump($value);echo '</pre>';?>
                       <td><input hidden="" class="hider" value='<?php echo json_encode($value['tasks']); ?>'><a href="javascript:void(0)" onclick='javascript:showTasks(this,0)'><img width="20px" src="/img/icons/16/view.png"></a></td>
                       <td><a class="barcode_link" href="<?php echo base_url('items/view');?>/<?php echo $value['test_item_id']?>"><pre style="white-space: nowrap;"><?php print $value['barcode']; ?></pre></a></td>
                       <td><?php print trim($value['manufacturer']); ?></td>
                       <td><?php print trim($value['model']); ?></td>
                       <td><?php print trim($value['name']); ?> </td>
                       <td><?php print trim($value['owner_name']); ?></td>
                       <td><?php print trim($value['location_name']); ?></td>
                       <td><?php print trim($value['site_name']); ?></td>
                       <td><?php print trim($value['test_type_name']); ?></td>
                       <td><?php print trim($value['test_person']); ?></td>
                       <td><?php print $due_date?></td>
                       <td><?php (isset($value['test_date'])) ? print date('d/m/Y', strtotime($value['test_date'])) : print "Never Tested"; ?></td>
                       <td><?php if(isset($value['test_date'])){
                                    print date('h:i A', strtotime($value['test_date']));
                                }else{ print "Never Tested"; } ?></td>
                       <td><?php // if($value['result']){ 
                               $flag = TRUE;
                               $failedTaskCount = 0;
                               foreach ($value['tasks'] as $key1 => $value1) {
                                   if($value1['result'] == 0)
                                   {
                                       $failedTaskCount++;
//                                       var_dump($failedTaskCount);
                                       $flag = FALSE;
//                                       break;
                                   }
                               }
                               if($flag)
                                print 'Pass';
                               else
                                print 'Fail';
                           
//                           }else{ print 'Fail';}?></td>
                       <td><?php print $value['total_tasks']; ?></td>
                       <td><?php print $failedTaskCount;?></td>
                       <!--<td><?php // print $value['test_type_notes']; ?></td>-->
                       <td class="hider" hidden=''><?php print json_encode($value['tasks']); ?></td>
                        <td><?php print $value['manager']; ?></td>
                       <td><a class="getPdf_link" href="#"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>
                    </tr>


                    <?php } ?>
                    <?php // $i = 0;
                    if($missedTests){
                    foreach($missedTests as $key => $value) {
//                        if($i == 150)
//                            break;
//                        $i++;
                     ?>
                    <tr>
                        <?php // var_dump($value);?>
                       <td><input hidden="" value='<?php echo json_encode($value['tasks']); ?>'><a href="javascript:void(0)" onclick='javascript:showTasks(this,1)'><img width="20px" src="/img/icons/16/view.png"></a></td>
                       <td><a class="barcode_link" href="<?php echo base_url('items/view');?>/<?php echo $value['item_id']?>"><pre style="white-space: nowrap;"><?php print $value['barcode']; ?></pre></a></td>
                       <td><?php print trim($value['manufacturer']); ?></td>
                       <td><?php print trim($value['model']); ?></td>
                       <td><?php print trim($value['name']); ?> </td>
                       <td><?php print trim($value['owner_name']); ?></td>
                       <td><?php print trim($value['location_name']); ?></td>
                       <td><?php print trim($value['site_name']); ?></td>
                       <td><?php print trim($value['test_type_name']); ?></td>
                       <td><?php print trim($value['test_person']); ?></td>
                       <td><?php print date('d/m/Y',  strtotime($value['missed_on']));?></td>
                       <td></td>
                       <td></td>
                       <td>Missed</td>
                       <td><?php print $value['total_tasks']; ?></td>
                       <td></td>
                       <!--<td></td>-->
                       <td class="hider" hidden=''><?php print json_encode($value['tasks']); ?></td>
                       <td><?php print $value['manager']; ?></td>
                       <td><a class="getPdf_link" href="#"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>
                    </tr>


                    <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form id="genPdf_form" action="<?php echo site_url('compliance/generateHistoryPdf'); ?>" class="hider" method="post">
    <input id="pdfAllData" hidden="" name="allData">
    <input id="pdfTasks" hidden="" name="tasks">
</form>
      <script>
        $(document).ready(function() {
            $('#chooseColumnsForm').trigger('reset');
            $('#history_table').find('td:empty').html('&nbsp;');
            var colCount =0;
            var arr = [];
            $('#history_table thead tr th').each(function() {
                    if($(this).attr("hidden") || $(this).attr('data-export')=='false'){

                }else{
                    arr.push($(this).index());
                }
            });

//            arr.pop();
            console.log(arr);

            var table = $('#history_table').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                "order": [[ 11, "desc" ]],
                columnDefs: [
                    { type: 'date-uk', targets: 10 },
                    { type: 'date-uk', targets: 11 }
                  ]
            });


            exportButtonSetup();
            toggleColumns(table);
            
        var filtered = '';
        <?php $filter = $this->session->userdata('comHistory_chk'); ?>
        filtered = '<?php echo $filter;?>';
        console.log(filtered);
        
        if(filtered != ''){
            console.log('not null '+filtered);
            $(".next_due_check[value='"+filtered+"']").prop('checked',true);
        }
        else{
            console.log('default with null'+filtered);
            $(".next_due_check[value='1']").prop('checked',true);
        }
        
         $('body').on('click', '#toggleColButton', function() {
                    toggleColumns(table);
        });

        $('.dataTables_length').appendTo('.due_table_contents');
        $('.dataTables_filter').appendTo('.due_table_contents');
//        $('.dataTables_filter').remove();

        $("#history_table tfoot th").each( function ( i ) {
            var thsel = $(this).attr('data-thSel');
          if(i>1 && i<10 || i==13){
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
                            table = $('#history_table').DataTable({
                                "pagingType": "full_numbers",
                                "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                                "order": [[ 11, "desc" ]],
                                columnDefs: [
                                    { type: 'date-uk', targets: 10 },
                                    { type: 'date-uk', targets: 11 }
                                  ]
                            });
                            exportButtonSetup();
                            toggleColumns(table);

                            $('.dataTables_length').appendTo('.due_table_contents');
                            $('.dataTables_filter').appendTo('.due_table_contents');
                        }
                        if(thsel){
                            console.log(thsel);
                        }
                } );

            table.column( i ).data().unique().sort().each( function ( d, j ) {
                if( d != '')
                    select.append( '<option value="'+d+'">'+d+'</option>' );
            } );
        }
        else
            $(this).html("&nbsp;");
        } );
        
        setTimeout(function(){$('#history_table').wrap('<div style="width:100%;overflow-x:auto;min-height:300px;background:#fff;"/>');},1000);
        
//---------- export PDF--------------        
        
        $('#exportPdfButton').on( 'click', function (e) {
             var data1 = $("#history_table").dataTable()._('tr', {"filter": "applied"});
//             var data = table.data();
//             console.log(data1);
//             console.log(data1);
//             var data = table
//                .data()
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
            var head = $('#history_table thead').clone();
            head.find('th[data-export="true"]').each(function(i){
//                console.log($(this).html());
                    cloneHead.push($(this).html());
            });
            cloneHead = '<thead><tr class="header" style="color:white"><th>'+cloneHead.join('</th><th>')+'</th></tr></thead>';
            
//            console.log(cloneHead+data);
            $('#exp_table_content').val(cloneHead+data);
            $('#export_form').submit();
        } );
        
// ----------CSV Export----------------        
        $('#exportCsvButton').on( 'click', function (e) {
             var data1 = $("#history_table").dataTable()._('tr', {"filter": "applied"});

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
            var head = $('#history_table thead').clone();
            head.find('th[data-export="true"]').each(function(i){
//                console.log($(this).html());
                    cloneHead.push($(this).html());
            });
            cloneHead = cloneHead.join(',');
            
//            alert(cloneHead+data);
            $('#csv_table_content').val(cloneHead+'|'+data);
            $('#export_csv_form').submit();
        } );
//        --------Clear Filter-----------
         $('#clearFilter').on('click',function(){
            $('.dataTable').find('tfoot th select option[value=""]').prop('selected',true);
            $('.dataTables_length').prependTo('.dataTables_wrapper');
            $('.dataTables_filter').prependTo('.dataTables_wrapper');
            table.destroy();
            table = $('#history_table').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                "order": [[ 11, "desc" ]],
                columnDefs: [
                    { type: 'date-uk', targets: 10 },
                    { type: 'date-uk', targets: 11 }
                  ]
            });
            exportButtonSetup();
            toggleColumns(table);

            $('.dataTables_length').appendTo('.due_table_contents');
            $('.dataTables_filter').appendTo('.due_table_contents');
         });
         
//---------Single Row history pdf---------

        $('body').on("click",'.getPdf_link',function(){
            var row = $(this).parent('td').parent('tr');
            var rowData=[];
            row.find('td').each(function(){
                rowData.push($(this).html()); 
            });
//            var rowData = table.row( row ).data();
            var temp = rowData;
            temp[0]='';
            $('#genPdf_form input#pdfTasks').val(temp[16]);
            temp[16]='';
            $('#genPdf_form input#pdfAllData').val(temp);
            $('#genPdf_form').submit();
        });
        
        $(".next_due_check").click(function(){
            var chkgrp = $('.next_due_check:checked');
            chkgrp.not(this).prop('checked',false);
            var filter = [];
            $('.next_due_check:checked').each(function(){
                filter.push($(this).val());
            });
            $('#filter_in').val(filter);
            if($('.next_due_check:checked').length){
                
                $('#filter_form').submit();
            }
        });
        
    });
    
    function showTasks(obj,result)
    {
        var jsonData = $(obj).siblings('input').val();
        jsonData = JSON.parse(jsonData);
        $('#complianceTaskModal').find('tbody').html('');
        if(!$.isEmptyObject(jsonData)){
            $.each(jsonData,function(k,v){
                console.log(v['task_name']);
                console.log(v['result']);
                
                    if($.isNumeric(v['result'])){
                        if(v['result'] == 1)
                            result = 'Pass';
                        else
                            result = 'Fail';
                    }else{
                        var result = v['result'];
                    }

                $('#complianceTaskModal').find('tbody').append('<tr><td>'+v['task_name']+'</td><td class="tResult">'+result+'</td><td class="tNotes">'+v['test_notes']+'</td></tr>');

            });
            if(result == 1)
            {
                $('#complianceTaskModal').find('tbody tr td.tResult').html('Missed');
                $('#complianceTaskModal').find('tbody tr td.tNotes').html('');
            }
        }
        else{
                $('#complianceTaskModal').find('tbody').append('<tr><td colspan="3"><span>No Tasks.</span></td></tr>');            
        }
                
        $('#complianceTaskModal').modal('show');
    }
    
    
    function exportButtonSetup(){
        $(document).find('table.dataTable').wrap('<div style="width:100%;overflow-x:auto;"/>');
//        $(document).find(".DTTT_container").prependTo('#export_csv');
//        $(document).find(".DTTT_button.DTTT_button_pdf").addClass('button').text('Export to '+ $(document).find(".DTTT_button.DTTT_button_pdf").text());
//        setTimeout(function(){
//            var width = $('.DTTT_container').outerWidth()/2;
//            $(document).find(".DTTT_button.DTTT_button_pdf div").css({'left':width+'px','margin-left':'4px'});
//        },2000);
//        $(document).find(".DTTT_button.DTTT_button_csv").addClass('button').text('Export to '+ $(document).find(".DTTT_button.DTTT_button_csv").text());
    }
    
    
   
    </script>
    
    <form id="filter_form" class="hider" hidden="" action="<?php echo base_url('/compliance/complianceshistory');?>" method="post">
        <input id="filter_in" name="filter">
        <input type="submit">
    </form>
    
    <form class="hider" id="export_form" hidden="" action="<?php echo base_url('/compliance/exportToPdf');?>" method="post">
        <input id="exp_table_content" name="allData">
        <input name="filename" value="Compliance History">
        <input type="submit">
    </form>
    
    <form class="hider" id="export_csv_form" hidden="" action="<?php echo base_url('/compliance/exporttocsv');?>" method="post">
        <input id="csv_table_content" name="allData">
        <input name="filename" value="Compliance History">
        <input type="submit">
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
     
     <!--Choose Column Modal-->    
    <div id="chooseColumnModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Choose Columns</h4>
                </div>
                <div class="modal-body">
                    <form id="chooseColumnsForm">
                        <table class="chooseColumnsTable" width="100%">
                            <tr>
                                <th><input checked="" type="checkbox" value="1"> QR Code</th>
                                <th><input checked="" type="checkbox" value="2"> Manufacturer</th>
                            </tr>
                            <tr>
                                <th><input checked="" type="checkbox" value="3"> Model</th>
                                <th><input checked="" type="checkbox" value="4"> Category</th>
                            </tr>
                            <tr>
                                <th><input checked="" type="checkbox" value="5"> Owner</th>
                                <th><input checked="" type="checkbox" value="6"> Location</th>
                            </tr>
                            <tr>
                                <th><input checked="" type="checkbox" value="7"> Site</th>
                                <th><input checked="" type="checkbox" value="8"> Safety Name</th>
                            </tr>
                            <tr>
                                <th><input checked="" type="checkbox" value="9"> Logged By</th>
                                <th><input checked="" type="checkbox" value="10"> Due Date</th>
                            </tr>
                            <tr>
                                <th><input checked="" type="checkbox" value="11"> Complete Date</th>
                                <th><input checked="" type="checkbox" value="12"> Complete Time</th>
                            </tr>
                            <tr>
                                <th><input checked="" type="checkbox" value="13"> Result</th> 
                                <th><input checked="" type="checkbox" value="14"> Number of Task</th>
                                
                            </tr>
                            <tr>
                                <th><input checked="" type="checkbox" value="15"> Tasks Failed</th> 
                                <th colspan="2"><input checked="" type="checkbox" value="17"> Manager</th>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="toggleColButton" class="btn btn-warning">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleColumns(table)
        {
            var chkboxToHide = $('.chooseColumnsTable').find('input[type="checkbox"]').not(':checked');
            var chkboxAll = $('.chooseColumnsTable').find('input[type="checkbox"]');
            chkboxAll.each(function(){
                var column = table.column( $(this).val());
                column.visible(true);
            });
            chkboxToHide.each(function(){
                var column = table.column( $(this).val());
                column.visible( ! column.visible() );
            });
            $('#chooseColumnModal').modal('hide');
        }
    </script>