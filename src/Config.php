<?php
/**
 * Fichier Config.php du 24/01/2018
 * Description : Fichier de la classe Config
 *
 * PHP version 5
 *
 * @category Configuration
 *
 * @package  Rcnchris\Core
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Config
 * <ul>
 * <li>Permet la gestion d'un conteneur de dépendances.</li>
 * </ul>
 *
 * @category Configuration
 *
 * @package  Rcnchris\Core
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class Config implements ContainerInterface
{

    /**
     * Données de configuration
     *
     * @var array
     */
    private $items;

    /**
     * Constructeur
     *
     * @param array|object|null $config
     */
    public function __construct($config = null)
    {
        if (is_array($config)) {
            $this->items = $config;
        } elseif (is_object($config)) {
            foreach ($config as $key => $value) {
                $this->items[$key] = $value;
            }
        }
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        return $this->items[$id];
    }

    /**
     * Obtenir un propriété qui n'existe pas
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists($id, $this->items);
    }
}
