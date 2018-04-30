<?php
namespace Tests\Rcnchris\Core\ORM;

use Iterator;
use Rcnchris\Core\ORM\NoRecordException;
use Rcnchris\Core\ORM\Query;

class QueryTest extends OrmTestCase
{

    public function testInstance()
    {
        $this->ekoTitre('ORM - Query');
        $this->assertInstanceOf(Query::class, $this->getQuery());
    }

    public function testOrmTestCaseSeeds()
    {
        $this->seedsCategories();
        $q = $this->getDbTests()->query('select * from categories');
        $q->execute();
        $this->assertNotEmpty($q->fetchAll());
    }

    public function testGetPDO()
    {
        $this->assertInstanceOf(\PDO::class, $this->getQuery()->getPdo());
    }

    public function testFromReturnInstance()
    {
        $q = $this->getQuery();
        $tableName = 'categories';
        $this->assertInstanceOf(Query::class, $q->from($tableName));
        $this->assertEquals(['categories'], $q->tables());
    }

    public function testFromWithoutAlias()
    {
        $q = $this->getQuery();
        $tableName = 'categories';
        $this->assertInstanceOf(Query::class, $q->from($tableName));
        $this->assertEquals(['categories'], $q->tables());
    }

    public function testFromWithAlias()
    {
        $q = $this->getQuery();
        $tableName = 'categories';
        $this->assertInstanceOf(Query::class, $q->from($tableName, 'cat')->from('posts', 'p'));
        $this->assertEquals(
            [
                'categories' => 'cat',
                'posts' => 'p'
            ],
            $q->tables()
        );
    }

    public function testToString()
    {
        $q = $this->getQuery()->from('categories');
        $this->assertEquals("SELECT categories.* FROM categories", (string)$q);
    }

    public function testSelectAllWithAlias()
    {
        $q = $this->getQuery()->from('categories', 'cat');
        $this->assertEquals("SELECT cat.* FROM categories as cat", (string)$q);
    }

    public function testSelectAllWithNtables()
    {
        $q = $this->getQuery()
            ->from('categories', 'cat')
            ->from('posts', 'p');

        $this->assertEquals(
            "SELECT cat.*, p.* FROM categories as cat, posts as p",
            (string)$q
        );
    }

    public function testSelect()
    {
        $q = $this->getQuery()
            ->from('categories', 'cat')
            ->select('cat.id, cat.title');

        $this->assertEquals(
            "SELECT cat.id, cat.title FROM categories as cat",
            (string)$q
        );
    }

    public function testLimit()
    {
        $q = $this->getQuery()
            ->from('categories', 'cat')
            ->limit(1);

        $this->assertEquals(
            "SELECT cat.* FROM categories as cat LIMIT 1",
            (string)$q
        );
    }

    public function testLimitWithOffset()
    {
        $q = $this->getQuery()
            ->from('categories', 'cat')
            ->limit(1, 1);

        $this->assertEquals(
            "SELECT cat.* FROM categories as cat LIMIT 1, 1",
            (string)$q
        );
    }

    public function testOrder()
    {
        $q = $this->getQuery()
            ->from('categories', 'cat')
            ->order('title');

        $this->assertEquals(
            "SELECT cat.* FROM categories as cat ORDER BY title",
            (string)$q
        );
    }

    public function testCount()
    {
        $this->seedsCategories();
        $this->assertEquals(
            2,
            $this->getQuery()->from('categories')->count()
        );
    }

    public function testWhere()
    {
        $this->assertEquals(
            "SELECT categories.* FROM categories WHERE (id = 2)",
            (string)$this->getQuery()->from('categories')->where('id = 2')
        );
    }

    public function testWithParams()
    {
        $this->seedsCategories();
        $query = $this->getQuery()->from('categories')->where('id = :id')->params(['id' => 2]);
        $this->assertEquals(2, $query->fetch()['id']);
    }

    public function testAll()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->all();
        $this->assertEquals(
            [
                ['id' => 1, 'title' => 'Article'],
                ['id' => 2, 'title' => 'Page']
            ],
            $result->toArray()
        );
    }

    public function testGetIterator()
    {
        $this->seedsCategories();
        $this->assertInstanceOf(Iterator::class, $this->getQuery()->from('categories')->getIterator());
    }

    public function testFetch()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->fetch();
        $this->assertInternalType('array', $result);
    }

    public function testFetchWithNoRecord()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->where('id = 12')->fetch();
        $this->assertFalse($result);
    }

    public function testFetchWithEntity()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->into(\stdClass::class)->fetch();
        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testFetchOrFail()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->fetchOrFail();
        $this->assertInternalType('array', $result);
    }

    public function testFetchOrFailWithNoRecordFound()
    {
        $this->seedsCategories();
        $this->expectException(NoRecordException::class);
        $this->getQuery()
            ->from('categories')
            ->where('id = 12')
            ->fetchOrFail();
    }

    public function testJoin()
    {
        $q = $this->getQuery()
            ->from('categories')
            ->join('posts', 'cat.id = p.category_id');
        $this->assertEquals(
            "SELECT categories.* FROM categories LEFT JOIN posts ON cat.id = p.category_id",
            (string)$q
        );
    }
}
