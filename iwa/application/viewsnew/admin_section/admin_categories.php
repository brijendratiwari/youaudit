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
    .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        min-height: 75px;
        overflow: auto !important;
    }
    #add_multiple_category .modal-content
    {
        width: 980px;
    }
    #tbody_multiple_cat .form-control{
        
        padding: 3px !important ;
        font-size: 11px !important;
    }
    #editmultipleuser
    {
        width: 100%;
        text-indent: -37px;
    }
    .eamil_conform.aligncenter.sorting {
    	width: 210px !important;
	}
	#Admin_Category th:first-child, #Admin_Category td:first-child { 
	text-align:center;
	}
	#Admin_Category th:first-child { 
	text-align:left !important;
	}

</style>
<script>
    $(document).ready(function() {

        $(".manuitem").addClass('hidden');
        $("#edit_button").click(function() {

            $(".manuitem").removeClass('hidden');
            $(".multicatg").addClass('hidden');

        });

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
                    required: true,
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
        // Script For Select User
        $("#select_user1").on('change', function() {

            //$("#select_user2").reset();
            var str = ($(this).val());
            var user2 = [];
            var option = '';
            $("#select_user2 option").each(function() {
                $(this).removeAttr("disabled");
            });
            $("#select_user3 option").each(function() {
                $(this).removeAttr("disabled");
            });
            $("#select_anather_user option").each(function() {
                $(this).removeAttr("disabled");
            });
            $("#select_user2 option").each(function() {
                if ($(this).val() == str)
                {
                    $(this).attr("disabled", "true");

                }


            });
            $("#select_user3 option").each(function() {
                if ($(this).val() == str)
                {
                    $(this).attr("disabled", "true");

                }


            });
            $("#select_anather_user option").each(function() {
                if ($(this).val() == str)
                {
                    $(this).attr("disabled", "true");

                }


            });

        });
        $("#select_user2").on('change', function() {

            //$("#select_user2").reset();
            var str2 = ($(this).val());
            var user3 = [];
            var option = '';

            $("#select_user3 option").each(function() {
                if ($(this).val() == str2)
                {
                    $(this).attr("disabled", "true");
                    //option=option+"<option value='"+$(this).val()+"'>"+$(this).text()+"</option>";
                }


            });
            $("#select_anather_user option").each(function() {
                if ($(this).val() == str2)
                {
                    $(this).attr("disabled", "true");
                    //option=option+"<option value='"+$(this).val()+"'>"+$(this).text()+"</option>";
                }


            });
        });

        $("#select_user3").on('change', function() {

            //$("#select_user2").reset();
            var str2 = ($(this).val());
            var user3 = [];
            var option = '';

            $("#select_anather_user option").each(function() {
                if ($(this).val() == str2)
                {
                    $(this).attr("disabled", "true");
                    //option=option+"<option value='"+$(this).val()+"'>"+$(this).text()+"</option>";
                }


            });
        });


        // 
        // Script For Select Edit  User 
        $("#edit_selectuser1").on('change', function() {

            //$("#select_user2").reset();
            var str = ($(this).val());
            var user2 = [];
            var option = '';
            $("#edit_selectuser2 option").each(function() {
                $(this).removeAttr("disabled");
            });
            $("#edit_selectuser3 option").each(function() {
                $(this).removeAttr("disabled");
            });
            $("#edit_select_user4 option").each(function() {
                $(this).removeAttr("disabled");
            });
            $("#edit_selectuser2 option").each(function() {
                if ($(this).val() == str)
                {
                    $(this).attr("disabled", "true");

                }


            });
            $("#edit_selectuser3 option").each(function() {
                if ($(this).val() == str)
                {
                    $(this).attr("disabled", "true");

                }


            });
            $("#edit_select_user4 option").each(function() {
                if ($(this).val() == str)
                {
                    $(this).attr("disabled", "true");

                }


            });

        });
        $("#edit_selectuser2").on('change', function() {

            //$("#select_user2").reset();
            var str2 = ($(this).val());
            var user3 = [];
            var option = '';

            $("#edit_selectuser3 option").each(function() {
                if ($(this).val() == str2)
                {
                    $(this).attr("disabled", "true");
                    //option=option+"<option value='"+$(this).val()+"'>"+$(this).text()+"</option>";
                }


            });
            $("#edit_select_user4 option").each(function() {
                if ($(this).val() == str2)
                {
                    $(this).attr("disabled", "true");
                    //option=option+"<option value='"+$(this).val()+"'>"+$(this).text()+"</option>";
                }


            });
        });

        $("#edit_selectuser3").on('change', function() {

            //$("#select_user2").reset();
            var str2 = ($(this).val());
            var user3 = [];
            var option = '';

            $("#edit_select_user4 option").each(function() {
                if ($(this).val() == str2)
                {
                    $(this).attr("disabled", "true");
                    //option=option+"<option value='"+$(this).val()+"'>"+$(this).text()+"</option>";
                }


            });
        });



        $("#category_name").on("keyup blur", function() {

            var category_name = $("#category_name").val();
            var acc_id = $("#adminID").val();
            var base_url_str = $("#base_url").val();
            $.ajax({
                type: "POST",
                url: base_url_str + "admin_section/checkCategory",
                data: {
                    'category': category_name,
                    'account_id': acc_id
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#multicat").removeClass('disabled');
                        $("#username_error").addClass("hide");
                    } else {
                        $("#multicat").addClass('disabled');
                        $("#username_error").removeClass("hide");
                    }
                }

            });
        });
        // script for data table 

        var base_url_str = $("#base_url").val();
        var master_table = $("#Admin_Category").DataTable({
            "oLanguage": {
                "sProcessing": "<div align='center'><img src='" + base_url_str + "/assets/img/ajax-loader.gif'></div>"},
            "ordering": true,
            "aLengthMenu": [[20, 40, -1], [20, 40, "All"]],
            "iDisplayLength": 20,
//            "scrollY": "585px",
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnRowCallback": function(nRow, aData) {
                var count = $('#Admin_Category thead th').length;
                var $nRow = $(nRow); // cache the row wrapped up in jQuery
                tdhtm = $nRow.children()[count - 1].innerHTML;

                if (tdhtm.search("enable") != -1) {
                    $nRow.css("background-color", "#f2b4b4");
                }

                return nRow;
            },
            "aoColumnDefs": [
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [0]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [1]},
                {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]}
            ]}
        );

        // script for validation for add category form
        $("#add_category_form").validate({
            rules: {
                category_name: "required",
                add_email: {
                    email: true
                },
            },
            messages: {
                category_name: "Please Enter Category Name",
                add_email: {
                    email: "Please Enter Valid Email Address"
                },
            }

        });
        // script for validation for edit category form
        $("#edit_category_form").validate({
            rules: {
                edit_category_name: "required",
                edit_email: {
                    email: true
                },
            },
            messages: {
                edit_category_name: "Please Enter Category Name",
                edit_email: {
                    edit_email: "Please Enter Valid Email Address"
                },
            }

        });
        $("body").on("click", ".edit", function() {

            var id = $(this).attr("data_category_id");
            var category_name = $(this).attr("data_category_name");
            var support_email = $(this).attr("data_support_emails");
            var email = support_email.split(',');
            var base_url = $("#base_url").val();


            $.ajax({
                type: "POST",
                url: base_url + "index.php/admin_section/getcategorydata/" + id,
                success: function(data) {

                    var category = $.parseJSON(data);
                    $("#quantity_enabled").val(category.quantity_enabled);
                    $("#edit_category_name").attr("value", category.name);

                    $("#category_id").attr("value", id);
                    if (email.length != 1) {
                        for (var j = 0; j < email.length; j++) {
                            $("#edit_selectuser" + (j + 1) + "").val(email[j]);
                        }
                    }
                    if (category.fields != 'null') {
                        var cat_fields = $.parseJSON(category.custom_fields);
                        $("#custom_fields option[value='']").prop("selected", true);
                        for (var i = 0; i < (category.fields); i++)
                        {
                            $("#custom_fields option[value='" + cat_fields[i] + "']").prop("selected", true);
                        }
                    }

                }
            });
        });
        $(document).on("blur", ".multicat", function() {

            var id = $(this).attr('data');
            var category_name = $(this).attr('value');
            var acc_id = $("#adminID").val();
            var base_url_str = $("#base_url").val();
            if (category_name != '') {
                $.ajax({
                    type: "POST",
                    url: base_url_str + "admin_section/checkCategory",
                    data: {
                        'category': category_name,
                        'account_id': acc_id
                    },
                    success: function(msg) {
                        // we need to check if the value is the same
                        if (msg == "1") {
                            $("#multiadd_button").removeClass('disabled');
                            $("#username_error_" + id).addClass("hide");
                            //Receiving the result of search here
                        } else {
                            $("#username_error_" + id).removeClass("hide");
                            $("#multiadd_button").addClass('disabled');
                        }


                    }
                });
            }
        });

        $('#myCollapsible').collapse({
            toggle: false
        })

// Multiple Checked
        $('body').find('.multiComSelect:checked').prop('checked', false);
        $('body').find('#selectAllchk').prop('checked', false);
        $('body').on('click', '.multiComSelect', function() {
            if ($('html').find('.multiComSelect:checked').length)
            {
                $('#multiCatEditBtn').addClass('in').removeClass('hide');
                if ($('html').find('.multiComSelect:not(:checked)').length == 0)
                    $('#selectAllchk').prop('checked', true);
            } else {
                $('#multiCatEditBtn').addClass('hide').removeClass('in');
                $('#selectAllchk').prop('checked', false);
            }
        });
        $('body').on('click', '#selectAllchk', function() {
            if ($(this).is(':checked')) {

                $('.multiComSelect').prop('checked', true);
                $('#multiCatEditBtn').addClass('in').removeClass('hide');
            }
            else {

                $('.multiComSelect').prop('checked', false);
                $('#multiCatEditBtn').addClass('hide').removeClass('in');
            }
        });
        $('#multiCatEditBtn').on('click', function() {

            $('#multiCategoriesEditModal').modal('show');
        });

        $('#multiCatEditBtn').on('click', function() {

            var ids = [];
            var cat_ids = [];
            $('#Admin_Category').find('input[type="checkbox"]:checked').each(function() {
                ids.push($(this).attr('value'));
            });
            console.log(ids);
            $('#multiCatIds').val(ids.join(','));
            $('#multiCategoryEditModal').find('select option[value=""]').prop('selected', true);
            $('#multiCategoryEditModal').modal('show');
        });

    });
    function deleteTemplate(editObj) {
        var url = $(editObj).attr('data-href');

        bootbox.confirm("Do you want to archive this Category ?", function(result) {
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

if ($this->session->flashdata('arrCourier')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('arrCourier'); ?>
    </div>
    <?php
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Categories</h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li ><a data-toggle="" href="<?php echo base_url('admin_section/admin_user'); ?>">Users</a>
                    </li>
                    <li ><a data-toggle="" href="<?php echo base_url("admin_section/admin_owner"); ?>">Owners</a>
                    </li>
                    <li class="active"><a data-toggle="" href="<?php echo base_url("admin_section/admin_categories"); ?>">Categories</a>
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
                    <?php
                    if ($arrSessionData['objSystemUser']->levelid == 3 || $arrSessionData['objSystemUser']->levelid == 4) {
                        ?>
                        <li><a data-toggle="" href="<?php echo base_url('admin_section/data_import'); ?>">Data Import</a>
                        </li>
                    <?php } ?>
                </ul>

                <!-- Tab panes -->

            </div>
        </div></div></div>
<!-- /.panel-body -->
<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header" style="margin-top: 0px;"><?php echo $customer_data[0]['name']; ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="col-lg-7">

                    <a  href="<?= base_url('admin_section/exportPDFForCategory/CSV') ?>" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>CSV</b>
                    </a>


                    <a  href="<?= base_url('admin_section/exportPDFForCategory/PDF') ?>" target="blank" class="button icon-with-text round">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br>PDF</b></a>

                    <a class="button icon-with-text round" id="create_category" data-target="#add_category" data-toggle="modal"><i class="fa fa-plus-circle"></i><b>Add <br>Category</b></a>

                    <a class="button icon-with-text round" id="multiple_category" data-target="#add_multiple_category" data-toggle="modal"><i class="fa fa-plus"></i><b>Add Multiple<br>Categories</b></a>
                    <a  class="button icon-with-text round" id="edit_button"><i class="fa fa-edit"></i><b>Edit</b></a>
                    <a  class="button icon-with-text round" onclick="$('#multiplecategory').submit();"><i class="fa fa-arrow-circle-down"></i><b>Save</b></a>

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
    </div>

    <!-- /.col-lg-12 -->
</div>
<!--        </div>
    </div>
</div>-->
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <div class="panel-body">
                <form action="<?php echo base_url() . 'admin_section/saveFaultEmails'; ?>" method="post">
     <input type="hidden" name="account_id" value="<?php echo $arrSessionData["objSystemUser"]->accountid; ?>">
     
         <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="Alert" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                           
                            <thead>
                                <tr>
                                    <th>Default Email Alerts</th>
                                    <td><div class="form-group col-md-12">
                                            <div class="col-md-6"> 
                                                <select name="default_alert_email"  class="form-control">
                                                    <option value="">----select user----</option>
                                                    <?php
                                                    foreach ($all_user as $val) {
                                                        ?>
                                                        <option <?php if($alertEmail[0]['support_email'] == $val['username'] ) echo "selected"; ?> value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <!--<input type="text" name='default_email' style="width:200px" disabled="" value='<?php // echo $customer_data[0]['support_email'] ?>' class="form-control">-->
                                            </div>
                                        </div></td>

                                </tr>
                                <tr>
                                    <th>Fault Alerts</th>
                                    <td><div class="form-group col-md-12">
                                            <div class="col-md-6">       
                                                <select name="fault_alert_email" class="form-control">
                                                    <option value="">----select user----</option>
                                                    <?php
                                                    foreach ($all_user as $val) {
                                                        ?>
                                                        <option <?php if($alertEmail[0]['fault_alert_email'] == $val['id'] ){ echo "selected";} ?> value="<?php echo $val['id']; ?>"><?php echo $val['username']; ?></option>  
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div></td>
                                </tr>
                                <tr>
                                    <th>Safety Alerts</th>
                                    <td><div class="form-group col-md-12">
                                            <div class="col-md-6">       
                                                <select name="safety_alert_email"  class="form-control">
                                                    <option value="">----select user----</option>
                                                    <?php
                                                    foreach ($all_user as $val) {
                                                        ?>
                                                        <option <?php if($alertEmail[0]['safety_alert_email'] == $val['id'] ){ echo "selected";} ?> value="<?php echo $val['id']; ?>"><?php echo $val['username']; ?></option>  
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td><div class="form-group col-md-12">
                                            <div class="col-md-6">       
                                               
                                            </div>
                                            <div class="col-md-6">       
                                                <button type="submit" class="btn btn-md btn-primary">Save</button>
                                            </div>
                                        </div></td>
                                </tr>

                            </thead>
                            
                            <tbody id="Master_Customer_body">
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4"></div>
    </div>

</div>
<div class="row">
    <form action="<?php echo base_url() . 'admin_section/editMultipleCategory'; ?>" method="POST" id="multiplecategory">
        <div class="col-lg-12">

            <div class="panel-body">

                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="Admin_Category" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="max-width:100px">
                                    	
                                    Select<input id="selectAllchk" type="checkbox" title="Select ALL">
                                        <button id="multiCatEditBtn" class="btn btn-warning fade hide" style="padding:0 5px;" type="button">Edit</button></th>
                                    <th>Category Name</th>
                                    <th>Alert/Reminder Email Address</th>
                                    <th>Supplier Email Address</th>
                                    <?php
                                    foreach ($custom_field as $header_name) {
                                        echo '<th>' . $header_name['field_name'] . '</th>';
                                    }
                                    ?>
                                    <th>Actions</th>
                                </tr>

                            </thead>
                            <tbody id="Master_Customer_body">
                                <?php
                                if (!empty($get_category)) {
                                    $k = 0;
                                    foreach ($get_category as $val) {
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" class="multiComSelect" value="<?php echo $val['id']; ?>"></td>
                                            <td><span class="multicatg"><?php echo $val['name']; ?></span>
                                                <input type="hidden" value="<?php echo $val['id']; ?>" name="category_id[]">
                                                <input id="editcategoryname_<?php echo $val['id']; ?>" name="edit_categoryname[]" class="form-control manuitem hidden" placeholder="Enter Category Name" value="<?php echo $val['name']; ?>"></td>
                                            <td>
                                                <span class="multicatg"><?php                    
                                                    if ($val['support_emails'] != "") {

                                                        $email = explode(",", $val['support_emails']);

                                                        foreach ($email as $emailrec) {
                                                            if ($emailrec) {

                                                                $email_array[$val['id']][] = array($emailrec);
                                                            }
                                                        }
                                                        if (!empty($email_array[$val['id']])) {
                                                            for ($i = 0; $i < count($email_array[$val['id']]); $i++) {
                                                                echo $email_array[$val['id']][$i][0] . ' ';
                                                            }
                                                        } else {
                                                            echo $customer_data[0]['support_email'];
                                                        }
                                                    } else {
//                                               Write your code here 
                                                        echo $customer_data[0]['support_email'];
                                                    }
                                                    ?></span>
                                                <textarea id="editmultipleuser" name="editmultipleuser[]" rows="2" cols="1" class="manuitem hidden">
                                                    <?php
                                                    $email = array();
                                                    $email_array = array();
                                                    if ($val['support_emails'] != "") {

                                                        $email = explode(",", $val['support_emails']);

                                                        foreach ($email as $emailrec) {
                                                            if ($emailrec) {

                                                                $email_array[$val['id']][] = array($emailrec);
                                                            }
                                                        }
                                                        if (!empty($email_array[$val['id']])) {
                                                            for ($i = 0; $i < count($email_array[$val['id']]); $i++) {
                                                                echo $email_array[$val['id']][$i][0] . ' ';
                                                            }
                                                        } else {
                                                            echo $customer_data[0]['support_email'];
                                                        }
                                                    } else {
//                                               Write your code here 
                                                        echo '';
                                                    }
                                                    ?>
                                                </textarea>
                                            </td>
                                              <td><?php echo $val['supplier_user']; ?></td>
                                            <?php
                                            foreach ($custom_field as $header_name) {
                                                if (array_key_exists($header_name['field_name'], $val)) {

                                                    echo '<td><span class="multicatg">Yes</span><select class="form-control manuitem hidden" id="custom_fields" name="custom_fields_' . $k . '[]">
                                    <option selected="" value="' . $header_name['id'] . '">Yes</option>
                                    <option value="">No</option>
                                </select></td>';
                                                } else {

                                                    echo '<td><span class="multicatg">No</span><select class="form-control manuitem hidden" id="custom_fields" name="custom_fields_' . $k . '[]">
                                    <option value="' . $header_name['id'] . '">Yes</option>
                                    <option selected="" value="">No</option>
                                </select></td>';
                                                }
                                            }
                                            ?>
                                            <td>

                                                <?php
                                                if ($val['active'] == 1) {
                                                    $access_icon = '<span class="action-w"><a  id="disableuser_id_' . $val['id'] . '" href="' . base_url('admin_section/disableCategory/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="Disable" class="disableadminuser"><i class="fa  fa-pause franchises-i"></i></a>Disable</span>';
                                                } else {
                                                    $access_icon = '<span class="action-w"><a  id="enableuser_id_' . $val['id'] . '" href="' . base_url('admin_section/enableCategory/' . $val['id']) . '" data_adminuser_id=' . $val['id'] . '  title="enable" class="enableadminuser"><i class="fa  fa-play franchises-i"></i></a>Enable</span>';
                                                }

                                                echo '<span class="action-w"><a href="#edit_category" data-toggle="modal" data_support_emails=' . $val['support_emails'] . ' id="edit_adminuser_id_' . $val['id'] . '" href="#edit_admin_user_form" title="Edit" data_category_name=' . $val['name'] . ' data_category_id=' . $val['id'] . ' class="edit"><i class="glyphicon glyphicon-edit franchises-i"></i></a>Edit</span>' . $access_icon . '<span class="action-w"><a href="javascript:void(0)" data-toggle="modal" onclick="deleteTemplate(this)" data-href="' . base_url('admin_section/archiveCategory/' . $val['id']) . '"  title="Archive"><i class="glyphicon glyphicon-remove-sign franchises-i"></i></a>Archive</span>';
                                                ?>  </td>
                                        </tr>
                                        <?php
                                    $k++; } 
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </form>
</div>

</div>
</div>
</div>



<!-- Modal For Add Category -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_category" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Category</h4>
            </div>

            <form action="<?php echo base_url() . 'admin_section/addCategory' ?>" method="post" id="add_category_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Category Name" class="form-control" name="category_name" id="category_name">
                            <div id="username_error" class="username_error hide">Category Is Already Exist.</div> 
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12 tb_header">
                        <div class="col-md-8">
                            <label> Custom Fields </label>
                        </div>
                    </div>
                    <?php
                    foreach ($custom_field as $custom_names) {
                        ?>
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label><?php echo $custom_names['field_name']; ?>  :</label>
                            </div>
                            <div class="col-md-6">       
                                <select name="custom_fields[]" id="custom_fields" class="form-control">
                                    <option value="<?php echo $custom_names['id']; ?>">Yes</option>
                                    <option value="" selected="">No</option>
                                </select>
                            </div>
                        </div>

                        <?php
                    }
                    ?>

                    <div class="form-group col-md-12 tb_header">
                        <div class="col-md-8">  <label>Support Alert/Reminder Email Address</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="select_user1" id="select_user1" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="select_user2" id="select_user2" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="select_user3" id="select_user3" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Add Email Address :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Add Email Address" class="form-control" name="add_email" id="add_email" type="text"></div>
                    </div>
                    <div class="form-group col-md-12"></div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <label><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"><i class="fa fa-plus"></i> Add Another User </a></label>

                            </div>

                        </div>
                        <div class="col-md-6"> 
                            <!--<input placeholder="Enter User Name" class="form-control" name="add_another_user" id="add_another_user" type="text"></div>-->
                            <div id="collapseOne" class="accordion-body collapse out">
                                <div class="accordion-inner">
                                    <select name="select_anather_user" id="select_anather_user" class="form-control">
                                        <option value="">----select user----</option>
                                        <?php
                                        foreach ($all_user as $val) {
                                            ?>
                                            <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
                                    <label><i class="fa fa-plus"></i> Add Another Email Address</label>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="collapseThree" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <input placeholder="Enter Another Email Address" class="form-control" name="add_another_email" id="add_another_email" type="text"></div>
                            </div></div>
                    </div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                    <label><i class="fa fa-plus"></i> Add Supplier User</label>
                                </a>
                            </div>
                        </div> 
                        <div class="col-md-6"> 
                   <!--<input placeholder="Enter Supplier Name" class="form-control" name="add_supplier_user" id="add_supplier_user" type="text"></div>-->
                            <div id="collapseTwo" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <select name="select_anather_user" id="select_anather_user" class="form-control">
                                        <option value="">----select user----</option>
                                        <?php
                                        foreach ($supplier_user as $sup) {
                                            ?>
                                            <option value="<?php echo $sup['username']; ?>"><?php echo $sup['username']; ?></option>  
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div></div>
                <input type="hidden" name="account_id" value="<?php echo $user_data; ?>">
                <input type="hidden" id="adminID" value="<?php echo $arrSessionData["objSystemUser"]->accountid; ?>"> 
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>


<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_category" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Edit Category</h4>
            </div>

            <form action="<?php echo base_url() . 'admin_section/editCategory' ?>" method="post" id="edit_category_form">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">  <label>Category Name :</label> </div>
                        <div class="col-md-6">  <input placeholder="Enter Category Name" class="form-control" name="edit_category_name" id="edit_category_name">
                        </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12 tb_header">
                        <div class="col-md-8">
                            <label> Custom Fields </label>
                        </div>
                    </div>
                    <?php
                    foreach ($custom_field as $edit_custom_field) {
                        ?>
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label><?php echo $edit_custom_field['field_name']; ?>  :</label>
                            </div>
                            <div class="col-md-6">  

                                <select name="custom_fields[]" id="custom_fields" class="form-control">
                                    <option value="<?php echo $edit_custom_field['id']; ?>">Yes</option>
                                    <option value="" selected="">No</option>
                                </select>
                            </div>
                        </div>

                        <?php
                    }
                    ?>
                    <div class="form-group col-md-12 tb_header">
                        <div class="col-md-8">  <label>Support Alert/Reminder Email Address</label> </div>
                    </div> <!-- /.form-group -->
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_selectuser1" id="edit_selectuser1" class="form-control">
                                <option value=''>----select user----</option>

                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_selectuser2" id="edit_selectuser2" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="edit_selectuser3" id="edit_selectuser3" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 

                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Add Email Address :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Add Email Address" class="form-control" name="edit_email" id="edit_email" type="text"></div>
                    </div>
                    <div class="form-group col-md-12"></div>
                    <!--                    <div class="form-group col-md-12">
                                            <div class="col-md-6"> 
                                                <label>Quantity Category
                                                    <span class="form_help">If set to yes, allows items to be set in quantities. For example, 1 Chair item actually contains 10 individual chairs.</span>
                                                </label>
                                            </div>
                                            <div class="col-md-6"> 
                                                <select id="quantity_enabled" name="quantity_enabled" class="form-control">
                                                    <option value="0">No</option>
                                                    <option selected="" value="1">Yes</option>
                                                </select>
                                            </div>
                                        </div>-->
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <label><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion6" href="#collapsesix"><i class="fa fa-plus"></i> Add Another User </a></label>

                            </div>

                        </div>
                        <div class="col-md-6"> 
                            <!--<input placeholder="Enter User Name" class="form-control" name="add_another_user" id="add_another_user" type="text"></div>-->
                            <div id="collapsesix" class="accordion-body collapse out">
                                <div class="accordion-inner">
                                    <select name="edit_select_user4" id="edit_select_user4" class="form-control">
                                        <option value="">----select user----</option>
                                        <?php
                                        foreach ($all_user as $edit_val) {
                                            ?>
                                            <option value="<?php echo $edit_val['username']; ?>"><?php echo $edit_val['username']; ?></option>  
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion4" href="#collapseFour">
                                    <label><i class="fa fa-plus"></i> Add Another Email Address</label>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="collapseFour" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <input placeholder="Enter Another Email Address" class="form-control" name="edit_add_another_email" id="edit_add_another_email" type="text"></div>
                            </div></div>
                    </div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion5" href="#collapseFive">
                                    <label><i class="fa fa-plus"></i> Add Supplier User</label>
                                </a>
                            </div>
                        </div> 
                        <div class="col-md-6"> 
                   <!--<input placeholder="Enter Supplier Name" class="form-control" name="add_supplier_user" id="add_supplier_user" type="text"></div>-->
                            <div id="collapseFive" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <select name="edit_select_anather_user" id="edit_select_anather_user" class="form-control">
                                        <option value="1">----select user----</option>
                                        <?php
                                        foreach ($supplier_user as $sup) {
                                            ?>
                                            <option value="<?php echo $sup['username']; ?>"><?php echo $sup['username']; ?></option>  
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <input type="hidden" id="category_id" name="category_id" value="">
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_multiple_category" class="modal fade" style="display: none;">
    <div class="modal-dialog" style="width: 980px;">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">Add Multiple Category</h4>
            </div>

            <form action="<?php echo base_url() . 'admin_section/addMultipleCategory' ?>" method="POST" id="multiadd">
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <div class="col-md-6"><label>Add Multiple Categories :</label> </div> 

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
                                <th>Category Name</th>
                                <?php
                                foreach ($custom_field as $multi_custom) {

                                    echo '<th>' . $multi_custom['field_name'] . '</th>';
                                }
                                ?>
                                <th>Select User</th>
                                <th>Select User</th>
                            </tr>
                        <tbody id="tbody_multiple_cat">

                            <tr id="row_1" style="display:none">
                                <td><input type="text" data=""  class="form-control multicat"  name="category_name_" id="category_name_" placeholder="Enter Category Name" ><div id="username_error_" class="username_error hide">Category Is Already Exist.</div> </td>
                                <?php foreach ($custom_field as $multi_custom_id) {
                                    ?>
                                    <td><select class="form-control multicustom" name="custom_field_">
                                            <option value="">No</option>
                                            <option value="<?php echo $multi_custom_id['id']; ?>">Yes</option>

                                            <?php
                                        }
                                        ?>
                                    </select></td>

                                <td> <select name="multi_select_user1_" id="multi_select_user1_" class="form-control multiemail">
                                        <option value=''>----select user----</option>


                                        <?php
                                        foreach ($all_user as $val) {
                                            ?>
                                            <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                            <?php
                                        }
                                        ?>

                                    </select></td>
                                <td> <select name="multi_select_user2_" id="multi_select_user2_" class="form-control multiemail">
                                        <option value=''>----select user----</option>

                                        <?php
                                        foreach ($all_user as $val) {
                                            ?>
                                            <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                            <?php
                                        }
                                        ?>

                                    </select></td>
                        <input type="hidden" name="account_id" value="<?php echo $user_data; ?>">
                        </tr>

                        <tr>
                            <td><input required="required" type="text" class="form-control multicat" data="1" name="category_name_1" id="category_name_1" placeholder="Enter Category Name" ><div id="username_error_1" class="username_error hide">Category Is Already Exist.</div></td>
                            <?php foreach ($custom_field as $multi_custom_id) {
                                ?>

                                <td><select class="form-control multicustom" name="custom_field_1[]">
                                        <option value="">No</option>
                                        <option value="<?php echo $multi_custom_id['id']; ?>">Yes</option>

                                        <?php
                                    }
                                    ?>
                                </select></td>
                            <td> <select name="multi_select_user1_1" id="multi_select_user1_1" class="form-control multiemail">
                                    <option value=''>----select user----</option>

                                    <?php
                                    foreach ($all_user as $val) {
                                        ?>
                                        <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                        <?php
                                    }
                                    ?>

                                </select></td>
                            <td> <select name="multi_select_user2_1" id="multi_select_user2_1" class="form-control multiemail">
                                    <option value=''>----select user----</option>

                                    <?php
                                    foreach ($all_user as $val) {
                                        ?>
                                        <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                        <?php
                                    }
                                    ?>

                                </select></td>

                        </tr>

                        </tbody>
                        <input type="hidden" name="count_row" id="count_row" value="1">

                        </thead>
                    </table>



                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary" type="submit" id="multiadd_button">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Multiple Edit Item Model -->
<div class="modal fade" id="multiCategoriesEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url() ?>admin_section/editmulticategories" method="post" id="editmultiitem">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Edit Multiple Categories</h4>
                </div>
                <div class="modal-body">

                    <input hidden="" name="category_id" id="multiCatIds">

                    <?php
                    foreach ($custom_field as $custom_names) {
                        ?>
                        <div class="form-group col-md-12">
                            <div class="col-md-6"> <label><?php echo $custom_names['field_name']; ?>  :</label>
                            </div>
                            <div class="col-md-6">       
                                <select name="custom_fields[]" id="custom_fields" class="form-control">
                                    <option value="<?php echo $custom_names['id']; ?>">Yes</option>
                                    <option value="" selected="">No</option>
                                </select>
                            </div>
                        </div>

                        <?php
                    }
                    ?>
                    <!-- Alert Email Address -->

                    <div class="form-group col-md-12 tb_header">
                        <div class="col-md-8">  <label>Support Alert/Reminder Email Address</label> </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="select_user1" id="select_user1" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="select_user2" id="select_user2" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">          <label>Select User:</label>
                        </div>
                        <div class="col-md-6">       
                            <select name="select_user3" id="select_user3" class="form-control">
                                <option value=''>----select user----</option>
                                <?php
                                foreach ($all_user as $val) {
                                    ?>
                                    <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div> 
                    <div class="form-group col-md-12">
                        <div class="col-md-6">      <label>Add Email Address :</label>
                        </div>
                        <div class="col-md-6">  <input placeholder="Add Email Address" class="form-control" name="add_email" id="add_email" type="text"></div>
                    </div>
                    <div class="form-group col-md-12"></div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <label><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_One"><i class="fa fa-plus"></i> Add Another User </a></label>

                            </div>

                        </div>
                        <div class="col-md-6"> 
                            <!--<input placeholder="Enter User Name" class="form-control" name="add_another_user" id="add_another_user" type="text"></div>-->
                            <div id="collapse_One" class="accordion-body collapse out">
                                <div class="accordion-inner">
                                    <select name="select_anather_user" id="select_anather_user" class="form-control">
                                        <option value="">----select user----</option>
                                        <?php
                                        foreach ($all_user as $val) {
                                            ?>
                                            <option value="<?php echo $val['username']; ?>"><?php echo $val['username']; ?></option>  
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapse_Three">
                                    <label><i class="fa fa-plus"></i> Add Another Email Address</label>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="collapse_Three" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <input placeholder="Enter Another Email Address" class="form-control" name="add_another_email" id="add_another_email" type="text"></div>
                            </div></div>
                    </div>
                    <div class="form-group col-md-12">

                        <div class="col-md-6">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_Two">
                                    <label><i class="fa fa-plus"></i> Add Supplier User</label>
                                </a>
                            </div>
                        </div> 
                        <div class="col-md-6"> 

                            <div id="collapse_Two" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <select name="select_anather_user" id="select_anather_user" class="form-control">
                                        <option value="">----select user----</option>
                                        <?php
                                        foreach ($supplier_user as $sup) {
                                            ?>
                                            <option value="<?php echo $sup['username']; ?>"><?php echo $sup['username']; ?></option>  
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>








