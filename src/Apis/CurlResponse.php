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
 * Class CurlResponse<br/>
 * <ul>
 * <li>Représente la réponse de l'éxécution de la commande <code>curl_exec()</code></li>
 * </ul>
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class CurlResponse
{
    /**
     * Réponse de l'API
     *
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
     * Code des entêtes HTTP
     *
     * @var array
     */
    private $httpCodes = [
        200 => 'OK'
        , 301 => 'Moved Permanently'
        , 401 => 'Unauthorized'
        , 403 => 'Forbidden'
        , 404 => 'Not Found'
        , 500 => 'Internal Server Error'
    ];

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

    /**
     * Vérifie que le résultat est de type JSON
     *
     * @return bool
     */
    public function isJson()
    {
        $parts = explode('/', $this->getContentType());
        $type = array_pop($parts);
        return $type === 'json';
    }

    /**
     * Vérifie que le résultat est de type HTML
     *
     * @return bool
     */
    public function isHtml()
    {
        $parts = explode('/', $this->getContentType());
        $type = array_pop($parts);
        return $type === 'html';
    }

    /**
     * Vérifie que le résultat est de type texte
     *
     * @return bool
     */
    public function isText()
    {
        $parts = explode('/', $this->getContentType());
        $type = current($parts);
        return $type === 'text';
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
     * Obtenir l'URL de la requête
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->curlInfos['url'];
    }

    /**
     * Obtenir la réponse brute
     *
     * @return mixed
     */
    public function get()
    {
        $httpCode = $this->getHttpCode();
        if ($httpCode === 200) {
            return $this->response;
        } elseif (array_key_exists($httpCode, $this->httpCodes)) {
            return $this->httpCodes[$httpCode];
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
        $response = $this->get();
        $array = [];
        if ($this->getType() === 'string') {
            if ($this->isJson()) {
                $array = json_decode($response, true);
            } elseif ($this->isHtml()) {
                array_push($array, $response);
            } elseif ($this->isText()) {
                $array = json_decode($response, true);
                if (json_last_error()) {
                    return json_last_error_msg();
                }
            }
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
        $response = $this->get();
        if ($this->getType() === 'string') {
            if ($this->isJson()) {
                if (is_null($key)) {
                    return $response;
                }
                $array = json_decode($response, true);
                if (array_key_exists($key, $array)) {
                    return json_encode($array[$key]);
                }
            }
        }
        return $response;
    }

    /**
     * Affiche le résultat brut de la requête
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->get();
    }
}
