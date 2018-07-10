<?php
$debug = true;
require '../vendor/autoload.php';

// PSR7
$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
$response = new \GuzzleHttp\Psr7\Response();

// Middlewares
(new \Rcnchris\Core\Middlewares\BootMiddleware())->__invoke($request, $response, function () {
    return null;
});
(new \Rcnchris\Core\Middlewares\PoweredByMiddleware())->__invoke($request, $response, function () {
    return null;
});
(new \Rcnchris\Core\Middlewares\SessionMiddleware())->__invoke($request, $response, function () {
    return null;
});
(new \Rcnchris\Core\Middlewares\CookiesMiddleware())->__invoke($request, $response, function () {
    return null;
});
(new \Rcnchris\Core\Middlewares\TrailingSlashMiddleware())->__invoke($request, $response, function () {
    return null;
});

if ($debug) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $folder = new \Rcnchris\Core\Tools\Folder(dirname(__DIR__));
    $composer = new \Rcnchris\Core\Tools\Composer(ROOT . DIRECTORY_SEPARATOR . 'composer.json');
    $session = new \Rcnchris\Core\Session\PHPSession();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <title>MyCore</title>
</head>
<body>

<div class="container-fluid" role="main">
    <?php
    if (!$debug) {
        echo \Michelf\MarkdownExtra::defaultTransform(file_get_contents('../README.md'));
    }
    ?>

    <?php if ($debug): ?>

        <!-- Titre -->
        <div class="row">
            <div class="col">
                <h1 class="display-3">Debug</h1>
                <hr/>
            </div>
        </div>

        <!-- Debug en cours -->
        <div class="row">
            <div class="col">
                <?php

                ?>
            </div>
        </div>

        <!-- Accordion -->
        <div class="row">
            <div class="col">
                <div class="accordion" id="accDebug">

                    <!-- Environnement -->
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Environnement
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accDebug">
                            <div class="card-body">
                                <h5 class="card-title">Serveur, configuration et constantes</h5>
                                <table class="table table-sm">
                                    <tbody>
                                    <tr>
                                        <th>Serveur</th>
                                        <td><samp><?= php_uname() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Version Apache</th>
                                        <td><samp><?= apache_get_version() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Version de PHP</th>
                                        <td><samp><?= PHP_VERSION ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>SAPI name</th>
                                        <td><samp><?= php_sapi_name() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Timezone</th>
                                        <td><samp><?= date_default_timezone_get() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Charset</th>
                                        <td><samp><?= mb_internal_encoding() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Localisation</th>
                                        <td><samp><?= locale_get_default() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Affichage des erreurs</th>
                                        <td><samp><?= error_reporting() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Constantes</th>
                                        <td>
                                            <ul>
                                                <?php foreach(get_defined_constants(true)['user'] as $name => $value): ?>
                                                    <li><samp><?= $name .' : '. $value?></samp></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Composer -->
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Composer <span class="badge badge-warning"><?= count($composer->getRequires('req'))+count($composer->getRequires('dev')) ?></span> librairies utilisées
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accDebug">
                            <div class="card-body">

                                <!-- Description -->
                                <h5 class="card-title">
                                    Description
                                </h5>
                                <div class="row">
                                    <div class="col">
                                        <dl>
                                            <dt>name</dt>
                                            <dd><?= $composer->get('name') ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col">
                                        <dl>
                                            <dt>description</dt>
                                            <dd><?= $composer->get('description') ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col">
                                        <dl>
                                            <dt>type</dt>
                                            <dd><?= $composer->get('type') ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col">
                                        <dl>
                                            <dt>license</dt>
                                            <dd><?= $composer->get('license') ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col">
                                        <dl>
                                            <dt>minimum-stability</dt>
                                            <dd><?= $composer->get('minimum-stability') ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col-12">
                                        <hr/>
                                    </div>
                                </div>

                                <!-- Requires -->
                                <div class="row">
                                    <div class="col-6">
                                        <table class="table table-sm">
                                            <thead>
                                            <tr>
                                                <th>Librairies <span class="badge badge-warning"><?= count($composer->getRequires('req')) ?></span></th>
                                                <th>Version</th>
                                                <th>Taille</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($composer->getRequires('req') as $name => $version): ?>
                                                <tr>
                                                    <td><samp><?= $name ?></samp></td>
                                                    <td><samp><span class="badge badge-warning"><?= $version ?></span></samp></td>
                                                    <?php if ($name === 'php'): ?>
                                                        <td></td>
                                                    <?php else: ?>
                                                        <td><samp><span class="badge badge-warning"><?= \Rcnchris\Core\Tools\Cmd::size(ROOT.DS.'vendor'.DS.$name, true) ?></span></samp></td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <table class="table table-sm">
                                            <thead>
                                            <tr>
                                                <th>Développement <span class="badge badge-warning"><?= count($composer->getRequires('dev')) ?></span></th>
                                                <th>Version</th>
                                                <th>Taille</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($composer->getRequires('dev') as $name => $version): ?>
                                                <tr>
                                                    <td><samp><?= $name ?></samp></td>
                                                    <td><samp><span class="badge badge-warning"><?= $version ?></span></samp></td>
                                                    <td><samp><span class="badge badge-warning"><?= \Rcnchris\Core\Tools\Cmd::size(ROOT.DS.'vendor'.DS.$name, true) ?></span></samp></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr/>

                                <!-- Autoload -->
                                <h5 class="card-title">
                                    Namespaces
                                </h5>
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Namespace</th>
                                        <th>Chemin</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($composer->get('autoload') as $type => $values): ?>
                                        <?php foreach($values as $namespace => $path): ?>
                                            <tr>
                                                <th><samp><?= $type ?></samp></th>
                                                <td><samp><?= $namespace ?></samp></td>
                                                <td><samp><?= $path ?></samp></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Fichiers et dossiers -->
                    <div class="card">
                        <div class="card-header" id="hFiles">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collFiles" aria-expanded="false" aria-controls="collFiles">
                                    Fichiers et dossiers <span class="badge badge-warning"><?= $folder->size() ?></span>
                                </button>
                            </h5>
                        </div>
                        <div id="collFiles" class="collapse" aria-labelledby="hFiles" data-parent="#accDebug">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="card-title">Dossiers</h5>
                                        <table class="table table-sm">
                                            <tbody>
                                            <?php foreach($folder->folders() as $dir): if ($dir[0] != '.'): ?>
                                                <tr>
                                                    <th><samp><?= $dir ?></samp></th>
                                                    <td><samp><span class="badge badge-warning"><?= $folder->get($dir)->size() ?></span></samp></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="card-title">Fichiers</h5>
                                        <table class="table table-sm">
                                            <tbody>
                                            <?php foreach($folder->files() as $file): ?>
                                                <tr>
                                                    <th><samp><?= $file ?></samp></th>
                                                    <td><samp><span class="badge badge-warning"><?= $folder->get($file)->size(true, 2) ?></span></samp></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session et cookies -->
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Session et cookies
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accDebug">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="card-title">Session</h5>
                                        <table class="table table-sm">
                                            <tbody>
                                            <?php foreach($session->get() as $key =>$value): ?>
                                                <tr>
                                                    <th><samp><?= $key ?></samp></th>
                                                    <td><samp><?= $value ?></samp></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="col-6">
                                        <h5 class="card-title">Cookies</h5>
                                        <table class="table table-sm">
                                            <tbody>
                                            <?php foreach($_COOKIE as $key => $value): ?>
                                                <tr>
                                                    <th><samp><?= $key ?></samp></th>
                                                    <td><samp><?= $value ?></samp></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PSR7 -->
                    <div class="card">
                        <div class="card-header" id="hPsr7">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collPsr7" aria-expanded="false" aria-controls="collPsr7">
                                    PSR7
                                </button>
                            </h5>
                        </div>
                        <div id="collPsr7" class="collapse" aria-labelledby="hPsr7" data-parent="#accDebug">
                            <div class="card-body">
                                <div class="row">

                                    <!-- Request -->
                                    <div class="col-6">
                                        <h3>
                                            <code><?= get_class($request) ?></code>
                                        </h3>
                                        <hr/>
                                        <table class="table table-sm">
                                            <tbody>

                                            <tr>
                                                <th>Cible</th>
                                                <td><samp><?= $request->getRequestTarget() ?></samp></td>
                                            </tr>

                                            <tr>
                                                <th>Méthode</th>
                                                <td><samp><?= $request->getMethod() ?></samp></td>
                                            </tr>

                                            <tr>
                                                <th>Version du protocol</th>
                                                <td><?= $request->getProtocolVersion() ?></td>
                                            </tr>

                                            <tr>
                                                <th>Paramètres du serveur</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir tous les paramètres <span class="badge badge-warning"><?= count($request->getServerParams()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($request->getServerParams() as $key => $value): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= $value ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Paramètres de l'URL</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir tous les paramètres <span class="badge badge-warning"><?= count($request->getQueryParams()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($request->getQueryParams() as $key => $value): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= $value ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Données postées</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir toutes les données postées <span class="badge badge-warning"><?= count($request->getParsedBody()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($request->getParsedBody() as $key => $value): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= $value ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Attributs de la requête</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir tous les attributs <span class="badge badge-warning"><?= count($request->getAttributes()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($request->getAttributes() as $key => $value): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= $value ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Cookies</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir tous les cookies <span class="badge badge-warning"><?= count($request->getCookieParams()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($request->getCookieParams() as $key => $value): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= $value ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Headers</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir tous les headers <span class="badge badge-warning"><?= count($request->getHeaders()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($request->getHeaders() as $key => $header): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= current($header) ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>

                                        <!-- URI -->
                                        <h3>
                                            <code><?= get_class($request->getUri()) ?></code>
                                        </h3>
                                        <hr/>
                                        <table class="table table-sm">
                                            <tbody>
                                            <tr>
                                                <th>URL</th>
                                                <td><?= $request->getUri() ?></td>
                                            </tr>
                                            <tr>
                                                <th>Schéma</th>
                                                <td><?= $request->getUri()->getScheme() ?></td>
                                            </tr>
                                            <tr>
                                                <th>Host</th>
                                                <td><?= $request->getUri()->getHost() ?></td>
                                            </tr>
                                            <tr>
                                                <th>Port</th>
                                                <td><?= $request->getUri()->getPort() ?></td>
                                            </tr>
                                            <tr>
                                                <th>Path</th>
                                                <td><?= $request->getUri()->getPath() ?></td>
                                            </tr>
                                            <tr>
                                                <th>Query</th>
                                                <td><?= $request->getUri()->getQuery() ?></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <!-- Body -->
                                        <h3>
                                            Body <code><?= get_class($request->getBody()) ?></code>
                                        </h3>
                                        <hr/>
                                        <table class="table table-sm">
                                            <tbody>
                                            <tr>
                                                <th>Readable</th>
                                                <td><?= $request->getBody()->isReadable() ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>';?></td>
                                            </tr>
                                            <tr>
                                                <th>Writable</th>
                                                <td><?= $request->getBody()->isWritable() ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Seekable</th>
                                                <td><?= $request->getBody()->isSeekable() ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Taille</th>
                                                <td><?= $request->getBody()->getSize() ?></td>
                                            </tr>
                                            <tr>
                                                <th>Meta-données</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir toutes les meta-données <span class="badge badge-warning"><?= count($request->getBody()->getMetadata()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($request->getBody()->getMetadata() as $key => $value): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= $value ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Response -->
                                    <div class="col-6">
                                        <h3>
                                            <code><?= get_class($response) ?></code>
                                        </h3>
                                        <hr/>
                                        <table class="table table-sm">
                                            <tbody>
                                            <tr>
                                                <th>Statut</th>
                                                <td><samp><?= $response->getStatusCode() ?></samp></td>
                                            </tr>
                                            <tr>
                                                <th>Phrase de raison</th>
                                                <td><samp><?= $response->getReasonPhrase() ?></samp></td>
                                            </tr>
                                            <tr>
                                                <th>Version du protocol</th>
                                                <td><samp><?= $response->getProtocolVersion() ?></samp></td>
                                            </tr>
                                            <tr>
                                                <th>Headers</th>
                                                <td>
                                                    <details>
                                                        <summary>Voir tous les headers <span class="badge badge-warning"><?= count($response->getHeaders()) ?></span></summary>
                                                        <table class="table table-sm">
                                                            <tbody>
                                                            <?php foreach($response->getHeaders() as $key => $header): ?>
                                                                <tr>
                                                                    <th><samp><?= $key ?></samp></th>
                                                                    <td><samp><?= current($header) ?></samp></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </details>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coverage -->
                    <div class="card">
                        <div class="card-header" id="hCoverage">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collCoverage" aria-expanded="false" aria-controls="collCoverage">
                                    Coverage
                                </button>
                            </h5>
                        </div>
                        <div id="collCoverage" class="collapse" aria-labelledby="hCoverage" data-parent="#accDebug">
                            <div class="card-body">
                                <iframe src="public/coverage/index.html" frameborder="0" width="100%" height="600"></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Documentation -->
                    <div class="card">
                        <div class="card-header" id="hDoc">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collDoc" aria-expanded="false" aria-controls="collDoc">
                                    Documentation
                                </button>
                            </h5>
                        </div>
                        <div id="collDoc" class="collapse" aria-labelledby="hDoc" data-parent="#accDebug">
                            <div class="card-body">
                                <iframe src="public/doc/index.html" frameborder="0" width="100%" height="600"></iframe>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <?php endif; ?>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
        integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
        crossorigin="anonymous"></script>
</body>
</html>