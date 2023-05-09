/** 
 *   @desc My jQuery UI fixes
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

(function ($) {

    $.fn.oldMenu = $.fn.menu;
    $.fn.menu = function (p1, p2, p3) {
        var ret = $(this).oldMenu(p1, p2, p3);
        $(this).each(function () {
            if (!$(this).hasClass('sh-menu')) {
                $(this).addClass('sh-menu')
                    .children().first().addClass('ui-menu-item-first');
                $(this).children().last().addClass('ui-menu-item-last');
                $(this).find('.ui-menu').addClass('sh-menu').each(function () {
                    $(this).children().first().addClass('ui-menu-item-first');
                    $(this).children().last().addClass('ui-menu-item-last');
                });
            }
        });
        return ret;
    };

})(jQuery);