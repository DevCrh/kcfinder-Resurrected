<?php

/** 
 *   @desc Browser calling script
 *   @package KCFinder
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

require "core/bootstrap.php";
$browser = "kcfinder\\browser";
$browser = new $browser();      
$browser->action();
