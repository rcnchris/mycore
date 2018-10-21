<?php
use Rcnchris\Core\Tools\Debug;

$env = new \Rcnchris\Core\Tools\Environnement($_SERVER);
$test=12.36987;
?>
<div class="row">
    <div class="col">
        <h2><code><?= Debug::getClass($env) ?></code></h2>
        <table class="table table-sm table-responsive">
            <tbody>
            <tr>
                <th>Méthodes</th>
                <td>
                    <code><?= Debug::getMethods($env)->join(', ') ?></code>
                </td>
            </tr>
            <tr>
                <th colspan="2" style="background:#CCC;">Utilisation</th>
            </tr>
            <tr>
                <th><code>get</code></th>
                <td>
                    <p class="lead">Obtenir les paramètres du serveur ou l'un d'entre eux</p>
                    <pre class="sh_php">$env->get()->count()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->get()->count() ?>
                    </div>
                    <pre class="sh_php">$env->get('HTTP_COOKIE')</pre>
                    <div class="alert alert-secondary">
                        <?= $env->get('HTTP_COOKIE') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getServerName</code></th>
                <td>
                    <p class="lead">Obtenir le nom du serveur</p>
                    <pre class="sh_php">$env->getServerName()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getServerName() ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getUname</code></th>
                <td>
                    <p class="lead">Obtenir le nom complet du serveur</p>
                    <pre class="sh_php">$env->getUname()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getUname() ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getIp</code></th>
                <td>
                    <p class="lead">Obtenir l'adresse IP du serveur</p>
                    <pre class="sh_php">$env->getIp()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getIp() ?>
                    </div>
                    <p class="lead">Obtenir l'adresse IP du client</p>
                    <pre class="sh_php">$env->getIp('remote')</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getIp('remote') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getUserAgent</code></th>
                <td>
                    <p class="lead">Obtenir le nom du navigateur du client</p>
                    <pre class="sh_php">$env->getUserAgent()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getUserAgent() ?>
                    </div>
                    <pre class="sh_php">$env->getUserAgent(null, true)</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getUserAgent(null, true) ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getApacheVersion</code></th>
                <td>
                    <p class="lead">Obtenir la version du serveur Apache</p>
                    <pre class="sh_php">$env->getApacheVersion()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getApacheVersion() ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getApacheModules</code></th>
                <td>
                    <p class="lead">Obtenir la liste des modules Apache</p>
                    <pre class="sh_php">$env->getApacheModules()->join(', ')</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getApacheModules()->join(', ') ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getApacheUser</code></th>
                <td>
                    <p class="lead">Obtenir le nom de l'utilisateru utilisé par Apache</p>
                    <pre class="sh_php">$env->getApacheUser()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getApacheUser() ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getPhpVersion</code></th>
                <td>
                    <p class="lead">Obtenir la version de PHP</p>
                    <pre class="sh_php">$env->getPhpVersion()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getPhpVersion() ?>
                    </div>
                    <pre class="sh_php">$env->getPhpVersion(false)</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getPhpVersion(false) ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th><code>getMysqlVersion</code></th>
                <td>
                    <p class="lead">Obtenir la version de MySQL</p>
                    <pre class="sh_php">$env->getMysqlVersion()</pre>
                    <div class="alert alert-secondary">
                        <?= $env->getMysqlVersion() ?>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <?= r($env) ?>
    </div>
    <div class="col">
        <?= r($env->getUserAgent(null, true)) ?>
        <?= r($env->getUserAgent()) ?>
        <?= r(Debug::isBool(true)) ?>
    </div>
</div>