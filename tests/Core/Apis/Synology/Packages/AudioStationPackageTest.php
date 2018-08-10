<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\AudioStationPackage;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class AudioStationPackageTest extends BaseTestCase
{

    public function makeAudioStationPackage()
    {
        return new AudioStationPackage($this->makeSynoAPI());
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology AudioStation Package');
        $this->assertInstanceOf(AudioStationPackage::class, $this->makeAudioStationPackage());
    }

    public function testGetAlbums()
    {
        $albums = $this->makeAudioStationPackage()->albums();
        $this->assertInstanceOf(Items::class, $albums);
        $this->assertTrue($albums->has('albums'));
    }

    public function testGetPlaylists()
    {
        $playlists = $this->makeAudioStationPackage()->playlists();
        $this->assertInstanceOf(Items::class, $playlists);
        $this->assertTrue($playlists->has('playlists'));
    }

    public function testSearchSong()
    {
        $songs = $this->makeAudioStationPackage()->searchSong('IAM');
        $this->assertInstanceOf(Items::class, $songs);
        $this->assertTrue($songs->has('songs'));
    }
}