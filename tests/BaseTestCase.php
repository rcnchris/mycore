<?php
namespace Tests\Rcnchris;

use Faker\Factory;
use Faker\Generator;
use Michelf\MarkdownExtra;
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
     * Liste des fichiers utilisés pour les tests
     *
     * @var array
     */
    private $usedFiles = [];

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
     * Obtenir la liste des fichiers utilisés pour les tests
     *
     * @return array
     */
    public function getUsedFiles()
    {
        return $this->usedFiles;
    }

    /**
     * Ajoute un fichier à la liste des fichiers utilisés
     *
     * @param string $file Nom complet du fichier
     */
    public function addUsedFile($file)
    {
        $this->ekoMessage('Création du fichier ' . basename($file));
        array_push($this->usedFiles, $file);
    }

    /**
     * Affiche un titre coloré dans la console
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
     * Affiche un message coloré dans la console
     *
     * @param string $message Message à afficher
     * @param string $color   Couleur du message
     */
    protected function ekoMessage($message, $color = "\n\033[0;35m")
    {
        echo "$color{$message}\033[m\n";
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
     * Vérifie le comportement d'un objet qui implémente ArrayAccess
     *
     * @param object $object Objet à tester
     * @param string $key    Nom d'une clé du tableau
     * @param mixed  $expect Valeur attendue
     * @param array  $methods Liste des méthodes à tester
     */
    protected function assertArrayAccess($object, $key, $expect, array $methods = [])
    {
        if (empty($methods)) {
            $methods = ['offsetExists', 'offsetGet', 'offsetSet', 'offsetUnset'];
        }

        foreach ($methods as $method) {

            if ($method === 'offsetExists') {
                // offsetExists
                $this->assertTrue(
                    isset($object[$key])
                    , $this->getMessage("Le comportement de ArrayAccess est incorrect dans le cas offsetExists")
                );
            }
            if ($method === 'offsetGet') {
                // offsetGet
                $this->assertEquals(
                    $expect
                    , $object[$key]
                    , $this->getMessage("Le comportement de ArrayAccess est incorrect dans le cas offsetGet")
                );
            }
            if ($method === 'offsetSet') {
                $object[$key] = $expect;
                $this->assertEquals($expect, $object[$key]);
            }
            if ($method === 'offsetUnset') {
                unset($object[$key]);
                $this->assertFalse(isset($object[$key]));
            }
        }
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
        $configFile = $this->rootPath() . '/app/config.php';
        if (file_exists($configFile)) {
            $settings = require $configFile;
        } else {
            $settings = [
                'app.prefix' => '/',
                'app.poweredBy' => 'MRC Consulting',
                'app.name' => 'My Core',
                'app.charset' => 'utf-8',
                'app.timezone' => 'UTC',
                'app.defaultLocale' => 'fr_FR',
                'app.sep_decimal' => ',',
                'app.sep_mil' => ' ',
                'app.templates' => dirname(__DIR__) . '/app/Templates'
            ];
        }

        // Dépendances
        $dependancesFile = $this->rootPath() . '/app/dependances.php';
        if (file_exists($dependancesFile)) {
            $dependances = require $dependancesFile;
        } else {
            $dependances = [

            ];
        }

        // Instantiation de Slim
        $app = new App(array_merge($settings, $dependances));

        // Routes
        $routesFile = $this->rootPath() . '/app/routes.php';
        if (file_exists($routesFile)) {
            $routes = require $routesFile;
        } else {
            $rootPath = $this->rootPath();
            $app->get('/', function (Request $request, Response $response) use ($rootPath) {
                $readmeFile = $rootPath . '/README.md';
                if (file_exists($readmeFile)) {
                    $content = file_get_contents($readmeFile);
                    $readme = MarkdownExtra::defaultTransform($content);

                    $body = $response->getBody();
                    $body->write($readme);
                    $newResponse = $response
                        ->withStatus(200)
                        ->withBody($body);
                    return $newResponse;
                }
            })->setName('home');
        }

        // Process the application
        $response = $app->process($request, $response);
        // Return the response
        return $response;
    }

    /**
     * Est exécuté en fin de test
     */
    public function tearDown()
    {
        // Supprime les fichiers utilisés pour les tests
        foreach ($this->usedFiles as $file) {
            if (file_exists($file)) {
                $this->ekoMessage('Suppression du fichier ' . basename($file));
                unlink($file);
            }
        }
    }
}