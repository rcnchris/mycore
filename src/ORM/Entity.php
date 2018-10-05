<?php
/**
 * Fichier Entity.php du 10/01/2018
 * Description : Fichier de la classe Entity
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
 * Class Entity
 * <ul>
 * <li>Représente un enregistrement au sein d'un Model</li>
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
class Entity
{
    /**
     * Identifiant
     *
     * @var int
     */
    public $id;

    /**
     * Date de création
     *
     * @var \DateTime
     */
    public $created;

    /**
     * Date de modification
     *
     * @var \DateTime
     */
    public $modified;

    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * Définir la date de création
     *
     * @param string|null $datetime Date
     */
    public function setCreated($datetime = null)
    {
        if (is_null($datetime)) {
            $this->created = new \DateTime(date('Y-m-d H:i:s'));
        } elseif (is_string($datetime)) {
            $this->created = new \DateTime($datetime);
        }
    }

    /**
     * Définir la date de modification
     *
     * @param string|null $datetime Date
     */
    public function setModified($datetime = null)
    {
        if (is_null($datetime)) {
            $this->modified = new \DateTime(date('Y-m-d H:i:s'));
        } elseif (is_string($datetime)) {
            $this->modified = new \DateTime($datetime);
        }
    }

    /**
     * Obtenir la liste des propriétés de l'entité avec leur type respectif
     *
     * @return array
     */
    public function getProperties()
    {
        $properties = [];
        foreach (get_object_vars($this) as $propertyName => $value) {
            $properties[$propertyName] = gettype($value);
        }
        return $properties;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
