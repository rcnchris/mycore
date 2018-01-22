<?php
/**
 * Fichier TagEntity.php du 17/01/2018 
 * Description : Fichier de la classe TagEntity 
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\ORM\Model
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\ORM\Model;
use Rcnchris\Core\ORM\Entity;

/**
 * Class TagEntity
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\ORM\Model
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris/fmk-php GPL
 *
 * @version Release: <1.0.0>
 *
 * @link https://github.com/rcnchris/fmk-php on Github
 */
class TagEntity extends Entity
{

    /**
     * @var string
     */
    public $title;
}