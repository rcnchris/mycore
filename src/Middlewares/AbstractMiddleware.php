<?php
/**
 * Fichier AbstractMiddleware.php du 09/07/2018
 * Description : Fichier de la classe AbstractMiddleware
 *
 * PHP version 5
 *
 * @category Middleware
 *
 * @package  Rcnchris\Core\Middlewares
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractMiddleware
 * <ul>
 * <li>Classe abstraite des middlewares de l'application</li>
 * </ul>
 *
 * @category Middleware
 *
 * @package  Rcnchris\Core\Middlewares
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
abstract class AbstractMiddleware
{
    /**
     * Conteneur de dépendances
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Est appelée lorsqu'un script tente d'appeler un objet comme une fonction. (callable)
     *
     * @param \Psr\Http\Message\RequestInterface  $request  Requête PSR7
     * @param \Psr\Http\Message\ResponseInterface $response Réponse PSR7
     * @param callable|null                       $next     Middleware suivant
     *
     * @return mixed
     */
    abstract public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null);

    /**
     * Ajoute le conteneur de dépendances à l'instance du middleware
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return self
     */
    public function withContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Obtenir le nom court du middleware
     *
     * @return string
     */
    public function getName()
    {
        $className = get_class($this);
        $parts = explode('\\', $className);
        $name = array_pop($parts);
        return str_replace('Middleware', '', $name);
    }

    /**
     * Obtenir le conteneur de dépendances ou la valeur d'une clé
     *
     * @param string|null $key Nom de la clé à retourner
     *
     * @return mixed|null|\Psr\Container\ContainerInterface
     */
    public function getContainer($key = null)
    {
        if (is_null($key)) {
            return $this->container;
        } elseif (!is_null($this->container) && $this->container->has($key)) {
            return $this->container->get($key);
        }
        return null;
    }

    /**
     * Est appelée pour lire des données depuis des propriétés inaccessibles.
     *
     * @param string $key Nom de la clé du conteneur à retourner
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->getContainer($key);
    }
}
