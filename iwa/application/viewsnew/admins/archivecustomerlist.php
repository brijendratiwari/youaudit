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
        overflow-y: auto!important;
    }
</style>
<script>

    $(document).ready(function () {
       

          
     


       




        var mcustomer_table = $("#MasterCustomer_Datatable").DataTable({
//        "oLanguage": {
            // "sProcessing": "<div align='center'><img src='" + base_url_str + "/assets/img/ajax-loader.gif'></div>"},
            "ordering": true,
            //"bProcessing": true,
            //"bServerSide": true,
            //"sAjaxSource": base_url_str + "admins/viewAccounts/" + account_id, "bDeferRender": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnDrawCallback": function() {
                var api = this.api();
                $(api.column(6).footer()).html(
                        api.column(6, {page: 'current'}).data().sum()
                        );
                $(api.column(8).footer()).html(
                        api.column(8, {page: 'current'}).data().sum()
                        );
            
               

            },
                 
            "aoColumnDefs": [
                {"bSortable": true, "aTargets": [0]},
                {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]},
              
             
            ]}
        );
        $("body").on("change", "#states", function () {
            mcustomer_table.column(3)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#acc_package", function () {
            mcustomer_table.column(6)
                    .search(this.value)
                    .draw();
        });
        
        
             var master_table = $("#Adminarchiveuser").DataTable();
 
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
        function restoretemplate(editObj){
            var url = $(editObj).attr('data-href');
       
            bootbox.confirm("Do you want to restore this admin user account?", function(result) {
                if (result) {
                    window.location.href=url;
                } else {
                    // Do nothing!
                }
            });
        }


</script>


<div class="row">
    <div class="col-lg-12">
     <h4 class="page-header"><?php echo strtoupper($this->session->userdata('ParentAccountName')); ?> / Archive Customer List</h4>
    </div>
 
  
    <!-- /.col-lg-12 -->
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
if ($this->session->flashdata('error')) {
    ?>
    <div class="alert alert-danger alert-dismissable">
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
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-customerlist">
                    <table id="MasterCustomer_Datatable" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                              
                                <th>Customer Name</th>
                                <th>City</th>
                                <th>State</th>
                                <th>QR Ref Code</th>
                                <th>Account Package</th>
                                <th>Annual Value</th>
                                <th>No of Assets</th>
                                <th>AC Created Date</th>
                                <th>No of User</th>
                                <th>Actions</th>
                            </tr>
                           
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php
                            foreach ($arrAccounts['results'] As $customer_detail) {
                                ?>
                                <tr>
                                 
                                  
                                    <td><?php echo $customer_detail->company_name; ?></td>
                                    <td><?php echo $customer_detail->city; ?></td>
                                    <td><?php echo $customer_detail->state; ?></td>
                                    <td><?php echo $customer_detail->qr_refcode; ?></td>
                                    <td><?php echo $customer_detail->package_name; ?></td>
                                    <td><?php echo $customer_detail->annual_value; ?></td>
                                    <td><?= $customer_detail->noOfAsset; ?></td>
                                    <td><?php echo date('d/m/Y', $customer_detail->create_date) ?></td>
                                    <td><?= $customer_detail->noOfUser; ?></td>
                                    <td>
                                        <?php
                                  
                                            $active = '<span class="action-w">  <a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="'.base_url('/admins/restoreCustomer/' . $customer_detail->customer_id . '/').'" title="Restore" class="Restore"><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>';
                                    echo $active;
                                        ?>
                                      
                                       
                                     
                                      

                                    </td>
                                </tr>

                                <?php
                            }
                            ?>
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
                    <table id="Adminarchiveuser" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">

                        <thead>
                            <tr>
                               
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="master_body">
                        <?php 
                        if(!empty($arrAdmins['results'])){
                        foreach($arrAdmins['results'] as $arrAdmin)
                        {
                        ?>
                            
                            <tr>
                                <td><?php echo $arrAdmin->firstname;?></td>
                                <td><?php echo $arrAdmin->lastname;?></td>
                                <td><?php echo $arrAdmin->username;?></td>
                                <td>
                                   
                                   <span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="restoretemplate(this)" data-href="<?php echo base_url('/admins/reactiveAdmin/'.$arrAdmin->adminid.'/'); ?>" title="Archive" alt="Archive" /><i class="fa fa-mail-reply franchises-i"></i></a>Restore</span>
            </td>
                            </tr>
                            <?php 
                        }
                        }
?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.table-responsive -->
        </div>
    </div>
        </div>
</div>


<!-- Modal for add master acc -->
<!-- Modal for add master acc -->
