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
                <li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $objItem->itemphotoid; ?>') no-repeat center center;"></li>
                <li class="clearfix" id="item_text_holder"><h2><?php echo $objItem->manufacturer." ".$objItem->model; ?></h2><p><?php echo $objItem->categoryname; ?><br /><?php echo $objItem->barcode; ?></p></li>
            </ul>

            <ul class="field" id="patform_pat_holder">
                <li class="header">Current PAT Result</li>
                <li><h3>PAT Date</h3><?php echo doParseDate($objItem->pattest_date); ?></li>
                <li><h3>PAT Status</h3><?php 
                
                    if ($objItem->pattest_status != "")
                                    {
                                        if ($objItem->pattest_status > 0)
                                        {
                                            echo '<span style="background-color:green;padding:4px;color:white;font-weight:bold;">PASS</span>';
                                        }
                                        else
                                        {
                                            echo '<span style="background-color:red;padding:4px;color:white;font-weight:bold;">FAIL</span>';
                                        }
                                    }
                                    else 
                                    {
                                        echo "Unknown";
                                    }
                
                     ?></li>
            </ul>
            
            <ul class="form">
                <li class="header">New PAT Result</li>
                
              
                
                
                <li class="arrow">
                    <label for="patform_pat_date">PAT Date</label>
                    <input type="date" name="patform_pat_date" id="patform_pat_date" value="<?php echo date('Y-m-d');?>" max="" />
                </li>
                
                
                
                <li class="arrow">
                    <label for="patform_pat_result">Result</label>
                    <select name="patform_pat_result" id="patform_pat_result">
                        <option value="-1">N/A</option>
                        <option value="1">PASS</option>
                        <option value="0">FAIL</option>
                    </select>
                </li>
            </ul>
            
<p><a href="#" class="green button" onclick="isaPat_doSaveResult('<?php echo $objItem->barcode; ?>');">Save Result</a></p>