<?php namespace kcfinder\cms;

/** 
 *   @desc CMS integration code: BolmerCMS
 *   @package KCFinder
 *   @version 3.12
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */
class BolmerCMS{
    protected static $authenticated = false;
    static function checkAuth() {
        require_once __DIR__ . '/security.php';
        $current_cwd = getcwd();
        if ( ! self::$authenticated) {
            define('BOLMER_API_MODE', true);
            define('IN_MANAGER_MODE', true);
            $init = realpath(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/index.php");
            include_once($init);
            $type = getService('user', true)->getLoginUserType();
            if($type=='manager'){
                self::$authenticated = true;
                if (!isset($_SESSION['KCFINDER'])) {
                    $_SESSION['KCFINDER'] = array();
                }
                $_SESSION['KCFINDER']['disabled'] = false;
                $_SESSION['KCFINDER']['_check4htaccess'] = false;
                $_SESSION['KCFINDER']['uploadURL'] = '/assets/';
                $_SESSION['KCFINDER']['uploadDir'] = BOLMER_BASE_PATH.'assets/';
                $_SESSION['KCFINDER']['theme'] = 'default';
            } else {
                self::$authenticated = false;
                \kcfinder\revoke_access();
            }
        }

        chdir($current_cwd);
        return self::$authenticated;
    }
}
\kcfinder\cms\BolmerCMS::checkAuth();
