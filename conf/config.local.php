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
    'allowExts' => "",
    
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
