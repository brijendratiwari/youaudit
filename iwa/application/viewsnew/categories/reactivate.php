<div class="box">
    <div class="heading">
      	<h1>Reactivate a Category</h1>
        <div class="buttons">
            <a class="button" onclick="$('#reactivate_category_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">

    <p>Use this form to reactivate a category for items.  The category will then be available for users to link items to.</p>
    <p>You are reactivating <strong><?php echo $strName; ?></strong></p>
    <?php echo form_open('categories/reactivateone/'.$intCategoryId.'/', array('id'=>'reactivate_category_form')); ?>