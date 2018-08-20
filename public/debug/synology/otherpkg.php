<?php $pkg = $syno->getPackage('AntiVirus') ?>
<div class="col">
    <div class="card">

        <div class="card-header">
            <h4>
                <?= $pkg->getName() ?>
                <small><span class="badge badge-secondary"><?= $pkg->getVersion() ?></span></small>
            </h4>
        </div>

        <div class="card-body">

            <pre class="sh_php"> $pkg = $syno->getPackage('AntiVirus');</pre>

            <h5 class="card-title">Classe : <code><?= get_class($pkg) ?></code></h5>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>
            <div class="alert alert-secondary">
                <code><?= implode(', ', get_class_methods($pkg)) ?></code>
            </div>

            <h6 class="card-subtitle mb-2 text-info">APIs</h6>
            <div class="alert alert-secondary">
                <code><?= $pkg->getMethods()->join() ?></code>
            </div>

            <h6 class="card-subtitle mb-2 text-info">Définition</h6>
            <?= r($pkg->getDefinition('Config', true)->toArray()) ?>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Configuration</h6>
            <?= r($pkg->config('Config', 'get')->toArray()) ?>

        </div>

    </div>
    <hr/>
</div>