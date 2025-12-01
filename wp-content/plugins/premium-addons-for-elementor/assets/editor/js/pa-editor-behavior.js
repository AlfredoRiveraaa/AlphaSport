(function ($) {

	'use strict';

	var PAWidgetsEditor = {

		init: function () {

			window.elementor.on('preview:loaded', function () {

				elementor.$preview[0].contentWindow.PAWidgetsEditor = PAWidgetsEditor;

			});

		},

		activateControlsTab: function (tab, parentTab) {

			setTimeout(function () {

				if (parentTab)
					$('.elementor-tab-control-' + parentTab).trigger('click');

				var $tab = $("div.elementor-control-" + tab);

				if ($tab.length && !$tab.hasClass('e-open')) {

					$tab.trigger('click');
				}

			}, 150);

		}

	}

	$(window).on('elementor:init', PAWidgetsEditor.init);

	window.PAWidgetsEditor = PAWidgetsEditor;


})(jQuery);
