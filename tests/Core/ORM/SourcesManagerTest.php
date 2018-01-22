<?php
namespace Tests\Rcnchris\Core\ORM;

use Rcnchris\Core\ORM\SourcesManager;

class SourcesManagerTest extends OrmTestCase {

    /**
     * Sources de données des tests
     *
     * @var array
     */
    private $sources = [
        'home' => [
            'host' => '192.168.1.2'
            , 'username' => 'phpunit'
            , 'password' => 'phpunit'
            , 'dbName' => 'home'
            , 'sgbd' => 'mysql'
            , 'port' => 3306
        ]
        ,'codes' => [
            'host' => '192.168.1.2'
            , 'username' => 'phpunit'
            , 'password' => 'phpunit'
            , 'dbName' => 'codes'
            , 'sgbd' => 'mysql'
            , 'port' => 3306
        ]
        ,'default' => [
            'host' => 'memory'
            , 'username' => ''
            , 'password' => ''
            , 'dbName' => ''
            , 'sgbd' => 'sqlite'
            , 'port' => 0
        ]
    ];

    public function makeSourceManager(array $sources = [])
    {
        if (empty($sources)) {
            $config = require $this->rootPath() . '/app/config.php';
            $sources = $config['datasources'];
        }
        return new SourcesManager($sources);
    }

    public function testInstance()
    {
        $this->ekoTitre('ORM - Sources Manager');
        $this->assertInstanceOf(SourcesManager::class, $this->makeSourceManager($this->sources));
    }

    public function testGetSources()
    {
        $m = $this->makeSourceManager($this->sources);
        $this->assertArrayHasKey('default', $m->getSources());
    }

    public function testGetSourcesWithKey()
    {
        $m = $this->makeSourceManager($this->sources);
        $this->assertArrayHasKey('host', $m->getSources('default'));
    }

    public function testGetSourcesWithMissingKey()
    {
        $m = $this->makeSourceManager($this->sources);
        $this->assertFalse($m->getSources('fake'));
    }

    public function testGetProperty()
    {
        $m = $this->makeSourceManager($this->sources);
        $this->assertArrayHasKey('host', $m->default);
    }

    public function testGetPropertyNotExists()
    {
        $m = $this->makeSourceManager($this->sources);
        $this->assertFalse($m->fake);
    }

    public function testSetSources()
    {
        $newSource = [
            'new' => [
                'host' => '192.168.1.2'
                , 'username' => 'phpunit'
                , 'password' => 'phpunit'
                , 'dbName' => 'new'
                , 'sgbd' => 'mysql'
                , 'port' => 3306
            ]
        ];
        $m = $this->makeSourceManager($this->sources);
        $m->setSources($newSource);
        $this->assertCount(1, $m->getSources());
    }

    public function testSetSourcesWithAdd()
    {
        $newSource = [
            'new' => [
                'host' => '192.168.1.2'
                , 'username' => 'phpunit'
                , 'password' => 'phpunit'
                , 'dbName' => 'new'
                , 'sgbd' => 'mysql'
                , 'port' => 3306
            ]
        ];
        $m = $this->makeSourceManager($this->sources);
        $m->setSources($newSource, true);
        $this->assertCount(4, $m->getSources());
    }

    public function testConnect()
    {
        $m = $this->makeSourceManager($this->sources);
        chdir($this->rootPath() . $this::TESTS_FOLDER);
        $this->assertInstanceOf(\PDO::class, $m->connect('default'));
        chdir($this->rootPath());
    }

    public function testConnectWithoutKey()
    {
        $m = $this->makeSourceManager($this->sources);
        chdir($this->rootPath() . $this::TESTS_FOLDER);
        $this->assertInstanceOf(\PDO::class, $m->connect());
        chdir($this->rootPath());
    }
}
