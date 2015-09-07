<div class="box">
    <div class="heading">
      	<h1>All Users</h1>
        
<?php 
        if ($arrSessionData['objSystemUser']->levelid > 2)
        {
?>
            <div class="buttons">
               <a href="<?php echo site_url('/users/exportExcelData/'); ?>" class="button">Import From Excel</a>
        
                <a href="<?php echo site_url('/users/add/'); ?>" class="button">Add A User</a>
            </div>           
<?php                           
        }                
?>
    </div>

    <div class="box_content">


        <table class="list_table">
            <thead>
                <tr class="header-row">
                    <th>&nbsp;</td>
                    <th class="left">Name</th>
                    <th class="left">Level</th>
                    <th class="right action">Actions</th>
                </tr>
            </thead>
            <tbody>

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
                    <?php if ($arrUser->photoid > 1)
                    {
                        
                        echo "<img src=\"".site_url('/images/viewlist/'.$arrUser->photoid)."\" title=\"".$arrUser->phototitle."\" />";
                    } else {
                        echo "<img src=\"".site_url('/images/viewdefaultimage/img/icons/categories/blank_avatar_male.jpg/50/50/')."\" title=\"User\" />";
                    }
                    ?>
                </td>
                <td><a href="<?php echo site_url('/users/edit/'.$arrUser->userid); ?>"><?php
                echo $arrUser->firstname." ".$arrUser->lastname;
            ?></a>
                </td>
                <td>
            <?php
                echo $arrUser->levelname;
            ?>
                </td>
                <td class="right action">
                    <?php if ($arrUser->active) { ?>
                        <a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/edit/'.$arrUser->userid); ?>"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                        <a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/delete/'.$arrUser->userid); ?>"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                    <?php 
                        if ($arrSessionData['objSystemUser']->levelid > 2)
                        {
                            ?>
                            <a href="<?php echo site_url('/reports/userActivity/'.$arrUser->userid); ?>"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>/iwa/img/icons/16/report.png" title="Activity Report" alt="Activity Report" /></a>
                            <?php
                        }
                    
                    }
                            else
                            {
                    ?>

                    <a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/reactivate/'.$arrUser->userid); ?>"><img src="/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
                    <?php
if ($arrSessionData['objSystemUser']->levelid > 2)
                        {
                            ?>
                            <a href="<?php echo site_url('/reports/userActivity/'.$arrUser->userid); ?>"><img src="/img/icons/16/report.png" title="Activity Report" alt="Activity Report" /></a>
                            <?php
                        }
                            }      



                    ?>
                </td>

            </tr>
            <?php

            }
        }
            ?>
            </tbody>
        </table>
    </div>                        
</div>
