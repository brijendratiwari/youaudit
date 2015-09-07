<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        /*height: 495px;*/
        overflow-y: scroll;
    } 
     .bootbox .modal-dialog{
        width: 300px;
    }
</style>
<script>
    $(document).ready(function() {
        $('#tabledivbody').sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            helper: fixHelper,
            update: function() {
                sendOrderToServer();
            },
            axis: "y",
            start: function(e, ui) {
                // modify ui.placeholder however you like
                ui.placeholder.html("Placeholder");
            }
        });

        // Return a helper with preserved width of cells
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };

        $("#showtooltip").tooltip('hide');
        
           $("#add_check_form").validate({
            rules: {
                check_name:"required",
               
            },
             messages: {
                check_name:"Check Name Required",
            }
        });
           $("#edit_check_form").validate({
            rules: {
                edit_check_name:"required",
               
            },
             messages: {
                edit_check_name:"Check Name Required",
            }
        });
        
        $(".editcheck").click(function(){
          var checkID=($(this).attr("check_id"));
          var check_name=($(this).attr("check_name"));
          var check_discription=($(this).attr("check_discription"));
          var check_sort_discription=($(this).attr("check_sort_discription"));
          
          $("#edit_check_name").attr("value",check_name);
          $("#long_short_description").val(check_discription);
          $("#edit_short_description").val(check_sort_discription);
          $("#edit_check_id").val(checkID);
        });
    });

    function sendOrderToServer() {

        var check_id = [];
        $("#tabledivbody tr").each(function() {
            check_id.push($(this).find('td:eq(0)').html());


        });
        var dataarr = JSON.stringify(check_id);

        $.ajax({
            type: "POST",
            dataType: "text",
            url: "<?php
echo base_url('fleet/savevehicleorder');
?>",
            data: {vehiclecheckid: dataarr},
            success: function(response) {

            }
        });
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


<div class="box">
    <div class="heading">
        <h1>Fleet Checks Management</h1>
        <a href="" id="showtooltip" class="" data-toggle="tooltip" data-placement="right" title=" You can reorder checks by drag and drop"><img src="<?php echo base_url('img/info-128.png'); ?>" width="25px"></a>

        <div class="buttons">
<!--            <a href="<?php echo site_url('fleet/newCheck'); ?>" class="button"></a>-->
             <button class="btn btn-primary btn-xs icon-with-text" type="button" id="addsysadmin"  data-target="#add_check" data-toggle="modal"><i class="glyphicon glyphicon-ok-sign"></i>
                    <b>Add vehicle check</b></button> 

        </div>
        
    </div>

    <div class="box_content">
        <table class="list_table sorted_table">
            <thead>
                <tr class="header-row">
                    <th class="left" style="display: none">id</th>

                    <th class="left">Check</th>
                    <th class="left">Description</th>
                    <th class="">Actions</th>
                </tr>
            </thead>
            <tbody class="sortable_fleet" id="tabledivbody">

                <?php
                foreach ($arrChecks as $key => $check) {
                    ?>
                    <tr class="">
                        <td style="display: none"><?php print $check['id']; ?></td>
                        <td><?php print $check['check_name']; ?></td>
                        <td><?php print $check['check_long_description']; ?></td>
                        <td class="right action">
                            <a data-target="#edit_check" class="editcheck" check_name="<?php echo $check['check_name'];?>" check_discription="<?php echo $check['check_long_description'];?>" check_sort_discription="<?php echo $check['check_short_description'];?>" check_id="<?php echo $check['id'];?>" data-toggle="modal" href="#"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                           
                            <a href="javascript:void(0)" data-toggle="modal" onclick="deleteTask(this)" data-id="<?php echo $check['id']; ?>"data-href="<?php echo base_url('fleet/deleteCheck/' . $check['id']); ?>"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/img/icons/16/erase.png" title="Remove" alt="Remove"></a>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
        <style>

            #showtooltip{
                /*vertical-align: sub!important;*/
                left: 27px;
                top: 7px;
                position: relative;
            }
            #tabledivbody tr{
                cursor: ns-resize;
            }

        </style>
        
    </div>     
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_check" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Check for the vehicles</h4>
            </div>

            <?php echo form_open('fleet/newcheck', array('id'=>'add_check_form')); ?>
                <div class="modal-body">
                    
                    <div class="form-group col-md-12">
                         <div class="col-md-6"><label for="check_name">Check name</label></div>
                         <div class="col-md-6"><input type="text" class="form-control" id="check_name" name="check_name"/></div>
                    <?php echo form_error('check_name'); ?>
                    </div> 
                    <div class="form-group col-md-12">
                          <div class="col-md-6"><label for="check_long_description">Check short description (for the app)</label></div>
                    <div class="col-md-6"><textarea name="check_short_description" class="form-control"></textarea></div>
                    <?php echo form_error('check_short_description'); ?>
                    </div>
                    <div class="form-group col-md-12">
                         <div class="col-md-6"><label for="check_long_description">Check long description</label></div>
                    <div class="col-md-6"><textarea name="check_long_description" class="form-control"></textarea></div>
                    <?php echo form_error('check_long_description'); ?>
                        </div>
                         
                    </div> <!-- /.form-group -->
                

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button_system">Save</button>
                </div>
            </form>
             </div>
        </div>

    </div>
        
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_check" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Check for the vehicles</h4>
            </div>

            <?php echo form_open('fleet/editCheck', array('id'=>'edit_check_form')); ?>
                <div class="modal-body">

                    <div class="form-group col-md-12">
                    <div class="col-md-6"><label for="make">Check name</label></div>
                    <div class="col-md-6"><input type="input" id="edit_check_name" class="form-control" name="edit_check_name" value="<?php print $objCheck->check_name; ?>"/>
                <?php echo form_error('check_name'); ?></div>
                    </div> 
                    <div class="form-group col-md-12">
                          <div class="col-md-6"><label for="check_long_description">Check short description (for the app)</label></div>
                    <div class="col-md-6"><textarea name="check_short_description" id="edit_short_description" class="form-control"></textarea></div>
                    <?php echo form_error('check_short_description'); ?>
                    </div>
                    <div class="form-group col-md-12">
                         <div class="col-md-6"><label for="check_long_description">Check long description</label></div>
                    <div class="col-md-6"><textarea name="check_long_description" id="long_short_description"" class="form-control"></textarea></div>
                    <?php echo form_error('check_long_description'); ?>
                        </div>
                         
                    </div> <!-- /.form-group -->
                
                        <input type="hidden" name="id" id="edit_check_id"/>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button_system">Save</button>
                </div>
            </form>
             </div>
        </div>

    </div>
    <!-- /.modal-dialog -->
