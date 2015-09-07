<div class="box">
    <div class="heading">
        <h1>All Suppliers</h1>
        <div class="buttons">
            <a class="button" href="<?php echo site_url ('/suppliers/add') ?>">Add supplier</a>
        </div>
    </div>

    <div class="box_content">
        <div class="content_main">
            <table class="list_table">
                <thead>
                <tr>
                    <th class="left">Name</th>
                    <th class="left">Telephone</th>
                    <th class="left">Website</th>
                    <th class="left">Email</th>
                    <th class="left">Contact Name</th>
                    <th class="left">Contact Job title</th>
                    <th class="right action">Actions</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($suppliers as $supplier) { ?>
                    <tr>
                        <td><?=$supplier['supplier_title']?></td>
                        <td><?=$supplier['supplier_telephone']?></td>
                        <td><a href="<?=$supplier['supplier_website']?>"><?=$supplier['supplier_website']?></a></td>
                        <td><a href="mailto:<?=$supplier['supplier_email']?>"><?=$supplier['supplier_email']?></a></td>
                        <td><?=$supplier['supplier_contact_name']?></td>
                        <td><?=$supplier['supplier_contact_job_title']?></td>
                        <td class="right">
                            <a href="<?php echo site_url('suppliers/view/' . $supplier['supplier_id']); ?>"><img src="/img/icons/16/view.png" title="View" alt="View"></a>
                            <a href="<?php echo site_url('suppliers/edit/' . $supplier['supplier_id']); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                            <a href="<?php echo site_url('suppliers/delete/' . $supplier['supplier_id']); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>