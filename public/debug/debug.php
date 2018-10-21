<?php
use Rcnchris\Core\Apis\ApiGouv\AdressesApiGouv;
use Rcnchris\Core\PDF\AbstractPDFFullBehaviors;
use Rcnchris\Core\Tools\Debug;
use Rcnchris\Core\Tools\Environnement;
use Rcnchris\Core\Tools\Items;

$dg = Debug::getInstance();
$env = new Environnement($_SERVER);
?>
<div class="row">
    <div class="col-2">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"
               aria-controls="v-pills-home" aria-selected="true">
                La classe Debug <span class="badge badge-warning"><?= Debug::getMethods($dg)->count() ?></span> méthodes
            </a>
            <?php foreach (Debug::getMethods($dg) as $method): ?>
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
                <h2>Classe <small><code><?= $dg->getClass($dg) ?></code></small></h2>

                <div class="alert alert-info">
                    Elle permet d'obtenir diverses informations sur une variable<strong> et s'utilise de manière statique ou instanciée</strong>.
                </div>

                <h3>Parent(s)</h3>
                <?= $html->table($dg->getParents($dg), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Interface(s)</h3>
                <?= $html->table($dg->getInterfaces($dg), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Trait(s)</h3>
                <?= $html->table($dg->getTraits($dg), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Propriété(s)</h3>
                <?= $html->table($dg->getProperties($dg), ['class' => 'table table-sm table-responsive']) ?>

                <h3>Méthodes</h3>

                <div class="alert alert-secondary">
                    <code><?= Debug::getMethods($dg)->join(', ') ?></code>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-getInstance" role="tabpanel"
                 aria-labelledby="v-pills-getInstance-tab">
                <p class="lead">Obtenir l'instance du debugger</p>
                <pre class="sh_php">$dg = Debug::getInstance();</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getClass($dg) ?></code>
                </div>
            </div>

            <div class="tab-pane fade" id="v-pills-getClass" role="tabpanel" aria-labelledby="v-pills-getClass-tab">
                <p class="lead">Obtenir le nom de la classe d'un objet</p>
                <pre class="sh_php">Debug::getClass($env)</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getClass($env) ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getClassShortName" role="tabpanel"
                 aria-labelledby="v-pills-getClassShortName-tab">
                <p class="lead">Obtenir le nom court de la classe d'un objet</p>
                <pre class="sh_php">Debug::getClassShortName($env)</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getClassShortName($env) ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getProperties" role="tabpanel"
                 aria-labelledby="v-pills-getProperties-tab">
                <p class="lead">Obtenir la liste des propriétés d'un objet</p>
                <pre class="sh_php">Debug::getProperties(new \\DateInterval('P10D'))</pre>
                <?= $html->table(Debug::getProperties(new \DateInterval('P10D')), ['class' => 'table table-sm']) ?>
            </div>
            <div class="tab-pane fade" id="v-pills-getMethods" role="tabpanel" aria-labelledby="v-pills-getMethods-tab">
                <p class="lead">Obtenir la liste des méthodes d'un objet</p>
                <pre class="sh_php">Debug::getMethods(new \\DateInterval('P10D'))->join(', ')</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getMethods(new \DateInterval('P10D'))->join(', ') ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getParentsMethods" role="tabpanel"
                 aria-labelledby="v-pills-getParentsMethods-tab">
                <p class="lead">Obtenir la liste des méthodes des parents d'un objet</p>
                <pre class="sh_php">Debug::getParentsMethods(new AdressesApiGouv())->join(', ')</pre>
                <div class="alert alert-secondary">
                    <?= $html->table(Debug::getParentsMethods(new AdressesApiGouv())->toArray(),
                        ['class' => 'table table-sm']) ?>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getParents" role="tabpanel" aria-labelledby="v-pills-getParents-tab">
                <p class="lead">Obtenir la liste des parents d'un objet</p>
                <pre class="sh_php">Debug::getParents(new AdressesApiGouv())->join(', ')</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getParents(new AdressesApiGouv())->join(', ') ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getInterfaces" role="tabpanel"
                 aria-labelledby="v-pills-getInterfaces-tab">
                <p class="lead">Obtenir la liste des interfaces utilisées par un pbjet</p>
                <pre class="sh_php">Debug::getInterfaces(new Items([]))->join(', ')</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getInterfaces(new Items([]))->join(', ') ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getTraits" role="tabpanel" aria-labelledby="v-pills-getTraits-tab">
                <p class="lead">Obtenir la liste des traits utilisés par un objet</p>
                <pre class="sh_php">Debug::getTraits(new AbstractPDFFullBehaviors([]))->join(', ')</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getTraits(new AbstractPDFFullBehaviors([]))->join(', ') ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getNamespace" role="tabpanel"
                 aria-labelledby="v-pills-getNamespace-tab">
                <p class="lead">Obtenir le namespace d'un objet</p>
                <pre class="sh_php">Debug::getNamespace($env)</pre>
                <div class="alert alert-secondary">

                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-getType" role="tabpanel" aria-labelledby="v-pills-getType-tab">
                <p class="lead">Obtenir le type d'une variable</p>
                <pre class="sh_php">Debug::getType([])</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::getType([]) ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-isObject" role="tabpanel" aria-labelledby="v-pills-isObject-tab">
                <p class="lead">Vérifier qu'une variable est un objet</p>
                <pre class="sh_php">Debug::isObject($env)</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::isObject($env) ?></code>
                </div>
                <pre class="sh_php">Debug::isObject([])</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::isObject([]) ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-isArray" role="tabpanel" aria-labelledby="v-pills-isArray-tab">
                <p class="lead">Vérifier qu'une variable est un tableau</p>
                <pre class="sh_php">Debug::isArray([])</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::isArray([]) ?></code>
                </div>
                <pre class="sh_php">Debug::isArray($env)</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::isArray($env) ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-isBool" role="tabpanel" aria-labelledby="v-pills-isBool-tab">
                <p class="lead">Vérifier qu'une variable est un booléen</p>
                <pre class="sh_php">Debug::isBool(false)</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::isBool(false) ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-isType" role="tabpanel" aria-labelledby="v-pills-isType-tab">
                <p class="lead">Vérifier qu'une variable est un booléen</p>
                <pre class="sh_php">Debug::isType('bool', false)</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::isType('bool', false) ?></code>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-makeItems" role="tabpanel" aria-labelledby="v-pills-makeItems-tab">
                <p class="lead">Obtenir une instance de la classe <code>Items</code> à partir d'une liste d'éléments</p>
                <pre class="sh_php">Debug::makeItems(['ola', 'ola', 'oli'], false)</pre>
                <div class="alert alert-secondary">
                    <code><?= Debug::makeItems(['ola', 'ola', 'oli']) ?></code>
                </div>
            </div>
        </div>
    </div>
</div>
