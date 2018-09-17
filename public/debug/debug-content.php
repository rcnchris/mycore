<?php
use Rcnchris\Core\Html\Html;

if ($debug) {

    // Objets
    $e = new \Rcnchris\Core\Tools\Environnement();
    $folder = new \Rcnchris\Core\Tools\Folder(dirname(__DIR__));
    $composer = new \Rcnchris\Core\Tools\Composer(ROOT . DIRECTORY_SEPARATOR . 'composer.json');
    $session = new \Rcnchris\Core\Session\PHPSession();
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
<?php //include 'apigouv.php'; ?>
<?php include 'html.php'; ?>
<?php // include 'orm.php'; ?>
<?php // include 'accordion.php'; ?>
