<?php
use Rcnchris\Core\Tools\Debug;

?>
<div class="row">
    <div class="col-2">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"
               aria-controls="v-pills-home" aria-selected="true">
                Helper Html <span class="badge badge-warning"><?= Debug::getMethods($html)->count() ?></span>
                méthodes
            </a>
            <?php foreach (Debug::getMethods($html) as $method): ?>
                <a class="nav-link" id="v-pills-<?= $method ?>-tab" data-toggle="pill" href="#v-pills-<?= $method ?>"
                   role="tab" aria-controls="v-pills-<?= $method ?>" aria-selected="false">
                    <?= $method ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-10">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <h2>Classe
                    <small><code><?= Debug::getClass($html) ?></code></small>
                </h2>

                <div class="alert alert-info">
                    Helpers HTML
                </div>

                <h3>Parent(s)</h3>
                <?= $html->table(Debug::getParents($html), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Interface(s)</h3>
                <?= $html->table(Debug::getInterfaces($html), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Trait(s)</h3>
                <?= $html->table(Debug::getTraits($html), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Propriété(s)</h3>
                <?= $html->table(Debug::getProperties($html), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Méthodes</h3>

                <div class="alert alert-secondary">
                    <code><?= Debug::getMethods($html)->join(', ') ?></code>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-getInstance" role="tabpanel"
                 aria-labelledby="v-pills-getInstance-tab">
                <h2>Obtenir l'instance du helper</h2>
                <hr>
                <pre class="sh_php">$html = Html::getInstance();</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getClass($html) ?></code>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-link" role="tabpanel"
                 aria-labelledby="v-pills-link-tab">
                <h2>Créer un lien</h2>
                <hr>
                <pre class="sh_php">$html->link('https://www.google.com', 'Google', ['class' => 'btn btn-sm btn-primary', 'target' => '_blank']);</pre>
                <?= $html->link('https://www.google.com', 'Google',
                    ['class' => 'btn btn-sm btn-primary', 'target' => '_blank']) ?>
            </div>

            <div class="tab-pane fade" id="v-pills-img" role="tabpanel"
                 aria-labelledby="v-pills-img-tab">
                <h2>Afficher une image</h2>
                <hr>
                <pre
                    class="sh_php">$html->img('http://lorempicsum.com/nemo/350/200/1', ['class' => 'img-responsive']);</pre>
                <?= $html->img('http://lorempicsum.com/nemo/350/200/1', ['class' => 'img-responsive']) ?>
            </div>

            <div class="tab-pane fade" id="v-pills-liste" role="tabpanel"
                 aria-labelledby="v-pills-liste-tab">
                <h2>Afficher une liste</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Liste simple</h3>
                            <pre class="sh_php">$html->liste(Debug::getMethods($html));</pre>
                            <?= $html->liste(Debug::getMethods($html)) ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Liste numérotée</h3>
                            <pre class="sh_php">$html->liste(Debug::getMethods($html), ['type' => 'ol']);</pre>
                            <?= $html->liste(Debug::getMethods($html), ['type' => 'ol']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-details" role="tabpanel"
                 aria-labelledby="v-pills-details-tab">
                <h2>Afficher un detail</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->details('Voir le détail', 'Ola les gens');</pre>
                            <?= $html->details('Voir le détail', 'Ola les gens') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-surround" role="tabpanel"
                 aria-labelledby="v-pills-surround-tab">
                <h2>Entourrer un contenu d'une balise</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->surround('Le contenu', 'code');</pre>
                            <?= $html->surround('Le contenu', 'code') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-source" role="tabpanel"
                 aria-labelledby="v-pills-source-tab">
                <h2>Afficher du code source</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->source("\$html->surround('Le contenu', 'code');");</pre>
                            <?= $html->source("\$html->surround('Le contenu', 'code');") ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->source(ROOT . '/public/index.php');");</pre>
                            <?= $html->source(ROOT . '/public/index.php') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-table" role="tabpanel"
                 aria-labelledby="v-pills-table-tab">
                <h2>Afficher un tableau</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre
                                class="sh_php">$html->table(['ola', 'ole', 'oli'], ['class' => 'table table-dark']);</pre>
                            <?= $html->table(['ola', 'ole', 'oli'], ['class' => 'table']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-icon" role="tabpanel"
                 aria-labelledby="v-pills-icon-tab">
                <h2>Afficher une icône</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->icon('eye');</pre>
                            <?= $html->icon('eye') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-input" role="tabpanel"
                 aria-labelledby="v-pills-input-tab">
                <h2>Afficher un champ de saisie</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->input();</pre>
                            <?= $html->input() ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-parseAttributes" role="tabpanel"
                 aria-labelledby="v-pills-parseAttributes-tab">
                <h2>Parser les attributs d'un tag</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->parseAttributes(['href' => 'https://google.com', 'class' => 'btn btn-sm']);</pre>
                            <?= $html->parseAttributes(['href' => 'https://google.com', 'class' => 'btn btn-sm']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-css" role="tabpanel"
                 aria-labelledby="v-pills-css-tab">
                <h2>Afficher lien vers un fichier CSS</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">htmlentities($html->source($html->css('app')));</pre>
                            <?= htmlentities($html->source($html->css('app'))) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-script" role="tabpanel"
                 aria-labelledby="v-pills-script-tab">
                <h2>Afficher lien vers un fichier Javascript</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">htmlentities($html->source($html->script('app')));</pre>
                            <?= htmlentities($html->source($html->script('app'))) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-getCdns" role="tabpanel"
                 aria-labelledby="v-pills-getCdns-tab">
                <h2>Obtenir le contenu des CDNs</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->getCdns();</pre>
                            <?= $html->getCdns() ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-setCdns" role="tabpanel"
                 aria-labelledby="v-pills-setCdns-tab">
                <h2>Définir le contenu des CDNs</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <pre class="sh_php">$html->setCdns($container['cdns']);</pre>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-field" role="tabpanel"
                 aria-labelledby="v-pills-field-tab">
                <h2>Générer un composant de saisie</h2>
                <hr>
                <div class="card-columns">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Texte stylé</h3>
<pre class="sh_php">
$html->field(
    'title',
    'Le titre qui va bien',
    ['class' => 'form-control']
);
</pre>
                            <?= $html->field('title', 'Le titre qui va bien', ['class' => 'form-control', 'required' => true]) ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Liste déroulante</h3>
<pre class="sh_php">
$html->field(
    'liste_id',
    'La liste',
    [
        'items' => ['ola','ole','oli'],
        'class' => 'form-control'
    ]);
</pre>
                            <?= $html->field('liste_id', 'La liste',
                                ['items' => ['ola', 'ole', 'oli'], 'class' => 'form-control']) ?>
                            <hr>
                            <h3 class="card-title">Liste déroulante à choix multiple</h3>
<pre class="sh_php">
$html->field(
    'liste_id',
    'La liste',
    [
        'items' => ['ola','ole','oli'],
        'class' => 'form-control',
        'multiple' => true
    ]);
</pre>
                            <?= $html->field('liste_id', 'La liste',
                                ['items' => ['ola', 'ole', 'oli'], 'class' => 'form-control', 'multiple' => true]) ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Zone de texte</h3>
<pre class="sh_php">
$html->field(
    'description',
    'Description',
    [
        'type' => 'textarea',
        'class' => 'form-control',
        'placeholder' => 'Lachez-vous !'
    ]);
</pre>
                            <?= $html->field('description', '', ['type' => 'textarea', 'class' => 'form-control', 'placeholder' => 'Lachez-vous !']) ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Fichier</h3>
<pre class="sh_php">
$html->field(
    'fileName',
    null,
    [
        'type' => 'file',
        'class' => 'form-control'
    ]);
</pre>
                            <?= $html->field('fileName', null, ['class' => 'form-control', 'type' => 'file']) ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Case à cocher</h3>
<pre class="sh_php">
$html->field(
    'choix',
    true,
    [
        'type' => 'checkbox',
        'label' => 'Choisir'
        'class' => 'form-control'
    ]);
</pre>
                            <?= $html->field('choix', true, ['class' => 'form-control', 'type' => 'checkbox', 'label' => 'Choisir']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-button" role="tabpanel"
                 aria-labelledby="v-pills-button-tab">
                <h2>Générer un bouton</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Bouton</h3>
                            <pre class="sh_php">$html->button('Envoyer');</pre>
                            <?= $html->button('Envoyer') ?>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Bouton stylé</h3>
                            <pre
                                class="sh_php">$html->button('Envoyer', 'submit', ['class' => 'btn btn-success']);</pre>
                            <?= $html->button('Envoyer', 'submit', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-selectRegions" role="tabpanel"
                 aria-labelledby="v-pills-selectRegions-tab">
                <h2>Générer une liste déroulante qui contient les régions françaises</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Régions</h3>
                            <pre class="sh_php">$html->selectRegions(['class' => 'form-control']);</pre>
                            <?= $html->selectRegions(['class' => 'form-control', 'multiple' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-selectDepartements" role="tabpanel"
                 aria-labelledby="v-pills-selectDepartements-tab">
                <h2>Générer une liste déroulante qui contient les départements français</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Départements</h3>
                            <pre class="sh_php">$html->selectDepartements(['class' => 'form-control']);</pre>
                            <?= $html->selectDepartements(['class' => 'form-control', 'multiple' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-selectVilles" role="tabpanel"
                 aria-labelledby="v-pills-selectVilles-tab">
                <h2>Générer une liste déroulante qui contient les villes du département du Var</h2>
                <hr>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Villes du Var</h3>
                            <pre class="sh_php">$html->selectVilles('83', ['class' => 'form-control']);</pre>
                            <?= $html->selectVilles('83', ['class' => 'form-control', 'multiple' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
