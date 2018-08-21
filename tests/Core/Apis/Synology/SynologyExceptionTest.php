<?php
namespace Tests\Rcnchris\Core\Apis\Synology;

use Rcnchris\Core\Apis\Synology\Packages\FileStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyException;

class SynologyExceptionTest extends SynologyBaseTestCase
{
    public function testInstance()
    {
        $this->ekoTitre('API - Synology Exceptions');
        $this->assertTrue(true);
    }

    public function test_103_UnknowMethod()
    {
        $pkg = $this->makeSynoAPI()->getPackage('FileStation');
        $this->expectExceptionWithCode(SynologyException::class, 103);
        $pkg->request('Sharing', 'get', ['id' => 'fake']);
    }

    public function test_101_InvalidParameter()
    {
        $pkg = new FileStationPackage($this->makeSynoAPI());
        $this->expectExceptionWithCode(SynologyException::class, 101);
        $pkg->request('MD5', 'start', ['version' => 2, 'filepath' => '/Commun/chevrolet.jpg']);
    }

//    public function test_414_FileStation()
//    {
//        $pkg = new FileStationPackage($this->makeSynoAPI());
//        $pkg->checkPerm('/Commun', 'chevrolet.jpg');
//        $this->expectExceptionWithCode(SynologyException::class, 414);
//    }

    public function test_800_FileStation()
    {
        $pkg = new FileStationPackage($this->makeSynoAPI());
        $pkg->addFavorite('/DDSMWEB', 'NAS virtuel');
        $this->expectExceptionWithCode(SynologyException::class, 800);
        $pkg->addFavorite('/DDSMWEB', 'NAS virtuel');
    }

    public function tearDown()
    {
        (new FileStationPackage($this->makeSynoAPI()))->deleteFavorite('/DDSMWEB');
    }
}