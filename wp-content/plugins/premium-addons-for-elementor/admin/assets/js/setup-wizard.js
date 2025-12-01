(function ($) {

	"use strict";

	window.PremiumAddonsWizardHandler = function () {

		var _this = this;

		_this.siteType = null;
		_this.paNavInstance = new PremiumAddonsNavigation();
		_this.debounce = false;
		_this.isSecondRun = paWizardSettings.isSecondRun;


		_this.init = function () {
			_this.addWizardFonts();
			_this.initEvents();
		};

		_this.addWizardFonts = function () {
			var dmSanFontLink = document.createElement('link');
			dmSanFontLink.rel = 'stylesheet';
			dmSanFontLink.href = 'https://fonts.googleapis.com/css?family=DM Sans:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
			dmSanFontLink.type = 'text/css';
			document.head.appendChild(dmSanFontLink);

			var dmSanDisplayLink = document.createElement('link');
			dmSanDisplayLink.rel = 'stylesheet';
			dmSanDisplayLink.href = 'https://fonts.googleapis.com/css?family=DM Serif Display:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
			dmSanDisplayLink.type = 'text/css';
			document.head.appendChild(dmSanDisplayLink);
		};

		_this.initEvents = function () {
			_this.siteTypeHandler();
			_this.initDefaultType();
			_this.navButtonsHandler();
			_this.subscribeBtnHandler();
			_this.recommendedListHandler();
			_this.wizardCompletionHandler();
			_this.wizardToggleListHandler();
			_this.handleProCTA();
			_this.handleWidgetsNotice();
		};

		_this.handleWidgetsNotice = function () {

			if (!$('.pa-wz-extra-notice-wrapper').length)
				return;

			$("#pa-wz-settings .pa-switcher .switch").on(
				'click',
				function () {
					var id = $(this).find('input').attr('id'),
						isChecked = $(this).find('input').prop('checked');
					$("input[name='" + id + "']").prop('checked', isChecked);

				}
			)

			$('#pa-wz-settings .pa-switcher input').on('change.paShowNotice', function () {
				var enabledCount = $('#pa-wz-settings .pa-wz-listing-outer-wrapper .pa-switcher input:checked').length;

				if (enabledCount >= 25) {
					$('.pa-wz-extra-notice-wrapper').fadeIn();
				} else {
					$('.pa-wz-extra-notice-wrapper').fadeOut();
				}
			});
		};


		_this.handleProCTA = function () {
			_this.paNavInstance.handlePaproActions();
		};

		_this.initDefaultType = function () {
			$('input.pa-wz-option[value="basic"]').click();
		};

		_this.wizardCompletionHandler = function () {

			$('a.finish-btn, .pa-wz-close').on('click.paCompleteSetup', function (e) {
				e.preventDefault();

				if ($(this).hasClass('pa-wz-close')) {
					$(location).attr('href', paWizardSettings.exitWizardURL);
				} else {
					var redirectURL = $(this).hasClass('pa-new-page') ? paWizardSettings.newPageURL : paWizardSettings.dashboardURL,
						customMiniCartTemplate = $('#pa-wz-settings .pa-switcher.premium-mini-cart input').prop('checked');

					_this.paNavInstance.saveElementsSettings('elements', 'wizard', customMiniCartTemplate, redirectURL);
				}
			});
		};

		_this.subscribeBtnHandler = function () {
			_this.paNavInstance.handleNewsLetterForm();
		};

		_this.siteTypeHandler = function () {

			var $siteTypes = $('input.pa-wz-option[name="pa-wz-site-type"]');

			$siteTypes.on('change', function () {

				var siteType = $(this).attr('value');

				_this.siteType = siteType;

				$('#premium-addons-setup-wizard').attr('pa-site-type', siteType);

				$('.pa-wz-option-wrapper').removeClass('pa-step-active');

				$(this).parent().addClass('pa-step-active');

				if (!$('#pa-step-widgets-content .pa-wz-recs').hasClass('toggled')) {

					$('#pa-step-widgets-content .pa-wz-toggler').click();

					setTimeout(() => {
						$('#pa-step-widgets-content .pa-wz-recs').css('height', 'calc( 100px + 6vh)');
					}, 400);
				}

				// enable disable elements in step 2 accordingly if siteType !== custom
				if ('custom' !== siteType) {
					_this.prepareEnabledElements(siteType);
				}
			});

		};

		_this.prepareEnabledElements = function (siteType) {

			var elementsToUse = null;

			$('#pa-wz-settings .pa-switcher').find('input').prop('checked', false);

			elementsToUse = [
				'premium-addon-title',
				'premium-addon-dual-header',
				'premium-addon-blog',
				'premium-addon-maps',
				'premium-carousel-widget',
				'premium-addon-person',
				'premium-img-gallery',
				'premium-addon-video-box',
				'premium-addon-testimonials',
				'premium-addon-button',
				'premium-counter',
				'premium-addon-pricing-table'
			];

			if ('ecommerce' === siteType) {
				elementsToUse.push(
					'premium-woo-products',
					'premium-woo-categories',
					'premium-mini-cart',
					'premium-woo-cta'
				);
			} else if ('blog' === siteType) {
				elementsToUse.push(
					'premium-post-ticker',
					'premium-world-clock',
					'premium-weather',
					'premium-tcloud'
				);
			}

			$.each(elementsToUse, function (index, selector) {
				$('#pa-wz-settings .pa-switcher.' + selector).find('input').prop('checked', true);
			});
		};

		var maxStepReached = 1;
		_this.navButtonsHandler = function () {


			$('#premium-addons-setup-wizard .next-arrow, #premium-addons-setup-wizard .prev-arrow , #premium-addons-setup-wizard  .pa-wz-step').on('click.paWizardNav', function () {
				if (_this.debounce) return;
				_this.debounce = true;

				setTimeout(() => { _this.debounce = false; }, 1000); // same duration of content transition

				var currentStep = Number($('#premium-addons-setup-wizard').attr('pa-current-step')),
				    newStep;

				if ($(this).hasClass('prev-arrow')) {
					newStep = currentStep - 1;

				} else if ($(this).hasClass('next-arrow')) {
					newStep = currentStep + 1;
					maxStepReached = newStep;

					if(currentStep === 2){
						_this.paNavInstance.saveElementsSettings('elements', 'wizard', false);
					}

				} else if ($(this).hasClass('pa-wz-step')) {
					var clickedStep = Number($(this).data('step'));
					if (clickedStep <= maxStepReached) {
						newStep = clickedStep;
					} else {
						return;
					}
				}

				// update current step flag.
				$('#premium-addons-setup-wizard').attr('pa-current-step', newStep);

				// show the PHP notice only in the first screen.
				if (1 === newStep && $('.pa-wz-notice-wrapper').length) {
					$('.pa-divider').addClass('pa-bt-border-dim');
					$('.pa-wz-notice-wrapper').removeClass('pa-hidden-content').addClass('pa-show-content')
				} else {
					$('.pa-divider').removeClass('pa-bt-border-dim');
					$('.pa-wz-notice-wrapper').addClass('pa-hidden-content').removeClass('pa-show-content');
				}

				// hide all content.
				$('.pa-wz-content-wrappers > *').addClass('pa-hidden-content').removeClass('pa-show-content');

				// update nav btns.
				$('#premium-addons-setup-wizard .prev-arrow').attr('pa-step-id', newStep - 1);
				$('#premium-addons-setup-wizard .next-arrow').attr('pa-step-id', newStep + 1);

				var stepKey = $('.pa-wz-step[data-step="' + newStep + '"]').data('step-key');
				$('#pa-step-' + stepKey + '-content').removeClass('pa-hidden-content').addClass('pa-show-content');

				//update the active step.
				$('.pa-wz-step').each(function () {
					var stepIndex = Number($(this).data('step'));
					$(this).toggleClass('pa-step-active', stepIndex <= newStep);
				});

				//update the active progressbar.
				var visualStep = $('.pa-wz-step.pa-step-active').length;
				var totalSteps = Number($('.pa-wz-step').length+1);
				var isLastStep = newStep === totalSteps;

				var progressHeight = isLastStep
					? `calc( ( ${visualStep - 1} ) * 9vh )`
					: `calc( ( ${visualStep - 1} ) * 9vh + 6.75vh )`;

				$('.pa-step-progress').css('height', progressHeight);
			});
		};


		_this.recommendedListHandler = function () {
			// unfold effect.
			var $unfoldContent = $('#pa-step-widgets-content .pa-wz-recs');

			$unfoldContent.css('height', 'calc( 100px + 6vh)');

			$('#pa-step-widgets-content .pa-wz-toggler').on('click.paWizardToggler', function (e) {
				var _this = this;
				e.preventDefault();

				if ($unfoldContent.hasClass("toggled")) {

					$unfoldContent.css("overflow", "visible");

					$unfoldContent.animate({ height: $($unfoldContent)[0].scrollHeight + 'px' }, function () {
						$(_this).text('See Less');
					}).removeClass("toggled");

				} else {

					$unfoldContent.css("overflow", "hidden");
					$unfoldContent.animate({ height: $('#pa-step-widgets-content .pa-wz-recs-row:first-child').outerHeight() * 2 + 'px' }, function () {
						$(_this).text('See More');
					}).addClass("toggled");
				}

				$('.pa-wz-recs #pa-wz-recs-gradient').toggleClass("toggled");
			});
		};

		_this.wizardToggleListHandler = function () {

			// hide the fade effect on scroll as it prevent the list click event.
			$('#pa-step-widgets-content .pa-wz-listing-outer-wrapper').on('mouseenter', function () {
				if (!$(this).hasClass('scrolled')) {
					$(this).addClass('scrolled');
					$('#pa-wz-list-gradient').css('z-index', '-1');
				}
			});

			$('#pa-step-widgets-content .pa-wz-listing-outer-wrapper').on('mouseleave', function () {
				$(this).removeClass('scrolled');
				$('#pa-wz-list-gradient').css('z-index', '1');
			});

			$('#pa-step-widgets-content .pa-wz-listing-wrapper').on('click.paWizardListing', function (e) {

				e.stopPropagation();
				e.preventDefault();

				if ($(this).hasClass('opened')) {

					$(this).find('.pa-wz-list-content').slideUp('slow');
					$(this).removeClass('opened');
				} else {

					$('#pa-step-widgets-content .pa-wz-listing-wrapper').removeClass('opened');
					$('#pa-step-widgets-content .pa-wz-list-content').slideUp('slow');

					$(this).addClass('opened');
					$(this).find('.pa-wz-list-content').slideDown('slow');
				}
			});

			// stops the switcher click event from bubbling up.
			$('#pa-step-widgets-content .pa-wz-list-content').on("click", function (e) {
				e.stopPropagation();
			});
		};
	};

	var instance = new PremiumAddonsWizardHandler();

	instance.init();

})(jQuery);
