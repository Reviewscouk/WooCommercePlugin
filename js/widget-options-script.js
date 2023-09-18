document.addEventListener("DOMContentLoaded", function () {
	jQuery(".widget-active-state").change(function (e) {
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
	let elementId = element.attr("id");
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
	let elementId = element.attr("id");
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
}

function getWidgetOptionsList(selectedWidget = "") {
	jQuery.ajax({
		url: `https://api.reviews.io/widget/list-with-key?store=${reviewsio_data.store_id}&widget=nuggets,floating,ugc,survey,rating-bar&selected_widget=${selectedWidget}&url_key=${reviewsio_data.store_id}&${Date.now()}`,
		method: "GET",
		success: function (data) {
			if (data && data.widget_options_list) {
				let dropdown = null;
				let selectedField = null;
				let attrValue = null;

				let optionsCount = [
					{
						name: "nuggets",
						editor: "nuggets",
						count: 0,
					},
					{
						name: "ugc",
						editor: "ugc",
						count: 0,
					},
					{
						name: "rating_bar",
						editor: "rating-bar",
						count: 0,
					},
				];

				jQuery("#nuggets-widget-options-dropdown").find("option").not(":first").remove();
				jQuery("#nuggets_shortcode-widget-options-dropdown").find("option").not(":first").remove();
				jQuery("#floating-react-widget-options-dropdown").find("option").not(":first").remove();
				jQuery("#ugc-widget-options-dropdown").find("option").not(":first").remove();
				jQuery("#survey-widget-options-dropdown").find("option").not(":first").remove();
				jQuery("#rating_bar-widget-options-dropdown").find("option").not(":first").remove();

				jQuery.each(data.widget_options_list, function () {
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

							attrValue = this.widget_id == selectedField ? "selected" : false;
							dropdown.append(
								jQuery("<option />")
									.val(this.widget_id)
									.text(this.name + " " + reviewType)
									.attr("selected", attrValue)
							);
							shortcodeDropdown.append(
								jQuery("<option />")
									.val(this.widget_id)
									.text(this.name + " " + reviewType)
							);
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

					attrValue = this.widget_id == selectedField ? "selected" : false;
					dropdown.append(
						jQuery("<option />").val(this.widget_id).text(this.name).attr("selected", attrValue)
					);
				});

				optionsCount.forEach(function (item) {
					if (item.count == 0) {
						let parent = jQuery(`#${item.name}-widget-options-dropdown`).parent();

						parent
							.parent()
							.parent()
							.siblings()
							.prevAll()
							.eq(1)
							.text(
								"Personalize widget styles to create a more engaging and cohesive design that aligns with your preferences and needs. The styles can be edited in the REVIEWS.io widget editor."
							);

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
				});
			}
		},
	});
}

getWidgetOptionsList();
