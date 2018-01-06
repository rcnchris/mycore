<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

use Rcnchris\App\Controllers\PagesController;

require '../vendor/autoload.php';

session_start();

// Container
$config = require '../app/config.php';

// Application
$app = new \Slim\App($config);

// Routes
$prefixApp = $app->getContainer()->get('app.prefix');
$app->get($prefixApp, PagesController::class . ':home')->setName('home');

// Tools
$prefixPackage = $prefixApp . 'tools';
$app->get($prefixPackage . '/common', PagesController::class . ':common')->setName('tools.common');
$app->get($prefixPackage . '/collection', PagesController::class . ':collection')->setName('tools.collection');
$app->get($prefixPackage . '/text', PagesController::class . ':text')->setName('tools.text');
$app->get($prefixPackage . '/folder', PagesController::class . ':folder')->setName('tools.folder');
$app->get($prefixPackage . '/cmd', PagesController::class . ':cmd')->setName('tools.cmd');
$app->get($prefixPackage . '/composer', PagesController::class . ':composer')->setName('tools.composer');

// API
$prefixPackage = $app->getContainer()->get('app.prefix') . 'api';
$app->get($prefixPackage . '/oneapi', PagesController::class . ':oneapi')->setName('api.oneapi');
$app->get($prefixPackage . '/synology', PagesController::class . ':synology')->setName('api.synology');

// Run app
$app->run();