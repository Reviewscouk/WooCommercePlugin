<?php
if (!defined('ABSPATH')) {
    exit;
}

$fp = fopen('php://temp', 'w+');

$batch_size = 100;
$offset = 0;
$headerArray = ['sku', 'name', 'image_url', 'link', 'mpn', 'woocommerce_product_sku', 'woocommerce_product_id', 'barcode', 'category', 'categories'];
fputcsv($fp, $headerArray);

while (true) {
    $args = array(
        'post_type' => 'product',
        'offset' => $offset,
        'posts_per_page' => $batch_size
    );

    $products = get_posts($args);

    // No more products to process
    if (empty($products)) {
        break;
    }

    // Add product rows
    $productArray = [];
    processProducts($productArray, $products);
    foreach ($productArray as $fields) {
        fputcsv($fp, $fields);
    }

    $offset += $batch_size;
}


rewind($fp);
$csv_contents = stream_get_contents($fp);
fclose($fp);

// Handle/Output your final sanitised CSV contents
header('Content-Type: text/csv; charset=UTF-8');
echo $csv_contents;



function processProducts(&$productArray, $products)
{

    foreach ($products as $product) {

        $_pf      = new WC_Product_Factory();
        $_product = $_pf->get_product($product->ID);

        $woocommerce_sku = $_product->get_sku();
        $woocommerce_id = $product->ID;

        $sku    = get_option('REVIEWSio_product_identifier') == 'id' ? $woocommerce_id : $woocommerce_sku;
        $image_link = '';

        $image_id = $_product->get_image_id();
        $image_url = '';

        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
        }

        $categories = get_the_terms($product->ID, 'product_cat');
        $categories_string = [];

        foreach ($categories as $cat) {
            if (!empty($cat->name)) {
                $categories_string[] = $cat->name;
            }
        }
        $categories_json = json_encode($categories_string);
        $categories_string = implode(', ', $categories_string);

        // Try to get barcode from meta, if nothing found, will return empty string
        $try = array('_barcode', 'barcode', '_gtin', 'gtin');

        $barcode = '';

        foreach ($try as $t) {

            if (!empty($barcode)) break;

            $barcode = get_post_meta($product->ID, $t, true);
        }

        // Always add the parent product
        $productArray[] = array($sku, $product->post_title, $image_url, get_permalink($product->ID), $sku, $woocommerce_sku, $woocommerce_id, $barcode, $categories_string, $categories_json);

        $newFields = [];
        $customProductAttributes = array('_barcode', 'barcode', '_gtin', 'gtin', 'mpn', '_mpn');
        if (!empty(get_option('REVIEWSio_product_feed_custom_attributes'))) {
            $additionalCustomProductAttributes = get_option('REVIEWSio_product_feed_custom_attributes');
            $additionalCustomProductAttributes = explode(',', $additionalCustomProductAttributes);
            if (!empty($additionalCustomProductAttributes)) {
                foreach ($additionalCustomProductAttributes as $additionalCustomProductAttribute) {
                    if (!in_array(strtolower($additionalCustomProductAttribute), $customProductAttributes)) {
                        $customProductAttributes[] = trim(strtolower($additionalCustomProductAttribute));
                    }
                }
            }
        }

        //Yoast Global Identifiers
        if (get_option('REVIEWSio_product_feed_wpseo_global_ids')) {
            $customProductAttributes[] = 'wpseo_gtin';
            $customProductAttributes[] = 'wpseo_mpn';
        }

        $attributes = $_product->get_attributes();
        $meta = get_post_meta($product->ID);

        $productAttributes = [];
        foreach ($attributes as $p) {
            $productAttributes[strtolower($p['name'])] = $p;
        }

        foreach ($meta as $k => $a) {
            if (empty($productAttributes[strtolower($k)])) {
                if (is_string($a)) {
                    $productAttributes[strtolower($k)] = $a;
                } elseif (is_array($a) && isset($a[0]) && is_string($a[0])) {
                    $productAttributes[strtolower($k)] = $a[0];
                }
            }
        }

        foreach ($customProductAttributes as $key) {
            $key = strtolower($key);
            if (isset($productAttributes[$key]) && $productAttributes[$key]['is_taxonomy']) {
                // pull in product terms if key provided
                $terms = wc_get_product_terms($product->ID, $key, ['fields' => 'names']);
                $value = array_shift($terms);
                $newFields[$key] = $value;
            } elseif (isset($productAttributes[$key]['options'][0])) {
                $newFields[$key] = $productAttributes[$key]['options'][0];
            } elseif (isset($productAttributes[$key]) && is_string($productAttributes[$key])) {
                $newFields[$key] = $productAttributes[$key];
            } else {
                $newFields[$key] = ' ';
            }
        }
        //Yoast Global Identifiers
        if (get_option('REVIEWSio_product_feed_wpseo_global_ids')) {
            $productMetaGlobalIds = get_post_meta($_product->get_id(), 'wpseo_global_identifier_values', true);
            if (!empty($productMetaGlobalIds)) {
                foreach ($productMetaGlobalIds as $columnName => $columnValue) {
                    if (strpos($columnName, 'gtin') !== false && !empty($columnValue)) {
                        $newFields['wpseo_gtin'] = $columnValue;
                    }
                    if (strpos($columnName, 'mpn') !== false && !empty($columnValue)) {
                        $newFields['wpseo_mpn'] = $columnValue;
                    }
                }
            }
        }
        //Add any matching attributes to product feeds and update existing columns
        if (!empty($newFields)) {
            foreach ($newFields as $columnName => $columnValue) {
                $insertAtColumnIndex = false;
                $columnName = strtolower($columnName);
                //Insert column name if does not exist or get the column index
                if (!in_array($columnName, $productArray[0])) {
                    $productArray[0][] = $columnName;
                } else {
                    $insertAtColumnIndex = array_search($columnName, $productArray[0]);
                }

                //If column already exists check and update existing value else add to end
                $newProductLine = $productArray[count($productArray) - 1];
                if (!empty($insertAtColumnIndex)) {
                    if (!isset($newProductLine[$insertAtColumnIndex]) || $newProductLine[$insertAtColumnIndex] != $columnValue) {
                        $newProductLine[$insertAtColumnIndex] = $columnValue;
                        $productArray[count($productArray) - 1] = $newProductLine;
                    }
                } else {
                    $productArray[count($productArray) - 1][] = $columnValue;
                }
            }
        }
        //Set MPN to SKU value if was converted to blank
        if (!empty($productArray[count($productArray) - 1])) {
            $mpn = $productArray[count($productArray) - 1][4];
            if (empty($mpn) || $mpn == ' ') {
                $newProductLine = $productArray[count($productArray) - 1];
                $newProductLine[4] = $sku;
                $productArray[count($productArray) - 1] = $newProductLine;
            }
        }

        // Add variants as additional products
        if ($_pf->get_product_type($product->ID) == 'variable' && get_option('REVIEWSio_use_parent_product') != 1) {
            $available_variations = $_product->get_available_variations();

            foreach ($available_variations as $variation) {
                $variant_sku = get_option('REVIEWSio_product_identifier') == 'id' ? $variation['variation_id'] : $variation['sku'];
                $variant_attributes = is_array($variation['attributes']) ? implode(' ',  array_filter(array_values($variation['attributes']))) : '';
                $variant_title = $product->post_title;

                if (!empty($variant_attributes)) {
                    //$variant_title .= ' - '.$variant_attributes;
                }
                $productArray[] = array($variant_sku, $variant_title, $image_url, get_permalink($product->ID), $variation['sku'], $variation['sku'], $variation['variation_id'], $barcode, $categories_string, $categories_json);

                $newFields = [];
                //Append main product attribute fields for variant products
                foreach ($customProductAttributes as $key) {
                    $key = strtolower($key);
                    $newFields[$key] = !empty($productAttributes[$key]['options'][0]) ? $productAttributes[$key]['options'][0] : ' ';
                }
                //Overwrite with variant specific values if available
                if (!empty($variation['attributes'])) {
                    foreach ($variation['attributes'] as $variant_attribute_key => $variant_attribute_value) {
                        $variantAttributeColumnName = str_replace('attribute_', '', $variant_attribute_key);
                        $variantAttributeColumnValue = !empty($variant_attribute_value) ? $variant_attribute_value : ' ';
                        if (!empty($newFields[strtolower($variantAttributeColumnName)])) {
                            $newFields[strtolower($variantAttributeColumnName)] = $variantAttributeColumnValue;
                        }
                    }
                }
                //Yoast Global Identifiers
                if (get_option('REVIEWSio_product_feed_wpseo_global_ids')) {
                    if (!empty($productMetaGlobalIds)) {
                        foreach ($productMetaGlobalIds as $columnName => $columnValue) {
                            if (strpos($columnName, 'gtin') !== false && !empty($columnValue)) {
                                $newFields['wpseo_gtin'] = $columnValue;
                            }
                            if (strpos($columnName, 'mpn') !== false && !empty($columnValue)) {
                                $newFields['wpseo_mpn'] = $columnValue;
                            }
                        }
                    }
                }

                //insert additional
                if (!empty($newFields)) {
                    foreach ($newFields as $columnName => $columnValue) {
                        $insertAtColumnIndex = false;
                        $columnName = strtolower($columnName);
                        //Insert column name if does not exist or get the column index
                        if (!in_array($columnName, $productArray[0])) {
                            $productArray[0][] = $columnName;
                        } else {
                            $insertAtColumnIndex = array_search($columnName, $productArray[0]);
                        }
                        //If colummn already exists check and update existing value else add to end
                        $newProductLine = $productArray[count($productArray) - 1];
                        if (!empty($insertAtColumnIndex)) {
                            if (!isset($newProductLine[$insertAtColumnIndex]) || $newProductLine[$insertAtColumnIndex] != $columnValue) {
                                $newProductLine[$insertAtColumnIndex] = $columnValue;
                                $productArray[count($productArray) - 1] = $newProductLine;
                            }
                        } else {
                            $productArray[count($productArray) - 1][] = $columnValue;
                        }
                    }
                }
                //Set MPN to SKU value if was converted to blank
                if (!empty($productArray[count($productArray) - 1])) {
                    $mpn = $productArray[count($productArray) - 1][4];
                    if (empty($mpn) || $mpn == ' ') {
                        $newProductLine = $productArray[count($productArray) - 1];
                        $newProductLine[4] = $sku;
                        $productArray[count($productArray) - 1] = $newProductLine;
                    }
                }
            }
        }

        // Clear product memory
        unset($product);
    }
}
