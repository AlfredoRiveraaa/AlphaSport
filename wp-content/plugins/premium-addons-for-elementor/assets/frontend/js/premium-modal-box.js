(function ($) {

	var PremiumModalBoxHandler = function ($scope, $) {

		var $modalElem = $scope.find(".premium-modal-box-container"),
			settings = $modalElem.data("settings"),
			$modal = $modalElem.find(".premium-modal-box-modal-dialog"),
			id = $scope.data('id'),
			isDismissible = $scope.hasClass('premium-modal-dismissible-yes');

		if (!settings) {
			return;
		}

		var modalOptions = {
			backdrop: isDismissible ? true : "static",
			keyboard: isDismissible
		};

		// Disable dismiss behavior if not dismissible.
		if (!isDismissible) {

			//Hide upper and lower close buttons.
			$modalElem.find(".premium-modal-box-close-button-container, .premium-modal-box-modal-footer").hide();

		}

		if ("pageload" === settings.trigger) {

			$(document).ready(function () {
				setTimeout(function () {
					$modalElem.find(".premium-modal-box-modal").modal(modalOptions);
				}, settings.delay * 1000);
			});

		} else if ("exit" === settings.trigger || settings.show_on_exit) {

			if (elementorFrontend.config.user && !settings.show_on_exit) {

				$modalElem.find(".premium-modal-box-modal").modal(modalOptions);
			} else {

				if (!localStorage.getItem('paModal' + id)) {

					var isTriggered = false;

					elementorFrontend.elements.$window.on('mouseleave', function (e) {

						if (!isTriggered && e.clientY <= 0) {

							isTriggered = true;
							$modalElem.find(".premium-modal-box-modal").modal(modalOptions);
							$modalElem.find(".premium-modal-box-modal").on('hidden.bs.modal', function () {
								localStorage.setItem('paModal' + id, true);
							});

						}

					});
				}
			}

		}

		if ($modal.data("modal-animation") && " " != $modal.data("modal-animation")) {

			var animationDelay = $modal.data('delay-animation');

			// Using IntersectionObserverAPI.
			var eleObserver = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						setTimeout(function () {
							$modal.css("opacity", "1").addClass("animated " + $modal.data("modal-animation"));
						}, animationDelay * 1000);

						eleObserver.unobserve(entry.target); // to only excecute the callback func once.
					}
				});
			}, {
				threshold: 0.25
			});

			eleObserver.observe($modal[0]);
		}
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/premium-addon-modal-box.default', PremiumModalBoxHandler);
	});
})(jQuery);

