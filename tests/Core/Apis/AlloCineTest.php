<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\AlloCine;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class AlloCineTest extends BaseTestCase
{
    /**
     * @return \Rcnchris\Core\Apis\AlloCine
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
        $this->ekoMessage("Recherche");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->search('Scarface'));
    }

    public function testGetMovie()
    {
        $this->ekoMessage("Obtenir un film");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->movie(900));
    }

    public function testGetReviewsOfMovie()
    {
        $this->ekoMessage("Critiques d'un film");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->reviewsOfMovie(900));
    }

    public function testGetTheaters()
    {
        $this->ekoMessage("Recherche de cinémas");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->theaters(83190));
    }

    public function testGetShowTimes()
    {
        $this->ekoMessage("Séances pour un code postal");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->showTimes(83190));
    }

    public function testGetMedia()
    {
        $this->ekoMessage("Médias");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->media(18408293));
    }

    public function testGetPerson()
    {
        $this->ekoMessage("Personne");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->person(1825));
    }

    public function testGetFilmographyOfPerson()
    {
        $this->ekoMessage("Filmographie");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->filmographyOfPerson(1825));
    }

    public function testGetMoviesListOfPerson()
    {
        $this->ekoMessage("Films d'une personne");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->currentMoviesOfPerson(1825));
    }

    public function testGetSerie()
    {
        $this->ekoMessage("Séries");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->tvseries(4963));
    }

    public function testGetSeason()
    {
        $this->ekoMessage("Saison d'une série");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->season(9730));
    }

    public function testGetEpisode()
    {
        $this->ekoMessage("Episode d'une série");
        $api = $this->makeAlloCineAPI();
        $this->assertInstanceOf(Items::class, $api->episode(230135));
    }
}
