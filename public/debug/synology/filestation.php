<?php
$fs = new \Rcnchris\Core\Apis\Synology\Packages\FileStationPackage($syno);
?>
<div class="col">
    <div class="card">

        <div class="card-header">
            <h4>
                <?= $fs->getName() ?>
                <small><span class="badge badge-secondary"><?= $fs->getVersion() ?></span></small>
            </h4>
        </div>

        <div class="card-body">

            <pre class="sh_php"> $fs = new \Rcnchris\Core\Apis\Synology\Packages\FileStationPackage($syno);</pre>

            <h5 class="card-title">Classe : <code><?= get_class($fs) ?></code></h5>

            <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>
            <div class="alert alert-secondary">
                <code><?= implode(', ', get_class_methods($fs)) ?></code>
            </div>

            <h6 class="card-subtitle mb-2 text-info">APIs</h6>
            <div class="alert alert-secondary">
                <code><?= $fs->getMethods()->join() ?></code>
            </div>

            <hr/>

            <h5 class="card-title"><code>Info</code></h5>
            <h6 class="card-subtitle mb-2 text-info">Configuration</h6>
            <?= r($fs->config('Info', 'get')->toArray()) ?>
            <hr/>

            <h5 class="card-title"><code>Search</code> - Recherche d'un terme</h5>
            <?php r($fs->search('/Download/Tests', 'chevrolet')->toArray()) ?>
            <hr/>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Sharing</code> - Liens partagés</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($fs->sharings()->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($fs->sharings(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Un lien</h6>
                    <?= r($fs->sharing($fs->sharings()->get('links')->first()->id)->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Entité</h6>
                    <?php
                    $item = $fs->sharing($fs->sharings()->get('links')->first()->id, true);
                    r($item);
                    ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Champs de l'entité</h6>
                    <?= r($item->getFields()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Accéder à la valeur d'un champ</h6>
                    <?= r($item->name) ?>
                </div>
                <div class="col">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>List</code> - Dossiers partagés</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($fs->sharedFolders()->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($fs->sharedFolders(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Lister les fichiers d'un dossier partagé</h6>
                    <?= r($fs->sharedFolderFiles('/Download/Tests')->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($fs->sharedFolderFiles('/Download/Tests', ['limit' => 3], 'name')) ?>
                </div>
                <div class="col">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>VirtualFolder</code> - Dossiers virtuels</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($fs->virtualFolders()->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($fs->virtualFolders(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Favorite</code> - Favoris</h5>
                </div>
                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($fs->favorites()->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($fs->favorites(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Un favori</h6>
                    <?= r($fs->favorite('/Download/Tests')) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Entité</h6>
                    <?php
                    $item = $fs->favorite($fs->favorites()->get('favorites')->first()->path, true);
                    r($item);
                    ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Champs de l'entité</h6>
                    <?= r($item->getFields()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Accéder à la valeur d'un champ</h6>
                    <?= r($item->name) ?>
                </div>
                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Editer un favori</h6>
                    <pre class="sh_php"> $fs->editFavorite('/Download/Tests', 'Nouveau nom'));</pre>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">

                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Un thumb</h6>
                    <pre class="sh_php"> $fs->thumb('/Download/Tests/chevrolet.jpg', 'medium');</pre>
                    <img src="<?= $fs->thumb('/Download/Tests/chevrolet.jpg', 'medium') ?>" alt="Chevrolet" class="img-thumbnail"/>
                </div>

                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Taille d'un fichier/dossier</h6>
                    <?php r($fs->size('/Download/Tests/chevrolet.jpg')->toArray()) ?>
                    <hr/>
                    <?php r($fs->size('/Download/Tests')->toArray()) ?>
                </div>

                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">MD5 d'un fichier</h6>
                    <?php r($fs->md5File('/Download/Tests/chevrolet.jpg')->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Vérifier les permissions</h6>
                    <?php r($fs->checkPerm('/Download/Tests', 'fake.txt')->toArray()) ?>
                </div>

                <div class="col">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Créer un dossier</h6>
                    <pre class="sh_php"> $fs->createFolder('/Download/Tests', 'Mycore');</pre>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Supprimer un fichier/dossier</h6>
                    <pre class="sh_php"> $fs->delete('/Download/Tests/lefichier.txt');</pre>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Renommer un fichier/dossier</h6>
                    <pre class="sh_php"> $fs->rename('/Download/Tests/chevrolet.jpg', 'chevroletSS.jpg');</pre>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Copier/Déplacer un fichier/dossier</h6>
                    <?= r($fs->copy('/Download/chevrolet.jpg', '/Download/Tests', ['overwrite' => 'true'])->toArray()) ?>
                </div>
                <div class="col">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Créer une archive</h6>
                    <?= r($fs->compress('/Download/Tests', '/Download/Tests/tests.zip')->toArray()) ?>
                </div>
                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Lister le contenu d'une archive compressée</h6>
                    <?= r($fs->extractList('/Download/Tests/tests.zip')->toArray()) ?>
                </div>
                <div class="col-4">
                    <h6 class="card-subtitle mb-2 text-info">Extraire le contenu d'une archive compressée</h6>
                    <pre class="sh_php"> $fs->extract('/Download/Tests/tests.zip', '/Download/Tests/extractions');</pre>
                </div>
                <div class="col">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Voir les tâches en cours</h6>
                    <?= r($fs->backgroundTask()->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Supprimer les tâches terminées</h6>
                    <?= r($fs->clearFinishedTasks()) ?>
                </div>
            </div>

        </div>
    </div>
    <hr/>
</div>