function initMap() {
	console.log('Google API Loaded');
}

jQuery(window).on("elementor/frontend/init", function () {

	var PremiumMapsHandler = function ($scope, $) {

		var $linkedCarouselWidget = null,
			mapElement = $scope.find(".premium_maps_map_height"),
			mapSettings = mapElement.data("settings"),
			mapStyle = mapElement.data("style"),
			premiumMapMarkers = [],
			premiumMapPopups = [];

		var checkGoogleMapsLoaded = setInterval(function () {
			if (typeof google !== "undefined" &&
				typeof google.maps !== "undefined" &&
				typeof google.maps.Map === "function") {
				clearInterval(checkGoogleMapsLoaded);
				// Once Google API is loaded, trigger the maps handler.
				setTimeout(function () {
					triggerMap();
				}, 150);

			}
		}, 100);

		function triggerMap() {

			if (mapSettings.loadScroll) {

				var $closestSection = $scope.closest('.elementor-top-section, .e-con');

				var eleObserver = new IntersectionObserver(function (entries) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							premiumMap = newMap(mapElement, mapSettings, mapStyle);
							eleObserver.unobserve(entry.target); // to only excecute the callback func once.
						}
					});
				}, {
					rootMargin: '-70% 0px 0px 0px'
				});

				eleObserver.observe($closestSection[0]);

			} else {

				premiumMap = newMap(mapElement, mapSettings, mapStyle);
			}

		}

		function newMap(map, settings, mapStyle) {

			var scrollwheel = settings.scrollwheel,
				streetViewControl = settings.streetViewControl,
				fullscreenControl = settings.fullScreen,
				zoomControl = settings.zoomControl,
				mapTypeControl = settings.typeControl,
				centerLat = JSON.parse(settings.centerlat),
				centerLong = JSON.parse(settings.centerlong),
				autoOpen = settings.automaticOpen,
				hoverOpen = settings.hoverOpen,
				hoverClose = settings.hoverClose,
				args = {
					mapId: settings.mapId || '',
					zoom: settings["zoom"],
					mapTypeId: settings["maptype"],
					center: { lat: centerLat, lng: centerLong },
					scrollwheel: scrollwheel,
					streetViewControl: streetViewControl,
					fullscreenControl: fullscreenControl,
					zoomControl: zoomControl,
					mapTypeControl: mapTypeControl,
					styles: mapStyle
				};

			if ("yes" === mapSettings.drag)
				args.gestureHandling = "none";

			var markers = map.find(".premium-pin");

			var map = new google.maps.Map(map[0], args);

			//Show the maps when it's ready.
			mapElement.removeClass('premium-addons__v-hidden');

			map.markers = [];

			$linkedCarouselWidget = settings.linkedCarouselId ? $("#" + settings.linkedCarouselId + " .premium-carousel-wrapper") : [];

			// add markers
			markers.each(function (index) {
				addMarker(jQuery(this), map, autoOpen, hoverOpen, hoverClose, index, args.mapId);
			});

			if ($linkedCarouselWidget.length > 0) {

				$linkedCarouselWidget.find(".premium-carousel-inner").on("afterChange", function (event, slick, currentSlide) {

					premiumMapPopups.map(function (popup, index) {

						popup.close();
					});

					if (premiumMapPopups[currentSlide])
						premiumMapPopups[currentSlide].open(map, map.markers[currentSlide]);

				});

			}

			if (mapSettings.cluster && window.markerClusterer && args.mapId) {

				// Initialize MarkerClusterer
				new markerClusterer.MarkerClusterer({
					map: map,
					markers: premiumMapMarkers,
					renderer: {
						render: function (options) {
							var count = options.count;
							var position = options.position;
							var clusterIcon = document.createElement("div");
							var iconSize = mapSettings.cluster_icon_size || 50;
							var iconUrl = mapSettings.cluster_icon || "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m1.png";

							// Customize cluster icon appearance
							clusterIcon.innerHTML =
								'<img src="' + iconUrl + '" ' +
								'width="' + iconSize + '" ' +
								'height="' + iconSize + '" ' +
								'style="position: absolute; transform: translate(-50%, -50%);">' +
								'<div style="position: absolute; ' +
								'top: 50%; ' +
								'left: 50%; ' +
								'transform: translate(-50%, -50%); ' +
								'color: white; ' +
								'font-weight: bold; ' +
								'font-size: 11px;">' +
								count +
								'</div>';

							var clusterMarker = new google.maps.marker.AdvancedMarkerElement({
								position: position,
								content: clusterIcon,
							});

							return clusterMarker;

						},
					}

				});


			}

			return map;
		}

		var activeInfoWindow = null;
		function addMarker(pin, map, autoOpen, hoverOpen, hoverClose, zIndex, mapID) {

			var latlng = new google.maps.LatLng(pin.data("lat"), pin.data("lng")),
				iconImg = pin.data("icon"),
				maxWidth = pin.data("max-width"),
				customID = pin.data("id"),
				isActive = pin.data("activated"),
				iconSize = parseInt(pin.data("icon-size"));

			if (!mapID) {

				if (iconImg) {
					var icon = {
						url: iconImg
					};

					if (iconSize) {
						icon.scaledSize = new google.maps.Size(iconSize, iconSize);
						icon.origin = new google.maps.Point(0, 0);
						icon.anchor = new google.maps.Point(iconSize / 2, iconSize);
					}
				}

				// Create marker
				var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					icon: icon,
					zIndex: zIndex
				});

			} else {

				if (iconImg) {

					var markerContent = document.createElement("div"),
						img = document.createElement("img");

					img.src = iconImg;
					img.width = iconSize || 50;
					img.height = iconSize || 50;
					img.style.display = "block";
					img.alt = "";
					markerContent.appendChild(img);
				}

				// Create marker
				var marker = new google.maps.marker.AdvancedMarkerElement({
					position: latlng,
					map: map,
					zIndex: zIndex,
					content: markerContent,
				});

			}

			//Used with Carousel Custom Navigation option
			if (customID) {

				google.maps.event.addListener(marker, "click", function () {

					if ($linkedCarouselWidget.length > 0) {

						var carouselSettings = $linkedCarouselWidget.data("settings");

						if (carouselSettings.navigation) {

							if (-1 != carouselSettings.navigation.indexOf("#" + customID)) {
								var slideIndex = carouselSettings.navigation.indexOf("#" + customID);
								$linkedCarouselWidget.find(".premium-carousel-inner").slick("slickGoTo", slideIndex);
							}
						}


					}

				});
			}

			// if marker contains HTML, add it to an infoWindow
			if (pin.find(".premium-maps-info-title").html() || pin.find(".premium-maps-info-desc").html()) {
				// create info window

				var infowindow = new google.maps.InfoWindow({
					maxWidth: maxWidth,
					content: pin.html()
				});

				//Opened by default.
				if (autoOpen || isActive) {
					infowindow.open(map, marker);
				}

				//Open on hover.
				if (hoverOpen) {

					var isTouch = checkTouchDevice(),
						triggerEvent = isTouch ? "click" : "mouseover";

					google.maps.event.addListener(marker, triggerEvent, function () {

						if (activeInfoWindow)
							activeInfoWindow.close();

						activeInfoWindow = infowindow;

						infowindow.open(map, marker);

					});

					//Close on mouse out.
					if (hoverClose && !isTouch) {
						google.maps.event.addListener(marker, "mouseout", function () {
							infowindow.close(map, marker);
						});
					}
				}

				// Show info window when marker is clicked
				google.maps.event.addListener(marker, "click", function () {

					if (activeInfoWindow)
						activeInfoWindow.close();

					//Used with Carousel Custom Navigation option
					if (customID) {

						if ($linkedCarouselWidget.length) {

							var carouselSettings = $linkedCarouselWidget.data("settings");

							if (carouselSettings.navigation) {
								if (-1 != carouselSettings.navigation.indexOf("#" + customID)) {
									var slideIndex = carouselSettings.navigation.indexOf("#" + customID);
									$linkedCarouselWidget.find(".premium-carousel-inner").slick("slickGoTo", slideIndex);
								}
							}


						}

					}

					activeInfoWindow = infowindow;

					infowindow.open(map, marker);

				});

				infowindow.addListener('visible', function () {

					if (pin.find('.advanced-pin').length > 0)
						$('.gm-ui-hover-effect').remove();

					$scope.find('.premium-maps-info-close').on('click', function () {
						infowindow.close(map, marker);
					})
				})


				if ($linkedCarouselWidget.length > 0)
					premiumMapPopups.push(infowindow);


			}

			// add to array
			map.markers.push(marker);
			premiumMapMarkers.push(marker);

		}

		function checkTouchDevice() {

			var currentDevice = elementorFrontend.getCurrentDeviceMode();

			return !['desktop', 'widescreen', 'laptop'].includes(currentDevice);

		}

	};

	elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-maps.default", PremiumMapsHandler);

});
