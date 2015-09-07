<div class="box">
    <div class="heading">
      	<h1>Delete a Location</h1>
        <div class="buttons">
            <a class="button" onclick="$('#delete_location_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">

    <p>Use this form to delete a location for items.  The location will actually be marked as inactive, preventing users
    adding items to the location.</p>
    <p><strong>Note:</strong> <em>You will not be able to delete locations that have active items linked to it.</em></p>
    
    <p>You are deleting <strong><?php echo $strName; ?></strong></p>
    <?php echo form_open('locations/deleteone/'.$intLocationId.'/', array('id' => 'delete_location_form')); ?>