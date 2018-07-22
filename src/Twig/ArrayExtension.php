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

use Rcnchris\Core\Html\Html;

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
     * @return \Twig_SimpleFilter[]
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
        if (array_key_exists('class', $options)) {
            $class = $options['class'];
        }
        return Html::table(
            $values,
            isset($class) ? ['class' => $class] : [],
            array_key_exists('key', $options),
            array_key_exists('col', $options)
        );
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('arrayMerge', [$this, 'arrayMerge']),
            new \Twig_SimpleFunction('extract', [$this, 'extract']),
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
