<?php
$audio = $syno->getPackage('AudioStation');
?>
<div class="col">
    <div class="card">

        <div class="card-header">
            <h4>
                <?= $audio->getName() ?>
                <small><span class="badge badge-secondary"><?= $audio->getVersion() ?></span></small>
            </h4>
        </div>

        <div class="card-body">

                <pre
                    class="sh_php"> $audio = $syno->getPackage('AudioStation');</pre>

            <h5 class="card-title">Classe : <code><?= get_class($audio) ?></code></h5>

            <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>

            <div class="alert alert-secondary">
                <code><?= implode(', ', get_class_methods($audio)) ?></code>
            </div>

            <h6 class="card-subtitle mb-2 text-info">APIs</h6>

            <div class="alert alert-secondary">
                <code><?= $audio->getMethods()->join() ?></code>
            </div>

            <?php

            // r($audio);
            //        $albums = $audio->albums(null, ['limit' => 10])->toArray();
            //        r($audio->albums(null, ['limit' => 10])->toArray());
            //        r($audio->albums('IAM', ['limit' => 10])->toArray());
            //        r($audio->albums('IAM', ['limit' => 10], 'name'));
            //        r($audio->artists(['limit' => 10])->toArray());
            //        r($audio->artists(['limit' => 10], 'name'));
            //        r($audio->composers(['limit' => 10])->toArray());
            //        r($audio->composers(['limit' => 10], 'name'));
            //        r($audio->genres(['limit' => 10])->toArray());
            //        r($audio->genres(['limit' => 10], 'name'));
            //        r($audio->folders(['limit' => 10])->toArray());
            //        r($audio->folder('dir_24')->toArray());
            //        r($audio->folder('dir_24', true));
            //        r($audio->playlists()->toArray());
            //        r($audio->playlists('name'));
            //        r($audio->playlist('playlist_shared_normal/346')->toArray());
            //        r($audio->playlist('playlist_shared_normal/346', true));
            //        r($audio->radios()->toArray());
            //        r($audio->radios('title'));
            //        r($audio->remotes()->toArray());
            //        r($audio->remotes('name'));
            //        r($audio->remote('F4CAE55B33A0')->toArray());
            //        r($audio->remote('F4CAE55B33A0', true));
            //        r($audio->remotePlaylist('F4CAE55B33A0')->toArray());
            //        r($audio->remotePlaylist('F4CAE55B33A0', true));
            //        r($audio->servers()->toArray());
            //        r($audio->servers([], 'title'));
            //        r($audio->songs(['limit' => 10])->toArray());
            //        r($audio->songs(['limit' => 10], 'title'));
            //        r($audio->song('music_v_77900')->toArray());
            //        r($audio->song('music_v_77900', true));
            //        r($audio->searchSongs('u-turn')->toArray());
            //        r($audio->searchSongs('u-turn', [], 'title'));
            ?>
            <hr/>

            <h5 class="card-title">Info</h5>
            <h6 class="card-subtitle mb-2 text-info">Configuration</h6>
            <?= r($audio->config()->toArray()) ?>
            <hr/>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Album</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->albums(['limit' => 3])->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->albums(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste pour un artiste</h6>
                    <?= r($audio->albums(['artist' => 'IAM', 'limit' => 3])->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->albums(['artist' => 'IAM', 'limit' => 3], 'name')) ?>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Artist</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->artists(['limit' => 3])->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->artists(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Composer</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->composers(['limit' => 3])->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->composers(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Folder</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->folders(['limit' => 3])->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->folders(['limit' => 3], 'title')) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Un dossier</h6>
                    <?= r($audio->folder('dir_19')->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Entité</h6>
                    <pre class="sh_php"> $audio->folder('dir_19', true);</pre>
                    <?php
                    $item = $audio->folder('dir_19', true);
                    r($item);
                    ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Champs de l'entité</h6>
                    <?= r($item->getFields()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Accéder à la valeur d'un champ</h6>
                    <?= r($item->title) ?>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Genre</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->genres()->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->genres(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-6">

                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Playlist</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->playlists()->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->playlists(['limit' => 3], 'name')) ?>
                </div>
                <div class="col-6">

                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Radio</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->radios()->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->radios(['limit' => 2], 'title')) ?>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Remote</code> - Lecteurs distants</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->remotes()->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->remotes('name')) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Un lecteur</h6>
                    <?= r($audio->remote('F4CAE55B33A0')->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Entité</h6>
                    <pre class="sh_php"> $audio->remote('F4CAE55B33A0', true);</pre>
                    <?php
                    $item = $audio->remote('F4CAE55B33A0', true);
                    r($item);
                    ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Champs de l'entité</h6>
                    <?= r($item->getFields()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Accéder à la valeur d'un champ</h6>
                    <?= r($item->state) ?>

                    <hr/>
                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Server</code> - Serveurs multimédias</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->servers()->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->servers(['limit' => 2], 'title')) ?>
                </div>
                <div class="col-6">

                </div>
                <div class="col-12">
                    <hr/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="card-title"><code>Song</code></h5>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Liste</h6>
                    <?= r($audio->songs(['limit' => 10])->toArray()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Extraction</h6>
                    <?= r($audio->songs(['limit' => 10], 'title')) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Un morceau</h6>
                    <?= r($audio->song('music_v_77898')->toArray()) ?>
                </div>
                <div class="col-6">
                    <h6 class="card-subtitle mb-2 text-info">Entité</h6>
                    <pre class="sh_php"> $audio->song('music_v_77898', true);</pre>
                    <?php
                    $item = $audio->song('music_v_77898', true);
                    r($item);
                    ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Champs de l'entité</h6>
                    <?= r($item->getFields()) ?>
                    <hr/>
                    <h6 class="card-subtitle mb-2 text-info">Accéder à la valeur d'un champ</h6>
                    <?= r($item->title) ?>
                </div>
                <div class="col">
                    <hr/>
                </div>
            </div>

        </div>
    </div>
    <hr/>
</div>