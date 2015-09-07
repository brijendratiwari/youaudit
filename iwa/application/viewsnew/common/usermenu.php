<ul id="menu"> 
    <li <?php
    if ($arrPageParameters['strSection'] == 'Welcome') {
        echo " class=\"selected\"";
    }
    ?>><a href="<?php echo site_url(); ?>">Dashboard</a></li>
    <li <?php
    if ($arrPageParameters['strSection'] == 'Items') {
        echo " class=\"selected\"";
    }
    ?>><a href="<?php echo site_url('/items/filter/'); ?>">Asset Register</a></li>
        <?php if ($arrSessionData['objSystemUser']->levelid >= 2) {
            if ($this->session->userdata('objSystemUser')->fleet == 1) {
                ?>
            <li <?php
            if ($arrPageParameters['strSection'] == 'Fleet') {
                echo " class=\"selected\"";
            }
            ?>><a href="<?php echo site_url('/fleet/'); ?>">Fleet</a></li>       
        <?php }
    } ?>

    <?php if ($this->session->userdata('objSystemUser')->compliance == 1) { ?>
        <li <?php
        if ($arrPageParameters['strSection'] == 'Compliance') {
            echo " class=\"selected\"";
        }
        ?>><a href="<?php echo site_url('/compliance/'); ?>">Compliance</a></li>       
    <?php } ?>
    <li <?php
    if ($arrPageParameters['strSection'] == 'Faults') {
        echo " class=\"selected\"";
    }
    ?>><a href="<?php echo site_url('/faults/'); ?>">Faults</a></li>
    <li <?php
    if ($arrSessionData['objSystemUser']->levelid >= 2) {
        if ($arrPageParameters['strSection'] == 'Fault History') {
            echo " class=\"selected\"";
        }
        ?>><a href="<?php echo site_url('/faults/faulthistory'); ?>">Faults History</a></li> <?php } ?>
    <?php
    if ($arrSessionData['objSystemUser']->levelid >= 2) {
        ?>
        <li <?php
            if ($arrPageParameters['strSection'] == 'Reports') {
                echo " class=\"selected\"";
            }
            ?>><a href="<?php echo site_url('/reports/'); ?>">Reports</a></li>
        <?php } ?>
    <?php
    if ($arrSessionData['objSystemUser']->levelid > 2) {
        ?>
        <!--        <li <?php
        if ($arrPageParameters['strSection'] == 'Reports') {
            echo " class=\"selected\"";
        }
        ?>><a href="<?php echo site_url('/reports/'); ?>">Reports</a></li>-->
        <li <?php
            if ($arrPageParameters['strSection'] == 'Admin_Section') {
                echo " class=\"selected\"";
            }
            ?>><a href="<?php echo site_url('/admin_section/admin_user'); ?>">Admin</a></li>


        <li <?php
            if ($arrPageParameters['strSection'] == 'Account') {
                echo " class=\"selected\"";
            }
            ?>><a href="<?php echo site_url('/account/edit/'); ?>">Account Details</a></li>
        <li <?php
            if ($arrPageParameters['strSection'] == 'Archive') {
                echo " class=\"selected\"";
            }
            ?>><a href="<?php echo site_url('/archive/archived_assets/'); ?>">Archived Assets</a></li>
    <?php // }  ?>
<?php } ?>





    <?php
    /*
      if ($arrSessionData['objSystemUser']->levelid > 1)
      {
      ?>
      <li>About <?php echo $arrSessionData['objSystemUser']->accountname; ?>
      <ul class="menu first">
      <li><a href="<?php
      echo site_url('/locations/viewall/');
      ?>">Locations &gt;&gt;</a>
      <ul class="menu">
      <li><a href="<?php
      echo site_url('/locations/viewall/');
      ?>">View All</a></li>
      <li><a href="<?php
      echo site_url('/locations/addone/');
      ?>">Add a new location</a></li>
      </ul></li>
      <?php
      if ($arrSessionData['objSystemUser']->levelid > 2)
      {
      ?>
      <li><a href="<?php
      echo site_url('/users/viewall/');
      ?>">Users &gt;&gt;</a>
      <ul class="menu">
      <li><a href="<?php
      echo site_url('/users/viewall/');
      ?>">View All</a></li>
      <li><a href="<?php
      echo site_url('/users/add/');
      ?>">Add a new user</a></li>
      </ul></li>


      <li><a href="<?php
      echo site_url('/faculties/viewall/');
      ?>">Faculties &gt;&gt;</a>
      <ul class="menu">
      <li><a href="<?php
      echo site_url('/faculties/viewall/');
      ?>">View All</a></li>
      <li><a href="<?php
      echo site_url('/faculties/addone/');
      ?>">Add a new faculty</a></li>
      </ul></li>

      <li><a href="<?php
      echo site_url('/categories/viewall/');
      ?>">Categories &gt;&gt;</a>
      <ul class="menu">
      <li><a href="<?php
      echo site_url('/categories/viewall/');
      ?>">View All</a></li>
      <li><a href="<?php
      echo site_url('/categories/addone/');
      ?>">Add a new category</a></li>
      </ul></li>

      <li><a href="<?php
      echo site_url('/account/edit/');
      ?>">Edit account details</a></li>
      <?php
      }
      ?>
      </ul>
      </li>
      <?php
      }

     */
    ?>

</ul>