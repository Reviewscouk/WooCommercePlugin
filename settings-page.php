<?php
if(!defined('ABSPATH')) {
  exit;
}
?>

<div class="wrap">
	<img src="https://assets.reviews.io/img/all-global-assets/logo/reviewsio-logo.svg" height="40"  style="margin-top:15px;" />
	<form method="post" action="options.php" autocomplete="off">
		<h2></h2><!-- Alerts Show Here -->

		<?php @settings_fields('woocommerce-reviews'); ?>
		<?php @do_settings_sections('woocommerce-reviews'); ?>

		<div style="background:#fff; padding:20px; margin:20px 0; box-shadow: 0 2px 10px -2px rgb(0 0 0 / 7%); border-radius: 4px;">
			<h2>Automated Review Collection - Powered by REVIEWS.io</h2>

			<div id="welcomeText">
				<p>Enter your API Credentials to Start Collecting Reviews from your Customers.</p>
			</div>
		</div>

		 <input hidden name='REVIEWSio_new_variables_set' value='1' />


		<?php
			$hide_legacy = get_option('REVIEWSio_hide_legacy');
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
					<p>You can find your API credentials on the REVIEWS.io Dashboard. Click the <b>Integrations</b> menu, and then scroll to <b>WooCommerce</b>.</p>

					<table class="form-table">
						<tr>
							<th>
								<label for="REVIEWSio_store_id">Store ID: </label>

			                    	</th>
							<td>
								<?php
								$store_id = get_option('REVIEWSio_store_id');
								?>
								<input type="text" name="REVIEWSio_store_id" value="<?php  echo $store_id; ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="REVIEWSio_api_key">API Key: </label>
							</th>
							<td>
								<?php
									$api_key = get_option('REVIEWSio_api_key');
								?>
								<input type="text" name="REVIEWSio_api_key" value="<?php  echo $api_key; ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="REVIEWSio_region">Region: </label>
							</th>
							<td>
								<?php
								$region = get_option('REVIEWSio_region');
								?>
								<select name="REVIEWSio_region">
									<option <?php echo ($region == 'uk') ? 'selected' : '' ?> value="uk">UK</option>
									<option <?php echo ($region == 'us') ? 'selected' : '' ?> value="us">US</option>
								</select>
							</td>
						</tr>
					</table>

					<p><strong>Not a REVIEWS.io Customer?</strong></p>
					<p>
						You can sign up for a REVIEWS.io plan here:
						<a href='https://www.reviews.io/business-solutions' target="_blank">UK</a> or
						<a href='https://www.reviews.io/business-solutions' target="_blank">International</a>
					</p>
		        </div>
		        <div id="tab-2" class="tab-content">
					<!-- Review Collection -->
					<table class="form-table">
						<tr>
							<th>
								<label for="REVIEWSio_send_product_review_invitation">Queue Invitations: </label>
			                    <p style="font-size:12px;font-weight:100;">Invitations will be queued when orders are Completed.</p>
							</th>
							<td>
								<?php
									$send_product_review_invitation = get_option('REVIEWSio_send_product_review_invitation');
								?>
								<select name="REVIEWSio_send_product_review_invitation">
									<option <?php echo ($send_product_review_invitation == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($send_product_review_invitation == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
					</table>

					<p>The invitation delay can be changed within the REVIEWS.io Dashboard under the <strong>Invitations</strong> menu, or from your <strong>Flow</strong>.
		        </div>
		        <div id="tab-3" class="tab-content">
					<p>Customise how product reviews are published on your website.</p>

					<table class="form-table">
						<?php
							$polaris_review_widget = get_option('REVIEWSio_polaris_review_widget');
						?>
						<tr>
							 	<th>
									<h3><strong>Product Review Widget Settings:</strong></h3>

								<label for="REVIEWSio_polaris_review_widget">Show Product Review Widget: </label>
				                    <p style="font-size:12px;font-weight:100;">
															A mobile friendly product reviews widget displaying product & customer attributes, photos and videos.</p>
							</th>
							<td>

								<select name="REVIEWSio_polaris_review_widget">
									<option <?php echo ($polaris_review_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Tab</option>
									<option <?php echo ($polaris_review_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
									<option <?php echo ($polaris_review_widget == 'bottom') ? 'selected' : '' ?> value="bottom">Show At Bottom of Page</option>
									<option <?php echo ($polaris_review_widget == '0') ? 'selected' : '' ?> value="0">Do Not Display</option>
								</select>
							</td>
						</tr>

						<?php if($polaris_review_widget == 'tab') { ?>
							<tr>
								<th>
										<label for="REVIEWSio_enable_product_rating_snippet">Review Tab Text: </label>
										<p style="font-size:12px;font-weight:100;">Sets the name of the review tab.</p>
								</th>
								<td>
									<?php
									$reviews_tab_name = get_option('REVIEWSio_reviews_tab_name');
									?>
									<input name='REVIEWSio_reviews_tab_name' value='<?php echo (!empty($reviews_tab_name) ? $reviews_tab_name : 'Reviews'); ?>'>
									</td>
							</tr>

						<?php } ?>





						<?php
							$product_review_widget = get_option('REVIEWSio_product_review_widget');
							if(!$hide_legacy) {
						?>
						<tr style="border-top: 1px solid #e4e4e4;">

							 	<th>
									<h3><strong>Legacy Widget Settings:</strong></h3>

								<label for="REVIEWSio_product_review_widget">Show Legacy Product Review Widget: </label>
			                    <p style="font-size:12px;font-weight:100;">Please note that to use the Legacy Widget, the "Show Product Review Widget" setting must be set to "Do Not Display".</p>
							</th>
							<td>

								<select name="REVIEWSio_product_review_widget">
									<option <?php echo ($product_review_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Reviews Tab</option>
									<option <?php echo ($product_review_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
                                    <option <?php echo ($product_review_widget == 'both') ? 'selected' : '' ?> value="both">Show in Both Places</option>
									<option <?php echo ($product_review_widget == '0') ? 'selected' : '' ?> value="0">Do Not Display</option>
								</select>
							</td>
						</tr>

						<tr style="border-bottom: 1px solid #e4e4e4">
							<th>
								<label for="REVIEWSio_widget_custom_css">Widget Custom CSS: </label>
													<p style="font-size:12px;font-weight:100;">Add custom CSS to the product reviews widget</p>
							</th>
							<td>
								<?php
									$widget_custom_css = get_option('REVIEWSio_widget_custom_css');
								?>
								<textarea name="REVIEWSio_widget_custom_css" style="width:300px;height:200px;"><?php echo htmlentities($widget_custom_css); ?></textarea>
							</td>
						</tr>


						<?php
						}
						?>





						<tr style="border-top: 1px solid #e4e4e4;">
							<th>
								<h3><strong>Rating Snippet Settings:</strong></h3>

			                    <label for="REVIEWSio_enable_product_rating_snippet">Enable Product Rating Snippet: </label>
			                    <p style="font-size:12px;font-weight:100;">When enabled a star rating will be displayed below the product title providing the product has reviews.<br /><br />If you would like to change how the rating is displaying you can choose the manual setting and use shortcode [rating_snippet] to display the rating.</p>
							</th>
							<td>
								<?php
								$enable_product_rating_snippet = get_option('REVIEWSio_enable_product_rating_snippet');
								?>
								<select name="REVIEWSio_enable_product_rating_snippet">
									<option <?php echo ($enable_product_rating_snippet == 1) ? 'selected' : '' ?> value="1">Enabled</option>
									<option <?php echo ($enable_product_rating_snippet == 0) ? 'selected' : '' ?> value="0">Disabled</option>
									<option <?php echo ($enable_product_rating_snippet == 'manual') ? 'selected' : '' ?> value="manual">Manual</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
													<label for="REVIEWSio_enable_product_rating_snippet">Rating Snippet Linebreak: </label>
													<p style="font-size:12px;font-weight:100;">Adds a line break between rating stars and text.</p>
							</th>
							<td>
								<?php
								$rating_snippet_no_linebreak = get_option('REVIEWSio_rating_snippet_no_linebreak');
								?>
								<select name="REVIEWSio_rating_snippet_no_linebreak">
									<option <?php echo ($rating_snippet_no_linebreak == 0) ? 'selected' : '' ?> value="0">Enabled (Default)</option>
									<option <?php echo ($rating_snippet_no_linebreak == 1) ? 'selected' : '' ?> value="1">Disabled</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
									<label for="REVIEWSio_enable_product_rating_snippet">Rating Snippet Text: </label>
									<p style="font-size:12px;font-weight:100;">Sets the descriptor after the number of reviews on the Rating Snippet.</p>
							</th>
							<td>
								<?php
								$rating_snippet_text = get_option('REVIEWSio_rating_snippet_text');
								?>
								<input name='REVIEWSio_rating_snippet_text' value='<?php echo (isset($rating_snippet_text) ? $rating_snippet_text : 'Reviews'); ?>'>
 								</td>
						</tr>

						<tr>
							<th>
								<label for="REVIEWSio_disable_rating_snippet_popup">Rating Snippet Popup: </label>
			                    <p style="font-size:12px;font-weight:100;">Disable or Enable the Rating Snippet Popup..</p>
							</th>
							<td>
								<?php
									$disable_rating_snippet_popup = get_option('REVIEWSio_disable_rating_snippet_popup');
								?>
								<select name="REVIEWSio_disable_rating_snippet_popup">
									<option <?php echo ($disable_rating_snippet_popup == '0') ? 'selected' : '' ?> value="0">Disabled (Anchor to Product Review Widget)</option>
									<option <?php echo ($disable_rating_snippet_popup == '1') ? 'selected' : '' ?> value="1">Enabled</option>
								</select>
							</td>
						</tr>


						<tr style="border-top: 1px solid #e4e4e4;">
							<th>
								<h3><strong>Customise Widgets:</strong></h3>

						    <label for="REVIEWSio_minimum_rating">Widget Language</label>
						    <p style="font-size:12px;font-weight:100;">Set the Language of the Product Review Widget and Rating Snippet popup.</p>
						  </th>
						  <td>
						    <?php
						      $polaris_lang = get_option('REVIEWSio_polaris_lang');
						    ?>
						    <select name='REVIEWSio_polaris_lang'>
						      <?php
						        foreach(['English (Default)' => 'en', 'Deutsch' => 'de', 'Deutsch (Informal)' => 'de-informal', 'Español' => 'es', 'Français' => 'fr',
						        'Italiano' => 'it', 'Nederlands' => 'nl', 'Suomi' => 'fi'] as $key => $value) {
						        ?>
						        <option <?php echo ($value == $polaris_lang ? 'selected' : ''); ?> value='<?php echo $value; ?>'>
						          <?php echo $key; ?>
						        </option>
						        <?php
						          }
						          ?>
						    </select>
						  </td>
						</tr>
						<tr>
						  <th>
						    <label for="REVIEWSio_minimum_rating">Minimum Review Rating</label>
						              <p style="font-size:12px;font-weight:100;">This option sets the minimum star rating of reviews displayed.</p>
						  </th>
						  <td>
						    <?php
						      $minimum_rating = get_option('REVIEWSio_minimum_rating');
						    ?>
						    <select name='REVIEWSio_minimum_rating'>
						      <?php
						        foreach(['None (Default)' => 1, '2 Stars' => 2, '3 Stars' => 3, '4 Stars' => 4, '5 Stars' => 5] as $key => $value) {
						        ?>
						        <option <?php echo ($value == $minimum_rating ? 'selected' : ''); ?> value='<?php echo $value; ?>'>
						          <?php echo $key; ?>
						        </option>
						        <?php
						          }
						          ?>
						    </select>
						  </td>
						</tr>

						<tr>
						  <th>
						    <label for="REVIEWSio_disable_rating_snippet_popup">Offset: (Default = 0)</label>
						              <p style="font-size:12px;font-weight:100;">This option sets the offset to the product widget element (Integer Number).</p>
						  </th>
						  <td>
						    <?php
						      $disable_rating_snippet_offset = get_option('REVIEWSio_disable_rating_snippet_offset');
						    ?>
						    <input type="text" name="REVIEWSio_disable_rating_snippet_offset" value="<?php  echo $disable_rating_snippet_offset; ?>" />
						  </td>
						</tr>

						<tr>
						  <th>
						    <label for="REVIEWSio_widget_hex_colour">Star Colour: </label>
						              <p style="font-size:12px;font-weight:100;">Sets the primary colour for your widgets, including the stars.</p>
						  </th>
						  <td>
						    <?php
						      $widget_hex_colour = get_option('REVIEWSio_widget_hex_colour');
						    ?>
						    <input type="text" name="REVIEWSio_widget_hex_colour" value="<?php  echo $widget_hex_colour; ?>" />
						  </td>
						</tr>

						<tr>
						  <th>
		              <label for="REVIEWSio_hide_write_review_button">Hide Write Review Button: </label>
		              <p style="font-size:12px;font-weight:100;">Write a Review Button will be hidden on your widgets.</p>
						  </th>
						  <td>
						    <?php
						    $hide_write_review_button = get_option('REVIEWSio_hide_write_review_button');
						    ?>
						    <select name="REVIEWSio_hide_write_review_button">
						      <option <?php echo ($hide_write_review_button == 1) ? 'selected' : '' ?> value="1">Hide Button</option>
						      <option <?php echo ($hide_write_review_button == 0) ? 'selected' : '' ?> value="0">Show Button</option>
						    </select>
						  </td>
						</tr>

						<tr>
						  <th>
		              <label for="REVIEWSio_per_page_review_widget">Reviews Per Page: </label>
		              <p style="font-size:12px;font-weight:100;">The amount of reviews displayed per page on the Product Review Widget.</p>
						  </th>
						  <td>
						    <?php
						    $per_page_review_widget = get_option('REVIEWSio_per_page_review_widget');
						    ?>
						    <input value='<?php echo (!empty($per_page_review_widget) ? $per_page_review_widget : 8); ?>' type='number' min='0' max='30' name="REVIEWSio_per_page_review_widget">
						  </td>
						</tr>
					</table>
		        </div>
		        <div id="tab-qa" class="tab-content">
					<p>Allow your visitors to ask questions about your products. Your answers will be published publicly.</p>

					<?php
							if(!$hide_legacy) {
					 ?>
					<table class="form-table">
						<tr>
							<th>
								<label for="REVIEWSio_product_review_widget">Show Legacy Question Answers Widget: </label>
			                    <p style="font-size:12px;font-weight:100;">The widget will be displayed in a tab on your product pages.</p>
							</th>
							<td>
								<?php
									$question_answers_widget = get_option('REVIEWSio_question_answers_widget');
								?>
								<select name="REVIEWSio_question_answers_widget">
									<option <?php echo ($question_answers_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Tab</option>
									<option <?php echo ($question_answers_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
									<option <?php echo ($question_answers_widget == 'both') ? 'selected' : '' ?> value="both">Show in Both Places</option>
									<option <?php echo ($question_answers_widget == '0') ? 'selected' : '' ?> value="0">Dont Display</option>
								</select>
							</td>
						</tr>
					</table>

					<?php
							} else {
					 ?>

					 <table class="form-table">
						 <tr>
 							<th>
                <label for="REVIEWSio_enable_rich_snippet">Enable Q&A: </label>
                <p style="font-size:12px;font-weight:100;">This will add a Q&A Tab to your Product Review Widget.</p>
 							</th>
 							<td>
 								<?php
 								$polaris_review_widget_questions = get_option('REVIEWSio_polaris_review_widget_questions');
 								?>
 								<select name="REVIEWSio_polaris_review_widget_questions">
 									<option <?php echo ($polaris_review_widget_questions == 1) ? 'selected' : '' ?> value="1">Yes</option>
 									<option <?php echo ($polaris_review_widget_questions == 0) ? 'selected' : '' ?> value="0">No</option>
 								</select>
 							</td>
 						</tr>
					 </table>

				 <?php
			 			}
					?>


		        </div>
		        <div id="tab-4" class="tab-content">

					<!-- Rich Snippets -->
					<table class="form-table">
						<!-- <tr>
							<th>
			                    <label for="REVIEWSio_enable_rich_snippet">Enable Company Rich Snippet: </label>
							</th>
							<td>
								<?php
								$enable_rich_snippet = get_option('REVIEWSio_enable_rich_snippet');
								?>
								<select name="REVIEWSio_enable_rich_snippet">
									<option <?php echo ($enable_rich_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enable_rich_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr> -->
						<tr>
							<th>
								<label for="REVIEWSio_enable_product_rich_snippet">Enable Product Rich Snippet: </label>
			                    <p style="font-size:12px;font-weight:100;">The product rich snippet will give you stars on natural search results for your product pages.</p>
							</th>
							<td>
								<?php
								$enable_product_rich_snippet = get_option('REVIEWSio_enable_product_rich_snippet');
								?>
								<select name="REVIEWSio_enable_product_rich_snippet">
									<option <?php echo ($enable_product_rich_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enable_product_rich_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
			                    <label for="REVIEWSio_enable_floating_widget">Enable Floating Widget: </label>
			                    <p style="font-size:12px;font-weight:100;">A floating reviews tab will be added to the right side of your site.</p>
							</th>
							<td>
								<?php
								$enable_floating_widget = get_option('REVIEWSio_enable_floating_widget');
								?>
								<select name="REVIEWSio_enable_floating_widget">
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
								<label for="REVIEWSio_product_feed">Enable Product Feed: </label>
			                    <p style="font-size:12px;font-weight:100;">For Product Invitations to queue correctly, we require access to your Product catalogue via a feed, which we will make available from <b><?php echo get_site_url(); ?>/index.php/reviews/product_feed</b>.</p>
							</th>
							<td>
								<?php
									$enableProductFeed = get_option('REVIEWSio_product_feed');
								?>
								<select name="REVIEWSio_product_feed">
									<option <?php echo ($enableProductFeed == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enableProductFeed == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
            <tr>
							<th>
								<label for="REVIEWSio_product_feed_custom_attributes">Include Product Data Attributes Feed: </label>
			                    <p style="font-size:12px;font-weight:100;">Add additional product data attributes field to be included as columns in your product feed. The following are always include by default: _barcode, barcode, _gtin, gtin, mpn, _mpn</p>
							</th>
              <td>
                <?php
                  $product_feed_custom_attributes = get_option('REVIEWSio_product_feed_custom_attributes');
                ?>
                <textarea name="REVIEWSio_product_feed_custom_attributes" style="width:300px;height:200px;" placeholder="_barcode, barcode, _gtin, gtin, mpn', _mpn"><?php echo htmlentities($product_feed_custom_attributes); ?></textarea>
              </td>
						</tr>

						<tr>
							<th>
								<label>Latest Orders CSV:</label>
			                    <p style="font-size:12px;font-weight:100;">Download this CSV and upload it to the <b>Review Booster</b> section in the REVIEWS.io Dashboard to start collecting reviews.</p>
							</th>
							<td>
								<a class="button" href="<?php echo get_site_url(); ?>/index.php/reviews/order_csv">Download Latest Orders CSV</a>
							</td>
						</tr>
					</table>
		        </div>
		        <div id="tab-6" class="tab-content">
					<!-- Advanced -->
					<table class="form-table">
						<tr>
							<th>
								<label for="REVIEWSio_disable_reviews_per_product">Disable Reviews Per Product: </label>
			                    <p style="font-size:12px;font-weight:100;">If this is enabled then you can use the WooCommerce "Reviews Enabled" setting to disable review collection for certain products.</p>
							</th>
							<td>
								<?php
									$disable_reviews_per_product = get_option('REVIEWSio_disable_reviews_per_product');
								?>
								<select name="REVIEWSio_disable_reviews_per_product">
									<option <?php echo ($disable_reviews_per_product== 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($disable_reviews_per_product== 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
								<label for="REVIEWSio_enable_cron">Enable Cron: </label>
			                    <p style="font-size:12px;font-weight:100;">If you use a third party system to mark orders as completed then review invitations may not be triggered. If this setting is enabled a cron will run hourly which queues invitations for recently completed orders. <br /><br /> To prevent the cron running on visitor page loads you should disable WP_CRON and setup a real cron as described <a target='_blank' href='https://easyengine.io/tutorials/wordpress/wp-cron-crontab/'>here</a>.</p>
							</th>
							<td>
								<?php
									$enableCron = get_option('REVIEWSio_enable_cron');
								?>
								<select name="REVIEWSio_enable_cron">
									<option <?php echo ($enableCron== 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enableCron== 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>

						<tr>
							<th>
								<label for="REVIEWSio_product_identifier">Change Product Identifier</label>
								<p style="font-size:12px;font-weight:100;">Use a different identifier for your products and variants. This identifier will be used for new invitations and for looking up existing reviews.</p>
							</th>
							<td>
								<?php
								$product_identifier = get_option('REVIEWSio_product_identifier');
								?>
								<select name="REVIEWSio_product_identifier">
									<option <?php echo ($product_identifier == 'sku') ? 'selected' : '' ?> value="sku">SKU (Recommended)</option>
									<option <?php echo ($product_identifier == 'id') ? 'selected' : '' ?> value="id">ID</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
								<label for="REVIEWSio_use_parent_product">Use Parent Product</label>
								<p style="font-size:12px;font-weight:100;">Enable this if you would like to only collect reviews on a parent product level. This is useful if you have products with many variations and you want to keep the data more manageable. </p>
							</th>
							<td>
								<?php
								$use_parent_product = get_option('REVIEWSio_use_parent_product');
								?>
								<select name="REVIEWSio_use_parent_product">
									<option <?php echo ($use_parent_product== 0) ? 'selected' : '' ?> value="0">No (Default)</option>
									<option <?php echo ($use_parent_product== 1) ? 'selected' : '' ?> value="1">Yes</option>
								</select>
							</td>
						</tr>

						<tr style="border-bottom: 1px solid #e4e4e4">
							<th>
								<label for="REVIEWSio_widget_custom_css">Advanced Product Reviews Widget Styles</label>
													<p style="font-size:12px;font-weight:100;">Sets the styles for the Product Reviews Widget. After using the designer tool, copy the "styles" block, which begins with "styles: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.</p>
							</th>
							<td>
								<?php
									$custom_reviews_widget_styles = get_option('REVIEWSio_custom_reviews_widget_styles');
								?>
								<textarea name="REVIEWSio_custom_reviews_widget_styles" style="width:300px;height:200px;"><?php echo htmlentities($custom_reviews_widget_styles); ?></textarea>
							</td>
						</tr>

						<tr>
							<th>
								<label for="REVIEWSio_hide_legacy">Show Legacy Widget Settings</label>
								<p style="font-size:12px;font-weight:100;">Enable this if you would like to use the legacy product widget. </p>
							</th>
							<td>
								<select name="REVIEWSio_hide_legacy">
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
