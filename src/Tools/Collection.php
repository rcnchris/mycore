<?php
/**
 * Fichier Collection.php du 10/10/2017
 * Description : Fichier de la classe Collection
 *
 * PHP version 5
 *
 * @category Collection
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

use Traversable;
use ArrayIterator;

/**
 * Class Collection
 * <ul>
 * <li>Permet de gérer une liste de données</li>
 * </ul>
 *
 * @category Collection
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <0.0.1>
 * @since    Release: <0.0.1>
 */
class Collection implements \ArrayAccess, \IteratorAggregate, \Countable, \Serializable
{

    /**
     * Tableau des données
     *
     * @var array
     */
    private $items;

    /**
     * Nom de la collection
     *
     * @var string
     */
    private $name;

    /**
     * Constructeur
     *
     * ### Exemple
     * - `$collection = new Collection('ola,ole,oli', "Liste simple vie une chaîne de caractères");`
     * - `$collection = new Collection(['ola', 'ole', 'oli'], "Liste simple via un tableau");`
     *
     * @param mixed|null  $items Liste de données (chaîne avec séparateur, json, array, objet)
     * @param string|null $name  Nom de la collection
     * @param string      $sep Caractère de séparation d'un item dans une chaîne de caractères
     */
    public function __construct($items = null, $name = null, $sep = ',')
    {
        if (is_null($items)) {
            $this->items = [];
        } elseif (is_string($items)) {
            $json = json_decode($items, true);
            if ($json) {
                $this->items = $json;
            } else {
                $items = str_replace(' ', '', $items);
                $this->items = explode(',', $items);
            }
        } elseif (is_array($items)) {
            $this->items = $items;
        } elseif ($items instanceof self) {
            $this->items = $items->toArray();
        } elseif (is_object($items)) {
            $datas = [];
            foreach ($items as $properties => $value) {
                $datas[$properties] = $value;
            }
            $this->items = $datas;
        }
        $this->name($name);
    }

    /**
     * Vide les données de la mémoire
     */
    public function __destruct()
    {
        unset($this->items);
    }

    /**
     * Obtenir la valeur d'une clé.
     *
     * <code>$collection->key</code>
     *
     * @param int|string $key Numéro ou nom de la clé
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Obtenir le contenu de la collection au format jSon
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Obtenir la collection sous forme de tableau
     *
     * <code>$array = $collection->toArray()</code>
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Obtenir le contenu d'une clé au format JSON
     *
     * <code>$string = $collection->toJson()</code>
     * <code>$string = $collection->toJson($key)</code>
     *
     * @param null $key Clé du tableau
     *
     * @return string
     */
    public function toJson($key = null)
    {
        return $key === null
            ? json_encode($this->toArray())
            : json_encode($this->get($key)->toArray());
    }

    /**
     * Obtenir une liste de valeur avec séparateur
     *
     * <code>$string = $collection->join()</code><br/>
     * <code>$string = $collection->join('#')</code>
     *
     * @param string $glue
     *
     * @return bool|string
     */
    public function join($glue = ', ')
    {
        return implode($glue, $this->items);
    }

    /**
     * Vérifie la présence d'une clé dans la collection
     *
     * <code>$this->user->has('email')</code><br/>
     * <code>$this->user->has('name.firtName')</code>
     *
     * @param int|string $key Numéro ou nom de la clé
     *
     * @return bool
     */
    public function has($key)
    {
        if ($this->get($key)) {
            return true;
        }
        return false;
    }

    /**
     * Obtenir la valeur d'une clé de la collection
     *
     * <code>$collection->get('email')</code><br/>
     *
     * @param string|int $key Clé de la collection
     *
     * @return Collection|mixed|null
     */
    public function get($key)
    {
        $indexes = explode('.', $key);
        return $this->getValue($indexes, $this->items);
    }

    /**
     * Retourne false si la clé n'existe pas
     *
     * @param string|int $key Clé de la collection
     *
     * @return bool|Collection|null
     */
    public function hasGet($key)
    {
        return $this->has($key)
            ? $this->get($key)
            : false;
    }

    /**
     * Obtenir le premier élément de la collection
     *
     * @return mixed|Collection|null
     */
    public function first()
    {
        if ($this->isEmpty()) {
            return null;
        }
        $first = array_slice($this->items, 0, 1);
        if (isset($first[0]) && !is_array($first[0])) {
            return $first[0];
        }
        return new self($first, "Premier élément de \"" . $this->name() . "\"");
    }

    /**
     * Obtenir le dernier élément de la collection
     *
     * @return Collection|mixed|null
     */
    public function last()
    {
        if ($this->isEmpty()) {
            return null;
        }
        $keys = $this->keys()->toArray();
        $lastKey = array_pop($keys);
        return $this->get($lastKey);
    }

    /**
     * Compte le nombre d'items de la collection
     *
     * @return int|void
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Obtenir la liste des clés de la collection
     *
     * @return Collection|null
     */
    public function keys()
    {
        if ($this->isEmpty()) {
            return null;
        }
        return new self(array_keys($this->items));
    }

    /**
     * Vérifie la présence d'une valeur dans la collection
     *
     * @param mixed $value  Valeur à chercher
     * @param bool  $strict Respecte la casse
     *
     * @return bool
     */
    public function inArray($value, $strict = true)
    {
        $exist = false;
        if (!in_array($value, $this->items, $strict)) {
            foreach ($this->items as $key => $item) {
                if (is_array($item) && !$exist) {
                    $exist = in_array($value, $item, $strict);
                }
            }
        } else {
            $exist = true;
        }
        return $exist;
    }

    /**
     * Ajoute une clé et sa valeur à la collection
     *
     * @param int|string|null $key
     * @param mixed|null      $value
     *
     * @return bool
     */
    public function set($key = null, $value = null)
    {
        if (is_null($key) && is_null($value)) {
            return false;
        }
        if (is_null($key)) {
            $this->items[] = $value;
            return true;
        }
        $this->items[$key] = $value;
        return true;
    }


    /**
     * Fusionne les items existants avec les nouveaux
     *
     * ### Exemple
     * - `$c->merge($items);`
     * - `$c->merge($items, true);`
     *
     * @param array     $items Nouveaux items
     * @param bool|null $self  S'applique à la collection courante, sinon retourne une nouvelle collection
     *
     * @return Collection
     */
    public function merge(array $items, $self = false)
    {
        if ($self) {
            $this->items = array_merge($this->items, $items);
            return $this;
        } else {
            return new self(array_merge($this->items, $items));
        }
    }

    /**
     * Vérifie si la collection est vide
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Vérifier si la valeur d'une clé est un tableau
     *
     * @param string $key Nom de la clé
     *
     * @return bool
     */
    public function isArray($key)
    {
        $is = false;
        $value = $this->hasGet($key);
        if ($value && $value instanceof self) {
            $is = true;
        }
        return $is;
    }

    /**
     * Obtenir le type de la collection
     *
     * @return string
     */
//    public function type()
//    {
//        $type = 'entity';
//        $typesKeys = $this->typesKeys();
//        $typesDatas = $this->typesDatas();
//
//        if ($typesKeys->count() === 1 && in_array('integer', $typesKeys->toArray())) {
//            $type = 'list';
//            // Si la structure des valeurs est identique pour toutes les entrées de la collection, c'est un type items
//            if ($typesDatas->count() > 1) {
//                $type = 'entity';
//            } elseif ($typesDatas->count() === 1 && in_array('array', $typesDatas->toArray())) {
//                $structItems = [];
//                foreach ($this->items as $i => $item) {
//                    foreach ($item as $key => $value) {
//                        $structItems[$i][$key] = gettype($key);
//                    }
//                }
//                $refStruct = isset($structItems[0]) ? $structItems[0] : [];
//                $diff = [];
//                foreach ($structItems as $i => $itemStruct) {
//                    $diff['keyName'] = array_diff(
//                        array_keys($itemStruct),
//                        array_keys($refStruct)
//                    );
//                    $diff['keyType'] = array_diff($itemStruct, $refStruct);
//                }
//                if (!empty($diff['keyType'])) {
//                    $type = 'entity';
//                } elseif (empty($diff['keyName'])) {
//                    $type = 'items';
//                }
//            }
//        } elseif ($typesKeys->count() === 1 && in_array('string', $typesKeys->toArray())) {
//            if ($typesDatas->count() === 1) {
//                $type = 'items';
//            }
//        }
//        return $type;
//    }

    /**
     * Obtenir ou définir le nom de la collection
     *
     * @param string|null $name Nom  à attribuer à la collection
     *
     * @return null|string
     */
    public function name($name = null)
    {
        if ($name != null
            && is_string($name)
            && $name != ''
        ) {
            $this->name = $name;
        }
        return $this->name;
    }

    /**
     * Obtenir la valeur d'une clé du tableau (récursive) pour la notation avec point
     *
     * @param array $indexes
     * @param       $value
     *
     * @return Collection|null
     */
    private function getValue(array $indexes, $value)
    {
        $key = array_shift($indexes);
        if (empty($indexes)) {
            if (!array_key_exists($key, $value)) {
                return null;
            }
            return is_array($value[$key])
                ? new self($value[$key])
                : $value[$key];
        } else {
            return $this->getValue($indexes, $value[$key]);
        }
    }

    /**
     * Obtenir la liste des types de données des clés de la collection
     *
     * @return Collection
     */
    public function typesKeys()
    {
        $typesKeys = [];
        foreach ($this->items as $key => $item) {
            $typesKeys[gettype($key)] = $key;
        }
        $ret = array_keys($typesKeys);
        sort($ret);
        return new self($ret);
    }

    /**
     * Obtenir la liste des types de données de la collection
     *
     * @return Collection
     */
    public function typesDatas()
    {
        $typesValues = [];
        foreach ($this->items as $key => $item) {
            $typesValues[] = gettype($item);
        }
        $ret = array_unique($typesValues);
        sort($ret);
        return new self($ret);
    }

    /**
     * Obtenir le type de données d'une clé
     *
     * @param $key
     *
     * @return bool|string|void
     */
    public function typeOf($key)
    {
        if ($this->has($key)) {
            return gettype($this->items[$key]);
        }
        return false;
    }

    /**
     * Obtenir une extraction des données à partir d'un nom de clé
     *
     * @param      $key
     * @param null $value
     *
     * @return Collection
     */
    public function extract($key, $value = null)
    {
        //return new self(array_column($this->items, $this->get($key), $value));
        return new self(array_column($this->items, $key, $value));
    }

    /**
     * Obtenir la valeur minimale de la collection
     *
     * @return Collection|mixed
     */
    public function min()
    {
        $minItem = min($this->items);
        return is_array($minItem) ? new self($minItem) : $minItem;
    }

    /**
     * Obtenir la valeur max de la collection
     *
     * @return Collection|mixed
     */
    public function max()
    {
        $maxItem = max($this->items);
        return is_array($maxItem) ? new self($maxItem) : $maxItem;
    }

    /**
     * Obtenir la somme des valeurs d'une collection
     *
     * @return number
     */
    public function sum()
    {
        return array_sum($this->items);
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
        return $this->has($offset);
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
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
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
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->has($offset)) {
            unset($this->items[$offset]);
        }
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
        return new ArrayIterator($this->items);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        unserialize($serialized);
    }
}
