<?php

/** 
 *   @desc Base configuration file
 *   @package KCFinder
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
        'flash'   =>  "swf",
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
    'dirPerms' => 0755,
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
     * '*' = Se permiten todas las extensiones
     * '!ext' = Se bloquea la extension
     * 'ext' = la extension se permite
     * Por defecto cuando hay una extension permitida 'ext' todas las demás extensiones son bloqueadas
     * amenos que se incluya '*', en todo caso se permiten todas.
     * 
     * cuando se usa '*' (todas las extensiones permitidas), puede bloquear una extension en concreto
     * ejemplo '* !html' (se permiten todas las extensiones excepto html)
     * 
     * !Tenga cuidado con el manejo de extensiones se recomienda encarecidamente que solo
     * !agregue las extensiones que va a utilizar por cuestiones de seguridad, nunca permita todas
     * !las extensiones.
     * !Hay varios Exploit que se aprovechan de tener permitidas todas las extensiones
     * 
     */

    'allowExts' => "png jpg jpeg",

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

    '_sessionVar' => "KCFINDER",
    '_check4htaccess' => true,
    '_normalizeFilenames' => true,
    '_dropUploadMaxFilesize' => 10485760, //9MB
    '_appendUniqueSuffixOnOverwrite' => true,	// If it is set to true files will not be overwritten and instead (upon coflict) a numeric suffix will be appended to uploaded file name.
    //'_tinyMCEPath' => "/tiny_mce",
);

/** Incluir una configuración local, si la hay 
 * Este es el mejor lugar para anular la configuración predeterminada y agregar cualquier 
 * Lógica de negocio a la configuración. No es recomendable hackear el 
 * Estructura de configuración principal, aunque puede proporcionar una forma rápida e indolora 
 * solución :-) 
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
