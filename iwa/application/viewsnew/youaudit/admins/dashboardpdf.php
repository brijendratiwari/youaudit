

<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                SYSTEM SUMMARY
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                        <table id="summary" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sys Admin Name</th>
                                    <th>Account Type</th>
                                    <th>Total Number</th>
                                    <th>Active</th>
                                    <th>Disabled</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($summary as $record) {
                                    ?>
                                    <tr> 
                                        <td><?php echo $record['sys_admin_name']; ?></td>
                                        <td><?php echo $record['type']; ?></td>
                                        <td><?php echo $record['total']; ?></td>
                                        <td><?php echo $record['enabled']; ?></td>
                                        <td><?php echo $record['disabled']; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table></div>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Recently Added Accounts
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sys Admin Name</th>
                                <th>Account Type</th>
                                <th>Customer Name</th>
                                <th>Package Type</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $recent_accounts = array_splice($recent_accounts, 0, 5);
                            foreach ($recent_accounts as $recent) {
                                ?>
                                <tr>
                                    <td><?php echo $recent['sys_admin_name']; ?></td>
                                    <td><?php echo $recent['type']; ?></td>
                                    <td><?php echo $recent['company']; ?></td>
                                    <td><?php echo $recent['package']; ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
</div>

<div class="row">

   



    <div class="col-lg-6">
        <div class="panel panel-warning">
            <div class="panel-heading">
                Current News : 
            </div>
            <div class="panel-body">
                <p><?php
                    if (isset($latest_news)) {
                        $this->load->helper('text');
                        echo word_wrap($latest_news['news_text'], 50);
                    }
                    ?></p>
            </div>
            <div class="panel-footer">
                <?php
                if (isset($latest_news)) {

                    $this->load->helper('date');

                    $datestring = "%d-%m-%Y %h:%i:%s %a";
                    echo mdate($datestring, $latest_news['create_date']);
                }
                ?>

            </div>
        </div>
    </div>

</div>

<!--   Model For Add Account For MaSTER -->
>