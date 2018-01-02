<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

chdir(dirname(dirname(dirname(__DIR__))));
require 'vendor/autoload.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <title>My Core</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="#">MyCore</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a href="coverage/index.html" class="nav-link" target="_blank"><i class="fa fa-bar-chart"></i> Coverage</a>
            </li>
            <li class="nav-item">
                <a href="doc/" class="nav-link" target="_blank"><i class="fa fa-book"></i> Documentation</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid" role="main">

    <div class="row">
        <div class="col-12">
            <h1 class="display-3">Ola les gens</h1>
            <hr/>
        </div>
    </div>

    <div class="row">

        <div class="col-4">
            <h2>API</h2>
            <?php

            $apiName = 'one';

            if ($apiName === 'one') {
                $api = (new \Rcnchris\Core\Apis\OneAPI('https://randomuser.me/api'));
            } elseif ($apiName === 'allo') {
                $api = new \Rcnchris\Core\Apis\AlloCine();
            } else {
                $api = new \Rcnchris\Core\Apis\OneAPI();
            }
            // http://api.allocine.fr/rest/v3/search?q=Dinosaure&format=json&partner=100043982026&sed=20171229&sig=ouxJi9P%2FwSaGFWUT4Rnhl1p42s8%3D
            // http://api.allocine.fr/rest/v3/search?q=Dinosaure&format=json&partner=100043982026&sed=20171230&sig=2c1Pbg9H4P1HEqUHiVG8SsOjMt0%3D

            r($api);
            ?>
        </div>

        <div class="col-8">
            <h2>Réponse</h2>
            <?php
            if ($apiName === 'one') {

                $response = $api->addParams('results', 2)->r();

            } elseif ($apiName === 'allo') {

                //$response = $api->search('scarface');
                $response = $api->episode(363695);

            } else {
                $response = $api->r();
            }
            ?>
            <table class="table table-sm">
                <tbody>
                <tr>
                    <th>URL de l'API</th>
                    <td><code><?= $api->url(false); ?></code></td>
                </tr>
                <tr>
                    <th>URL de la réponse</th>
                    <td><code><?= $response->getUrl(); ?></code></td>
                </tr>
                <tr>
                    <th>Code HTTP</th>
                    <td>
                        <?php if ($response->getHttpCode() === 200): ?>
                        <div class="badge badge-success"><?= $response->getHttpCode(); ?></div>
                        <?php else: ?>
                        <div class="badge badge-danger"><?= $response->getHttpCode(); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Content Type</th>
                    <td><code><?= $response->getContentType(); ?></code></td>
                </tr>
                <tr>
                    <th>Charset</th>
                    <td><code><?= $response->getCharset(); ?></code></td>
                </tr>
                <tr>
                    <th>Réponse dans un tableau</th>
                    <td><?= r($response->toArray()) ?></td>
                </tr>
                <tr>
                    <th>Journal des requêtes</th>
                    <td>
                        <?php $logs = $api->getLog(); ?>
                        <table class="table-sm">
                            <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Titre</th>
                                <th>Détails</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><code><?= $log['class'] ?></code></td>
                                    <td><?= $log['title'] ?></td>
                                    <td>
                                        <table class="table table-sm">
                                            <tbody>
                                        <?php foreach ($log['details'] as $k =>$v): ?>
                                            <?php if ($k != 'url'): ?>
                                            <tr>
                                                <th><?= $k ?></th>
                                                <td><?= is_array($v) ? r($v) : $v ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>