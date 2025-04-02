<?php

/** This file is part of KCFinder project
  *
  *   @desc Join all CSS files from current directory
  *   @package kcfinder-Resurrected
  *   @version 4.0
  *   @copyright 2010-2025 KCFinder Resurrected
  *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
  *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
  */

namespace kcfinder;

chdir("..");
require "core/autoload.php";
$min = new minifier("css");
$min->minify("cache/base.css");
