<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<script>
    $(document).ready(function() {

        $('#mycolorpicker').html('');
        $('#demo').hide();
        $('#mycolorpicker').html('<div class="form-item"><label for="color">Color:</label><input type="text" id="color" name="color" value="<?php
if ($this->session->userdata['theme_design']->color != "") {
    echo $this->session->userdata['theme_design']->color;
} else {
    echo '#000';
}
?>" /></div><div id="colorpickerField1"></div>');
        $('#colorpickerField1').farbtastic('#color');

    });

    function Handlechange()
    {
        var fileinput = document.getElementById("item_photo");
        document.getElementById("select_file").innerHTML = fileinput.value.replace("C:\\fakepath\\", "");
    }
    function Showfile()
    {
        var fileinput = document.getElementById("fevicon_icon");
        document.getElementById("choose_file").innerHTML = fileinput.value.replace("C:\\fakepath\\", "");
    }
    $('body').on('change', '#account_fleet', function()
    {
        var fleet = $('#account_fleet option:selected').val();
        if (fleet == 0)
        {
         bootbox.confirm("Do you want to remove this Module ?", function(result) {
            if (result) {
               return true;
            } else {
               $("#account_fleet option[value='1']").prop("selected", "selected");
            }
        });
        }
    });
    $('body').on('change', '#account_compliance', function()
    {
        var compliance = $('#account_compliance option:selected').val();
        if (compliance == 0)
        {
         bootbox.confirm("Do you want to remove this Module ?", function(result) {
            if (result) {
               return true;
            } else {
               $("#account_compliance option[value='1']").prop("selected", "selected");
            }
        });
        }
    });
    $('body').on('change', '#account_condition', function()
    {
        var condition = $('#account_condition option:selected').val();
        if (condition == 0)
        {
         bootbox.confirm("Do you want to remove this Module ?", function(result) {
            if (result) {
               return true;
            } else {
               $("#account_condition option[value='1']").prop("selected", "selected");
            }
        });
        }
    });
    $('body').on('change', '#account_depreciation', function()
    {
        var depreciation = $('#account_depreciation option:selected').val();
        if (depreciation == 0)
        {
         bootbox.confirm("Do you want to remove this Module ?", function(result) {
            if (result) {
               return true;
            } else {
               $("#account_depreciation option[value='1']").prop("selected", "selected");
            }
        });
        }
    });
    $('body').on('change', '#account_reporting', function()
    {
        var reporting = $('#account_reporting option:selected').val();
        if (reporting == 0)
        {
         bootbox.confirm("Do you want to remove this Module ?", function(result) {
            if (result) {
               return true;
            } else {
               $("#account_reporting option[value='1']").prop("selected", "selected");
            }
        });
        }
    });
    

</script>
<style>
    #acc_details .table tr {
        box-shadow: 0 -1px 0 0 #00aeef inset;
    }
     .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        min-height: 75px;
        overflow: auto !important;
    }
</style>
<div class="box">
    <div class="heading">
        <h1>Edit Account</h1>

        <div class="buttons">
            <a class="button icon-with-text round" onclick="$('#edit_account_form').submit();"><i class="fa fa-arrow-circle-down"></i>Save</a>
        </div>
        <div class="col-md-10 text-right">
            <span class="com-name">                     <?= $strAccountName; ?>
                <!--<img src="<?= base_url('/img/circle-red.png'); ?>" width="60" /></span>-->
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

    <div class="box_content">

        <div class="error_outer"><?php echo $error; ?></div>

        <p>Use this form to update your account details.</p>
        <div class="content_main row">
            <?php echo form_open_multipart('account/edit/', array('id' => 'edit_account_form')); ?>
            <div id="general_information" class="form_block col-md-6">
                <div id="acc_details" class="table-responsive">

                    <table class="table">
                        <tbody>

                            <tr class="tb_header"><td>General Information</td><td></td></tr>

                            <tr><td><label for="account_name">Name*</label></td>
                                <td><input class="form-control" type="input" name="account_name" value="<?php echo $strAccountName; ?>" /></td><?php echo form_error('account_name'); ?></tr>
                            <tr><td><label for="account_address">Address*</label></td>
                                <td><input class="form-control" type="input" name="account_address" value="<?php echo $strAccountAddress; ?>" /></td><?php echo form_error('account_address'); ?></tr>
                            <tr><td><label for="account_city">City*</label></td>
                                <td><input class="form-control" type="input" name="account_city" value="<?php echo $strAccountCity; ?>" /></td><?php echo form_error('account_city'); ?></tr>
                            <tr><td><label for="account_state">State</label></td>
                                <td> 
                                    <select class="form-control" name="account_state" id="account_state">
                                        <option name="NSW" id="NSW" value="NSW" <?php echo ($strAccountState == 'NSW') ? 'selected="selected"' : ''; ?>>NSW</option>
                                        <option name="VIC" id="VIC" value="VIC" <?php echo ($strAccountState == 'VIC') ? 'selected="selected"' : ''; ?>>VIC</option>                <option name="QLD" id="QLD" value="QLD" <?php echo ($strAccountState == 'QLD') ? 'selected="selected"' : ''; ?>>QLD</option>
                                        <option name="SA" id="SA" value="SA" <?php echo ($strAccountState == 'SA') ? 'selected="selected"' : ''; ?>>SA</option>
                                        <option name="TAS" id="TAS" value="TAS" <?php echo ($strAccountState == 'TAS') ? 'selected="selected"' : ''; ?>>TAS</option>
                                        <option name="WA" id="WA" value="WA" <?php echo ($strAccountState == 'WA') ? 'selected="selected"' : ''; ?>>WA</option>
                                        <option name="NT" id="NT" value="NT" <?php echo ($strAccountState == 'NT') ? 'selected="selected"' : ''; ?>>NT</option>
                                        <option name="ACT" id="ACT" value="ACT" <?php echo ($strAccountState == 'ACT') ? 'selected="selected"' : ''; ?>>ACT</option>

                                    </select>
                                </td></tr>
                            <tr><td><label for="account_postcode">Post Code*</label></td>
                                <td><input  class="form-control" type="input" name="account_postcode" value="<?php echo $strAccountPostCode; ?>" /></td><?php echo form_error('account_postcode'); ?></tr>

                            <tr><td><label for="account_qrcode">QR Ref Code</label></td>
                                <td><input  class="form-control" type="input" name="account_qrcode" value="<?php echo $strAccountQrcode; ?>" disabled/></td><?php echo form_error('account_qrcode'); ?></tr>
                            <tr><td><label for="account_package">Package</label></td>
                                <td> 
                                    <select class="form-control" name="account_package" id="account_package" disabled>
                                        <option value=""></option>
                                        <?php
                                        foreach ($packages as $pkg) {
                                            if ($pkg['id'] == $strAccountPackage) {
                                                echo '<option value="' . $pkg['id'] . '" selected=selected>' . $pkg['name'] . '</option>';
                                            } else {
                                                echo '<option value="' . $pkg['id'] . '">' . $pkg['name'] . '</option>';
                                            }
                                        }
                                        ?>

                                    </select>
                                </td></tr>
                            <tr class="tb_header"><td>Contact Details</td><td></td></tr>
                            <tr><td><label for="account_contactname">Contact Name*</label></td>
                                <td><input class="form-control" type="input" name="account_contactname" value="<?php echo $strAccountContactName; ?>" /></td><?php echo form_error('account_name'); ?></tr>
                            <tr><td><label for="account_contactemail">Username/Email Address*</label></td><td><input class="form-control" type="input" name="account_contactemail" value="<?php echo $strAccountContactEmail; ?>" /></td><?php echo form_error('account_contactemail'); ?></tr>
                            <tr><td><label for="account_contactnumber">Contact Number*</label></td>
                                <td><input class="form-control" type="input" name="account_contactnumber" value="<?php echo $strAccountContactNumber; ?>" /></td><?php echo form_error('account_contactnumber'); ?></tr>
                            <tr><td><label for="account_supportaddress">Support eMail*</label></td><td><input class="form-control" type="input" name="account_supportaddress" value="<?php echo $strAccountSupportAddress; ?>" /></td><?php echo form_error('account_supportaddress'); ?></tr>
                            <?php if ($this->session->userdata('objSystemUser')->fleet == 1) { ?>
                                <tr><td><label for="account_fleetcontact">Fleet Contact Name</label></td>
                                    <td><input class="form-control" type="input" name="account_fleetcontact" value="<?php if($strAccountFleetContact) echo $strAccountFleetContact; ?>" /></td><?php echo form_error('account_fleetcontact'); ?></tr>
                                <tr><td><label for="account_fleetemail">Fleet Email Address</label></td>
                                    <td><input class="form-control" type="input" name="account_fleetemail" value="<?php if($strAccountFleetEmail)echo $strAccountFleetEmail; ?>" /></td><?php echo form_error('account_fleetemail'); ?></tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="col-md-6">
                <div id="acc_details" class="table-responsive">
                    <table class="table">
                        <tbody>

                            <?php if ($this->session->userdata('objSystemUser')->compliance == 1) { ?>
                                <tr><td><label for="account_compliancecontact">Safety Contact Name</label></td>
                                    <td><input class="form-control" type="input" name="account_compliancecontact" value="<?php if($strAccountComplianceContact) echo $strAccountComplianceContact; ?>" /></td><?php echo form_error('account_compliancecontact'); ?></tr>
                                <tr><td><label for="account_complianceemail">Safety Email Address</label></td>
                                    <td><input class="form-control" type="input" name="account_complianceemail" value="<?php if($strAccountComplianceEmail) echo $strAccountComplianceEmail; ?>" /></td><?php echo form_error('account_complianceemail'); ?></tr>
                            <?php } ?>

                            <tr><td><label for="account_fleet">Fleet Module</label></td>
                                <td> 
                                    <select class="form-control" name="account_fleet" id="account_fleet">
                                        <option value="0" <?php echo ($strAccountFleet == '0') ? 'selected="selected"' : ''; ?>>No</option>
                                        <option value="1" <?php echo ($strAccountFleet == '1') ? 'selected="selected"' : ''; ?>>Yes</option>       
                                    </select>
                                </td></tr>
                            <tr><td><label for="account_compliance">Safety Module</label></td>
                                <td> 
                                    <select class="form-control" name="account_compliance" id="account_compliance">
                                        <option value="0" <?php echo ($strAccountCompliance == '0') ? 'selected="selected"' : ''; ?>>No</option>
                                        <option value="1" <?php echo ($strAccountCompliance == '1') ? 'selected="selected"' : ''; ?>>Yes</option>       
                                    </select>
                                </td></tr>
                            <tr><td><label for="account_condition">Condition Module</label></td>
                                <td> 
                                    <select class="form-control" name="account_condition" id="account_condition">
                                        <option value="0" <?php echo ($strAccountCondition == '0') ? 'selected="selected"' : ''; ?>>No</option>
                                        <option value="1" <?php echo ($strAccountCondition == '1') ? 'selected="selected"' : ''; ?>>Yes</option>       
                                    </select>
                                </td></tr>
                            <tr><td><label for="account_depreciation">Depreciation Module</label></td>
                                <td> 
                                    <select class="form-control" name="account_depreciation" id="account_depreciation">
                                        <option value="0" <?php echo ($strAccountDepreciation == '0') ? 'selected="selected"' : ''; ?>>No</option>
                                        <option value="1" <?php echo ($strAccountDepreciation == '1') ? 'selected="selected"' : ''; ?>>Yes</option>       
                                    </select>
                                </td></tr>
                            <tr><td><label for="account_reporting">Reporting Module</label></td>
                                <td> 
                                    <select class="form-control" name="account_reporting" id="account_reporting">
                                        <option value="0" <?php echo ($strAccountReporting == '0') ? 'selected="selected"' : ''; ?>>No</option>
                                        <option value="1" <?php echo ($strAccountReporting == '1') ? 'selected="selected"' : ''; ?>>Yes</option>       
                                    </select>
                                </td></tr>                             
                        </tbody>
                    </table>
                </div>
            </div>
            <?php echo form_close(); ?>

            <div class="col-md-6">
                <form enctype="multipart/form-data" id="edit_theme_form" accept-charset="utf-8" method="post" action="<?php echo base_url('/account/update_theme'); ?>">
                    <div id="acc_details" class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr class="tb_header"><td>Theme</td>

                                    <td><button type="submit" class="btn btn-xs thcol col-md-offset-7">Save Changes in Theme</button></td></tr>

                                <tr><td class="col-md-6"><label for="logo">Logo</label>
                                        <div style="color: #0963f6; float: left;">Max Size : 100kb Max Height : 250px Max Width : 600px</div></td>
                                    <td>

                                        <div class="col-md-offset-2 col-md-7" style="height: 28px;"><span id="select_file" class="file-select">choose file <i class="fa fa-sort pull-right"></i>

                                            </span> <input type="file" style="opacity: 0" id="item_photo" class="item_photo" onchange="Handlechange();" name="file" value="<?php echo $arrSessionData['theme_design']->logo; ?>" /></div>

                                    </td><?php echo form_error('upload'); ?></tr>
                                <tr><td><div><label for="favicon">Favicon</label></div>
                                        <div style="color: #0963f6; float: left;">Standard Size : 25*25</div></td>
                                    <td><div class="col-md-offset-2 col-md-7" style="height: 28px;"><span id="choose_file" class="file-select">choose file <i class="fa fa-sort pull-right"></i></span><input  type="file" style="opacity: 0" id="fevicon_icon" class="item_photo" onchange="Showfile();" name="file_favicon" value="<?php echo $arrSessionData['theme_design']->favicon; ?>" /></div></td><?php echo form_error('file_favicon'); ?></tr>
                                <tr id="mycolorpicker"></tr>

                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>
<style>
    .text_width{
        width:20%;
    }
    .form_block {
        float: left;
        width: 50%;
    }
    .text_width {
        width: 50%;
    }
    #mycolorpicker {display: inline-flex; width: 100%; margin-top: 10px;margin-left: 8px;}
    #mycolorpicker .form-item #color {
        background: #fff !important; 
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        color: black !important;
        display: block;
        font-size: 14px;
        height: 34px;
        line-height: 1.42857;
        padding: 6px 12px;
        transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
        width: 100%;}
    .farbtastic, .farbtastic .wheel { float: right} 
    #mycolorpicker .form-item {display: inline-table; float: left; width: 100% !important;}
    #mycolorpicker #colorpickerField1 { float: right; left: 80%; position: relative;}
    .thcol
    {
        background-color: #708090;
    }
    .thcol:hover,.thcol:focus
    {
        color: #fff;
    }


</style>