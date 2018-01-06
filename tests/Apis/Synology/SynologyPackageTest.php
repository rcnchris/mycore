<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyPackage;

class SynologyPackageTest extends SynologyAbstractTest{

    /**
     * @param $name
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyPackage
     */
    public function makePackage($name)
    {
        return $this->makeAbstract('nas')->getPackage($name);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package');
        $this->assertInstanceOf(SynologyPackage::class, $this->makePackage('API'));
    }

    public function testGetName()
    {
        $this->assertEquals('API', $this->makePackage('API')->getName());
    }

    public function testGetApis()
    {
        $apis = $this->makePackage('API')->getApis();
        $this->assertNotEmpty($apis);
        $this->assertContains('Info', $apis);

        $apis = $this->makePackage('API')->getApis(true);
        $this->assertNotEmpty($apis);
        $this->assertContains('SYNO.API.Info', $apis);
    }

    public function testGetDefinition()
    {
        $def = $this->makePackage('DownloadStation')->getDefinition('Task');
        $this->assertNotEmpty($def);
        $this->assertArrayHasKey('SYNO.API.Auth', $def);
        $this->assertArrayHasKey('SYNO.DownloadStation.Task', $def);
    }
}
