<?php

if(!defined('ABSPATH')) {
  exit;
}

/**
 * Plugin Name: REVIEWS.io for WooCommerce
 * Depends: WooCommerce
 * Plugin URI: https://wordpress.org/plugins/reviewscouk-for-woocommerce/
 * Description: REVIEWS.io is an all-in-one solution for your review strategy. Collect company, product, video, and photo reviews to increase your conversation rate both in your store and on Google.
 * Author: Reviews.co.uk
 * License: GPL
 * Version: 0.5
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 7.7
 */

function reviewsio_admin_scripts() {
    wp_register_script('reviewsio-admin-script',false, array(),false, false);
    wp_enqueue_script('reviewsio-admin-script');
    wp_add_inline_script('reviewsio-admin-script','
        document.addEventListener("DOMContentLoaded", function() {
            formatDataFeed();
            jQuery(".widget-active-state").each(function() {
                widgetOptionsActiveState(jQuery(this));
            })
            let domain = "";

            jQuery(".js-api-tab").css("display", "block");
            jQuery(".FlexTabs__item").click(function(e) {
                e.preventDefault();
                jQuery(this).addClass("isActive");
                jQuery(this).siblings().removeClass("isActive");
                
                let tab = jQuery(this).attr("id");
                let tabContent = jQuery("." + tab)

                jQuery(".tab-contents").each(function() {
                    const tabContent = jQuery(this);
                    tabContent.css("display", "none");
                    
                    if (tabContent.hasClass(tab)) {
                        tabContent.fadeIn();
                    }
                })
            });

            jQuery("#reviewsio-settings").keydown(function(event) {
                event.which === 13 && event.preventDefault();
            });

            // Color picker selector
            let inputs = jQuery(`#reviewsio-settings [id*="color"]`).filter(".colour-picker");
            let colorSelectorIds = getInputIds(inputs);
            for (i = 0; i < colorSelectorIds.length; i++) {
                initEditorColorPickr(colorSelectorIds[i]);
            }
        });

        jQuery.ajax({
            url: "https://api.reviews.io/woocommerce/info",
            headers: {
                "store": "' . get_option("REVIEWSio_store_id") . '",
                "apikey": "' . get_option('REVIEWSio_api_key') . '",
            },
            success: function(res) {
                jQuery("#api-notification").css("display", "none");
                jQuery(".FlexTabs__item").removeClass("u-pointerEvents--none Button--disabled");
                
                if (res && res.data) {
                    let data = res.data;
                    domain = res.data.domain;
                    let region = data.domain == "co.uk" ? "uk" : "us";

                    let regionInput = jQuery("#REVIEWSio_region");
                    let params = new Proxy(new URLSearchParams(window.location.search), {
                        get: (searchParams, prop) => searchParams.get(prop),
                    });
                    let value = params.page;
                    
                    if ((value === "reviewscouk") && ((regionInput.val() == "") || (regionInput.val() != region))) {
                        jQuery("#api-notification-heading").text("Please Wait");
                        jQuery("#api-notification-text").text("Configuring store domain.");
                        jQuery("#api-notification").css("display", "block");
                        regionInput.val(region);
                        jQuery("#submit").click();
                    }

                    jQuery(".js-validated-user").css("display", "block");
                    jQuery(".js-invalidated-user").css("display", "none");
                    
                    if ((data.stats.store_total_reviews > 0) || (data.stats.product_total_reviews > 0)) {
                        const stats = data.stats;
                        let heading = "Overall Statistics";
                        let message = `<p><span style="white-space: nowrap">Average Company Rating: <strong>${stats.store_average_rating}</strong></span> &nbsp;|&nbsp; <span style="white-space: nowrap">Company Reviews: <strong>${stats.store_total_reviews}</strong></strong></span> &nbsp;|&nbsp; <span style="white-space: nowrap"></strong>Average Product Rating: <strong>${stats.product_average_rating}</strong></span> &nbsp;|&nbsp; <span style="white-space: nowrap"></strong>Product Reviews: <strong>${stats.product_total_reviews}</span></p>`;
                        
                        jQuery("#welcomeHeading").html(heading);
                        jQuery("#welcomeText").html(message);
                    }
                }
            },
            error: function(e) {
                jQuery(".FlexTabs__item").addClass("u-pointerEvents--none Button--disabled");
                jQuery(".js-validated-user").css("display", "none");
                jQuery(".js-invalidated-user").css("display", "block");
                jQuery("#api-notification").css("display", "block");
            },
        });

        jQuery.ajax({
            url: "https://api.reviews.io/widget/survey-campaigns",
            headers: {
                "store": "' . get_option("REVIEWSio_store_id") . '",
                "apikey": "' . get_option('REVIEWSio_api_key') . '",
            },
            success: function(data) {
                if (data && data.survey_campaigns) {
                    let dropdown = null;
                    let selectedField = null;
                    let attrValue = null;

                    selectedField = jQuery("#survey-widget-campaign").val();
                    dropdown = jQuery("#survey-widget-campaign-dropdown");
                    dropdown.find("option").remove();
                    
                    data.survey_campaigns.forEach(function(item) {
                        attrValue = this.widget_id == selectedField ? "selected" : false
                        dropdown.append(jQuery("<option />").val(item.id).text(item.title).attr("selected", attrValue));
                    });
                }
            },
            error: function(e) {
            },
        });

        function widgetOptionsActiveState(e) {
            let optionId = jQuery(e).attr("id");
            let optionName = optionId.substring(3);
            let option = jQuery(e).find(":selected").val();
            
            if (option == 1) {
                jQuery(`.js-${optionName}-widget-option-container`).css("display", "block");
            } else {
                jQuery(`.js-${optionName}-widget-option-container`).css("display", "none");
            }
        }
        
        function polarisTabActiveState() {
            let el = jQuery("#polaris-widget-location")
            let val = el.find(":selected").val();

            if (val == "tab") {
                jQuery(".js-polaris-review-tab-name").css("display", "block");
            } else {
                jQuery(".js-polaris-review-tab-name").css("display", "none");
            }
        }

        function toggleFeedNotification() {
            jQuery(".js-feed-notification").css("display", "block");
        }
        
        function toggleFeedFeedback(type) {
            let newFeedAttrInput = jQuery("#product-feed-custom-attributes-new");
            let feedback = newFeedAttrInput.siblings().filter(".Field__feedback").children();

            switch (type) {
                case "empty":
                    feedback.text("Please fill in this field");
                    break;
                case "exists":
                    feedback.text("This attribute has already been added");
                    break;
            }

            newFeedAttrInput.parent().addClass("isFailure");
        }

        function removeDataFeed(buttonId) {
            let selectedItem = buttonId.replace("feed-button-", "");
            let feedAttrInput = jQuery("#product-feed-custom-attributes");

            let feed = feedAttrInput.val().split(", ");
            feed = feed.filter(item => item !== selectedItem);
            let newFeed = feed.join(", ");
            feedAttrInput.val(newFeed);
            toggleFeedNotification();
        }

        function formatDataFeed() {
            if (!jQuery("#product-feed-custom-attributes").length) return;
            let feedAttrInput = jQuery("#product-feed-custom-attributes");
            let feedListElement = jQuery("#product_feed_custom_attributes-list");
            let feed = feedAttrInput.val().split(", ");
            if ((!feed) || (feed[0] === "")) return;

            feedListElement.empty();
            feed.forEach(function(item, idx) {
                jQuery("<li>", {
                    id: `feed-${item}`,
                    text: item,
                }).appendTo(feedListElement);
                
                jQuery("<span>", {
                    id: `feed-button-${item}`,
                    class: "remove-button",
                    text: "x",
                }).appendTo(jQuery(`#feed-${item}`));
            });
        }

        function addNewAttribute() {
            let newFeedAttrInput = jQuery("#product-feed-custom-attributes-new");
            let feedAttrInput = jQuery("#product-feed-custom-attributes");

            newFeedAttrInput.parent().removeClass("isFailure");
            let feed = feedAttrInput.val().split(", ")

            if (newFeedAttrInput.val() == "") {
                toggleFeedFeedback("empty")
                return;
            }
            if (feed.includes(newFeedAttrInput.val())) {
                toggleFeedFeedback("exists")
                return;
            }

            if (feed.length === 1 && feed[0] === "") feed = [];
            feed.push(newFeedAttrInput.val());
            let newFeed = feed.join(", ");
            
            feedAttrInput.val(newFeed);
            newFeedAttrInput.val("").focus();
            toggleFeedNotification();
            formatDataFeed();

            jQuery(".feed-list li span").click(function() {
                removeDataFeed(jQuery(this).attr("id"));
                jQuery(this).parent().remove();
            });
        }

        jQuery(document).ready(function() {
            let inputField = jQuery("#product-feed-custom-attributes-new");
            inputField.keyup(function(event) {
                if (jQuery(this).is(":focus")) {
                    if (event.keyCode === 13) {
                        addNewAttribute();
                    }
                }
            });

            jQuery(".feed-list li span").click(function() {
                let listItem = jQuery(this);
                removeDataFeed(listItem.attr("id"));
                listItem.parent().remove();
            });
        });

        function getInputIds(inputs) {
            let ids = [];
            inputs.each(function (index) {
                ids.push(jQuery(this).attr("id"));
            });
            return ids;
        }

        let editorColorPickers = {};
        function initEditorColorPickr(id) {
            editorColorPickers[id] = Pickr.create({
                el: "#"+id,
                theme: "nano",
                default: jQuery("#input-"+id).val(),
                components: {
                    preview: true,
                    opacity: true,
                    hue: true,
                    interaction: {
                        hex: true,
                        rgba: true,
                        input: true,
                        save: true
                    }
                }
            });

            editorColorPickers[id].on("save", function(color, instance) {
                let colorString = editorColorPickers[id].getColor().toHEXA().toString();
                document.querySelector("#input-"+id).value = colorString;
                editorColorPickers[id].setColor(colorString, true);
                editorColorPickers[id].hide();
            });
        }
    ');

    // Get widget options list
    getWidgetsData();

    
    wp_register_style( 'reviewsio-dashboard-style',  'https://assets.reviews.io/css/dashboard.css', array(), '', false);
    wp_enqueue_style('reviewsio-dashboard-style');
    wp_register_style( 'reviewsio-admin-style',  false, array(), '', false);
    wp_enqueue_style('reviewsio-admin-style');
    wp_add_inline_style('reviewsio-admin-style','
        code {
            padding: 2px 4px;
            font-size: 90%;
            color: #c7254e;
            white-space: nowrap;
            background-color: #f9f2f4;
            border-radius: 4px;
        }

        label {
            cursor: default;
        }
        
        .settings_page_reviewscouk .tabs-menu {
          height: 29px;
          clear: both;
          margin: 0;
          margin-bottom: 10px;
        }

        .settings_page_reviewscouk .tabs-menu li {
            height: 30px;
            line-height: 30px;
            float: left;
            margin-bottom:-2px;
            margin-right: 10px;
            border-width: 1px;
            border-radius: 4px;
            background: #fff;
            border-color: rgba(35,36,53,.1);
            box-shadow: 0 2px 10px -2px rgb(0 0 0 / 7%);
        }

        .settings_page_reviewscouk .tabs-menu li.current {
            position: relative;
            background-color: #fff;
            border-bottom: 1px solid #fff;
            z-index: 5;
        }

        .settings_page_reviewscouk .tabs-menu li a {
            padding: 10px;
            color: #000;
            text-decoration: none;
            box-shadow:none;
            outline:0;
        }

        .settings_page_reviewscouk .tabs-menu .current a {
            color: #000;
            font-weight: 600;
        }

        .settings_page_reviewscouk .tab {
            clear: both;
            box-shadow: 0 2px 10px -2px rgb(0 0 0 / 7%);
            border-radius: 4px;
            background-color: #fff;
            margin-bottom: 20px;
            width: auto;
        }

        .settings_page_reviewscouk .tab-content {
            // width: 660px;
            padding: 20px;
            display: none;
        }

        .settings_page_reviewscouk #tab-1 {
         display: block;
        }

        .menu-container {
            display: flex;
        }

        .side-menu {
            width: 200px;
        }
        
        .side-menu ul {
            width: 200px;
        }

        .side-menu ul li {
            padding: 10px 5px;
            cursor: pointer;
        }

        .tab-contents {
            display: none;
        }

        .list {
            list-style: disc;
            padding-left: 40px;
        }

        .remove-button {
            margin: 0 0 0 5px;
            padding: 0;
            border: none;
            background: 0 0;
            cursor: pointer;
            vertical-align: middle;
            font: 700 16px Arial,sans-serif;
        }

        .TagsInputElement .feed-list li {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            text-align: center !important;
            padding: 5px 15px !important;
            gap: 5px;
        }

        .Field--colourPicker .colourPicker__indicator .pickr .pcr-button {
            transform: scale(2);
        }
    ');

}

function getWidgetsData() {
    wp_register_script('reviewsio-widget-options-script',false, array(),false, false);
    wp_enqueue_script('reviewsio-widget-options-script');
    wp_add_inline_script('reviewsio-widget-options-script','
        document.addEventListener("DOMContentLoaded", function() {
            jQuery(".widget-active-state").change(function(e) {
                if (e.target.value != "1") return;
                getWidgetOptionsList(this.id.slice(3));
            });
        });

        function showWidget(divId) {
            jQuery(".js-widget-tab").removeClass("isActive");
            const tabName = "#" + divId + "-tab";
            jQuery(tabName).addClass("isActive");

            jQuery(".js-widget").css("display", "none");
            jQuery("#" + divId).fadeIn();
        }

        function addWidgetIdToShortcode(e) {
            let element = jQuery(e);
            let elementId =  element.attr("id");
            let selectedWidget = elementId.substring(0, elementId.indexOf("-"));
            let shortcodeElement = jQuery(`#${selectedWidget}-shortcode`);
            let widgetId = jQuery(e).find(":selected").val();

            shortcodeElement.children().eq(0).text("");
            if (widgetId != "") {
                shortcodeElement.children().eq(0).append(`\&#32;widget_id=\'${widgetId}\'`);
            }
        }
        
        function addSkuToShortcode(e) {
            let sku = "";
            let element = jQuery(e);
            let elementId =  element.attr("id");
            let selectedWidget = elementId.substring(0, elementId.indexOf("-"));
            let shortcodeElement = jQuery(`#${selectedWidget}-shortcode`);
            sku = jQuery(`#${selectedWidget}-widget-sku`).val();
        
            shortcodeElement.children().eq(1).text("");
            if (sku != "") {
                shortcodeElement.children().eq(1).append(`\&#32;sku=\'${sku}\'`);
            }
        }

        function copyToClipboard(buttonId, copyId) {
            let text = document.getElementById(copyId).textContent.trim();
			navigator.clipboard.writeText(text);

            let copyButton = document.getElementById(buttonId);
			copyButton.innerHTML = "Copied!";
			setTimeout(() => {
				copyButton.innerHTML = "Copy";
			}, 2000);
		};
        
        function getWidgetOptionsList (selectedWidget = "") {
            jQuery.ajax({
                url: `https://api.reviews.io/widget/list-with-key?widget=nuggets,floating,ugc,survey,rating-bar&selected_widget=${selectedWidget}&url_key='.get_option("REVIEWSio_store_id").'`,
                headers: {
                    "apikey": "' . get_option('REVIEWSio_api_key') . '",
                    "store": "' . get_option("REVIEWSio_store_id") . '",
                },
                success: function(data) {
                    if (data && data.widget_options_list) {
                        let dropdown = null;
                        let selectedField = null;
                        let attrValue = null;

                        let optionsCount = [
                            {
                                name: "nuggets",
                                editor: "nuggets",
                                count: 0
                            },
                            {
                                name: "ugc",
                                editor: "ugc",
                                count: 0
                            },
                            {
                                name: "rating_bar",
                                editor: "rating-bar",
                                count: 0
                            },
                        ]

                        jQuery("#nuggets-widget-options-dropdown").find("option").not(":first").remove();
                        jQuery("#nuggets_shortcode-widget-options-dropdown").find("option").not(":first").remove();
                        jQuery("#floating-react-widget-options-dropdown").find("option").not(":first").remove();
                        jQuery("#ugc-widget-options-dropdown").find("option").not(":first").remove();
                        jQuery("#survey-widget-options-dropdown").find("option").not(":first").remove();
                        jQuery("#rating_bar-widget-options-dropdown").find("option").not(":first").remove();
                        
                        jQuery.each(data.widget_options_list, function() {
                            switch (this.widget_name) {
                                case "nuggets-widget":
                                    let reviewType = "";
                                    let widgetOptions = JSON.parse(this.widget_options);
                                    dropdown = jQuery("#nuggets-widget-options-dropdown");
                                    let shortcodeDropdown = jQuery("#nuggets_shortcode-widget-options-dropdown");
                                    selectedField = jQuery("#nuggets-widget-option").val();

                                    switch (widgetOptions.types) {
                                        case "product_review":
                                            reviewType = "(Product Nuggets)";
                                            break;
                                        case "store_review":
                                            reviewType = "(Company Nuggets)";
                                            break;
                                        case "store_review,product_review":
                                            reviewType = "(All Nuggets)";
                                            break;
                                    }

                                    attrValue = this.widget_id == selectedField ? "selected" : false
                                    dropdown.append(jQuery("<option />").val(this.widget_id).text(this.name + " " + reviewType).attr("selected", attrValue));
                                    shortcodeDropdown.append(jQuery("<option />").val(this.widget_id).text(this.name + " " + reviewType));
                                    optionsCount[0].count++;
                                    return;
                                case "floating-widget":
                                    dropdown = jQuery("#floating-react-widget-options-dropdown");
                                    selectedField = jQuery("#floating-react-widget-option").val();
                                    break;
                                case "ugc-widget":
                                    dropdown = jQuery("#ugc-widget-options-dropdown");
                                    selectedField = jQuery("#ugc-widget-option").val();
                                    optionsCount[1].count++;
                                    break;
                                case "survey-widget":
                                    dropdown = jQuery("#survey-widget-options-dropdown");
                                    selectedField = jQuery("#survey-widget-option").val();
                                    break;
                                case "rating-bar-widget":
                                    dropdown = jQuery("#rating_bar-widget-options-dropdown");
                                    selectedField = jQuery("#rating_bar-widget-option").val();
                                    optionsCount[2].count++;
                                    break;
                            }
                                
                            attrValue = this.widget_id == selectedField ? "selected" : false
                            dropdown.append(jQuery("<option />").val(this.widget_id).text(this.name).attr("selected", attrValue));
                        });

                        optionsCount.forEach(function(item) {
                            if (item.count == 0) {
                                let parent = jQuery(`#${item.name}-widget-options-dropdown`).parent();
                                
                                parent.parent().parent().siblings().prevAll().eq(1).text("Personalize widget styles to create a more engaging and cohesive design that aligns with your preferences and needs. The styles can be edited in the REVIEWS.io widget editor.");
                                
                                parent.parent().parent().siblings().nextAll().eq(1).remove();
                                parent.parent().siblings().empty();
                                parent.empty();
                                jQuery("<a>", {
                                    class: "Button Button--primary Button--sm",
                                    text: "Customise Widget",
                                    target: "_blank",
                                    href: `https://dash.reviews.${domain}/widgets/editor/${item.editor}`,
                                }).appendTo(parent);
                            }
                        })
                    }
                }
            });
        }

        getWidgetOptionsList();
    ');
}

if (!class_exists('WooCommerce_Reviews')) {
    class WooCommerce_Reviews
    {

        /**
         * Used for local testing
         *
         * 'live' -- for live server
         * 'dev' -- for dev testing
         */
        protected $env  = 'live'; // change to 'dev' for testing and setup your urls !!! DON'T forget to revert when push to live !!!
        protected $urls = [
            'widget' => 'http://localhost:8040/',
            'dash'   => 'https://dashboard.test/',
            'api'    => 'http://restapi.test/',
        ];

        protected $numWidgets = 0;
        protected $richsnippet_shortcode_url = '';

        public function __construct()
        {
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'add_menu'));
            add_filter('init', array($this, 'init'));
            add_action('hourly_order_process_event', array($this, 'process_recent_orders'));
            register_activation_hook(__FILE__, array($this, 'run_on_activation'));
            register_deactivation_hook(__FILE__, array($this, 'run_on_deactivate'));

            if (get_option('REVIEWSio_enable_product_rich_snippet')) {
                add_filter( 'wpseo_schema_product', '__return_false');
            }
        }

        public function getSubDomain($sub)
        {
            if ($this->env == 'dev') {
                return $this->urls[$sub];
            }
            $region = get_option('REVIEWSio_region');
            if ($region == 'uk') {
                return 'https://' . $sub . '.reviews.co.uk/';
            } else {
                return 'https://' . $sub . '.reviews.io/';
            }
        }

        public function getWidgetDomain()
        {
            return $this->getSubDomain('widget');
        }

        public function getDashDomain()
        {
            return $this->getSubDomain('dash');
        }

        public function getApiDomain()
        {
            return $this->getSubDomain('api');
        }

        public function admin_init()
        {
            $this->init_settings();
        }

        public function init_settings()
        {
          $optionsPrefix = 'REVIEWSio_';
          $options = ["region","domain","store_id","api_key","product_feed","widget_hex_colour","widget_custom_css",
          "enable_rich_snippet","enable_product_rich_snippet","enable_product_rich_snippet_server_side","enable_product_rating_snippet",
          "enable_nuggets_widget","nuggets_widget_options","nuggets_widget_tags","enable_nuggets_bar_widget","nuggets_bar_widget_id","nuggets_bar_widget_tags","enable_floating_react_widget","floating_react_widget_options","ugc_widget_options","enable_survey_widget","survey_widget_options","survey_widget_campaign_options","carousel_type","carousel_custom_styles",
          "polaris_review_widget","reviews_tab_name","polaris_review_widget_questions","polaris_custom_styles","product_review_widget","question_answers_widget",
          "hide_write_review_button","per_page_review_widget","send_product_review_invitation","enable_cron",
          "enable_floating_widget","product_identifier","disable_reviews_per_product","use_parent_product", "use_parent_product_rich_snippet",
          "custom_reviews_widget_styles","disable_rating_snippet_popup", "disable_rating_snippet_popup_category", "minimum_rating","rating_snippet_text",
          "polaris_lang","disable_rating_snippet_offset","hide_legacy","rating_snippet_no_linebreak","new_variables_set", "product_feed_custom_attributes",
          "widget_custom_header_config", "widget_custom_filtering_config" , "widget_custom_reviews_config", "product_feed_wpseo_global_ids"];

          foreach($options as $o) {
            register_setting('woocommerce-reviews', $optionsPrefix . $o);

            if(get_option($o) && !get_option($optionsPrefix . $o) && !get_option($optionsPrefix . "new_variables_set")) {
              update_option(($optionsPrefix . $o), get_option($o));
            }
          }
          update_option($optionsPrefix. "new_variables_set", 1);
        }

        public function setDefaultSettings()
        {
            update_option('REVIEWSio_product_feed', 1);
            update_option('REVIEWSio_send_product_review_invitation', 1);
            if(!get_option('REVIEWSio_product_review_widget')) {
              update_option('REVIEWSio_polaris_review_widget', 'tab');
              update_option('REVIEWSio_hide_legacy', 1);
            }

            update_option('REVIEWSio_reviews_tab_name', 'Reviews');
            update_option('REVIEWSio_product_identifier', 'sku');
            update_option('REVIEWSio_use_parent_product', 0);
            update_option('REVIEWSio_use_parent_product_rich_snippet', 0);
            update_option('REVIEWSio_disable_rating_snippet_popup', 1);
            update_option('REVIEWSio_disable_rating_snippet_popup_category', 1);
            update_option('REVIEWSio_minimum_rating', "1");
            update_option('REVIEWSio_rating_snippet_text', "Reviews");
            update_option('REVIEWSio_polaris_lang', "en");
            update_option('REVIEWSio_disable_rating_snippet_offset', 0);
            update_option('REVIEWSio_rating_snippet_no_linebreak', 0);
        }

        public function add_menu()
        {
            $page = add_options_page('REVIEWS.io Settings', 'REVIEWS.io', 'manage_options', 'reviewscouk', array(&$this, 'reviews_settings_page'));

            add_action('load-' . $page, array($this, 'load_page'));
        }

        public function load_page()
        {
            if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
                try {
                    $this->afterSettingsUpdated();
                } catch (Exception $e) {
                }
            }
        }

        protected function afterSettingsUpdated()
        {
            $feed    = $this->sendFeed();
            $install = $this->sendAppInstall();
        }

        protected function sendFeed()
        {
            return $this->apiPost('integration/set-feed', array(
                'url'     => get_site_url() . '/index.php/reviews/product_feed',
                'format'  => 'csv',
                'mapping' => array(
                    'id'        => 'sku',
                    'name'      => 'name',
                    'image_url' => 'image_url',
                    'link'      => 'link',
                    'mpn'       => 'mpn',
                ),
            ));
        }

        protected function sendAppInstall()
        {
            return $this->apiPost('integration/app-installed', array(
                'platform' => 'woocommerce',
                'url'      => get_site_url(),
            ));
        }

        protected function apiPost($url, $data)
        {
            try {
                $response = wp_remote_post( $this->getApiDomain() . $url, array(
                    'method'  => 'POST',
                    'headers' => array(
                        'store'        => get_option('REVIEWSio_store_id'),
                        'apikey'       => get_option('REVIEWSio_api_key'),
                        'Content-Type' => 'application/json',
                    ),
                    'body'    => json_encode($data),
                ));

                if (is_array($response)) {
                    return $response['body'];
                }
                return false;
            } catch (Exception $e) {
                return false;
            }
        }

        public function reviews_settings_page()
        {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            include sprintf("%s/settings-page.php", dirname(__FILE__));
        }

        /*
         * This runs hourly and runs processCompletedOrder if it hasn't already been run. This solves problems for clients using solutions like Veeqo to complete orders.
         */
        public function process_recent_orders()
        {
            wp_reset_query();
            if (get_option('REVIEWSio_enable_cron')) {
                $orders = get_posts(array(
                    'numberposts'  => 30,
                    'meta_key'     => '_reviewscouk_status',
                    'meta_compare' => 'NOT EXISTS',
                    'post_type'    => wc_get_order_types(),
                    'post_status'  => array('wc-completed'),
                    'date_query'   => array(
                        'after' => date('Y-m-d', strtotime('-5 days')),
                    ),
                ));

                foreach ($orders as $order) {
                    $this->processCompletedOrder($order->ID);
                }
            }
        }

        public function run_on_activation()
        {
            $this->setDefaultSettings();

            wp_schedule_event(current_time('timestamp'), 'hourly', 'hourly_order_process_event');
        }

        public function run_on_deactivate()
        {
            wp_clear_scheduled_hook('hourly_order_process_event');
        }

        public function processCompletedOrder($order_id)
        {
            update_post_meta($order_id, '_reviewscouk_status', 'processed');

            $api_url = $this->getApiDomain();
            $order   = new WC_Order($order_id);
            $items   = $order->get_items();

            $p = array();
            foreach ($items as $row) {
                $productmeta = wc_get_product($row['product_id']);

                if(!$productmeta) continue;

                $sku         = get_option('REVIEWSio_product_identifier') == 'id' ? $row['product_id'] : $productmeta->get_sku();

                if ($productmeta->get_type() == 'variable' && get_option('REVIEWSio_use_parent_product') != 1) {
                    $available_variations = $productmeta->get_available_variations();
                    foreach ($available_variations as $variation) {
                        if ($variation['variation_id'] == $row['variation_id']) {
                            $sku = get_option('REVIEWSio_product_identifier') == 'id' ? $variation['variation_id'] : $variation['sku'];
                        }
                    }
                }

                $url = get_permalink($row['product_id']);

                $attachment_url = wp_get_attachment_url(get_post_thumbnail_id($row['product_id']));

                if (!empty($sku) && !(get_option('REVIEWSio_disable_reviews_per_product') == '1' && $productmeta->post->comment_status == 'closed')) {
                    $p[] = array(
                        'sku'     => $sku,
                        'name'    => $row['name'],
                        'image'   => $attachment_url,
                        'pageUrl' => $url,
                    );
                }
            }

            $country_code = 'GB';
            if (isset($order->get_address()['country'])) {
                $country_code = $order->get_address()['country'];
            }

            $data = array(
                'order_id' => $order->get_order_number(),
                'email'    => $order->get_billing_email(),
                'name'     => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                'source'   => 'woocom',
                'products' => $p,
                'country_code' => $country_code,
            );

            // Get phone number, format, and send
            $phone = $order->get_billing_phone();
            if(!empty($phone)) {
              $dialing_code = WC()->countries->get_country_calling_code($country_code);
              if(!empty($dialing_code) && is_string($dialing_code) && isset($phone[0])) {
                if ($phone[0] == '0') {
                  $data['phone'] = $dialing_code . ltrim($phone, '0');
                } elseif ($phone[0] == '+') {
                  $data['phone'] = $phone;
                } else {
                  $data['phone'] = $dialing_code . $phone;
                }
              }
            }

            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '' && get_option('REVIEWSio_send_product_review_invitation') == '1') {
                $this->apiPost('invitation', $data);
            }
        }

        function add_async_attribute($tag, $handle) {
            if (stripos($handle, 'reviewsio-rating-snippet')!==false){
                return str_replace(' src=', ' async="async" src=', $tag);
            }else{
                return $tag;
            }
        }
        
        public function reviewsio_rating_snippet_scripts() {
            add_filter('script_loader_tag', [$this, 'add_async_attribute'], 10, 2);

            wp_register_script('reviewsio-rating-snippet', $this->getWidgetDomain().'rating-snippet/dist.js', array(),false, false);
            wp_enqueue_script('reviewsio-rating-snippet');

            wp_register_style( 'reviewsio-rating-snippet-font-style',  false, array(), '', false);
            wp_enqueue_style('reviewsio-rating-snippet-font-style');

            $writeButton = '';
            if(get_option("REVIEWSio_hide_write_review_button") == "1") {
                $writeButton = 'writeButton: false,';
            }

            $load_polaris = true;
            $snippet_disable = '';

            if (is_product() && get_option('REVIEWSio_disable_rating_snippet_popup') == "0") {
                $load_polaris = false;
                $scroll_pos =  get_option('REVIEWSio_disable_rating_snippet_offset') !=='' ? get_option('REVIEWSio_disable_rating_snippet_offset') : 0;
                $snippet_disable = "snippetul = document.querySelectorAll('.ruk_rating_snippet');
                    if (snippetul[0]) {
                        snippetul[0].onclick = function(event) {
                            event.preventDefault();
                            var productWidget = document.getElementById('widget-' + ".$this->numWidgets.");
                            if (productWidget) {
                                if(jQuery){
                                  reviewsTabButton = jQuery('.wc-tabs a[href=\"#tab-reviews\"]');
                                  if(reviewsTabButton.length) {
                                    reviewsTabButton.trigger('click');
                                  }
                                }
                                var topPos = productWidget.offsetTop;
                                productWidget.scrollTop = topPos;
                                window.scrollTo(0, topPos - parseInt(".$scroll_pos ."));
                            }
                        }
                    }
                ";
            } else if (!is_product() && get_option('REVIEWSio_disable_rating_snippet_popup_category') == "0") {
                $load_polaris = false;
                $snippet_disable = "snippetul = document.querySelectorAll('.ruk_rating_snippet');
                    for (i in snippetul) {
                        snippetul[i].onclick = function(event) {

                        }
                    }
                ";
            }
            if (is_product() && get_option('REVIEWSio_disable_rating_snippet_popup_category') == "0") {
                $snippet_disable .= "let ReviewsIO_additionalSnippets = document.querySelectorAll('.ruk_rating_snippet');
                    for (let i=1; i < ReviewsIO_additionalSnippets.length; i++) {
                        if(ReviewsIO_additionalSnippets[i]) {
                            ReviewsIO_additionalSnippets[i].onclick = function(event) {

                            }
                        }
                    }
                ";
            }

            wp_add_inline_script('reviewsio-rating-snippet', '
                window.addEventListener("load", function() {
                    var snippetCss= document.createElement("link");
                    snippetCss.rel = "stylesheet";
                    snippetCss.href = "'.$this->getWidgetDomain().'rating-snippet/dist.css";
                    document.head.insertBefore(snippetCss, document.head.childNodes[document.head.childNodes.length - 1].nextSibling);

                    loadReviewsIoRatingSnippets();
                    '. $snippet_disable .'
                });

                var loadReviewsIoRatingSnippets = function () {
                  ratingSnippet("ruk_rating_snippet",{
                      store: "'. get_option("REVIEWSio_store_id").'",
                      lang: "' . (get_option('REVIEWSio_polaris_lang') ? get_option('REVIEWSio_polaris_lang') : 'en').'",
                      usePolaris: '.($load_polaris?"true":"false").',
                      color: "'. $this->getHexColor() .'",
                      linebreak: "' . (get_option('REVIEWSio_rating_snippet_no_linebreak') == 1 ? false : true).'",
                      minRating: "' . (get_option('REVIEWSio_minimum_rating') ? get_option('REVIEWSio_minimum_rating') : 1).'",
                      text: "' . (get_option('REVIEWSio_rating_snippet_text') ? get_option('REVIEWSio_rating_snippet_text') : 'Reviews').'",
                      '. $writeButton . '
                      '. (!empty(get_option('REVIEWSio_per_page_review_widget')) && is_int((int)get_option('REVIEWSio_per_page_review_widget')) ? 'polarisPerPage:' .get_option('REVIEWSio_per_page_review_widget').',' : '') .'
                      '. (!empty(get_option('REVIEWSio_widget_custom_header_config')) ? 'polarisHeader: {' .get_option('REVIEWSio_widget_custom_header_config').'},' : '') .'
                      '. (!empty(get_option('REVIEWSio_widget_custom_filtering_config')) ? 'polarisFiltering: {' .get_option('REVIEWSio_widget_custom_filtering_config').'},' : '') .'
                      '. (!empty(get_option('REVIEWSio_widget_custom_reviews_config')) ? 'polarisReviews: {' .get_option('REVIEWSio_widget_custom_reviews_config').'},' : '') .'
                  });
                }
            ');
        }

        public function reviewsio_nuggets_widget_scripts($skus = null)
        {
            wp_register_script('reviewsio-nuggets-widget-script', 'https://widget.reviews.io/modern-widgets/nuggets.js', array(),false, false);
            wp_register_style( 'reviewsio-nuggets-widget-style',  'https://assets.reviews.io/css/widgets/nuggets-widget.css', array(), false, false);

            wp_enqueue_script('reviewsio-nuggets-widget-script');
            wp_enqueue_style('reviewsio-nuggets-widget-style');


            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != ''  && get_option('REVIEWSio_nuggets_widget_options') != '') {
                $skus = $this->getProductSkus();
                ?>
                    <script>
                        window.addEventListener('load', function() {
                            let nuggetScript = document.createElement('script');
                            nuggetScript.src = 'https://widget.reviews.io/modern-widgets/nuggets.js';
                            document.getElementsByTagName('head')[0].appendChild(nuggetScript)
                        });
                    </script>
                    <div 
                        class="reviews-io-nuggets-widget"
                        data-widget-id="<?php echo get_option('REVIEWSio_nuggets_widget_options') ?>"
                        data-store-name="<?php echo get_option('REVIEWSio_store_id') ?>"
                        lang="en"
                        data-sku="<?php echo implode(';', $skus) ?>"
                        tags="<?php echo get_option('REVIEWSio_nuggets_widget_tags') ?>"
                        branch=""
                    ></div>
                <?php
            } else {
                echo '<script>console.log("Missing REVIEWS.io API Credentials for Nuggets Widget")</script>';
            }
        }

        public function nuggets_widget_shortcode($widget = null)
        {
            $widget_id = get_option('REVIEWSio_nuggets_widget_options');
            $skus = $this->getProductSkus();

            if (!empty($widget) && !empty($widget['widget_id'])) {
                $widget_id = $widget['widget_id'];
            }

            $skus = implode(';', $skus) . ';';
            if (!empty($widget) && !empty($widget['sku'])) {
                $skus .= $widget['sku'];
            }

            wp_register_script('reviewsio-nuggets-widget-script', 'https://widget.reviews.io/modern-widgets/nuggets.js', array(),false, false);
            wp_register_style( 'reviewsio-nuggets-widget-style',  'https://assets.reviews.io/css/widgets/nuggets-widget.css', array(), false, false);

            wp_enqueue_script('reviewsio-nuggets-widget-script');
            wp_enqueue_style('reviewsio-nuggets-widget-style');


            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id')) {
                ?>
                    <script>
                        window.addEventListener('load', function() {
                            let nuggetScript = document.createElement('script');
                            nuggetScript.src = 'https://widget.reviews.io/modern-widgets/nuggets.js';
                            document.getElementsByTagName('head')[0].appendChild(nuggetScript)
                        });
                    </script>
                <?php               
                    return '
                        <div 
                            class="reviews-io-nuggets-widget"
                            data-widget-id="' . $widget_id . '"
                            data-store-name="' . get_option('REVIEWSio_store_id') . '"
                            lang="en"
                            data-sku="' . $skus . '"
                            tags="' . get_option('REVIEWSio_nuggets_widget_tags') . '"
                            branch=""
                        ></div>
                    ';
            } else {
                echo '<script>console.log("Missing REVIEWS.io API Credentials for Nuggets Widget")</script>';
            }
        }

        public function reviewsio_nuggets_bar_widget_scripts($skus = null)
        {
            // wp_register_script('reviewsio-nuggets-bar-widget-script', 'https://widget.reviews.io/modern-widgets/nuggets.js', array(),false, false);
            // wp_register_style( 'reviewsio-nuggets-bar-widget-style',  'https://assets.reviews.io/css/widgets/nuggets-widget.css', array(), false, false);

            // wp_enqueue_script('reviewsio-nuggets-bar-widget-script');
            // wp_enqueue_style('reviewsio-nuggets-bar-widget-style');


            // if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != ''  && get_option('REVIEWSio_nuggets_bar_widget_id') != '') {
            //     $skus = $this->getProductSkus();
                ?>
                    <!-- <script>
                        window.addEventListener('load', function() {
                            let nuggetScript = document.createElement('script');
                            nuggetScript.src = 'https://widget.reviews.io/modern-widgets/nuggets.js';
                            document.getElementsByTagName('head')[0].appendChild(nuggetScript)
                        });
                    </script>
                    <div 
                        class="reviews-io-nuggets-bar-widget"
                        data-widget-id="xVA8bM2yRrZnzXYT"
                        data-store-name="aj-reviews"
                        lang="en"
                    ></div> -->
                <?php
            // } else {
            //     echo '<script>console.log("Missing REVIEWS.io API Credentials for Nuggets Bar Widget")</script>';
            // }
        }

        public function nuggets_bar_widget_shortcode($widget = null)
        {
            $widget_id = get_option('REVIEWSio_nuggets_bar_widget_id');
            $skus = $this->getProductSkus();

            if (!empty($widget) && !empty($widget['widget_id'])) {
                $widget_id = $widget['widget_id'];
            }

            $skus = implode(';', $skus) . ';';
            if (!empty($widget) && !empty($widget['sku'])) {
                $skus .= $widget['sku'];
            }


            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '' && get_option('REVIEWSio_nuggets_bar_widget_id') != '') {
                ?>
                    <!-- <script>
                        window.addEventListener('load', function() {
                            let nuggetScript = document.createElement('script');
                            nuggetScript.src = 'https://widget.reviews.io/modern-widgets/nuggets.js';
                            document.getElementsByTagName('head')[0].appendChild(nuggetScript)
                        }); -->
                    </script>
                <?php               
                    return '
                        <div 
                            class="reviews-io-nuggets-bar-widget"
                            data-widget-id="' . $widget_id . '"
                            data-store-name="' . get_option('REVIEWSio_store_id') . '"
                            lang="en"
                            data-sku="' . $skus . '"
                        ></div>
                    ';
            } else {
                echo '<script>console.log("Missing REVIEWS.io API Credentials for Nuggets Bar Widget")</script>';
            }
        }
        
        public function reviewsio_floating_react_widget_scripts($skus = null)
        {
            wp_register_script('reviewsio-floating-react-widget-script', 'https://widget.reviews.io/modern-widgets/floating.js', array(),false, false);
            wp_register_style( 'reviewsio-floating-react-widget-style',  'https://assets.reviews.io/css/widgets/floating-widget.css', array(), false, false);

            wp_enqueue_script('reviewsio-floating-reactwidget-script');
            wp_enqueue_style('reviewsio-floating-reactwidget-style');


            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '' && get_option('REVIEWSio_floating_react_widget_options') != '') {
                ?>
                    <?php
                        $skus = $this->getProductSkus();
                    ?>
                    <script>
                        window.addEventListener('load', function() {
                            let floatingcript = document.createElement('script');
                            floatingcript.src = 'https://widget.reviews.io/modern-widgets/floating.js';
                            document.getElementsByTagName('head')[0].appendChild(floatingcript)
                        });
                    </script>
                    <div 
                        class="reviews-io-floating-widget"
                        data-widget-id="<?php echo get_option('REVIEWSio_floating_react_widget_options') ?>"
                        data-store-name="<?php echo get_option('REVIEWSio_store_id') ?>"
                        lang="en"
                    ></div>
                <?php
            } else {
                    echo '<script>console.log("Missing REVIEWS.io Floating Widget API Credentials")</script>';
            }
        }

        public function ugc_widget_shortcode($widget = null)
        {
            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '' && $widget['widget_id'] != '') {
                ?>
                    <script>
                        window.addEventListener('load', function() {
                            let ugcScript = document.createElement('script');
                            ugcScript.src = 'https://widget.reviews.io/modern-widgets/ugc.js';
                            document.getElementsByTagName('head')[0].appendChild(ugcScript);
                        });
                    </script>
                <?php
                    return '
                        <div 
                            class="reviews-io-ugc-widget"
                            data-widget-id="' . $widget['widget_id'] . '"
                            data-store-name="' . get_option('REVIEWSio_store_id') . '"
                            lang="en"
                        ></div>
                    ';
            } else {
                echo '<script>console.log("Missing REVIEWS.io API Credentials for UGC Widget")</script>';
            }
        }

        public function rating_bar_widget_shortcode($widget = null)
        {
            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '' && $widget['widget_id'] != '') {
                ?>
                    <script>
                        window.addEventListener('load', function() {
                            let ratingBarScript = document.createElement('script');
                            ratingBarScript.src = 'https://widget.reviews.io/modern-widgets/rating-bar.js';
                            document.getElementsByTagName('head')[0].appendChild(ratingBarScript)
                        });
                    </script>
                <?php               
                    return '
                        <div 
                            class="reviews-io-rating-bar-widget"
                            data-widget-id="' . $widget['widget_id'] . '"
                            data-store-name="' . get_option('REVIEWSio_store_id') . '"
                            lang="en"
                        ></div>
                    ';
            } else {
                echo '<script>console.log("Missing REVIEWS.io API Credentials for Rating Bar Widget")</script>';
            }
        }

        public function reviewsio_survey_widget_scripts($skus = null)
        {
            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != ''  && get_option('REVIEWSio_survey_widget_options') != '') {
                $skus = $this->getProductSkus();
                ?>
                    <script>
                        window.addEventListener('load', function() {
                            let surveyScript = document.createElement('script');
                            surveyScript.src = 'https://widget.reviews.io/modern-widgets/survey.js';
                            document.getElementsByTagName('head')[0].appendChild(surveyScript)
                        });
                    </script>
                    <div 
                        class="reviews-io-survey-widget"
                        store-name="<?php echo get_option('REVIEWSio_store_id') ?>"
                        widget-id="<?php echo get_option('REVIEWSio_survey_widget_options') ?>"
                        campaign-id="<?php echo get_option('REVIEWSio_survey_widget_campaign_options') ?>"
                        lang="en"
                    ></div>
                <?php
            } else {
                echo '<script>console.log("Missing REVIEWS.io API Credentials for Survey Widget")</script>';
            }
        }

        public function survey_widget_shortcode($widget = null)
        {
            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '' && $widget['widget_id'] != '' && $widget['campaign_id'] != '') {
                ?>
                    <script>
                        window.addEventListener('load', function() {
                            let surveyShortcodeScript = document.createElement('script');
                            surveyShortcodeScript.src = 'https://widget.reviews.io/modern-widgets/survey.js';
                            document.getElementsByTagName('head')[0].appendChild(surveyShortcodeScript)
                        });
                    </script>
                <?php               
                    return '
                        <div 
                            class="reviews-io-survey-widget"
                            widget-id="' . $widget['widget_id'] . '"
                            campaign-id="' . $widget['campaign_id'] . '"
                            store-name="' . get_option('REVIEWSio_store_id') . '"
                            lang="en"
                        ></div>
                    ';
            } else {
                echo '<script>console.log("Missing REVIEWS.io API Credentials for Survey Widget")</script>';
            }
        }

        public function reviewsio_carousel_widget_scripts() {
            wp_register_script('reviewsio-carousel-script', $this->getWidgetDomain().'carousel-inline-iframeless/dist.js?_t=2023032710', array(),false, false);

              wp_enqueue_script('reviewsio-carousel-script');
        }

        public function getCarouselType($option, $type)
        {
            $carouselType = 'default';
            $styles = '';

            switch ($type) {
                case 'card':
                    $carouselType = 'default';
                    $styles = 'CarouselWidget--sideHeader--withcards';        
                    break;
                case 'carousel':
                    $carouselType = 'default';
                    $styles = 'CarouselWidget--sideHeader';        
                    break;
                case 'fullwidth_card':
                    $carouselType = 'topHeader';
                    $styles = 'CarouselWidget--topHeader--withcards';        
                    break;
                case 'fullwidth':
                    $carouselType = 'topHeader';
                    $styles = 'CarouselWidget--topHeader';        
                    break;
                case 'bulky':
                    $carouselType = 'bulky';
                    $styles = 'CarouselWidget--sideHeader--withcards CarouselWidget--scrollButtons-coloured';        
                    break;
                default:
                    break;
            }

            switch ($option) {
                case 'option':
                    return $carouselType;
                case 'styles':
                    return $styles;
            }
        }

        public function carousel_widget_shortcode($widget = null)
        {
            $this->numWidgets++;
            if (get_option('REVIEWSio_api_key') == '' && get_option('REVIEWSio_store_id') == '') {
                echo 'Missing REVIEWS.io API Credentials';
                return;
            }
            
            ?>
                <?php add_action('wp_footer', array($this, 'reviewsio_carousel_widget_scripts')); ?>
                <?php
                    $skus = '';
                    $color = $this->getHexColor();
                    $carouselType = get_option('REVIEWSio_carousel_type');
                    if ($carouselType == '') $carouselType = 'card';
                    if (!empty($widget) && !empty($widget['sku'])) $skus = $widget['sku'];
                ?>
                <script>
                    window.addEventListener('load', function() {
                        let carouselStylesheet = document.createElement('link');
                        carouselStylesheet.type = 'text/css'
                        carouselStylesheet.rel = 'stylesheet'
                        carouselStylesheet.href = 'https://assets.reviews.io/css/widgets/carousel-widget.css?_t=2023032710';
                        document.getElementsByTagName('head')[0].appendChild(carouselStylesheet)

                        new carouselInlineWidget(('carousel-widget-<?php echo $this->numWidgets ?>'), {
                            //Your REVIEWS.io account ID and widget type:
                            store: '<?php echo get_option('REVIEWSio_store_id') ?>',
                            sku: '<?php echo $skus ?>',
                            lang: 'en',
                            carousel_type: '<?php echo $this->getCarouselType('option', $carouselType); ?>',
                            styles_carousel: '<?php echo $this->getCarouselType('styles', $carouselType); ?>',

                            <?php if (empty(get_option('REVIEWSio_carousel_custom_styles'))) { ?>
                                /* Widget settings: */
                                options:{
                                    general:{
                                        /*What reviews should the widget display? Available options: company, product, third_party. You can choose one type or multiple separated by comma.*/
                                        review_type: 'company, product',
                                        /*Minimum number of reviews required for widget to be displayed*/
                                        min_reviews: '1',
                                        /*Maximum number of reviews to include in the carousel widget.*/
                                        max_reviews: '20',
                                        address_format: 'CITY, COUNTRY',
                                        /*Carousel auto-scrolling speed. 3000 = 3 seconds. If you want to disable auto-scroll, set this value to false.*/
                                        enable_auto_scroll: 10000,
                                    },
                                    header:{
                                        /*Show overall rating stars*/
                                        enable_overall_stars: true,
                                        rating_decimal_places: 2,
                                    },
                                    reviews: {
                                        /*Show customer name*/
                                        enable_customer_name: true,
                                        /*Show customer location*/
                                        enable_customer_location: true,
                                        /*Show "verified review" badge*/
                                        enable_verified_badge: true,
                                        /*Show "verified subscriber" badge*/
                                        enable_subscriber_badge: true,
                                        /*Show "I recommend this product" badge (Only for product reviews)*/
                                        enable_recommends_badge: true,
                                        /*Show photos attached to reviews*/
                                        enable_photos: true,
                                        /*Show videos attached to reviews*/
                                        enable_videos: true,
                                        /*Show when review was written*/
                                        enable_review_date: true,
                                        /*Hide reviews written by the same customer (This may occur when customer reviews multiple products)*/
                                        disable_same_customer: true,
                                        /*Minimum star rating*/
                                        min_review_percent: 4,
                                        /*Show 3rd party review source*/
                                        third_party_source: true,
                                        /*Hide reviews without comments (still shows if review has a photo)*/
                                        hide_empty_reviews: true,
                                        /*Show product name*/
                                        enable_product_name: true,
                                        /*Show only reviews which have specific tags (multiple semicolon separated tags allowed i.e tag1;tag2)*/
                                        tags: "",
                                        /*Show branch, only one input*/
                                        branch: "",
                                        enable_branch_name: false,
                                    },
                                    popups: {
                                        /*Make review items clickable (When they are clicked, a popup appears with more information about a customer and review)*/
                                        enable_review_popups:  true,
                                        /*Show "was this review helpful" buttons*/
                                        enable_helpful_buttons: true,
                                        /*Show how many times review was upvoted as helpful*/
                                        enable_helpful_count: true,
                                        /*Show share buttons*/
                                        enable_share_buttons: true,
                                    },
                                },
                                translations: {
                                    verified_customer: "Verified Customer",
                                },
                                styles: {
                                    /*Base font size is a reference size for all text elements. When base value gets changed, all TextHeading and TexBody elements get proportionally adjusted.*/
                                    '--base-font-size': '18px',
                                    '--base-maxwidth':'768px',

                                    /*Logo styles:*/
                                    '--reviewsio-logo-style':'var(--logo-normal)',

                                    /*Star styles:*/
                                    '--common-star-color':' #0E1311',
                                    '--common-star-disabled-color':' rgba(0,0,0,0.25)',
                                    '--medium-star-size':'28px',
                                    '--small-star-size':'19px', /*Modal*/
                                    '--x-small-star-size':'22px',
                                    '--x-small-star-display':'inline-flex',

                                    /*Header styles:*/
                                    '--header-order':'1',
                                    '--header-width':'160px',
                                    '--header-bg-start-color':'transparent',
                                    '--header-bg-end-color':'transparent',
                                    '--header-gradient-direction':'135deg',
                                    '--header-padding':'0.5em',
                                    '--header-border-width':'0px',
                                    '--header-border-color':'rgba(0,0,0,0.1)',
                                    '--header-border-radius':'0px',
                                    '--header-shadow-size':'0px',
                                    '--header-shadow-color':'rgba(0, 0, 0, 0.1)',

                                    /*Header content styles:*/
                                    '--header-star-color':'inherit',
                                    '--header-disabled-star-color':'inherit',
                                    '--header-heading-text-color':'inherit',
                                    '--header-heading-font-size':'1.3em',
                                    '--header-heading-font-weight':'inherit',
                                    '--header-heading-line-height':'inherit',
                                    '--header-heading-text-transform':'inherit',
                                    '--header-subheading-text-color':'inherit',
                                    '--header-subheading-font-size':'inherit',
                                    '--header-subheading-font-weight':'inherit',
                                    '--header-subheading-line-height':'inherit',
                                    '--header-subheading-text-transform':'inherit',

                                    /*Review item styles:*/
                                    '--item-maximum-columns':'1',/*Must be 1*/
                                    '--item-background-start-color':'transparent',
                                    '--item-background-end-color':'transparent',
                                    '--item-gradient-direction':'135deg',
                                    '--item-padding':'0.5em',
                                    '--item-border-width':'0px',
                                    '--item-border-color':'rgba(0,0,0,0.1)',
                                    '--item-border-radius':'0px',
                                    '--item-shadow-size':'0px',
                                    '--item-shadow-color':'rgba(0,0,0,0.1)',

                                    /*Heading styles:*/
                                    '--heading-text-color':' #0E1311',
                                    '--heading-text-font-weight':' 600',
                                    '--heading-text-font-family':' inherit',
                                    '--heading-text-line-height':' 1.4',
                                    '--heading-text-letter-spacing':'0',
                                    '--heading-text-transform':'none',

                                    /*Body text styles:*/
                                    '--body-text-color':' #0E1311',
                                    '--body-text-font-weight':'400',
                                    '--body-text-font-family':' inherit',
                                    '--body-text-line-height':' 1.4',
                                    '--body-text-letter-spacing':'0',
                                    '--body-text-transform':'none',

                                    /*Scroll button styles:*/
                                    '--scroll-button-icon-color':'#0E1311',
                                    '--scroll-button-icon-size':'24px',
                                    '--scroll-button-bg-color':'transparent',

                                    '--scroll-button-border-width':'0px',
                                    '--scroll-button-border-color':'rgba(0,0,0,0.1)',

                                    '--scroll-button-border-radius':'60px',
                                    '--scroll-button-shadow-size':'0px',
                                    '--scroll-button-shadow-color':'rgba(0,0,0,0.1)',
                                    '--scroll-button-horizontal-position':'0px',
                                    '--scroll-button-vertical-position':'0px',

                                    /*Badge styles:*/
                                    '--badge-icon-color':'#0E1311',
                                    '--badge-icon-font-size':'20px',
                                    '--badge-text-color':'#0E1311',
                                    '--badge-text-font-size':'1.2em',
                                    '--badge-text-letter-spacing':'inherit',
                                    '--badge-text-transform':'inherit',

                                    /*Author styles:*/
                                    '--author-font-size':'1.2em',
                                    '--author-font-weight':'inherit',
                                    '--author-text-transform':'inherit',

                                    /*Product photo or review photo styles:*/
                                    '--photo-video-thumbnail-size':'60px',
                                    '--photo-video-thumbnail-border-radius':'0px',

                                    /*Popup styles:*/
                                    '--popup-backdrop-color':'rgba(0,0,0,0.75)',
                                    '--popup-color':'#ffffff',
                                    '--popup-star-color':'inherit',
                                    '--popup-disabled-star-color':'inherit',
                                    '--popup-heading-text-color':'inherit',
                                    '--popup-body-text-color':'inherit',
                                    '--popup-badge-icon-color':'inherit',
                                    '--popup-badge-icon-font-size':'19px',
                                    '--popup-badge-text-color':'inherit',
                                    '--popup-badge-text-font-size':'14px',
                                    '--popup-border-width':'0px',
                                    '--popup-border-color':'rgba(0,0,0,0.1)',
                                    '--popup-border-radius':'0px',
                                    '--popup-shadow-size':'0px',
                                    '--popup-shadow-color':'rgba(0,0,0,0.1)',
                                    '--popup-icon-color':'#0E1311',

                                    /*Tooltip styles:*/
                                    '--tooltip-bg-color':'#0E1311',
                                    '--tooltip-text-color':'#ffffff',
                                },
                            <?php } else {
                                echo get_option('REVIEWSio_carousel_custom_styles');
                            } ?> 
                        });
                    });
                </script>
            <?php
            return '<div id="carousel-widget-' . $this->numWidgets . '"></div>';
        }

        public function reviewsio_product_review_scripts()
        {
            wp_register_script('reviewsio-product-review',$this->getWidgetDomain().'product/dist.js', array(),false, false);
            wp_enqueue_script('reviewsio-product-review');

            $writeButton = '';
            if(get_option("REVIEWSio_hide_write_review_button") == "1") {
                $writeButton = 'writeButton: false,';
            }

            $skus = $this->getProductSkus();
            $color = $this->getHexColor();
            $custom_css = $this->prepareCss(get_option('REVIEWSio_widget_custom_css'));

            wp_add_inline_script('reviewsio-product-review','
                window.addEventListener("load", function() {
                    productWidget("widget-'.$this->numWidgets.'",{
                        store: "'.get_option('REVIEWSio_store_id').'",
                        sku: "'.implode(';', $skus).'",
                        minRating: "' . (get_option('REVIEWSio_minimum_rating') ? get_option('REVIEWSio_minimum_rating') : 1).'",
                        primaryClr: "'. $color .'",
                        neutralClr: "#EBEBEB",
                        buttonClr: "#EEE",
                        textClr: "#333",
                        tabClr: "#eee",
                        ratingStars: false,
                        showAvatars: true,
                        '. $writeButton . '
                        onSummary: function(data){
                            if(jQuery){
                                jQuery(\'[href="#tab-reviews"]\').html(\'Reviews (\'+data.count+\')\');
                            }
                        },
                        css: "'.$custom_css.'",
                    });
                });
            ');
        }

        public function reviewsio_polaris_review_scripts() {
          wp_register_script('reviewsio-polaris-review',$this->getWidgetDomain().'polaris/build.js', array(),false, false);
          wp_enqueue_script('reviewsio-polaris-review');
        }

        public function reviewsio_qa_scripts() {
            wp_register_script('reviewsio-qa',$this->getWidgetDomain().'questions-answers/dist.js', array(),false, false);
            wp_enqueue_script('reviewsio-qa');
            wp_add_inline_script('reviewsio-qa','
                document.addEventListener("load", function() {
                    questionsWidget("questions-widget", {
                        store: "'.get_option('REVIEWSio_store_id').'",
                        group: "'.get_the_id().'"
                    });
                });
            ');
        }

        public function reviewsio_floating_widget_snippet_scripts() {
            wp_register_script('reviewsio-floating-widget-script', $this->getWidgetDomain().'rich-snippet-reviews-widgets/dist.js', array(),false, false);
            wp_register_style( 'reviewsio-floating-widget-style',  $this->getWidgetDomain().'floating-widget/css/dist.css', array(), false, false);

            wp_enqueue_script('reviewsio-floating-widget-script');
            wp_enqueue_style('reviewsio-floating-widget-style');

            wp_add_inline_script('reviewsio-floating-widget-script','
              window.addEventListener("load", (event) => {
                    richSnippetReviewsWidgets({
                        store: "'.(get_option('REVIEWSio_store_id')).'",
                        primaryClr: "'. ($this->getHexColor()) .'",
                        widgetName: "floating-widget",
                        numReviews: 40,
                        floatPosition: "right",
                        contentMode: "company",
                        tabStyle: "normal",
                        hideDates: false
                    });
              });');
        }

        public function reviewsio_rich_snippet_scripts() {

          wp_register_script('reviewsio-rich-snippet',$this->getWidgetDomain().'rich-snippet/dist.js', array(),false, false);
          wp_enqueue_script('reviewsio-rich-snippet');

          if ($this->shouldHideProductReviews()) {
              return;
          }
          $enabled         = get_option('REVIEWSio_enable_rich_snippet');
          $product_enabled = get_option('REVIEWSio_enable_product_rich_snippet');
          $skus            = $this->getProductSkus();

          if ($enabled && empty($skus)) {
              wp_add_inline_script('reviewsio-rich-snippet','
                  richSnippet({
                      store: "' . get_option('REVIEWSio_store_id') . '"
                  });
              ');
          } else if ($product_enabled && !empty($skus) && is_product()) {

            global $product;

            $validUntil = date('Y-m-d', strtotime('+30 days'));

            $brand = $product->get_attribute( 'pa_brand' );

            if ($product->is_type('variable')) {
              $variants = $product->get_available_variations();
            }

            $offer = '{
                "@type": "Offer",
                "itemCondition": "NewCondition",
                "availability": " ' . $this->formatAvailability($product->get_stock_status()) . '",
                "price": "' . $product->get_price() . '",
                "priceCurrency": "' . get_woocommerce_currency() . '",
                "sku": "' . $skus[0] . '",
                "priceValidUntil": "'. $validUntil .'",
                "url": "'.get_permalink($product->get_id()).'",
                "seller" : {
                    "@type": "Organization",
                    "name": "' . get_bloginfo("name") . '",
                    "url": "' . get_bloginfo("url") . '"
                }
            },';

            if(!empty($variants) && !(get_option('REVIEWSio_use_parent_product_rich_snippet') == 1)) {
              foreach($variants as $variant) {
                $offer.= ('{
                    "@type": "Offer",
                    "itemCondition": "NewCondition",
                    "availability": "' . $this->formatAvailability((!empty($variant['is_purchasable']) ? 'instock' : 'outofstock')) . '",
                    "price": "' . $variant['display_price'] . '",
                    "priceCurrency": "' . get_woocommerce_currency() . '",
                    "sku": "' . $variant['sku'] . '",
                    "priceValidUntil": "'. $validUntil .'",
                    "url": "'.get_permalink($product->get_id()).'",
                    ' . apply_filters(('REVIEWSio_snippet-'. $variant['variation_id']), "", $product, $variant). '
                    "seller" : {
                        "@type": "Organization",
                        "name": "' . htmlspecialchars(get_bloginfo("name")) . '",
                        "url": "' . get_bloginfo("url") . '"
                    }
                },');
              }
            }

            $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
            if (get_option('REVIEWSio_enable_product_rich_snippet_server_side')) {
                $baseData = [
                    "@context" => "http://schema.org",
                    "@type" => "Product",
                    "name"=> htmlspecialchars($product->get_name()),
                    "image" => $image[0] ?? '',
                    "description" => json_encode(apply_filters('REVIEWSio_description', htmlspecialchars(strip_tags($product->get_description())), $product)),
                    "brand" => [
                        "@type" => "Brand",
                        "name: " => apply_filters('REVIEWSio_brand', (htmlspecialchars(!empty($brand) ? $brand : get_bloginfo("name"))), $product)
                    ],
                    "offers" => [json_decode('['.rtrim($offer, ',').']')]
                ];

                $snippets = $this->getServerSideSnippets(implode(';', $skus), $baseData);

                if ($snippets) {
                    echo ("<script type='application/ld+json'>" . json_encode($snippets,  JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . "</script>");
                }
            } else {
                wp_add_inline_script('reviewsio-rich-snippet','
                    var reviewsIOConfig = {"store" : `'.get_option('REVIEWSio_store_id').'`, "sku" : `'. implode(';', $skus) .'`};
                    richSnippet({
                        store: "'.get_option('REVIEWSio_store_id').'",
                        sku: "'.implode(';', $skus).'",
                        data:{
                            "@context": "http://schema.org",
                            "@type": "Product",
                            "name": "' . htmlspecialchars($product->get_name()) . '",
                            image: "' . ($image[0] ?? "") . '",
                            description: ' . json_encode(apply_filters('REVIEWSio_description', htmlspecialchars(strip_tags($product->get_description())), $product)) . ',
                            brand: {
                            "@type": "Brand",
                            name: "'.apply_filters('REVIEWSio_brand', (htmlspecialchars(!empty($brand) ? $brand : get_bloginfo("name"))), $product).'"
                            },
                            ' . apply_filters('REVIEWSio_snippet', "", $product). '
                            offers: ['.($offer).']
                        }
                    });
                ');
            }
          }
        }

        private function getServerSideSnippets($sku, $baseData) {
            $json = false;
            $maxRetries = 3;
            $url = 'https://api.reviews.io/json-ld/product/richsnippet?store='.get_option('REVIEWSio_store_id').'&sku='.urlencode($sku).'&data=true&k=1';
            for ($i=0; $i<$maxRetries; $i++) {
                $data = @file_get_contents($url);

                if (($json = json_decode($data, 1)) !== false) {
                    break;
                } else {
                    msleep(10);
                    $url .= "1";
                }
            }

            if (!$json) {
                $json = [];
            }

            return array_merge($json, $baseData);
        }

        public function product_rating_snippet_markup()
        {
            if ($this->shouldHideProductReviews()) {
                return;
            }
            $skus    = $this->getProductSkus();
            $enabled = get_option('REVIEWSio_enable_product_rating_snippet');
            if ($enabled == 1) {
                echo '<div class="ruk_rating_snippet" data-sku="' . implode(';', $skus) . '"></div>';
            }
        }



        public function product_rating_snippet_shortcode()
        {
            $skus = $this->getProductSkus();
            if(!empty($skus)) {
                add_action('wp_footer', array($this, 'reviewsio_rating_snippet_scripts'));
                return '<div class="ruk_rating_snippet" data-sku="' . implode(';', $skus) . '"></div>';
            }
        }
        

        /**
         * format availability status
         * @param $status string
         *
         * @return string -- http://schema.org/InStock
         */
        public function formatAvailability($stock_status)
        {
            switch ($stock_status) {
                case 'instock':
                    return 'http://schema.org/InStock';
                    break;
                case 'outofstock':
                    return 'http://schema.org/OutOfStock';
                    break;

                default:
                    return 'http://schema.org/InStock';
                    break;
            }
        }

        public function getProductSkus()
        {
            global $product;
            $cache = 'REVIEWSio_skus-'.get_the_ID();

            if(wp_cache_get($cache)) {
              return wp_cache_get($cache);
            }

            $skus = [];
            if (is_object($product) && $product instanceof WC_Product) {
                $meta = get_post_meta(get_the_ID(), '_sku');
                $sku  = get_option('REVIEWSio_product_identifier') == 'id' ? get_the_ID() : (isset($meta[0]) ? $meta[0] : '');
                if (!empty($sku)) {
                    $skus[] = $sku;
                }

                if(get_option('REVIEWSio_use_parent_product') == 1) {
                  return $skus;
                }

                if ($product->get_type() == 'variable') {
                    $available_variations = $product->get_available_variations();
                    foreach ($available_variations as $variant) {
                        $skus[] = get_option('REVIEWSio_product_identifier') == 'id' ? $variant['variation_id'] : $variant['sku'];
                    }
                }
            }

            wp_cache_set($cache, $skus, '', 7200);

            return $skus;
        }

        public function redirect_hook($page_template)
        {
            $actual_link = explode('/', get_site_url() . $_SERVER['REQUEST_URI']);


            if (in_array('product_feed', $actual_link) && in_array('reviews', $actual_link)) {
                $product_feed = get_option('REVIEWSio_product_feed');
                if ($product_feed) {
                    global $wp_query;
                    status_header(200);
                    $wp_query->is_404 = false;
                    include dirname(__FILE__) . '/product-feed.php';
                    exit();
                }
            }

            if (in_array('order_csv', $actual_link) && in_array('reviews', $actual_link)) {
                if (is_user_logged_in() && current_user_can('manage_options')) {
                    global $wp_query;
                    status_header(200);
                    $wp_query->is_404 = false;
                    include dirname(__FILE__) . '/order_csv.php';
                    exit();
                } else {
                    auth_redirect();
                }
            }

            return $page_template;
        }

        public function product_review_tab($tabs)
        {
            // if (empty(get_option('REVIEWSio_reviews_tab_name'))) return;
            if (in_array(get_option('REVIEWSio_polaris_review_widget'), array('tab'))) {
                $tabs['reviews'] = array(
                    'title'    => !empty(get_option('REVIEWSio_reviews_tab_name')) ? get_option('REVIEWSio_reviews_tab_name') : 'Reviews',
                    'callback' => array($this, 'polarisReviewWidget'),
                    'priority' => 50,
                );

                if ($this->shouldHideProductReviews()) {
                    unset($tabs['reviews']);
                }
            } else if (in_array(get_option('REVIEWSio_product_review_widget'), array('tab', 'both')))
            {
                $tabs['reviews'] = array(
                    'title'    => !empty(get_option('REVIEWSio_reviews_tab_name')) ? get_option('REVIEWSio_reviews_tab_name') : 'Reviews',
                    'callback' => array($this, 'productReviewWidget'),
                    'priority' => 50,
                );

                if ($this->shouldHideProductReviews()) {
                    unset($tabs['reviews']);
                }
            }

            if (!get_option('REVIEWSio_hide_legacy') && in_array(get_option('REVIEWSio_question_answers_widget'), array('tab', 'both'))) {
                $tabs['qanda'] = array(
                    'title'    => 'Questions & Answers',
                    'callback' => array($this, 'questionAnswersWidget'),
                    'priority' => 60,
                );
            }

            if ((get_option('REVIEWSio_polaris_review_widget') != "tab") && (get_option('REVIEWSio_product_review_widget') != "tab")) {
                unset($tabs['reviews']);
            }

            return $tabs;
        }

        private function shouldHideProductReviews()
        {
            $post = get_post(get_the_ID());
            return get_option('REVIEWSio_disable_reviews_per_product') == '1' && $post->comment_status == 'closed';
        }

        public function productPage()
        {
          if (in_array(get_option('REVIEWSio_polaris_review_widget'), array('summary', '1', 'bottom'))) {
              if (!$this->shouldHideProductReviews()) {
                  $this->polarisReviewWidget();
              }
            } else if (in_array(get_option('REVIEWSio_product_review_widget'), array('summary', '1', 'both'))) {
                if (!$this->shouldHideProductReviews()) {
                    $this->productReviewWidget();
                }
            }

            if (!get_option('REVIEWSio_hide_legacy') && in_array(get_option('REVIEWSio_question_answers_widget'), array('summary', '1', 'both'))) {
                $this->questionAnswersWidget();
            }
        }

        public function getHexColor()
        {
            $colour = get_option('REVIEWSio_widget_hex_colour');
            if (strpos($colour, '#') === false) {
                $colour = '#' . $colour;
            }

            if (!preg_match('/^#[a-f0-9]{6}$/i', $colour)) {
                $colour = '#f47e27';
            }

            return $colour;
        }

        /*
         * Remove Newlines and Escape Quotes in Custom Widget CSS
         */
        protected function prepareCss($css)
        {
            $css = str_replace("\n", '', $css);
            $css = str_replace("\r", '', $css);
            $css = str_replace('"', '\"', $css);
            return $css;
        }

        /**
         * Product Review Widget
         * Rendered
         */
        public function productReviewWidget($skus = null)
        {
            $this->numWidgets++;
            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '') {
                ?>
                    <?php add_action('wp_footer', array($this, 'reviewsio_product_review_scripts')); ?>
                    <div id="widget-<?php echo $this->numWidgets; ?>"></div>
                <?php
        } else {
                echo 'Missing REVIEWS.io API Credentials';
            }
        }

        public function polarisReviewWidget($skus = null)
        {
          $this->numWidgets++;
          if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '') {
              ?>
                  <?php add_action('wp_footer', array($this, 'reviewsio_polaris_review_scripts')); ?>
                  <?php
                    $skus = $this->getProductSkus();
                    $color = $this->getHexColor();
                  ?>

                  <script>

                    var REVIEWSio_options = {
                        //Your REVIEWS.io account ID and widget type:
                        store: '<?php echo get_option('REVIEWSio_store_id') ?>',
                        widget: 'polaris',
                        
                            <?php if (empty(get_option('REVIEWSio_polaris_custom_styles'))) { ?>
                                /* Widget Settings */
                                options: {
                                    types: 'product_review<?php echo (get_option('REVIEWSio_polaris_review_widget_questions') ? ', questions' : '') ?>',
                                    lang: '<?php echo (get_option('REVIEWSio_polaris_lang') ? get_option('REVIEWSio_polaris_lang') : 'en') ?>',
                                    //Possible layout options: bordered, large and reverse.
                                    layout: '',
                                    //How many reviews & questions to show per page?
                                    per_page: <?php echo !empty(get_option('REVIEWSio_per_page_review_widget')) && is_int((int)get_option('REVIEWSio_per_page_review_widget')) ? get_option('REVIEWSio_per_page_review_widget') : 8 ?>,
                                    //Product specific settings. Provide product SKU for which reviews should be displayed:
                                    product_review:{
                                        //Display product reviews - include multiple product SKUs seperated by Semi-Colons (Main Indentifer in your product catalog )
                                        sku: '<?php echo implode(';', $skus) ?>',
                                        min_rating: '<?php echo  (get_option('REVIEWSio_minimum_rating') ? get_option('REVIEWSio_minimum_rating') : 1) ?>',
                                        hide_if_no_results: false,
                                        enable_rich_snippets: false,
                                    },
                                    //Questions settings:
                                    questions:{
                                        hide_if_no_results: false,
                                        enable_ask_question: true,
                                        show_dates: true,
                                        //Display group questions by providing a grouping variable, new questions will be assigned to this group.
                                        grouping: '<?php echo implode(';', $skus) ?>',
                                    },
                                    <?php if (!empty(get_option('REVIEWSio_widget_custom_header_config'))) {
                                        echo get_option('REVIEWSio_widget_custom_header_config');
                                    } else { ?>
                                        //Header settings:
                                        header:{
                                            enable_summary: true, //Show overall rating & review count
                                            enable_ratings: true,
                                            enable_attributes: true,
                                            enable_image_gallery: true, //Show photo & video gallery
                                            enable_percent_recommended: false, //Show what percentage of reviewers recommend it
                                            enable_write_review: "<?php echo (get_option('REVIEWSio_hide_write_review_button') == '1' ? false : true ) ?>",
                                            enable_ask_question: true,
                                            enable_sub_header: true, //Show subheader
                                        },
                                    <?php
                                    }

                                    if (!empty(get_option('REVIEWSio_widget_custom_filtering_config'))) {
                                        echo get_option('REVIEWSio_widget_custom_filtering_config');
                                    } else { ?>
                                        //Filtering settings:
                                        filtering:{
                                            enable: true, //Show filtering options
                                            enable_text_search: true, //Show search field
                                            enable_sorting: true, //Show sorting options (most recent, most popular)
                                            enable_overall_rating_filter: true, //Show overall rating breakdown filter
                                            enable_ratings_filters: true, //Show product attributes filter
                                            enable_attributes_filters: true, //Show author attributes filter
                                        },
                                    <?php
                                    }

                                    if (!empty(get_option('REVIEWSio_widget_custom_reviews_config'))) {
                                        echo get_option('REVIEWSio_widget_custom_reviews_config');
                                    } else { 
                                    ?>
                                        //Review settings:
                                        reviews:{
                                            enable_avatar: true, //Show author avatar
                                            enable_reviewer_name:  true, //Show author name
                                            enable_reviewer_address:  true, //Show author location
                                            reviewer_address_format: 'city, country', //Author location display format
                                            enable_verified_badge: true,
                                            enable_reviewer_recommends: true,
                                            enable_attributes: true, //Show author attributes
                                            enable_product_name: true, //Show display product name
                                            enable_images: true, //Show display review photos
                                            enable_ratings: true, //Show product attributes (additional ratings)
                                            enable_share: true, //Show share buttons
                                            enable_helpful_vote: true,
                                            enable_helpful_display: true, //Show how many times times review upvoted
                                            enable_report: true, //Show report button
                                            enable_date: true, //Show when review was published
                                        },
                                    <?php 
                                    } 
                                    ?>
                                },
                                //Style settings:
                                <?php if (!empty(get_option('REVIEWSio_custom_reviews_widget_styles'))) {
                                    echo get_option('REVIEWSio_custom_reviews_widget_styles');
                                } else {
                                ?>
                                    styles: {
                                        //Base font size is a reference size for all text elements. When base value gets changed, all TextHeading and TexBody elements get proportionally adjusted.
                                        '--base-font-size': '16px',

                                        //Button styles (shared between buttons):
                                        '--common-button-font-family': 'inherit',
                                        '--common-button-font-size':'16px',
                                        '--common-button-font-weight':'500',
                                        '--common-button-letter-spacing':'0',
                                        '--common-button-text-transform':'none',
                                        '--common-button-vertical-padding':'10px',
                                        '--common-button-horizontal-padding':'20px',
                                        '--common-button-border-width':'2px',
                                        '--common-button-border-radius':'0px',

                                        //Primary button styles:
                                        '--primary-button-bg-color': '#0E1311',
                                        '--primary-button-border-color': '#0E1311',
                                        '--primary-button-text-color': '#ffffff',

                                        //Secondary button styles:
                                        '--secondary-button-bg-color': 'transparent',
                                        '--secondary-button-border-color': '#0E1311',
                                        '--secondary-button-text-color': '#0E1311',

                                        //Star styles:
                                        '--common-star-color': '<?php echo $color ?>',
                                        '--common-star-disabled-color': 'rgba(0,0,0,0.25)',
                                        '--medium-star-size': '22px',
                                        '--small-star-size': '19px',

                                        //Heading styles:
                                        '--heading-text-color': '#0E1311',
                                        '--heading-text-font-weight': '600',
                                        '--heading-text-font-family': 'inherit',
                                        '--heading-text-line-height': '1.4',
                                        '--heading-text-letter-spacing': '0',
                                        '--heading-text-transform': 'none',

                                        //Body text styles:
                                        '--body-text-color': '#0E1311',
                                        '--body-text-font-weight': '400',
                                        '--body-text-font-family': 'inherit',
                                        '--body-text-line-height': '1.4',
                                        '--body-text-letter-spacing': '0',
                                        '--body-text-transform': 'none',

                                        //Input field styles:
                                        '--inputfield-text-font-family': 'inherit',
                                        '--input-text-font-size': '14px',
                                        '--inputfield-text-font-weight': '400',
                                        '--inputfield-text-color': '#0E1311',
                                        '--inputfield-border-color': 'rgba(0,0,0,0.2)',
                                        '--inputfield-background-color': 'transparent',
                                        '--inputfield-border-width': '1px',
                                        '--inputfield-border-radius': '0px',

                                        '--common-border-color': 'rgba(0,0,0,0.15)',
                                        '--common-border-width': '1px',
                                        '--common-sidebar-width': '190px',

                                        //Slider indicator (for attributes) styles:
                                        '--slider-indicator-bg-color': 'rgba(0,0,0,0.1)',
                                        '--slider-indicator-button-color': '#0E1311',
                                        '--slider-indicator-width': '190px',

                                        //Badge styles:
                                        '--badge-icon-color': '#0E1311',
                                        '--badge-icon-font-size': 'inherit',
                                        '--badge-text-color': '#0E1311',
                                        '--badge-text-font-size': 'inherit',
                                        '--badge-text-letter-spacing': 'inherit',
                                        '--badge-text-transform': 'inherit',

                                        //Author styles:
                                        '--author-font-size': 'inherit',
                                        '--author-text-transform': 'none',

                                        //Author avatar styles:
                                        '--avatar-thumbnail-size': '60px',
                                        '--avatar-thumbnail-border-radius': '100px',
                                        '--avatar-thumbnail-text-color': '#0E1311',
                                        '--avatar-thumbnail-bg-color': 'rgba(0,0,0,0.1)',

                                        //Product photo or review photo styles:
                                        '--photo-video-thumbnail-size': '80px',
                                        '--photo-video-thumbnail-border-radius': '0px',

                                        //Media (photo & video) slider styles:
                                        '--mediaslider-scroll-button-icon-color': '#0E1311',
                                        '--mediaslider-scroll-button-bg-color': 'rgba(255, 255, 255, 0.85)',
                                        '--mediaslider-overlay-text-color': '#ffffff',
                                        '--mediaslider-overlay-bg-color': 'rgba(0, 0, 0, 0.8))',
                                        '--mediaslider-item-size': '110px',

                                        //Pagination & tabs styles (normal):
                                        '--pagination-tab-text-color': '#0E1311',
                                        '--pagination-tab-text-transform': 'none',
                                        '--pagination-tab-text-letter-spacing': '0',
                                        '--pagination-tab-text-font-size': '16px',
                                        '--pagination-tab-text-font-weight': '600',

                                        //Pagination & tabs styles (active):
                                        '--pagination-tab-active-text-color': '#0E1311',
                                        '--pagination-tab-active-text-font-weight': '600',
                                        '--pagination-tab-active-border-color': '#0E1311',
                                        '--pagination-tab-border-width': '3px',
                                    },
                                <?php 
                                }
                            } else {
                                echo get_option('REVIEWSio_polaris_custom_styles');
                            } ?>
                    };

                    var REVIEWSio_sku = '<?php echo implode(';', $skus) ?>';
                    REVIEWSio_options.options.product_review.sku = REVIEWSio_sku;
                    REVIEWSio_options.options.questions.grouping = REVIEWSio_sku;


                    window.addEventListener('load', function() {
                      new ReviewsWidget(('#widget-<?php echo $this->numWidgets ?>'), REVIEWSio_options);
                    });
                  </script>
                   <div id="widget-<?php echo $this->numWidgets; ?>"></div>
              <?php
        } else {
                echo 'Missing REVIEWS.io API Credentials';
            }
        }


        public function questionAnswersWidget()
        {
            if (get_option('REVIEWSio_api_key') != '' && get_option('REVIEWSio_store_id') != '') {

                ?>
                    <div id="questions-widget" style="width:100%;"></div>
                    <?php add_action('wp_footer', array($this, 'reviewsio_qa_scripts')); ?>
                <?php
        } else {
                echo 'Missing REVIEWS.io API Credentials';
            }
        }

        public function init()
        {
            add_action('woocommerce_order_status_completed', array($this, 'processCompletedOrder'));
            add_filter('template_redirect', array($this, 'redirect_hook'));
            add_filter('woocommerce_product_tabs', array($this, 'product_review_tab'));

            if(get_option('REVIEWSio_polaris_review_widget') == 'bottom') {
              add_filter('woocommerce_after_single_product', array($this, 'productPage'));
            } else {
              add_filter('woocommerce_after_single_product_summary', array($this, 'productPage'));
            }

            add_action('woocommerce_single_product_summary', array($this, 'product_rating_snippet_markup'), 5);
            add_action('woocommerce_after_shop_loop_item', array($this, 'product_rating_snippet_markup'), 5);
            add_shortcode('rating_snippet', array($this, 'product_rating_snippet_shortcode'));
            add_shortcode('richsnippet', array($this, 'richsnippet_widget'));

            if(get_option('REVIEWSio_enable_product_rating_snippet')) {
                add_action('wp_footer', array($this, 'reviewsio_rating_snippet_scripts'));
            }

            if(get_option('REVIEWSio_enable_floating_widget')) {
                add_action('wp_footer', array($this, 'reviewsio_floating_widget_snippet_scripts'));
            }

            if (get_option('REVIEWSio_enable_product_rich_snippet_server_side')) {
                add_action('wp_head', array($this, 'reviewsio_rich_snippet_scripts'));

            } else {
                add_action('wp_footer', array($this, 'reviewsio_rich_snippet_scripts'));
            }

            if (function_exists('WC')) {
                $enabled  = get_option('REVIEWSio_enable_rich_snippet') || get_option('REVIEWSio_enable_product_rich_snippet');
                if ($enabled) {
                    // Remove existing structured data
                    remove_action('wp_footer', array(WC()->structured_data, 'output_structured_data'), 10);
                }
            }

            if (get_option('REVIEWSio_enable_nuggets_widget')) {
                add_filter('woocommerce_single_product_summary', array($this, 'reviewsio_nuggets_widget_scripts'));
            }
            add_shortcode('nuggets_widget', array($this, 'nuggets_widget_shortcode'));
            
            // if (get_option('REVIEWSio_enable_nuggets_bar_widget')) {
            //     add_filter('wp_footer', array($this, 'reviewsio_nuggets_bar_widget_scripts'));
            // }
            add_shortcode('nuggets_bar_widget', array($this, 'nuggets_bar_widget_shortcode'));


            if(get_option('REVIEWSio_enable_floating_react_widget')) {
                add_action('wp_footer', array($this, 'reviewsio_floating_react_widget_scripts'));
            }

            add_shortcode('ugc_widget', array($this, 'ugc_widget_shortcode'));

            add_shortcode('rating_bar_widget', array($this, 'rating_bar_widget_shortcode'));

            add_shortcode('carousel_widget', array($this, 'carousel_widget_shortcode'));
            
            if (get_option('REVIEWSio_enable_survey_widget')) {
                add_filter('wp_footer', array($this, 'reviewsio_survey_widget_scripts'));
            }
            add_shortcode('survey_widget', array($this, 'survey_widget_shortcode'));

            if (isset($_GET["page"]) && trim($_GET["page"]) == 'reviewscouk') {
                add_action('admin_enqueue_scripts', 'reviewsio_admin_scripts');
            }
        }

        public function add_richsnippet_shortcode_scripts() {
            wp_register_script('richsnippet-shortcode-script',false, array(),false, false);
            wp_enqueue_script('richsnippet-shortcode-script');
            wp_add_inline_script('richsnippet-shortcode-script',"
                jQuery.get('".$this->richsnippet_shortcode_url."', function(r){
                    jQuery('#snippetWidget').html(r);
                });
            ");
        }

        public function richsnippet_widget($opts = [], $content = '')
        {

            ob_start();

            $jq = isset($opts['jq']) ? $opts['jq'] : true;

            if (!isset($opts['primary'])) {
                $opts['primary'] = '1bd172';
            }

            if (!isset($opts['text'])) {
                $opts['text'] = '565656';
            }

            if (!isset($opts['bg'])) {
                $opts['bg'] = 'f9f9f9';
            }

            if (!isset($opts['height'])) {
                $opts['height'] = '600';
            }

            if (!isset($opts['head'])) {
                $opts['head'] = 'ffffff';
            }

            if (!isset($opts['footer'])) {
                $opts['footer'] = 1;
            }

            if (!isset($opts['dates'])) {
                $opts['dates'] = 1;
            }

            if (!isset($opts['names'])) {
                $opts['names'] = 1;
            }

            if (!isset($opts['numreviews'])) {
                $opts['numreviews'] = 21;
            }

            if (!isset($opts['header'])) {
                $opts['header'] = '';
            }

            if (!isset($opts['headingsize'])) {
                $opts['headingsize'] = 20;
            }

            $storeid = get_option('REVIEWSio_store_id');

            $this->richsnippet_shortcode_url = $this->getWidgetDomain() . 'rich-snippet-reviews/widget?store=' . $storeid . '&primaryClr=%23' . $opts['primary'] . '&textClr=%23' . $opts['text'] . '&bgClr=%23' . $opts['bg'] . '&height=' . $opts['height'] . '&headClr=%23' . $opts['head'] . '&header=' . $opts['header'] . '&headingSize=' . $opts['headingsize'] . 'px&numReviews=' . $opts['numreviews'] . '&names=' . $opts['names'] . '&dates=' . $opts['dates'] . '&footer=' . $opts['footer'];


            if (isset($opts['tag'])) {
                $this->richsnippet_shortcode_url .= '&tag=' . $opts['tag'];
            }
            ?>

        <div id='snippetWidget'></div>
        <?php
            add_action('wp_footer', array($this, 'add_richsnippet_shortcode_scripts'));
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
    }
}

if (class_exists('WooCommerce_Reviews')) {
    $woocommercereviews = new WooCommerce_Reviews();

    // Add a link to the settings page onto the plugin page
    if (isset($woocommercereviews)) {
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
