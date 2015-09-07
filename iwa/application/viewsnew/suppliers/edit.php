<div class="box">
    <div class="heading">
        <h1>Edit a supplier</h1>
        <div class="buttons">
            <a class="button" onclick="$('#editsupplier').submit();">Save</a>
        </div>
    </div>

    <div class="box_content">
        <div class="content_main">
            <p>Use this form to edit a supplier</p>
            <!--<form id="editsupplier" name="editsupplier" action="/iwa/suppliers/edit/<?=$supplier->supplier_id?>" method="POST">-->
                 <?php echo form_open_multipart('suppliers/edit/'.$supplier->supplier_id.'/', array('id'=>'editsupplier')); ?>   
        
            <div class="form_block">
                    <div class="form_row">
                        <label for="name">Name*</label>
                        <input type="text" name="supplier_title" value="<?php echo $supplier->supplier_title; ?>" />
                        <?php echo form_error('supplier_title'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Website</label>
                        <input type="text" name="supplier_website" value="<?php echo $supplier->supplier_website; ?>" />
                        <?php echo form_error('supplier_website'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Telephone</label>
                        <input type="text" name="supplier_telephone" value="<?php echo $supplier->supplier_telephone; ?>" />
                        <?php echo form_error('supplier_telephone'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Email Address</label>
                        <input type="text" name="supplier_email" value="<?php echo $supplier->supplier_email; ?>" />
                        <?php echo form_error('supplier_email'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Address 1</label>
                        <input type="text" name="supplier_address1" value="<?php echo $supplier->supplier_address1; ?>" />
                        <?php echo form_error('supplier_address1'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Address 2</label>
                        <input type="text" name="supplier_address2" value="<?php echo $supplier->supplier_address2; ?>" />
                        <?php echo form_error('supplier_address2'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Address 3</label>
                        <input type="text" name="supplier_address3" value="<?php echo $supplier->supplier_address3; ?>" />
                        <?php echo form_error('supplier_address3'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Town</label>
                        <input type="text" name="supplier_town" value="<?php echo $supplier->supplier_town; ?>" />
                        <?php echo form_error('supplier_town'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">County</label>
                        <input type="text" name="supplier_county" value="<?php echo $supplier->supplier_county; ?>" />
                        <?php echo form_error('supplier_county'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Postcode</label>
                        <input type="text" name="supplier_postcode" value="<?php echo $supplier->supplier_postcode; ?>" />
                        <?php echo form_error('supplier_postcode'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Contact Name</label>
                        <input type="text" name="supplier_contact_name" value="<?php echo $supplier->supplier_contact_name; ?>" />
                        <?php echo form_error('supplier_contact_name'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Contact Email Address</label>
                        <input type="text" name="supplier_contact_email" value="<?php echo $supplier->supplier_contact_email; ?>" />
                        <?php echo form_error('supplier_contact_email'); ?>
                    </div>

                    <div class="form_row">
                        <label for="name">Contact Job Title</label>
                        <input type="text" name="supplier_contact_job_title" value="<?php echo $supplier->supplier_contact_job_title; ?>" />
                        <?php echo form_error('supplier_contact_job_title'); ?>
                    </div>

                </div>
            </form>