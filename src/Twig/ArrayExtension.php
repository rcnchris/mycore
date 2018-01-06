<?php
/**
 * Fichier ArrayExtension.php du 06/01/2018
 * Description : Fichier de la classe ArrayExtension
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Rcnchris\Core\Twig
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Twig;

/**
 * Class ArrayExtension
 *
 * @category New
 *
 * @package Rcnchris\Core\Twig
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris/fmk-php GPL
 *
 * @version Release: <1.0.0>
 *
 * @link https://github.com/rcnchris/fmk-php on Github
 */
class ArrayExtension extends \Twig_Extension
{
    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('arrayMerge', [$this, 'arrayMerge'])
            , new \Twig_SimpleFunction('extract', [$this, 'extract'])
            , new \Twig_SimpleFunction('inArray', [$this, 'inArray'])
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
     * @param       $value
     * @param array $array
     *
     * @return bool
     */
    public function inArray($value, array $array)
    {
        return in_array($value, $array);
    }
}
