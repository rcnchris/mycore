<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyAPI;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;
use Rcnchris\Core\Apis\Synology\SynologyException;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class SynologyAPITest extends BaseTestCase
{

    public function testInstance()
    {
        $this->ekoTitre('API - SynologyAPI');
        $this->assertInstanceOf(SynologyAPI::class, $this->makeSynoAPI());
    }

    public function testInstanceWithUrl()
    {
        $this->assertInstanceOf(SynologyAPI::class, $this->makeSynoAPI('http://192.168.1.2:5551/webapi'));
    }

    public function testGetBaseUrl()
    {
        $api = $this->makeSynoAPI();
        $this->assertEquals('http://192.168.1.2:5551/webapi', $api->getBaseUrl());

        $api = $this->makeSynoAPI('http://192.168.1.2:5551/webapi');
        $this->assertEquals('http://192.168.1.2:5551/webapi', $api->getBaseUrl());
    }

    public function testGetConfig()
    {
        $this->assertInstanceOf(Items::class, $this->makeSynoAPI()->getConfig());
    }

    public function testGetApis()
    {
        $this->assertArrayHasKey('SYNO.API.Auth', $this->makeSynoAPI()->getApis()->toArray());
    }

    public function testGetApiDefinition()
    {
        $definition = $this->makeSynoAPI()->getApiDefinition('AudioStation.Album')->toArray();
        $this->assertArrayHasKey('SYNO.API.Auth', $definition);
        $this->assertArrayHasKey('SYNO.AudioStation.Album', $definition);
    }

    public function testGetApiDefinitionOnceTime()
    {
        $api = $this->makeSynoAPI();
        $def1 = $api->getApiDefinition('DownloadStation.Task');
        $def2 = $api->getApiDefinition('DownloadStation.Task');
        $this->assertEquals($def1, $def2);
    }

    public function testGetPackages()
    {
        $api = $this->makeSynoAPI();
        $this->assertContains('API', $api->getPackages()->toArray());
        $this->assertArrayHasKey('API', $api->getPackages(true)->toArray());
    }

    public function testGetPackageName()
    {
        $api = $this->makeSynoAPI();
        $apiName = 'SYNO.AudioStation.Album';
        $this->assertEquals('AudioStation', $api->getPackageName($apiName));
    }

    public function testHasPackage()
    {
        $api = $this->makeSynoAPI();
        $this->assertTrue($api->hasPackage('API'));
        $this->assertFalse($api->hasPackage('Fake'));
    }

    public function testGetMethodsOfPackage()
    {
        $api = $this->makeSynoAPI();
        $packeName = 'API';
        $this->assertEquals([
            'Auth',
            'Encryption',
            'Info',
            'OTP'
        ], $api->getMethodsOfPackage($packeName)->toArray());
    }

    public function testGetPackage()
    {
        $this->assertInstanceOf(SynologyAPIPackage::class, $this->makeSynoAPI()->getPackage('DownloadStation'));
    }

    public function testGetCurrentPackage()
    {
        $this->assertNull($this->makeSynoAPI()->getCurrentPackage());
    }

    public function testGetSidsToItemsInstance()
    {
        $api = $this->makeSynoAPI();
        $this->assertInstanceOf(Items::class, $api->getSids());
    }

    public function testLogin()
    {
        $sid = $this->makeSynoAPI()->login('DownloadStation.Task');
        $this->assertInternalType('string', $sid);
    }

    public function testLoginWithGoodUser()
    {
        $sid = $this->makeSynoAPI()->login('DownloadStation.Task', 'sid', 'phpunit', 'mycoretest');
        $this->assertInternalType('string', $sid);
    }

    public function testLoginWithWrongUser()
    {
        $this->expectException(SynologyException::class);
        $this->makeSynoAPI()->login('DownloadStation.Task', 'sid', 'fake', 'fake');
    }

    public function testLogout()
    {
        $api = $this->makeSynoAPI();
        $api->login('DownloadStation.Task', 'sid', 'phpunit', 'mycoretest');
        $this->assertTrue($api->logout('DownloadStation.Task'));
    }

    public function testLogoutWithApiNotConnected()
    {
        $api = $this->makeSynoAPI();
        $api->login('DownloadStation.Task', 'sid', 'phpunit', 'mycoretest');
        $this->assertFalse($api->logout('AudioStation.Album'));
    }

    public function testLoginOnceTime()
    {
        $api = $this->makeSynoAPI();
        $sid1 = $api->login('DownloadStation.Task');
        $sid2 = $api->login('DownloadStation.Task');
        $this->assertEquals($sid1, $sid2);
    }

    public function testLoginWithWrongAuthentificationFormat()
    {
        $api = $this->makeSynoAPI();
        $this->expectException(SynologyException::class);
        $api->login('DownloadStation.Task', 'fake');
    }

    public function testGetLog()
    {
        $api = $this->makeSynoAPI();
        $api->getApis();
        $this->assertInstanceOf(Items::class, $api->getLog());
    }
}