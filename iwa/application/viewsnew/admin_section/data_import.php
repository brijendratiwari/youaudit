<?php // var_dump($this->session->flashdata('importDataMsg'));die; ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<?php $this->load->helper('text'); ?>
<style>
    .modal-body{
        min-height: 100px;
        max-height: 595px; 
        overflow-y: scroll;
    } 
    .glyphicon-chevron-up
    {
        cursor: pointer;
    }
    .glyphicon-chevron-down
    {
        cursor: pointer;
    }
    .import_error{
         color: red;
         font-weight: bold;
    }
    .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        min-height: 75px;
        overflow: auto !important;
    }
</style>
<script>
    $(document).ready(function() {
   var c=0; 
$("body").on("change", "#field_type", function()
        {
            var type = $('#field_type option:selected').val();
            if (type == 'pick_list_type')
            {
                $('#field_values').css('display', 'block');
            }
            else
            {
                $('#field_values').css('display', 'none');
            }
        });
        
        $("#add_custom_form").validate({
            rules: {
                field_name: "required"
            },
            messages: {
                field_name: "Please Enter Custom Field Name"

            }
        });
        $("#uploadForm").validate({
            rules: {
                no_of_asset: "required",
                file:"required"
            },
            messages: {
                no_of_asset: "Please select nuMber of assets",
                file: "Please select csv for upload"

            }
        });
        
        
          
        var user_owners = $("#User_owners").DataTable({
            "ordering": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
//            "scrollX":"auto",
//            "bScrollCollapse": true,

            "bDestroy": true, //!!!--- for remove data table warning.
            }
        );


         // estblish link and site link

        $("body").find('.site').change(function() {
           
            var data_id=$(this).attr('data_id');
            $("#location_"+ data_id).empty();
            var site_id = this.value;
            if (site_id != 0) {
                $.getJSON("<?php echo base_url('items/getlocationbysite'); ?>" + '/' + site_id, function(data) {
                    if (data.results.length != 0) {

                        var location_data = '';
                        for (var i = 0; i < data.results.length; i++) {
                            location_data += '<option value=' + data.results[i].id + '>' + data.results[i].name + '</option>';
                        }
                        $("#location_"+ data_id).append(location_data);
                    }
                    else {
                        $("#location_"+ data_id).append("<option value='0'>Not Set</option>");
                    }
                });
            }
            else {
                $.getJSON("<?php echo base_url('items/getalllocation'); ?>", function(data) {
                    if (data.results.length != 0) {

                        var location_data = '';
                        location_data += "<option value='0'>Not Set</option>";
                        for (var i = 0; i < data.results.length; i++) {
                            location_data += '<option value=' + data.results[i].locationid + '>' + data.results[i].locationname + '</option>';
                        }
                        $("#location_"+ data_id).append(location_data);
                    }
                    else {
                        $("#location_"+ data_id).append("<option value='0'>Not Set</option>");
                    }
                });
            }
        });
        
         // select site accroding to location for multi acc
        $("body").find('.location').change(function() {

                 
            var site_id = this.value;
            var data_id=$(this).attr('data_id');
         
            $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>" + '/' + site_id, function(data) {
               
                if (data.results.length != 0) {
                    $('#site_'+data_id+' option[value="' + data.results[0].site_id + '"]').attr('selected', 'selected');
                }
                else {
                    $('#site_'+data_id+' option[value="0"]').attr('selected', 'selected');
                }
            });
        });

  $(document).find('.item_date').datepicker({dateFormat: "dd/mm/yy"});
  
 
  $('#save_button').click(function(){
    var base_url = $("#base_url").val(); 
    var custom_name = $('#field_name').val();
    if($('#field_name').val()){
       
           $.ajax({
                type: "POST",
                url: base_url + "admin_section/import_custom_field",
                dataType: 'json',
                data: "&field_name=" + custom_name,
                success: function(data) {
                    if(data!=0){
                    $('#add_custom').modal('hide');
                    $('#User_owners tr').append($("<td>"));
        $('#User_owners thead tr>td:last').html($('#field_name').val());
        var custom_name = $('#field_name').val();
        $('#User_owners thead tr>td:last').append("<input type='hidden' name='custom_field_name[]' value='"+data+"'>");
        $('#User_owners tbody tr').each(function(){$(this).children('td:last').append($('<input type="text" readonly class="form-control" name="'+custom_name+'[]"><input type="hidden" name="count" value="'+c+'">'));   c++;});
      
                }
                else{
                $('#add_custom').modal('hide');
                 $('#custom_error').append('<div class="alert alert-warning alert-dismissable">' +
                                            '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>' +
                                            ' Custom Field Is Already Exits</div>');
                }
                }
            });
        
    }else{alert('Custom Field Limit Is Over');}
});
   
    $("body").find('#edit_field').click(function() {
    $(document).find("#User_owners td input").removeAttr('readonly');
    $(document).find("#User_owners td select").removeAttr('readonly');
    });
   
     var tds = $("#User_owners").children('tbody').children('tr').children('td').length;
    if(tds>1){
        
   $(document).find("#customfield_data").prop("disabled",false);
    }
    
   $(document).find(".category").blur(function(){
       
       if(this.value =='' || this.value =='0'){
       $(this).next().empty();
        $(this).next().append("please enter category name");
     
         $(document).find("#import_save_button").prop("disabled",true);
        }
        else{
         $(this).next().empty();
         $(document).find("#import_save_button").prop("disabled",false);
        }
      
    });
   
    $(document).find(".site").blur(function(){
      
       if(this.value =='' || this.value =='0'){
       $(this).next().empty();
        $(this).next().append("please enter site name");
         $(document).find("#import_save_button").prop("disabled",true);
        }
        else{
         $(this).next().empty();
         $(document).find("#import_save_button").prop("disabled",false);
        }
      
    }); 
    
    $(document).find(".location").blur(function(){
      
       if(this.value =='' || this.value =='0'){
       $(this).next().empty();
        $(this).next().append("please enter location name");
         $(document).find("#import_save_button").prop("disabled",true);
        }
        else{
         $(this).next().empty();
         $(document).find("#import_save_button").prop("disabled",false);
        }
      
    }); 
    
     $(document).find(".owner").blur(function(){
      
       if(this.value =='' || this.value =='0'){
       $(this).next().empty();
        $(this).next().append("please enter owner name");
         $(document).find("#import_save_button").prop("disabled",true);
        }
        else{
         $(this).next().empty();
         $(document).find("#import_save_button").prop("disabled",false);
        }
      
    }); 
    
   
   
   });
   
    function saveTemplate(editObj) {
        var url = $(editObj).attr('data-href');
        
        bootbox.confirm("Do you want to import this data ?", function(result) {
            if (result) {
               $('#import_form').submit();
            } else {
                // Do nothing!
            }
        });
    }
    
    
  
</script>


<?php
if ($this->session->flashdata('importDataMsg')) {
    
    $importMsg = $this->session->flashdata('importDataMsg');
    if(empty($importMsg['existedQrCode'])){
        
        $msg = 'Data Import Successfully';
    }else{
        foreach($importMsg['existedQrCode'] as $qrCode){
            $qrCodes[] = $qrCode;
        }
        $msg = "There is some QrCode which is allready existing,Can't be import '".  implode(',',$qrCodes)."'";
    }
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $msg; ?>
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
<div id="custom_error">
   
</div>
<div class="row">
    <div class="col-lg-12">
      <div class="text-right col-md-5" style="float:right">
                    <span class ="com-name"><?= $arrSessionData['objSystemUser']->accountname; ?>
                        <!--<img src="<?= base_url('/img/circle-red.png'); ?>" width="60" />-->
                    </span>
                    <?php
                    $logo = 'logo.png';
                    if (isset($this->session->userdata['theme_design']->logo) && $this->session->userdata['theme_design']->logo != '') {

                        $logo = $this->session->userdata['theme_design']->logo;
                    }
                    ?>

                    <div class="logocls">
                        <img  alt="iSchool"  class="imgreplace" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>"  >

                    </div>
                </div>
    </div>
    
</div>

<div class="row">
    <div class="col-lg-12   ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>  Admin  </h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li ><a data-toggle="" href="<?php echo base_url('admin_section/admin_user'); ?>">Users</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url("admin_section/admin_owner"); ?>">Owners</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url("admin_section/admin_categories"); ?>">Categories</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/'); ?>">Items</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_sites'); ?>">Sites</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_location'); ?>">Location</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/customFields'); ?>">Custom Fields</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_supplier'); ?>">Suppliers/Customers</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_archive'); ?>">Archive</a>
                    </li>
                     <li  class="active"><a data-toggle="" href="<?php echo base_url('admin_section/data_import'); ?>">Data Import</a>
                    </li>
                </ul>

                <!-- Tab panes -->

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header">Data Import</h1>
    </div>
  
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
    <form id="uploadForm" action="<?php echo base_url('admin_section/data_import'); ?>" method="post" enctype="multipart/form-data"> 
                <div class="col-lg-3" style="border-style:solid;border-width:0.5px; padding: 16px 4px 5px 34px">
                    
                    
                     <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon">Enter First QR Code-<?php echo $this->session->userdata('objSystemUser')->qrcode; ?></div>
      <input type="number" style="width:50%" class="form-control"  name="start_qrcode" id="start_qrcode" value="001">
     
    </div>
  </div>
                                       <div class="form-group">
    <div class="input-group">
      <div class="input-group-addon">Number To Asset Importing</div>
      <input type="number" style="width:50%" name="no_of_asset" value="001" class="form-control" id="no_of_asset">
     
    </div>
  </div>
                </div>
                                <div class="col-lg-3">

                   
                                    <button class="button icon-with-text round" type="button" disabled id="customfield_data" data-target="#add_custom" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add Custom Field</b></button>

                                 <button type="button" class="button icon-with-text round" id="edit_field">
                        <i class="glyphicon glyphicon-edit franchises-i"></i>
                        <b>Edit</b>
                    </button>
                   
                       
                                    <button type="button" id="import_save_button" onclick="saveTemplate(this)" class="button icon-with-text round"><i class="fa fa-arrow-circle-down"></i><b>Save</b></button>
                </div>
                <div class="col-lg-2">
                    <a  href="<?= base_url('admin_section/getCsvForUpload/'.$this->session->userdata('objSystemUser')->accountid.'') ?>" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Download Import CSV File</b>
                    </a>
             </div>
                <div class="col-lg-4" >
                    <div style="margin-top:30px;">
                            <span class="col-lg-12">
                                <span style="padding: 0" class="col-lg-8"> 
                                    <span class="file-select">Select CSV File <i class="fa fa-sort pull-right"></i></span>
                                   <input type="file" style="opacity: 0" value="upload" name="file" class="item_photo"> </span>
                                <input type="hidden" value="<?php echo $this->session->userdata('objSystemUser')->qrcode; ?>" name="qr_code">
                                <span style="padding: 0" class="col-lg-3"><button  type="submit" id="import_csv" class="grad pic_button">UPLOAD</button></span>
                          
                            </span>
                        </div>
                </div>
    </form>  
            </div>
        </div>

    </div>
</div>
<form action="<?php echo base_url('admin_section/add_import_data'); ?>" method="post" id="import_form">
<div class="row" style="overflow-x: auto;">
    <div class="col-lg-12">

        <div class="panel-body">
            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="User_owners" class="table table-bordered"  cellspacing="0">
                        <thead id="table_head">
                            <tr>
                                 <th>QR Code</th>
                                <?php foreach($head as $headKey => $headVal){ ?>
                                <th><?php echo $headKey; ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                           
                           <?php echo $import_data;?> 
                               
                                
                                
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div></form>



 <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_custom" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Custom Field</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add'); ?>" method="post" id="add_custom_form">
                <div class="modal-body modbody">
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Custom Field Name :</label> </div>
                        <div class="col-md-7">  <input placeholder="Enter Custom Field Name" class="form-control" name="field_name" id="field_name">
                        </div>
                    </div> <!-- /.form-group -->

                   
              

                    <input type="hidden" name="sites" value="1">
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="button" id="save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>







<!-- /.col-lg-12 -->
</div>


</div>
</div>
</div>



<!-- Modal For Add User -->
