<?php
/**
 * Fichier AbstractSynology.php du 30/12/2017
 * Description : Fichier de la classe AbstractSynology
 *
 * PHP version 5
 *
 * @category New
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
 * Class AbstractSynology
 *
 * @category New
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
class AbstractSynology extends OneAPI
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
     * Constructeur
     *
     * @param array $config Configuration de connexion au NAS
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        if (!$this->parseConfig($config)) {
            throw new \Exception("Configuration incomplète !");
        }
        $this->config = $config;
        $this->initialize($config['protocol'] . '://' . $config['address'] . ':' . $config['port'] . '/webapi');
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
     * Suppression du contenu
     */
    public function __destruct()
    {
        unset($this->config);
    }
}
