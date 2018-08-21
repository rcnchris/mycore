<?php
$dl = $syno->getPackage('DownloadStation');
?>
<div class="col">
    <div class="card">
        <div class="card-header">
            <h4>
                <?= $dl->getName() ?>
                <small><span class="badge badge-secondary"><?= $dl->getVersion() ?></span></small>
            </h4>
        </div>

        <div class="card-body">

                <pre
                    class="sh_php"> $dl = $syno->getPackage('DownloadStation');</pre>

            <h5 class="card-title">Classe : <code><?= get_class($dl) ?></code></h5>

            <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>

            <div class="alert alert-secondary">
                <code><?= implode(', ', get_class_methods($dl)) ?></code>
            </div>

            <h6 class="card-subtitle mb-2 text-info">APIs</h6>

            <div class="alert alert-secondary">
                <code><?= $dl->getMethods()->join() ?></code>
            </div>
            <?php
            // r($dl);

            ////r($dl->listBT()->toArray());
            //    $dl->createTask([
            //        'uri' => 'ftps://192.168.1.2:21/web/index.php',
            //        'username' => 'phpunit',
            //        'password' => 'mycoretest'
            //    ]);

            //r($dl->configSchedule()->toArray());
            //r($dl->statistics()->toArray());
            //r($dl->listBT()->toArray());
            //        r($dl->tasks()->toArray());
            //        r($dl->tasks([], 'title'));
            //        r($dl->task('dbid_195')->toArray());

            //                $dl->logout('Task');
            ?>
            <hr/>
            <h6 class="card-subtitle mb-2 text-info">Configuration</h6>
            <?= r($dl->config()->toArray()) ?>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Tâches</h6>
            <?= r($dl->tasks(['limit' => 10], 'title')) ?>
        </div>
    </div>
    <hr/>
</div>