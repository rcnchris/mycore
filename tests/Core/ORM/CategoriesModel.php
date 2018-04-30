<?php
/**
 * Fichier CategoriesModel.php du 30/04/2018
 * Description : Fichier de la classe CategoriesModel
 *
 * PHP version 5
 *
 * @category Modèles
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

use Rcnchris\Core\ORM\Model;

/**
 * Class CategoriesModel
 *
 * @category Modèles
 *
 * @package  Tests\Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class CategoriesModel extends Model
{
    /**
     * Constructeur
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        $this->setTable('categories');
        $this->hasMany('posts');
    }
}
