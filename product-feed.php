<?php
header('Content-Type: text/csv; charset=UTF-8');

$args = array('post_type' =>'product', 'showposts'=>1000);

$products = get_posts($args);

$productArray[] = ['sku', 'name', 'image_url', 'link', 'mpn'];

foreach ($products as $product)
{

	$_pf      = new WC_Product_Factory();
	$_product = $_pf->get_product($product->ID);

	$sku        = $_product->get_sku();
	$image_link = '';

	$image_id = $_product->get_image_id();

	if ($image_id)
	{
		$image_url = wp_get_attachment_url($image_id);
	}

	if ($_product->product_type == 'simple')
	{
		$productArray[] = [$sku, $product->post_title, $image_url, $_product->post->guid, $sku];
	}
	else if ($_product->product_type == 'variable')
	{
		$available_variations = $_product->get_available_variations();

		foreach ($available_variations as $variation)
		{
			$productArray[] = [$variation['sku'], $product->post_title, $image_url, $_product->post->guid, $variation['sku']];
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
