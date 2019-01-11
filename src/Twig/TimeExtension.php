<?php
/**
 * Fichier TimeExtension.php du 06/01/2018
 * Description : Fichier de la classe TimeExtension
 *
 * PHP version 5
 *
 * @category Twig
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
 * <ul>
 * <li>Helper sur les dates</li>
 * </ul>
 *
 * @category Twig
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.0>
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
            new \Twig_SimpleFunction('now', [$this, 'now'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('getDate', [$this, 'getDate'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('dateDiff', [$this, 'dateDiff'], ['is_safe' => ['html']])
        ];
    }


    /**
     * Obtenir l'instance d'un DateTime à partir d'une valeur et d'un format
     * - Fonction
     *
     * @param string      $value  Valeur de la date
     * @param string|null $format Format de la date
     *
     * @return \DateTime
     */
    public function getDate($value, $format = 'Y-m-d')
    {
        $d = (new \DateTime())->createFromFormat($format, $value);
        return $d;
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

    /**
     * Obtenir la différence en entre deux dates
     * - Fonction
     *
     * @param mixed       $start    Date de départ
     * @param mixed       $end      Date de fin
     * @param string|null $format   Format des dates fournies en chaîne de caractères
     * @param bool|null   $absolute Valeur absolue
     *
     * @return bool|\DateInterval
     */
    public function dateDiff($start, $end, $format = 'd-m-Y H:i:s', $absolute = false)
    {
        if (is_string($start)) {
            $start = \DateTime::createFromFormat($format, $start);
        }
        if (is_string($end)) {
            $end = \DateTime::createFromFormat($format, $end);
        }
        return $start->diff($end, $absolute);
    }
}
