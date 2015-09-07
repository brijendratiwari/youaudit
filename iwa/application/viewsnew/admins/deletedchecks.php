<div class="box">
    <div class="heading">
        <h1>Deleted vehicle checks</h1>
        <div class="buttons">

        </div>
    </div>
    <div class="box_content">
        <p>Use this area to restore deleted checks back to accounts</p>
        <table class="list_table">
            <thead>
                <tr class="header-row">
                    <th class="left">Check</th>
                    <th class="left">Description</th>
                    <th class="right action">Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($arrVehicleChecks as $key => $check) {


                ?>
                <tr>
                    <td><?php print $check['check_name']; ?> <?php echo ($check['default'] == 1 ? '<strong>(Default check)</strong>' : ''); ?></td>
                    <td><?php print $check['check_description']; ?></td>
                    <td class="right action">
                        <a href="<?php echo site_url('admins/deletedchecks/' . $check['id']); ?>"><img src="/img/icons/16/refresh.png" title="Restore" alt="Restore"></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>