<div class="box">
    <div class="heading">
      	<h1>Edit location</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_location_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
    <p>Use this form to edit a location for items</p>
    <?php echo form_open('locations/editone/'.$intLocationId.'/', array('id' => 'edit_location_form')); ?>    
