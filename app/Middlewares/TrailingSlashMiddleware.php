<?php
/**
 * Fichier TrailingSlashMiddleware.php du 17/10/2017
 * Description : Fichier de la classe TrailingSlashMiddleware
 *
 * PHP version 5
 *
 * @category URL
 *
 * @package  Rcnchris\App\Middlewares
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\App\Middlewares;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TrailingSlashMiddleware -
 * Enlève le dernier slash à l'URL si il existe.
 *
 * @category URL
 *
 * @package  Rcnchris\App\Middlewares
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class TrailingSlashMiddleware
{

    /**
     * Enlève le dernier slash à l'url
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param callable            $next
     *
     * @return static
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        if ($path != '/' && substr($path, -1) == '/') {
            $uri = $uri->withPath(substr($path, 0, -1));
            return $request->getMethod() == 'GET'
                ? $response->withRedirect((string)$uri, 301)
                : $next($request->withUri($uri), $response);
        }
        return $next($request, $response);
    }
}
