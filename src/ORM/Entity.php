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
     * Définir la date de création
     *
     * @param string|null $datetime Date
     */
    public function setCreated($datetime = null)
    {
        if (is_null($datetime)) {
            $this->created = new \DateTime(date('y-m-d H:i:s'));
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
            $this->modified = new \DateTime(date('y-m-d H:i:s'));
        } elseif (is_string($datetime)) {
            $this->modified = new \DateTime($datetime);
        }
    }
}
