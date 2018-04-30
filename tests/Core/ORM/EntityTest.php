<?php
namespace Tests\Rcnchris\Core\ORM;

class EntityTest extends OrmTestCase
{

    /**
     * @var PostsModel
     */
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->seedsPosts();
        $this->model = $this->getModel('posts');
    }

    public function testInstance()
    {
        parent::testInstance();
        $this->assertEquals(PostsEntity::class, $this->model->getEntity());
    }

    public function testSetDates()
    {
        $this->model->insert([
            'title' => 'On vérifie les dates',
            'category_id' => 1
        ]);
        $this->assertEquals('On vérifie les dates', $this->model->find($this->model->lastInsertId())->title);
        $this->assertInstanceOf(\DateTime::class, $this->model->find(21)->created);
    }

    public function testGetPropertiesOfModel()
    {
        $this->assertEquals([
            'title',
            'category_id',
            'id',
            'created',
            'modified'
        ], $this->model->getProperties());
    }
}
