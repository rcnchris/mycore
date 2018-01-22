<?php
/**
 * Fichier OrmTestCase.php du 17/01/2018
 * Description : Fichier de la classe OrmTestCase
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Tests\RcnchrisCore\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\ORM;
use Tests\Rcnchris\BaseTestCase;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use Rcnchris\Core\ORM\DbFactory;

/**
 * Class OrmTestCase
 *
 * @category New
 *
 * @package  Tests\RcnchrisCore\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class OrmTestCase extends BaseTestCase
{

    use TestCaseTrait;

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

    public function setUp()
    {
        // Base de données des tests unitaires en mémoire
        $this->db = $this->getConnection();
        $this->seedsCategories();
        $this->seedsPosts();
        $this->seedsTags();
    }

    public function seedsPosts()
    {
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
        for ($i = 1; $i <= 20; $i++) {
            $sql = $sql
                . "('" . $this->faker()->sentence(3) . "', "
                . rand(1, 3) . ", '"
                . $this->faker()->date("Y-m-d H:i:s") . "', '"
                . $this->faker()->date("Y-m-d H:i:s") . "'),";
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $this->db->query($sql);
    }

    public function seedsCategories()
    {
        // Catégories
        $this->db->query('DROP TABLE IF EXISTS categories');
        $this->db->query("CREATE TABLE IF NOT EXISTS categories (
          id INTEGER PRIMARY KEY AUTOINCREMENT
          , title VARCHAR(50));");
        $this->db->query("INSERT INTO categories (title) VALUES ('Article'), ('Page');");
    }

    public function seedsTags()
    {
        // Tags
        $this->db->query('DROP TABLE IF EXISTS tags');
        $this->db->query("CREATE TABLE IF NOT EXISTS tags (
          id INTEGER PRIMARY KEY AUTOINCREMENT
          , title VARCHAR(50)
        );");
        $this->db->query("INSERT INTO tags (title) VALUES ('"
            . $this->faker()->words(1, true) . "'), ('"
            . $this->faker()->words(1, true) . "'), ('"
            . $this->faker()->words(1, true) . "')");

        // Association des tags aux posts
        $this->db->query('DROP TABLE IF EXISTS posts_tags');
        $this->db->query("CREATE TABLE IF NOT EXISTS posts_tags (
          post_id INTEGER
          , tag_id INTEGER
          , title VARCHAR(50)
        );");
        $sql = "INSERT INTO posts_tags (post_id, tag_id, title) VALUES ";
        for ($i = 1; $i <= 20; $i++) {
            $sql = $sql . '('
                . rand(1, 20) . ', '
                . rand(1, 3) . ", '"
                . $this->faker()->words(1, true) . "'),";
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $this->db->query($sql);
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
     * Returns the test database connection.
     *
     * @return Connection
     */
    protected function getConnection()
    {
        if (is_null($this->db)) {
            $this->db = $this->makeDb('dbTests', 0, '', '', '', 'sqlite');
        }
        return $this->db;
    }

    /**
     * Returns the test dataset.
     *
     * @return IDataSet
     */
    protected function getDataSet()
    {
        // TODO: Implement getDataSet() method.
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