<?php
if(!defined('ABSPATH')) {
  exit;
}

header('Content-Type: text/csv; charset=UTF-8');

use Automattic\WooCommerce\Utilities\OrderUtil;

/**
 * Check WooCommerce HPOS option is enabled
 *
 */
function is_hpos_enabled() {
    if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
        return true;
    }

    return false;
}

function get_order_details($o)
{
    $using_hpos = is_hpos_enabled();

    if ($using_hpos) {
        $order = new WC_Order($o->get_id());
        $order_data = $order->get_data();

        $order_id = $order->get_id();
        $firstname = $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'];
        $email = $order_data['billing']['email'];
    } else {
        $order = new WC_Order($o->ID);
        $order_id  = $order->get_order_number();
        $firstname = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $email = $order->get_billing_email();
    }

    return (object) [
        'order' => $order,
        'order_id' => $order_id,
        'firstname' => $firstname,
        'email' => $email,

    ];
}

$args = array(
    'post_type'      => 'shop_order',
    'post_status'    => array( 'wc-processing', 'wc-completed' ),
    'posts_per_page' => 300,
    'orderby'        => 'id',
    'order'          => 'desc'
);

$hpos_args = array(
    'type'    => 'shop_order',
    'status'  => array( 'wc-processing', 'wc-completed' ),
    'orderby' => 'id',
    'order'   => 'desc'
);

$i = 0;
$using_hpos  = is_hpos_enabled();
$productArray[] = ['order id', 'customer name', 'email', 'sku', 'date'];

if ($using_hpos) {
    $orders = wc_get_orders($hpos_args);
} else {
    $orders = get_posts($args);
}

foreach ($orders as $o)
{
    $order_details = get_order_details($o);

    $order = $order_details->order;
    $order_id = $order_details->order_id;
    $firstname = $order_details->firstname;
    $email = $order_details->email;
    
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
            
            $productArray[] = [$order_id, $firstname, $email, $sku, get_the_date('d/m/Y', $order_details->order_id)];
            $addedItems = true;
        }
        else
        {
            $productArray[] = [$order_id, $firstname, $email, '', get_the_date('d/m/Y', $order_details->order_id)];
            $addedItems = true;
        }

    }

    if(!$addedItems){
        $productArray[] = [$order_id, $firstname, $email, '', get_the_date('d/m/Y', $order_details->order_id)];
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
