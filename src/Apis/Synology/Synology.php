<?php
/**
 * Fichier Synology.php du 25/08/2018
 * Description : Fichier de la classe Synology
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */
namespace Rcnchris\Core\Apis\Synology;

use Locale;
use Rcnchris\Core\Apis\CurlAPI;
use Rcnchris\Core\Html\Html;
use Rcnchris\Core\Tools\Items;

/**
 * Class Synology
 *
 * @category API
 *
 * @package  Rcnchris\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Synology extends CurlAPI
{
    /**
     * Préfixe du nom des API
     *
     * const string
     */
    const PREFIXE_NAME = 'SYNO';

    /**
     * Configuration par défaut
     *
     * @var array
     */
    private $defaultConfig = [
        'name' => '',
        'description' => '',
        'address' => '',
        'port' => 5000,
        'protocol' => 'http',
        'version' => 1,
        'ssl' => false,
        'user' => 'php',
        'pwd' => 'php',
        'format' => 'sid'
    ];

    /**
     * Configuration de connexion
     *
     * @var \Rcnchris\Core\Tools\Items
     */
    private $config;

    /**
     * Chemins et version de toutes les API de l'instance
     *
     * @var array
     */
    private $apiPaths = [];

    /**
     * Liste des SID obtenus par API
     *
     * @var array
     */
    private $sids = [];

    /**
     * Constructeur
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
        parent::__construct($this->getBaseUrl());
    }

    /**
     * Obtenir la configuration de connexion
     *
     * @return Items
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Définir la configuration
     *
     * @param array $config
     */
    public function setConfig(array $config = [])
    {
        $this->config = new Items(array_merge($this->defaultConfig, $config));
        $this->setBaseUrl(
            $this->config->get('protocol') . '://'
            . $this->config->get('address') . ':'
            . $this->config->get('port')
            . '/webapi'
        );
    }

    /**
     * Effectuer une requête sur une API Synology
     *
     * - `$syno->request('AudioStation.Genre', 'list', ['limit' => 3]);`
     * - `$syno->request('VideoStation.Movie', 'list', ['limit' => 3, 'account' => 'phpunit', 'passwd' => 'mycoretest']);`
     *
     * @param string      $apiName Nom de l'API (AudioStation.Album, VideoStation.Movie...)
     * @param string|null $method  Nom de la méthode à utiliser
     * @param array|null  $params  Paramètres de la requête
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function request($apiName, $method = 'list', array $params = [])
    {
        $apiParts = explode('.', $this->getPrefixName(true) . $apiName);
        if (!isset($params['account'])) {
            $params['account'] = $this->getConfig()->get('user');
        }
        if (!isset($params['passwd'])) {
            $params['passwd'] = $this->getConfig()->get('pwd');
        }
        if (!isset($params['format'])) {
            $params['format'] = $this->getConfig()->get('format');
        }
        $url = $this->makeUrl($apiParts, $method, $params);

        return $this->getResponse(
            $this->exec($url, $apiName . ' ' . $method . ' by ' . $params['account'], true),
            $apiName,
            $method,
            $params
        )->get('data');
    }

    /**
     * Obtenir une URL formée à partir des éléments nécessaires
     *
     * @param array  $apiParts Tableau des parties du nom de l'API (['SYNO', 'VideoStation', 'Movie'])
     * @param string $method   Nom de la méthode
     * @param array  $params   Paramètres de la requête
     *
     * @return string
     * @throws \Exception
     */
    private function makeUrl($apiParts, $method, $params)
    {
        $apiName = implode('.', $apiParts);

        // 1 - Obtenir le 'path' de l'API demandée.
        $apiDefinition = $this->getApiDefinition($apiName);

        // Se connecter à l'API pour obtenir le sid
        $sid = $this->login($apiName, $params);
        unset($params['account']);
        unset($params['passwd']);
        unset($params['format']);

        $version = $apiDefinition->get('maxVersion');
        if (isset($params['version'])) {
            $version = intval($params['version']);
        }

        $params = array_merge([
            'api' => $apiName,
            'version' => $version,
            'method' => $method,
            '_sid' => $sid
        ], $params);

        return $this
            ->addUrlParts($apiDefinition->get('path'), true)
            ->addUrlParams($params, null, true)
            ->getUrl();
    }

    /**
     * Se connecte à une API et retourne le SID obtenu
     *
     * @param       $apiName
     * @param array $params
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items|string
     */
    private function login($apiName, array $params = [])
    {
        $sid = $this->getSids($apiName, $params['account']);
        if ($sid) {
            return $sid;
        }
        $session = explode('.', $apiName)[1];
        $response = $this
            ->addUrlParts($this->apiPaths[$this->getPrefixName(true) . 'API.Auth']['path'], true)
            ->addUrlParams([
                'api' => $this->getPrefixName(true) . 'API.Auth',
                'version' => 2,
                'method' => 'login',
                'account' => $params['account'],
                'passwd' => $params['passwd'],
                'session' => $session,
                'format' => $params['format']
            ], null, true)
            ->exec(true, "Login to $session by " . $params['account'], true);

        $sid = $this->getResponse($response)->get('data.sid');
        $this->setSid($apiName, $params['account'], $sid);
        return $sid;
    }

    /**
     * Se déconnecter d'une API
     *
     * @param string $apiName Nom de l'API sans le préfixe méthode publique
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function logout($apiName)
    {
        $session = explode('.', $apiName)[0];
        $response = $this
            ->addUrlParts($this->apiPaths[$this->getPrefixName(true) . 'API.Auth']['path'], true)
            ->addUrlParams([
                'api' => $this->getPrefixName(true) . 'API.Auth',
                'version' => 1,
                'method' => 'logout',
                'session' => $session
            ], null, true)
            ->exec(true, "Logout to $session by " . $this->getConfig()->get('user'), true);

        // Suppression des sids de l'API
        unset($this->sids[$this->getPrefixName(true) . $apiName]);
        return $this->getResponse($response)->get('success');
    }

    /**
     * Obtenir le logo Synology
     * à inclure dans une balise HTML <img src="$syno->logo()"/>
     *
     * @param array|null $attributes Attrbut de la balise img
     *
     * @return string
     */
    public function logo(array $attributes = [])
    {
        return empty($attributes)
            ? "data:image/jpg;base64,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 TNuBU0kkkylImmQpSEIAFKAAABwHm8AcB//Z "
            : '<img ' . Html::parseAttributes($attributes) . 'src="data:image/jpg;base64,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 TNuBU0kkkylImmQpSEIAFKAAABwHm8AcB//Z "/>';
    }

    /**
     * Obtenir la liste de tous des SID obtenus
     * ou uniquement ceux d'un package
     *
     * @param string|null $apiName Nom complet de l'API (SYNO.DownloadStation.Task, SYNO.AudioStation.Album...)
     * @param string|null $user    Login utilisateur
     *
     * @return array|bool|string
     */
    public function getSids($apiName = null, $user = null)
    {
        if (empty($this->sids)) {
            return false;
        }
        if (is_null($apiName)) {
            return $this->sids;
        } else {
            if (array_key_exists($apiName, $this->sids) && is_null($user)) {
                return current($this->sids[$apiName]);
            }
            if (array_key_exists($apiName, $this->sids) && array_key_exists($user, $this->sids[$apiName])) {
                return $this->sids[$apiName][$user];
            }
        }
        return false;
    }

    /**
     * Obtenir la définition d'une API depuis le fichier JSON
     * généré depuis le nas par là : /var/packages/...
     * - `cp /var/packages/AudioStation/target/webapi/AudioStation.api AudioStation.json`
     *
     * @param string $apiName Nom de l'API (AudioStation, VideoStation...)
     *
     * @return bool|\Rcnchris\Core\Tools\Items
     */
    public function getJsonDefinition($apiName)
    {
        $fileName = __DIR__ . "/definitions/$apiName.json";
        if (is_file($fileName)) {
            return new Items(json_decode(file_get_contents($fileName), true));
        }
        return false;
    }

    /**
     * Obtenir les messages des erreurs de toutes les API
     * ou pour une d'entre elle
     *
     * @param string|null $apiName Nom de l'API
     * @param int|null    $code    Code de l'erreur spécifique à Synology
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function getErrorsMessages($apiName = null, $code = null)
    {
        $allErrors = require __DIR__ . '/errors-codes.php';
        $lang = substr(Locale::getDefault(), 0, 2);
        if (!is_null($apiName)) {
            $errors = array_key_exists($apiName, $allErrors)
                ? $allErrors[$apiName]
                : [];
        } else {
            $errors = $allErrors;
        }
        $errors = new Items($errors);
        if (!is_null($code)) {
            if ($errors->has($code)) {
                return $errors->has($code . '.' . $lang)
                    ? $errors->get($code . '.' . $lang)
                    : $errors->get($code . '.en');
            } elseif (array_key_exists($code, $allErrors)) {
                $allErrors = new Items($allErrors);
                if ($allErrors->has($code)) {
                    return $allErrors->has($code . '.' . $lang)
                        ? $allErrors->get($code . '.' . $lang)
                        : $allErrors->get($code . '.en');
                }
            }
        }
        return $errors;
    }

    /**
     * Obtenir les chemins nécessaires à l'utilisation de l'API
     * (API.Info, API.Auth et celui de l'API demandée)
     *
     * @param string $apiName Nom de l'API (SYNO.VideoStation.Movie...)
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    private function getApiDefinition($apiName)
    {
        if (empty($this->apiPaths) || !array_key_exists($apiName, $this->apiPaths)) {
            $response = $this
                ->addUrlParts('query.cgi', true)
                ->addUrlParams([
                    'api' => $this->getPrefixName(true) . 'API.Info',
                    'version' => 1,
                    'method' => 'query',
                    'query' => $this->getPrefixName(true) . 'API.Info' . ',' . $this->getPrefixName(true) . 'API.Auth,' . $apiName
                ], null, true)
                ->exec(true, "Get $apiName definition by " . $this->getConfig()->get('user'), true);

            foreach ($this->getResponse($response)->get('data')->toArray() as $apiName => $definition) {
                $this->apiPaths[$apiName] = $definition;
            }
        }
        return (new Items($this->apiPaths))->get($apiName, false);
    }

    /**
     * Obtenir la définition de toutes les API ou certaines d'entre elles
     *
     * @param array|null $apiNames Liste des API dont il faut récupérer la définition
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getApiDefinitions(array $apiNames = [])
    {
        $query = 'all';
        if (!empty($apiNames)) {
            $apis = [];
            foreach ($apiNames as $apiName) {
                $apis[] = $this->getPrefixName(true) . $apiName;
            }
            $query = $this->getPrefixName(true) . 'API.Info' . ','
                . $this->getPrefixName(true) . 'API.Auth,'
                . implode(',', $apis);
        }
        $response = $this
            ->addUrlParts('query.cgi', true)
            ->addUrlParams([
                'api' => $this->getPrefixName(true) . 'API.Info',
                'version' => 1,
                'method' => 'query',
                'query' => $query
            ], null, true)
            ->exec(true, "Get $query definitions by " . $this->getConfig()->get('user'), true);

        foreach ($this->getResponse($response)->get('data')->toArray() as $apiName => $definition) {
            $this->apiPaths[$apiName] = $definition;
        }
        return new Items($this->apiPaths);
    }

    /**
     * Traitement de la réponse de l'API
     *
     * @param mixed       $response Réponse de l'API
     *
     * @param string|null $apiName  Nom de l'API
     * @param string|null $method   Nom de la méthode
     * @param array|null  $params   Paramètres de la requête
     *
     * @return mixed|\Rcnchris\Core\Tools\Items
     */
    private function getResponse($response, $apiName = null, $method = null, array $params = [])
    {
        // Mettre la réponse dans une instance de Items
        if (is_string($response)) {
            $response = json_decode($response, true);
            if ($response) {
                $response = new Items($response);
            }
        }

        // Traitement des erreurs
        if (!$response->get('success')) {
            return $this->getError($response, $apiName, $method, $params);
        }
        return $response;
    }

    /**
     * Retourne un objet Items avec les informations pour debugger l'erreur
     *
     * @param \Rcnchris\Core\Tools\Items $response
     * @param string                     $apiName Nom de l'API
     * @param string                     $method  Nom de la méthode
     * @param array                      $params  Paramètres de la requête
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    private function getError(Items $response, $apiName, $method, array $params)
    {
        $code = 0;
        $message = null;
        if ($response->has('error.code')) {
            $code = $response->get('error.code');
            $session = explode('.', $apiName)[0];
            $message = $this->getErrorsMessages($session, $response->get('error.code'));
        }

        $error = [
            'nas' => $this->getConfig()->get('name') . ' - ' . $this->getConfig()->get('description'),
            'api' => $apiName,
            'method' => $method,
            'params' => $params,
            'code' => $code,
            'message' => $message,
            'data' => is_object($response) ? $response->toArray() : $response,
            'cURL' => $this->getCurlInfos(),
            'config' => $this->getConfig()->toArray(),
            'logs' => $this->getLog()
        ];
        return new Items(['data' => $error]);
    }

    /**
     * Obtenir le préfixe du nom des API avec point ou pas
     *
     * @param bool|null $withPoint sans point par défaut
     *
     * @return string
     */
    private function getPrefixName($withPoint = false)
    {
        return $withPoint
            ? $this::PREFIXE_NAME . '.'
            : $this::PREFIXE_NAME;
    }

    /**
     * Définir un sid pour uine API et utilisateur
     *
     * @param string $apiName Nom de l'API
     * @param string $user    Login utilisateur
     * @param string $sid     Code d'authentification
     */
    private function setSid($apiName, $user, $sid)
    {
        $this->sids[$apiName][$user] = $sid;
    }
}
