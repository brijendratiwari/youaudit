<div class="box">
    <div class="heading">
      	<h1>Add a Category</h1>
        <div class="buttons">
            <a class="button" onclick="$('#add_category_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
        <p>Use this form to add a category for items</p>
        <?php echo form_open('categories/addone/', array('id'=>'add_category_form')); ?>    