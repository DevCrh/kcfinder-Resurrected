/** 
 *   @desc Image editor
 *   @package kcfinder-Resurrected
 *   @version 4.0
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */
_.editImage = function (file) {
    var url;
    _.imageEditDialog({
            upload: _.uploadURL,
            dir: _.dir,
            file: encodeURIComponent(file.name)
        }, {
            title: encodeURIComponent(file.name)
        },
        function (imageInfo) { //callback 
            var data = new FormData();
            url = _.getURL('editimage');
            // Agregar los datos básicos
            data.append('file', file.name);
            data.append('dir', _.dir);
            data.append('base64', imageInfo.imageBase64);

            // Metadatos adicionales
            data.append('ext', imageInfo.extension);
            data.append('width', imageInfo.width);
            data.append('height', imageInfo.height);
            data.append('quality', imageInfo.quality);

            // Token CSRF
            data.append('csrf_token', csrfToken);

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                contentType: false, // Crucial para FormData
                processData: false, // Crucial para FormData
                cache: false,
                beforeSend: function () {
                    $('#loading').html(_.label("Guardando imagen...")).show();
                },
                success: function (resp) {
                    if (_.check4errors(resp)) {
                        return;
                    }
                    _.refresh();
                },
                error: function (xhr) {
                    console.error("Error en AJAX:", xhr.responseText);
                    _.alert(_.label("Error al guardar: " + xhr.responseText));
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        }
    );
    return false;
};

/**
 * Iniciar Filerobot
 * 
 */
function initFilerobotEditor(imageUrl, file, onSaveCallback) {
    var checkInterval = setInterval(function () {
        if (typeof FilerobotImageEditor !== 'undefined') {
            clearInterval(checkInterval);
            // Extraer nombre y extensión del archivo
            var fileName = file.substring(0, file.lastIndexOf('.')) || file;
            var fileExt = file.substring(file.lastIndexOf('.') + 1) || 'jpg';
            // Convertir extensión a formato válido para Filerobot
            var validExtensions = ['jpeg', 'jpg', 'png', 'webp'];
            var outputFormat = validExtensions.includes(fileExt.toLowerCase()) ? fileExt.toLowerCase() : 'jpeg';
            var config = {
                source: imageUrl,
                onSave: onSaveCallback,
                tools: [
                    'crop',
                    'rotate',
                    'flip',
                    'sharpen',
                    'brightness',
                    'contrast',
                    'hue',
                    'saturation',
                    'noise',
                    'filter'
                ],
                language: 'es',
                translations: {
                    save: 'Guardar',
                    cancel: 'Cancelar',
                    close: 'Cerrar',
                    crop: 'Recortar',
                    filters: 'Filtros',
                    adjust: 'Ajustes',
                    apply: 'Aplicar'
                },
                defaultSavedImageName: fileName + '_editada',
                defaultSavedImageType: outputFormat,
                quality: 95
            };
            const container = document.getElementById("filerobot-editor-container");
            const ImageEditor = new window.FilerobotImageEditor(container, config);
            ImageEditor.render({
                onClose: (closingReason) => {
                    console.log('Closing reason', closingReason);
                    filerobotImageEditor.terminate();
                },
                // additional config provided while rendering
                observePluginContainerSize: true
            });
        }
    }, 100);
}