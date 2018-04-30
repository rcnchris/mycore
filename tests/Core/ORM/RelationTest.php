<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\Relation;

class RelationTest extends OrmTestCase
{
    /**
     * @var PostsModel
     */
    private $posts;

    /**
     * @var CategoriesModel
     */
    private $categories;

    public function setUp()
    {
        parent::setUp();
        $this->seedsCategories();
        $this->seedsPosts();
        $this->categories = $this->getModel('categories');
        $this->posts = $this->getModel('posts');
    }

    public function testInstance()
    {
        parent::testInstance();
        $relations = $this->posts->getRelations();
        $this->assertInstanceOf(Relation::class, current($relations));
    }

    public function testProperties()
    {
        $relation = current($this->posts->getRelations());

        $this->assertObjectHasAttribute('mainTable', $relation);
        $this->assertEquals('posts', $relation->mainTable);

        $this->assertObjectHasAttribute('refTable', $relation);
        $this->assertEquals('categories', $relation->refTable);

        $this->assertObjectHasAttribute('type', $relation);
        $this->assertEquals('belongsTo', $relation->type);

        $this->assertObjectHasAttribute('foreignKey', $relation);
        $this->assertEquals('category_id', $relation->foreignKey);

        $this->assertObjectHasAttribute('propertyName', $relation);
        $this->assertEquals('category', $relation->propertyName);
    }
}
