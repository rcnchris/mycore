<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\SourcesManager;

class SourcesManagerTest extends OrmTestCase
{

    public function testInstance()
    {
        parent::testInstance();
        $this->assertInstanceOf(SourcesManager::class, $this->getManager());
    }

    public function testHasDefaultSource()
    {
        $m = $this->getManager();
        $this->assertTrue($m->has('default'));
    }

    public function testGetSources()
    {
        $m = $this->getManager();
        $this->assertArrayHasKey('test', $m->getSources());
        $this->assertArrayHasKey('host', $m->getSources('test'));
        $this->assertArrayHasKey('host', $m->test);
        $this->assertFalse($m->getSources('fake'));
    }

    public function testSetSources()
    {
        $newSources = [
            'default' => [
                'host' => 'dbApp',
                'username' => '',
                'password' => '',
                'dbName' => 'dbApp',
                'sgbd' => 'sqlite',
                'port' => 0,
                'fileName' => $this->rootPath() . '/public/dbApp.sqlite'
            ],
        ];
        $m = $this->getManager()->setSources($newSources);
        $this->assertInstanceOf(SourcesManager::class, $m);
        $this->assertCount(1, $m->getSources());
    }

    public function testSetSourcesWithAdd()
    {
        $newSources = [
            'new' => [
                'host' => 'dbApp',
                'username' => '',
                'password' => '',
                'dbName' => 'dbApp',
                'sgbd' => 'sqlite',
                'port' => 0,
                'fileName' => $this->rootPath() . '/public/dbApp.sqlite'
            ],
        ];
        $m = $this->getManager()->setSources($newSources, true);
        $this->assertInstanceOf(SourcesManager::class, $m);
        $this->assertCount(3, $m->getSources());
    }

    public function testConnnect()
    {
        $m = $this->getManager();
        $this->assertInstanceOf(\PDO::class, $m->connect('default'));
    }

    public function testConnnectWithoutSourceName()
    {
        $m = $this->getManager();
        $this->assertInstanceOf(\PDO::class, $m->connect());
    }

    public function testOrmTestCaseGetManager()
    {
        $this->assertInstanceOf(SourcesManager::class, $this->getManager());
    }
}
