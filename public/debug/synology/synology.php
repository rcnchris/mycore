<!-- Synology -->
<?php
$syno = new \Rcnchris\Core\Apis\Synology\Synology($config->get('synology')['nas']);
?>

<!-- API -->
<div class="row">
    <div class="col-12">
        <img src="<?= $syno->logo() ?>"/>
        <hr/>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <?= $syno->getConfig()->get('description') ?>
                    - <span class="badge badge-secondary"><?= $syno->getConfig()->get('user') ?></span>
                </h5>
            </div>
            <div class="card-body">

                <pre class="sh_php"> $syno = new \Rcnchris\Core\Apis\Synology\Synology($config);</pre>

                <h5 class="card-title">Classe : <code><?= get_class($syno) ?></code></h5>

                <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>

                <div class="alert alert-secondary">
                    <code><?= implode(', ', get_class_methods($syno)) ?></code>
                </div>
                <hr/>

                <h5 class="card-title">Définitions</h5>
                <?= r($syno->getApiDefinitions(['VideoStation.Movie', 'AudioStation.Genre'])->toArray()) ?>
                <hr/>

                <h5 class="card-title">Request</h5>
                <?php
                r($syno->request('VideoStation.Movie', 'list', ['limit' => 3])->toArray());
                r($syno->request('VideoStation.Movie', 'list', ['limit' => 3, 'account' => 'phpunit', 'passwd' => 'mycoretest'])->toArray());
                r($syno->request('VideoStation.Movie', 'search', ['title' => 'parrain'])->toArray());
                r($syno->request('AudioStation.Genre')->toArray());
                $syno->logout('VideoStation.Movie');
                $syno->logout('AudioStation.Genre');
                ?>
                <hr/>
                <h5 class="card-title">Définition JSON</h5>
                <?= r($syno->getJsonDefinition('DownloadStation')->toArray()) ?>
                <hr/>

                <h5 class="card-title">Journal</h5>
                <?= r($syno->getLog()) ?>
            </div>
        </div>
        <hr/>
    </div>

</div>

<?php
/**
 * Déconnexion des API utilisées
 * Pas nécessaire car fait par __destruct de SynologyAPI
 */
$sids = $syno->getSids();
foreach ($sids as $apiName => $sid) {
    $syno->logout($apiName);
}
?>

<!-- Journal des requêtes -->
<div class="row">
    <div class="col">
        <hr/>
        <div class="card">
            <div class="card-header">Contenu l'instance <code>$syno</code></div>
            <div class="card-body">

                <h5 class="card-title">Packages</h5>

                <h6 class="card-subtitle mb-2 text-info">
                    Identifiants de connexions obtenus par les APIs
                    <span class="badge badge-warning"><?= count($sids) ?></span>
                </h6>
                <?= $html->details('Voir les SIDs', $html->table($sids, ['class' => 'table table-sm table-striped'])) ?>

                <hr/>

                <h5 class="card-title">Journal</h5>
                <?php $logs = $syno->getLog(true) ?>

                <h6 class="card-subtitle mb-2 text-info">
                    <span class="badge badge-warning"><?= $logs->count() ?></span> requêtes exécutées, en <span
                        class="badge badge-warning"><?= $logs->extract('details')->extract('total_time')->sum() ?></span>
                    seconde(s)
                </h6>
                <details>
                    <summary>Voir toutes les requêtes</summary>
                    <table class="table table-sm table-striped">
                        <thead>
                        <tr>
                            <th>Titre</th>
                            <th>URL</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Temps</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>
                                    <small><i><?= $log['title'] ?></i></small>
                                </td>
                                <td>
                                    <small><?= $log['details']['url'] ?></small>
                                </td>
                                <td><span class="badge badge-secondary"><?= $log['details']['http_code'] ?></span></td>
                                <td>
                                    <small><?= $log['details']['content_type'] ?></small>
                                </td>
                                <td>
                                    <small><?= $log['details']['total_time'] ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </details>
            </div>
        </div>

        <hr/>
    </div>
</div>
