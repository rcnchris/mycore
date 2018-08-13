<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages\Entities;

use Rcnchris\Core\Apis\Synology\Packages\DownloadStationPackage;
use Rcnchris\Core\Apis\Synology\Packages\Entities\TaskEntity;
use Tests\Rcnchris\BaseTestCase;

class TaskEntityTest extends BaseTestCase
{

    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\Entities\TaskEntity
     */
    public function makeSynologyTaskEntity()
    {
        $dl = new DownloadStationPackage($this->makeSynoAPI());
        return $dl->taskEntity('dbid_68');
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Entities : DownloadStation.Task');
        $this->assertInstanceOf(TaskEntity::class, $this->makeSynologyTaskEntity());
    }

}