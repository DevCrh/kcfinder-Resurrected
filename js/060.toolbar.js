/** 
 *   @desc Toolbar functionality
 *   @package kcfinder-Resurrected
 *   @version 4.0
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

_.initToolbar = function () {
    $('#toolbar').disableTextSelect();
    $('#toolbar a').click(function () {
        _.menu.hide();
    });

    if (!$.$.kuki.isSet('displaySettings'))
        $.$.kuki.set('displaySettings', "off");

    if ($.$.kuki.get('displaySettings') == "on") {
        $('#toolbar a[href="kcact:settings"]').addClass('selected');
        $('#settings').show();
        _.resize();
        $('#lang').transForm();
    }

    $('#toolbar a[href="kcact:settings"]').click(function () {
        var jSettings = $('#settings');
        if (jSettings.css('display') == "none") {
            $(this).addClass('selected');
            $.$.kuki.set('displaySettings', "on");
            jSettings.show();
            _.fixFilesHeight();
            if (!jSettings.find('.tf-select #lang').get(0))
                $('#lang').transForm();
        } else {
            $(this).removeClass('selected');
            $.$.kuki.set('displaySettings', "off");
            jSettings.hide();
            _.fixFilesHeight();
        }
        return false;
    });

    $('#toolbar a[href="kcact:refresh"]').click(function () {
        _.refresh();
        return false;
    });

    $('#toolbar a[href="kcact:maximize"]').click(function () {
        _.maximize(this);
        return false;
    });

    $('#toolbar a[href="kcact:about"]').click(function () {
        const now = new Date();
        var html = '<div class="box about">' +
            '<div class="head">KCFinder ' + _.version + '</div>';
        html +=
            '<div>' + _.label("Licenses:") + ' <a href="http://opensource.org/licenses/GPL-3.0" target="_blank">GPLv3</a> & <a href="http://opensource.org/licenses/LGPL-3.0" target="_blank">LGPLv3</a></div>' +
            '<div>Esta bifurcación es administrada por DevCrh &copy; ' + now.getFullYear() + '</div>' +
            '<div></div>';
        var dlg = _.dialog(_.label("About"), html, {
            width: 301
        });
        return false;
    });

    _.initUploadButton();
};

_.initUploadButton = function () {
    var btn = $('#toolbar a[href="kcact:upload"]');
    if (!_.access.files.upload) {
        btn.hide();
        return;
    }
    var top = btn.get(0).offsetTop,
        width = btn.outerWidth(),
        height = btn.outerHeight(),
        jInput = $('#upload input');

    $('#toolbar').prepend('<div id="upload" style="top:' + top + 'px;width:' + width + 'px;height:' + height + 'px"><form enctype="multipart/form-data" method="post" target="uploadResponse" action="' + _.getURL('upload') + '"><input type="file" name="upload[]" onchange="_.uploadFile(this.form)" style="height:' + height + 'px" multiple="multiple" /><input type="hidden" name="csrf_token" value="' + csrfToken + '"></form></div>');
    jInput.css('margin-left', "-" + (jInput.outerWidth() - width));
    $('#upload').mouseover(function () {
        $('#toolbar a[href="kcact:upload"]').addClass('hover');
    }).mouseout(function () {
        $('#toolbar a[href="kcact:upload"]').removeClass('hover');
    });
};

_.uploadFile = function (form) {
    // Verifica si la carpeta actual tiene permisos de escritura
    if (!_.dirWritable) {
        // Muestra un mensaje de error si no se puede escribir
        _.alert(_.label("Cannot write to upload folder."));
        // Elimina el componente de carga y reinicia el botón de subida
        $('#upload').detach();
        _.initUploadButton();
        return; // Termina la ejecución de la función
    }

    // Crea un objeto FormData con los datos del formulario para realizar una solicitud AJAX
    var post = new FormData(form);
    post.append('dir', _.dir);
    $.ajax({
        url: $(form).attr('action'), // URL del formulario
        type: 'POST', // Método HTTP para enviar los datos
        data: post, // Datos a enviar
        processData: false, // Indica que no se deben procesar los datos (se usan directamente)
        contentType: false, // Desactiva el encabezado 'Content-Type' predeterminado
        cache: false, // No almacena en caché la respuesta

        // Antes de enviar la solicitud, muestra un mensaje de carga
        beforeSend: function () {
            $('#loading').html(_.label("Uploading file...")).show();
        },
        // En caso de éxito en la subida
        success: function (data) {
            // Divide la respuesta del servidor por líneas
            var response = data.split("\n"),
                selected = [], // Archivos seleccionados correctamente
                errors = []; // Errores encontrados

            // Procesa cada línea de la respuesta
            $.each(response, function (i, row) {
                // Si la línea comienza con "/", es un archivo subido exitosamente
                if (row.substr(0, 1) == '/')
                    selected[selected.length] = row.substr(1, row.length - 1);
                else
                    errors[errors.length] = row; // Si no, se considera un error
            });

            // Si hay errores, los une en un mensaje y los muestra
            if (errors.length) {
                errors = errors.join("\n");
                if (errors.replace(/^\s+/g, "").replace(/\s+$/g, "").length)
                    _.alert(errors);
            }

            // Si no hay archivos seleccionados, establece 'selected' como null
            if (!selected.length)
                selected = null;

            // Actualiza la vista con los archivos subidos
            _.refresh(selected);
            // Elimina el componente de carga
            $('#upload').detach();
        },

        // En caso de error en la solicitud AJAX
        error: function (xhr) {
            // Muestra un mensaje de error genérico
            _.alert(_.label("No file was uploaded."));
            // Opcionalmente, imprime el error en la consola para depuración
            console.log(xhr.responseText);
        },

        // Siempre se ejecuta al finalizar la solicitud (éxito o error)
        complete: function () {
            // Oculta el mensaje de carga
            $('#loading').hide();
            // Reinicia el botón de subida y actualiza la vista
            _.initUploadButton();
            _.refresh();
        }
    });
};

_.maximize = function (button) {

    // TINYMCE 3
    if (_.opener.name == "tinymce") {

        var par = window.parent.document,
            ifr = $('iframe[src*="browse.php?opener=tinymce&"]', par),
            id = parseInt(ifr.attr('id').replace(/^mce_(\d+)_ifr$/, "$1")),
            win = $('#mce_' + id, par);

        if ($(button).hasClass('selected')) {
            $(button).removeClass('selected');
            win.css({
                left: _.maximizeMCE.left,
                top: _.maximizeMCE.top,
                width: _.maximizeMCE.width,
                height: _.maximizeMCE.height
            });
            ifr.css({
                width: _.maximizeMCE.width - _.maximizeMCE.Hspace,
                height: _.maximizeMCE.height - _.maximizeMCE.Vspace
            });

        } else {
            $(button).addClass('selected')
            _.maximizeMCE = {
                width: parseInt(win.css('width')),
                height: parseInt(win.css('height')),
                left: win.position().left,
                top: win.position().top,
                Hspace: parseInt(win.css('width')) - parseInt(ifr.css('width')),
                Vspace: parseInt(win.css('height')) - parseInt(ifr.css('height'))
            };
            var width = $(window.top).width(),
                height = $(window.top).height();
            win.css({
                left: $(window.parent).scrollLeft(),
                top: $(window.parent).scrollTop(),
                width: width,
                height: height
            });
            ifr.css({
                width: width - _.maximizeMCE.Hspace,
                height: height - _.maximizeMCE.Vspace
            });
        }

        // TINYMCE 4
    } else if (_.opener.name == "tinymce4") {

        var par = window.parent.document,
            ifr = $('iframe[src*="browse.php?opener=tinymce4&"]', par).parent(),
            win = ifr.parent();

        if ($(button).hasClass('selected')) {
            $(button).removeClass('selected');

            win.css({
                left: _.maximizeMCE4.left,
                top: _.maximizeMCE4.top,
                width: _.maximizeMCE4.width,
                height: _.maximizeMCE4.height
            });

            ifr.css({
                width: _.maximizeMCE4.width,
                height: _.maximizeMCE4.height - _.maximizeMCE4.Vspace
            });

        } else {
            $(button).addClass('selected');

            _.maximizeMCE4 = {
                width: parseInt(win.css('width')),
                height: parseInt(win.css('height')),
                left: win.position().left,
                top: win.position().top,
                Vspace: win.outerHeight(true) - ifr.outerHeight(true) - 1
            };

            var width = $(window.top).width(),
                height = $(window.top).height();

            win.css({
                left: 0,
                top: 0,
                width: width,
                height: height
            });

            ifr.css({
                width: width,
                height: height - _.maximizeMCE4.Vspace
            });
        }

        // PUPUP WINDOW
    } else if (window.opener) {
        window.moveTo(0, 0);
        width = screen.availWidth;
        height = screen.availHeight;
        if ($.agent.opera)
            height -= 50;
        window.resizeTo(width, height);

    } else {
        if (window.parent) {
            var el = null;
            $(window.parent.document).find('iframe').each(function () {
                if (this.src.replace('/?', '?') == window.location.href.replace('/?', '?')) {
                    el = this;
                    return false;
                }
            });

            // IFRAME
            if (el !== null)
                $(el).toggleFullscreen(window.parent.document);

            // SELF WINDOW
            else
                $('body').toggleFullscreen();

        } else
            $('body').toggleFullscreen();
    }
};

_.refresh = function (selected) {
    _.fadeFiles();
    $.ajax({
        type: "post",
        dataType: "json",
        url: _.getURL("chDir"),
        data: {
            csrf_token: csrfToken,
            dir: _.dir
        },
        async: false,
        success: function (data) {
            if (_.check4errors(data)) {
                $('#files > div').css({
                    opacity: "",
                    filter: ""
                });
                return;
            }
            _.dirWritable = data.dirWritable;
            _.files = data.files ? data.files : [];
            _.orderFiles(null, selected);
            _.statusDir();
        },
        error: function () {
            $('#files > div').css({
                opacity: "",
                filter: ""
            });
            $('#files').html(_.label("Unknown error."));
        }
    });
};