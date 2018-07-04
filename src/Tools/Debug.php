<?php
/**
 * Fichier Debug.php du 01/07/2018
 * Description : Fichier de la classe Debug
 *
 * PHP version 5
 *
 * @category New
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

/**
 * Class Debug
 *
 * @category New
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class Debug
{

    /**
     * Instance
     *
     * @var $this
     */
    private static $instance;

    /**
     * Obtenir une instance (Singleton)
     *
     * @return \Rcnchris\Core\Tools\Debug
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param object $object
     *
     * @return string
     * @throws \Exception
     * @see http://php.net/manual/fr/function.get-class.php
     */
    public static function getClass($object)
    {
        return get_class(self::isObject($object));
    }

    /**
     * Obtenir le nom sans le namespace
     *
     * @param null $object
     *
     * @return string
     */
    public static function getClassShortName($object = null)
    {
        $className = self::getClass($object);
        $parts = explode('\\', $className);
        return array_pop($parts);
    }

    /**
     * Obtenir les propriétés d'un objet
     *
     * @param object|null $object
     *
     * @return array
     * @throws \Exception
     * @see http://php.net/manual/fr/function.get-object-vars.php
     */
    public static function getProperties($object = null)
    {
        return get_object_vars(self::isObject($object));
    }

    /**
     * @param null $object
     *
     * @return array
     * @see http://php.net/manual/fr/function.get-class-methods.php
     */
    public static function getMethods($object = null)
    {
        return get_class_methods(self::getClass($object));
    }

    /**
     * Obtenir les méthodes des parents
     *
     * @param object|null $object
     *
     * @return array
     */
    public static function getParentsMethods($object = null)
    {
        $methods = [];
        foreach (self::getParents($object) as $parent) {
            $methods[$parent] = get_class_methods(get_parent_class($parent));
        }
        return $methods;
    }

    /**
     * Retourne la liste des parents
     *
     * @param object|null $object  Objet dont il faut retourner la liste des parents
     * @param bool|null   $reverse inverse l'ordre de retour du tableau
     *
     * @return array
     * @see http://php.net/manual/fr/function.get-parent-class.php
     */
    public static function getParents($object = null, $reverse = false)
    {
        $parents = [];
        $value = self::getClass($object);
        while (get_parent_class($value) != null) {
            $parent = get_parent_class($value);
            $parents[] = $parent;
            $value = $parent;
        }
        if ($reverse) {
            return array_reverse($parents);
        }
        return $parents;
    }

    /**
     * Obtenir la liste des interfaces utilisées
     *
     * @param null $object
     *
     * @return array
     * @see http://php.net/manual/fr/function.class-uses.php
     */
    public static function getInterfaces($object = null)
    {
        return array_keys(class_implements(self::getClass($object)));
    }

    /**
     * Obtenir la liste des traits utilisés
     *
     * @param null $object
     *
     * @return array
     * @see http://php.net/manual/fr/function.class-uses.php
     */
    public static function getTraits($object)
    {
        return array_keys(class_uses(self::getClass($object)));
    }

    /**
     * Obtenir la liste des constantes
     *
     * @param string|null $key Clé de la constante à retourner
     *
     * @return array|mixed
     */
    public static function getConstants($key = null)
    {
        $constants = get_defined_constants(true);
        if (!is_null($key) && array_key_exists($key, $constants)) {
            return $constants[$key];
        }
        return $constants;
    }

    /**
     * Vérifie que la variable est un objet sinon lève une `Exception`
     *
     * @param mixed $variable Variable qui doit être un objet
     *
     * @return $this
     * @throws \Exception
     */
    private static function isObject($variable)
    {
        if (!is_object($variable)) {
            throw new \Exception("Le type de la variable passé à la fonction doit être un objet. Là c'est : " . gettype($variable) . "... Pas bien...");
        }
        return $variable;
    }
}
