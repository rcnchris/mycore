<?php
/**
 * Fichier SynologyAPIPackage.php du 09/08/2018
 * Description : Fichier de la classe SynologyAPIPackage
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

/**
 * Class SynologyAPIPackage
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
class SynologyAPIPackage
{

    /**
     * Nom du package (AudioStation, DownloadStation...)
     *
     * @var string
     */
    private $name;

    /**
     * API Synology
     *
     * @var SynologyAPI
     */
    private $api;

    /**
     * Texte de l'icône du package
     *
     * @var string|null
     */
    private $icon = null;

    /**
     * Constructeur
     * Définit le nom du package et l'instance de l'API
     *
     * @param string                                   $packageName Nom du package à instancier
     * @param \Rcnchris\Core\Apis\Synology\SynologyAPI $api         API de Synology
     *
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function __construct($packageName, SynologyAPI $api)
    {
        $this->api = $api;
        if (!$this->api->hasPackage((string)$packageName)) {
            throw new SynologyException("Le package $packageName n'existe pas pour l'API " . get_class($api));
        }
        $this->setName($packageName);
        $this->api->setCurrentPackage($this);
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
     * Définir le nom du package
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Obtenir la définition d'une méthode
     *
     * @param string $methodName Nom de la méthode d'un package
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function getDefinition($methodName)
    {
        return $this->api->getApiDefinition($this->getName() . '.' . $methodName);
    }

    /**
     * Obtenir la liste des méthodes du package
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getMethods()
    {
        return $this->api->getMethodsOfPackage($this->getName());
    }

    /**
     * Obtenir le texte de l'icône du package
     *
     * @return null|string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Définir le texte de l'icône du package
     *
     * @param string $icon Texte de l'icône du package
     *
     * @return self
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Effectuer une requête sur le package à partir d'une API et d'une méthode
     *
     * @param string      $apiEndName Nom de final de l'API à utilier au sein du package (Task, Album, Info...)
     * @param string|null $method     Nom de la méthode de l'API (list, query...)
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $key        Nom de la clé à retourner au sein de la réponse
     *
     * @return null|\Rcnchris\Core\Tools\Items|bool
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function request($apiEndName, $method = 'list', array $params = [], $key = null)
    {
        $apiShortName = $this->getName() . '.' . $apiEndName;
        $sid = $this->api->login($apiShortName);
        $definition = $this->getDefinition($apiEndName);
        $apiPath = $definition->get($this->api->getPrefixApiName() . '.' . $apiShortName, false)->get('path');
        $apiVersion = $definition->get($this->api->getPrefixApiName() . '.' . $apiShortName, false)->get('minVersion');

        $response = $this->api
            ->addUrlParts($apiPath, true)
            ->addUrlParams([
                'api' => $this->api->getPrefixApiName() . '.' . $apiShortName,
                'version' => $apiVersion,
                'method' => $method,
                '_sid' => $sid
            ], true)
            ->addUrlParams($params)
            ->exec(true, $apiShortName . ' ' . $method)
            ->get('items');

        /**
         * Clé de la réponse à retourner
         */
        return $key
            ? $response->get($key)
            : $response;
    }

    /**
     * Obtenir la configuration du package courant
     *
     * @param string $apiEndName
     * @param string $method
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function config($apiEndName = 'Info', $method = 'getinfo')
    {
        return $this->getItems($apiEndName, $method);
    }

    /**
     * Obtenir la version du package
     * - `$api->getVersion();`
     *
     * @return string
     */
    public function getVersion()
    {
        $version = null;
        if ($this->getName() === 'DownloadStation') {
            $version = $this->request('Info', 'getinfo', [], 'version_string');
        } elseif ($this->getName() === 'AudioStation') {
            $parts = $this->request('Info', 'getinfo', [], 'version')->toArray();
            $version = $parts['major'] . '.' . $parts['minor'] . '-' . $parts['build'];
        } elseif ($this->getName() === 'VideoStation') {
            $version = $this->request('Info', 'getinfo', [], 'version_string');
        }
        return $version;
    }

    /**
     * Obtenir les items d'une pour une API et une méthode
     *
     * @param string      $apiEndName Partie finale du nom de l'API (Album, Task, Movie...)
     * @param string      $method     Nom de la méthode à utiliser
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé d'un item à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    protected function getItems($apiEndName, $method, array $params = [], $itemsKey = null, $extractKey = null)
    {
        $response = $this->request($apiEndName, $method, $params);
        if (!is_null($extractKey)) {
            return $response->get($itemsKey)->extract($extractKey, 'id')->toArray();
        }
        return $response;
    }

    /**
     * Obtenir un item à partir d'un package
     *
     * @param \Rcnchris\Core\Apis\Synology\SynologyAPIPackage $package    Package de l'item
     * @param string                                          $apiEndName Nom de la partie finale de l'API (Album,
     *                                                                    Task, Movie...)
     * @param string                                          $method     Nom de la méthode de l'API
     * @param string|int                                      $id         Identifiant de l'item
     * @param string                                          $itemsKey   Nom de la clé qui contient les items
     * @param bool|null                                       $toEntity   Retourne une entité Synology
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    protected function getItem(SynologyAPIPackage $package, $apiEndName, $method, $id, $itemsKey, $toEntity = false)
    {
        $response = $this->request($apiEndName, $method, compact('id'))->get($itemsKey)->first();
        if ($toEntity) {
            return new SynologyAPIEntity($package, $response->toArray());
        }
        return $response;
    }

    /**
     * Obtenir l'instance de l'API Synology
     *
     * @return SynologyAPI
     */
    public function getApi()
    {
        return $this->api;
    }
}
