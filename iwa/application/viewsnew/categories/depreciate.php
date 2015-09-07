<div class="box">
    <div class="heading">
      	<h1>Depreciate All Items</h1>
        <div class="buttons">
            <a class="button" onclick="$('#depreciate_category_form').submit();">Save</a>
        </div>
    </div>
    <div class="box_content">
        <div class="content_main">

    <p>Use this form to depreciate all items.  The depreciation rate set at category level will be applied to the current system value of all items within each category on your account.  These rates are listed below, for reference.</p>
    <p><strong>Note:</strong> <em>You will not be able to undo this process, all values will be updated.</em></p>
    
    
    <?php 
    if (count($arrCategoriesData['results'])>0) 
    {
	?>
	    <table class="list_table">
                <thead>
                    <tr>
                        <th class="left">Name</th>
                       
                        <th class="right">Depreciation Rate (%)</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
	<?php
	foreach ($arrCategoriesData['results'] as $arrCategory)
	{
	   
	?>
                    <tr>

                        <td><?php echo $arrCategory->categoryname; ?></td>
                        
                        <td class="right"><?php if ($arrCategory->categorydepreciationrate > 0)
                                                {
                                                    echo "<strong>";
                                                }   
                                                echo $arrCategory->categorydepreciationrate; 
                                                if ($arrCategory->categorydepreciationrate > 0)
                                                {
                                                    echo "</strong>";
                                                }  
                                                ?></td>
                       
                    </tr>
	<?php  
	}
	?>
                </tbody>
	</table>
    <?php
    }
    else
    {
	?>
	<p>You don't have any categories added at present.</p>
	<?php
    }
    ?>
    
    
    
    <?php echo form_open('categories/depreciate/', array('id'=>'depreciate_category_form')); ?>