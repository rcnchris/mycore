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
 * @version  Release: <1.0.1>
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
            new \Twig_SimpleFilter('getList', [$this, 'getList'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Entourre une valeur d'une balise <code>
     * - Filtre
     *
     * @param string     $value      Valeur à mettre dans la balise `code`
     * @param array|null $attributes Attributs de la balise `code`
     *
     * @return string|null
     */
    public function code($value, array $attributes = [])
    {
        if (!is_string($value)) {
            return null;
        }
        return $this->surround($value, 'code', $attributes);
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
        if (!is_string($value) || !is_string($tag)) {
            return null;
        }
        return '<' . $tag . $this->parseAttributes($attributes) . '>' . $value . '</' . $tag . '>';
    }

    /**
     * Obtenir une liste ul ou ol
     * - Filtre
     *
     * @param mixed       $value Liste
     * @param string|null $tag   `ul` ou `ol`
     *
     * @return string
     */
    public function getList($value, $tag = 'ul')
    {
        $html = "<$tag>";
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                if (is_string($v)) {
                    $html .= "<li>$k : $v</li>";
                } elseif (is_numeric($v)) {
                    $html .= "<li>$k : " . $v . "</li>";
                } elseif (is_object($v)) {
                    $html .= "<li>$k : " . get_class($v) . "</li>";
                } elseif (is_array($v) && !is_array($k)) {
                    $html .= "<li>$k : " . implode(', ', array_keys($v)) . "</li>";
                } elseif (is_resource($v)) {
                    $html .= "<li>$k : " . get_resource_type($v) . "</li>";
                }
            }
            $html .= "</$tag>";
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
        $attributes['href'] = $url;
        if (is_null($label) || $label === '') {
            $label = $url;
        }
        return $this->surround($label, 'a', $attributes);
    }

    /**
     * Obtenir les attributs d'une balise HTML dans une chaîne de caractères
     *
     * @param array|null $attributes Attributs d'une balise HTML
     *
     * @return string|null
     */
    private function parseAttributes(array $attributes = [])
    {
        $ret = [];
        foreach ($attributes as $attribute => $value) {
            $ret[] = $attribute . '="' . $value . '"';
        }
        sort($ret);
        return !empty($ret)
            ? ' ' . implode(' ', $ret)
            : null;
    }
}
