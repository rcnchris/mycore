<?php
/**
 * Fichier PostsEntity.php du 30/04/2018
 * Description : Fichier de la classe PostsEntity
 *
 * PHP version 5
 *
 * @category Entités
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
 * Class PostsEntity
 *
 * @category Entités
 *
 * @package  Tests\Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class PostsEntity extends Entity
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $category_id;
}
