<?php
/**
 * Fichier QueryResult.php du 20/10/2017
 * Description : Fichier de la classe QueryResult
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

/**
 * Class QueryResult
 * <ul>
 * <li>Représente le résultat d'une requête</li>
 * </ul>
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.1>
 */
class QueryResult implements \ArrayAccess, \Iterator
{

    /**
     * Liste de tous les enregistrements
     *
     * @var array
     */
    private $records;

    /**
     * Indice de récupération des enregistrents
     *
     * @var int
     */
    private $index = 0;

    /**
     * Liste des enregistrements hydraté (avec les données)
     *
     * @var array
     */
    private $hydratedRecords = [];

    /**
     * Objet qui représente un enregistrement
     *
     * @var null|object
     */
    private $entity;

    /**
     * Constructeur
     *
     * @param array       $records Tableau de données
     * @param object|null $entity  Classe de l'entité à hydrater
     */
    public function __construct(array $records, $entity = null)
    {
        $this->records = $records;
        $this->entity = $entity;
    }

    /**
     * Suppression des données de l'instance
     */
    public function __destruct()
    {
        unset($this->records);
        unset($this->hydratedRecords);
        unset($this->entity);
    }

    /**
     * Obtenir un record par son index
     *
     * @param $index
     *
     * @return null|object
     */
    public function get($index)
    {
        if ($this->entity) {
            if (!isset($this->hydratedRecords[$index])) {
                $this->hydratedRecords[$index] = Hydrator::hydrate($this->records[$index], $this->entity);
            }
            return $this->hydratedRecords[$index];
        } else {
            return $this->records[$index];
        }
    }

    /**
     * Obtenir le résultat sous forme de tableau
     *
     * @param bool|null $withEntity Les enregistrements sont stockés dans la classe entity,
     *                              sinon dans un tableau
     *
     * @return array
     */
    public function toArray($withEntity = false)
    {
        if ($withEntity) {
            $records = [];
            foreach ($this->records as $k => $record) {
                $records[] = $this->get($k);
            }
            return $records;
        } else {
            return $this->records;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->get($this->index);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->records[$this->index]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->records[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("Impossible de définir un enregistrement dans l'objet " . get_class($this));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("Impossible de supprimer un enregistrement dans l'objet " . get_class($this));
    }
}
