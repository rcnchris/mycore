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
    ],
    /**
     * CDN locaux et distants
     */
    'cdn' => [
        'highcharts' => [
            'name' => 'Highcharts',
            'core' => [
                'js' => [
                    'src' => 'https://code.highcharts.com/highcharts.js',
                    'min' => 'https://code.highcharts.com/highcharts.min.js',
                ]
            ]
        ],
        'jquery' => [
            'name' => 'jQuery',
            'path' => '/components/jquery',
            'core' => [
                'js' => [
                    'min' => '/jquery.min.js',
                    'src' => '/jquery.js',
                ],
            ]
        ],
        'bootstrap4' => [
            'name' => 'Bootstrap 4',
            'path' => '/twbs/bootstrap',
            'core' => [
                'css' => [
                    'min' => '/dist/css/bootstrap.min.css',
                    'src' => '/dist/css/bootstrap.css',
                ],
                'js' => [
                    'min' => '/dist/js/bootstrap.min.js',
                    'src' => '/dist/js/bootstrap.js',
                ],
            ],
        ],
        'datatables' => [
            'name' => 'Datatables',
            'core' => [
                'css' => [
                    'src' => 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.css',
                    'min' => 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css'
                ]
            ]
        ]
    ]
];
