(
	function ($) {
		'use strict'

		var GoogleMapHandler = function ($scope, $) {
			var $element = $scope.find('.tm-google-map')

			const observer = new IntersectionObserver((entries, _observer) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						var $map = $element.children('.map')

						var optionsString = $element.children('.map-options').html()
						var options = false
						try {
							options = JSON.parse(optionsString)
						} catch (ex) {
						}

						if (options) {
							// Force disable mouse wheel, draggable in editor mode.
							if ($('body').hasClass('elementor-editor-active')) {
								options.settings.scrollwheel = false
								options.settings.draggable = false
							}

							var overlays = options.overlay
							var settings = options.settings

							$map.gmap3(settings).overlay(overlays).on({
								mouseover: function (overlay) {
									overlay.$.css({ zIndex: 2 })

									var info = overlay.$.find('.gmap-info-wrapper')
									info.find('.edumall-map-overlay-content').show()
								},
								mouseout: function (overlay) {
									overlay.$.css({ zIndex: 1 })

									var info = overlay.$.find('.gmap-info-wrapper')
									info.find('.edumall-map-overlay-content').hide()
								}
							})
						}
					}
				})
			}, { rootMargin: '0px 0px 0px 0px' })

			observer.observe($element.get(0))
		}

		$(window).on('elementor/frontend/init', function () {
			elementorFrontend.hooks.addAction('frontend/element_ready/tm-google-map.default', GoogleMapHandler)
		})
	}
)(jQuery)
