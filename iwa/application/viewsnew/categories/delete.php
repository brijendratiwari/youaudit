<div class="box">
    <div class="heading">
      	<h1>Delete a Category</h1>
        <div class="buttons">
            <a class="button" onclick="$('#delete_category_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">

    <p>Use this form to delete a category for items.  The category will actually be marked as inactive, preventing users
    adding items to the category.</p>
    <p><strong>Note:</strong> <em>You will not be able to delete categories that have active items linked to it.</em></p>
    
    <p>You are deleting <strong><?php echo $strName; ?></strong></p>
    <?php echo form_open('categories/deleteone/'.$intCategoryId.'/', array('id'=>'delete_category_form')); ?>