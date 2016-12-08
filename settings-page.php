<div class="wrap">
	<img src="http://dmevjikmobvk2.cloudfront.net/public/images/logo.svg" height="40"  />
	<form method="post" action="options.php" autocomplete="off">
		<h2></h2><!-- Alerts Show Here -->

		<?php @settings_fields('woocommerce-reviews'); ?>
		<?php @do_settings_fields('woocommerce-reviews'); ?>

		<div style="background:#fff; padding:20px; margin:20px 0; border:1px solid #ccc;">
			<h2>Automated Review Collection - Powered by Reviews.co.uk</h2>

			<div id="welcomeText">
				<p>Enter your API Credentials to Start Collecting Reviews from your Customers.</p>
			</div>
		</div>


		<div id="tabs-container">
		    <ul class="tabs-menu">
		        <li class="current"><a href="#tab-1">API Settings</a></li>
		        <li><a href="#tab-2">Review Invitations</a></li>
		        <li><a href="#tab-3">Product Reviews</a></li>
		        <li><a href="#tab-4">Rich Snippets</a></li>
		        <li><a href="#tab-5">Data Feeds</a></li>
		        <li><a href="#tab-6">Advanced</a></li>
		    </ul>
		    <div class="tab">
		        <div id="tab-1" class="tab-content">
					<!-- API Settings -->
					<p>You can find your API credentials on the Reviews Dashboard. <br /><br /> Go to <b>Company Setup &gt; Automated Review Collection &gt; WooCommerce &gt; Configuration</b></p>

					<table class="form-table">
						<tr>
							<th>
								<label for="store_id">Store ID: </label>

			                    	</th>
							<td>
								<?php
								$store_id = get_option('store_id');
								?>
								<input type="text" name="store_id" value="<?php  echo $store_id; ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="api_key">API Key: </label>
							</th>
							<td>
								<?php
									$api_key = get_option('api_key');
								?>
								<input type="text" name="api_key" value="<?php  echo $api_key; ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="region">Region: </label>
							</th>
							<td>
								<?php
								$region = get_option('region');
								?>
								<select name="region">
									<option <?php echo ($region == 'uk') ? 'selected' : '' ?> value="uk">UK</option>
									<option <?php echo ($region == 'us') ? 'selected' : '' ?> value="us">US</option>
								</select>
							</td>
						</tr>
					</table>

					<p><strong>Not a Reviews.co.uk Customer?</strong></p>
					<p>
						You'll need to sign up for one of our packages at <a href="http://www.reviews.co.uk" target="_blank">Reviews.co.uk</a> or <a href="http://www.reviews.io" target="_blank">Reviews.io</a> to use this plugin.
					</p>
		        </div>
		        <div id="tab-2" class="tab-content">
					<!-- Review Collection -->
					<table class="form-table">
						<tr>
							<th>
								<label for="send_product_review_invitation">Queue Products Review Emails: </label>
			                    <p style="font-size:12px;font-weight:100;">Product Review Invitations will be queued when orders are dispatched.</p>
							</th>
							<td>
								<?php
									$send_product_review_invitation = get_option('send_product_review_invitation');
								?>
								<select name="send_product_review_invitation">
									<option <?php echo ($send_product_review_invitation == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($send_product_review_invitation == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
								<label for="send_merchant_review_invitation">Queue Merchant Review Emails: </label>
			                    <p style="font-size:12px;font-weight:100;">Review Invitations will be queued when orders are dispatched.</p>
							</th>
							<td>
								<?php
									$send_merchant_review_invitation = get_option('send_merchant_review_invitation');
								?>
								<select name="send_merchant_review_invitation">
									<option <?php echo ($send_merchant_review_invitation == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($send_merchant_review_invitation == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
					</table>

					<p>The invitation delay can be changed within the Reviews.co.uk Dashboard in <strong>Company Setup</strong> &gt; <strong>Customize Review Invitation</strong> and <strong>Product Setup</strong> &gt; <strong>Customize Review Invitation</strong><strong>
		        </div>
		        <div id="tab-3" class="tab-content">
					<p>Customize how product reviews are published on your website.</p>

					<table class="form-table">
						<tr>
							<th>
								<label for="product_review_widget">Show Product Review Widget: </label>
			                    <p style="font-size:12px;font-weight:100;">Product reviews will be displayed at the bottom of your product pages.</p>
							</th>
							<td>
								<?php
									$product_review_widget = get_option('product_review_widget');
								?>
								<select name="product_review_widget">
									<option <?php echo ($product_review_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Reviews Tab</option>
									<option <?php echo ($product_review_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
									<option <?php echo ($product_review_widget == '0') ? 'selected' : '' ?> value="0">Dont Display</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
								<label for="widget_hex_colour">Widget Hex Colour: </label>
			                    <p style="font-size:12px;font-weight:100;">This will be used as the primary colour of your product review widget.</p>
							</th>
							<td>
								<?php
									$widget_hex_colour = get_option('widget_hex_colour');
								?>
								<input type="text" name="widget_hex_colour" value="<?php  echo $widget_hex_colour; ?>" />
							</td>
						</tr>
						<tr>
							<th>
			                    <label for="hide_write_review_button">Hide Write Review Button: </label>
			                    <p style="font-size:12px;font-weight:100;">Write a Review Button will be hidden on product page.</p>
							</th>
							<td>
								<?php
								$hide_write_review_button = get_option('hide_write_review_button');
								?>
								<select name="hide_write_review_button">
									<option <?php echo ($hide_write_review_button == 1) ? 'selected' : '' ?> value="1">Hide Button</option>
									<option <?php echo ($hide_write_review_button == 0) ? 'selected' : '' ?> value="0">Show Button</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
			                    <label for="enable_product_rating_snippet">Enable Product Rating Snippet: </label>
			                    <p style="font-size:12px;font-weight:100;">When enabled a star rating will be displayed below the product title providing the product has reviews.</p>
							</th>
							<td>
								<?php
								$enable_product_rating_snippet = get_option('enable_product_rating_snippet');
								?>
								<select name="enable_product_rating_snippet">
									<option <?php echo ($enable_product_rating_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enable_product_rating_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
					</table>
		        </div>
		        <div id="tab-4" class="tab-content">

					<!-- Rich Snippets -->
					<table class="form-table">
						<tr>
							<th>
			                    <label for="enable_rich_snippet">Enable Merchant Rich Snippet: </label>
			                    <p style="font-size:12px;font-weight:100;">This rich snippet will give you stars on natural search results.</p>
							</th>
							<td>
								<?php
								$enable_rich_snippet = get_option('enable_rich_snippet');
								?>
								<select name="enable_rich_snippet">
									<option <?php echo ($enable_rich_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enable_rich_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
								<label for="enable_product_rich_snippet">Enable Product Rich Snippet: </label>
			                    <p style="font-size:12px;font-weight:100;">The product rich snippet will give you stars on natural search results for your product pages.</p>
							</th>
							<td>
								<?php
								$enable_product_rich_snippet = get_option('enable_product_rich_snippet');
								?>
								<select name="enable_product_rich_snippet">
									<option <?php echo ($enable_product_rich_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enable_product_rich_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
			                    <label for="enable_floating_widget">Enable Floating Widget: </label>
			                    <p style="font-size:12px;font-weight:100;">A floating reviews tab will be added to the right side of your site. This is highly recommended if you use Merchant Rich Snippets.</p>
							</th>
							<td>
								<?php
								$enable_floating_widget = get_option('enable_floating_widget');
								?>
								<select name="enable_floating_widget">
									<option <?php echo ($enable_floating_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enable_floating_widget == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
					</table>
		        </div>
		        <div id="tab-5" class="tab-content">
					<!-- Data Feeds-->
					<table class="form-table">
						<tr>
							<th>
								<label for="product_feed">Enable Product Feed: </label>
			                    <p style="font-size:12px;font-weight:100;">For best integration we require a feed of your products. If you enable this feature a product feed will be available at: <b>http://<?php echo $_SERVER['HTTP_HOST']; ?>/index.php/reviews/product_feed</b>. You can add this in the Reviews.co.uk Dashboard at Product Setup -&gt; Product Catalog -&gt; Add Product</p>
							</th>
							<td>
								<?php
									$enableProductFeed = get_option('product_feed');
								?>
								<select name="product_feed">
									<option <?php echo ($enableProductFeed == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enableProductFeed == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
								<label>Latest Orders CSV:</label>
			                    <p style="font-size:12px;font-weight:100;">Download this CSV and upload it to the <b>Review Booster</b> in the Reviews.co.uk Dashboard to start collecting reviews.</p>
							</th>
							<td>
								<a class="button" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/index.php/reviews/order_csv">Download Latest Orders CSV</a>
							</td>
						</tr>
					</table>
		        </div>
		        <div id="tab-6" class="tab-content">
					<!-- Advanced -->
					<table class="form-table">
						<tr>
							<th>
								<label for="disable_reviews_per_product">Disable Reviews Per Product: </label>
			                    <p style="font-size:12px;font-weight:100;">If this is enabled then you can use the WooCommerce "Reviews Enabled" setting to disable review collection for certain products</p>
							</th>
							<td>
								<?php
									$disable_reviews_per_product = get_option('disable_reviews_per_product');
								?>
								<select name="disable_reviews_per_product">
									<option <?php echo ($disable_reviews_per_product== 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($disable_reviews_per_product== 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
								<label for="enable_cron">Enable Cron: </label>
			                    <p style="font-size:12px;font-weight:100;">If you use a third party system to mark orders as completed then review invitations may not be triggered. If this setting is enabled a cron will run hourly which queues invitations for recently completed orders. <br /><br /> To prevent the cron running on visitor page loads you should disable WP_CRON and setup a real cron as described here: https://easyengine.io/tutorials/wordpress/wp-cron-crontab/.</p>
							</th>
							<td>
								<?php
									$enableCron = get_option('enable_cron');
								?>
								<select name="enable_cron">
									<option <?php echo ($enableCron== 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enableCron== 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
								<label for="product_identifier">Change Product Identifier</label>
								<p style="font-size:12px;font-weight:100;">Use a different identifier for your products and variants. This identifier will be used for new invitations and for looking up existing reviews.</p>
							</th>
							<td>
								<?php
								$product_identifier = get_option('product_identifier');
								?>
								<select name="product_identifier">
									<option <?php echo ($product_identifier == 'sku') ? 'selected' : '' ?> value="sku">SKU (Recommended)</option>
									<option <?php echo ($product_identifier == 'id') ? 'selected' : '' ?> value="id">ID</option>
								</select>
							</td>
						</tr>
					</table>
		        </div>
		    </div>
		</div>

		<script type="text/javascript">
		jQuery(document).ready(function() {
		    jQuery(".tabs-menu a").click(function(event) {
		        event.preventDefault();
		        jQuery(this).parent().addClass("current");
		        jQuery(this).parent().siblings().removeClass("current");
		        var tab = jQuery(this).attr("href");
		        jQuery(".tab-content").not(tab).css("display", "none");
		        jQuery(tab).fadeIn();
		    });
		});
		</script>

		<style type="text/css">
		.tabs-menu {
		    height: 30px;
		    clear: both;
			margin:0;
		}

		.tabs-menu li {
		    height: 30px;
		    line-height: 30px;
		    float: left;
			margin-bottom:-2px;
		    margin-right: 10px;
		    background-color: #0085ba;
		    border-top: 1px solid #d4d4d1;
		    border-right: 1px solid #d4d4d1;
		    border-left: 1px solid #d4d4d1;
			outline:0;
		}

		.tabs-menu li.current {
		    position: relative;
		    background-color: #fff;
		    border-bottom: 1px solid #fff;
		    z-index: 5;
		}

		.tabs-menu li a {
		    padding: 10px;
		    text-transform: uppercase;
		    color: #fff;
		    text-decoration: none;
			box-shadow:none;
			outline:0;
		}

		.tabs-menu .current a {
		    color: #2e7da3;
		}

		.tab {
			clear: both;
		    border: 1px solid #d4d4d1;
		    background-color: #fff;
		    margin-bottom: 20px;
		    width: auto;
		}

		.tab-content {
		    width: 660px;
		    padding: 20px;
		    display: none;
		}

		#tab-1 {
		 display: block;
		}
		</style>
		<?php @submit_button(); ?>

		<script>
			jQuery.ajax({
				url: 'https://api.reviews.co.uk/merchant/latest?store=<?php echo get_option('store_id'); ?>',
				success: function(data){
					if(data.stats.total_reviews > 0){
						var message = '<p>Rated <strong>' + data.stats.average_rating + ' stars (' + data.word + ')</strong> based on <strong>' + data.stats.total_reviews + '</strong> Merchant Reviews.</p>';
						jQuery('#welcomeText').html(message);
					}
				}
			});
		</script>
	</form>
</div>
