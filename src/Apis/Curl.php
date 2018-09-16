<?php
/**
 * Fichier Curl.php du 29/08/2018
 * Description : Fichier de la classe Curl
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

use Intervention\Image\ImageManagerStatic;
use Rcnchris\Core\Tools\Items;
use Rcnchris\Core\Tools\Text;
use SimpleXMLElement;

/**
 * Class Curl
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
class Curl
{

    /**
     * Session cURL
     *
     * @var resource
     */
    private $curl;

    /**
     * Options de transmissions par défaut de cURL
     *
     * @var array
     */
    private $defaultOptions = [
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_CRLF => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_FORBID_REUSE => false,
        CURLOPT_FRESH_CONNECT => false,
        CURLOPT_FTP_USE_EPRT => false,
        CURLOPT_FTP_USE_EPSV => true,
        CURLOPT_HEADER => false,
        CURLOPT_POST => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        //CURLOPT_SSLVERSION => 'CURL_SSLVERSION_TLSv1_2'
        CURLOPT_TIMEOUT => 4,
        CURLOPT_UPLOAD => false,
    ];

    /**
     * Contenu du retour de curl_exec
     *
     * @var mixed
     */
    private $response;

    /**
     * URL donnée à la construction de l'instance
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Tableau des requêtes exécutées
     *
     * @var array
     */
    private $logs = [];

    /**
     * Constructeur
     *
     * - `$api = new Curl();`
     * - `$api = new Curl('https://geo.api.gouv.fr/regions');`
     *
     * @param string|null $url Adresse internet sous forme de chaîne de caractères
     *
     * @see http://php.net/manual/fr/function.curl-init.php
     */
    public function __construct($url = null)
    {
        $this->curl = curl_init($url);
        $this->setBaseUrl((string)$this);
        $this->setOptions($this->defaultOptions);
    }

    /**
     * Fermeture de la session cURL
     *
     * @see http://php.net/manual/fr/function.curl-close.php
     */
    public function __destruct()
    {
        if (!is_null($this->curl)) {
            curl_close($this->curl);
        }
    }

    /**
     * Obtenir l'URL de cURL
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getInfos('url');
    }

    /**
     * Définir une ou plusieurs options de transmission de cURL
     *
     * - `$url->setOptions(CURLOPT_AUTOREFERER, false);`
     * - `$url->setOptions([CURLOPT_AUTOREFERER => false, CURLOPT_RETURNTRANSFER => false]);`
     *
     * @param array|string $option Nom de l'option à définir ou tableau options/valeur
     * @param mixed|null   $value  Valeur dans le cas où l'option est passée en premier paramètre
     *
     * @return self
     *
     * @see http://php.net/manual/fr/function.curl-setopt.php
     * @see http://php.net/manual/fr/function.curl-setopt-array.php
     */
    public function setOptions($option, $value = null)
    {
        is_array($option)
            ? curl_setopt_array($this->curl, $option)
            : curl_setopt($this->curl, $option, $value);
        return $this;
    }

    /**
     * Exécute la requête de l'URL courante et stocke la réponse et la trace
     *
     * @param string|null $title Titre de la requête pour le journal
     *
     * @return \Rcnchris\Core\Apis\Curl
     */
    public function exec($title = null)
    {
        $this->response = curl_exec($this->curl);
        $this->log($title);
        return $this;
    }

    /**
     * Obtenir les informations détaillant un transfert cURL
     *
     * @param string|null $key Nom d'une clé
     *
     * @return array|mixed
     *
     * @see http://php.net/manual/fr/function.curl-getinfo.php
     */
    public function getInfos($key = null)
    {
        $infos = curl_getinfo($this->curl);
        if (array_key_exists($key, $infos)) {
            return $infos[$key];
        }
        return $infos;
    }

    /**
     * Obtenir le code HTTP du retour
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->getInfos('http_code');
    }

    /**
     * Obtenir l'URL courante de l'API
     *
     * @return string|null
     */
    public function getContentType()
    {
        $contentType = explode(';', $this->getInfos('content_type'));
        return current($contentType);
    }

    /**
     * Obtenir le charset courant de l'API
     *
     * @return string|null
     */
    public function getCharset()
    {
        $contentType = explode(';', $this->getInfos('content_type'));
        $charset = end($contentType);
        return Text::getAfter('=', $charset);
    }

    /**
     * Obtenir l'IP du serveur de la requête
     *
     * @return string
     */
    public function getServerIP()
    {
        return $this->getInfos('primary_ip');
    }

    /**
     * Obtenir la réponse cURL sous forme d'objet en fonction de son contenu, type, statut...
     *
     * @return \Intervention\Image\Image|null|\Rcnchris\Core\Tools\Items|SimpleXMLElement
     */
    public function getResponse()
    {
        if ($this->getHttpCode() === 200) {

            $response = null;

            if ($this->getContentType() === 'application/json' || $this->getContentType() === 'text/plain') {
                $content = json_decode($this->response, true);
                if (is_array($content)) {
                    $response = new Items($content);
                } else {
                    $response = new Items([
                        'error' => json_last_error_msg(),
                        'infos' => $this->getInfos(),
                        'response' => $this->response
                    ]);
                }
            }

            if ($this->getContentType() === 'text/xml') {
                $response = simplexml_load_string($this->response);
            }

            if ($this->getContentType() === 'image/jpeg') {
                $response = ImageManagerStatic::make($this->getUrl());
            }
            return $response;
        }

        return new Items([
            'error' => $this->getHttpCode(),
            'infos' => $this->getInfos(),
            'response' => $this->response,
            'curlError' => curl_error($this->curl)
        ]);
    }

    /**
     * Ajoute des parties à l'URL courante
     *
     * @param array|string $parts Partie(s) à ajouter à l'URL
     *
     * @return \Rcnchris\Core\Apis\Curl
     * @throws \Exception
     */
    public function withParts($parts)
    {
        $this->setUrl();
        $url = $this->getUrl();
        if (is_string($parts)) {
            $url .= '/' . trim($parts, '/');
        } elseif (is_array($parts)) {
            $url .= '/' . implode('/', $parts);
        }
        $this->setUrl($url);
        return $this;
    }

    /**
     * Définir le navigateur de la requête
     *
     * @param string $browser Nom du navigateur
     *
     * @return self
     */
    public function withUserAgent($browser)
    {
        return $this->setOptions(CURLOPT_USERAGENT, $browser);
    }

    /**
     * Ajoute des paramètres à l'URL
     *
     * @param array     $params
     *
     * @param bool|null $erase Supprime les paramètres existants
     *
     * @return self
     */
    public function withParams(array $params = [], $erase = false)
    {
        if ($erase) {
            $this->setUrl($this->getUrl());
        }
        $this->setUrl($this->getUrl() . '?' . http_build_query($params));
        return $this;
    }

    /**
     * Obtenir les paramètres de la requête cURL
     *
     * @param bool|null $toString Retourne une chaîne de caractères décodée plutot qu'un objet
     *
     * @return bool|\Rcnchris\Core\Tools\Items|string
     */
    public function getParams($toString = false)
    {
        if ($this->parseUrl('query')) {
            if ($toString) {
                return rawurldecode($this->parseUrl('query'));
            }
            $query = $this->parseUrl('query');
            $items = explode('&', $query);
            $params = [];
            foreach ($items as $param) {
                list($key, $value) = explode('=', $param);
                $params[$key] = rawurldecode($value);
            }
            return new Items($params);
        }
        return false;
    }

    /**
     * Obtenir l'URL de base
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Définir l'URL de base
     *
     * @param string $url URL de base
     *
     * @return self
     */
    public function setBaseUrl($url)
    {
        if ($this->isUrl($url)) {
            $this->baseUrl = $url;
        }
        return $this;
    }

    /**
     * Définir l'URL de cURL
     *
     * @param string $url Nouvelle URL de cURL
     *
     * @return self|bool
     */
    public function setUrl($url = null)
    {
        if (is_null($url)) {
            $url = $this->getBaseUrl();
        }
        if ($this->isUrl($url)) {
            if ($this->getUrl() === '') {
                $this->setBaseUrl($url);
            }
            $this->setOptions(CURLOPT_URL, $url);
            return $this;
        }
        return false;
    }

    /**
     * Obtenir l'URL de cURL
     *
     * @param bool|null $decode Retourne l'URL décodée
     *
     * @return string
     */
    public function getUrl($decode = false)
    {
        $url = (string)$this;
        if ($decode) {
            return rawurldecode($url);
        }
        return $url;
    }

    /**
     * Obtenir les parties de l'URL dans un objet ou la valeur d'une clé
     *
     * - `$api->parseUrl()->toArray();`
     * - `$api->parseUrl('host');`
     *
     * @param string|null $key Clé à retourner
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     *
     * @see http://php.net/manual/fr/function.parse-url.php
     */
    public function parseUrl($key = null)
    {
        $parts = new Items(parse_url((string)$this));
        if (is_null($key)) {
            return $parts;
        }
        if ($parts->has($key)) {
            return $parts->get($key);
        }
        return false;
    }

    /**
     * Vérifie si la chaîne correspond à une syntaxe d'URL valide
     *
     * @param string $url URL à vérifier
     *
     * @return bool
     *
     * @see http://php.net/manual/fr/function.filter-var.php
     */
    private function isUrl($url)
    {
        return $url === filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Ajoute la dernière requête au journal
     *
     * @param string|null $title Titre de la requête
     */
    private function log($title = null)
    {
        array_push($this->logs, [
            'class' => get_class($this),
            'title' => $title,
            'details' => $this->getInfos()
        ]);
    }

    /**
     * Obtenir le journal des requêtes exécutées
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getLog()
    {
        return new Items($this->logs);
    }

    /**
     * Obtenir cURL
     *
     * @return resource
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * Obtenir des informations de l'extension PECL si elle est disponible
     *
     * - `$api->getGeoipInfos('country_name_by_name', $api->parseUrl('host'));`
     *
     * @param string     $type  Fin du nom de la fonction à utiliser
     * @param mixed|null $param Paramètre de la fonction
     *
     * @return bool|mixed
     */
    public function getGeoipInfos($type, $param = null)
    {
        if (extension_loaded('pecl')) {
            $functionName = 'geoip_' . $type;
            if (function_exists($functionName)) {
                return call_user_func($functionName, [$param]);
            }
        }
        return false;
    }
}
