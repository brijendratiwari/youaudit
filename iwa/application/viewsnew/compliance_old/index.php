<style>

    .ui-widget-content{
        background-image: none !important;

    }

</style>
<div class="heading">
    <h1>Compliance Checks</h1>
    <div class="buttons">
        <a href="<?php echo site_url('/compliance/addmultiple/'); ?>" class="button">Add Multiple</a>

        <a href="<?php echo site_url('/compliance/add/'); ?>" class="button">Add Compliance Check</a>
    </div>
</div>
<div class="box_content">
    <div class="tabs">
        <a href="#due" class="active">Overdue Compliance Checks</a>
        <a href="#upcoming">Upcoming Compliance Checks</a>
        <a href="#list">List Compliance Checks</a>
        <a href="#compliance">Multiple Compliance </a>
    </div>
    <div class="content_main">

        <!--
        Hide compliance_snapshot
        <div id="compliance_snapshot">
              <table class="list_table" style="width: 500px; margin: auto;">
                  <thead>
                      <th colspan="4"><h2>Compliance Snapshot</h2></th>
                  </thead>
                  <tbody>
                      <tr>
                          <td><strong>Overdue Mandatory Checks</strong></td>
                          <td><?php print count($dueTests['dueMandatory']); ?></td>
                          <td><strong>Mandatory Checks within 7 days</strong></td>
                          <td><?php print count($upcomingTests['dueMandatory']); ?></td>
                      </tr>
                      <tr>
                          <td><strong>Overdue Optional Checks</strong></td>
                          <td><?php print count($dueTests['dueOptional']); ?></td>
                          <td><strong>Optional checks within 7 days</strong></td>
                          <td><?php print count($dueTests['dueOptional']); ?></td>
                      </tr>
                  </tbody>
              </table>
      </div> -->
        <!--<div id="compliance_snapshot" style="border: solid; border-width: 1px; width: 585px; margin-bottom: 15px; height: 70px;padding: 8px; border-radius: 19px;">
            <h2 style="padding:0px; margin: 0px;">Compliance Snapshot</h2>
            <div style="float: left; margin-right: 85px;">
            <ul>
                <li>Overdue Mandatory Checks: <strong><?php print count($dueTests['dueMandatory']); ?></strong></li>
                <li>Overdue Optional Checks: <strong><?php print count($dueTests['dueOptional']); ?></strong></li>
            </ul>
            </div>
            <div style="float: left; margin-right: 85px;">
                <ul>
                    <li>Mandatory Checks due within 7 days: <strong><?php print count($upcomingTests['dueMandatory']); ?></strong></li>
                    <li>Optional Checks due within 7 days: <strong><?php print count($upcomingTests['dueOptional']); ?></strong></li>
                </ul>
            </div>

        </div>-->
        <div id="due" class="form_block">
            <h1>Items which have MANDATORY compliance checks overdue</h1>
            <p>Click view item, to see the tests which are overdue</p>
           
<div id="mendatary_compliance"></div>
            <table class="list_table">
                <thead>
                    <tr>
                        <th>Compliance Check</th>
                        <th>Category</th>
                        <th>Manufacturer and Model</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Site</th>
                        <th>Date Due</th>
                        <th class="right action">Actions</th>
                    </tr></thead>
                <tbody class="compliance_mandatory" id="compliance_mandatory">
                    <?php
                    foreach ($dueTests['dueMandatory'] as $key => $value) {
                        foreach ($value['tests'] as $test) {
                            ?>
                            <tr>
                                <td><?php print $test['test_type_name'] ?></td>
                                <td><?php print $value['item']->categoryname; ?> </td>
                                <td><?php print $value['item']->manufacturer . " " . $value['item']->model; ?></td>
                                <td><?php print $value['item']->owner; ?></td>
                                <td><?php print $value['item']->location; ?></td>
                                <td><?php print $value['item']->site; ?></td>
                                <?php if (is_numeric($test['due_ts'])) { ?>
                                    <td><?php (isset($test['due_ts'])) ? print date('d/m/Y', $test['due_ts']) : print "Never Tested"; ?></td>
                                <?php } else { ?>
                                    <td><?php (isset($test['due_ts'])) ? print $test['due_ts'] : print "Never Tested"; ?></td>
                                <?php } ?>
                                <td class="right action"><a href="<?php echo site_url('/items/view/' . $value['item']->itemid . '/'); ?>"><img src="/img/icons/16/view.png" title="View Item" alt="View Item" /></a></td>
                            </tr>


                        <?php
                        }
                    }
                    ?>
                </tbody>

            </table>
           
            <h1>Items which have OPTIONAL compliance checks overdue</h1>
            <p>Click view item, to see the tests which are overdue</p>
            <table class="list_table">
                <thead>
                    <tr>
                        <th>Compliance Check</th>
                        <th>Category</th>
                        <th>Manufacturer and Model</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Site</th>
                        <th>Date Due</th>
                        <th class="right action">Actions</th>
                    </tr>
                    <?php
                    foreach ($dueTests['dueOptional'] as $key => $value) {

                        foreach ($value['tests'] as $test) {
                            ?>
                            <tr>
                                <td><?php print $test['test_type_name'] ?></td>
                                <td><?php print $value['item']->categoryname; ?> </td>
                                <td><?php print $value['item']->manufacturer . " " . $value['item']->model; ?></td>
                                <td><?php print $value['item']->owner; ?></td>
                                <td><?php print $value['item']->location; ?></td>
                                <td><?php print $value['item']->site; ?></td>
                                <?php if (is_numeric($test['due_ts'])) { ?>
                                    <td><?php (isset($test['due_ts'])) ? print date('d/m/Y', $test['due_ts']) : print "Never Tested"; ?></td>
                                <?php } else { ?>
                                    <td><?php (isset($test['due_ts'])) ? print $test['due_ts'] : print "Never Tested"; ?></td>
        <?php } ?>
                                <td class="right action"><a href="<?php echo site_url('/items/view/' . $value['item']->itemid . '/'); ?>"><img src="/img/icons/16/view.png" title="View Item" alt="View Item" /></a></td>
                            </tr>


                        <?php
                        }
                    }
                    ?>
                </thead>

            </table>
        </div>

        <div id="upcoming" class="form_block">
            <h1>Items which have MANDATORY compliance checks due soon</h1>
            <p>Click view item, to see the tests which are due soon</p>
            <table class="list_table">
                <thead>
                    <tr>
                        <th>Compliance Check</th>
                        <th>Category</th>
                        <th>Manufacturer and Model</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Site</th>
                        <th>Date Due</th>
                        <th class="right action">Actions</th>
                    </tr>
                    <?php
                    if (count($upcomingTests['dueMandatory']) > 0) {
                        foreach ($upcomingTests['dueMandatory'] as $key => $value) {

                            foreach ($value['tests'] as $test) {
                                ?>
                                <tr>
                                    <td><?php print $test['test_type_name'] ?></td>
                                    <td><?php print $value['item']->categoryname; ?> </td>
                                    <td><?php print $value['item']->manufacturer . " " . $value['item']->model; ?></td>
                                    <td><?php print $value['item']->owner; ?></td>
                                    <td><?php print $value['item']->location; ?></td>
                                    <td><?php print $value['item']->site; ?></td>
                                    <td><?php (isset($test['due_ts'])) ? print date('d/m/Y', $test['due_ts']) : print "Never Tested"; ?></td>
                                    <td class="right action"><a href="<?php echo site_url('/items/view/' . $value['item']->itemid . '/'); ?>"><img src="/img/icons/16/view.png" title="View Item" alt="View Item" /></a></td>
                                </tr>


                                <?php
                            }
                        }
                    }
                    ?>
                </thead>

            </table>

            <h1>Items which have OPTIONAL compliance checks due soon</h1>
            <p>Click view item, to see the tests which are due</p>
            <table class="list_table">
                <thead>
                    <tr>
                        <th>Compliance Check</th>
                        <th>Category</th>
                        <th>Manufacturer and Model</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Site</th>
                        <th>Date Due</th>
                        <th class="right action">Actions</th>
                    </tr>
                    <?php
                    if (count($upcomingTests['dueOptional']) > 0) {
                        foreach ($upcomingTests['dueOptional'] as $key => $value) {

                            foreach ($value['tests'] as $test) {
                                ?>
                                <tr>
                                    <td><?php print $test['test_type_name'] ?></td>
                                    <td><?php print $value['item']->categoryname; ?> </td>
                                    <td><?php print $value['item']->manufacturer . " " . $value['item']->model; ?></td>
                                    <td><?php print $value['item']->owner; ?></td>
                                    <td><?php print $value['item']->location; ?></td>
                                    <td><?php print $value['item']->site; ?></td>
                                    <td><?php (isset($test['due_ts'])) ? print date('d/m/Y', $test['due_ts']) : print "Never Tested"; ?></td>
                                    <td class="right action"><a href="<?php echo site_url('/items/view/' . $value['item']->itemid . '/'); ?>"><img src="/img/icons/16/view.png" title="View Item" alt="View Item" /></a></td>
                                </tr>


                                <?php
                            }
                        }
                    }
                    ?>
                </thead>

            </table>
        </div>

        <div id="list" class="form_block">
            <ul>
                <li><h1>All Compliance Checks</h1></li>
                <li>Filter by Category: <form name="filter" method="post" action="#list">
                        <select name="filter_cat" onchange="this.form.submit();">
                            <option value="">-- Please Select --</option>
                            <?php foreach ($categories['results'] as $category) { ?>
                                <option value="<?php print $category->categoryid; ?>" <?php print ($category_filter == $category->categoryid) ? "selected=\"selected\"" : ""; ?>><?php print $category->categoryname; ?></option>
<?php } ?>
                        </select>
                    </form></li>
            </ul>
            <p></p>
            <table class="list_table">
                <thead>
                    <tr>
                        <th>Check Name</th>
                        <th>Category</th>
                        <th>Mandatory</th>
                        <th>Frequency</th>
                        <th class="right action">Actions</th>
                    </tr>
<?php foreach ($allTests as $test) { ?>
                        <tr>
                            <td><?php print $test['test_name']; ?></td>
                            <td><?php print $test['cat_name']; ?> </td>
                            <td><?php print ($test['test_mandatory'] == 1) ? "Yes" : "No"; ?></td>
                            <td><?php print $test['freq']; ?> </td>
                            <td class="right action">
                                <a href="<?php echo site_url() . '/compliance/view/' . $test['test_type_id']; ?>"><img src="/img/icons/16/view.png" title="View Vehicle" alt="View Vehicle"></a>
                                <a href="<?php echo site_url() . '/compliance/edit/' . $test['test_type_id']; ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                                <a href="<?php echo site_url() . '/compliance/remove/' . $test['test_type_id']; ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                            </td>
                        </tr>
<?php } ?>
                </thead>

            </table>
        </div>
        <div id="compliance" class="form_block">
            <ul>
                <li><h2>Multiple Compliance </h2></li>
            </ul> 
            <p></p>
            <script>
                $(function() {
                    $("#accordion").accordion({heightStyle: "content", icons: {"header": "ui-icon-plus", "activeHeader": "ui-icon-minus"}, collapsible: true, animate: 600,active : false});
                });
            </script>
                        <div id="pagination_ctrl"></div>
            <div id="accordion">
                <?php
                foreach ($allComplianceChild as $record) {
                    echo '<h3>' . $record['parent'] . '</h3>';
                    echo '<div>';
                    ?>
                    <table class="list_table">
                        <thead>
                            <tr>
                                <th>Check Name</th>
                                <th>Category</th>
                                <th>Mandatory</th>
                                <th>Frequency</th>
                                <th class="right action">Actions</th>
                            </tr>
                            <?php
                            foreach ($record as $childname) {

                                foreach ($childname as $data) {
                                    ?>
                                    <tr>
                                        <td><?php print $data['test_name']; ?></td>
                                        <td><?php print $data['cat_name']; ?> </td>
                                        <td><?php print ($data['test_mandatory'] == 1) ? "Yes" : "No"; ?></td>
                                        <td><?php print $data['freq']; ?> </td>
                                        <td class="right action">
                                            <a href="<?php echo site_url() . '/compliance/view/' . $data['test_type_id']; ?>"><img src="/img/icons/16/view.png" title="View Vehicle" alt="View Vehicle"></a>
                                            <a href="<?php echo site_url() . '/compliance/edit/' . $data['test_type_id']; ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                                            <a href="<?php echo site_url() . '/compliance/remove/' . $data['test_type_id']; ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </thead>

                    </table>
                    <?php
                    echo '</div>';
                }
                ?></div>


        </div>
        <script>
            var lis = $('#accordion').children('h3').length;
            $('#pagination_ctrl').smartpaginator({totalrecords: lis, recordsperpage: 3, initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black', datacontainer: 'accordion', dataelement: 'h3', length: 3});

          var row_no =  $('.compliance_mandatory').children('tr').length;
          $('#mendatary_compliance').smartpaginator({totalrecords: row_no , recordsperpage: 10, initval: 0, next: 'Next', prev: 'Prev', first: 'First', last: 'Last', theme: 'black',controlsalways:true, datacontainer: 'compliance_mandatory', dataelement: 'tr', length: 3});

        </script>



