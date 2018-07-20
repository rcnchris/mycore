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
        'jquery' => [
            'name' => 'jQuery',
            'core' => [
                '151' => [
                    'js' => [
                        'min' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js',
                    ],
                ],
                'latest' => [
                    'js' => [
                        'min' => 'https://code.jquery.com/jquery-3.3.1.slim.min.js',
                    ],
                ],
            ]
        ],
        'bootstrap' => [
            'name' => 'Bootstrap 4',
            'core' => [
                'latest' => [
                    'css' => [
                        'min' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',
                    ],
                    'js' => [
                        'min' => '/dist/js/bootstrap.min.js',
                        'src' => '/dist/js/bootstrap.js',
                    ],
                ]
            ],
        ],
        'popper' => [
            'name' => 'Popper',
            'core' => [
                'latest' => [
                    'js' => [
                        'min' => 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'
                    ]
                ]
            ]
        ],
        'datatables' => [
            'name' => 'Datatables',
            'core' => [
                'latest' => [
                    'css' => [
                        'src' => 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.css',
                        'min' => 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css'
                    ]
                ],

            ]
        ],
        'highcharts' => [
            'name' => 'Highcharts',
            'core' => [
                'latest' => [
                    'js' => [
                        'src' => 'https://code.highcharts.com/highcharts.js',
                        'min' => 'https://code.highcharts.com/highcharts.min.js',
                    ]
                ]
            ]
        ],

    ]
];
