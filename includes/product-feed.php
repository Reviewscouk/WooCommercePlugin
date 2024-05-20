<?php
if (!defined('ABSPATH')) {
    exit;
}

global $refresh_cron_feed;

// Define file directory for CSV Cron to save in
$parentDirectory = dirname(__DIR__);
$filesDirectory = $parentDirectory . '/files/';
if (!file_exists($filesDirectory)) {
    mkdir($filesDirectory, 0777, true);
}
$url = $_SERVER['REQUEST_URI'];
$refreshFeed = explode('?', $url);
$csvFilePath = $filesDirectory . 'product_feed.csv';


// Download from plugin directory if file exists
if (file_exists($csvFilePath) && !in_array('refresh', $refreshFeed) && !$refresh_cron_feed) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($csvFilePath));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($csvFilePath));
    ob_clean();
    flush();
    readfile($csvFilePath);
    exit;
}

// Generate product feed CSV
$fp = fopen('php://temp', 'w+');

$batch_size = 100;
$offset = 0;

$headerArray = [
    'sku',
    'name',
    'image_url',
    'link',
    'mpn',
    'woocommerce_product_sku',
    'woocommerce_product_id',
    'barcode',
    'category',
    'categories'
];

$customProductAttributes = [
    '_barcode',
    '_gtin',
    'gtin',
    '_mpn'
];

// Add additional columns to an array
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

// Yoast Global Identifiers
if (get_option('REVIEWSio_product_feed_wpseo_global_ids')) {
    $customProductAttributes[] = 'wpseo_gtin';
    $customProductAttributes[] = 'wpseo_mpn';
}

// WooCommerce Google Product Feed Attributes
if (get_option('REVIEWSio_enable_gpf_data')) {
    $customProductAttributes[] = 'gpf_gtin';
}

// Append addtional columns to the header array
foreach ($customProductAttributes as $columnName) {
    $headerArray[] = $columnName;
}

fputcsv($fp, $headerArray);

// Loop through records in batches and append to csv file
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
    processProducts($productArray, $products, $headerArray, $customProductAttributes);
    foreach ($productArray as $fields) {
        fputcsv($fp, $fields);
    }

    $offset += $batch_size;
}


rewind($fp);
$csv_contents = stream_get_contents($fp);
fclose($fp);

// Handle/Output your final sanitised CSV contents for download
header('Content-Type: text/csv; charset=UTF-8');
echo $csv_contents;

// Save generated file to plugin directory if product feed cron is enabled
if (get_option('REVIEWSio_enable_product_feed_cron')) {
    file_put_contents($csvFilePath, $csv_contents);
}




function processProducts(&$productArray, $products, $headerArray, $customProductAttributes)
{
    foreach ($products as $product) {
        $_pf      = new WC_Product_Factory();
        $_product = $_pf->get_product($product->ID);

        $woocommerce_sku = $_product->get_sku();
        $woocommerce_id = $product->ID;

        $sku = get_option('REVIEWSio_product_identifier') == 'id' ? $woocommerce_id : $woocommerce_sku;

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

        $attributes = $_product->get_attributes();
        $meta = get_post_meta($product->ID);

        // Try to get barcode from meta, if nothing found, will return empty string
        $gtinFields = [
            '_barcode',
            'barcode',
            '_gtin',
            'gtin'
        ];
        $barcode = '';
        foreach ($gtinFields as $gtinField) {
            if (!empty($barcode)) {
                break;
            }

            // $barcode = get_post_meta($product->ID, 'attribute_' . $gtinField, true);
            if (!empty($attributes[$gtinField]) && !empty($attributes[$gtinField]['options'])) {
                $barcode = $attributes[$gtinField]['options'][0];
            }
        }

        // Always add the parent product
        $productArray[] = [
            $sku,
            $product->post_title,
            $image_url,
            get_permalink($product->ID),
            $sku,
            $woocommerce_sku,
            $woocommerce_id,
            $barcode,
            $categories_string,
            $categories_json
        ];

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

        $newFields = [];
        foreach ($customProductAttributes as $key) {
            $key = strtolower($key);
            if (!empty($productAttributes[$key]) && !empty($productAttributes[$key]['is_taxonomy'])) {
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

        //WooCommerce Google Product Feed Attributes
        if (get_option('REVIEWSio_enable_gpf_data')) {
            $gpfData = get_post_meta($_product->get_id(), '_woocommerce_gpf_data', true);
            if (!empty($gpfData)) {
                foreach ($gpfData as $columnName => $columnValue) {
                    if (strpos($columnName, 'gtin') !== false && !empty($columnValue)) {
                        $newFields['gpf_gtin'] = $columnValue;
                    }
                }
            }
        }

        //Add any matching attributes to product feeds and update existing columns
        if (!empty($newFields)) {
            foreach ($newFields as $columnName => $columnValue) {
                $insertAtColumnIndex = false;
                $columnName = strtolower($columnName);
                //If column does not exist, this might break - might need a check
                $insertAtColumnIndex = array_search($columnName, $headerArray);
                //If column already exists check and update existing value else add to end
                $newProductLine = $productArray[count($productArray) - 1];
                if (!empty($insertAtColumnIndex)) {
                    if (!isset($newProductLine[$insertAtColumnIndex]) || $newProductLine[$insertAtColumnIndex] != $columnValue) {
                        $newProductLine[$insertAtColumnIndex] = $columnValue;
                        $productArray[count($productArray) - 1] = $newProductLine;
                    }
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
                        if (!empty($variant_attribute_value)) {
                            // Add variant meta value if available
                            $variantAttributeColumnValue = $variant_attribute_value;
                        } else if (!empty($attributes[$variantAttributeColumnName]['options'][0])) {
                            // Add parent meto values if available
                            $variantAttributeColumnValue = $attributes[$variantAttributeColumnName]['options'][0];
                        } else {
                            $variantAttributeColumnValue = ' ';
                        }

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

                //WooCommerce Google Product Feed Attributes
                if (get_option('REVIEWSio_enable_gpf_data')) {
                    $gpfVariantData = get_post_meta($variation['variation_id'], '_woocommerce_gpf_data', true);
                    if (!empty($gpfVariantData)) {
                        foreach ($gpfVariantData as $columnName => $columnValue) {
                            if (strpos($columnName, 'gtin') !== false && !empty($columnValue)) {
                                $newFields['gpf_gtin'] = $columnValue;
                            }
                        }
                    }
                }

                //insert additional
                if (!empty($newFields)) {
                    foreach ($newFields as $columnName => $columnValue) {
                        $insertAtColumnIndex = false;
                        $columnName = strtolower($columnName);
                        //If column does not exist then it might break
                        $insertAtColumnIndex = array_search($columnName, $headerArray);
                        //If colummn already exists check and update existing value else add to end
                        $newProductLine = $productArray[count($productArray) - 1];
                        if (!empty($insertAtColumnIndex)) {
                            if (!isset($newProductLine[$insertAtColumnIndex]) || $newProductLine[$insertAtColumnIndex] != $columnValue) {
                                $newProductLine[$insertAtColumnIndex] = $columnValue;
                                $productArray[count($productArray) - 1] = $newProductLine;
                            }
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
        unset($_pf);
    }
}
