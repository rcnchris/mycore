<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\Query;
use Rcnchris\Core\ORM\QueryResult;
use Tests\Rcnchris\BaseTestCase;

class QueryResultTestCase extends BaseTestCase {

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

    public function testInstance()
    {
        $this->ekoTitre('ORM - QueryResult');
        $this->assertInstanceOf(QueryResult::class, $this->postsQuery->all());
    }

    public function testGet()
    {
        $item = $this->postsQuery->all()->get(1);
        $this->assertNotEmpty($item);
    }

    public function testGetEntity()
    {
        $item = $this->makeQuery()->from('posts')->into(\stdClass::class)->all()->get(1);
        $this->assertInstanceOf(\stdClass::class, $item);
    }

    public function testToArray()
    {
        $array = $this->makeQuery()->from('posts')->all()->toArray();
        $this->assertInternalType('array', $array);
    }

    public function testToArrayWithEntity()
    {
        $array = $this->makeQuery()->from('posts')->into(\stdClass::class)->all()->toArray(true);
        $this->assertInternalType('array', $array);
    }

    public function testGetKey()
    {
        $key = $this->makeQuery()->from('posts')->all()->key();
        $this->assertEquals(0, $key);
    }

    public function testOffSetExists()
    {
        $result = $this->makeQuery()->from('posts')->all();
        $this->assertTrue(isset($result[0]['title']));
    }

    public function testOffsetSet()
    {
        $r = $this->postsQuery->all();
        $this->expectException(\Exception::class);
        $r[0] = [
            'id' => 21,
            'title' => 'Offset set',
            'category_id' => 1,
            'created' => $this->faker()->date('Y-m-d H:i:s'),
            'modified'  => $this->faker()->date('Y-m-d H:i:s')
        ];
    }

    public function testOffsetUnset()
    {
        $r = $this->postsQuery->all();
        $this->expectException(\Exception::class);
        unset($r[0]);
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
}
