<?php

require_once dirname(__DIR__) . '/core/autoload.php';
require_once dirname(__DIR__) . '/integration/security.php';

if (!function_exists('validateCSRF')) {
    function validateCSRF($token)
    {
        return $token === 'test-token' ? true : 'Invalid CSRF token.';
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SecurityUploaderHarness extends \kcfinder\uploader
{
    public function __construct()
    {
    }

    public function filenameIsSafe($filename)
    {
        return $this->checkFilename($filename);
    }
}

class SecurityBrowserHarness extends \kcfinder\browser
{
    public function __construct($typeDir, array $config)
    {
        $this->typeDir = str_replace('\\', '/', $typeDir);
        $this->type = 'images';
        $this->types = array('images' => '*img');
        $this->config = $config;
        $this->action = 'security-test';
    }

    public function crop()
    {
        return $this->act_crop();
    }

    public function editImage()
    {
        return $this->act_editimage();
    }

    protected function errorMsg($message, $data = array())
    {
        throw new RuntimeException($message);
    }
}

function security_assert($condition, $message)
{
    if (!$condition) {
        fwrite(STDERR, "FAIL: $message\n");
        exit(1);
    }
}

function security_expect_failure($callback, $message)
{
    try {
        $callback();
    } catch (RuntimeException $exception) {
        return;
    }

    security_assert(false, $message);
}

$uploader = new SecurityUploaderHarness();
security_assert($uploader->filenameIsSafe('photo.jpg'), 'A normal filename must remain valid.');
security_assert(!$uploader->filenameIsSafe('../secret.jpg'), 'Unix traversal must be rejected.');
security_assert(!$uploader->filenameIsSafe('..\\secret.jpg'), 'Windows traversal must be rejected.');

security_assert(\kcfinder\image::dimensionsWithinLimit(2000, 1000, 2000000), 'An image at the pixel limit must remain valid.');
security_assert(!\kcfinder\image::dimensionsWithinLimit(2001, 1000, 2000000), 'An oversized image must be rejected.');
security_assert(!\kcfinder\image::dimensionsWithinLimit(0, 1000, 2000000), 'Zero dimensions must be rejected.');

$sample = imagecreatetruecolor(2, 2);
ob_start();
imagepng($sample);
$pngData = ob_get_clean();
if (PHP_VERSION_ID < 80000) imagedestroy($sample);
security_assert(\kcfinder\image::safeImageStringSize($pngData, 4, IMAGETYPE_PNG) !== false, 'A valid image must remain editable.');
security_assert(\kcfinder\image::safeImageStringSize($pngData, 3, IMAGETYPE_PNG) === false, 'An oversized edited image must be rejected.');
security_assert(\kcfinder\image::safeImageStringSize('not an image', 4, IMAGETYPE_PNG) === false, 'Invalid image bytes must be rejected before writing.');

$workDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'kcfinder-security-' . bin2hex(random_bytes(8));
$imageDir = $workDir . DIRECTORY_SEPARATOR . 'images';
security_assert(mkdir($imageDir, 0700, true), 'The test image directory must be created.');
$sourcePath = $imageDir . DIRECTORY_SEPARATOR . 'photo.png';
security_assert(file_put_contents($sourcePath, $pngData) !== false, 'The valid source image must be written.');

$browserConfig = array(
    'access' => array('files' => array('upload' => true)),
    'allowExts' => 'png jpg jpeg webp',
    'allowMimeTypes' => array('image/png', 'image/jpeg', 'image/webp'),
    'jpegQuality' => 90,
    'filePerms' => 0600,
    '_dropUploadMaxFilesize' => 1048576,
    '_maxImagePixels' => 1000000
);
$browser = new SecurityBrowserHarness($imageDir, $browserConfig);

$_GET = array();
$_POST = array(
    'csrf_token' => 'test-token',
    'dir' => '',
    'file' => '../outside.png',
    'x' => '0',
    'y' => '0',
    'w' => '1',
    'h' => '1'
);
security_expect_failure(function () use ($browser) {
    $browser->crop();
}, 'Crop traversal must be rejected by the real action.');

$_POST['file'] = 'photo.png';
$cropResult = json_decode($browser->crop(), true);
security_assert(isset($cropResult['newFile']), 'A legitimate crop must remain available.');
security_assert(is_file($imageDir . DIRECTORY_SEPARATOR . $cropResult['newFile']), 'The legitimate crop must be written inside the managed directory.');

$deniedConfig = $browserConfig;
$deniedConfig['access']['files']['upload'] = false;
$deniedBrowser = new SecurityBrowserHarness($imageDir, $deniedConfig);
$_POST = array(
    'csrf_token' => 'test-token',
    'dir' => '',
    'file' => 'photo.png',
    'ext' => 'png',
    'base64' => 'data:image/png;base64,' . base64_encode($pngData)
);
security_expect_failure(function () use ($deniedBrowser) {
    $deniedBrowser->editImage();
}, 'Image editing must require upload permission.');

$_POST['base64'] = base64_encode('not an image');
security_expect_failure(function () use ($browser) {
    $browser->editImage();
}, 'Invalid edited image bytes must be rejected before writing.');

$_POST['base64'] = 'data:image/png;base64,' . base64_encode($pngData);
$editResult = json_decode($browser->editImage(), true);
security_assert(isset($editResult['fileName']), 'A legitimate image edit must remain available.');
security_assert(is_file($imageDir . DIRECTORY_SEPARATOR . $editResult['fileName']), 'The legitimate edit must be written inside the managed directory.');

security_assert(\kcfinder\phpGet::isSafeUrl('https://93.184.216.34/image.jpg'), 'A public HTTPS URL must remain valid.');
security_assert(!\kcfinder\phpGet::isSafeUrl('file:///etc/passwd'), 'Non-HTTP schemes must be rejected.');
security_assert(!\kcfinder\phpGet::isSafeUrl('http://127.0.0.1/admin'), 'IPv4 loopback must be rejected.');
security_assert(!\kcfinder\phpGet::isSafeUrl('http://[::1]:8080/admin'), 'IPv6 loopback must be rejected.');
security_assert(!\kcfinder\phpGet::isSafeUrl('http://[::ffff:127.0.0.1]/admin'), 'IPv4-mapped IPv6 loopback must be rejected.');
security_assert(!\kcfinder\phpGet::isSafeUrl('http://169.254.169.254/latest/meta-data/'), 'Link-local metadata addresses must be rejected.');

$_SESSION = array(
    'kcCsrf' => 'stale-token',
    'KCFINDER' => array('disabled' => false, 'uploadDir' => '/sensitive')
);
\kcfinder\revoke_access();
security_assert($_SESSION['KCFINDER'] === array('disabled' => true), 'Revocation must replace stale KCFinder state.');
security_assert(!isset($_SESSION['kcCsrf']), 'Revocation must remove the stale CSRF token.');

$_SESSION['KCFINDER'] = array('disabled' => false, 'uploadDir' => '/sensitive');
$_SESSION['kcCsrf'] = 'stale-token';
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}
if (!defined('HOST')) {
    define('HOST', 'localhost');
}
require_once dirname(__DIR__) . '/integration/default.php';
security_assert(Default_kcfinderPlugin::checkAuth() === false, 'The bundled example must fail closed.');
security_assert($_SESSION['KCFINDER'] === array('disabled' => true), 'A denied example request must revoke stale access.');
session_write_close();

foreach (glob($imageDir . DIRECTORY_SEPARATOR . '*') as $testFile) {
    @unlink($testFile);
}
@rmdir($imageDir);
@rmdir($workDir);

echo "Security boundary checks passed.\n";
