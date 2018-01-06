<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

use Rcnchris\App\Controllers\PagesController;

require '../vendor/autoload.php';

session_start();

// Configuration
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

// Twig
$prefixPackage = $app->getContainer()->get('app.prefix') . 'twig';
$app->get($prefixPackage . '/array', PagesController::class . ':twigArray')->setName('twig.array');
$app->get($prefixPackage . '/debug', PagesController::class . ':twigDebug')->setName('twig.debug');
$app->get($prefixPackage . '/file', PagesController::class . ':twigFile')->setName('twig.file');
$app->get($prefixPackage . '/icons', PagesController::class . ':twigIcons')->setName('twig.icons');
$app->get($prefixPackage . '/text', PagesController::class . ':twigText')->setName('twig.text');
$app->get($prefixPackage . '/html', PagesController::class . ':twigHtml')->setName('twig.html');
$app->get($prefixPackage . '/form', PagesController::class . ':twigForm')->setName('twig.form');
$app->get($prefixPackage . '/bootstrap', PagesController::class . ':twigBootstrap')->setName('twig.bootstrap');
$app->get($prefixPackage . '/time', PagesController::class . ':twigTime')->setName('twig.time');

// Run app
$app->run();