<?php
namespace Tests\Rcnchris;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Rcnchris\Core\ORM\DbFactory;
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
     * @var \PDO
     */
    protected $db;

    /**
     * Liste des fichiers de bases de données utilisés pour les tests
     *
     * @var array
     */
    protected $dbFiles = [];

    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        // Base de données des tests unitaires en mémoire
        $this->db = $this->makeDb('dbTests', 0, '', '', '', 'sqlite');

        // Base de données des tests unitaires dans un fichier
        //$fileName = $this->rootPath() . $this::TESTS_FOLDER . '/ORM/dbTests.sqlite';
        //$this->db = $this->makeDb('dbTests', 0, '', '', '', 'sqlite', $fileName);
        //array_push($this->dbFiles, $fileName);

        $this->seeds();
    }

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

    public function seeds()
    {
        // Catégories
        $this->db->query('DROP TABLE IF EXISTS categories');
        $this->db->query("CREATE TABLE IF NOT EXISTS categories (
          id INTEGER PRIMARY KEY AUTOINCREMENT
          , title VARCHAR(50));");
        $this->db->query("INSERT INTO categories (title) VALUES ('Article'), ('Page');");

        // Posts
        $this->db->query('DROP TABLE IF EXISTS posts');
        $this->db->query("CREATE TABLE IF NOT EXISTS posts (
          id INTEGER PRIMARY KEY AUTOINCREMENT
          , title VARCHAR(100)
          , category_id INTEGER
          , created DATETIME
          , modified DATETIME
        );");

        $sql = 'INSERT INTO posts (title, category_id, created, modified) VALUES ';
        for ($i = 1 ; $i <= 20 ; $i++) {
            $sql = $sql
                . "('" . $this->faker()->sentence(3) . "', "
                . rand(1, 3) . ", '"
                . $this->faker()->date("Y-m-d H:i:s") . "', '"
                . $this->faker()->date("Y-m-d H:i:s") . "'),";
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $this->db->query($sql);
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
     * Obtenir une instance de base de données
     *
     * @param string      $server
     * @param int         $port
     * @param string      $user
     * @param string      $password
     * @param string      $dbname
     * @param string|null $sgbd
     * @param string|null $file
     *
     * @return null|\PDO|string
     * @throws \Exception
     */
    protected function makeDb($server = '', $port = 0, $user = '', $password = '', $dbname = '', $sgbd = null, $file = null)
    {
        chdir($this->rootPath() . $this::TESTS_FOLDER . '/Core/ORM');
        $db = DbFactory::get($server, $port, $user, $password, $dbname, $sgbd, $file);
        chdir($this->rootPath());
        return $db;
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

    /**
     * Supprime les éléments utilisés pour les tests
     */
    public function tearDown()
    {
        // Fichiers des bases de données utilisés pour les tests
        foreach ($this->dbFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}