(function ($) {
    Drupal.behaviors.gfWidgetCategories = {
        attach: function (context, settings) {

            // На маленьких экранах переносим блоки ассортиментов
            // под форму поиска, и делаим их текст "схлопывающимся".
            if (window.matchMedia("(max-width: 990px)").matches) {
                $searchForm = $('#views-exposed-form-products-main-catalog');

                if (!$searchForm.length) {
                    $searchForm = $('#views-exposed-form-products-page-5');
                }

                $block = $('#menu-categories-block');
                $collHeader = $('.widget_categories h3', $block);

                $collHeader.next().hide();
                $collHeader.addClass('collapsible collapsed');
                $block.appendTo($searchForm);

                $collHeader.click(function () {

                    $header = $(this);
                    $content = $header.next();
                    $content.slideToggle(500, function () {
                        $header.toggleClass('collapsed');
                    });
                });

            }


        }
    };
})(jQuery);

