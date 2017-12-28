<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\CurlResponse;
use Rcnchris\Core\Apis\OneAPI;
use Tests\Rcnchris\BaseTestCase;

class CurlResponseTest extends BaseTestCase {

    /**
     * @var string
     */
    private $urlRandomUser;

    /**
     * @var OneAPI
     */
    private $randomUserApi;

    /**
     * Réponse de RandomUser avec 1 user
     *
     * @var CurlResponse
     */
    private $response;

    public function setUp()
    {
        $this->urlRandomUser = 'https://randomuser.me/api';
        $this->randomUserApi = $this->makeApi($this->urlRandomUser);
        $this->response = $this->makeResponse();
    }

    /**
     * Obtenir une instance
     *
     * @param string $url
     *
     * @return OneAPI
     */
    public function makeApi($url = null)
    {
        return new OneAPI($url);
    }

    public function makeResponse($params = null)
    {
        return $this->randomUserApi->r($params);
    }

    public function testInstanceResponse()
    {
        $this->ekoTitre('API - Response');
        $this->assertInstanceOf(CurlResponse::class, $this->response);
    }

    public function testGetResponse()
    {
        $this->assertInternalType('string', $this->makeResponse()->get());
    }

    public function testGetType()
    {
        $response = $this->makeResponse();
        $this->assertEquals('string', $response->getType());
    }

    public function testGetHttpCode()
    {
        $this->assertEquals(200, $this->response->getHttpCode());
    }

    public function testGetContentType()
    {
        $this->assertEquals('application/json', $this->response->getContentType());
    }

    public function testGetCharset()
    {
        $this->assertEquals('utf-8', $this->response->getCharset());
    }

    public function testIsJson()
    {
        $this->assertTrue($this->response->isJson());
    }

    public function testToArray()
    {
        $this->assertNotEmpty($this->response->toArray());
        $this->assertNotEmpty($this->response->toArray('info'));
    }

    public function testToJson()
    {
        $this->assertNotEmpty($this->response->toJson());
        $this->assertNotEmpty($this->response->toJson('info'));
    }

    public function testGetWithValidUrl()
    {
        $api = $this->makeApi('https://randomuser.me/api');
        $response = $api->r();
        $this->assertInternalType('string', $response->get());
    }

    public function testGetWithMovedPermanentlyUrl()
    {
        $api = $this->makeApi('http://randomuser.me/api');
        $response = $api->r();
        $this->assertEquals('301 : Moved Permanently', $response->get());
    }

    public function testGetWithWrongUrl()
    {
        $api = $this->makeApi('https://randomuser.mz/api');
        $response = $api->r();
        $this->assertEquals('Could not resolve host: randomuser.mz', $response->get());
    }

    public function testGetWithNotFoundUrl()
    {
        $api = $this->makeApi('http://api.allocine.fr/rest/v3');
        $response = $api->r(['q' => 'scarface']);
        $this->assertEquals('404 : Not Found', $response->get());
    }
}
