<?php
namespace Tests\Rcnchris\Core\ORM\Model;

use Rcnchris\Core\ORM\Model;

class PostsModel extends Model
{

    /**
     * Constructeur
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct($pdo);
        $this->setTable('posts');
        $this->setEntity(PostEntity::class);

        $this->belongsTo('categories', [
            'foreignKey' => 'category_id'
            , 'propertyName' => 'category'
        ]);

        $this->hasMany('tags', [
            'foreignKey' => 'post_id'
            , 'propertyName' => 'Tags'
        ]);
    }
}