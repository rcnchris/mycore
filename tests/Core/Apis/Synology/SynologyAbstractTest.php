<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyAbstract;
use Tests\Rcnchris\BaseTestCase;

class SynologyAbstractTest extends BaseTestCase
{

    /**
     *
     * @param array $config
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyAbstract
     */
    public function makeAbstract($config = null)
    {
        if (is_null($config)) {
            $config = [
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
        return new SynologyAbstract($config);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology');
        $this->assertInstanceOf(SynologyAbstract::class, $this->makeAbstract());
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
        $api = $this->makeAbstract();
        $this->assertNotEmpty($api->getConfig());
        $this->assertEquals('nas', $api->getConfig('name'));
        $this->assertEmpty($api->getConfig('fake'));
    }

//    public function testGetApis()
//    {
//        $this->assertContains(
//            'SYNO.API.Info',
//            $this->makeAbstract()->getApis()
//        );
//    }

//    public function testGetApiDef()
//    {
//        $api = $this->makeAbstract();
//        $this->assertNotEmpty($api->getApiDef('SYNO.API.Info'));
//        $this->assertEquals('query.cgi', $api->getApiDef('SYNO.API.Info', 'path'));
//        $this->assertFalse($api->getApiDef('SYNO.API.Fake'));
//    }

//    public function testHasApi()
//    {
//        $this->assertTrue($this->makeAbstract()->hasApi('SYNO.API.Info'));
//    }
//
//    public function testHasPackage()
//    {
//        $this->assertTrue($this->makeAbstract()->hasPackage('API'));
//    }

    public function testGetSids()
    {
        $this->assertEmpty($this->makeAbstract()->getSids());
    }
}
