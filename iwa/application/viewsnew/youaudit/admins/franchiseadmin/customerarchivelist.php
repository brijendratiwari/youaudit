<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        height: 495px;
        overflow-y: scroll;
    } 
    .qrcode_error
    {
        color: red;
        font-weight: bold;
    }
    .qrcode_limit
    {
        color: red;
        font-weight: bold;
    }
         .bootbox .modal-dialog{
        width: 400px;
    }
      .bootbox .modal-body{
        height: 75px;
        overflow: auto !important;
    }
</style>
<script>

    $(document).ready(function() {
        
      
        
        

    });
 function deleteTemplate(editObj){
            var url = $(editObj).attr('data-href');
          
            bootbox.confirm("Do you want to restore this customer account?", function(result) {
                if (result) {
                    window.location.href=url;
                } else {
                    // Do nothing!
                }
            });
        }
 function restoreadmin(editObj){
            var url = $(editObj).attr('data-href');
          
            bootbox.confirm("Do you want to restore this admin  account?", function(result) {
                if (result) {
                    window.location.href=url;
                } else {
                    // Do nothing!
                }
            });
        }

</script>

<BR>
<div class="panel panel-default">
    <div class="panel-heading">
        <b>  <?php echo strtoupper($account_name); ?>  CUSTOMER / ACCOUNT LIST </b>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-pills">
              <li ><a data-toggle="" href="<?php echo base_url("youaudit/franchise_customerlist/$masterid"); ?>">Customer List</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/franchiseAdminUser/$masterid"); ?>">Admin Users</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/franchise_admins/complianceChecksForFranchise/$masterid"); ?>">Compliance Templates</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/franchise_profiles/$masterid"); ?>">Profiles</a>
            </li>
            <li class="active"><a data-toggle="" href="<?php echo base_url("youaudit/franchise_admins/restorecustomer/$masterid"); ?>">Archive Account
</a>
            </li>
        </ul>

        <!-- Tab panes -->

    </div>
    <!-- /.panel-body -->
</div>
   <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Archive Customer</h1>
                </div>
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Archive Admin User</h1>
                </div>
            </div>
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
<?php
if ($this->session->flashdata('error')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-lg-12">
  <div class="col-lg-6">
        <div class="panel-body">

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="Franchise_ArchiveCustomer_Datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                           <tr class="access">
                              
                                <th>Customer Name</th>
                                <th>City</th>
                                <th>State</th>
                               
                                <th>QR Ref Code</th>
                                <th>Account Package</th>
                                <th>Annual Value</th>
                                <th>No of Assets</th>
                                
                                <th>AC Created Date</th>
                                <th>No of User</th>
                                <th style="width:12%">Actions</th>
                            </tr>
                          
                        </thead>
                        <tbody id="Master_Customer_body">
                        </tbody>
                        <tfoot>
                        <th>Total</th>
                    
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        
                        </tfoot>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
         <div class="col-lg-6">

        <div class="panel-body">
            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="franchisearchiveadminuser_datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">

                        <thead>
                            <tr>
                             
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="master_body">


                        </tbody>
                        <tfoot>
                              <tr>
                             
                                <th></th>
                                <th></th>
                                <th></th>

                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.table-responsive -->
        </div>
    </div>
    </div>
    <input type="hidden" name="masterid" value="<?php echo $masterid;?>" id="franchiseid">
</div>


<!-- Modal for add master acc -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_customer_ac" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Create Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/addFranchiseCustomerAc' ?>" method="post" id="add_customer_account">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>General Information</label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="company_name" id="company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Address :</label> </div> 
                        <div class="col-md-6">  <input placeholder="Enter Address" class="form-control" name="comapany_address" id="comapany_address"></div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>City : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Company City" class="form-control" name="company_city" id="company_city"></div>
                    </div> 

                 <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>State :</label></div>
                        <div class="col-md-6"> <select class="form-control" name="company_state"> 
                                <option value="NSW">NSW</option>
                                <option value="VIC">VIC</option>
                                <option value="QLD">QLD</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="WA">WA</option>
                                <option value="NT">NT</option>
                                <option value="ACT">ACT</option>
                                               </select></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Postcode :</label>
                        </div>
                        <div class="col-md-6"> 
                            <input type="text" placeholder="Enter Company Postcode" class="form-control" name="company_postcode" id="company_postcode">
                        </div>
                    </div> 

                    <!--  Add First User  -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Add First User</label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>First Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="first_name" id="first_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="last_name" id="last_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>UserName / Email Address :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter UserName" class="form-control" name="username" id="username">
                            <div id="username_error" class="username_error hide">Username Is Already Exist.</div> 
                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Contact Name" class="form-control" name="contact_name" id="contact_name">

                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Number:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Phone Number" class="form-control" name="contact_phone" id="contact_phone">

                        </div>

                    </div><!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Enter Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="contact_password" id="contact_password" type="password"> <div class="result"></div></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="confirm_password" id="confirm_password" type="password"></div>
                    </div> 


                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add To Owner List : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="add_owner" id="add_owner" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Master Support Email :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Master Support Email" class="form-control" name="support_email" id="support_email" type="text"></div>
                    </div> 
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Ref Code</label>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>QR Ref Code:</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control"  name="qr_refcode" id="qr_refcode_hidden" type="text">
                            <div id="qrcode_error" class="qrcode_error hide">QR Code Already Exist.</div>
                            <div id="qrcode_limit" class="qrcode_limit hide">Please enter a value between 3 and 4 characters long.</div>
                            <!--<input class="form-control"  name="qr_refcode" id="qr_refcode" type="hidden">-->
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Account Package</label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Package:</label>
                        </div>

                        <div class="col-md-6">       
                            <select name="package_type" id="package_type" class="form-control">
                                <?php
                                foreach ($customer_package as $val) {
                                    ?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>  
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Verified: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="verify_package" id="verify_package" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Annual Value: </label>
                        </div>
                        <div class="col-md-6">       
                            <input placeholder="Enter Annual Value" class="form-control" name="annual_value" id="annual_value" type="text"> 
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Additional Modules</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Compliance Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="compliance_module" id="compliance_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Fleet Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="fleet_module" id="fleet_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Condition Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="condition_module" id="condition_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Depreciation Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="depreciation_module" id="depreciation_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Reporting Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="reporting_module" id="reporting_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add Profile: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="profile" id="profile" class="form-control">
                                <option value="0">None</option>
                                <?php foreach ($profilelist as $pro) { ?>
                                    <option value="<?php echo $pro->profile_id; ?>"><?php echo $pro->profile_name; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="masterid" id="master_account_id" value="<?php echo $masterid; ?>"/>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>




<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_customer_ac" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Account</h4>
            </div>

            <form action="<?php echo base_url() . 'youaudit/editFranchiseCustomerAc' ?>" method="post" id="edit_customer_account_form">
                <div class="modal-body">

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>General Information</label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Company Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Company Name" class="form-control" name="edit_company_name" id="edit_company_name">
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">         <label>Address :</label> </div> 
                        <div class="col-md-6">  <input placeholder="Enter Address" class="form-control" name="edit_comapany_address" id="edit_comapany_address"></div>

                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">        <label>City : </label> </div>

                        <div class="col-md-6">  <input placeholder="Enter Company City" class="form-control" name="edit_company_city" id="edit_company_city"></div>
                    </div> 

                   <div class="form-group col-md-12">
                        <div class="col-md-6">            <label>State :</label></div>
                 
                         <div class="col-md-6"> <select class="form-control" name="edit_company_state" id="edit_company_state"> 
                                <option value="NSW">NSW</option>
                                <option value="VIC">VIC</option>
                                <option value="QLD">QLD</option>
                                <option value="SA">SA</option>
                                <option value="TAS">TAS</option>
                                <option value="WA">WA</option>
                                <option value="NT">NT</option>
                                <option value="ACT">ACT</option>
                                               </select></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Postcode :</label>
                        </div>
                        <div class="col-md-6"> 
                            <input type="text" placeholder="Enter Company Postcode" class="form-control" name="edit_company_postcode" id="edit_company_postcode">
                        </div>
                    </div> 

                    <!--  Add First User  -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Add First User</label> </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>First Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter First Name" class="form-control" name="edit_first_name" id="edit_first_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Last Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Last Name" class="form-control" name="edit_last_name" id="edit_last_name">
                        </div>
                    </div> <!-- /.form-group -->
                   <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>UserName / Email Address :</label> </div>
                        <div class="col-md-6"> 
                            <input placeholder="Enter UserName" class="form-control" name="edit_contact_username" id="edit_contact_username" type="text">
                            <input name="check_username" id="check_username" type="hidden">
                             <div id="edit_username_error" class="username_error hide">Username Already Exist.</div>
                        </div>
                         
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Name:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Contact Name" class="form-control" name="edit_contact_name" id="edit_contact_name">

                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Contact Number:</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Phone Number" class="form-control" name="edit_contact_phone" id="edit_contact_phone">

                        </div>

                    </div><!-<!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Enter Password :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Password" class="form-control" name="edit_contact_password" id="edit_contact_password" type="password"><div class="result"></div></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Confirm Password :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Enter Repassword" class="form-control" name="edit_confirm_password" id="edit_confirm_password" type="password"></div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add To Owner List : </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_add_owner" id="edit_add_owner" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Master Support Email :</label>
                        </div>
                        <div class="col-md-6"><input placeholder="Enter Master Support Email" class="form-control" name="edit_support_email" id="edit_support_email" type="text"></div>
                    </div> 
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Ref Code</label>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>QR Ref Code:</label>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" disabled=""  name="edit_qr_refcode" id="edit_qr_refcode" type="text">
                            <!--<input class="form-control"   name="edit_qr_refcode_hidden" id="edit_qr_refcode_hidden" type="hidden">-->
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label>Account Package</label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Package:</label>
                        </div>

                        <div class="col-md-6">       
                            <select name="edit_package_type" id="edit_package_type" class="form-control">
                                <?php
                                foreach ($customer_package as $val) {
                                    ?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>  
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Verified: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_verify_package" id="edit_verify_package" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label> Annual Value: </label>
                        </div>
                        <div class="col-md-6">       
                            <input placeholder="Enter Annual Value" class="form-control" name="edit_annual_value" id="edit_annual_value" type="text"> 
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Additional Modules</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Compliance Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_compliance_module" id="edit_compliance_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Fleet Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_fleet_module" id="edit_fleet_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Condition Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_condition_module" id="edit_condition_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Depreciation Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_depreciation_module" id="edit_depreciation_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Reporting Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_reporting_module" id="edit_reporting_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Add Profile: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_profile" id="edit_profile" class="form-control">
                                <option value="0">None</option>
<?php foreach ($profilelist as $pro) { ?>
                                    <option value="<?php echo $pro->profile_id; ?>"><?php echo $pro->profile_name; ?></option>

<?php } ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="masterid" value="<?php echo $masterid; ?>" id="edit_masterac_id_cus"/>
                    <input type="hidden" name="edit_customer_id" id="edit_customer_id">

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>



<!--Edit multiple USer Credentials-->
<div class="modal fade" id="multiUserEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Multiple Account</h4>
            </div>
            <form action="<?php echo base_url('youaudit/franchise_admins/editMultipleAccount'); ?>" method="post" id="edit_multipleuser_account">
                <div class="modal-body" style="height:325px;">
                    <input hidden="" name="account_id" id="multiComIds">
                        <input type="hidden" name="masterid" id="master_account_id" value="<?php echo $masterid; ?>"/>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Package:</label>
                        </div>

                        <div class="col-md-6">       
                            <select name="multiple_package_type" id="multiple_package_type" class="form-control">
                                <?php
                                foreach ($customer_package as $val) {
                                    ?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>  
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                     

                    
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Compliance Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_compliance_module" id="multiple_compliance_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Fleet Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_fleet_module" id="multiple_fleet_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Condition Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_condition_module" id="multiple_condition_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Depreciation Module: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_depreciation_module" id="multiple_depreciation_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6"> <label>Reporting Modules: </label>
                        </div>
                        <div class="col-md-6">       
                            <select name="multiple_reporting_module" id="multiple_reporting_module" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                  

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_button_system">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
















