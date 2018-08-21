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
     * Contenu de la clé 'error' de la réponse
     *
     * @var Items
     */
    public $errors;

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
     * Instance du package qui utilise l'API
     *
     * @var SynologyAPIPackage
     */
    private $currentPackage;

    /**
     * Liste des packages instanciés dans cette instance
     *
     * @var SynologyAPIPackage[]
     */
    public $packages = [];

    /**
     * Liste des méthodes par API
     *
     * Pour info... pour l'instant
     *
     * @var array
     */
    public $apiMethods = [];

    /**
     * Liste des méthodes rencontrées
     *
     * @var array
     */
    public $enableMethods = [
        'list',
        'video_list',
        'video_getinfo',
        'get',
        'getinfo',
        'getconfig',
        'getstatus',
        'get_task_detail',
        'getCategory',
        'getModule',
        'getimage',
        'getcountry',
        'getregion',
        'status',
        'search',
        'list_share',
        'start',
        'restart',
        'stop',
        'clean',
        'open',
        'close',
        'create',
        'add',
        'add_by_file',
        'addvideo',
        'delete',
        'deletevideo',
        'edit',
        'edit_adv',
        'replace_all',
        'rename',
        'write',
        'clear_broken',
        'clear_invalid',
        'clear_finished',
        'download',
        'upload',
        'stream',
        'transcode',
        'update',
        'updateradios',
        'copytolibrary',
        'updatesongs',
        'createsmart',
        'updatesmart',
        'getplaylist',
        'updateplaylist',
        'control',
        'resume',
        'pause',
        'refresh',
        'testpassword',
        'setconfig',
        'setpassword',
        'setserverconfig',
        'setimage',
        'setinfo',
        'getstreamid',
        'stream',
        'getsonginfo',
        'deletesonginfo',
        'getlyrics',
        'setlyrics',
        'searchlyrics',
        'getsongcover',
        'getfoldercover',
        'getcover',
        'verify_account',
        'login',
        'get_download_default_dest',
        'download_to_local',
        'get_satellite',
        'create_satellite',
        'edit_satellite',
        'delete_satellite',
        'get_lnb',
        'create_lnb',
        'edit_lnb',
        'delete_lnb',
        'get_tp',
        'get_tp_default',
        'save_tp',
        'delete_all_channels',
        'delete_passed',
        'create_repeat',
        'getinfo_repeat',
        'edit_repeat',
        'delete_repeat',
        'getinfo_userdefine',
        'create_userdefine',
        'edit_userdefine',
        'delete_userdefine',
        'getchannel',
        'setchannel',
        'query',
        'updateinfo'
    ];

    /**
     * Constructeur
     * Accepte l'URL de base ou un tableau de configuration
     */
    public function __construct()
    {
        $config = func_get_arg(0);
        if (is_array($config)) {
            $this->setConfig($config);
            parent::__construct(
                $this->config->get('protocol') . '://'
                . $this->config->get('address') . ':'
                . $this->config->get('port') . '/webapi'
            );
        } elseif (is_string($config)) {
            parent::__construct($config);
        }
    }

    /**
     * Suppression du contenu et déconnexion des session Synology ouvertes
     */
    public function __destruct()
    {
        $sids = $this->getSids();
        if ($sids) {
            foreach ($sids as $apiName => $sid) {
                $this->logout($apiName);
            }
        }
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
                ->exec(true, 'API list')
                ->get('items');
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
            ->get('items');

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
            if ($withMethods) {
                $packages[$this->getPackageName($apiName)][] = $this->getMethodName($apiName);
            } else {
                $packages[] = $this->getPackageName($apiName);
            }
        }
        !$withMethods
            ? $packages = array_unique($packages)
            : null;
        return new Items($packages);
    }

    /**
     * Obtenir la liste exhaustive de tous les noms finaux des packages
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getAllApiEndNames()
    {
        $pkgs = $this->getPackages(true);
        $apis = [];
        foreach ($pkgs as $pkgName => $apiNames) {
            foreach ($apiNames as $apiName) {
                $apis[] = $apiName;
            }
        }
        $apis = array_unique($apis);
        sort($apis);
        return new Items($apis);
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
        $packageName = str_replace($this->getPrefixApiName(true), '', $apiName);
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
    public function getApisOfPackage($packageName)
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
        if ($this->hasPackage($packageName) && !array_key_exists($packageName, $this->packages)) {
            $className = 'Rcnchris\Core\Apis\Synology\Packages\\' . $packageName . 'Package';
            if (class_exists($className)) {
                $this->packages[$packageName] = new $className($this);
            } else {
                $this->packages[$packageName] = new SynologyAPIPackage($packageName, $this);
            }
        }
        return $this->packages[$packageName];
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
                "Le format d'authentification $format est incorrect. Merci d'essayer un de ceux-là : "
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
            ->get('sid');

        $this->setSid($apiName, $sid);
        return $sid;
    }

    /**
     * Se déconnecte d'une API
     *
     * @param string $apiName Nom court de l'API (DownloadStation.Task, AudioStation.Album)
     *
     * @return array|bool
     * @throws \Rcnchris\Core\Apis\ApiException
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function logout($apiName)
    {
        $apiName = $this->getApiFullName($apiName);
        $apiShortName = str_replace($this->getPrefixApiName(true), '', $apiName);
        if ($this->getSids($apiName)) {
            $this->setBaseUrl($this->getBaseUrl());
            $authPath = $this->getApiDefinition($apiShortName)
                ->get($this->getPrefixApiName() . '.' . $this->getAuthApiName(), false)
                ->get('path');

            $this
                ->addUrlParts($authPath)
                ->addUrlParams([
                    'api' => $this->getPrefixApiName() . '.' . $this->getAuthApiName(),
                    'version' => 1,
                    'method' => 'logout',
                    'session' => $apiName
                ])
                ->exec(true, "Logout to $apiShortName by " . $this->getConfig()->get('user'));

            unset($this->sids[$apiName]);
            return true;
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
        if ($response instanceof Items) {

            if (is_bool($response->toArray())) {
                return $response->toArray();
            }

            if ($response->get('success') === false) {
                if ($response->has('error')) {
                    $this->errors = $response->get('error');
                }
                $codes = [];
                if ($this->errors->has('code')) {
                    $codes[] = $this->errors->get('code');
                    if ($this->errors->has('errors')) {
                        foreach ($this->errors->get('errors') as $error) {
                            $codes[] = $error['code'];
                        }
                    }
                }
                throw new SynologyException($this->currentPackage, intval(end($codes)));
            }

            if ($response->has('data')) {
                return $response->get('data');
            }
        }
        return $response;
    }


    /**
     * Obtenir la liste de tous des SID obtenus
     * ou uniquement ceux d'un package
     *
     * @param string|null $apiName Nom complet de l'API (SYNO.DownloadStation.Task, SYNO.AudioStation.Album...)
     *
     * @return bool|array|string
     */
    public function getSids($apiName = null)
    {
        if (empty($this->sids)) {
            return false;
        }
        if (is_null($apiName)) {
            return $this->sids;
        } else {
            if (array_key_exists($apiName, $this->sids)) {
                return $this->sids[$apiName];
            }
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
     * Ajouter une instance d'un package dans cette instance
     *
     * @param SynologyAPIPackage $package Instance d'un package Synology (AudioStationPackage, VideoStationPackage...)
     *
     * @return $this
     */
    public function addPackage(SynologyAPIPackage $package)
    {
        $this->packages[$package->getName()] = $package;
        $this->currentPackage = $package;
        return $this;
    }

    /**
     * Obtenir le préfixe du nom de toutes les API
     *
     * @param bool|null $withEndPoint Ajoute un point après le préfixe
     *
     * @return string
     */
    public function getPrefixApiName($withEndPoint = false)
    {
        $parts = explode('.', $this::API_AUTH_NAME);
        $prefix = current($parts);
        if ($withEndPoint) {
            $prefix .= '.';
        }
        return $prefix;
    }

    /**
     * Obtenir la liste des messages d'erreurs Synology
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getErrorMessages()
    {
        $messages = new Items(require_once __DIR__ . '/errors-codes.php');
        return $messages;
    }

    /**
     * Obtenir le nom de l'API d'authentification
     *
     * @return string
     */
    private function getAuthApiName()
    {
        return str_replace($this->getPrefixApiName(true), '', $this::API_AUTH_NAME);
    }

    /**
     * Obtenir toujours le nom de l'API complet
     *
     * @param string $apiName Nom de l'API sous deux formes possibles (SYNO.AudioStation.Album ou AudioStation.Album)
     *
     * @return string
     */
    private function getApiFullName($apiName)
    {
        $withPrefix = strstr($apiName, '.', true) === $this->getPrefixApiName();
        if (!$withPrefix) {
            $apiName = $this->getPrefixApiName(true) . $apiName;
        }
        return $apiName;
    }

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
}
