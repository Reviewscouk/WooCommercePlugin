<?php
/*
Plugin Name: Reviews.co.uk for WooCommerce
Depends: WooCommerce
Plugin URI: http://www.reviews.co.uk
Description: Integrate Reviews.co.uk with WooCommerce to automatically send review requests.
Author: Reviews.co.uk
License: GPL
Version: 0.1
*/

if (!class_exists('WooCommerce_Reviews'))
{
	class WooCommerce_Reviews
	{
		public function __construct()
		{
			wp_reset_query();
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'add_menu'));
			add_filter('init', array($this, 'init'));
		}

		public function admin_init()
		{
			$this->init_settings();
		}

		public function init_settings()
		{
			register_setting('woocommerce-reviews', 'store_id');
			register_setting('woocommerce-reviews', 'api_key');
			register_setting('woocommerce-reviews', 'product_feed');
			register_setting('woocommerce-reviews', 'create_csv');
			register_setting('woocommerce-reviews', 'widget_hex_colour');
			register_setting('woocommerce-reviews', 'enable_rich_snippet');
			register_setting('woocommerce-reviews', 'enable_product_rich_snippet');
			register_setting('woocommerce-reviews', 'show_product_questions');
			register_setting('woocommerce-reviews', 'product_review_widget');
			register_setting('woocommerce-reviews', 'send_product_review_invitation');
			register_setting('woocommerce-reviews', 'send_merchant_review_invitation');
			register_setting('woocommerce-reviews', 'region');
		}

		public function add_menu()
		{
			add_options_page('WooCommerce Reviews.co.uk Settings', 'Reviews.co.uk', 'manage_options', 'reviewscouk', array(&$this, 'reviews_settings_page'));
		}

		public function reviews_settings_page()
		{
			if (!current_user_can('manage_options'))
			{
				wp_die(__('You do not have sufficient permissions to access this page.'));

			}
			include(sprintf("%s/settings-page.php", dirname(__FILE__)));
		}

		function init()
		{
			add_action('woocommerce_order_status_completed', 'orderCompleted');
			add_filter('template_redirect', 'product_feed');
			add_filter('woocommerce_product_tabs', 'product_review_tab');
			add_filter('woocommerce_after_single_product_summary', 'productPage');
			add_action('wp_footer', 'enable_rich_snippet');

			function enable_rich_snippet()
			{
                global $product;

				$enabled = get_option('enable_rich_snippet');
				$product_enabled = get_option('enable_product_rich_snippet');
                $region = get_option('region');
                if($region == 'uk'){
                    $domain = 'widget.reviews.co.uk';
                }
                else
                {
                    $domain = 'widget.reviews.io';
                }

                // Getting Product SKU
                $skus = array();
                if($product){
                    $sku = get_post_meta(get_the_ID(), '_sku');
                    $skus[] = $sku[0];

                    if ($product->product_type != 'simple')
                    {
                        $available_variations = $product->get_available_variations();
                        foreach ($available_variations as $variant)
                        {
                            $skus[] = $variant['sku'];
                        }
                    }
                }

                if($enabled && empty($sku) ){
                    echo '<script src="https://'.$domain.'/rich-snippet/dist.js"></script>
                    <script type="text/javascript">
                    richSnippet({
                        store: "'.get_option('store_id').'"
                    });
                    </script>';
                }
                else if( $product_enabled && !empty($sku) )
                {
                    echo '<script src="https://'.$domain.'/rich-snippet/dist.js"></script>
                    <script>
                    richSnippet({
                        store: "'.get_option('store_id').'",
                        sku: "'.implode(';',$skus).'"
                    });
                    </script>';
                }
			}

			function product_feed($page_template)
			{
				$actual_link = explode('/', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

				$slug = '';

				if ($actual_link[count($actual_link) - 1] == '')
				{
					$slug = $actual_link[count($actual_link) - 2];
				}
				else
				{
					$slug = $actual_link[count($actual_link) - 1];
				}

				if ($slug == 'product_feed')
				{
					$product_feed = get_option('product_feed');
					if($product_feed)
					{
						global $wp_query;
						status_header(200);
						$wp_query->is_404 = false;
						include(dirname(__FILE__) . '/product-feed.php');
						exit();
					}
				}

				if($slug == 'order_csv')
				{
					if ( is_user_logged_in() && current_user_can( 'manage_options' ) )
					{
						global $wp_query;
						status_header(200);
						$wp_query->is_404 = false;
						include(dirname(__FILE__) . '/order_csv.php');
						exit();
					}
					else
					{
						auth_redirect();
					}
				}

				return $page_template;
			}

			function product_review_tab($tabs){
				if (in_array(get_option('product_review_widget'),array('tab'))){
					$tabs['reviews'] = array(
						'title' => 'Reviews',
						'callback' => 'productReviewWidget',
						'priority' => 50
					);
				}

				return $tabs;
			}

			function productPage(){
				if (in_array(get_option('product_review_widget'),array('summary','1'))){
					productReviewWidget();
				}
			}

			function productReviewWidget(){
				global $woocommerce, $product, $post, $re_wcvt_options;

				if(get_option('api_key') != '' && get_option('store_id') != ''){
					// Build SKU Array
					$sku = get_post_meta(get_the_ID(), '_sku');
					$skus = array();
					if ($product->product_type != 'simple')
					{
						$available_variations = $product->get_available_variations();
						foreach ($available_variations as $variant)
						{
							$skus[] = $variant['sku'];
						}
					}
					$skus[] = $sku[0];

					// Prepare HEX Colour
					$colour = get_option('widget_hex_colour');
					if (strpos($colour, '#') === FALSE) $colour = '#' . $colour;
					if (!preg_match('/^#[a-f0-9]{6}$/i', $colour)) $colour = '#5db11f';

					echo '
					<script src="https://widget.reviews.co.uk/product/dist.js"></script>
					<div id="widget"></div>
					<script type="text/javascript">
						productWidget("widget",{
						store: "' . get_option('store_id') . '",
						sku: "' . implode(';', $skus) . '", // Multiple SKU"s Seperated by Semi-Colons
						primaryClr: "' . $colour . '",
						neutralClr: "#EBEBEB",
						buttonClr: "#EEE",
						textClr: "#333",
						tabClr: "#eee",
						ratingStars: false,
						showAvatars: true
					});
					</script>';
				}
				else
				{
					echo 'Missing Reviews.co.uk API Credentials';
				}
			}

			function orderCompleted($order_id)
			{
				$api_url = 'https://api.reviews.co.uk';

				$region = get_option('region');
				if ($region == 'us'){
					$api_url = 'https://api.reviews.io';
				}

				$order = new WC_Order($order_id);
				$items = $order->get_items();

				foreach ($items as $row)
				{
					$productmeta = wc_get_product($row['product_id']);
					$sku = $productmeta->get_sku();

					if ($productmeta->product_type == 'simple')
					{
						$sku = $productmeta->get_sku();
					}
					else
					{
						$available_variations = $productmeta->get_available_variations();
						foreach ($available_variations as $variation)
						{
							if ($variation['variation_id'] == $row['variation_id'])
							{
								$sku = $variation['sku'];
							}
						}
					}

					$url = $productmeta->post->guid;

					$attachment_url = wp_get_attachment_url(get_post_thumbnail_id($row['product_id']));

					$p[] = array(
						'image'   => $attachment_url,
						'id'      => $row['product_id'],
						'sku'     => $sku,
						'name'    => $row['name'],
						'pageUrl' => $url
					);
				}

				$post_params['order_id'] = $order_id;
				$post_params['email']    = $order->billing_email;
				$post_params['name']     = $order->billing_first_name . ' ' . $order->billing_last_name;
				$post_params['products'] = json_encode($p);

				// Only do this if we have all the info required
				if (get_option('api_key') != '' && get_option('store_id') != '' && get_option('send_product_review_invitation') == '1')
				{
					// Send product request
					$product_url_string = $api_url . '/product/invitation';
					$ch                 = curl_init($product_url_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'store:' . get_option('store_id'),
						'apikey:' . get_option('api_key')
					));
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
					curl_exec($ch);
					curl_close($ch);
				}

				if (get_option('api_key') != '' && get_option('store_id') != '' && get_option('send_merchant_review_invitation') == '1')
				{
					$order_params             = array();
					$order_params['name']     = $order->billing_first_name . ' ' . $order->billing_last_name;
					$order_params['store']    = get_option('store_id');
					$order_params['email']    = $order->billing_email;
					$order_params['order_id'] = $order_id;
					$order_params['api_key']  = get_option('api_key');

					$product_url_string = $api_url . '/merchant/invitation';
					$ch                 = curl_init($product_url_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'store:' . get_option('store_id'),
						'apikey:' . get_option('api_key'
						)));
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $order_params);
					curl_exec($ch);

					curl_close($ch);
				}
			}
		}
	}
}

if (class_exists('WooCommerce_Reviews'))
{
	$woocommercereviews = new WooCommerce_Reviews();

	// Add a link to the settings page onto the plugin page
	if (isset($woocommercereviews))
	{
		function woocommercereviews_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=reviewscouk">' . __('Settings', 'woocommercereviews') . '</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", 'woocommercereviews_settings_link');
	}
}
