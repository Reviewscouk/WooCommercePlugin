<div class="wrap">
	    <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/logo.svg'; ?>" height="40"  style="margin-top:15px;" />
	<form method="post" action="options.php" autocomplete="off">
		<h2></h2><!-- Alerts Show Here -->

		<?php @settings_fields('woocommerce-reviews'); ?>
		<?php @do_settings_sections('woocommerce-reviews'); ?>

		<div style="background:#fff; padding:20px; margin:20px 0; border:1px solid #ccc;">
			<h2>Automated Review Collection - Powered by Reviews.co.uk</h2>

			<div id="welcomeText">
				<p>Enter your API Credentials to Start Collecting Reviews from your Customers.</p>
			</div>
		</div>


		<?php
			$hide_legacy = get_option('hide_legacy');
 		?>

		<div id="tabs-container">
		    <ul class="tabs-menu">
		        <li class="current"><a href="#tab-1">API Settings</a></li>
		        <li><a href="#tab-2">Review Invitations</a></li>
		        <li><a href="#tab-3">Product Reviews</a></li>
		        <li><a href="#tab-qa">Q&A</a></li>
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
						<?php
							$polaris_review_widget = get_option('polaris_review_widget');
						?>
						<tr>
							 	<th>
								<label for="polaris_review_widget">Show <strong>New</strong> Product Review Widget: </label>
				                    <p style="font-size:12px;font-weight:100;">
															A mobile friendly product reviews widget displaying product & customer attributes, photos and videos.</p>
							</th>
							<td>

								<select name="polaris_review_widget">
									<option <?php echo ($polaris_review_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Reviews Tab</option>
									<option <?php echo ($polaris_review_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
									<option <?php echo ($polaris_review_widget == '0') ? 'selected' : '' ?> value="0">Do Not Display</option>
								</select>
							</td>
						</tr>

						<?php
							$product_review_widget = get_option('product_review_widget');
							if(!$hide_legacy) {
						?>
						<tr style="border-top: 1px solid #e4e4e4;">

							 	<th>
									<h3><strong>Legacy Widget Settings:</strong></h3>

								<label for="product_review_widget">Show Legacy Product Review Widget: </label>
			                    <p style="font-size:12px;font-weight:100;">Please note that to use the Legacy Widget, the "Show New Product Review Widget" setting must be set to "Do Not Display".</p>
							</th>
							<td>

								<select name="product_review_widget">
									<option <?php echo ($product_review_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Reviews Tab</option>
									<option <?php echo ($product_review_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
                                    <option <?php echo ($product_review_widget == 'both') ? 'selected' : '' ?> value="both">Show in Both Places</option>
									<option <?php echo ($product_review_widget == '0') ? 'selected' : '' ?> value="0">Do Not Display</option>
								</select>
							</td>
						</tr>

						<tr style="border-bottom: 1px solid #e4e4e4">
							<th>
								<label for="widget_custom_css">Widget Custom CSS: </label>
													<p style="font-size:12px;font-weight:100;">Add custom CSS to the product reviews widget</p>
							</th>
							<td>
								<?php
									$widget_custom_css = get_option('widget_custom_css');
								?>
								<textarea name="widget_custom_css" style="width:300px;height:200px;"><?php echo htmlentities($widget_custom_css); ?></textarea>
							</td>
						</tr>


						<?php
						}
						?>
						<tr>
							<th>
								<label for="disable_rating_snippet_popup">Offset: (Default = 0)</label>
			                    <p style="font-size:12px;font-weight:100;">This option set the offset to the product widget element. (Integer Number)</p>
							</th>
							<td>
								<?php
									$disable_rating_snippet_offset = get_option('disable_rating_snippet_offset');
								?>
								<input type="text" name="disable_rating_snippet_offset" value="<?php  echo $disable_rating_snippet_offset; ?>" />
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




						<tr style="border-top: 1px solid #e4e4e4;">
							<th>
								<h3><strong>Rating Snippet Settings:</strong></h3>

			                    <label for="enable_product_rating_snippet">Enable Product Rating Snippet: </label>
			                    <p style="font-size:12px;font-weight:100;">When enabled a star rating will be displayed below the product title providing the product has reviews.<br /><br />If you would like to change how the rating is displaying you can choose the manual setting and use shortcode [rating_snippet] to display the rating.</p>
							</th>
							<td>
								<?php
								$enable_product_rating_snippet = get_option('enable_product_rating_snippet');
								?>
								<select name="enable_product_rating_snippet">
									<option <?php echo ($enable_product_rating_snippet == 1) ? 'selected' : '' ?> value="1">Enabled</option>
									<option <?php echo ($enable_product_rating_snippet == 0) ? 'selected' : '' ?> value="0">Disabled</option>
									<option <?php echo ($enable_product_rating_snippet == 'manual') ? 'selected' : '' ?> value="manual">Manual</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
													<label for="enable_product_rating_snippet">Rating Snippet Linebreak: </label>
													<p style="font-size:12px;font-weight:100;">Adds a line break between rating stars and text.</p>
							</th>
							<td>
								<?php
								$rating_snippet_no_linebreak = get_option('rating_snippet_no_linebreak');
								?>
								<select name="rating_snippet_no_linebreak">
									<option <?php echo ($rating_snippet_no_linebreak == 0) ? 'selected' : '' ?> value="0">Enabled (Default)</option>
									<option <?php echo ($rating_snippet_no_linebreak == 1) ? 'selected' : '' ?> value="1">Disabled</option>
								</select>
							</td>
						</tr>

						<tr style="border-bottom: 1px solid #e4e4e4;">
							<th>
								<label for="disable_rating_snippet_popup">Disable/Enable the rating snippet popup: </label>
			                    <p style="font-size:12px;font-weight:100;">This option will disable/enable the rating snippet popup on product pages and anchor to product widget.</p>
							</th>
							<td>
								<?php
									$disable_rating_snippet_popup = get_option('disable_rating_snippet_popup');
								?>
								<select name="disable_rating_snippet_popup">
									<option <?php echo ($disable_rating_snippet_popup == '0') ? 'selected' : '' ?> value="0">Popup Disabled</option>
									<option <?php echo ($disable_rating_snippet_popup == '1') ? 'selected' : '' ?> value="1">Popup Enabled</option>
								</select>
							</td>
						</tr>
					</table>
		        </div>
		        <div id="tab-qa" class="tab-content">
					<p>Allow your visitors to ask questions about your products. Your answers will be published publicly.</p>

					<table class="form-table">
						<tr>
							<th>
								<label for="product_review_widget">Show Question Answers Widget: </label>
			                    <p style="font-size:12px;font-weight:100;">The widget will be displayed in a tab on your product pages.</p>
							</th>
							<td>
								<?php
									$question_answers_widget = get_option('question_answers_widget');
								?>
								<select name="question_answers_widget">
									<option <?php echo ($question_answers_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Tab</option>
									<option <?php echo ($question_answers_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
									<option <?php echo ($question_answers_widget == 'both') ? 'selected' : '' ?> value="both">Show in Both Places</option>
									<option <?php echo ($question_answers_widget == '0') ? 'selected' : '' ?> value="0">Dont Display</option>
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
								<a class="button" href="//<?php echo $_SERVER['HTTP_HOST']; ?>/index.php/reviews/order_csv">Download Latest Orders CSV</a>
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
						<tr>
							<th>
								<label for="use_parent_product">Use Parent Product</label>
								<p style="font-size:12px;font-weight:100;">Enable this if you would like to only collect reviews on a parent product level. This is useful if you have products with many variations and you want to keep the data more manageable. </p>
							</th>
							<td>
								<?php
								$use_parent_product = get_option('use_parent_product');
								?>
								<select name="use_parent_product">
									<option <?php echo ($use_parent_product== 0) ? 'selected' : '' ?> value="0">No (Default)</option>
									<option <?php echo ($use_parent_product== 1) ? 'selected' : '' ?> value="1">Yes</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
								<label for="hide_legacy">Show Legacy Widget Settings</label>
								<p style="font-size:12px;font-weight:100;">Enable this if you would like to use the legacy product widget. </p>
							</th>
							<td>
								<select name="hide_legacy">
									<option <?php echo ($hide_legacy== 1) ? 'selected' : '' ?> value="1">No (Recommended)</option>
									<option <?php echo ($hide_legacy== 0) ? 'selected' : '' ?> value="0">Yes</option>
								</select>
							</td>
						</tr>
					</table>
		        </div>
		    </div>
		</div>

		<?php add_action('admin_enqueue_scripts','reviewsio_admin_scripts'); ?>
		<?php @submit_button(); ?>
	</form>
</div>
