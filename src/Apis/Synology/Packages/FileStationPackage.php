<?php
/**
 * Fichier FileStationPackage.php du 17/08/2018
 * Description : Fichier de la classe FileStationPackage
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
 * Class FileStationPackage
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
class FileStationPackage extends SynologyAPIPackage
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
        parent::__construct('FileStation', $syno);
    }

    /**
     * Obtenir la liste des liens partagés
     * - `$pkg->sharings();`
     * - `$pkg->sharings(['limit' => 10], 'name');`
     *
     * @param array|null  $params     Paramètres de la requête
     *                                - version 3
     *                                - offset
     *                                - limit
     *                                - sort_by
     *                                - sort_direction (asc ou desc)
     *                                - force_clean
     * @param string|null $extractKey Nom de la clé de la réponse à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function sharings(array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'version' => 3,
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'force_clean' => 'false'
        ], $params);
        return $this->getItems('Sharing', 'list', $params, 'links', $extractKey);
    }

    /**
     * Obtenir un lien partagé par son identifiant
     *
     * @param string    $id       Identifiant du lien partagé
     * @param bool|null $toEntity Retourne une Entity plutôt que des Items
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAPIEntity|\Rcnchris\Core\Tools\Items
     */
    public function sharing($id, $toEntity = false)
    {
        return $this->getItem($this, 'Sharing', 'getinfo', $id, null, $toEntity);
    }

    /**
     * Créer un lien partagé à partir d'un chemin
     *
     * @param string     $path   Chemin du dossier ou fichier
     * @param array|null $params Paramètres de la requêtes
     *                           - version 3
     *                           - path
     *                           - password
     *                           - date_expired (YYYY-MM-DD)
     *                           - date_available (YYYY-MM-DD)
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function createSharing($path, array $params = [])
    {
        $params = array_merge([
            'version' => 3,
            'path' => $path,
            //'password' => '',
            'date_expired' => 0,
            'date_available' => 0
        ], $params);
        return $this->request('Sharing', 'create', $params);
    }

    /**
     * Supprimer un lien partagé à partir de son identifiant
     *
     * @param string $id Identifiant du lien partagé à supprimer
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function deleteSharing($id)
    {
        return $this->request('Sharing', 'delete', compact('id'));
    }

    /**
     * Supprimer tous les liens expirés
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function clearSharing()
    {
        return $this->request('Sharing', 'clear_invalid', ['version' => 3]);
    }

    /**
     * Obtenir les dossiers partagés
     *
     * @param array|null  $params       Paramètres de la requête
     *                                  <ul>
     *                                  <li>offset</li>
     *                                  <li>limit</li>
     *                                  <li>sort_by</li>
     *                                  <li>sort_direction</li>
     *                                  <li>onlywritable</li>
     *                                  <li>additional
     *                                  (real_path,owner,time,perm,mount_point,_type,sync_share,volume_status)</li>
     *                                  </ul>
     *
     * @param string|null $extractKey   Nom de la clé à extraire parmi les items
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function sharedFolders(array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'onlywritable' => 'false',
            'additional' => 'volume_status',
        ], $params);
        return $this->getItems('List', 'list_share', $params, 'shares', $extractKey);
    }

    public function sharedFolderFiles($folderPath, array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'version' => 2,
            'folder_path' => $folderPath,
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            //'pattern' => '',
            'filetype' => 'all',
            //'goto_path' => '',
            'additional' => 'size'
        ], $params);
        return $this->getItems('List', 'list', $params, 'files', $extractKey);
    }

    /**
     * Chercher un terme dans un dossier partagé
     * - `$pkg->search('/Download/Tests', 'chevrolet');`
     * - `$pkg->search('/Download/Tests', null, ['extension' => 'jpg']);`
     *
     * @param string     $folderPath Chemin de départ de la recherche
     * @param string     $pattern    Terme recherché
     * @param array|null $params     Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function search($folderPath, $pattern = null, array $params = [])
    {
        $apiEndName = 'Search';
        $params = array_merge([
            'version' => 2,
            'folder_path' => $folderPath,
            'recursive' => 'true',
            'pattern' => $pattern,
            'extension' => null,
            'filetype' => 'all',
            // 'size_from' => 0,
            // 'size_to' => 0,
            // 'mtime_from' => 0,
            // 'mtime_to' => 0,
            // 'crtime_from' => 0,
            // 'crtime_to' => 0,
            // 'atime_from' => 0,
            // 'atime_to' => 0,
            // 'owner' => '',
            // 'group' => ''
        ], $params);
        $taskid = $this->startTask($apiEndName, $params);

        $params = array_merge([
            'version' => 2,
            'taskid' => $taskid,
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            // 'pattern' => '',
            'filetype' => 'all',
            'additional' => 'size',
        ], $params);
        $response = $this->request($apiEndName, 'list', $params);
        $this->stopTask($taskid, $apiEndName, true);
        return $response;
    }

    /**
     * Obte,nir la liste des montages virtuels
     * - `$pkg->virtualFolders();`
     *
     * @param array|null  $params     Paramètres de la requête
     *                                - type (nfs, cifs ou iso)
     *                                - offset
     *                                - limit
     *                                - sort_by
     *                                - sort_direction
     *                                - additional
     * @param string|null $extractKey Nom de la clé de la réponse à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function virtualFolders(array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'type' => 'iso',
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'additional' => 'volume_status'
        ], $params);
        return $this->getItems('VirtualFolder', 'list', $params, 'folders', $extractKey);
    }

    /**
     * Obtenir la liste des favoris
     * - `$pkg->favorites();`
     *
     * @param array|null  $params     Paramètres de la requête
     *                                - offset
     *                                - limit
     *                                - status_filter (valid, broken ou all)
     *                                - additional (name,size,user,group,mtime,atime,ctime,crtime,posix,type)
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function favorites(array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0,
            'status_filter' => 'all',
            // 'additional' => 'type'
        ], $params);
        return $this->getItems('Favorite', 'list', $params, 'favorites', $extractKey);
    }

    /**
     * Obtenir un favori par son chemin
     *
     * @param string    $path     Chemin du favori à trouver
     * @param bool|null $toEntity Retourner une Entity plutot que Items
     *
     * @return mixed|null|\Rcnchris\Core\Apis\Synology\SynologyAPIEntity
     */
    public function favorite($path, $toEntity = false)
    {
        $favorites = $this->favorites();
        $favorite = $favorites
            ->get('favorites')
            ->toArray(function ($favorite) use ($path) {
                if ($favorite['path'] === $path) {
                    return $favorite;
                }
                return null;
            });
//        if (empty($favorite) or !is_array($favorite)) {
//            return null;
//        }
        if ($toEntity) {
            return new SynologyAPIEntity($this, current($favorite));
        }
        return current($favorite);
    }

    /**
     * Ajouter un favori
     * - `$pkg->addFavorite('/music', 'Musique');`
     *
     * @param string $path  Chemin du favori
     * @param string $name  Nom du favori
     * @param int    $index Position dans la liste
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function addFavorite($path, $name, $index = -1)
    {
        return $this->request('Favorite', 'add', compact('path', 'name', 'index'));
    }

    /**
     * Supprimer un favori
     *
     * @param string $path Chemin du favori
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function deleteFavorite($path)
    {
        return $this->request('Favorite', 'delete', compact('path'));
    }

    /**
     * Modifier le nom d'un favori
     *
     * @param string $path Chemin du favori
     * @param string $name Nouveau nom du favori
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function editFavorite($path, $name)
    {
        return $this->request('Favorite', 'edit', compact('path', 'name'));
    }

    /**
     * Supprimer les favoris invalides
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function clearFavorites()
    {
        return $this->request('Favorite', 'clear_broken');
    }

    /**
     * Obtenir l'URL d'une image
     * - `$fs->thumb('/Commun/chevrolet.jpg');`
     * - `$fs->thumb('/Commun/chevrolet.jpg', 'medium', 1);`
     *
     * @param string      $path   Chemin de l'image
     * @param string|null $size   Taille de l'image (small, medium, original ou large)
     * @param int|null    $rotate Rotation de l'image
     *                            - 0 Pas de rotation
     *                            - 1 90°
     *                            - 2 180°
     *                            - 3 270°
     *                            - 4 360°
     *
     * @return string
     */
    public function thumb($path, $size = 'original', $rotate = 0)
    {
        $version = 2;
        return $this->makeUrl('Thumb', 'get', compact('version', 'path', 'size', 'rotate'));
    }

    /**
     * Obtenir la taille d'un fichier/dossier
     * - `$pkg->size('/Commun/chevrolet.jpg');`
     *
     * @param string $path Chemin du fichier/dossier
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function size($path)
    {
        $apiEndName = 'DirSize';
        $version = 2;
        $taskid = $this->startTask($apiEndName, compact('version', 'path'));
        $response = $this->request($apiEndName, 'status', compact('version', 'taskid'));
        $this->stopTask($taskid, $apiEndName);
        return $response;
    }

    /**
     * Obtenir le hash d'un fichier au format MD5
     * - `$pkg->md5File('/Commun/chevrolet.jpg')`
     *
     * @param string $file_path Chemin du fichier
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function md5File($file_path)
    {
        $apiEndName = 'MD5';
        $version = 2;
        $taskid = $this->startTask($apiEndName, compact('version', 'file_path'));
        $response = $this->request($apiEndName, 'status', compact('taskid'));
        $this->stopTask($taskid, $apiEndName);
        return $response;
    }

    /**
     * Obtenir les permissions pour un dossier et un nom de fichier
     * - `$pkg->checkPerm('/Commun', 'fake.txt');`
     *
     * @param string $path     Chemin du dossier à vérifier
     * @param string $filename Nom du fichier à vérifier
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function checkPerm($path, $filename)
    {
        $apiEndName = 'CheckPermission';
        $version = 3;
        return $this->request($apiEndName, 'write', compact('version', 'path', 'filename'));
    }

    /**
     * Copier un fichier dans un répertoire partagé
     * à tester en post.
     * Notez que chaque paramètre est passé dans chaque partie mais
     * que les données du fichier binaire doivent être la dernière partie.
     *
     * @param mixed      $src    Contenu du fichier au format binaire
     * @param string     $dest   Un chemin de dossier de destination commençant par un dossier partagé dans lequel les
     *                           fichiers peuvent être téléchargés.
     * @param array|null $params Paramètres de la requête
     *                           - create_parents : Créez un ou plusieurs dossiers parents s'il n'en existe pas.
     *                           - overwrite (true: remplace le fichier de destination s'il en existe un, false: ignore
     *                           le téléchargement si le fichier de destination existe et lorsqu'il n'est pas spécifié
     *                           comme vrai ou faux, le téléchargement sera traité avec une erreur lorsque le fichier
     *                           de destination existe)
     *                           - mtime : Définir la dernière heure de modification du fichier téléchargé. Timestamp Linux en milliseconde.
     *                           - crtime : Définissez l'heure de création du fichier téléchargé. Timestamp Linux en milliseconde.
     *                           - atime : Définir la dernière heure d'accès du fichier téléchargé. Timestamp Linux en milliseconde.
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function uploadFile($src, $dest, array $params = [])
    {
        $params = array_merge([
            'version' => 2,
            'path' => $dest,
            'create_parents' => 'false',
            'overwrite' => 'true',
//            'mtime' => 0,
//            'crtime' => 0,
//            'atime' => 0,
            'filename' => $src
        ], $params);
        return $this->request('Upload', 'upload', $params);
    }

    /**
     * Télécharger un fichier/dossier
     * à tester
     *
     * @param string $path Chemin du fichier/dossier à télécharger
     * @param string $mode (open ou download)
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function download($path, $mode = 'download')
    {
        $apiEndName = 'Download';
        $version = 2;
        return $this->request($apiEndName, 'download', compact('version', 'path', 'mode'));
    }

    /**
     * Créer un dossier dans un dossier partagé
     *
     * @param string     $folder_path Chemin du dossier partagé
     * @param string     $name        Nom du dossier à créer
     * @param array|null $params      Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function createFolder($folder_path, $name, array $params = [])
    {
        $params = array_merge([
            'version' => 2,
            'folder_path' => $folder_path,
            'name' => $name,
            'force_parent' => 'false',
            'additional' => 'real_path,size,owner,time,perm,type'
        ], $params);
        return $this->request('CreateFolder', 'create', $params);
    }

    /**
     * Renommer un fichier/dossier
     * - `$pkg->rename('/Commun/chevrolet.jpg', 'chevroletSS.jpg');`
     *
     * @param string     $path   Chemin du fichier/dossier à renommer
     * @param string     $name   Nouveau nom du fichier/dossier
     * @param array|null $params Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function rename($path, $name, array $params = [])
    {
        $params = array_merge([
            'version' => 2,
            'path' => $path,
            'name' => $name,
            'additional' => 'size',
            //'search_taskid' => ''
        ], $params);
        return $this->request('Rename', 'rename', $params);
    }

    /**
     * Copier ou déplacer un fichier/dossier
     *
     * @param string     $path       Chemin du fichier/dossier à copier/déplacer
     * @param string     $destFolder Chemin de destination du fichier/dossier à copier/déplacer
     * @param array|null $params     Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function copy($path, $destFolder, array $params = [])
    {
        $apiEndName = 'CopyMove';
        $version = 3;
        $params = array_merge([
            'version' => $version,
            'path' => $path,
            'dest_folder_path' => $destFolder,
            'overwrite' => 'false',
            'remove_src' => 'false',
            'accurate_progress' => 'true',
            //'search_taskid' => ''
        ], $params);
        $taskid = $this->startTask($apiEndName, $params);
        $response = $this->request($apiEndName, 'status', compact('version', 'taskid'));
        $this->stopTask($taskid, $apiEndName);
        return $response;
    }

    /**
     * Supprimer un fichier/dossier
     *
     * @param string     $path   Chemin du fichier/dossier à supprimer
     * @param array|null $params Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function delete($path, array $params = [])
    {
        $apiEndName = 'Delete';
        $version = 2;
        $params = array_merge([
            'version' => $version,
            'path' => $path,
            'recursive' => 'true',
            //'search_taskid' => ''
        ], $params);

        return $this->request($apiEndName, 'delete', $params);
    }

    /**
     * Extraire une archive
     *
     * @param string     $filePath   Archive à décompresser
     * @param string     $destFolder Dossier de destination
     * @param array|null $params     Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function extract($filePath, $destFolder, array $params = [])
    {
        $apiEndName = 'Extract';
        $version = 2;
        $params = array_merge([
            'version' => $version,
            'file_path' => $filePath,
            'dest_folder_path' => $destFolder,
            'overwrite' => 'true',
            'keep_dir' => 'true',
            'create_subfolder' => 'true',
            'codepage' => $this->getCodePage(),
//            'password' => '',
//            'item_id' => 0,
        ], $params);
        $taskid = $this->startTask($apiEndName, $params);
        $response = $this->request($apiEndName, 'status', compact('version', 'taskid'));
        $this->stopTask($taskid, $apiEndName);
        return $response;
    }

    /**
     * Lire le contenu d'une archive compressée
     *
     * @param string     $filePath Chemin de l'archive
     * @param array|null $params   Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function extractList($filePath, array $params = [])
    {
        $version = 2;
        $params = array_merge([
            'version' => $version,
            'file_path' => $filePath,
            'offset' => 0,
            'limit' => -1,
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ], $params);
        return $this->request('Extract', 'list', $params);
    }

    /**
     * Créer une archive
     * - `$fs->compress('/Download/Piles.xlsx', '/Download/piles.zip');`
     *
     * @param string     $src    Chemin du fichier/dossier à compresser
     * @param string     $dest   Chemin du fichier de destination
     * @param array|null $params Paramètres de la requête
     *                           - version 3
     *                           - path
     *                           - dest_file_path
     *                           - level (moderate, store, fastest ou best)
     *                           - mode (add, update, refreshen, ou synchronize)
     *                           - format (zip ou 7z)
     *                           - password
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function compress($src, $dest, array $params = [])
    {
        $apiEndName = 'Compress';
        $version = 3;
        $params = array_merge([
            'version' => $version,
            'path' => $src,
            'dest_file_path' => $dest,
            'level' => 'best',
            'mode' => 'synchronize',
            'format' => 'zip',
            //'password' => ''
        ], $params);
        $taskid = $this->startTask($apiEndName, $params);
        $response = $this->request($apiEndName, 'status', compact('version', 'taskid'));
        $this->stopTask($taskid, $apiEndName);
        return $response;
    }

    /**
     * Obtenir la liste des tâches en cours
     *
     * @param array $params Paramètres de la requête
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function backgroundTask(array $params = [])
    {
        $params = array_merge([
            'version' => 3,
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'crtime',
            'sort_direction' => 'asc',
            //'api_filter' => 'SYNO.FileStation.CopyMove'
        ], $params);
        return $this->request('BackgroundTask', 'list', $params);
    }

    /**
     * Supprimer toutes tâches terminées
     *
     * @param string|null $taskid Identifiant de la tâche à supprimer
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function clearFinishedTasks($taskid = null)
    {
        return is_null($taskid)
            ? $this->request('BackgroundTask', 'clear_finished')
            : $this->request('BackgroundTask', 'clear_finished', compact('taskid'));
    }

    /**
     * Obtenir le codepage de l'instance
     *
     * @return string
     */
    public function getCodePage()
    {
        return $this->config('Info', 'get')->get('system_codepage');
    }
}
