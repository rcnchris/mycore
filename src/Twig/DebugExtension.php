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

/**
 * Class DebugExtension
 *
 * @category Debug
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
            new \Twig_SimpleFilter('getClass', [$this, 'getClass'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('getMethods', [$this, 'getMethods'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('getParentClass', [$this, 'getParentClass'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('getParentMethods', [$this, 'getParentMethods'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('getImplements', [$this, 'getImplements'], ['is_safe' => ['html']])
            , new \Twig_SimpleFilter('getTraits', [$this, 'getTraits'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Obtenir le nom de la classe d'un objet
     *
     * @param $object
     *
     * @return bool|string
     */
    public function getClass($object)
    {
        if (is_object($object)) {
            return get_class($object);
        }
        return false;
    }

    /**
     * Obtenir les méthodes d'un objet
     *
     * @param $object
     *
     * @return array|bool
     */
    public function getMethods($object)
    {
        if (is_object($object)) {
            $methods = get_class_methods(get_class($object));
            sort($methods);
            return $methods;
        }
        return false;
    }

    /**
     * Nom du parent de la classe de l'objet passé en paramètre
     *
     * @exemple object|getParentClass
     *
     * @param object $value Objet dont il faut récupérer le nom du parent
     *
     * @return bool|string
     */
    public function getParentClass($value)
    {
        return is_object($value) ? get_parent_class($value) : false;
    }

    /**
     * Obtenir les méthodes du parent d'un objet
     *
     * @param $value
     *
     * @return array
     */
    public function getParentMethods($value)
    {
        return get_class_methods(get_parent_class($value));
    }

    /**
     * Obtenir les interfaces utilisées par un objet
     *
     * @param $value
     *
     * @return array|bool
     */
    public function getImplements($value)
    {
        return is_object($value) ? class_implements($value) : false;
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
        return is_object($value) ? class_uses($value) : false;
    }

    /**
     * Obtenir la liste des fonctions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('r', [$this, 'phpRef'], ['is_safe' => ['html']])
            , new \Twig_SimpleFunction('vd', [$this, 'vd'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Faire un var_dump
     *
     * @param $value
     */
    public function vd($value)
    {
        var_dump($value);
    }

    /**
     * Utiliser phpref pour debugger
     *
     * @param $value
     */
    public function phpRef($value)
    {
        r($value);
    }
}