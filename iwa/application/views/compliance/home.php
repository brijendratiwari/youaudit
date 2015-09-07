
    <div class="homepage-box">
            <h3>All About You</h3>
        <div class="container">

                <p>You've got <strong><?php echo $intUserItemCount; ?></strong> item<?php if ($intUserItemCount != 1) { ?>s<?php } ?> assigned to you.</p>
                    
                <?php if ($intUserItemCount > 0) { ?>
                <p class="right"><a href="<?php echo site_url('/items/filter/fr_userid_exact/'.$arrSessionData['objSystemUser']->userid); 
                                ?>" class="button" >View Your Items</a></p>
                <?php } ?>
            <div class="divider">
                <p>Do you need to change your information?</p>
                <p class="right"><a href="<?php echo site_url('/users/editme/'); 
                                ?>" class="button" >Edit Me</a></p>
            </div>

            <?php
            if ($arrSessionData['objSystemUser']->levelid == 3)
            {
            
                if (count($arrSuperAdminRequest['results']) == 0)
                {
            ?>
                 <div class="divider"> 
                     <p>Do you need to become the account SuperAdmin?</p>

                     <p class="right"><a href="<?php
                        echo site_url('/users/becomesuperadmin/');
                                            ?>" class="button">Change</a></p>
                   </div>
            <?php
                }
                else 
                { 
                    ?>
                    <div class="divider"> 
                        <p><strong>
                                <?php 
                                if ($arrSuperAdminRequest['results'][0]->userid 
                                        == $arrSessionData['objSystemUser']->userid)
                                {
                                    echo "You have";
                                }
                                else
                                {
                                    echo "Someone has";
                                }
                                
                                ?> already requested SuperAdmin status</strong></p>
                    </div>
                    <?php
                }

            }
            else
            {
                if ($arrSessionData['objSystemUser']->levelid == 4)
                {
                    if (count($arrSuperAdminRequest['results']) > 0)
                    {
                        ?>
                        <div class="divider"> 
                            <p><strong><?php echo $arrSuperAdminRequest['results'][0]->firstname." ".$arrSuperAdminRequest['results'][0]->lastname; ?> has requested to be SuperAdmin</strong></p>
                        </div>
                        <?php
                    }
                    else
                    {
                        ?>

                        <div class="divider"> 
                            <p><strong>You are the account SuperAdmin</strong></p>
                        </div>
                        <?php
                    }
                }
            }
            ?>

                
        </div>
        
    </div>
<?php

/*
if($_SERVER['REMOTE_ADDR'] != '217.155.36.203')
{
        if (count($arrNewestItems)>0)
        {
?>
<div class="homepage-box wide">
        <div class="header">
            <h3>10 Newest Items</h3>
        </div>
        <div class="container">
            <ul>
            <?php
            foreach ($arrNewestItems as $objItem)
            {
            ?>
            <li class="portrait">
                <div class="image_holder" style="background-image: url('<?php echo site_url('/images/viewlist/'.$objItem->itemphotoid); ?>');">
                    &nbsp;
                </div>
                
                <div class="description_holder"><p><?php echo $objItem->manufacturer." ".$objItem->model; ?></p></div>
                <a href="<?php echo site_url('/items/view/'.$objItem->id); 
                                ?>" class="spyglass" title="View Item"></a>
            </li>
            <?php
            }
            ?>
            </ul>
            <p class="right"><a href="<?php echo site_url('/items/filter/'); 
                               ?>" class="button" >View All Items</a></p>
            
        </div>
    </div>

<?php
        }
}
else
{
*/   
        if ($arrCommonItems && (count($arrCommonItems)>0))
        {  
?>
    <div class="homepage-box wide">

            <h3>10 Common Items</h3>

        <div class="container">
            <table>
                
            <?php
            foreach ($arrCommonItems as $objItem)
            {
            ?>
                <tr>
                    <td><a href="<?php echo site_url('/items/filter/fr_manufacturer_exact/'.$objItem->manufacturer); 
                               ?>"><?php echo $objItem->manufacturer; ?> <?php echo $objItem->model; ?></a></td>
                    <td><?php echo $objItem->count; ?></td>
                </tr>
            <?php
            }
            ?>
                
            </table>
            <p class="right"><a href="<?php echo site_url('/items/filter/'); 
                               ?>" class="button" >View All Items</a></p>
            
        </div>
    </div>
<?php 
        }
#}
       
?>   
    
<p style="clear: both;">&nbsp;</p>
        
   
