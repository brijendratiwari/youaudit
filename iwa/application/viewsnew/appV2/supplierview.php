
<h2><?php echo $objSupplier->supplier_title; ?></h2>

<ul class="field" id="item_data_holder">
    <li class="header">Supplier Details</li>
	<li><h3 style="width: 80px; text-align: left;">Address 1</h3><?php echo $objSupplier->supplier_address1; ?></li>
	<li><h3 style="width: 80px; text-align: left;">Address 2</h3><?php echo $objSupplier->supplier_address2; ?></li>
	<li><h3 style="width: 80px; text-align: left;">Address 3</h3><?php echo $objSupplier->supplier_address3; ?></li>
	<li><h3 style="width: 80px; text-align: left;">Town/City</h3><?php echo $objSupplier->supplier_town; ?></li>
	<li><h3 style="width: 80px; text-align: left;">County/State</h3><?php echo $objSupplier->supplier_county; ?></li>
	<li><h3 style="width: 80px; text-align: left;">Postcode</h3><?php echo $objSupplier->supplier_postcode; ?></li>
    <li><h3 style="width: 80px; text-align: left;">Website</h3><a href="<?php echo $objSupplier->supplier_website; ?>"><?php echo $objSupplier->supplier_website; ?></a></li>
    <li><h3 style="width: 80px; text-align: left;">E-mail</h3><a href="mailto:<?php echo $objSupplier->supplier_email; ?>"><?php echo $objSupplier->supplier_email; ?></a></li>
    <li><h3 style="width: 80px; text-align: left;">Telephone</h3><a href="tel:<?php echo $objSupplier->supplier_telephone; ?>"><?php echo $objSupplier->supplier_telephone; ?></a></li>
    <li><h3 style="width: 80px; text-align: left;">Contact</h3><?php echo $objSupplier->supplier_contact_name; ?></li>
    <li><h3 style="width: 80px; text-align: left;">Email</h3><?php echo $objSupplier->supplier_contact_email; ?></li>
</ul>
