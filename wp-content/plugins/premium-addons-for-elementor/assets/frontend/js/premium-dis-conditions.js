(function ($) {

	$(window).on('elementor/frontend/init', function () {

		//Time range condition cookie.
		var localTimeZone = new Date().toString().match(/([A-Z]+[\+-][0-9]+.*)/)[1],
			isSecured = (document.location.protocol === 'https:') ? 'secure' : '';

		if (-1 != localTimeZone.indexOf("(")) {
			localTimeZone = localTimeZone.split('(')[0];
		}

		document.cookie = "localTimeZone=" + localTimeZone + ";SameSite=Strict;" + isSecured;

		var premiumDisplayConditionsHandler = function ($scope) {

			if (!$scope.hasClass('pa-display-conditions-yes'))
				return;

			var isEditMode = elementorFrontend.isEditMode();

			if (isEditMode) {

				$scope.append('<div class="pa-display-conditions-label">DC</div>');

				$scope.find('.pa-display-conditions-label').on('click', function (e) {

					window.PAWidgetsEditor.activateControlsTab('section_pa_display_conditions', 'advanced');

				});

			}

		};

		elementorFrontend.hooks.addAction("frontend/element_ready/global", premiumDisplayConditionsHandler);

	});

})(jQuery);
