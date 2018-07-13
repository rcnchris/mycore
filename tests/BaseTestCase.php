<?php
namespace Tests\Rcnchris;

use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rcnchris\Core\Config\ConfigContainer;

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
     * Mapping des interfaces et leur méthodes
     *
     * @var array
     */
    protected $mapMethodsInterfaces = [
        'ArrayAccess' => ['offsetExists', 'offsetGet', 'offsetSet', 'offsetUnset'],
        'Countable' => ['count'],
        'IteratorAggregate' => ['getIterator'],
        'Serializable' => ['serialize', 'unserialize'],
    ];

    /**
     * Méthodes magiques PHP
     *
     * @var array
     */
    protected $magicMethods = ['__get', '__set', '__toString'];

    /**
     * Configuration
     *
     * @var array
     */
    private $config;

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
    protected function addUsedFile($file)
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
     * Vérifie que les méthodes magiques de PHP sont fonctionnelles
     *
     * @param object     $object   Object à vérifier
     * @param string     $property Prorpiété de test
     * @param mixed      $expect   Valeur attendue
     * @param array|null $methods  Liste des méthodes à tester
     */
    protected function assertMagicMethods($object, $property, $expect, array $methods = [])
    {
        $allMethods = ['get', 'set', 'toString'];
        if (empty($methods)) {
            $methods = $allMethods;
        }

        if (in_array('get', $methods)) {
            $this->assertEquals($expect, $object->$property);
        }

        if (in_array('set', $methods)) {
            $object->$property = 'new';
            $this->assertEquals('new', $object->$property);
        }

        if (in_array('toString', $methods)) {
            $this->assertInternalType('string', (string)$object);
        }
    }

    /**
     * Vérifie le comportement d'un objet qui implémente ArrayAccess
     *
     * ### Exemple
     * - `$this->assertArrayAccess($result, 1, ['id' => 2, 'title' => 'Page'], ['Exists', 'Get']);`
     *
     * @param object     $object  Instance de l'objet à tester
     * @param string     $key     Nom d'une clé du tableau
     * @param mixed      $expect  Valeur attendue
     * @param array|null $methods Liste des méthodes à tester
     */
    protected function assertArrayAccess($object, $key, $expect, array $methods = [])
    {
        $interfaceName = 'ArrayAccess';
        $class = get_class($object);
        $this->ekoMsgInfo("Implémentation $interfaceName dans $class");

        $this->assertArrayHasKey(
            $interfaceName, class_implements($object),
            $this->getMessage("L'instance de $class n'implémente pas l'interface $interfaceName")
        );

        if (empty($methods)) {
            $methods = $this->mapMethodsInterfaces[$interfaceName];
        } else {
            $methods = array_map(function ($method) {
                return 'offset' . ucfirst($method);
            }, $methods);
        }

        foreach ($methods as $method) {
            if ($method === 'offsetExists') {
                $this->assertTrue(
                    isset($object[$key]),
                    $this->getMessage("Le comportement de $interfaceName est incorrect dans le cas $method pour $class")
                );
            }
            if ($method === 'offsetGet') {
                $this->assertEquals(
                    $expect,
                    $object[$key],
                    $this->getMessage("Le comportement de $interfaceName est incorrect dans le cas $method pour $class")
                );
            }
            if ($method === 'offsetSet') {
                $object[$key] = $expect;
                $this->assertEquals(
                    $expect,
                    $object[$key],
                    $this->getMessage("Le comportement de $interfaceName est incorrect dans le cas $method pour $class")
                );
            }
            if ($method === 'offsetUnset') {
                unset($object[$key]);
                $this->assertFalse(
                    isset($object[$key]),
                    $this->getMessage("Le comportement de $interfaceName est incorrect dans le cas $method pour $class")
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
     * Vérifie que l'instance d'un objet implémente une liste d'interfaces
     *
     * @param object       $object     Objet à tester
     * @param string|array $implements Liste des interfaces dont il faut vérifier l'implémentaion dans l'objet
     */
    protected function assertObjectImplementInterfaces($object, $implements)
    {
        if (is_string($implements)) {
            $implements = explode(',', $implements);
        }
        foreach ($implements as $interface) {
            $this->assertTrue(
                in_array($interface, class_implements($object)),
                $this->getMessage("L'objet " . get_class($object) . " n'implémente pas l'interface $interface !")
            );
        }
    }

    /**
     * Vérifie que l'instance d'un objet utilise une liste de traits
     *
     * @param object       $object Objet à tester
     * @param string|array $traits Liste des traits dont il faut vérifier l'utilisation dans l'objet
     */
    protected function assertObjectUseTraits($object, $traits)
    {
        if (is_string($traits)) {
            $traits = explode(',', $traits);
        }
        foreach ($traits as $trait) {
            $this->assertTrue(
                in_array($trait, class_uses($object)),
                $this->getMessage("L'objet " . get_class($object) . " n'utilise pas le trait $trait !")
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
            'Serializable', class_implements($object),
            $this->getMessage("L'instance de $class n'implémente pas l'interface Serializable")
        );

        $this->assertInternalType(
            'string', $object->serialize(),
            $this->getMessage("Le retour de la méthode 'serialize' n'est pas au format string pour l'instance de $class")
        );

        $this->assertTrue(
            method_exists($object, 'unserialize'),
            $this->getMessage("La méthode 'unserialize est absente de l'instance de $class")
        );
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

    /**
     * Obtenir une nouvelle requête PSR7
     *
     * @return ServerRequestInterface
     */
    protected function makeRequestPsr7()
    {
        return ServerRequest::fromGlobals();
    }

    /**
     * Obtenir une nouvelle réponse PSR7
     *
     * @param int    $status
     * @param array  $headers
     * @param null   $body
     * @param string $version
     * @param null   $reason
     *
     * @return ResponseInterface
     */
    protected function makeResponsePsr7(
        $status = 200,
        array $headers = [],
        $body = null,
        $version = '1.1',
        $reason = null
    ) {
        return new Response($status, $headers, $body, $version, $reason);
    }

    /**
     * Obtenir un nouveau conteneur de dépendances
     *
     * @param array|null $data Données du conteneur
     *
     * @return ContainerInterface
     */
    protected function makeContainer(array $data = [])
    {
        return new ConfigContainer($data);
    }
}
