<ul class="data">
    <li><p>There are <?php echo count($arrResults); ?> search results</p></li>
</ul>

<ul>
<?php

foreach ($arrResults as $objItem)
{
?>
    <li class="arrow">
        <a href="#" onclick="isaItem_getItem('<?php echo $objItem->barcode; ?>',true);"><?php 
        
        echo $objItem->barcode.": ".$objItem->manufacturer." ".$objItem->model; ?></a></li><?php                
}
?>
</ul>