<div class="box">
    <div class="heading">
      	<h1>Add a location</h1>
        <div class="buttons">
            <a class="button" onclick="$('#add_location_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
    <p>Use this form to add a location for items</p>
    <?php echo form_open('locations/addone/', array('id' => 'add_location_form')); ?>