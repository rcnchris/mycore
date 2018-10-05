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
     * Instance du modèle principal
     *
     * @var Model
     */
    public $mainModel;

    /**
     * Nom de la table à joindre
     *
     * @var string
     */
    public $refTable;

    /**
     * Options de la relation
     *
     * @var array
     */
    public $options = [];

    /**
     * Constructeur
     *
     * @param string $tableName Nom de la table
     * @param array  $options   Options de la relation
     */
    public function __construct($tableName, array $options)
    {
        $this->refTable = $tableName;
        foreach ($options as $k => $v) {
            $this->options[$k] = $v;
        }
        $this->mainModel = $this->options['mainModel'];
        unset($this->options['mainModel']);
    }

    /**
     * Obtenir la description verbeuse de la relation
     *
     * @return string
     */
    public function __toString()
    {
        return 'One ' . $this->mainModel->getTableName() . ' '
        . $this->options['type'] . ' '
        . $this->refTable . ' where '
        . $this->options['conditions'];
    }

    /**
     * Obtenir la liste des enregistrements liés
     *
     * @return array
     */
    public function findList()
    {
        $query = new Query($this->mainModel->getPdo());
        if (isset($this->options['conditions'])) {
            $conditions = $this->options['conditions'];
            if (is_array($conditions)) {
                foreach ($conditions as $field => $value) {
                    $query->where($field . ' = ' . $value);
                }
            } elseif (is_string($conditions)) {
                $query->where($conditions);
            }
        }
        return $query->from($this->refTable)->all()->toArray();
    }
}
