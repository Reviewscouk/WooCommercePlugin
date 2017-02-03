<?php
header('Content-Type: text/csv; charset=UTF-8');

$args = array('post_type' =>'product', 'showposts'=>10000);

$products = get_posts($args);

$productArray[] = ['sku', 'name', 'image_url', 'link', 'mpn', 'woocommerce_product_sku','woocommerce_product_id'];

foreach ($products as $product)
{

	$_pf      = new WC_Product_Factory();
	$_product = $_pf->get_product($product->ID);

	$woocommerce_sku = $_product->get_sku();
	$woocommerce_id = $product->ID;
	$sku    = get_option('product_identifier') == 'id'? $woocommerce_id : $woocommerce_sku;
	$image_link = '';

	$image_id = $_product->get_image_id();

	if ($image_id)
	{
		$image_url = wp_get_attachment_url($image_id);
	}

	// Always add the parent product
	$productArray[] = [$sku, $product->post_title, $image_url, get_permalink($product->ID), $sku, $woocommerce_sku, $woocommerce_id];

	// Add variants as additional products
	if ($_product->product_type == 'variable' && get_option('use_parent_product') != 1)
	{
		$available_variations = $_product->get_available_variations();

		foreach ($available_variations as $variation)
		{
			$variant_sku = get_option('product_identifier') == 'id'? $variation['variation_id'] : $variation['sku'];
			$variant_attributes = is_array($variation['attributes'])? implode(' ',  array_filter(array_values($variation['attributes']))) : '';
			$variant_title = $product->post_title;
			if(!empty($variant_attributes)){
				//$variant_title .= ' - '.$variant_attributes;
			}
			$productArray[] = [ $variant_sku, $variant_title, $image_url, get_permalink($product->ID), $variation['sku'], $variation['sku'], $variation['variation_id']];
		}
	}
}

$fp = fopen('php://temp', 'w+');
foreach ($productArray as $fields)
{
	fputcsv($fp, $fields);
}

rewind($fp);
$csv_contents = stream_get_contents($fp);
fclose($fp);

// Handle/Output your final sanitised CSV contents
echo $csv_contents;
