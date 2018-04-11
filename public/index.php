<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require '../vendor/autoload.php';

// Configuration
$config = require '../app/config.php';
$dependances = require '../app/dependences.php';

// Application
$app = new \Slim\App(array_merge($config, $dependances));

// Middlewares
require '../app/middlewares.php';

// Routes
require '../app/routes.php';

// Run app
if (php_sapi_name() !== 'cli') {
    $app->run();
}