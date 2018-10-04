<div class="row">

    <div class="col-6">
        <h2>Manager de sources de données</h2>
        <pre class="sh_php"> $manager = new SourcesManager($config->get('datasources'));</pre>
        <?php
        $manager = new \Rcnchris\Core\ORM\SourcesManager($config->get('datasources'));
        r($manager);
        ?>
        <hr/>
        <h2>Sources de données</h2>
        <pre class="sh_php"> $manager->getSources();</pre>
        <table class="table table-sm">
            <tbody>
            <?php foreach ($manager->getSources() as $name => $cf): ?>
                <tr>
                    <td><?= $name ?></td>
                    <td><?= $cf['host'] ?></td>
                    <td><?= $cf['username'] ?></td>
                    <td><?= $cf['sgbd'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <hr/>
        <h2>Instance PDO</h2>
        <pre class="sh_php"> $db = $manager->connect('demo');</pre>
        <?php
        $db = $manager->connect('demo');
        r($db->getAvailableDrivers());
        ?>
    </div>

    <div class="col-6">
        <h2>Une Table</h2>
        <pre class="sh_php"> $users = new Table('users', $db);</pre>
        <?php
        $users = new \Rcnchris\Core\ORM\Table('users', $db);
        r($users);
        ?>
        <hr/>
        <h2>Une requête à partir de la table</h2>
        <pre class="sh_php"> $users->query()->all()->toArray();</pre>
        <table class="table table-sm">
            <thead>
            <tr>
                <th>Login</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>eMail</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users->query()->all()->toArray() as $user): ?>
                <tr>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['prenom'] ?></td>
                    <td><?= $user['nom'] ?></td>
                    <td><?= $user['email'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <hr/>
        <h2>Une requête à partir d'une connexion PDO</h2>
        <?php
        $query = new \Rcnchris\Core\ORM\Query($db);
        ?>
        <pre class="sh_php"> $query->from('medias')->where("extension = 'jpg'")->all()->toArray();</pre>
        <table class="table table-sm">
            <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Taille</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($query->from('medias')->where("extension = 'jpg'")->all()->toArray() as $user): ?>
                <tr>
                    <td><?= $user['title'] ?></td>
                    <td><?= $user['description'] ?></td>
                    <td><?= $user['size'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <pre class="sh_sql"><?= $query->__toString() ?></pre>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <hr/>
    </div>
    <div class="col-6">
        <h2>Modèle</h2>
        <?php
        $users = new \Tests\Rcnchris\Core\ORM\UsersModel($db);
        r($users);
        ?>
    </div>
    <div class="col-6">
        <h2>Méthodes</h2>

        <div class="row">
            <div class="col-6">
                <p>Nom du modèle</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->getName();</pre>
                <div class="alert alert-secondary">
                    <?= $users->getName() ?>
                </div>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Nom de la table</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->getTableName();</pre>
                <div class="alert alert-secondary">
                    <?= $users->getTableName() ?>
                </div>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Alias de la table</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->getAlias();</pre>
                <div class="alert alert-secondary">
                    <?= $users->getAlias() ?>
                </div>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Nombre d'enregistrements</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->count();</pre>
                <div class="alert alert-secondary">
                    <?= $users->count() ?>
                </div>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Obtenir les enregistrements dans un tableau</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->->findAll()->all()->toArray();</pre>
                <?= r($users->findAll()->all()->toArray()) ?>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                Obtenir une liste des enregistrements
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->findList('prenom');</pre>
                <?= r($users->findList('prenom')) ?>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Obtenir les propriétés d'une entité</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->getProperties();</pre>
                <?= r($users->getProperties()) ?>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Obtenir un enregistrement par son identifiant</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->find(1);</pre>
                <?= r($users->find(1)) ?>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Obtenir un enregistrement par la valeur d'un champ</p>
            </div>
            <div class="col-6">
                <pre class="sh_php"> $users->findBy('username', 'rcn');</pre>
                <?= r($users->findBy('username', 'rcn')) ?>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Obtenir l'instance de la table</p>
            </div>
            <div class="col-4">
                <pre class="sh_php"> $users->getTable();</pre>
                <?= r($users->getTable()) ?>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>
    </div>
</div>