<?php
$debug = true;
$start = microtime(true);

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set("display_errors", 1);

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require '../vendor/autoload.php';

$config = require __DIR__ . '/../app/config.php';
$app = new \Slim\App($config);
$container = $app->getContainer();
$container['start_at'] = $start;

require __DIR__ . '/../app/dependencies.php';
require __DIR__ . '/../app/middlewares.php';
require __DIR__ . '/../app/routes.php';

// Run app
if (PHP_SAPI !== 'cli') {
    $app->run();
}
