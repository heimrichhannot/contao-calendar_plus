(function($){

    var CalendarPlus = {
        onReady : function(){
            this.showEventsInModal();
        },
        showEventsInModal : function(){
            $('[data-event="modal"]').on('click', function(){
                var $this = $(this);
                $($this.data('target') + ' .modal-dialog').empty();
               $($this.data('target') + ' .modal-dialog').load($this.attr('href'), function(){
               });
            });
        }
    }


    $(document).ready(function(){CalendarPlus.onReady()});

})(jQuery);