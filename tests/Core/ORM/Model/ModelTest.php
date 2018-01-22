<?php
namespace Tests\Rcnchris\Core\ORM\Model;

use Rcnchris\Core\ORM\Query;
use Rcnchris\Core\ORM\QueryResult;
use Tests\Rcnchris\Core\ORM\OrmTestCase;

class ModelTest extends OrmTestCase
{
    /**
     * @var PostsModel
     */
    private $Posts;

    public function setUp()
    {
        parent::setUp();
        $this->Posts = new PostsModel($this->db);
    }

    public function testInstance()
    {
        $this->ekoTitre('ORM - Model');
        $this->assertInstanceOf(PostsModel::class, $this->Posts);
    }

    public function testGetName()
    {
        $this->assertEquals('Posts', $this->Posts->getName());
    }

    public function testGetTable()
    {
        $this->assertEquals('posts', $this->Posts->getTable());
    }

    public function testGetAlias()
    {
        $this->assertEquals('p', $this->Posts->getAlias());
    }

    public function testGetNewEntity()
    {
        $this->assertInternalType('array', $this->Posts->getNewEntity());
    }

    public function testFindAll()
    {
        $this->assertInstanceOf(Query::class, $this->Posts->findAll());
    }

    public function testMakeQuery()
    {
        $this->assertInstanceOf(Query::class, $this->Posts->makeQuery());
    }

    public function testFindBy()
    {
        $this->assertInstanceOf(PostEntity::class, $this->Posts->findBy('id', 1));
    }

    public function testFindList()
    {
        $this->assertInternalType('array', $this->Posts->findList());
        $this->assertInternalType('array', $this->Posts->findList('title'));
        $this->assertInternalType('array', $this->Posts->findList('title', 'id'));
    }

    public function testFind()
    {
        $this->assertInstanceOf(PostEntity::class, $this->Posts->find(1));
    }

    public function testFindWithJoin()
    {
        $find = $this->Posts->find(1, ['categories' => 'categories.id = p.category_id']);
        $this->assertInstanceOf(PostEntity::class, $find);
    }

    public function testCount()
    {
        $this->assertEquals(20, $this->Posts->count());
    }

    public function testSetEntity()
    {
        $this->Posts->setEntity(\stdClass::class);
        $this->assertEquals(\stdClass::class, $this->Posts->getEntity());
        $this->Posts->setEntity(PostEntity::class);
    }

    public function testExists()
    {
        $this->assertTrue($this->Posts->exists(1));
    }

    public function testLastInsertId()
    {
        $this->assertEquals(20, $this->Posts->lastInsertId());
    }

    public function testQuery()
    {
        $items = $this->Posts->query('select * from categories');
        $this->assertInstanceOf(QueryResult::class, $items);
        $this->assertNotEmpty($items->toArray());
    }

    public function testQueryWithParam()
    {
        $item = $this->Posts->query('select * from categories where id = :id', ['id' => 3]);
        $this->assertInstanceOf(QueryResult::class, $item);
    }

    public function testInsert()
    {
        $data = [
            'title' => 'Nouvel article'
            , 'category_id' => 3
            , 'created' => date('Y-m-d H:i:s')
        ];
        $this->Posts->insert($data);
        $this->assertEquals(21, $this->Posts->lastInsertId());
    }

    public function testDelete()
    {
        $this->Posts->delete(21);
        $this->assertEquals(20, $this->Posts->count());
    }

    public function testUpdate()
    {
        $this->Posts->update(20, ['title' => 'Le dernier']);
        $this->assertEquals('Le dernier', $this->Posts->find(20)->title);
    }

    public function testGetProperties()
    {
        $p = $this->Posts->getProperties();
        $this->assertContains('id', $p);
        $this->assertContains('created', $p);
        $this->assertContains('modified', $p);
        $this->assertContains('title', $p);
        $this->assertContains('categoryId', $p);
    }

    public function testGetRelations()
    {
        $r = $this->Posts->getRelations();
        $this->assertNotEmpty($r);
    }
}
