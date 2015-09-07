<div class="box">
    <div class="heading">
        <h1>All Custom Fields</h1>
        <div class="buttons">
            <a href="<?php echo site_url('/customfields/add/'); ?>" class="button"><img src=<?php echo base_url("/img/ui-icons/add-similer.png");?> />Add A Custom Field</a>
        </div>

    </div>

    <div class="box_content">
        <div class="content_main">

            <?php if (count($arrCustomFields) > 0) { ?>
                <p>To use custom fields, you must assign custom fields to categories. To do this, click on <a href="<?=site_url('categories')?>">categories</a>, then click the 'edit category' icon. You can then select which custom fields you wish to activate on that category.</p>
                <table class="list_table">
                    <thead>
                    <tr>
                        <th class="left">Field Name</th>


                        <th class="right action">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach($arrCustomFields as $field) { ?>
                        <tr>
                            <td><?=$field->field_name?></td>
                            <td class="right">
                                <a href="<?=site_url('customfields/edit/' . $field->id)?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                                <a href="<?=site_url('customfields/delete/' . $field->id)?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            <?php } else { ?>

                <p>You don't have any categories added at present.</p>
            <?php } ?>
        </div>
    </div>
</div>