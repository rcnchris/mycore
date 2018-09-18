<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\Model;
use Rcnchris\Core\ORM\Query;
use Rcnchris\Core\ORM\QueryResult;
use Rcnchris\Core\Tools\Collection;
use Rcnchris\Core\Tools\Items;

class ModelTest extends OrmTestCase
{

    /**
     * @var CategoriesModel
     */
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->seedsCategories();
        $this->model = $this->getModel('categories');
    }

    public function testOrmTestCaseGetModel()
    {
        $this->assertInstanceOf(CategoriesModel::class, $this->getModel('categories'));
        $this->assertInstanceOf(PostsModel::class, $this->getModel('posts'));
    }

    public function testInstance()
    {
        parent::testInstance();
        $this->assertInstanceOf(Model::class, $this->model);
    }

    public function testGetPdo()
    {
        $this->assertInstanceOf(\PDO::class, $this->model->getPdo());
    }

    public function testFindAllReturnQuery()
    {
        $this->assertInstanceOf(Query::class, $this->model->findAll());
    }

    public function testFind()
    {
        $this->assertInstanceOf(
            \stdClass::class,
            $this->model->find(1)
        );
    }

    public function testFindWithJoins()
    {
        $this->seedsPosts();
        $this->assertInstanceOf(
            \stdClass::class,
            $this->model->find(1, ['posts' => 'categories.id = posts.category_id'])
        );
    }

    public function testFindByField()
    {
        $this->assertInstanceOf(
            \stdClass::class,
            $this->model->findBy('title', 'Page')
        );
    }

    public function testFindList()
    {
        $this->assertEquals(
            [
                1 => 'Article',
                2 => 'Page'
            ],
            $this->model->findList()
        );
    }

    public function testGetNewEntity()
    {
        $this->assertEquals([], $this->model->getNewEntity());
    }

    public function testGetName()
    {
        $this->assertEquals('Categories', $this->model->getName());
    }

    public function testCount()
    {
        $this->assertEquals(2, $this->model->count());
    }

    public function testGetEntity()
    {
        $this->assertEquals(\stdClass::class, $this->model->getEntity());
    }

    public function testSetEntity()
    {
        $this->model->setEntity(Items::class);
        $this->assertInstanceOf(Items::class, $this->model->findAll()->fetch());
    }

    public function testExists()
    {
        $this->assertTrue($this->model->exists(2));
    }

    public function testLastInsertId()
    {
        $this->assertEquals(2, $this->model->lastInsertId());
    }

    public function testQuery()
    {
        $this->assertInstanceOf(
            QueryResult::class,
            $this->model->query('SELECT * FROM categories')
        );
    }

    public function testQueryWithParams()
    {
        $this->assertInstanceOf(
            QueryResult::class,
            $this->model->query('SELECT * FROM categories WHERE id = :id', ['id' => 2])
        );
    }

    public function testUpdate()
    {
        $this->assertTrue($this->model->update(2, ['title' => 'Pages']));
    }

    public function testDelete()
    {
        $this->assertTrue($this->model->delete(2));
    }

    public function testInsert()
    {
        $this->assertTrue($this->model->insert(['title' => 'New']));
    }

    public function testGetAlias()
    {
        $this->assertEquals('c', $this->model->getAlias());
    }

    public function testGetProperties()
    {
        $this->assertEmpty($this->model->getProperties());
    }

    public function testGetRelations()
    {
        $this->assertNotEmpty($this->model->getRelations());
    }

    public function testWithRelationBelongTo()
    {
        $this->seedsPosts();
        $query = $this->getModel('posts')
            ->withRelation('categories')
            ->select('posts.title as postTitle, categories.title as categoryTitle');
        $this->assertEquals(
            "SELECT posts.title as postTitle, categories.title as categoryTitle FROM posts LEFT JOIN categories ON posts.category_id = categories.id",
            (string)$query
        );
    }

    public function testWithWrongRelationName()
    {
        $this->seedsPosts();
        $this->assertFalse($this->getModel('posts')->withRelation('fake'));
    }

    public function testToArray()
    {
        $this->assertEquals($this->model->findAll()->all()->toArray(), $this->model->toArray());
    }
}
