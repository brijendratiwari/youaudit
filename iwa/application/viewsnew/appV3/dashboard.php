    <img class="logo" src="img/ischool-logo.png" width="200" />
    
    <ul class="profile">
        <li class="picture" style="background:url('https://www.ischoolaudit.com/isa/appversionthree/viewUserHero/<?php echo $this->session->userdata('objAppUser')->photoid; ?>');"></li>
        <li class="clearfix"><h2>Hi, <?php echo $this->session->userdata('objAppUser')->firstname; ?></h2><p><?php echo $this->session->userdata('objAppUser')->accountname; ?><br/><?php echo $this->session->userdata('objAppUser')->levelname;?></p></li>
    </ul>
    
    <?php if ($intTotalItemsOnAccount > 0)
    {
        ?>
    
    <ul>
        <li class="header">Manage Items</li>
        <li class="arrow"><a href="#" onclick="isaItem_showLookUpForm();"><img src="img/icon-search.png" width="29" class="ico"> Look up item</a></li>
        <?php if ($this->session->userdata('objAppUser')->levelid > 1) {?>
        <li class="arrow"><a href="#" onclick="isaPat_showLookUpForm();"><img src="img/icon-pat.png" width="29" class="ico"> PAT Results</a></li>
        <li class="arrow" id="add-item-link"><a href="#" onclick="isaAddItem_showForm();"><img src="img/icon-add.png" width="29" class="ico"> Add An Item</a></li>
        <?php } ?>
    </ul>
    <?php if ($this->session->userdata('objAppUser')->levelid > 1) {?>
    <ul>
        <li class="header">Manage Locations</li>
        <li class="arrow"><a href="#" onclick="isaLocation_showLookUpForm();"><img src="img/icon-search.png" width="29" class="ico"> Look up location</a></li>
    </ul>
    <?php } ?>
    
    <?php
    } else {
    ?>
    <ul><li>Your account has no items added yet.  Add at least one item using the website before using the App.</li></ul>
    <?php
    }
    ?>
    <ul>
        <li class="header">Application Settings</li>
        <li class="arrow"><a href="#" onclick="isaLogin_doLogoutAndForgetMe();"><img src="img/icon-problem.png" width="29" class="ico">Log Out &amp; Forget Me</a></li>
    </ul>
    <p style="text-align:right;">Version 3.0</p>