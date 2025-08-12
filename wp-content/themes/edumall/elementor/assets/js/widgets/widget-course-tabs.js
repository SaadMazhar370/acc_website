(
	function ($) {
		'use strict'

		var EdumallCourseTabs = function ($scope, $) {
			var $element = $scope.find('.edumall-tabpanel')

			const observer = new IntersectionObserver((entries, _observer) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						var navTabs = $element.children('.edumall-tab-content')
						var activeTab = navTabs.children('.active')

						if (!activeTab.hasClass('ajax-loaded')) {
							loadCourseData(activeTab)

							activeTab.addClass('ajax-loaded')
						}
					}
				})
			}, { rootMargin: '0px 0px 0px 0px' })

			observer.observe($element.get(0))

			$(document).on('EdumallTabChange', function (e, tabPanel, currentTab) {
				if (!currentTab.hasClass('ajax-loaded')) {
					loadCourseData(currentTab)

					currentTab.addClass('ajax-loaded')
				} else {
					// Fixed layout broken after window resize & tab change.
					var $component = currentTab.find('.tm-tab-course-element')
					var layout = currentTab.data('layout')

					if ('grid' === layout) {
						$component.EdumallGridLayout('calculateMasonrySize')
					} else {
						var swiper = $component.children('.swiper-inner').children('.swiper')[ 0 ].swiper
						swiper.update()
					}
				}
			})

			function loadCourseData (currentTab) {
				var $component = currentTab.find('.tm-tab-course-element')
				var layout = currentTab.data('layout')
				if ('grid' === layout) {
					$component.EdumallGridLayout()
				} else {
					$component.EdumallSwiper()
				}

				var query = currentTab.data('query')
				query.action = 'get_course_tabs'

				$.ajax({
					url: $edumall.ajaxurl,
					type: 'POST',
					data: query,
					dataType: 'json',
					success: function (response) {
						if (!response.success) {
							$component.remove()
							currentTab.find('.edumall-grid-response-messages').html(response.template)
						} else {
							if ('grid' === layout) {
								var $grid = $component.children('.edumall-grid')
								$grid.children('.grid-item').remove()
								$component.EdumallGridLayout('update', $(response.template))
							} else {
								var swiper = $component.children('.swiper-inner').children('.swiper')[ 0 ].swiper
								swiper.removeAllSlides()
								swiper.appendSlide(response.template)
								swiper.update()
							}
						}
					}
				})
			}
		}

		$(window).on('elementor/frontend/init', function () {
			elementorFrontend.hooks.addAction('frontend/element_ready/tm-course-tabs.default', EdumallCourseTabs)
		})
	}
)(jQuery)
