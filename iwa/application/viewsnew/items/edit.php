<div class="box">
    <div class="heading">
      	<h1>Edit Item</h1>
        <div class="buttons">
            <a class="button" onclick="$('#item_edit').submit();">Save</a>
        </div>
    </div>
    
    <div class="box_content">

        <div data-class="tabs">
<!--          <a href="#general_information">General Information</a>
          <a href="#item_dates">Item Dates</a>
          <a href="#item_ownership">Item Ownership</a>
          <a href="#item_photo">Item Photo</a>-->
        </div>

        <div class="content_main">
            <p>Use this form to edit a product</p>
            <?php echo form_open_multipart('items/edit/'.$intItemId.'/', array('id'=>'item_edit')); ?>