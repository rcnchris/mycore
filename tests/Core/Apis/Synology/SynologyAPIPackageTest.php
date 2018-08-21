<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyAPI;
use Rcnchris\Core\Apis\Synology\SynologyAPIPackage;
use Rcnchris\Core\Apis\Synology\SynologyException;
use Rcnchris\Core\Tools\Items;

class SynologyAPIPackageTest extends SynologyBaseTestCase
{

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Packages');
        $this->assertInstanceOf(SynologyAPIPackage::class, $this->makeSynologyPackage('DownloadStation'));
    }

    public function testInstanceWithWrongPackageName()
    {
        $this->expectException(SynologyException::class);
        $this->makeSynologyPackage('FakeStation');
    }

    public function testGetName()
    {
        $this->assertEquals('DownloadStation', $this->makeSynologyPackage('DownloadStation')->getName());
    }

    public function testGetDefinition()
    {
        $definition = $this->makeSynologyPackage('DownloadStation')->getDefinition('Task');
        $this->assertInstanceOf(Items::class, $definition);
        $this->assertArrayHasKey('SYNO.API.Auth', $definition->toArray());
        $this->assertArrayHasKey('path', $definition->get('SYNO.API.Auth', false));
        $this->assertArrayHasKey('SYNO.DownloadStation.Task', $definition->toArray());
        $this->assertArrayHasKey('path', $definition->get('SYNO.DownloadStation.Task', false));
    }

    public function testGetDefinitionWithOnlyApi()
    {
        $definition = $this->makeSynologyPackage('DownloadStation')->getDefinition('Task', true);
        $this->assertInstanceOf(Items::class, $definition);
        $this->assertArrayNotHasKey('SYNO.API.Auth', $definition->toArray());
        $this->assertArrayHasKey('path', $definition->toArray());
    }

    public function testGetMethods()
    {
        $methods = $this->makeSynologyPackage('DownloadStation')->getMethods();
        $this->assertInstanceOf(Items::class, $methods);
        $this->assertContains('Task', $methods->toArray());
    }

    public function testSetIcon()
    {
        $icon = 'fa fa-download';
        $pkg = $this->makeSynologyPackage('DownloadStation');
        $this->assertInstanceOf(SynologyAPIPackage::class, $pkg->setIcon($icon));
        $this->assertEquals($icon, $pkg->getIcon());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->makeSynologyPackage('DownloadStation')->getVersion());
        $this->assertInternalType('string', $this->makeSynologyPackage('AudioStation')->getVersion());
    }

    public function testGetApi()
    {
        $this->assertInstanceOf(SynologyAPI::class, $this->makeSynologyPackage('DownloadStation')->getApi());
    }

    public function testGetSid()
    {
        $pkg = $this->makeSynologyPackage('DownloadStation');
        $pkg->request('Task', 'list');
        $this->assertInternalType('string', $pkg->getSid('Task'));
    }

    public function testLogin()
    {
        $pkg = $this->makeSynologyPackage('AudioStation');
        $this->assertInternalType('string', $pkg->login('Playlist'));
        $this->assertTrue($pkg->logout('Playlist'));
    }

    public function testLogout()
    {
        $pkg = $this->makeSynologyPackage('DownloadStation');
        $pkg->request('Task', 'list');
        $this->assertTrue($pkg->logout('Task'));
    }
}
