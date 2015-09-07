    <h2>Delete a category</h2>
    <p>Use this form to delete a category for items.  The category will actually be marked as inactive, preventing users
    adding items to the category.</p>
    <p><strong>Note:</strong> <em>You will not be able to delete categories that have active items linked to it.</em></p>
    
    <p>You are deleting <strong><?php echo $strName; ?></strong></p>
    <?php echo form_open('categories/deleteone/'.$intCategoryId.'/'); ?>