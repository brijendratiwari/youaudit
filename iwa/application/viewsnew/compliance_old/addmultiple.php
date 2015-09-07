<script>
    $(document).ready(function() {
        $("#parent_block").hide();
        $('.check_name').draggable({
            appendTo: 'body',
            helper: "clone",
            start: function(e, u) {
            },
            stop: function() {

            }}).click(function() {
//            var b = parseInt($(this.width));

        })


        $('.bs-example').droppable({
            accept: ".check_name",
            drop: function(e, u) {
                var a = u.helper.clone();
                var obj = a.text();
                var arr = obj.split('_');
                console.log(a)
                $('#tokenfield-typeahead').tokenfield('createToken', {value: arr[0], label: arr[1]});

            }})

        $("#add_parent_block").click(function() {
            $("#parent_block").toggle('slow');
        });
    });
</script>
<div class="heading">
    <h2>Add Multiple Compliance</h2>
    <div class="buttons">
        <a class="button" onclick="$('#add_multiple').submit();">Save</a>
    </div>
</div>
<div class="box_content">
    <div class="tabs">
        <a href="#list">Add Multiple Compliance </a>
    </div>
    <div class="content_main">
        <div id="list" class="form_block">
            <?php echo form_open('compliance/addmultiple/', array('id' => 'add_multiple')); ?> 
            <div class="bs-example" id="parent_block" >
                <div class="form-group">
                    <input type="text" class="form" id="parent" name="parent_name" value="" placeholder="Parent Name" />
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control token-example-field" name="child_id" id="tokenfield-typeahead" value="" style="width:400px;" placeholder="Drop Child Here" />
                </div><br>
            </div>
            </form>
            <ul>
                <li><a class="button" id="add_parent_block">ADD</a></li>
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
                            <td class="check_name" ><?php echo '<p style="display:none">' . $test['test_type_id'] . '_</p>';
                    print $test['test_name'];
                        ?></td>
                            <td><?php print $test['cat_name']; ?> </td>
                            <td><?php print ($test['test_mandatory'] == 1) ? "Yes" : "No"; ?></td>
                            <td><?php print $test['freq']; ?> </td>
                            <td class="right action">
                                <a href="https://www.iworkaudit.com/iwa/compliance/view/<?php print $test['test_type_id']; ?>"><img src="/img/icons/16/view.png" title="View Vehicle" alt="View Vehicle"></a>
                                <a href="https://www.iworkaudit.com/iwa/compliance/edit/<?php print $test['test_type_id']; ?>"><img src="/img/icons/16/modify.png" title="Edit" alt="Edit"></a>
                                <a href="https://www.iworkaudit.com/iwa/compliance/remove/<?php print $test['test_type_id']; ?>"><img src="/img/icons/16/erase.png" title="Delete" alt="Delete"></a>
                            </td>
                        </tr>
<?php } ?>
                </thead>

            </table>
            <!--  demo Table  -->


        </div>
