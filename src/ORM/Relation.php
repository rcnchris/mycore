<?php
/**
 * Fichier Relation.php du 21/12/2017
 * Description : Fichier de la classe Relation
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
 * Class Relation<br/>
 * <ul>
 * <li>Représente une relation entre deux modèles</li>
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
class Relation
{

    /**
     * Nom de la table
     *
     * @var string
     */
    public $tableName;

    public function __construct($tableName, array $options)
    {
        $this->tableName = $tableName;
        foreach ($options as $k => $v) {
            $this->$k = $v;
        }
    }
}
