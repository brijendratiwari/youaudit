<div class="row col-lg-12">
    <?php $logo = 'logo.png'; ?>
    <div class='logo_cls'><img alt='Youaudit' src='http://192.168.10.139:8080/youaudit/iwa/brochure/logo/logo.png'></div>
</div>
<div id="wrapper">
    <div id="content">
        <div class="box">
            <div id="first_table" class="content_main pdf_details">
                <div class="row col-md-12 col-lg-12 pdf_details_headding">
                    <h3>Item Details</h3>
                </div>

                <div class="row">

                    <div class="col-lg-4 col-md-4">

                        <div class="table-responsive" id="view_itemdetails">
                            <table class="table">
                                <tbody>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header">

                                        <td style="width: 50%"> <div style='font-weight: bold; color:#fff;'>
                                                Item Details
                                            </div></td>
                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Category</td>
                                        <td>

                                            <label><?php
                                                if ($objItem->categoryname) {
                                                    echo $objItem->categoryname;
                                                } else {
                                                    echo '-';
                                                }
                                                ?></label>
                                        </td>
                                    </tr>

                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Item/Menu*</td>
                                        <td> 
                                            <label><?php
                                                if ($objItem->item_manu_name) {
                                                    echo $objItem->item_manu_name;
                                                } else {
                                                    echo '-';
                                                }
                                                ?></label>

                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Manufacturer*</td>
                                        <td>
                                            <label><?php
                                                if ($objItem->manufacturer) {
                                                    echo $objItem->manufacturer;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label>


                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Model</td>
                                        <td> <label><?php
                                                if ($objItem->model) {
                                                    echo $objItem->model;
                                                } else {
                                                    echo '-';
                                                }
                                                ?></label>

                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Quantity</td>
                                        <td><label><?php
                                                if ($objItem->quantity == 0) {
                                                    echo '-';
                                                } else {
                                                    echo $objItem->quantity;
                                                }
                                                ?></label> 

                                        </td>
                                    </tr>

                                    <tr  style="border:1px solid #00aeef;"  class="tb_header">
                                        <td> <div style='font-weight: bold; color:#fff;'>
                                                Item Details
                                            </div></td>
                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>QR Code</td>
                                        <td><label><?php echo $objItem->barcode; ?></label> 
                                        </td>

                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Serial No</td>
                                        <td><label><?php echo $objItem->serial_number; ?></label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header">
                                        <td>Item Quality</td>
                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Status</td>
                                        <td>  
                                            <label><?php
                                                if ($objItem->itemstatusname) {
                                                    echo $objItem->itemstatusname;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label>

                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Condition</td>
                                        <td>
                                            <label><?php
                                                if ($objItem->condition_name) {
                                                    echo $objItem->condition_name;
                                                } else {
                                                    echo '-';
                                                }
                                                ?></label>
                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header">
                                        <td> <div style='font-weight: bold; color:#fff;'>
                                                Ownership
                                            </div></td>

                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Owner*</td>
                                        <td> 
                                            <label><?php echo $objItem->userfirstname . " " . $objItem->userlastname; ?></label>

                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Site*</td>
                                        <td>
                                            <label><?php
                                                if ($objItem->sitename) {
                                                    echo $objItem->sitename;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label>

                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Location*</td>
                                        <td>
                                            <label><?php
                                                if ($objItem->locationname) {
                                                    echo $objItem->locationname;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label>


                                            <?php echo form_error('location_id'); ?>  
                                        </td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Supplier*</td>
                                        <td>
                                            <label><?php
                                                if ($objItem->suppliers_title) {
                                                    echo $objItem->suppliers_title;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label>

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <table class="table">
                                <tbody>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header">

                                        <td> <div style='font-weight: bold; color:#fff;'>
                                                Notes
                                            </div></td>
                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Notes</td>
                                        <td>
                                            <label><?php
                                                if ($objItem->notes) {
                                                    echo $objItem->notes;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="table-responsive" id="view_itemdetails">
                            <table class="table" >
                                <tbody>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header">

                                        <td style="width: 50%"> <div style='font-weight: bold; color:#fff;'>
                                                Item Dates
                                            </div></td>

                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Purchase Date</td>
                                        <td><label><?php
                                                if ($objItem->purchase_date != '') {
                                                    echo date('d/m/y', strtotime($objItem->purchase_date));
                                                } else {
                                                    
                                                }
                                                ?></label></td>

                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Warranty Expiry</td>
                                        <td><label><?php
                                                if ($objItem->warranty_date != '') {
                                                    echo date('d/m/y', strtotime($objItem->warranty_date));
                                                } else {
                                                    
                                                }
                                                ?></label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Replacement Due</td>
                                        <td><label><?php
                                                if ($objItem->replace_date != '') {
                                                    echo date('d/m/y', strtotime($objItem->replace_date));
                                                } else {
                                                    
                                                }
                                                ?></label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Age Of Asset</td>
                                        <td><label><?php
                                                if (isset($objItem->purchase_date)) {
                                                    $date2 = date('d-m-Y', strtotime($objItem->purchase_date));
                                                    $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                                                    $diff = abs(strtotime($date2) - strtotime($date1));

                                                    $years = floor($diff / (365 * 60 * 60 * 24));
                                                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                                                    echo $years . ' year ' . $months . ' month ';
                                                }
                                                ?></label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header">
                                        <td>Item Valuation</td>
                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Purchase Price</td>
                                        <td><label><?php
                                                if ($objItem->value) {
                                                    echo $objItem->value;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;" >
                                        <td>Current Value</td>
                                        <td><label><?php
                                                if ($objItem->current_value) {
                                                    echo $objItem->current_value;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label>
                                        </td>
                                    </tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="view_itemdetails_fault">
                            <table class="table" >
                                <tbody>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header_warning">

                                        <td style="width: 50%"> <div style='font-weight: bold; color:#fff;'>
                                                Total Faults
                                            </div></td>
                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_font_warning">
                                        <td>Total Faults</td>
                                        <td><label><?php
                                                if ($numberOfFaults) {
                                                    echo $numberOfFaults;
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_font_warning">
                                        <td>Last Fault Date</td>
                                        <td><label><?php
                                                if ($lastDateOfFaults) {
                                                    echo date("d/m/Y h:i:s", strtotime($lastDateOfFaults));
                                                } else {
                                                    echo "-";
                                                }
                                                ?></label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_font_warning">
                                        <td>Last Compliance Check</td>
                                        <td><label>12/06/2014</label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_font_warning">
                                        <td>Compliance Result</td>
                                        <td><label>MISSED</label></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="view_itemdetails">
                            <table class="table" >
                                <tbody>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_header_success">

                                        <td style="width: 50%"> <div style='font-weight: bold; color:#fff;'>
                                                PAT Test Date
                                            </div></td>
                                        <td></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_font_success">
                                        <td>PAT Test Date </td>
                                        <td><label><?php
                                                if (strtotime($objItem->pattest_date) > 0) {
                                                    echo date('d/m/Y', strtotime($objItem->pattest_date));
                                                } else {
                                                    echo '';
                                                }
                                                ?> </label></td>
                                    </tr>
                                    <tr  style="border:1px solid #00aeef;"  class="tb_font_success">
                                        <td>PAT Result</td>
                                        <td>  
                                            <label><?php echo $objItem->pat_status; ?></label>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <table class="table">
                            <tbody>

                                <tr  style="border:1px solid #00aeef;"  class="tb_header">

                                    <td style="width: 50%"> <div style='font-weight: bold; color:#fff;'>
                                            Items Custom Fields
                                        </div></td>
                                    <td></td>
                                </tr>  


                                <?php foreach ($arrCustomFields as $custom_name) {
                                    ?>

                                    <tr  style="border:1px solid #00aeef;" >    
                                        <td><?php echo $custom_name->field_name; ?></td>


                                        <td>  <label><?php
                                                if (isset($custom_name->content)) {
                                                    echo $custom_name->content;
                                                }
                                                ?></label></td></tr>



                                    <?php
                                }
                                ?>

                            </tbody></table>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <table class="table">
                            <tbody class="blue-border">
                                <tr  style="border:1px solid #00aeef;"  class="tb_header">

                                    <td> <span style='font-weight: bold; color:#fff;'>
                                            Photo
                                        </span></td>
                                </tr>  

                                <tr  style="border:1px solid #00aeef;" >


                                    <td>  
                                        <?php
                                        if ($itemPics) {
                                            if (strpos($itemPics, ',') != false) {
                                                $arr_image = explode(',', $itemPics);
                                                ?>
                                                <?php for ($i = 0; $i < count($arr_image); $i++) { ?>

                                                    <img style="height: 150px; width: 155px;padding: 15px;float: left;" class="thumbnail" src="<?php echo base_url() . $arr_image[$i]; ?>"/>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <img style="height: 150px; width: 155px;padding: 15px;float: left;" class="thumbnail" src="<?php echo base_url() . $itemPics; ?> "/>    
                                            <?php
                                            }
                                        }
                                        ?>

                                    </td>
                                </tr>                             
                            </tbody></table>
                    </div>

                </div>
            </div>
        </div> 
    </div>
</div>

<!--<style>
    .text_width{
        width:20%;
    }
    .logo_cls {
        float: left;
        margin: 20px;
        max-height: 250px;
        max-width: 300px;
    }
    #view_itemdetails .table tr, #acc_details .table tr{
        box-shadow: 0 -1px 0 0 #59b2d9 inset !important;
    }
    .tb_header {
        background: #00aeef none repeat scroll 0 0;
        color: #ffffff;
        font-weight: bold; color:#fff;;
    }
</style>-->
