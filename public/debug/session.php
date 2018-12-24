<?php
use Rcnchris\Core\Tools\Debug;

$session = new \Rcnchris\Core\Session\PHPSession('mycore');
?>
    <h2>Session</h2>
    <h3>Parent(s)</h3>
<?= $html->table(Debug::getParents($session), ['class' => 'table table-sm table-responsive']) ?>
    <h3>Interface(s)</h3>
    <code><?= join(', ', Debug::getInterfaces($session)->toArray()) ?></code>
    <h3>Méthodes</h3>

    <div class="alert alert-secondary">
        <code><?= Debug::getMethods($session)->join(', ') ?></code>
    </div>
<?php
r($session);
r($session->get());