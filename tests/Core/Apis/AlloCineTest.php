<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\AlloCine;
use Rcnchris\Core\Apis\CurlResponse;
use Tests\Rcnchris\BaseTestCase;

class AlloCineTest extends BaseTestCase {

    /**
     * Instance
     *
     * @var AlloCine
     */
    private $api;

    public function setUp()
    {
        $this->api = $this->makeApi();
    }

    /**
     * Obtenir une instance de l'API
     *
     * @return AlloCine
     */
    public function makeApi()
    {
        return new AlloCine();
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Allo Ciné');
        $this->assertInstanceOf(AlloCine::class, $this->api);
        $this->assertEquals('http://api.allocine.fr/rest/v3', $this->api->url());
    }

    public function testHasConstant()
    {
        $api = $this->api;
        $this->assertInternalType('string', $api::PARTNER);
        $this->assertInternalType('string', $api::KEY);
    }

    public function testSearch()
    {
        $response = $this->api->search('scarface');
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testMovie()
    {
        $response = $this->api->movie(27022);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testReviewList()
    {
        $response = $this->api->reviewlist(27022);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testTheaterList()
    {
        $response = $this->api->theaterlist('83190');
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testShowTimeList()
    {
        $response = $this->api->showtimelist('83000', 'P0201', 240850);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testMedia()
    {
        $response = $this->api->media(18408293);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testPerson()
    {
        $response = $this->api->person(1825);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testFilmography()
    {
        $response = $this->api->filmography(1825);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testMoviesList()
    {
        $response = $this->api->movielist(1825);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testTvSeries()
    {
        $response = $this->api->tvseries(4963);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testSeasons()
    {
        $response = $this->api->season(9730);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }

    public function testEpisode()
    {
        $response = $this->api->episode(363695);
        $this->assertInstanceOf(CurlResponse::class, $response);
    }
}
