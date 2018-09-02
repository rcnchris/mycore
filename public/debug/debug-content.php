<?php
use Rcnchris\Core\Html\Html;

$html = Html::getInstance();

if ($debug) {

    // Objets
    $config = require ROOT . DS . 'tests/config.php';
    $config = new Rcnchris\Core\Config\ConfigContainer($config);
    $e = new \Rcnchris\Core\Tools\Environnement();
    $folder = new \Rcnchris\Core\Tools\Folder(dirname(__DIR__));
    $composer = new \Rcnchris\Core\Tools\Composer(ROOT . DIRECTORY_SEPARATOR . 'composer.json');
    $session = new \Rcnchris\Core\Session\PHPSession();
//    $html->setCdns($config->get('cdn'));
    //$rand = \Rcnchris\Core\Tools\RandomItems::getInstance();
    //$adr = new \Rcnchris\Core\Apis\ApiGouv\AdressesApiGouv();
//    $dog = new \Rcnchris\Core\Apis\CurlAPI('https://dog.ceo/api');
//    $allo = new \Rcnchris\Core\Apis\AlloCine();
    //$randAPI = new CurlAPI('https://randomuser.me/api');
}
?>

<!-- Titre -->
<div class="row">
    <div class="col">
        <?= Html::surround('Debug', 'h1', ['class' => 'display-3']) ?>
        <hr/>
    </div>
</div>

<?php include 'accordion.php'; ?>
