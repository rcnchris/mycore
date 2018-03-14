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
 * Class Relation
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
 * @version  Release: <1.0.0>
 * @since    Release: <0.1.2>
 */
class Relation
{

    /**
     * Nom de la table
     *
     * @var string
     */
    public $tableName;

    /**
     * Constructeur
     *
     * @param string $tableName Nom de la table
     * @param array  $options   Options de la relation
     */
    public function __construct($tableName, array $options)
    {
        $this->tableName = $tableName;
        foreach ($options as $k => $v) {
            $this->$k = $v;
        }
    }
}
