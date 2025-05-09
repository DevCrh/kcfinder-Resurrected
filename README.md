# KCFinder Resurrected Ajax & PHP web file manager 

### Ultima Version 4.0 01/04/2025
#### Changelog
4.00-test1: 2025-04-01
* Agregado CSRF Token a todos los endpoint de core/browser.php
* Agregado CSRF Token a core/uploader.php
* Algunos problemas de seguridad importantes resueltos
* Agregado editor de imágenes subidas Filerobot
* Mejoras en verificación de archivos subidos
* JQuery actualizado a v3.7.1
* Agregado ejemplo de para la nueva integración (index.php)
* Mejoras en dragupload desde sitios externos
* Múltiples mejoras de código
* Eliminación de código obsoleto JS y referencias a navegadores descontinuados

### Description
KCFinder Resurrected, es la continuación del proyecto original kcfinder (https://github.com/sunhater/kcfinder) creado por Pavel Tzonkov, el cual esta sin soporte ni desarrollo desde Sep 2014, KCFinder Resurrected fue actualizado a la ultima version de jQuery y jQuery UI, se corrigieron algunos errores de seguridad críticos y se agregaron nuevas características como el poder recortar imágenes ya subidas desde el menu con Jcrop, ademas de contar con mejor soporte para versiones actuales de php y una renovación de la interfaz.

## Overview
KCFinder es un reemplazo gratuito de código abierto del administrador de archivos web CKFinder. Se puede integrar en los editores web CKEditor y TinyMCE WYSIWYG (o sus aplicaciones web personalizadas) para cargar y administrar imágenes y otros archivos que se pueden incrustar en el contenido HTML generado por un editor.

## Licenses
* GNU General Public License, version 3
* GNU Lesser General Public License, version 3

#### Credits
Original project by Pavel Tzonkov https://github.com/sunhater/kcfinder
Editor de imágenes Filerobot https://scaleflex.github.io/filerobot-image-editor/

## Features
* Edición de imágenes Subidas con Filerobot 
* Recorte rápido de imágenes Subidas con Jcrop
* Jquery y Jquery Ui en su ultima Version (jQuery v3.7.1  y  jQuery UI v1.13.2)
* Motor Ajax con respuestas JSON 
* Carga de varios archivos 
* Cargar archivos usando HTML5 arrastrar y soltar desde el administrador de archivos local 
* Arrastre y suelte imágenes desde páginas HTML externas. Se pueden eliminar varias imágenes usando la selección (solo Firefox) 
* Descargar varios archivos o una carpeta como un solo archivo ZIP 
* Seleccione varios archivos con la tecla Ctrl / Comando 
* Portapapeles para copiar, mover y descargar múltiples archivos 
* Fácil de integrar y configurar en aplicaciones web 
* Opción para seleccionar y devolver varios archivos. Solo para aplicaciones personalizadas 
* Cambiar el tamaño de las imágenes cargadas. Resolución de imagen máxima configurable 
* Soporte de marca de agua PNG * Resolución de miniaturas configurable 
* Rotar y / o voltear automáticamente las imágenes cargadas dependiendo de la etiqueta EXIF de información de orientación si existe 
* Soporte de múltiples temas 
* Soporta varios idiomas
* Vista previa de imágenes en tamaño completo

## Compatibility
* KCFinder se prueba oficialmente en el servidor web Apache 2.4 solamente, pero probablemente funcionará en otros servidores web.
* Se requiere PHP 7.4 o superior. El modo seguro debe estar desactivado. 
* Se requiere al menos una de estas extensiones de PHP: GD, ImageMagick o GraphicsMagick. 
* Para trabajar con caché HTTP del lado del cliente, el PHP debe instalarse como módulo Apache. 
* KCFinder soporta el reconocimiento de tipo MIME para los archivos cargados. Si planea usar esta función, debe cargar la extensión Fileinfo PHP. 
* La extensión PHP ZIP debe cargarse para tener la opción de descargar múltiples archivos y directorios como un solo archivo ZIP. 
* La rotación automática y volteo de imágenes requiere la extensión PHP EXIF.

## Desarrollo
Espero poder subir actualizaciones y correcciones con mas frecuencia, aun falta un reproductor de video y un visor de documentos.
