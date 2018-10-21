<?php
/**
 * Fichier Controller.php du 20/10/2018
 * Description : Fichier de la classe Controller
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Rcnchris\Core
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace App;

use Psr\Container\ContainerInterface;

/**
 * Class Controller
 *
 * @category Controller
 *
 * @package  Rcnchris\Core
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Controller
{

    /**
     * Constructeur
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructeur
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Permet d'obtenir la valeur d'une clé du conteneur
     *
     * ### Example
     * - `$this->settings`
     *
     * @param string $name Nom de la clé du conteneur
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function __get($name)
    {
        return $this->getContainer($name);
    }

    /**
     * Obtenir le conteneur de dépendances
     * ou la valeur de l'une de ses clés
     *
     * ### Example
     * - `$this->getContainer();`
     * - `$this->getContainer('debug');`
     *
     * @param string|null $key Nom d'une clé du conteneur de dépendances
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer($key = null)
    {
        return is_null($key)
            ? $this->container
            : $this->container->get($key);
    }
}
