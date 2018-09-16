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