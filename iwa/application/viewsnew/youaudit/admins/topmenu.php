
<ul id="menu">
                            <li <?php
            if ($arrPageParameters['strPage'] == "Youaudit Dashboard") {
                echo " class=\"selected\"";
            }
            ?>>
                                <a  href="<?php echo base_url('youaudit/dashboard'); ?>"><span class="glyphicon glyphicon-home"></span> Dashboard</a>
                            </li>
                        
                            <li  <?php
            if ($arrPageParameters['strPage'] == "Youaudit Master Account" || $arrPageParameters['strPage'] == "Master Customer" || $arrPageParameters['strPage'] == "Master Admin User" || $arrPageParameters['strPage'] == "Compliance" || $arrPageParameters['strPage'] == "Master Profile" || $arrPageParameters['strPage'] == "View All" || $arrPageParameters['strPage'] == "Master Archive Account" ) {
                echo " class=\"selected\"";
            }
            ?>>
                                
                                <a  href="<?php echo base_url('youaudit/masterAccount'); ?>"><span class="glyphicon glyphicon-folder-open"></span> Master Accounts</a>
                            </li>
                            
                            <li  <?php
            if ($arrPageParameters['strPage'] == "Youaudit Franchise Account" || $arrPageParameters['strPage'] == "Franchise Customer" || $arrPageParameters['strPage'] == "Franchise Admin User" || $arrPageParameters['strPage'] == "Franchise Compliance" || $arrPageParameters['strPage'] == "Franchise Profile" || $arrPageParameters['strPage'] == "Franchise Archive Account"  ) {
                echo " class=\"selected\"";
            }
            ?>>
                                <a  href="<?php echo base_url('youaudit/franchisesAccount'); ?>"><span class="glyphicon glyphicon-folder-open"></span> Franchises</a>
                            </li>
                            <li  <?php
            if ($arrPageParameters['strPage'] == "Youaudit Admins") {
                echo " class=\"selected\"";
            }
            ?>>
                                <a  href="<?php echo base_url('youaudit/adminlist'); ?>"><span aria-hidden="true" class="glyphicon glyphicon-user"></span> Admin Users</a>
                            </li>
                              <li  <?php
            if ($arrPageParameters['strPage'] == "Youaudit Packages") {
                echo " class=\"selected\"";
            }
            ?>>
                                <a  href="<?php echo base_url('youaudit/packagelist'); ?>"><span aria-hidden="true" class="glyphicon glyphicon-envelope"></span> Package Creation</a>
                            </li>
                             <li  <?php
            if ($arrPageParameters['strPage'] == "Youaudit Archive Account") {
                echo " class=\"selected\"";
            }
            ?>>
                                <a  href="<?php echo base_url('youaudit/archive'); ?>"><span aria-hidden="true" class="fa fa-mail-reply"></span> Archive Account</a>
                            </li>
    </ul>

        
 