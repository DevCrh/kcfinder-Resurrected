<?php

/** 
 *   @desc MIME type detection class
 *   @package kcfinder-Resurrected
 *   @version 4.0
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

namespace kcfinder;

class type_mime
{

    public function checkFile($file, array $config)
    {
        if (!class_exists("finfo"))
            return "Fileinfo PECL extension is missing.";

        if (!isset($config['params']))
            return "Undefined MIME types.";

        $finfo = strlen($config['mime_magic'])
            ? new \finfo(FILEINFO_MIME, $config['mime_magic'])
            : new \finfo(FILEINFO_MIME);
        if (!$finfo)
            return "Opening fileinfo database failed.";

        $type = $finfo->file($file);
        $type = substr($type, 0, strrpos($type, ";"));

        $mimes = $config['params'];
        if (substr($mimes, 0, 1) == "!") {
            $mimes = trim(substr($mimes, 1));
            return in_array($type, explode(" ", $mimes))
                ? "You can't upload such files."
                : true;
        }

        return !in_array($type, explode(" ", $mimes))
            ? "You can't upload such files."
            : true;
    }
}
