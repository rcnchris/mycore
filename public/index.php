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

?>
<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <!-- Coloration syntaxique -->
    <link rel="stylesheet" href="/cdn/vendor/shjs/css/sh_acid.min.css">

    <title>MyCore</title>
</head>
<body onload="sh_highlightDocument('/cdn/vendor/shjs/lang/', '.min.js');">

<div class="container-fluid" role="main">
    <?php
    if (!$debug) {
        echo \Michelf\MarkdownExtra::defaultTransform(file_get_contents('../README.md'));
    } else {
        include 'debug/debug-content.php';
    }
    ?>
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

<!-- Coloration syntaxique -->
<script type="text/javascript" src="/cdn/vendor/shjs/sh_main.min.js"></script>

</body>
</html>