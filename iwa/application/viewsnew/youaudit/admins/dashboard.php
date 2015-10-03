<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<style>
    .modal-body{
        height: 595px;
        overflow-y: scroll;
    } 
</style>
<script>

    $(document).ready(function() {

//     update username Automatically For Franchise and Master
        $('#add_franchisesAC #contact_email').on('blur keyup', function()
        {
            var contact_email = $('#add_franchisesAC #contact_email').val();
            $('#contact_username_franchises').val(contact_email);
        });
        $('#add_masterAC #contact_email').on('blur keyup', function()
        {
            var contact_email = $('#add_masterAC #contact_email').val();
            $('#contact_username').val(contact_email);
        });



//     **********************************************************************   
        $("#add_master_account").validate({
            rules: {
                sys_admin_name: "required",
                company_name: "required",
                contact_name: {
                    required: true

                },
                contact_email: {
                    required: true,
                    email: true
                },
                contact_phone: {
                    required: true,
                    digits: true
                },
                first_name: "required",
                last_name: "required",
                contact_username: {
                    required: true,
                    email: true
                },
                contact_password: "required",
                confirm_password: {
                    required: true,
                    equalTo: "#contact_password"
                },
                pin_number: {
                    required: true,
                    digits: true,
                    rangelength: [6, 6],
                },
                account_limit: "required"
            },
            messages: {
                sys_admin_name: "Please Enter Name",
                company_name: "Please Enter Company Name",
                contact_name: "Please Enter Contact Name",
                contact_phone: "Please Enter Valid Number",
                contact_username: {
                    required: "Please Enter Username",
                    email: "Enter Valid Emails"
                },
                contact_email: "Please Enter Email Address",
                first_name: "Please Enter First Name",
                last_name: "Please Enter Last Name",
                contact_password: {
                    required: "Please Enter Password",
                },
                confirm_password: {
                    required: "Please Enter Confirm Password",
                    equalTo: "Password Is Not Matching"
                },
                pin_number: {
                    required: "Please provide a pin number",
                    digits: "Please Enter Only Digits",
                    rangelength: "Please Enter Only 6 Digits"
                },
            }
        });
        $("#add_franchise_account").validate({
            rules: {
                sys_franchise_name: "required",
                company_name: "required",
                contact_name: {
                    required: true

                },
                contact_email: {
                    required: true,
                    email: true
                },
                contact_phone: {
                    required: true,
                    digits: true
                },
                contact_password_franchise: "required",
                contact_username_franchises: {
                    required: true,
                    email: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#contact_password_franchise"
                },
                pin_number: {
                    required: true,
                    digits: true,
                    rangelength: [6, 6],
                },
                account_limit: "required"
            },
            messages: {
                sys_franchise_name: "Please Enter Name",
                company_name: "Please Enter Company Name",
                contact_name: "Please Enter Contact Name",
                contact_phone: "Please Enter Valid Number",
                contact_username_franchises: {
                    required: "Please Enter Username",
                    email: "Enter Valid Emails"
                },
                contact_email: "Please Enter Email Address",
                contact_password_franchise: {
                    required: "Please Enter Password",
                },
                confirm_password: {
                    required: "Please Enter Confirm Password",
                    equalTo: "Password Is Not Matching"
                },
                pin_number: {
                    required: "Please provide a pin number",
                    digits: "Please Enter Only Digits",
                    rangelength: "Please Enter Only 6 Digits"
                },
            }
        });

        $("#getNews").validate({
            rules: {
                news_text: {
                    required: true,
                }

            }
        });


        // Check username For master
        $("#contact_username").on("keyup blur", function() {

            var username = $("#contact_username").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_username",
                data: {
                    'username': username
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#username_error").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#username_error").addClass("hide");
                    }
                }

            });

        });

        // check franchies username is present or not

        $("#contact_username_franchises").on("keyup blur", function() {

            var username = $("#contact_username_franchises").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_username_franchies",
                data: {
                    'username': username
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button_franchise").addClass('disabled');
                        $("#username_error_frenchise").removeClass("hide");
                    } else {
                        $("#save_button_franchise").removeClass('disabled');
                        $("#username_error_frenchise").addClass("hide");
                    }
                }

            });

        });

        // Check master System Admin
        $("#sys_admin_name").on("keyup blur", function() {

            var sys_Admin_name = $("#sys_admin_name").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_sysAdminName",
                data: {
                    'sys_admin_name': sys_Admin_name
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#error_div_master").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#error_div_master").addClass("hide");
                    }
                }

            });

        });
        $("#franchise_account").click(function() {
            $(".result").empty();
        })

        $("#master_account").click(function() {
            $(".result").empty();
        })

        // Check Franchises System Admin
        $("#sys_franchise_name").on("keyup blur", function() {

            var sys_Admin_name = $("#sys_franchise_name").val();
            var base_url_str = $("#base_url").val();


            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/youaudit_admins/check_sysAdminNameForFranchises",
                data: {
                    'sys_franchises_name': sys_Admin_name
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button_franchise").addClass('disabled');
                        $("#error_div_franchise").removeClass("hide");
                    } else {
                        $("#save_button_franchise").removeClass('disabled');
                        $("#error_div_franchise").addClass("hide");
                    }
                }

            });

        });



    });


</script>

<div class="row">
    <div class="col-lg-3">

        <h1 class="page-header">Dashboard</h1>

    </div>


    <div class="col-lg-9" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <button data-target="#add_franchisesAC" data-toggle="modal"  class="btn btn-primary btn-xs icon-with-text" type="button" id="franchise_account">
                    <i class="fa fa-plus"></i>
                    <b> Add Franchises</b></button>
                <button data-target="#add_masterAC" data-toggle="modal"  class="btn btn-primary btn-xs icon-with-text" type="button" id="master_account">
                    <i class="fa fa-plus"></i>
                    <b> Add Master AC</b></button>



            </div>
        </div>
    </div>

    <!-- /.col-lg-12 -->
</div>
<?php
if ($this->session->flashdata('arrCourier')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('arrCourier'); ?>
    </div>
    <?php
}
?>
<?php
if ($this->session->flashdata('success')) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                SYSTEM SUMMARY
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="summary" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sys Admin Name</th>
                                    <th>Account Type</th>
                                    <th>Total Number</th>
                                    <th>Active</th>
                                    <th>Disabled</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($summary as $record) {
                                    ?>
                                    <tr> 
                                        <td><a href="<?php
                                            if ($record['master_account_id']) {
                                                echo base_url('youaudit/master_admins/customerlist/' . $record['id']);
                                            } else {
                                                echo base_url('youaudit/franchise_admins/franchise_customerlist/' . $record['id']);
                                            }
                                            ?>"><?php echo $record['sys_admin_name']; ?></a></td>
                                        <td><?php echo $record['type']; ?></td>
                                        <td><?php echo $record['total']; ?></td>
                                        <td><?php echo $record['enabled']; ?></td>
                                        <td><?php echo $record['disabled']; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table></div>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Recently Added Accounts
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sys Admin Name</th>
                                <th>Account Type</th>
                                <th>Customer Name</th>
                                <th>Package Type</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $recent_accounts = array_splice($recent_accounts, 0, 5);
                            foreach ($recent_accounts as $recent) {
                                ?>
                                <tr>
                                    <td><a href="<?php
                                        if ($recent['type'] == 'master') {
                                            echo base_url('youaudit/master_admins/customerlist/' . $recent['id']);
                                        } else {
                                            echo base_url('youaudit/franchise_admins/franchise_customerlist/' . $recent['id']);
                                        }
                                        ?>"><?php echo $recent['sys_admin_name']; ?></a></td>
                                    <td><?php echo $recent['type']; ?></td>
                                    <td><?php echo $recent['company']; ?></td>
                                    <td><?php echo $recent['package']; ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
</div>

<div class="row">

    <div class="col-lg-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                Customer Latest News Text
            </div>
            <form action="<?php echo base_url() . 'youaudit/setNews' ?>" method="POST" id="getNews">
                <div class="panel-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-3"> <label for="news_text">News Text :</label></div>
                        <div class="col-md-9">  

                            <textarea data-required="true" data-minlength="5" name="news_text" id="news_text" cols="10" rows="2" class="form-control parsley-validated"></textarea>
                        </div>
                    </div> 

                </div>
                <div class="panel-footer">
                    <button class="btn btn-outline btn-warning btn-md" type="submit" name="news_submit">Update News</button>

                </div>
            </form>
        </div>
    </div>
    <!--<div class="col-lg-6"></div>-->




    <div class="col-lg-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                Current News : 
            </div>
            <div class="panel-body">
                <p><?php
                    if (isset($latest_news)) {
                        $this->load->helper('text');
                        echo word_wrap($latest_news['news_text'], 50);
                    }
                    ?></p>
            </div>
            <div class="panel-footer">
                <?php
                if (isset($latest_news)) {

                    $this->load->helper('date');

                    $datestring = "%d-%m-%Y %h:%i:%s %a";
                    echo mdate($datestring, $latest_news['create_date']);
                }
                ?>

            </div>
        </div>
    </div>

</div>

<!--   Model For Add Account For MaSTER -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_masterAC" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title" style="font-size:25px; font-family:Oswald">Add Master Account</h4>
            </div>

            <form action="<?php echo base_url('youaudit/addmasterAccount'); ?>" method="POST" id="add_master_account">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>SYS Admin Name :</label> </div>
                        <div class="col-md-6"><input type="text" placeholder="Enter Sys Admin Name" class="form-control" name="sys_admin_name" id="sys_admin_name">
                            <div id="error_div_master" class="username_error hide">Sys Admin Name Already Is Exist.</div>
                        </div>

                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input type="text"  placeholder="Enter Company Name" class="form-control" name="company_name" id="company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Contact Name :</label> </div> 

                        <div class="col-md-6">  <input type="text" placeholder="Enter Contact Name" class="form-control" name="contact_name" id="contact_name"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact Email Address : </label> </div>

                        <div class="col-md-6">  <input type="text" placeholder="Enter Email Address" class="form-control" name="contact_email" id="contact_email"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact First Name : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="first_name" id="first_name1"></div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact Last Name : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name1"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>Contact Phone Number :</label></div>
                        <div class="col-md-6">   <input type="text" placeholder="Enter Phone Number" class="form-control" name="contact_phone" id="contact_phone"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Contact UserName :</label>

                        </div>
                        <div class="col-md-6"> 
                            <input type="text" placeholder="Enter Contact UserName" class="form-control" name="contact_username" id="contact_username">
                            <div id="username_error" class="username_error hide">Username Already Exist.</div>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="contact_password" id="contact_password" type="password"></div>
                        <div class="result"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="confirm_password" id="confirm_password" type="password"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>6 Digit Pin :</label>
                        </div>
                        <div class="col-md-6">       
                            <input  placeholder="Enter 6 Digit Pin" class="form-control" name="pin_number" id="pin_number" type="password">
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Enable Report :</label>
                        </div>
                        <div class="col-md-6">       
                            <label class="checkbox-inline">
                                <input type="checkbox" name="report_allow">
                            </label>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Account Limit : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="number" name="account_limit" id="account_limit" min="0" class="form-control">
                        </div>
                    </div> 

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="master" value="1">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" id="save_button" type="submit">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>



<!--   Model For Add Account For Franchise -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_franchisesAC" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title" style="font-size:25px; font-family:Oswald">Add Franchises Account</h4>
            </div>

            <form action="<?php echo base_url('youaudit/addfranchiseAccount'); ?>" method="POST" id="add_franchise_account">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>SYS Franchise Name :</label> </div>
                        <div class="col-md-6"><input placeholder="Enter Sys Franchise Name" class="form-control" name="sys_franchise_name" id="sys_franchise_name">
                            <div id="error_div_franchise" class="username_error hide">Sys Admin Name Already Is Exist.</div>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="company_name" id="company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Contact Name :</label> </div> 

                        <div class="col-md-6">  <input placeholder="Enter Contact Name" class="form-control" name="contact_name" id="contact_name"></div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact Email Address : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Email Address" class="form-control" name="contact_email" id="contact_email"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact First Name : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="first_name" id="first_name"></div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>Contact Last Name : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name"></div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>Contact Phone Number :</label></div>
                        <div class="col-md-6">   <input placeholder="Enter Phone Number" class="form-control" name="contact_phone" id="contact_phone"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Contact UserName :</label>

                        </div>
                        <div class="col-md-6"> 
                            <input placeholder="Enter Contact UserName" class="form-control" name="contact_username_franchises" id="contact_username_franchises">
                            <div id="username_error_frenchise" class="username_error hide">Username Already Exist.</div>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="contact_password_franchise" id="contact_password_franchise" type="password"></div>
                        <span class="result"></span>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Confirm password" class="form-control" name="confirm_password" id="confirm_password" type="password"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>6 Digit Pin :</label>
                        </div>
                        <div class="col-md-6">       
                            <input placeholder="Enter 6 Digit Pin" class="form-control" name="pin_number" id="pin_number" type="password"> 
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Account Limit : </label>
                        </div>
                        <div class="col-md-6">       
                            <input type="number" name="account_limit" id="account_limit" min="0" class="form-control">
                        </div>
                    </div> 

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="franchise" value="1">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button_franchise">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>
<!--<div class="row">
    <div class="col-lg-12">
<div class="panel-body">
                             Nav tabs 
                            <ul class="nav nav-pills">
                                <li class="active"><a data-toggle="tab" href="#home-pills">Home</a>
                                </li>
                                <li><a data-toggle="tab" href="#profile-pills">Profile</a>
                                </li>
                                <li><a data-toggle="tab" href="#messages-pills">Messages</a>
                                </li>
                                <li><a data-toggle="tab" href="#settings-pills">Settings</a>
                                </li>
                            </ul>
</div>
    </div>
    </div>-->