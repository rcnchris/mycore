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
 * Class Entity<br/>
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
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
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
     * @param $datetime
     */
    public function setCreated($datetime)
    {
        if (is_string($datetime)) {
            $this->created = new \DateTime($datetime);
        }
    }

    /**
     * Définir la date de modification
     *
     * @param $datetime
     */
    public function setModified($datetime)
    {
        if (is_string($datetime)) {
            $this->modified = new \DateTime($datetime);
        }
    }
}
