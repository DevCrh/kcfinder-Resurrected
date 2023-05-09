<?php

// Puerto y la URL del sitio
define('PROTOCOL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"); // Detectar si está en HTTPS o HTTP
define('HOST', $_SERVER['HTTP_HOST'] === 'localhost' ? 'localhost' : $_SERVER['HTTP_HOST']); // Dominio o host localhost.com tudominio.com
define('CUR_PAGE', PROTOCOL . '://' . HOST);

$origin = parse_url($_SERVER['HTTP_REFERER']);
$valid_domains = array("localhost", "127.0.0.1");

if (!in_array($origin['host'], $valid_domains)) {
    //echo sprintf("Origin domain is <b>%s</b>, with the HTTP referrer <b>%s</b><br>", $origin['host'], $_SERVER['HTTP_REFERER']);
    http_response_code(403);
    die('Warning, access forbidden!');
}

/**
 * Sobre escriba la configuración de config sin modificar el archivo config
 */
$_LOCALS = array();
