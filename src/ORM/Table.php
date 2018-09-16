<?php
/**
 * Fichier Table.php du 16/09/2018
 * Description : Fichier de la classe Table
 *
 * PHP version 5
 *
 * @category base de données
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
 * Class Table
 *
 * @category base de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Table
{

    /**
     * Nom de la table
     *
     * @var string
     */
    private $name;

    /**
     * Instance de PDO
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Constructeur
     *
     * @param string $name Nom de la table
     * @param \PDO   $pdo  Instance PDO
     */
    public function __construct($name, \PDO $pdo)
    {
        $this->name = $name;
        $this->setPdo($pdo);
    }

    /**
     * Obtenir l'instance de PDO
     *
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Définir l'instance de PDO
     *
     * @param \PDO $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Obtenir le nom de la table
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Obtenir une requête à partir de la table
     *
     * @return Query
     */
    public function query()
    {
        return (new Query($this->getPdo()))
            ->from($this->getName());
    }
}
