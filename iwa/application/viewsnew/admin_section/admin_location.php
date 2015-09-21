<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<style>
    .modal-body{
        min-height: 200px;
        max-height: 595px; 
        overflow-y: scroll;
    } 
    .qty {
        width: 40px;
        height: 25px;
        text-align: center;
    }
    input.qtyplus { width:25px; height:25px;}
    input.qtyminus { width:25px; height:25px;}
    .qrcode_error
    {
        color: red;
        font-weight: bold;
    }
    .locqrcode_error
    {
        color: red;
        font-weight: bold;
    }

    .edit_qrcode_error
    {
        color: red;
        font-weight: bold;
    }

    .multiadd_qrcode_error
    {
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
    .locationbar {
        float: left !important;
        width: 80% !important;
    }
    .location_bar
    {
        float: left !important;
        width: 62% !important;  
    }
    .grp_addon
    {
        float: left;
        padding: 9px;
        width: 38%;   
    }
    .errors
    {
        color: red;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function() {

        $('#multiple_category').on('click', function()
        {
            $("#count_row").val(1);
            $('.qty').attr('value', 1);
            $('input[name=quantity]').val(1);
            $('.multiple').remove();
        });
// This button will increment the value
        $('.qtyplus').click(function(e) {


            $('').clone().appendTo('#tbody_multiple_cat');
            var count = $("table.reference tr").length;
            var $clone = $("#row_1").clone();
            $clone.attr({
                id: "emlRow_" + count,
                name: "emlRow_" + count,
                style: "" // remove "display:none"
            });
            $clone.find(".multicustom").each(function() {
                $(this).attr({
                    id: $(this).attr("id") + (count - 1),
                    name: $(this).attr("name") + (count - 1) + '[]',
                });
                $("#count_row").val(count - 1);
                $('.qty').val((count - 1));
            });
            $clone.find(".multiemail").each(function() {
                $(this).attr({
                    id: $(this).attr("id") + (count - 1),
                    name: $(this).attr("name") + (count - 1)
                });
                $("#count_row").val(count - 1);
                $('.qty').val((count - 1));
            });
            $clone.find("input").each(function() {
                $(this).attr({
                    id: $(this).attr("id") + (count - 1),
                    name: $(this).attr("name") + (count - 1),
                    data: (count - 1)
                });
                $("#count_row").val(count - 1);
                $('.qty').val((count - 1));
            });
            $clone.find("div").each(function() {
                $(this).attr({
                    id: $(this).attr("id") + (count - 1),
                });
                $("#count_row").val(count - 1);
                $('.qty').val((count - 1));
            });
            count++;
            $("#tbody_multiple_cat").append($clone);
        });
        $(".qtyminus").click(function(e) {
            var num_row = $("#tbody_multiple_cat tr").length;
//            alert(num_row);
            if (num_row > 2) {
                $("#tbody_multiple_cat tr:last").remove();
                count = $("#count_row").val();
                sub = count - 1;
                $("#count_row").val(sub);
                $('.qty').val(sub);
            }
            else
            {
                $("#count_row").val(1);
            }

        });

        $('#multiple_category').on('click', function() {
            var total_count = $("#multiple_user tr").length;
            for (var i = 1; i < total_count - 1; i++)
            {
                $('#error_msg' + i).css('display', 'none');
                $('#location_msg' + i).css('display', 'none');
            }
        });
        $('.qtyplus').on('click', function() {
            var total_count = $("#multiple_user tr").length;
            for (var i = 1; i < total_count - 1; i++)
            {
                $('#error_msg' + i).css('display', 'none');
                $('#location_msg' + i).css('display', 'none');
            }
        });

        $('#multiadd_button').on('click', function() {
            var totalcount = $("#multiple_user tr").length;
            for (var n = 1; n < totalcount - 1; n++)
            {
                if ($('#site_name_' + n + ' option:selected').val() == "")
                {
                    $('#error_msg' + n).css('display', 'block');
                    $('#error_msg' + n).addClass('errors');
                    $('#multiadd_button').prop('disabled', true);
                    return false;
                }
            }
        });
        $("body").on("click", ".edit", function() {

            var locationname = $(this).attr("data_locationname");
            var qrcode = $(this).attr("data_qrcode");
            var barcode = qrcode.split($('#code').val());

            var sitename = $(this).attr("data_site_id");
            var owner = $(this).attr("data_owner_id");
            var adminuser_id = $(this).attr("data_adminuser_id");

            $("#edit_location_name").attr("value", locationname);
            $("#edit_qr_code").attr("value", barcode[1]);

            $("#edit_site_name option[value='" + sitename + "']").prop("selected", "selected");
            $("#edit_owner_id option[value='" + owner + "']").prop("selected", "selected");
            $("#editownerid").val(owner);
            $("#adminuser_id_1").attr("value", adminuser_id);


        });
        var user_locations = $("#Admin_Location").DataTable({
            "ordering": true,
            "aLengthMenu": [[40, 80, -1], [40, 80, "All"]],
            "iDisplayLength": 40,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnRowCallback": function(nRow, aData) {

                var $nRow = $(nRow); // cache the row wrapped up in jQuery
                tdhtm = $nRow.children()[6].innerHTML;

                if (tdhtm.search("enable") != -1) {
                    $nRow.css("background-color", "#f2b4b4");
                }

                return nRow;
            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [2]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [3]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [4]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [5]},
                {"sClass": "eamil_conform aligncenter", "aTargets": [6]}
            ]}
        );
        $("#addlocation").validate({
            rules: {
                location_name: "required",
                site_name: "required"
            },
            messages: {
                location_name: "Please Enter Location Name",
                site_name: "Please Enter Site Name"
            }
        });
        $("#edit_location_form").validate({
            rules: {
                edit_location_name: "required",
//                edit_qr_code: "required",
                edit_site_name: "required"
            },
            messages: {
                edit_location_name: "Please Enter Location Name",
//                edit_qr_code: "Please Enter QR Code",
                edit_site_name: "Please Enter Site Name"
            }
        });

        $("#qr_code").on("keyup blur", function() {
            var pre = $("#code").val();
            var qr_code = $("#qr_code").val();
            var code = pre + qr_code;
            var base_url_str = $("#base_url").val();
            if (qr_code != '') {
                $.ajax({
                    type: "POST",
                    url: base_url_str + "admin_section/checkQRNumber",
                    data: {
                        'qr_code': code
                    },
                    success: function(msg) {

                        // we need to check if the value is the same
                        if (msg == "1") {
                            //Receiving the result of search here

                            $("#qrcode_error").removeClass("hide");
                            $("#save_button").attr("disabled", true);
                        } else {

                            $("#qrcode_error").addClass("hide");
                            $("#save_button").attr("disabled", false);
                        }
                    }

                });
            }

        });

        $("#edit_qr_code").on("keyup blur", function() {

            var code = $('#code').val();
            var qr_code = $("#edit_qr_code").val();
            var qrcode = code + qr_code;
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkQRNumber",
                data: {
                    'qr_code': qrcode
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here

                        $("#edit_qrcode_error").removeClass("hide");
                        $("#edit_save_button").attr("disabled", true);
                    } else {

                        $("#edit_qrcode_error").addClass("hide");
                        $("#edit_save_button").attr("disabled", false);

                    }
                }

            });

        });

        $("#qrcode1").on("keyup blur", function() {
            var pre = $("#code").val();
            var qr_code = $("#qrcode1").val();
            var code = pre + qr_code;
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkQRNumber",
                data: {
                    'qr_code': code
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {

                        $("#multiadd_qrcode_error").removeClass("hide");
                    } else {

                        $("#multiadd_qrcode_error").addClass("hide");
                    }
                }

            });

        });
    });
    function check_barcode(multiple)
    {
        var multi_bar = multiple.id;
        var barcode = multi_bar.split('qrcode_');
        var pre = $("#code").val();
        var qr_code = $("#qrcode_" + barcode[1]).val();
        var code = pre + qr_code;
        var base_url_str = $("#base_url").val();
        if (qr_code != '') {
            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkQRNumber",
                data: {
                    'qr_code': code
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here

                        $("#locqrcode_err" + barcode[1]).removeClass("hide");
                        $("#multiadd_button").attr("disabled", true);
                    } else {

                        $("#locqrcode_err" + barcode[1]).addClass("hide");
                        $("#multiadd_button").attr("disabled", false);
                    }
                }

            });
        }
    }
    function checksite(site)
    {
        var data = site.id;
        var site_name = data.split('site_name_');
        var site_id = site_name[1];
        if ($('#site_name_' + site_id + ' option:selected').val() == "")
        {
            $('#error_msg' + site_id).css('display', 'block');
            $('#error_msg' + site_id).addClass('errors');
            $('#multiadd_button').prop('disabled', true);
            return false;
        }
        else
        {
            $('#error_msg' + site_id).css('display', 'none');
            $('#multiadd_button').removeAttr('disabled');
            return true;
        }
    }
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this Location ?", function(result) {
            if (result) {
                window.location.href = url;
            } else {
                // Do nothing!
            }
        });
    }
</script> 
<input type="hidden" id="code" value="<?php echo $arrSessionData["objSystemUser"]->qrcode; ?>">
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Locations</h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li ><a data-toggle="" href="<?php echo base_url('admin_section/admin_user'); ?>">Users</a>
                    </li>
                    <li ><a data-toggle="" href="<?php echo base_url("admin_section/admin_owner"); ?>">Owners</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url("admin_section/admin_categories"); ?>">Categories</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/'); ?>">Items</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_sites'); ?>">Sites</a>
                    </li>
                    <li class="active"><a data-toggle="" href="<?php echo base_url('admin_section/admin_location'); ?>">Location</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/customFields'); ?>">Custom Fields</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_supplier'); ?>">Suppliers/Customers</a>
                    </li>
                    <li><a data-toggle="" href="<?php echo base_url('admin_section/admin_archive'); ?>">Archive</a>
                    </li>
                    <?php
                    if ($arrSessionData['objSystemUser']->levelid == 3 || $arrSessionData['objSystemUser']->levelid == 4) {
                        ?>
                        <li><a data-toggle="" href="<?php echo base_url('admin_section/data_import'); ?>">Data Import</a>
                        </li>
                    <?php } ?>
                </ul>
                <!-- Tab panes -->

            </div>
            <!-- /.panel-body -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header"><?php echo $customer_data; ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-7">




                    <a  href="<?= base_url('admin_section/exportPDFForLocation/CSV') ?>" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>CSV</b>
                    </a>


                    <a  href="<?= base_url('admin_section/exportPDFForLocation/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>PDF</b></a>

                    <a  class="button icon-with-text round" id="create_location" data-target="#add_location" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add <br>Location</b></a>


                    <a class="button icon-with-text round" id="multiple_category" data-target="#add_multiple_category" data-toggle="modal"><i class="fa fa-plus"></i><b>Add Multiple<br>Locations</b></a>

                </div>
                <div class="text-right col-md-5">
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

        <!-- /.col-lg-12 -->
    </div>
</div>

<!--    </div>
</div>-->
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

    <div class="col-lg-12">

        <div class="panel-body">

            <div class="table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                    <table id="Admin_Location" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Location Name</th>
                                <th>Location QR Code</th>
                                <th>Site</th>
                                <th>Owner</th>
                                <th>Last Audit</th>
                                <th>Audit Result</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="Master_Customer_body">
                            <?php foreach ($locations as $location) {
                                ?>
                                <tr>
                                    <td><?php echo $location->name; ?></td>
                                    <td><?php
                                        if ($location->barcode != NULL) {
                                            echo $location->barcode;
                                        } else {
                                            echo "-";
                                        }
                                        ?></td>
                                    <td><?php echo $location->url; ?>
                                    </td>
                                    <td><?php echo $location->owner_name; ?></td>
                                    <td><?php
                                        if ($location->loc_date['date']) {
                                            echo date('d/m/Y H:i:s', strtotime($location->loc_date['date']));
                                        }
                                        ?></td>
                                    <td></td>
                                    <?php
                                    if ($location->active == 1) {
                                        $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $location->id . '" href="' . base_url('admin_section/disableLocation/' . $location->id) . '" data_adminuser_id=' . $location->id . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                    } else {
                                        $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $location->id . '" href="' . base_url('admin_section/enableLocation/' . $location->id) . '" data_adminuser_id=' . $location->id . '  title="enable" class="enableadminuser"><i class="fa  fa-play franchises-i"></i></a>Enable</span>';
                                    }
                                    ?>
                                    <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $location->id; ?>" href="#edit_location" title="Edit" data_locationname="<?php echo $location->name; ?>" data_site_id="<?php echo $location->site_id; ?>" data_owner_id="<?php echo $location->owner_id; ?>" data_qrcode="<?php echo $location->barcode; ?>" data_site="<?php echo $location->url; ?>" data_adminuser_id="<?php echo $location->id; ?>" class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span><?php echo $access_icon; ?><span class="action-w"><a  href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="<?php echo base_url('admin_section/archiveLocation/' . $location->id); ?>"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>



<!-- Modal For Add User -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_location" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Location</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_location'); ?>" method="post" id="addlocation">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Location Name :</label> </div>
                        <div class="col-md-7">  <input placeholder="Enter Location" class="form-control" name="location_name" id="location_name">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Location QR Code :</label> </div>
                        <div class="col-md-7">  
                            <div class="input-group col-md-12">
                                <div class="input-group-addon grpaddon">
                                    <?php echo $arrSessionData["objSystemUser"]->qrcode; ?></div>
                                <input placeholder="Enter Location QR Code" class="form-control locationbar" name="qr_code" id="qr_code">
                            </div>
                            <div id="qrcode_error" class="qrcode_error hide">QR Code Already Exist.</div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Site :</label> </div>
                        <div class="col-md-7"> <select class="form-control" name="site_name" id="site_name">  <?php
                                foreach ($arrSites['results'] as $site_value) {
                                    ?>
                                    <option value= "<?php echo $site_value->siteid ?>" ><?php echo $site_value->sitename; ?></option>
                                    <?php
                                }
                                ?></select>
                        </div>
                    </div><!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Owner</label> </div>
                        <div class="col-md-7">  <select name="new_owner_id" id="new_owner_id" class="form-control">
                                <option value="0">----SELECT----</option>
                                <?php
                                foreach ($arrOwners['results'] as $arrOwner) {
                                    echo "<option ";
                                    echo 'value="' . $arrOwner->ownerid . '" ';
                                    if ($objItem->userid == $arrOwner->ownerid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrOwner->owner_name . "</option>\r\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                </div>    </div>
        </form>
    </div>

</div>
<!-- /.modal-dialog -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_location" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit location</h4>
            </div>

            <form action="<?php echo base_url('admin_section/editLocation'); ?>" method="post" id="edit_location_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Location Name :</label> </div>
                        <div class="col-md-7">  <input placeholder="Enter Location" class="form-control" name="edit_location_name" id="edit_location_name">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Location QR Code :</label> </div>
                        <div class="col-md-7">  
                            <div class="input-group col-md-12">
                                <div class="input-group-addon"><?php echo $arrSessionData["objSystemUser"]->qrcode; ?></div>
                                <input placeholder="Enter Location QR Code" class="form-control locationbar" name="edit_qr_code" id="edit_qr_code">
                            </div>
                            <div id="edit_qrcode_error" class="edit_qrcode_error hide">QR Code Already Exist.</div>
                        </div>

                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Site :</label> </div>
                        <div class="col-md-7">  <select  class="form-control" name="edit_site_name" id="edit_site_name">

                                <?php
                                foreach ($arrSites['results'] as $site_value) {
                                    ?>
                                    <option value= "<?php echo $site_value->siteid ?>" ><?php echo $site_value->sitename; ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div><!-- /.form-group -->

                    <div class="form-group col-md-12">
                        <div class="col-md-5">  <label>Owner</label> </div>
                        <div class="col-md-7">  <select name="edit_owner_id" id="edit_owner_id" class="form-control">
                                <option value="0">----SELECT----</option>
                                <?php foreach ($arrOwners['results'] as $arrOwner) { ?>
                                    <option value= "<?php echo $arrOwner->ownerid; ?>" ><?php echo $arrOwner->owner_name; ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="editownerid" id="editownerid"/>
                    <input type="hidden" name="adminuser_id" id="adminuser_id_1"/>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="edit_save_button">Save</button>
                </div></div>
        </form>
    </div>

</div>



<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_multiple_category" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 650px;">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Multiple Locations</h4>
            </div>

            <form action="<?php echo base_url('admin_section/add_multiplelocations'); ?>" method="POST" id="updatepassword">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Add Multiple Locations :</label> </div> 

                        <div class="col-md-6">  
                            <!--<input type="Number" class="form-control" name="number_of_rows" value="1" id="number_of_rows">-->
                           <!-- <div class="input-group"><input id="users" type="text" data-min="1" class="form-control bfh-number"></div>-->
                            <input type='button' value='-' class='qtyminus' field='quantity' />
                            <input type='text' name='quantity' class='qty' disabled/>
                            <input type='button' value='+' class='qtyplus' field='quantity' />
                        </div>
                    </div> <!-- /.form-group -->


                    <table class="table table-striped table-bordered table-hover reference" id="multiple_user">
                        <thead>
                            <tr>
                                <th>Location Name</th>
                                <th>Location Qr Code</th>
                                <th>Site</th>
                                <th>Owner</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_multiple_cat">
                            <tr>
                                <td><input type="text" data=""  class="form-control multicat" required=""  name="location_name_1" id="location_name_1" placeholder="Enter Location Name"></td>
                                <td><div class="input-group">
                                        <div class="input-group-addon grp_addon">
                                            <?php echo $arrSessionData["objSystemUser"]->qrcode; ?></div>
                                        <input type="text" data=""  class="form-control multicat location_bar"  name="qrcode_1" id="qrcode_1" placeholder="Enter QR Code Na" onblur="check_barcode(this);"></div>
                                    <div id="locqrcode_err1" class="locqrcode_error hide">QR Code Already Exist.</div>
                                </td>
                                <td><select name="site_name_1" id="site_name_1" class="form-control multiemail" required="" onchange="checksite(this);">
                                        <option value=''>--select site--</option>
                                        <?php
                                        foreach ($arrSites['results'] as $site_value) {
                                            ?>
                                            <option value= "<?php echo $site_value->siteid ?>" ><?php echo $site_value->sitename; ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select>
                                <div id="error_msg1">A LOCATION MUST BE ALLOCATED TO A SITE</div></td>
                                <td><select name="multi_owner_id_1" id="multi_owner_id_1" class="form-control">
                                        <option value="0">--select owner--</option>
                                        <?php
                                        foreach ($arrOwners['results'] as $arrmultiOwner) {
                                            echo "<option ";
                                            echo 'value="' . $arrmultiOwner->ownerid . '" ';

                                            echo '>' . $arrmultiOwner->owner_name . "</option>\r\n";
                                        }
                                        ?>
                                    </select></td>                            

                            </tr>
                            <tr id="row_1" style="display:none">
                                <td><input type="text" data=""   class="form-control multicat"  name="location_name_" id="location_name_" placeholder="Enter Location Name"> </td>
                                <td><div class="input-group">
                                        <div class="input-group-addon grp_addon">
                                            <?php echo $arrSessionData["objSystemUser"]->qrcode; ?></div>
                                        <input type="text" data=""  class="form-control multicat location_bar"  name="qrcode_" id="qrcode_" placeholder="Enter QR Code Na" onblur="check_barcode(this);"></div>
                                    <div id="locqrcode_err" class="locqrcode_error hide">QR Code Already Exist.</div></td>
                                <td> <select name="site_name_" id="site_name_" class="form-control multiemail" onchange="checksite(this);">
                                        <option value=''>--select site--</option>
                                        <?php
                                        foreach ($arrSites['results'] as $site_value) {
                                            ?>
                                            <option value= "<?php echo $site_value->siteid ?>" ><?php echo $site_value->sitename; ?></option>
                                            <?php
                                        }
                                        ?>

                                    </select><div id="error_msg">A LOCATION MUST BE ALLOCATED TO A SITE</div></td>
                                <td><select name="multi_owner_id_" id="multi_owner_id_" class="form-control multiemail">
                                        <option value="0">--select owner--</option>
                                        <?php
                                        foreach ($arrOwners['results'] as $arrcopyOwner) {
                                            echo "<option ";
                                            echo 'value="' . $arrcopyOwner->ownerid . '" ';

                                            echo '>' . $arrcopyOwner->owner_name . "</option>\r\n";
                                        }
                                        ?>
                                    </select></td>    

                        <input type="hidden" name="account_id" value="<?php echo $user_data; ?>">
                        </tr>


                        </tbody>
                        <input type="hidden" name="count_row" id="count_row" value="1">

                        </thead>
                    </table>



                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="multiadd_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>














