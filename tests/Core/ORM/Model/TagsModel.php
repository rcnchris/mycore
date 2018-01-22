<?php
/**
 * Fichier TagsModel.php du 17/01/2018 
 * Description : Fichier de la classe TagsModel 
 *
 * PHP version 5
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\ORM\Model
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris GPL
 *
 * @link https://github.com/rcnchris On Github
 */

namespace Tests\Rcnchris\Core\ORM\Model;
use Rcnchris\Core\ORM\Model;

/**
 * Class TagsModel
 *
 * @category New
 *
 * @package Tests\Rcnchris\Core\ORM\Model
 *
 * @author Raoul <rcn.chris@gmail.com>
 *
 * @license https://github.com/rcnchris/fmk-php GPL
 *
 * @version Release: <1.0.0>
 *
 * @link https://github.com/rcnchris/fmk-php on Github
 */
class TagsModel extends Model
{
    protected function initialize()
    {
        $this->setTable('tags');
        $this->setEntity(TagEntity::class);
        $this->belongsTo(TagsModel::class);
    }

}