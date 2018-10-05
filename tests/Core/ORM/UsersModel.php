<?php
/**
 * Fichier UsersModel.php du 30/04/2018
 * Description : Fichier de la classe UsersModel
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
 * Class UsersModel
 *
 * @category Modèles
 *
 * @package  Tests\Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class UsersModel extends Model
{
    /**
     * Constructeur
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        $this->setTable('users');
        $this->setEntity(User::class);
        $this->belongsTo('tabs', [
            'propertyName' => 'civilite',
            'conditions' => 'parent_id = 1'
        ]);
    }
}
