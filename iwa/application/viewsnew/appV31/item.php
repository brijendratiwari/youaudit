<?php
    function doParseValue($strValue)
    {
        if (($strValue == null) || ($strValue == ""))
        {
            return "Not Available";
        }
        else
        {
            return $strValue;
        }
    }
    function doParseDate($strDate)
    {
        if (($strDate != NULL) && ($strDate != ""))
        {
            
            return date("d/m/Y", strtotime($strDate));
           
        }
        else
        {
            return 'Not Available';
        }
    }
?>
<ul class="profile">
    <li class="picture" style="background:url('https://www.iworkaudit.com/iwa/appversionthree/viewUserHero/<?php echo $arrItem['itemphotoid']; ?>') no-repeat center center;"></li>
    <li class="clearfix" id="item_text_holder"><h2><?php echo $arrItem['manufacturer']." ".$arrItem['model']; ?></h2><p><?php echo $arrItem['categoryname']; ?></p></li>
</ul>
<ul class="field" id="item_data_holder">
    <li class="header">Item Overview</li>
    <li><h3>Barcode</h3><?php echo $arrItem['barcode']; ?></li>
    <li><h3>Serial</h3><?php echo doParseValue($arrItem['serial_number']); ?></li>
    <li><h3>Status</h3><?php echo doParseValue($arrItem['itemstatusname']); ?></li>
</ul>
<ul class="field" id="item_data_notes_holder">
    <li class="header">Item Notes</li>
    <?php
        if ($arrItem['notes'] == "")
        {
    ?>
    <li><em>No notes</em></li>
    <?php
        }
        else
        {
            $arrNotesList = explode('<br />', nl2br($arrItem['notes']));
            foreach($arrNotesList as $strNote)
            {
                ?><li><?php echo $strNote; ?></li><?php
            }
        }
    ?>
</ul>
<ul class="field" id="item_data_pat_holder">
    <li class="header">PAT Information</li>
    <?php
        if ($arrItem['pattest_status']== 5)
        {
            ?>
            <li><em>PAT is not required on this item</em></li>
            <?php
        }
        else
        {
            ?>
            <li><h3>PAT Date</h3><?php echo doParseDate($arrItem['pattest_date']); ?></li>
            <li><h3>PAT Status</h3><?php
            
            if ($arrItem['pattest_status']=== 1)
            {
                ?>
                <span style="background-color:green;padding:4px;color:white;font-weight:bold;">PASS</span>
                <?php
            }
            else 
            {
                if ($arrItem['pattest_status']=== 0)
                {
                ?>
                <span style="background-color:red;padding:4px;color:white;font-weight:bold;">FAIL</span>
                <?php
                }
                else
                {
                ?>Not Available<?php
                }
            }
        }
            ?></li>
</ul>
<ul class="field" id="item_data_dates_holder">
    <li class="header">Item Dates</li>
    <li><h3>Purchased</h3><?php echo doParseDate($arrItem['purchase_date']);?></li>
    <li><h3>Warranty</h3><?php echo doParseDate($arrItem['warranty_date']);?></li>
    <li><h3>Replace</h3><?php echo doParseDate($arrItem['replace_date']); ?></li>
</ul>
<ul id="item_data_options_holder">
    <li class="header">Item Options</li>
    <?php if($this->session->userdata('objAppUser')->compliance == 1) { ?>
        <li class="arrow"><a href="#" onclick="isaItem_showCompliance(<?php echo $arrItem['itemid']; ?>);"><img src="img/icon-bloke.png" width="29" class="ico"> Complete Compliance Checks</a></li>
    <?php } ?>
    <li class="arrow"><a href="#" onclick="isaItem_showOwnershipForm(<?php echo $arrItem['itemid']; ?>);"><img src="img/icon-bloke.png" width="29" class="ico"> Change Item Ownership</a></li>
    <li class="arrow"><a href="#" onclick="isaItem_showChangeStatusForm(<?php echo $arrItem['itemid']; ?>);"><img src="img/icon-bloke.png" width="29" class="ico"> Change Item Status</a></li>
    <li class="arrow"><a href="#" onclick="isaItem_showTicketForm(<?php echo $arrItem['itemid']; ?>);"><img src="img/icon-problem.png" width="29" class="ico"> Report a Problem</a></li>
    <?php if ($this->session->userdata('objAppUser')->levelid > 1) {?>
        <li class="arrow"><a href="#" onclick="isaItem_showPhotoForm(<?php echo $arrItem['itemid']; ?>);"><img src="img/icon-camera.png" width="29" class="ico"> Update Item Photo</a></li>
        <li class="arrow"><a href="#" onclick="isaItem_showCopyForm(<?php echo $arrItem['itemid']; ?>);"><img src="img/icon-add.png" width="29" class="ico"> Add Similar Item</a></li>
    <?php } ?>
</ul>