<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\AlloCine;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class AlloCine2Test extends BaseTestCase
{
    /**
     * @return \Rcnchris\Core\Apis\AlloCine2
     */
    public function makeAlloCineAPI()
    {
        return new AlloCine();
    }

    public function testInstance()
    {
        $this->ekoTitre('API - AlloCiné');
        $this->assertInstanceOf(AlloCine::class, $this->makeAlloCineAPI());
    }

    public function testGetMethods()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertContains('movie', $api->getMethods());
    }

    public function testSearch()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->search('Scarface'));
    }

    public function testGetMovie()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->movie(900));
    }

    public function testGetReviewsOfMovie()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->reviewsOfMovie(900));
    }

    public function testGetTheaters()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->theaters(83190));
    }

    public function testGetShowTimes()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->showTimes(83190));
    }

    public function testGetMedia()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->media(18408293));
    }

    public function testGetPerson()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->person(1825));
    }

    public function testGetFilmographyOfPerson()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->filmographyOfPerson(1825));
    }

    public function testGetMoviesListOfPerson()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->currentMoviesOfPerson(1825));
    }

    public function testGetSerie()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->tvseries(4963));
    }

    public function testGetSeason()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->season(9730));
    }

    public function testGetEpisode()
    {
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->episode(230135));
    }
}
