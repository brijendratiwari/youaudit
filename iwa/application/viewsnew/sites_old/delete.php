<div class="box">
    <div class="heading">
      	<h1>Delete a Faculty</h1>
        <div class="buttons">
            <a class="button" onclick="$('#delete_faculty_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">



    <p>Use this form to delete a faculty for items.  The faculty will actually be marked as inactive, preventing users
    adding items to the faculty.</p>
    <p><strong>Note:</strong> <em>You will not be able to delete faculties that have active items linked to it.</em></p>
    
    <p>You are deleting <strong><?php echo $strName; ?></strong></p>
    <?php echo form_open('faculties/deleteone/'.$intFacultyId.'/', array('id'=>'delete_faculty_form')); ?>