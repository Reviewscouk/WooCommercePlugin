<div class="wrap">
	<h2>Reviews.co.uk WooCommerce Plugin</h2>
	<form method="post" action="options.php" autocomplete="off">
		<?php @settings_fields('woocommerce-reviews'); ?>
		<?php @do_settings_fields('woocommerce-reviews'); ?>

		<div style="background:#fff; padding:20px; margin:20px 0; border:1px solid #ccc;">
			<p><strong>Introduction</strong></p>

			<p></p><strong>Already a Reviews.co.uk Customer?</strong></p>
				<p>To configure this plugin you need to have your API credentials, which can be found on your Reviews.co.uk dashboard.</p>
			<hr>
			<p><strong>Not a Reviews.co.uk Customer?</strong></p>
			<p>
				You'll need to sign up for one of our packages at <a href="http://www.reviews.co.uk" target="_blank">Reviews.co.uk</a> or <a href="http://review.io" target="_blank">Review.io</a> to use this plugin.
			</p>
		</div>


		<h2>API Settings</h2>

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
		<h2>Product Reviews Widget</h2></td>
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
		</table>
		<h2>Review Invitations</h2></td>
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
		<h2>Rich Snippets</h2></td>
		<table class="form-table">
			<tr>
				<th>
                    <label for="product_feed">Enable Merchant Rich Snippet: </label>
                    <p style="font-size:12px;font-weight:100;">The rich snippet code will be appended to the footer. You can add some rules to your css to style the #rs_container element.</p>
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
					<label for="product_feed">Enable Product Rich Snippet: </label>
                    <p style="font-size:12px;font-weight:100;">The rich snippet code will be appended to the footer. You can add some rules to your css to style the #rs_container element.</p>
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
		</table>
		<h2>Data Feeds</h2></td>
		<table class="form-table">
			<tr>
				<th>
					<label for="product_feed">Enable Product Feed: </label>
                    <p style="font-size:12px;font-weight:100;">For full integration we require a feed of your products. If you enable this feature a product feed will be available at: <b>http://<?php echo $_SERVER['HTTP_HOST']; ?>/index.php/reviews/product_feed</b>. You can add this in the Reviews.co.uk Dashboard at Product Setup -&gt; Product Catalog -&gt; Add Product</p>
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
		<?php @submit_button(); ?>
	</form>
</div>
