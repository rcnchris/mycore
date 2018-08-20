<!-- Synology -->
<?php
$syno = new \Rcnchris\Core\Apis\Synology\SynologyAPI($config->get('synology')['nas']);
?>

<!-- API -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5>
                    <?= $syno->getConfig()->get('description') ?>
                    - <span class="badge badge-secondary"><?= $syno->getConfig()->get('user') ?></span>
                </h5>
            </div>
            <div class="card-body">

                <pre class="sh_php"> $syno = new \Rcnchris\Core\Apis\Synology\SynologyAPI($config);</pre>

                <h5 class="card-title">Classe : <code><?= get_class($syno) ?></code></h5>

                <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>

                <div class="alert alert-secondary">
                    <code><?= implode(', ', get_class_methods($syno)) ?></code>
                </div>

                <h6 class="card-subtitle mb-2 text-info">Packages Synology</h6>
                <pre class="sh_php"> $syno->getPackages()->join();</pre>
                <div class="alert alert-secondary">
                    <code><?= $syno->getPackages()->join() ?></code>
                </div>

                <h6 class="card-subtitle mb-2 text-info">Liste des noms finaux des APIs</h6>
                <pre class="sh_php"> $syno->getAllApiEndNames()->join()</pre>
                <details>
                    <summary>Voir toutes les APIs</summary>
                    <div class="alert alert-secondary">
                        <code><?= $syno->getAllApiEndNames()->join() ?></code>
                    </div>
                </details>

                <hr/>

                <h6 class="card-subtitle mb-2 text-info">Liste des méthodes des APIs</h6>
                <pre class="sh_php"> $syno->enableMethods;</pre>
                <?php $methods = $syno->enableMethods;
                sort($methods) ?>
                <div class="alert alert-secondary">
                    <code><?= implode(', ', $methods) ?></code>
                </div>

                <hr/>

                <h6 class="card-subtitle mb-2 text-info">Messages d'erreurs Synology</h6>
                <pre class="sh_php"> $syno->getErrorMessages();</pre>
                <details>
                    <summary>Voir tous les messages</summary>
                    <?= $html->table($syno->getErrorMessages()->toArray(), ['class' => 'table table-sm']) ?>
                </details>
            </div>
        </div>

        <hr/>
    </div>
</div>

<!-- Packages -->
<div class="row">

    <!-- AudioStation -->
    <?php // include_once 'audiostation.php' ?>

    <!-- DownloadStation -->
    <?php // include_once 'downloadstation.php' ?>

    <!-- VideoStation -->
    <?php // include_once 'videostation.php' ?>

    <!-- FileStation -->
    <?php include_once 'filestation.php' ?>

    <!-- Other wihtout Package class -->
    <?php // include_once 'otherpkg.php' ?>

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
