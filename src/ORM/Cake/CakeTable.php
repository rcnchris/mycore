<?php
/**
 * Fichier CakeTable.php du 28/06/2018
 * Description : Fichier de la classe CakeTable
 *
 * PHP version 5
 *
 * @category Table
 *
 * @package  App\ORM\Cake
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM\Cake;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Rcnchris\Core\ORM\Cake\Behaviors\DateAppBehavior;
use Rcnchris\Core\ORM\Cake\Behaviors\SluggableBehavior;

/**
 * Class CakeTable
 *
 * @category Table
 *
 * @package  Rcnchris\Core\ORM\Cake
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class CakeTable extends Table
{
    // phpcs:disable
    /**
     * Options par défaut du behavior `SluggableBehavior`
     *
     * @var array
     */
    protected $_behaviorSlugOptions = [
        'field' => 'title',
        'slug' => 'slug',
        'replacement' => '-'
    ];

    /**
     * Options par défaut de l'arbre intervallaire
     *
     * @var array
     */
    protected $_behaviorTreeOptions = [
        'parent' => 'parent_id',
        'left' => 'lft',
        'right' => 'rght',
        'level' => 'level'
    ];
    // phpcs:enable

    /**
     * Options personnalisées du slug de la Table enfant
     *
     * @var array
     */
    protected $behaviorSlugOptions = [];

    /**
     * Options personnalisées de l'arbre intervallaire
     *
     * @var array
     */
    protected $behaviorTreeOptions = [];

    /**
     * Initialisation par défaut de la table
     *
     * @param array $config Configuration de la table
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        /**
         * Clé primaire de la table
         */
        $this->setPrimaryKey('id');

        /**
         * Champ affiché par défaut dans les findList
         */
        $this->setDisplayField('title');

        /**
         * Behaviors : Tree
         * Si la table contient toutes les colonnes de gestion d'un arbre,
         * telles que définies dans la configuration, le comportement `Tree` est associé à la table
         */
        $options = array_merge($this->_behaviorTreeOptions, $this->behaviorTreeOptions);
        if (!empty(array_intersect($this->getColumns(), array_values($options)))) {
            $this->addBehavior('Tree', $options);
        }

        /**
         * Behaviors : Slug
         * Si la table contient une colonne nommée `slug`
         * le comportement `SluggableBehavior` est associé à la table
         */
        if ($this->hasField('slug')) {
            $options = array_merge($this->_behaviorSlugOptions, $this->behaviorSlugOptions);
            $this->addBehavior(SluggableBehavior::class, $options);
        }

        /**
         * Behaviors : Dates de création et modification
         * Si la table contient les colonnes `created_at` et `updated_at`,
         * le comportement `Timestamp` est associé à la table
         */
        if ($this->hasField('created_at') && $this->hasField('updated_at')) {
            $this->addBehavior('Timestamp', [
                'events' => [
                    'Model.beforeSave' => [
                        'created_at' => 'new',
                        'updated_at' => 'always',
                    ],
                    'tabs.completed' => [
                        'completed_at' => 'always'
                    ]
                ]
            ]);
        }

        /**
         * Behaviors : Dates d'application
         * Si la table contient les colonnes `start_at` et `end_at`,
         * le comportement `DateAppBehavior` est associé à la table
         */
        if ($this->hasField('start_at') && $this->hasField('end_at')) {
            $this->addBehavior(DateAppBehavior::class);
        }
    }

    /**
     * Retourne les données pour les pages d'index
     *
     * @param \Cake\ORM\Query $query
     * @param array           $options
     *
     * @return \Cake\ORM\Query
     */
    public function findIndex(Query $query, array $options)
    {
        return $this->find('all', $options);
    }

    /**
     * Retourne l'entité à afficher à partir de son identifiant sans les associations.
     * Les associations sont à renseigner dans la clé 'contain' des options.
     *
     * @param \Cake\ORM\Query $query
     * @param array           $options
     *
     * @return $this
     */
    public function findShow(Query $query, array $options)
    {
        $id = $options[$this->getPrimaryKey()];
        return $this->get($id, $options);
    }

    /**
     * Liste des colonnes ou définition de l'une d'entre elles
     *
     * @param string|null $name Nom d'une colonne de la table
     *
     * @return array|null
     */
    public function getColumns($name = null)
    {
        return !is_null($name)
            ? $this->getSchema()->getColumn($name)
            : $this->getSchema()->columns();
    }

    /**
     * Liste des index ou définition de l'un d'entre eux
     *
     * @param string|null $name Nom d'un index de la table ou la définition de l'un d'entre eux
     *
     * @return array|null
     */
    public function getIndexes($name = null)
    {
        return !is_null($name)
            ? $this->getSchema()->getIndex($name)
            : $this->getSchema()->indexes();
    }

    /**
     * Liste des contraintes ou définition de l'une d'entre elles
     *
     * @param string|null $name Nom d'un contrainte de la table ou la définition de l'une d'entre elle
     *
     * @return array|null
     */
    public function getConstraints($name = null)
    {
        return !is_null($name)
            ? $this->getSchema()->getConstraint($name)
            : $this->getSchema()->constraints();
    }

    /**
     * Liste des behaviors ou définition de l'un d'entre eux
     *
     * @param string|null $name Nom du `Behavior`
     *
     * @return array|\Cake\ORM\Behavior
     */
    public function getBehaviors($name = null)
    {
        return is_null($name)
            ? $this->behaviors()->loaded()
            : $this->getBehavior($name);
    }

    /**
     * Liste des associations ou définition de l'une d'entre elles
     *
     * @param string|null $name Nom de l'association
     *
     * @return array|\Cake\ORM\Association
     */
    public function getAssociations($name = null)
    {
        return is_null($name)
            ? $this->associations()->keys()
            : $this->getAssociation($name);
    }

    /**
     * Liste des associations pour un type donné
     *
     * @param string $type Nom du type
     *
     * @return array[\Cake\ORM\Association]
     */
    public function getAssociationsBy($type)
    {
        return $this->associations()->getByType($type);
    }

    /**
     * Obtenir le script SQL de création de la table
     *
     * @return string
     */
    public function sqlCreate()
    {
        return current($this->getSchema()->createSql($this->getConnection()));
    }

    /**
     * Obtenir les informations de la table dans un tableau
     *
     * @return array
     */
    public function describe()
    {
        return $this->getConnection()
            ->getSchemaCollection()
            ->describe($this->getTable())
            ->__debugInfo();
    }

    /**
     * Getter : Liste des règles de validation d'un champ
     *
     * @param string $fieldName nom d'une colonne de la table
     *
     * @return array
     */
    public function getFieldRules($fieldName)
    {
        return array_keys($this->getValidator()->field($fieldName)->rules());
    }

    /**
     * Convertit une liste de tags en une chaine de caractères
     *
     * @param \Cake\Event\Event                $event
     * @param \Cake\Datasource\EntityInterface $entity
     * @param \ArrayObject                     $options
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (isset($entity->tag_string)) {
            $entity->Tags = $this->buildTags($entity->tag_string);
        }
    }

    /**
     * Ajoute les tags fournit en liste
     *
     * @param $tagString
     *
     * @return array
     */
    protected function buildTags($tagString)
    {
        // Trim tags
        $newTags = array_map('trim', explode(',', $tagString));
        // Retire tous les tags vides
        $newTags = array_filter($newTags);
        // Réduit les tags dupliqués
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
            ->where(['Tags.title IN' => $newTags]);

        // Retire les tags existants de la liste des tags nouveaux.
        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        // Ajoute les tags existants.
        foreach ($query as $tag) {
            $out[] = $tag;
        }
        // Ajoute les nouveaux tags.
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }
        return $out;
    }

    /**
     * Obtenir le résumé des propriétés
     * Ajoute le commentaire de la table dans les clés
     *
     * @return array
     */
    public function __debugInfo()
    {
        $items = parent::__debugInfo();
        $items['comment'] = $this->getComment();
        return $items;
    }

    /**
     * Obtenir le commentaire MySQL associé à la table courante
     * ou à celle spécifiée en paramètre
     *
     * @param string|null $tableName Nom d'une table
     *
     * @return mixed
     */
    public function getComment($tableName = null)
    {
        if (is_null($tableName)) {
            $tableName = $this->_table;
        }
        $q = $this->_connection->prepare('select * from _commentsTables where tableName = :tableName;');
        $q->execute(['tableName' => $tableName]);
        return $q->fetch(\PDO::FETCH_ASSOC)['comment'];
    }

    /**
     * Obtenir les données des tables associées sous forme de listes
     *
     * @return array
     */
    public function getRelated()
    {
        return [];
    }
}
