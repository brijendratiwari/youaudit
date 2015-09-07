<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>    
<div class="heading">
    <h1>Remove <?php echo $objItem->barcode . " (" . $objItem->manufacturer . " " . $objItem->model . ")"; ?>?</h1>
    <div class="buttons">
        <div class="buttons">
            <a class="btn icon-with-text round" onclick="confirm_deletion();">
                <i class="fa fa-arrow-circle-down"></i>
                Archive Asset</a>
        </div>
    </div>
</div>    
<div class="box_content">
    <div class="content_main">
        <?php if ($this->session->flashdata('error')) { ?>
            <div class="alert alert-warning alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>
        <div style="padding-top:20px;" class="row">
            <div class="col-md-6">
                <table class="list_table table table-bordered table-striped" width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <td>Show Purchase Price</td>
                            <td><?php
                                if ($objItem->value != 0) {
                                    echo $currency . $objItem->value;
                                } else {
                                    ?>
                                    Not Set
                                <?php } ?></td>
                        </tr>
                        <tr>
                            <td>Show Current Value</td>
                            <td><?php
                                if ($objItem->current_value != 0) {
                                    echo $currency . $objItem->current_value;
                                } else {
                                    if ($objItem->value != 0) {
                                        ?>    
                                        Purchase Value (<?php echo $currency . $objItem->value; ?>)
                                        <?php
                                    } else {
                                        ?>
                                        Not Set
                                        <?php
                                    }
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td>Show Replacement Date</td>
                            <td><?php
                                if ($objItem->replace_date != "") {
                                    if (strtotime($objItem->replace_date) < 0) {
                                        echo '-';
                                    } else {
                                        echo date("d/m/Y", strtotime($objItem->replace_date));
                                    }
                                } else {
                                    echo "Not Available";
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td>Show Asset Age</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="padding-top:20px;" class="row">
            <div class="col-md-6"><h3>Complete Form Below</h3></div>
        </div>
        <div style="padding-top:20px;" class="row">
            <?php echo form_open('items/mark_deleted/' . $intItemId . '/', array('id' => 'delete_item_form')); ?>
            <div class="col-md-6">
                <table class="list_table table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td>Why is asset being removed? Reason Code</td>
                            <td>

                                <select  class='form-control'  name='reason' required>
                                    <option value="-1">Select</option>
                                    <?php
                                    foreach ($arrRemoveReasons['results'] as $arrReasons) {
                                        echo "<option value=\"" . $arrReasons->reasonid . "\">" . $arrReasons->reason . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>How was asset removed? Reason Code</td>
                            <td>
                                <select  class='form-control'  name='itemstatus' required>
                                    <option value="-1">Select</option>
                                    <?php
                                    foreach ($arrItemStatuses['results'] as $arrStatus) {
                                        echo "<option value=\"" . $arrStatus->statusid . "\">" . $arrStatus->statusname . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Payment / Income for Removed Asset? Sold / Scrap / Recycled etc</td>
                            <td><input name='payment' data-currentvalue="<?php
                                if ($objItem->current_value != 0) {
                                    echo $currency . $objItem->current_value;
                                } else {
                                    echo $currency . $objItem->value;
                                }
                                ?>" class='form-control' value=''></td>
                        </tr>
                        <tr>
                            <td>Net Income of Removed Asset</td>
                            <td><input name='net_gain_loss' value='' class='form-control' readonly='true'></td>
                        </tr>
                    </tbody>
                </table>
                <input hidden='' name='safety' value='1'>
            </div>
            </form>
        </div>

        <script>
                function confirm_deletion() {
                    bootbox.confirm("Are you sure?", function(result) {
                        if (result) {
                            $('#delete_item_form').submit();
                        } else {
                            // Do nothing!
                        }
                    });
                }
                $(function() {
                    $('[name=payment]').on('keyup blur', function() {
                        var payment = $(this).val();
                        var current_value = $(this).attr('data-currentvalue');
                        var numericRegex = /[(0-9)+.?(0-9)*]+/igm;
                        //        console.log(numericRegex.test(payment));
                        //        console.log(payment+' - '+current_value);

                        if (payment.match(numericRegex) != '' && payment != '') {
                            var net_gain_loss = (current_value - payment).toFixed(2);
                            $('[name=net_gain_loss]').val(net_gain_loss);
                        }
                    });
                });
        </script>
        <style>
            .bootbox .modal-dialog{
                width: 300px;
                left: 0 !important;
            }
            .list_table select
            {
                color: #000;
                padding: 2px;
                width: 100%!important;
                border-color: #ccc!important;
                border-radius: 4px!important;
            }
        </style>