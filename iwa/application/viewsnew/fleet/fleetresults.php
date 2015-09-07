
<div class="box">
    	<div class="heading">
            <h1><?php echo $strReportName ?></h1>  
            <?php 
                if (count($arrResults) > 0)
                {
            ?>
                <div class="buttons">

                    <a target="_blank" href="<?php echo site_url('fleet/createPdf/'.$strPdfUrl); ?>" class="button">Download as a PDF</a>
                </div>
            <?php
                }
            ?>
        </div>       
    
    <?php 
        if (count($arrResults) > 0)
        {
    ?>
        <div class="box_content">
            <div class="content_main">

            

        <table class="list_table">
            <thead>
                <tr>
                <?php
                    foreach ($arrReportFields as $arrReportField)
                    {
                        echo "<th class=\"left\">".$arrReportField['strName']."</th>";
                    }
                ?>
                </tr>
            </thead>
            <tbody>
            <?php
            $arrTotals = array();
            
            foreach ($arrResults as $objItem)
            {
            ?>
            <tr>
                <?php
                foreach ($arrReportFields as $arrReportField)
                {
                    echo "<td>";
                    if (array_key_exists('strConversion', $arrReportField))
                    {
                        switch ($arrReportField['strConversion'])
                        {
                            case 'date':
                                $arrDate = explode('-', $objItem->{$arrReportField['strFieldReference']});
                                if (count($arrDate) >1) {
                                    echo $arrDate[2]."/".$arrDate[1]."/".$arrDate[0];
                                }
                                else
                                {
                                    echo "Unknown";
                                }
                                break;
                            case 'datetime':
                                $arrDateTime = explode(' ', $objItem->{$arrReportField['strFieldReference']});
                                $strTime = $arrDateTime[1];
                                $arrDate = explode('-', $arrDateTime[0]);
                                echo $arrDate[2]."/".$arrDate[1]."/".$arrDate[0]." ".$strTime;
                                break;
                            case 'pat_result':
                                if ($objItem->{$arrReportField['strFieldReference']} === null)
                                {
                                    echo "-";
                                }
                                else
                                {
                                    if ($objItem->{$arrReportField['strFieldReference']} == 1)
                                    {
                                        echo "Pass";
                                    }
                                    else
                                    {
                                        echo "Fail";
                                    }
                                }
                                break;
                            case 'price':
                                echo $currency.$objItem->{$arrReportField['strFieldReference']};
                                break;
                        }
                    }
                    else 
                    {
                        echo $objItem[$arrReportField['strFieldReference']];
                    }
                    echo "</td>";
                    if (array_key_exists('arrFooter',$arrReportField) 
                            && array_key_exists('booTotal',$arrReportField['arrFooter']))
                    {
                        if (array_key_exists($arrReportField['strFieldReference'], $arrTotals))
                        {
                            $arrTotals[$arrReportField['strFieldReference']] += $objItem->{$arrReportField['strFieldReference']};
                        }
                        else
                        {
                            $arrTotals[$arrReportField['strFieldReference']] = $objItem->{$arrReportField['strFieldReference']};
                        }
                        
                    }
                }
                ?>
            </tr>
            <?php    
            }
            ?>
            </tbody>
            <?php
            echo "<tfoot><tr>";
        
        foreach ($arrReportFields as $arrReportField)
        {
            if (array_key_exists('arrFooter',$arrReportField))
            {
                if (array_key_exists('booTotal',$arrReportField['arrFooter']) 
                        && $arrReportField['arrFooter']['booTotal'])
                {
                    echo "<td><strong>";
                    if (array_key_exists('strConversion', $arrReportField) 
                            && ($arrReportField['strConversion'] == "price"))
                    {
                        echo $currency;
                    }
                    echo number_format($arrTotals[$arrReportField['strFieldReference']], 2);
                    echo "</strong></td>";
                }
                else
                {
                    if (array_key_exists('booTotalLabel',$arrReportField['arrFooter']) 
                        && $arrReportField['arrFooter']['booTotalLabel'])
                    {
                        echo "<td";
                        if (array_key_exists('intColSpan',$arrReportField['arrFooter']) 
                            && ($arrReportField['arrFooter']['intColSpan']>0)) 
                        {
                            echo " colspan=\"".$arrReportField['arrFooter']['intColSpan']."\"";
                        }
                        echo " class=\"right\"><strong>";
                        echo "Totals</strong></td>";
                    }
                }
            }
        }
        echo "</tr></tfoot>";
        ?>
            
            
        </table>
    <?php
        }
        else
        {
    ?>
            <p>Report was empty</p>
    <?php 
        } 
    ?>
            </div>
        </div>
    </div>