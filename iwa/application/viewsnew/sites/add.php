    <div class="box">
    	<div class="heading">
            <h1>Add a site</h1>
            <div class="buttons">
                <a class="button" onclick="$('#add_site_form').submit();">Save</a>
            </div>
        </div>
    
        <div class="box_content">
            <div class="content_main">
                <p>Use this form to add a site for items</p>
                <?php echo form_open('sites/addone/', array('id'=>'add_site_form')); ?>