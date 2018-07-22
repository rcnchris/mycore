<?php
/**
 * Fichier DebugExtension.php du 09/10/2017
 * Description : Fichier de la classe DebugExtension
 *
 * PHP version 5
 *
 * @category Debug
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

use Rcnchris\Core\Tools\Debug;

/**
 * Class DebugExtension
 * <ul>
 * <li>Helper de Debug</li>
 * </ul>
 *
 * @category Debug
 *
 * @package  Rcnchris\Core\Twig
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.0>
 */
class DebugExtension extends \Twig_Extension
{

    /**
     * Obtenir la liste des filtres
     *
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('getClass', [$this, 'getClass'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('getMethods', [$this, 'getMethods'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('getProperties', [$this, 'getProperties'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('getParentClass', [$this, 'getParentClass'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('getParentMethods', [$this, 'getParentMethods'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('getImplements', [$this, 'getImplements'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('getTraits', [$this, 'getTraits'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('isObject', [$this, 'isObject'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('isArray', [$this, 'isArray'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir le nom de la classe d'un objet
     * - Filtre
     *
     * @param object $object Objet dont il faut récupérer le nom de la classe
     *
     * @return bool|string
     */
    public function getClass($object)
    {
        try {
            return Debug::getClass($object);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir les méthodes d'un objet
     * - Filtre
     *
     * @param object $object Objet
     *
     * @return array|bool
     */
    public function getMethods($object)
    {
        try {
            return Debug::getMethods($object);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir la liste des propriétés publiques d'un objet
     * - Filtre
     *
     * ### Exemple
     * * `o|getProperties`
     *
     * @param object $o Objet
     *
     * @return array
     */
    public function getProperties($o)
    {
        return Debug::getProperties($o);
    }

    /**
     * Nom du parent de la classe de l'objet passé en paramètre
     * - Filtre
     *
     * ### Exemple
     * - `object|getParentClass;`
     * - `object|getParentClass(true);`
     *
     * @param object    $value  Objet dont il faut récupérer le nom du parent
     *
     * @return bool|string
     */
    public function getParentClass($value)
    {
        try {
            return Debug::getParents($value);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtenir les méthodes du parent d'un objet
     * - Filtre
     *
     * @param $value
     *
     * @return array
     */
    public function getParentMethods($value)
    {
        return Debug::getParentsMethods($value);
    }

    /**
     * Obtenir les interfaces utilisées par un objet
     * - Filtre
     *
     * ### Exemple
     * - `o|getImplements`
     *
     * @param object $value Objet
     *
     * @return array|bool
     */
    public function getImplements($value)
    {
        return Debug::getInterfaces($value);
    }

    /**
     * Obtenir la liste des traits utilisés
     * - Filtre
     *
     * ### Exemple
     * - `o|getTraits`
     *
     * @param object $value Objet dont on veut connaître les traits utilisés
     *
     * @return array|bool
     */
    public function getTraits($value)
    {
        return Debug::getTraits($value);
    }

    /**
     * Vérifie si la variable est un objet
     * - Filtre
     *
     * @param mixed $var Variable
     *
     * @return bool
     */
    public function isObject($var)
    {
        return is_object($var);
    }

    /**
     * Vérifie si la variable est un tableau
     * - Filtre
     *
     * @param mixed $var Variable
     *
     * @return bool
     */
    public function isArray($var)
    {
        return is_array($var);
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('r', [$this, 'phpRef'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('vd', [$this, 'vd'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('getConstants', [$this, 'getConstants'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Faire un var_dump
     * - Fonction
     *
     * @param $value
     */
    public function vd($value)
    {
        var_dump($value);
    }

    /**
     * Utiliser phpref pour debugger
     * - Fonction
     *
     * @param $value
     */
    public function phpRef($value)
    {
        r($value);
    }

    /**
     * Obtenir les constantes définies ou l'une d'entre elles
     * - Fonction
     *
     * @param string|null $key Nom de l'extension
     *
     * @return array [mixed]
     */
    public function getConstants($key = null)
    {
        $constants = get_defined_constants(true);
        if (!is_null($key) && array_key_exists($key, $constants)) {
            return $constants[$key];
        }
        return $constants;
    }
}
