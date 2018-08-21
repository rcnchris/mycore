<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\Core\Apis\Synology\SynologyBaseTestCase;

class AudioStationPackageTest extends SynologyBaseTestCase
{
    /**
     * @var AudioStationPackage
     */
    private $audioStation;

    /**
     * Constructeur
     */
    public function setUp()
    {
        $this->audioStation = $this->makeAudioStationPackage();
    }

    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage
     */
    private function makeAudioStationPackage()
    {
        return new AudioStationPackage($this->makeSynoAPI());
    }

    /**
     * Instance et titre
     */
    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : ' . $this->audioStation->getName());
        $this->assertInstanceOf(AudioStationPackage::class, $this->audioStation);
    }

    /**
     * Obtenir la configuration
     */
    public function testConfig()
    {
        $config = $this->audioStation->config();
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
    }

    /**
     * Obtenir la liste des albums musicaux
     */
    public function testAlbums()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'albums',
            [
                'expectedResponseKeys' => 'albums,offset,total',
                'itemsKey' => 'albums',
                'expectedItemKeys' => 'name',
                'extractKey' => 'name',
                'typeItemsKey' => 'int',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir la liste des artistes
     */
    public function testArtists()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'artists',
            [
                'expectedResponseKeys' => 'artists,offset,total',
                'itemsKey' => 'artists',
                'expectedItemKeys' => 'name',
                'extractKey' => 'name',
                'typeItemsKey' => 'int',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir la liste des compositeurs
     */
    public function testComposers()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'composers',
            [
                'expectedResponseKeys' => 'composers,offset,total',
                'itemsKey' => 'composers',
                'expectedItemKeys' => 'name',
                'extractKey' => 'name',
                'typeItemsKey' => 'int',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir la liste des dossiers
     */
    public function testFolders()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'folders',
            [
                'expectedResponseKeys' => 'items,offset,total',
                'itemsKey' => 'items',
                'expectedItemKeys' => 'id,is_personal,path,title,type',
                'extractKey' => 'title',
                'typeItemsKey' => 'string',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir un dossier
     */
    public function testFolder()
    {
        $folder = $this->audioStation->folders(['limit' => 1])->get('items')->first();
        $this->assertInstanceOf(Items::class, $this->audioStation->folder($folder->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $this->audioStation->folder($folder->id, true));
    }

    /**
     * Obtenir la liste des radios
     */
    public function testRadios()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'radios',
            [
                'expectedResponseKeys' => 'radios,offset,total',
                'itemsKey' => 'radios',
                'expectedItemKeys' => 'desc,id,title,type,url',
                'extractKey' => 'title',
                'typeItemsKey' => 'string',
                'params' => ['limit' => 2]
            ]
        );
    }

    /**
     * Obtenir la liste des lecteurs distants
     */
    public function testRemotes()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'remotes',
            [
                'expectedResponseKeys' => 'players',
                'itemsKey' => 'players',
                'expectedItemKeys' => 'id,is_multiple,name,password_protected,support_seek,support_set_volume,type',
                'extractKey' => 'name',
                'typeItemsKey' => 'string'
            ]
        );
    }

    /**
     * Obtenir un lecteur distant
     */
    public function testRemote()
    {
        $players = $this->audioStation->remotes()->get('players');
        if (!$players->isEmpty()) {
            $this->assertInstanceOf(Items::class, $this->audioStation->remote($players->first()->id));
            $this->assertInstanceOf(SynologyAPIEntity::class, $this->audioStation->remote($players->first()->id, true));
        } else {
            $this->markTestSkipped('Aucun lecteur à tester');
        }
    }

    public function testRemotePlaylist()
    {
        $players = $this->audioStation->remotes()->get('players');
        if (!$players->isEmpty()) {
            $this->assertInstanceOf(Items::class, $this->audioStation->remotePlaylist($players->first()->id));
            $this->assertInstanceOf(SynologyAPIEntity::class, $this->audioStation->remotePlaylist($players->first()->id, true));
        } else {
            $this->markTestSkipped('Aucun lecteur à tester');
        }
    }

    /**
     * Obtenir la liste des serveurs multimédias
     */
    public function testServers()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'servers',
            [
                'expectedResponseKeys' => 'list',
                'itemsKey' => 'list',
                'expectedItemKeys' => 'cover,id,path,title,type',
                'extractKey' => 'title',
                'typeItemsKey' => 'string',
                'params' => ['limit' => 2]
            ]
        );
    }

    /**
     * Obtenir la liste des morceaux
     */
    public function testSongs()
    {
        $items = $this->audioStation->songs(['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('songs'));
        $this->assertNotEmpty($items->toArray());
        $this->assertEquals(10, $items->get('songs')->count());
    }

    /**
     * Obtenir un morceaux par son identifiant
     */
    public function testSong()
    {
        $song = $this->audioStation->songs(['limit' => 10])->get('songs')->first();
        $this->assertInstanceOf(Items::class, $this->audioStation->song($song->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $this->audioStation->song($song->id, true));
    }

    public function testLyricsOfSong()
    {
        $song = $this->audioStation->songs(['limit' => 10])->get('songs')->first();
        $this->assertInternalType('string', $this->audioStation->lyricsOfSong($song->id));
    }

    /**
     * Obtenir la liste des genres
     */
    public function testGenres()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'genres',
            [
                'expectedResponseKeys' => 'genres,offset,total',
                'itemsKey' => 'genres',
                'expectedItemKeys' => 'name',
                'extractKey' => 'name',
                'typeItemsKey' => 'int',
                'params' => ['limit' => 3]
            ]
        );
    }

    /**
     * Obtenir les listes de lectures
     */
    public function testPlaylists()
    {
        $this->assertSynologyList(
            $this->audioStation,
            'playlists',
            [
                'expectedResponseKeys' => 'playlists,offset,total',
                'itemsKey' => 'playlists',
                'expectedItemKeys' => 'name',
                'extractKey' => 'name',
                'typeItemsKey' => 'string',
                'params' => ['limit' => 3]
            ]
        );
    }

    public function testPlaylist()
    {
        $api = $this->audioStation;
        $playlist = $api->playlists()->get('playlists')->first();
        $this->assertInstanceOf(Items::class, $api->playlist($playlist->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->playlist($playlist->id, true));
    }

    public function testSearchSongs()
    {
        $items = $this->audioStation->searchSongs('IAM', ['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('songs'));
        $this->assertNotEmpty($items->toArray());
    }

    public function tearDown()
    {
        unset($this->audioStation);
    }
}
