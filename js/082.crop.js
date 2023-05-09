/** 
 *   @desc Image Crop
 *   @package KCFinder
 *   @version 3.80
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */
var size;
_.cropImage = function (file) {
    var url, data = new FormData();
    _.imageCropDialog({
            upload: _.uploadURL,
            dir: _.dir,
            file: encodeURIComponent(file.name)
        }, {
            title: encodeURIComponent(file.name)
        },
        function () {
            url = _.getURL('crop');
            data.append('file', file.name);
            data.append('dir', _.dir);
            data.append('x', size.x);
            data.append('y', size.y);
            data.append('w', size.w);
            data.append('h', size.h);
            $.ajax({
                type: "post",
                url: url,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                data: data,
                beforeSend: function () {
                    $('#loading').html(_.label("Croping file...")).show();
                },
                success: function (resp) {
                    if (_.check4errors(resp, false))
                        return;

                    _.refresh();
                },
                error: function (xhr) {
                    _.alert(_.label("Unknown error."));
                    console.log(xhr.responseText);
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        }
    );
    return false;
};

function loadCrop() {
    $('#RecortarImagen').Jcrop({
        setSelect: [0, 0, 150, 180],
        boxWidth: 500,
        boxHeight: 380,
        //aspectRatio: 1,
        onSelect: function (c) {
            size = {
                x: c.x,
                y: c.y,
                w: c.w,
                h: c.h
            };
        }
    });
}