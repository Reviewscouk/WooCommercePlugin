document.addEventListener("DOMContentLoaded", function () {
	// Get list tags
	jQuery(".js-tags-list").each(function () {
		const list = jQuery(this).attr("id");
		const tags = jQuery(this).next().children().children().attr("id");

		formatDataFeed(list, tags);
	});

	jQuery(".widget-active-state").each(function () {
		widgetOptionsActiveState(jQuery(this));
	});

	let domain = "";

	jQuery(".js-api-tab").css("display", "block");
	jQuery(".FlexTabs__item").click(function (e) {
		e.preventDefault();
		jQuery(this).addClass("isActive");
		jQuery(this).siblings().removeClass("isActive");

		let tab = jQuery(this).attr("id");
		let tabContent = jQuery("." + tab);

		jQuery(".tab-contents").each(function () {
			const tabContent = jQuery(this);
			tabContent.css("display", "none");

			if (tabContent.hasClass(tab)) {
				tabContent.fadeIn();
			}
		});
	});

	jQuery("#reviewsio-settings").keydown(function (event) {
		event.which === 13 && event.preventDefault();
	});

	// Color picker selector
	let inputs = jQuery(`#reviewsio-settings [id*="color"]`).filter(".colour-picker");
	let colorSelectorIds = getInputIds(inputs);
	for (i = 0; i < colorSelectorIds.length; i++) {
		initEditorColorPickr(colorSelectorIds[i]);
	}

	var triggers = document.querySelectorAll('.reviews-collapse-trigger');

	triggers.forEach(function (trigger) {
		trigger.addEventListener('click', function () {
			var triggerIcon = this.querySelector('.reviews-collapse-icon');

			if (triggerIcon) {
				triggerIcon.classList.toggle('reviews-content-active');
			}

			var ico = trigger.querySelector('.IconButton__icon')
			if (ico.classList.contains('ricon-thin-arrow--up')) {
				ico.classList.remove('ricon-thin-arrow--up');
				ico.classList.add('ricon-thin-arrow--down');
			} else {
				ico.classList.remove('ricon-thin-arrow--down');
				ico.classList.add('ricon-thin-arrow--up');
			}

			var content = this.nextElementSibling;

			if (content && content.classList.contains('reviews-collapse-content')) {
				content.classList.toggle('reviews-content-active');
			}
		});
	});
});

jQuery.ajax({
	url: `https://api.reviews.io/woocommerce/info?store=${reviewsio_data.store_id}&${Date.now()}`,
	method: "GET",
	success: function (res) {
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

			if (value === "reviewscouk" && (regionInput.val() == "" || regionInput.val() != region)) {
				jQuery("#api-notification-heading").text("Please Wait");
				jQuery("#api-notification-text").text("Configuring store domain.");
				jQuery("#api-notification").css("display", "block");
				regionInput.val(region);
				jQuery("#submit").click();
			}

			jQuery(".js-validated-user").css("display", "block");
			jQuery(".js-invalidated-user").css("display", "none");

			if (data.stats.store_total_reviews > 0 || data.stats.product_total_reviews > 0) {
				const stats = data.stats;
				let heading = "Overall Statistics";
				let message = `<p><span style="white-space: nowrap">Average Company Rating: <strong>${stats.store_average_rating}</strong></span> &nbsp;|&nbsp; <span style="white-space: nowrap">Company Reviews: <strong>${stats.store_total_reviews}</strong></strong></span> &nbsp;|&nbsp; <span style="white-space: nowrap"></strong>Average Product Rating: <strong>${stats.product_average_rating}</strong></span> &nbsp;|&nbsp; <span style="white-space: nowrap"></strong>Product Reviews: <strong>${stats.product_total_reviews}</span></p>`;

				jQuery("#welcomeHeading").html(heading);
				jQuery("#welcomeText").html(message);
			}
		}
	},
	error: function (e) {
		jQuery(".FlexTabs__item").addClass("u-pointerEvents--none Button--disabled");
		jQuery(".js-validated-user").css("display", "none");
		jQuery(".js-invalidated-user").css("display", "block");
		jQuery("#api-notification").css("display", "block");
	},
});

jQuery.ajax({
	url: `https://api.reviews.io/widget/survey-campaigns?store=${reviewsio_data.store_id}&${Date.now()}`,
	success: function (data) {
		if (data && data.survey_campaigns) {
			let dropdown = null;
			let selectedField = null;
			let attrValue = null;

			selectedField = jQuery("#survey-widget-campaign").val();
			dropdown = jQuery("#survey-widget-campaign-dropdown");
			dropdown.find("option").remove();

			data.survey_campaigns.forEach(function (item) {
				attrValue = this.widget_id == selectedField ? "selected" : false;
				dropdown.append(jQuery("<option />").val(item.id).text(item.title).attr("selected", attrValue));
			});
		}
	},
	error: function (e) {},
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
	let el = jQuery("#polaris-widget-location");
	let val = el.find(":selected").val();

	if (val == "tab") {
		jQuery(".js-polaris-review-tab-name").css("display", "block");
	} else {
		jQuery(".js-polaris-review-tab-name").css("display", "none");
	}
}

function toggleNotification(list, tags) {
	jQuery(".js-unsaved-notification").css("display", "block");
}

function toggleFeedFeedback(type, newValue) {
	let newFeedAttrInput = jQuery(`#${newValue}`);
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

function removeDataFeed(button, list) {
	let feedAttrInput = jQuery(`#${list}`);
	let feed = feedAttrInput.val().split(", ");
	feed = feed.filter((item) => item !== button);
	let newFeed = feed.join(", ");
	feedAttrInput.val(newFeed);
	toggleNotification();
}

function formatDataFeed(list, tags) {
	// if (!jQuery(`#${list}`).length) return;
	let feedAttrInput = jQuery(`#${list}`);
	let feedListElement = jQuery(`#${tags}`);
	let feed = feedAttrInput.val().split(", ");
	if (!feed || feed[0] === "") return;

	feedListElement.empty();
	feed.forEach(function (item, idx) {
		var listItem = jQuery("<li>", {
			text: item,
			"data-title": item,
		}).appendTo(feedListElement);

		jQuery("<span>", {
			class: "remove-button",
			text: "x",
			"data-title": item,
		}).appendTo(listItem);
	});
}

function addNewAttribute(newValue, list, tags) {
	let newFeedAttrInput = jQuery(`#${newValue}`);
	let feedAttrInput = jQuery(`#${list}`);

	newFeedAttrInput.parent().removeClass("isFailure");
	let feed = feedAttrInput.val().split(", ");

	if (newFeedAttrInput.val() == "") {
		toggleFeedFeedback("empty", newValue);
		return;
	}
	if (feed.includes(newFeedAttrInput.val())) {
		toggleFeedFeedback("exists", newValue);
		return;
	}

	if (feed.length === 1 && feed[0] === "") feed = [];
	feed.push(newFeedAttrInput.val());
	let newFeed = feed.join(", ");

	feedAttrInput.val(newFeed);
	newFeedAttrInput.val("").focus();
	toggleNotification();
	formatDataFeed(list, tags);

	jQuery(`#${tags} li span`).click(function () {
		removeDataFeed(jQuery(this).attr("data-title"), list);
		jQuery(this).parent().remove();
	});
}

jQuery(document).ready(function () {
	jQuery(".tags li span").click(function () {
		let list = jQuery(this).parent().parent().parent().parent().prev().attr('id');
		let listItem = jQuery(this);

		removeDataFeed(listItem.attr("data-title"), list);
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
		el: "#" + id,
		theme: "nano",
		default: jQuery("#input-" + id).val(),
		components: {
			preview: true,
			opacity: true,
			hue: true,
			interaction: {
				hex: true,
				rgba: true,
				input: true,
				save: true,
			},
		},
	});

	editorColorPickers[id].on("save", function (color, instance) {
		let colorString = editorColorPickers[id].getColor().toHEXA().toString();
		document.querySelector("#input-" + id).value = colorString;
		editorColorPickers[id].setColor(colorString, true);
		editorColorPickers[id].hide();
	});
}
