<div class="box">
    <div class="heading">
      	<h1>All Sites</h1>
<?php 
if ($arrSessionData['objSystemUser']->levelid > 2)
{
?>
        <div class="buttons">
        	<a href="<?php echo site_url('/sites/addone/'); ?>" class="button">Add A Site</a>
        </div>
<?php
}
?>
    </div>

    <div class="box_content">
        <div style="display: none;">
        <div id="mymap" style="width:600px; height:350px;"></div>
        </div>
        <div class="content_main">

    <?php 
    if (count($arrSitesData['results'])>0) 
    {
	?>
	    <table class="list_table">
                <thead>
                    <tr>
                        <th class="left">Name</th>
                        <th class="right action">Actions</th>
                    </tr>
                </thead>
		
	<?php
	foreach ($arrSitesData['results'] as $arrSite)
	{
	   
	?>
                <tbody>

                    <tr <?php if ($arrSite->siteactive == 0) { echo "class=\"inactive\"";} ?>>

                        <td><?php echo $arrSite->sitename; ?></td>
                        <td class="right"><?php
                            if ($arrSite->siteactive != 0)
                            {
                                ?>
                            <a lat="<?php print $arrSite->lat; ?>" lon="<?php print $arrSite->lon; ?>" postcode="<?php print $arrSite->postcode; ?>" href="#<? //print (empty($arrSite->lon) && empty($arrSite->lat) ? $arrSite->postcode : $arrSite->lat . "&" . $arrSite->lon); ?>" class="googleMap">View Location</a>
                                <a href="<?php echo site_url('/sites/editone/'.$arrSite->siteid.'/'); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                                <a href="<?php echo site_url('/sites/deleteone/'.$arrSite->siteid.'/'); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                                <?php
                            }
                            else
                            {
                            ?>
                                <a href="<?php echo site_url('/sites/reactivateone/'.$arrSite->siteid.'/'); ?>"><img src="/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
	<?php  
	}
	?>
	</table>
        
    <?php
    }
    else
    {
	?>
	<p>You don't have any locations added at present.</p>
	<?php
    }
    ?>
        </div>
    </div>