<?php
namespace Tests\Rcnchris\Core\ORM\Model;

use Rcnchris\Core\ORM\Model;

class PostsModel extends Model
{
    /**
     * Initialise le model
     */
    protected function initialize()
    {
        $this->setTable('posts');
        $this->setEntity(PostEntity::class);
        $this->belongsTo(CategoriesModel::class);
    }
}