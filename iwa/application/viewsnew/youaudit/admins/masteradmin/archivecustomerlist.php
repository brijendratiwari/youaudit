<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        height: 595px;
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




    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');
        
        bootbox.confirm("Do you want to restore this customer account?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
    function deleteadminTemplate(editObj) {
        var url = $(editObj).attr('data-href');
      
        bootbox.confirm("Do you want to restore this admin account?", function(result) {
            if (result) {
                
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }

</script>

<br>
<div class="panel panel-default">
    <div class="panel-heading">
        <b>  <?php echo strtoupper($account_name); ?> / CUSTOMER LIST </b>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-pills">
             <li ><a data-toggle="" href="<?php echo base_url("youaudit/customerlist/$masterid"); ?>">Customer List</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/Adminuser/$masterid"); ?>">Admin Users</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/master_admins/complianceChecks/$masterid"); ?>">Compliance Templates</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/profiles/$masterid"); ?>">Profiles</a>
            </li>
             <li class="active"><a  data-toggle="" href="<?php echo base_url("youaudit/master_admins/arcivelist/$masterid"); ?>">Archive Account</a>
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
                    <table id="Master_ArchiveCustomer_Datatable" class="table table-bordered" width="100%" cellspacing="0">
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
                <input type="hidden" name="masterid" id="master_account_id" value="<?php echo $masterid; ?>"/>
    </div>
        <div class="col-lg-6">
              <div class="panel-body">
            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="archiveAdminuser_datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">

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
                                <tr><th></th>
                                <th></th>
                                <th></th>

                                <th></th> </tr>
                        </tfoot>
                    </table>
                </div>
                 <input type="hidden" name="masterid" id="masterac_id" value="<?php echo $masterid; ?>"/>
            </div>
            <!-- /.table-responsive -->
        </div>
        </div>
       
    </div>
</div>


