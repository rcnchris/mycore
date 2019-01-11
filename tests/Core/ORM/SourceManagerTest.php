<?php
namespace Core\ORM;

use Rcnchris\Core\ORM\SourcesManager;
use Tests\Rcnchris\BaseTestCase;

class SourceManagerTest extends BaseTestCase
{

    /**
     * @var SourcesManager
     */
    private $dbManager;

    /**
     * @param array $configs
     *
     * @return \Rcnchris\Core\ORM\SourcesManager
     */
    public function makeSource(array $configs = [])
    {
        return new SourcesManager($configs);
    }

    public function setUp()
    {
        $this->dbManager = $this->makeSource($this->getConfig('datasources'));
    }

    public function testInstance()
    {
        $this->ekoTitre('ORM - Sources Manager');
        $this->assertInstanceOf(SourcesManager::class, $this->dbManager);
    }

    public function testGetSources()
    {
        $sources = $this->dbManager->getSources();
        $this->assertInternalType('array', $sources);
        $this->assertNotEmpty($sources);
    }

    public function testGetSourcesWithSource()
    {
        $src = $this->dbManager->getSources('default');
        $this->assertInternalType('array', $src);
        $this->assertNotEmpty($src);
    }

    public function testGetSourcesWithWrongSource()
    {
        $this->assertFalse($this->dbManager->getSources('fake'));
    }

    public function testHasSource()
    {
        $this->assertTrue($this->dbManager->has('default'));
    }

    public function testSetSources()
    {
        $this->assertInstanceOf(
            SourcesManager::class,
            $this->dbManager->setSources($this->getConfig('datasources'))
        );
    }

    public function testSetSourcesWithAdd()
    {
        $this->assertInstanceOf(
            SourcesManager::class,
            $this->dbManager->setSources(['newdb' => [
                'host' => '192.168.1.2',
                'username' => 'demo',
                'password' => 'demo',
                'dbName' => 'demo',
                'sgbd' => 'mysql',
                'port' => 3306
            ]], true)
        );
    }

    public function testMagicGet()
    {
        $this->assertInternalType('array', $this->dbManager->default);
    }

    public function testConnectWithoutName()
    {
        $this->assertInstanceOf(\PDO::class, $this->dbManager->connect());
    }

    public function testConnectWithName()
    {
        $this->assertInstanceOf(\PDO::class, $this->dbManager->connect('default'));
    }

    public function testConnectWithMissingName()
    {
        $this->assertInstanceOf(\PDO::class, $this->dbManager->connect('fake'));
    }
}
