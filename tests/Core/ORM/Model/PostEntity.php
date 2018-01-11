<?php
namespace Tests\Rcnchris\Core\ORM\Model;

use Rcnchris\Core\ORM\Entity;

class PostEntity extends Entity
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $categoryId;
}