<div class="box">
    <div class="heading">
      	<h1>Edit Site</h1>
        <div class="buttons">
            <a class="button" onclick="$('#edit_site_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">
        <p>Use this form to edit a site for items</p>
        <?php echo form_open('sites/editone/'.$intSiteId.'/', array('id'=>'edit_site_form')); ?>