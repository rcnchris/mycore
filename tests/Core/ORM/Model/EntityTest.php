<?php
namespace Tests\Rcnchris\Core\ORM\Model;
use Tests\Rcnchris\BaseTestCase;

class EntityTest extends BaseTestCase
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
        $this->ekoTitre('ORM - Entity');
        $this->assertInstanceOf(PostEntity::class, $this->Posts->find(1));
    }
}