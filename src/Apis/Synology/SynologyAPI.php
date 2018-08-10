<?php
/**
 * Fichier SynologyAPI.php du 08/08/2018
 * Description : Fichier de la classe SynologyAPI
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\CurlAPI;
use Rcnchris\Core\Tools\Items;

/**
 * Class SynologyAPI
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <2.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class SynologyAPI extends CurlAPI
{

    /**
     * Nom de l'API d'authentification
     * Permet de déterminer le préfixe et de connaître le nom de l'API d'authentification
     *
     * const string
     */
    const API_AUTH_NAME = 'SYNO.API.Auth';

    /**
     * Version de l'API d'authentification
     */
    const AUTH_VERSION = 2;

    /**
     * Configuration de l'API
     *
     * @var Items
     */
    private $config;

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
        'user' => 'phpunit',
        'pwd' => 'phpunit'
    ];

    /**
     * Liste des API stockées dans l'instance
     *
     * @var Items
     */
    private $apiList = null;

    /**
     * Formats d'authentification
     *
     * @var array
     */
    private $authFormats = ['sid', 'cookie'];

    /**
     * Liste des identifiants des API déjà obtenus par l'instance
     *
     * @var array
     */
    private $sids = [];

    /**
     * Liste des définitions d'API déjà obtenues par l'instance
     *
     * @var array
     */
    private $definitions = [];

    /**
     * Constructeur
     * Accepte l'URL de base ou un tableau de configuration
     */
    public function __construct()
    {
        $config = func_get_arg(0);
        if (is_array($config)) {
            $this->setConfig($config);
            parent::__construct($this->config->get('protocol') . '://' . $this->config->get('address') . ':' . $this->config->get('port') . '/webapi');
        } elseif (is_string($config)) {
            parent::__construct($config);
        }
    }

    /**
     * Suppression du contenu et déconnexion des session Synology ouvertes
     */
    public function __destruct()
    {
//        foreach ($this->getSids()->keys()->toArray() as $apiName) {
//            $this->logout($apiName);
//        }
        unset($this->config);
        parent::__destruct();
    }

    /**
     * Obtenir l'URL de base de l'API à partir de la configuration si elle existe, sinon l'URL de cURL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if ($this->config) {
            return $this->config->get('protocol')
            . '://' . $this->config->get('address')
            . ':' . $this->config->get('port')
            . '/webapi';
        } else {
            return $this->getCurlInfos('url');
        }
    }

    /**
     * Définir la configuration de l'API
     *
     * @param array $config Configuration de l'API
     *
     * @return array
     */
    public function setConfig(array $config = [])
    {
        return $this->parseConfig($config);
    }

    /**
     * Obtenir la configuration de l'API
     *
     * @return Items
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Obtenir la liste des apis
     *
     * @param bool|null $force Forcer la requête si déjà exécutée dans l'instance
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function getApis($force = false)
    {
        if (is_null($this->apiList) || $force) {
            $this->apiList = $this
                ->addUrlParts('query.cgi', true)
                ->addUrlParams([
                    'api' => 'SYNO.API.Info',
                    'version' => 1,
                    'method' => 'query',
                    'query' => 'all'
                ], true)
                ->exec(true, 'API list ')
                ->get('items')
                ->get('data');
        }
        return $this->apiList;
    }

    /**
     * Obtenir la définition d'une API par son nom
     *
     * @param string $apiName Nom de l'API (AntiVirus.Config, AudioStation.Album...)
     *
     * @return null|\Rcnchris\Core\Tools\Items
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function getApiDefinition($apiName)
    {
        $apiShortName = $apiName;
        $apiName = $this->getPrefixApiName() . '.' . $apiName;

        if (isset($this->definitions[$apiName])) {
            return $this->definitions[$apiName];
        }
        $definition = $this
            ->addUrlParts('query.cgi', true)
            ->addUrlParams([
                'api' => 'SYNO.API.Info',
                'version' => 1,
                'method' => 'query',
                'query' => "SYNO.API.Auth,$apiName",
            ], true)
            ->exec(true, 'Definition of ' . $apiShortName)
            ->get('items')
            ->get('data');

        $this->definitions[$apiName] = $definition;
        return $definition;
    }

    /**
     * Obtenir la liste des packages disponibles
     *
     * @param bool|null $withMethods Ajoute la liste des méthodes pour chaque package
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getPackages($withMethods = false)
    {
        $packages = [];
        foreach ($this->getApis()->keys() as $apiName) {
            $withMethods
                ? $packages[$this->getPackageName($apiName)][] = $this->getMethodName($apiName)
                : $packages[] = $this->getPackageName($apiName);
        }
        !$withMethods
            ? $packages = array_unique($packages)
            : null;
        return new Items($packages);
    }

    /**
     * Obtenir le nom du package à partir du nom de l'API
     * - SYNO.AudioStation.Album -> AudioStation
     *
     * @param string $apiName Nom de l'API (SYNO.AudioStation.Album, SYNO.DownloadStation.Task...)
     *
     * @return string
     */
    public function getPackageName($apiName)
    {
        $packageName = str_replace('SYNO.', '', $apiName);
        $packageName = explode('.', $packageName);
        return current($packageName);
    }

    /**
     * Obtenir le nom de la méthode d'une API à partir de son nom
     * - SYNO.AudioStation.Album -> Album
     *
     * @param string $apiName Nom de l'API (SYNO.AudioStation.Album, SYNO.API, SYNO.DownloadStation.Task...)
     *
     * @return string|null
     */
    public function getMethodName($apiName)
    {
        $packageName = str_replace('SYNO.', '', $apiName);
        $packageName = explode('.', $packageName);
        return end($packageName);
    }

    /**
     * Vérifier la présence d'un package
     *
     * @param string $packageName Nom du package (AudioStation, API, DownloadStation...)
     *
     * @return bool
     */
    public function hasPackage($packageName)
    {
        return $this->getPackages()->hasValue($packageName);
    }

    /**
     * Obtenir la liste des méthodes d'un package
     *
     * @param string $packageName Nom du package (AudioStation, DownloadStation...)
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getMethodsOfPackage($packageName)
    {
        $methods = [];
        if ($this->hasPackage($packageName)) {
            foreach ($this->getApis()->keys() as $apiName) {
                if ($this->getPackageName($apiName) === $packageName) {
                    $methods[] = $this->getMethodName($apiName);
                }
            }
        }
        $methods = array_unique($methods);
        return new Items($methods);
    }

    /**
     * Obtenir l'instance d'un package
     *
     * @param string $packageName Nom du package (AudioStation, DownloadStation...)
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIPackage
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function getPackage($packageName)
    {
        return new SynologyAPIPackage($packageName, $this);
    }

    /**
     * Se connecter à une API avec les données de configuration
     * - `$syno->login('DownloadStation.Task');`
     *
     * @param string      $apiName Nom du package et de la méthode (DownloadStation.Task, AudioStation.Album...)
     * @param string|null $format  Format d'authentification (sid ou cookie)
     * @param string|null $user    Login de connexion
     * @param string|null $pwd     Mot de passse
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function login($apiName, $format = 'sid', $user = null, $pwd = null)
    {
        if (!in_array($format, $this->authFormats)) {
            throw new SynologyException(
                "Le format d'authentication $format est incorrect. Merci d'essayer un de ceux-là : "
                . implode(', ', $this->authFormats)
            );
        }

        $apiShortName = $apiName;
        $apiName = $this->getPrefixApiName() . '.' . $apiName;
        $sid = $this->getSids($apiName);
        if ($sid) {
            return $sid;
        }
        $definition = $this->getApiDefinition($apiShortName);
        $authPath = $definition->get($this->getPrefixApiName() . '.' . $this->getAuthApiName(), false)->get('path');
        if (is_null($user)) {
            $user = $this->getConfig()->get('user');
        }
        if (is_null($pwd)) {
            $pwd = $this->getConfig()->get('pwd');
        }
        $sid = $this
            ->addUrlParts($authPath, true)
            ->addUrlParams([
                'api' => $this->getPrefixApiName() . '.' . $this->getAuthApiName(),
                'version' => $this::AUTH_VERSION,
                'method' => 'login',
                'account' => $user,
                'passwd' => $pwd,
                'session' => $this->getPackageName($apiName),
                'format' => $format
            ], true)
            ->exec(true, 'Login to ' . $apiShortName . ' by ' . $this->getConfig()->get('user'))
            ->get('items')
            ->get('data')
            ->get('sid');

        $this->setSid($apiName, $sid);
        return $sid;
    }

    /**
     * Se déconnecte d'une API
     *
     * @param string $apiName Nom de l'API (DownloadStation.Task, AudioStation.Album)
     *
     * @return array|bool
     * @throws \Rcnchris\Core\Apis\ApiException
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function logout($apiName)
    {
        $apiShortName = $apiName;
        $apiName = $this->getPrefixApiName() . '.' . $apiName;
        if ($this->getSids($apiName)) {
            $this->setBaseUrl($this->getBaseUrl());
            $authPath = $this->getApiDefinition($apiShortName)
                ->get($this->getPrefixApiName() . '.' . $this->getAuthApiName(), false)
                ->get('path');

            $response = $this
                ->addUrlParts($authPath)
                ->addUrlParams([
                    'api' => $this->getPrefixApiName() . '.' . $this->getAuthApiName(),
                    'version' => 1,
                    'method' => 'logout',
                    'session' => $apiName
                ])
                ->exec(true, "Logout to $apiShortName by " . $this->getConfig()->get('user'))
                ->get('items');

            unset($this->sids[$apiName]);
            return $response->toArray();
        }
        return false;
    }

    /**
     * Retourne la réponse en traitant les erreurs Synology
     *
     * @param string|null $format Format du retour (array, items, xml)
     *
     * @return mixed|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function get($format = null)
    {
        $response = parent::get($format);
        if (!$response instanceof Items) {
            $content = json_decode($response, true);
            if (isset($content['success'])) {
                if (isset($content['error']['code'])) {
                    throw new SynologyException('', $content['error']['code']);
                }
            }
        } elseif (!$response->get('success')) {
            throw new SynologyException('', intval($response->get('error.code')));
        }
        return $response;
    }


    /**
     * Obtenir la liste de tous des SID obtenus
     * ou uniquement ceux d'un package
     *
     * @param string|null $apiName Nom de l'API (DownloadStation.Task, AudioStation.Album...)
     *
     * @return bool|\Rcnchris\Core\Tools\Items
     */
    public function getSids($apiName = null)
    {
        if (is_null($apiName)) {
            return new Items($this->sids);
        } elseif (array_key_exists($apiName, $this->sids)) {
            return $this->sids[$apiName];
        }
        return false;
    }

    /**
     * Définir un SID pour une API
     *
     * @param string $apiName Nom de l'API
     * @param string $sid     SID obtenu
     */
    public function setSid($apiName, $sid)
    {
        $this->sids[$apiName] = $sid;
    }

    /**
     * Obtenir la liste des définitions obtenues ou l'une d'entre elles
     *
     * @param string|null $apiName Nom complet de l'API (DownloadStation.Task, AudioStation.Album...)
     *
     * @return array
     */
//    public function getDefinitions($apiName = null)
//    {
//        if (is_null($apiName)) {
//            return $this->definitions;
//        } else {
//            $apiName = $this->getPrefixApiName() . '.' . $apiName;
//            if (array_key_exists($apiName, $this->definitions)) {
//                return $this->definitions[$apiName];
//            }
//        }
//        return null;
//    }

    /**
     * Alimente le tableau de configuration
     *
     * @param array $config Configuration
     *
     * @return array
     */
    private function parseConfig(array $config = [])
    {
        $this->config = new Items(array_merge($this->defaultConfig, $config));
        return $this;
    }

    /**
     * Obtenir le préfixe du nom de toutes les API
     *
     * @return string
     */
    public function getPrefixApiName()
    {
        $parts = explode('.', $this::API_AUTH_NAME);
        return current($parts);
    }

    /**
     * Obtenir le journal des requêtes sous formes d'objet Items
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getLog()
    {
        return new Items(parent::getLog());
    }

    private function getAuthApiName()
    {
        return str_replace($this->getPrefixApiName() . '.', '', $this::API_AUTH_NAME);
    }
}
