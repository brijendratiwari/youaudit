<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        height: 595px;
        overflow-y: scroll;
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

        bootbox.confirm("Do you want to restore this Master Account ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
    function restorefranchise(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to restore this Franchise Account ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
    function restoresystem(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to restore this System Admin Account ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
</script>



<?php
if ($this->session->flashdata('success')) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php
}
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
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Archive Master Account</h1>
                </div>
                <div class="col-lg-6">
                    <h1 class="page-header" align="center">Archive Franchise Account</h1>
                </div>
            </div>
<div class="row">
    <div class="col-lg-12">
 <div class="col-lg-6">
        <div class="panel-body">

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="master_archivedatatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                               
                                <th>Admin Name</th>
                                <th>Company Name</th>
                                <th>Contact Name</th>
                              
                             
                                <th>Contact Username</th>
                                <th>Account Limit</th>
                                <th>Total Value</th>
                                <th>Action</th>
                            </tr>
                          
                        </thead>
                        <tbody id="master_body">
                        </tbody>
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
                    <table id="franchise_archivedatatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                               
                                <th>Franchise Name</th>
                                <th>Company Name</th>
                                <th>Contact Name</th>
                                <th>Contact Username</th>
                                <th>Account Limit</th>
                                <th>Total Value</th>
                                <th>Action</th>
                            </tr>
                           
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.table-responsive -->
        </div>
    </div>
    </div>
</div>

<div class="row">
     <div class="row">

                <div class="col-lg-5">

                </div>
                <div class="col-lg-4">
                    <h1 class="page-header">Archive System Admin User</h1>
                </div>
                <div class="col-lg-3">

                </div>

            </div>
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">
            <div class="table-responsive">
                 <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                <table id="archivesystemAdmin_datatable" class="table table-bordered" width="100%" cellspacing="0">
               
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
                </table>
                 </div>
            </div>
            <!-- /.table-responsive -->
        </div>
    </div>
</div>