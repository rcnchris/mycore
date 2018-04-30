<?php
/**
 * Fichier PostsModel.php du 30/04/2018
 * Description : Fichier de la classe PostsModel
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
 * Class PostsModel
 *
 * @category Modèles
 *
 * @package  Tests\Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class PostsModel extends Model
{
    /**
     * Constructeur
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        $this->setTable('posts');
        $this->setEntity(PostsEntity::class);
        $this->belongsTo('categories', [
            'foreignKey' => 'category_id'
            , 'propertyName' => 'category'
        ]);
    }
}
