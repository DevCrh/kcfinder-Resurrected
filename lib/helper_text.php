<?php

/** 
 *   @desc Text processing helper class
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

namespace kcfinder;

class text
{

  /** Reemplazar espacios en blanco repetidos por un solo espacio
   * @param string $string
   * @return string 
   */

  static function clearWhitespaces($string)
  {
    return trim(preg_replace('/\s+/s', " ", $string));
  }

  /** Normalizar la cadena para el valor del atributo HTML
   * @param string $string
   * @return string 
   */

  static function htmlValue($string)
  {
    return
      str_replace(
        '"',
        "&quot;",
        str_replace(
          "'",
          '&#39;',
          str_replace(
            '<',
            '&lt;',
            str_replace(
              '&',
              "&amp;",
              $string
            )
          )
        )
      );
  }

  /** Normalizar la cadena para el valor de cadena de JavaScript
   * @param string $string
   * @return string
   */

  static function jsValue($string)
  {
    return
      preg_replace(
        '/\r?\n/',
        "\\n",
        str_replace(
          '"',
          "\\\"",
          str_replace(
            "'",
            "\\'",
            str_replace(
              "\\",
              "\\\\",
              $string
            )
          )
        )
      );
  }
}
