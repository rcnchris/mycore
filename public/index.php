<?php
$debug = true;
require '../vendor/autoload.php';
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
    } else {

        error_reporting(E_ALL);
        ini_set("display_errors", 1);

        define('ROOT', dirname(__DIR__));

        $folder = new \Rcnchris\Core\Tools\Folder(dirname(__DIR__));
        $composer = new \Rcnchris\Core\Tools\Composer(ROOT . DIRECTORY_SEPARATOR . 'composer.json');
    }
    ?>

    <?php if ($debug): ?>
        <div class="row">
            <div class="col">
                <h1 class="display-3">Debug</h1>
                <hr/>
                <p class="lead">Taille du projet : <span class="badge badge-warning"><?= $folder->size() ?></span></p>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?php
                $tableFactory = \Rcnchris\Core\ORM\TableFactory::getInstance([
                    'host' => 'dbMyCore',
//                    'username' => '',
//                    'password' => '',
//                    'dbName' => 'dbMyCore',
                    'sgbd' => 'sqlite',
                    //'fileName' => ROOT . '/public/dbMycore.sqlite'
                ]);
                r($tableFactory);
                ?>
            </div>
            <div class="col-6">
                <?php
                r($tableFactory->getConfig());
                //r($tableFactory->get('users')->getColumns());
                $users = $tableFactory->get('users', ['orm' => 'cake']);
                //r($users);
                r($users->find()->toArray());

                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Dossiers de la racine</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($folder->folders() as $dir): ?>
                        <tr>
                            <td><?= $dir ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Fichiers de la racine</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($folder->files() as $file): ?>
                        <tr>
                            <td><?= $file ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Librairies utilisées</th>
                        <th>Versions souhaitées</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($composer->getRequires('req') as $name => $version): ?>
                        <tr>
                            <td><?= $name ?></td>
                            <td><?= $version ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Librairies utilisées pour le développement</th>
                        <th>Versions souhaitées</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($composer->getRequires('dev') as $name => $version): ?>
                        <tr>
                            <td><?= $name ?></td>
                            <td><?= $version ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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