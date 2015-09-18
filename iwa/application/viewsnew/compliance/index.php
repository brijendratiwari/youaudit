<?php // print "<pre>"; print_r($dueTests['dueMandatory']); print "</pre>";   ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />

<div class="heading">
    <h1>SAFETY CHECKS DUE</h1>
    <div class="buttons">
        
    </div>
</div>
<div class="box_content">
   
    <div class="ver_tabs">
        <a class="active" href="#"><span>Safety Checks Due</span></a>
        <a class="" href="<?php echo base_url('compliance/complianceshistory'); ?>"><span>Safety History</span></a>
        <a class="" href="<?php echo base_url('compliance/complianceslist'); ?>"><span>List of Safety Checks</span></a>
        <a class="" href="<?php echo base_url('compliance/compliancesadmin'); ?>"><span>Safety Admin</span></a>
        <a class="" href="<?php echo base_url('compliance/adhoc'); ?>"><span>Complete Adhoc Checks</span></a>
        <a class="" href="<?php echo base_url('compliance/templates'); ?>"><span>Templates</span></a>
        <a class="" href="<?php echo base_url('compliance/report'); ?>"><span>Report</span></a>
    </div>
    <div class="content_main">
        <div class="compliance_box_top">
            <div class="due_filter">
                <h3>DUE IN NEXT</h3>
                <input class="next_due_check" name="" value="1" type="checkbox"><span>&nbsp;7 Days</span><br>
                <input class="next_due_check" name="" value="2" type="checkbox"><span>&nbsp;Month</span><br>
                <input class="next_due_check" name="" value="3" type="checkbox"><span>&nbsp;Quarter</span><br>
                <input class="next_due_check" name="" value="4" type="checkbox"><span>&nbsp;Six Month</span><br>
                <input class="next_due_check" name="" value="5" type="checkbox"><span>&nbsp;Year</span>
            </div>
            

            <div id="compliance_snapshot">
                <!--<h1 style="width: 500px; margin: auto;">COMPLIANCE CHECKS DUE</h1>-->
                <table class="list_table" style="width: 500px; margin: auto;" frame="box" rules="all">
                    <thead>
                    <th colspan="4"><h2>Safety Summary</h2></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Safety Checks Due in Next 7 Days</strong></td>
                            <!--<td><?php // print count($dueTests['dueMandatory']); ?></td>-->
                            <td id="next_7Td">0</td>
                        </tr>
                        <tr>
                            <td><strong>Safety Checks Due in Next 30 Days</strong></td>
                            <td id="next_30Td">0</td>
                        </tr>
                        <tr>
                            <td><strong>Safety Checks Overdue</strong></td>
                            <td id="overDuesTd">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="button_holder">
                <div id="export_csv">
                    <a class="" id="exportCsvButton" href="#"> <img src="<?= base_url('/img/ui-icons/csv-icon.png'); ?>" alt="..."/></a>
                    <a class="" id="exportPdfButton" href="#"> <img src="<?= base_url('/img/ui-icons/pdf-icon.png'); ?>" alt="..."/></a>
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
                        <th data-export="true">Safety Name</th>
                        <th data-export="true">QR Code</th>
                        <th data-export="true">Manufacturer</th>
                        <th data-export="true">Model</th>
                        <th data-export="true">Category</th>
                        <th data-export="true">Owner</th>
                        <th data-export="true">Location</th>
                        <th data-export="true">Site</th>
                        <th class="hider" hidden="">Safety id</th>
                        <th class="hider" hidden="">item id</th>
                        <th data-export="true">Due Date</th>
                        <th data-export="false">Days till Due</th>
                        <th data-export="true">Manager</th>
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
                <th></th>
                </tfoot>
                <tbody>

                    <?php
                    $missedDaysArray = array(1=>1,7=>7,31=>31,90=>31,121=>45,182=>45,365=>60,730=>60,1095=>60);
                    foreach ($dueTests['dueMandatory'] as $key => $value) {
//                        echo '<pre>';var_dump($value);echo '</pre>';
                        foreach ($value['tests'] as $test) {
//                            var_dump($test);
                            $overdue = false;
                            $day_remain = '';
//                            var_dump(is_int($test['due_ts']),$test['due_ts'],date('Y-m-d h:i:s',$test['due_ts']));
                            if (is_int($test['due_ts'])) {
                                
                                $to = date('Y/m/d', $test['due_ts']);
                                
//                                var_dump($test['due_ts'],$to);
//                                $from = '2015/03/05'; 
                                $from = date('Y/m/d', time());
                                $d1 = (date_create($to));
                                $d2 = (date_create($from));
//                                var_dump($d2,$d1);
                                $diff = date_diff($d2, $d1);
                                $day_remain = $diff->format('%R%a ');
//                                var_dump($test['due_ts'].' <-> '.$day_remain.'<br>');
                                if($test['test_type_frequency'] != '11')    //for mon-fri daily
                                {
                                    if ($day_remain < 0 && $diff->format('%a') == 1) {
                                        $day_remain = '<span class="dayCount">Overdue</span>';
                                        $overdue = true;
                                    } else {
                                        if ($diff->format('%R%a') >= 0){
                                            $day_remain = '<span class="dayCount">'.$diff->format('%a').'</span> Days';
                                            $sort = $diff->format('%a');
                                        }
                                        else {
                                            if($diff->format('%a') <= $missedDaysArray[$test['test_days']]){
                                                $day_remain = '<span class="dayCount">Overdue</span>';
                                                $overdue = true;
                                            }else{
                                                $day_remain = '';
                                            }
                                        }
                                    }
                                }else{
                                    $mfDay = str_replace('/','-',$from);
                                    $mfDay = date('D', strtotime($mfDay));
                                    
                                    $duefDay = str_replace('/','-',$to);
                                    $duefDay = date('D', strtotime($duefDay));
//                                    var_dump($mfDay);
                                    if(($mfDay == 'Sat' || $mfDay == 'Sun')&&($duefDay == 'Sat' || $duefDay == 'Sun'))
                                    {
                                        if($mfDay == 'Sat')
                                            $adTemp = 2;
                                        if($mfDay == 'Sun')
                                            $adTemp = 1;
                                        echo $diff->format('%a').'-'.$adTemp.'='.($adTemp-$diff->format('%a')).'<br>';
                                        $day_remain = '<span class="dayCount">'.($adTemp-$diff->format('%a')).'</span> Days';
                                        $sort = ($adTemp-$diff->format('%a'));
                                    }
                                    else{
                                        
                                        if($mfDay == 'Mon'){
//                                            var_dump($mfDay);
//                                            echo $diff->format('%a').', '.$day_remain.', '.date('d/m/Y', $test['due_ts']).'<br>';
                                            if ($day_remain < 0 && $diff->format('%a') == 3) {
                                                $day_remain = '<span class="dayCount">Overdue</span>';
                                                $overdue = true;
                                            } else {
                                                if ($diff->format('%R%a') >= 0){
                                                    $day_remain = '<span class="dayCount">'.$diff->format('%a').'</span> Days';
                                                    $sort = $diff->format('%a');
                                                }
                                                else{
                                                    if($diff->format('%a') <= $missedDaysArray[$test['test_days']]){
                                                        $day_remain = '<span class="dayCount">Overdue</span>';
                                                        $overdue = true;
                                                    }else{
                                                        $day_remain = '';
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            if ($day_remain < 0 && $diff->format('%a') == 1) {
                                                $day_remain = '<span class="dayCount">Overdue</span>';
                                                $overdue = true;
                                            } else {
                                                if ($diff->format('%R%a') >= 0){
                                                    $day_remain = '<span class="dayCount">'.$diff->format('%a').'</span> Days';
                                                    $sort = $diff->format('%a');
                                                }
                                                else{
                                                    if($diff->format('%a') <= $missedDaysArray[$test['test_days']]){
                                                        $day_remain = '<span class="dayCount">Overdue</span>';
                                                        $overdue = true;
                                                    }else{
                                                        $day_remain = '';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $day_remain = '<span class="dayCount">0</span> Days';
                            }
                            $sort = $sort+1;
                            ?>
                            <tr class="comRow <?php if($day_remain == ''){ echo 'remove';} if ($overdue) { print 'overDueRow'; }?>" data-sort="<?php if ($overdue) { print 0; }else{ print $sort;}?>" data-testdays="<?php print $test['test_days'];?>" data-testfreq="<?php print $test['test_type_frequency'];?>" >
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
                                
                                <td class="dueCol"><?php
                                    if ($test['due_ts'] != 'Now') {
                                        
                                            print date('d/m/Y', $test['due_ts']);
                                        
                                    } else {
                                        print $test['due_ts'];
                                    }
                                    ?></td>
                                <td class="daysCol"><?php ($day_remain != '') ? print $day_remain : print "-"; ?></td>
                                <td><?php print $test['manager']; ?></td>
                                <td class="freq_col hider" hidden=""><?php print $test['test_days'].','.$test['test_type_frequency']; ?></td>
                                <td class="hider" hidden=""><?php print $value['item']->categoryid; ?></td>
                                <td class="right action makeComTd <?php if($overdue == FALSE && $sort > 3){ echo 'disableCom'; }?>"><a style="font-size: 15px;padding: 5px;" class="doComplianceLink" href="#" data-backdrop="static" data-toggle="modal"><img width="30px" src="/img/complaince_check.png" alt="Complete Check"></a></td>
                            </tr>


    <?php }
}
?>
                </tbody>

            </table>
        </div>
        <script>
            
            $(document).ready(function() {
                
                $('.next_due_check[value="<?php echo $this->session->userdata('checksDue_chk'); ?>"]').prop('checked',true);
                
            //------------filter search--------
//                var filter_pos = "<?php // if(isset($this->session->userdata('filter_state'))){ echo $this->session->userdata('filter_state'); }else{ echo ',,,,,,'; } $this->session->set_userdata('filter_state',',,,,,,'); ?>";
                var filter_pos = '<?php echo $this->session->userdata('filter_state'); 
                $this->session->set_userdata('filter_state','["","","","","","",""]'); ?>';
                
                
            //----------------
            
                cloneOverdues();

                $('#due_table').find('td:empty').html('&nbsp;');
                countDues();
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
//                    "stateSave": true
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
//                                            "stateSave": true
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
                
                resume_filter(filter_pos,table);
                
                
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
//                "stateSave": true
            });
            
            exportButtonSetup();
            toggleColumns(table);

            $('.dataTables_length').appendTo('.due_table_contents');
            $('.dataTables_filter').appendTo('.due_table_contents');
         });
         
        
                
//-----Exporting pdf--------
                $('#exportPdfButton').on( 'click', function (e) {
                var data1 = $("#due_table").dataTable()._('tr', {"filter": "applied"});
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
                
// ----------CSV Export----------------        
        $('#exportCsvButton').on( 'click', function (e) {
             var data1 = $("#due_table").dataTable()._('tr', {"filter": "applied"});
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
                    return rowArr.join(',');
                } ).join( '|' );
//                console.log(data);
            var cloneHead = [];
            var head = $('#due_table thead').clone();
            head.find('th[data-export="true"]').each(function(i){
//                console.log($(this).html());
                    cloneHead.push($(this).html());
            });
            cloneHead = cloneHead.join(',');
            
//            alert(cloneHead+data);
            $('#csv_table_content').val(cloneHead+'|'+data);
            $('#export_csv_form').submit();
        } );
        
//---------------------        
                $('body').on('click', '#toggleColButton', function() {
                    toggleColumns(table);
                });
                $('body').on('click', '.doComplianceLink', function() {
                    var row = $(this).parent('td').parent('tr');
                    var rowData = table.row(row).data();
                    modalSetup(rowData);
                    rememberFilter();
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
                    window.location.href='<?php echo base_url('compliance/index');?>/'+$(this).val();
                });
                
//--------Save State                ------------
                $(window).bind('beforeunload',function(){
                    rememberFilter();
                });
                
            });
            
            function rememberFilter()
            {
                var arr = [];
                $("#due_table tfoot").find('select').each(function(){
                    arr.push($(this).val());
                });
                console.log(arr);
                $('#filter_state').val(arr.join(','));
            }
            
            function resume_filter(filter,table)
            {
                console.log(filter);
                if(filter != ''){
                    filter = JSON.parse(filter);
                    $.each(filter,function(k,v){
                        $("#due_table tfoot").find('select').eq(k).find('option[value="'+v+'"]').prop('selected',true);
                        var i = $("#due_table tfoot").find('select').eq(k).parent('th').index();
                        if(v != ''){
                            table.column(i)
                                .search('^' + v + '$', true, false)
                                .draw();
                        }
    //                    console.log(k+'=>'+v);

                    });
                }
            }
            
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
                    if(temp.toLowerCase() == 'overdue')
                        overdue++;
                });
                $('#next_7Td').html(under_7+overdue);
                $('#next_30Td').html(under_30+overdue);
                $('#overDuesTd').html(overdue);
                console.log('with in 7 day: '+under_7+' with in 30 days: '+under_30+' overdue: '+overdue);
            }

            function modalSetup(rowData) {
                console.log(rowData);
                var qrcode = rowData[1];
                var manufacturer = rowData[2];
                var model = rowData[3];
                var complianceName = rowData[0];
                var complianceId = rowData[8];
                var itemId = rowData[9];
                var dueDate = rowData[10];
                var freq = rowData[13].split(',');
                var catId = rowData[14];
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
//                $('#email_data').val(rowData);
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
                var dateArray = [0,7,31,90,181,365];
                var visibilityJSON = '{"10":0,"6":1,"11":1,"2":5,"3":6,"1":7,"7":31,"9":31,"5":31,"4":31,"12":60,"13":60}';
                var visibility = $.parseJSON(visibilityJSON);
                var sortArray = [];
                var now = new Date();
                console.log(visibility);
                var endLimit = dateArray[$('.next_due_check:checked').val()];
//                console.log(endLimit+': '+now.getDate()+'+'+endLimit+' = '+(now.getDate()+endLimit));
                var end = new Date(now.getFullYear(),(now.getMonth()),(now.getDate()+endLimit),0,0,0);
                $.each($('#due_table').find('.comRow'), function (idx) {
                    var freq = +$(this).attr('data-testdays');
                    var freqId = +$(this).attr('data-testfreq');
                    sortArray.push(+$(this).attr('data-sort'));
                    var dueOn = $(this).find('.dueCol').html();
                    
                        if(dueOn == 'Now'){
                            dueOn = now.getDate()+'/'+(now.getMonth()+1)+'/'+now.getFullYear();
                        }
                    var temp = dueOn.split('/');
                    var currentDate = new Date(parseInt(temp[2]),(parseInt(temp[1])-1),parseInt(temp[0]),0,0,0);
//                    console.log('due on: '+dueOn+',test days: '+freq);
//                    console.log(currentDate+' to '+end+', and freqId = '+freqId);
//                    console.log('row starts....'+idx);
                    var between = [];
                    var counter = 0;
//                    var lastSeen = Math.round((currentDate.getTime()-now.getTime())/(1000*60*60*24));
//                    console.log(lastSeen);
                    while (currentDate < end) {
                        
                        currentDate.setDate(currentDate.getDate() + parseInt(freq));
                        between.push(new Date(currentDate));
                        currentDate = new Date(currentDate);
                        var day = currentDate.getDate();
                        var month = currentDate.getMonth()+1;
                        if(day<=9){day = '0'+day;}
                        if(month<=9){month = '0'+month;}
                        var dueDate = day+'/'+month+'/'+currentDate.getFullYear();
                        
//                        console.log(currentDate+'-'+now+'/'+'(1000*60*60*24)'+' = '+parseInt((currentDate.getTime()-now.getTime())/(1000*60*60*24)));
                        var daysRemain = Math.round((currentDate.getTime()-now.getTime())/(1000*60*60*24));
//                        console.log(daysRemain+' for '+currentDate+'and freqId is: '+freqId );
                        if(daysRemain < visibility[freqId]){
                            if((daysRemain >= '-'+freq) && (daysRemain < parseInt(endLimit))){
    //                        if(daysRemain < parseInt(endLimit)){
                                if(freqId == 11){
                                    var daySeq = currentDate.getDay();
                                    if(daySeq != 6 && daySeq != 0){
//                                        console.log('Day is '+currentDate.getDay());
                                        if(daysRemain == '-1'){
                                            if(currentDate.getDate()+1 < 9){
                                                var mf_date = '0'+(currentDate.getDate()+1);
                                            }
                                            if(currentDate.getMonth()+1 < 9){
                                                var mf_month = '0'+(currentDate.getMonth()+1);
                                            }
                                            var clone = $(this).clone();
                                            clone.attr('data-sort',daysRemain+2);
    //                                        clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(mf_date+'/'+mf_month+'/'+currentDate.getFullYear());
                                            clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(dueDate);
                                            clone.find('td.daysCol').html('<span class="dayCount">'+(daysRemain+1)+'</span> Days');
                                            clone.find('td.disableCom').removeClass('disableCom');

                                        }
                                        else{
                                            var clone = $(this).clone();
                                            clone.attr('data-sort',daysRemain+2);
                                            clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(dueDate);
                                            clone.find('td.daysCol').html('<span class="dayCount">'+(daysRemain+1)+'</span> Days');
                                        }
                                        sortArray.push(clone.attr('data-sort'));
                                        if(clone.attr('data-sort') >= 4){
            //                                console.log(clone.attr('data-sort'));
                                            clone.find('td.makeComTd').addClass('disableCom');
                                        }
                                        if( daysRemain < -1 ){
            //                                console.log('overdue found on date: '+currentDate);
                                            clone.addClass('overDueRow');
                                            clone.attr('data-sort',0);
                                            clone.find('td.daysCol').html('<span class="dayCount">Overdue</span>');
            //                                console.log(clone.html());
                                        }
                                    }
                                    else{
                                    }
                                }else{
                                    var clone = $(this).clone();
                                    clone.attr('data-sort',daysRemain+2);
                                    clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(dueDate);
                                    clone.find('td.daysCol').html('<span class="dayCount">'+(daysRemain+1)+'</span> Days');
                                    clone.find('td.disableCom').removeClass('disableCom');
    //                                console.log(daysRemain+1);
                                    sortArray.push(clone.attr('data-sort'));
                                    if(clone.attr('data-sort') >= 4){
        //                                console.log(clone.attr('data-sort'));
                                        clone.find('td.makeComTd').addClass('disableCom');
                                    }
                                    if( daysRemain < -1 ){
        //                                console.log('overdue found on date: '+currentDate);
                                        clone.addClass('overDueRow');
                                        clone.attr('data-sort',0);
                                        clone.find('td.daysCol').html('<span class="dayCount">Overdue</span>');
        //                                console.log(clone.html());
                                    }
                                }
                                
                                
                                
    //                            console.log(currentDate+' : {last seen: '+lastSeen+' and counter is: '+counter+'}');

                            }
                        }
                        if(counter == 2 && daysRemain > 2)
                            break;
                        if(freq == 1)
                            counter++;
                        
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

            .right.action.makeComTd.disableCom .doComplianceLink{
                pointer-events: none;
                cursor: default;
                opacity: 0.7;
            }
        </style>

        <!--Compliance Modal-->
        <div class="modal fade" id="complianceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="compliance_form" action="<?php echo base_url() ?>compliance/makeComplianceCheck" method="post" >
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
                                        <label class="col-md-6">Safety Name</label></td><td colspan="2"><input id="m_comaplianceName" hidden="" name="compliance_check_name"> <span id="compliance_name" class="col-md-6">Safety Name</span></td>
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
                                <input id="filter_state" hidden="" name="filter_state">
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
                            <th><input checked="" type="checkbox" value="0"> Safety Name</th>
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
                        <tr>
                            <th><input checked="" type="checkbox" value="10"> Due Date</th>
                            <th><input checked="" type="checkbox" value="11"> Days till Due</th> 
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
<form id="export_form" class="hider" hidden="" action="<?php echo base_url('/compliance/exportToPdf');?>" method="post">
    <input id="exp_table_content" name="allData">
    <input name="filename" value="Compliance Checks Due">
    <input type="submit">
</form>

<form id="export_csv_form" class="hider" hidden="" action="<?php echo base_url('/compliance/exporttocsv');?>" method="post">
    <input id="csv_table_content" name="allData">
    <input name="filename" value="Compliance Checks Due">
    <input type="submit">
</form>

