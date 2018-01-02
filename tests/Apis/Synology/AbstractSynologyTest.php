<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyAbstract;
use Tests\Rcnchris\BaseTestCase;

class AbstractSynologyTest extends BaseTestCase {

    /**
     * Configuration de connexion
     *
     * @var array
     */
    protected $config;

    public function setUp()
    {
        $this->config = [
            'name' => 'nas',
            'description' => 'Nas du salon',
            'address' => '192.168.1.2',
            'port' => 5551,
            'protocol' => 'http',
            'version' => 1,
            'ssl' => false,
            'user' => 'rcn',
            'pwd' => 'maracla'
        ];
    }

    /**
     * @param array $config
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAbstract
     */
    public function makeAbstract(array $config)
    {
        return new SynologyAbstract($config);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Abstraction Synology');
        $this->assertInstanceOf(SynologyAbstract::class, $this->makeAbstract($this->config));
    }

    public function testInstanceWithWrongConfig()
    {
        $config = [
            'fake' => 'zob'
        ];
        $this->expectException(\Exception::class);
        $this->makeAbstract($config);
    }

    public function testGetConfig()
    {
        $api = $this->makeAbstract($this->config);
        $this->assertNotEmpty($api->getConfig());
        $this->assertEquals('nas', $api->getConfig('name'));
        $this->assertEmpty($api->getConfig('fake'));
    }

    public function testGetBaseUrl()
    {
        $baseUrl = $this->config['protocol'] . '://' . $this->config['address'] . ':' . $this->config['port'] . '/webapi';
        $this->assertEquals($baseUrl, $this->makeAbstract($this->config)->getBaseUrl());
    }

    public function testGetApis()
    {
        $this->assertContains(
            'SYNO.API.Info',
            $this->makeAbstract($this->config)->getApis()
        );
    }

    public function testGetApiDef()
    {
        $api = $this->makeAbstract($this->config);
        $this->assertEquals('query.cgi', $api->getApiDef('SYNO.API.Info'));
        $this->assertEquals(1, $api->getApiDef('SYNO.API.Info', 'minVersion'));
        $this->assertFalse($api->getApiDef('SYNO.API.Fake'));
    }

    public function testHasApi()
    {
        $this->assertTrue($this->makeAbstract($this->config)->hasApi('SYNO.API.Info'));
    }

    public function testHasPackage()
    {
        $this->assertTrue($this->makeAbstract($this->config)->hasPackage('API'));
    }
}
