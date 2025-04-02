<?php

/** 
 *   @desc Session class
 *   @package kcfinder-Resurrected
 *   @version 4.0
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

namespace kcfinder;

class session
{
    const SESSION_VAR = "_sessionVar";
    public $values;
    protected $config;

    public function __construct($configFile, $files)
    {
        // Puerto y la URL del sitio
        define('PROTOCOL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"); // Detectar si está en HTTPS o HTTP
        define('HOST', $_SERVER['HTTP_HOST'] === 'localhost' ? 'localhost' : $_SERVER['HTTP_HOST']); // Dominio o host localhost.com tudominio.com
        define('CUR_PAGE', PROTOCOL . '://' . HOST);

        // Start session if it is not already started
        if (!session_id())
            session_start();

        $config = require($configFile);

        // _sessionVar option is set
        if (isset($config[self::SESSION_VAR])) {
            $session = &$config[self::SESSION_VAR];

            // _sessionVar option is string
            if (is_string($session))
                $session = &$_SESSION[$session];

            if (!is_array($session))
                $session = array();

            // Use global _SESSION array if _sessionVar option is not set
        } else
            $session = &$_SESSION;

        // Security the session
        /**
         * Block external domains
         * '_denyExtDomains' => true
         */
        if ($config['_denyExtDomains']) {
            $origin = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : array('host' => null);
            if (!in_array($origin['host'], $config['_allowDomains'])) {
                if (!empty($files)) {
                    foreach ($files as $tmpFile) {
                        if (file_exists($tmpFile)) {
                            @unlink($tmpFile);
                        }
                    }
                }
                die('Warning, access forbidden!');
            }
        }

        /**
         * Csrf protected session
         */
        $cookie  = isset($_COOKIE['kcCsrf']) ? $_COOKIE['kcCsrf'] : '';
        $token = isset($_SESSION['kcCsrf']) ? $_SESSION['kcCsrf'] : '';
        if ($token !== $cookie) {
            if (!empty($files)) {
                foreach ($files as $tmpFile) {
                    if (file_exists($tmpFile)) {
                        @unlink($tmpFile);
                    }
                }
            }
            die('Not Valid Session Csrf Token');
        }

        // Load session configuration
        foreach ($config as $key => $val)
            $this->config[$key] = ((substr($key, 0, 1) != "_") && isset($session[$key]))
                ? $session[$key]
                : $val;

        // Session data goes to 'self' element
        if (!isset($session['self']))
            $session['self'] = array();
        $this->values = &$session['self'];
    }

    public function getConfig()
    {
        return $this->config;
    }
}
