<div class="box">
    <div class="heading">
        <h1>All Locations</h1>

        <?php  
        if ($arrSessionData['objSystemUser']->levelid > 2) {
            ?>
            <div class="buttons">
                <a href="<?php echo site_url('/locations/addone/'); ?>" class="button">Add A Location</a>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="box_content">
        <div class="content_main">

            <?php
            if (count($arrLocationsData['results']) > 0) {
                ?>
                <table class="list_table">
                    <thead>
                        <tr>
                            <th class="left">Barcode</th>
                            <th class="left">Name</th>
                            <th class="left">Last Audit</th>
                            <th class="right action">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    foreach ($arrLocationsData['results'] as $arrLocation) {
        ?>
                            <tr <?php if ($arrLocation->locationactive == 0) {
                        echo "class=\"inactive\"";
                    } ?>>
                                <td><?php echo $arrLocation->locationbarcode; ?></td>
                                <td><?php echo $arrLocation->locationname; ?></td>
                                <td><?php
                    if ($arrLocation->location_audit) {
                        if (strtotime($arrLocation->location_audit['date']) < 0) {
                            echo '-';
                        } else {

                            echo date("d/m/Y H:i:s", strtotime($arrLocation->location_audit['date'])) . " by " . $arrLocation->location_audit['user'];
                        }
                    } else {
                        echo "&nbsp";
                    }
        ?></td>
                                <td class="right"><?php
                                    if ($arrLocation->locationactive != 0) {
                                        ?>
                                        <a href="<?php echo site_url('/locations/editone/' . $arrLocation->locationid . '/'); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit" /></a>
                                        <a href="<?php echo site_url('/locations/deleteone/' . $arrLocation->locationid . '/'); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete" /></a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo site_url('/locations/reactivateone/' . $arrLocation->locationid . '/'); ?>"><img src="/img/icons/16/refresh.png" title="Reactivate" alt="Reactivate" /></a>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                                    <?php
                                }
                                ?>
                    </tbody>
                </table>
                                <?php
                            } else {
                                ?>
                <p>You don't have any locations added at present.</p>
                        <?php
                    }
                    ?>
        </div>
    </div>
</div>