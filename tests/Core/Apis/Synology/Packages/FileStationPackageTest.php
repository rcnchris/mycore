<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\FileStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class FileStationPackageTest extends BaseTestCase
{
    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\FileStationPackage
     */
    public function makeFileStationPackage()
    {
        return new FileStationPackage($this->makeSynoAPI());
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : FileStation');
        $this->assertInstanceOf(FileStationPackage::class, $this->makeFileStationPackage());
    }

    public function testConfig()
    {
        $api = $this->makeFileStationPackage();
        $config = $api->config('Info', 'get');
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
        $this->assertArrayHasKeys($config->toArray(),
            ['is_manager', 'support_virtual_protocol', 'support_sharing', 'hostname']);
    }

    public function testSharings()
    {
        $api = $this->makeFileStationPackage();

        $items = $api->sharings();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
        $this->assertArrayHasKeys($items->toArray(), ['links', 'offset', 'total']);
    }

    public function testSharing()
    {
        $api = $this->makeFileStationPackage();

        $item = $api->sharing('MvZFrSiKH');
        $this->assertInstanceOf(Items::class, $item);
        $this->assertNotEmpty($item->toArray());
        $this->assertArrayHasKeys($item->toArray(), ['id', 'name', 'path', 'qrcode', 'url']);

        $item = $api->sharing('MvZFrSiKH', true);
        $this->assertInstanceOf(SynologyAPIEntity::class, $item);
        $this->assertObjectHasAttributes($item, ['id', 'name', 'path', 'qrcode', 'url']);
    }

    public function testCreateAndDeleteSharing()
    {
        $api = $this->makeFileStationPackage();

        $response = $api->createSharing('/Download/Piles.xlsx');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertNotEmpty($response->toArray());
        $this->assertArrayHasKeys($response->toArray(), ['links', 'has_folder']);
        $this->assertCount(1, $response->get('links'));
        $this->assertTrue($api->deleteSharing($response->get('links')->first()->id));
    }

    public function testClearSharing()
    {
        $this->assertTrue($this->makeFileStationPackage()->clearSharing());
    }

    public function testSharedFolders()
    {
        $api = $this->makeFileStationPackage();

        $items = $api->sharedFolders();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
        $this->assertArrayHasKeys($items->toArray(), ['shares', 'offset', 'total']);
    }

    public function testSearch()
    {
        $search = 'Piles.xlsx';
        $pkg = $this->makeFileStationPackage();
        $response = $pkg->search('/Download', $search);
        $this->assertInstanceOf(Items::class, $response);
        $this->assertNotEmpty($response->toArray());
        $this->assertArrayHasKeys($response->toArray(), ['files', 'offset', 'total']);
    }

    public function testVirtualFolders()
    {
        $pkg = $this->makeFileStationPackage();
        $items = $pkg->virtualFolders();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
        $this->assertArrayHasKeys($items->toArray(), ['folders', 'offset', 'total']);

        $items = $pkg->virtualFolders([], 'name');
        $this->assertInternalType('array', $items);
    }

    public function testFavorites()
    {
        $pkg = $this->makeFileStationPackage();
        $items = $pkg->favorites();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
        $this->assertArrayHasKeys($items->toArray(), ['favorites', 'offset', 'total']);

        $items = $pkg->favorites([], 'name');
        $this->assertInternalType('array', $items);
    }

    public function testFavorite()
    {
        $pkg=$this->makeFileStationPackage();
        $favorite = $pkg->favorite('/video');
        $this->assertInternalType('array', $favorite);

        $favorite = $pkg->favorite('/video', true);
        $this->assertInstanceOf(SynologyAPIEntity::class, $favorite);
    }

    public function testAddDeleteFavorite()
    {
        $pkg = $this->makeFileStationPackage();
        $this->assertTrue($pkg->addFavorite('/DDSMWEB', 'Nas virtuel'), $this->getMessage("Le favori n'a pas été créé"));
        $this->assertTrue($pkg->deleteFavorite('/DDSMWEB'), $this->getMessage("Le favori n'a pas été supprimé"));
    }

    public function testEditFavorite()
    {
        $pkg = $this->makeFileStationPackage();
        $initName = 'NAS virtuel';
        $pkg->addFavorite('/DDSMWEB', $initName);
        $pkg->editFavorite('/DDSMWEB', 'Nouveau nom');
        $this->assertEquals('Nouveau nom', $pkg->favorite('/DDSMWEB', true)->name);
        $this->assertTrue($pkg->deleteFavorite('/DDSMWEB'));
    }

    public function testClearFavorites()
    {
        $this->assertTrue($this->makeFileStationPackage()->clearFavorites());
    }

    public function testSize()
    {
        $pkg = $this->makeFileStationPackage();
        $size = $pkg->size('/Commun');
        $this->assertInstanceOf(Items::class, $size);
        $this->assertArrayHasKeys($size->toArray(), ['finished', 'num_dir', 'num_file', 'total_size']);
    }

    public function testThumb()
    {
        $pkg = $this->makeFileStationPackage();
        $thumbUrl = $pkg->thumb('/Commun/chevrolet.jpg');
        $this->assertInternalType('string', $thumbUrl);
        $this->assertEquals($thumbUrl, filter_var($thumbUrl, FILTER_VALIDATE_URL));
    }

    public function testMd5File()
    {
        $pkg = $this->makeFileStationPackage();
        $md5 = $pkg->md5File('/Commun/chevrolet.jpg');
        $this->assertInstanceOf(Items::class, $md5);
        $this->assertArrayHasKeys($md5->toArray(), ['finished', 'md5']);
    }

    public function testCheckPerm()
    {
        $pkg = $this->makeFileStationPackage();
        $response = $pkg->checkPerm('/Commun', 'fake.txt');
        $this->assertInstanceOf(Items::class, $response);
    }

//    public function testUploadFile()
//    {
//        $pkg = $this->makeFileStationPackage();
//        $response = $pkg->uploadFile(__FILE__, '/Download');
//        $this->assertInstanceOf(Items::class, $response);
//    }

    public function testDownload()
    {
        $pkg = $this->makeFileStationPackage();
        $response = $pkg->download('/Download/chevrolet.jpg');
        $this->assertInternalType('string', $response);
    }

    public function testCreateDeleteFolder()
    {
        $pkg = $this->makeFileStationPackage();
        $response = $pkg->createFolder('/Download', 'Fake');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), 'folders');
        $this->assertTrue($pkg->delete('/Download/Fake'));
    }

//    public function testRename()
//    {
//        $pkg = $this->makeFileStationPackage();
//        $response = $pkg->rename('/Download/chevrolet.jpg', 'chevroletSS.jpg');
//        $this->assertInstanceOf(Items::class, $response);
//        $this->assertArrayHasKeys($response->toArray(), 'files');
//    }
}
