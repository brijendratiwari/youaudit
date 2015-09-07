<div class="box">
    <div class="heading">
      	<h1>Reactivate a Faculty</h1>
        <div class="buttons">
            <a class="button" onclick="$('#reactivate_faculty_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">

<h2>Reactivate a faculty</h2>
    <p>Use this form to reactivate a faculty for items.  The faculty will then be available for users to link items to.</p>
    <p>You are reactivating <strong><?php echo $strName; ?></strong></p>
    <?php echo form_open('faculties/reactivateone/'.$intFacultyId.'/', array('id'=>'reactivate_faculty_form')); ?>