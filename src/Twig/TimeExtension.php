<?php
/**
 * Fichier TimeExtension.php du 06/01/2018
 * Description : Fichier de la classe TimeExtension
 *
 * PHP version 5
 *
 * @category Extension
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Twig;

/**
 * Class TimeExtension
 *
 * @category Extension
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class TimeExtension extends \Twig_Extension
{
    /**
     * Obtenir la liste des filtres
     *
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('ago', [$this, 'ago'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir une date formatée
     *
     * @param        $date
     * @param string $format
     *
     * @return string
     */
    public function ago($date, $format = 'd/m/Y H:i')
    {
        if (is_string($date)) {
            $date = new \Datetime($date);
        }
        return '<span class="timeago" datetime="'
        . $date->format(\DateTime::ISO8601) . '">'
        . $date->format($format) . '</span>';
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('now', [$this, 'now'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir maintenant
     *
     * @return float
     */
    public function now()
    {
        return microtime(true);
    }
}
