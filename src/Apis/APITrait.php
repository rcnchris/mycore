<?php
/**
 * Fichier APITrait.php du 26/12/2017
 * Description : Fichier du trait APITrait
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

use Faker\Factory;
use Faker\Generator;

/**
 * Trait APITrait<br/>
 * <ul>
 * <li>Comportements communs à toutes API au travers de Curl</li>
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
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
trait APITrait
{

    /**
     * Ressource Curl
     *
     * @var resource
     */
    private $curl;

    /**
     * Requêtes de l'URL (après le ?)
     *
     * @var array
     */
    protected $params = [];

    /**
     * Générateur aléatoire
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Journal des requêtes exécutées
     *
     * @var array
     */
    private $log = [];

    /**
     * Initialise la ressource Curl à partir d'une URL
     *
     * @param string|null $url URL
     *
     * @return $this
     */
    protected function initialize($url = null)
    {
        if (is_null($this->curl)) {
            $this->curl = curl_init($url);
        }
        return $this;
    }

    /**
     * Obtenir la ressource Curl
     *
     * @return resource
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * Définir les options de Curl avec un tableau
     *
     * @param array $options
     *
     * @return $this
     */
    public function setCurlOptions(array $options)
    {
        curl_setopt_array($this->curl, $options);
        return $this;
    }

    /**
     * Définir l'URL de CURL
     *
     * @param string $url URL à définir dans CURL
     *
     * @return $this
     */
    public function setCurlUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, (string)$url);
        return $this;
    }

    /**
     * Définir le navigateur à utiliser par l'API
     *
     * @param string $nav Nom complet du navigateur
     *
     * @return $this
     */
    protected function setCurlNav($nav)
    {
        curl_setopt($this->curl, CURLOPT_USERAGENT, (string)$nav);
        return $this;
    }

    /**
     * Obtenir les options de Curl ou l'une d'entre elles
     *
     * @param string|null $key Nom de l'option
     *
     * @return mixed
     */
    public function getCurlInfos($key = null)
    {
        $infos = curl_getinfo($this->getCurl());
        return $key
            ? $infos[$key]
            : $infos;
    }

    /**
     * Ajoute une ou plusieurs requêtes à la liste des requêtes
     *
     * @param string|array $params Nom du paramètre ou tableau de paramètres
     * @param mixed|null   $value  Valeur de
     *                             l                                                                                                                           a
     *                             requête si $query est une chaîne
     * @param bool|null    $erase  Efface les requêtes existantes le cas échéant
     *
     * @return $this
     */
    public function addParams($params, $value = null, $erase = false)
    {
        if ($erase) {
            $this->params = [];
        }
        if (is_string($params)) {
            $this->params[$params] = $value;
        } elseif (is_array($params)) {
            $this->params = !$erase
                ? array_merge($this->params, $params)
                : $params;
        }
        return $this;
    }

    /**
     * Obtenir la liste des paramètres de l'URL
     *
     * @param bool|null $build Si vrai, retourne une chaîne de caractères formée pour l'URL (key1=value&key2=value...)
     *
     * @return array|null|string
     */
    public function getParams($build = true)
    {
        if ($build && count($this->params) > 0) {
            return '?' . http_build_query($this->params);
        } elseif (!$build && count($this->params) > 0) {
            return $this->params;
        }
        return null;
    }

    /**
     * Ajoute des éléments à l'URL
     *
     * @param string $parts Parties à ajouter à l'URL
     *
     * @return $this
     */
    public function addUrlPart($parts)
    {
        trim($parts, '/');
        $this->setCurlUrl($this->url(false) . '/' . $parts);
        return $this;
    }

    /**
     * Effectuer une requête sur une API
     *
     * @param string|array|null $params Paramètres de la requête
     *                                  sous forme de tableau ou URL à exécuter
     * @param string|null $logTitle Titre de la requête dans le journal, URL si vide
     *
     * @return \Rcnchris\Core\Apis\CurlResponse
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function r($params = null, $logTitle = null)
    {
        // Doit exécuter une requête sur l'api via CURL
        // Si $params est une chaine de caractère, la considérer comme l'url à exécuter telle quelle
        // Si $params est un tableau, ce sont les paramètres à concaténer à l'URL de l'API
        $url = null;
        if (is_null($params)) {
            $url = $this->url();
        } elseif (is_string($params)) {
            $url = $params;
        } elseif (is_array($params) && !empty($params)) {
            $this->addParams($params);
            $url = $this->url();
        }
        if (is_null($url) || $url === '' || !is_string($url)) {
            throw new ApiException("Aucune URL à exécuter !");
        }
        $this->setCurlUrl($url);
        $response = curl_exec($this->curl);
        $this->addLog($logTitle);
        return new CurlResponse($this->curl, $response);
    }

    /**
     * Obtenir l'URL de l'API
     *
     * @param bool|null $full Si faux, les paramètres ne font pas partie de l'URL retournée
     *
     * @return mixed|string
     */
    public function url($full = true)
    {
        $url = $this->getCurlInfos('url');
        return $full
            ? $url . $this->getParams()
            : $url;
    }

    /**
     * Utiliser un navigateur particulier
     *
     * @param string|null $userAgent Navigateur, si vide navigaetur aléatoire
     *
     * @return $this
     */
    public function withUserAgent($userAgent = null)
    {
        $this->faker = Factory::create();
        if (is_null($userAgent)) {
            $userAgent = $this->faker->userAgent;
        }
        curl_setopt($this->curl, CURLOPT_USERAGENT, $userAgent);
        return $this;
    }

    /**
     * Obtenir la liste des requêtes exécutées
     *
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Ajoute une requête au journal
     *
     * @param string|null $title Titre de la requête dans le journal
     */
    private function addLog($title = null)
    {
        if (is_null($title)) {
            $title = $this->getCurlInfos('url');
        }
        array_push($this->log, [
            'class' => get_class($this)
            , 'title' => $title
            , 'details' => $this->getCurlInfos()
        ]);
    }

    /**
     * Fermer la ressource curl
     *
     * @return void
     */
    protected function close()
    {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * Libère la mémoire
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->log);
        unset($this->params);
        unset($this->faker);
        $this->close();
    }
}
