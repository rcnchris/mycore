<?php
return [
    'rootPath' => function () {
        return dirname(__DIR__);
    },
    /**
     * Nom de la configuration à utiliser
     * - local : Tests locaux
     * - dev : Tests Travis
     * - prod : Production
     */
    'config.name' => 'dev',

    /**
     * Personnalisation
     */
    'app.prefix' => '/_lab/mycore/',
    'app.poweredBy' => 'MRC Consulting',
    'app.name' => 'My Core',
    /**
     * Localisation
     */

    'app.charset' => 'utf-8',
    'app.timezone' => 'UTC',
    'app.defaultLocale' => 'fr_FR',
    'app.sep_decimal' => ',',
    'app.sep_mil' => ' ',
    'app.templates' => dirname(__DIR__) . '/app/Templates',
    'app.logsPath' => dirname(__DIR__) . '/logs/app.log',
    'datasources' => [
        'default' => [
            'host' => 'dbApp',
            'username' => '',
            'password' => '',
            'dbName' => 'dbApp',
            'sgbd' => 'sqlite',
            'port' => 0,
            'fileName' => realpath(dirname(__DIR__) . '/public/dbApp.sqlite')
        ],
        'test' => [
            'host' => 'dbTests',
            'username' => '',
            'password' => '',
            'dbName' => 'dbTests',
            'sgbd' => 'sqlite',
            'port' => 0,
            'fileName' => realpath(dirname(__DIR__) . '/Core/ORM/dbTests.sqlite')
        ]
    ]
];
