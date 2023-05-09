/** 
 *   @desc Right Click jQuery Plugin
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

(function ($) {
    $.fn.rightClick = function (func) {
        var events = "contextmenu rightclick";
        $(this).each(function () {
            $(this).unbind(events).bind(events, function (e) {
                $.globalBlur();
                e.preventDefault();
                $.clearSelection();
                if ($.isFunction(func))
                    func(this, e);
            });
        });
        return $(this);
    };
})(jQuery);