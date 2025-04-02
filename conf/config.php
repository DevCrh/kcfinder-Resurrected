<?php
/** 
 *   @desc Base configuration file
 *   @package kcfinder-Resurrected
 *   @version 4.0
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

$_DEFAULTS = array(
    // GENERAL SETTINGS
    'disabled' => true,
    'uploadURL' => "upload",
    'uploadDir' => "",
    'theme' => "default",
    'lang' => "es",

    'types' => array(
        // (F)CKEditor types
        'files'   =>  "",
        'flash'   =>  "",
        'images'  =>  "*img",

        // TinyMCE types
        'file'    =>  "",
        'media'   =>  "swf flv avi mpg mpeg qt mov wmv asf rm",
        'image'   =>  "*img",
    ),

    // IMAGE SETTINGS
    'imageDriversPriority' => "gd imagick gmagick",
    'jpegQuality' => 90,
    'thumbsDir' => ".thumbs",

    'maxImageWidth' => 0,
    'maxImageHeight' => 0,

    'thumbWidth' => 100,
    'thumbHeight' => 100,

    'watermark' => "",

    // DISABLE / ENABLE SETTINGS
    'denyZipDownload' => false,
    'denyExtensionRename' => true,

    // PERMISSION SETTINGS
    'dirPerms' => 0744, //0755,
    'filePerms' => 0644,
    'allowRecursiveDirCreate' => false,

    'access' => array(
        'files' => array(
            'upload' => true,
            'delete' => true,
            'copy'   => true,
            'move'   => true,
            'rename' => true
        ),
        'dirs' => array(
            'create' => true,
            'delete' => true,
            'rename' => true
        )
    ),

    /**
     * ------- Parámetro 'allowExts' ----------------------------           
     * '*' = es: Se permiten todas las extensiones en: All extensions are allowed
     * '!ext' = es: Se bloquea la extension en: The extension is blocked
     * 'ext' = es: la extension se permite en: extension is allowed
     * es: Por defecto cuando hay una extension permitida 'ext' todas las demás extensiones son bloqueadas
     * amenos que se incluya '*', en todo caso se permiten todas.
     * en: By default, when an 'ext' extension is allowed, all other extensions are blocked.
     * Unless '*' is included, all extensions are allowed.
     * 
     * es: cuando se usa '*' (todas las extensiones permitidas), puede bloquear una extension en concreto
     * en: When using '*' (all extensions allowed), you can block a specific extension
     * es: ejemplo '* !html' (se permiten todas las extensiones excepto html)
     * en: example '* !html' (all extensions are allowed except html)
     * 
     * es:
     * !Tenga cuidado con el manejo de extensiones se recomienda encarecidamente que solo
     * !agregue las extensiones que va a utilizar por cuestiones de seguridad, nunca permita todas
     * !las extensiones.
     * !Hay varios Exploit que se aprovechan de tener permitidas todas las extensiones
     * 
     * en:  
     * Be careful when handling extensions.
     * It is strongly recommended that you only add the extensions you intend to use for security
     * reasons; never allow all extensions. There are several exploits that take advantage of 
     * allowing all extensions.
     * 
     */

    'allowExts' => "png jpg jpeg",

    /**
     * ------- Parámetro 'allowMimeTypes' ---------------------------- 
     * es:
     * a partir de version 4.0
     * El sistema valida tanto la extensión como el tipo MIME real del archivo para garantizar
     * que coincidan con los formatos permitidos, proporcionando una capa adicional de seguridad
     * contra archivos maliciosos o corruptos
     * 
     * en:
     * As of version 4.0
     * The system validates both the file extension and the actual MIME type to ensure
     * that they match the allowed formats, providing an additional layer of security
     * against malicious or corrupted files
     */
    'allowMimeTypes' => [
        'image/png',
        'image/jpeg', // Cubre tanto .jpg como .jpeg
        'image/gif'
    ],
    // MISC SETTINGS
    'filenameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'dirnameChangeChars' => array(/*
        ' ' => "_",
        ':' => "."
    */),

    'mime_magic' => "",
    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',

    // THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION SETTINGS
    '_denyExtDomains' => true,
    '_allowDomains' => array("localhost", "127.0.0.1"),
    '_sessionVar' => "KCFINDER",
    '_sessionCsrf' => true,
    '_check4htaccess' => true,
    '_normalizeFilenames' => true,
    '_dropUploadMaxFilesize' => 10485760, //9MB
    '_appendUniqueSuffixOnOverwrite' => true,    // If it is set to true files will not be overwritten and instead (upon coflict) a numeric suffix will be appended to uploaded file name.
    //'_tinyMCEPath' => "/tiny_mce",
);

/** es: Incluir una configuración local, si la hay 
 * Este es el mejor lugar para anular la configuración predeterminada y agregar cualquier ajuste
 * No es recomendable modificar la Estructura de configuración principal.
 * en: Include a local configuration, if any
 * This is the best place to override the default configuration and add any settings
 * Modifying the main configuration structure is not recommended.
 */

if (file_exists(dirname(__FILE__) . '/config.local.php')) {
    require_once dirname(__FILE__) . '/config.local.php';
}

if (isset($_LOCALS)) {
    $_CONFIG = array_merge($_DEFAULTS, $_LOCALS);
} else {
    $_CONFIG = &$_DEFAULTS;
}

return $_CONFIG;
