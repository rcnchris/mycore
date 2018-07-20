<?php
/**
 * Fichier BootMiddleware.php du 09/07/2018
 * Description : Fichier de la classe BootMiddleware
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
 * Class BootMiddleware
 * <ul>
 * <li>Démarre l'application et définit les variables de localisation et l'environnement</li>
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
class BootMiddleware extends AbstractMiddleware
{
    /**
     * Confiuration par défaut
     *
     * @var array
     */
    private $defaultConfig = [
        'php' => '5.6',
        'timezone' => 'Europe/Paris',
        'locale' => 'fr_FR',
        'charset' => 'utf-8',
    ];

    /**
     * Est appelée lorsqu'un script tente d'appeler un objet comme une fonction. (callable)
     *
     * @param \Psr\Http\Message\RequestInterface  $request  Requête PSR7
     * @param \Psr\Http\Message\ResponseInterface $response Réponse PSR7
     * @param callable|null                       $next     Middleware suivant
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $this->defineConstants();

        /**
         * Version de PHP
         */
        if (version_compare(PHP_VERSION, $this->defaultConfig['php']) < 0) {
            throw new \Exception(
                "Version de PHP " . PHP_VERSION
                . " non supportée par cette application ! Elle a besoin de PHP "
                . $this->defaultConfig['php']. " ou supérieur."
            );
        }

        /**
         * Localisation
         */
        if (!is_null($this->container)) {
            date_default_timezone_set($this->getContainer('app.timezone'));
            mb_internal_encoding($this->getContainer('app.charset'));
            if (extension_loaded('intl')) {
                ini_set('intl.default_locale', $this->getContainer('app.defaultLocale'));
            }
            setlocale(LC_MONETARY, $this->getContainer('app.defaultLocale'));
        } else {
            date_default_timezone_set($this->defaultConfig['timezone']);
            mb_internal_encoding($this->defaultConfig['charset']);
            if (extension_loaded('intl')) {
                ini_set('intl.default_locale', $this->defaultConfig['locale']);
            }
            setlocale(LC_MONETARY, $this->defaultConfig['locale']);
        }

        /**
         * Affichage des erreurs PHP si la clé du conteneur de dépendances `debug` est à `true`
         */
        error_reporting(0);
        if ($this->getContainer('debug') === true) {
            error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
            ini_set("display_errors", 1);
        }

        return $next($request, $response);
    }

    /**
     * Définit les constantes de l'application
     */
    private function defineConstants()
    {
        $constants = [
            'DS' => DIRECTORY_SEPARATOR,
            'ROOT' => dirname(dirname(__DIR__))
        ];
        foreach ($constants as $name => $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }
}
