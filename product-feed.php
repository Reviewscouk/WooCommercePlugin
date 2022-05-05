<?php
if(!defined('ABSPATH')) {
  exit;
}

header('Content-Type: text/csv; charset=UTF-8');

$args = array('post_type' =>'product', 'showposts'=>10000);

$products = get_posts($args);

$productArray[] = array('sku', 'name', 'image_url', 'link', 'mpn', 'woocommerce_product_sku','woocommerce_product_id', 'barcode', 'category', 'categories');

foreach ($products as $product)
{

	$_pf      = new WC_Product_Factory();
	$_product = $_pf->get_product($product->ID);

	$woocommerce_sku = $_product->get_sku();
	$woocommerce_id = $product->ID;
	$sku    = get_option('REVIEWSio_product_identifier') == 'id'? $woocommerce_id : $woocommerce_sku;
	$image_link = '';

	$image_id = $_product->get_image_id();
	$image_url = '';

	if ($image_id)
	{
		$image_url = wp_get_attachment_url($image_id);
	}

	$categories = get_the_terms( $product->ID, 'product_cat' );
	$categories_string = [];

	foreach($categories as $cat){
		if (!empty($cat->name)){
			$categories_string[] = $cat->name;
		}
	}
	$categories_json = json_encode($categories_string);


	$categories_string = implode(', ', $categories_string);

	// Try to get barcode from meta, if nothing found, will return empty string
	$try = array('_barcode', 'barcode', '_gtin', 'gtin');

	$barcode = '';

	foreach($try as $t) {

		if(!empty($barcode)) break;

		$barcode = get_post_meta($product->ID, $t, true);

	}

	// Always add the parent product
	$productArray[] = array($sku, $product->post_title, $image_url, get_permalink($product->ID), $sku, $woocommerce_sku, $woocommerce_id, $barcode, $categories_string, $categories_json);

  $newFields = [];
  $customProductAttributes = array('_barcode', 'barcode', '_gtin', 'gtin', 'mpn', '_mpn');
  if (!empty(get_option('REVIEWSio_product_feed_custom_attributes'))) {
    $additionalCustomProductAttributes = get_option('REVIEWSio_product_feed_custom_attributes');
    $additionalCustomProductAttributes = explode(',', $additionalCustomProductAttributes);
    if(!empty($additionalCustomProductAttributes)) {
      foreach ($additionalCustomProductAttributes as $additionalCustomProductAttribute) {
        if(!in_array($additionalCustomProductAttribute, $customProductAttributes)) {
          $customProductAttributes[] = trim($additionalCustomProductAttribute);
        }
      }
    }
  }
  foreach($_product->get_attributes() as $productAttribute) {
      if(in_array($productAttribute['name'], $customProductAttributes)) {
          $newFields[$productAttribute['name']] = $productAttribute['options'][0];
      }
  }
  //Add any matching attributes to product feeds and update existing columns
  if(!empty($newFields)) {
      foreach ($newFields as $columnName => $columnValue) {
          $insertAtColumnIndex = false;
          //Insert column name if does not exist or get the column index
          if(!in_array($columnName, $productArray[0])) {
              $productArray[0][] = $columnName;
          } else {
              $insertAtColumnIndex = array_search($columnName, $productArray[0]);
          }
          //If colummn already exists check and update existing value else add to end
          $newProductLine = $productArray[count($productArray)-1];
          if(!empty($insertAtColumnIndex)) {
              if($newProductLine[$insertAtColumnIndex] != $columnValue) {
                  $newProductLine[$insertAtColumnIndex] = $columnValue;
                  $productArray[count($productArray)-1] = $newProductLine;
              }
          } else {
              $productArray[count($productArray)-1][] = $columnValue;
          }
      }
  }
	// Add variants as additional products
	if ($_pf->get_product_type($product->ID) == 'variable' && get_option('REVIEWSio_use_parent_product') != 1)
	{
		$available_variations = $_product->get_available_variations();

		foreach ($available_variations as $variation)
		{
			$variant_sku = get_option('REVIEWSio_product_identifier') == 'id'? $variation['variation_id'] : $variation['sku'];
			$variant_attributes = is_array($variation['attributes'])? implode(' ',  array_filter(array_values($variation['attributes']))) : '';
			$variant_title = $product->post_title;

			if(!empty($variant_attributes)){
				//$variant_title .= ' - '.$variant_attributes;
			}
			$productArray[] = array( $variant_sku, $variant_title, $image_url, get_permalink($product->ID), $variation['sku'], $variation['sku'], $variation['variation_id'], $barcode, $categories_string, $categories_json);

      $newFields = [];
      //Append main product attribute fields for variant products
      foreach($_product->get_attributes() as $productAttribute) {
          if(in_array($productAttribute['name'], $customProductAttributes)) {
              $newFields[$productAttribute['name']] = $productAttribute['options'][0];
          }
      }
      //Overwrite new column value if variant attribute data is available
      if(!empty($variation['attributes'])){
          foreach ($variation['attributes'] as $variant_attribute_key => $variant_attribute_value) {
              $variantAttributeColumnName = str_replace('attribute_', '', $variant_attribute_key);
              $variantAttributeColumnValue = $variant_attribute_value;
              $newFields[$variantAttributeColumnName] = $variant_attribute_value;
          }
      }
      //Insert additional data
      if(!empty($newFields)) {
          foreach ($newFields as $columnName => $columnValue) {
              $insertAtColumnIndex = false;
              //Insert column name if does not exist or get the column index
              if(!in_array($columnName, $productArray[0])) {
                  $productArray[0][] = $columnName;
              } else {
                  $insertAtColumnIndex = array_search($columnName, $productArray[0]);
              }
              //If colummn already exists check and update existing value else add to end
              $newProductLine = $productArray[count($productArray)-1];
              if(!empty($insertAtColumnIndex)) {
                  if($newProductLine[$insertAtColumnIndex] != $columnValue) {
                      $newProductLine[$insertAtColumnIndex] = $columnValue;
                      $productArray[count($productArray)-1] = $newProductLine;
                  }
              } else {
                  $productArray[count($productArray)-1][] = $columnValue;
              }
          }
      }

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
