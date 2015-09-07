<script>
    $(document).ready(function() {
        $('#tabledivbody').sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            helper: fixHelper,
            update: function() {
                sendOrderToServer();
            },
            axis: "y",
            start: function(e, ui) {
                // modify ui.placeholder however you like
                ui.placeholder.html("Placeholder");
            }
        });

        // Return a helper with preserved width of cells
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };

        $("#showtooltip").tooltip('hide');

    });

    function sendOrderToServer() {

        var check_id = [];
        $("#tabledivbody tr").each(function() {
            check_id.push($(this).find('td:eq(0)').html());


        });
        var dataarr = JSON.stringify(check_id);

        $.ajax({
            type: "POST",
            dataType: "text",
            url: "<?php
echo base_url('fleet/savevehicleorder');
?>",
            data: {vehiclecheckid: dataarr},
            success: function(response) {

            }
        });
    }
</script>


<div class="box">
    <div class="heading">
        <h1>Fleet Checks Management</h1>
        <a href="" id="showtooltip" class="" data-toggle="tooltip" data-placement="right" title=" You can reorder checks by drag and drop"><img src="<?php echo base_url('img/info-128.png'); ?>" width="25px"></a>

        <div class="buttons">
            <a href="<?php echo site_url('fleet/newCheck'); ?>" class="button">Add vehicle check</a>

        </div>
    </div>

    <div class="box_content">
        <table class="list_table sorted_table">
            <thead>
                <tr class="header-row">
                    <th class="left" style="display: none">id</th>

                    <th class="left">Check</th>
                    <th class="left">Description</th>
                    <th class="right action">Actions</th>
                </tr>
            </thead>
            <tbody class="sortable_fleet" id="tabledivbody">

                <?php
                foreach ($arrChecks as $key => $check) {
                    ?>
                    <tr class="">
                        <td style="display: none"><?php print $check['id']; ?></td>
                        <td><?php print $check['check_name']; ?></td>
                        <td><?php print $check['check_long_description']; ?></td>
                        <td class="right action">
                            <a href="<?php echo site_url('fleet/editCheck/' . $check['id']); ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                            <a href="<?php echo site_url('fleet/deleteCheck/' . $check['id']); ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
        <style>

            #showtooltip{
                /*vertical-align: sub!important;*/
                left: 27px;
                top: 7px;
                position: relative;
            }
            #tabledivbody tr{
                cursor: ns-resize;
            }

        </style>
