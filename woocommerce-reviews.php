<?php
/*
Plugin Name: Reviews.co.uk for WooCommerce
Depends: WooCommerce
Plugin URI: http://www.reviews.co.uk
Description: Integrate Reviews.co.uk with WooCommerce to automatically send review requests.
Author: Reviews.co.uk
License: GPL
Version: 0.2
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
			add_action('hourly_order_process_event', array($this,'process_recent_orders'));
			register_activation_hook(__FILE__, array($this, 'run_on_activation'));
			register_deactivation_hook( __FILE__, array($this, 'run_on_deactivate') );
		}

		public function admin_init()
		{
			$this->init_settings();
		}

		public function init_settings()
		{
			register_setting('woocommerce-reviews', 'region');
			register_setting('woocommerce-reviews', 'store_id');
			register_setting('woocommerce-reviews', 'api_key');
			register_setting('woocommerce-reviews', 'product_feed');
			register_setting('woocommerce-reviews', 'create_csv');
			register_setting('woocommerce-reviews', 'widget_hex_colour');
			register_setting('woocommerce-reviews', 'output_rich_snippet_code');
			register_setting('woocommerce-reviews', 'enable_product_rich_snippet');
			register_setting('woocommerce-reviews', 'enable_product_rating_snippet');
			register_setting('woocommerce-reviews', 'show_product_questions');
			register_setting('woocommerce-reviews', 'product_review_widget');
			register_setting('woocommerce-reviews', 'send_product_review_invitation');
			register_setting('woocommerce-reviews', 'send_merchant_review_invitation');
			register_setting('woocommerce-reviews', 'enable_cron');
			register_setting('woocommerce-reviews', 'enable_floating_widget');
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

		public function process_recent_orders(){
			/*
			 * This runs hourly and runs processCompletedOrder if it hasn't already been run. This solves problems for clients using solutions like Veeqo to complete orders.
			 */
			if(get_option('enable_cron')){
				$orders = get_posts( array(
				    'numberposts' => 30,
					'meta_key' => '_reviewscouk_status',
					'meta_compare' => 'NOT EXISTS',
				    'post_type'   => wc_get_order_types(),
				    'post_status' => array('wc-completed'),
					'date_query' => array(
				        'after' => date('Y-m-d', strtotime('-5 days'))
				    ),
				) );

				foreach($orders as $order){
					$this->processCompletedOrder($order->ID);
				}
			}
		}

		public function run_on_activation(){
			wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'hourly_order_process_event');
		}

		public function run_on_deactivate(){
			wp_clear_scheduled_hook('hourly_order_process_event');
		}

		public function processCompletedOrder($order_id)
		{
			update_post_meta($order_id, '_reviewscouk_status','processed');

			$api_url = $this->getApiDomain();
			$order = new WC_Order($order_id);
			$items = $order->get_items();

			foreach ($items as $row)
			{
				$productmeta = wc_get_product($row['product_id']);
				$sku = $productmeta->get_sku();

				if($productmeta->product_type == 'variable')
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

        public function rating_snippet_footer_scripts(){
            $enabled = get_option('enable_product_rating_snippet');
            if($enabled){
            ?>
                <script src="https://<?php echo $this->getWidgetDomain(); ?>/product/dist.js"></script>

                <script src="https://<?php echo $this->getWidgetDomain(); ?>/rating-snippet/dist.js"></script>
                <link rel="stylesheet" href="https://<?php echo $this->getWidgetDomain(); ?>/rating-snippet/dist.css" />

                <script>
                ratingSnippet("ruk_rating_snippet",{
                store: "<?php echo get_option('store_id'); ?>",
                    color: "<?php echo $this->getHexColor(); ?>",
                    linebreak: true,
                    text: "Reviews"
                });
                </script>
            <?php
            }
        }

        public function floating_widget(){  
            $enabled = get_option('enable_floating_widget');
            $store = get_option('store_id');
            if($enabled){
            ?>
                <script type="text/javascript" src="https://<?php echo $this->getDashDomain(); ?>/widget/float.js" data-store="<?php echo $store; ?>" data-color="<?php echo $this->getHexColor(); ?>" data-position="right"></script>
                <link href="https://<?php echo $this->getDashDomain(); ?>/widget/float.css" rel="stylesheet" /> 
            <?php
            }
        }

        public function product_rating_snippet_markup() {
            $skus = $this->getProductSkus();
            $enabled = get_option('enable_product_rating_snippet');
            if($enabled){
                echo '<div class="ruk_rating_snippet" data-sku="'.implode(';',$skus).'"></div>';
            }
        }

        public function getSubDomain($sub){
            $region = get_option('region');
            if($region == 'uk'){
                return $sub.'.reviews.co.uk';
            }
            else
            {
                return $sub.'.reviews.io';
            }
        }

        public function getWidgetDomain(){
            return $this->getSubDomain('widget');
        }

        public function getDashDomain(){
            return $this->getSubDomain('dash');
        }

        public function getApiDomain(){
            return $this->getSubDomain('api');
        }

        public function output_rich_snippet_code()
        {
            global $product;

            $enabled = get_option('output_rich_snippet_code');
            $product_enabled = get_option('enable_product_rich_snippet');

            // Getting Product SKU
            $skus = $this->getProductSkus();

            if($enabled && empty($skus) ){
                echo '<script src="https://'.$this->getWidgetDomain().'/rich-snippet/dist.js"></script>
                <script type="text/javascript">
                richSnippet({
                    store: "'.get_option('store_id').'"
                });
                </script>';
            }
            else if( $product_enabled && !empty($skus) )
            {
                echo '<script src="https://'.$this->getWidgetDomain().'/rich-snippet/dist.js"></script>
                <script>
                richSnippet({
                    store: "'.get_option('store_id').'",
                    sku: "'.implode(';',$skus).'"
                });
                </script>';
            }
        }

        public function getProductSkus(){
            global $product;
            
            $skus = array();
            if($product){
                $sku = get_post_meta(get_the_ID(), '_sku');
                if(!empty($sku)){
                    $skus[] = $sku[0];
                }

                if ($product->product_type == 'variable')
                {
                    $available_variations = $product->get_available_variations();
                    foreach ($available_variations as $variant)
                    {
                        $skus[] = $variant['sku'];
                    }
                }
            }

            return $skus;
        }

        public function product_feed($page_template)
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

        public function product_review_tab($tabs){
            if (in_array(get_option('product_review_widget'),array('tab'))){
                $tabs['reviews'] = array(
                    'title' => 'Reviews',
                    'callback' => array($this,'productReviewWidget'),
                    'priority' => 50
                );
            }

            return $tabs;
        }

        public function productPage(){
            if (in_array(get_option('product_review_widget'),array('summary','1'))){
                $this->productReviewWidget();
            }
        }

        public function getHexColor(){
            $colour = get_option('widget_hex_colour');
            if (strpos($colour, '#') === FALSE) $colour = '#' . $colour;
            if (!preg_match('/^#[a-f0-9]{6}$/i', $colour)) $colour = '#5db11f';
            return $colour;
        }

        public function productReviewWidget(){
            if(get_option('api_key') != '' && get_option('store_id') != ''){
                $skus = $this->getProductSkus();

                $color = $this->getHexColor();
                echo '
                <script src="https://'.$this->getWidgetDomain().'/product/dist.js"></script>
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

		function init()
		{
			add_action('woocommerce_order_status_completed', array($this,'processCompletedOrder'));
			add_filter('template_redirect', array($this, 'product_feed'));
			add_filter('woocommerce_product_tabs', array($this,'product_review_tab'));
			add_filter('woocommerce_after_single_product_summary', array($this,'productPage'));
			add_action('wp_footer', array($this,'output_rich_snippet_code'));
			add_action('wp_footer', array($this,'rating_snippet_footer_scripts'));
			add_action('wp_footer', array($this,'floating_widget'));
            add_action('woocommerce_single_product_summary', array($this,'product_rating_snippet_markup'), 6);
            add_action('woocommerce_after_shop_loop_item', array($this, 'product_rating_snippet_markup'), 5  );
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
