<?php
$video = new \Rcnchris\Core\Apis\Synology\Packages\VideoStationPackage($syno);
?>
<div class="col">
    <div class="card">

        <div class="card-header">
            <h4>
                <?= $video->getName() ?>
                <small><span class="badge badge-secondary"><?= $video->getVersion() ?></span></small>
            </h4>
        </div>

        <div class="card-body">

                <pre
                    class="sh_php"> $video = new \Rcnchris\Core\Apis\Synology\Packages\VideoStationPackage($syno);</pre>

            <h5 class="card-title">Classe : <code><?= get_class($video) ?></code></h5>

            <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>

            <div class="alert alert-secondary">
                <code><?= implode(', ', get_class_methods($video)) ?></code>
            </div>

            <h6 class="card-subtitle mb-2 text-info">APIs</h6>

            <div class="alert alert-secondary">
                <code><?= $video->getMethods()->join() ?></code>
            </div>

            <h6 class="card-subtitle mb-2 text-info">Configuration</h6>
            <?= r($video->config()->toArray()) ?>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Films</h6>
            <?= r($video->movies(['limit' => 10], 'title')) ?>

            <?php
            //    r($video->movies(['limit' => 10])->toArray());
            //    r($video->collections()->toArray());
            //    r($video->videos(['limit' => 10])->toArray());
            //    r($video->videos(['limit' => 10], 'title'));
            ?>
        </div>
    </div>
    <hr/>
</div>