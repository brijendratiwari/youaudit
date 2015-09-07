<style>
    .modal-body{
        min-height: 350px;
        max-height: 595px;
        overflow-y: scroll;
    } 
</style>
<script>
    $(document).ready(function()
    {
        $("#report_fault_form").validate({
            rules: {
                severity: "required",
                status: "required",
                order: {required: true, min: 1},
                job_notes: "required"
            },
            messages: {
                severity: "Please Enter Severity",
                status: "Please Enter Status",
                order: "Please Select order",
                job_notes: "Please Enter Job Note"
            }
        });

        $("#update_fault_form").validate({
            rules: {
                action: {required: true, min: 1},
                status: {required: true, min: 1},
                reason_code: {required: true, min: 1},
                job_notes: "required"
            },
            messages: {
                action: "Please Select Action",
                status: "Please Select Status",
                reason_code: "Please Select Reason Code",
                job_notes: "Please Enter Job Note"
            }
        });

        $("#fix_item_form").validate({
            rules: {
                action: {required: true, min: 1},
                status: {required: true, min: 1},
                fix_code: {required: true, min: 1},
                job_notes: "required"
            },
            messages: {
                action: "Please Select Action",
                status: "Please Select Status",
                fix_code: "Please Select Fix Code",
                job_notes: "Please Enter Job Note"
            }
        });

        var fault_table = $("#fault_table").DataTable({
            "ordering": true,
            "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
            "iDisplayLength": 10,
            "bDestroy": true, //!!!--- for remove data table warning.
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [6]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [7]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [8]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [9]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [10]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [11]},
                {"sClass": "eamil_conform aligncenter", "aTargets": 12},
                {"sClass": "eamil_conform aligncenter", "aTargets": [13]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [14]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [15]},
                {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [16]},
            ]}

        );
    }
    );
</script>
<div class="row">
    <h1>"Faults - Current Open Jobs"</h1>
</div>
<div class="row">
    <div style="margin-top: 10px;" class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-1">

                    <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-file-word-o"></i>
                        <b>Export CSV</b></button> 
                </div><div class="col-lg-2">
                    <button id="b1" type="button" class="btn btn-primary btn-xs" ><i class="fa  fa-file-pdf-o"></i>
                        <b> Export PDF</b></button>
                </div>
                <div class="col-lg-2">
                    <button data-toggle="modal" data-target="#" id="hide_column" type="button" class="btn btn-primary btn-xs"><i class="fa fa-trash-o"></i>
                        <b>Show Hide Columns</b></button>
                </div><div class="col-lg-1">
                    <button data-toggle="modal" data-target="#" id="clear_filter" type="button" class="btn btn-primary btn-xs"><i class="fa fa-repeat"></i>
                        <b>Clear Filters</b></button> 
                </div>
                <div class="col-lg-3">
                    <button data-toggle="modal" data-target="#report_fault" id="item_add" type="button" class="btn btn-primary btn-xs"><i class="fa fa-plus-square-o"></i>
                        <b>Report Fault</b></button>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="panel-body">

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="fault_table" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>QR Code</th>
                                <th>Category</th>
                                <th>Item/Menu</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Site</th>
                                <th>Location</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Date Fault Reported</th>
                                <th>Fault Time</th>
                                <th>Severity</th>
                                <th>Enter Order No</th>
                                <th>Job Notes</th>
                                <th>Actions</th>
                            </tr>
                            <tr>
                                <th>[ ] Edit</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th> 
                            </tr>
                        </thead>
						<?php if(!empty($fullItemsData)) { 
							foreach($fullItemsData as $value ) { 
							echo "<pre>"; print_R($value); die;
						?>
                        <tbody id="asset_body">
                            <tr>
                                <td>[ ]</td>
                                <td>PSD2345</td>
                                <td>Power Tools</td>
                                <td>Drills</td>
                                <td>Bosch</td>
                                <td>BG654</td>
                                <td>Adelaide</td>
                                <td>Workshop</td>
                                <td>Service Team</td>
                                <td>Faulty</td>
                                <td>Open Job</td>
                                <td>22/12/14</td>
                                <td>1 mth 13 days</td>
                                <td>High</td>
                                <td>PO9876</td>
                                <td>Drill Bit teeth broken battery low</td>
                                <td><a id="" data-toggle="modal" href="#update_fault" data_customer_id='' title="Update Fault" class="updatefault"><i class="fa  fa-recycle"></i></a>&nbsp;<a data-toggle="modal" href="#fix_item" title="Fix item" class="fixitem" data_customer_id=''><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-toggle="modal" href="#view_fault" title="View Fault" class="viewfault" data_customer_id=''><i class="fa fa-eye"></i></a></td>
                            </tr> 
							<?php 	} //end of foreach		
								} // End of if
							?>	
							
							</tbody>
                        <tfoot><tr><td colspan="3">Totals / Count</td>
                                <td>34</td>
                                <td colspan="13"></td>
                            </tr></tfoot>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>
<!-- Report Fault Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="report_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title">Report Fault</h4>
            </div>

            <form action="#" method="post" id="report_fault_form">
                <div class="modal-body">
                    <!-- Report Fault -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Report Fault</h4></label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>"Item/Menu" "Manufacturer"</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>"QR CODE" "Category" "Location"</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Severity</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Severity" class="form-control" name="severity" id="severity">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Status</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Status" class="form-control" name="status" id="status">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Enter Order No</label> </div>
                        <div class="col-md-6">  <select name="order" id="order" class="form-control">
                                <option>----select----</option>  
                            </select>
                        </div></div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                    </div>

                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

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
<!-- View Fault Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title">View Fault</h4>
            </div>

            <div class="modal-body">
                <!-- View Fault -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label><h4>View Fault</h4></label> </div>
                </div>
                <div class="form-group col-md-12">
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>"Item/Menu" "Manufacturer"</label> </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>"QR CODE" "Category" "Location"</label> </div>
                </div>
                <div class="form-group col-md-12">
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Status</label> </div>
                    <div class="col-md-6">  <input placeholder="Enter Status" class="form-control" name="status" id="status" disabled>
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Action</label> </div>
                    <div class="col-md-6">  <input placeholder="Enter Action" class="form-control" name="action" id="action" disabled>
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Reason Code</label> </div>
                    <div class="col-md-6">  <input placeholder="Enter Reason Code" class="form-control" name="reason_code" id="reason_code" disabled>
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                </div>

                <!-- Job Notes -->
                <div class="form-group col-md-12">
                    <div class="col-md-6">      <label>Job Notes</label>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2" disabled></textarea>  
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Fix Item Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="fix_item" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title">Fix Item</h4>
            </div>

            <form action="#" method="post" id="fix_item_form">
                <div class="modal-body">
                    <!-- Fix Item -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Fix Update Fault</h4></label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>"Item/Menu" "Manufacturer"</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>"QR CODE" "Category" "Location"</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Action</label> </div>
                        <div class="col-md-6"> <select name="action" id="action" class="form-control">
                                <option>----select----</option>  
                            </select> 
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Status</label> </div>
                        <div class="col-md-6">  <select name="status" id="status" class="form-control">
                                <option>----select----</option>  
                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Fix Code</label> </div>
                        <div class="col-md-6">  <select name="fix_code" id="fix_code" class="form-control">
                                <option>----select----</option>  
                            </select>
                        </div></div> <!-- /.form-group -->
                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

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
<!-- Update Fault Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="update_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title">Update Fault</h4>
            </div>

            <form action="#" method="post" id="update_fault_form">
                <div class="modal-body">
                    <!-- Fix Item -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label><h4>Fix Update Fault</h4></label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>"Item/Menu" "Manufacturer"</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>"QR CODE" "Category" "Location"</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                    </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Action</label> </div>
                        <div class="col-md-6"> <select name="action" id="action" class="form-control">
                                <option>----select----</option>  
                            </select> 
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Status</label> </div>
                        <div class="col-md-6">  <select name="status" id="status" class="form-control">
                                <option>----select----</option>  
                            </select>
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Reason Code</label> </div>
                        <div class="col-md-6">  <select name="reason_code" id="reason_code" class="form-control">
                                <option>----select----</option>  
                            </select>
                        </div></div> <!-- /.form-group -->
                    <!-- Job Notes -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Job Notes</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-12"><textarea placeholder="Enter Job Notes" class="form-control" name="job_notes" id="job_notes" cols="10" rows="2"></textarea>  
                        </div>
                    </div>

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
