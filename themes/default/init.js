// Preload some images
$.each([
    "loading.gif",
    "ui-icons_777777_256x240.png",
    "ui-icons_777620_256x240.png",
    "ui-icons_ffffff_256x240.png"
], function(i, img) {
    new Image().src = "themes/default/img/" + img;
});
