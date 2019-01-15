<?php
namespace Tests\Functional;

use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Tests\Rcnchris\BaseTestCase;

class FunctionalTestCase extends BaseTestCase
{
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = false;

    /**
     * Chemin du fichier des dépendances
     */
    const MID_PATH = __DIR__ . '/../app/middlewares.php';

    /**
     * Chemin du fichier routes
     */
    const RTE_PATH = __DIR__ . '/../app/routes.php';

    public function runApp($method, $url, $data = null)
    {
        // Request
        $env = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $url,
            'BASEURL' => $this->getConfig('baseUrl'),
        ]);
        $request = Request::createFromEnvironment($env);
        if (isset($data)) {
            $request = $request->withParsedBody($data);
        }

        // Response
        $response = new Response();

        // Configuration
        $settings = require self::CONFIG_PATH;

        // App Slim
        $app = new App($settings);

        // Dépendances
        require self::DEP_PATH;

        // Middlewares
        if ($this->withMiddleware) {
            require self::MID_PATH;
        }

        // Routes
        require self::RTE_PATH;

        // Process the application
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }
}
