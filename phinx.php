<?php
/**
 * Fichier phinx.php du 19/03/2018
 * Description : Fichier de configuration de Phinx
 *
 * PHP version 5
 *
 * @category Bases de données
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.3.4>
 */

require 'public/index.php';

/**
 * Récupération des chemins de migration et seeding de chaque module
 */
$migrationsPath = [];
$seedingsPath = [];
foreach ($mods as $mod) {
    if ($mod::MIGRATIONS && is_dir($mod::MIGRATIONS)) {
        $migrationsPath[] = $mod::MIGRATIONS;
    }
    if ($mod::SEEDS && is_dir($mod::SEEDS)) {
        $seedingsPath[] = $mod::SEEDS;
    }
}

return [
    'paths' => [
        'migrations' => $migrationsPath,
        'seeds' => $seedingsPath
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'sqlite',
            'name' => $app->getContainer()->get('app.root') . '/public/dbApp',
            //'connection' => $app->getContainer()->get('dbApp'),
            'charset' => 'utf8'
        ]
    ]
];
