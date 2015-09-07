<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>

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

    .multiadd{
        min-height: 70px;
        max-height: 300px;
        overflow-y: scroll;
    } 


</style>
<script>
    $(document).ready(function () {
        $("#row_count_manufacture").val(1);
        $("#row_count").val(1)
        var rowCount = 1;


        var max_fields = 100; //maximum input boxes allowed
        var wrapper_items = $(".item_input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_item_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function (e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment

                $(wrapper_items).append('<div class="col-md-8"><input placeholder="Enter Item" class="form-control" name="item_name_' + x + '" id="item_name_' + x + '" required><a href="#" class="remove_field">Remove</a></div>'); //add input box
                $("#row_count").val(x);
            }
            $("#remove_item").css("display", "block");
        });

        $(wrapper_items).on("click", ".remove_field", function (e) { //user click on remove text
            e.preventDefault();
            num_row = $("#row_count").val();
            if (num_row > 1) {
                $(this).parent('div').remove();
                x--;
                $("#row_count").val(x);
            }
        })
// add manufacture text box

        var max_fields = 100; //maximum input boxes allowed
        var wrapper_manufacture = $(".manufacture_input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_manufacture_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function (e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment

                $(wrapper_manufacture).append('<div class="col-md-8"><input placeholder="Enter Manufacture" class="form-control" name="manufacture_name_' + x + '" id="manufacture_name_' + x + '" required><a href="#" class="remove_field">Remove</a></div>'); //add input box
                $("#row_count_manufacture").val(x);
            }
            $("#remove_manufacture").css("display", "block");
        });

        $(wrapper_manufacture).on("click", ".remove_field", function (e) { //user click on remove text
            e.preventDefault();
            num_row = $("#row_count_manufacture").val();
            if (num_row > 1) {
                $(this).parent('div').remove();
                x--;
                $("#row_count_manufacture").val(x);
            }
        })

        // Script For Enable Field

        $("#edit_button").click(function () {

            $(".item").prop('disabled', false);
            $("#add_item_button").prop('disabled', false);
            $("#add_manufacturer_button").prop('disabled', false);

        });
    });
</script>

<div class="col-lg-7" style="margin-top: 35px;">
    <div class="panel panel-default">

        <!-- /.panel-heading -->
        <div class="panel-body">



            <button  class="btn btn-primary btn-xs" type="button" id="b1" style="margin-left:40px;"><i class="fa  fa-file-pdf-o"></i>
                <b> Export PDF</b></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button  class="btn btn-primary btn-xs" type="button"><i class="fa fa-file-word-o"></i>
                <b>Export CSV</b></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button class="btn btn-primary btn-xs" type="button" id="edit_button"><i class="fa fa-edit"></i>
                <b>Edit</b></button>



        </div>
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
<div class="row">
    <div class="col-lg-6">
        <h3 class="page-header" align="center">Items/Menu</h3>
    </div>
    <div class="col-lg-6">
        <h3 class="page-header" align="center">Manufacturer</h3>
    </div>
</div>

<div class="row">

    <form action="<?php echo base_url() . 'admin_section/editItems_Manu' ?>" method="POST">
        <div class="col-lg-4">
            <div class="panel-body multiadd">
                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="ITEM_Datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                </tr>
                                <?php
                                if (!empty($getlist)) {
                                    foreach ($getlist['list'] as $val) {
                                        ?>

                                        <tr>
                                            <td>
                                                <div class="form-group col-md-12">
                                                    <input class="form-control item"  name='item_name[]' id="item_<?php echo $val['id']; ?>" value="<?php echo $val['item_manu_name']; ?>" disabled="">
                                                    <input type="hidden" name="item_id[]" value="<?php echo $val['id']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>

                            </thead>
                            <tbody id="Master_Customer_body">
                            </tbody>

                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
        <div class="col-lg-2">

            <button class="btn-primary btn-sm" type="submit" id="add_item_button" disabled="">Save</button>
        </div>
    </form>
    <form action="<?php echo base_url() . 'admin_section/editManufacturer' ?>" method="POST">
        <div class="col-lg-4">

            <div class="panel-body multiadd">

                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">

                        <table id="MANUFACTURE_Datatable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                </tr>
                                <?php
                                if (!empty($getmanufacturer)) {
                                    foreach ($getmanufacturer as $val) {
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="form-group col-md-12">
                                                    <input class="form-control item"  name="manufacturer_name[]" id="manufacturer_<?php echo $val['id']; ?>" value="<?php echo $val['manufacturer_name']; ?>" disabled="">
                                                    <input type="hidden" name="manufacturer_id[]" value="<?php echo $val['id']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </thead>
                            <tbody id="Master_Customer_body">
                            </tbody>

                        </table>

                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <button class="btn-primary btn-sm" type="submit" id="add_manufacturer_button" disabled="">Save</button>
        </div>
    </form>

</div><br><br><br>
<div class="row">
    <div class="col-lg-12">

        <div class="col-lg-4">  
            <form action="<?php echo base_url() . 'admin_section/addItemsManu' ?>" method="post" id="add_multiowner_form">  
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9"> Add Multiple New Items/Menu</div><div class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="add_item_field_button btn-success btn-sm" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button></div>
                        </div></div>


                    <div class="panel-body multiadd">

                        <div class="form-group col-md-12">
                            <div class="item_input_fields_wrap">

                                <div class="col-md-8"><input placeholder="Enter Item" class="form-control" name="item_name_1" id="item_name_1" required><a href="#" class="remove_field" id="remove_item" style="display: none">Remove</a></div>
                            </div>
                            <input type="hidden" name="row_count" id="row_count" value="1">
                            <input type="hidden" name="account_id" value="<?php echo $user_data['customer_account_id']; ?>">
                        </div>
                    </div> 

                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary" type="submit" id="item_save_button">Submit</button>

                </div>
            </form>
        </div>

        <div class="col-lg-4">
        </div>

        <div class="col-lg-4">  
            <form action="<?php echo base_url() . 'admin_section/addManufacturer' ?>" method="post" id="add_multiowner_form">
                <div class="panel panel-primary">

                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9">  Add Multiple New Manufacturer</div><div class="col-md-3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="add_manufacture_field_button btn-success btn-sm" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button></div>
                        </div></div>
                    <div class="panel-body multiadd">

                        <div class="form-group col-md-12">
                            <div class="manufacture_input_fields_wrap">

                                <div class="col-md-8"><input placeholder="Enter Item" class="form-control" name="manufacture_name_1" id="manufacture_name_1" required><a href="#" class="remove_field" id="remove_manufacture" style="display: none" >Remove</a></div>
                            </div>
                            <input type="hidden" name="row_count_manufacture" id="row_count_manufacture" value="1">
                            <input type="hidden" name="account_id" value="<?php echo $user_data['customer_account_id']; ?>">

                        </div>
                    </div>  
                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary" type="submit" id="save_button">Submit</button>

                </div>
            </form>    </div>    

    </div>
</div>