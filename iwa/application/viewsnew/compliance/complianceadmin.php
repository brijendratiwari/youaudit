<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    form#add_compliance {
        float: left;
        margin-left: 1%;
        /*width: 34%;*/
    }
    table{ 
        /*display: block;*/ 
        float: left;
    }
    #compliance_snapshot{
        width: 100%;
    }

    #table_head {
        background: url(../../includes/img/th-bg.png) repeat-x scroll center bottom rgb(247, 247, 247); 
        border: 1px solid rgb(229, 229, 229);
        padding: 3px 10px;
        min-height: 70px;
    }
    .scroll_table {
        float: left;
/*        max-height: 500px;
        overflow-x: hidden;
        overflow-y: auto;*/
        width: 100%;
    }
    
    .scroll_table_cover {
        float: left;
        margin-left: 85px;
        width: 57%;
    }
    .ui-draggable{
        cursor: move;
    }
    .row{
        padding: 5px;
    }
    sup {
        color: rgb(254, 185, 17);
        font-size: 20px;
        text-shadow: 1px 0 0;
        top: 0em;
    }
    #tasks_table_filter input {
        border: 1px solid rgb(211, 211, 211);
        border-radius: 3px;
        box-shadow: 0 0 1px rgb(128, 128, 128);
        margin-top: 3px;
    }
    .bootbox .modal-dialog{
        width: 300px;
    }
    div.invisible_droppable{
        height: 200px;
        left: 80px;
        margin-top: -100px;
        position: absolute;
        min-width: 400px;
        width: auto;
        display: none;
        opacity: 0.5;
        line-height: 10px;
        text-align: center;
    }
    
    
</style>
<script>
    var count = 1, options = '';
    <?php foreach ($allMeasurements as $key => $value) { ?>
        options += '<option value="<?php echo $value['id']; ?>"><?php echo $value['measurement_name']; ?></option>';
    <?php }?>
    $(document).ready(function() {

        $('.check_name_row').draggable({
            appendTo: 'body',
            helper: "clone",
            scroll:true,
            start: function(e, u) {
                var xOffset = ($(document).find('.task_row').length)*($(document).find('.task_row').height());
                xOffset = '-'+xOffset+' px !important'; 
                $('.droppable_task div').css({'background-color':'#F9F2E0','display':'block','marginTop':xOffset});
                $(this).data("startingScrollTop",$(document).scrollTop());
//                console.log($(document).find('.task_row').length+'*'+$(document).find('.task_row').height()+' start: '+xOffset);
                
            },
            drag: function(event,ui){
               var st = parseInt($(this).data("startingScrollTop"));
               
//               ui.position.top = $(document).scrollTop() - st;
                if ( $.browser.webkit ) {
                   ui.originalPosition.top -= $(document).scrollTop();
                   ui.position.top -= $(document).scrollTop();
                   ui.offset.top -= $(document).scrollTop();
//                   console.log('webkit');
                }
//               console.log(ui.position.top);
               
            },
            stop: function() {
                $('.droppable_task div').css({'background-color':'rgba(0,0,0,0)','display':'none'});
            }}).click(function() {
//            var b = parseInt($(this.width));

        });


        $('.droppable_task div').droppable({
            accept: ".check_name_row",
            activeClass: "ui-state-default",
            drop: function(e, u) {
                var chk = $('body').find('input.multiTaskCheck:checked');
                var flag = true;
                if(chk.length > 0)
                {
                    $.each(chk,function(){
                        console.log($(this).parent('td').siblings('td.check_name').text());
                        var obj = $(this).parent('td').siblings('td.check_name').text();
                        task_row(obj,flag);
                        $(this).prop('checked',false);
                    });
                }
                else
                {            
                    var a = u.helper.clone().children('.check_name');
                    var obj = a.text();
                    task_row(obj,flag);
                }
                    
            }});
            
            $('body').on('change','.check_type',function(){
            
                if(+$(this).val()){
                    $(this).parent('td').next('td').children('select').prop('disabled',false);
                }
                else{
                    $(this).parent('td').next('td').children('select').prop('disabled',true);
                }
            });
            
            $('body').on('click','.remove_task',function(){
                    $(this).parent('td').parent('tr').remove();
            });

    });
     function task_row(obj,flag)
     {
            var arr = obj.split('_');
//                console.log(arr);
//                $('#tokenfield-typeahead').tokenfield('createToken', {value: arr[0], label: arr[1]});
            var trs = $(document).find('input.task_name_input');

            $.each(trs, function(){
                var temp = $(this).val();
    //                    console.log(arr[2]+' == '+temp);
                if(temp != '')
                    if(arr[2].match(temp))
                    {
    //                            console.log(temp);
                        flag = false;
                    }
            });
            if(flag)
                { 
                    add_row();
                    console.log(arr[0]+','+arr[1]+','+arr[2]);
                    $('#compliance_add tr').last().prev().attr('data-new','false').find('input[type="text"]').val(arr[2]).attr('data-id',arr[1]).prop('readonly',true).parent().parent('tr').find('select').prop('disabled',true);
                    if(arr[0] != '')
                    {
                        $('#compliance_add tr').last().prev().find('select.check_type option[value="1"]').prop('selected',true);
                        $('#compliance_add tr').last().prev().find('select.add_measurements option[value="'+arr[0]+'"]').prop('selected',true);
                    }
                }
     }
    function add_row() {
                $('#compliance_add tr').last().before('<tr class="task_row" data-new="true" ><td><input class="task_name_input" data-id="0" type="text" /></td><td><select class="check_type"><option value="0">Standard</option><option value="1">Numerical</option></select></td> <td><select disabled="true" class="add_measurements"><option value="0">Select Measurement</option>'+options+'</select></td><td><img class="remove_task" alt="Remove" title="Remove" src="/img/icons/16/erase.png"></td></tr>');
    count++;
    }
    
    function beforeTestAdd(){
        var tasksArr = [],tasksStr;
        var comName = $('input[name="Compliance_check_name"]').val();
        var comCat = $('select[name="category"]').val();
        var comFre = $('select[name="frequency"]').val();
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
        if(comName.trim() == '' || comCat.trim() == '' || comFre.trim() == ''){
            $('input#Compliance_check_name[value=""]').effect('shake', {times: 3, distance: 5}, "fast");
            $('input#category[value=""]').effect('shake', {times: 3, distance: 5}, "fast");
            $('input#frequency[value=""]').effect('shake', {times: 3, distance: 5}, "fast");
            return false;
        }
        if($(document).find('#compliance_add tr.task_row').length == 0){
            $('#atleastOneTaskLabel').effect('shake', {times: 3, distance: 5}, "fast");
            return false;
        }
    }
    
    function deleteTask(editObj){
        var url = $(editObj).attr('data-href');
        bootbox.confirm("Are you sure?", function(result) {
            if (result) {
                window.location.href=url;
            } else {
                // Do nothing!
            }
        });
    }
</script>

<div class="heading">
    <h1>Safety Admin</h1>

</div>
<div class="box_content">
    <div class="ver_tabs" style="">
        <a class="" href="<?php echo base_url('compliance'); ?>" class="active"><span>Safety Checks Due</span></a>
        <a class="" href="<?php echo base_url('compliance/complianceshistory'); ?>"><span>Safety History</span></a>
        <a class="" href="<?php echo base_url('compliance/complianceslist'); ?>"><span>List of Safety Checks</span></a>
        <a class="active" href="#"><span>Safety Admin</span></a>
        <a class="" href="<?php echo base_url('compliance/adhoc'); ?>"><span>Complete Adhoc Checks</span></a>
        <a class="" href="<?php echo base_url('compliance/templates'); ?>"><span>Templates</span></a>
        <a class="" href="<?php echo base_url('compliance/report'); ?>"><span>Report</span></a>
    </div>
    <script>
        $(function() {
            $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
        });
    </script>
    <div class="content_main">
        <div class="compliance_box_top" style="min-height: 500px;">
            <div id="compliance_snapshot">
                <?php echo form_open('compliance/compliancesadmin/', array('id' => 'add_compliance','action'=>'post')); ?>
                <table  class="list_table" style="width: 100%;"> 
                    <thead>
                    <th colspan="4">
                        <!--<a href="<?php echo site_url('/items/changelinks/' . $arrItem->itemid . '/'); ?>" style=" margin-right: 100%;" ><img width="30px" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/add_icon.png" title="Change Links" alt="Change Links" /></a>-->
                    <h3>Add Safety Check</h3></th>
                    </thead>
                    <tbody id="compliance_add">
                        <tr>
                            <td><strong>Safety Name</strong><sup> *</sup></td>
                            <td colspan="3"><input type="input" id='Compliance_check_name' name="Compliance_check_name"/></td>
                        </tr>
                        <tr>    
                            <td><strong>Category</strong><sup> *</sup></td>
                            <td colspan="3">  <select id='category' name="category">
                                    <option value="">-- Please Select --</option>
                                    <?php foreach ($categories['results'] as $category) { ?>
                                        <option value="<?php print $category->categoryid; ?>"><?php print $category->categoryname; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>

                        <tr>
                            <td><strong>Mandatory</strong></td>
                            <td colspan="3">   <select name="mandatory">
                                    <option value="">-- Please Select --</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select></td>
                        </tr>
                        
                        <tr>
                            <td><strong>Frequency</strong><sup> *</sup></td>
                            <td colspan="3">  <select id='frequency' name="frequency">
                                    <option value="">-- Please Select --</option>
                                    <?php foreach ($frequencies as $frequency) { ?>
                                        <option value="<?php print $frequency['test_freq_id']; ?>"><?php print $frequency['test_frequency']; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>    
                            <td><strong>Manager Of Check</strong></td>
                            <td colspan="3"><select name="manager_of_check">
                                    <option value="0">Not Set</option>
                                    <?php
                                    foreach ($arrUsers['results'] as $arrUser) {
                                        echo "<option ";
                                        echo 'value="' . $arrUser->userid . '" ';
                                        if ($intUserId == $arrUser->userid) {
                                            echo 'selected="selected" ';
                                        }
                                        echo '>' . $arrUser->userfirstname . " " . $arrUser->userlastname . "</option>\r\n";
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><strong>Start Date Of Check</strong></td>
                            <td colspan="3"><input type="input" name="start_of_check"  class="datepicker" /></td>
                        </tr>
                        <tr>
                            <td><strong>Reminder</strong></td>
                            <td colspan="3">
                                <select name="reminder">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Alert</strong></td>
                            <td colspan="3">   <select name="alert">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select></td>
                        </tr>
                        <tr> 
                             <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/add_icon.png" title="add" alt="add" width="20px"/><a href="javascript:add_row();" >&nbsp;Add New Task</a></strong></td>
                            <td colspan="3" class="droppable_task"><div class="invisible_droppable"><b style="font-size:30px; position: relative; top:70px;">Drop Task Here.</b></div></td>
                        </tr>
                    </tbody>
                </table>
                <div id='atleastOneTaskLabel'><p>One Task MUST be added to a safety check.</p></div>
                <p>Drag & Drop Required Tasks into the Box Above.</p>
                <p>Fields Marked <sup>*</sup> are Mandatory.</p>
                        <input id="task_details" name="task_details" hidden="true">
                       <input class="button" onclick="return beforeTestAdd()"  type="submit" value="Save">
                     
                <?php echo form_close(); ?>

                <div class="scroll_table_cover">
                    <div id="table_head">
                        <div style="width: 20%; float: left;"><a href="#AddNewTaskModal" data-toggle="modal" style=" margin-right: 100%;" ><img width="20px" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/add_icon.png" title="Add Task" alt="Add Task" /></a><h5>Add Task</h5></div><div style="width: 80%; float: left;"><h3>List of Tasks</h3></div>
                    </div>
                    <div class="scroll_table">
                        <table id="tasks_table" class="list_table" style="width: 100%;  border:1px solid #ddd; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Name Of Task</th>
                                    <th>Type Of Task</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allTests as $test) { ?>
                                    <tr class="check_name_row">
                                        <td><input type="checkbox" class="multiTaskCheck"></td>
                                        <td class="check_name"><?php
                                            echo '<p style="display:none">'.$test['mid'].'_'. $test['id'] . '_</p>';
                                            print $test['task_name'];
                                            ?>
                                        </td>
                                        <td><?php if($test['measurement_name'] != ''){print "(Number) ".$test['measurement_name'];}else{print 'Standard';} ?> </td>
                                        <td class="right action">
                                            <a  href="#EditTaskModal" data-toggle="modal" onclick="editTaskSetup(this)" data-id="<?php echo $test['id']; ?>"data-href="<?php echo site_url() . 'compliance/edittask/' . $test['id']; ?>"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                                            <a href="javascript:void(0)" onclick="deleteTask(this)" data-id="<?php echo $test['id']; ?>" data-href="<?php echo site_url() . 'compliance/removeTask/' . $test['id']; ?>"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/icons/16/erase.png" title="Remove" alt="Remove"></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table> 
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--      <div id="list" class="form_block">
                <ul>
                    <li><h1>All Compliance Checks</h1></li>
                    <li>Filter by Category: <form name="filter" method="post" action="#list">
                            <select name="filter_cat" onchange="this.form.submit();">
                                <option value="">-- Please Select --</option>
    <?php foreach ($categories['results'] as $category) { ?>
                                                <option value="<?php print $category->categoryid; ?>" <?php print ($category_filter == $category->categoryid) ? "selected=\"selected\"" : ""; ?>><?php print $category->categoryname; ?></option>
    <?php } ?>
                            </select>
                        </form></li>
                </ul>
                <p></p>
    
            </div>-->
    
    
    
<!--New Task Add Modal-->

<div class="modal fade" id="AddNewTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <?php echo form_open('compliance/addtask/', array('id' => 'add_new_task')); ?>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Add New Task</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label class="col-md-4">Task Name</label>
                            <input class="col-md-6" type="input" name="task_name"/>
                            <?php echo form_error('checkname'); ?>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Type Of Task</label>
                            <select name="type_of_task" id="type_of_task" class="col-md-6">
                                <option value="">-- Please Select --</option>
                                <option value="0">Standard</option>
                                <option value="1">Numerical</option>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Measurement</label>
                            <select name="measurement_type" class="col-md-6" id="measurement_type">
                                <option value="">-- Please Select --</option>
                                <?php foreach ($allMeasurements as $key => $value) { ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['measurement_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" onclick="return beforeAddTask()" class="btn btn-warning">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!--Task Edit Modal-->

<div class="modal fade" id="EditTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <?php echo form_open('compliance/edittask/', array('id' => 'edit_task')); ?>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Edit Task</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label class="col-md-4">Task Name</label>
                            <input class="col-md-6" type="input" name="test_type_name"/>
                            <?php echo form_error('checkname'); ?>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Type Of Task</label>
                            <select name="type_of_task" id="type_of_task" class="col-md-6">
                                <option value="0">Standard</option>
                                <option value="1">Numerical</option>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-4">Measurement</label>
                            <select name="measurement_type" disabled="true" class="col-md-6" id="measurement_type">
                                <?php foreach ($allMeasurements as $key => $value) { ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['measurement_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" onclick="return beforeUpdateTask()" class="btn btn-warning">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function(){
        
        var table = $('#tasks_table').DataTable({
                    "pagingType": "full_numbers",
                    "order": [[ 1, "desc" ]]
                });
       $('#edit_task').find('select#type_of_task').on('change',function(){
            if(+$(this).val()){
                $('#edit_task').find('select#measurement_type').prop('disabled',false);
            }
            else{
                $('#edit_task').find('select#measurement_type').prop('disabled',true);
            }
       });
    });
    
    function editTaskSetup(editObj)
    {
        $('#edit_task').trigger('reset');
        $('#edit_task').attr('action',$(editObj).attr('data-href'));
        $.ajax({
            url:'<?php echo base_url('compliance/getTasks');?>/1/'+$(editObj).attr('data-id'),
            type:'get',
            success:function(data){
                data = JSON.parse(data);
                if(data != '[]')
                {
                    $('#edit_task').find('input[name="test_type_name"]').val(data['task_name']);
                    $('#edit_task').find('select[name="type_of_task"] option[value="'+data['type_of_task']+'"]').prop('selected',true);
                    $('#edit_task').find('select[name="measurement_type"] option[value="'+data['measurement']+'"]').prop('selected',true);
                    if(data['type_of_task'] == 1)
                        {
                            $('#edit_task').find('select#measurement_type').prop('disabled',false);
                        }
                        else{
                            $('#edit_task').find('select#measurement_type').prop('disabled',true);
                        }
                }
                
            },
            error:function(data){
                alert(JSON.stringify(data));
            }
        });
    }
</script>