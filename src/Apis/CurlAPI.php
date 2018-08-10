<?php
/**
 * Fichier CurlAPI.php du 03/08/2018
 * Description : Fichier de la classe CurlAPI
 *
 * PHP version 5
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

use Rcnchris\Core\Tools\Items;
use Rcnchris\Core\Tools\Text;

/**
 * Class CurlAPI
 * <ul>
 * <li>Permet d'exécuter une API via son URL</li>
 * <li>Possiblité de formater la réponse sous forme d'objets selon le type de retour de l'API.</li>
 * <li>API utilisées :
 *      <ul>
 *          <li>https://dog.ceo/api</li>
 *          <li>https://geo.api.gouv.fr</li>
 *          <li>https://etablissements-publics.api.gouv.fr/v1/organismes</li>
 *      </ul>
 * </li>
 * </ul>
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class CurlAPI
{
    /**
     * Options de Curl par défaut
     *
     * @var array
     */
    protected $defaultOptions = [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 10,
        //CURLOPT_SSLVERSION => 'CURL_SSLVERSION_TLSv1_2'
    ];

    /**
     * URL de base de l'API
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Clé d'authentification de l'API
     *
     * @const string
     */
    private $api_key = null;

    /**
     * Ressource Curl
     *
     * @var resource
     */
    private $curl;

    /**
     * Réponse de l'exécution de la ressource Curl
     *
     * @var mixed
     */
    private $response;

    /**
     * Partie(s) de à ajouter à l'URL de base lors de l'exécution
     *
     * @var array
     */
    private $parts = [];

    /**
     * Paramètres de l'URL
     *
     * @var array
     */
    private $params = [];

    /**
     * Journal des requêtes exécutées
     *
     * @var array
     */
    private $log = [];

    /**
     * Code des entêtes HTTP
     *
     * @var array
     */
    private $httpCodes = [
        200 => 'OK',
        301 => 'Moved Permanently',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway ou Proxy Error',
        505 => 'HTTP Version not supported',
    ];

    /**
     * Constructeur
     * Définit l'URL de base de l'API et options de Curl
     *
     * @param string     $url     URl de base de l'API
     * @param array|null $options Options de Curl
     *
     * @throws \Rcnchris\Core\Apis\ApiException
     * @see http://php.net/manual/en/function.curl-init.php
     */
    public function __construct($url, array $options = [])
    {
        $this->setBaseUrl($url);
        $this->curl = curl_init($this->baseUrl);
        $this->setCurlOptions($options);
    }

    /**
     * Fermeture de la ressource Curl
     */
    public function __destruct()
    {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
        }
        unset($this->parts);
        unset($this->params);
        unset($this->response);
    }

    /**
     * Obtenir l'URL de base de l'API
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Obtenir les options de Curl ou l'une d'entre elles
     *
     * @param string|null $key Nom de l'option
     *
     * @return array|mixed
     * @see http://php.net/manual/fr/function.curl-getinfo.php
     */
    public function getCurlInfos($key = null)
    {
        $infos = curl_getinfo($this->curl);
        return $key
            ? $infos[$key]
            : $infos;
    }

    /**
     * Obtenir l'URL de l'API
     *
     * @param bool $withParts
     * @param bool $withParams
     *
     * @return string
     */
    public function getUrl($withParts = true, $withParams = true)
    {
        $url = $this->baseUrl;
        if ($withParts && !empty($this->parts)) {
            $url .= '/' . implode('/', $this->parts);
        }
        if ($withParams && !empty($this->params)) {
            $url .= '?' . $this->getParams();
        }
        return $url;
    }

    /**
     * Obtenir l'URL courante de l'API
     *
     * @return string|null
     */
    public function getContentType()
    {
        $contentType = explode(';', $this->getCurlInfos('content_type'));
        return current($contentType);
    }

    /**
     * Obtenir le charset courant de l'API
     *
     * @return string|null
     */
    public function getCharset()
    {
        $contentType = explode(';', $this->getCurlInfos('content_type'));
        $charset = end($contentType);
        return Text::getAfter('=', $charset);
    }

    /**
     * Obtenir le code HTTP du retour
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->getCurlInfos('http_code');
    }

    /**
     * Définir l'URL de base de l'API
     *
     * @param string $baseUrl URL de base de l'API
     *
     * @return $this
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function setBaseUrl($baseUrl)
    {
        if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            throw new ApiException("L'URL $baseUrl est incorrecte !");
        }
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * Définir les options de Curl
     *
     * @param mixed      $options Options de Curl sous forme de tableau ou constante de l'option
     * @param mixed|null $value   Valeur de l'option si $options n'est pas un tableau
     *
     * @return $this
     * @see http://php.net/manual/fr/function.curl-setopt.php
     * @see http://php.net/manual/fr/function.curl-setopt-array.php
     */
    public function setCurlOptions($options, $value = null)
    {
        if (is_array($options)) {
            $options = ($options + $this->defaultOptions);
            curl_setopt_array($this->curl, $options);
        } else {
            curl_setopt($this->curl, $options, $value);
        }
        return $this;
    }

    /**
     * Exécuter la requête de la ressource Curl
     *
     * @param bool|null   $build Construit la requête à partir des parties et paramètres de l'URL
     * @param string|null $title Titre de l'enregistrement dans le journal
     *
     * @return $this
     */
    public function exec($build = true, $title = null)
    {
        if ($build) {
            $this->setCurlOptions(CURLOPT_URL, $this->getUrl());
        }
        $this->response = curl_exec($this->curl);
        $this->log($title);
        return $this;
    }

    /**
     * Obtenir le contenu de la réponse
     *
     * @param string|null $format Format du retour (array, items, xml)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function get($format = null)
    {
        if ($this->getHttpCode() === 200) {
            $formats = ['array', 'items', 'xml'];
            if (!is_null($format) && in_array(strtolower($format), $formats)) {
                $contentType = $this->getContentType();

                if ($contentType === 'text/xml' && $format === 'xml') {
                    return simplexml_load_string($this->response);
                }

                if ($contentType === 'application/json' && $format != 'xml') {
                    $items = new Items($this->response);
                    return $items;
                }

                if ($contentType === 'text/plain' && $format === 'items') {
                    $items = new Items($this->response);
                    return $items;
                }
            }
            return $this->response;
        } else {
            return $this->getHttpCodes($this->getCurlInfos('http_code'));
        }
    }

    /**
     * Ajoute une ou plusieurs parties séparées par des '/' à l'URL de base
     *
     * @param string     $urlParts Partie(s) d'URL à ajouter à l'url de base
     * @param bool|false $erase    Efface les parties précédentes
     *
     * @return $this
     */
    public function addUrlParts($urlParts, $erase = false)
    {
        if ($erase) {
            $this->parts = [];
        }
        $this->parts = explode('/', trim($urlParts, '/'));
        return $this;
    }

    /**
     * Ajoute des paramètres à l'URL
     *
     * @param string|array $params Paramètres de l'URL
     * @param mixed|null   $value  Valeur du paramètre si $params n'est pas un tableau
     * @param bool|null    $erase  Efface les paramètres précédents
     *
     * @return $this
     */
    public function addUrlParams($params, $value = null, $erase = false)
    {
        if ($erase) {
            $this->params = [];
        }
        if (is_string($params)) {
            $this->params[$params] = $value;
        } elseif (is_array($params)) {
            $this->params = array_merge($this->params, $params);
        }
        return $this;
    }

    /**
     * Obtenir les paramètres de l'URL
     *
     * @param bool|null $build Retourne un tableau si vrai, sinon une chaîne de caractères
     *
     * @return array|string
     */
    public function getParams($build = true)
    {
        return $build
            ? http_build_query($this->params)
            : $this->params;
    }

    /**
     * Obtenir le journal des requêtes
     * - `$allo->getLog();`
     *
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Définir le navigateur de la requête
     *
     * @param string $browser Nom du navigateur
     *
     * @return \Rcnchris\Core\Apis\CurlAPI
     */
    public function withUserAgent($browser)
    {
        return $this->setCurlOptions(CURLOPT_USERAGENT, $browser);
    }

    /**
     * Retourne les informations de versions de cURL
     *
     * @param string|null $key Clé du tableau à retourner ()
     *
     * @return null|\Rcnchris\Core\Tools\Items
     * @see http://php.net/manual/fr/function.curl-version.php
     */
    public function getVersion($key = null)
    {
        $infos = new Items(curl_version());
        return is_null($key)
            ? $infos :
            $infos->get($key);
    }

    /**
     * Obtenir la clé de l'API
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Définir la clé de l'API
     *
     * @param string $api_key Clé de l'API
     *
     * @return $this
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    /**
     * Obtenir les codes erreurs HTTP
     *
     * @param int|string|null $code Code dont il faut retourner le texte de l'erreur
     *
     * @return array
     */
    public function getHttpCodes($code = null)
    {

        if (is_null($code)) {
            return $this->httpCodes;
        } elseif (array_key_exists(intval($code), $this->httpCodes)) {
            return $code . ' : ' . $this->httpCodes[$code];
        } else {
            return null;
        }
    }

    /**
     * Ajoute une trace dans le journal des requêtes
     *
     * @param string|null $title Titre de l'enregistrement dans le journal
     */
    private function log($title = null)
    {
        array_push($this->log, [
            'class' => get_class($this),
            'title' => $title,
            'details' => $this->getCurlInfos()
        ]);
    }
}
