<?php
use Rcnchris\Core\Html\Html;
use Rcnchris\Core\Tools\Debug;

$o = new \Rcnchris\Core\Apis\ApiGouv\AdressesApiGouv();
?>
<h2>Classe
    <small><code><?= Debug::getClass($o) ?></code></small>
</h2>

<div class="alert alert-info">

</div>

<h3>Parent(s)</h3>
<?= Html::table(Debug::getParents($o), ['class' => 'table table-sm table-responsive']) ?>

<h3>Interface(s)</h3>
<?= Html::table(Debug::getInterfaces($dg), ['class' => 'table table-sm table-responsive']) ?>

<h3>Trait(s)</h3>
<?= Html::table(Debug::getTraits($dg), ['class' => 'table table-sm table-responsive']) ?>

<h3>Propriété(s)</h3>
<?= Html::table(Debug::getProperties($dg), ['class' => 'table table-sm table-responsive']) ?>

<h3>Méthodes</h3>

<div class="alert alert-secondary">
    <code><?= Debug::getMethods($dg)->join(', ') ?></code>
</div>