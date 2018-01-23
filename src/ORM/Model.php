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
     * Nom de la table avec son alias
     * ['alias' => 'nomTable']
     *
     * @var array
     */
    protected $table = [];

    /**
     * Classe à utiliser pour représenter un enregistrement
     *
     * @var string
     */
    protected $entity = \stdClass::class;

    /**
     * Liste des relations du model
     *
     * @var Relation[]
     */
    protected $relations = [];

    /**
     * Constructeur
     *
     * ### Exemple
     * - `$posts = new PostModel($pdo);`
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
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
     * - `$model->makeQuery();`
     *
     * @return Query
     */
    public function makeQuery()
    {
        return (new Query($this->getPdo()))
            ->from($this->getTable(true))
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
        $list = [];
        if (is_null($field)) {
            $field = 'title';
        }
        $results = $this->makeQuery()
            ->select('id', $field)
            ->where($conditions)
            ->all()
            ->toArray();
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
     * - `$posts->exists(12);`
     *
     * @param $id
     *
     * @return bool
     */
    public function exists($id)
    {
        $stmt = $this->pdo->prepare("select id from {$this->getTable()} where id = ?");
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
        $stmt = $this->pdo->prepare("insert into {$this->getTable()} ($fields) values ($values)");
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
        $stmt = $this->pdo->prepare("UPDATE " . $this->getTable() . " SET $fieldQuery WHERE id = :id");
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
        $stmt = $this->pdo->prepare("delete from {$this->getTable()} where id = ?");
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
     * - `$posts->getTable(true);`
     *
     * @param bool|null $withAlias Ajout de la mention 'as alias' après le nom de la table
     *
     * @return string
     */
    public function getTable($withAlias = false)
    {
        $tableName = current($this->table);
        if ($withAlias) {
            return $tableName . ' as ' . array_search($tableName, $this->table);
        }
        return $tableName;
    }

    /**
     * Obtenir l'alias utilisé dans les requêtes
     *
     * ### Exemple
     * - `$posts->getAlias();`
     *
     * @return string
     */
    public function getAlias()
    {
        $parts = explode(' ', $this->getTable(true));
        return array_pop($parts);
    }

    /**
     * Définit le nom de la table du model
     *
     * ### Exemple
     * - `$posts->setTable('posts');`
     *
     * @param string      $table Nom de la table du model
     * @param string|null $alias Alias de la table dans les requêtes
     */
    protected function setTable($table, $alias = null)
    {
        if (is_null($alias)) {
            $alias = $table[0];
        }
        $this->table = [$alias => $table];
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
     * @param string $tableName Nom de la table
     * @param array  $options   Options de la relation
     */
    protected function belongsTo($tableName, array $options = [])
    {
        $options = array_merge(['type' => __FUNCTION__], $options);
        $this->addRelation(strtolower($tableName), $options);
    }

    /**
     * Ajoute une relation hasMany (a plusieurs)
     *
     * ### Exemple
     * - `$this->hasMany('tags', [
     *       'join' => 'left'
     *      , 'joinTable' => 'images_tags'
     *      , 'conditions' => ''
     *    ]);`
     *
     * @param string $tableName Nom de la table
     * @param array  $options   Options de la relation
     *
     * @return Relation
     */
    protected function hasMany($tableName, array $options = [])
    {
        $options = array_merge(['type' => __FUNCTION__], $options);
        $this->addRelation($tableName, $options);
    }

    /**
     * Ajoute une relation à la liste des relations
     *
     * ### Exemple
     * - `$model->addRelation('tags', );`
     *
     * @param       $tableName
     * @param array $options
     */
    private function addRelation($tableName, array $options = [])
    {
        $this->relations[] = new Relation($tableName, $options);
    }

    /**
     * Liste des relations du model
     *
     * ### Exemple
     * - `$posts->getRelations();`
     *
     * @return Relation[]
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