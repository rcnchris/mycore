<?php
/**
 * Fichier Hydrator.php du 20/10/2017
 * Description : Fichier de la classe Hydrator
 *
 * PHP version 5
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM;

/**
 * Class Hydrator
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class Hydrator
{
    /**
     * Définir les valeurs des propriétés d'un objet avec les valeurs de la base de données
     *
     * ### Exemple
     * - `Hydrator::hydrate($this->records[$index], $this->entity);`
     *
     * @param array         $array  Tableau de données
     * @param string|object $object Entité à hydrater
     *
     * @return object
     */
    public static function hydrate(array $array, $object)
    {
        $instance = is_string($object)
            ? new $object()
            : $object;
        foreach ($array as $key => $value) {
            $method = self::getSetter($key);
            if (method_exists($instance, $method)) {
                $instance->$method($value);
            } else {
                $property = lcfirst(self::getProperty($key));
                $instance->$property = $value;
            }
        }
        return $instance;
    }

    /**
     * Obtenir le setter
     *
     * ### Exemple
     * - `self::getSetter($key);`
     *
     * @param $fieldName
     *
     * @return string
     */
    private static function getSetter($fieldName)
    {
        return 'set' . self::getProperty($fieldName);
    }

    /**
     * Obtenir la propriété
     *
     * ### Exemple
     * - `self::getProperty($key);`
     *
     * @param $fieldName
     *
     * @return string
     */
    private static function getProperty($fieldName)
    {
        return join('', array_map('ucfirst', explode('_', $fieldName)));
    }
}
