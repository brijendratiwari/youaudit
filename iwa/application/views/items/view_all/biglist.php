    <?php 
                            if ($arrSessionData['objSystemUser']->levelid >1) 
                            {
                            ?><p class="right"><a href="<?php echo site_url('/items/addmultiple/'); ?>" class="button">Add Multiple Similar Items</a> <a href="<?php echo site_url('/items/addone/'); ?>" class="button">Add an Item</a></p><?php
                            }
                            ?>
       
	    <table class="full-wide">
		<tr class="header-row">
		    <td><a class="sorter" onclick="changelist('order', 'barcode');"><?php
                        if ($strOrderField == "barcode")
                        {
                            if ($strOrderDirection == "desc")
                            {
                                echo "<img src=\"/img/filter-arrow-down.png\" />";
                            }
                            else
                            {
                                echo "<img src=\"/img/filter-arrow-up.png\" />";
                            }
                                
                        }
                        else
                        {
                            echo "<img src=\"/img/filter-arrow.png\" />";
                        }    
                            
                        ?> Barcode</a></td>
                    <td style="width:60px;">&nbsp;</td>
		    <td><a class="sorter" onclick="changelist('order', 'manufacturer');"><?php
                        if ($strOrderField == "manufacturer")
                        {
                            if ($strOrderDirection == "desc")
                            {
                                echo "<img src=\"/img/filter-arrow-down.png\" />";
                            }
                            else
                            {
                                echo "<img src=\"/img/filter-arrow-up.png\" />";
                            }
                                
                        }
                        else
                        {
                            echo "<img src=\"/img/filter-arrow.png\" />";
                        }    
                            
                        ?> Manufacturer and Model</a></td>
		    
		    <td><a class="sorter" onclick="changelist('order', 'categoryname');"><?php
                        if ($strOrderField == "categoryname")
                        {
                            if ($strOrderDirection == "desc")
                            {
                                echo "<img src=\"/img/filter-arrow-down.png\" />";
                            }
                            else
                            {
                                echo "<img src=\"/img/filter-arrow-up.png\" />";
                            }
                                
                        }
                        else
                        {
                            echo "<img src=\"/img/filter-arrow.png\" />";
                        }    
                            
                        ?> Category</a></td>
                    <td>Status</td>
                    <?php 
                            if ($arrSessionData['objSystemUser']->levelid >1) 
                            {
                            ?>
                                 <td colspan="2"><a class="sorter" onclick="changelist('order', 'value');"><?php
                        if ($strOrderField == "value")
                        {
                            if ($strOrderDirection == "desc")
                            {
                                echo "<img src=\"/img/filter-arrow-down.png\" />";
                            }
                            else
                            {
                                echo "<img src=\"/img/filter-arrow-up.png\" />";
                            }
                                
                        }
                        else
                        {
                            echo "<img src=\"/img/filter-arrow.png\" />";
                        }    
                            
                        ?> Value</a></td>
                           <?php 
                            }
                        ?>
                    <td>Site</td>
                    <td style="width:60px;">&nbsp;</td>
		    <td>Owner</td>
		    <td>Location</td>
		    <td>Actions</td>
		</tr>
                <tr>
                    <td><input type="text" name="filter_barcode" value="<?php
                               if (array_key_exists('strbarcode', $arrFilters))
                               {
                                   echo $arrFilters['strbarcode'];
                               }
                               
                               ?>" style="width:60px; margin-bottom: 2px;" /></td>
                    <td></td>
                    <td><select name="filter_manufacturer">
	    <option value=""></option>
	    <?php
		foreach ($arrManufacturers as $arrManufacturer)
		{
		    echo "<option ";
		    echo 'value="'.$arrManufacturer.'" ';
                    if (array_key_exists('strmanufacturer', $arrFilters))
                    {
                        if ($arrFilters['strmanufacturer'] == $arrManufacturer)
                        {
                            echo 'selected="selected" ';
                        }
                    }
		    echo '>'.$arrManufacturer."</option>\r\n";
		}
	    ?>
	</select></td>
                    
                    <td><select name="filter_categoryname">
                            <option value=""></option>
                            <?php
                                foreach ($arrCategories['results'] as $arrCategory)
                                {
                                    echo "<option ";
                                    echo 'value="'.$arrCategory->categoryname.'" ';
                                    if (array_key_exists('strcategoryname', $arrFilters))
                                    {
                                            if ($arrFilters['strcategoryname'] == $arrCategory->categoryname)
                                            {
                                                echo 'selected="selected" ';
                                            }
                                    }
                                    echo '>'.$arrCategory->categoryname."</option>\r\n";
                                }
                            ?>
                        </select></td>
                    <td></td>
                    <?php 
                            if ($arrSessionData['objSystemUser']->levelid >1) 
                            {
                            ?>
                                 <td colspan="2"></td>
                           <?php 
                            }
                        ?>
                    <td><select name="filter_sitename">
                            <option value=""></option>
                            <?php
                                foreach ($arrFaculties['results'] as $arrSite)
                                {
                                    echo "<option ";
                                    echo 'value="'.$arrSite->sitename.'" ';
                                    if (array_key_exists('strsitename', $arrFilters))
                                    {
                                            if ($arrFilters['strsitename'] == $arrSite->sitename)
                                            {
                                                echo 'selected="selected" ';
                                            }
                                    }
                                    echo '>'.$arrSite->sitename."</option>\r\n";
                                }
                            ?>
                        </select></td>
                    <td></td>
                    <td><select name="filter_userid">
                            <option value=""></option>
                            <?php


                                foreach ($arrUsers['results'] as $arrUser)
                                {
                                    echo "<option ";
                                    echo 'value="'.$arrUser->userid.'" ';
                                    if (array_key_exists('struserid', $arrFilters))
                                    {
                                        if ($arrFilters['struserid'] == $arrUser->userid)
                                        {
                                            echo 'selected="selected" ';
                                        }
                                    }
                                    echo '>'.$arrUser->userfirstname." ".$arrUser->userlastname."</option>\r\n";
                                }
                            ?>
                        </select></td>
                    <td><select name="filter_locationid">
                            <option value=""></option>
                            <?php
                                foreach ($arrLocations['results'] as $arrLocation)
                                {
                                    echo "<option ";
                                    echo 'value="'.$arrLocation->locationid.'" ';
                                    if (array_key_exists('strlocationid', $arrFilters))
                                    {
                                        if ($arrFilters['strlocationid'] == $arrLocation->locationid)
                                        {
                                            echo 'selected="selected" ';
                                        }
                                    }
                                    echo '>'.$arrLocation->locationname."</option>\r\n";
                                }
                            ?>
                        </select></td>
                    <td><a onclick="changelist('filter', false);" class="button">Filter</a></td>
                </tr>
<?php 
    if (count($arrItemsData['results'])>0) 
    {

	foreach ($arrItemsData['results'] as $arrItem)
	{
	   
	?>
                
                
		<tr class="triple-height">
		    <td class="right-align"><a href="<?php echo site_url('/items/view/'.$arrItem->itemid.'/'); ?>"><pre><?php echo $arrItem->barcode; ?></pre></a></td>
                    <td class="centre-align"><?php if ($arrItem->itemphotoid > 1)
                {
                    echo "<img src=\"".site_url('/images/viewlist/'.$arrItem->itemphotoid)."\" title=\"".$arrItem->itemphototitle."\" />";
                }
                ?></td>
		    <td><?php echo $arrItem->manufacturer; ?> <?php echo $arrItem->model; ?></td>
		    
		    <td><?php echo $arrItem->categoryname; ?></td>
                    <td><?php echo $arrItem->statusname; ?></td>
                    <?php 
                            if ($arrSessionData['objSystemUser']->levelid >1) 
                            {
                            ?>
                    
                           
                                <td class="price-pound">&pound;</td>
                                <td class="price<?php if ($arrItem->value == 0) { echo " centre-align";} ?>"><?php
                                        if ($arrItem->value != 0)
                                        {
                                         echo $arrItem->value; 
                                        }
                                        else 
                                        {
                                        ?>-<?php
                                        }
                                        ?>
                                 </td>
                                
                           <?php 
                            }
                        ?>
                    <td class="centre-align"><?php
                        if ($arrItem->siteid > 0)
                        {
                            echo "<strong>".$arrItem->sitename."</strong>";
                        }
                        else
                        {
                            echo "-";
                        }
                        
                             ?></td>
                    <td><?php if (($arrItem->userid > 0) && ($arrItem->userphotoid > 1))
                {
                    echo "<img src=\"".site_url('/images/viewlist/'.$arrItem->userphotoid)."\" title=\"".$arrItem->userphototitle."\" />";
                }
                ?></td>
		    <td><?php 
                        if ($arrItem->userid > 0)
                        {
                           echo "<strong>".$arrItem->userfirstname." ".$arrItem->userlastname."</strong>"; 
                        }
                        else
                        {
                            echo "-";
                        }
                            ?></td>
		    <td><?php if ($arrItem->locationid > 0)
                        {
                           echo "<strong>".$arrItem->locationname."</strong>"; 
                        }
                        else
                        {
                            echo "-";
                        }
                        
                         ?></td>
		    <td width="55px"><a href="<?php echo site_url('/items/view/'.$arrItem->itemid.'/'); ?>"><img src="/img/icons/16/view.png" title="View Item" alt="View Item" /></a>
			<?php 
                            if ($arrItem->siteid == 0) 
                            {
                            ?>
                        <a href="<?php echo site_url('/items/changelinks/'.$arrItem->itemid.'/'); ?>"><img src="/img/icons/16/users.png" title="Change Links" alt="Change Links" /></a>
                        <?php
                            }
                        ?>
			<?php 
                            if ($arrSessionData['objSystemUser']->levelid >1) 
                            {
                            ?>
                                 <a href="<?php echo site_url('/items/edit/'.$arrItem->itemid.'/'); ?>"><img src="/img/icons/16/modify.png" title="Edit Item" alt="Edit Item" /></a>
                           <?php 
                            }
                        ?>
                           
		    
		    </td>
		</tr>
	<?php  
	}
	?>
	</table>
        
        <p style="text-align:right;">
        <?php if ($booDisplayPrevLink) { ?>
            <a onclick="changelist('page', <?php echo $intPrevPage; ?>);" title="Next" class="button" >&lt;&lt; Previous</a>
        <?php } ?>

        <?php if ($booDisplayNextLink) { ?>
            <a onclick="changelist('page', <?php echo $intNextPage; ?>);" title="Next" class="button" >Next &gt;&gt;</a>
        <?php } ?>
        </p>



        <p style="text-align:right; clear:both;"><strong><?php echo $intItemsDisplayCount; ?></strong> items displayed of <strong><?php echo $intItemsCount; ?></strong> found.</p>
        
        <h3>Settings</h3>
        <div class="form_row">
        <label for-="page_limit">Results per page?</label>
            <select name="page_limit">
                <option value="">10</option>
                <option value="20" <?php 
                                    
                                        if ($mixPageLimit == 20)
                                        {
                                            echo 'selected="selected" ';
                                        }
                                    
                                    ?>>20</option>
                <option value="40" <?php 
                                    
                                        if ($mixPageLimit == 40)
                                        {
                                            echo 'selected="selected" ';
                                        }
                                    
                                    ?>>40</option>
                <option value="none"<?php 
                                    
                                        if ($mixPageLimit == "none")
                                        {
                                            echo 'selected="selected" ';
                                        }
                                    
                                    ?>>all</option>
            </select>
        </div>
        <div class="form_row">
            <label for="submit">Ready?</label>
	    <input onclick="changelist('page', false);" class="button" type="button" value="Update" /> 
            
            <!--<a onclick="changelist('page', false);" class="button">Update</a>-->
        </div>
        
        
        <?php
    
} 
else
{
    echo "</table>";
}
    
?>
        <script type="text/javascript"><!--
function filter() 
{
	url = '';
	
	var filter_barcode = $('input[name=\'filter_barcode\']').attr('value');
	
	if (filter_barcode) {
		url += '/fr_barcode_start/' + encodeURIComponent(filter_barcode);
	}
        
        var filter_manufacturer = $('select[name=\'filter_manufacturer\']').attr('value');
	
	if (filter_manufacturer) {
		url += '/fr_manufacturer_exact/' + encodeURIComponent(filter_manufacturer);
	}
        
        var filter_categoryname = $('select[name=\'filter_categoryname\']').attr('value');
	
	if (filter_categoryname) {
		url += '/fr_categoryname_exact/' + encodeURIComponent(filter_categoryname);
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
        
	return url;
}

function pagination ()
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
    order = '<?php echo $strOrderField; ?>';
    direction = '<?php echo $strOrderDirection; ?>';
    url = '<?php echo site_url('/items/filter/'); ?>';
    
    if (mode == 'order')
    {
        url += '/or_'+encodeURIComponent(field);
        if ((order == field) && direction == 'asc' ) 
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
            url += '/or_'+ encodeURIComponent(order)+'/'+direction;
        }
        if ((mode == 'page') && field)
        {
            url += '/pg_page/'+field;
        }
        
    }   
    url += filter();
    url += pagination();
    location = url;
}

//--></script> 
