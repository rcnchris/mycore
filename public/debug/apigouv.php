<?php
//r($adr);
//r($adr->searchCommunes('nom', 'toulon')->toArray());
//r($adr->getCommune(83123)->toArray());
//r($adr->getCommunesDuDepartement(83)->toArray());
//r($adr->searchDepartements()->toArray());
//r($adr->searchDepartements('nom', 'var')->toArray());
//r($adr->getDepartement(83)->toArray());
//r($adr->getRegions('93')->toArray());
?>

<div class="row">
    <div class="col-12">
        <hr/>
    </div>
    <div class="col-4">
        <h3>Régions</h3>
        <?= $html->selectRegions(['class' => 'form-control', 'multiple' => true]) ?>
    </div>
    <div class="col-4">
        <h3>Départements</h3>
        <?= $html->selectDepartements(['class' => 'form-control', 'multiple' => true]) ?>
    </div>
    <div class="col-4">
        <h3>Villes</h3>
        <?= $html->selectVilles(83, ['class' => 'form-control', 'multiple' => true]) ?>
    </div>
    <div class="col-12">
        <hr/>
    </div>
</div>
