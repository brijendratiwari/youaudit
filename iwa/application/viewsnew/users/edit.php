<div class="box">
    <div class="heading">
      	<h1>Edit User</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_user_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        
        <div class="tabs">
          <a href="#general_information">General Information</a>
          <a href="#user_photos">User Photo</a>
          <?php
            if (!$booSuppressPasswordChange)
            {
            ?>
                <a href="#user_password">User Password</a>
          <?php
            } ?>
          
        </div>
        <div class="content_main">
            <p>Use this form to edit a user on the account</p>
            <?php echo form_open_multipart('users/edit/'.$intUserId.'/', array('id' => 'edit_user_form')); ?>    
<div id="general_information" class="form_block">
            
        <?php
        if ($intLevelId != 4)
        {
        ?>
        
            <div class="form_row">
                <label for="level_id">Level*</label>	
                <select name="level_id">
                    <option value="0">Select</option>
                    <?php
                        foreach ($arrLevels['results'] as $arrLevel)
                        {
                            if ($arrLevel->levelid != 4)
                            {    
                            echo "<option ";
                            echo 'value="'.$arrLevel->levelid.'" ';
                            if ($intLevelId == $arrLevel->levelid)
                            {
                                echo 'selected="selected" ';
                            }
                            echo '>'.$arrLevel->levelname."</option>\r\n";
                            }
                        }
                    ?>
                </select>
                <?php echo form_error('level_id'); ?>
            </div>
        
       <?php
        }
        ?>
            
            