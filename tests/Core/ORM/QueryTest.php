<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\NoRecordException;
use Rcnchris\Core\ORM\Query;
use Rcnchris\Core\ORM\QueryResult;
use Rcnchris\Core\Tools\Collection;
use Tests\Rcnchris\BaseTestCase;

class QueryTestCase extends BaseTestCase {

    /**
     * Requête de base sur les posts
     *
     * @var Query
     */
    private $postsQuery;

    public function setUp()
    {
        parent::setUp();
        $this->postsQuery = $this->makeQuery()->from('posts');
    }

    /**
     * @param \PDO $db
     *
     * @return \Rcnchris\Core\ORM\Query
     */
    public function makeQuery(\PDO $db = null)
    {
        return new Query(is_null($db) ? $this->db : $db);
    }

    public function testInstance()
    {
        $this->ekoTitre('ORM - Query');
        $this->assertInstanceOf(Query::class, $this->postsQuery);
    }

    public function testGetPDO()
    {
        $this->assertInstanceOf(\PDO::class, $this->postsQuery->getPdo());
    }

    public function testFrom()
    {
        $this->assertEquals('SELECT * FROM posts', $this->postsQuery->__toString());
    }

    public function testFromWithAlias()
    {
        $query = $this->makeQuery()->from('posts', 'p');
        $this->assertEquals('SELECT * FROM posts as p', $query->__toString());
    }

    public function testToString()
    {
        $this->assertEquals('SELECT * FROM posts', (string)$this->postsQuery);
    }

    public function testSelect()
    {
        $query = $this->postsQuery;
        $query = $query->select('id', 'title', 'created');
        $this->assertEquals('SELECT id, title, created FROM posts', $query->__toString());
    }

    public function testSelectWithAlias()
    {
        $query = $this->makeQuery()->from('posts', 'p')->select('id', 'title', 'created');
        $this->assertEquals('SELECT id, title, created FROM posts as p', $query->__toString());
    }

    public function testLimit()
    {
        $query = $this->postsQuery;
        $query = $query->limit(5);
        $this->assertEquals('SELECT * FROM posts LIMIT 5, 0', $query->__toString());
    }

    public function testOrder()
    {
        $query = $this->postsQuery;
        $query = $query->order('title');
        $this->assertEquals('SELECT * FROM posts ORDER BY title', $query->__toString());
    }

    public function testCount()
    {
        $this->assertEquals(20, $this->postsQuery->count());
    }

    public function testWhere()
    {
        $query = $query = $this->postsQuery;
        $query = $query->where('id = 1');
        $this->assertEquals('SELECT * FROM posts WHERE (id = 1)', $query->__toString());
    }

    public function testFetch()
    {
        $query = $this->postsQuery;
        $result = $query->where('id = 1')->fetch();
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }

    public function testFetchOrFail()
    {
        $query = $this->postsQuery;
        $result = $query->where('id = 1')->fetchOrFail();
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }

    public function testFetchOrFailWithFail()
    {
        $this->expectException(NoRecordException::class);
        $this->postsQuery
            ->where('id = 10000')
            ->fetchOrFail();
    }

    public function testWithParams()
    {
        $query = $this->postsQuery;
        $query = $query
            ->where('id = :id')
            ->params(['id' => 1]);
        $this->assertEquals('SELECT * FROM posts WHERE (id = :id)', $query->__toString());
        $this->assertInternalType('array', $query->fetch());
    }

    public function testInto()
    {
        $query = $this->postsQuery;
        $query = $query->into(\stdClass::class);
        $this->assertInstanceOf(\stdClass::class, $query->fetch());
        $this->assertInstanceOf(\stdClass::class, $query->fetchOrFail());
    }

    public function testIntoCollection()
    {
        $query = $this->postsQuery;
        $query = $query->into(Collection::class);
        $this->assertInstanceOf(Collection::class, $query->fetch());
        $this->assertInstanceOf(Collection::class, $query->fetchOrFail());
    }

    public function testGetAll()
    {
        $this->assertInstanceOf(QueryResult::class, $this->postsQuery->all());
    }

    public function testGetIterator()
    {
        $items = [];
        foreach ($this->postsQuery as $item) {
            $items[$item['id']] = $item['title'];
        }
        $this->assertCount(20, $items);
    }

    public function testJoinTable()
    {
        $query = $this->postsQuery;
        $query = $query
            ->select('posts.title, categories.title')
            ->join('categories', 'categories.id = posts.category_id');

        $this->assertEquals(
            "SELECT posts.title, categories.title FROM posts LEFT JOIN categories ON categories.id = posts.category_id"
            , $query->__toString()
        );
    }
}
