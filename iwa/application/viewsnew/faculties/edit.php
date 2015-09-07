<div class="box">
    <div class="heading">
      	<h1>Edit Faculty</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_faculty_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
        <p>Use this form to edit a faculty for items</p>
        <?php echo form_open('faculties/editone/'.$intFacultyId.'/', array('id'=>'edit_faculty_form')); ?>