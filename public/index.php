<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require '../vendor/autoload.php';

session_start();

// Configuration
$config = require '../app/config.php';

// Application
$app = new \Slim\App($config);

// Routes
require '../app/routes.php';

// Run app
$app->run();