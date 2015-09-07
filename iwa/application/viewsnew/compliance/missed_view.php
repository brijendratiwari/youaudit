<?php // print "<pre>"; print_r($dueTests['dueMandatory']); print "</pre>";   ?>
<script type="text/javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/iwa/includes/js/jquery-1.8.3.min.js"></script>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />


            <table  class="due_table list_table">
<!--                <thead>
                    <tr>
                        <th>Compliance id</th>
                        <th>item id</th>
                        <th data-export="true">Due Date</th>
                    </tr>

                </thead>-->
                
                <tbody>

                    <?php
                    $missedDaysArray = array(1=>1,7=>7,31=>31,90=>31,121=>45,182=>45,365=>60,730=>60,1095=>60);
                    foreach ($dueTests['dueMandatory'] as $key => $value) {
                        foreach ($value['tests'] as $test) {
//                            var_dump($test);
                            $overdue = false;
                            $day_remain = '';
                            if (is_int($test['due_ts'])) {

                                $to = date('Y/m/d', $test['due_ts']);
//                                var_dump($to);
//                                $from = '2014/07/27'; 
                                $from = date('Y/m/d', time());
                                $d1 = (date_create($to));
                                $d2 = (date_create($from));
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
                            ?>
                    <?php if($day_remain == ''){
                                $data[] = $test['test_type_id'].'|'.$value['item']->itemid;
                                
                    }}} ?>
                </tbody>

            </table>
        <script>
            $(document).ready(function() {

                    var postData = [];
                    $('html').find('tbody tr').each(function(){
                        postData.push($(this).find('td.com_id').html()+'|'+$(this).find('td.item_id').html());
                    });
                    var te = postData.join(',');
                    console.log(te);
                    $.ajax({
                     url:'<?php echo base_url('/compliance_history_cron/setMissedCron');?>',
                     type:'post',
                     data:{postData:te}
                    });
            });
            
            function cloneOverdues()
            {
                var sortArray = [];
                var now = new Date();
//                console.log(now);
                var endLimit = 7;
//                console.log(endLimit+': '+now.getDate()+'+'+endLimit+' = '+(now.getDate()+endLimit));
                var end = new Date(now.getFullYear(),(now.getMonth()),(now.getDate()+endLimit),0,0,0);
                $.each($('body').find('#due_table').find('.comRow'), function (idx) {
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
                        console.log(daysRemain+' for '+currentDate+'and freqId is: '+freqId );
                        if((daysRemain >= '-'+freq) && (daysRemain < parseInt(endLimit))){
                            if(freqId == 11){
                                if((currentDate.getDay() != 6) || (currentDate.getDay() != 0)){
//                                    console.log(currentDate.getDay());
//                                    if(daysRemain == '-1'){
//                                        if(currentDate.getDate()+1 < 9){
//                                            var mf_date = '0'+(currentDate.getDate()+1);
//                                        }
//                                        if(currentDate.getMonth()+1 < 9){
//                                            var mf_month = '0'+(currentDate.getMonth()+1);
//                                        }
                                        var clone = $(this).clone();
                                        clone.attr('data-sort',daysRemain+2);
//                                        clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(mf_date+'/'+mf_month+'/'+currentDate.getFullYear());
                                        clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').removeClass('comRow').find('td.dueCol').html(dueDate);
                                        clone.find('td.daysCol').html('<span class="dayCount">'+(daysRemain+1)+'</span> Days');
                                    }
////                                    else{
//                                        var clone = $(this).clone();
//                                        clone.attr('data-sort',daysRemain+1);
//                                        clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').find('td.dueCol').html(dueDate);
//                                        clone.find('td.daysCol').html('<span class="dayCount">'+(daysRemain+1)+'</span> Days');
////                                    }
//                                }
//                                else{
//                                }
                            }else{
                                var clone = $(this).clone();
                                clone.attr('data-sort',daysRemain+2);
                                clone.insertAfter(this).removeClass('remove').removeClass('overDueRow').removeClass('comRow').find('td.dueCol').html(dueDate);
                                clone.find('td.daysCol').html('<span class="dayCount">'+(daysRemain+1)+'</span> Days');
                                
                                
                            }
                            if( daysRemain < 0 ){
                                console.log('overdue found on date: '+currentDate);
                                clone.addClass('overDueRow');
                                clone.attr('data-sort',0);
                                clone.find('td.daysCol').html('<span class="dayCount">Overdue</span>');
                            }
                            sortArray.push(clone.attr('data-sort'));
                            
                            
                            
                        }
                        if(counter == 4)
                            break;
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
                    $('body').find('#due_table').find('.comRow[data-sort="'+v+'"]').prependTo($('#due_table').find('tbody'));
                });
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
    top: -18px;
   }
   @media screen and (-webkit-min-device-pixel-ratio:0) {
    .taskStatus {
    position: relative;
    top: 0;
   }
   }
   .remove{
       display: none;
   }
        </style>


