<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <img src="https://assets.reviews.io/img/all-global-assets/logo/reviewsio-logo.svg" height="40" style="margin-top:15px;" />
    <form id="reviewsio-settings" method="post" action="options.php" autocomplete="off">
        <h2></h2><!-- Alerts Show Here -->

        <?php @settings_fields('woocommerce-reviews'); ?>
        <?php @do_settings_sections('woocommerce-reviews'); ?>

        <div style="background:#fff; padding:20px; margin:20px 0; box-shadow: 3px 10px -5px rgba(0,0,0,.1); border-radius: 6px;">
            <h2 id="welcomeHeading" class="TextHeading TextHeading--sm">Automated Review Collection - Powered by REVIEWS.io</h2>

            <div id="welcomeText">
                <p>Enter your API Credentials to Start Collecting Reviews from your Customers.</p>
            </div>
        </div>

        <input hidden name='REVIEWSio_new_variables_set' value='1' />


        <?php
        $hide_legacy = get_option('REVIEWSio_hide_legacy');
        ?>

        <!-- Unsaved changes message -->
        <div class="GlobalNotification GlobalNotification--sm GlobalNotification--coloured-warning u-marginBottom--md js-unsaved-notification" style="display: none;">
            <div class="flex-row flex-middle-xxs">
                <div class="flex-col-xxs-12">
                    <div class="TextHeading TextHeading--xxxxs u-marginBottom--none">
                        Unsaved Changes
                    </div>
                    <div id="js-collector-current-widget-info" class="js-collector-toggle-info TextBody TextBody--xxxs u-marginBottom--none">
                        Please remember to save your changes if you want to apply the below settings.
                    </div>
                </div>
            </div>
        </div>

        <div class="ContentPanel">
            <div class="FlexTabs FlexTabs--inPanel">
                <div id="js-api-tab" class="FlexTabs__item isActive">
                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">API Settings</div>
                </div>
                <div id="js-invitations-tab" class="FlexTabs__item">
                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Review Invitations</div>
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

            <div class="tab-contents js-api-tab">
                <!-- API Settings -->
                <div id="api-notification" class="GlobalNotification GlobalNotification--warning" style="display: none">
                    <div class="flex-row flex-middle-xxs">
                        <div class="flex-col-xxs-1 u-textCenter--all">
                            <img class="GlobalNotification__imageIcon" src="https://assets.reviews.io/img/all-global-assets/icons/icon-api--md--colour.svg">
                        </div>
                        <div class="flex-col-xxs-11">
                            <div id="api-notification-heading" class="TextHeading TextHeading--xxxxs">
                                Connect with REVIEWS.io
                            </div>
                            <div id="api-notification-text" class="TextBody TextBody--xxxs u-marginBottom--none">
                                Enter your API crendentials found under WooCommerce Integration in the REVIEWS.io dashboard.
                            </div>
                        </div>
                    </div>
                </div>

                <p>You can find your API credentials on the REVIEWS.io Dashboard. Click the <b>Integrations</b> menu, and then scroll to <b>WooCommerce</b>.</p>

                <div class="u-marginTop--lg">
                    <div>
                        <div class="flex-row">
                            <div class="flex-col-xxs-12 flex-col-sm-6">
                                <div class="Field u-marginTop--xxs u-width--100">
                                    <?php
                                    $store_id = get_option('REVIEWSio_store_id');
                                    ?>
                                    <input class="Field__input" type="text" name="REVIEWSio_store_id" value="<?php echo esc_attr($store_id); ?>" />
                                    <label class="Field__label">
                                        Store ID
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex-row">
                            <div class="flex-col-xxs-12 flex-col-sm-6">
                                <div class="Field u-marginTop--xxs u-width--100">
                                    <?php
                                    $api_key = get_option('REVIEWSio_api_key');
                                    ?>
                                    <input class="Field__input" type="text" name="REVIEWSio_api_key" value="<?php echo esc_attr($api_key); ?>" />
                                    <label class="Field__label">
                                        API Key
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="u-marginTop--md js-validated-user" style="display: none;">
                    <?php
                    $region = get_option('REVIEWSio_region');
                    ?>
                    <strong>Region: </strong>
                    <input class="Field__input" id="REVIEWSio_region" type="hidden" name="REVIEWSio_region" value="<?php echo esc_attr($region) ?>" style="width: 50px;">

                    <span id="REVIEWSio_region_label"><?php echo $region == 'uk' ? 'UK' : 'US' ?></span>


                    <!-- <input class="Field__input" id="REVIEWSio_region_label" type="text" name="REVIEWSio_region_label" value="<?php echo $region == 'uk' ? 'UK' : 'US' ?>" style="width: 50px;" disabled> -->
                </p>

                <p class="u-marginTop--md js-invalidated-user" style="display: none;">
                    <strong>Not a REVIEWS.io Customer?</strong>
                    <br>
                    You can sign up for a
                    <a href='https://www.reviews.io/business-solutions' target="_blank">REVIEWS.io plan here</a>:
                </p>
            </div>

            <div class="tab-contents js-invitations-tab">
                <!-- Review Collection -->
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_send_product_review_invitation">Queue Invitations: </label>
                    <p class="TextBody TextBody--xxxs">Invitations will be queued when orders are Completed.</p>
                    <?php
                    $send_product_review_invitation = get_option('REVIEWSio_send_product_review_invitation');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_send_product_review_invitation">
                                    <option <?php echo ($send_product_review_invitation == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($send_product_review_invitation == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="u-marginTop--md TextBody TextBody--xxs">The invitation delay can be changed within the REVIEWS.io Dashboard under the <strong>Invitations</strong> menu, or from your <strong>Flow</strong>.
            </div>

            <div class="tab-contents js-snippets-tab">
                <!-- Rich Snippets -->
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
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_product_rich_snippet">Enable Product Rich Snippet: </label>
                    <p class="TextBody TextBody--xxxs">The product rich snippet will give you stars on natural search results for your product pages.</p>

                    <?php
                    $enable_product_rich_snippet = get_option('REVIEWSio_enable_product_rich_snippet');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_product_rich_snippet">
                                    <option <?php echo ($enable_product_rich_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($enable_product_rich_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_product_rich_snippet_server_side">Server Side Rich Snippets: </label>
                    <p class="TextBody TextBody--xxxs">Add the structured data into the HTML source of your page instead of using a Javascript widget.</p>

                    <?php
                    $enable_product_rich_snippet = get_option('REVIEWSio_enable_product_rich_snippet_server_side');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_product_rich_snippet_server_side">
                                    <option <?php echo ($enable_product_rich_snippet == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($enable_product_rich_snippet == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-contents js-feeds-tab">
                <!-- Data Feeds-->
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_product_feed">Enable Product Feed: </label>
                    <p class="TextBody TextBody--xxxs">For Product Invitations to queue correctly, we require access to your Product catalogue via a feed, which we will make available from <a href="<?php echo esc_url(get_site_url()); ?>/index.php/reviews/product_feed"><?php echo esc_url(get_site_url()); ?>/index.php/reviews/product_feed</a>.</p>
                    <p class="TextBody TextBody--xxxs">
                        <strong style="font-size:12px;">Note:</strong> There is an issue with data not being added correctly when renaming attributes in WooCommerce, please remove the attribute with changed name and the associated products, and then re-add them to ensure data integrity.
                    </p>

                    <?php
                    $enableProductFeed = get_option('REVIEWSio_product_feed');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_product_feed">
                                    <option <?php echo ($enableProductFeed == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($enableProductFeed == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_product_feed_wpseo_global_ids">Include WooCommerce SEO Global Product Identifiers: </label>
                    <p class="TextBody TextBody--xxxs">Add product global identifiers from WooCommece SEO (Yoast) into the product feed.</p>

                    <?php
                    $enableWpSeoGlobalIds = get_option('REVIEWSio_product_feed_wpseo_global_ids');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_product_feed_wpseo_global_ids">
                                    <option <?php echo ($enableWpSeoGlobalIds == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($enableWpSeoGlobalIds == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_gpf_data">Include WooCommerce Google Product Feed Attributes: </label>
                    <p class="TextBody TextBody--xxxs">Add attributes from WooCommerce Google Product Feed plugin into the product feed.</p>

                    <?php
                    $enableGpfAttributes = get_option('REVIEWSio_enable_gpf_data');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_gpf_data">
                                    <option <?php echo ($enableGpfAttributes == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($enableGpfAttributes == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_product_feed_custom_attributes">Include Product Data Attributes Feed: </label>
                    <p class="TextBody TextBody--xxxs">Add additional product data attributes field to be included as columns in your product feed. The following are always included by default: _barcode, barcode, _gtin, gtin, mpn, _mpn</p>

                    <!-- Add new attribute -->
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6" style="display: flex">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <input id="product-feed-custom-attributes-new" type="text" class="Field__input u-width--100" style="max-width: none;" placeholder="Add a new attribute" on="addNewAttribute()" />
                                <div class="Field__feedback">
                                    <div class="feedback__inner js-field-feedback">
                                        Error
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="Button Button--sm Button--primary u-marginTop--xs u-marginLeft--sm" onclick="addNewAttribute('product-feed-custom-attributes-new', 'product-feed-custom-attributes', 'product_feed_custom_attributes-list')">Add</div>
                            </div>

                        </div>
                    </div>

                    <!-- Attribute value -->
                    <?php
                    $product_feed_custom_attributes = get_option('REVIEWSio_product_feed_custom_attributes');
                    ?>

                    <input type="hidden" id="product-feed-custom-attributes" class="js-tags-list" name="REVIEWSio_product_feed_custom_attributes" value="<?php echo esc_attr(htmlentities($product_feed_custom_attributes)); ?>">

                    <!-- Attribute tags -->
                    <div class="u-marginBottom--sm">
                        <div class="TagsInputElement">
                            <ul id="product_feed_custom_attributes-list" class="flex-row tags"></ul>
                        </div>
                    </div>
                </div>

                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                <div>
                    <label class="TextHeading TextHeading--xxxs">Latest Orders CSV:</label>
                    <p class="TextBody TextBody--xxxs">Download this CSV and upload it to the <b>Review Booster</b> section in the REVIEWS.io Dashboard to start collecting reviews.</p>
                    <a class="Button Button--outline Button--xs" href="<?php echo esc_url(get_site_url()); ?>/index.php/reviews/order_csv">Download Latest Orders CSV</a>
                </div>

                <?php if (get_option('REVIEWSio_enable_product_feed_cron')) : ?>
                    <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                    <div>
                        <div>
                            <label class="TextHeading TextHeading--xxxs">Set Cron Interval:</label>
                            <p class="TextBody TextBody--xxxs">Choose to update product feed hourly, twice a day, daily or weekly.</p>
                            <?php
                            $productFeedCronFrequency = get_option('REVIEWSio_product_feed_cron_frequency');
                            ?>
                            <div class="flex-row">
                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                    <div class="Field u-marginTop--xxs u-width--100">
                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_product_feed_cron_frequency">
                                            <option <?php echo ($productFeedCronFrequency == 'hourly') ? 'selected' : '' ?> value="hourly">Hourly (60 Minutes)</option>
                                            <option <?php echo ($productFeedCronFrequency == 'twicedaily') ? 'selected' : '' ?> value="twicedaily">Twice a Day (12 Hours)</option>
                                            <option <?php echo ($productFeedCronFrequency == 'daily') ? 'selected' : '' ?> value="daily">Daily (24 Hours)</option>
                                            <option <?php echo ($productFeedCronFrequency == 'weekly') ? 'selected' : '' ?> value="weekly">Weekly (7 Days)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="u-marginBottom--md">
                            <label class="TextHeading TextHeading--xxxs">Refresh Product Feed:</label>
                            <p class="TextBody TextBody--xxxs">Sync latest feed from WooCommerce.</p>
                            <a class="Button Button--outline Button--xs" href="<?php echo esc_url(get_site_url()); ?>/index.php/reviews/product_feed?refresh">Refresh and Download Feed</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-contents js-widgets-tab">
                <!-- Widgets -->
                <div class="TextBody TextBody--xxs">
                    <p>
                        Enable and customize REVIEWS.io widgets on your website. Additionally, you can enhance your WordPress site by incorporating our shortcodes, allowing you to display REVIEWS.io widgets seamlessly. The customisation for the widgets below can be found in the
                        <a href="https://dash.reviews.<?php echo $region == 'uk' ? 'co.uk' : 'io' ?>/widgets" target="_blank">REVIEWS.io widget library</a>.
                    </p>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--lg"></div>

                <div class="flex-row">
                    <div class="flex-col-xxs-3 u-paddingRight--none">
                        <div id="global-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('global')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-star-badge-cog--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Global Customization</div>
                                </div>
                            </div>
                        </div>
                        <div id="product-reviews-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab js-product-reviews-tab isActive" onclick="showWidget('product-reviews')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-review-adverts--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Product Reviews Widget</div>
                                </div>
                            </div>
                        </div>
                        <div id="rating-snippet-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('rating-snippet')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-three-stars--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Rating Snippet</div>
                                </div>
                            </div>
                        </div>
                        <div id="nuggets-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('nuggets')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-trophy--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Nuggets Widget</div>
                                </div>
                            </div>
                        </div>
                        <div id="floating-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('floating')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-floating-widget--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Floating Widget</div>
                                </div>
                            </div>
                        </div>
                        <div id="ugc-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('ugc')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-influence-widget-fullpage--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">UGC Widget</div>
                                </div>
                            </div>
                        </div>
                        <div id="survey-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('survey')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-notebook-pen--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Survey Widget</div>
                                </div>
                            </div>
                        </div>
                        <div id="rating-bar-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('rating-bar')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-rating-box--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Rating Bar</div>
                                </div>
                            </div>
                        </div>
                        <div id="carousel-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('carousel')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-influence-widget-carousel--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Carousel Widget</div>
                                </div>
                            </div>
                        </div>
                        <div id="custom-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('custom')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-code--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Header & Footer</div>
                                </div>
                            </div>
                        </div>
                        <div id="legacy-tab" class="ContentPanelTab ContentPanelTab--vertical ContentPanelTab--gradient-bg--yellow u-paddingTop--sm u-paddingBottom--sm js-widget-tab" onclick="showWidget('legacy')">
                            <div class="flex-row flex-row--noMargin flex-middle-xxs u-flexWrap--nowrap">
                                <img class="ContentPanelTab__icon ContentPanelTab__icon--sm u-marginRight--sm" src="https://assets.reviews.io/img/all-global-assets/icons/icon-timeline--md--colour.svg" alt="">
                                <div>
                                    <div class="TextHeading TextHeading--xxxs u-marginBottom--none">Legacy Widgets</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-col-xxs-9 u-paddingLeft--none">
                        <div class="ContentPanel u-shadow--none u-paddingRight--none" style="box-shadow: none; padding-top: 0;">
                            <div>
                                <div id="global" class="form-table js-widget" style="display: none">
                                    <div>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-md-6">
                                                <h3><strong>Global Widget Styles</strong></h3>
                                                <p>
                                                    Customize different aspects of these widgets globally such as what gets displayed on the page or how it looks.
                                                </p>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/reviewsio-widgets@2x.png">
                                            </div>
                                        </div>

                                        <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                        <div class="GlobalNotification GlobalNotification--sm GlobalNotification--coloured-warning u-marginBottom--md" style="display: block;">
                                            <div class="flex-row flex-middle-xxs">
                                                <div class="flex-col-xxs-12">
                                                    <div class="TextHeading TextHeading--xxxxs u-marginBottom--none">
                                                        Please Note
                                                    </div>
                                                    <div id="js-collector-current-widget-info" class="js-collector-toggle-info TextBody TextBody--xxxs u-marginBottom--none">
                                                        The following styles are only applied to our <strong>Product Reviews</strong>, <strong>Rating Snippet</strong> and <strong>Legacy Floating</strong> widgets.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_minimum_rating">Widget Language</label>
                                            <p class="TextBody TextBody--xxxs">Set the Language of the widgets.</p>
                                            <?php
                                            $polaris_lang = get_option('REVIEWSio_polaris_lang');
                                            ?>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select name='REVIEWSio_polaris_lang' class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;">
                                                            <?php
                                                            foreach (
                                                                [
                                                                    'English (Default)' => 'en',
                                                                    'Deutsch' => 'de',
                                                                    'Deutsch (Informal)' => 'de-informal',
                                                                    'Español' => 'es',
                                                                    'Français' => 'fr',
                                                                    'Italiano' => 'it',
                                                                    'Nederlands' => 'nl',
                                                                    'Suomi' => 'fi'
                                                                ] as $key => $value
                                                            ) {
                                                            ?>
                                                                <option <?php echo ($value == $polaris_lang ? 'selected' : ''); ?> value='<?php echo esc_attr($value); ?>'>
                                                                    <?php echo esc_html($key); ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_minimum_rating">Minimum Review Rating</label>
                                            <p class="TextBody TextBody--xxxs">This option sets the minimum star rating of reviews displayed.</p>
                                            <?php
                                            $minimum_rating = get_option('REVIEWSio_minimum_rating');
                                            ?>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select name='REVIEWSio_minimum_rating' class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;">
                                                            <?php
                                                            foreach (['None (Default)' => 1, '2 Stars' => 2, '3 Stars' => 3, '4 Stars' => 4, '5 Stars' => 5] as $key => $value) {
                                                            ?>
                                                                <option <?php echo ($value == $minimum_rating ? 'selected' : ''); ?> value='<?php echo esc_attr($value); ?>'>
                                                                    <?php echo esc_html($key); ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_minimum_rating">Offset: (Default = 0)</label>
                                            <p class="TextBody TextBody--xxxs">This option sets the offset to the product widget element (Integer Number).</p>
                                            <?php
                                            $disable_rating_snippet_offset = get_option('REVIEWSio_disable_rating_snippet_offset');
                                            ?>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <input type="text" name="REVIEWSio_disable_rating_snippet_offset" class="Field__input u-width--100" style="max-width: none;" value="<?php echo esc_attr($disable_rating_snippet_offset); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_minimum_rating">Star Colour</label>
                                            <p class="TextBody TextBody--xxxs">Sets the primary colour for your widgets, including the stars.</p>
                                            <?php
                                            $widget_hex_colour = get_option('REVIEWSio_widget_hex_colour');
                                            ?>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field Field--colourPicker u-marginTop--xxs u-width--100">
                                                        <input id="input-global-star-color" class="Field__input" type="text" name="REVIEWSio_widget_hex_colour" value="<?php echo esc_attr($widget_hex_colour); ?>">
                                                        <div class="colourPicker__indicator">
                                                            <span id="global-star-color" class="colour-picker"></span>
                                                        </div>

                                                        <div class="Field__feedback">
                                                            <div class="feedback__inner js-field-feedback">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_minimum_rating">Hide Write Review Button</label>
                                            <p class="TextBody TextBody--xxxs">Write a Review Button will be hidden on your widgets.</p>
                                            <?php
                                            $hide_write_review_button = get_option('REVIEWSio_hide_write_review_button');
                                            ?>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select name="REVIEWSio_hide_write_review_button" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;">
                                                            <option <?php echo ($hide_write_review_button == 1) ? 'selected' : '' ?> value="1">Hide Button</option>
                                                            <option <?php echo ($hide_write_review_button == 0) ? 'selected' : '' ?> value="0">Show Button</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_minimum_rating">Reviews Per Page</label>
                                            <p class="TextBody TextBody--xxxs">The amount of reviews displayed per page in the Product Review Widget and Popup Product Review Widget.</p>
                                            <?php
                                            $per_page_review_widget = get_option('REVIEWSio_per_page_review_widget');
                                            ?>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <input value='<?php echo (!empty($per_page_review_widget) ? esc_attr($per_page_review_widget) : esc_attr(8)); ?>' type='number' min='0' max='30' name="REVIEWSio_per_page_review_widget" class="Field__input u-width--100" style="max-width: none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="product-reviews" class="form-table js-widget">
                                    <?php if (!empty(get_option('REVIEWSio_polaris_custom_styles'))) { ?>                                         
                                        <div class="GlobalNotification GlobalNotification--sm GlobalNotification--coloured-danger">
                                            <div class="flex-row flex-middle-xxs">
                                                <div class="flex-col-xxs-1 u-textCenter--all">
                                                    <img class="GlobalNotification__imageIcon" src="https://assets.reviews.io/img/all-global-assets/icons/icon-warning--md.svg">
                                                </div>
                                                <div class="flex-col-xxs-11">
                                                    <div class="TextHeading TextHeading--xxxxs">
                                                        Configuration has been overridden.
                                                    </div>
                                                    <div class="TextBody TextBody--xxxs u-marginBottom--none">
                                                        You have enabled <strong>Configuration Code Override</strong> please be aware that Enable Q&A, Include AI Summary and all Legacy settings are overriden.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div>  
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-md-6">
                                                <h3><strong>Product Review Widget Settings</strong></h3>
                                                <p>
                                                    A mobile friendly product reviews widget displaying product & customer attributes, photos, videos as well as questions & answers.
                                                </p>
                                                <ul class="list">
                                                    <li>Display desired content type e.g. Product, Company, 3rd Party Reviews.</li>
                                                    <li>Display overall Product Rating and Product Attributes.</li>
                                                    <li>Customise showing a Photo and Video Gallery</li>
                                                    <li>Option to hide or show 'Write Review' and 'Ask Question' buttons.</li>
                                                    <li>Thoroughly customise styling of the widget</li>
                                                </ul>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--elementswidgetv2@2x.png">
                                            </div>
                                        </div>

                                        <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                        <label class="TextHeading TextHeading--xxxs u-marginTop--xxs">Show Product Review Widget</label>
                                        <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                            A mobile friendly product reviews widget displaying product & customer attributes, photos and videos.
                                        </p>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-sm-6">
                                                <div class="Field u-marginTop--xxs u-width--100" tooltip="Use the dropdown menu to choose where to show the widget." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                    <?php
                                                    $polaris_review_widget = get_option('REVIEWSio_polaris_review_widget');
                                                    ?>
                                                    <select id="polaris-widget-location" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_polaris_review_widget" onchange="polarisTabActiveState()">
                                                        <option <?php echo ($polaris_review_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Tab</option>
                                                        <option <?php echo ($polaris_review_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
                                                        <option <?php echo ($polaris_review_widget == 'bottom') ? 'selected' : '' ?> value="bottom">Show At Bottom of Page</option>
                                                        <option <?php echo ($polaris_review_widget == 'manual') ? 'selected' : '' ?> value="manual">Manual</option>
                                                        <option <?php echo ($polaris_review_widget == '0') ? 'selected' : '' ?> value="0">Do Not Display</option>
                                                    </select>
                                                    <div class="Field__label">
                                                        Widget Location
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if ($polaris_review_widget == 'tab') { ?>
                                                <div class="flex-col-xxs-12 flex-col-sm-6 js-polaris-review-tab-name">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="Sets the name of the review tab." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <?php
                                                        $reviews_tab_name = get_option('REVIEWSio_reviews_tab_name');
                                                        ?>
                                                        <input class="Field__input" name='REVIEWSio_reviews_tab_name' value='<?php echo (!empty($reviews_tab_name) ? esc_attr($reviews_tab_name) : esc_attr('Reviews')); ?>'>

                                                        <div class="Field__label">
                                                            Review Tab Text
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 u-paddingTop--sm form-table">
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_rich_snippet">Enable Q&A: </label>
                                                <p class="TextBody TextBody--xxxs">Allow your visitors to ask questions about your products. Your answers will be published publicly. This will add a Q&A Tab to your Product Review Widget.</p>
                                                <?php
                                                    $polaris_review_widget_questions = get_option('REVIEWSio_polaris_review_widget_questions');
                                                ?>
                                                <div class="flex-row">
                                                    <div class="flex-col-xxs-12 flex-col-sm-6">
                                                        <div class="Field u-marginTop--xxs u-width--100">
                                                            <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_polaris_review_widget_questions">
                                                                <option <?php echo ($polaris_review_widget_questions == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                                                <option <?php echo ($polaris_review_widget_questions == 0) ? 'selected' : '' ?> value="0">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-col-xxs-12 u-paddingTop--sm form-table">
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_sentiment_analysis">Include AI Summary: </label>
                                                <p class="TextBody TextBody--xxxs">This will add an AI summary section on your Product Reviews Widget.</p>
                                                <p class="TextBody TextBody--xxxs">
                                                    <strong>Note:</strong> A product requires 50 reviews to generate summary, feature must be enabled in the REVIEWS.io dashboard and is only plans Grow and higher.
                                                </p>
                                                <?php
                                                    $polaris_sentiment = get_option('REVIEWSio_sentiment_analysis');
                                                ?>
                                                <div class="flex-row">
                                                    <div class="flex-col-xxs-12 flex-col-sm-6">
                                                        <div class="Field u-marginTop--xxs u-width--100">
                                                            <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_sentiment_analysis">
                                                                <option <?php echo ($polaris_sentiment == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                                                <option <?php echo ($polaris_sentiment == 0) ? 'selected' : '' ?> value="0">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-col-xxs-12 u-paddingTop--sm">
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_carousel_custom_styles">Configuration Code Override</label>
                                                <p class="TextBody TextBody--xxxs">
                                                    Set custom options and styles for the Product Review widget below to thoroughly customize every aspect of the widget. The options can be edited from the <a href="https://dash.reviews.<?php echo $region == 'uk' ? 'co.uk' : 'io' ?>/widgets/editor/product-reviews-widget" target="_blank">REVIEWS.io widget editor</a>
                                                </p>
                                                <p class="TextBody TextBody--xxxs">
                                                    <strong>Note:</strong> Adding anything in the field below will overwrite all other settings you have configured. Please leave this field empty if you wish to use the settings above, or legacy settings.
                                                </p>

                                                <?php
                                                $polaris_custom_styles = get_option('REVIEWSio_polaris_custom_styles');
                                                ?>
                                                <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_polaris_custom_styles" style="width:100%;height:400px;border-color:#D1D8DA;border-radius:4px;padding:12px;"><?php echo esc_html($polaris_custom_styles); ?></textarea>
                                            </div>
                                        </div>

                                        <?php 
                                            $legacyInUse = !empty(get_option('REVIEWSio_widget_custom_header_config')) || !empty(get_option('REVIEWSio_widget_custom_filtering_config')) || !empty(get_option('REVIEWSio_widget_custom_reviews_config')) || !empty(get_option('REVIEWSio_custom_reviews_widget_styles'));
                                        ?>
                                                        
                                        <div class="reviews-collapse-trigger u-marginTop--md">
                                            <div class="flex-row flex-row--noMargin flex-middle-xxs flex-between-xxs u-flexWrap--nowrap u-cursorPointer u-highlightHover--grey u-padding--sm">
                                                <div>
                                                    <div class="flex-row flex-row--noMargin flex-start-xxs flex-middle-xxs">
                                                        <div>
                                                            <img class="u-marginRight--sm u-verticalAlign--top u-marginBottom--none" src="https://assets.reviews.io/img/all-global-assets/icons/icon-settings--sm.svg" style="width: 35px" alt="">
                                                        </div>

                                                        <h3 class="TextHeading TextHeading--xxs u-marginBottom--none">
                                                            Legacy Settings <?php echo ($legacyInUse) ? '(In use)' : '' ?>
                                                        </h3>
                                                    </div>
                                                </div>

                                                <div class="IconButton IconButton--xs IconButton--rotateOnClick180 reviews-collapse-icon">
                                                    <i class="IconButton__icon ricon-thin-arrow--up"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-row reviews-collapse-content flex-col-xxs-12 u-paddingTop--sm" style="width:100%">
                                            <div>
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_widget_custom_header_config">Advanced Product Reviews 'Header' Config</label>
                                                <p class="TextBody TextBody--xxxs">
                                                    Sets 'header' section config for the Product Reviews Widget. After using the designer tool, copy the "header" block, which begins with "header: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.
                                                </p>

                                                <?php
                                                    $custom_widget_header_config = get_option('REVIEWSio_widget_custom_header_config');
                                                ?>

                                                <div class="flex-row flex-col-xxs-12">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_widget_custom_header_config" style="width:100%;height:150px;"><?php echo wp_kses(htmlentities($custom_widget_header_config),[]); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_widget_custom_filtering_config">Advanced Product Reviews 'Filtering' Config</label>
                                                <p class="TextBody TextBody--xxxs">
                                                    Sets 'filtering' section config for the Product Reviews Widget. After using the designer tool, copy the "filtering" block, which begins with "filtering: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.
                                                </p>

                                                <?php
                                                    $custom_widget_filtering_config = get_option('REVIEWSio_widget_custom_filtering_config');
                                                ?>

                                                <div class="flex-row flex-col-xxs-12">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_widget_custom_filtering_config" style="width:100%;height:150px;"><?php echo wp_kses(htmlentities($custom_widget_filtering_config), []); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_widget_custom_reviews_config">Advanced Product Reviews 'Reviews' Config</label>
                                                <p class="TextBody TextBody--xxxs">
                                                    Sets 'reviews' section config for the Product Reviews Widget. After using the designer tool, copy the "reviews" block, which begins with "reviews: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.
                                                </p>

                                                <?php
                                                    $custom_widget_reviews_config = get_option('REVIEWSio_widget_custom_reviews_config');
                                                ?>

                                                <div class="flex-row flex-col-xxs-12">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_widget_custom_reviews_config" style="width:100%;height:150px;"><?php echo wp_kses(htmlentities($custom_widget_reviews_config), []); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_widget_custom_css">Advanced Product Reviews Widget 'Styles' Config</label>
                                                <p class="TextBody TextBody--xxxs">
                                                    Sets the 'styles' for the Product Reviews Widget. After using the designer tool, copy the "styles" block, which begins with "styles: {" and ends in "},". Please note that this is an advanced feature and incorrect use may break your Product Reviews Widget.
                                                </p>

                                                <?php
                                                    $custom_reviews_widget_styles = get_option('REVIEWSio_custom_reviews_widget_styles');
                                                ?>

                                                <div class="flex-row flex-col-xxs-12">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_custom_reviews_widget_styles" style="width:100%;height:150px;"><?php echo wp_kses(htmlentities($custom_reviews_widget_styles), []); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="u-marginTop--md">
                                        <h3><strong>Generate Product Reviews Shortcode</strong></h3>
    
                                        <p class="TextBody TextBody--xxxs">
                                            If set to manual mode, you can install the Product Reviews widget using the following shortcode: <code>[product_reviews_widget sku='your-sku']</code>
                                        </p>
    
                                        <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                            Additional information on embedding shortcodes can be found in the <a href="https://wordpress.com/support/wordpress-editor/blocks/shortcode-block/" target="_blank">WordPress Documentation</a>.
                                        </p>
    
                                        <div class="GlobalNotification GlobalNotification--coloured-success u-marginBottom--lg">
                                            <div class="flex-row flex-middle-xxs">
                                                <div class="flex-col-xxs-1 u-textCenter--all">
                                                    <img class="GlobalNotification__imageIcon" src="https://assets.reviews.io/img/all-global-assets/icons/icon-code--md--colour.svg">
                                                </div>
                                                <div class="flex-col-xxs-9">
                                                    <div class="TextHeading TextHeading--xxxxs">Use the following shortcode to embed widget on a page:</div>
                                                    <div id="product_reviews_widget-shortcode" class="TextBody TextBody--xxxs u-marginBottom--none">
                                                        [product_reviews_widget<span></span><span></span>]
                                                    </div>
                                                </div>
                                                <div class="flex-col-xxs-2 u-textRight--all">
                                                    <div id="product_reviews_widget-shortcode-copy-button" class="Button Button--xs Button--outline u-marginBottom--none" onclick="copyToClipboard('product_reviews_widget-shortcode-copy-button', 'product_reviews_widget-shortcode')">
                                                        Copy
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div id="rating-snippet" class="form-table js-widget" style="display: none">
                                    <div>
                                        <div>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-md-6">
                                                    <h3><strong>Rating Snippet Settings</strong></h3>
                                                    <p>An ideal way to display a product rating on your category pages.</p>
                                                    <p>
                                                        Quickly evaluate the overall customer sentiment by considering the average rating and review count. This helps customers gauge the satisfaction level of previous buyers for customers to utilize this snippet to make an informed purchasing decision.
                                                    </p>
                                                    <p>
                                                        Access detailed information by clicking on the snippet to open our product reviews widget displaying individual reviews.
                                                    </p>
                                                </div>
                                                <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                    <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--rating-snippets@2x.png">
                                                </div>
                                            </div>

                                            <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_product_rating_snippet">Enable Product Rating Snippet: </label>
                                            <p class="TextBody TextBody--xxxs">
                                                When enabled a star rating will be displayed below the product title providing the product has reviews.><br />If you would like to change how the rating is displaying you can choose the manual setting and use shortcode <code>[rating_snippet]</code> to display the rating.
                                            </p>

                                            <?php
                                            $enable_product_rating_snippet = get_option('REVIEWSio_enable_product_rating_snippet');
                                            ?>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_product_rating_snippet">
                                                            <option <?php echo ($enable_product_rating_snippet == 1) ? 'selected' : '' ?> value="1">Enabled</option>
                                                            <option <?php echo ($enable_product_rating_snippet == 0) ? 'selected' : '' ?> value="0">Disabled</option>
                                                            <option <?php echo ($enable_product_rating_snippet == 'manual') ? 'selected' : '' ?> value="manual">Manual</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_product_rating_snippet">Rating Snippet Linebreak: </label>
                                            <p class="TextBody TextBody--xxxs">Adds a line break between rating stars and text.</p>
                                            <?php
                                            $rating_snippet_no_linebreak = get_option('REVIEWSio_rating_snippet_no_linebreak');
                                            ?>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_rating_snippet_no_linebreak">
                                                            <option <?php echo ($rating_snippet_no_linebreak == 0) ? 'selected' : '' ?> value="0">Enabled (Default)</option>
                                                            <option <?php echo ($rating_snippet_no_linebreak == 1) ? 'selected' : '' ?> value="1">Disabled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_product_rating_snippet">Rating Snippet Text: </label>
                                            <p class="TextBody TextBody--xxxs">Sets the descriptor after the number of reviews on the Rating Snippet.</p>
                                            <?php
                                            $rating_snippet_text = get_option('REVIEWSio_rating_snippet_text');
                                            ?>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <input class="Field__input" name='REVIEWSio_rating_snippet_text' placeholder="Reviews" value='<?php echo (isset($rating_snippet_text) ? esc_attr($rating_snippet_text) : esc_attr('Reviews')); ?>'>
                                                        <label class="Field__label">
                                                            Text
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_disable_rating_snippet_popup">Rating Snippet Popup: </label>
                                            <p class="TextBody TextBody--xxxs">Disable or Enable the Rating Snippet Popup on product pages.</p>
                                            <?php
                                            $disable_rating_snippet_popup = get_option('REVIEWSio_disable_rating_snippet_popup');
                                            ?>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_disable_rating_snippet_popup">
                                                            <option <?php echo ($disable_rating_snippet_popup == '0') ? 'selected' : '' ?> value="0">Disabled (Anchor to Product Review Widget)</option>
                                                            <option <?php echo ($disable_rating_snippet_popup == '1') ? 'selected' : '' ?> value="1">Enabled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_disable_rating_snippet_popup_category">Listen for Changes: </label>
                                            <p class="TextBody TextBody--xxxs">Enable this option to listen for page changes.</p>
                                            <?php
                                            $enable_rating_snippet_listen_for_changes = get_option('REVIEWSio_enable_rating_snippet_listen_for_changes');
                                            ?>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_rating_snippet_listen_for_changes">
                                                            <option <?php echo ($enable_rating_snippet_listen_for_changes == 0) ? 'selected' : '' ?> value="0">Disabled (Default)</option>
                                                            <option <?php echo ($enable_rating_snippet_listen_for_changes == 1) ? 'selected' : '' ?> value="1">Enabled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_rating_snippet_show_empty_stars">Show Empty Stars: </label>
                                            <p class="TextBody TextBody--xxxs">Enable this option to show stars on products with no reviews.</p>
                                            <?php
                                            $enable_rating_snippet_show_empty_stars = get_option('REVIEWSio_enable_rating_snippet_show_empty_stars');
                                            ?>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_rating_snippet_show_empty_stars">
                                                            <option <?php echo ($enable_rating_snippet_show_empty_stars == 0) ? 'selected' : '' ?> value="0">Disabled (Default)</option>
                                                            <option <?php echo ($enable_rating_snippet_show_empty_stars == 1) ? 'selected' : '' ?> value="1">Enabled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_disable_rating_snippet_popup_category">Rating Snippet Popup on Category Pages: </label>
                                            <p class="TextBody TextBody--xxxs">Disable or Enable the Rating Snippet Popup on homepage and category pages.</p>
                                            <?php
                                            $disable_rating_snippet_popup_category = get_option('REVIEWSio_disable_rating_snippet_popup_category');
                                            ?>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_disable_rating_snippet_popup_category">
                                                            <option <?php echo ($disable_rating_snippet_popup_category == '0') ? 'selected' : '' ?> value="0">Disabled</option>
                                                            <option <?php echo ($disable_rating_snippet_popup_category == '1') ? 'selected' : '' ?> value="1">Enabled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_rating_snippet_custom_collection_location">Custom Category Pages Location:</label>
                                            <p class="TextBody TextBody--xxxs">Enable this option to set custom hook locations to add the rating to specific locations in your theme. The default hook used is <code>woocommerce_after_shop_loop_item</code> which is located on the category page.</p>
                                            <?php
                                            $enable_rating_snippet_custom_collection_location = get_option('REVIEWSio_enable_rating_snippet_custom_collection_location');
                                            $custom_rating_snippet_collection_hook = get_option('REVIEWSio_custom_rating_snippet_collection_hook');
                                            ?>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_rating_snippet_custom_collection_location">
                                                            <option <?php echo ($enable_rating_snippet_custom_collection_location == 0) ? 'selected' : '' ?> value="0">Disabled (Default)</option>
                                                            <option <?php echo ($enable_rating_snippet_custom_collection_location == 1) ? 'selected' : '' ?> value="1">Enabled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Add new tag -->
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-8" style="display: flex">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <input id="rating-snippet-new-hook" type="text" class="Field__input u-width--100" style="max-width: none;" placeholder="Add a template hook" on="addNewAttribute()" />
                                                        <div class="Field__feedback">
                                                            <div class="feedback__inner js-field-feedback">
                                                                Error
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="Button Button--sm Button--primary u-marginTop--xs u-marginLeft--sm" style="height: 46px; margin-top: 2px !important" onclick="addNewAttribute('rating-snippet-new-hook', 'rating-snippet-hooks', 'rating-snippet-hooks-tags')">Add</div>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Tag value list -->
                                            <input type="hidden" id="rating-snippet-hooks" class="js-tags-list" name="REVIEWSio_custom_rating_snippet_collection_hook" value="<?php echo esc_attr(htmlentities($custom_rating_snippet_collection_hook)); ?>">

                                            <!-- Tags list -->
                                            <div class="u-marginBottom--sm">
                                                <div class="TagsInputElement">
                                                    <ul id="rating-snippet-hooks-tags" class="flex-row tags"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="nuggets" class="form-table js-widget" style="display: none">
                                    <div class="flex-row">
                                        <div class="flex-col-xxs-12 flex-col-md-6">
                                            <h3><strong>Nuggets Widget Settings</strong></h3>
                                            <p>
                                                A mobile-friendly Review Nuggets Widget that displays excerpts from company and product reviews. It can be placed close to your call-to-action buttons.
                                            </p>
                                            <ul class="list">
                                                <li>Easy to select review snippets to add to the nugget from already existing reviews.</li>
                                                <li>This can be placed next to a product on a product page.</li>
                                                <li>Customize the types of reviews shown.</li>
                                                <li>Able to style in line with the user's requirements.</li>
                                                <li>Minimal design.</li>
                                            </ul>
                                        </div>
                                        <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                            <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--nuggets-widget@2x.png">
                                        </div>
                                    </div>

                                    <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                    <h3><strong>Nuggets on Product Page</strong></h3>
                                    <div>
                                        <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_nuggets_widget">Add Widget Above "Call to Action" Button</label>
                                        <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                            Use the dropdown menu to enable or disable the Nuggets widget. Select 'Yes' to enable the widget, or 'No' to disable.
                                        </p>

                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-sm-6">
                                                <div class="Field u-marginTop--xxs u-width--100" tooltip="Use the dropdown menu to enable or disable the Nuggets widget. Select 'Yes' to enable the widget, or 'No' to disable." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                    <?php
                                                    $enable_nuggets_widget = get_option('REVIEWSio_enable_nuggets_widget');
                                                    ?>
                                                    <select id="js-nuggets" class="Field__input Field__input--globalSelect u-width--100 widget-active-state" style="max-width: none;" name="REVIEWSio_enable_nuggets_widget" onchange="widgetOptionsActiveState(this)">
                                                        <option <?php echo ($enable_nuggets_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                                        <option <?php echo ($enable_nuggets_widget == 0) ? 'selected' : '' ?> value="0">No</option>
                                                    </select>
                                                    <div class="Field__label">
                                                        Setting
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-sm-6 js-nuggets-widget-option-container">
                                                <div class="Field u-marginTop--xxs u-width--100" tooltip="The dropdown menu to the contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                    <?php
                                                    $nuggets_widget_options = get_option('REVIEWSio_nuggets_widget_options');
                                                    ?>
                                                    <input id="nuggets-widget-option" type="hidden" value="<?php echo esc_attr($nuggets_widget_options) ?>">
                                                    <select id="nuggets-widget-options-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name='REVIEWSio_nuggets_widget_options'></select>
                                                    <div class="Field__label">
                                                        Widget Style
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                    <div>
                                        <h3><strong>Generate Nuggets Widget Shortcode</strong></h3>
                                        <p class="TextBody TextBody--xxxs">
                                            Enhance your website with dynamic content and features using shortcodes, which are small pieces of code enclosed in square brackets, using <code>[widget_name]</code>. For our Nuggets shortcode, simply add the 'widget_id' from our widget editor in the format <code>[nuggets_widget widget_id='your widget id']</code>.
                                        </p>
                                        <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                            Additional information on embedding shortcodes can be found in the <a href="https://wordpress.com/support/wordpress-editor/blocks/shortcode-block/" target="_blank">WordPress Documentation</a>.
                                        </p>
                                        <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                            Generate the widget shortcode using the controls provided and add it to specific template files to incorporate the Nuggets widget on your desired page.
                                        </p>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-sm-6">
                                                <div class="Field u-marginTop--xxs u-width--100" tooltip="The dropdown menu contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and copy the shortcode generated below." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                    <select id="nuggets_shortcode-widget-options-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" onchange="addWidgetIdToShortcode(this)">
                                                        <option value="">Please Select</option>
                                                    </select>
                                                    <div class="Field__label">
                                                        Widget Style
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-sm-6">
                                                <div class="Field u-marginTop--xxs u-width--100" tooltip="If you wish to show specific product reviews for this particular shortcode, please type in the required sku's in text field below and copy the shortcode generated below." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                    <input id="nuggets_shortcode-widget-sku" type="text" class="Field__input" placeholder="sku1;sku2" oninput="addSkuToShortcode(this)">
                                                    <div class="Field__label">
                                                        Sku (Optional)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="GlobalNotification GlobalNotification--coloured-success u-marginBottom--lg">
                                            <div class="flex-row flex-middle-xxs">
                                                <div class="flex-col-xxs-1 u-textCenter--all">
                                                    <img class="GlobalNotification__imageIcon" src="https://assets.reviews.io/img/all-global-assets/icons/icon-code--md--colour.svg">
                                                </div>
                                                <div class="flex-col-xxs-9">
                                                    <div class="TextHeading TextHeading--xxxxs">Use the following shortcode to embed widget on a page:</div>
                                                    <div id="nuggets_shortcode-shortcode" class="TextBody TextBody--xxxs u-marginBottom--none">
                                                        [nuggets_widget<span></span><span></span>]
                                                    </div>
                                                </div>
                                                <div class="flex-col-xxs-2 u-textRight--all">
                                                    <div id="nuggets_shortcode-shortcode-copy-button" class="Button Button--xs Button--outline u-marginBottom--none" onclick="copyToClipboard('nuggets_shortcode-shortcode-copy-button', 'nuggets_shortcode-shortcode')">
                                                        Copy
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="floating" class="form-table js-widget" style="display: none">
                                    <div>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-md-6">
                                                <h3><strong>Floating Widget Settings</strong></h3>
                                                <p>
                                                    A customisable floating widget that doesn't affect your website's layout. Shows reviews, merchant metrics, 3rd party feedback & more. The launcher button is positioned at the bottom or side of a screen.
                                                </p>
                                                <p>
                                                    The Floating widget is compact and fits in with the layout of your site. When clicked, it expands to reveal review content, containing star ratings and review comments.
                                                </p>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--floating-widget--center--custom@2x.png">
                                            </div>
                                        </div>

                                        <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_nuggets_widget">Add Widget in the site</label>
                                            <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                                Use the dropdown menu to enable or disable the Nuggets widget. Select 'Yes' to enable the widget, or 'No' to disable.
                                            </p>

                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="Use the dropdown menu to enable or disable the Floating widget. Select 'Yes' to enable the widget, or 'No' to disable." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <?php
                                                        $enable_floating_react_widget = get_option('REVIEWSio_enable_floating_react_widget');
                                                        ?>
                                                        <select id="js-floating" class="Field__input Field__input--globalSelect u-width--100 widget-active-state" style="max-width: none;" name="REVIEWSio_enable_floating_react_widget" onchange="widgetOptionsActiveState(this)">
                                                            <option <?php echo ($enable_floating_react_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                                            <option <?php echo ($enable_floating_react_widget == 0) ? 'selected' : '' ?> value="0">No</option>
                                                        </select>
                                                        <div class="Field__label">
                                                            Setting
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-col-xxs-12 flex-col-sm-6 js-floating-widget-option-container">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="The dropdown menu to the contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <?php
                                                        $floating_react_widget_options = get_option('REVIEWSio_floating_react_widget_options');
                                                        ?>
                                                        <input id="floating-react-widget-option" type="hidden" value="<?php echo esc_attr($floating_react_widget_options) ?>">
                                                        <select id="floating-react-widget-options-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name='REVIEWSio_floating_react_widget_options'></select>
                                                        <div class="Field__label">
                                                            Widget Style
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php /*
										<label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_floating_react_widget">Enable floating Widget: </label>
										<p class="TextBody TextBody--xxxs">
											Use the dropdown menu to enable or disable the Floating widget. Select 'Yes' to enable the widget, or 'No' to disable.
										</p>
										
										<?php
											$enable_floating_react_widget = get_option('REVIEWSio_enable_floating_react_widget');
										?>
										<div class="flex-row">
											<div class="flex-col-xxs-12 flex-col-sm-6">
												<div class="Field u-marginTop--xxs u-width--100">
													<select id="js-floating" class="Field__input Field__input--globalSelect u-width--100 widget-active-state" style="max-width: none;" name="REVIEWSio_enable_floating_react_widget">
														<option <?php echo ($enable_floating_react_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
														<option <?php echo ($enable_floating_react_widget == 0) ? 'selected' : '' ?> value="0">No</option>
													</select>`
												</div>
											</div>
										</div>
									</div>
			
									<div>
										<label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_floating_react_widget_option">Floating Widget Styles: </label>
										<p class="TextBody TextBody--xxxs">
											The dropdown menu to the right contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes.
										</p>

										<?php
											$floating_react_widget_options = get_option('REVIEWSio_floating_react_widget_options');
										?>
										<div class="flex-row">
											<div class="flex-col-xxs-12 flex-col-sm-6">
												<div class="Field u-marginTop--xxs u-width--100">
													<input id="floating-react-widget-option" type="hidden" value="<?php echo $floating_react_widget_options ?>">
													<select id="floating-react-widget-options-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name='REVIEWSio_floating_react_widget_options'></select>
												</div>
											</div>
										</div>
								*/ ?>
                                    </div>
                                </div>

                                <div id="ugc" class="form-table js-widget" style="display: none">
                                    <div>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-md-6">
                                                <h3><strong>UGC Widget Shortcode Settings</strong></h3>
                                                <p>
                                                    Showcase a collection of user-generated content, including engaging reviews and vibrant Instagram photos, creating an immersive visual experience that captivates your audience and highlights the authenticity of your brand
                                                </p>
                                                <p>
                                                    By incorporating UGC galleries, you can leverage the social proof and credibility of user-generated content to boost customer trust, encourage active engagement, and ultimately drive conversions.
                                                </p>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--ugc-widget@2x.png">
                                            </div>
                                        </div>

                                        <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                        <div>
                                            <h3><strong>Generate UGC Widget Shortcode</strong></h3>
                                            <p class="TextBody TextBody--xxxs">
                                                Enhance your website with dynamic content and features using shortcodes, which are small pieces of code enclosed in square brackets, using <code>[widget_name]</code>. For our UGC shortcode, simply add the 'widget_id' from our widget editor in the format <code>[ugc_widget widget_id='your widget id']</code>.
                                            </p>
                                            <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                                Additional information on embedding shortcodes can be found in the <a href="https://wordpress.com/support/wordpress-editor/blocks/shortcode-block/" target="_blank">WordPress Documentation</a>.
                                            </p>
                                            <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                                Generate the widget shortcode using the controls provided and add it to specific template files to incorporate the UGC widget on your desired page.
                                            </p>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="The dropdown menu contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and copy the shortcode generated below." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <select id="ugc-widget-options-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" onchange="addWidgetIdToShortcode(this)">
                                                            <option value="">Please Select</option>
                                                        </select>
                                                        <div class="Field__label">
                                                            Widget Style
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="If you wish to show specific product reviews for this particular shortcode, please type in the required sku's in text field below and copy the shortcode generated below." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <input id="ugc-widget-sku" type="text" class="Field__input" name='REVIEWSio_ugc_widget_sku' placeholder="sku1;sku2" oninput="addSkuToShortcode(this)">
                                                        <div class="Field__label">
                                                            Sku (Optional)
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="GlobalNotification GlobalNotification--coloured-success u-marginBottom--lg">
                                                <div class="flex-row flex-middle-xxs">
                                                    <div class="flex-col-xxs-1 u-textCenter--all">
                                                        <img class="GlobalNotification__imageIcon" src="https://assets.reviews.io/img/all-global-assets/icons/icon-code--md--colour.svg">
                                                    </div>
                                                    <div class="flex-col-xxs-9">
                                                        <div class="TextHeading TextHeading--xxxxs">Use the following shortcode to embed widget on a page:</div>
                                                        <div id="ugc-shortcode" class="TextBody TextBody--xxxs u-marginBottom--none">
                                                            [ugc_widget<span></span><span></span>]
                                                        </div>
                                                    </div>
                                                    <div class="flex-col-xxs-2 u-textRight--all">
                                                        <div id="ugc-shortcode-copy-button" class="Button Button--xs Button--outline u-marginBottom--none" onclick="copyToClipboard('ugc-shortcode-copy-button', 'ugc-shortcode')">
                                                            Copy
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="survey" class="form-table js-widget" style="display: none">
                                    <div>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-md-6">
                                                <h3><strong>Survey Widget Settings</strong></h3>
                                                <p>
                                                    A customisable widget that displays your surveys.
                                                    <br><br>
                                                    Integrating the survey widget on your website can help you gather valuable feedback on various aspects of your online presence, such as website functionality, features, user experience, and more. This feedback can inform decision-making and drive improvements to enhance overall customer satisfaction.
                                                </p>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--survey-widget--center--minimal--thumbs@2x.png">
                                            </div>
                                        </div>

                                        <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_enable_nuggets_widget">Add widget in the site</label>
                                            <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                                Use the dropdown menu to enable or disable the Survey widget. Select 'Yes' to enable the widget, or 'No' to disable.
                                            </p>



                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="Use the dropdown menu to enable or disable the Nuggets widget. Select 'Yes' to enable the widget, or 'No' to disable." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <?php
                                                        $enable_survey_widget = get_option('REVIEWSio_enable_survey_widget');
                                                        ?>
                                                        <select id="js-survey" class="Field__input Field__input--globalSelect u-width--100 widget-active-state" style="max-width: none;" name="REVIEWSio_enable_survey_widget" onchange="widgetOptionsActiveState(this)">
                                                            <option <?php echo ($enable_survey_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                                            <option <?php echo ($enable_survey_widget == 0) ? 'selected' : '' ?> value="0">No</option>
                                                        </select>
                                                        <div class="Field__label">
                                                            Setting
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex-col-xxs-12 flex-col-sm-6 js-survey-widget-option-container">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="The dropdown menu to the contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and the corresponding styles will be applied to your widget on saving changes." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <?php
                                                        $survey_widget_options = get_option('REVIEWSio_survey_widget_options');
                                                        ?>
                                                        <input id="survey-widget-option" type="hidden" value="<?php echo esc_attr($survey_widget_options); ?>">
                                                        <select id="survey-widget-options-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name='REVIEWSio_survey_widget_options'></select>
                                                        <div class="Field__label">
                                                            Widget Style
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>


                                            <div class="flex-row js-survey-widget-option-container">
                                                <div class="flex-col-xxs-12">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="Please select a campaign from the list of available campaigns. This will load the correct survey for the customers based on your selection." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <?php
                                                        $survey_widget_campaign_options = get_option('REVIEWSio_survey_widget_campaign_options');
                                                        ?>
                                                        <input id="survey-widget-campaign" type="hidden" value="<?php echo esc_attr($survey_widget_campaign_options) ?>">
                                                        <select id="survey-widget-campaign-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name='REVIEWSio_survey_widget_campaign_options'>
                                                            <option value="">Please Select</option>
                                                        </select>
                                                        <div class="Field__label">
                                                            Survey Widget Campaign
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>




                                    </div>

                                </div>

                                <div id="rating-bar" class="form-table js-widget" style="display: none">
                                    <div>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-md-6">
                                                <h3><strong>Rating Bar Shortcode Settings</strong></h3>
                                                <p>
                                                    Elevate your website's appeal and engagement with our versatile and customizable rating bar widget. Seamlessly display your overall rating and review count in a visually appealing format that effortlessly integrates into your website's design.
                                                </p>
                                                <p>
                                                    Whether you choose to incorporate it as an elegant inline element or as a convenient sticky bar positioned at the top or bottom of the page, our rating bar widget empowers you to effortlessly showcase the essence of your customers' experiences, adding a touch of sophistication and credibility to your brand.
                                                </p>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--rating-bar-widget--top--1@2x.png">
                                            </div>
                                        </div>

                                        <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                        <div>
                                            <h3><strong>Generate Rating Bar Shortcode</strong></h3>
                                            <p class="TextBody TextBody--xxxs">
                                                Enhance your website with dynamic content and features using shortcodes, which are small pieces of code enclosed in square brackets, using <code>[widget_name]</code>. For our Rating Bar shortcode, simply add the 'widget_id' from our widget editor in the format <code>[rating_bar_widget widget_id='your widget id']</code>.
                                            </p>
                                            <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                                Additional information on embedding shortcodes can be found in the <a href="https://wordpress.com/support/wordpress-editor/blocks/shortcode-block/" target="_blank">WordPress Documentation</a>.
                                            </p>
                                            <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                                Generate the widget shortcode using the controls provided and add it to specific template files to incorporate the Rating Bar widget on your desired page.
                                            </p>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="The dropdown menu contains a list of your personalised styles made in the REVIEWS.io widget editor. Simply select the option you want from the list, and copy the shortcode generated below." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <select id="rating_bar-widget-options-dropdown" class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" onchange="addWidgetIdToShortcode(this)">
                                                            <option value="">Please Select</option>
                                                        </select>
                                                        <div class="Field__label">
                                                            Widget Style
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                                    <div class="Field u-marginTop--xxs u-width--100" tooltip="If you wish to show specific product reviews for this particular shortcode, please type in the required sku's in text field below and copy the shortcode generated below." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                        <input id="rating_bar-widget-sku" type="text" class="Field__input" placeholder="sku1;sku2" oninput="addSkuToShortcode(this)">
                                                        <div class="Field__label">
                                                            Sku (Optional)
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="GlobalNotification GlobalNotification--coloured-success u-marginBottom--lg">
                                                <div class="flex-row flex-middle-xxs">
                                                    <div class="flex-col-xxs-1 u-textCenter--all">
                                                        <img class="GlobalNotification__imageIcon" src="https://assets.reviews.io/img/all-global-assets/icons/icon-code--md--colour.svg">
                                                    </div>
                                                    <div class="flex-col-xxs-9">
                                                        <div class="TextHeading TextHeading--xxxxs">Use the following shortcode to embed widget on a page:</div>
                                                        <div id="rating_bar-shortcode" class="TextBody TextBody--xxxs u-marginBottom--none">
                                                            [rating_bar_widget<span></span><span></span>]
                                                        </div>
                                                    </div>
                                                    <div class="flex-col-xxs-2 u-textRight--all">
                                                        <div id="rating_bar-shortcode-copy-button" class="Button Button--xs Button--outline u-marginBottom--none" onclick="copyToClipboard('rating_bar-shortcode-copy-button', 'rating_bar-shortcode')">
                                                            Copy
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="carousel" class="form-table js-widget" style="display: none">
                                    <div>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-md-6">
                                                <h3><strong>Carousel Shortcode Settings</strong></h3>
                                                <p>
                                                    A minimal carousel widget with a header on the side. Displays reviews, photos, videos & feedback from 3rd party platforms in cards sliding horizontally.
                                                </p>
                                                <p>
                                                    Present a diverse range of reviews in a visually appealing and interactive manner, allowing users to explore and engage with a variety of reviews, photos and videos.
                                                </p>
                                            </div>
                                            <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                                <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/thumbnail--carousel-sideheader-cards-widget@2x.png">
                                            </div>
                                        </div>

                                        <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                        <h3><strong>Generate Carousel Shortcode</strong></h3>
                                        <p class="TextBody TextBody--xxxs">
                                            Enhance your website with dynamic content and features using shortcodes, which are small pieces of code enclosed in square brackets, using <code>[widget_name]</code>. For our Rating Bar shortcode, simply add the 'widget_id' from our widget editor in the format <code>[carousel_widget]</code>.
                                        </p>
                                        <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                            Additional information on embedding shortcodes can be found in the <a href="https://wordpress.com/support/wordpress-editor/blocks/shortcode-block/" target="_blank">WordPress Documentation</a>.
                                        </p>
                                        <div class="flex-row" style="gap: 20px">
                                            <div class="flex-col-xs">
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_carousel_type">Carousel Type</label>
                                                <p class="TextBody TextBody--xxxs u-marginBottom--md">
                                                    Generate the widget shortcode using the controls provided and add it to specific template files to incorporate the Rating Bar widget on your desired page.
                                                    <br>
                                                    <strong>Note:</strong> Please save any changes you wish to make to the Carousel Type or Custom Carousel Styles field.
                                                </p>
                                                <div class="flex-row">
                                                    <div class="flex-col-xxs-12 flex-col-sm-6">
                                                        <div class="Field u-marginTop--xxs u-width--100" tooltip="Select the type of Carousel widget to display in the page, which will be applied to all carousel shortcodes. Note: Please save the changes to apply the carousel type." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                            <?php
                                                            $carousel_type = get_option('REVIEWSio_carousel_type');
                                                            ?>
                                                            <select class="Field__input Field__input--globalSelect" name="REVIEWSio_carousel_type">
                                                                <option <?php echo ($carousel_type == 'card') ? 'selected' : '' ?> value="card">Card Carousel</option>
                                                                <option <?php echo ($carousel_type == 'carousel') ? 'selected' : '' ?> value="carousel">Carousel</option>
                                                                <option <?php echo ($carousel_type == 'fullwidth_card') ? 'selected' : '' ?> value="fullwidth_card">Fullwidth Card Carousel</option>
                                                                <option <?php echo ($carousel_type == 'fullwidth') ? 'selected' : '' ?> value="fullwidth">Fullwidth Carousel</option>
                                                                <option <?php echo ($carousel_type == 'bulky') ? 'selected' : '' ?> value="bulky">Bulky Carousel</option>
                                                            </select>
                                                            <div class="Field__label">
                                                                Carousel Type
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-col-xxs-12 flex-col-sm-6">
                                                        <div class="Field u-marginTop--xxs u-width--100" tooltip="If you wish to show specific product reviews for this particular shortcode, please type in the required sku's in text field below and copy the shortcode generated below." tooltip-size="180" tooltip-fontsize="xxxxs" tooltip-position="top" tooltip-enable="false">
                                                            <input id="carousel-widget-sku" type="text" class="Field__input" placeholder="sku1;sku2" oninput="addSkuToShortcode(this)">
                                                            <div class="Field__label">
                                                                Sku (Optional)
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="u-paddingTop--sm">
                                            <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_carousel_custom_styles">Custom Carousel Styles</label>
                                            <p class="TextBody TextBody--xxxs">
                                                Set custom options and styles for the carousel widget such as review types and or minimum number of reviews to display. The options can be edited from the REVIEWS.io widget editor and all the styles from the 'options' object and below can be copied over to the text area. Leaving field blank sets the default styles.
                                                <br>
                                                <strong>Note:</strong> Please save the changes to apply these custom styles.
                                            </p>

                                            <?php
                                            $carousel_custom_styles = get_option('REVIEWSio_carousel_custom_styles');
                                            ?>
                                            <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_carousel_custom_styles" style="width:100%;height:400px;border-color:#D1D8DA;border-radius:4px;padding:12px;"><?php echo esc_textarea($carousel_custom_styles); ?></textarea>
                                        </div>

                                        <div class="GlobalNotification GlobalNotification--coloured-success u-marginBottom--lg u-marginTop--lg">
                                            <div class="flex-row flex-middle-xxs">
                                                <div class="flex-col-xxs-1 u-textCenter--all">
                                                    <img class="GlobalNotification__imageIcon" src="https://assets.reviews.io/img/all-global-assets/icons/icon-code--md--colour.svg">
                                                </div>
                                                <div class="flex-col-xxs-9">
                                                    <div class="TextHeading TextHeading--xxxxs">Use the following shortcode to embed widget on a page:</div>
                                                    <div id="carousel-shortcode" class="TextBody TextBody--xxxs u-marginBottom--none">
                                                        [carousel_widget<span></span><span></span>]
                                                    </div>
                                                </div>
                                                <div class="flex-col-xxs-2 u-textRight--all">
                                                    <div id="carousel-shortcode-copy-button" class="Button Button--xs Button--outline u-marginBottom--none" onclick="copyToClipboard('carousel-shortcode-copy-button', 'carousel-shortcode')">
                                                        Copy
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="custom" class="form-table js-widget" style="display: none">
                                    <div class="flex-row">
                                        <div class="flex-col-xxs-12 flex-col-md-6">
                                            <h3><strong>Header and Footer Scripts</strong></h3>
                                            <p>
                                                Add custom code before the footer, which can help widgets to be displayed on all pages at the bottom, in the homepage or all pages.
                                            </p>
                                        </div>
                                        <div class="flex-col-xxs-12 flex-col-md-6 u-textCenter--all">
                                            <!-- <img style="max-width:420px" class="u-width--100" src="https://assets.reviews.io/img/all-global-assets/pages/widgets/reviewsio-widgets@2x.png"> -->
                                        </div>
                                    </div>
                                    <div class="u-hr u-marginTop--md u-marginBottom--md"></div>

                                    <div>
                                        <h3><strong>Footer Scripts</strong></h3>
                                        <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_footer_scripts">Add script before the footer</label>
                                        <div>
                                            <p class="TextBody TextBody--xxxs">
                                                Enable or disable execution of footer scripts using the dropdown below.
                                            </p>
                                        </div>
                                        <?php
                                        $enable_footer_scripts = get_option('REVIEWSio_enable_footer_scripts');
                                        ?>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12">
                                                <div class="Field u-marginTop--xxs">
                                                    <select class="Field__input Field__input--globalSelect" style="max-width: none" name="REVIEWSio_enable_footer_scripts">
                                                        <option <?php echo ($enable_footer_scripts == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                                        <option <?php echo ($enable_footer_scripts == 0) ? 'selected' : '' ?> value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_custom_footer_hooks">Hook Name </label>
                                            <p class="TextBody TextBody--xxxs">The <code>storefront_before_footer</code> hook from the WooCommerce Storefront theme is used by default. This can be customised by setting the field to a hook of your choice.</p>

                                            <!-- Add new attribute -->
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12" style="display: flex">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <input id="custom-footer-hook-new" type="text" class="Field__input u-width--100" style="max-width: none;" placeholder="Add a template hook" on="addNewAttribute()" />
                                                        <div class="Field__feedback">
                                                            <div class="feedback__inner js-field-feedback">
                                                                Error
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="Button Button--sm Button--primary u-marginTop--xs u-marginLeft--sm" onclick="addNewAttribute('custom-footer-hook-new', 'custom-footer-hooks-list', 'custom-footer-hooks-tags')">Add</div>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Attribute value -->
                                            <?php
                                            $custom_footer_hooks = get_option('REVIEWSio_custom_footer_hooks');
                                            ?>

                                            <input type="hidden" id="custom-footer-hooks-list" class="js-tags-list" name="REVIEWSio_custom_footer_hooks" value="<?php echo esc_attr(htmlentities($custom_footer_hooks)); ?>">

                                            <!-- Attribute tags -->
                                            <div class="u-marginBottom--sm">
                                                <div class="TagsInputElement">
                                                    <ul id="custom-footer-hooks-tags" class="flex-row tags"></ul>
                                                </div>
                                            </div>
                                        </div>

                                        <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_footer_scripts">Footer Configuration Options</label>
                                        <div>
                                            <p class="TextBody TextBody--xxxs u-marginBottom--sm">
                                                Customise options related to displaying content above the footer
                                            </p>
                                        </div>
                                        <div class="TextBody TextBody--xxxs u-marginBottom--md">
                                            <div style="display: flex;">
                                                <label class="CheckSelection u-marginBottom--none">
                                                    <?php
                                                    $footer_show_on_homepage = get_option('REVIEWSio_footer_show_on_homepage');
                                                    ?>
                                                    <input class="CS__field" type="checkbox" name="REVIEWSio_footer_show_on_homepage" <?php echo $footer_show_on_homepage ? 'checked' : ''; ?>>
                                                    <div class="CS__check">
                                                        <i class="ricon-checkmark"></i>
                                                    </div>
                                                </label>
                                                <p class="TextBody TextBody--xxxs u-marginBottom--none">Show on homepage</p>
                                            </div>
                                        </div>

                                        <div class="TextBody TextBody--xxxs u-marginBottom--md">
                                            <div style="display: flex;">
                                                <label class="CheckSelection u-marginBottom--none">
                                                    <?php
                                                    $footer_show_on_collection_pages = get_option('REVIEWSio_footer_show_on_collection_pages');
                                                    ?>
                                                    <input class="CS__field" type="checkbox" name="REVIEWSio_footer_show_on_collection_pages" <?php echo $footer_show_on_collection_pages ? 'checked' : ''; ?>>
                                                    <div class="CS__check">
                                                        <i class="ricon-checkmark"></i>
                                                    </div>
                                                </label>
                                                <p class="TextBody TextBody--xxxs u-marginBottom--none">Show on collection pages</p>
                                            </div>
                                        </div>

                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12">
                                                <?php
                                                $footer_custom_script = get_option('REVIEWSio_footer_custom_script');
                                                ?>
                                                <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_footer_custom_script" placeholder="Place footer code here." style="width:100%;height:200px;border-color:#D1D8DA;border-radius:4px;padding:12px;"><?php echo esc_textarea($footer_custom_script); ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div id="legacy" class="form-table js-widget" style="display: none">
                                    <div class="GlobalNotification GlobalNotification--sm GlobalNotification--coloured-warning u-marginBottom--md" style="display: block;">
                                        <div class="flex-row flex-middle-xxs">
                                            <div class="flex-col-xxs-12">
                                                <div class="TextHeading TextHeading--xxxxs u-marginBottom--none">
                                                    Please Note
                                                </div>
                                                <div id="js-collector-current-widget-info" class="js-collector-toggle-info TextBody TextBody--xxxs u-marginBottom--none">
                                                    The widgets and options below are being deprecated and will be removed in future updates, please use the main widgets found under the REVIEWS.io Widgets tab to ensure you have the latest updates and features.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h3><strong>Floating Widget Settings</strong></h3>
                                        <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_floating_widget">Enable Legacy Floating Widget: </label>
                                        <div>
                                            <p class="TextBody TextBody--xxxs">
                                                A floating reviews tab will be added to the right side of your site.
                                            </p>
                                        </div>
                                        <?php
                                        $enable_floating_widget = get_option('REVIEWSio_enable_floating_widget');
                                        ?>
                                        <div class="flex-row">
                                            <div class="flex-col-xxs-12 flex-col-sm-6">
                                                <div class="Field u-marginTop--xxs u-width--100">
                                                    <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_floating_widget">
                                                        <option <?php echo ($enable_floating_widget == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                                        <option <?php echo ($enable_floating_widget == 0) ? 'selected' : '' ?> value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                                    <div>
                                        <div>
                                            <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_hide_legacy">Show Legacy Widget Settings</label>
                                            <p class="TextBody TextBody--xxxs">Enable this if you would like to use the legacy product widget.</p>
                                            <p class="TextBody TextBody--xxxs"><strong>Note:</strong> Save changes to show/hide additional settings for the legacy widget.</p>
                                            <div class="flex-row">
                                                <div class="flex-col-xxs-12 flex-col-sm-6">
                                                    <div class="Field u-marginTop--xxs u-width--100">
                                                        <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_hide_legacy">
                                                            <option <?php echo ($hide_legacy == 1) ? 'selected' : '' ?> value="1">No (Recommended)</option>
                                                            <option <?php echo ($hide_legacy == 0) ? 'selected' : '' ?> value="0">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $product_review_widget = get_option('REVIEWSio_product_review_widget');
                                        if (!$hide_legacy) {
                                        ?>
                                            <div>
                                                <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_product_review_widget">Show Legacy Product Review Widget: </label>
                                                <p class="TextBody TextBody--xxxs">Please note that to use the Legacy Widget, the "Show Product Review Widget" setting in the Product Reviews Widget tab must be set to "Do Not Display".</p>

                                                <div class="flex-row">
                                                    <div class="flex-col-xxs-12 flex-col-sm-6">
                                                        <div class="Field u-marginTop--xxs u-width--100">
                                                            <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_product_review_widget">
                                                                <option <?php echo ($product_review_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Reviews Tab</option>
                                                                <option <?php echo ($product_review_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
                                                                <option <?php echo ($product_review_widget == 'both') ? 'selected' : '' ?> value="both">Show in Both Places</option>
                                                                <option <?php echo ($product_review_widget == '0') ? 'selected' : '' ?> value="0">Do Not Display</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="TextHeading TextHeading--xxxs u-marginTop--xxs" for="REVIEWSio_widget_custom_css">Widget Custom CSS: </label>
                                                <p class="TextBody TextBody--xxxs">Add custom CSS to the legacy product reviews widget</p>
                                                <?php
                                                $widget_custom_css = get_option('REVIEWSio_widget_custom_css');
                                                ?>
                                                <div class="flex-row">
                                                    <div class="flex-col-xxs-12 flex-col-sm-6">
                                                        <div class="Field u-marginTop--xxs u-width--100">
                                                            <textarea class="Field__input u-whiteSpace--prewrap" name="REVIEWSio_widget_custom_css"><?php echo esc_textarea(htmlentities($widget_custom_css)); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        if (!$hide_legacy) {
                                        ?>
                                            <div class="form-table">
                                                <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_product_review_widget">Show Legacy Question Answers Widget: </label>
                                                <p class="TextBody TextBody--xxxs" style="font-size:12px;font-weight:100;">The widget will be displayed in a tab on your product pages.</p>

                                                <?php
                                                $question_answers_widget = get_option('REVIEWSio_question_answers_widget');
                                                ?>
                                                <div class="flex-row">
                                                    <div class="flex-col-xxs-12 flex-col-sm-6">
                                                        <div class="Field u-marginTop--xxs u-width--100">
                                                            <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_question_answers_widget">
                                                                <option <?php echo ($question_answers_widget == 'tab') ? 'selected' : '' ?> value="tab">Show In Tab</option>
                                                                <option <?php echo ($question_answers_widget == 'summary') ? 'selected' : '' ?> value="summary">Show Below Product Summary</option>
                                                                <option <?php echo ($question_answers_widget == 'both') ? 'selected' : '' ?> value="both">Show in Both Places</option>
                                                                <option <?php echo ($question_answers_widget == '0') ? 'selected' : '' ?> value="0">Dont Display</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-contents js-advanced-tab">
                <!-- Advanced -->

                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_disable_elementor_blocks">Disable Elementor Widget Blocks: </label>
                    <p class="TextBody TextBody--xxxs">If enabled, all REVIEWSio widget blocks in the Elementor editor and blocks placed on Elementor pages will be removed.</p>

                    <?php
                    $disable_elementor_blocks = get_option('REVIEWSio_disable_elementor_blocks');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_disable_elementor_blocks">
                                    <option <?php echo ($disable_elementor_blocks == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($disable_elementor_blocks == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_disable_reviews_per_product">Disable Reviews Per Product: </label>
                    <p class="TextBody TextBody--xxxs">If this is enabled then you can use the WooCommerce "Reviews Enabled" setting to disable review collection for certain products.</p>

                    <?php
                    $disable_reviews_per_product = get_option('REVIEWSio_disable_reviews_per_product');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_disable_reviews_per_product">
                                    <option <?php echo ($disable_reviews_per_product == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($disable_reviews_per_product == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_cron">Enable Cron For Review Invitations: </label>
                    <p class="TextBody TextBody--xxxs">
                        If you use a third party system to mark orders as completed then review invitations may not be triggered. If this setting is enabled a cron will run hourly which queues invitations for recently completed orders. <br /><br /> To prevent the cron running on visitor page loads you should disable WP_CRON and setup a real cron as described <a target='_blank' href='https://easyengine.io/tutorials/wordpress/wp-cron-crontab/'>here</a>.
                    </p>

                    <?php
                    $enableCron = get_option('REVIEWSio_enable_cron');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_cron">
                                    <option <?php echo ($enableCron == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($enableCron == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" class="TextHeading TextHeading--xxxs" for="REVIEWSio_enable_cron">Enable Cron For Product Feed: </label>
                    <p class="TextBody TextBody--xxxs">
                        Enable this option to generate a product feed updated daily to the server.
                    </p>

                    <?php
                    $enableProductFeedCron = get_option('REVIEWSio_enable_product_feed_cron');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_enable_product_feed_cron">
                                    <option <?php echo ($enableProductFeedCron == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                    <option <?php echo ($enableProductFeedCron == 0) ? 'selected' : '' ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_product_identifier">Change Product Identifier</label>
                    <p class="TextBody TextBody--xxxs">Use a different identifier for your products and variants. This identifier will be used for new invitations and for looking up existing reviews.</p>

                    <?php
                    $product_identifier = get_option('REVIEWSio_product_identifier');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_product_identifier">
                                    <option <?php echo ($product_identifier == 'sku') ? 'selected' : '' ?> value="sku">SKU (Recommended)</option>
                                    <option <?php echo ($product_identifier == 'id') ? 'selected' : '' ?> value="id">ID</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_use_parent_product">Use Parent Product</label>
                    <p class="TextBody TextBody--xxxs">Enable this if you would like to only collect reviews on a parent product level. This is useful if you have products with many variations and you want to keep the data more manageable. </p>

                    <?php
                    $use_parent_product = get_option('REVIEWSio_use_parent_product');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_use_parent_product">
                                    <option <?php echo ($use_parent_product == 0) ? 'selected' : '' ?> value="0">No (Default)</option>
                                    <option <?php echo ($use_parent_product == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="u-hr u-marginTop--md u-marginBottom--md"></div>
                <div>
                    <label class="TextHeading TextHeading--xxxs" for="REVIEWSio_use_parent_product_rich_snippet">Use Parent Product only on Rich Snippets</label>
                    <p class="TextBody TextBody--xxxs">Enable this if you would like to only show rich snippet data for the parent product, and not any accompanying variants.</p>

                    <?php
                    $use_parent_product_rich = get_option('REVIEWSio_use_parent_product_rich_snippet');
                    ?>
                    <div class="flex-row">
                        <div class="flex-col-xxs-12 flex-col-sm-6">
                            <div class="Field u-marginTop--xxs u-width--100">
                                <select class="Field__input Field__input--globalSelect u-width--100" style="max-width: none;" name="REVIEWSio_use_parent_product_rich_snippet">
                                    <option <?php echo ($use_parent_product_rich == 0) ? 'selected' : '' ?> value="0">No (Default)</option>
                                    <option <?php echo ($use_parent_product_rich == 1) ? 'selected' : '' ?> value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php add_action('admin_enqueue_scripts', 'reviewsio_admin_scripts'); ?>
        <div class="u-textRight--all">
            <input type="submit" name="submit" id="submit" class="Button Button--primary Button--sm" value="Save Changes">
        </div>
    </form>
</div>