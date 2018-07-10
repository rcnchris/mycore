<?php
/**
 * Fichier CakeEntity.php du 10/07/2018
 * Description : Fichier de la classe CakeEntity
 *
 * PHP version 5
 *
 * @category Entité
 *
 * @package  Rcnchris\Core\ORM\Cake
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM\Cake;

use Cake\ORM\Entity;

/**
 * Class CakeEntity
 *
 * @category Entité
 *
 * @package  Rcnchris\Core\ORM\Cake
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class CakeEntity extends Entity
{
    /**
     * Propriétés accessibles en modification
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    /**
     * Obtenir le chemin racine de l'application
     *
     * @return string
     */
    protected function rootPath()
    {
        return dirname(dirname(dirname(__DIR__)));
    }
}
