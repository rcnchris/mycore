<?php
/**
 * Fichier AudioStationPackage.php du 10/08/2018
 * Description : Fichier de la classe AudioStationPackage
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
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;

/**
 * Class AudioStationPackage
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
class AudioStationPackage extends SynologyAPIPackage
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
        parent::__construct('AudioStation', $syno);
    }

    /**
     * Obtenir la liste des albums ou ceux d'un artiste
     * - `$audio->albums(null, ['limit' => 10])->toArray();`
     * - `$audio->albums('IAM', ['limit' => 10])->toArray();`
     * - `$audio->albums('IAM', ['limit' => 10], 'name');`
     *
     * @param string|null $artist     Nom de l'artiste
     * @param array       $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function albums($artist = null, array $params = [], $extractKey = null)
    {
        return $this->getItems('Album', 'list', array_merge($params, compact('artist')), 'albums', $extractKey);
    }

    /**
     * Obtenir la listes des artistes ou l'un d'entre eux
     * - `$audio->artists()->toArray();`
     * - `$audio->artists(['limit' => 10], 'name');`
     *
     * @param array $params
     * @param null  $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function artists(array $params = [], $extractKey = null)
    {
        return $this->getItems('Artist', 'list', $params, 'artists', $extractKey);
    }

    /**
     * Obtenir la liste des compositeurs
     * - `$audio->composers(['limit' => 10])->toArray();`
     * - `$audio->composers(['limit' => 10], 'name');`
     *
     * @param array|null  $params Paramètres de la requête
     * @param string|null $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function composers(array $params = [], $extractKey = null)
    {
        return $this->getItems('Composer', 'list', $params, 'composers', $extractKey);
    }

    /**
     * Obtenir la liste des genres
     * - `$audio->genres(['limit' => 10])->toArray();`
     * - `$audio->genres(['limit' => 10], 'name');`
     *
     * @param array $params
     * @param null  $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function genres(array $params = [], $extractKey = null)
    {
        return $this->getItems('Genre', 'list', $params, 'genres', $extractKey);
    }

    /**
     * Obtenir la liste des genres
     * - `$audio->genres(['limit' => 10])->toArray();`
     * - `$audio->genres(['limit' => 10], 'name');`
     *
     * @param array $params
     * @param null  $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function folders(array $params = [], $extractKey = null)
    {
        return $this->getItems('Folder', 'list', $params, 'items', $extractKey);
    }

    /**
     * Obtenir un dossier par son identifiant
     * - `$audio->folder('dir_24')->toArray();`
     * - `$audio->folder('dir_24', true);`
     *
     * @param string    $id       Identifiant du dossier
     * @param bool|true $toEntity Retourne l'instance d'un entité Synology
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function folder($id, $toEntity = false)
    {
        return $this->getItem($this, 'Folder', 'getinfo', $id, 'items', $toEntity);
    }

    /**
     * Obtenir les paroles d'une chanson
     *
     * @param string $id Identifiant du morceau
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function lyricsOfSong($id)
    {
        return $this->request('Lyrics', 'getlyrics', compact('id'))->get('lyrics');
    }

    /**
     * Obtenir les listes de lectures
     * - `$audio->playlists()->toArray();`
     * - `$audio->playlists('name');`
     *
     * @param bool $extractKey
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function playlists($extractKey = null)
    {
        return $this->getItems('Playlist', 'list', [], 'playlists', $extractKey);
    }

    /**
     * Obtenir un playlist par son identifiant
     * - `$audio->playlist('playlist_shared_normal/346')->toArray();`
     * - `$audio->playlist('playlist_shared_normal/346', true);`
     *
     * @param string $id
     * @param bool   $toEntity
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function playlist($id, $toEntity = false)
    {
        return $this->getItem($this, 'Playlist', 'getinfo', $id, 'playlists', $toEntity);
    }

    /**
     * Obtenir la liste des radios
     * - `$audio->radios()->toArray();`
     * - `$audio->radios('title');`
     *
     * @param bool $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function radios($extractKey = null)
    {
        return $this->getItems('Radio', 'list', [], 'radios', $extractKey);
    }

    /**
     * Obtenir la liste des lecteurs distants
     * - `$audio->remotes()->toArray();`
     * - `$audio->remotes('name');`
     *
     * @param string $extractKey
     *
     * @return array|null|\Rcnchris\Core\Tools\Items
     */
    public function remotes($extractKey = null)
    {
        return $this->getItems('RemotePlayer', 'list', [], 'players', $extractKey);
    }

    /**
     * Obtenir un lecteur distant
     * - `$audio->remote('F4CAE55B33A0')->toArray();`
     * - `$audio->remote('F4CAE55B33A0', true);`
     *
     * @param string    $id
     * @param bool|null $toEntity
     *
     * @return bool|null|\Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function remote($id, $toEntity = false)
    {
        $response = $this->request('RemotePlayer', 'getstatus', compact('id'));
        return $toEntity
            ? new SynologyAPIEntity($this, $response->toArray())
            : $response;
    }

    /**
     * Obtenir la playlist d'un lecteur distants
     * - `$audio->remotePlaylist('F4CAE55B33A0')->toArray();`
     * - `$audio->remotePlaylist('F4CAE55B33A0', true);`
     *
     * @param      $id
     * @param bool $toEntity
     *
     * @return bool|null|\Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function remotePlaylist($id, $toEntity = false)
    {
        $response = $this->request('RemotePlayer', 'getplaylist', compact('id'));
        return $toEntity
            ? new SynologyAPIEntity($this, $response->toArray())
            : $response;
    }

    /**
     * Obtenir la liste des serveurs multimédia
     * - `$audio->servers()->toArray();`
     * - `$audio->servers([], 'title')`
     *
     * @param array  $params
     * @param string $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function servers(array $params = [], $extractKey = null)
    {
        return $this->getItems('MediaServer', 'list', $params, 'list', $extractKey);
    }

    /**
     * Obtenir la liste des morceaux
     * - `$audio->songs(['limit' => 10])->toArray();`
     * - `$audio->songs(['limit' => 10], 'title');`
     *
     * @param array  $params
     * @param string $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function songs(array $params = [], $extractKey = null)
    {
        return $this->getItems('Song', 'list', $params, 'songs', $extractKey);
    }

    /**
     * Obtenir un morceau par son identifiant
     * - `$audio->song('music_v_77900')->toArray();`
     * - `$audio->song('music_v_77900', true);`
     *
     * @param string $id
     * @param bool   $toEntity
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function song($id, $toEntity = false)
    {
        return $this->getItem($this, 'Song', 'getinfo', $id, 'songs', $toEntity);
    }

    /**
     * Chercher un morceau à partir de son titre
     * - `$audio->searchSongs('u-turn')->toArray();`
     * - `$audio->searchSongs('u-turn', [], 'title');`
     *
     * @param string      $title  Titre du morceau
     * @param array|null  $params Paramètres de la requête
     *                            - limit
     *                            - offset
     *                            - sort_by
     *                            - sort_direction (asc|desc)
     * @param string|null $extractKey
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function searchSongs($title, array $params = [], $extractKey = null)
    {
        return $this->getItems('Song', 'search', array_merge($params, compact('title')), 'songs', $extractKey);
    }
}
