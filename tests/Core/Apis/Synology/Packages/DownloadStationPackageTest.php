<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\DownloadStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyException;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class DownloadStationPackageTest extends BaseTestCase
{
    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\DownloadStationPackage
     */
    public function makeDownloadStationPackage()
    {
        return new DownloadStationPackage($this->makeSynoAPI());
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : DownloadStation');
        $this->assertInstanceOf(DownloadStationPackage::class, $this->makeDownloadStationPackage());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->makeDownloadStationPackage()->getVersion());
    }

    public function testConfig()
    {
        $api = $this->makeDownloadStationPackage();
        $config = $api->config();
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
    }

    public function testTasks()
    {
        $api = $this->makeDownloadStationPackage();
        $this->assertInstanceOf(Items::class, $api->tasks());
    }

    public function testTask()
    {
        $api = $this->makeDownloadStationPackage();
        $this->assertInternalType('array', $api->task('dbid_68')->toArray());
    }

    public function testTaskWithWrongId()
    {
        $api = $this->makeDownloadStationPackage();
        $this->expectExceptionWithCode(SynologyException::class, 404);
        $api->task('dbid_999');
    }

    public function testTaskWithInvalideParameter()
    {
        $api = $this->makeDownloadStationPackage();
        $this->expectExceptionWithCode(SynologyException::class, 101);
        $api->task('fake');
    }

    public function testCreateTask()
    {
        $api = $this->makeDownloadStationPackage();
        $params = [
            'uri' => 'ftps://192.168.1.2:21/web/index.php',
            'username' => 'phpunit',
            'password' => 'mycoretest'
        ];
        $this->assertTrue($api->createTask($params));
    }

    public function testConfigSchedule()
    {
        $api = $this->makeDownloadStationPackage();
        $config = $api->configSchedule();
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
    }

//    public function testRssSite()
//    {
//        $api = $this->makeDownloadStationPackage();
//        $items = $api->rssSites();
//        $this->assertInstanceOf(Items::class, $items);
//        $this->assertNotEmpty($items->toArray());
//    }

    public function testStatistics()
    {
        $api = $this->makeDownloadStationPackage();
        $items = $api->statistics();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
    }
    public function testListBT()
    {
        $api = $this->makeDownloadStationPackage();
        $items = $api->listBT();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
    }
}