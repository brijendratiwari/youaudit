<h2>Current Users</h2>
   

        
        <?php 
            
                    
                   
                        if ($arrSessionData['objSystemUser']->levelid > 2)
                        {
                            ?><p class="right"><a href="<?php echo site_url('/users/add/'); ?>" class="button">Add A User</a></p><?php
                        }
                        
                    
?>

<table class="half-wide">
        <tr class="header-row">
            <td style="width:60px;">&nbsp;</td>
            <td>Name</td>
            <td>Level</td>
            <td>Actions</td>
        </tr>
        
        <?php
    if (count($arrUsers['results'])>0) 
    {
	foreach ($arrUsers['results'] as $arrUser)
	{
           ?>
        <tr <?php if ($arrUser->active != 1) 
            { 
                echo " class=\"inactive\" ";
                
                }
                ?> >
            
            <td>
                <?php if (($arrUser->photoid > 1) && ($arrUser->active == 1))
                {
                    echo "<img src=\"".site_url('/images/viewlist/'.$arrUser->photoid)."\" title=\"".$arrUser->phototitle."\" />";
                }
                ?>
            </td>
            <td><a href="<?php echo site_url('/users/view/'.$arrUser->userid); ?>"><?php
	    echo $arrUser->firstname." ".$arrUser->lastname;
        ?></a>
            </td>
            <td>
        <?php
            echo $arrUser->levelname;
        ?>
            </td>
            <td>
                <?php if ($arrUser->active) { ?>
                    <a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/edit/'.$arrUser->userid); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                    <a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/delete/'.$arrUser->userid); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                <?php }
                        else
                        {
                ?>
                
                <a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/reactivate/'.$arrUser->userid); ?>"><img src="/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
                <?php
                            
                        }      
                      
                
                
                ?>
            </td>
            
        </tr>
        <?php
         
	}
    }
	?>
    </table>