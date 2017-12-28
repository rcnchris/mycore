<?php
/**
 * Fichier OneAPI.php du 26/12/2017
 * Description : Fichier de la classe OneAPI
 *
 * PHP version 7
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis;

use Rcnchris\Core\Tools\Collection;

/**
 * Class OneAPI<br/>
 * <ul>
 * <li>Représente n'importe quelle API à partir de son URL</li>
 * </ul>
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <0.0.1>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class OneAPI
{

    use APITrait;

    /**
     * Constructeur
     *
     * @param string|null $url URL de base
     */
    public function __construct($url = null)
    {
        $this->initialize($url);
    }
}
