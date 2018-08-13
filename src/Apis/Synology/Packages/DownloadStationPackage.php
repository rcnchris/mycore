<?php
/**
 * Fichier DownloadStationPackage.php du 11/08/2018
 * Description : Fichier de la classe DownloadStationPackage
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

use Rcnchris\Core\Apis\Synology\Packages\Entities\TaskEntity;
use Rcnchris\Core\Apis\Synology\SynologyAPI;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;

/**
 * Class DownloadStationPackage
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
class DownloadStationPackage extends SynologyAPIPackage
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
        parent::__construct('DownloadStation', $syno);
    }

    /**
     * Obtenir la configuration du planificateur
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function configSchedule()
    {
        return $this->getItems('Schedule', 'getconfig');
    }

    /**
     * Obtenir les statistiques
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function statistics()
    {
        return $this->getItems('Statistic', 'getinfo');
    }

    /**
     * Obtenir la liste des téléchargements bittorrents
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function listBT()
    {
        return $this->getItems('BTSearch', 'list');
    }

    /**
     * Obtenir la listes des albums
     * - `$dl->tasks()->toArray();`
     * - `$dl->tasks(['limit' => 10], 'title');`
     *
     * @param array|null $params  Paramètres de la requête
     *                            - offset
     *                            - limit
     *                            - additional (detail, transfer, file, tracker, peer)
     *
     * @return null|\Rcnchris\Core\Tools\Items
     */
    public function tasks(array $params = ['additional' => 'detail,transfer,file,tracker,peer'], $extractKey = null)
    {
        return $this->getItems('Task', 'list', $params, 'tasks', $extractKey);
    }

    /**
     * Obtenir une tâche par son identifiant
     * - `$dl->task('dbid_195')->toArray();`
     *
     * @param string $id          Identifiant de la tâche
     * @param string $additional Type de retour (detail, transfer, file, tracker, peer)
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function task($id, $additional = 'detail,transfer,file,tracker,peer')
    {
        //return $this->getItem($this, 'Task', 'getinfo', compact('id', 'additional'), 'tasks', $toEntity);

        return $this
            ->request('Task', 'getinfo', ['id' => $id, 'additional' => $additional])
            ->get('tasks')
            ->first();
    }

    /**
     * Obtenir l'entité d'une tâche
     *
     * @param        $id
     * @param string $additional
     *
     * @return \Rcnchris\Core\Apis\Synology\Packages\Entities\TaskEntity
     */
    public function taskEntity($id, $additional = 'detail,transfer,file,tracker,peer')
    {
        return new TaskEntity(
            $this,
            $this->task($id, compact('additional'))->toArray()
        );
    }

    /**
     * Créer une tâche de téléchargement
     *
     * @param array $params Paramètres de la tâche
     *                      - uri : Optional. Accepts HTTP/FTP/magnet/ED2K links or the file path starting with a
     *                      shared folder, separated by ",".
     *                      - file : Optional. File uploading from client. For more info, please see Limitations on
     *                      page 30.
     *                      - username : Optional. Login username
     *                      - password : Optional. Login password
     *                      - unzip_password : Optional. Password for unzipping download tasks
     *                      - destination : Optional. Download destination path starting with a shared folder
     *
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function createTask(array $params = [])
    {
        return $this->request('Task', 'create', $params);
    }
}
