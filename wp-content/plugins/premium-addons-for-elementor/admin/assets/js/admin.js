(function ($) {

	"use strict";

	var redHadfontLink = document.createElement('link');
	redHadfontLink.rel = 'stylesheet';
	redHadfontLink.href = 'https://fonts.googleapis.com/css?family=Red Hat Display:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
	redHadfontLink.type = 'text/css';
	document.head.appendChild(redHadfontLink);

	var poppinsfontLink = document.createElement('link');
	poppinsfontLink.rel = 'stylesheet';
	poppinsfontLink.href = 'https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
	poppinsfontLink.type = 'text/css';
	document.head.appendChild(poppinsfontLink);

	var pluaJakartaFontLInk = document.createElement('link');
	pluaJakartaFontLInk.rel = 'stylesheet';
	pluaJakartaFontLInk.href = 'https://fonts.googleapis.com/css?family=Plus Jakarta Sans:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
	pluaJakartaFontLInk.type = 'text/css';
	document.head.appendChild(pluaJakartaFontLInk);

	var settings = premiumAddonsSettings.settings;


	window.PremiumAddonsNavigation = function () {

		var self = this,
			$tabs = $(".pa-settings-tab"),
			$elementsTabs = $(".pa-elements-tab");

		var urlString = window.location.href,
			url = new URL(urlString);

		self.init = function () {

			if (!$tabs.length) {
				return;
			}

			self.genButtonDisplay();

			self.initNavTabs($tabs);

			self.initElementsTabs($elementsTabs);

			self.getUnusedWidget();

			self.handleElementsActions();

			self.handleSearchField();

			self.handleSettingsSave();

			self.handleRollBack();

			self.handleNewsLetterForm();

			self.handlePaproActions();

			self.handleWhiteLabelingAction()

		};

		// Handle settings form submission
		self.handleSettingsSave = function () {

			$("#pa-features .pa-section-info-cta input, #pa-modules .pa-switcher input, #pa-modules .pa-section-info-cta input").on(
				'change',
				function () {

					if ('mini-cart' === $(this).attr('id')) {
						if ($(this).prop('checked')) {

							$("#pa_mc_temp").prop("checked", true);
							self.saveElementsSettings('elements', 'default', true);
						}
					} else {
						self.saveElementsSettings('elements', 'default');
					}
				}
			)

			$("#pa-ver-control input, #pa-integrations input, #pa-ver-control input, #pa-integrations select").change(
				function () {
					self.saveElementsSettings('additional', 'default');
				}
			);

			$("#pa-integrations input[type=text]").on(
				'keyup',
				function () {
					self.saveElementsSettings('additional', 'default');
				}
			)

		};

		//get unused widgets.
		self.getUnusedWidget = function () {

			$.ajax(
				{
					url: settings.ajaxurl,
					type: 'POST',
					data: {
						action: 'pa_get_unused_widgets',
						security: settings.unused_nonce,
					},
					beforeSend: function () {
						$(".pa-btn-unused i").addClass("loading");
					},
					success: function (response) {
						console.log('unused widgets retrieved');

						self.unusedElements = response.data;

						$(".pa-btn-unused").removeClass("dimmed pa-fade").find("i").remove();

					},
					error: function (err) {
						console.log(err);
					}
				}
			);
		};

		self.disableElementorCustomTemplate = function () {
			$.ajax({
				url: settings.ajaxurl,
				type: "POST",
				data: {
					action: "pa_disable_elementor_mc_template",
					security: settings.nonce,
				},
				success: function (response) {
					console.log(response.data);
				},
				error: function (err) {
					console.log(err);
				},
			});
		}

		// Handle global enable/disable buttons
		self.handleElementsActions = function () {

			$(".pa-typed-search select").on(
				'change',
				function () {

					var filter = $(this).val(),
						$activeTab = $(".pa-switchers-container").not(".hidden");

					$activeTab.find(".pa-switcher").removeClass("hidden");

					if ('free' === filter) {
						$activeTab.find(".pro-element").addClass("hidden");
					} else if ('pro' === filter) {
						$activeTab.find(".pa-switcher").not(".pro-element").addClass("hidden");
					}
				}
			);

			$(".pa-elements-filter input").on(
				'keyup',
				function () {
					var filter = $(this).val(),
						$activeTab = $(".pa-switchers-container").not(".hidden"),
						currentQuerySwitchers = $activeTab.find(".pa-switcher");

					currentQuerySwitchers.addClass("hidden");
					var searchResults = currentQuerySwitchers.filter(function (index, switcher) {
						var elementName = $(switcher).find(".pa-element-name").text().toLowerCase();

						return -1 != elementName.indexOf(filter.toLowerCase()) ? $(switcher) : '';
					});

					searchResults.removeClass("hidden");
				}
			);

			// Enable/Disable all widgets
			$(".pa-btn-group").on(
				"click",
				'.pa-btn',
				function () {

					var $btn = $(this),
						isChecked = $btn.hasClass("pa-btn-enable"),
						customTemp = false;

					//If button is not already activated.
					if (!$btn.hasClass("active")) {
						$(".pa-btn-group .pa-btn").removeClass("active");
						$btn.addClass("active");

						$.ajax(
							{
								url: settings.ajaxurl,
								type: 'POST',
								data: {
									action: 'pa_save_global_btn',
									security: settings.nonce,
									isGlobalOn: isChecked
								}
							}
						);

					}

					//If it's enable all button.
					if (isChecked) {
						$(".pa-btn-group .pa-btn-unused").removeClass("dimmed");
						customTemp = true;
					} else {
						$(".pa-btn-group .pa-btn-unused").addClass("dimmed");
					}

					$("#pa-modules .pa-switcher input").prop("checked", isChecked);

					self.saveElementsSettings('elements', 'default', customTemp);

				}
			);

			//Disable unused widgets.
			$(".pa-btn-group").on(
				"click",
				'.pa-btn-unused:not(.dimmed)',
				function () {

					$.each(self.unusedElements, function (index, selector) {
						$('#pa-modules .pa-switcher.' + selector).find('input').prop('checked', false);
					});

					self.saveElementsSettings('elements', 'default');
				}
			);

			$("#pa-modules .pa-switcher input").on(
				'change',
				function () {
					var $this = $(this),
						id = $this.attr('id'),
						isChecked = $this.prop('checked');

					$("input[name='" + id + "']").prop('checked', isChecked);
				}
			)

			// Clear regenerated assets.
			$(".pa-section-info-cta").on(
				"click",
				'.pa-btn-regenerate',
				function () {

					var _this = $(this);
					_this.addClass("loading");

					$.ajax(
						{
							url: settings.ajaxurl,
							type: 'POST',
							data: {
								action: 'pa_clear_cached_assets',
								security: settings.generate_nonce,
							},
							success: function (response) {

								swal.fire({
									title: 'Generated Assets Cleared!',
									text: 'Click OK to continue',
									type: 'success',
									timer: 1500
								});

								_this.removeClass("loading");

							},
						}
					);
				}
			);

			// Clear saved site cursor settings.
			$('.pa-btn-clear-cursor').on('click', function () {
				var _this = $(this);
				_this.addClass("loading");

				$.ajax(
					{
						url: settings.ajaxurl,
						type: 'POST',
						data: {
							action: 'pa_clear_site_cursor_settings',
							security: settings.site_cursor_nonce,
						},
						success: function (response) {

							swal.fire({
								title: 'Site Cursor Cleared!',
								text: 'Click OK to continue',
								type: 'success',
								timer: 1500
							});

							_this.removeClass("loading");
							console.log(response)
						},
					}
				);
			});
		};

		self.handleSearchField = function () {

			var searchInput = url.searchParams.get("search");

			if (!searchInput)
				return;

			$(".pa-elements-filter input").val(searchInput).trigger("keyup");


		}

		// Handle Tabs Elements
		self.initElementsTabs = function ($elem) {

			var $links = $elem.find('a'),
				$sections = $(".pa-switchers-container");

			$sections.eq(0).removeClass("hidden");
			$links.eq(0).addClass("active");

			$links.on(
				'click',
				function (e) {

					e.preventDefault();

					var $link = $(this),
						href = $link.attr('href');

					// Set this tab to active
					$links.removeClass("active");
					$link.addClass("active");

					// Navigate to tab section
					$sections.addClass("hidden");
					$("#" + href).removeClass("hidden");

				}
			);
		};

		// Handle settings tabs
		self.initNavTabs = function ($elem) {

			var $links = $elem.find('a'),
				$lastSection = null;

			$(window).on(
				'hashchange',
				function () {

					var hash = window.location.hash.match(new RegExp('tab=([^&]*)')),
						slug = hash ? hash[1] : $links.first().attr('href').replace('#tab=', ''),
						$link = $('#pa-tab-link-' + slug);

					if (!$link.length) {
						return

					}
					$links.removeClass('pa-section-active');
					$link.addClass('pa-section-active');

					// Hide the last active section
					if ($lastSection) {
						$lastSection.hide();
					}

					var $section = $('#pa-section-' + slug);
					$section.css(
						{
							display: 'block'
						}
					);

					$lastSection = $section;

				}
			).trigger('hashchange');

		};

		self.handleRollBack = function () {

			// Rollback button
			$('.pa-rollback-button').on(
				'click',
				function (event) {

					event.preventDefault();

					var $this = $(this),
						href = $this.attr('href');

					if (!href) {
						return;
					}

					// Show PAPRO stable version if PAPRO Rollback is clicked
					var isPAPRO = '';
					if (-1 !== href.indexOf('papro_rollback')) {
						isPAPRO = 'papro_';
					}

					var premiumRollBackConfirm = premiumAddonsSettings.premiumRollBackConfirm;

					var dialogsManager = new DialogsManager.Instance();

					dialogsManager.createWidget(
						'confirm',
						{
							headerMessage: premiumRollBackConfirm.i18n.rollback_to_previous_version,
							message: premiumRollBackConfirm['i18n'][isPAPRO + 'rollback_confirm'],
							strings: {
								cancel: premiumRollBackConfirm.i18n.cancel,
								confirm: premiumRollBackConfirm.i18n.yes,
							},
							onConfirm: function () {

								$this.addClass('loading');

								location.href = $this.attr('href');

							}
						}
					).show();
				}
			);

		};

		/**
		 * Save elements settings.
		 * @param {String} action request action param.
		 * @param {String} source elements source, wizard|default (dashboard).
		 * @param {Boolean} updateCustomTemplate true if we need to update the Mini Cart custom template option.
		 * @param {String|null} redirectURL wizard redirection URL.
		 */
		self.saveElementsSettings = function (action, source, updateCustomTemplate = false, redirectURL) {

			var $form = null,
				defaultAddons = '';


			if (source === 'wizard') {
				if (settings.isSecondRun && Array.isArray(settings.savedFeatures)) {
					settings.savedFeatures.forEach(function (feature) {
						defaultAddons += `&${feature}=on`;
					});
				} else {
					defaultAddons = '&premium-assets-generator=on&premium-templates=on&premium-equal-height=on&premium-wrapper-link=on&pa-display-conditions=on&premium-duplicator';
				}
			}


			// We don't need to check the source as it'll always be 'wizard or default', so the 2nd part of the condition is always true.
			if (updateCustomTemplate) {
				defaultAddons += '&pa_mc_temp=on';
				self.disableElementorCustomTemplate();
			}

			if ('elements' === action) {
				$form = $('form#pa-settings, form#pa-features, form#pa-wz-settings');
				action = 'pa_elements_settings';
			} else {
				$form = $('form#pa-ver-control, form#pa-integrations');
				action = 'pa_additional_settings';
			}

			$.ajax(
				{
					url: settings.ajaxurl,
					type: 'POST',
					data: {
						action: action,
						security: settings.nonce,
						fields: $form.serialize() + defaultAddons,
					},
					success: function () {
						console.log('settings saved');

						self.genButtonDisplay();
					},
					error: function (err) {
						console.log(err);
					},
					complete: function () {
						if (redirectURL) {
							$(location).attr('href', redirectURL);
						}
					}

				}
			);
		}

		self.genButtonDisplay = function () {
			var $form = $('form#pa-settings'),
				searchTerm = 'premium-assets-generator=on',
				indexOfFirst = $form.serialize().indexOf(searchTerm);

			if (indexOfFirst !== -1) {
				$('.pa-btn-generate').show();
			} else {
				$('.pa-btn-generate').hide();
			}
		};

		self.handlePaproActions = function () {

			$(".pro-slider").on(
				'click',
				function () {
					var isFeature = 'feature' === $(this).prev().attr('pa-element'),
						elementName = $(this).prev().attr('name').replace('premium-', '');

					var colorArr = ['#FF7800', '#6C9800', '#00BCF1', '#F7C230', '#006CE7'],
						redirectionLink = " https://premiumaddons.com/pro/?utm_source=" + elementName + "&utm_medium=wp-dash-pro&utm_campaign=get-pro&utm_term=" + settings.theme + '#get-pa-pro',
						iconClass = $(this).parent().prev().find('.pa-element-icon').attr('class'),
						iconColor = colorArr[Math.floor(Math.random() * colorArr.length)],
						demoLink = isFeature ? $(this).parents('.pa-section-outer-wrap').find('> a').attr('href') : $(this).parents('.pa-switcher').find('.pa-demo-link').attr('href'),
						eleTitle = isFeature ? $(this).parents('.pa-section-info-wrap').find('.pa-section-info > h4').text() : $(this).prev().attr('title') + ' Widget';

					// update icon.
					if (isFeature) {
						$('#pa-dash-pro-popup-cta').addClass('pa-feature-element');;

					} else {
						$('#pa-dash-pro-popup-cta').removeClass('pa-feature-element');
						$('#pa-dash-pro-popup-cta .pa-popup-widget-icon i').attr('class', iconClass).css('color', iconColor);
					}

					// update widget name.
					$('#pa-dash-pro-popup-cta .primary-des .pa-widget-name').text(eleTitle);

					// update CTA links.
					$('#pa-dash-pro-popup-cta .pa-popup-cta:first-child').attr('href', demoLink);
					$('#pa-dash-pro-popup-cta .pa-popup-cta:last-child').attr('href', redirectionLink);

					$('#pa-dash-pro-popup-cta').show().find('.popup-body').css('animation-name', 'swal2-show');
				}
			);

			$('.pa-popup-close').on('click', function () { self.closeProPopup(); });

			//Close popup when escape keyboard button is tapped.
			jQuery(document).on('keydown', function (e) {
				if (e.key === "Escape" || e.keyCode === 27) {
					self.closeProPopup();
				}
			});

			$(document).on('click', '#pa-dash-pro-popup-cta', function (e) {
				if ($(e.target).closest(".popup-body").length < 1) { self.closeProPopup(); }
			});

		};

		self.closeProPopup = function () {
			$('#pa-dash-pro-popup-cta .popup-body').css('animation-name', 'swal2-hide');

			setTimeout(() => {
				$('#pa-dash-pro-popup-cta').hide();
			}, 302);
		};

		self.handleWhiteLabelingAction = function () {
			// Trigger SWAL for White Labeling
			$(".premium-white-label-form.pro-inactive").on(
				'submit',
				function (e) {

					e.preventDefault();

					var redirectionLink = " https://premiumaddons.com/pro/?utm_source=wp-menu&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=";

					Swal.fire(
						{
							title: '<span class="pa-swal-head">Enable White Labeling Options<span>',
							html: 'Premium Addons can be completely re-branded with your own brand name and author details. Your clients will never know what tools you are using to build their website and will think that this is your own tool set. White-labeling works as long as your license is active.',
							type: 'warning',
							showCloseButton: true,
							showCancelButton: true,
							cancelButtonText: "More Info",
							focusConfirm: true
						}
					).then(
						function (res) {
							// Handle More Info button
							if (res.dismiss === 'cancel') {
								window.open(redirectionLink + settings.theme, '_blank');
							}

						}
					);
				}
			);
		};

		self.handleNewsLetterForm = function () {

			$('.pa-newsletter-form').on('submit', function (e) {
				e.preventDefault();

				var email = $("#pa_news_email").val(),
					_this = this,
					isWizardForm = $(this).hasClass('pa-wizard-form');

				if (checkEmail(email)) {
					$.ajax(
						{
							url: settings.ajaxurl,
							type: 'POST',
							data: {
								action: 'subscribe_newsletter',
								security: settings.nonce,
								email: email
							},
							beforeSend: function () {
								console.log("Adding user to subscribers list");

								if (isWizardForm) {

									$(_this).find('.pa-wz-msg').remove();

									$(_this).animate({
										opacity: '0.45'
									}, 500);

									$(_this).find('.pa-btn').attr('disabled', 'disabled').find('.pa-wz-news-svg').hide();
									$(_this).find('.pa-btn .pa-wz-spinner').show();
								}
							},
							success: function (response) {
								if (response.data) {
									var status = response.data.status;

									if (status) {
										console.log("User added to subscribers list");

										if (isWizardForm) {
											$(_this).append('<span class="pa-wz-success pa-wz-msg">' + settings.i18n.successMsg + '</span>');
										} else {
											swal.fire({
												title: 'Thanks for subscribing!',
												text: 'Click OK to continue',
												type: 'success',
												timer: 1000
											});
										}
									}
								}

							},
							error: function (err) {
								console.log(err);

								if (isWizardForm) {
									$(_this).append('<span class="pa-wz-danger pa-wz-msg">' + settings.i18n.failMsg + '</span>');
								}
							},
							complete: function () {

								$(_this).find('.pa-btn').removeAttr('disabled').find('.pa-wz-spinner').hide();
								$(_this).find('.pa-btn .pa-wz-news-svg').show();

								$(_this).animate({
									opacity: '1'
								}, 100);
							}
						}
					);
				} else {
					Swal.fire({
						type: 'error',
						title: 'Invalid Email Address...',
						text: 'Please enter a valid email address!'
					});
				}

			})

		};

		function checkEmail(emailAddress) {
			var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
			return pattern.test(emailAddress);
		}

	};

	var instance = new PremiumAddonsNavigation();

	instance.init();

})(jQuery);
