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

        <div class="col-6">
            <?php
            $apiName = 'one';
            if ($apiName === 'one') {
                $api = (new \Rcnchris\Core\Apis\OneAPI('https://randomuser.me/api'))->addParams('results', 2);
            } elseif ($apiName === 'allo') {
                $api = new \Rcnchris\Core\Apis\AlloCine();
            } else {
                $api = new \Rcnchris\Core\Apis\OneAPI();
            }
            // http://api.allocine.fr/rest/v3/search?q=Mechanic+%3A+Resurrection&format=json&partner=100043982026&sed=20171228&sig=sSSnq26%2F7CPlTxQ0HJ48ARKbF5s%3D
            // http://api.allocine.fr/rest/v3/search?q=Scarface&format=json&partner=100043982026&sed=20171228&sig=NKHrZBtnDLqLzZAJ92lygyS8BD0%3D?q=Scarface&format=json&partner=100043982026
            r($api);
            ?>
        </div>

        <div class="col-6">
            <?php
            r($api->url());
            if ($apiName === 'one') {
                $response = $api->r();
                r($response);
                r($response->toJson('info'));
            } elseif ($apiName === 'allo') {
                $response = $api->r();
                r($response);
                r($response->get());
                //r($api->search('Scarface')->toArray());
            } else {
                r($api->r());
            }
            r($api->getLog());
            ?>
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