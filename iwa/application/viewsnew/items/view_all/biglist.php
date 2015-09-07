<style>
    .searchbox {
        background: none repeat scroll 0 0 #ffffff;
        border: 1px solid #d5d6d5;
        border-radius: 15px;
        float: left;
        padding: 0 6px;
        width: 100%;
        margin-top: 15px;
    }
    #gsearch {
        color: #b0b0b0;
        float: left;
        font-weight: bold;
        padding-top: 6px;
    }
    #itemSearch
    {
        border: none;
    }
    .page_link { margin-top:15px; }
    .page_link a {
        background: none repeat scroll 0 0 #fff;
        border: 1px solid #e5e5e5;
        color: #666;
        padding: 5px 10px;
        text-decoration: none;
    }
    .page_link a:hover { background: #00aeef; color: #fff;}
    .page_link strong {
        background: none repeat scroll 0 0 #00aeef;
        border: 1px solid #ccc;
        color: #eee;
        padding: 5px 10px;
    }
</style>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<div class="row">
    <h1>View Asset Details</h1>
</div>
<div class="heading">



    <div class="col-md-12" style="margin-top: 10px;">
        <div class="col-md-9">
            <div class="icon-nav">
                <form  id="csvform" action="<?= site_url($_SERVER['REDIRECT_QUERY_STRING']) ?>" method="post">
                    <input  type="hidden" value="ExportResultsasCSV" name="csvfile">
                    <a href="javascript:$('#csvform').submit();" class="button icon-with-text round" type="button" style="padding:0">
                        <i class="fa  fa-file-pdf-o"></i>
                        <b>Export to <br />CSV</b>
                    </a>
                </form>
            </div>
            <div class="icon-nav">
                <a class="button icon-with-text round" target="blank" href="<?php echo site_url('items/exportPDF/') ?>" style="padding: 0">
                    <i class="fa  fa-file-pdf-o"></i>
                    <b>Export to PDF</b></a>
            </div>

            <?php
            if ($arrSessionData['objSystemUser']->levelid > 1) {
                ?>

                <a id="clearfilter" href="<?= site_url('items/filter') ?>" class="button icon-with-text round">
                    <i class="glyphicon glyphicon-repeat" ></i>
                    <b>Clear Filter</b></a>
                <a id="activate-column-selector" href="#" class="button icon-with-text round">
                    <i class="glyphicon glyphicon-th-list" style="transform: rotate(91deg)"></i>
                    <b>Show/Hide Columns</b>
                </a>
                <?php
                if ($arrSessionData['objSystemUser']->levelid > 2) {
                    ?>
                    <a href="<?php echo site_url('/items/confirmdeleted/'); ?>" class="button icon-with-text round">
                        <i class="fa  fa-trash-o"></i>
                        <b>Confirm Deletions</b></a>
                    <?php
                }
                ?>
                <a href="#" data-toggle="modal" data-target="#add_item" class="btn btn-primary icon-with-text round" id="additem" style="top: -20px;position: relative">
                    <i class="fa  fa-plus"></i>
                    Add Items</a>
            </div>
            <div class="text-right col-md-3">
                <span class ="com-name"><?= $arrSessionData['objSystemUser']->accountname; ?>
                    <img src="<?= base_url('/img/circle-red.png'); ?>" width="60" /></span>
            </div>
        </div>

    </div>
    <?php
}
?>
</div>
<?php if (count($arrItemsData['results']) > 0) { ?>
    <div class="pagination">

        <label for="page_limit">Show Entries</label>
        <select id="per_page" class="form-control" name="page_limit" onchange="changelist('page', false);">
            <option>20</option>
            <option value="50" <?php
            if ($mixPageLimit == 50) {
                echo 'selected="selected" ';
            }
            ?>>50</option>
            <option value="100" <?php
            if ($mixPageLimit == 100) {
                echo 'selected="selected" ';
            }
            ?>>100</option>
            <option value="250" <?php
            if ($mixPageLimit == 250) {
                echo 'selected="selected" ';
            }
            ?>>250</option>
            <option value="500" <?php
            if ($mixPageLimit == 500) {
                echo 'selected="selected" ';
            }
            ?>>500</option>
            <option value="none" <?php
            if ($mixPageLimit == "none") {
                echo 'selected="selected" ';
            }
            ?>>all</option>

        </select>
        <!--        <label for="page_limit">items per page</label>-->
    </div>
    <div class="col-md-2 pull-right">
        <div class="searchbox"><i id="gsearch" class="fa fa-search"></i>
            <input type="text" placeholder="Search" id="itemSearch">
        </div></div>
<?php } ?>
<div class="box_content" style="overflow-x: auto">
    <div class="content_main">
        <table class="list_table tb" id="item_table">
            <thead>

                <tr> <th class="left">Select</th>
                    <?php foreach ($arrUserColumns as $column) { ?>
                        <th class="left">
                            <a class="sorter" onclick="changelist('order', '<?= $column[0]->input_name ?>');"><?php
                                if ($strOrderField == $column[0]->input_name) {
                                    if ($strOrderDirection == "desc") {
                                        echo "<img src=\"" . base_url() . "/img/filter-arrow-down.png\" />";
                                    } else {
                                        echo "<img src=\"" . base_url() . "/img/filter-arrow-up.png\" />";
                                    }
                                } else {
                                    echo "<img src=\"" . base_url() . "/img/filter-arrow.png\" />";
                                }
                                ?> <?= $column[0]->name ?></a></th>

                    <?php } ?>
                    <th class="right action" style="text-align: center">Actions</th>

                </tr>
            </thead>
            <tbody>
                <tr class="filter">
                    <td class="left"><input type="checkbox" title="Select ALL" id="selectAllchk"><button type="button" id="multiComEditBtn" class="btn btn-warning fade hide" style="padding:0 5px;" onclick="multiComInit()">Edit</button></td>
                    <?php
                    foreach ($arrUserColumns as $column) {
                        ?>
                        <?php if ($column[0]->input_name == "barcode" || $allColumns == true) { ?>
                            <td><input type="text" name="filter_barcode" value="<?php
                                if (array_key_exists('strbarcode', $arrFilters)) {
                                    echo $arrFilters['strbarcode'];
                                }
                                ?>" style="width:60px; margin-bottom: 2px;" />
                                <a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" alt="Filter Results"/></a>
                            </td>

                        <?php } elseif ($column[0]->input_name == "serial_number" || $allColumns == true) { ?>
                            <td><input type="text" name="filter_serial_number" value="<?php
                                if (array_key_exists('strserialnumber', $arrFilters)) {
                                    echo $arrFilters['strserialnumber'];
                                }
                                ?>" style="width:60px; margin-bottom: 2px;" />
                                <a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" alt="Filter Results"/></a>
                            </td>

                        <?php } elseif ($column[0]->input_name == "manufacturer" || $allColumns == true) { ?>
                            <td><select name="filter_manufacturer" onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrManufacturers as $arrManufacturer) {
                                        echo "<option ";
                                        echo 'value="' . $arrManufacturer . '" ';
                                        if (array_key_exists('strmanufacturer', $arrFilters)) {
                                            if ($arrFilters['strmanufacturer'] == $arrManufacturer) {
                                                echo 'selected="selected" ';
                                            }
                                        }
                                        echo '>' . $arrManufacturer . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>
                        <?php } elseif ($column[0]->input_name == "item_manu" || $allColumns == true) { ?>
                            <td><select name="filter_item_manu" onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrItemManu['list'] as $arrManu) {
                                        echo "<option ";
                                        echo 'value="' . $arrManu['item_manu_name'] . '" ';
                                        if (array_key_exists('stritemmanuname', $arrFilters)) {
                                            if ($arrFilters['stritemmanuname'] == $arrManu['item_manu_name']) {
                                                echo 'selected="selected" ';
                                            }
                                        }
                                        echo '>' . $arrManu['item_manu_name'] . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>
                        <?php } elseif ($column[0]->input_name == "categoryname") { ?>
                            <td><select name="filter_categoryname" onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrCategories['results'] as $arrCategory) {
                                        echo "<option ";
                                        echo 'value="' . $arrCategory->categoryname . '" ';
                                        if (array_key_exists('strcategoryname', $arrFilters)) {
                                            if ($arrFilters['strcategoryname'] == $arrCategory->categoryname) {
                                                echo 'selected="selected" ';
                                            }
                                        }
                                        echo '>' . $arrCategory->categoryname . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>

                        <?php } elseif ($column[0]->input_name == "statusname") { ?>
                            <td><select name="filter_itemstatusid" onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrItemStatuses['results'] as $arrItemStatus) {
                                        echo "<option ";
                                        echo 'value="' . $arrItemStatus->statusid . '" ';
                                        if (array_key_exists('stritemstatusid', $arrFilters)) {
                                            if ($arrFilters['stritemstatusid'] == $arrItemStatus->statusid) {
                                                echo 'selected="selected" ';
                                            }
                                        }
                                        echo '>' . $arrItemStatus->statusname . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>

                        <?php } elseif ($column[0]->input_name == "sitename") { ?>
                            <td><select name="filter_sitename" onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrSites['results'] as $arrSite) {
                                        echo "<option ";
                                        echo 'value="' . $arrSite->sitename . '" ';
                                        if (array_key_exists('strsitename', $arrFilters)) {
                                            if ($arrFilters['strsitename'] == $arrSite->sitename) {
                                                echo 'selected="selected" ';
                                            }
                                        }
                                        echo '>' . $arrSite->sitename . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>

                        <?php } elseif ($column[0]->input_name == "owner") { ?>
                            <td><select name="filter_userid"  onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrUsers['results'] as $arrUser) {
                                        echo "<option ";
                                        echo 'value="' . $arrUser->userid . '" ';
                                        if (array_key_exists('struserid', $arrFilters)) {
                                            if ($arrFilters['struserid'] == $arrUser->userid) {
                                                echo 'selected="selected" ';
                                            }
                                        }
                                        echo '>' . $arrUser->userfirstname . " " . $arrUser->userlastname . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>

                        <?php } elseif ($column[0]->input_name == "locationname") { ?>
                            <td><select name="filter_locationid" onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrLocations['results'] as $arrLocation) {
                                        echo "<option ";
                                        echo 'value="' . $arrLocation->locationid . '" ';
                                        if (array_key_exists('strlocationid', $arrFilters)) {
                                            if ($arrFilters['strlocationid'] == $arrLocation->locationid) {
                                                echo 'selected="selected" ';
                                            }
                                        }
                                        echo '>' . $arrLocation->locationname . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>

                            <!--  DropDown For Pat Status  -->
                        <?php } elseif ($column[0]->input_name == "pat_status") { ?>
                            <td><select name="filter_patstatus"  onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrPatStatus as $arrPat) {
                                        echo "<option ";
                                        echo 'value="' . $arrPat->id . '" ';

                                        if (array_key_exists('strpatid', $arrFilters)) {
                                            if ($arrFilters['strpatid'] == $arrPat->id) {
                                                echo 'selected="selected" ';
                                            }
                                        }

                                        echo '>' . $arrPat->pattest_name . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>


                        <?php } elseif ($column[0]->input_name == "condition_name") {
                            ?>
                            <td><select name="filter_constatus"  onchange="changelist('filter', false);">
                                    <option value=""></option>
                                    <?php
                                    foreach ($arrCondition as $arrCon) {
                                        echo "<option ";
                                        echo 'value="' . $arrCon['id'] . '" ';

                                        if (array_key_exists('strconid', $arrFilters)) {
                                            if ($arrFilters['strconid'] == $arrCon['id']) {
                                                echo 'selected="selected" ';
                                            }
                                        }

                                        echo '>' . $arrCon['condition'] . "</option>\r\n";
                                    }
                                    ?>
                                </select>
                                <!--<a onclick="changelist('filter', false);"><img class="filter" src="/img/icons/filter.png" /></a>-->
                            </td>


                        <?php } else {
                            ?>
                            <td></td>
                        <?php } ?>
                    <?php } ?>
                    <!--<td class="right"></td>-->

                </tr>

                <?php
                if (count($arrItemsData['results']) > 0) {

                    foreach ($arrItemsData['results'] as $arrItem) {
                        ?>


                        <tr>  <td><input class="multiComSelect" type="checkbox" value="<?php echo $arrItem->itemid; ?>"><input class="" type="hidden" id="category_id_<?php echo $arrItem->itemid; ?>" value="<?php echo $arrItem->categoryid; ?>"></td>

                            <?php foreach ($arrUserColumns as $column) { ?>
                                <?php
                                if ($column[0]->combination) {
                                    $columns = explode(',', $column[0]->combination);
                                    ?>
                                    <td><?php echo $arrItem->{$columns[0]} ?> <?php echo $arrItem->{$columns[1]} ?></td>
                                <?php } else { ?>
                                    <?php if ($column[0]->name == "QR Code") { ?>
                                        <td><a href="<?php echo site_url('/items/view/' . $arrItem->itemid . '/'); ?>"><pre><?php echo $arrItem->barcode; ?></pre></a></td>
                                    <?php } elseif ($column[0]->name == "Photo") { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><img src="<?= site_url('/images/viewlist/' . $arrItem->itemphotoid) ?>" title="<?= $arrItem->itemphototitle ?>"/></td>
                                    <?php } elseif ($column[0]->name == "Purchase Price") { ?>
                                        <?php $total = $arrItem->value * $arrItem->quantity; ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <? if ($arrItem->quantity > 1) { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td>&dollar;<?php echo $total; ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <? } else { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td>&dollar;<?php echo $arrItem->{$column[0]->input_name} ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <? } ?>
                                    <?php } elseif ($column[0]->name == "Purchase Date") { ?>
                                        <?php
                                        $date = $arrItem->purchase_date;
                                      
                                         if (strtotime($date) > 0) {
                                        $newDate = date("d-m-Y", strtotime($date));
                                         }else{
                                             $newDate = '-';
                                         }
                                        ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>  <?= $newDate; ?> </td>
                                    <?php } elseif ($column[0]->name == "Age Of Asset") { ?>
                                        <?php
                                        $date2 = date('d-m-Y', strtotime($arrItem->purchase_date));
                                        $date1 = date('d-m-Y H:i:s', strtotime(date('Y-m-d')));

                                        $diff = abs(strtotime($date2) - strtotime($date1));

                                        $years = floor($diff / (365 * 60 * 60 * 24));
                                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));

                                        $age = $years . ' year ' . $months . ' month ';
                                        ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>  <?= $age; ?> </td>
                                    <?php } else { ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><?= ($column[0]->name == "Value" ? '&dollar' : '') ?><?php echo $arrItem->{$column[0]->input_name} ?></td>
                                        <?php
                                    }
                                }
                                ?>
                            <?php } ?>

                                                                                                                                                                                                                                    <td>

                                                                                                                                                                                                                            <a data-toggle="modal" data-target="#add_similar_item" href="#" title="Add similar" id="addsimilaritem" class="add_similar icon-with-text" data_item_id="<?php echo $arrItem->itemid; ?>"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;
                                                                                                                                                                                                                            <a href="<?php echo site_url('/items/view/' . $arrItem->itemid . '/'); ?>" class="icon-with-text" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;

                                <?php
                                if ($arrSessionData['objSystemUser']->levelid > 1) {
                                    ?>
                                                                                                                                                                                                                                                                                                                         <a href="<?php echo site_url('/items/editItem/' . $arrItem->itemid . '/'); ?>" class="icon-with-text" title="Edit">
                                                                                                                                                                                                                                                                                                                         <i class="fa fa-edit"></i></a>
                                    <?php
                                }
                                ?>


                                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                                        </tr>
                        <?php
                    }
                    ?>
                                                                                                                                                                                    </tbody>
                                 <tr> <td></td>
                    <?php
                    if (count($arrItemsData['results']) > 0) {

                        for ($k = 0; $k < count($arrItemsData['results']); $k++) {
                            $quantity = array();
                            $purchase_price = array();
                            $current_value = array();
                            for ($i = 0; $i < $intItemsDisplayCount; $i++) {
                                $quantity[] = $arrItemsData['results'][$i]->quantity;
                                $purchase_price[] = $arrItemsData['results'][$i]->value;
                                $current_value[] = $arrItemsData['results'][$i]->current_value;
                            }
                        }

                        foreach ($arrUserColumns as $column) {
                            if ($column[0]->name == "Quantity") {
                                ?>
                                    <td style="text-align: center;" id="<?php echo $column[0]->id; ?>"><?php echo array_sum($quantity); ?></td>
                            <?php } elseif ($column[0]->name == "Purchase Price") { ?>
                                                                    <td style="text-align: center;" id="<?php echo $column[0]->id; ?>"><?php echo array_sum($purchase_price); ?></td>
                            <?php } elseif ($column[0]->name == "Current Value") { ?>
                                                                    <td style="text-align: center;" id="<?php echo $column[0]->id; ?>"><?php echo array_sum($current_value); ?></td>
                            <?php } else { ?>
                                                                <td id="<?php echo $column[0]->id; ?>"><?php echo ''; ?></td>
                                                                
                                <?php
                            }
                        }
                    }
                    ?>

                                                    <td></td>

                                                </tr>                                                                                                                                               </table>

            <?php
        } else {
            echo "</table>";
        }
        ?>
    </div>
    </div>

    <div class="page_link">
    <?php echo $this->pagination->create_links();
    ?>
            <!--    <ul>
    <?php if ($booDisplayPrevLink) { ?>
                                                                                                                                                                                                        <li><a class="prev" onclick="changelist('page', <?php echo $intPrevPage; ?>);" title="Previous Page" ></a></li>
    <?php } ?>
    <?php
    if ($intPagesAvailable > 0) {
        for ($intCount = 1; $intCount <= $intPagesAvailable; $intCount++) {

            echo "<li";
            if ($intCurrentPage == $intCount) {
                echo " class=\"selected\"";
            }
            echo "><a onclick=\"changelist('page', " . $intCount . ");\"";
            echo " title=\"Page " . $intCount . "\" >" . $intCount . "</a></li>";
        }
    }
    ?>
    <?php if ($booDisplayNextLink) { ?>
                                                                                                                                                                                                        <li><a class="next" onclick="changelist('page', <?php echo $intNextPage; ?>);" title="Next Page" ></a></li>
    <?php } ?>
                </ul>-->
    </div>

    <div id="columnselector" style="display: none;">
        <form action="<?= site_url('items/filter/') ?>" method="post">
        <?php foreach ($arrColumns as $column) { ?>
                                                                                                                                                                                                <div class="form_row">
                                                                                                                                                                                                    <label for="item_make"><?= $column->name ?></label>
                                                                                                                                                                                                    <input type="checkbox" name="columns[]" value="<?= $column->id ?>" <?php
                foreach ($arrUserColumns as $usercolumn) {
                    print ($usercolumn[0]->id == $column->id ? 'checked' : '');
                };
                ?> />
                                                                                                                                                                                               
                                                                                                                                                                                    <!--            <hr style="margin: 0;"/>-->
                                                                                                                                                                                                 </div>
        <?php } ?>


            <input class="button" type="submit" value="Apply columns" style="width: 100%;"/>
        </form>
    </div>
    <style>
    .modal-body .row
    {
        margin: 10px 0px;
    }
    #add_item .modal-dialog
    {
        width: 600px;
    }
    #add_item .modal-body{
        height: 595px;
        overflow-y: scroll;
    } 
    .error
    {
        color: red;
    }
    </style>
    <!-- Edit Item Model -->
    <div class="modal fade" id="multiComplianceEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo base_url() ?>items/editmultiitem" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Edit Multiple Items</h4>
                        </div>
                        <div class="modal-body">
                 
                          <input hidden="" name="items_id" id="multiComIds">
                            <div class="row">
                             
                                <label class="col-md-4">Category</label>
                                <select class="col-md-6" name="category">
                                    <option value="">-- Please Select --</option>
                            <?php foreach ($arrCategories['results'] as $category) { ?>
                                                                                                                                                                                                                        <option value="<?php print $category->categoryid; ?>" data-selector="<?php print $category->categoryname; ?>"><?php print $category->categoryname; ?></option>
                        <?php } ?>
                                </select>
                            </div> 
                            <div class="row">
                                
                                <label class="col-md-4">Owner</label>
                                <select class="col-md-6" name="user">
                                    <option value="">-- Please Select --</option>
                            <?php foreach ($arrUsers['results'] as $user) { ?>
                                                                                                                                                                                                                        <option value="<?php print $user->userid; ?>" data-selector="<?php print $user->userfirstname . ' ' . $user->userlastname; ?>"><?php print $user->userfirstname . ' ' . $user->userlastname; ?></option>
                        <?php } ?>
                                </select>
                            </div> 
                            <div class="row">
                                
                                <label class="col-md-4">Location</label>
                                <select class="col-md-6" name="location">
                                    <option value="">-- Please Select --</option>
                            <?php foreach ($arrLocations['results'] as $location) { ?>
                                                                                                                                                                                                                        <option value="<?php print $location->locationid; ?>" data-selector="<?php print $location->locationname; ?>"><?php print $location->locationname; ?></option>
                        <?php } ?>
                                </select>
                            </div> 
                            <div class="row">
                                
                                <label class="col-md-4">Status</label>
                                <select class="col-md-6" name="status">
                                    <option value="">-- Please Select --</option>
                            <?php foreach ($arrItemStatuses['results'] as $status) { ?>
                                                                                                                                                                                                                        <option value="<?php print $status->statusid; ?>" data-selector="<?php print $status->statusname; ?>"><?php print $status->statusname; ?></option>
                        <?php } ?>
                                </select>
                            </div> 
                            <div class="row">
                                
                                <label class="col-md-4">Supplier</label>
                                <select class="col-md-6" name="supplier">
                                    <option value="">-- Please Select --</option>
                            <?php foreach ($arrSuppliers as $supplier) { ?>
                                                                                                                                                                                                                        <option value="<?php print $supplier['supplier_id']; ?>" data-selector="<?php print $supplier['supplier_title']; ?>"><?php print $supplier['supplier_title']; ?></option>
                        <?php } ?>
                                </select>
                            </div> 
                            <div class="row">
                                
                                <label class="col-md-4">Site</label>
                                <select class="col-md-6" name="site">
                                    <option value="">-- Please Select --</option>
                            <?php foreach ($arrSites['results'] as $site) { ?>
                                                                                                                                                                                                                        <option value="<?php print $site->siteid; ?>" data-selector="<?php print $site->sitename; ?>"><?php print $site->sitename; ?></option>
                        <?php } ?>
                                </select>
                            </div> 
                            <div class="row">
                                
                                <label class="col-md-4">Warranty Date</label>
                                <input type="input" name="item_warranty" id="item_warranty" value="" class="datepicker" />
                             
                            </div> 
      
    <div id="custom_header">
             <img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span>
                                   
                                  </div>   

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" onclick="return beforeMultipleEdit()" class="btn btn-warning">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    <script type="text/javascript">
        function filter()
        {
            url = '';
            var filter_barcode = $('input[name=\'filter_barcode\']').attr('value');
            if (filter_barcode) {
                url += '/fr_barcode_start/' + encodeURIComponent(filter_barcode);
            }

            var filter_serial_number = $('input[name=\'filter_serial_number\']').attr('value');
            if (filter_serial_number) {
                url += '/fr_serial_start/' + encodeURIComponent(filter_serial_number);
            }

            var filter_manufacturer = $('select[name=\'filter_manufacturer\']').attr('value');
            if (filter_manufacturer) {
                url += '/fr_manufacturer_exact/' + encodeURIComponent(filter_manufacturer);
            }

            var filter_item_manu = $('select[name=\'filter_item_manu\']').attr('value');
            if (filter_item_manu) {

                url += '/fr_itemmanuname_exact/' + encodeURIComponent(filter_item_manu);
            }

            var filter_categoryname = $('select[name=\'filter_categoryname\']').attr('value');
            if (filter_categoryname) {
                url += '/fr_categoryname_exact/' + encodeURIComponent(filter_categoryname);
            }

            var filter_itemstatusid = $('select[name=\'filter_itemstatusid\']').attr('value');
            if (filter_itemstatusid) {
                url += '/fr_itemstatusid_exact/' + encodeURIComponent(filter_itemstatusid);
            }

            var filter_sitename = $('select[name=\'filter_sitename\']').attr('value');
            if (filter_sitename) {
                url += '/fr_sitename_exact/' + encodeURIComponent(filter_sitename);
            }

            var filter_locationid = $('select[name=\'filter_locationid\']').attr('value');
            if (filter_locationid) {
                url += '/fr_locationid_exact/' + encodeURIComponent(filter_locationid);
            }

            var filter_userid = $('select[name=\'filter_userid\']').attr('value');
            if (filter_userid) {
                url += '/fr_userid_exact/' + encodeURIComponent(filter_userid);
            }

            var filter_patstatus = $('select[name=\'filter_patstatus\']').attr('value');
            if (filter_patstatus) {
                url += '/fr_patid_exact/' + encodeURIComponent(filter_patstatus);
            }
            var filter_constatus = $('select[name=\'filter_constatus\']').attr('value');
            if (filter_constatus) {
                url += '/fr_conid_exact/' + encodeURIComponent(filter_constatus);
            }

            return url;
        }

        function pagination()
        {
            url = '';
            var page_limit = $('select[name=\'page_limit\']').attr('value');
            if (page_limit) {
                url += '/pg_limit/' + encodeURIComponent(page_limit);
            }
            return url;
        }

        function changelist(mode, field)
        {
            console.log(mode);
            order = '<?php echo $strOrderField; ?>';
            direction = '<?php echo $strOrderDirection; ?>';
            url = '<?php echo base_url('/items/filter/'); ?>';
            if (mode == 'order')
            {
                url += '/or_' + encodeURIComponent(field);
                if ((order == field) && direction == 'asc')
                {
                    url += '/desc';
                }
                else
                {
                    url += '/asc';
                }

            }
            else
            {
                if (order != 'FALSE')
                {
                    url += '/or_' + encodeURIComponent(order) + '/' + direction;
                }
                if ((mode == 'page') && field)
                {
                    url += '/pg_page/' + field;
                }

            }
            url += filter();
            url += pagination();
            location = url;
        }


        $(document).ready(function() {
            $('body').find('.multiComSelect:checked').prop('checked', false);
            $('body').find('#selectAllchk').prop('checked', false);
            $('body').on('click', '.multiComSelect', function() {
                if ($('html').find('.multiComSelect:checked').length)
                {
                    $('#multiComEditBtn').addClass('in').removeClass('hide');
                    if ($('html').find('.multiComSelect:not(:checked)').length == 0)
                        $('#selectAllchk').prop('checked', true);
                } else {
                    $('#multiComEditBtn').addClass('hide').removeClass('in');
                    $('#selectAllchk').prop('checked', false);
                }
            });
            // Generate Reference Qr code for account


        });
        function multiComInit() {

        }
        function beforeMultipleEdit()
        {

        }

        $(function() {
            $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
        });
        //------------Select All check----------
        $('body').on('click', '#selectAllchk', function() {
            if ($(this).is(':checked')) {

                $('.multiComSelect').prop('checked', true);
                $('#multiComEditBtn').addClass('in').removeClass('hide');
            } else {

                $('.multiComSelect').prop('checked', false);
                $('#multiComEditBtn').addClass('hide').removeClass('in');
            }
        });
        $('#multiComEditBtn').on('click', function() {
            var ids = [];
            var cat_ids = [];
            $('#item_table').find('input[type="checkbox"]:checked').each(function() {
                ids.push($(this).attr('value'));
                cat_ids.push($('#category_id_' + $(this).attr('value')).attr('value'));
            });
            console.log(ids);
            console.log(cat_ids);
            var category_ids = (unique(cat_ids));
            showCustomeField(category_ids);
            $('#multiComIds').val(ids.join(','));
            $('#multiComplianceEditModal').find('select option[value=""]').prop('selected', true);
            $('#multiComplianceEditModal').find('#item_warranty').val('');
            $('#multiComplianceEditModal').modal('show');
        });
        function showCustomeField(cat_id) {


            $('#custom_header').html('');
            $('#custom_header').html('<img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span>');
            $.ajax({
                url: '<?php echo base_url('items/getCustomFields'); ?>',
                type: 'post',
                data: {cat_ids: cat_id},
                success: function(data) {
                    $('#custom_header').html('');
                    data = JSON.parse(data);
                    $.each(data, function(k, v) {
                        var html_content = '<div class="row"><label class="col-md-4" >' + v['name'] + '</label><input type="text" name="custom_' + v['id'] + '" "></div>';
                        $(html_content).appendTo('#custom_header');
                    });
                },
                error: function(data) {
                }
            });
        }
        function unique(array) {
            return array.filter(function(el, index, arr) {
                return index == arr.indexOf(el);
            });
        }


    </script>
       
        
    <!--  Modal For Add Items  -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_item" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                    <h4 id="myModalLabel" class="modal-title">Add Item</h4>
                </div>

                <form action="<?php echo base_url() . 'items/addmultiple' ?>" method="POST" id="add_item_form"  enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Item Details -->
                        <div class="form-group col-md-12">
                            <div class="col-md-6"><label><h4>Item Details</h4></label> </div>
                        </div> 
                        <div class="form-group col-md-12">
                            <div class="col-md-6">  <label>Category*</label> </div>
                            <div class="col-md-6">  <select name="category_id" id="category_id" class="form-control">
                                    <option value="0">Select</option>
                                <?php
                                foreach ($arrCategories['results'] as $arrCategory) {
                                    echo "<option ";
                                    echo 'value="' . $arrCategory->categoryid . '" ';
                                    if ($intCategoryId == $arrCategory->categoryid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrCategory->categoryname . "</option>\r\n";
                                }
                                ?>
                                        </select>
                                    </div></div> <!-- /.form-group -->
                                <div class="form-group col-md-12">
                                    <div class="col-md-6">         <label>Item/Manu*</label> </div> 
                                    <div class="col-md-6">  <select name="manu" id="manu" class="form-control">
                                <?php foreach ($arrItemManu['list'] as $item) { ?>
                                                                                                                                                                                                                                                                <option value="<?php echo $item['item_manu_name']; ?>"><?php echo $item['item_manu_name']; ?></option>
                            <?php } ?>
                                                </select></div>

                                        </div> <!-- /.form-group -->
                                        <div class="form-group col-md-12">
                                            <div class="col-md-6"><label>OR Type New/Item Manu</label> </div>
                                            <div class="col-md-6"><input placeholder="Enter new item" class="form-control" name="new_item" id="new_item"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="col-md-6">        <label>Manufacturer*</label> </div>

                                            <div class="col-md-6"> <select name="manufacturer" id="manufacturer" class="form-control">
                                <?php foreach ($arrManufaturer as $manufacturer) { ?>
                                                                                                                                                                                                                                                        <option value="<?php echo $manufacturer['manufacturer_name']; ?>"><?php echo $manufacturer['manufacturer_name']; ?></option>
                                <?php } ?>
                                                  
                                                        </select> </div>
                                                </div> 
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6"><label>OR Type New Manufacturer</label> </div>
                                                    <div class="col-md-6"><input placeholder="Enter manufacturer" class="form-control" name="item_make" id="item_make"></div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">            <label>Model</label></div>
                                                    <div class="col-md-6">
                                                        <input placeholder="Enter Model" name="item_model" id="item_model" class="form-control">
                                                    </div>
                                                </div> 
                                               
                                                <div class="form-group col-md-12" id="quan">
                                                </div>
                                                <!-- Item ID -->
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6"><label><h4>Item ID</h4></label> </div>
                                                </div> 
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">  <label>Enter QR Code*</label> </div>
                                                    <div class="col-md-6">  <input placeholder="Enter QR Code" class="form-control" name="item_barcode" id="item_barcode">
                                                        <div id="qrcode_error" class="qrcode_error hide">QR Code Already Exist.</div>
                                                    </div>
                                                </div> <!-- /.form-group -->
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">  <label>Enter Serial No</label> </div>
                                                    <div class="col-md-6">  <input placeholder="Enter Serial No" class="form-control" name="item_serial_number" id="item_serial_number">
                                                    </div>
                                                </div> <!-- /.form-group -->
                                                <div class="form-group col-md-12">
                                                </div>
                                                <!-- Quality -->
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6"><label><h4>Quality</h4></label></div>
                                                </div> 
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">  <label>Status</label> </div>
                                                    <div class="col-md-6">  <select name="status_id" id="status_id" class="form-control">
                                <?php
                                foreach ($arrItemStatuses['results'] as $arrStatus) {
                                    echo "<option ";
                                    echo 'value="' . $arrStatus->statusid . '" ';
                                    if ($intItemStatusId == $arrStatus->statusid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrStatus->statusname . "</option>\r\n";
                                }
                                ?>
                                                        </select>
                                                    </div>
                                                </div> <!-- /.form-group -->
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">          <label>Condition</label>
                                                    </div>
                                                    <div class="col-md-6"><select name="item_condition" id="item_condition" class="form-control">
                                                            <option>----SELECT----</option>  
                                <?php
                                foreach ($arrCondition as $arrConn) {
                                    ?>
                                                                                                                                                                                    <option value="<?php echo $arrConn['id']; ?>"><?php echo $arrConn['condition']; ?></option>                     
                                    <?php
                                }
                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                </div>
                                                <!-- Ownership -->
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">      <label>Owner*</label>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <select name="owner_id" id="owner_id" class="form-control">
                                                            <option value="0">Not Set</option>
                                <?php
                                foreach ($arrUsers['results'] as $arrUser) {
                                    echo "<option ";
                                    echo 'value="' . $arrUser->userid . '" ';
                                    if ($intUserId == $arrUser->userid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrUser->userfirstname . " " . $arrUser->userlastname . "</option>\r\n";
                                }
                                ?>
                                                                </select></div>
                                                        </div> 
                                                        <div class="form-group col-md-12">
                                                            <div class="col-md-6"> <label>Site*</label>
                                                            </div>
                                                            <div class="col-md-6">       
                                                                <select name="site_id" id="site_id" class="form-control">
                                                                    <option value="0">Not Set</option>
                                <?php
                                foreach ($arrSites['results'] as $arrSite) {
                                    echo "<option ";
                                    echo 'value="' . $arrSite->siteid . '" ';
                                    if ($intSiteId == $arrSite->siteid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrSite->sitename . "</option>\r\n";
                                }
                                ?>
                                                                </select>
                                                            </div>
                                                        </div> 

                                                        <div class="form-group col-md-12">
                                                            <div class="col-md-6"> <label>Location*</label>
                                                            </div>
                                                            <div class="col-md-6">       
                                                                <select name="location_id" id="location_id" class="form-control">
                                                                    <option value="0">Not Set</option>
                                <?php
                                foreach ($arrLocations['results'] as $arrLocation) {
                                    echo "<option ";
                                    echo 'value="' . $arrLocation->locationid . '" ';
                                    if ($intLocationId == $arrLocation->locationid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrLocation->locationname . "</option>\r\n";
                                }
                                ?>
                                                                        </select>
                                                                    </div>
                                                                </div> 

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"><label>Supplier</label>
                                                                    </div>
                                                                    <div class="col-md-6"><select name="supplier" id="supplier" class="form-control">
                                                                            <option value="">Please Select</option>
                                <?php
                                foreach ($arrSuppliers as $supplier) {
                                    echo "<option ";
                                    echo 'value="' . $supplier['supplier_id'] . '" ';
                                    if ($supplier_id == $supplier['supplier_id']) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $supplier['supplier_title'] . "</option>\r\n";
                                }
                                ?>
                                                                        </select></div>
                                                                </div> 
                                                                <div class="form-group col-md-12">
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6">
                                                                        <label><h4>Item Dates</h4></label>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6">
                                                                        <label>Purchase Date</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input placeholder="Enter Purchase Date" class="form-control datepicker" name="item_purchased" id="item_purchased" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6">
                                                                        <label>Warranty Expiry</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input placeholder="Enter Expiry Date" class="form-control datepicker" name="add_warranty_date" id="add_warranty_date" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6">
                                                                        <label>Replacement Due</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input placeholder="Enter Replacement Date" class="form-control datepicker" name="item_replace" id="item_replace" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6">
                                                                        <label><h4>Valuation</h4></label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"><label>Purchase Price</label>
                                                                    </div>

                                                                    <div class="col-md-6">       
                                                                        <input placeholder="Enter Purchase Price" class="form-control" name="item_value" id="item_value" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"> <label> Current Value </label>
                                                                    </div>
                                                                    <div class="col-md-6">       
                                                                        <input placeholder="Enter Current Value" class="form-control" name="item_current_value" id="item_current_value" type="text">

                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6">
                                                                        <label><h4>Custom Fields</h4></label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12" id="custom_fields">

                                                                </div> 


                                                                <div class="form-group col-md-12">
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"><label>Notes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-12"><textarea placeholder="Enter Notes" class="form-control" name="item_notes" id="item_notes" cols="10" rows="2"></textarea>  
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"> <label>Documents</label>
                                                                    </div>
                                                                    <div class="col-md-6">       
                                                                        Choose File <input class="fileupload" name="pdf_file" id="pdf_file" type="file" value="UPLOAD">  
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"> <label>Photo</label>
                                                                    </div>
                                                                    <div class="col-md-6">       
                                                                        Choose File <input class="fileupload" type="file" name="photo_file" size="20" class="upload">

                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"> <label>PAT Test Date</label>
                                                                    </div>
                                                                    <div class="col-md-6">       
                                                                        <input placeholder="Enter PAT Test Date" class="form-control datepicker" name="item_pattestdate" id="item_pattestdate" type="text">  
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"> <label>PAT Test Result</label>
                                                                    </div>
                                                                    <div class="col-md-6">    
                                                                        <select name="item_patteststatus">
                                                                        <option value="-1" <?php
                                if ($intPatTestStatus === null) {
                                    echo "selected=\"selected\"";
                                }
                                ?>>Unknown</option>
                <option value="1" <?php
                                if ($intPatTestStatus === "1") {
                                    echo "selected=\"selected\"";
                                }
                                ?>>Pass</option>
                <option value="0" <?php
                                if ($intPatTestStatus === "0") {
                                    echo "selected=\"selected\"";
                                }
                                ?>>Fail</option>
                <option value="5" <?php
                                if ($intPatTestStatus === "5") {
                                    echo "selected=\"selected\"";
                                }
                                ?>>Not Required</option>
                                                                        </select>                </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                </div>
                                                               
                                                               
                                                            </div>
                   

                                                            <div class="modal-footer">
                                                                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                                                <button class="btn btn-primary" type="submit" id="save_button">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>

    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_similar_item" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                    <h4 id="myModalLabel" class="modal-title">Add Similar Item</h4>
                </div>

                
                    <div class="modal-body">
                        
                        <form action="<?php echo base_url() . 'items/addSimilarItem/' ?>" method="POST" id="add_similaritem_form"  enctype="multipart/form-data">
                          
                                                 <div class="form-group col-md-12">
                                                    <div class="col-md-6"><label><h4>Item ID</h4></label> </div>
                                                     
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">  <label>Enter QR Code*</label> </div>
                                        <div class="col-md-6">  <input placeholder="Enter QR Code" class="form-control" name="item_barcode_similar" id="item_barcode_similar">
                                                        <div id="qrcodeerror_similar" class="qrcodeerror hide">QR Code Already Exist.</div>
                                                    </div>
                                                </div> <!-- /.form-group -->
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6">  <label>Enter Serial No</label> </div>
                                                    <div class="col-md-6">  <input placeholder="Enter Serial No" class="form-control" name="item_serial_number_similar" id="item_serial_number_similar">
                                                    </div>
                                                </div> <!-- /.form-group -->
                                                <div class="form-group col-md-12">
                                                </div>
                                                <!-- Quality -->
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6"><label><h4>Item  Details</h4></label> </div>
                                                </div> 
                                                  <div class="form-group col-md-12">
                                                    <div class="col-md-6">
                                                        <label>Quantity</label>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <input placeholder="Enter Quantity" name="item_quantity_similar" id="item_quantity_similar" class="form-control">

                                                    </div>
                                                </div> <!-- /.form-group -->
                                             
                                                <div class="form-group col-md-12">
                                                    <div class="col-md-6"><label><h4>Ownership</h4></label> </div>
                                                </div> 
                                                <!-- Ownership -->
                                                      <div class="form-group col-md-12">
                                                    <div class="col-md-6">      <label>Owner*</label>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <select name="owner_id_similar" id="owner_id_similar" class="form-control">
                                                            <option value="0">Not Set</option>
                                <?php
                                foreach ($arrUsers['results'] as $arrUser) {
                                    echo "<option ";
                                    echo 'value="' . $arrUser->userid . '" ';
                                    if ($intUserId == $arrUser->userid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrUser->userfirstname . " " . $arrUser->userlastname . "</option>\r\n";
                                }
                                ?>
                                                                </select></div>
                                                        </div> 
                                                        <div class="form-group col-md-12">
                                                            <div class="col-md-6"> <label>Site*</label>
                                                            </div>
                                                            <div class="col-md-6">       
                                                                <select name="site_id_similar" id="site_id_similar" class="form-control">
                                                                    <option value="0">Not Set</option>
                                <?php
                                foreach ($arrSites['results'] as $arrSite) {
                                    echo "<option ";
                                    echo 'value="' . $arrSite->siteid . '" ';
                                    if ($intSiteId == $arrSite->siteid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrSite->sitename . "</option>\r\n";
                                }
                                ?>
                                                                </select>
                                                            </div>
                                                        </div> 

                                                        <div class="form-group col-md-12">
                                                            <div class="col-md-6"> <label>Location*</label>
                                                            </div>
                                                            <div class="col-md-6">       
                                                                <select name="location_id_similar" id="location_id_similar" class="form-control">
                                                                    <option value="0">Not Set</option>
                                <?php
                                foreach ($arrLocations['results'] as $arrLocation) {
                                    echo "<option ";
                                    echo 'value="' . $arrLocation->locationid . '" ';
                                    if ($intLocationId == $arrLocation->locationid) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $arrLocation->locationname . "</option>\r\n";
                                }
                                ?>
                                                                        </select>
                                                                    </div>
                                                                </div> 

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"><label>Supplier</label>
                                                                    </div>
                                                                    <div class="col-md-6"><select name="supplier_similar" id="supplier_similar" class="form-control">
                                                                            <option value="">Please Select</option>
                                <?php
                                foreach ($arrSuppliers as $supplier) {
                                    echo "<option ";
                                    echo 'value="' . $supplier['supplier_id'] . '" ';
                                    if ($supplier_id == $supplier['supplier_id']) {
                                        echo 'selected="selected" ';
                                    }
                                    echo '>' . $supplier['supplier_title'] . "</option>\r\n";
                                }
                                ?>
                                                                        </select></div>
                                                                </div> 
                                                                <div class="form-group col-md-12">
                                                                </div>

                                                             
                                                 <div class="form-group col-md-12">
                                                    <div class="col-md-6">          <label>Condition</label>
                                                    </div>
                                                    <div class="col-md-6"><select name="item_condition_similar" id="item_condition_similar" class="form-control">
                                                            <option>----SELECT----</option>  
                                <?php
                                foreach ($arrCondition as $arrConn) {
                                    ?>
                                                                                                                                                                                    <option value="<?php echo $arrConn['id']; ?>"><?php echo $arrConn['condition']; ?></option>                     
                                    <?php
                                }
                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6">
                                                                        <label>Purchase Date</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input placeholder="Enter Purchase Date" class="form-control datepicker" name="item_purchased_similar" id="item_purchased_similar" type="text">
                                                                    </div>
                                                                </div>
                                                                
                                                               

                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-6"><label>Purchase Price</label>
                                                                    </div>

                                                                    <div class="col-md-6">       
                                                                        <input placeholder="Enter Purchase Price" class="form-control" name="item_value_similar" id="item_value_similar" type="text">
                                                                    </div>
                                                               <input type="hidden" readonly="" name="itemID" id="itemID">
                                                                </div>

                                                             
                                                        
                                                               
                                                                
                                               
                                                             
                                                            </div>

                                                      
          <div class="modal-footer">
                                                                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                                                <button class="btn btn-primary" type="submit" id="similar_item">Save</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
    <script type="text/javascript">
        /* When Category is checked, see if category is a quantity category */
        $(function() {
            $('#category_id').change(function() {

                /* Quantity category check */
                $.getJSON("/youaudit/iwa/categories/checkCategory/" + $('#category_id').val(), function(data) {
               
                    if (data.name == 1) {
                        $("#quan").empty();

                        str = ('<div class="col-md-6">' +
                                '<label>' + "Quality" + '</label>' +
                                '</div>' +
                                '<div class="col-md-6">' + '<input type="input" placeholder="Enter Quantity" class="form-control" id="item_quantity" name="item_quantity"' +
                                '</div>');
                        $("#quan").append(str);

                    }
                    else
                    {
                        $("#quan").empty();
                    }
                });
                /* Custom Fields call */
                $.getJSON("/youaudit/iwa/categories/getCustomFields/" + $('#category_id').val(), function(data) {
                    $('#custom_fields').empty();
                    for (var i = 0; i < data.length; i++) {
                        $('#custom_fields').append('<div class="form_row">' +
                                '<label id="label_' + data[i].id + '" for="' + data[i].id + '">' + data[i].field_name + '</label>' +
                                '<input type="input" class="form-control" id="' + data[i].id + '" name="' + data[i].id + '"' +
                                '</div>');
                    }
                });
            });
        });</script>
                                            <script type="text/javascript">
                                                function filter()
                                                {


                                                    url = '';
                                                    var filter_barcode = $('input[name=\'filter_barcode\']').attr('value');
                                                    if (filter_barcode) {
                                                        url += '/fr_barcode_start/' + encodeURIComponent(filter_barcode);
                                                    }

                                                    var filter_serial_number = $('input[name=\'filter_serial_number\']').attr('value');
                                                    if (filter_serial_number) {
                                                        url += '/fr_serial_start/' + encodeURIComponent(filter_serial_number);
                                                    }

                                                    var filter_manufacturer = $('select[name=\'filter_manufacturer\']').attr('value');
                                                    if (filter_manufacturer) {
                                                        url += '/fr_manufacturer_exact/' + encodeURIComponent(filter_manufacturer);
                                                    }

                                                    var filter_item_manu = $('select[name=\'filter_item_manu\']').attr('value');
                                                    if (filter_item_manu) {

                                                        url += '/fr_itemmanuname_exact/' + encodeURIComponent(filter_item_manu);
                                                    }

                                                    var filter_categoryname = $('select[name=\'filter_categoryname\']').attr('value');
                                                    if (filter_categoryname) {
                                                        url += '/fr_categoryname_exact/' + encodeURIComponent(filter_categoryname);
                                                    }

                                                    var filter_itemstatusid = $('select[name=\'filter_itemstatusid\']').attr('value');
                                                    if (filter_itemstatusid) {
                                                        url += '/fr_itemstatusid_exact/' + encodeURIComponent(filter_itemstatusid);
                                                    }

                                                    var filter_sitename = $('select[name=\'filter_sitename\']').attr('value');
                                                    if (filter_sitename) {
                                                        url += '/fr_sitename_exact/' + encodeURIComponent(filter_sitename);
                                                    }

                                                    var filter_locationid = $('select[name=\'filter_locationid\']').attr('value');
                                                    if (filter_locationid) {
                                                        url += '/fr_locationid_exact/' + encodeURIComponent(filter_locationid);
                                                    }

                                                    var filter_userid = $('select[name=\'filter_userid\']').attr('value');
                                                    if (filter_userid) {
                                                        url += '/fr_userid_exact/' + encodeURIComponent(filter_userid);
                                                    }

                                                    var filter_patstatus = $('select[name=\'filter_patstatus\']').attr('value');
                                                    if (filter_patstatus) {
                                                        url += '/fr_patid_exact/' + encodeURIComponent(filter_patstatus);
                                                    }
                                                    var filter_constatus = $('select[name=\'filter_constatus\']').attr('value');
                                                    if (filter_constatus) {
                                                        url += '/fr_conid_exact/' + encodeURIComponent(filter_constatus);
                                                    }

                                                    return url;
                                                }

                                                function pagination()
                                                {
                                                    url = '';
                                                    var page_limit = $('select[name=\'page_limit\']').attr('value');
                                                    if (page_limit) {
                                                        url += '/pg_limit/' + encodeURIComponent(page_limit);
                                                        var base_url = $("#base_url").val();

                                                        $.ajax({
                                                            type: "POST",
                                                            url: base_url,
                                                            success: function(data) {

                                                                var itemdata = $.parseJSON(data);

                                                            }
                                                        });
                                                    }
                                                    return url;
                                                }

                                                function changelist(mode, field)
                                                {
                                                    console.log(mode);
                                                    order = '<?php echo $strOrderField; ?>';
                                                    direction = '<?php echo $strOrderDirection; ?>';
                                                    url = '<?php echo base_url('/items/filter/'); ?>';
                                                    if (mode == 'order')
                                                    {
                                                        url += '/or_' + encodeURIComponent(field);
                                                        if ((order == field) && direction == 'asc')
                                                        {
                                                            url += '/desc';
                                                        }
                                                        else
                                                        {
                                                            url += '/asc';
                                                        }

                                                    }
                                                    else
                                                    {
                                                        if (order != 'FALSE')
                                                        {
                                                            url += '/or_' + encodeURIComponent(order) + '/' + direction;
                                                        }
                                                        if ((mode == 'page') && field)
                                                        {
                                                            url += '/pg_page/' + field;
                                                        }

                                                    }
                                                    url += filter();
                                                    url += pagination();
                                                    location = url;
                                                }


                                                $(document).ready(function() {
                                                    $('body').find('.multiComSelect:checked').prop('checked', false);
                                                    $('body').find('#selectAllchk').prop('checked', false);
                                                    $('body').on('click', '.multiComSelect', function() {
                                                        if ($('html').find('.multiComSelect:checked').length)
                                                        {
                                                            $('#multiComEditBtn').addClass('in').removeClass('hide');
                                                            if ($('html').find('.multiComSelect:not(:checked)').length == 0)
                                                                $('#selectAllchk').prop('checked', true);
                                                        } else {
                                                            $('#multiComEditBtn').addClass('hide').removeClass('in');
                                                            $('#selectAllchk').prop('checked', false);
                                                        }
                                                    });
                                                });
                                                function multiComInit() {

                                                }
                                                function beforeMultipleEdit()
                                                {

                                                }

//                                            $(function() {
//                                                $(".datepicker").datepicker({dateFormat: "dd-mm-yy"});
//                                            });
//------------Select All check----------
                                                $('body').on('click', '#selectAllchk', function() {
                                                    if ($(this).is(':checked')) {

                                                        $('.multiComSelect').prop('checked', true);
                                                        $('#multiComEditBtn').addClass('in').removeClass('hide');
                                                    } else {

                                                        $('.multiComSelect').prop('checked', false);
                                                        $('#multiComEditBtn').addClass('hide').removeClass('in');
                                                    }
                                                });
                                                $('#multiComEditBtn').on('click', function() {
                                                    var ids = [];
                                                    var cat_ids = [];
                                                    $('#item_table').find('input[type="checkbox"]:checked').each(function() {
                                                        ids.push($(this).attr('value'));
                                                        cat_ids.push($('#category_id_' + $(this).attr('value')).attr('value'));
                                                    });
                                                    console.log(ids);
                                                    console.log(cat_ids);
                                                    var category_ids = (unique(cat_ids));
                                                    showCustomeField(category_ids);
                                                    $('#multiComIds').val(ids.join(','));
                                                    $('#multiComplianceEditModal').find('select option[value=""]').prop('selected', true);
                                                    $('#multiComplianceEditModal').find('#itemwarranty').val('');
                                                    $('#multiComplianceEditModal').modal('show');
                                                });
                                                function showCustomeField(cat_id) {


                                                    $('#custom_header').html('');
                                                    $('#custom_header').html('<img width="15" src="<?php echo base_url('../img/ajax-loader.gif'); ?>"><span>&nbsp;&nbsp;Please Wait...</span>');
                                                    $.ajax({
                                                        url: '<?php echo base_url('items/getCustomFields'); ?>',
                                                        type: 'post',
                                                        data: {cat_ids: cat_id},
                                                        success: function(data) {
                                                            $('#custom_header').html('');
                                                            data = JSON.parse(data);
                                                            $.each(data, function(k, v) {
                                                                var html_content = '<div class="row"><label class="col-md-4" >' + v['name'] + '</label><input type="text" name="custom_' + v['id'] + '" "></div>';
                                                                $(html_content).appendTo('#custom_header');
                                                            });
                                                        },
                                                        error: function(data) {
                                                        }
                                                    });
                                                }
                                                function unique(array) {
                                                    return array.filter(function(el, index, arr) {
                                                        return index == arr.indexOf(el);
                                                    });
                                                }

</script>
<script>
    $(document).ready(function() {

        $(".datepicker").datepicker({dateFormat: "dd/mm/yy"});
        $("#add_item_form").validate({
            rules: {
                category_id: {required: true, min: 1},
                item_model: {required: true},
                item_quantity: {required: true},
                item_barcode: "required",
                item_serial_number: "required",
                status_id: {required: true, min: 1},
                item_condition: {required: true, min: 1},
                owner_id: {required: true, min: 1},
                site_id: {required: true, min: 1},
                location_id: {required: true, min: 1},
                supplier: {required: true, min: 1},
                item_patteststatus: {required: true, min: 1},
                item_purchased: "required",
                add_warranty_date: "required",
                item_replace: "required",
                item_value: "required",
                asset_type: "required",
                drill_bits: "required",
                accessories: "required",
                item_notes: "required",
                item_pattestdate: "required",
            },
            messages: {
                category_id: "Please Select Category",
                item_model: "Please Select Model",
                item_quantity: "Please Select Quantity",
                item_barcode: "Please Enter QR_code",
                item_serial_number: "Please Enter Serial Number",
                status_id: "Please Enter Status",
                item_condition: "Please Select Condition",
                owner_id: "Please Select Owner",
                site_id: "Please Select Site",
                location_id: "Please Select Location",
                supplier: "Please Select Supplier",
                item_purchased: "Please Enter Purchase Date",
                add_warranty_date: "Please Enter Expiry Date",
                item_replace: "Please Enter Replacement Date",
                item_value: "Please Enter Purchase Price",
                item_current_value: "Please Enter Current Value",
                asset_type: "Please Enter Asset Type",
                drill_bits: "Please Enter Drill Bits",
                accessories: "Please Enter Accessories",
                item_notes: "Please Enter Notes",
                item_pattestdate: "Please Enter PAT Test Date",
                item_patteststatus: "Please Enter PAT Test Result"
            }
        });


        $("#add_similaritem_form").validate({
            rules: {
                item_quantity_similar: {required: true},
                item_barcode_similar: "required",
                item_serial_number_similar: "required",
                status_id_similar: {required: true, min: 1},
                owner_id_similar: {required: true, min: 1},
                site_id_similar: {required: true, min: 1},
                location_id_similar: {required: true, min: 1},
                supplier_similar: {required: true, min: 1},
                item_value_similar: "required",
                item_condition_similar: {required: true, min: 1},
                item_purchased_similar: "required",
            },
            messages: {
                item_quantity_similar: "Please Select Quantity",
                item_barcode_similar: "Please Enter QR_code",
                item_serial_number_similar: "Please Enter Serial Number",
                status_id_similar: "Please Enter Status",
                item_condition_similar: "Please Select Condition",
                owner_id_similar: "Please Select Owner",
                site_id_similar: "Please Select Site",
                location_id_similar: "Please Select Location",
                supplier_similar: "Please Select Supplier",
                item_value_similar: "Please Enter Purchase Price",
                item_purchased_similar: "Please Enter Purchase Date",
            },
        });

        // script for change password
        $("body").on("click", ".add_similar", function() {
            $(".result").empty();
            var item_id = $(this).attr("data_item_id");

            $("#itemID").attr("value", item_id);
        });

        // code to check unique barcode

        $("#item_barcode").on("keyup blur", function() {

            var bar_code = $("#item_barcode").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "items/checkQrcode",
                data: {
                    'bar_code': bar_code
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#save_button").addClass('disabled');
                        $("#qrcode_error").removeClass("hide");
                    } else {
                        $("#save_button").removeClass('disabled');
                        $("#qrcode_error").addClass("hide");
                    }
                }

            });
        });
        // code to check unique barcode

        $("#item_barcode_similar").on("keyup blur", function() {

            var bar_code = $("#item_barcode_similar").val();
            var base_url_str = $("#base_url").val();

            $.ajax({
                type: "POST",
                url: base_url_str + "items/checkQrcode",
                data: {
                    'bar_code': bar_code
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == "1") {
                        //Receiving the result of search here
                        $("#similar_item").addClass('disabled');
                        $("#qrcodeerror_similar").removeClass("hide");
                    } else {
                        $("#similar_item").removeClass('disabled');
                        $("#qrcodeerror_similar").addClass("hide");
                    }
                }

            });
        });
        
          $(document).find('#site_id').change(function(){
      alert('Hello')
    });
    });
    </script>
    
<style>
    .modal-body
    {
        height: 595px;
        overflow-y: scroll;
    }
    .qrcode_error
    {
        color: red;
        font-weight: bold;
    }
    .qrcodeerror
    {
        color: red;
        font-weight: bold;
    }
</style>
