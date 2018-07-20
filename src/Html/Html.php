<?php
/**
 * Fichier Html.php du 13/07/2018
 * Description : Fichier de la classe Html
 *
 * PHP version 5
 *
 * @category HTML
 *
 * @package  Rcnchris\Core\Html
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Html;

/**
 * Class Html
 *
 * @category HTML
 *
 * @package  Rcnchris\Core\Html
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Html
{
    /**
     * Instance de cette classe
     *
     * @var self
     */
    private static $instance;

    /**
     * Obtenir l'instance de cette classe
     *
     * @return \Rcnchris\Core\Html\Html
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Génère un lien
     *
     * @param string     $label      Label du lien
     * @param string     $link       Lien
     * @param array|null $attributes Attributs du lien
     *
     * @return string
     */
    public static function link($label, $link, array $attributes = [])
    {
        $attributes = array_merge($attributes, ['href' => $link]);
        return self::surround($label, 'a', $attributes);
    }

    /**
     * Obtenir une balise `img`
     *
     * @param string     $src        Chemin de l'image
     * @param array|null $attributes Attributs de la balise `img`
     *
     * @return string
     */
    public static function img($src, array $attributes = [])
    {
        $attributes = array_merge($attributes, ['src' => $src]);
        return '<img' . self::parseAttributes($attributes) . '>';
    }

    /**
     * Générer une liste `ul` ou `ol` selon le type
     *
     * @param mixed      $items      Liste des items à lister
     * @param array|null $attributes Attributs de la liste
     * @param bool|null  $withKeys   La valeur est précédée de sa clé
     *
     * @return string
     */
    public static function liste($items, array $attributes = ['type' => 'ul'], $withKeys = true)
    {
        $type = $attributes['type'];
        unset($attributes['type']);

        $html = '';
        if ($type != 'dl') {
            foreach ($items as $key => $value) {
                if (!is_array($value)) {
                    $html = $withKeys
                        ? $html . self::surround($key . ' : ' . $value, 'li')
                        : $html . self::surround($value, 'li');
                }
            }
        } else {
            foreach ($items as $key => $value) {
                $html .= self::surround($key, 'dt') . self::surround($value, 'dd');
            }
        }
        return self::surround($html, $type);
    }

    /**
     * Générer une balise `details`
     *
     * @param string     $title      Titre
     * @param string     $content    Contenu
     * @param array|null $attributes Attributs de la balise `details`
     *
     * @return string
     */
    public static function details($title, $content, array $attributes = [])
    {
        return self::surround(self::surround($title, 'summary') . $content, 'details', $attributes);
    }

    /**
     * Entourre le contenu d'une balise
     *
     * @param string     $content    Contenu à entourrer
     * @param string     $tag        Balise HTML
     * @param array|null $attributes Attributs de la balise qui entourre
     *
     * @return string
     */
    public static function surround($content, $tag, array $attributes = [])
    {
        $attr = self::parseAttributes($attributes);
        return '<' . $tag . $attr . '>' . $content . '</' . $tag . '>';
        //return "<$tag $attr>$content</$tag>";
    }

    /**
     * Générer le contenu d'un fichier source formaté
     *
     * @param string     $content    Chemin du fichier ou son contenu
     * @param array|null $attributes Attributs de la balise `pre`
     * @param bool|null  $withHeader Si vrai, le chemin du fichier précède l'affichage de son contenu dans le cas où le
     *                               contenu passé est un chemin de fichier
     *
     * @return string
     */
    public static function source($content, array $attributes = [], $withHeader = false)
    {
        $header = null;
        $ext = null;
        if (is_file($content)) {
            $file = $content;
            $parts = explode('.', $file);
            if (count($parts) > 1) {
                $ext = array_pop($parts);
            }
            if ($withHeader) {
                $header = self::surround($file, 'code') . "\n";
            }
            $content = strtolower($ext) === 'php'
                ? highlight_file($file, true)
                : htmlentities(file_get_contents($file));
        }
        return $header . self::surround($content, 'pre', $attributes);
    }

    /**
     * Obtenir un tableau HTML à partir d'une liste
     * - `$html->table($items);`
     * - `$html->table($items, ['class' => 'table table-sm']);`
     * - `$html->table($items, ['caption' => 'Un titre']);`
     * - `$html->table($items, [], false);`
     *
     * @param mixed      $items      Liste
     * @param array|null $attributes Attributs du tableau
     * @param bool|null  $withHeader fficher les entêtes
     * @param bool|null  $colMode    Lite ou présentation (list, presentation)
     *
     * @return string
     */
    public static function table($items, array $attributes = [], $withHeader = true, $colMode = false)
    {
        // Caption
        $caption = null;
        if (array_key_exists('caption', $attributes)) {
            $caption = self::surround($attributes['caption'], 'caption');
            unset($attributes['caption']);
        }

        // Attributes
        $attributes = self::parseAttributes($attributes);

        $html = '<table' . $attributes . '>';
        $html .= $caption;

        // Header
        if ($colMode) {
            $html .= '<thead><tr>';
            foreach (array_keys($items) as $key) {
                $html .= self::surround($key, 'th');
            }
            $html .= '</tr></thead>';
        }

        // Body
        $html .= '<tbody>';
        if ($colMode) {
            $html .= '<tr>';
            foreach ($items as $key => $value) {
                if (is_array($value)) {
                    $html .= self::surround(self::table($value, [], $withHeader, $colMode), 'td');
                } else {
                    $html .= self::surround($value, 'td');
                }
            }
            $html .= '</tr>';
        } else {
            foreach ($items as $key => $value) {
                $html .= '<tr>';
                if ($withHeader) {
                    $html .= self::surround($key, 'th');
                }
                if (is_array($value)) {
                    $html .= self::surround(self::table($value, [], $withHeader, $colMode), 'td');
                } else {
                    $html .= self::surround($value, 'td');
                }
                $html .= '</tr>';
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    /**
     * Obtenir les attributs d'une balise HTML dans une chaîne de caractères
     *
     * @param array $attributes
     *
     * @return string
     */
    protected static function parseAttributes(array $attributes)
    {
        $attr = [];
        foreach ($attributes as $key => $value) {
            $attr[] = $key . '="' . $value . '"';
        }
        return !empty($attr)
            ? ' ' . implode(' ', $attr)
            : null;
    }
}
