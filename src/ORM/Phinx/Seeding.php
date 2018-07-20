<?php
/**
 * Fichier Seeding.php du 10/07/2018
 * Description : Fichier de la classe Seeding
 *
 * PHP version 5
 *
 * @category Seeds
 *
 * @package  Rcnchris\Core\ORM\Phinx
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM\Phinx;

use Cake\ORM\TableRegistry;
use Phinx\Seed\AbstractSeed;

/**
 * Class Seeding
 *
 * @category Seeds
 *
 * @package  Rcnchris\Core\ORM\Phinx
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Seeding extends AbstractSeed
{
    const SEEDINGS_TABLE_NAME = 'seedings';
    const DEB_HISTO = '2016-01-01 09:00:00';
    const YEAR_APPLICATION_DATE = 10;

    /**
     * Tableau des données à insérer
     *
     * @var array
     */
    protected $items = [];

    /**
     * Démarrage du seeding
     *
     * @var \Datetime
     */
    private $start;

    /**
     * Obtenir la date au format MySQL
     *
     * @param int $addYear Nombre d'année à ajouter à la date du jour
     *
     * @return bool|string
     */
    protected function nowMysql($addYear = 0)
    {
        return $addYear === 0 ?
            date('Y-m-d H:i:s') :
            date('Y-m-d H:i:s', time() + 3600 * 24 * 360 * $addYear);
    }

    /**
     * Trace du seeding
     *
     * @param string   $description Description du seeding
     * @param string   $migration   Nom de la migration associée
     * @param int|null $nbItems     Nombre d'items insérés par le seeding
     */
    protected function logSeeding($description, $migration = null, $nbItems = null)
    {
        $trace = [
            [
                'title' => $this->getName(),
                'description' => $description,
                'data' => serialize($this->items),
                'nb_items' => is_null($nbItems) ? count($this->items) : $nbItems,
                'started_at' => $this->start->format('Y-m-d H:i:s'),
                'ended_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'migration_name' => $migration
            ]
        ];
        $this->table($this::SEEDINGS_TABLE_NAME)->insert($trace)->save();
    }

    /**
     * Appelle la méthode `start`
     *
     * @param float|null $start Timestamp de départ
     *
     * @return void
     */
    protected function startSeeding($start = null)
    {
        if (is_null($start)) {
            $this->start();
        }
    }

    /**
     * Fixe le début du traitement de seeding
     *
     * @return float|mixed
     */
    private function start()
    {
        if (is_null($this->start)) {
            $this->start = new \DateTime();
        }
        return $this->start;
    }

    /**
     * Obtenir le chemin de la racine du projet
     *
     * @return string
     */
    protected function rootPath()
    {
        return dirname(__DIR__);
    }

    /**
     * Obtenir la liste des bases de données
     *
     * @return array|mixed
     */
    protected function dbList()
    {
        return $this->query('show databases;');
    }

    /**
     * Obtenir l'instance d'une table Cake
     *
     * @param string $tableName Nom de la table
     * @param string $className Nom de la classe de la table
     *
     * @return \Cake\ORM\Table
     */
    protected function getCakeTable($tableName, $className)
    {
        return TableRegistry::getTableLocator()->get($tableName, ['className' => $className]);
    }
}
