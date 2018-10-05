<?php
/**
 * Fichier User.php du 04/10/2018
 * Description : Fichier de la classe User
 *
 * PHP version 5
 *
 * @category base de données
 *
 * @package  Tests\Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\Entity;

/**
 * Class User
 *
 * @category base de données
 *
 * @package  Tests\Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class User extends Entity
{
    /**
     * Date anniversaire
     *
     * @var \DateTime
     */
    public $birthday;

    /**
     * @var string
     */
    public $civilite;

    /**
     * Obtenir la date d'anniversaire sous forme d'objet
     *
     * @param string|null $birthday Date anniversaire
     */
    public function setBirthday($birthday = null)
    {
        $this->birthday = \DateTime::createFromFormat('Y-m-d H:i:s', $birthday);
    }

    public function setCivilite()
    {

    }
}
