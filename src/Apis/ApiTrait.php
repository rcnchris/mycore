<?php
/**
 * Fichier ApiTrait.php du 10/10/2017
 * Description : Fichier de la classe ApiTrait
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis;

use Rcnchris\Core\Exceptions\ApiException;

/**
 * Trait ApiTrait<br/>
 *
 * <ul>
 * <li>Comportements communs à toutes les API</li>
 * </ul>
 *
 * @category API
 *
 * @package  Core\Apis
 *
 * @author   <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
trait ApiTrait
{
    /**
     * URL de base de l'API
     *
     * @var string
     */
    public $baseUrl;

    /**
     * Ressource Curl
     *
     * @var resource
     */
    private $curl;

    /**
     * Options de curl par défaut
     *
     * @var array
     */
    protected $curlOptions = [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 10
    ];

    /**
     * Méthodes de l'API
     *
     * @var array
     */
    protected $methods = [];

    /**
     * Paramètres de l'API
     *
     * @var array
     */
    protected $params = [];

    /**
     * Journal des requêtes exécutées
     *
     * @var array
     */
    private $log = [];

    /**
     * Constructeur
     *
     * Définit l'url de base de l'API
     *
     * @param string $baseUrl URL de base de l'API
     *
     * @throws ApiException
     */
    public function __construct($baseUrl)
    {
        $this->initialize($baseUrl);
    }

    /**
     * Initialise la ressource Curl avec l'URL de base et les options par défaut
     *
     * <code>$api->initialize('https://randomuser.me/api');</code>
     *
     * @param string $baseUrl URL de base
     *
     * @return $this
     * @throws \Rcnchris\Core\Exceptions\ApiException
     */
    protected function initialize($baseUrl)
    {
        if (!function_exists('curl_init')) {
            throw new ApiException("Curl n'est pas installé !", 100);
        }

        // Initialisation de Curl
        $this->curl = curl_init($baseUrl);
        $this->setOptions($this->curlOptions);
        $this->setUrl($baseUrl);
        $this->baseUrl = $this->getUrl();
        return $this;
    }

    /**
     * Ajoute des paramètres à l'URL.
     *
     * <code>$api->addParams(['version' => $version, 'method' => $method]);</code><br/>
     * <code>$api->addParams(['version' => $version, 'method' => $method], true);</code>
     *
     * @param array     $params Paramètres à ajouter
     * @param bool|null $erase  Si vrai, les valeurs existantes sont effacées. Faux par défaut.
     *
     * @return $this
     */
    public function addParams(array $params, $erase = false)
    {
        if ($erase) {
            $this->params = [];
        }
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * Obtenir les paramètres à appliquer à l'URL
     *
     * <code>$array = $api->getParams()</code><br/>
     * <code>$string = $api->getParams(true)</code>
     *
     * @param bool|null $buildQuery Si vrai, une chaîne de caractères de type URL
     *                              avec l'ensemble des paramètres est retournée
     *
     * @return array|string
     */
    public function getParams($buildQuery = false)
    {
        $ret = null;
        $ret = $buildQuery ? http_build_query($this->params) : $this->params;
        return $ret;
    }

    /**
     * Obtenir la ressource Curl
     *
     * <code>$curl = $api->getCurl();</code>
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
    public function setOptions(array $options)
    {
        curl_setopt_array($this->curl, $options);
        return $this;
    }

    /**
     * Retourne une URL formatée avec les paramètres
     *
     * @param array|null $params Paramètres supplémentaires à ajouter à la requête
     *
     * @return string
     */
    public function makeUrl(array $params = [])
    {
        $this->params = array_merge($this->params, $params);
        return $this->getUrl() . '?' . http_build_query($this->params);
    }

    /**
     * Définir l'URL qui sera exécutée
     *
     * @param string $url URL
     *
     * @return $this
     */
    public function setUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        return $this;
    }

    /**
     * Effectuer une requête à partir de l'URL
     * Ou à partir de celle de Curl
     *
     * @param string      $url  URL de la requête API
     * @param string|null $name Nom de la requête
     *
     * @return mixed
     * @throws \Rcnchris\Core\Exceptions\ApiException
     */
    protected function _request($url = null, $name = null)
    {
        // Définition de l'URL à exécuter
        if (is_null($url)) {
            //$url = $this->getUrl() . '?' . $this->getParams(true);
            $url = $this->getUrl();
        }
        if (is_null($url) || $url === '' || !is_string($url)) {
            throw new ApiException("Aucune URL à exécuter !");
        }
        $this->setUrl($url);

        // Exécution de l'URL via Curl
        $response = curl_exec($this->curl);

        // Log de la requête
        $this->log[] = ['name' => $name, 'detail' => $this->getCurlInfos()];

        // Transformation du retour json en tableau
        if ($response && is_string($response)) {
            $newResponse = json_decode($response, true);
            if (!$newResponse) {
                return $response;
            }
            return $newResponse;
        }
        return $response;
    }

    /**
     * Ajouter une partie à l'URL avant les paramètres de celle-ci.
     * Change l'URL stockée dans l'instance de curl mais pas l'URL de base.
     *
     * @exemple $api->addUrlPart(/webapi);
     *
     * @param string|null $part Partir de l'URL à ajouter avant les paramètres
     *
     * @return $this
     */
    public function addUrlPart($part = null)
    {
        if ($part[0] != '/') {
            $part = '/' . $part;
        }
        return $this->setUrl($this->baseUrl . $part);
    }

    /**
     * Obtenir l'URL de la ressouce Curl
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getCurlInfos('url');
    }

    /**
     * Obtenir une information sur Curl
     *
     * @exemple $this->_getInfos('url');
     *
     * @param string|null $key Nom de la clé à retourner
     *
     * @return mixed
     */
    public function getCurlInfos($key = null)
    {
        $infos = curl_getinfo($this->curl);
        return $key
            ? $infos[$key]
            : $infos;
    }

    /**
     * Obtenir la liste des requêtes exécutées
     *
     * @return array
     */
    public function log()
    {
        return $this->log;
    }

    /**
     * Fermer la ressource curl
     */
    public function close()
    {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * Libère la mémoire
     */
    public function __destruct()
    {
        unset($this->log);
        unset($this->params);
        $this->close();
    }
}
