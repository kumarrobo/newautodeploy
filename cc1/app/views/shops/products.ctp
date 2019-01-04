<allProducts>
	<?php foreach($products as $product) { ?>
		<product>
			<id><?php echo $objMd5->encrypt($product['Product']['id'],encKey); ?></id>
			<name><?php echo $product['Product']['name']; ?></name>
			<price><?php echo $product['Product']['price']; ?></price>
			<validity><?php echo $product['Product']['validity']; ?></validity>
			<image><?php echo Router::url('/',true) . "img/retailProducts/" . strtolower($product['Product']['code']) . ".jpg"; ?></image>
			<allParams>
				<param>
					<field>Mobile</field>
					<type>VARCHAR</type>
					<length>10</length>
				</param>
				<?php if($product['Product']['id'] == PNR_PRODUCT) { ?>
				<param>
					<field>PNR Number</field>
					<type>VARCHAR</type>
					<length>10</length>
				</param>
				<?php } ?>
			</allParams>
		</product>
	<?php } ?>
</allProducts>