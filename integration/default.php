<?php

/** 
 *	@desc CMS integration code: default
 *	@package kcfinder-Resurrected
 *  @version 4.0
 *	@license http://opensource.org/licenses/GPL-3.0 GPLv3
 *	@license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

class Default_kcfinderPlugin
{
    protected static $authenticated = false;

    public static function checkAuth()
    {
        $current_cwd = getcwd();
        $auth = true; // simula tu que has iniciado session
        // Start session if it is not already started
        if (!session_id())
            session_start();

        if (!self::$authenticated) {
            // Verifica autenticación y configura KCFinder
            $site_path = realpath(ROOT . 'upload');
            if ($auth) {
                self::$authenticated = true;
                $_SESSION['KCFINDER'] = array();
                $_SESSION['KCFINDER']['disabled'] = false;
                $_SESSION['KCFINDER']['allowExts'] = 'png jpg jpeg gif';
                $_SESSION['KCFINDER']['allowMimeTypes'] = ['image/png', 'image/jpeg', 'image/gif'];
                //$_SESSION['KCFINDER']['_check4htaccess'] = true;
                $_SESSION['KCFINDER']['uploadDir'] = $site_path;  // ruta de subida de archivos
                $_SESSION['KCFINDER']['uploadURL'] = '/upload/'; // Url de acceso a los recursos
                $_SESSION['KCFINDER']['types'] = array('media' => "*");
                //$_SESSION['KCFINDER']['thumbsDir'] = "/.thumbs";  // directorio de las miniaturas
                $_SESSION['KCFINDER']['lang'] = 'es';
                $_SESSION['KCFINDER']['access'] = array('files' => array('upload' => true, 'delete' => true, 'copy' => false, 'move' => true, 'rename' => true), 'dirs' => array('create' => true, 'delete' => true, 'rename' => true));
                // CSRF Token
                $token = bin2hex(random_bytes(100));
                $_SESSION['kcCsrf'] = $token;
                setcookie('kcCsrf', $token, 0, '/', HOST);
            } else {
                // Limpia la sesión de KCFinder
                unset($_SESSION['kcCsrf'], $_SESSION['KCFINDER']);
                if (isset($_COOKIE['kcCsrf'])) {
                    setcookie('kcCsrf', '', time() - 3600, '/', HOST);
                }
            }
        }
        chdir($current_cwd);
        return self::$authenticated;
    }
}
