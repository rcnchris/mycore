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
    private $abstract;

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
        $this->abstract = $abstract;
//        if (!$this->abstract->hasPackage($name)) {
//            throw new SynologyException(
//                "Le package $name est introuvable sur " . $this->abstract->getConfig('description')
//            );
//        }
        $this->name = $name;
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
        $a = $this->abstract;
        $prefix = $a::PREFIXE_API . '.' . $this->getName() . '.';
        $keysForPackage = [];
        $apis = $this->abstract->getApis();
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
     * @param string $apiShortName Nom court de l'API (Genre, Movie...)
     *
     * @return mixed
     */
    public function getDefinition($apiShortName)
    {
        $a = $this->abstract;
        return $this->abstract->getApiDef($a::PREFIXE_API . '.' . $this->getName() . '.' . $apiShortName);
    }

    public function get($apiShortName, array $params = [])
    {
        $sid = $this->login($apiShortName);
        return $sid;
    }

    /**
     * Obtenir un identifiant de connexion pour un package
     *
     * @param string $apiShortName
     * @param string $format ('sid', 'cookie')
     *
     * @return array
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    private function login($apiShortName, $format = 'sid')
    {
        $formats = ['sid', 'cookie'];
        if (!in_array($format, $formats)) {
            throw new SynologyException(
                "Le format '$format' n'est pas accepté. Essyez plutôt un de ceux-ci : " . implode(', ', $formats)
            );
        }
        $sid = $this->abstract->getSids($this->getName());
        if ($sid) {
            return $sid;
        }
        $pathAuth = $this->getDefinition($apiShortName)['SYNO.API.Auth']['path'];
        $this->abstract->setCurlUrl($this->abstract->getBaseUrl());
        $this->abstract->addUrlPart($pathAuth);
        $this->abstract->addParams([
            'api' => 'SYNO.API.Auth'
            , 'version' => 2
            , 'method' => 'login'
            , 'account' => $this->abstract->getConfig('user')
            , 'passwd' => $this->abstract->getConfig('pwd')
            , 'session' => $this->getName()
            , 'format' => $format
        ], null, true);
        return $this->abstract->url();
    }
}
