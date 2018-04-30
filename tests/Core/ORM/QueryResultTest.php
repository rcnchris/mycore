<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\QueryResult;

class QueryResultTest extends OrmTestCase
{

    public function testInstance()
    {
        parent::testInstance();
        $this->seedsCategories();
        $this->assertInstanceOf(QueryResult::class, $this->getQuery()->from('categories')->all());
    }

    public function testGet()
    {
        $this->seedsCategories();
        $r = $this->getQuery()->from('categories')->all();
        $this->assertInternalType('array', $r->get(1));
    }

    public function testGetWithEntity()
    {
        $this->seedsCategories();
        $r = $this->getQuery()->from('categories')->into(\stdClass::class)->all();
        $this->assertInstanceOf(\stdClass::class, $r->get(1));
    }

    public function testToArray()
    {
        $this->seedsCategories();
        $r = $this->getQuery()
            ->from('categories')
            ->all()
            ->toArray();
        $this->assertInternalType('array', $r);
        $this->assertInternalType('array', $r[0]);
    }

    public function testToArrayWithEntity()
    {
        $this->seedsCategories();
        $r = $this->getQuery()
            ->from('categories')
            ->into(\stdClass::class)
            ->all()
            ->toArray(true);
        $this->assertInternalType('array', $r);
        $this->assertInstanceOf(\stdClass::class, $r[0]);
    }

    public function testCurrent()
    {
        $this->seedsCategories();
        $r = $this->getQuery()->from('categories')->all();
        $this->assertInternalType('array', $r->current());
    }

    public function testNext()
    {
        $this->seedsCategories();
        $r = $this->getQuery()->from('categories')->all();
        $r->next();
        $this->assertEquals(2, $r->toArray()[1]['id']);
    }

    public function testKey()
    {
        $this->seedsCategories();
        $r = $this->getQuery()->from('categories')->all();
        $this->assertEquals(0, $r->key());
    }

    public function testValid()
    {
        $this->seedsCategories();
        $r = $this->getQuery()->from('categories')->all();
        $this->assertTrue($r->valid());
    }

    public function testRewind()
    {
        $this->seedsCategories();
        $r = $this->getQuery()->from('categories')->all();
        $this->assertEquals(['id' => 1, 'title' => 'Article'], $r->current());
        $r->next();
        $this->assertEquals(['id' => 2, 'title' => 'Page'], $r->current());
        $r->rewind();
        $this->assertEquals(['id' => 1, 'title' => 'Article'], $r->current());
    }

    public function testArrayAcess()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->all();
        $this->assertArrayAccess($result, 1, ['id' => 2, 'title' => 'Page'], ['Exists', 'Get']);
    }

    public function testOffsetSet()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->all();
        $this->expectException(\Exception::class);
        $result[3] = ['id' => 3, 'title' => 'About'];
    }

    public function testOffsetUnsetSet()
    {
        $this->seedsCategories();
        $result = $this->getQuery()->from('categories')->all();
        $this->expectException(\Exception::class);
        unset($result[0]);
    }
}
