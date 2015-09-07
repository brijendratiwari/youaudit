<style>
   div.tabs ul.nav.nav-pills li a:hover{
        background: none !important;
    }
    </style>
<div class="box">
    <div class="heading">
      	<h1>Edit My Profile</h1>
        <div class="buttons">
         
            <button style="display: block;" id="save_item" class="button update icon-with-text round" onclick="$('#edit_myprofile_form').submit();"><i class="fa fa-fw">&#xf0ab;</i>Save</button>
<!--            <a class="button" onclick="$('#edit_myprofile_form').submit();">Save</a>-->
        </div>
    </div>
    <div class="box_content">
        <div class="tabs">
            <ul class="nav nav-pills">
                <li>  <a href="#general_information">General Information</a></li>
                <li><a href="#user_photos">User Photo</a></li>
          <?php
            if (!$booSuppressPasswordChange)
            {
            ?>
                <li><a href="#user_password">User Password</a></li>
          <?php
            } ?>
                </ul>
        </div>
        <div class="content_main">
<div id="general_information" class="form_block">
    <?php echo form_open_multipart('users/editme/', array('id'=>'edit_myprofile_form')); ?>