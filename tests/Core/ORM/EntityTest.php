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
        $idInsert = $this->model->lastInsertId();
        $this->assertEquals('On vérifie les dates', $this->model->find($idInsert)->title);
        $this->assertInstanceOf(\DateTime::class, $this->model->find($idInsert)->created);
    }

    public function testSetDatesWithDates()
    {
        $date = date('Y-m-d H:i:s');
        $this->model->insert([
            'title' => 'On vérifie les dates',
            'category_id' => 1,
            'created' => $date,
            'modified' => $date,
        ]);
        $idInsert = $this->model->lastInsertId();
        $this->assertEquals('On vérifie les dates', $this->model->find($idInsert)->title);
        $this->assertInstanceOf(\DateTime::class, $this->model->find($idInsert)->created);
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
