<?php
namespace Tests\Rcnchris\Core\ORM\Model;

use Rcnchris\Core\ORM\Model;

class CategoriesModel extends Model
{

    protected function initialize()
    {
        $this->setTable('categories');
        $this->setEntity(CategoryEntity::class);
    }
}
