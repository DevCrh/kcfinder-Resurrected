<?php
require "integration/default.php";

define('ROOT', dirname(__FILE__));
define('HOST', $_SERVER['HTTP_HOST'] === 'localhost' ? 'localhost' : $_SERVER['HTTP_HOST']); // Dominio o host localhost.com tudominio.com

// configurar variables de inicio
$Default_kcfinderPlugin = new Default_kcfinderPlugin();
$Default_kcfinderPlugin::checkAuth();
?>
<html>

<head>
    <title>KCFinder Resurrected Example</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="adapters/jquery.js" type="text/javascript"></script>
    <style type="text/css">
        #kcfinder {
            width: 900px;
            height: 700px;
            border: 1px solid #6b6b6b;
            border-radius: 5px;
        }

        #kcfinder iframe {
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <br><br>
    <div>
        <button onClick="open_kcfinder();">Abrir KcFinder</button>
    </div>
    <br><br>
    <div id="kcfinder"></div>
</body>


<script type="text/javascript">
    function open_kcfinder() {
        $('#kcfinder').kcfinder({
            url: "./browse.php",
            theme: "default",
            lang: "es",
            callback: function(file) {
                alert('Selected file: "' + file + '"');
            },
            callbackMultiple: function(files) {
                alert('Selected files:\n  "' + files.join('",\n  "') + '"');
            }
        });
    };
</script>

</html>