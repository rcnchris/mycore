<?php
/**
 * Fichier Model.php du 09/01/2018
 * Description : Fichier de la classe Model
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
 * Class Model<br/>
 * <ul>
 * <li>Représente un modèle de données qui s'appuie sur une connexion PDO</li>
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
class Model
{
    /**
     * Instance de PDO
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Nom de la table
     *
     * @var string
     */
    protected $table;

    /**
     * Classe à utiliser pour représenter un enregistrement
     *
     * @var string
     */
    protected $entity = \stdClass::class;

    /**
     * Liste des relations du model
     *
     * @var array
     */
    protected $relations = [];

    /**
     * Constructeur
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->initialize();
    }

    /**
     * Initialise le model
     */
    protected function initialize()
    {
        // Méthode utilisée par les modèles
    }

    /**
     * Obtenir tous les enregistrements
     *
     * ### Exemple
     * - `$model->findAll()`
     *
     * @return Query
     */
    public function findAll()
    {
        return $this->makeQuery();
    }

    /**
     * Obtenir l'instance d'une Query
     *
     * ### Exemple
     * - `$model->makeQuery()`
     *
     * @return Query
     */
    public function makeQuery()
    {
        return (new Query($this->getPdo()))
            ->from($this->table, $this->table[0])
            ->into($this->entity);
    }

    /**
     * Récupère un enregistrement par rapport à un champ
     *
     * ### Exemple
     * - `$model->findBy('title', 'Le premier')`
     *
     * @param string $field
     * @param string $value
     *
     * @return mixed
     * @throws NoRecordException
     */
    public function findBy($field, $value)
    {
        return $this->makeQuery()
            ->where("$field = :field")
            ->params(['field' => $value])
            ->fetch();
    }

    /**
     * Récupère une liste clé/valeurs
     *
     * ### Exemple
     * - `$model->findList('title')`
     * - `$model->findList('title', 'id')`
     *
     * @param string|null $field Nom du champ à retourner indicé par le champ ID
     * @param null        $conditions
     *
     * @return array
     */
    public function findList($field = null, $conditions = null)
    {
        if (is_null($field)) {
            $field = 'title';
        }
        $results = $this->makeQuery()->select('id', $field)->where($conditions)->all()->toArray();
        $list = [];
        foreach ($results as $result) {
            $list[$result['id']] = $result[$field];
        }
        return $list;
    }

    /**
     * Récupère un item à partir de son id
     *
     * ### Exemple
     * - `$posts->find(12)`
     * - `$posts->find(12, ['categories' => 'categories.id = p.id'])`
     *
     * @param int        $id
     * @param array|null $joins
     *
     * @return mixed
     * @throws \Rcnchris\Core\ORM\NoRecordException
     */
    public function find($id, $joins = [])
    {
        $id = intval($id);
        $query = $this->makeQuery();
        if (!empty($joins)) {
            foreach ($joins as $table => $condition) {
                $query = $query->join($table, $condition);
            }
            $t = $this->getTable();
            $query->where($t[0] . ".id = $id");
        } else {
            $query->where("id = $id");
        }
        return $query->fetchOrFail();
    }

    /**
     * Obtenir une nouvelle entité
     *
     * ### Exemple
     * - `$posts->getNewEntity()`
     *
     * @return array
     */
    public function getNewEntity()
    {
        return [];
    }

    /**
     * Récupère le nombre d'enregistrements
     *
     * ### Exemple
     * - `$posts->count()`
     *
     * @return int
     */
    public function count()
    {
        return $this->makeQuery()->count();
    }

    /**
     * Obtenir le nom de la classe d'une entité
     *
     * ### Exemple
     * - `$posts->getEntity()`
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Définit la classe de l'entité
     *
     * ### Exemple
     * - `$posts->setEntity(Collection::class)`
     *
     * @param string $entity Nom de la classe à utiliser pour une entité
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Vérifier la présence d'un id dans la table
     *
     * ### Exemple
     * - `$posts->exists(12)`
     *
     * @param $id
     *
     * @return bool
     */
    public function exists($id)
    {
        $stmt = $this->pdo->prepare("select id from {$this->table} where id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * CRUD
     */

    /**
     * Crée un enregistrement
     *
     * ### Exemple
     * - `$posts->insert(['title' => 'Nouveau', 'category_id' => 3]);`
     *
     * @param array $params
     *
     * @return bool
     */
    public function insert(array $params)
    {
        $fields = array_keys($params);
        $values = join(', ', array_map(function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = join(', ', $fields);
        $stmt = $this->pdo->prepare("insert into {$this->table} ($fields) values ($values)");
        return $stmt->execute($params);
    }

    /**
     * Met à jour les champs d'un enregistrement
     *
     * ### Exemple
     * - `$posts->update(12, ['title' => 'Nouveau titre']);`
     *
     * @param int   $id
     * @param array $params
     *
     * @return bool
     */
    public function update($id, array $params)
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $params['id'] = $id;
        $stmt = $this->pdo->prepare("UPDATE " . $this->table . " SET $fieldQuery WHERE id = :id");
        return $stmt->execute($params);
    }

    /**
     * Supprime un enregistrement
     *
     * ### Exemple
     * - `$posts->delete(12);`
     *
     * @param int $id Identifiant
     *
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("delete from {$this->table} where id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * FIN CRUD
     */

    /**
     * Obtenir le nom de la table
     *
     * ### Exemple
     * - `$posts->getTable();`
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Définit le nom de la table du model
     *
     * ### Exemple
     * - `$posts->setTable('posts');`
     *
     * @param string $table Nom de la table du model
     */
    protected function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Obtenir l'instance de PDO
     *
     * ### Exemple
     * - `$posts->getPdo();`
     *
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Exécute une commande SQL
     * et retourne le résultat sous forme d'instance de QueryResult
     *
     * ### Exemple
     * - `$posts->query('SELECT * FROM categories');`
     * - `$posts->query('SELECT * FROM categories WHERE id = :id', ['id' => 3]);`
     *
     * @param string     $sql    Ordre SQL
     * @param array|null $params Paramètres de l'ordre SQL
     *
     * @return QueryResult
     */
    public function query($sql, array $params = [])
    {
        $items = null;
        if (empty($params)) {
            $stmt = $this->getPdo()->query($sql);
        } else {
            $stmt = $this->getPdo()->prepare($sql);
            $stmt->execute($params);
        }
        return new QueryResult($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Obtenir le nom court du model
     *
     * ### Exemple
     * - `$posts->getName();`
     *
     * @return string
     */
    public function getName()
    {
        $parts = explode('\\', get_class($this));
        $name = str_replace('Model', '', end($parts));
        return $name;
    }

    /**
     * Obtenir le dernier id inséré
     *
     * ### Exemple
     * - `$posts->lastInsertId();`
     *
     * @return string
     */
    public function lastInsertId()
    {
        return $this->getPdo()->lastInsertId();
    }

    /**
     * Génère la liste des champs et la valeur
     *
     * ### Exemple
     * - `$posts->buildFieldQuery(['id' => 12]);`
     *
     * @param array $params
     *
     * @return string
     */
    private function buildFieldQuery(array $params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }

    /**
     * Ajoute une relation belongsTo (appartient à)
     *
     * @param mixed $model
     * @param array $options
     */
    protected function belongsTo($model, array $options = [])
    {
        $this->addRelation(__FUNCTION__, $model, $options);
    }

    /**
     * Ajoute une relation hasMany (a plusieurs)
     *
     * @param mixed $model
     * @param array $options
     *
     * @return Relation
     */
    protected function hasMany($model, array $options = [])
    {
        $this->addRelation(__FUNCTION__, $model, $options);
    }

    /**
     * Ajoute une relation à la liste des relations
     *
     * @param string        $type    Type de la relation (belongsTo, hasMany...)
     * @param string|object $model   Model de données à relier
     * @param array|null    $options Options de la relation
     */
    private function addRelation($type, $model, array $options = [])
    {
        $this->relations[$type][] = new Relation($this, $model, $options);
    }

    /**
     * Liste des relations du model
     *
     * ### Exemple
     * - `$posts->getRelations();`
     *
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * Liste des propriétés de l'entité
     *
     * ### Exemple
     * - `$posts->getProperties();`
     *
     * @return array
     */
    public function getProperties()
    {
        return array_keys(get_class_vars($this->entity));
    }
}
