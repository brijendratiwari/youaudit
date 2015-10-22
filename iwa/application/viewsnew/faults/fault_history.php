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
#view_fault .modal-body{
     height: 595px;
    overflow-y: scroll;
}
</style>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/bootbox/bootbox.min.js"></script>
<script>
    $(document).ready(function() {


//        ####################################################################################################################
//          fault history view js...
                  var site_server = '<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>';
        var base_url = $("#base_url").val();
        $('body').on('click',".viewfault",function() {
            var iId = $(this).attr('id');
            var account_id = $(this).attr('account_id');
            var ticket_id = $(this).attr('ticket_id');
            var type = 'Fix';
            // Call ajax
            $.ajax({
                type: "POST",
                url: base_url + "faults/ajaxfetchItemForSingleItem",
                dataType: 'json',
                data: "&id=" + iId + "&account_id=" + account_id + "&type=" + type+"&ticket_id="+ticket_id,
                success: function(data) {
                    console.log('I am result');
                    $("#view_fault #v_item_manu").val(data.item_manu_name);
                    $("#view_fault #v_manufacturer").val(data.manufacturer);
                    $("#view_fault #v_serial_number").val(data.barcode);
                    $("#view_fault #v_categoryname").val(data.categoryname);
                    $("#view_fault #v_locationname").val(data.locationname);
                    $("#view_fault #v_itemstatusname").val(data.itemstatusname);
                    $("#view_fault #v_order_no").val(data.order_no);
                    $("#view_fault .actionData").html(data.actionData);


                    $("#view_fault  #v_status").find('option').each(function(i, opt) {
                        if (opt.value == data.itemstatusid) {
                            $(opt).attr('selected', 'selected');
                        }

                    });

                    $("#view_fault  #v_severity").find('option').each(function(i, opt) {
                        if (opt.value == data.severity) {
                            $(opt).attr('selected', 'selected');
                        }
                        else
                        {
                            $(opt).attr('selected', false);
                        }

                    });

                    $("#view_fault  #v_fix_code").find('option').each(function(i, opt) {
                        if (opt.value == data.fix_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#view_fault #v_action").find('option').each(function(i, opt) {

                        if (opt.value == data.ticket_action) {
                            $(opt).attr('selected', 'selected');

                        }
                    });

                    $("#view_fault #v_reason_code").find('option').each(function(i, opt) {

                        if (opt.value == data.reason_code) {
                            $(opt).attr('selected', 'selected');

                        }
                    });
                      if (data.allPhoto != null) {
                        var updatePhoto = data.allPhoto.split(',');
                    }
                    else {
                        var updatePhoto = data.photoid.split(',');
                    }
                                      if (updatePhoto.length != 0) {
                        $('.fault_photo2').css('display', 'block');


                        $("#photo_div_resolve").empty();
                        for (var i = 0; i < updatePhoto.length; i++) {
                            var img_div = '';
                            img_div += "<div style='float:left' class='ui-lightbox-gallery'>";
                            img_div += "<div class='image_single'>";
                            img_div += "<img width='65' alt='Gallery Image' style='display: inline-block' class='thumbnail thumb'  src='" + base_url + "/index.php/images/viewList/" + updatePhoto[i] + "'>";
                            img_div += "</div></div>";
                            $("#photo_div_resolve").append(img_div);
                        }
                    }
                    else
                    {
                        $('.fault_photo2').css('display', 'none');
                    }

//                    if (data.allNotes) {
//                        var jobnotes = data.allNotes.split(',');
//                        $(".job_notes_div").empty();
//                        var job_div = '';
//
//                        job_div += "<ul>";
//                        for (var i = 0; i < jobnotes.length; i++) {
//
//                            job_div += "<li>" + jobnotes[i] + "</li>";
//                        }
//
//                        job_div += "</ul>";
//                        console.log(job_div);
//                        $(".job_notes_div").html(job_div);
//                    }
//                      var notes_div = '';
//                    if (data.allNotes != "") {
//                        var allNote = data.allNotes.split(',');
//                        var noteDate = data.notesDate.split(',');
//                            $(".job_notes_div").empty();
//                       
//                        notes_div += "<ul>";
//                        for (var i = 0; i < allNote.length; i++) {
//                            notes_div += "<li style='list-style:none;padding:0;margin:0;'>"+noteDate[i]+ " - "+ allNote[i] + "</li>";
//
//                        }
//                        notes_div += "</ul>";
//                    }else{
//                    $(".job_notes_div").empty();
//                       notes_div += "<ul><li style='list-style:none;padding:0;margin:0;'>" +data.loggedByDate+ " - "+ data.jobnote + "</li></ul>";
//                    }
                  
//                        $(".job_notes_div").html(notes_div);

                    $("#view_fault #v_job_notes").val(data.jobnote);
                    $("#save_button").show();

                } // End of success
            }); // End of ajax

        }) // End of function

       //        ####################################################################################################################










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
                    localStorage.removeItem('DataTables_/youaudit/iwa/faults/faultHisyoryAjaxData');
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
            localStorage.removeItem('DataTables_/youaudit/iwa/faults/faultHisyoryAjaxData');
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

                <a href="<?= base_url('faults/exportPDFForFixFaults/CSV') ?>" class="button icon-with-text round" type="button" style="padding:0">
                    <i class="fa  fa-file-pdf-o"></i>
                    <b>Export to <br />CSV</b>
                </a>

            </div>
            <div class="icon-nav">

                <a class="button icon-with-text round"  href="<?= base_url('faults/exportPDFForFixFaults/PDF') ?>" style="padding: 0">
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
    <input type="hidden" id="item-url" value="<?php echo base_url('/faults/faultHisyoryAjaxData'); ?>">
    <table class="list_table tb" id="itemtable">
        <thead>

            <tr> 
                <?php
                foreach ($arrColumns as $column) {
                    $cnt = 22;
                    ?>
                    <th class="left" data-export="true"><?php echo $column->name; ?></th>

                <?php } ?>
                <th data-export="true">Incident Type</th>
                <th data-export="true">Fault Date</th>
                <th data-export="true">Incident Length</th>
                <th data-export="true">Severity</th>
                <th data-export="true">Fix Date</th>
                <th data-export="true">Order No</th>
                <th data-export="true">Job Notes</th>
                <th data-export="true">Fault Logged By</th>
                <th data-export="true">Fix Logged By</th>
                <th data-export="true">Fix Reason Code</th>
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
<!--modal for show hide column....-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="columnselect" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Choose Columns</h4>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('faults/faulthistory/') ?>" method="post">


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



<!-- View Fault Model -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="view_fault" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 id="myModalLabel" class="modal-title">View Fault</h4>
            </div>

            <div class="modal-body">
                <!-- Report Fault -->

                <div class="form-group col-md-12">
                </div>

                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Item</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="item_manu" id="v_item_manu">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Manufacturer</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="manufacturer" id="v_manufacturer">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>QR CODE</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="serial_number" id="v_serial_number">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6">  <label>Category</label> </div>
                    <div class="col-md-6">  <input readonly="readonly" class="form-control" name="categoryname" id="v_categoryname">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Location</label> </div>
                    <div class="col-md-6"><input readonly="readonly" class="form-control" name="locationname" id="v_locationname">
                    </div>
                </div>

                <div class="form-group col-md-12">
                </div>
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Severity</label> </div>
                    <div class="col-md-6">
                        <select class="form-control" name="severity" id="v_severity" disabled="">
                            <option value="low">Low<option>
                            <option value="normal">Normal<option>
                            <option value="High">High<option>
                            <option value="critical">Critical<option>
                        </select>
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Status</label> </div>
                    <div class="col-md-6"><input  class="form-control" name="itemstatusname" id="v_itemstatusname" disabled="">
                    </div>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Enter Order No</label> </div>
                    <div class="col-md-6"><input type="text" name="order_no" id="v_order_no" class="form-control" value="" disabled="" /></div></div> <!-- /.form-group -->
                <div class="form-group col-md-12">
                </div>
                       <div class="col-md-12">
                           <div class="col-md-3"><label>Timeline/ Job Notes</label></div>
                           <div class="actionData col-md-9"></div>

                        </div> 
                <!-- Job Notes -->
<!--                <div class="form-group col-md-12">
                    <div class="col-md-6"><label>Job Notes</label>
                    </div>
                    <div class="col-md-6 job_notes_div">
                    </div>
                </div>-->
                <div class="form-group col-md-12" >

                </div>
                  <div class="form-group col-md-12 fault_photo2">
                    <div class="col-md-6"><label>Photos</label>   </div>
                    <div class="col-md-6" id="photo_div_resolve"> </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
            </div>
        </div>

    </div>
    <!-- /.modal-dialog -->
</div>


