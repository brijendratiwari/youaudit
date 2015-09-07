<div class="box">
    <div class="heading">
      	<h1>Edit a Category</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_category_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
        <p>Use this form to edit a category for items</p>
        <?php echo form_open('categories/editone/'.$intCategoryId.'/', array('id'=>'edit_category_form')); ?>    
