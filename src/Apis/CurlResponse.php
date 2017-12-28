<?php
/**
 * Fichier CurlResponse.php du 28/12/2017
 * Description : Fichier de la classe CurlResponse
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

/**
 * Class CurlResponse
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
class CurlResponse
{
    /**
     * @var mixed
     */
    private $response;

    /**
     * Curl
     *
     * @var resource
     */
    private $curl;

    /**
     * Informations sur CURL
     *
     * @var array
     */
    private $curlInfos = [];

    /**
     * Constructeur
     *
     * @param resource $curl     CURL
     * @param mixed    $response Réponse de curl_exec()
     */
    public function __construct($curl, $response)
    {
        $this->response = $response;
        $this->curl = $curl;
        $this->curlInfos = curl_getinfo($this->curl);
    }

    /**
     * Obtenir le type de la réponse
     *
     * @return string|void
     */
    public function getType()
    {
        return getType($this->response);
    }

    /**
     * Obtenir le code HTTP de retour de la requête
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->curlInfos['http_code'];
    }

    /**
     * Obtenir le Content Type du retour de la requête
     *
     * @return mixed
     */
    public function getContentType()
    {
        $type = current(explode(';', $this->curlInfos['content_type']));
        return $type;
    }

    public function isJson()
    {
        $parts = explode('/', $this->getContentType());
        $type = array_pop($parts);
        return $type === 'json';
    }

    /**
     * Obtenir le Charset du retour de la requête
     *
     * @return array|mixed
     */
    public function getCharset()
    {
        $parts = explode(';', $this->curlInfos['content_type']);
        $charset = array_pop($parts);
        $charset = explode('=', $charset);
        $charset = array_pop($charset);
        return $charset;
    }

    /**
     * Obtenir la réponse brute
     *
     * @return mixed
     */
    public function get()
    {
        $httpCode = $this->getHttpCode();
        if (!$this->response) {
            if ($httpCode === 301) {
                return '301 : Moved Permanently';
            } elseif ($httpCode === 401) {
                return '401 : Unauthorized';
            } elseif ($httpCode === 403) {
                return '403 : Forbidden';
            } elseif ($httpCode === 404) {
                return '404 : Not Found';
            }
        }
        if ($httpCode === 200) {
            return $this->response;
        }
        return curl_error($this->curl);
    }

    /**
     * Obtenir la réponse sous la forme d'un tableau
     *
     * @param string|null $key Nom de la clé de la réponse à retourner
     *
     * @return array|mixed
     */
    public function toArray($key = null)
    {
        $array = [];
        if ($this->getType() === 'string' && $this->isJson()) {
            $array = json_decode($this->response, true);
        }
        if (!is_null($key) && array_key_exists($key, $array)) {
            return $array[$key];
        }
        return $array;
    }

    /**
     * Obtenir la réponse au format JSON
     *
     * @param string|null $key Nom de la clé de la réponse à retourner
     *
     * @return mixed|null|string
     */
    public function toJson($key = null)
    {
        if ($this->getType() === 'string' && $this->isJson()) {
            if (is_null($key)) {
                return $this->response;
            }
            $array = json_decode($this->response, true);
            if (array_key_exists($key, $array)) {
                return json_encode($array[$key]);
            }
        }
    }
}
