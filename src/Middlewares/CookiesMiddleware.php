<?php
/**
 * Fichier CookiesMiddleware.php du 09/07/2018
 * Description : Fichier de la classe CookiesMiddleware
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
use Rcnchris\Core\Session\PHPCookies;

/**
 * Class CookiesMiddleware
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
class CookiesMiddleware extends AbstractMiddleware
{

    /**
     * @param \Psr\Http\Message\RequestInterface  $request  Requête PSR7
     * @param \Psr\Http\Message\ResponseInterface $response Réponse PSR7
     * @param callable|null                       $next     Middleware suivant
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $arrayCookies = [
            'ip' => $request->getServerParams()['REMOTE_ADDR'],
            'nav' => current($request->getHeader('User-Agent')),
            'lastHit' => time(),
            'lastUrl' => $request->getUri()->getPath()
        ];
        new PHPCookies($arrayCookies, [
            'lifetime' => time() + 3600 * 24 * 3,
            'path' => $this->prefix(),
            'domain' => $request->getUri()->getAuthority()
        ]);
        return $next($request, $response);
    }
}
