<!-- Synology -->
<?php
$audio2 = new \Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage($syno);
$dlPkg = new \Rcnchris\Core\Apis\Synology\Packages\DownloadStationPackage($syno);
?>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">API</h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    Utilisateur : <?= $syno->getConfig()->get('user') ?>
                </h6>
                <p class="card-text">
                    Packages : <code><?= $syno->getPackages()->join() ?></code>
                </p>
            </div>
        </div>
    </div>

    <div class="col-6">
        <?php
        $dl = $syno->getPackage('DownloadStation', $syno)->setIcon('fa fa-download');
        $audio = $syno->getPackage('AudioStation', $syno)->setIcon('fa fa-music');
        ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="<?= $dl->getIcon() ?>"></i>
                    Package <?= $dl->getName() ?>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    Version <span class="badge badge-secondary"><?= $dl->getVersion() ?></span>
                </h6>
                <p class="card-text">
                    Méthodes de l'API : <code><?= $dl->getMethods()->join() ?></code>
                </p>

                <p class="card-text">
                    Classe : <code><?= get_class($dl) ?></code>
                </p>
                <hr/>
                <p class="card-text">
                    <?php
                    $taches = $dl->request('Task', 'list');
                    ?>
                    Liste des tâches <span class="badge badge-warning"><?= $taches->get('total') ?></span>
                </p>
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Type</th>
                        <th>Taille</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($taches->get('tasks') as $task): ?>
                        <tr>
                            <td><?= $task['title'] ?></td>
                            <td><?= $task['status'] ?></td>
                            <td><?= $task['type'] ?></td>
                            <td align="right">
                                <span class="badge badge-secondary"><?= Common::bitsSize($task['size'], 2) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr/>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="<?= $audio->getIcon() ?>"></i>
                    Package <?= $audio->getName() ?>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    Version <span class="badge badge-secondary"><?= $audio->getVersion() ?></span>
                </h6>

                <p class="card-text">
                    Méthodes de l'API : <code><?= $audio->getMethods()->join() ?></code>
                </p>

                <p class="card-text">
                    Classe : <code><?= get_class($audio) ?></code>
                </p>

                <hr/>

                <p class="card-text">
                    <?php
                    $playlists = $audio->request('Playlist', 'list');
                    ?>
                    Listes de lectures <span class="badge badge-warning"><?= $playlists->get('total') ?></span>
                </p>

                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Statut</th>
                        <th>Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($playlists->get('playlists') as $playlist): ?>
                        <tr>
                            <td><?= $playlist['name'] ?></td>
                            <td><?= $playlist['sharing_status'] ?></td>
                            <td><?= $playlist['type'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr/>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="<?= $audio2->getIcon() ?>"></i>
                    Package <?= $audio2->getName() ?>
                </h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    Version <span class="badge badge-secondary"><?= $audio2->getVersion() ?></span>
                </h6>

                <p class="card-text">
                    Méthodes de l'API : <code><?= $audio2->getMethods()->join() ?></code>
                </p>

                <p class="card-text">
                    Classe : <code><?= get_class($audio2) ?></code>
                </p>

                <hr/>

                <p class="card-text">
                    Listes de lectures <span class="badge badge-warning"><?= $playlists->get('total') ?></span>
                </p>

                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Statut</th>
                        <th>Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($audio2->playlists()->get('playlists') as $playlist): ?>
                        <tr>
                            <td><?= $playlist['name'] ?></td>
                            <td><?= $playlist['sharing_status'] ?></td>
                            <td><?= $playlist['type'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <hr/>
                <?php
                $dlPkg->createTask([
                    'uri' => 'ftps://192.168.1.2:21/Download/Piles.xlsx',
                    'username' => 'phpunit',
                    'password' => 'mycoretest'
                ]);
                r($dlPkg->tasks()->toArray());

                ?>
            </div>
        </div>

    </div>

    <!-- Journal des requêtes des API -->
    <div class="col-12">
        <hr/>
        <h2>Journal des requêtes <span class="badge badge-warning"><?= $syno->getLog()->count() ?></span></h2>
        <table class="table table-sm">
            <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>URL</th>
                <th>Statut</th>
                <th>Temps</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($syno->getLog() as $k => $log): ?>
                <tr>
                    <td><?= $k ?></td>
                    <td><?= $log['title'] ?></td>
                    <td><?= $log['details']['url'] ?></td>
                    <td><?= $log['details']['http_code'] ?></td>
                    <td><?= $log['details']['total_time'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>