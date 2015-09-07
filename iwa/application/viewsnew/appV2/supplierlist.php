<ul class="data">
    <li><p>There are <?php echo (count($arrSuppliers) > 0 ? count($arrSuppliers) : '0'); ?> search results</p></li>
</ul>

<ul>
<?php foreach ($arrSuppliers as $supplier) {  ?>
	<li class="arrow">
		<a href="#" onclick="isaSuppliers_getSupplier('<?php echo $supplier['supplier_id']; ?>');">
		<?php print $supplier['supplier_title']; ?></a>
	</li>
<?php } ?>
</ul>