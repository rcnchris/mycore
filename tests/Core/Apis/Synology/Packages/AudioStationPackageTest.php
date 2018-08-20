<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class AudioStationPackageTest extends BaseTestCase
{

    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage
     */
    public function makeAudioStationPackage()
    {
        return new AudioStationPackage($this->makeSynoAPI());
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : AudioStation');
        $this->assertInstanceOf(AudioStationPackage::class, $this->makeAudioStationPackage());
    }

    public function testConfig()
    {
        $config = $this->makeAudioStationPackage()->config();
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
    }

    public function testAlbums()
    {
        $items = $this->makeAudioStationPackage()->albums(null, ['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('albums'));
        $this->assertNotEmpty($items->toArray());
        $this->assertCount(10, $items->get('albums')->toArray());
    }

    public function testArtists()
    {
        $items = $this->makeAudioStationPackage()->artists(['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('artists'));
        $this->assertNotEmpty($items->toArray());
        $this->assertCount(10, $items->get('artists')->toArray());
    }

    public function testComposers()
    {
        $items = $this->makeAudioStationPackage()->composers(['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('composers'));
        $this->assertNotEmpty($items->toArray());
        $this->assertCount(10, $items->get('composers')->toArray());
    }

    public function testFolders()
    {
        $items = $this->makeAudioStationPackage()->folders(['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('items'));
        $this->assertNotEmpty($items->toArray());
        $this->assertCount(10, $items->get('items')->toArray());
    }

    public function testFolder()
    {
        $api = $this->makeAudioStationPackage();
        $folder = $api->folders(['limit' => 10])->get('items')->first();
        $this->assertInstanceOf(Items::class, $api->folder($folder->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->folder($folder->id, true));
    }

    public function testRadios()
    {
        $items = $this->makeAudioStationPackage()->radios();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('radios'));
        $this->assertNotEmpty($items->toArray());
    }

    public function testRemotes()
    {
        $items = $this->makeAudioStationPackage()->remotes();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('players'));
        $this->assertNotEmpty($items->toArray());
    }

    public function testRemote()
    {
        $api = $this->makeAudioStationPackage();
        $players = $api->remotes()->get('players');
        if (!$players->isEmpty()) {
            $this->assertInstanceOf(Items::class, $api->remote($players->first()->id));
            $this->assertInstanceOf(SynologyAPIEntity::class, $api->remote($players->first()->id, true));
        } else {
            $this->markTestSkipped('Aucun lecteur à tester');
        }
    }

    public function testRemotePlaylist()
    {
        $api = $this->makeAudioStationPackage();
        $players = $api->remotes()->get('players');
        if (!$players->isEmpty()) {
            $this->assertInstanceOf(Items::class, $api->remotePlaylist($players->first()->id));
            $this->assertInstanceOf(SynologyAPIEntity::class, $api->remotePlaylist($players->first()->id, true));
        } else {
            $this->markTestSkipped('Aucun lecteur à tester');
        }
    }

    public function testServers()
    {
        $items = $this->makeAudioStationPackage()->servers(['limit' => 2]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('list'));
        $this->assertNotEmpty($items->toArray());
        $this->assertEquals(2, $items->get('list')->count());
    }

    public function testSongs()
    {
        $items = $this->makeAudioStationPackage()->songs(['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('songs'));
        $this->assertNotEmpty($items->toArray());
        $this->assertEquals(10, $items->get('songs')->count());
    }

    public function testSong()
    {
        $api = $this->makeAudioStationPackage();
        $song = $api->songs(['limit' => 10])->get('songs')->first();
        $this->assertInstanceOf(Items::class, $api->song($song->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->song($song->id, true));
    }

    public function testLyricsOfSong()
    {
        $api = $this->makeAudioStationPackage();
        $song = $api->songs(['limit' => 10])->get('songs')->first();
        $this->assertInternalType('string', $api->lyricsOfSong($song->id));
    }

    public function testGenres()
    {
        $items = $this->makeAudioStationPackage()->genres(['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('genres'));
        $this->assertNotEmpty($items->toArray());
        $this->assertCount(10, $items->get('genres')->toArray());
    }

    public function testPlaylists()
    {
        $items = $this->makeAudioStationPackage()->playlists();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('playlists'));
        $this->assertNotEmpty($items->toArray());
    }

    public function testPlaylist()
    {
        $api = $this->makeAudioStationPackage();
        $playlist = $api->playlists()->get('playlists')->first();
        $this->assertInstanceOf(Items::class, $api->playlist($playlist->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->playlist($playlist->id, true));
    }

    public function testSearchSongs()
    {
        $items = $this->makeAudioStationPackage()->searchSongs('IAM', ['limit' => 10]);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertTrue($items->has('songs'));
        $this->assertNotEmpty($items->toArray());
    }
}
