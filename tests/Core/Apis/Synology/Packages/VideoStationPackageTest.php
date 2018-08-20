<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\VideoStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class VideoStationPackageTest extends BaseTestCase
{
    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\VideoStationPackage
     */
    public function makeVideoStationPackage()
    {
        return new VideoStationPackage($this->makeSynoAPI());
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : VideoStation');
        $this->assertInstanceOf(VideoStationPackage::class, $this->makeVideoStationPackage());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->makeVideoStationPackage()->getVersion());
    }

    public function testConfig()
    {
        $api = $this->makeVideoStationPackage();
        $config = $api->config();
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
    }

    public function testCollections()
    {
        $api = $this->makeVideoStationPackage();

        $items = $api->collections();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $api->collections([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testVideosOfCollection()
    {
        $api = $this->makeVideoStationPackage();
        $collection = $api->collections()->get('collections')->first();
        $items = $api->videosOfCollection($collection->id);
        $this->assertInstanceOf(Items::class, $items);
    }

    public function testMovies()
    {
        $api = $this->makeVideoStationPackage();

        $items = $api->movies();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
        $this->assertTrue($items->has('movies'));

        $items = $api->movies([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testMovie()
    {
        $api = $this->makeVideoStationPackage();
        $movie = $api->movies()->get('movies')->first();
        $this->assertInstanceOf(Items::class, $api->movie($movie->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->movie($movie->id, true));
    }

    public function testVideos()
    {
        $api = $this->makeVideoStationPackage();

        $items = $api->videos();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $api->videos([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testVideo()
    {
        $api = $this->makeVideoStationPackage();
        $video= $api->videos()->get('videos')->first();
        $this->assertInstanceOf(Items::class, $api->video($video->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->video($video->id, true));
    }

    public function testTvShows()
    {
        $api = $this->makeVideoStationPackage();

        $items = $api->tvshows();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $api->tvshows([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testTvShow()
    {
        $api = $this->makeVideoStationPackage();
        $tvshow = $api->tvshows()->get('tvshows')->first();
        $this->assertInstanceOf(Items::class, $api->tvshow($tvshow->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->tvshow($tvshow->id, true));
    }

    public function testEpisodes()
    {
        $api = $this->makeVideoStationPackage();

        $items = $api->episodes();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $api->episodes([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testEpisode()
    {
        $api = $this->makeVideoStationPackage();
        $tvshow = $api->episodes()->get('episodes')->first();
        $this->assertInstanceOf(Items::class, $api->episode($tvshow->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $api->episode($tvshow->id, true));
    }
}
