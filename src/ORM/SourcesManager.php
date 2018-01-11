<?php
/**
 * Fichier SourcesManager.php du 21/12/2017
 * Description : Fichier de la classe SourcesManager
 *
 * PHP version 5
 *
 * @category Bases de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\ORM;

/**
 * Class SourcesManager<br/>
 * <ul>
 * <li>Responsable de la fourniture des configurations des sources de données</li>
 * </ul>
 *
 * @category Bases de données
 *
 * @package  Rcnchris\Core\ORM
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris/fmk-php GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris/fmk-php on Github
 */
class SourcesManager
{

    /**
     * Liste de toutes les sources de données
     * sous la forme key => [config]
     *
     * @var array
     */
    private $sources = [];

    /**
     * Constructeur
     *
     * @param array $sources
     */
    public function __construct(array $sources)
    {
        if (!empty($sources)) {
            $this->sources = $sources;
        }
    }

    /**
     * Obtenir les sources de données ou l'une d'entre elle
     *
     * @param string|null $key Nom de la clé
     *
     * @return array|bool
     */
    public function getSources($key = null)
    {
        if (is_null($key)) {
            return $this->sources;
        } elseif ($this->has($key)) {
            return $this->sources[$key];
        }
        return false;
    }

    /**
     * Définit les sources de données
     *
     * @param array $sources Liste des sources de données avec leur configuration
     * @param bool  $add Si vrai, les sources sont ajoutées à celles existantes
     */
    public function setSources($sources, $add = false)
    {
        if ($add) {
            $sources = array_merge($this->sources, $sources);
        }
        $this->sources = $sources;
    }

    /**
     * Vérifie la présence d'une clé dans les source
     *
     * @param string $key Nom de la clé
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->sources);
    }

    /**
     * Obtenir une instance PDO à partir d'une clé
     *
     * @param string|null $key Nom de ma clé
     *
     * @return null|\PDO|string
     * @throws \Exception
     */
    public function connect($key = null)
    {
        if (is_null($key)) {
            $key = 'default';
        }
        $config = $this->getSources($key);
        return DbFactory::get(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password'],
            $config['dbName'],
            $config['sgbd']
        );
    }

    /**
     * Obtenir une source de données par son nom
     *
     * @param string $key Nom d'une source de données
     *
     * @return array|bool
     */
    public function __get($key)
    {
        return array_key_exists($key, $this->sources)
            ? $this->sources[$key]
            : false;
    }
}
