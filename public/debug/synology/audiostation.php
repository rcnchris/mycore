<?php
$audio = new \Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage($syno);
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
                    class="sh_php"> $audio = new \Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage($syno);</pre>

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

            <h6 class="card-subtitle mb-2 text-info">Configuration</h6>
            <?= r($audio->config()->toArray()) ?>
            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Songs</h6>
            <?= r($audio->songs(['limit' => 10], 'title')) ?>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Song</h6>
            <?= r($audio->song('music_v_77898')->toArray()) ?>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Song entity</h6>
            <?php $song = $audio->song('music_v_77898', true) ?>
            <?= r($song) ?>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Champs de l'entité</h6>
            <?= r($song->getFields()) ?>

            <hr/>

            <h6 class="card-subtitle mb-2 text-info">Accéder à la valeur d'un champ</h6>
            <?= r($song->title) ?>

        </div>
    </div>
    <hr/>
</div>