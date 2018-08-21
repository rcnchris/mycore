<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\DownloadStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyException;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\Core\Apis\Synology\SynologyBaseTestCase;

class DownloadStationPackageTest extends SynologyBaseTestCase
{
    /**
     * @var DownloadStationPackage
     */
    private $downloadStation;

    /**
     * Constructeur
     */
    public function setUp()
    {
        $this->downloadStation = $this->makeDownloadStationPackage();
    }

    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\DownloadStationPackage
     */
    public function makeDownloadStationPackage()
    {
        return new DownloadStationPackage($this->makeSynoAPI());
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : ' . $this->downloadStation->getName());
        $this->assertInstanceOf(DownloadStationPackage::class, $this->makeDownloadStationPackage());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->downloadStation->getVersion());
    }

    public function testConfig()
    {
        $config = $this->downloadStation->config();
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
    }

    public function testTasks()
    {
        $this->assertInstanceOf(Items::class, $this->downloadStation->tasks());
    }

    public function testTask()
    {
        $this->assertInternalType(
            'array',
            $this->downloadStation->task(
                $this->downloadStation
                    ->tasks()
                    ->get('tasks')
                    ->first()
                    ->id
            )->toArray()
        );
    }

    public function testTaskWithWrongId()
    {
        $api = $this->makeDownloadStationPackage();
        $this->expectExceptionWithCode(SynologyException::class, 404);
        $api->task('dbid_999');
    }

    public function testTaskWithInvalideParameter()
    {
        $this->expectExceptionWithCode(SynologyException::class, 101);
        $this->downloadStation->task('fake');
    }

    public function testCreateTask()
    {
        $params = [
            'uri' => 'ftps://192.168.1.2:21/web/index.php',
            'username' => 'phpunit',
            'password' => 'mycoretest'
        ];
        $this->assertTrue($this->downloadStation->createTask($params));
    }

    public function testConfigSchedule()
    {
        $config = $this->downloadStation->configSchedule();
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
        $items = $this->downloadStation->statistics();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
    }

    public function testListBT()
    {
        $items = $this->downloadStation->listBT();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
    }
}
