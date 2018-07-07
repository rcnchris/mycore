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
use Cake\Database\Schema\TableSchema;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Locator\TableLocator;
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
        'orm' => 'cake'
    ];

    /**
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
     * Obtenir la configuration
     *
     * @return array
     */
    public function getConfig($orm)
    {
        if ($orm === 'cake') {

        }
        return $this->config;
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
     * @return \Cake\ORM\Table|\Rcnchris\Core\ORM\Phinx\PhinxTable
     */
    public static function get($name, array $options = [])
    {
        $options = self::parseOptions($options);
        if (self::hasOrm($options['orm'])) {
            $orm = $options['orm'];
            unset($options['orm']);

            if ($orm === 'cake') {

                return TableRegistry::getTableLocator()->get($name, [
                    'connection' => new Connection(self::getInstance()->getConfig($orm))
                ]);

            } elseif ($orm === 'phinx') {

                return new PhinxTable($name, $options, AdapterFactory::instance()->getAdapter('mysql', []));

            }
            die("Le nom de l'ORM $orm n'est pas gérée par cette classe !");
        }
    }

    /**
     * Vérifie la cohérence des options avant de les retourner.
     * Fusionne les options par défaut.
     *
     * @param array $options Options à vérifier
     *
     * @return array
     */
    private static function parseOptions(array $options)
    {
        $options = array_merge(self::$defaultOptions, $options);
        if (isset($options['config'])) {
            self::setConfig($options['config']);
        }
        return $options;
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
}
