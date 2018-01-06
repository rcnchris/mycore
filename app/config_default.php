<?php
return [
    'settings' => [
        'debug' => true
        , 'displayErrorDetails' => function($c) {
            return $c->debug;
        }
        , 'addContentLengthHeader' => true // Allow the web server to send the content-length header
        , 'determineRouteBeforeAppMiddleware' => true

        // Renderer settings
        , 'renderer' => [
            'template_path' => 'app/Views/',
        ]
    ]

    , 'debug' => function($c) {
        return $c->get('settings')['debug'];
    }

    , 'view' => function ($c) {
        $dir = dirname(__DIR__);
        $view = new \Slim\Views\Twig($dir . '/app/Views', [
            'cache' => $c->debug ? false : $dir . '/tmp/cache',
            'debug' => $c->debug
        ]);
        if ($c->debug) {
            $view->addExtension(new Twig_Extension_Debug());
        }
        $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

        foreach ($c['twig.extensions'] as $extension) {
            if (is_string($extension)) {
                $view->addExtension(new $extension());
            } elseif (is_object($extension)) {
                $view->addExtension($extension);
            }
        }
        $view->getEnvironment()->addGlobal('debug', $c->get('debug'));

        return $view;
    }

    , 'twig.extensions' => function () {
        return [
            Rcnchris\Core\Twig\DebugExtension::class
            , Rcnchris\Core\Twig\TextExtension::class
            , Rcnchris\Core\Twig\IconsExtension::class
        ];
    }

    /**
     * Page 404
     */
    , 'notFoundHandler' => function (\Psr\Container\ContainerInterface $c) {
        return function (\Slim\Http\Request $request, \Slim\Http\Response $response) use ($c) {
            $twig = $c->get('view');
            $twig->getLoader()->addPath(dirname(__DIR__) . '/app/Views/');
            $response = $response->withStatus(404);
            return $twig->render($response, '404', compact('request', 'response'));
        };
    }

    /**
     * Personnalisation
     */
    , 'app.prefix' => '/_lab/mycore/'
    , 'app.poweredBy' => 'MRC Consulting'
    , 'app.name' => 'My Core'

    /**
     * Localisation
     */
    , 'app.charset' => 'utf-8'
    , 'app.timezone' => 'UTC'
    , 'app.defaultLocale' => 'fr_FR'
    , 'app.sep_decimal' => ','
    , 'app.sep_mil' => ' '

    , 'datasources' => [
        'default' => [
            'host' => 'localhost'
            , 'username' => 'user'
            , 'password' => 'secret'
            , 'dbName' => 'home'
            , 'sgbd' => 'mysql'
            , 'port' => 3306
        ]
        , 'codes' => [
            'host' => 'localhost'
            , 'username' => 'user'
            , 'password' => 'secret'
            , 'dbName' => 'codes'
            , 'sgbd' => 'mysql'
            , 'port' => 3306
        ]
    ]
];
