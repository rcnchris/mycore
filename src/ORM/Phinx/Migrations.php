<?php
/**
 * Fichier Migrations.php du 10/07/2018
 * Description : Fichier de la classe Migrations
 *
 * PHP version 5
 *
 * @category Migration
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

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Table;
use Phinx\Migration\AbstractMigration;

/**
 * Class Migrations
 *
 * @category Migration
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
class Migrations extends AbstractMigration
{
    const LENGTH_TITLE = 70;
    const LENGTH_DESCRIPTION = MysqlAdapter::TEXT_LONG;
    const LENGTH_PHONE = 25;
    const LENGTH_EMAIL = 50;

    const LENGTH_CODEPOSTAL = 10;
    const LENGTH_SIREN = 9;
    const LENGTH_SIRET = 14;

    const MYSQL_DEFINER_USER = 'rcn';
    const MYSQL_DEFINER_SERVER = '%';

    /**
     * Options par défaut d'une table
     *
     * @var array
     */
    protected $defaultTableOptions = [
        'signed' => false,
        'engine' => 'InnoDB',
        'comment' => ''
    ];

    /**
     * Noms et commentaires des colonnes `title` et `slug`
     *
     * @var array
     */
    protected $defaultTitleSlug = [
        'title' => 'Intitulé',
        'slug' => 'Slug'
    ];

    /**
     * Option de la colonne texte `description`
     *
     * @var array
     */
    protected $defaultDescription = [
        'field' => "description",
        'comment' => "Description",
        'null' => true,
        'limit' => MysqlAdapter::TEXT_LONG
    ];

    /**
     * Noms et commentaires des colonnes datetime
     *
     * @var array
     */
    protected $defaultDatetimeFields = [
        'created_at' => 'Création',
        'updated_at' => 'Modification',
        'start_at' => 'Début',
        'end_at' => 'Fin'
    ];

    /**
     * Instancie une nouvelle table avec les options par défaut
     *
     * @param string      $tableName    Nom de la table dans la base de données
     * @param string|null $tableComment Commentaire de la table
     * @param array|null  $options      Options de la table
     *
     * @return \Rcnchris\Core\ORM\Phinx\PhinxTable
     */
    protected function newTable($tableName, $tableComment = '', array $options = [])
    {
        $options['comment'] = $tableComment;
        $options = array_merge($this->defaultTableOptions, $options);
        return new PhinxTable($tableName, $options, $this->getAdapter());
    }

    /**
     * Ajoute les champs `title` et `slug` à la table
     * Ajout un index unique sur la colonne `slug` par défaut
     *
     * @param \Phinx\Db\Table $table             Instance de la table migrée
     * @param array           $fields            Liste des colonnes à créer
     * @param bool|null       $withUniqSlugIndex Un index unique est créé sur la colonne `slug`, si vrai.
     *
     * @return \Phinx\Db\Table
     */
    protected function addTitleSlugFields(Table $table, array $fields = [], $withUniqSlugIndex = true)
    {
        $fields = array_merge($this->defaultTitleSlug, $fields);
        foreach ($fields as $fieldName => $comment) {
            $table->addColumn($fieldName, 'string', ['comment' => $comment, 'limit' => $this::LENGTH_TITLE]);
        }
        if ($withUniqSlugIndex) {
            $table->addIndex(['slug'], [
                'unique' => true,
                'name' => 'idx_' . $table->getName() . '_uniq_slug'
            ]);
        }
        return $table;
    }

    /**
     * Ajoute les champs datime par défaut à la table
     * - `created_at`, `updated`, `start_at`, `end_at`
     *
     * @param \Phinx\Db\Table $table  Instance de la table migrée
     * @param array           $fields Options des colonnes
     *
     * @return \Phinx\Db\Table
     */
    protected function addDateTimeFields(Table $table, array $fields = [])
    {
        $fields = array_merge($this->defaultDatetimeFields, $fields);
        foreach ($fields as $fieldName => $comment) {
            $table->addColumn($fieldName, 'datetime', ['comment' => $comment, 'null' => true]);
        }
        return $table;
    }

    /**
     * Ajoute la colonne `slug` à la table
     *
     * @param \Phinx\Db\Table $table         Instance de la table migrée
     * @param bool            $withUniqIndex Crée un index unique sur la colonne
     *
     * @return \Phinx\Db\Table
     */
    protected function addSlug(Table $table, $withUniqIndex = true)
    {
        $table = $table->addColumn('slug', 'string', ['comment' => 'Slug']);
        if ($withUniqIndex) {
            $table->addIndex(['slug'], [
                'unique' => true,
                'name' => 'idx_' . $table->getName() . '_uniq_slug'
            ]);
        }
        return $table;
    }

    /**
     * Ajoute le champ texte `description` à la table
     *
     * @param \Phinx\Db\Table $table   Instance de la table migrée
     * @param array           $options Options de la colonne `description`
     *
     * @return \Phinx\Db\Table
     */
    protected function addDescription(Table $table, array $options = [])
    {
        $options = array_merge($this->defaultDescription, $options);
        return $table->addColumn(
            $options['field'],
            'text',
            [
                'comment' => $options['comment'],
                'null' => $options['null'],
                'limit' => $options['limit']
            ]
        );
    }

    /**
     * Ajoute les champs `description` et dates à la table
     *
     * @param \Phinx\Db\Table $table
     */
    protected function finalizeTable(Table $table)
    {
        $table->create();
        $this->addDescription($table);
        $this->addDateTimeFields($table);
        $table->save();
    }
}
