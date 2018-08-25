<?php
/**
 * Fichier Url.php du 25/08/2018
 * Description : Fichier de la classe Url
 *
 * PHP version 5
 *
 * @category URL
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Tools;

/**
 * Class Url
 *
 * @category URL
 *
 * @package  Rcnchris\Core\Tools
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Url
{
    /**
     * URL en chaîne de caractères
     *
     * @var string
     */
    private $url;

    /**
     * Constructeur
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = (string)$url;
        $parts = parse_url($this->url);
        foreach ($parts as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Obtenir les paramètres sous forme de tableau
     *
     * @return array
     */
    public function params()
    {
        $strParams = explode('&', $this->query);
        $params = [];
        foreach ($strParams as $param) {
            list($key, $value) = explode('=', $param);
            $params[$key] = $value;
        }
        return $params;
    }
}
