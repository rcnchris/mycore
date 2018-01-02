<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyException;
use Rcnchris\Core\Apis\Synology\SynologyPackage;

class SynologyPackageTest extends AbstractSynologyTest {

    /**
     * @param $name
     *
     * @return \Rcnchris\Core\Apis\Synology\SynologyPackage
     */
    public function makePackage($name)
    {
        return $this->makeAbstract($this->config)->getPackage($name);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package');
        $this->assertInstanceOf(SynologyPackage::class, $this->makePackage('API'));

        $this->expectException(SynologyException::class);
        $this->makePackage('Fake');
    }

    public function testGetName()
    {
        $this->assertEquals('API', $this->makePackage('API')->getName());
    }

    public function testGetApis()
    {
        $this->assertNotEmpty($this->makePackage('API')->getApis());
    }

    public function testGetDefinition()
    {
        $this->assertEquals('query.cgi', $this->makePackage('API')->getDefinition('Info'));
    }
}
