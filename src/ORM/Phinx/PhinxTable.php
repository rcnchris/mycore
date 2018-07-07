<?php
/**
 * Fichier PhinxTable.php du 28/06/2018
 * Description : Fichier de la classe PhinxTable
 *
 * PHP version 5
 *
 * @category New
 *
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM\Phinx;

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Table;

/**
 * Class PhinxTable
 * <ul>
 * <li>Table d'une base de données relationnelle</li>
 * </ul>
 *
 * @category ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class PhinxTable extends Table
{

    /**
     * Class Constuctor.
     *
     * @param string                             $name    Table Name
     * @param array                              $options Options
     * @param \Phinx\Db\Adapter\AdapterInterface $adapter Database Adapter
     */
    public function __construct($name, $options = [], AdapterInterface $adapter = null)
    {
        parent::__construct($name, $options, $adapter);
    }

    /**
     * Ajoute une colonne à la table
     *
     * @param \Phinx\Db\Table\Column|string $columnName
     * @param null                          $type
     * @param array                         $options
     *
     * @return $this
     */
    public function addColumn($columnName, $type = null, $options = [])
    {
        parent::addColumn($columnName, $type, $options);
        return $this;
    }

    /**
     * Ajoute un index à la table
     *
     * @param array|\Phinx\Db\Table\Index|string $columns
     * @param array                              $options
     *
     * @return $this
     */
    public function addIndex($columns, array $options = [])
    {
        parent::addIndex($columns, $options);
        return $this;
    }

    /**
     * Ajoute une clé étrangère à la table
     *
     * @param array|string           $columns
     * @param \Phinx\Db\Table|string $referencedTable
     * @param array                  $referencedColumns
     * @param array                  $options
     *
     * @return $this
     */
    public function addForeignKey($columns, $referencedTable, $referencedColumns = ['id'], $options = [])
    {
        parent::addForeignKey($columns, $referencedTable, $referencedColumns, $options);
        return $this;
    }

    /**
     * Ajoute une colonne nommée `slug` à la table et crée un dex unique dessus
     *
     * @param bool|null $withUniqIndex L'index n'est pas créé si `false`
     *
     * @return $this
     */
    public function addSlug($withUniqIndex = true)
    {
        $this->addColumn('slug', 'string', ['comment' => 'Slug']);
        if ($withUniqIndex) {
            $this->addIndex(['slug'], [
                'unique' => true,
                'name' => 'idx_' . $this->getName() . '_uniq_slug'
            ]);
        }
        return $this;
    }
}
