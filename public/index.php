<?php
$debug = true;
$start = microtime(true);
require '../vendor/autoload.php';

$config = new Rcnchris\Core\Config\ConfigContainer(require '../tests/config.php');

// PSR7
$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
$response = new \GuzzleHttp\Psr7\Response();

// Middlewares
(new \Rcnchris\Core\Middlewares\WhoopsMiddleware())
    ->__invoke($request, $response, function () {
        return null;
    });
(new \Rcnchris\Core\Middlewares\BootMiddleware())
    ->withContainer($config)
    ->__invoke($request, $response, function () {
        return null;
    });
(new \Rcnchris\Core\Middlewares\PoweredByMiddleware())
    ->__invoke($request, $response, function () {
        return null;
    });
(new \Rcnchris\Core\Middlewares\SessionMiddleware())
    ->__invoke($request, $response, function () {
        return null;
    });
(new \Rcnchris\Core\Middlewares\CookiesMiddleware())
    ->__invoke($request, $response, function () {
        return null;
    });
(new \Rcnchris\Core\Middlewares\TrailingSlashMiddleware())
    ->__invoke($request, $response, function () {
        return null;
    });

$html = Rcnchris\Core\Html\Html::getInstance();
$html->setCdns($config->get('cdn'), $config->get('appPrefix'));
?>
<!doctype html>
<html lang="<?= substr($config->get('locale'), 0, 2) ?>">
<head>
    <!-- Required meta tags -->
    <meta charset="<?= $config->get('charset') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?= $html->css('bootstrap', 'min') ?>
    <?= $html->css('shjs', 'min') ?>
    <?= $html->css('app') ?>

    <title><?= $config->get('appName') ?></title>
</head>
<body onload="sh_highlightDocument('/cdn/vendor/shjs/lang/', '.min.js');">

<div class="container-fluid" role="main">
    <?php
    if ($debug) {
        include 'debug/debug-content.php';
    } else {
        echo \Michelf\MarkdownExtra::defaultTransform(file_get_contents('../README.md'));
    }
    ?>
</div>

<footer class="footer">
    <div class="container-fluid">
        <span class="text-muted">
            Mémoire utilisée : <?= \Rcnchris\Core\Tools\Common::getMemoryUse(true) ?> - <?= microtime(true) - $start ?>
        </span>
    </div>
</footer>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<?= $html->script('jquery', 'min') ?>
<?= $html->script('popper', 'min') ?>
<?= $html->script('bootstrap', 'min') ?>
<?= $html->script('shjs', 'min') ?>
<?= $html->script('fontawesome', 'src') ?>
<?= $html->script('app', 'src') ?>

</body>
</html>