<?php
/**
 * Fichier WhoopsMiddleware.php du 02/08/2018
 * Description : Fichier de la classe WhoopsMiddleware
 *
 * PHP version 5
 *
 * @category New
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
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class WhoopsMiddleware
 *
 * @category Debug
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
class WhoopsMiddleware
{
    /**
     * Charge le middleware de gestion des erreurs Whoops
     *
     * @param \Psr\Http\Message\RequestInterface  $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable                            $next
     *
     * @return mixed
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
        return $next($request, $response);
    }
}
