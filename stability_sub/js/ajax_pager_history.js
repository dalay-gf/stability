(function ($) {
    Drupal.behaviors.gfAjaxPagerHistory = {
        attach: function (context, settings) {

            // Заносим в историю бр-ра переходы по
            // ajax-ссылкам пейджера в каталоге.
            $('.jquery-once-3-processed ul.pager a', context).each(function() {
                var path = ($(this).attr('href').substr(0, 1) != '/') ?
                    '/' + $(this).attr('href') : $(this).attr('href');
                $(this).click(function() {
                    window.history.pushState(null, null, path);
                    return false;
                });
            });
        }
    };
})(jQuery);

