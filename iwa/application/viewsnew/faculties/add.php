    <div class="box">
    	<div class="heading">
            <h1>Add a faculty</h1>
            <div class="buttons">
                <a class="button" onclick="$('#add_faculty_form').submit();">Save</a>
            </div>
        </div>
    
        <div class="box_content">
            <div class="content_main">
                <p>Use this form to add a faculty for items</p>
                <?php echo form_open('faculties/addone/', array('id'=>'add_faculty_form')); ?>