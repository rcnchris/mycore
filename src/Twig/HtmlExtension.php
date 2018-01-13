<?php
/**
 * Fichier HtmlExtension.php du 06/01/2018
 * Description : Fichier de la classe HtmlExtension
 *
 * PHP version 5
 *
 * @category HTML
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
 * Class HtmlExtension
 *
 * @category HTML
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
class HtmlExtension extends \Twig_Extension
{
    /**
     * Obtenir la liste des filtres
     *
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('code', [$this, 'code'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('surround', [$this, 'surround'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Entourre une valeur d'une balise <code>
     *
     * @param string $value
     *
     * @return null|string
     */
    public function code($value)
    {
        if (!is_string($value)) {
            return null;
        }
        return '<code>' . $value . '</code>';
    }

    /**
     * Entoure une valeur d'une balise HTML
     *
     * ### Exemple
     * - `'montexte|surround('code')`
     *
     * @param string $value Valeur à entourer
     * @param string $balise Balise HTML
     *
     * @return string
     */
    public function surround($value, $balise)
    {
        if(!is_string($value) || !is_string($balise)) {
            return null;
        }
        return "<$balise>$value</$balise>";
    }
}
