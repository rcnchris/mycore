<?php
/**
 * Fichier Query.php du 20/10/2017
 * Description : Fichier de la classe Query
 *
 * PHP version 5
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM;

use Traversable;

/**
 * Class Query
 * <ul>
 * <li>Gestion des requêtes sur les bases de données PDO</li>
 * </ul>
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.4.8>
 *
 * @since    Release: <0.1.1>
 */
class Query implements \IteratorAggregate
{

    /**
     * Liste des champs à sélectionner
     *
     * @var string
     */
    private $select;

    /**
     * Table principale de la requête
     *
     * @var array
     */
    private $from = [];

    /**
     * Conditions
     *
     * @var array
     */
    private $where = [];

    /**
     * Champs à regrouper
     *
     * @var string
     */
    private $group;

    /**
     * Champs à trier
     *
     * @var array
     */
    private $order = [];

    /**
     * Nombre d'enregistrement à retourner
     *
     * @var string
     */
    private $limit;

    /**
     * Liste des tables à joindre.
     *
     * @var array
     */
    private $joins = [];

    /**
     * Paramètres
     *
     * @var array
     */
    private $params = [];

    /**
     * Nom ou instance de la classe qui représente un enregistrement
     *
     * @var string|object
     */
    private $entity;

    /**
     * Instance de PDO
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Constructeur
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Définir la table principale de la requête
     *
     * @param string      $table Nom de la table
     * @param string|null $alias Alias de la table
     *
     * @return $this
     */
    public function from($table, $alias = null)
    {
        if ($alias) {
            $this->from[$table] = $alias;
        } else {
            $this->from[] = $table;
        }
        return $this;
    }

    /**
     * Définir les tables à joindre
     *
     * @param string      $table     Nom de la table
     * @param string      $condition Condition de la jointure
     * @param string|null $type      Sens de la jointure
     *
     * @return $this
     */
    public function join($table, $condition, $type = 'LEFT')
    {
        $this->joins[$type] = [$table, $condition];
        return $this;
    }

    /**
     * Définir les champs à retourner
     *
     * @param string $fields Nom du champ
     *
     * @return $this
     */
    public function select($fields)
    {
        $fields = func_get_args();
        $this->select = $fields;
        return $this;
    }

    /**
     * Spécifie la limite
     *
     * @param int      $length
     * @param int|null $offset
     *
     * @return $this
     */
    public function limit($length, $offset = null)
    {
        $this->limit = is_null($offset)
            ? $length
            : "$length, $offset";
        return $this;
    }

    /**
     * Spécifie l'ordre de récupération
     *
     * @param string $field
     *
     * @return $this
     */
    public function order($field)
    {
        $this->order[] = $field;
        return $this;
    }

    /**
     * Obtenir le nombre d'enregistrements
     *
     * @return int
     */
    public function count()
    {
        $query = clone $this;

        $parts = explode(' ', current($this->from));
        $field = array_pop($parts);
        //var_dump($field);
        //var_dump($query->select("COUNT($field.id)")->__toString());

        return intval($query->select("COUNT($field.id)")->execute()->fetchColumn());
    }

    /**
     * Définir les conditions de la requête
     *
     * @param string $conditions
     *
     * @return $this
     */
    public function where($conditions)
    {
        $conditions = func_get_args();
        if (!is_null($conditions[0])) {
            $this->where = array_merge($this->where, $conditions);
        }
        return $this;
    }

    /**
     * Paramètres de la requête
     *
     * @param array $params
     *
     * @return $this
     */
    public function params(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * Récupère un enregistrement
     *
     * @return bool|mixed|object
     */
    public function fetch()
    {
        $record = $this->execute()->fetch(\PDO::FETCH_ASSOC);
        if ($record === false) {
            return false;
        }
        if ($this->entity) {
            return Hydrator::hydrate($record, $this->entity);
        }
        return $record;
    }

    /**
     * Récupère un enregistrement ou renvoie une exception
     *
     * @return bool|mixed|object
     * @throws NoRecordException
     */
    public function fetchOrFail()
    {
        $record = $this->fetch();
        if ($record === false) {
            throw new NoRecordException();
        }
        return $record;
    }

    /**
     * Définir l'entité qui représentera chaque enregistrement
     *
     * @param string $entity Nom de la classe à instancier
     *
     * @return Query
     */
    public function into($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Exécute et retourne le résultat de la requête
     *
     * @return \PDOStatement
     */
    private function execute()
    {
        $query = $this->__toString();
        if (!empty($this->params)) {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($this->params);
            return $stmt;
        }
        return $this->pdo->query($query);
    }


    /**
     * Retourne tous les enregistrements de la table dans un tableau associatif
     *
     * @return QueryResult
     */
    public function all()
    {
        return new QueryResult(
            $this->execute()->fetchAll(\PDO::FETCH_ASSOC),
            $this->entity,
            $this->getPdo()
        );
    }

    /**
     * Génère et retourne la requête SQL
     *
     * @return string
     */
    public function __toString()
    {
        // SELECT
        $parts = ['SELECT'];
        if ($this->select) {
            $parts[] = join(', ', $this->select);
        } else {
            if (count($this->from) > 1) {
                $select = array_map(function ($table) {
                    return $table . '.*';
                }, $this->from);
                $parts[] = join(', ', $select);
            } else {
                $parts[] = current($this->from) . '.*';
            }
        }

        // FROM
        $parts[] = 'FROM';
        $parts[] = $this->buildFrom();

        // JOINS
        if (!empty($this->joins)) {
            foreach ($this->joins as $type => $joins) {
                list($table, $condition) = $joins;
                $parts[] = strtoupper($type) . " JOIN $table ON $condition";
            }
        }

        // WHERE
        if (!empty($this->where)) {
            $parts[] = 'WHERE';
            $parts[] = "(" . join(') AND (', $this->where) . ")";
        }

        // ORDER
        if (!empty($this->order)) {
            $parts[] = 'ORDER BY';
            $parts[] = join(', ', $this->order);
        }

        // LIMIT
        if ($this->limit) {
            $parts[] = 'LIMIT ' . $this->limit;
        }

        return join(' ', $parts);
    }

    /**
     * Génère la clause FROM
     *
     * @return string
     */
    private function buildFrom()
    {
        $from = [];
        foreach ($this->from as $key => $value) {
            if (is_string($key)) {
                $from[] = "$key as $value";
            } else {
                $from[] = $value;
            }
        }
        return join(', ', $from);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *       <b>Traversable</b>
     */
    public function getIterator()
    {
        return $this->all();
    }

    /**
     * Obtenir l'instance de PDO
     *
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Obtenir la liste des tables utilisées dans la requête
     *
     * @return array[string]
     */
    public function tables()
    {
        return $this->from;
    }
}
