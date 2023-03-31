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

		<div class="ContentPanel">
			<div class="FlexTabs FlexTabs--inPanel">
				<div id="js-api-tab" class="FlexTabs__item isActive">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">API Settings</div>
				</div>
				<div id="js-invitations-tab" class="FlexTabs__item">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Review Invitations</div>
				</div>
				<div id="js-reviews-tab" class="FlexTabs__item">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Product Reviews</div>
				</div>
				<div id="js-qa-tab" class="FlexTabs__item">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Q&amp;A</div>
				</div>
				<div id="js-snippets-tab" class="FlexTabs__item">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Rich Snippets</div>
				</div>
				<div id="js-feeds-tab" class="FlexTabs__item">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Data Feeds</div>
				</div>
				<div id="js-widgets-tab" class="FlexTabs__item">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">REVIEWS.io Widgets</div>
				</div>
				<div id="js-advanced-tab" class="FlexTabs__item">
					<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Advanced</div>
				</div>
			</div>

			<div class="tab-contents js-api-tab">Tab 1</div>
			<div class="tab-contents js-invitations-tab">Tab 2</div>
			<div class="tab-contents js-reviews-tab">Tab 3</div>
			<div class="tab-contents js-qa-tab">Tab 4</div>
			<div class="tab-contents js-snippets-tab">Tab 5</div>
			<div class="tab-contents js-feeds-tab">Tab 6</div>

			<div class="tab-contents js-widgets-tab">
				<div class="TextBody TextBody--xxs">
					<p>
						Enable and customise how REVIEWS.io widgets on your website. The customisation for the widgets below can be found in the  <a href="https://dash.reviews.co.uk/widgets" target="_blank">REVIEWS.io widget library</a>.
					</p>
					<p>
						Enhance your website with dynamic content and features by making use of shortcodes - small pieces of code enclosed in square brackets. You can easily add these shortcodes using the format [widget name_widget], ie. [nuggets_widget] Our plugin offers several widgets that support shortcodes, including Nuggets, UGC, and Rating Bar.
					</p>
					<p>
						To use our shortcodes, you'll need to add a 'widget_id' - you can find this in our widget editor. The shortcode format is [widget_name widget_id='your widget id']. This is also a great way to add SKUs to your content! Simply separate each SKU with a semi-colon, like this: [widget_name widget_id='your widget id' sku='sku1;sku2;sku3'].
					</p>
				</div>
				<div class="u-hr u-marginTop--md u-marginBottom--lg"></div>
				
				<div class="flex-row">
					<div class="flex-col-xxs-3 u-paddingRight--none">
	
						<div id="nuggets-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab isActive" onclick="showWidget('nuggets')">
							<div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
								<img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.local/img/all-global-assets/icons/icon-upload-file--md--colour.svg" alt="">
								<div>
									<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Nuggets Widget</div>
								</div>
							</div>
						</div>
						<div id="floating-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('floating')">
							<div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
								<img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.local/img/all-global-assets/icons/icon-upload-file--md--colour.svg" alt="">
								<div>
									<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Floating Widget</div>
								</div>
							</div>
						</div>
						<div id="survey-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('survey')">
							<div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
								<img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.local/img/all-global-assets/icons/icon-upload-file--md--colour.svg" alt="">
								<div>
									<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Survey Widget</div>
								</div>
							</div>
						</div>
						<div id="carousel-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('carousel')">
							<div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
								<img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.local/img/all-global-assets/icons/icon-upload-file--md--colour.svg" alt="">
								<div>
									<div class="TextHeading TextHeading--xxxs u-marginBottom--none">Carousel Widget</div>
								</div>
							</div>
						</div>
					</div>
	
					<div class="flex-col-xxs-9 u-paddingLeft--none">
						<div class="ContentPanel u-shadow--none" style="box-shadow: none;">
							<div class="menu-data">
								<div id="nuggets" class="form-table js-widget">
									<h3><strong>Nuggets Widget Settings:</strong></h3>
	
									<div>
										<label for="REVIEWSio_enable_nuggets_widget">Enable Nuggets Widget: </label>
										<p style="font-size:12px;font-weight:100;">
											Use the dropdown menu to enable or disable the Nuggets widget. Select 'Yes' to enable the widget, or 'No' to disable.
										</p>
		
										<?php
											$enable_nuggets_widget = get_option('REVIEWSio_enable_nuggets_widget');
										?>
										<select id="js-nuggets" class="widget-active-state" name="REVIEWSio_enable_nuggets_widget">
											<option <?php echo ($enable_nuggets_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
											<option <?php echo ($enable_nuggets_widget == 0) ? 'selected' : '' ?> value="0">No</option>
										</select>
									</div>

									<div class="u-paddingTop--lg">
										<label for="REVIEWSio_nuggets_widget_options">Nuggets Widget Styles: </label>
										<p style="font-size:12px;font-weight:100;">
											The dropdown menu to the right contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes.
										</p>
										<?php
											$nuggets_widget_options = get_option('REVIEWSio_nuggets_widget_options');
										?>
										<input id="nuggets-widget-option" type="hidden" value="<?php echo $nuggets_widget_options ?>">
										<select id="nuggets-widget-options-dropdown" name='REVIEWSio_nuggets_widget_options'></select>
									</div>
								</div>
			
								<div id="floating" class="form-table js-widget" style="display: none">
									<div>
										`<h3><strong>Floating Widget Settings:</strong></h3>
		
										<label for="REVIEWSio_enable_floating_react_widget">Enable floating Widget: </label>
										<p style="font-size:12px;font-weight:100;">
											Use the dropdown menu to enable or disable the Floating widget. Select 'Yes' to enable the widget, or 'No' to disable.
										</p>
										
										<?php
											$enable_floating_react_widget = get_option('REVIEWSio_enable_floating_react_widget');
										?>
										<select id="js-floating" class="widget-active-state" name="REVIEWSio_enable_floating_react_widget">
											<option <?php echo ($enable_floating_react_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
											<option <?php echo ($enable_floating_react_widget == 0) ? 'selected' : '' ?> value="0">No</option>
										</select>`
									</div>
			
									<div class="u-paddingTop--lg">
										<label for="REVIEWSio_floating_react_widget_option">Floating Widget Styles: </label>
										<p style="font-size:12px;font-weight:100;">
											The dropdown menu to the right contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes.
										</p>

										<?php
											$floating_react_widget_options = get_option('REVIEWSio_floating_react_widget_options');
										?>
										<input id="floating-react-widget-option" type="hidden" value="<?php echo $floating_react_widget_options ?>">
										<select id="floating-react-widget-options-dropdown" name='REVIEWSio_floating_react_widget_options'></select>
									</div>
								</div>
			
								<div id="survey" class="form-table js-widget" style="display: none">
									<div>
										<h3><strong>Survey Settings:</strong></h3>
		
										<label for="REVIEWSio_enable_survey_widget">Enable Survey Widget: </label>
										<p style="font-size:12px;font-weight:100;">
											Use the dropdown menu to enable or disable the Survey widget. Select 'Yes' to enable the widget, or 'No' to disable.
										</p>
		
										<?php
											$enable_survey_widget = get_option('REVIEWSio_enable_survey_widget');
										?>
										<select id="js-survey" class="widget-active-state" name="REVIEWSio_enable_survey_widget">
											<option <?php echo ($enable_survey_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
											<option <?php echo ($enable_survey_widget == 0) ? 'selected' : '' ?> value="0">No</option>
										</select>
									</div>
			
									<div class="u-paddingTop--lg">
										<label for="REVIEWSio_survey_widget_options">Survey Widget Styles: </label>
										<p style="font-size:12px;font-weight:100;">
											The dropdown menu to the right contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes.
										</p>

										<?php
											$survey_widget_options = get_option('REVIEWSio_survey_widget_options');
										?>
										<input id="survey-widget-option" type="hidden" value="<?php echo $survey_widget_options ?>">
										<select id="survey-widget-options-dropdown" name='REVIEWSio_survey_widget_options'></select>
									</div>
									
									<div class="u-paddingTop--lg">
										<label for="REVIEWSio_survey_widget_campaign">Survey Widget Campaign: </label>
										<p style="font-size:12px;font-weight:100;">
											Please select a campaign from the list of available campaigns. This will load the correct survey for the customers based on your selection.
										</p>

										<?php
											$survey_widget_campaign = get_option('REVIEWSio_survey_widget_campaign');
										?>
										<input name='REVIEWSio_survey_widget_campaign' value='<?php echo (isset($survey_widget_campaign) ? $survey_widget_campaign : ''); ?>'>
									</div>
								</div>
			
								<div id="carousel" class="form-table js-widget" style="display: none">
									<div>
										<h3><strong>Carousel Shortcode Settings:</strong></h3>
									</div>
			
									<div>
										<label for="REVIEWSio_carousel_type">Carousel Type </label>
										<p style="font-size:12px;font-weight:100;">Select the type of Carousel widget to display in the page.</p>

										<?php
											$carousel_type = get_option('REVIEWSio_carousel_type');
										?>
										<select name="REVIEWSio_carousel_type">
											<option <?php echo ($carousel_type == 'card') ? 'selected' : '' ?> value="card">Card Carousel</option>
											<option <?php echo ($carousel_type == 'carousel') ? 'selected' : '' ?> value="carousel">Carousel</option>
											<option <?php echo ($carousel_type == 'fullwidth_card') ? 'selected' : '' ?> value="fullwidth_card">Fullwidth Card Carousel</option>
											<option <?php echo ($carousel_type == 'fullwidth') ? 'selected' : '' ?> value="fullwidth">Fullwidth Carousel</option>
											<option <?php echo ($carousel_type == 'bulky') ? 'selected' : '' ?> value="bulky">Bulky Carousel</option>
										</select>
									</div>
			
									<div class="u-paddingTop--lg">
										<label for="REVIEWSio_carousel_custom_styles">Custom Carousel Styles</label>
										<p style="font-size:12px;font-weight:100;">
											Set custom options and styles for the carousel widget such as review types and or minimum number of reviews to display. The options can be edited from the REVIEWS.io widget editor and all the styles from the 'options' object and below can be copied over to the text area. Leaving field blank sets the default styles.
										</p>

										<?php
											$carousel_custom_styles = get_option('REVIEWSio_carousel_custom_styles');
										?>
										<textarea name="REVIEWSio_carousel_custom_styles" style="width:100%;height:400px;"><?php echo $carousel_custom_styles; ?></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="tab-contents js-advanced-tab"></div>
		</div>
							

		<div id="tabs-container">
		    <ul class="tabs-menu">
		        <li class="current"><a href="#tab-1">API Settings</a></li>
		        <li><a href="#tab-2">Review Invitations</a></li>
		        <li><a href="#tab-3">Product Reviews</a></li>
		        <li><a href="#tab-qa">Q&A</a></li>
		        <li><a href="#tab-4">Rich Snippets</a></li>
		        <li><a href="#tab-5">Data Feeds</a></li>
		        <li><a href="#tab-widgets">REVIEWS.io Widgets</a></li>
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
			                    <p style="font-size:12px;font-weight:100;">Disable or Enable the Rating Snippet Popup on product pages.</p>
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

						<tr>
							<th>
								<label for="REVIEWSio_disable_rating_snippet_popup_category">Rating Snippet Popup on Category Pages: </label>
			                    <p style="font-size:12px;font-weight:100;">Disable or Enable the Rating Snippet Popup on homepage and category pages.</p>
							</th>
							<td>
								<?php
									$disable_rating_snippet_popup_category = get_option('REVIEWSio_disable_rating_snippet_popup_category');
								?>
								<select name="REVIEWSio_disable_rating_snippet_popup_category">
									<option <?php echo ($disable_rating_snippet_popup_category == '0') ? 'selected' : '' ?> value="0">Disabled</option>
									<option <?php echo ($disable_rating_snippet_popup_category == '1') ? 'selected' : '' ?> value="1">Enabled</option>
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
		              <p style="font-size:12px;font-weight:100;">The amount of reviews displayed per page in the Product Review Widget and Popup Product Review Widget.</p>
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
								<label for="REVIEWSio_enable_product_rich_snippet_server_side">Server Side Rich Snippets: </label>
			                    <p style="font-size:12px;font-weight:100;">Add the structured data into the HTML source of your page instead of using a Javascript widget.</p>
							</th>
							<td>
								<?php
								$enable_product_rich_snippet = get_option('REVIEWSio_enable_product_rich_snippet_server_side');
								?>
								<select name="REVIEWSio_enable_product_rich_snippet_server_side">
									<option <?php echo ($enable_product_rich_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enable_product_rich_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>
			                    <label for="REVIEWSio_enable_floating_widget">Enable Legacy Floating Widget: </label>
			                    <div>
									<p style="font-size:12px;font-weight:100;">
										A floating reviews tab will be added to the right side of your site.
									</p>
									<p style="font-size:12px;font-weight:100;">
										<strong style="font-size:12px;">Note:</strong> This widget is being deprecated and will be removed in future updates, please use main floating widget found under REVIEWS.io Widgets tab to ensure you have the latest updates and features.
									</p>
								</div>
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
								<label for="REVIEWSio_product_feed_wpseo_global_ids">Include WooCommerce SEO Global Product Identifiers: </label>
                    <p style="font-size:12px;font-weight:100;">
                        Add product global identifiers from WooCommece SEO (Yoast) into the product feed.
                    </p>
							</th>
							<td>
								<?php
									$enableWpSeoGlobalIds = get_option('REVIEWSio_product_feed_wpseo_global_ids');
								?>
								<select name="REVIEWSio_product_feed_wpseo_global_ids">
									<option <?php echo ($enableWpSeoGlobalIds == 1) ? 'selected' : '' ?> value="1">Yes</option>
									<option <?php echo ($enableWpSeoGlobalIds == 0) ? 'selected' : '' ?> value="0">No</option>
								</select>
							</td>
						</tr>
            <tr>
							<th>
								<label for="REVIEWSio_product_feed_custom_attributes">Include Product Data Attributes Feed: </label>
			                    <p style="font-size:12px;font-weight:100;">Add additional product data attributes field to be included as columns in your product feed. The following are always included by default: _barcode, barcode, _gtin, gtin, mpn, _mpn</p>
							</th>
              <td>
                <?php
                  $product_feed_custom_attributes = get_option('REVIEWSio_product_feed_custom_attributes');
                ?>
                <textarea name="REVIEWSio_product_feed_custom_attributes" style="width:300px;height:200px;" placeholder="_barcode, barcode, _gtin, gtin, mpn, _mpn"><?php echo htmlentities($product_feed_custom_attributes); ?></textarea>
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
				<div id="tab-widgets" class="tab-content">
					<div style="border-bottom: 1px solid #e4e4e4;">
						<p>
							Enable and customise how REVIEWS.io widgets on your website. The customisation for the widgets below can be found in the  <a href="https://dash.reviews.co.uk/widgets" target="_blank">REVIEWS.io widget library</a>.
						</p>
						<p>
							Enhance your website with dynamic content and features by making use of shortcodes - small pieces of code enclosed in square brackets. You can easily add these shortcodes using the format [widget name_widget], ie. [nuggets_widget] Our plugin offers several widgets that support shortcodes, including Nuggets, UGC, and Rating Bar.
						</p>
						<p>
							To use our shortcodes, you'll need to add a 'widget_id' - you can find this in our widget editor. The shortcode format is [widget_name widget_id='your widget id']. This is also a great way to add SKUs to your content! Simply separate each SKU with a semi-colon, like this: [widget_name widget_id='your widget id' sku='sku1;sku2;sku3'].
						</p>
					</div>

					<div class="menu-container">
						<div class="side-menu">
							<ul>
								<li onclick="showWidget('nuggets')">Nuggets Widget</li>
								<li onclick="showWidget('floating')">Floating Widget</li>
								<li onclick="showWidget('survey')">Survey Widget</li>
								<li onclick="showWidget('carousel')">Carousel Widget</li>
							</ul>
						</div>
	
						<div class="menu-data">
							<table id="nuggets" class="form-table js-widget">
								<tr>
									<th>
										<h3><strong>Nuggets Widget Settings:</strong></h3>
		
										<label for="REVIEWSio_enable_nuggets_widget">Enable Nuggets Widget: </label>
										<p style="font-size:12px;font-weight:100;">
											Use the dropdown menu to enable or disable the Nuggets widget. Select 'Yes' to enable the widget, or 'No' to disable.
										</p>
									</th>
		
									<td>
										<?php
											$enable_nuggets_widget = get_option('REVIEWSio_enable_nuggets_widget');
										?>
										<select id="js-nuggets" class="widget-active-state" name="REVIEWSio_enable_nuggets_widget">
											<option <?php echo ($enable_nuggets_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
											<option <?php echo ($enable_nuggets_widget == 0) ? 'selected' : '' ?> value="0">No</option>
										</select>
									</td>
								</tr>
		
								<tr>
									<th>
										<label for="REVIEWSio_nuggets_widget_options">Nuggets Widget Styles: </label>
										<p style="font-size:12px;font-weight:100;">
											The dropdown menu to the right contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes.
										</p>
									</th>
									<td>
										<?php
											$nuggets_widget_options = get_option('REVIEWSio_nuggets_widget_options');
										?>
										<input id="nuggets-widget-option" type="hidden" value="<?php echo $nuggets_widget_options ?>">
										<select id="nuggets-widget-options-dropdown" name='REVIEWSio_nuggets_widget_options'></select>
									</td>
								</tr>
								
								<?php /* <tr>
									<th>
										<label for="REVIEWSio_nuggets_widget_tags">Nuggets Widget Tags: </label>
										<p style="font-size:12px;font-weight:100;">Tags...</p>
									</th>
									<td>
										<?php
											$nuggets_widget_tags = get_option('REVIEWSio_nuggets_widget_tags');
										?>
										<input name='REVIEWSio_nuggets_widget_tags' value='<?php echo (isset($nuggets_widget_tags) ? $nuggets_widget_tags : ''); ?>'>
									</td>
								</tr> */ ?>
							</table>
		
							<?php /* 
							<table class="form-table">
								<tr style="border-top: 1px solid #e4e4e4;">
									<th>
										<h3><strong>Nuggets Bar Widget Settings:</strong></h3>
		
										<label for="REVIEWSio_enable_nuggets_bar_widget">Enable Nuggets Bar Widget: </label>
										<p style="font-size:12px;font-weight:100;">
											Review Nuggets Bar...
										</p>
									</th>
		
									<td>
										<?php
											$enable_nuggets_bar_widget = get_option('REVIEWSio_enable_nuggets_bar_widget');
										?>
										<select name="REVIEWSio_enable_nuggets_bar_widget">
											<option <?php echo ($enable_nuggets_bar_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
											<option <?php echo ($enable_nuggets_bar_widget == 0) ? 'selected' : '' ?> value="0">No</option>
										</select>
									</td>
								</tr>
		
								<tr>
									<th>
										<label for="REVIEWSio_nuggets_bar_widget_id">Nuggets Bar Widget Id: </label>
										<p style="font-size:12px;font-weight:100;">Widget Id...</p>
									</th>
									<td>
										<?php
											$nuggets_bar_widget_id = get_option('REVIEWSio_nuggets_bar_widget_id');
										?>
										<input name='REVIEWSio_nuggets_bar_widget_id' value='<?php echo (isset($nuggets_bar_widget_id) ? $nuggets_bar_widget_id : ''); ?>'>
									</td>
								</tr>
							</table>
							*/ ?>
		
							<table id="floating" class="form-table js-widget" style="display: none">
								<tr style="border-top: 1px solid #e4e4e4;">
									<th>
										<h3><strong>Floating Widget Settings:</strong></h3>
		
										<label for="REVIEWSio_enable_floating_react_widget">Enable floating Widget: </label>
										<p style="font-size:12px;font-weight:100;">
											Use the dropdown menu to enable or disable the Floating widget. Select 'Yes' to enable the widget, or 'No' to disable.
										</p>
									</th>
		
									<td>
										<?php
											$enable_floating_react_widget = get_option('REVIEWSio_enable_floating_react_widget');
										?>
										<select id="js-floating" class="widget-active-state" name="REVIEWSio_enable_floating_react_widget">
											<option <?php echo ($enable_floating_react_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
											<option <?php echo ($enable_floating_react_widget == 0) ? 'selected' : '' ?> value="0">No</option>
										</select>
									</td>
								</tr>
		
								<tr>
									<th>
										<label for="REVIEWSio_floating_react_widget_option">Floating Widget Styles: </label>
										<p style="font-size:12px;font-weight:100;">
											The dropdown menu to the right contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes.
										</p>
									</th>
									<td>
										<?php
											$floating_react_widget_options = get_option('REVIEWSio_floating_react_widget_options');
										?>
										<input id="floating-react-widget-option" type="hidden" value="<?php echo $floating_react_widget_options ?>">
										<select id="floating-react-widget-options-dropdown" name='REVIEWSio_floating_react_widget_options'></select>
									</td>
								</tr>
							</table>
		
							<table id="survey" class="form-table js-widget" style="display: none">
								<tr style="border-top: 1px solid #e4e4e4;">
									<th>
										<h3><strong>Survey Settings:</strong></h3>
		
										<label for="REVIEWSio_enable_survey_widget">Enable Survey Widget: </label>
										<p style="font-size:12px;font-weight:100;">
											Use the dropdown menu to enable or disable the Survey widget. Select 'Yes' to enable the widget, or 'No' to disable.
										</p>
									</th>
		
									<td>
										<?php
											$enable_survey_widget = get_option('REVIEWSio_enable_survey_widget');
										?>
										<select id="js-survey" class="widget-active-state" name="REVIEWSio_enable_survey_widget">
											<option <?php echo ($enable_survey_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
											<option <?php echo ($enable_survey_widget == 0) ? 'selected' : '' ?> value="0">No</option>
										</select>
									</td>
								</tr>
		
								<tr>
									<th>
										<label for="REVIEWSio_survey_widget_options">Survey Widget Styles: </label>
										<p style="font-size:12px;font-weight:100;">
											The dropdown menu to the right contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes.
										</p>
									</th>
									<td>
										<?php
											$survey_widget_options = get_option('REVIEWSio_survey_widget_options');
										?>
										<input id="survey-widget-option" type="hidden" value="<?php echo $survey_widget_options ?>">
										<select id="survey-widget-options-dropdown" name='REVIEWSio_survey_widget_options'></select>
									</td>
								</tr>
								
								<tr>
									<th>
										<label for="REVIEWSio_survey_widget_campaign">Survey Widget Campaign: </label>
										<p style="font-size:12px;font-weight:100;">
											Please select a campaign from the list of available campaigns. This will load the correct survey for the customers based on your selection.
										</p>
									</th>
									<td>
										<?php
											$survey_widget_campaign = get_option('REVIEWSio_survey_widget_campaign');
										?>
										<input name='REVIEWSio_survey_widget_campaign' value='<?php echo (isset($survey_widget_campaign) ? $survey_widget_campaign : ''); ?>'>
									</td>
								</tr>
							</table>
		
							<table id="carousel" class="form-table js-widget" style="display: none">
								<tr style="border-top: 1px solid #e4e4e4;">
									<th>
										<h3><strong>Carousel Shortcode Settings:</strong></h3>
									</th>
								</tr>
		
								<tr>
									<th>
										<label for="REVIEWSio_carousel_type">Carousel Type </label>
										<p style="font-size:12px;font-weight:100;">Select the type of Carousel widget to display in the page.</p>
									</th>
									<td>
										<?php
											$carousel_type = get_option('REVIEWSio_carousel_type');
										?>
										<select name="REVIEWSio_carousel_type">
											<option <?php echo ($carousel_type == 'card') ? 'selected' : '' ?> value="card">Card Carousel</option>
											<option <?php echo ($carousel_type == 'carousel') ? 'selected' : '' ?> value="carousel">Carousel</option>
											<option <?php echo ($carousel_type == 'fullwidth_card') ? 'selected' : '' ?> value="fullwidth_card">Fullwidth Card Carousel</option>
											<option <?php echo ($carousel_type == 'fullwidth') ? 'selected' : '' ?> value="fullwidth">Fullwidth Carousel</option>
											<option <?php echo ($carousel_type == 'bulky') ? 'selected' : '' ?> value="bulky">Bulky Carousel</option>
										</select>
									</td>
								</tr>
		
								<tr>
									<th>
										<label for="REVIEWSio_carousel_custom_styles">Custom Carousel Styles</label>
										<p style="font-size:12px;font-weight:100;">
											Set custom options and styles for the carousel widget such as review types and or minimum number of reviews to display. The options can be edited from the REVIEWS.io widget editor and all the styles from the 'options' object and below can be copied over to the text area. Leaving field blank sets the default styles.
										</p>
									</th>
									<td>
										<?php
											$carousel_custom_styles = get_option('REVIEWSio_carousel_custom_styles');
										?>
										<textarea name="REVIEWSio_carousel_custom_styles" style="width:300px;height:200px;"><?php echo $carousel_custom_styles; ?></textarea>
									</td>
								</tr>
							</table>
						</div>
					</div>
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
						</tr

            <tr>
							<th>
								<label for="REVIEWSio_use_parent_product_rich_snippet">Use Parent Product only on Rich Snippets</label>
								<p style="font-size:12px;font-weight:100;">Enable this if you would like to only show rich snippet data for the parent product, and not any accompanying variants.</p>
							</th>
							<td>
								<?php
								$use_parent_product_rich = get_option('REVIEWSio_use_parent_product_rich_snippet');
								?>
								<select name="REVIEWSio_use_parent_product_rich_snippet">
									<option <?php echo ($use_parent_product_rich== 0) ? 'selected' : '' ?> value="0">No (Default)</option>
									<option <?php echo ($use_parent_product_rich== 1) ? 'selected' : '' ?> value="1">Yes</option>
								</select>
							</td>
						</tr>

						<tr style="border-top: 1px solid #e4e4e4">
							<th>
								<label for="REVIEWSio_widget_custom_header_config">Advanced Product Reviews 'Header' Config</label>
													<p style="font-size:12px;font-weight:100;">Sets 'header' section config for the Product Reviews Widget. After using the designer tool, copy the "header" block, which begins with "header: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.</p>
							</th>
							<td>
								<?php
									$custom_widget_header_config = get_option('REVIEWSio_widget_custom_header_config');
								?>
								<textarea name="REVIEWSio_widget_custom_header_config" style="width:300px;height:200px;"><?php echo htmlentities($custom_widget_header_config); ?></textarea>
							</td>
						</tr>
						<tr>
							<th>
								<label for="REVIEWSio_widget_custom_filtering_config">Advanced Product Reviews 'Filtering' Config</label>
													<p style="font-size:12px;font-weight:100;">Sets 'filtering' section config for the Product Reviews Widget. After using the designer tool, copy the "filtering" block, which begins with "filtering: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.</p>
							</th>
							<td>
								<?php
									$custom_widget_filtering_config = get_option('REVIEWSio_widget_custom_filtering_config');
								?>
								<textarea name="REVIEWSio_widget_custom_filtering_config" style="width:300px;height:200px;"><?php echo htmlentities($custom_widget_filtering_config); ?></textarea>
							</td>
						</tr>
						<tr>
							<th>
								<label for="REVIEWSio_widget_custom_reviews_config">Advanced Product Reviews 'Reviews' Config</label>
													<p style="font-size:12px;font-weight:100;">Sets 'reviews' section config for the Product Reviews Widget. After using the designer tool, copy the "reviews" block, which begins with "reviews: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.</p>
							</th>
							<td>
								<?php
									$custom_widget_reviews_config = get_option('REVIEWSio_widget_custom_reviews_config');
								?>
								<textarea name="REVIEWSio_widget_custom_reviews_config" style="width:300px;height:200px;"><?php echo htmlentities($custom_widget_reviews_config); ?></textarea>
							</td>
						</tr>
						<tr style="border-bottom: 1px solid #e4e4e4">
							<th>
								<label for="REVIEWSio_widget_custom_css">Advanced Product Reviews Widget 'Styles' Config</label>
													<p style="font-size:12px;font-weight:100;">Sets the 'styles' for the Product Reviews Widget. After using the designer tool, copy the "styles" block, which begins with "styles: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.</p>
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
