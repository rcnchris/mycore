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

use Rcnchris\Core\Html\Html;

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
 * @version  Release: <1.0.2>
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
            new \Twig_SimpleFilter('code', [$this, 'code'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('surround', [$this, 'surround'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('liste', [$this, 'liste'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Entourre une valeur d'une balise <code>
     * - Filtre
     *
     * @param string     $value      Valeur à mettre dans la balise `code`
     * @param array|null $attributes Attributs de la balise `code`
     * @param bool|null  $withHeader Ajoute l'emplacement du fichier, si `$value` en est un
     *
     * @return null|string
     */
    public function code($value, array $attributes = [], $withHeader = false)
    {
        return Html::source($value, $attributes, $withHeader);
    }

    /**
     * Entoure une valeur d'une balise HTML
     * - Filtre
     *
     * ### Exemple
     * - `'montexte|surround('code')`
     * - `'montexte|surround('pre', {class: 'sh_php'})`
     *
     * @param string     $value      Valeur à entourer
     * @param string     $tag        Balise HTML
     * @param array|null $attributes Attributs de la balise
     *
     * @return string
     */
    public function surround($value, $tag, array $attributes = [])
    {
        return Html::surround($value, $tag, $attributes);
    }

    /**
     * Obtenir une liste ul ou ol
     * - Filtre
     *
     * @param mixed       $value    Liste
     * @param string|null $tag      `ul`, `ol` ou `dl`
     * @param bool|null   $withKeys Les valeurs sont affichées avec leur clés
     *
     * @return string
     */
    public function liste($value, $tag = 'ul', $withKeys = true)
    {
        return Html::liste($value, ['type' => $tag], $withKeys);
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('details', [$this, 'details'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('link', [$this, 'link'], ['is_safe' => ['html']]),
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
     * @param string     $title      Titre
     * @param string     $content    Contenu caché
     * @param array|null $attributes Attributs de la balise `details`
     *
     * @return string
     */
    public function details($title, $content, array $attributes = [])
    {
        return Html::details($title, $content, $attributes);
    }

    /**
     * Obtenir un lien
     * - Fonction
     *
     * @param string      $url        URL de l'attribut `href`
     * @param string|null $label      Texte visible
     * @param array|null  $attributes Attribut de la balise `a`
     *
     * @return string
     */
    public function link($url, $label = null, array $attributes = [])
    {
        return Html::link($url, $label, $attributes);
    }
}
