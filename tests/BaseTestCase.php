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
     * Retour console bavard
     */
    const VERBOSE = true;

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
        if ($this::VERBOSE) {
            $this->ekoMessage('Création du fichier ' . basename($file));
        }
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
        if ($this::VERBOSE) {
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
        if ($this::VERBOSE) {
            return "\033[0;33m$message\033[m";
        }
    }

    protected function ekoMsgInfo($message)
    {
        if ($this::VERBOSE) {
            echo "\033[0;34m$message\n\033[m";
        }
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
     * ### Exemple
     * - `$this->assertArrayAccess($result, 1, ['id' => 2, 'title' => 'Page'], ['Exists', 'Get']);`
     *
     * @param object     $object  Objet à tester
     * @param string     $key     Nom d'une clé du tableau
     * @param mixed      $expect  Valeur attendue
     * @param array|null $methods Liste des méthodes à tester
     */
    protected function assertArrayAccess($object, $key, $expect, array $methods = [])
    {
        $class = get_class($object);
        $this->ekoMsgInfo("Implémentation ArrayAccess de $class");

        $this->assertArrayHasKey(
            'ArrayAccess'
            , class_implements($object)
            , $this->getMessage("L'instance de $class n'implémente pas l'interface ArrayAccess")
        );

        if (empty($methods)) {
            $methods = ['offsetExists', 'offsetGet', 'offsetSet', 'offsetUnset'];
        } else {
            $methods = array_map(function ($method) {
                return 'offset' . ucfirst($method);
            }, $methods);
        }

        foreach ($methods as $method) {

            if ($method === 'offsetExists') {
                $this->assertTrue(
                    isset($object[$key]),
                    $this->getMessage("Le comportement de ArrayAccess est incorrect dans le cas $method pour $class")
                );
            }
            if ($method === 'offsetGet') {
                $this->assertEquals(
                    $expect,
                    $object[$key],
                    $this->getMessage("Le comportement de ArrayAccess est incorrect dans le cas $method pour $class")
                );
            }
            if ($method === 'offsetSet') {
                $object[$key] = $expect;
                $this->assertEquals(
                    $expect,
                    $object[$key],
                    $this->getMessage("Le comportement de ArrayAccess est incorrect dans le cas $method pour $class")
                );
            }
            if ($method === 'offsetUnset') {
                unset($object[$key]);
                $this->assertFalse(
                    isset($object[$key]),
                    $this->getMessage("Le comportement de ArrayAccess est incorrect dans le cas $method pour $class")
                );
            }
        }
    }

    /**
     * Vérifie la présence d'une liste de méthodes dans un objet
     *
     * @param object $object  Instance de l'objet
     * @param array  $methods Liste des méthodes dont i lfaut vérifier la présence
     */
    public function assertObjectHasMethods($object, array $methods)
    {
        foreach ($methods as $methodName) {
            $this->assertTrue(
                method_exists($object, $methodName),
                $this->getMessage("La méthode $methodName n'existe pas dans la clase " . get_class($object))
            );
        }
    }


    /**
     * Vérifie si un objet est sérialisable
     *
     * @param $object
     */
    public function assertSerializable($object)
    {
        $class = get_class($object);
        $this->ekoMsgInfo("Implémentation Serializable de $class");

        $this->assertArrayHasKey(
            'Serializable'
            , class_implements($object)
            , $this->getMessage("L'instance de $class n'implémente pas l'interface Serializable")
        );

        $this->assertInternalType(
            'string'
            , $object->serialize()
            ,
            $this->getMessage("Le retour de la méthode 'serialize' n'est pas au format string pour l'instance de $class")
        );

        $this->assertTrue(
            method_exists($object, 'unserialize')
            , $this->getMessage("La méthode 'unserialize est absente de l'instance de $class")
        );
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
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );
        $request = Request::createFromEnvironment($environment);
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }
        $response = new Response();

        // Configuration
        $configFile = $this->rootPath() . '/app/config.php';
        if (file_exists($configFile)) {
            $settings = require $configFile;
        } else {
            $settings = [
                'app.prefix' => '/_lab/mycore/',
                'app.poweredBy' => 'MRC Consulting',
                'app.name' => 'My Core',
                'app.charset' => 'utf-8',
                'app.timezone' => 'UTC',
                'app.defaultLocale' => 'fr_FR',
                'app.sep_decimal' => ',',
                'app.sep_mil' => ' ',
                'app.templates' => dirname(__DIR__) . '/app/Templates',
                'app.logsPath' => dirname(__DIR__) . '/logs/app.log'
            ];
        }

        // Dépendances
        $dependancesFile = $this->rootPath() . '/app/dependances.php';
        if (file_exists($dependancesFile)) {
            $dependances = require $dependancesFile;
        } else {
            $dependances = [];
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
                if ($this::VERBOSE) {
                    $this->ekoMessage('Suppression du fichier ' . basename($file));
                }
                unlink($file);
            }
        }
    }

    /**
     * Obtenir la configuration des tests
     *
     * @param string|null $key Nom d'une clé de la configuration
     *
     * @return mixed
     */
    protected function getConfig($key = null)
    {
        $config = require $this->rootPath() . '/tests/config.php';
        if (is_null($key)) {
            return $config;
        } elseif (array_key_exists($key, $config)) {
            return $config[$key];
        }
        return false;
    }
}
