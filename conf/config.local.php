<?php

/**
 * en: Here you can override the config.php settings
 * es: Aquí puede sobrescribir la configuración de config.php
 */
$_LOCALS = array(
    // IMAGE SETTINGS
    'jpegQuality' => 90,
    'thumbsDir' => ".thumbs",

    'maxImageWidth' => 0,
    'maxImageHeight' => 0,

    'thumbWidth' => 100,
    'thumbHeight' => 100,

    'watermark' => "",
    'denyExtensionRename' => true,

    /**
     * en: Extensions allowed for upload
     * es: Extensiones permitidas para subir
     */
    'allowExts' => "",
    "allowMimeTypes" => "",

    // MISC SETTINGS
    'filenameChangeChars' => array(
        ' ' => "_",
        ':' => ".",
        'ñ' => "n",
        'Ñ' => "N",
        '-' => "_"
    ),
    'dirnameChangeChars' => array(
        ' ' => "_",
        ':' => ".",
        'ñ' => "n",
        'Ñ' => "N",
        '-' => "_"
    ),
    '_denyExtDomains' => true,
    '_allowDomains' => array("127.0.0.1", "localhost"),
    '_sessionCsrf' => true,

    /**
     * Cookies
     */
    'cookieDomain' => "127.0.0.1",
    'cookiePath' => "/",

    'access' => array(
        'files' => array(
            'upload' => false,
            'delete' => false,
            'copy'   => false,
            'move'   => false,
            'rename' => false
        ),
        'dirs' => array(
            'create' => false,
            'delete' => false,
            'rename' => false
        )
    ),
);
