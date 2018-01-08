<?php
/**
 * Fichier SynologyPackage.php du 07/11/2017
 * Description : Fichier de la classe Package
 *
 * PHP version 7
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

/**
 * Class SynologyPackage<br/>
 * <ul>
 * <li>Classe parente de tous les packages d'un NAS Synology (AudioStation, FileStation...)</li>
 * </ul>
 *
 * @category Synology
 *
 * @package  Rcnchris\Core\Apis\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class SynologyPackage
{

    /**
     * Nom du package
     *
     * @var string
     */
    private $name;

    /**
     * Instance de AbstractAPI
     *
     * @var SynologyAbstract
     */
    private $nas;

    /**
     * Définitions des API de l'instance
     *
     * @var array
     */
    private $definitions = [];

    /**
     * Constructeur
     *
     * @param string                                        $name     Nom du package (API, AudioStation...)
     * @param \Rcnchris\Core\Apis\Synology\SynologyAbstract $abstract Instance de l'abstraction Synology
     *
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function __construct($name, SynologyAbstract $abstract)
    {
        $this->nas = $abstract;
        $this->name = $name;
    }

    /**
     * Fermeture de l'objet
     */
    public function __destruct()
    {
        $this->logout();
        unset($this->definitions);
        unset($this->nas);
    }

    /**
     * Obtenir le nom du package
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Obtenir la liste des APIs du package
     *
     * @param bool $fullName Obtenir le nom complet de l'API
     *
     * @return array
     */
    public function getApis($fullName = false)
    {
        $a = $this->nas;
        $prefix = $a::PREFIXE_API . '.' . $this->getName() . '.';
        $keysForPackage = [];
        $apis = $this->nas->getApis();
        foreach ($apis as $key) {
            $keyParts = explode('.', $key);
            $prefixParts = array_filter(explode('.', $prefix));
            if ($keyParts[0] === $prefixParts[0] && $keyParts[1] === $prefixParts[1]) {
                if ($fullName) {
                    $keysForPackage[] = $key;
                } else {
                    $keysForPackage[] = $keyParts[2];
                }
            }
        }
        return $keysForPackage;
    }

    /**
     * Obtenir la définition de l'API
     *
     * @param string      $apiShortName Nom court de l'API (Genre, Movie...)
     * @param string|null $key          Nom de la clé à retourner
     *
     * @return mixed
     */
    public function getDefinition($apiShortName, $key = null)
    {
        $a = $this->getNas();
        if (!array_key_exists($apiShortName, $this->definitions)) {
            $this->definitions[$apiShortName] = $this
                ->getNas()
                ->getApiDef($a::PREFIXE_API . '.' . $this->getName() . '.' . $apiShortName);
        }
        if ($key) {
            return $this->definitions[$apiShortName][$this->getApiFullName($apiShortName)][$key];
        }
        return $this->definitions[$apiShortName];
    }

    /**
     * Obtenir les données d'une méthode
     *
     * @param string      $apiShortName Nom court de l'API (Genre, Movie...)
     * @param string      $method       Nom de la méthode de l'API (list, getinfo...)
     * @param array|null  $params       Paramètres de la requête
     * @param string|null $key          Nom de la clé à retourner
     *
     * @return array|bool
     * @throws \Rcnchris\Core\Apis\ApiException
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function get($apiShortName, $method = 'list', array $params = [], $key = null)
    {
        $sid = $this->login($apiShortName);
        if ($sid) {
            $a = $this->getNas();
            $apiFullName = $a::PREFIXE_API . '.' . $this->getName() . '.' . $apiShortName;
            $def = $this->getNas()->getApiDef($apiFullName);
            $cgiPath = $def[$apiFullName]['path'];
            $version = $def[$apiFullName]['minVersion'];
            $this->getNas()->setUrl($this->getNas()->getBaseUrl());
            $this->getNas()->addUrlPart($cgiPath);
            $this->getNas()->addParams([
                'api' => $apiFullName
                , 'version' => $version
                , 'method' => $method
                , '_sid' => $sid
            ], null, true);
            $this->getNas()->addParams($params);
            $response = $this->getNas()->r(null, $this->getName() . ' ' . $apiShortName . ' ' . $method);
            $datas = $this->getNas()->parseResponse($response->toArray());
            if ($key) {
                return array_key_exists($key, $datas)
                    ? $datas[$key]
                    : false;
            } else {
                return $datas;
            }
        }
        return false;
    }

    /**
     * Obtenir le nom complet de l'API à partir de son nom court
     * Movie --> SYNO.VideoStation.Movie
     *
     * @param string $apiShortName Nom court de l'API
     *
     * @return string
     */
    private function getApiFullName($apiShortName)
    {
        $a = $this->nas;
        return $a::PREFIXE_API . '.' . $this->getName() . '.' . $apiShortName;
    }

    /**
     * Obtenir un identifiant de connexion pour un package
     *
     * $sid = $this->login($apiShortName);
     *
     * @param string $apiShortName
     * @param string $format ('sid', 'cookie')
     *
     * @return string|null
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    private function login($apiShortName, $format = 'sid')
    {
        $formats = ['sid', 'cookie'];
        if (!in_array($format, $formats)) {
            throw new SynologyException(
                "Le format '$format' n'est pas accepté. Essayez plutôt un de ceux-ci : " . implode(', ', $formats)
            );
        }
        $sid = $this->nas->getSids($this->getName());
        if ($sid) {
            return $sid;
        }
        $this->nas->setUrl($this->nas->getBaseUrl());
        $this->nas->addUrlPart('auth.cgi');
        $this->nas->addParams([
            'api' => 'SYNO.API.Auth'
            , 'version' => 2
            , 'method' => 'login'
            , 'account' => $this->nas->getConfig('user')
            , 'passwd' => $this->nas->getConfig('pwd')
            , 'session' => $this->getName()
            , 'format' => $format
        ], null, true);

        $sid = $this->nas->r(null, 'Login to ' . $this->getName())->toArray();

        if (array_key_exists('success', $sid) && $sid['success'] === true) {
            $this->nas->setSid($this->getName(), $sid['data']['sid']);
            return $sid['data']['sid'];
        }
        return null;
    }

    /**
     * Se déconnecte du package
     *
     * @return bool
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    private function logout()
    {
        $sid = $this->nas->getSids($this->getName());
        if ($sid) {
            $this->nas->setUrl($this->nas->getBaseUrl());
            $this->nas->addUrlPart('auth.cgi');
            $this->nas->addParams([
                'api' => 'SYNO.API.Auth'
                , 'version' => 1
                , 'method' => 'logout'
                , 'session' => $this->getName()
            ], null, true);
            $response = $this->nas->r(null, 'Logout to ' . $this->getName());
            return $response->toArray('success');
        }
        return false;
    }

    /**
     * Obtenir l'instance de l'abstration du NAS
     *
     * @return SynologyAbstract
     */
    public function getNas()
    {
        return $this->nas;
    }
}
