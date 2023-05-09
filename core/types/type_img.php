<?php

/** 
 *   @desc Image detection class
 *   @package KCFinder
 *   @version 3.12
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

namespace kcfinder;

class type_img
{

    public function checkFile($file, array $config)
    {

        $driver = isset($config['imageDriversPriority']) ? image::getDriver(explode(" ", $config['imageDriversPriority'])) : "gd";
        $img = image::factory($driver, $file);

        if ($img->initError)
            return "Unknown image format/encoding.";

        return true;
    }
}
