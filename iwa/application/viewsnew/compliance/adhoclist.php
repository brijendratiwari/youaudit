<?php // print "<pre>"; print_r($dueTests['dueMandatory']); print "</pre>";   ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />

<div class="heading">
    <h1>COMPLIANCE CHECKS DUE</h1>
    <div class="buttons">
        
    </div>
</div>
<div class="box_content">
    <div class="ver_tabs">
        <a class="" href="<?php echo base_url('compliance'); ?>" class="active"><span>Compliance Checks Due</span></a>
        <a class="" href="<?php echo base_url('compliance/complianceshistory'); ?>"><span>Compliance History</span></a>
        <a class="" href="<?php echo base_url('compliance/complianceslist'); ?>"><span>List of Compliance Checks</span></a>
        <a class="" href="<?php echo base_url('compliance/compliancesadmin'); ?>"><span>Compliance Admin</span></a>
        <a class="active" href="#"><span>Complete Adhoc Checks</span></a>
        <a class="" href="<?php echo base_url('compliance/templates'); ?>"><span>Templates</span></a>
        <a class="" href="<?php echo base_url('compliance/report'); ?>"><span>Report</span></a>
    </div>
    <div class="content_main">
        <div class="compliance_box_top">
<!--            <div class="due_filter">
                <h3>DUE IN NEXT</h3>
                <input class="next_due_check" name="" value="1" type="checkbox"><span>&nbsp;7 Days</span><br>
                <input class="next_due_check" name="" value="2" type="checkbox"><span>&nbsp;Month</span><br>
                <input class="next_due_check" name="" value="3" type="checkbox"><span>&nbsp;Quarter</span><br>
                <input class="next_due_check" name="" value="4" type="checkbox"><span>&nbsp;Six Month</span>
            </div>-->
            

<!--            <div id="compliance_snapshot">
                <h1 style="width: 500px; margin: auto;">COMPLIANCE CHECKS DUE</h1>
                <table class="list_table" style="width: 500px; margin: auto;" frame="box" rules="all">
                    <thead>
                    <th colspan="4"><h2>Compliance Summary</h2></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Compliance Checks Due in Next 7 Days</strong></td>
                            <td><?php // print count($dueTests['dueMandatory']); ?></td>
                            <td id="next_7Td">0</td>
                        </tr>
                        <tr>
                            <td><strong>Compliance Checks Due in Next 30 Days</strong></td>
                            <td id="next_30Td">0</td>
                        </tr>
                        <tr>
                            <td><strong>Compliance Checks Overdue</strong></td>
                            <td id="overDuesTd">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>-->
            <div class="button_holder">
                <div id="export_csv">

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

        <div id="due" class="form_block">
            <table id="due_table" class="list_table">
                <thead>
                    <tr>
                        <th data-export="true">Compliance Name</th>
                        <th data-export="true">QR Code</th>
                        <th data-export="true">Manufacturer</th>
                        <th data-export="true">Model</th>
                        <th data-export="true">Category</th>
                        <th data-export="true">Owner</th>
                        <th data-export="true">Location</th>
                        <th data-export="true">Site</th>
                        <th class="hider" hidden="">Compliance id</th>
                        <th class="hider" hidden="">item id</th>
                        <th class="hider" hidden=""></th>
                        <th class="hider" hidden=""></th>
                        <th data-export="false" class="right action">Complete Check</th>
                    </tr>

                </thead>
                <tfoot>
                <th></th>
                <th>QR Code</th>
                <th>Manufacturer</th>
                <th>Model</th>
                <th>Category</th>
                <th>Owner</th>
                <th>Location</th>
                <th>Site</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tfoot>
                <tbody>

                    <?php
                    foreach ($dueTests['dueMandatory'] as $key => $value) {
                        foreach ($value['tests'] as $test) {
//                            var_dump($value);
                            ?>
                            <tr class="comRow" data-testdays="<?php print $test['test_days'];?>" data-testfreq="<?php print $test['test_type_frequency'];?>" >
                                <td><?php print $test['test_type_name'] ?></td>
                                <td><?php print $value['item']->barcode; ?> </td>
                                <td><?php print $value['item']->manufacturer; ?></td>
                                <td><?php print $value['item']->model; ?></td>
                                <td><?php print $value['item']->categoryname; ?> </td>
                                <td><?php print $value['item']->owner; ?></td>
                                <td><?php print $value['item']->location; ?></td>
                                <td><?php print $value['item']->site; ?></td>
                                <td class="hider" hidden=""><?php print $test['test_type_id'] ?></td>
                                <td class="hider" hidden=""><?php print $value['item']->itemid; ?></td>

                                <td class="freq_col hider" hidden=""><?php print $test['test_days'].','.$test['test_type_frequency']; ?></td>
                                <td class="hider" hidden=""><?php print $value['item']->categoryid; ?></td>
                                <td class="right action"><a style="font-size: 15px;padding: 5px;" class="doComplianceLink" href="#" data-backdrop="static" data-toggle="modal"><img width="30px" src="/img/complaince_check.png" alt="Complete Check"></a></td>
                            </tr>


    <?php }
}
?>
                </tbody>

            </table>
        </div>
        <script>
            $(document).ready(function() {
                $('#due_table').find('td:empty').html('&nbsp;');
                $('.next_due_check[value="<?php echo $this->session->userdata('adhocChecksDue_chk'); ?>"]').prop('checked',true);
//                cloneOverdues();
//                countDues();
                $('#chooseColumnsForm').trigger('reset');
                var colCount =0;
                var arr = [];
                $('#due_table thead tr th').each(function() {
                    if($(this).attr("hidden") || $(this).attr('data-export')=='false'){
                        
                    }else{
                        arr.push($(this).index());
                    }
                });
                
//                arr.pop();
                console.log(arr);
                
                var table = $('#due_table').DataTable({
                    "pagingType": "full_numbers",
                    "aaSorting": [],
                    "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "aButtons": [{
                                "sExtends": "csv",
                                "mColumns": arr
                            },
//                            {
//                                "sExtends": "pdf",
//                                "mColumns": arr
//                            }
                        ],
                        "sSwfPath": "<?php echo base_url();   ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                    }
                });
                
                exportButtonSetup();

                $('.dataTables_length').appendTo('.due_table_contents');
                $('.dataTables_filter').appendTo('.due_table_contents');
                table.row('.remove').remove().draw();
//                cloneOverdues(table);
                //        $('.dataTables_paginate').appendTo('.due_table_contents');
//                $('.dataTables_filter').remove();

                $("#due_table tfoot th").each(function(i) {
                    if (i==0 || i > 1&& i<8) {
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
                                        $('.dataTables_filter').prependTo('.dataTables_wrapper');
                                        table.destroy();
                                        table = $('#due_table').DataTable({
                                            "pagingType": "full_numbers",
                                            "aaSorting": [],
                                            "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                                            "dom": 'T<"clear">lfrtip',
                                            "tableTools": {
                                                "aButtons": [{
                                                        "sExtends": "csv",
                                                        "mColumns": arr
                                                    },
//                                                    {
//                                                        "sExtends": "pdf",
//                                                        "mColumns": arr
//                                                    }
                                                ],
                                                "sSwfPath": "<?php echo base_url();   ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                                            }
                                        });
                                        exportButtonSetup();
                                        toggleColumns(table);
                                        
                                        $('.dataTables_length').appendTo('.due_table_contents');
                                        $('.dataTables_filter').appendTo('.due_table_contents');
                                    }
                                });

                        table.column(i).data().unique().sort().each(function(d, j) {
                            if (d != '&nbsp;')
                                select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    }
                    else
                        $(this).html("&nbsp;");
                });
                
                //        --------Clear Filter-----------
         $('#clearFilter').on('click',function(){
            $('.dataTable').find('tfoot th select option[value=""]').prop('selected',true);
            $('.dataTables_length').prependTo('.dataTables_wrapper');
            $('.dataTables_filter').prependTo('.dataTables_wrapper');
            table.destroy();
            table = $('#due_table').DataTable({
                "pagingType": "full_numbers",
//                "order": [[ 13, "desc" ]],
                "aaSorting": [],
                "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "aButtons": [{
                            "sExtends": "csv",
                            "mColumns": arr
                        },
//                                                    {
//                                                        "sExtends": "pdf",
//                                                        "mColumns": arr
//                                                    }
                    ],
                    "sSwfPath": "<?php echo base_url();   ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                }
            });
            
            exportButtonSetup();
            toggleColumns(table);

            $('.dataTables_length').appendTo('.due_table_contents');
            $('.dataTables_filter').appendTo('.due_table_contents');
         });
         
        
                
                //-----Exporting pdf--------
                $('#exportPdfButton').on( 'click', function (e) {
                    var data = table
                        .data()
                        .map( function (row) {
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
                    var head = $('#due_table thead').clone();
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
                $('body').on('click', '#toggleColButton', function() {
                    toggleColumns(table);
                });
                $('body').on('click', '.doComplianceLink', function() {
                    var row = $(this).parent('td').parent('tr');
                    var rowData = table.row(row).data();
                    modalSetup(rowData);
                });

                $('body').on('click', '.complianceName', function() {
                    if (!$(this).hasClass('complianceMeasureType')) {
                        $(this).toggleClass('failedCheck').toggleClass('passedCheck');
                        if ($(this).hasClass('failedCheck')) {
                            $(this).children('.taskStatus').html('&times;');
                        }
                        else {
                            $(this).children('.taskStatus').html('&check;');
                        }
                        $(this).siblings('.complianceNote').toggleClass('hide').toggleClass('in');
                        $(this).siblings('.complianceMesurement').toggleClass('hide').toggleClass('in');
                    }
                });
                
                $('.next_due_check').on('change',function(){
                    var chkGroup = $('.next_due_check');
                    chkGroup.not(this).prop('checked',false);
                    window.location.href='<?php echo base_url('compliance/adhoc');?>/'+$(this).val();
                });
            });
            
            function countDues()
            {
                var under_7 = 0,under_30 = 0,overdue = 0;
                $('body').find('.dayCount').each(function(){
                    var temp = $(this).html();
//                    console.log(temp);
                    if(temp <= 7)
                        under_7++;
                    if(temp <= 30)
                        under_30++;
                    if(temp == 'Overdue')
                        overdue++;
                });
                $('#next_7Td').html(under_7);
                $('#next_30Td').html(under_30);
                $('#overDuesTd').html(overdue);
                console.log('with in 7 day: '+under_7+' with in 30 days: '+under_30+' overdue: '+overdue);
            }

            function modalSetup(rowData) {
                var qrcode = rowData[1];
                var manufacturer = rowData[2];
                var model = rowData[3];
                var complianceName = rowData[0];
                var complianceId = rowData[8];
                var itemId = rowData[9];
                var dueDate = '';
                var freq = rowData[10].split(',');
                var catId = rowData[11];
                $('#item_id').val(itemId);
                $('#cat_id').val(catId);
                $('#check_id').val(complianceId);
                $('#barcodeSpan').html(qrcode);
                $('#manufacturerSpan').html(manufacturer);
                $('#modelSpan').html(model);
                $('#due_date').html(dueDate);
                $('#compliance_name').html(complianceName);
                $('#m_comaplianceName').val(complianceName);
                $('#m_duedate').val(dueDate);
                $('#m_freq').val(freq[0]);
                $('#m_freqid').val(freq[1]);
                $('#complianceModal').modal('show');
                $('#checks_td').html('');
                $('#checks_td').html('<img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span>');
                $.ajax({
                    url: '<?php echo base_url() ?>compliance/getTasks',
                    type: 'post',
                    data: {'complianceId': complianceId},
                    success: function(data) {


                        $('#checks_td').html('');
                        data = JSON.parse(data);
                        $.each(data, function(k, v) {
                            if (v['type_of_task'] == 0) {
                                var htmlString = '<div class="complianceChecks" style="width: 100%;" data-taskid="' + v['id'] + '">\n\
                            <div class="complianceName failedCheck"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/add.png">&nbsp;\n\
                                <span class="taskName">' + v['task_name'] + '</span>\n\
                                <div class="taskStatus" style="">&times;</div>\n\
                            </div>\n\
                            <div class="complianceNote fade in" style="">\n\
                                <textarea class="form-control" style="" placeholder="Write Note Here"></textarea>\n\
                            </div>\n\
                        </div>';
                            } else {
                                var htmlString = '<div class="complianceChecks" style="width: 100%;" data-taskid="' + v['id'] + '">\n\
                            <div class="complianceName complianceMeasureType"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/add.png">&nbsp;\n\
                            <span class="taskName">' + v['task_name'] + '</span>\n\
                            </div>\n\
                            <div class="complianceMeasurement fade in">\n\
                                <div class="input-group"><input class="form-control" type="text" required="required" style="" placeholder="Write Measurement"/><span class="input-group-addon">' + v['measurement_name'] + '</span></div>\n\
                            <div class="complianceNote complianceMeasureType fade in" style="">\n\
                                <textarea class="form-control" style="" placeholder="Write Note Here"></textarea></div>\n\
                            </div>\n\
                        </div>';
                            }
                            $(htmlString).appendTo('#checks_td');

                        });
                    }
                });
            }
            
            function cloneOverdues()
            {
                var dateArray = [0,4,28,87,179];
                var sortArray = [];
                var now = new Date();
                var endLimit = dateArray[$('.next_due_check:checked').val()];
                console.log(now.getDay()+'+'+endLimit+' = '+(now.getDay()+endLimit));
                var end = new Date(now.getFullYear(),(now.getMonth()+1),endLimit,0,0,0);
                $.each($('#due_table').find('.comRow'), function (idx) {
                    var freq = $(this).attr('data-testdays');
                    var freqId = +$(this).attr('data-testfreq');
                    sortArray.push(+$(this).attr('data-sort'));
                    var dueOn = $(this).find('.dueCol').html();
                    console.log('due on: '+dueOn+',test days: '+freq);
                    if(dueOn == 'Now')
                        dueOn = now.getDate()+'/'+(now.getMonth()+1)+'/'+now.getFullYear();
                    var temp = dueOn.split('/');
                    var currentDate = new Date(parseInt(temp[2]),parseInt(temp[1])-1,parseInt(temp[0]),0,0,0);
                    
                    console.log(currentDate+' to '+end+', and freqId = '+freqId);
//                    console.log('row starts....'+idx);
                    var between = [];
                    var counter = 0;
                    while (currentDate.valueOf() < end.valueOf()) {
                        
                        currentDate.setDate(currentDate.getDate() + parseInt(freq));
                        between.push(new Date(currentDate));
                        currentDate = new Date(currentDate);
                        var day = currentDate.getDate();
                        var month = currentDate.getMonth()+1;
                        if(day<=9){day = '0'+day;}
                        if(month<=9){month = '0'+month;}
                        var dueDate = day+'/'+month+'/'+currentDate.getFullYear();
                        
//                        console.log(currentDate+'-'+now+'/'+'(1000*60*60*24)'+' = '+parseInt((currentDate.getTime()-now.getTime())/(1000*60*60*24)));
                        var daysRemain = parseInt((currentDate.getTime()-now.getTime())/(1000*60*60*24));
                        if(daysRemain>=0){
//                            if(freqId == 11){
//                                if(currentDate.getDay() != 6 || currentDate.getDay() != 0){
//                                    var clone = $(this).clone();
//                                    clone.attr('data-sort',daysRemain+1);
//                                    clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(dueDate);
//                                    clone.find('td.daysCol').html('<span class="dayCount">'+daysRemain+'</span> Days');
//                                }
//                                else{
//                                    alert(currentDate);
//                                }
//                            }else{
                                var clone = $(this).clone();
                                clone.attr('data-sort',daysRemain+1);
                                clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(dueDate);
                                clone.find('td.daysCol').html('<span class="dayCount">'+daysRemain+'</span> Days');
//                            }
                            sortArray.push(daysRemain+1);
                        }
//                        if(counter == 2)
//                            break;
                        if(freq == 1)
                            counter++;
//                        console.log(currentDate+' < '+end);
                    }
                    
//                    console.log('row complete....\n\
//                        _____________________X__________________');
                });
                sortArray.sort(function(a, b){return b-a;});
                customSort(sortArray);
            }
            
            function customSort(sortArray)
            {
                $.each(sortArray,function(k,v){
                    $('#due_table').find('.comRow[data-sort="'+v+'"]').prependTo($('#due_table').find('tbody'));
                });
            }
            
            function beforeComplianceCheck()
            {
                var flag = true;
                var passedChecks = [];
                var failedChecks = [];
                var measureChecks = [];
                $.each($('#compliance_form').find('.complianceNote.fade.in textarea').not('.complianceMeasureType.fade.in textarea'), function() {
                    if ($(this).val() == '') {
                        $(this).css('border', '1px solid #DE9FAB');
                        $(this).effect('shake', {times: 3, distance: 5}, "fast");
                        flag = false;
                        return false;
                    }
                    else {
                        $(this).css('border', '1px solid lightgrey');
                    }
                });
                $.each($('#compliance_form').find('.complianceMeasurement.fade.in').find('input'), function() {
                    if ($(this).val() == '') {
                        $(this).css('border', '1px solid #DE9FAB');
                        $(this).effect('shake', {times: 3, distance: 5}, "fast");
                        flag = false;
                        return false;
                    }
                    else {
                        $(this).css('border', '1px solid lightgrey');
                    }
                });
                $.each($('#compliance_form').find('.complianceChecks .passedCheck'), function() {
                    passedChecks.push($(this).parent().attr('data-taskid'));
                });
                $.each($('#compliance_form').find('.complianceChecks .failedCheck'), function() {
                    failedChecks.push($(this).parent().attr('data-taskid') + '|' + $(this).siblings('.complianceNote').children('textarea').val());
                });
                $.each($('#compliance_form').find('.complianceChecks .complianceName.complianceMeasureType'), function() {
                    var task = $(this).parent().attr('data-taskid');
                    var measure = $(this).siblings('.complianceMeasurement').find('input').val();
                    measure += ' ' + $(this).siblings('.complianceMeasurement').find('span').html();
                    var note = $(this).siblings('.complianceMeasurement').find('.complianceNote').children('textarea').val();
                    measureChecks.push(task + '|' + measure + '|' + note);
                });
                $('#passedChecks').val(passedChecks.join(','));
                $('#failedChecks').val(failedChecks.join(','));
                $('#measureChecks').val(measureChecks.join(','));
                return flag;
            }

              function export_add(name) {
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
        <style>
            .DTTT_container{
                display: block;
            }
            .DTTT_container a{
                margin-left: 4px;
            }
            
            .complianceName{
                border: 1px solid rgb(211, 211, 211);
                border-radius: 4px;
                height: 33px;
                margin-bottom: 5px;
                padding: 5px;
                cursor: pointer;
            }
            .complianceNote textarea{
                width: 100%; max-width: 100%; min-width: 100%; margin-bottom:20px;
            }
            .input-group{
                margin-bottom: 5px;
                width: 37%;
            }
            .complianceNote{
                width: 100%; max-width: 100%; min-width: 100%;
            }
            .failedCheck{
                border-color: #DE9FAB;
                background-color: #E8A7B2;
            }
            .passedCheck{
                border-color: #80C16D;
                background-color: #C1EFB3;
            }
            .taskStatus{
                float: right; font-size: 14px; font-weight: bold;
            }
            #export_csv{
            min-width: 40%;
        }
        #export_csv a{
            float: left;
            margin-left: 5px;
        }
        table.dataTable tbody tr.overDueRow {
            background-color: #ED6059;
            color: #FFF;
        }
        .taskStatus {
    position: relative;
    /*top: -18px;*/
   }
   @media screen and (-webkit-min-device-pixel-ratio:0) {
    .taskStatus {
    position: relative;
    /*top: 0;*/
   }
   }
        .compliance_box_top {
            min-height: 70px;
        }
        </style>

        <!--Compliance Modal-->
        <div class="modal fade" id="complianceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="compliance_form" action="<?php echo base_url() ?>compliance/makeComplianceCheck" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Complete Check</h4>
                        </div>
                        <div class="modal-body">
                            <table class="list_table">
                                <tr>
                                    <th><span class="" id="barcodeSpan">--------</span></th><th><span class="" id="itemSpan">Item Name</span></th><th><span class="" id="manufacturerSpan">Manufacturer</span></th><th><span class="" id="modelSpan">Model</span></th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input id="check_id" name="compliance_check_id" hidden="">
                                        <label class="col-md-6">Compliance Name</label></td><td colspan="2"><input id="m_comaplianceName" hidden="" name="compliance_check_name"> <span id="compliance_name" class="col-md-6">Compliance Name</span></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <label class="col-md-6">Due Date</label></td><td colspan="2"><input id="m_duedate" hidden="" name="due_date"><span id="due_date" class="col-md-6">DD/MM/YYYY</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"><label class="col-md-4">Checks</label></td>
                                </tr>
                                <tr>
                                <input hidden="" name="passedChecks" id="passedChecks">
                                <input hidden="" name="failedChecks" id="failedChecks">
                                <input hidden="" name="measureChecks" id="measureChecks">
                                <input hidden="" name="item_id" id="item_id">
                                <input hidden="" name="cat_id" id="cat_id">
                                <input id="m_freq" hidden="" name="test_freq">
                                <input id="m_freqid" hidden="" name="test_freq_id">
                                <!--<input id="email_data" hidden="" name="email_data">-->
                                <td id="checks_td" colspan="4">
                                    <img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span>
                                </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" onclick="return beforeComplianceCheck()" class="btn btn-warning">Save changes</button>
                        </div>
                    </form>
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
                            <th><input checked="" type="checkbox" value="0"> Compliance Name</th>
                            <th><input checked="" type="checkbox" value="1"> QR Code</th>
                            
                        </tr>
                        <tr>
                            <th><input checked="" type="checkbox" value="2"> Manufacturer</th>
                            <th><input checked="" type="checkbox" value="3"> Model</th>
                            
                        </tr>
                        <tr>
                            <th><input checked="" type="checkbox" value="4"> Category</th>
                            <th><input checked="" type="checkbox" value="5"> Owner</th>
                            
                        </tr>
                        <tr>
                            <th><input checked="" type="checkbox" value="6"> Location</th>
                            <th><input checked="" type="checkbox" value="7"> Site</th>
                        </tr>
<!--                        <tr>
                            <th><input checked="" type="checkbox" value="10"> Due Date</th>
                            <th><input checked="" type="checkbox" value="11"> Days till Due</th> 
                        </tr>-->
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
<form id="export_form" class="hider" hidden="" action="<?php echo base_url('/compliance/exportToPdf');?>" method="post">
    <input id="exp_table_content" name="allData">
    <input type="submit">
</form>