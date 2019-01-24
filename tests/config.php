<?php
return [
    'debug' => true,
    'rootPath' => dirname(__DIR__),

    /**
     * Nom de la configuration à utiliser
     * - local : Tests locaux (Tous les tests)
     * - dev : Tests Travis (Tests elligibles pour Travis)
     * - prod : Production (Production)
     */
    'configName' => 'local',

    /**
     * Personnalisation
     */
    'appPrefix' => '/_lab/mycore/',
    'poweredBy' => 'MRC Consulting',
    'appName' => 'My Core',

    /**
     * Localisation
     */
    'charset' => 'utf-8',
    'timezone' => new \DateTimeZone('Europe/Paris'),
    'locale' => 'fr_FR',
    'sep_decimal' => ',',
    'sep_mil' => ' ',
    'templates' => dirname(__DIR__) . '/src/App/templates',
    'logs' => dirname(__DIR__) . '/logs/app.log',

    'phpVersion' => PHP_VERSION,
    'sapi' => PHP_SAPI,

    /**
     * Database Datasources
     */
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
        'demo' => [
            'host' => '192.168.1.2',
            'username' => 'demo',
            'password' => 'demo',
            'dbName' => 'demo',
            'sgbd' => 'mysql',
            'port' => 3306
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
        'app' => [
            'name' => 'MyApp',
            'core' => [
                'latest' => [
                    'css' => [
                        'src' => 'public/css/app.css',
                    ],
                    'js' => [
                        'src' => 'public/js/app.js',
                    ],
                ],
            ]
        ],
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
                        'min' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css',
                    ],
                    'js' => [
                        'min' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js',
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
        'shjs' => [
            'name' => 'SHJS',
            'core' => [
                'latest' => [
                    'css' => [
                        'min' => '/cdn/vendor/shjs/css/sh_acid.min.css',
                    ],
                    'js' => [
                        'min' => '/cdn/vendor/shjs/sh_main.min.js',
                    ],
                ]
            ],
        ],
        'fontawesome' => [
            'name' => 'FontAwesome',
            'core' => [
                'latest' => [
                    'js' => [
                        'src' => 'https://use.fontawesome.com/releases/v5.0.8/js/all.js',
                    ]
                ],

            ],
        ],
    ]
];
