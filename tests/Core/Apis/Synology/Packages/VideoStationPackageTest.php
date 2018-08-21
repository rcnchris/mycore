<?php
namespace Tests\Rcnchris\Core\Apis\Synology\Packages;

use Rcnchris\Core\Apis\Synology\Packages\VideoStationPackage;
use Rcnchris\Core\Apis\Synology\SynologyAPIEntity;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\Core\Apis\Synology\SynologyBaseTestCase;

class VideoStationPackageTest extends SynologyBaseTestCase
{
    /**
     * @var VideoStationPackage
     */
    private $videoStation;

    /**
     * Constructeur
     */
    public function setUp()
    {
        $this->videoStation = $this->makeVideoStationPackage();
    }

    /**
     * @return \Rcnchris\Core\Apis\Synology\Packages\VideoStationPackage
     */
    public function makeVideoStationPackage()
    {
        return new VideoStationPackage($this->makeSynoAPI());
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Synology Package : ' . $this->videoStation->getName());
        $this->assertInstanceOf(VideoStationPackage::class, $this->videoStation);
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->videoStation->getVersion());
    }

    public function testConfig()
    {
        $config = $this->videoStation->config();
        $this->assertInstanceOf(Items::class, $config);
        $this->assertNotEmpty($config->toArray());
    }

    public function testCollections()
    {
        $items = $this->videoStation->collections();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $this->videoStation->collections([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testVideosOfCollection()
    {
        $collection = $this->videoStation->collections()->get('collections')->first();
        $items = $this->videoStation->videosOfCollection($collection->id);
        $this->assertInstanceOf(Items::class, $items);
    }

    public function testMovies()
    {
        $items = $this->videoStation->movies();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());
        $this->assertTrue($items->has('movies'));

        $items = $this->videoStation->movies([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testMovie()
    {
        $movie = $this->videoStation->movies()->get('movies')->first();
        $this->assertInstanceOf(Items::class, $this->videoStation->movie($movie->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $this->videoStation->movie($movie->id, true));
    }

    public function testVideos()
    {
        $items = $this->videoStation->videos();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $this->videoStation->videos([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testVideo()
    {
        $video = $this->videoStation->videos()->get('videos')->first();
        $this->assertInstanceOf(Items::class, $this->videoStation->video($video->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $this->videoStation->video($video->id, true));
    }

    public function testTvShows()
    {
        $items = $this->videoStation->tvshows();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $this->videoStation->tvshows([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testTvShow()
    {
        $tvshow = $this->videoStation->tvshows()->get('tvshows')->first();
        $this->assertInstanceOf(Items::class, $this->videoStation->tvshow($tvshow->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $this->videoStation->tvshow($tvshow->id, true));
    }

    public function testEpisodes()
    {
        $items = $this->videoStation->episodes();
        $this->assertInstanceOf(Items::class, $items);
        $this->assertNotEmpty($items->toArray());

        $items = $this->videoStation->episodes([], 'title');
        $this->assertInternalType('array', $items);
        $this->assertNotEmpty($items);
    }

    public function testEpisode()
    {
        $tvshow = $this->videoStation->episodes()->get('episodes')->first();
        $this->assertInstanceOf(Items::class, $this->videoStation->episode($tvshow->id));
        $this->assertInstanceOf(SynologyAPIEntity::class, $this->videoStation->episode($tvshow->id, true));
    }
}
