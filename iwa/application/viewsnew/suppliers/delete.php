<div class="box">
    <div class="heading">
        <h1>Add a supplier</h1>
        <div class="buttons">

        </div>
    </div>

    <div class="box_content">
        <div class="content_main">
            <p>Use this form to delete a supplier. </p>
            <p><strong>Note:</strong> <em>You will not be able to delete suppliers that have active items linked to it.</em></p>

            <p>You are deleting the supplier <strong><?php echo $supplier->supplier_title; ?></strong></p>

            <a href="<?php echo site_url('suppliers/confirm_delete/' . $supplier->supplier_id); ?>"><button>I confirm I want to delete the supplier <?=$supplier->supplier_title?></button></a>