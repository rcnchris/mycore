<?php
/**
 * Fichier ConfigContainer.php du 10/07/2018
 * Description : Fichier de la classe ConfigContainer
 *
 * PHP version 5
 *
 * @category Conteneur de dépendances
 *
 * @package  Rcnchris\Core\Config
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Config;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Rcnchris\Core\HelpTrait;

/**
 * Class Config
 *
 * @category Conteneur de dépendances
 *
 * @package  Rcnchris\Core\Config
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class ConfigContainer implements ContainerInterface, \ArrayAccess
{

    /**
     * Aide de cette classe
     *
     * @var array
     */
    protected $help = [
        "Permet de disposer d'un conteneur de dépendances",
    ];

    /**
     * Configuration
     *
     * @var array
     */
    private $config;

    /**
     * Constructeur
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Obtenir la valeur d'une clé
     *
     * @param string $key Nom de la clé à retourner
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $key Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($key)
    {
        return array_key_exists($key, $this->config)
            ? $this->config[$key]
            : null;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($key)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $key Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($key)
    {
        return in_array($key, $this->keys());
    }

    /**
     * Définir une clé et sa valeur
     *
     * @param string     $key   Nom de la clé
     * @param mixed|null $value Valeur de la clé
     */
    public function set($key, $value = null)
    {
        $this->config[$key] = $value;
    }

    /**
     * Supprimer une clé et sa valeur
     *
     * @param string $key
     */
    public function del($key)
    {
        if ($this->has($key)) {
            unset($this->config[$key]);
        }
    }

    /**
     * Obtenir la liste des clés
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->config);
    }

    /**
     * Obtenir tout le conteneur sous forme de tableau
     *
     * @return array
     */
    public function all()
    {
        return $this->config;
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
        $this->del($offset);
    }

    /**
     * Obtenir l'aide de cette classe
     *
     * @param bool|null $text Si faux, c'est le tableau qui ets retourné
     *
     * @return array|string
     */
    public function help($text = true)
    {
        if ($text) {
            return join('. ', $this->help);
        }
        return $this->help;
    }
}
