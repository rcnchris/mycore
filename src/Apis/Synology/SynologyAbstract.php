<?php
/**
 * Fichier SynologyAbstract.php du 30/12/2017
 * Description : Fichier de la classe AbstractSynology
 *
 * PHP version 5
 *
 * @category Synology
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

use Rcnchris\Core\Apis\OneAPI;

/**
 * Class SynologyAbstract
 * <ul>
 * <li>Abstraction des API d'un NAS Synology</li>
 * </ul>
 *
 * @category Synology
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @version  Release: <1.0.0>
 */
class SynologyAbstract extends OneAPI
{

    /**
     * Configuration de connexion au NAS
     *
     * @var array
     */
    private $config;

    /**
     * Préfixe du nom des APIs
     *
     * @const string
     */
    const PREFIXE_API = 'SYNO';

    /**
     * Clés de configuration attendues
     *
     * @var array
     */
    private $configKeys = [
        'address' => '',
        'port' => 0,
        'protocol' => '',
        'version' => 0,
        'ssl' => false,
        'user' => '',
        'pwd' => ''
    ];

    /**
     * Liste des identifiants obtenus grâce à login()
     *
     * @var array
     */
    private $sids = [];

    /**
     * Constructeur
     *
     * @param array $config Configuration de connexion au NAS
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        if (!$this->parseConfig($config) || empty($config)) {
            throw new \Exception("Configuration incomplète !");
        }
        $this->config = $config;
        $this->initialize($config['protocol'] . '://' . $config['address'] . ':' . $config['port'] . '/webapi');
        $this->setCurlOptions($this->curlOptions);
    }

    /**
     * Suppression du contenu
     */
    public function __destruct()
    {
        unset($this->config);
    }

    /**
     * Obtenir l'URL de base de toutes les API du NAS
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->getConfig('protocol')
        . '://' . $this->getConfig('address')
        . ':' . $this->getConfig('port')
        . '/webapi';
    }

    /**
     * Obtenir la configuration de connexion au NAS
     *
     * @param string|null $key Nom du paramètre à retourner
     *
     * @return array
     */
    public function getConfig($key = null)
    {
        if (is_null($key)) {
            return $this->config;
        } elseif (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return [];
    }

    /**
     * Obtenir la liste des apis disponibles sur le NAS
     *
     * @return array|mixed
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function getApis()
    {
        $this->setUrl($this->getBaseUrl());
        $this->addUrlPart('query.cgi');
        $this->addParams([
            'api' => $this::PREFIXE_API . '.API.Info'
            , 'version' => 1
            , 'method' => 'query'
            , 'query' => 'all'
        ], null, true);
        return array_keys($this->parseResponse($this->r(null, 'API List')->toArray()));
    }

    /**
     * Obtenir la définition d'une API du NAS par son nom
     *
     * @param string $apiName Nom de l'API (SYNO.API.Info, SYNO.AudioStation.Song)
     * @param string $key     Nom de la clé de la définition (path, minVersion, maxVersion...)
     *
     * @return mixed
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function getApiDef($apiName, $key = null)
    {
        $this->setUrl($this->getBaseUrl());
        $this->addUrlPart('query.cgi');
        $this->addParams([
            'api' => $this::PREFIXE_API . '.API.Info'
            , 'version' => 1
            , 'method' => 'query'
            , 'query' => "SYNO.API.Auth,$apiName"
        ], null, true);

        $response = $this->parseResponse($this->r(null, 'API Def : ' . $apiName)->toArray());
        if (array_key_exists($apiName, $response)) {
            if (is_null($key)) {
                return $response;
            } elseif (array_key_exists($key, $response[$apiName])) {
                return $response[$apiName][$key];
            }
        }
        return false;
    }

    /**
     * Vérifie la présence d'une API
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasApi($name)
    {
        return in_array($name, $this->getApis());
    }

    /**
     * Vérifie la présence d'un package
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasPackage($name)
    {
        return in_array($name, $this->getPackages());
    }

    /**
     * Obtenir la liste de tous les packages
     *
     * @return array
     */
    public function getPackages()
    {
        $packages = [];
        foreach ($this->getApis() as $api) {
            $apiName = str_replace($this::PREFIXE_API . '.', '', $api);
            $apiName = explode('.', $apiName)[0];
            $packages[] = $apiName;
        }
        $packages = array_unique($packages);
        return $packages;
    }

    /**
     * Obtenir l'instance d'un package
     *
     * @param string $name Nom du package (AudioStation, FileStation...)
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyPackage
     */
    public function getPackage($name)
    {
        return new SynologyPackage($name, $this);
    }

    /**
     * Obtenir la liste de tous des SID obtenus
     * ou uniquement ceux d'un package
     *
     * @param string|null $packageName Nom du package
     *
     * @return array
     */
    public function getSids($packageName = null)
    {
        if (is_null($packageName)) {
            return $this->sids;
        } elseif (array_key_exists($packageName, $this->sids)) {
            return $this->sids[$packageName];
        }
        return false;
    }

    /**
     * Définir un SID pour un package
     *
     * @param string $packageName Nom du package
     * @param string $sid         SID obtenu
     */
    public function setSid($packageName, $sid)
    {
        $this->sids[$packageName] = $sid;
    }

    /**
     * Vérifie que la configuration est valide
     *
     * @param array $config
     *
     * @return bool
     */
    private function parseConfig(array $config)
    {
        if (!empty($config)) {
            $diff = array_diff(array_keys($this->configKeys), array_keys($config));
            if (!empty($diff)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Vérifie la réponse du NAS
     * - Retourne le contenu de la clé data dans le cas d'un succès
     * - Sinon retourne le code erreur Synology
     *
     * @param array $response
     *
     * @return mixed
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function parseResponse(array $response)
    {
        if (array_key_exists('error', $response)) {
            throw new SynologyException('', $response['error']['code']);
        }
        return $response['data'];
    }
}
