<?php
namespace Tests\Rcnchris;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class BaseTestCase extends TestCase
{

    /**
     * Emplacement des tests
     */
    const TESTS_FOLDER = '/tests';

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @return \Faker\Generator
     */
    public function faker()
    {
        if (is_null($this->faker)) {
            $this->faker = Factory::create('fr_FR');
        }
        return $this->faker;
    }

    /**
     * Obtenir le chemin racine du projet
     *
     * @return string
     */
    protected function rootPath()
    {
        return dirname(__DIR__);
    }

    /**
     * Affiche un titre coloré en début de test
     *
     * @param string $titre Titre
     * @param bool   $isTest
     */
    protected function ekoTitre($titre = '', $isTest = false)
    {
        $methods = get_class_methods(get_class($this));
        $tests = array_map(function ($method) {
            if (substr($method, 0, 4) === 'test') {
                return $method;
            };
        }, $methods);
        $tests = count(array_filter($tests));
        if ($isTest === true) {
            $tests--;
        }
        $parts = explode(' - ', $titre);
        echo "\n\033[0;36m{$parts[0]}\033[m - {$parts[1]} (\033[0;32m$tests\033[m)\n";
    }

    /**
     * Obtenir le message d'erreur d'une assertion
     *
     * ### Exemple
     * - `$this->assertEquals(
     *      'ola'
     *      , Text::substr($phrase, -12, 3)
     *      , $this->getMessage('Démarrer avec une position négative et une longueur inférieure')
     * );`
     *
     * @param string $message Message brut
     *
     * @return string
     */
    protected function getMessage($message)
    {
        return "\033[0;33m$message\033[m";
    }

    /**
     * Supprime les espaces et retours à la ligne
     *
     * @param $string
     *
     * @return string
     */
    protected function trim($string)
    {
        $lines = explode(PHP_EOL, $string);
        $lines = array_map('trim', $lines);
        return implode('', $lines);
    }

    /**
     * Compare deux expressions en utilisant le trim de cette classe
     *
     * @param string $expected Chaîne de caractères à comparer
     * @param string $actual   Chaîne de caractères à comparer
     */
    protected function assertSimilar($expected, $actual)
    {
        $this->assertEquals($this->trim($expected), $this->trim($actual));
    }

    /**
     * @param      $requestMethod
     * @param      $requestUri
     * @param null $requestData
     *
     * @return \Psr\Http\Message\ResponseInterface|\Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );
        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);
        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }
        // Set up a response object
        $response = new Response();
        // Configuration
        $settings = require $this->rootPath() . '/app/config.php';
        // Instantiate the application
        $app = new App($settings);
        // Routes
        require $this->rootPath() . '/app/routes.php';
        // Process the application
        $response = $app->process($request, $response);
        // Return the response
        return $response;
    }
}