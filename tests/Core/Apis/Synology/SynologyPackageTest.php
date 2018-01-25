<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\SynologyException;
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
        $this->ekoTitre('API - Package Synology');
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

    public function testGetDefinitionWithKey()
    {
        $path = $this->makePackage('DownloadStation')->getDefinition('Task', 'path');
        $this->assertEquals('DownloadStation/task.cgi', $path);
    }

    public function testGetDatas()
    {
        $audio = $this->makePackage('AudioStation');
        $genres = $audio->get('Genre');
        $this->assertNotEmpty($genres);
        $this->assertArrayHasKey('genres', $genres);
        $this->assertArrayHasKey('total', $genres);
        $this->assertArrayHasKey('offset', $genres);
    }

    public function testGetDatasWithKey()
    {
        $audio = $this->makePackage('AudioStation');
        $genres = $audio->get('Genre', 'list', [], 'genres');
        $this->assertNotEmpty($genres);
    }

    public function testGetDatasWithWrongMethod()
    {
        $audio = $this->makePackage('AudioStation');
        $this->expectException(SynologyException::class);
        $audio->get('Genre', 'fake');

        locale_set_default('en_EN');
        $this->expectException(SynologyException::class);
        $audio->get('Genre', 'fake');
        locale_set_default('fr_FR');
    }

    public function testUseLoginWhenMultipleGet()
    {
        $audio = $this->makePackage('AudioStation');
        $this->assertNotEmpty($audio->get('Genre', 'list', [], 'genres'));
        $this->assertNotEmpty($audio->get('Playlist', 'list', [], 'playlists'));
    }

    public function testGetLog()
    {
        $audio = $this->makePackage('AudioStation');
        $audio->get('Playlist', 'list', [], 'playlists');
        $this->assertNotEmpty($audio->getLog());
    }
}
