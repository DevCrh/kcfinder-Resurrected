4.00-test1: 2025-04-01
* Agregado CSRF Token a todos los endpoint de core/browser.php
* Agregado CSRF Token a core/uploader.php
* Algunos problemas de seguridad importantes resueltos
* Agregado editor de imágenes subidas Filerobot
* Mejoras en verificación de archivos subidos
* JQuery actualizado a v3.7.1
* Agregado ejemplo de para la nueva integración (index.php)
* Mejoras en dragupload desde sitios externos
* Múltiples mejoras de código
* Eliminación de código obsoleto JS y referencias a navegadores descontinuados

3.80-test1: 2023-05-07
* jQuery y jQuery Ui actualizados a la ultima version
* Cambios en el manejo de extensiones
* XSS vulnerability (CVE-2019-14315) corregida
* Corrección de agotamiento memoria
* Código obsoleto actualizado
* Mejoras en CSRF
* pequeños errores corregidos
* Correcciones de seguridad en general
* Recorte de imágenes agregado

3.20-test2: 2014-08-24
----------------------
* jQuery adapter added
* Session handling improvements. Now `_sessionVar` option could get arrays by reference
* Session related ini options in `conf/config.php` are removed and no more supported
* Removed redundant closing tags in PHP files

3.20-test1: 2014-08-19
----------------------
* "`DOCUMENT_ROOT` is symlink" bugfix
* Uniform is replaced with my alternative - transForm (http://jquery.sunhater.com/transForm)
* Improved image viewer
* Improved drag & drop upload functionality. Progress bars in a dialog box.
* Supports drag & drop images from external html page
* Language dropdown in settings toolbar
* No border-radius when scrollbar(s) exists into files and folders panes
* `_dropUploadMaxFilesize` option is added. (Drag & dropping bigger files cause crash the web browser)
* `composer.json` file added
* 4 new labels to translate

3.12: 2014-07-09
----------------
* XSS security fix
* Performance fix
* taphold event added. Emulates right-click on touchscreen devices
* Click with `Shift` key functionality added
* Minor fixes

3.11: 2014-04-21
----------------
* "Unknown error." fixes when using `_normalizeFilenames` setting and upon new folder creation

3.10: 2014-04-16
----------------
* Important secirity fixes

3.0: 2014-04-08
---------------
* Minor fixes

3.0-pre1: 2014-04-02
--------------------
* Now KCFinder requires PHP >= 5.3 becouse of using namespace: `kcfinder`
* Support CSS & JavaScript minifier (on the fly)
* jQuery UI & Uniform support. New theme & theme engine (old themes are not supported)
* Improvements in JavaScript code to be well compressed and faster
* Keep PNG transparency in generated thumbnails
* New image viewer

2.54: 2014-03-12
----------------
* Performance fix only

2.53: 2014-02-22
----------------
* Session start fix
* TinyMCE 4 support

2.52: 2014-01-20
----------------
* Various image drivers support (`gd`, `imagemagick`, `graphicsmagic`)
* Auto-rotate images based on EXIF data
* PNG watermark support

2.51: 2010-08-25
----------------
* Drag and drop uploading plugin - big fixes
* Cookies problem when using single words or IPs as hostname resolved
* Vietnamese localization

2.5: 2010-08-23
---------------
* Drupal module support
* Drag and drop uploading plugin
* Two more language labels
* Localhost cookies bugfix
* Renaming current folder bugfix
* Small bugfixes

2.41: 2010-07-24
----------------
* Directory types engine improvement
* New `denyExtensionRename` config setting added

2.4: 2010-07-20
---------------
* Online checking if new version is released in About box. To use this feature you should to have `curl`, `http` or `socket` extension, or `allow_url_fopen` ini setting should be `on`
* New `denyUpdateCheck` config setting added
* New `dark` theme added (made by Dark Preacher)
* Additional `theme` GET parameter to choose a theme from URL
* Thumbnails loading improvement
* Some changes in Oxygen CSS theme
* Replace `alert()` and `confirm()` JavaScript functions with good-looking boxes
* Safari 3 right-click fix
* Small bugfixes

2.32: 2010-07-11
----------------
* `filenameChangeChars` and `dirnameChangeChars` config settings added
* Content-Type header fix for `css.php`, `js_localize.php` and `js/browser/joiner.php`
* CKEditorFuncNum with index `0` bugfix
* Session save handler example in `core/autoload.php`

2.31: 2010-07-01
----------------
* Proportional uploaded image resize bugfix
* Slideshow bugfixes
* Other small bugfixes

2.3: 2010-06-25
---------------
* Replace XML Ajax responses with JSON
* Replace old `readonly` config option with advanced `access` option. PLEASE UPDATE YOUR OLD CONFIG FILE!!!
* Slideshow images in current folder using arrow keys
* Multipe files upload similar to Facebook upload (not works in IE!)
* Option to set protocol, domain and port in `uploadURL` setting
* Bugfixes

2.21: 2010-11-19
----------------
* Bugfixes only

2.2: 2010-07-27
---------------
* Many bugfixes
* Read-only config option

2.1: 2010-07-04
---------------
* Endless JavaScript loop on KCFinder disable bugfix
* New config setting whether to generate `.htaccess` file in upload folder
* Upload to specified folder from CKEditor & FCKeditor direct upload dialog
* Select multiple files bugfixes

2.0: 2010-07-01
---------------
* Brand new core
* Option to resize `files/folders` panels with mouse drag
* Select multiple files with `Ctrl` key
* Return list of files to custom integrating application
* Animated folder tree
* Directory Type specific configuration settings
* Download multiple files or a folder as ZIP file

1.7: 2010-06-17
---------------
* Maximize toolbar button
* Clipboard for copying and moving multiple files
* Show warning if the browser is not capable to display KCFinder
* Google Chrome Frame support for old versions of Internet Explorer

1.6: 2010-06-02
---------------
* Support of Windows Apache server
* Support of Fileinfo PHP extension to detect mime types (`*mime` directory type)
* Option to deny globaly some dangerous extensions like `exe`, `php`, `pl`, `cgi` etc
* Check for `denied` file extension on file rename
* Disallow to upload hidden files (with names begins with `.`)
* Missing last character of filenames without extension bugfix
* Some small bugfixes

1.5: 2010-05-30
---------------
* Filenames with spaces download bugfix
* FCKEditor direct upload bugfix
* Thumbnail generation bugfixes

1.4: 2010-05-24
---------------
* Client-side caching bugfix
* Custom integrations - `window.KCFinder.callBack()`
* Security fixes

1.3: 2010-05-06
---------------
* Another session bugfix. Now session configuratin works!
* Show filename by default bugfix
* Loading box on top right corner

1.2: 2010-05-03
---------------
* Thumbnail generation bugfix
* Session bugfix
* other small bugfixes
