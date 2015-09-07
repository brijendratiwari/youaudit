<ul class="data">
    <li><p>There are <?php echo count($arrResults); ?> search results</p></li>
</ul>
<ul>
<?php

foreach ($arrResults as $objVehicle)
{
?>
    <li class="arrow">
        <a href="#" onclick="isaVehicle_getVehicle('<?php echo $objVehicle->fleetid; ?>',true);"><?php
        
        echo ($objVehicle->barcode ? $objVehicle->barcode . ": " : '').$objVehicle->make." ".$objVehicle->model; ?></a></li><?php
}
?>
</ul>