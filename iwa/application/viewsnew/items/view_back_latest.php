<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit_new/includes/css/sub_style.css" rel="stylesheet" type="text/css" />

<div class="heading">
    <div class="buttons" style="float: left!important">
        <?php
        if ($arrSessionData['objSystemUser']->levelid > 1) {
            ?>
            <a id="item_edit" class="button icon-with-text round"><i class="fa fa-fw">&#xf044;</i> Edit item</a>
            <div style="float: left; margin-left: 5px;">
                <a onclick="$('#itemedit').submit();" class="button update icon-with-text round" style="display: none;">   <i class="fa fa-fw">&#xf0ab;</i> Save</a>
            </div>
            <?php
        }
        if ($arrSessionData['objSystemUser']->userid == $objItem->userid) {
            ?><a href="<?php echo site_url('/items/changelinks/' . $objItem->itemid . '/'); ?>" class="button icon-with-text round">   <i class="fa fa-fw">&#xf007;</i> Change Owner or Location</a><?php
        } else {
            ?><a href="<?php echo site_url('/items/itsmine/' . $objItem->itemid . '/'); ?>" class="button icon-with-text round"> <i class="fa fa-fw">&#xf0f0;</i>I Have This Now</a> <?php
            ?><a href="<?php echo site_url('/items/changelinks/' . $objItem->itemid . '/'); ?>" class="button icon-with-text round"> <i class="fa fa-fw">&#xf007;</i>  Change Owner or Location</a><?php
        }
        //}
        ?>
        <a href="<?php echo site_url('/compliance/log/' . $objItem->itemid . '/'); ?>" class="button icon-with-text round"> <i class="fa fa-fw">&#xf15c;</i> Log Compliance Check</a>
        <a href="<?php echo site_url('/items/raiseticket/' . $objItem->itemid . '/'); ?>" class="button icon-with-text round"> <i class="fa fa-fw">&#xf071;</i>Report Fault</a>
        <a href="#" class="button icon-with-text round">   <i class="fa fa-fw">&#xf0ad;</i>Fix Item</a>
        <?php
        if ($arrSessionData['objSystemUser']->levelid > 2) {
            if (($objItem->mark_deleted == 0) && ($objItem->mark_deleted_2 == 0)) {
                ?>  <a href="<?php echo site_url('/items/markdeleted/' . $objItem->itemid); ?>" class="button icon-with-text round">  <i class="fa fa-fw">&#xf1f8;</i> Remove item</a><?php
            }
                  ?>  <a href="#" class="button icon-with-text round">  <i class="fa fa-fw">&#xf058;</i>Condition Check</a>
                  <?php
        }
        ?>
    </div>
</div>
<div class="box_content">

    <div class="tabs"><ul>
            <li>   <a href="#first_table">Item Details</a></li>
            <li>   <a href="#second_table">Item History</a></li>
            <li>   <a href="#fourth_table">Item Fault History</a></li>
            <?php if ($this->session->userdata('objSystemUser')->compliance == 1) { ?>
                <li>    <a href="#third_table">Compliance History</a></li>
            <?php } ?>
            <li>  <a href="#pat_table">Item Pat History</a>  </li></ul>
    </div>
   <h1><?php echo $objItem->manufacturer .' '. $objItem->model .' / ' .$objItem->barcode;
            ?></h1>
    <script type="text/javascript">
        /* When Category is checked, see if category is a quantity category */


        $(function () {
            $('#category_id').change(function () {

                var url = $('#base_url').val();

                var linkforcategory = url + "categories/checkCategory/" + $('#category_id').val();
                /* Quantity category check */
                $.getJSON(linkforcategory, function (data) {
                    if (data.quantity == 1) {
                        /*                    $('#item_quantity').append('<label for="item_quantity">Item Quantity</label>' +
                         '<input type="input" name="item_quantity"/>'
                         ).show();*/
                        $('#item_quantity').show();
                    } else {
                        $('#quantity').val('')
                        $('#item_quantity').hide();
                    }
                });

                /* Custom Fields call */
                $.getJSON(url + "categories/getCustomFields/" + $('#category_id').val(), function (data) {
                    $('#custom_field_div').empty();
                    str = '';
                    for (var i = 0; i < data.length; i++) {
                        str = str + '<div class="col-md-4">' + data[i].field_name + '</div><div class="col-md-8"><input type="text" placeholder="Enter Content" class="form-control" name=' + data[i].id + ' id=' + data[i].id + '></div></br></br></br>';
                    }
                    $('#custom_field_div').html(str);
                });
            });
        });
    </script>
    <input type="hidden" name="base_url" id="base_url" value="<?= base_url(); ?>">
    <div id="first_table" class="content_main">
        <div class="row">
            <div class="col-lg-4">
                <form enctype="multipart/form-data" id="itemedit" accept-charset="utf-8" method="post" action="<?php echo base_url('items/edit/' . $objItem->itemid); ?>">
                    <div class="table-responsive" id="view_itemdetails">
                        <table class="table">
                            <tbody>
                                <tr class="tb_header">
                                    <td>Item Details</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td> <select name="category_id" id="category_id" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php
                                            foreach ($arrCategories['results'] as $arrCategory) {
                                                echo "<option ";
                                                echo 'value="' . $arrCategory->categoryid . '" ';
                                                if ($arrCategory->categoryid == $objItem->categoryid) {
                                                    echo 'selected="selected" ';
                                                }
                                                echo '>' . $arrCategory->categoryname . "</option>";
                                            }
                                            ?>
                                        </select></td>
                                </tr>

                                <tr>
                                    <td>Item/Menu*</td>
                                    <td> <select name="item_manu" id="item_manu" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php foreach ($getitemmanu['list'] as $item) { ?>
                                                <option value="<?php echo $item['item_manu_name']; ?>"<?php
                                                if ($item['item_manu_name'] == $objItem->item_manu) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>><?php echo $item['item_manu_name']; ?></option>
                                                    <?php } ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td>Manufacturer*</td>
                                    <td><select name="item_make" id="item_make" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php foreach ($arrManufaturer as $manufacturer) { ?>
                                                <option value="<?php echo $manufacturer['manufacturer_name']; ?>"<?php
                                                if ($manufacturer['manufacturer_name'] == $objItem->manufacturer) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>><?php echo $manufacturer['manufacturer_name']; ?></option>
                                                    <?php } ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td><input type="text" name="item_model" id="model" class="form-control" value="<?php echo $objItem->model; ?> " disabled>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Quantity</td>
                                    <td><input type="text" name="item_quantity" id="quantity" class="form-control" value="<?php echo $objItem->quantity; ?>" disabled>

                                    </td>
                                </tr>
                                <tr class="tb_header">
                                    <td>Item Details</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>QR Code</td>
                                    <td><input placeholder="Enter QR Code" class="form-control" name="item_barcode" id="qr_code" value="<?php echo $objItem->barcode; ?>" disabled=""></td>
                                </tr>
                                <tr>
                                    <td>Serial No</td>
                                    <td><input placeholder="Enter Serial No" class="form-control" name="item_serial_number" value="<?php echo $objItem->serial_number; ?>" id="serial_no" disabled></td>
                                </tr>
                                <tr class="tb_header">
                                    <td>Item Quality</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>  <select name="status_id" id="status_id" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php
                                            foreach ($arrItemStatuses['results'] as $arrStatus) {
                                                echo "<option ";
                                                echo 'value="' . $arrStatus->statusid . '" ';
                                                if ($arrStatus->statusid == $objItem->itemstatusid) {
                                                    echo 'selected="selected" ';
                                                }
                                                echo '>' . $arrStatus->statusname . "</option>\r\n";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td>Condition</td>
                                    <td><select name="item_condition" id="item_condition" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php foreach ($conditions as $con) { ?>
                                                <option value="<?php echo $con->id; ?>" <?php
                                                if ($con->id == $arrItemFieldContent[0]['id']) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>><?php echo $con->condition; ?></option>
                                                    <?php } ?>
                                        </select></td>
                                </tr>
                                <tr class="tb_header">
                                    <td>Ownership</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Owner*</td>
                                    <td> <select name="owner_id" id="owner_id" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php
                                            foreach ($arrUsers['results'] as $arrUser) {
                                                echo "<option ";
                                                echo 'value="' . $arrUser->userid . '" ';
                                                if ($arrUser->userid == $objItem->userid) {
                                                    echo 'selected="selected" ';
                                                }
                                                echo '>' . $arrUser->userfirstname . " " . $arrUser->userlastname . "</option>\r\n";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td>Site*</td>
                                    <td><select name="site_id" id="site_id" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php
                                            foreach ($arrSites['results'] as $arrSite) {
                                                echo "<option ";
                                                echo 'value="' . $arrSite->siteid . '" ';
                                                if ($arrSite->siteid == $objItem->siteid) {
                                                    echo 'selected="selected" ';
                                                }
                                                echo '>' . $arrSite->sitename . "</option>\r\n";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td>Location*</td>
                                    <td><select name="location_id" id="location_id" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php
                                            foreach ($arrLocations['results'] as $arrLocation) {
                                                echo "<option ";
                                                echo 'value="' . $arrLocation->locationid . '" ';
                                                if ($arrLocation->locationid == $objItem->locationid) {
                                                    echo 'selected="selected" ';
                                                }
                                                echo '>' . $arrLocation->locationname . "</option>\r\n";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td>Supplier*</td>
                                    <td><select name="supplier" id="supplier" class="form-control" disabled>
                                            <option>----SELECT----</option>
                                            <?php
                                            foreach ($arrSuppliers as $supplier) {
                                                echo "<option ";
                                                echo 'value="' . $supplier['supplier_id'] . '" ';
                                                if ($supplier['supplier_id'] == $objItem->supplier) {
                                                    echo 'selected="selected" ';
                                                }
                                                echo '>' . $supplier['supplier_title'] . "</option>\r\n";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr class="tb_header">
                                    <td>Notes</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <textarea placeholder="Enter Notes" class="form-control" name="item_notes" value="<?php echo $objItem->notes; ?>" id="notes" cols="13" rows="3" disabled><?php echo $objItem->notes; ?></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

            </div>
            <div class="col-lg-4">
                <div class="table-responsive" id="view_itemdetails">
                    <table class="table" >
                        <tbody>
                            <tr class="tb_header">
                                <td>Item Dates</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Purchase Date</td>
                                <td><input placeholder="Enter Purchase Date" class="form-control col-lg-10 datepicker" name="item_purchased" id="item_purchased" value="<?php echo date('d/m/y', strtotime($objItem->purchase_date)); ?>" type="text" disabled></td>
                            </tr>
                            <tr>
                                <td>Warranty Expiry</td>
                                <td><input placeholder="Enter Expiry Date" class="form-control col-lg-10 datepicker" name="item_warranty" id="item_warranty" type="text" value="<?php echo date('d/m/y', strtotime($objItem->warranty_date)); ?>" disabled></td>
                            </tr>
                            <tr>
                                <td>Replacement Due</td>
                                <td><input placeholder="Enter Replacement Date" class="form-control col-lg-10 datepicker" name="item_replace" id="item_replace" value="<?php echo date('d/m/y', strtotime($objItem->replace_date)); ?>" type="text" disabled></td>
                            </tr>
                            <tr>
                                <td>Age Of Asset</td>
                                <td><input class="form-control" name="asset_age" id="asset_age" type="text" value="<?php
                                    if (isset($objItem->purchase_date)) {
                                        $date2 = date('d-m-Y', strtotime($objItem->purchase_date));
                                        $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                                        $diff = abs(strtotime($date2) - strtotime($date1));

                                        $years = floor($diff / (365 * 60 * 60 * 24));
                                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                                        echo $years . ' year ' . $months . ' month ';
                                    }
                                    ?>" disabled></td>
                            </tr>
                            <tr class="tb_header">
                                <td>Item Valuation</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Purchase Price</td>
                                <td><input placeholder="Enter Purchase Price" class="form-control" name="item_value" id="purchase_price" value="<?php echo $objItem->value; ?>" type="text" disabled></td>
                            </tr>
                            <tr>
                                <td>Current Value</td>
                                <td><input placeholder="Enter Current Value" class="form-control" name="item_current_value" value="<?php echo $objItem->current_value; ?>" id="current_value" type="text" disabled>
                                </td>
                            </tr>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive" id="view_itemdetails">
                    <table class="table" >
                        <tbody>
                            <tr class="tb_header_warning">
                                <td>Total Faults</td>
                                <td></td>
                            </tr>
                            <tr class="tb_font_warning">
                                <td>Total Faults</td>
                                <td><input class="form-control" name="total_fault" id="total_fault" type="text" value="4" disabled></td>
                            </tr>
                            <tr class="tb_font_warning">
                                <td>Last Fault Date</td>
                                <td><input class="form-control col-lg-10 datepicker" name="fault_date" id="fault_date" type="text" value="23/08/2014" disabled></td>
                            </tr>
                            <tr class="tb_font_warning">
                                <td>Last Compliance Check</td>
                                <td><input class="form-control col-lg-10 datepicker" name="compliance_date" id="compliance_date" type="text" value="12/06/2014" disabled></td>
                            </tr>
                            <tr class="tb_font_warning">
                                <td>Compliance Result</td>
                                <td><input class="form-control" name="asset_age" id="asset_age" type="text" value="MISSED" disabled></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive" id="view_itemdetails">
                    <table class="table" >
                        <tbody>
                            <tr class="tb_header_success">
                                <td>PAT Test Date</td>
                                <td></td>
                            </tr>
                            <tr class="tb_font_success">
                                <td>PAT Test Date</td>
                                <td><input class="form-control col-lg-10 datepicker" name="item_pattestdate" id="test_date" type="text" value="<?php echo date('d/m/y', strtotime($objItem->pattest_date)); ?> " disabled></td>
                            </tr>
                            <tr class="tb_font_success">
                                <td>PAT Result</td>
                                <td>  <select name="item_patteststatus" id="item_patteststatus" class="form-control" disabled>
                                        <option>----SELECT----</option>
                                        <option value="-1" <?php
                                        if ($objItem->pattest_status === null) {
                                            echo "selected=\"selected\"";
                                        }
                                        ?>>Unknown</option>
                                        <option value="1" <?php
                                        if ($objItem->pattest_status === "1") {
                                            echo "selected=\"selected\"";
                                        }
                                        ?>>Pass</option>
                                        <option value="0" <?php
                                        if ($objItem->pattest_status === "0") {
                                            echo "selected=\"selected\"";
                                        }
                                        ?>>Fail</option>
                                        <option value="5" <?php
                                        if ($objItem->pattest_status === "5") {
                                            echo "selected=\"selected\"";
                                        }
                                        ?>>Not Required</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <table class="table"><tbody><tr class="tb_header">
                                    <td>Items Custom Fields</td>
                                    <td></td>
  </tr></tbody></table>
                <div  id="custom_field_div">
                    <?php foreach ($arrCustomFields as $custom_name) {
                        ?>

                        <div class="col-md-4">
                            <?php echo $custom_name->field_name; ?>
                        </div>
                        <div id="custom_field_id" class="col-md-8">
                            <input placeholder="Enter Asset Type" class="form-control" placeholder="Enter Content" disabled="" name="<?php echo $custom_name->id; ?>" id="asset_type" type="text" value="<?php
                            if (isset($custom_name->content)) {
                                echo $custom_name->content;
                            }
                            ?>" ></br>

                        </div>

                        <?php
                    }
                    ?>

                </div>
            </div>

            </form>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        PHOTOS
                    </div>
                    <div class="panel-body">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="margin-bottom: 0px;">

                                <img  style="margin:0 auto; height: 150px; width: 286px; padding-top: 15px; padding-bottom: 15px;" src="<?php echo site_url('/images/viewhero/' . $objItem->itemphotoid); ?>" alt="No Logo" class="image-responsive"/> </div>
                            <div>

                            </div> </div>
                    </div>
                    <form enctype="multipart/form-data" accept-charset="utf-8" method="post" action="<?php echo base_url('/items/photo_upload/' . $objItem->itemid); ?>">
                        <div class="panel-footer" style="height: 48px;">
                            <span class="col-lg-12"><span class="col-lg-6"><input class="item_photo" type="file" name="photo_file" value="upload"> </span>
                                <span class="col-lg-6"><button class="btn btn-primary btn-xs">UPLOAD</button></span>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        PDF / DOCUMENT
                    </div>
                    <div class="panel-body">

                        <ul><?php
                            foreach ($pdf_number as $list) {
                                ?><li>
                                    <div  class="pdf_upload">
                                        <a href='<?php echo site_url('/items/pdf_download/' . $list['s3_key']); ?>'><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit_new/iwa/brochure/images/pdf.png'; ?>"   title='pdf' ></a>
                                        <label for="nickname"><?php echo $list['file_name']; ?>    </label>

                                        <?php if ($arrSessionData['objSystemUser']->levelid > 2) { ?>
                                            <a class="delete" href='<?php echo site_url('/items/pdf_delete/' . $list['s3_key']); ?>'>
                                                <img alt="Delete"  title="Delete" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit_new/iwa/img/icons/16/erase.png'; ?>">
                                            </a>

                                        <?php } ?>
                                    </div>
                                </li>
                                <?php
                            }
                            ?></ul>
                    </div>
                    <form enctype="multipart/form-data" accept-charset="utf-8" method="post" action="<?php echo base_url('/items/pdf_upload'); ?>">
                        <div class="panel-footer" style="height: 48px;">
                            <span class="col-lg-12"><span class="col-lg-6"><input class="item_photo" type="file" name="pdf_file" value="upload"> </span>
                                <span class="col-lg-6"><button type="submit" class="btn btn-primary btn-xs">UPLOAD</button></span>
                            </span>
                        </div>
                        <input type="hidden" name="item_id" value="<?php echo $objItem->itemid; ?>" >
                    </form>
                </div>

            </div>
        </div>
    </div>


    <?php
    if ($arrSessionData['objSystemUser']->levelid > 1) {
        ?>
        <div id="second_table" class="content_main">

            <div id="second_table_container" class="log_container">
                <table class="list_table left">
                    <thead>
                        <tr>
                            <th class="left">Date</th>
                            <th class="left">User</th>
                            <th class="left">Location</th>
                            <th class="left">Site</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($arrItemHistory as $strDate => $arrRecord) {
                            ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($strDate)); ?></td>
                                <?php
                                if (isset($arrRecord['audit'])) {
                                    ?>
                                    <td colspan="3"><em><strong>Item was marked as <?php
                                                if ($arrRecord['audit']->present == 1) {
                                                    echo "present";
                                                } else {
                                                    echo "missing";
                                                }

                                                echo " by " . $arrRecord['audit']->userfirstname . " " . $arrRecord['audit']->userlastname;
                                                echo " on an audit of location " . $arrRecord['audit']->name;
                                                ?></strong></em>
                                    </td>
                                    <?php
                                } else {
                                    ?>

                                    <td><?php
                                        if (isset($arrRecord['user'])) {
                                            echo $arrRecord['user']->userfirstname . " " . $arrRecord['user']->userlastname;
                                        }
                                        ?></td>
                                    <td><?php
                                        if (isset($arrRecord['location'])) {
                                            echo $arrRecord['location']->locationname;
                                        }
                                        ?></td>
                                    <td><?php
                                        if (isset($arrRecord['site'])) {
                                            echo $arrRecord['site']->sitename;
                                        }
                                        ?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>

                </table>

                <table class="list_table right">
                    <thead>
                        <tr>
                            <th class="left">Date</th>
                            <th class="left">User</th>
                            <th class="left">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($arrItemLogHistory as $log) {
                            ?>
                            <tr>
                                <td style="width: 100px;"><?php echo date('d-m-Y H:i:s', strtotime($log->date)); ?></td>
                                <td style="width: 100px;"><?= $log->firstname ?> <?= $log->lastname ?></td>
                                <td><?php echo $log->message; ?></td>

                            </tr>
                            <?php
                        }

                        if (!$arrItemLogHistory) {
                            ?>
                            <tr>
                                <td colspan="3">No Logs Found</td>
                            </tr>
                        <? } ?>
                    </tbody>

                </table>
            </div>
        </div>
        <?php
    }


    if ($arrSessionData['objSystemUser']->levelid > 1) {
        ?>
        <div id="fourth_table" class="content_main">


            <table class="list_table">
                <thead>
                    <tr>
                        <th class="left">Date</th>
                        <th class="left">User</th>
                        <th class="left">Reported issue</th>
                        <th class="left">Priority</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($arrItemTicketHistory as $ticket) {
                        ?>
                        <tr>
                            <td style="width: 120px;"><?php echo date('d/m/Y H:i:s', strtotime($ticket['date'])); ?></td>
                            <td style="width: 100px;"><?php echo $ticket['username']; ?></td>
                            <td><?php echo $ticket['description']; ?></td>
                            <td><?php echo $priorities[$ticket['priority']]; ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>

            </table>
        </div>




        <div id="pat_table" class="content_main">


            <table class="list_table">
                <thead>
                    <tr>
                        <th class="left">PAT Date</th>
                        <th class="left">PAT Test Status </th>
                        <th class="left">Logged By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
//    var_dump($arrPatHistory);

                    foreach ($arrPatHistory["results"] as $pat_result) {
                        ?>
                        <tr>
                            <td><?php echo date('d/m/Y h:i:s', strtotime($pat_result->date)); ?></td>
                            <td><?php echo $pat_result->pattest_name; ?></td>
                            <td><?php echo $pat_result->firstname . ' ' . $pat_result->lastname ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>

            </table>
        </div>



    <?php }
    ?>


    <?php if ($this->session->userdata('objSystemUser')->compliance == 1) {
        ?>
        <div id="third_table" class="content_main">
    <!--                        <style>
                .due_table_contents {
                    float: right;
                }
                .compliance_box_top {
                    min-height: 50px;
                    width: 100%;
                }
                .dataTable tfoot {
                    border-collapse: collapse;
                    display: table-header-group;
                    width: 100% !important;
                }
                .dataTable tfoot select{
                    width:100%!important;
                 }
            </style>-->

            <div class="compliance_box_top">
                <div class="button_holder">
                    <div id="export_csv">
                        <a class="button" id="exportCsvButton" href="#">Export as CSV</a>
                        <a class="button" id="exportPdfButton" href="#">Export as PDF</a>
                    </div>
                </div>
                <div class="due_table_contents">
                        <!--<input id="goto_page" style="float: right;" type="number">-->
                </div>

            </div>
          <!--                <table class="list_table">
                              <thead>
                                  <tr>
                                      <th class="left">Compliance Check</th>
                                      <th class="left">Check Result</th>
                                      <th class="left">Check Notes</th>
                                      <th class="left">Logged by</th>
                                  </tr>
                              </thead>
                              <tbody>
            <?php
            foreach ($arrItemCompliance as $check) {
                ?>
                                              <tr>
                                                  <td><?php echo date('d/m/Y', strtotime($check['test_date'])); ?></td>
                                                  <td><?php echo ($check['result'] == 1) ? "Pass" : "Fail"; ?></td>
                                                  <td><?php echo $check['test_notes']; ?></td>
                                                  <td><?php echo $check['test_person']; ?></td>
                                              </tr>
                <?php
            }
            ?>

                              </tbody>

                          </table>-->
            <div id="history" class="form_block">

                <table id="history_table" class="list_table" frame="box" rules="all">
                    <thead>
                        <tr>
                            <th data-export="false">Actions</th>
                            <th data-export="true">Compliance Name</th>
                            <th data-export="true">Logged By</th>
                            <th data-export="true">Due Date</th>
                            <th data-export="true">Complete Date</th>
                            <th data-export="true">Complete Time</th>
                            <th data-export="true">Result</th>
                            <th data-export="true">No Of Task</th>
                            <th data-export="true">Tasks Failed</th>

                            <th hidden=''></th>
                            <th hidden=''></th>
                            <th data-export="false">Doc</th>
                        </tr>

                    </thead>
                    <tfoot>
                    <th>Actions</th>
                    <th>Compliance Name</th>
                    <th>Logged By</th>
                    <th>Due Date</th>
                    <th>Complete Date</th>
                    <th>Complete Time</th>
                    <th>Result</th>
                    <th>No Of Task</th>
                    <th>Checks Failed</th>
                    <th></th>
                    </tfoot>
                    <tbody>

                        <?php
                        foreach ($dueTests as $key => $value) {

                            $due_date = date('d/m/Y', strtotime($value['test_date'] . " +" . $value['test_days'] . " days")); // due date calculation
                            $missed = '';
                            if ($value['test_days'] > 0) {    //Missed Check Logic
                                switch ($value['test_days']) {
                                    case 1: {    //Daily
                                            if (strtotime($due_date . ' +1 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 5: {    //Daily (Mon-Fri)
                                            if (strtotime($due_date . ' +1 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 7: {    //Weekly
                                            if (strtotime($due_date . ' +7 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 31: {    //Monthly
                                            if (strtotime($due_date . ' +31 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 90: {    //Quaterly
                                            if (strtotime($due_date . ' +31 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 121: {    //Tri-Annual
                                            if (strtotime($due_date . ' +45 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 182: {    //Six Monthly
                                            if (strtotime($due_date . ' +45 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 365: {    //Annual
                                            if (strtotime($due_date . ' +60 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 730: {    //2 Year
                                            if (strtotime($due_date . ' +60 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                    case 1095: {    //3 Year
                                            if (strtotime($due_date . ' +60 day') > strtotime('now')) {
                                                $missed = 'Missed';
                                            }
                                            break;
                                        }
                                }
//                            if($missed=='Missed')
//                                var_dump ($value['test_type']);
                            }
                            ?>
                            <tr>
                                <td><a href='javascript:showTasks(<?php print json_encode($value['tasks']); ?>,"<?php
                                    if ($missed == '') {
                                        print 0;
                                    } else {
                                        print 1;
                                    }
                                    ?>")'><img width="20px" src="/img/icons/16/view.png"></a></td>
                                <td><?php print $value['test_type_name']; ?></td>
                                <td><?php print trim($value['test_person']); ?></td>
                                <td><?php echo ($due_date == '01/01/1970') ? '-' : $due_date; ?></td>
                                <td><?php (isset($value['test_date'])) ? print date('d/m/Y', strtotime($value['test_date'])) : print "Never Tested"; ?></td>
                                <td><?php
                                    if (isset($value['test_date'])) {
                                        print date('h:i A', strtotime($value['test_date']));
                                    } else {
                                        print "Never Tested";
                                    }
                                    ?></td>
                                <td><?php
                                    if ($missed == '') {
                                        if ($value['result']) {
                                            $flag = TRUE;
                                            $failedTaskCount = 0;
                                            foreach ($value['tasks'] as $key1 => $value1) {
                                                if ($value1['result'] == 0) {
                                                    $failedTaskCount++;
                                                    $flag = FALSE;
//                                       break;
                                                }
                                            }
                                            if ($flag)
                                                print 'Pass';
                                            else
                                                print 'Fail';
                                        }else {
                                            print 'Fail';
                                        }
                                    } else {
                                        print $missed;
                                    }
                                    ?></td>
                                <td><?php print $value['total_tasks']; ?></td>
                                <td></td>
                                <td hidden=''><?php print json_encode($value['tasks']); ?></td>
                                <td hidden=''><?php echo $objItem->barcode; ?></td>
                                <td><a class="getPdf_link" href="#"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/img/pdf.png" title="Get pdf" alt="Get pdf" /></a></td>
                            </tr>


                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <form id="export_csv_form" hidden="" action="<?php echo base_url('/items/exporttocsv'); ?>" method="post">
                <input id="csv_table_content" name="allData">
                <input id="pdfTasks" hidden="" name="tasks">
                <input type="submit">
            </form>
            <script>
                $(document).ready(function () {
                    $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});

    <?php if ($this->uri->segment(2) == 'editItem') { ?>
                        $('.update').css('display', 'block');
                        $("#view_itemdetails input").removeAttr("disabled");
                        $("#view_itemdetails textarea").removeAttr("disabled");
                        $("#view_itemdetails select").removeAttr("disabled");
                        $("#item_purchased").datepicker("option", "disabled", false);
                        $("#item_warranty").datepicker("option", "disabled", false);
                        $("#item_replace").datepicker("option", "disabled", false);
                        $("#item_pattestdate").datepicker("option", "disabled", false);
                        $("#fault_date").datepicker("option", "disabled", false);
                        $("#compliance_date").datepicker("option", "disabled", false);
                        $("#custom_field_div input").removeAttr("disabled");
    <?php }
    ?>

                    //            Trigging Of Edit Button
                    $("#item_edit").click(function () {
                        $('.update').css('display', 'block');
                        $("#view_itemdetails input").removeAttr("disabled");
                        $("#view_itemdetails textarea").removeAttr("disabled");
                        $("#view_itemdetails select").removeAttr("disabled");
                        $("#custom_field_div input").removeAttr("disabled");
                        $("#item_purchased").datepicker("option", "disabled", false);
                        $("#item_warranty").datepicker("option", "disabled", false);
                        $("#item_replace").datepicker("option", "disabled", false);
                        $("#item_pattestdate").datepicker("option", "disabled", false);
                        $("#fault_date").datepicker("option", "disabled", false);
                        $("#compliance_date").datepicker("option", "disabled", false);

                    });


                    $('#chooseColumnsForm').trigger('reset');
                    $('#history_table').find('td:empty').html('&nbsp;');
                    var colCount = 0;
                    var arr = [];
                    $('#history_table thead tr th').each(function () {
                        if ($(this).attr("hidden") || $(this).attr('data-export') == 'false') {

                        } else {
                            arr.push($(this).index());
                        }
                    });

                    //            arr.pop();
                    console.log(arr);

                    var table = $('#history_table').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                        "order": [[4, "desc"]],
                        columnDefs: [
                            {type: 'date-uk', targets: 3},
                            {type: 'date-uk', targets: 4}
                        ]
                                //                "dom": 'T<"clear">lfrtip',
                                //                "tableTools": {
                                //                    "aButtons": [{
                                //                            "sExtends": "csv",
                                //                            "mColumns": arr
                                //                        },
                                ////                        {
                                ////                            "sExtends": "pdf",
                                ////                            "mColumns": arr
                                ////                        }
                                //                    ],
                                //                    "sSwfPath": "<?php echo base_url(); ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                                //                }
                    });


                    //            exportButtonSetup();
                    //            toggleColumns(table);

                    //        var filtered = '';
                    //        <?php $filter = $this->session->userdata('comHistory_chk'); ?>
                    //        filtered = <?php echo $filter; ?>;
                    //        console.log(filtered);
                    //
                    //        if(filtered != ''){
                    //            console.log('not null '+filtered);
                    //            $(".next_due_check[value='"+filtered+"']").prop('checked',true);
                    //        }
                    //        else{
                    //            console.log('default with null'+filtered);
                    //            $(".next_due_check[value='1']").prop('checked',true);
                    //        }
                    //
                    //         $('body').on('click', '#toggleColButton', function() {
                    //                    toggleColumns(table);
                    //        });

                    $('.dataTables_length').appendTo('.due_table_contents');
                    //        $('.dataTables_paginate').appendTo('.due_table_contents');
                    //        $('.dataTables_filter').remove();

                    $("#history_table tfoot th").each(function (i) {
                        if (i == 1 || i == 2 || i == 6) {
                            var select = $('<select><option value="">Reset Filter</option></select>')
                                    .appendTo($(this).empty())
                                    .on('change', function () {
                                        if ($(this).val() != '')
                                        {
                                            console.log(table);
                                            table.column(i)
                                                    .search('^' + $(this).val() + '$', true, false)
                                                    .draw();
                                        }
                                        else {
                                            console.log(table);
                                            $('.dataTables_length').prependTo('.dataTables_wrapper');
                                            table.destroy();
                                            table = $('#history_table').DataTable({
                                                "pagingType": "full_numbers",
                                                "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                                                "order": [[4, "asc"]],
                                                columnDefs: [
                                                    {type: 'date-uk', targets: 3},
                                                    {type: 'date-uk', targets: 4}
                                                ]
                                                        //                                "dom": 'T<"clear">lfrtip',
                                                        //                                "tableTools": {
                                                        //                                    "aButtons": [{
                                                        //                                            "sExtends": "csv",
                                                        //                                            "mColumns": arr
                                                        //                                        },
                                                        ////                                        {
                                                        ////                                            "sExtends": "pdf",
                                                        ////                                            "mColumns": arr
                                                        ////                                        }
                                                        //                                    ],
                                                        //                                    "sSwfPath": "<?php echo base_url(); ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                                                        //                                }
                                            });
                                            //                            exportButtonSetup();
                                            //                            toggleColumns(table);

                                            $('.dataTables_length').appendTo('.due_table_contents');
                                        }
                                    });

                            table.column(i).data().unique().sort().each(function (d, j) {
                                if (d != '&nbsp;')
                                    select.append('<option value="' + d + '">' + d + '</option>');
                            });
                        }
                        else
                            $(this).html("&nbsp;");
                    });

                    setTimeout(function () {
                        $('#history_table').wrap('<div style="width:100%;overflow-x:auto;min-height:300px;background:#fff;"/>');
                    }, 1000);
                    //  ------------------export---------
                    $('#exportPdfButton').on('click', function (e) {

                        var data = table
                                .data()
                                .map(function (row) {
                                    //                    console.log(row);
                                    var rowArr = [];
                                    $.each(arr, function (i, v) {
                                        rowArr.push(row[v]);
                                    });
                                    return '<td>' + rowArr.join('</td><td>') + '</td>';
                                })
                                .join('</tr><tr>');
                        data = '<tbody><tr>' + data + '</tr></tbody>';
                        var cloneHead = [];
                        var head = $('#history_table thead').clone();
                        head.find('th[data-export="true"]').each(function (i) {
                            console.log($(this).html());
                            cloneHead.push($(this).html());
                        });
                        cloneHead = '<thead><tr><th>' + cloneHead.join('</th><th>') + '</th></tr></thead>';

                        console.log(cloneHead);
                        $('#exp_table_content').val(cloneHead + data);
                        $('#export_form').submit();
                    });

                    // ----------CSV Export----------------
                    $('#exportCsvButton').on('click', function (e) {
                        var data1 = $("#history_table").dataTable()._('tr', {"filter": "applied"});
                        //             var data = table.data();
                        //             console.log(data1);
                        //             console.log(data1);
                        //             var data = table
                        //                .data()
                        var data = data1.map(function (row) {
                            //                    console.log(row);
                            var rowArr = [];
                            $.each(arr, function (i, v) {
                                rowArr.push(row[v]);
                            });
                            return rowArr.join(',');
                        }).join('|');
                        //                console.log(data);
                        var cloneHead = [];
                        var head = $('#history_table thead').clone();
                        head.find('th[data-export="true"]').each(function (i) {
                            //                console.log($(this).html());
                            cloneHead.push($(this).html());
                        });
                        cloneHead = cloneHead.join(',');

                        //            alert(cloneHead+data);
                        $('#csv_table_content').val(cloneHead + '|' + data);
                        $('#export_csv_form').submit();
                    });


                    //        --------Clear Filter-----------
                    $('#clearFilter').on('click', function () {
                        $('.dataTable').find('tfoot th select option[value=""]').prop('selected', true);
                        $('.dataTables_length').prependTo('.dataTables_wrapper');
                        table.destroy();
                        table = $('#history_table').DataTable({
                            "pagingType": "full_numbers",
                            "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
                            "order": [[4, "asc"]],
                            columnDefs: [
                                {type: 'date-uk', targets: 3},
                                {type: 'date-uk', targets: 4}
                            ]
                                    //                "dom": 'T<"clear">lfrtip',
                                    //                "tableTools": {
                                    //                    "aButtons": [{
                                    //                            "sExtends": "csv",
                                    //                            "mColumns": arr
                                    //                        },
                                    ////                                        {
                                    ////                                            "sExtends": "pdf",
                                    ////                                            "mColumns": arr
                                    ////                                        }
                                    //                    ],
                                    //                    "sSwfPath": "<?php echo base_url(); ?>../brochure/js/datatable/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                                    //                }
                        });
                        //            exportButtonSetup();
                        //            toggleColumns(table);
                        //
                        $('.dataTables_length').appendTo('.due_table_contents');
                    });


                    $('body').on("click", '.getPdf_link', function () {
                        var row = $(this).parent('td').parent('tr');
                        var rowData = table.row(row).data();
                        rowData.shift();
                        //            console.log(rowData[7]);
                        //            var temp = array2json(rowData);
                        //            console.log(temp);
                        ;
                        $('#genPdf_form input#pdfTasks').val(rowData[8]);
                        rowData[8] = '';
                        console.log(rowData);
                        $('#genPdf_form input#pdfAllData').val(rowData);
                        $('#genPdf_form').submit();
                    });
                    //
                    //        $(".next_due_check").click(function(){
                    //            var chkgrp = $('.next_due_check:checked');
                    //            chkgrp.not(this).prop('checked',false);
                    //            var filter = [];
                    //            $('.next_due_check:checked').each(function(){
                    //                filter.push($(this).val());
                    //            });
                    //            $('#filter_in').val(filter);
                    //            if($('.next_due_check:checked').length){
                    //
                    //                $('#filter_form').submit();
                    //            }
                    //        });

                });

                function showTasks(jsonData, result)
                {
                    $('#complianceTaskModal').find('tbody').html('');
                    if (!$.isEmptyObject(jsonData)) {
                        $.each(jsonData, function (k, v) {
                            console.log(v['task_name']);
                            console.log(v['result']);

                            if ($.isNumeric(v['result'])) {
                                if (v['result'] == 1)
                                    result = 'PASS';
                                else
                                    result = 'FAIL';
                            } else {
                                var result = v['result'];
                            }

                            $('#complianceTaskModal').find('tbody').append('<tr><td>' + v['task_name'] + '</td><td class="tResult">' + result + '</td><td class="tNotes">' + v['test_notes'] + '</td></tr>');

                        });
                        if (result == 1)
                        {
                            $('#complianceTaskModal').find('tbody tr td.tResult').html('Missed');
                            $('#complianceTaskModal').find('tbody tr td.tNotes').html('');
                        }
                    }
                    else {
                        $('#complianceTaskModal').find('tbody').append('<tr><td colspan="3"><span>No Tasks.</span></td></tr>');
                    }

                    $('#complianceTaskModal').modal('show');
                }


                //    function exportButtonSetup(){
                //        $(document).find(".DTTT_container").prependTo('#export_csv');
                //        $(document).find(".DTTT_button.DTTT_button_pdf").addClass('button').text('Export to '+ $(document).find(".DTTT_button.DTTT_button_pdf").text());
                //        setTimeout(function(){
                //            var width = $('.DTTT_container').outerWidth()/2;
                //            $(document).find(".DTTT_button.DTTT_button_pdf div").css({'left':width+'px','margin-left':'4px'});
                //        },2000);
                //        $(document).find(".DTTT_button.DTTT_button_csv").addClass('button').text('Export to '+ $(document).find(".DTTT_button.DTTT_button_csv").text());
                //    }



            </script>
            <style>
                .DTTT_container{
                    display: block;
                }
                .DTTT_container a{
                    margin-left: 4px;
                }
                #export_csv{
                    min-width: 40%;
                }
                #export_csv a{
                    float: left;
                    margin-left: 5px;
                }
                .compliance_box_top {
                    margin-bottom: 50px;
                    min-height: 45px;
                }
                .due_table_contents{
                    margin-top: 60px;
                }
            </style>
            <form id="export_form" hidden="" action="<?php echo base_url('/compliance/exportToPdf'); ?>" method="post">
                <input id="exp_table_content" name="allData">
                <input name="filename" value="Compliance History">
                <input type="submit">
            </form>

            <form id="genPdf_form" action="<?php echo site_url('items/generateHistoryPdf'); ?>" method="post">
                <input id="pdfAllData" hidden="" name="allData">
                <input id="pdfTasks" hidden="" name="tasks">
            </form>
            <!--Compliance Task Modal-->
            <div class="modal fade" id="complianceTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Tasks</h4>
                        </div>
                        <div class="modal-body">
                            <table class="list_table">
                                <thead><tr><th>Task Name</th><th>Task Result</th><th>Notes</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    <?php } ?>
</div>
</div>