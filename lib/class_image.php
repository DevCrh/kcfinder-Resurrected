<?php

/** 
 *   @desc Abstract image driver class
 *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
 *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
 */

namespace kcfinder;

abstract class image
{
  const DEFAULT_JPEG_QUALITY = 75;

  /** Image resource or object
   * @var mixed */
  protected $image;

  /** Image width in pixels
   * @var integer */
  protected $width;

  /** Image height in pixels
   * @var integer */
  protected $height;

  /** Init error
   * @var bool */
  protected $initError = false;

  /** Driver specific options
   * @var array */
  protected $options = array();


  /** Magic method which allows read-only access to all protected or private
   * class properties
   * @param string $property
   * @return mixed */

  final public function __get($property)
  {
    return property_exists($this, $property) ? $this->$property : null;
  }



  /** Constructor. El parámetro $image debe ser: 
   * 1. Una instancia de clase de controlador de imagen (instancia de copia). 
   * 2. Una imagen representada por el tipo de la propiedad $image 
   * (recurso u objeto).
   * 3. Una matriz con dos elementos. Primero - ancho, segundo - altura. 
   * Crea una imagen en blanco. 
   * 4. Una cadena de nombre de archivo. Obtener archivo de formulario de imagen. 
   * El segundo paramaeter se utiliza para pasar algunas opciones específicas del controlador de imagen
   * @param mixed $image
   * @param array $options */

  public function __construct($image, array $options = array())
  {
    $this->image = $this->width = $this->height = null;
    $imageDetails = $this->buildImage($image);

    if ($imageDetails !== false)
      list($this->image, $this->width, $this->height) = $imageDetails;
    else
      $this->initError = true;
    $this->options = $options;
  }


  /** Patrón de fábrica para cargar el controlador seleccionado. $image y $options se aprueban 
   * al constructor del controlador de imagen
   * @param string $driver
   * @param mixed $image
   * @return object */

  final static function factory($driver, $image, array $options = array())
  {
    $class = __NAMESPACE__ . "\\image_$driver";
    return new $class($image, $options);
  }

  /** Comprueba si se pueden utilizar los controladores del parámetro de matriz.
   * Regresa primero encontrado uno.
   * @param array $drivers
   * @return string */

  final static function getDriver(array $drivers = array('gd'))
  {
    foreach ($drivers as $driver) {
      if (!preg_match('/^[a-z0-9\_]+$/i', $driver))
        continue;
      $class = __NAMESPACE__ . "\\image_$driver";
      if (class_exists($class) && method_exists($class, "available")) {
        eval("\$avail = $class::available();");
        if ($avail) return $driver;
      }
    }
    return false;
  }


  /** Devuelve una matriz. Elemento 0 - recurso de imagen. Elemento 1 - ancho. Elemento 2 - altura.
   * Devuelve FALSE en caso de error.
   * @param array $image
   * @return array */

  final protected function buildImage($image)
  {
    $class = get_class($this);

    if ($image instanceof $class) {
      $width = $image->width;
      $height = $image->height;
      $img = $image->image;
    } elseif (is_array($image)) {
      list($key, $width) = old_each($image);
      list($key, $height) = old_each($image);
      $img = $this->getBlankImage($width, $height);
    } else
      $img = $this->getImage($image, $width, $height);

    return ($img !== false)
      ? array($img, $width, $height)
      : false;
  }


  /** Devuelve el ancho proporcional calculado a partir de la altura dada
   * @param integer $resizedHeight
   * @return integer */

  final public function getPropWidth($resizedHeight)
  {
    $width = round(($this->width * $resizedHeight) / $this->height);
    if (!$width) $width = 1;
    return $width;
  }


  /** Devuelve la altura proporcional calculada a partir de la anchura dada
   * @param integer $resizedWidth
   * @return integer */

  final public function getPropHeight($resizedWidth)
  {
    $height = round(($this->height * $resizedWidth) / $this->width);
    if (!$height) $height = 1;
    return $height;
  }

  /** Returns comparising memory limit with value
   * @param integer $val
   * @return boolean */
  final public function checkMemoryLimitOverload($val)
  {
    $memory_limit = ini_get('memory_limit');
    if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
      if ($matches[2] == 'M') {
        $memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
      } else if ($matches[2] == 'K') {
        $memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
      }
    }
    return $val >= $memory_limit;
  }

  /** Comprueba si PHP necesita algunas extensiones adicionales para usar el controlador de imagen. 
   * Éste El método estático debe implementarse en clases de controladores como abstract
   * methods
   * @return bool */
  static function available()
  {
    return false;
  }

  /** Comprueba si el archivo es una imagen. Este método estático debe implementarse en 
   * clases de controladores como métodos abstractos
   * @param string $file
   * @return bool */
  static function checkImage($file)
  {
    return false;
  }

  /** Cambiar el tamaño de la imagen. Debe devolver TRUE en el éxito o FALSE en el error
   * @param integer $width
   * @param integer $height
   * @return bool */
  abstract public function resize($width, $height);

  /** Resize image to fit in given resolution. Should returns TRUE on success
   * or FALSE on failure. If $background is set, the image size will be
   * $width x $height and the empty spaces (if any) will be filled with defined
   * color. Background color examples: "#5f5", "#ff67ca", array(255, 255, 255)
   * @param integer $width
   * @param integer $height
   * @param mixed $background
   * @return bool */
  abstract public function resizeFit($width, $height, $background = false);

  /** Resize and crop the image to fit in given resolution. Returns TRUE on
   * success or FALSE on failure
   * @param mixed $src
   * @param integer $offset
   * @return bool */
  abstract public function resizeCrop($width, $height, $offset = false);


  /** Rotate image
   * @param integer $angle
   * @param string $background
   * @return bool */
  abstract public function rotate($angle, $background = "#000000");

  abstract public function flipHorizontal();

  abstract public function flipVertical();

  /** Apply a PNG or GIF watermark to the image. $top and $left parameters sets
   * the offset of the watermark in pixels. Boolean and NULL values are possible
   * too. In default case (FALSE, FALSE) the watermark should be applyed to
   * the bottom right corner. NULL values means center aligning. If the
   * watermark is bigger than the image or it's partialy or fully outside the
   * image, it shoudn't be applied
   * @param string $file
   * @param mixed $top
   * @param mixed $left
   * @return bool */
  abstract public function watermark($file, $left = false, $top = false);

  /** Should output the image. Second parameter is used to pass some options like
   *   'file' - if is set, the output will be written to a file
   *   'quality' - compression quality
   * It's possible to use extra specific options required by image type ($type)
   * @param string $type
   * @param array $options
   * @return bool */
  abstract public function output($type = 'jpeg', array $options = array());

  /** This method should create a blank image with selected size. Should returns
   * resource or object related to the created image, which will be passed to
   * $image property
   * @param integer $width
   * @param integer $height
   * @return mixed */
  abstract protected function getBlankImage($width, $height);

  /** This method should create an image from source image. Only first parameter
   * ($image) is input. Its type should be filename string or a type of the
   * $image property. See the constructor reference for details. The
   * parametters $width and $height are output only. Should returns resource or
   * object related to the created image, which will be passed to $image
   * property
   * @param mixed $image
   * @param integer $width
   * @param integer $height
   * @return mixed */
  abstract protected function getImage($image, &$width, &$height);
}
