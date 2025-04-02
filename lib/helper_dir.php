<?php

/** 
 *   @desc Directory helper class
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

namespace kcfinder;

class dir
{

    /** Comprueba si el directorio dado es realmente escribible. El PHP estándar 
     * La función is_writable() no funciona correctamente en servidores Windows
     * @param string $dir
     * @return bool 
     */

    static function isWritable($dir)
    {
        $dir = path::normalize($dir);
        if (!is_dir($dir))
            return false;
        $i = 0;
        do {
            $file = "$dir/is_writable_" . md5($i++);
        } while (file_exists($file));
        if (!@touch($file))
            return false;
        unlink($file);
        return true;
    }

    /** Elimina recursivamente el directorio dado. Devuelve TRUE en éxito. 
     * Si $firstFailExit parámetro es true (valor predeterminado), el método devuelve el 
     * ruta al primer archivo o directorio fallido que no se puede eliminar. 
     * Si $firstFailExit es false, el método devuelve una matriz con error 
     * archivos y directorios que no se pueden eliminar. El tercer parámetro 
     * $failed se utiliza únicamente para uso interno.
     * @param string $dir
     * @param bool $firstFailExit
     * @param array $failed
     * @return mixed */

    static function prune($dir, $firstFailExit = true, array $failed = null)
    {
        if ($failed === null) $failed = array();
        $files = self::content($dir);
        if ($files === false) {
            if ($firstFailExit)
                return $dir;
            $failed[] = $dir;
            return $failed;
        }

        foreach ($files as $file) {
            if (is_dir($file)) {
                $failed_in = self::prune($file, $firstFailExit, $failed);
                if ($failed_in !== true) {
                    if ($firstFailExit)
                        return $failed_in;
                    if (is_array($failed_in))
                        $failed = array_merge($failed, $failed_in);
                    else
                        $failed[] = $failed_in;
                }
            } elseif (!@unlink($file)) {
                if ($firstFailExit)
                    return $file;
                $failed[] = $file;
            }
        }

        if (!@rmdir($dir)) {
            if ($firstFailExit)
                return $dir;
            $failed[] = $dir;
        }

        return count($failed) ? $failed : true;
    }

    /** Obtenga el contenido del directorio dado. Devuelve una matriz con nombres de archivo. 
     * o FALSO en caso de fallo
     * @param string $dir
     * @param array $options
     * @return mixed */

    static function content($dir, array $options = [])
    {

        $defaultOptions = array(
            'types' => "all",   // Allowed: "all" or possible return values
            // of filetype(), or an array with them
            'addPath' => true,  // Whether to add directory path to filenames
            //'pattern' => '/./', // Regular expression pattern for filename
            'pattern' => '/^[^\.].+/', // Regular expression pattern for filename -- by default don't show hidden files
            'followLinks' => true
        );

        if (!is_dir($dir) || !is_readable($dir))
            return false;

        if (strtoupper(substr(PHP_OS, 0, 3)) == "WIN")
            $dir = str_replace("\\", "/", $dir);
        $dir = rtrim($dir, "/");

        $dh = @opendir($dir);
        if ($dh === false)
            return false;

        if ($options === null)
            $options = $defaultOptions;

        foreach ($defaultOptions as $key => $val)
            if (!isset($options[$key]))
                $options[$key] = $val;

        $files = array();
        while (($file = @readdir($dh)) !== false) {

            if (($file == '.') || ($file == '..') ||
                !preg_match($options['pattern'], $file)
            )
                continue;

            $fullpath = "$dir/$file";
            $type = filetype($fullpath);

            // If file is a symlink, get the true type of its destination
            if ($options['followLinks'] && ($type == "link"))
                $type = filetype(realpath($fullpath));

            if (($options['types'] === "all") || ($type === $options['types']) ||
                (is_array($options['types']) && in_array($type, $options['types']))
            )
                $files[] = $options['addPath'] ? $fullpath : $file;
        }
        closedir($dh);
        usort($files, array(__NAMESPACE__ . "\\dir", "fileSort"));
        return $files;
    }

    static function fileSort($a, $b)
    {
        if (function_exists("mb_strtolower")) {
            $a = mb_strtolower($a);
            $b = mb_strtolower($b);
        } else {
            $a = strtolower($a);
            $b = strtolower($b);
        }
        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
    }
}
