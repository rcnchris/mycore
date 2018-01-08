<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyAbstract;
use Tests\Rcnchris\BaseTestCase;

class SynologyAbstractTest extends BaseTestCase {

    /**
     *
     * @param $name
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAbstract
     */
    public function makeAbstract($name)
    {
        $config = [];
        if (is_string($name)) {
            $config = require $this->rootPath() . '/app/config.php';
            $config = $config['synology'][$name];
        } elseif (is_array($name)) {
            $config = $name;
        }
        return new SynologyAbstract($config);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Abstraction Synology');
        $this->assertInstanceOf(SynologyAbstract::class, $this->makeAbstract('nas'));
    }

    public function testInstanceWithEmptyConfig()
    {
        $this->expectException(\Exception::class);
        $this->makeAbstract([]);
    }

    public function testInstanceWithIncompleteConfig()
    {
        $this->expectException(\Exception::class);
        $this->makeAbstract([
            'host' => 'localhost'
        ]);
    }

    public function testGetConfig()
    {
        $api = $this->makeAbstract('nas');
        $this->assertNotEmpty($api->getConfig());
        $this->assertEquals('nas', $api->getConfig('name'));
        $this->assertEmpty($api->getConfig('fake'));
    }

    public function testGetApis()
    {
        $this->assertContains(
            'SYNO.API.Info',
            $this->makeAbstract('nas')->getApis()
        );
    }

    public function testGetApiDef()
    {
        $api = $this->makeAbstract('nas');
        $this->assertNotEmpty($api->getApiDef('SYNO.API.Info'));
        $this->assertEquals('query.cgi', $api->getApiDef('SYNO.API.Info', 'path'));
        $this->assertFalse($api->getApiDef('SYNO.API.Fake'));
    }

    public function testHasApi()
    {
        $this->assertTrue($this->makeAbstract('nas')->hasApi('SYNO.API.Info'));
    }

    public function testHasPackage()
    {
        $this->assertTrue($this->makeAbstract('nas')->hasPackage('API'));
    }

    public function testGetSids()
    {
        $this->assertEmpty($this->makeAbstract('nas')->getSids());
    }
}
