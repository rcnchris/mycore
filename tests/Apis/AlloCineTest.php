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
        //var_dump($response);
        //var_dump($response->url());
        $this->assertInstanceOf(CurlResponse::class, $response);
    }
}
