<div class="box">
    <div class="heading">
      	<h1>Delete vehicle check</h1>
        <div class="buttons">
            <a class="button" onclick="$('#delete_vehicle_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
            <p>Are you sure you wish to remove <?=$objCheck->check_name; ?>?</p>
            <p>Once removed, the check can only be recovered by a system administrator.</p>
            <?php echo form_open('admins/deletecheck/' . $objCheck->id, array('id'=>'delete_vehicle_form')); ?>
            <label for="delete">Are you sure? </label>
            <select id="delete" name="delete">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>

            </form>
    
        </div>
    </div>
</div>