    <h2>Item Details</h2>
    
    <p class="right">
        
        <?php 
            if ($objItem->siteid == 0)
                    {
                    
                   
                        if ($arrSessionData['objSystemUser']->userid == $objItem->userid)
                        {
                            ?><a href="<?php echo site_url('/items/changelinks/'.$objItem->itemid.'/'); ?>" class="button">Change Owner or Location</a><?php
                        }
                        else
                        {
                            ?><a href="<?php echo site_url('/items/itsmine/'.$objItem->itemid.'/'); ?>" class="button">I have this now</a> <?php
                            ?><a href="<?php echo site_url('/items/changelinks/'.$objItem->itemid.'/'); ?>" class="button">Change Owner or Location</a><?php
                        }
                    }
		?>
        <a href="<?php echo site_url('/items/raiseticket/'.$objItem->itemid.'/'); ?>" class="button">Raise a Support Ticket</a>
        <?php 
            
                    
                   
                        if ($arrSessionData['objSystemUser']->levelid > 1)
                        {
                            ?><a href="<?php echo site_url('/items/edit/'.$objItem->itemid); ?>" class="button">Edit item</a><?php
                        }
                        
                    
?>
    </p>
    
    <table class="half-wide">
	<tr class="header-row">
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
            
	</tr>
        <tr>
	    <td><strong>Manufacturer &amp; Model</strong></td>
	    <td><strong><?php echo $objItem->manufacturer; ?> <em><?php echo $objItem->model; ?></em></strong></td>
            
	</tr>
        <tr>
            <td><strong>Item Main Image</strong></td>
            
            <td><?php if ($objItem->itemphotoid > 1)
                {
                    
                    echo "<img src=\"".site_url('/images/viewhero/'.$objItem->itemphotoid)."\" title=\"".$objItem->itemphototitle."\" />";
                }
                ?></td>
            
        </tr>
	<tr>
	    <td><strong>Category</strong></td>
	    <td><?php echo $objItem->categoryname; ?></td>
            
	</tr>
        <tr>
	    <td><strong>Status</strong></td>
	    <td><?php echo $objItem->itemstatusname; ?></td>
            
	</tr>
	<tr>
	    <td><strong>Barcode<strong></td>
	    <td><pre><?php echo $objItem->barcode; ?></pre></td>
            
	</tr>
	<tr>
	    <td><strong>Serial Number</strong></td>
	    <td><pre><?php echo $objItem->serial_number; ?></pre></td>
            
	</tr>
        <?php
        if ($arrSessionData['objSystemUser']->levelid > 1)
			{
			?>
        <tr>
	    <td><strong>Value</strong></td>
	    <td><?php
            if ($objItem->value != 0)
            {
            ?>    
            &pound;<?php echo $objItem->value; ?>
            
            <?php
            }
            else 
            {
            ?>
            Not Set
            <?php
            }
            ?>
            </td>
            
	</tr>
        <tr>
	    <td><strong>Purchased</strong></td>
            <td><?php
            if ($objItem->purchase_date != "")
            {
                if(strtotime($objItem->purchase_date) < 0){
                    echo '-';
                }else{
                echo date("d/m/Y", strtotime($objItem->purchase_date));
            }}
            else 
            {
                echo "Not Available";
            }
            ?></td>
            
        </tr>
        <tr>
	    <td><strong>Warranty Expires</strong></td>
            <td><?php
            if ($objItem->warranty_date != "")
            {
                echo date("d/m/Y", strtotime($objItem->warranty_date));
            }
            else 
            {
                echo "Not Available";
            }
            ?></td>
            
        </tr>
        <tr>
	    <td><strong>Replacement Date</strong></td>
            <td><?php
            if ($objItem->replace_date != "")
            {
                echo date("d/m/Y", strtotime($objItem->replace_date));
            }
            else 
            {
                echo "Not Available";
            }
            ?></td>
            
        </tr>
        
        <tr>
	    <td><strong>PAT Date</strong></td>
            <td><?php
            if ($objItem->pattest_date != "")
            {
                echo date("d/m/Y", strtotime($objItem->pattest_date));
            }
            else 
            {
                echo "Not Available";
            }
            ?></td>
            
        </tr>
        
        <tr>
	    <td><strong>PAT Status</strong></td>
            <td><?php
            if ($objItem->pattest_status != "")
            {
                if ($objItem->pattest_status > 0)
                {
                    echo "<span style=\"background-color:green;padding:4px;color:white;font-weight:bold;\">PASS</span>";
                }
                else
                {
                    echo "<span style=\"background-color:red;padding:4px;color:white;font-weight:bold;\">FAIL</span>";
                }
            }
            else 
            {
                echo "N/A";
            }
            ?></td>
            
        </tr>
        <?php
                        }
       ?>
        
        <tr>
            <td><strong>Notes</strong></td>
            <td>
            <?php
            if ($objItem->notes == "")
            {
            ?>
            -
            <?php
            }
            else 
            {
            ?>
                <ul>
                <?php
                echo $strItemNotesList;
                ?>
                </ul>
            <?php
            }
            ?>
            </td>
            
        </tr>
        
        
	<tr>
	    <td><strong>Present Owner</strong></td>
	    <td>
		<?php
		if ($arrSessionData['objSystemUser']->userid == $objItem->userid)
		{
		    ?>Me (<?php echo $objItem->userfirstname." ".$objItem->userlastname; ?>)<?php
		}
		else
		{
                    if ($objItem->userid != 0)
                    {
                        echo $objItem->userfirstname." ".$objItem->userlastname;
                    }
                    else
                    {
                        echo "-";
                    }
		}
		
		?>
	    </td>
           
	</tr>
	<tr>
	    <td><strong>Present Location</strong></td>
	    <td><?php
		    if ($objItem->locationid != 0)
                    {
                        echo $objItem->locationname;
                    }
                    else
                    {
                        echo "-";
                    }
		
		?></td>
             
        <tr>
            <td><strong>Faculty</strong></td>
            <td><?php
            if ($objItem->siteid != 0)
                    {
                        echo $objItem->sitename;
                    }
                    else
                    {
                        echo "-";
                    }
            ?></td>
            
        </tr>
        
	
    </table>
    
    <?php
        if ($arrSessionData['objSystemUser']->levelid >1)
        {
    ?>
    <h3>Item History</h3>
    <table class="half-wide">
        <tr class="header-row">
	    <td>Date</td>
	    <td>User</td>
            <td>Location</td>
	</tr>
        <?php
        if ($objItem->userid == 0)
        {
            ?>
        <tr>
            <td><?php echo $objItem->owner_since; ?></td>
            <td><em>Faculty Item</em></td>
            <td>&nbsp;</td>
        </tr>
            <?
        }
                    
        
        foreach ($arrItemHistory as $strDate=>$arrRecord)
        {
        ?>
        <tr>
            <td><?php echo $strDate; ?></td>
            <td><?php if (isset($arrRecord['user']))
                        {
                            echo $arrRecord['user']->userfirstname." ".$arrRecord['user']->userlastname; 
                        }
                            ?></td>   
            <td><?php if (isset($arrRecord['location']))
                        {
                            echo $arrRecord['location']->locationname; 
                        }
                            ?></td>
        </tr>
        <?php
        }
        ?>
        
        
        
    </table>
    
    <?php
        }
    ?>