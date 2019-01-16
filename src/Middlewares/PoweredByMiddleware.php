<?php
/**
 * Fichier PoweredByMiddleware.php du 09/07/2018
 * Description : Fichier de la classe PoweredByMiddleware
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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class PoweredByMiddleware
 * <ul>
 * <li>Ajoute le header PoweredBy</li>
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
class PoweredByMiddleware extends AbstractMiddleware
{
    /**
     * Est appelée lorsqu'un script tente d'appeler un objet comme une fonction. (callable)
     *
     * @param \Psr\Http\Message\RequestInterface  $request  Requête PSR7
     * @param \Psr\Http\Message\ResponseInterface $response Réponse PSR7
     * @param callable|null                       $next     Middleware suivant
     *
     * @return mixed
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        return $next($request, $response->withHeader('X-Powered-By', 'MRC Consulting'));
    }
}
