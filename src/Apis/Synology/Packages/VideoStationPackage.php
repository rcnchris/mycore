<?php
/**
 * Fichier VideoStationPackage.php du 12/08/2018
 * Description : Fichier de la classe VideoStationPackage
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology\Packages
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\SynologyAPI;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;

/**
 * Class VideoStationPackage
 *
 * @category API
 *
 * @package  Rcnchris\Core\Apis\Synology\Packages
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class VideoStationPackage extends SynologyAPIPackage
{

    /**
     * Constructeur
     *
     * @param \Rcnchris\Core\Apis\Synology\SynologyAPI $syno
     *
     * @throws \Rcnchris\Core\Apis\Synology\SynologyException
     */
    public function __construct(SynologyAPI $syno)
    {
        parent::__construct('VideoStation', $syno);
    }

    /**
     * Obtenir la liste des collections
     * - `$video->collections()->toArray();`
     * - `$video->collections([], 'name');`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function collections(array $params = [], $extractKey = null)
    {
        return $this->getItems('Collection', 'list', $params, 'collections', $extractKey);
    }

    /**
     * Obtenir les vidéos d'une collection par son identifiant
     * - `$video->videosOfCollection(3)->toArray();`
     * - `$video->videosOfCollection(3, 'title');`
     *
     * @param string      $id         Identifiant de la collection
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function videosOfCollection($id, $extractKey = null)
    {
        return $this->getItems('Collection', 'video_list', compact('id'), 'videos', $extractKey);
    }

    /**
     * Obtenir la liste des films
     * - `$video->movies(['limit' => 10])->toArray();`
     * - `$video->movies(['limit' => 10], 'title');`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function movies(array $params = [], $extractKey = null)
    {
        return $this->getItems('Movie', 'list', $params, 'movies', $extractKey);
    }

    /**
     * Obtenir un film par son identifiant
     * - `$video->movie(292)->toArray();`
     * - `$video->movie(292, true);`
     *
     * @param string|int $id       Identifiant de l'item
     * @param bool|null  $toEntity Retourner une instance de SynologyAPIEntity
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function movie($id, $toEntity = false)
    {
        return $this->getItem($this, 'Movie', 'getinfo', $id, 'movies', $toEntity);
    }

    /**
     * Obtenir la liste des vidéos personnelles
     * - `$video->videos(['limit' => 10])->toArray();`
     * - `$video->videos(['limit' => 10], 'title');`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function videos(array $params = [], $extractKey = null)
    {
        return $this->getItems('HomeVideo', 'list', $params, 'videos', $extractKey);
    }

    /**
     * Obtenir une vidéo personnelle par son identifiant
     * - `$video->video(7)->toArray();`
     * - `$video->video(7, true);`
     *
     * @param string|int $id       Identifiant de l'item
     * @param bool|null  $toEntity Retourner une instance de SynologyAPIEntity
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function video($id, $toEntity = false)
    {
        return $this->getItem($this, 'HomeVideo', 'getinfo', $id, 'videos', $toEntity);
    }

    /**
     * Obtenir la liste des séries TV
     * - `$video->tvshows(['limit' => 10])->toArray();`
     * - `$video->tvshows(['limit' => 10], 'title');`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function tvshows(array $params = [], $extractKey = null)
    {
        return $this->getItems('TVShow', 'list', $params, 'tvshows', $extractKey);
    }

    /**
     * Obtenir une série TV
     * - `$video->tvshow(57)->toArray();`
     * - `$video->tvshow(57, true);`
     *
     * @param string|int $id       Identifiant de l'item
     * @param bool|null  $toEntity Retourner une instance de SynologyAPIEntity
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function tvshow($id, $toEntity = false)
    {
        return $this->getItem($this, 'TVShow', 'getinfo', $id, 'tvshows', $toEntity);
    }

    /**
     * Obtenir la liste des épisodes
     * - `$video->episodes(['limit' => 10])->toArray();`
     * - `$video->episodes(['limit' => 10], 'tagline');`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function episodes(array $params = [], $extractKey = null)
    {
        return $this->getItems('TVShowEpisode', 'list', $params, 'episodes', $extractKey);
    }

    /**
     * Obtenir un épisode
     * - `$video->episode(644)->toArray();`
     * - `$video->episode(644, true);`
     *
     * @param string|int $id       Identifiant de l'item
     * @param bool|null  $toEntity Retourner une instance de SynologyAPIEntity
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function episode($id, $toEntity = false)
    {
        return $this->getItem($this, 'TVShowEpisode', 'getinfo', $id, 'episodes', $toEntity);
    }
}
