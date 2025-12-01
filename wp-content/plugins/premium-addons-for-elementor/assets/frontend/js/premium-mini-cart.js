(function ($) {

	var PremiumMiniCartHandler = function ($scope, $) {

		var settings = $scope.find('.pa-woo-mc__outer-container').data('settings');

		if (!settings) {
			return;
		}

		var triggerEvent = settings.trigger,
			type = settings.type,
			id = $scope.data('id'),
			hoverTimeout,
			paodometer,
			paSubtotalOdometer;

		$scope.find('.pa-woo-mc__inner-container').off('click.paToggleMiniCart mouseenter.paToggleMiniCart mouseleave.paToggleMiniCart');

		initWidgetEvents();

		initCartContentEvents();

		updateCartDynamicText();

		initCountingEffect();

		if (settings.crossSells) {
			setTimeout(function () {
				initCrossSellsCarousel();
			}, 0);
		}

		// Reinitialize the event listeners after the mini cart is refreshed.
		$(document.body).on('wc_fragments_loaded wc_fragments_refreshed', function (e) {

			hideContentIfEmptyCart();
			initCartContentEvents();
			updateCartDynamicText();

			if (settings.crossSells) {
				setTimeout(function () {
					initCrossSellsCarousel();
				}, 0);
			}

			// counting effect.
			if ($scope.hasClass('premium-mc-counting-yes')) {
				setTimeout(function () {
					var newCount = $scope.find('.pa-woo-mc__count-placeholder').text(),
						newSubtotal = $scope.find('.pa-woo-mc__text-wrapper .pa-woo-mc__subtotal-placeholder').text();

					if ($scope.find('.pa-woo-mc__badge.pa-counting').length) {
						paodometer.update(newCount);
					}

					if ($scope.find('.pa-woo-mc__subtotal.pa-counting .pa-woo-mc__subtotal-val').length) {
						paSubtotalOdometer.update(newSubtotal);
					}
				}, 0);
			}
		});

		if (settings.cssSelector) {
			var cartSelector = settings.cssSelector,
				selectorName = cartSelector.replace(/^[.#]/, '');

			$(cartSelector).off('click.paCustomTrigger').on('click.paCustomTrigger', function (e) {
				e.preventDefault();

				$('.elementor-widget-premium-mini-cart .pa-woo-mc__outer-container[data-cart-selector="' + selectorName + '"]')
					.find('.pa-woo-mc__inner-container')
					.trigger('click.paToggleMiniCart');
			});
		}

		/**Helper Function */

		function initCountingEffect() {
			var isEnabled = $scope.hasClass('premium-mc-counting-yes');

			if (!isEnabled) {
				return;
			}

			if ($scope.find('.pa-woo-mc__badge.pa-counting .odometer-wrapper').length) {
				paodometer = new Odometer({
					el: $scope.find('.pa-woo-mc__badge.pa-counting .odometer-wrapper')[0],
					value: $scope.find('.pa-woo-mc__count-placeholder').text(),
					format: '(,ddd).dd'
				});
			}

			if ($scope.find('.pa-woo-mc__subtotal.pa-counting .pa-woo-mc__subtotal-val').length) {
				paSubtotalOdometer = new Odometer({
					el: $scope.find('.pa-woo-mc__subtotal.pa-counting .pa-woo-mc__subtotal-val')[0],
					value: $scope.find('.pa-woo-mc__text-wrapper .pa-woo-mc__subtotal-placeholder').text(),
					format: '(,ddd).dd'
				});
			}
		}

		function initCrossSellsCarousel() {
			$scope.find('.pa-woo-mc__cross-sells').slick({
				infinite: true,
				draggable: true,
				pauseOnHover: true,
				arrows: false,
				slidesToShow: settings.slidesToShow || 3,
				slidesToScroll: settings.slidesToScroll || 1,
				speed: settings.speed || 1000,
				autoplay: settings.autoplay,
				autoplaySpeed: settings.autoplaySpeed || 5000,
				rtl: elementorFrontend.config.is_rtl,
			});

			//cross sells nav.
			$scope.find('.pa-woo-mc__cross-sells-arrows a').on('click.paCrossSellsNav', function () {

				if ($(this).hasClass('prev-arrow')) {
					$scope.find('.pa-woo-mc__cross-sells').slick('slickPrev');

				} else if ($(this).hasClass('next-arrow')) {
					$scope.find('.pa-woo-mc__cross-sells').slick('slickNext');
				}
			});

			if ($scope.find('.pa-woo-mc__cross-sells .pa-woo-mc__cross-sell-product').length > 1) {
				// show carousel arrows.
				$scope.find('.pa-woo-mc__cross-sells-arrows')
					.css({
						visibility: 'inherit',
						opacity: '1'
					});

			} else {
				// hide carousel arrows.
				$scope.find('.pa-woo-mc__cross-sells-arrows')
					.css({
						visibility: 'hidden',
						opacity: '0'
					});
			}
		}

		/**Hides the cart footer if the cart is empty. */
		function hideContentIfEmptyCart() {

			if ($('.pa-woo-mc__content-wrapper-' + id + ' .pa-woo-mc__empty-msg').length) {
				$('.pa-woo-mc__content-wrapper-' + id).addClass('pa-hide-content');
			} else {
				$('.pa-woo-mc__content-wrapper-' + id).removeClass('pa-hide-content');
			}
		}


		/** Handles Mini Cart Display */
		function toggleMiniCart(e) {
			if ('hover' === triggerEvent) {
				e.stopPropagation();
				clearTimeout(hoverTimeout);

				$scope.find('.pa-woo-mc__content-wrapper-' + id)
					.removeClass('premium-addons__v-hidden')
					.addClass('pa-woo-mc__open');

			} else {
				if ('slide' === type) {
					$(".pa-woo-mc__overlay-" + id).removeClass("premium-addons__v-hidden"); // show overlay for slide type.
				}

				$scope.find('.pa-woo-mc__content-wrapper-' + id)
					.removeClass('premium-addons__v-hidden')
					.toggleClass('pa-woo-mc__open');
			}

			// refresh carousel on opening the mini cart.
			if ($scope.find(".slick-slider").length > 0) {
				$scope.find('.pa-woo-mc__cross-sells').slick('setPosition');
			}
		}
		/**
		 * Appends a tax label to a given subtotal.
		 */
		function appendTaxLabel(labelText) {

			var $subtotal = $scope.find('.pa-woo-mc__inner-container .pa-woo-mc__subtotal'),
				$footerSubtotal = $scope.find('.pa-woo-mc__cart-footer .pa-woo-mc__subtotal');

			if ($scope.hasClass('pa-trigger-label-yes') && 0 === $subtotal.find('.pa-woo-mc__tax-label').length) {
				$subtotal.append(' <small class="pa-woo-mc__tax-label">' + labelText + '</small>');
			}

			if ($scope.hasClass('pa-footer-label-yes') && 0 === $footerSubtotal.find('.pa-woo-mc__tax-label').length) {
				$footerSubtotal.append(' <small class="pa-woo-mc__tax-label">' + labelText + '</small>');
			}
		}

		/**
		 * Updates Cart Dynamic Text.
		 * We add the text here as it gets replaced with only the items' count when
		 * WC Fragments are refreshed.
		 */
		function updateCartDynamicText() {

			var countTxt = $scope.find('div[data-pa-count-txt]').data('pa-count-txt');

			if (countTxt) {
				var itemCount = $scope.find('.pa-woo-mc__cart-count').text();

				if ($scope.hasClass('pa-woo-mc__layout-3')) {
					// in layout 3, the count text is inside the cart header.
					var rawCount = $scope.find('.pa-woo-mc__cart-count').text().match(/\d+/);
					var itemCount = rawCount ? rawCount[0] : 0;

					$scope.find('.pa-woo-mc__cart-header .pa-woo-mc__cart-count').text(itemCount + ' ' + countTxt);
				} else {
					if (countTxt.includes('{{count}}')) { // all layouts but layout 3.
						var newTxt = countTxt.replace("{{count}}", '<span class="pa-woo-mc__cart-count">' + itemCount + '</span>');
						$scope.find('.pa-woo-mc__cart-footer .pa-woo-mc__subtotal-heading').html(newTxt);
					}

				}
			}

			if (settings.removeTxt) {
				$scope.find('.pa-woo-mc__remove-item span').text(settings.removeTxt);
			}

			if (settings.crossSellTxt) {
				$scope.find('.pa-woo-mc__cross-sells-heading').text(settings.crossSellTxt);
			}

			if (settings.taxLabel) {
				appendTaxLabel(settings.taxLabel);
			}

			// updating the progressbar.
			if ($scope.find('.pa-woo-mc__progressbar-wrapper').length) {
				var subtotal = parseFloat($scope.find('.pa-woo-mc__progressbar-wrapper .pa-woo-mc__subtotal-placeholder').text()),
					progressTxt = $scope.find('.pa-woo-mc__progressbar-wrapper').data('pa-progress-txt'),
					freeShippingThreshold = parseFloat($scope.find('.pa-woo-mc__progressbar-wrapper').data('pa-progress-threshold')),
					progressVal = parseFloat(((subtotal / freeShippingThreshold) * 100).toFixed(2)),
					completeTxt = $scope.find('.pa-woo-mc__progressbar-wrapper').data('pa-progress-complete');

				$scope.find('.pa-woo-mc__progressbar').attr('value', progressVal);

				// update its message if the purchase threshold is met.
				if ((subtotal >= freeShippingThreshold) && completeTxt) {
					$scope.find('.pa-woo-mc__progress-heading').html(completeTxt);
				} else {
					$scope.find('.pa-woo-mc__progress-heading').html(progressTxt);
				}
			}
		}

		/** Adds Cart Items' Events.
		 * Updating the item quantity, or deleting it.
		*/
		function initCartContentEvents() {

			$('.pa-woo-mc__qty-btn').on('click', function (e) {
				e.stopPropagation();

				var $input = $(this).parent().find('.pa-woo-mc__input')[0],
					allowBackorders = $scope.hasClass('pa-allow-bkorders-yes'),
					itemStock = parseInt($($input).attr('max')),
					currentVal = parseInt($($input).val());

				if ($(this).hasClass('plus')) {
					// backorders allowed || current value less than max stock.
					// we check if item stock is NAN, to cover the case where no stock is specified for a product.
					if (allowBackorders || currentVal < itemStock || isNaN(itemStock)) {
						if (allowBackorders) {
							$($input).removeAttr('max');
						}

						$input.stepUp();
						$($input).trigger('change');
					} else {
						$(this).parents('.pa-woo-mc__item-wrapper').find('.pa-woo-mc__item-notice').text(PAWooMCartSettings.stock_msg + itemStock);
					}

				} else {

					$input.stepDown();
					$($input).trigger('change');
				}
			});

			// update item quantity.
			$scope.find('.pa-woo-mc__input').on('change', function () {

				var itemKey = $(this).attr('name').replace('cart-', ''),
					newQty = $(this).val();

				if ('1' === newQty) {
					$(this).siblings('.pa-woo-mc__qty-btn.minus').addClass('disabled');
				} else {
					$(this).siblings('.pa-woo-mc__qty-btn.minus').removeClass('disabled');
				}

				sendCartAjax('pa_update_mc_qty', itemKey, newQty);
			});

			// delete cart item.
			$scope.find('.pa-woo-mc__remove-item').on('click.paRemoveCartItem', function (e) {
				e.stopPropagation();
				var itemKey = $(this).data('pa-item-key').replace('cart-', '');
				sendCartAjax('pa_delete_cart_item', itemKey, false);
			});

			$scope.find('.pa-woo-mc__input').on('click', function (e) {
				e.stopPropagation();
			});

		}

		/**
		 * Sends an ajax request to update/delete a cart item.
		 *
		 * @param {String} action Request action.
		 * @param {String} itemKey Items's key.
		 * @param {Boolean|String} qty false|item quantity.
		 */
		function sendCartAjax(action, itemKey, extraData) {

			var data = {
				action: action,
				nonce: PAWooMCartSettings.mini_cart_nonce,
			};

			switch (action) {
				case 'pa_update_mc_qty':
					if (!extraData) {
						return;
					}

					data.itemKey = itemKey;
					data.quantity = extraData;
					break;

				case 'pa_delete_cart_item':
					data.itemKey = itemKey;
					break;

				case 'pa_apply_coupon':
				case 'pa_remove_coupon':

					data.couponCode = extraData;
					break;
			}

			var $removeLink = $scope.find('.pa-woo-mc__remove-coupon');

			$.ajax({
				url: PAWooMCartSettings.ajaxurl,
				dataType: 'JSON',
				type: 'POST',
				data: data,
				beforeSend: function () {
					$scope.find('.pa-woo-mc__widget-shopping-outer-wrapper').append('<div class="premium-loading-feed"><div class="premium-loader"></div></div>');
				},
				success: function (res) {

					$(document.body).trigger('wc_fragment_refresh');

					if ('pa_apply_coupon' === action) {
						$scope.find('.pa-woo-mc__coupon-notice').removeClass('pa-error-notice').text(res.data);
						$removeLink.css('display', 'inline-block');
					}

					if ('pa_remove_coupon' === action) {
						$removeLink.hide();
						$scope.find('.pa-woo-mc__coupon-field').val('');
						$scope.find('.pa-woo-mc__coupon-notice').removeClass('pa-error-notice').text('');
					}

				},
				error: function (err) {
					console.log(err);

					var status = err.status;

					if ('pa_apply_coupon' === action) {
						$scope.find('.pa-woo-mc__coupon-notice').addClass('pa-error-notice').text(err.responseJSON.data);

						if (status === 409) {
							$removeLink.css('display', 'inline-block');
						} else {
							$removeLink.hide();
						}
					}
				},
				complete: function (res) {
					$scope.find('.premium-loading-feed').remove();
				}
			});
		}

		/** Add the widget's basic events, doesn't need to be re-added on cart fragments refresh */
		function initWidgetEvents() {

			if ('click' === triggerEvent) {
				$scope.find('.pa-woo-mc__inner-container').on('click.paToggleMiniCart', toggleMiniCart);
			} else {
				// hover => mini window.
				$scope.find('.pa-woo-mc__inner-container').on('mouseenter.paToggleMiniCart', toggleMiniCart);

				$scope.on('mouseleave.paToggleMiniCart', function (e) {

					hoverTimeout = setTimeout(function () {
						$scope.find('.pa-woo-mc__content-wrapper-' + id).removeClass('pa-woo-mc__open');
					}, 300);
				});
			}

			// remove all btn.
			$scope.find('.pa-woo-mc__remove-all-btn').on('click.paConfirm', function () {
				$(this).hide();
				$scope.find('.pa-woo-mc__empty-mc-confirm').css('display', 'flex');
			});

			$scope.find('.pa-woo-mc__confirm-btn').on('click.paEmptyMC', function () {

				if ($(this).hasClass('pa-empty-mc')) {
					sendCartAjax('pa_delete_cart_items', false, false);
				}

				// show the main message anyway.
				$scope.find('.pa-woo-mc__remove-all-btn').show();
				$scope.find('.pa-woo-mc__empty-mc-confirm').hide();
			});

			// apply coupon.
			$scope.find('.pa-woo-mc__coupon-submit').on('click', function () {
				var couponCode = $scope.find('.pa-woo-mc__coupon-field').val();

				if (couponCode) {
					sendCartAjax('pa_apply_coupon', false, couponCode);
				}
			});

			// remove coupon.
			$scope.find('.pa-woo-mc__remove-coupon').on('click.paRemoveCoupon', function (e) {
				e.preventDefault();
				var couponCode = $scope.find('.pa-woo-mc__coupon-field').val();

				if (couponCode) {
					sendCartAjax('pa_remove_coupon', false, couponCode);
				}
			});

			//On Click outside, close everything.
			$("body").off("click.paCloseMC");
			if (settings.clickOutside) {

				$("body").on("click.paCloseMC", function (event) {
					var mcContent = ".premium-tabs-nav-list-item, .pa-woo-mc__content-wrapper, .pa-woo-mc__content-wrapper *, .pa-woo-mc__inner-container, .pa-woo-mc__inner-container *";

					if (!$(event.target).is(mcContent)) {
						if ('menu' === type) {
							$scope.find('.pa-woo-mc__content-wrapper-' + id).removeClass('pa-woo-mc__open');
						} else {
							$(".pa-woo-mc__overlay-" + id).addClass("premium-addons__v-hidden");
							$scope.find('.pa-woo-mc__content-wrapper-' + id).removeClass('pa-woo-mc__open');
						}
					}
				});
			}

			/**
			 * Events: Closing the slide menu.
			 */
			$scope.find(".pa-woo-mc__close-button").on("click", function () {
				$(".pa-woo-mc__overlay-" + id).addClass("premium-addons__v-hidden");
				$scope.find('.pa-woo-mc__content-wrapper-' + id).removeClass('pa-woo-mc__open');
			});

			if (settings.coupon) {

				$scope.find('.pa-woo-mc__coupon-toggler').click(function () {
					if ($scope.find('.pa-woo-mc__coupon-wrapper').is(':visible')) {
						$scope.find('.pa-woo-mc__coupon-wrapper').slideUp();
					} else {
						$scope.find('.pa-woo-mc__coupon-wrapper').slideDown();
					}
				});
			}
		}
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/premium-mini-cart.default', PremiumMiniCartHandler);
	});
})(jQuery);
