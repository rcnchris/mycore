<?php
/**
 * Fichier Cdn.php du 19/07/2018
 * Description : Fichier de la classe Cdn
 *
 * PHP version 5
 *
 * @category CDN
 *
 * @package  Rcnchris\Core\Html
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Html;

use Rcnchris\Core\Tools\Items;

/**
 * Class Cdn
 *
 * @category CDN
 *
 * @package  Rcnchris\Core\Html
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Cdn extends Html
{
    /**
     * Liste des CDN
     *
     * @var \Rcnchris\Core\Tools\Items
     */
    private $items;

    public function __construct($items)
    {
        $this->items = new Items($items);
    }
}
