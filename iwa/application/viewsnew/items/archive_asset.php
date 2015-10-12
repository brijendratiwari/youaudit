<style>
    /*    #itemtable tfoot, #itemtable thead {
            display: none;
        }*/
    #itemtable thead tr th, #itemtable tbody tr td, .list_table thead tr th, .list_table tfoot tr th {
        text-align: center !important;
        min-width: 220px !important; 
    }
    .list_table thead tr th { 
        color:#ffffff !important
    }
    #columnselect .modal-body
    {
        height: 320px;
        overflow-y: scroll;
    }
    #itemtable select{
        color: black;
    }
    .modal-body .row
    {
        margin: 10px 0px;
    }
    .error
    {
        color: red;
    }
    .dataTable tfoot select
    {
        width: auto!important;
    }
    .bootbox .modal-dialog{
        width: 400px;
    }
    .bootbox .modal-body{
        min-height: 75px;
        overflow: auto !important;
    }
    .fileupload { padding:0; margin-bottom:10px}
    #itemtable td a 
    {
        text-decoration: none;
        font-weight: bold;
        font-size: 12px!important;
    }
    #itemtable > thead {
    display: none !important;
}
    #itemtable > tfoot {
    display: none !important;
}
</style>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<script>
    $(document).ready(function() {

        var url = $('#item-url').val();
        var base_url_str = $("#base_url").val();
        var num_of_th = $('#num_of_th').val();
        var numofth = num_of_th.split(',');
          var aryJSONColTable = [];
        for (var k = 0; k < numofth.length; k++) {

            if (k == 0) {
                aryJSONColTable.push({
                    "bSortable": true,
                    "aTargets": [k]
                });
            }
            else if (k > 19)
            {
                aryJSONColTable.push({
                    "sClass": "eamil_conform aligncenter",
//                    "bSortable": false,
                    "aTargets": [k]
                });
            }
            else
            {
                aryJSONColTable.push({
                    "sClass": "eamil_conform aligncenter",
//                    "bSortable": false,
                    "aTargets": [k],
                });
            }
        }

        var item_table = $("#itemtable").DataTable({
              "initComplete" : function () {
        $('.dataTables_scrollBody thead tr').addClass('hidden');
    },
            "oLanguage": {
                "sProcessing": "<div align='center'><img src='<?php echo base_url('./img/ajax-loader.gif'); ?>'</div>"},
            "ordering": true,
            "bProcessing": true,
            "bServerSide": true,
            "stateSave": true,
            "bSortCellsTop": true,
            "sAjaxSource": url,
            "bDeferRender": true,
            "aLengthMenu": [[20, 50, 100, 250, 500, -1], [20, 50, 100, 250, 500, "All"]],
            "iDisplayLength": 20,
            "sScrollX": "100%",
            "sScrollY": "570px",
            "bScrollCollapse": false,
            "bDestroy": true, //!!!--- for remove data table warning.
            "fnDrawCallback": function() {
                var api = this.api();
                $(api.column(6).footer()).html(
                        api.column(6, {page: 'current'}).data().sum()
                        );
                $(api.column(19).footer()).html(
                        api.column(19, {page: 'current'}).data().sum()
                        );
                $(api.column(20).footer()).html(
                        api.column(20, {page: 'current'}).data().sum()
                        );

            },
            "aoColumnDefs":
                    aryJSONColTable
        });
        for (var k = 0; k < numofth.length; k++) {
            var column = item_table.column(k);
            column.visible(true);
        }

        $("body").on("keyup", "#filter_barcode", function() {
            item_table.column(1)
                    .search(this.value)
                    .draw();
        });
        for (var m = 22; m < numofth.length; m++) {
            $("body").on("keyup", "#" + m, function() {
                var ind = this.id;
                var val = this.value;
                item_table.column(ind)
                        .search(val)
                        .draw();
            });
        }
        $("body").on("change", "#filtercategoryname", function() {

            item_table.column(3)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filteritem_manu", function() {
            item_table.column(4)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filtermanufacturer", function() {
            item_table.column(5)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filtersitename", function() {
            item_table.column(8)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filterlocationid", function() {
            item_table.column(9)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filteruserid", function() {
            item_table.column(10)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filtersupplier", function() {
            item_table.column(11)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filteritemstatusid", function() {
            item_table.column(12)
                    .search(this.value)
                    .draw();
        });
        $("body").on("change", "#filterconstatus", function() {
            item_table.column(13)
                    .search(this.value)
                    .draw();
        });
        // Show Hide Column

        var total = $('#total').val();
        if (total != '') {
            var sub = total.split(',');
            if (sub.length > 0) {
                var hiddencolumns = new Array(sub);
            }
        }
        else
        {
            var hiddencolumns = new Array();
        }

        if (hiddencolumns.length > 0) {
            for (var j = 0; j < hiddencolumns.length; j++)
            {
                var column = item_table.column(hiddencolumns[j]);
                column.visible(false);
            }
        }

        $('body').on('click', '#clearfilter', function(e) {
            e.preventDefault();
            bootbox.confirm("Do you want to reset this table?", function(result) {
                if (result) {
                    localStorage.removeItem('DataTables_/youaudit/iwa/archive/archived_assets');
                    item_table.destroy();
                    localStorage.clear();
                    window.location = window.location;
                    $('#itemtable select option[value=""]').prop('selected', true);
                    $('#filter_barcode').val('');
                } else {
                    // Do nothing!
                }
            });
        });
        $('#activate-column-selector').on('click', function()
        {
            localStorage.removeItem('DataTables_/youaudit/iwa/items/filter');
            item_table.destroy();
            localStorage.clear();
            window.location = window.location;
        });

        function unique(array) {
            return array.filter(function(el, index, arr) {
                return index == arr.indexOf(el);
            });
        }

        //-----Exporting pdf--------

        // column array for csv
        var arr = [];
        var showcol = $('#assetfilter').val();
        if (showcol != '') {
            var sub = showcol.split(',');
            if (sub.length > 0) {
                for (var i = 0; i < sub.length; i++) {
                    if (sub[i] != 2) {
                        arr.push(sub[i]);
                    }
                }

            }
        }
        console.log(arr);
        // column array for pdf
        var pdfarr = [];
        var showcol = $('#assetfilter').val();
        if (showcol != '') {
            var sub = showcol.split(',');
            if (sub.length > 0) {
                for (var i = 0; i < sub.length; i++) {
                    pdfarr.push(sub[i]);
                }

            }
        }
        console.log(pdfarr);
        $('#exportPdfButton').on('click', function(e) {
            var data1 = $("#itemtable").dataTable()._('tr', {"filter": "applied"});
            var data = data1.map(function(row) {
                var rowArr = [];
                $.each(pdfarr, function(i, v) {
                    rowArr.push(row[v]);
                });
                return '<td>' + rowArr.join('</td><td>') + '</td>';
            })
                    .join('</tr><tr>');
            data = '<tbody><tr>' + data + '</tr></tbody>';
            var cloneHead = [];
            var cloneFoot = [];
            var head = $('#itemtable thead').clone();
            var foot = $('#itemtable tfoot').clone();
            head.find('th[data-export="true"]').each(function(i) {
                console.log($(this).html());
                cloneHead.push($(this).html());
            });
            foot.find('th[data-export="true"]').each(function(j) {
                console.log($(this).html());
                cloneFoot.push($(this).html());
            });
            cloneHead = '<thead><tr><th>' + cloneHead.join('</th><th>') + '</th></tr></thead>';
            cloneFoot = '<tfoot><tr><th>Summary- TOTAL / COUNT' + cloneFoot.join('</th><th>') + '</th></tr></tfoot>';
            console.log(cloneHead);
            $('#exp_table_content').val(cloneHead + data + cloneFoot);
            $('#export_form').submit();
        });
        // ----------CSV Export----------------        
        $('#exportCsvButton').on('click', function(e) {
            var data1 = $("#itemtable").dataTable()._('tr', {"filter": "applied"});
            var data = data1.map(function(row) {
                var rowArr = [];
                $.each(arr, function(i, v) {
                    rowArr.push(row[v]);
                });
                return rowArr.join(',');
            }).join('|');
            var cloneHead = [];
            var cloneFoot = [];
            var head = $('#itemtable thead').clone();
            var foot = $('#itemtable tfoot').clone();
            head.find('th[data-export="true"]').each(function(i) {
                cloneHead.push($(this).html());
            });
            foot.find('th[data-export="true"]').each(function(j) {
                cloneFoot.push($(this).html());
            });
            cloneHead = cloneHead.join(',');
            cloneFoot = cloneFoot.join(',');
            // remove photo th
            var heads = cloneHead.split(',');
            var reshead = [];
            $.each(heads, function(i, v) {
                if (heads[i] != 'Photo') {
                    reshead.push(heads[i]);
                }
            });
            var foots = cloneFoot.split(',');
            var resfoot = [];
            $.each(foots, function(j, v) {
                if (heads[j] != 'Photo') {
                    if (j == '0') {
                        foots[j] = 'Summary- TOTAL / COUNT';
                    }
                    resfoot.push(foots[j]);
                }
            });
            $('#csv_table_content').val(reshead + '|' + data + '|' + resfoot);
            $('#export_csv_form').submit();
        });

        $(document).find("#itemtable").find("tfoot").addClass("pp");
    });
</script>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />

<div class="row">
    <h1>Archived/Removed Asset Register</h1>
</div>

<div class="heading">

    <div class="col-md-12" style="margin-top: 10px;">
        <div class="col-md-7">
            <div class="icon-nav">

                <a href="#" id="exportCsvButton" class="button icon-with-text round" type="button" style="padding:0">
                    <i class="fa  fa-file-pdf-o"></i>
                    <b>Export to <br />CSV</b>
                </a>

            </div>
            <div class="icon-nav">

                <a class="button icon-with-text round" id="exportPdfButton" href="#" style="padding: 0">
                    <i class="fa  fa-file-pdf-o"></i>
                    <b>Export to PDF</b></a>

            </div>
            <?php
            if ($arrSessionData['objSystemUser']->levelid > 1) {
                ?>

                <a id="clearfilter" class="button icon-with-text round">
                    <i class="glyphicon glyphicon-repeat" ></i>
                    <b>Clear Filter</b></a>
                <a class="button icon-with-text round" data-target="#columnselect" data-toggle="modal">
                    <i class="glyphicon glyphicon-th-list" style="transform: rotate(91deg)"></i>
                    <b>Show/Hide Columns</b>
                </a>
                <?php
            }
            ?>
        </div>
        <div class="text-right col-md-5">
            <span class ="com-name"><?= $arrSessionData['objSystemUser']->accountname; ?>
            </span>
            <?php
            $logo = 'logo.png';
            if (isset($this->session->userdata['theme_design']->logo) && $this->session->userdata['theme_design']->logo != '') {

                $logo = $this->session->userdata['theme_design']->logo;
            }
            ?>

            <div class="logocls">
                <img  alt="iSchool"  class="imgreplace" src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/youaudit/iwa/brochure/logo/' . $logo; ?>"  >

            </div>
        </div>
    </div>

</div>

<div class="row" style="overflow-x: auto;">
    <input type="hidden" id="item-url" value="<?php echo base_url('/archive/archived_items'); ?>">
    <table class="list_table tb" id="itemtable">
        <thead>

            <tr> 
                <?php
                foreach ($arrColumns as $column) {
                    $cnt = 22;
                    ?>
                    <th class="left" data-export="true"><?php echo $column->name; ?></th>

                <?php } ?>
                <th data-export="true">Removal Date</th>
                <th data-export="true">Removal Logged By</th>
                <th data-export="true">Confirm By</th>
                <th data-export="true">Reason</th>
                <th data-export="true">Method Of Removal</th>
                <th data-export="true">Income Removal</th>
                <th data-export="true">Net Income</th>
                <th class="" data-export="false" style="text-align: center;width: 160px;float: left;padding-left: 10px;height: 35px;">Actions</th>

            </tr>


            <tr> 
                  <?php
                foreach ($arrColumns as $column) {
                    ?>
                    <?php if ($column->input_name == "barcode") { ?>
                        <th><input type="text" name="filter_barcode" id="filter_barcode"></th>
                    <?php } elseif ($column->input_name == "photoid") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "categoryname") { ?>
                        <th><select id="filtercategoryname">
                                <option value=""></option>
                                <?php
                                foreach ($arrCategories['results'] as $arrCategory) {
                                    echo '<option value="' . $arrCategory->categoryname . '">' . $arrCategory->categoryname . '</option>';
                                }
                                ?>
                            </select>
                        </th>
                    <?php } elseif ($column->input_name == "item_manu") { ?>
                        <th><select id="filteritem_manu">
                                <option value=""></option>
                                <?php
                                foreach ($arrItemManu['list'] as $arrManu) {
                                    echo '<option value="' . $arrManu['item_manu_name'] . '">' . $arrManu['item_manu_name'] . '</option>';
                                    echo '>' . $arrManu['item_manu_name'] . "</option>\r\n";
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "manufacturer") { ?>
                        <th><select id="filtermanufacturer">
                                <option value=""></option>
                                <?php
                                foreach ($arrManufacturers as $arrManufacturer) {
                                    echo '<option value="' . $arrManufacturer . '">' . $arrManufacturer . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "model") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "quantity") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "sitename") { ?>
                        <th><select id="filtersitename">
                                <option value=""></option>
                                <?php
                                foreach ($arrSites['results'] as $arrSite) {
                                    echo '<option value="' . $arrSite->sitename . '">' . $arrSite->sitename . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "locationname") { ?>
                        <th><select id="filterlocationid">
                                <option value=""></option>
                                <?php
                                foreach ($arrLocations['results'] as $arrLocation) {
                                    echo '<option value="' . $arrLocation->locationname . '">' . $arrLocation->locationname . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "owner") { ?>
                        <th><select id="filteruserid">
                                <option value=""></option>
                                <?php
                                foreach ($arrOwners['results'] as $arrOwner) {
                                    echo '<option value="' . $arrOwner->owner_name . '">' . $arrOwner->owner_name . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "supplier") { ?>
                        <th>
                            <select id="filtersupplier">
                                <option value=""></option>
                                <?php
                                foreach ($arrSuppliers as $supplier) {
                                    echo '<option value="' . $supplier['supplier_name'] . '">' . $supplier['supplier_name'] . '</option>';
                                }
                                ?>
                            </select> 
                        </th>
                    <?php } elseif ($column->input_name == "statusname") { ?>
                        <th><select id="filteritemstatusid">
                                <option value=""></option>
                                <?php
                                foreach ($arrItemStatuses['results'] as $arrItemStatus) {
                                    echo '<option value="' . $arrItemStatus->statusname . '">' . $arrItemStatus->statusname . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "condition_name") { ?>
                        <th><select id="filterconstatus">
                                <option value=""></option>
                                <?php
                                foreach ($arrCondition as $arrCon) {
                                    echo '<option value="' . $arrCon['condition'] . '">' . $arrCon['condition'] . '</option>';
                                }
                                ?>
                            </select></th>
                    <?php } elseif ($column->input_name == "total_faults") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "serial_number") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "asset_age") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "purchase_date") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "warranty_expiry") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "replacement_due") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "value") { ?>
                        <th></th>
                    <?php } elseif ($column->input_name == "current_value") { ?>
                        <th></th>
                    <?php } else { ?>

                        <th><input type="text" id="<?php echo $cnt; ?>" name="filter_custom" class="filter_custom"></th> <?php
                        $cnt++;
                    }
                    ?>
                <?php } ?>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>

        <tbody>

        </tbody>
        <tfoot>
        <th>Total/Count</th> 
        <?php
        foreach ($arrColumns as $column) {
            ?>
            <th data-export="true"></th>          
            <?php
        }
        ?>
        <th data-export="true"></th>
        <th data-export="true"></th>
        <th data-export="true"></th>
        <th data-export="true"></th>
        <th data-export="true"></th>
        <th data-export="true"></th>
        <th data-export="true"></th>
        <!--<th data-export="true"></th>-->
        </tfoot>
    </table>
</div> 
<input type="hidden" id="num_of_th" value="<?php
$data = array();
$data[] = '0';
for ($i = 0; $i < count($arrUserColumns); $i++) {
    if (strpos($arrUserColumns[$i][0]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrUserColumns[$i][0]->id);
        $data[] = $explode_custom[2];
    } else {
        $data[] = $arrUserColumns[$i][0]->id;
    }
}
for ($j = 1; $j <= 7; $j++) {
    $data[] = $explode_custom[2] + $j;
}
$ids1 = array();
foreach ($data as $elem1) {
    $ids1[] = $elem1;
}
echo implode(',', $ids1);
?>">


<input type="hidden" id="assetfilter" value="<?php
$data = array();

for ($i = 0; $i < count($arrUserColumns); $i++) {
    if (strpos($arrUserColumns[$i][0]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrUserColumns[$i][0]->id);
        $data[] = $explode_custom[2];
    } else {
        $data[] = $arrUserColumns[$i][0]->id;
    }
}

$ids = array();
foreach ($data as $elem) {
    $ids[] = $elem;
}
for ($j = 1; $j < 7; $j++) {
    $ids[] = $i + $j;
}
echo implode(',', $ids);
?>">

<input type="hidden" id="total" value="<?php
$data = array();
for ($i = 0; $i < count($arrUserColumns); $i++) {
    if (strpos($arrUserColumns[$i][0]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrUserColumns[$i][0]->id);
        $data[] = $explode_custom[2];
    } else {
        $data[] = $arrUserColumns[$i][0]->id;
    }
}

$ids1 = array();
foreach ($data as $elem1) {
    $ids1[] = $elem1;
}

$remain = array();
for ($i = 0; $i < count($arrColumns); $i++) {
    if (strpos($arrColumns[$i]->id, 'custom') !== false) {
        $explode_custom = explode('_', $arrColumns[$i]->id);
        $remain[] = $explode_custom[2];
    } else {
        $remain[] = $arrColumns[$i]->id;
    }
}
$ids2 = array();
foreach ($remain as $elem2) {
    $ids2[] = $elem2;
}

$arr1 = array_diff($ids2, $ids1);
if (!empty($arr1)) {
    echo implode(',', $arr1);
}
?>">
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="columnselect" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Choose Columns</h4>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('archive/archived_assets/') ?>" method="post">


                    <div class="col-md-12">
                        <?php
                        $arr = intval(count($arrColumns) / 2);
                        for ($i = 0; $i < $arr; $i++) {
                            ?>
                            <div class="col-md-6">
                                <div class="col-md-2"><input type="checkbox" name="columns[]" value="<?= $arrColumns[$i]->id; ?>" <?php
                                    foreach ($arrUserColumnsFilter as $usercolumn) {
                                        print ($usercolumn[0]->id == $arrColumns[$i]->id ? 'checked' : '');
                                    };
                                    ?> /></div>
                                <div class="col-md-10"><?= $arrColumns[$i]->name; ?></div>
                            </div>
                        <?php } for ($i = $arr; $i < count($arrColumns); $i++) { ?>

                            <div class="col-md-6">
                                <div class="col-md-2"><input type="checkbox" name="columns[]" value="<?= $arrColumns[$i]->id; ?>" <?php
                                    foreach ($arrUserColumnsFilter as $usercolumn) {
                                        print ($usercolumn[0]->id == $arrColumns[$i]->id ? 'checked' : '');
                                    };
                                    ?> /></div>
                                <div class="col-md-10"><?= $arrColumns[$i]->name; ?></div> 
                            </div>
                        <?php } ?>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input class="btn btn-warning" type="submit" value="Apply columns"/>
            </div>
            </form>
        </div>
    </div>
</div>

<form id="export_form" class="hider" hidden="" action="<?php echo base_url('/items/exportToPdf'); ?>" method="post">
    <input id="exp_table_content" name="allData">
    <input name="filename" value="Itemlist">
    <input type="submit">
</form>
<form id="export_csv_form" class="hider" hidden="" action="<?php echo base_url('/items/exporttocsv'); ?>" method="post">
    <input id="csv_table_content" name="allData">
    <input name="filename" value="Itemlist">
    <input type="submit">
</form>



