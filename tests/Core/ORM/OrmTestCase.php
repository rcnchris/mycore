<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\Model;
use Rcnchris\Core\ORM\Query;
use Rcnchris\Core\ORM\SourcesManager;
use Tests\Rcnchris\BaseTestCase;

class OrmTestCase extends BaseTestCase
{

    /**
     * Manageur des sources de données
     *
     * @var SourcesManager
     */
    private $manager;

    /**
     * @var \PDO
     */
    private $db;

    public function setUp()
    {
        $this->markTestSkipped('Uniquement en local');
        $this->manager = $this->getManager();
    }

    public function testInstance()
    {
        $className = str_replace('Test', '', get_class($this));
        $className = explode('\\', $className);
        $className = end($className);
        $this->ekoTitre("ORM - $className");
    }

    /**
     * @param array $sources Tableau des sources de données
     *
     * @return \Rcnchris\Core\ORM\SourcesManager
     */
    protected function getManager(array $sources = [])
    {
        if (empty($sources)) {
            $sources = $this->getConfig('datasources');
        }
        return new SourcesManager($sources);
    }

    /**
     * @param \PDO $pdo Instance PDO
     *
     * @return \Rcnchris\Core\ORM\Query
     */
    protected function getQuery(\PDO $pdo = null)
    {
        return is_null($pdo)
            ? new Query($this->getDbTests())
            : new Query($pdo);
    }

    /**
     * Obtenir la connexion à une base de données par le nom de la source dans la configuration
     *
     * @param string|null $name Nom de la source de données dans la configuration
     *
     * @return null|\PDO|string
     */
    protected function getDb($name = null)
    {
        return $this->manager->connect($name);
    }

    /**
     * @param null $name
     *
     * @return \Rcnchris\Core\ORM\Model
     * @throws \Exception
     */
    protected function getModel($name = null)
    {
        if (is_null($name)) {
            return new Model($this->getDbTests());
        } else {
            $modelName = '\Tests\Rcnchris\Core\ORM\\' . ucfirst($name) . 'Model';
            if (class_exists($modelName)) {
                return new $modelName($this->getDbTests());
            }
            throw new \Exception("La modèle $modelName est introuvable !");
        }
    }

    /**
     * @return null|\PDO|string
     */
    protected function getDbTests()
    {
        if (is_null($this->db)) {
            $this->db = $this->getDb('test');
        }
        return $this->db;
    }

    /**
     * Alimente la table des catégories
     */
    public function seedsCategories()
    {
        $db = $this->getDbTests();
        $db->query('DROP TABLE IF EXISTS categories');
        $db->query("CREATE TABLE IF NOT EXISTS categories (
          id INTEGER PRIMARY KEY AUTOINCREMENT
          , title VARCHAR(50));"
        );
        $db->query("INSERT INTO categories (title) VALUES ('Article'), ('Page');");
    }

    /**
     * Alimente la table des posts
     */
    public function seedsPosts()
    {
        $db = $this->getDbTests();
        $db->query('DROP TABLE IF EXISTS posts');
        $db->query("CREATE TABLE IF NOT EXISTS posts (
          id INTEGER PRIMARY KEY AUTOINCREMENT
          , title VARCHAR(100)
          , category_id INTEGER
          , created DATETIME
          , modified DATETIME
        );");

        $sql = 'INSERT INTO posts (title, category_id, created, modified) VALUES ';
        for ($i = 1; $i <= 20; $i++) {
            $sql = $sql
                . "('" . $this->faker()->sentence(3) . "', "
                . rand(1, 2) . ", '"
                . $this->faker()->date("Y-m-d H:i:s") . "', '"
                . $this->faker()->date("Y-m-d H:i:s") . "'),";
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $db->query($sql);
    }
}
