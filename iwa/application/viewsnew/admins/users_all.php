<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<style>
    .modal-body{
        min-height: 200px;
        max-height: 595px; 
        overflow-y: scroll;
    } 
</style>
<script>
    $(document).ready(function(){
         var base_url_str = $("#base_url").val();

        var master_table = $("#restore_user").DataTable({
            "oLanguage": {
                "sProcessing": "<div align='center'><img src='" + base_url_str + "/assets/img/ajax-loader.gif'></div>"},
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnRowCallback": function(nRow, aData) {

            var $nRow = $(nRow); // cache the row wrapped up in jQuery
            tdhtm = $nRow.children()[4].innerHTML;

            if (tdhtm.search("enable") != -1) {
                $nRow.css("background-color", "#f2b4b4");
            }

            return nRow;
        },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
//                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            ]}


        ); 
    
      // script for user change password
        $("body").on("click", ".change_password_model", function() {
            $(".result").empty();
            var user_id = $(this).attr("data_user_id");
            var username = $(this).attr("data_user_name");
            $("#user_id").attr("value", user_id);
             $("#username").attr("value",username);
             $("#username_dis").attr("value",username);
        });
        
           // script for edit adminuser
        $("body").on("click", ".edit", function () {

            var firstname = $(this).attr("data_firstname");
            var lastname = $(this).attr("data_lastname");
            var username = $(this).attr("data_username");
            var acess=$(this).attr("data_access");
            var adminuser_id = $(this).attr("data_adminuser_id");
            
            $("#edit_first_name").attr("value", firstname);
            $("#edit_last_name").attr("value", lastname);
            $("#edit_username").attr("value", username);
            $("#adminuser_id").attr("value", adminuser_id);
            $("#edit_access_level").attr("value", acess);


        });
        
              $("#updatepassword").validate({
            rules: {
                confirm_newpassword: {
                    required: true,
                    equalTo: "#new_password"
                },
            },
            messages: {
                confirm_newpassword: {
                    equalTo: "Password Is Not Matching"
                },
            }
        });
    });
</script>

<a class="btn btn-xs btn-success" href="<?php echo base_url('/admins/viewaccounts'); ?>">Return To List</a>
 <div class="row">
                <div class="col-lg-12">

                    <div class="panel-body multiadd">

                        <div class="table-responsive">
                            <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                                <table id="restore_user" class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr style="background-color: #00AEEF; color: white">
                                           <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Username</th>
                                            <th>Level</th>
                                            <th>Actions</th>
                                        </tr>

                                    </thead>
                                        <tbody>
<?php
if (count($arrUsers['results'])>0) {
	foreach ($arrUsers['results'] as $intAccountId => $arrAccount) {
            foreach ($arrAccount['levels'] as $intLevelId => $arrLevel) {
                foreach ($arrLevel['users'] as $arrUser) { ?> 
            <tr>
                <?php if ($arrUser->active != 0) { ?>
                <td><?php echo $arrUser->firstname; ?></td>
                <td><?php echo $arrUser->lastname; ?></td>
                <?php } else { ?>
             
                   
                      <td><?php echo $arrUser->firstname . " (Inactive)"; ?></td>
                      <td><?php echo $arrUser->lastname . " (Inactive)"; ?></td>  
                
                <?php  } ?>                
                <td><?php echo $arrUser->username; ?></td>
                <td><?php echo $arrUser->levelname; ?></td>
                <td>
                    <?php if ($arrUser->active == 0) { ?>
                    <span class="action-w"><a href="<?php echo site_url('/admins/reactivateuser/'.$arrUser->userid); ?>" title="Activate" alt="Activate" /><i class="glyphicon glyphicon-play franchises-i enable"></i></a>Activate</span>
                    <?php } else { ?>
                    <span class="action-w"><a title="Change Credentials" class="change_password_model" data_user_name="<?php echo $arrUser->username; ?>" data_user_id="<?php echo $arrUser->userid; ?>" alt="Change Credentials" data-toggle="modal" id="changepassword_id_<?php echo $arrUser->userid; ?>" href="#change_password_model"/><i class="glyphicon glyphicon-lock franchises-i"></i></a>Password</span><span class="action-w"><a href="<?php echo site_url('/admins/inheritUser/'.$arrUser->userid); ?>" title="Inherit User" alt="Inherit User" ><i class="glyphicon glyphicon-download franchises-i"></i></a>Inherit User</span>
   
                    <span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $arrUser->userid; ?>" href="#edit_user" title="Edit" data_firstname="<?php echo $arrUser->firstname; ?>" data_lastname=" <?php echo $arrUser->lastname; ?>" data_username="<?php echo $arrUser->username; ?>" data_owner="<?php echo $arrUser->owner; ?>" data_adminuser_id="<?php echo $arrUser->userid; ?>" data_access="<?php echo $arrUser->levelid; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span>
                    
                    <?php
                                              
                                                    $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('admins/disableUser/' . $arrUser->userid) . '" data_adminuser_id=' . $arrUser->userid . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                                    echo $access_icon;
                                                    
                                             
              } ?>
                    
                </td>
            </tr>
                <?php }
            }
          }
       
}?>
        </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="change_password_model" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Change Password</h4>
            </div>

            <form action="<?php echo base_url('admins/changecredentialsuser'); ?>" method="POST" id="updatepassword">
                <div class="modal-body">
                     
                    
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Username :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter New Username" disabled="disabled" class="form-control" name="username_dis" id="username_dis">
                            <input type="hidden" placeholder="Enter New Username" class="form-control" name="username" id="username">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>New Password :</label> </div>
                        <div class="col-md-6">  <input type="password" placeholder="Enter New Password" class="form-control" name="password" id="new_password"><div class="result"></div>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Confirm Password :</label> </div> 

                        <div class="col-md-6">  <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirm_newpassword" id="confirm_newpassword"></div>
                    </div> <!-- /.form-group -->


                    <input type="hidden" name="user_id" id="user_id"/>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="changepassword">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_user" class="modal fade" style="display: none;">
 <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit User</h4>
            </div>

            <form action="<?php echo base_url('admins/editInhertUser'); ?>" method="post" id="edit_user_account">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>First Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter First Name" class="form-control" name="edit_first_name" id="edit_first_name"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input type="text" placeholder="Enter Last Name" class="form-control" name="edit_last_name" id="edit_last_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Username :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter UserName" class="form-control" name="edit_username" id="edit_username" disabled="">

                        </div>

                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Access Level : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_access_level" id="edit_access_level" class="form-control">
                                <option>-----select------</option>
                                <?php foreach ($access_level as $level) { ?>
                                    <option value="<?php echo $level->id; ?>"><?php echo $level->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                   

                    <input type="hidden" name="adminuser_id" id="adminuser_id"/>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button_system">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>