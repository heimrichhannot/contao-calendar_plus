(function ($) {

	var CalendarPlus = {
		onReady: function () {
			this.showEventsInModal();
		},
		showEventsInModal: function () {
			$('body').on('click', '[data-event="modal"]', function (e) {
				e.preventDefault();
				var $modal = $($(this).data('target'));

				// change history base
				if (!$modal.hasClass('in')) {
					$modal.data('history-base-filtered', window.location.href);
				}
			});
		}
	}


	$(document).ready(function () {
		CalendarPlus.onReady()
	});

})(jQuery);
