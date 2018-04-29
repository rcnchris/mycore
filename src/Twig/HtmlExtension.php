<?php
/**
 * Fichier HtmlExtension.php du 06/01/2018
 * Description : Fichier de la classe HtmlExtension
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
 * Class HtmlExtension
 * <ul>
 * <li>Helper sur les balises HTML</li>
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
            ,
            new \Twig_SimpleFilter('surround', [$this, 'surround'], ['is_safe' => ['html']])
            ,
            new \Twig_SimpleFilter('getList', [$this, 'getList'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Entourre une valeur d'une balise <code>
     * - Filtre
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
     * - Filtre
     *
     * ### Exemple
     * - `'montexte|surround('code')`
     *
     * @param string $value  Valeur à entourer
     * @param string $balise Balise HTML
     *
     * @return string
     */
    public function surround($value, $balise)
    {
        if (!is_string($value) || !is_string($balise)) {
            return null;
        }
        return "<$balise>$value</$balise>";
    }

    /**
     * Obtenir une liste ul ou ol
     * - Filtre
     *
     * @param mixed  $value Liste
     * @param string $type  ul ou ol
     *
     * @return string
     */
    public function getList($value, $type = 'ul')
    {
        $html = "<$type>";
        if (is_array($value)) {
            foreach ($value as $key => $value) {
                if (is_string($value)) {
                    $html .= "<li>$key : $value</li>";
                } elseif (is_object($value)) {
                    $html .= "<li>$key : " . get_class($value) . "</li>";
                } elseif (is_array($value)) {
                    $html .= "<li>$key : " . array_keys($value) . "</li>";
                }
            }
            $html .= "</$type>";
        }
        return $html;
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('details', [$this, 'details'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir une balise details
     * - Fonction
     *
     * ### Exemple
     * - `$ext->details($titre, $content);`
     * - `{{ details(titre, content) }}`
     *
     * @param string $title   Titre
     * @param string $content Contenu caché
     *
     * @return string
     */
    public function details($title, $content)
    {
        $html = '<details>';
        $html .= "<summary>$title</summary>";
        $html .= $content;
        $html .= '</details>';
        return $html;
    }
}
