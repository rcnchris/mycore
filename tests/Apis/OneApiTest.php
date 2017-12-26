<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\OneApi;
use Rcnchris\Core\Tools\Collection;
use Tests\Rcnchris\BaseTestCase;

class OneApiTest extends BaseTestCase {

    /**
     * @var OneAPI
     */
    private $api;

    /**
     * Constructeur.
     */
    public function setUp()
    {
        $this->api = $this->makeOneAPI();
    }

    /**
     * Obtenir une instance de OneAPI.
     *
     * @param string|null $url URL par défaut ou celle spécifiée
     *
     * @return \Rcnchris\Core\Apis\OneApi
     */
    public function makeOneAPI($url = null)
    {
        if (is_null($url)) {
            $url = 'https://randomuser.me/api';
        }
        return new OneAPI($url);
    }

    /**
     * TESTS.
     */

    /**
     * Obtenir l'instance de AlloCine.
     */
    public function testInstance()
    {
        $this->ekoTitre('API - OneAPI');
        $this->assertInstanceOf(OneAPI::class, $this->api);
    }

    public function testAddUrlPartWithoutBeginSlash()
    {
        $api = $this->makeOneAPI();
        $api->addUrlPart('fake');
        $this->assertEquals('https://randomuser.me/api/fake', $api->getUrl());
    }

    public function testAddUrlPartWithBeginSlash()
    {
        $api = $this->makeOneAPI();
        $api->addUrlPart('/fake');
        $this->assertEquals('https://randomuser.me/api/fake', $api->getUrl());
    }

    public function testAddParams()
    {
        $this->api->addParams(['results' => 1]);
        $response = $this->api->request('Tests unitaires');
        $this->assertEquals('https://randomuser.me/api?results=1', $this->api->getUrl());
    }

    public function testGetLog()
    {
        $this->api->addParams(['results' => 1]);
        $response = $this->api->request('Tests unitaires');
        $this->assertInstanceOf(Collection::class, $this->api->getLog());
    }
}
