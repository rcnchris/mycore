<?php
/**
 * Fichier TableFactory.php du 28/06/2018
 * Description : Fichier de la classe TableFactory
 *
 * PHP version 5
 *
 * @category ORM
 *
 * @package  App\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM;

use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Rcnchris\Core\ORM\Cake\CakeTable;
use Rcnchris\Core\ORM\Phinx\PhinxTable;
use Cake\ORM\TableRegistry;
use Phinx\Db\Adapter\AdapterFactory;

/**
 * Class TableFactory
 *
 * @category ORM
 *
 * @package  App\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class TableFactory
{
    /**
     * Instance
     *
     * @var self
     */
    private static $instance;

    /**
     * ORM gérés par cette classe
     *
     * @var array
     */
    private static $orms = ['cake', 'phinx'];

    /**
     * Options par défaut de création d'une table
     *
     * @var array
     */
    private static $defaultOptions = [
        'orm' => 'phinx'
    ];

    /**
     * Type de l'ORM courant
     *
     * @var string
     */
    private static $orm;

    /**
     * Configuration de la table demandée
     *
     * @var array
     */
    private $config;

    /**
     * Clé et valeurs d'une configuration par défaut
     *
     * @var array
     */
    private $defaultConfig = [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbName' => '',
        'sgbd' => 'mysql',
        'port' => 3306,
        'fileName' => '',
    ];

    /**
     * Constructeur non public afin d'éviter la création d'une nouvelle instance du *Singleton* via l'opérateur `new`
     *
     * @param array $config
     */
    protected function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Obtenir une instance (Singleton)
     *
     * @param array $config
     *
     * @return self
     */
    public static function getInstance(array $config = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Obtenir la configuration ou la valeur d'une clé
     * - `getConfig();`
     * - `getConfig('host');`
     *
     * @param string|null $key Clé de la configuration à retourner
     *
     * @return mixed|null
     */
    public function getConfig($key = null)
    {
        if (is_null($key)) {
            return $this->config;
        } elseif (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return null;
    }

    /**
     * Définir la configuration
     *
     * @param array $config
     */
    public function setConfig($config)
    {
        $config = array_merge($this->defaultConfig, $config);
        $this->config = $config;
    }

    /**
     * Obtenir une table
     *
     * @param string     $name    Nom de la table
     * @param array|null $options Options de la table
     *
     * @return \Rcnchris\Core\ORM\Phinx\PhinxTable|\Cake\ORM\Table
     */
    public static function get($name, array $options = [])
    {
        self::parseOptions($options);
        if (self::$orm === 'cake') {
            return self::getCakeTable($name)->setTable($name);
        } elseif (self::$orm === 'phinx') {
            return self::getPhinxTable($name);
        }
        return null;
    }

    /**
     * Vérifie la cohérence des options avant de les retourner.
     * Fusionne les options par défaut.
     *
     * @param array $options Options à vérifier
     *
     * @return void
     */
    private static function parseOptions(array $options)
    {
        $options = array_merge(self::$defaultOptions, $options);
        if (isset($options['orm']) && self::hasOrm($options['orm'])) {
            self::$orm = $options['orm'];
            unset($options['orm']);
        } else {
            self::$orm = self::$defaultOptions['orm'];
        }

        if (isset($options['config'])) {
            self::setConfig($options['config']);
        }
        unset($options);
    }

    /**
     * Vérifie la présence d'un ORM dans la liste
     *
     * @param string $name Nom d'un ORM
     *
     * @return bool
     */
    private static function hasOrm($name)
    {
        return in_array($name, self::$orms);
    }

    /**
     * Obtenir l'instance d'une table de l'ORM Cake
     *
     * @param string $name Nom de la table tel que définit dans la base de données
     *
     * @return \Cake\ORM\Table
     */
    private static function getCakeTable($name)
    {
        $drivers = self::getCakeDrivers();
        ConnectionManager::setConfig('default', [
            'className' => Connection::class,
            'driver' => $drivers[self::getInstance()->getConfig('sgbd')],
            'host' => self::getInstance()->getConfig('host'),
            'database' => self::getInstance()->getConfig('dbName'),
            'username' => self::getInstance()->getConfig('username'),
            'password' => self::getInstance()->getConfig('password'),
            'persistent' => true,
            'timezone' => 'UTC',
            'encoding' => 'utf8',
            'cacheMetadata' => false,
            'quoteIdentifiers' => false
        ]);
        return TableRegistry::getTableLocator()->get($name, ['className' => CakeTable::class]);
    }

    /**
     * Obtenir la liste des drivers Cake ou l'un d'entre eux à partir d'un nom de sgbd
     *
     * @param string|null $sgbd Nom du SGBD
     *
     * @return array|null|string
     */
    public static function getCakeDrivers($sgbd = null)
    {
        $drivers = ConnectionManager::getDsnClassMap();
        if (is_null($sgbd)) {
            return $drivers;
        } elseif (array_key_exists($sgbd, $drivers)) {
            return $drivers[$sgbd];
        }
        return null;
    }

    /**
     * Obtenir l'instance d'une table de l'ORM Phinx
     *
     * @param string $name Nom de la table tel que définit dans la base de données
     *
     * @return \Rcnchris\Core\ORM\Phinx\PhinxTable
     */
    private static function getPhinxTable($name)
    {
        $table = new PhinxTable($name);
        $adapter = AdapterFactory::instance()
            ->getAdapter(
                self::getInstance()->getConfig('sgbd'),
                [
                    'name' => self::getInstance()->getConfig('host'),
                    'suffix' => '.sqlite'
                ]
            );
        $table->setAdapter($adapter);
        return $table;
    }

    /**
     * La méthode clone est privée afin d'empêcher le clonage de l'instance *Singleton*.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * La méthode de désérialisation est privée afin d'empêcher le clonage de l'instance *Singleton*.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
