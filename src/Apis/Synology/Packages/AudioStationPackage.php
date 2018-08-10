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
    public function __construct(SynologyAPI $syno)
    {
        parent::__construct('AudioStation', $syno);
    }

    /**
     * Obtenir la listes des albums
     *
     * - `$audio->albums()->toArray();`
     * - `$audio->albums('IAM', ['limit' => 10])->toArray();`
     *
     * @param string|null $artist Nom d'un artiste
     * @param array|null  $params Paramètres de la requête
     *                            - limit
     *                            - sort_by
     *                            - sort_direction (asc|desc)
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function albums($artist = null, array $params = [])
    {
        return $this->request('Album', 'list', array_merge($params, compact('artist')));
    }

    /**
     * Obtenir les listes de lectures
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function playlists()
    {
        return $this->request('Playlist', 'list');
    }

    /**
     * Chercher un morceau à partir de son titre
     *
     * @param string     $title  Titre du morceau
     * @param array|null $params Paramètres de la requête
     *                            - limit
     *                            - offset
     *                            - sort_by
     *                            - sort_direction (asc|desc)
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function searchSong($title, array $params = [])
    {
        return $this->request('Song', 'search', array_merge($params, compact('title')));
    }
}
