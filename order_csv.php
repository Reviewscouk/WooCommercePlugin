<?php
header('Content-Type: text/csv; charset=UTF-8');
$args = array(
	'post_type'      => 'shop_order',
	'post_status'    => 'publish',
	'posts_per_page' => 300,
	'orderby'=> 'id',
	'order' => 'desc',
	'tax_query'      => array(
		array(
			'taxonomy' => 'shop_order_status',
			'field'    => 'slug',
			'terms'    => array('completed')
		)
	)
);

$orders = get_posts($args);
$i      = 0;

$productArray[] = ['order id', 'customer name', 'email', 'sku', 'date'];

foreach ($orders as $o)
{

	$order_id = $o->ID;
	$order    = new WC_Order($order_id);

	$billing_address = $order->get_billing_address();

	$firstname       = $order->billing_first_name .' ' .$order->billing_last_name;
	$email           = $order->billing_email;

	foreach ($order->get_items() as $item)
	{
		$product = wc_get_product($item['product_id']);

        if($product){
            $sku = $product->get_sku();

            if($product->product_type == 'variant')
            {
                $available_variations = $product->get_available_variations();

                foreach ($available_variations as $variation)
                {

                    if ($variation['variation_id'] == $item['variation_id'])
                    {
                        $sku = $variation['sku'];
                    }
                }

            }

            $productArray[] = [$o->ID, $firstname, $email, $sku, $o->post_date];
        }
        else
        {
            $productArray[] = [$o->ID, $firstname, $email, '', $o->post_date];
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

exit();
?>
