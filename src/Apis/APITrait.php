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

/**
 * Class APITrait<br/>
 *
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
    protected $queries = [];

    /**
     * Réponse de l'exécution d'une requête
     *
     * @var mixed
     */
    protected $response;

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
            $this->setCurlOptions($this->curlOptions);
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
     * @param string|array $query Nom de la requete ou tableau de requêtes
     * @param mixed|null   $value Valeur de la requête si $query est une chaîne
     * @param bool|null    $erase Efface les requêtes existantes le cas échéant
     *
     * @return $this
     */
    public function addQuery($query, $value = null, $erase = true)
    {
        if (is_string($query)) {
            if ($erase) {
                $this->queries = [];
            }
            $this->queries[$query] = $value;
        } elseif (is_array($query)) {
            $this->queries = !$erase
                ? array_merge($this->queries, $query)
                : $query;
        }
        return $this;
    }

    /**
     * Obtenir la liste des requêtes de l'URL
     *
     * @param bool|null $build Si vrai, retourne une chaîne de caractères formée pour l'URL (key1=value&key2=value...)
     *
     * @return array|string
     */
    public function getQueries($build = true)
    {
        return !$build
            ? $this->queries
            : http_build_query($this->queries);
    }

    /**
     * Exécuter une requête sur l'API
     *
     * @param string|null $url URL de la requête (sans les queries)
     *
     * @return mixed|null|string
     * @throws ApiException
     */
    public function request($url = null)
    {
        // Définition de l'URL à exécuter
        if (is_null($url)) {
            $url = $this->getCurlInfos('url');
        }
        if (is_null($url) || $url === '' || !is_string($url)) {
            throw new ApiException("Aucune URL à exécuter !");
        }

        // Queries
        $url = $url . '?' . $this->getQueries();
        curl_setopt($this->curl, CURLOPT_URL, $url);
        $this->response = curl_exec($this->curl);
        return $this;
    }

    /**
     * Obtenir l'URL de l'API
     *
     * @param bool|null $full Si faux, les queries ne font pas partie de l'URL retournée
     *
     * @return mixed|string
     */
    public function url($full = true)
    {
        $url = $this->getCurlInfos('url');
        return $full
            ? $url . '?' . $this->getQueries()
            : $url;
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
        $this->close();
    }
}
