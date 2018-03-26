<?php
/**
 * Fichier ArrayExtension.php du 06/01/2018
 * Description : Fichier de la classe ArrayExtension
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
 * Class ArrayExtension
 * <ul>
 * <li>Helper sur les tableaux</li>
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
class ArrayExtension extends \Twig_Extension
{
    /**
     * Obtenir la liste des filtres
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('toHtml', [$this, 'toHtml'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir un tableau HTML à partir d'un tableau PHP
     *
     * @param array $values  Tableau à afficher
     * @param array $options Options du tableau
     *
     * @return string
     */
    public function toHtml(array $values, array $options = [])
    {
        $class = null;

        // Application des options
        if (array_key_exists('class', $options)) {
            $class = ' class="' . $options['class'] . '"';
        }
        $withHeader = false;
        if (array_key_exists('header', $options)) {
            $withHeader = true;
        }

        $html = "<table$class>";
        $keys = array_keys($values);
        if (is_numeric($keys[0]) && !is_array(current($values))) {
            // La valeur n'est pas un tableau, donc liste simple
            if ($withHeader) {
                $html .= "<thead>";
                $html .= "<tr><th>#</th><th>Libellé</th></tr>";
                $html .= "</thead>";
            }
            $html .= '<tbody>';
            foreach ($values as $k => $value) {
                $html .= "<tr><td>$k</td><td>$value</td></tr>";
            }
            $html .= '</tbody>';
        } elseif (is_numeric($keys[0]) && is_array(current($values))) {
            // La valeur est un tableau
            $html .= '<thead><tr>';
            $keys = array_keys(current($values));
            foreach ($keys as $field) {
                $html .= "<th>$field</th>";
            }
            $html .= '</tr></thead><tbody>';

            foreach ($values as $k => $item) {
                $html .= "<tr>";
                foreach ($keys as $field) {
                    $html .= "<td>" . $item[$field] . "</td>";
                }
                $html .= "</tr>";
            }
            $html .= '</tbody>';
        } elseif (is_string($keys[0])) {
            // Tableau associatif
            $html .= '<thead><tr><th>Clé</th><th>Valeur</th></tr></thead>';
            $html .= '<tbody>';
            foreach ($values as $field => $value) {
                $html .= "<tr>";
                $html .= "<th>$field</th>";
                $html .= "<td>$value</td>";
                $html .= "</tr>";
            }
            $html .= '</tbody>';
        }
        $html .= '</table>';
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
            new \Twig_SimpleFunction('arrayMerge', [$this, 'arrayMerge'])
            ,
            new \Twig_SimpleFunction('extract', [$this, 'extract'])
            ,
            new \Twig_SimpleFunction('inArray', [$this, 'inArray'])
        ];
    }

    /**
     * Fusionner plusieurs tableaux
     *
     * @return array
     */
    public function arrayMerge()
    {
        $args = func_get_args();
        $ret = [];
        foreach ($args as $log) {
            foreach ($log as $query) {
                $ret[] = $query;
            }
        }
        return $ret;
    }

    /**
     * Extrait les valeurs d'une colonne d'un tableau
     *
     * @param array       $array Tableau d'entrée
     * @param string      $value Nom de la colonne à extraire
     * @param string|null $key   Nom de la colonne qui servira de clé au tableau
     *
     * @return array
     */
    public function extract(array $array, $value, $key = null)
    {
        return array_column($array, $value, $key);
    }

    /**
     * Vérifie la présence d'une valeur dans un tableau
     *
     * @param mixed $value Valeur à chercher
     * @param array $array Tableau de valeurs
     *
     * @return bool
     */
    public function inArray($value, array $array)
    {
        return in_array($value, $array);
    }
}
