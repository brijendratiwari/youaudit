 <div class="box">
    <div class="heading"><h1>Reactivate <?php echo $arrUser['result'][0]->firstname." ".$arrUser['result'][0]->lastname; ?></h1>
        <div class="buttons">
            <a class="button" onclick="$('#reactivate_user_form').submit();">Save</a>
        </div>
    </div>
    
     <div class="content_main">
         <p>Use this form to reactivate a user.  The user will be able to login.  The user will also be able to be inherited.</p>
    
         <p>You are reactivating <strong><?php echo $arrUser['result'][0]->firstname." ".$arrUser['result'][0]->lastname; ?></strong></p>
        <?php echo form_open('admins/reactivateuser/'.$arrUser['result'][0]->userid.'/', array('id'=>'reactivate_user_form')); ?>

        <div class="form_row">
            <label for="safety">Are you sure?</label>
            <select name="safety">
                <option value="0" selected="selected">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

    </div>
 </div>