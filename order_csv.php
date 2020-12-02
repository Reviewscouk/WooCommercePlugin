<?php
header('Content-Type: text/csv; charset=UTF-8');

$args = array(
    'post_type'   => 'shop_order',
    'post_status' => array( 'wc-processing', 'wc-completed' ),
    'posts_per_page' => 300,
    'orderby'=> 'id',
    'order' => 'desc'
);

$orders = get_posts($args);
$i      = 0;

$productArray[] = ['order id', 'customer name', 'email', 'sku', 'date'];

foreach ($orders as $o)
{
    $order    = new WC_Order($o->ID);

    $order_id = $order->get_order_number();

    $firstname       = $order->get_billing_first_name() .' ' .$order->get_billing_last_name();
    $email           = $order->get_billing_email();

    $addedItems = false;

    foreach ($order->get_items() as $item)
    {
        $product = wc_get_product($item['product_id']);

        if($product){
            $sku = $product->get_sku();

            if($product->get_type() == 'variant')
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

            $productArray[] = [$order_id, $firstname, $email, $sku, get_the_date('d/m/Y', $order_id)];
            $addedItems = true;
        }
        else
        {
            $productArray[] = [$order_id, $firstname, $email, '', get_the_date('d/m/Y', $order_id)];
            $addedItems = true;
        }

    }

    if(!$addedItems){
        $productArray[] = [$order_id, $firstname, $email, '', get_the_date('d/m/Y', $order_id)];
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
