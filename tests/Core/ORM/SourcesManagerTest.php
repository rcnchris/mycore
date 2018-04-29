<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\SourcesManager;
use Tests\Rcnchris\BaseTestCase;

class SourcesManagerTest extends BaseTestCase
{

    public function makeSourceManager(array $sources = [])
    {
        if (empty($sources)) {
            //$config = require $this->rootPath() . '/tests/config.php';
            $sources = $this->getConfig('datasources');
        }
        return new SourcesManager($sources);
    }

    public function testInstance()
    {
        $this->ekoTitre('ORM - Sources Manager');
        $this->assertInstanceOf(SourcesManager::class, $this->makeSourceManager());
    }

    public function testGetSources()
    {
        $m = $this->makeSourceManager();
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
        $m = $this->makeSourceManager()->setSources($newSources);
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
        $m = $this->makeSourceManager()->setSources($newSources, true);
        $this->assertInstanceOf(SourcesManager::class, $m);
        $this->assertCount(3, $m->getSources());
    }

    public function testConnnect()
    {
        $m = $this->makeSourceManager();
        $this->assertInstanceOf(\PDO::class, $m->connect('default'));
    }

    public function testConnnectWithoutSourceName()
    {
        $m = $this->makeSourceManager();
        $this->assertInstanceOf(\PDO::class, $m->connect());
    }
}
