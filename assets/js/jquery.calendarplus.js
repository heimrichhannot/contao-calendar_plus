(function ($) {

    var CalendarPlus = {
        onReady: function () {
            this.showEventsInModal();
            this.closeEventsModal();
        },
        showEventsInModal: function () {
            $('body').on('click', '[data-event="modal"]', function (e) {
                e.preventDefault();
                var $this = $(this),
                    $modal = $($this.data('target')),
                    $replace = $modal.find('.modal-dialog');

                // change history base to filtered url, as long we are not in modal view
                if (!$modal.hasClass('in')) {
                    $modal.data('history-base-filtered', window.location.href);
                }

                $replace.load($this.attr('href'), function (responseText, textStatus, jqXHR) {
                    $replace.html(responseText);
                    history.pushState(null, null, $this.attr('href'));
                    $modal.modal('show');
                });
            });
        },
        closeEventsModal: function () {
            $('.mod_eventreader_plus').on('hide.bs.modal', function (e) {

                var $this = $(this);

                // set url to history-base-filtered if set (modal content replaced via ajax)
                if($this.data('history-base-filtered'))
                {
                    history.pushState(null, null, $this.data('history-base-filtered'));
                }
                // redirect to base url (modal window opened via direct event url)
                else{
                    window.location.href = $this.data('history-base');
                }
            });
        }
    }


    $(document).ready(function () {
        CalendarPlus.onReady()
    });

})(jQuery);
