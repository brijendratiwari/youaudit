<div class="box">
    <div class="heading">
        <h1>Fleet Checks Management</h1>
        <div class="buttons">
            <a href="<?php echo site_url('admins/newCheck'); ?>" class="button">Add vehicle check</a>
            <a href="<?php echo site_url('admins/deletedchecks'); ?>" class="button">View deleted checks</a>

        </div>
    </div>
    <div class="box_content">
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
                    <td><?php print $check['check_name']; ?></td>
                    <td><?php print $check['check_description']; ?></td>
                    <td class="right action">
                        <a href="<?php echo site_url('admins/editCheck/' . $check['id']); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                        <a href="<?php echo site_url('admins/deleteCheck/' . $check['id']); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>