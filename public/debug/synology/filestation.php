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

            <h6 class="card-subtitle mb-2 text-info">Configuration</h6>
            <?= r($fs->config('Info', 'get')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Recherche</h6>
            <?php r($fs->search('/Commun', 'chevrolet.jpg')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Liens partagés</h6>
            <?= r($fs->sharings(['limit' => 10], 'name')) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Un lien</h6>
            <?= r($fs->sharing('MvZFrSiKH')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Dossiers partagés</h6>
            <?= r($fs->sharedFolders()->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Dossiers virtuels</h6>
            <?= r($fs->virtualFolders()->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Favoris</h6>
            <?= r($fs->favorites()->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Un favori</h6>
            <?= r($fs->favorite('/video')) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Un thumb</h6>
            <pre class="sh_php"> $fs->thumb('/Commun/chevrolet.jpg', 'medium');</pre>
            <img src="<?= $fs->thumb('/Commun/chevrolet.jpg', 'medium') ?>" alt="Chevrolet" />
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Taille des fichiers/dossiers</h6>
            <?php r($fs->size('/Commun/chevrolet.jpg')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">MD5 d'un fichier</h6>
            <?php r($fs->md5File('/Commun/chevrolet.jpg')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Vérifier les permissions</h6>
            <?php r($fs->checkPerm('/Commun', 'fake.txt')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Copier un fichier dans un dossier partagé</h6>
            <pre class="sh_php"> $fs->uploadFile('fichier.txt', '/Commun');</pre>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Télécharger un fichier depuis un dossier partagé</h6>
            <pre class="sh_php"> $fs->download('/Commun/chevrolet.jpg');</pre>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Créer un dossier</h6>
            <pre class="sh_php"> $fs->createFolder('/Commun', 'Mycore');</pre>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Renommer un fichier/dossier</h6>
            <pre class="sh_php"> $fs->rename('/Commun/chevrolet.jpg', 'chevroletSS.jpg');</pre>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Copier/Déplacer un fichier/dossier</h6>
            <?= r($fs->copy('/Commun/chevrolet.jpg', '/Download', ['overwrite' => 'true'])->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Supprimer un fichier/dossier</h6>
            <pre class="sh_php"> $fs->delete('/Download/lefichier.txt');</pre>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Lister le contenu d'une archive compressée</h6>
            <?= r($fs->extractList('/Download/chevrolet.zip')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Extraire le contenu d'une archive compressée</h6>
            <pre class="sh_php"> $fs->extract('/Download/chevrolet.zip', '/Commun');</pre>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Créer une archive</h6>
            <?= r($fs->compress('/Download/Piles.xlsx', '/Download/piles.zip')->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Voir les tâches en cours</h6>
            <?= r($fs->backgroundTask()->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Supprimer les tâches terminées</h6>
            <?= r($fs->clearFinishedTasks()) ?>
            <hr/>

        </div>
    </div>
    <hr/>
</div>