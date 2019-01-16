<?php
/**
 * Fichier Url.php du 14/01/2019
 * Description : Fichier de la classe Url
 *
 * PHP version 5
 *
 * @category New
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

use Rcnchris\Core\Apis\Curl;

/**
 * Class Url
 *
 * @category New
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
     * URL au format texte
     *
     * @var string|null
     */
    private $url;

    /**
     * Instance de Curl
     *
     * @var Curl
     */
    private $curl;

    /**
     * Constructeur
     *
     * @param string|null $url URL au format texte
     */
    public function __construct($url = null)
    {
        $this->set($url);
    }

    /**
     * Obtenir l'URL au format texte
     *
     * @return null|string
     */
    public function __toString()
    {
        return $this->url;
    }

    /**
     * Obtenir une partie de l'URL
     *
     * @param string $key Nom d'une clé retournée par parse_url()
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     */
    public function __get($key)
    {
        return $this->parse()->get($key);
    }

    /**
     * Obtenir la réponse de l'URL dans un objet selon lle content-type
     *
     * @param bool $object Si faux, c'est la réponse brute qui est retournée au format texte
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Image|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function get($object = true)
    {
        return $this->curl()->exec()->getResponse($object);
    }

    /**
     * Définir l'URL
     *
     * @param null|string $url URL au format texte
     */
    public function set($url)
    {
        $this->url = filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Obtenir les parties de l'URL dans une instance de <code>Items</code>
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function parse()
    {
        return new Items(parse_url($this->url));
    }

    /**
     * Obtenir la liste des queries dans une instance de <code>Items</code>
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function queries()
    {
        $query = $this->parse()->get('query');
        $queries = [];
        if ($query) {
            $query = trim($query, '?');
            $items = explode('&', $query);
            foreach ($items as $item) {
                $itemParts = explode('=', $item);
                $queries[$itemParts[0]] = $itemParts[1];
            }
        }
        return new Items($queries);
    }

    /**
     * Obtenir une instance de <code>Curl</code>
     *
     * @return \Rcnchris\Core\Apis\Curl
     */
    private function curl()
    {
        if (is_null($this->curl)) {
            $this->curl = new Curl($this->url);
        }
        return $this->curl;
    }

    /**
     * Obtenir les informations d'exécutions de l'URL
     *
     * @param string|null $key Nom d'une clé
     *
     * @return array|mixed
     */
    public function getInfos($key = null)
    {
        return $this->curl()->exec()->getInfos($key);
    }
}
