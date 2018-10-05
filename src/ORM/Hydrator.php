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
 * <ul>
 * <li>Est chargé de peuplé les objets avec les informations de la bases de données</li>
 * </ul>
 *
 * @category Base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class Hydrator
{
    /**
     * Définir les valeurs des propriétés d'un objet avec les valeurs de la base de données
     *
     * ### Example
     * - `Hydrator::hydrate($this->records[$index], $this->entity);`
     *
     * @param array         $array  Tableau de données
     * @param string|object $entityClass Entité à hydrater
     *
     * @return object
     */
    public static function hydrate(array $array, $entityClass, \PDO $pdo = null)
    {
        $instance = is_string($entityClass)
            ? new $entityClass($pdo)
            : $entityClass;
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
     * ### Example
     * - `self::getSetter($key);`
     *
     * @param string $fieldName Nom du champ
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
     * ### Example
     * - `self::getProperty($key);`
     *
     * @param string $fieldName Nom du champ
     *
     * @return string
     */
    private static function getProperty($fieldName)
    {
        return join('', array_map('ucfirst', explode('_', $fieldName)));
    }
}
