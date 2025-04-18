/** 
 *   @desc Dialog boxes functionality
 *   @package kcfinder-Resurrected
 *   @version 4.0
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

_.alert = function (text, field, options) {
    var close = !field ?
        function () {} :
        ($.isFunction(field) ?
            field :
            function () {
                setTimeout(function () {
                    field.focus();
                }, 1);
            }
        ),
        o = {
            close: function () {
                close();
                if ($(this).hasClass('ui-dialog-content'))
                    $(this).dialog('destroy').detach();
            }
        };

    $.extend(o, options);

    return _.dialog(_.label("Warning"), text.replace("\n", "<br />\n"), o);
};

_.confirm = function (text, callback, options) {
    var o = {
        buttons: [{
                text: _.label("Yes"),
                icons: {
                    primary: "ui-icon-check"
                },
                click: function () {
                    callback();
                    $(this).dialog('destroy').detach();
                }
            },
            {
                text: _.label("No"),
                icons: {
                    primary: "ui-icon-closethick"
                },
                click: function () {
                    $(this).dialog('destroy').detach();
                }
            }
        ]
    };

    $.extend(o, options);
    return _.dialog(_.label("Confirmation"), text, o);
};

_.dialog = function (title, content, options) {

    if (!options) options = {};
    var dlg = $('<div></div>');
    dlg.hide().attr('title', title).html(content).appendTo('body');
    if (dlg.find('form').get(0) && !dlg.find('form [type="submit"]').get(0))
        dlg.find('form').append('<input type="hidden" name="csrf_token" value="' + csrfToken + '" />');
    dlg.find('form').append('<button type="submit" style="width:0;height:0;padding:0;margin:0;border:0;visibility:hidden">Submit</button>');

    var o = {
        resizable: false,
        minHeight: false,
        modal: true,
        width: 351,
        buttons: [{
            text: _.label("OK"),
            icons: {
                primary: "ui-icon-check"
            },
            click: function () {
                if (typeof options.close != "undefined")
                    options.close();
                if ($(this).hasClass('ui-dialog-content'))
                    $(this).dialog('destroy').detach();
            }
        }],
        close: function () {
            if ($(this).hasClass('ui-dialog-content'))
                $(this).dialog('destroy').detach();
        },
        closeText: false,
        zindex: 1000000,
        alone: false,
        blur: false,
        legend: false,
        nopadding: false,
        show: {
            effect: "fade",
            duration: 250
        },
        hide: {
            effect: "fade",
            duration: 250
        }
    };

    $.extend(o, options);

    if (o.alone)
        $('.ui-dialog .ui-dialog-content').dialog('destroy').detach();

    dlg.dialog(o);

    if (o.nopadding)
        dlg.css({
            padding: 0
        });

    if (o.blur)
        dlg.parent().find('.ui-dialog-buttonpane button').first().get(0).blur();

    if (o.legend)
        dlg.parent().find('.ui-dialog-buttonpane').prepend('<div style="float:left;padding:10px 0 0 10px">' + o.legend + '</div>');

    if ($.agent && $.agent.firefox)
        dlg.css('overflow-x', "hidden");

    return dlg;
};

_.fileNameDialog = function (post, inputName, inputValue, url, labels, callBack, selectAll) {
    var html = '<form method="post" action="javascript:;"><input name="' + inputName + '" type="text" /></form>',
        submit = function () {
            var name = dlg.find('[type="text"]').get(0);
            name.value = $.trim(name.value);
            if (name.value == "") {
                _.alert(_.label(labels.errEmpty), function () {
                    name.focus();
                });
                return false;
            } else if (/[\/\\]/g.test(name.value)) {
                _.alert(_.label(labels.errSlash), function () {
                    name.focus();
                });
                return false;
            } else if (name.value.substr(0, 1) == ".") {
                _.alert(_.label(labels.errDot), function () {
                    name.focus();
                });
                return false;
            }
            post[inputName] = name.value;
            post['csrf_token'] = csrfToken;
            $.ajax({
                type: "post",
                dataType: "json",
                url: url,
                data: post,
                async: false,
                success: function (data) {
                    if (_.check4errors(data, false))
                        return;
                    if (callBack) callBack(data);
                    dlg.dialog("destroy").detach();
                },
                error: function () {
                    _.alert(_.label("Unknown error."));
                }
            });
            return false;
        },
        dlg = _.dialog(_.label(labels.title), html, {
            width: 351,
            buttons: [{
                    text: _.label("OK"),
                    icons: {
                        primary: "ui-icon-check"
                    },
                    click: function () {
                        submit();
                    }
                },
                {
                    text: _.label("Cancel"),
                    icons: {
                        primary: "ui-icon-closethick"
                    },
                    click: function () {
                        $(this).dialog('destroy').detach();
                    }
                }
            ]
        }),

        field = dlg.find('[type="text"]');

    field.transForm().attr('value', inputValue).css('width', 310);
    dlg.find('form').submit(submit);

    if (!selectAll && /^(.+)\.[^\.]+$/.test(inputValue))
        field.selection(0, inputValue.replace(/^(.+)\.[^\.]+$/, "$1").length);
    else {
        field.get(0).focus();
        field.get(0).select();
    }
};

_.imageCropDialog = function (post, labels, callBack) {
    var w = $(window),
        w_w = w.width(),
        w_h = w.height(),
        weig = (w_w * .60),
        heig = (w_h * .85);
    if (weig > 550)
        weig = 540;

    if (heig > 495)
        heig = 494;
    var html = '<div><img onload="loadCrop();" src="' + post.upload + '/' + post.dir + '/' + post.file + '" id="RecortarImagen" class="img"/><br></div>',
        dlg = _.dialog(_.label(labels.title), html, {
            width: weig,
            height: heig,
            buttons: [{
                    text: _.label("Crop"),
                    icons: {
                        primary: "ui-icon-check"
                    },
                    click: function () {
                        if (callBack) callBack();
                        dlg.dialog("destroy").detach();
                    }
                },
                {
                    text: _.label("Cancel"),
                    icons: {
                        primary: "ui-icon-closethick"
                    },
                    click: function () {
                        $(this).dialog('destroy').detach();
                    }
                }
            ]
        });
}

_.imageEditDialog = function (post, labels, callBack) {
    var w = $(window),
        w_w = w.width(),
        w_h = w.height(),
        weig = (w_w * 0.9), // Puedes ajustar estos valores
        heig = (w_h * 0.9);
    // URL completa de la imagen
    var imageUrl = post.upload + '/' + post.dir + '/' + post.file;
    // HTML del editor
    var html = `<div id="filerobot-editor-container" style="width:100%; height:${heig - 100}px;"></div>`;
    var dlg = _.dialog(_.label(labels.title), html, {
        width: weig,
        height: heig,
        modal: true,
        buttons: [{
            text: _.label("Cancelar"),
            icons: {
                primary: "ui-icon-closethick"
            },
            click: function () {
                $(this).dialog('destroy').detach();
            }
        }],
        open: function () {
            initFilerobotEditor(imageUrl, post.file, callBack);
        }
    });
}

_.viewOfficeDialog = function (post, labels) {
    var w = $(window),
        w_w = w.width(),
        w_h = w.height(),
        weig = (w_w * .80),
        heig = (w_h * .95),
        html = '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' + post + '" width="100%" height="100%"></iframe>',
        dlg = _.dialog(_.label(labels.title), html, {
            width: weig,
            height: heig,
            buttons: [{
                text: _.label(labels.button),
                icons: {
                    primary: "ui-icon-closethick"
                },
                click: function () {
                    $(this).dialog('destroy').detach();
                }
            }]
        });
}