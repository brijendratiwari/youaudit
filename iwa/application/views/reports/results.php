    <h2><?php echo $strReportName ?></h2>    
    
    <?php 
        if (count($arrResults) > 0)
        {
    ?>
    <p class="right"><a target="_blank" href="<?php echo site_url('reports/createPdf/'.$strPdfUrl); ?>" class="button">Download as a PDF</a></p>
    
        <table class="full-wide">
            <tr class="header-row">
                <?php
                    foreach ($arrReportFields as $arrReportField)
                    {
                        echo "<td>".$arrReportField['strName']."</td>";
                    }
                ?>
            </tr>
            <?php
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
                                echo $arrDate[2]."/".$arrDate[1]."/".$arrDate[0];
                                break;
                            case 'pat_result':
                                if ($objItem->{$arrReportField['strFieldReference']} == 0)
                                {
                                    echo "Fail";
                                }
                                else
                                {
                                    echo "Pass";
                                }
                                break;
                        }
                    }
                    else 
                    {
                        echo $objItem->{$arrReportField['strFieldReference']};
                    }
                    echo "</td>";
                }
                ?>
            </tr>
            <?php    
            }
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
