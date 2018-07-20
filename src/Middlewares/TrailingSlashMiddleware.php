<?php
/**
 * Fichier TrailingSlashMiddleware.php du 09/07/2018
 * Description : Fichier de la classe TrailingSlashMiddleware
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
 * Class TrailingSlashMiddleware
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
class TrailingSlashMiddleware extends AbstractMiddleware
{

    /**
     * Est appelée lorsqu'un script tente d'appeler un objet comme une fonction. (callable)
     *
     * @param \Psr\Http\Message\RequestInterface  $request  Requête PSR7
     * @param \Psr\Http\Message\ResponseInterface $response Réponse PSR7
     * @param callable|null                       $next     Middleware suivant
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $url = (string)$request->getUri();
        if (!empty($url) && substr($url, strlen($url) - 1, 1) === '/') {
            $newUrl = substr($url, 0, -1);
            $request = $request->withHeader('Location', $newUrl);
        }
        return $next($request, $response);
    }
}
