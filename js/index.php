<?php

/** 
  *   @desc Join all JavaScript files from current directory
  *   @package KCFinder
  *   @version 3.80
  *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
  *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
  */

namespace kcfinder;

chdir("..");
require "core/autoload.php";
$min = new minifier("js");
$min->minify("cache/base.js");
