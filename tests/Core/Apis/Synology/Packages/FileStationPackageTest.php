<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\FileStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Apis\Synology\SynologyException;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\Core\Apis\Synology\SynologyBaseTestCase;

class FileStationPackageTest extends SynologyBaseTestCase
{
    /**
     * @var FileStationPackage
     */
    private $fileStation;

    /**
     * Constructeur
     */
    public function setUp()
    {
        $this->fileStation = $this->makeFileStationPackage();
    }

    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\FileStationPackage
     */
    private function makeFileStationPackage()
    {
        return new FileStationPackage($this->makeSynoAPI());
    }

    /**
     * Instance et titre
     */
    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : ' . $this->fileStation->getName());
        $this->assertInstanceOf(FileStationPackage::class, $this->fileStation);
    }

    /**
     * Obtenir la configuration du package
     */
    public function testConfig()
    {
        $config = $this->fileStation->config('Info', 'get');
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
        $this->assertArrayHasKeys($config->toArray(), 'is_manager,support_virtual_protocol,support_sharing,hostname');
    }

    /**
     * Obtenir la liste des liens partagés
     */
    public function testSharings()
    {
        $this->assertSynologyList(
            $this->fileStation,
            'sharings',
            [
                'expectedResponseKeys' => 'links,offset,total',
                'itemsKey' => 'links',
                'expectedItemKeys' => 'date_available,date_expired,has_password,id,isFolder,link_owner,name,path,status,url',
                'extractKey' => 'name',
                'typeItemsKey' => 'string',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir un lien partagé par son identifiant
     */
    public function testSharing()
    {
        $expectedKeys = 'date_available,date_expired,has_password,id,isFolder,link_owner,name,path,status,url';

        $item = $this->fileStation->sharings()->get('links')->first();
        $this->assertInstanceOf(Items::class, $item);
        $this->assertNotEmpty($item->toArray());
        $this->assertArrayHasKeys($item->toArray(), $expectedKeys);

        $item = $this->fileStation->sharing($item->id, true);
        $this->assertInstanceOf(SynologyAPIEntity::class, $item);
        $this->assertObjectHasAttributes($item, $expectedKeys);
    }

    /**
     * Créer un lien partagé et le supprimer à partir de son identifiant
     */
    public function testCreateAndDeleteSharing()
    {
        $expectedResponseKeys = 'links,has_folder';
        $response = $this->fileStation->createSharing('/Download/Tests/Piles.xlsx');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertNotEmpty($response->toArray());
        $this->assertArrayHasKeys($response->toArray(), $expectedResponseKeys);
        $this->assertTrue($this->fileStation->deleteSharing($response->get('links')->first()->id));
    }

    /**
     * Supprimer les liens expirés
     */
    public function testClearSharing()
    {
        $this->assertTrue($this->fileStation->clearSharing());
    }

    /**
     * Obtenir la liste des dossiers partagés
     */
    public function testSharedFolders()
    {
        $this->assertSynologyList(
            $this->fileStation,
            'sharedFolders',
            [
                'expectedResponseKeys' => 'shares,offset,total',
                'itemsKey' => 'shares',
                'expectedItemKeys' => 'path,name,additional',
                'extractKey' => 'name',
                'typeItemsKey' => 'int',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir la liste des fichiers d'un chemin partagé
     */
    public function testSharedFolderFiles()
    {
        $expectedResponseKeys = 'files,offset,total';
        $expectedItemKeys = 'additional,isdir,name,path';
        $items = $this->fileStation->sharedFolderFiles('/Download/Tests');
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
        $this->assertArrayHasKeys($items->toArray(), $expectedResponseKeys);
        $this->assertArrayHasKeys($items->get('files')->first()->toArray(), $expectedItemKeys);

        $list = $this->fileStation->sharedFolderFiles('/Download/Tests', ['limit' => 3], 'name');
        $this->assertInternalType('array', $list);
        $this->assertTrue(count($list) <= 3);
        $this->assertInternalType('int', current(array_keys($list)));
    }

    /**
     * Chercher un terme dans un dossier partagé
     */
    public function testSearch()
    {
        $expectedResponseKeys = 'files,offset,total';
        $expectedItemKeys = 'additional,isdir,name,path';
        $response = $this->fileStation->search('/Download/Tests', 'Piles.xlsx');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertNotEmpty($response->toArray());
        $this->assertArrayHasKeys($response->toArray(), $expectedResponseKeys);
        $this->assertArrayHasKeys($response->get('files')->first()->toArray(), $expectedItemKeys);
    }

    /**
     * Obtenir la liste des dossiers virtuels (montages)
     */
    public function testVirtualFolders()
    {
        $this->assertSynologyList(
            $this->fileStation,
            'virtualFolders',
            [
                'expectedResponseKeys' => 'folders,offset,total',
                'itemsKey' => 'folders',
                'expectedItemKeys' => 'additional,isdir,name,path',
                'extractKey' => 'name',
                'typeItemsKey' => 'int',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir la liste des favoris
     */
    public function testFavorites()
    {
        $this->assertSynologyList(
            $this->fileStation,
            'favorites',
            [
                'expectedResponseKeys' => 'favorites,offset,total',
                'itemsKey' => 'favorites',
                'expectedItemKeys' => 'isdir,name,path,status',
                'extractKey' => 'name',
                'typeItemsKey' => 'int',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir un favori à partir de son chemin
     */
    public function testFavorite()
    {
        $expectedKeys = 'path,name,status';

        $item = $this->fileStation->favorites()->get('favorites')->first();
        $this->assertInstanceOf(Items::class, $item);
        $this->assertNotEmpty($item->toArray());
        $this->assertArrayHasKeys($item->toArray(), $expectedKeys);

        $item = $this->fileStation->favorite($item->path, true);
        $this->assertInstanceOf(SynologyAPIEntity::class, $item);
        $this->assertObjectHasAttributes($item, $expectedKeys);
    }

    /**
     * Ajouter un favori et le supprimer par son identifiant
     */
    public function testAddDeleteFavorite()
    {
        $this->assertTrue(
            $this->fileStation->addFavorite('/DDSMWEB', 'Nas virtuel'),
            $this->getMessage("Le favori n'a pas été créé")
        );
        $this->assertTrue(
            $this->fileStation->deleteFavorite('/DDSMWEB'),
            $this->getMessage("Le favori n'a pas été supprimé")
        );
    }

    /**
     * Renommer un favori
     */
    public function testEditFavorite()
    {
        $initName = 'NAS virtuel';
        $this->fileStation->addFavorite('/DDSMWEB', $initName);
        $this->fileStation->editFavorite('/DDSMWEB', 'Nouveau nom');
        $this->assertEquals('Nouveau nom', $this->fileStation->favorite('/DDSMWEB', true)->name);
        $this->assertTrue($this->fileStation->deleteFavorite('/DDSMWEB'));
    }

    /**
     * Supprimer les favoris erronés
     */
    public function testClearFavorites()
    {
        $this->assertTrue($this->fileStation->clearFavorites());
    }

    /**
     * Obtenir la taille d'un fichier/dossier
     */
    public function testSize()
    {
        $expectedKeys = 'finished,num_dir,num_file,total_size';
        $size = $this->fileStation->size('/Download/Tests');
        $this->assertInstanceOf(Items::class, $size);
        $this->assertArrayHasKeys($size->toArray(), $expectedKeys);
    }

    /**
     * Obtenir l'url d'une image
     */
    public function testThumb()
    {
        $thumbUrl = $this->fileStation->thumb('/Download/Tests/chevrolet.jpg');
        $this->assertInternalType('string', $thumbUrl);
        $this->assertEquals($thumbUrl, filter_var($thumbUrl, FILTER_VALIDATE_URL));
    }

    /**
     * Obtenir le code MD d'un fichier
     */
    public function testMd5File()
    {
        $md5 = $this->fileStation->md5File('/Download/Tests/chevrolet.jpg');
        $this->assertInstanceOf(Items::class, $md5);
        $this->assertArrayHasKeys($md5->toArray(), ['finished', 'md5']);
    }

    /**
     * Vérifier les permissions sur un fichier
     */
    public function testCheckPerm()
    {
        $response = $this->fileStation->checkPerm('/Download/Tests', 'fake.txt');
        $this->assertInstanceOf(Items::class, $response);
    }

//    public function testUploadFile()
//    {
//        $pkg = $this->makeFileStationPackage();
//        $response = $pkg->uploadFile(__FILE__, '/Download');
//        $this->assertInstanceOf(Items::class, $response);
//    }

    /**
     * Télécharger une fichier
     */
    public function testDownload()
    {
        $response = $this->fileStation->download('/Download/Tests/chevrolet.jpg');
        $this->assertInternalType('string', $response);
    }

    /**
     * Créer et supprimer un dossier
     */
    public function testCreateDeleteFolder()
    {
        $response = $this->fileStation->createFolder('/Download/Tests', 'Fake');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), 'folders');
        $this->assertTrue($this->fileStation->delete('/Download/Tests/Fake'));
    }

    /**
     * Copier un fichier
     */
    public function testCopy()
    {
        $response = $this->fileStation->copy(
            '/Download/chevrolet.jpg',
            '/Download/Tests/chevrolet.jpg',
            ['overwrite' => 'true']
        );
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), 'dest_folder_path');
        $this->assertEquals('/Download/Tests/chevrolet.jpg', $response->get('dest_folder_path'));
    }

    /**
     * Lire le contenu d'une archive
     */
    public function testExtractList()
    {
        // Créer une archive
        $response = $this->fileStation->compress('/Download/Tests', '/Download/Tests/tests.zip');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), 'dest_file_path,finished,progress');

        // Lire le contenu
        $response = $this->fileStation->extractList('/Download/Tests/tests.zip');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), 'items,total');
    }

    /**
     * Extraire une archive
     * La méthode stop retourne une exception 401, mais l'extraction est faite
     */
    public function testExtract()
    {
        $expectedResponseKeys = 'finished,progress,dest_folder_path';
        $this->expectExceptionWithCode(SynologyException::class, 401);
        $response = $this->fileStation->extract('/Download/Tests/tests.zip', '/Download/Tests/extractions');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), $expectedResponseKeys);
    }

    /**
     * Obtenir les tâches non terminées
     */
    public function testBackgroundTasks()
    {
        $response = $this->fileStation->backgroundTask();
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), 'offset,tasks,total');
    }

    /**
     * Supprimer les tâches terminées
     */
    public function testClearTasks()
    {
        $this->assertTrue($this->fileStation->clearFinishedTasks());
    }

    /**
     * Obtenir le codepage de l'instance
     */
    public function testGetCodePage()
    {
        $this->assertEquals('fre', $this->fileStation->getCodePage());
    }

    /**
     * Renommer un fichier
     */
    public function testRename()
    {
        $expectedResponseKeys = 'files';
        $expectedItemKeys = 'isdir,name,path';
        $initName = 'Syno_UsersGuide_NAServer_fra.pdf';
        $response = $this->fileStation->rename('/Download/Tests/' . $initName, 'Synology_UsersGuide_fra.pdf');
        $this->assertInstanceOf(Items::class, $response);
        $this->assertArrayHasKeys($response->toArray(), $expectedResponseKeys);
        $this->assertArrayHasKeys($response->get('files')->first()->toArray(), $expectedItemKeys);
        // Se remettre à l'état initial
        $this->fileStation->rename('/Download/Tests/Synology_UsersGuide_fra.pdf', $initName);
    }

    public function tearDown()
    {
        unset($this->fileStation);
    }
}
