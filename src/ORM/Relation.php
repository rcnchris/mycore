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
     * Modèle qui possède la relation
     *
     * @var object
     */
    private $mainModel;

    /**
     * Modèle à lier
     *
     * @var mixed
     */
    private $model;

    /**
     * Constructeur
     *
     * @param Model        $mainModel
     * @param Model|string $model
     * @param array|null   $options
     */
    public function __construct($mainModel, $model, array $options = [])
    {
        if (is_string($model) && class_exists($model)) {
            $this->model = new $model($mainModel->getPdo());
        } elseif ($model instanceof Model) {
            $this->model = $model;
        }
        $this->mainModel = $mainModel;
    }
}
