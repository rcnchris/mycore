<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\CurlResponse;
use Rcnchris\Core\Apis\OneAPI;
use Tests\Rcnchris\BaseTestCase;

class CurlResponseTest extends BaseTestCase {

    /**
     * @var string
     */
    public $urlRandomUser;

    /**
     * @var OneAPI
     */
    public $randomUserApi;

    /**
     * Réponse de RandomUser avec 1 user
     *
     * @var CurlResponse
     */
    public $response;

    public function setUp()
    {
        if ($this->getConfig('config.name') != 'local') {
            $this->markTestSkipped('Uniquement en local');
        }
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
        $response = $this->makeApi($this->urlRandomUser)->r(['results' => 1]);
        $this->assertInternalType('string', $response->get());
    }

    public function testGetType()
    {
        $response = $this
            ->makeApi($this->urlRandomUser)
            ->r(['results' => 1]);
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

    public function testGetUrl()
    {
        $this->assertEquals($this->urlRandomUser, $this->response->getUrl());
    }

    public function testIsJson()
    {
        $this->assertTrue($this->response->isJson());
    }

    public function testIsHtml()
    {
        $this->assertFalse($this->response->isHtml());
    }

    public function testIsText()
    {
        $this->assertFalse($this->response->isText());
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

    public function testToString()
    {
        $this->assertEquals($this->response->toJson(), (string)$this->response);
    }

    public function testGetWithValidUrl()
    {
        $api = $this->makeApi('https://randomuser.me/api');
        $response = $api->r();
        $this->assertInternalType('string', $response->get());
    }

    /**
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function testGetWithWrongUrl()
    {
        $api = $this->makeApi('https://randomuser.mz/api');
        $response = $api->r();
        $this->assertEquals('Could not resolve host: randomuser.mz', $response->get());
    }

    /**
     * 301
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function testGetWithMovedPermanentlyUrl()
    {
        $api = $this->makeApi('http://randomuser.me/api');
        $response = $api->r();
        $this->assertEquals('Moved Permanently', $response->get());
    }

    /**
     * 403
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function testGetWithForbiddenUrl()
    {
        $api = $this->makeApi('http://api.allocine.fr/rest/v3/search?q=Dinosaure&format=json&partner=100043982026&sed=20171229&sig=VKm5CXWOg37PVXN563cudvCmP9M%3D');
        $response = $api->r();
        $this->assertEquals('Forbidden', $response->get());
    }

    /**
     * 404
     * @throws \Rcnchris\Core\Apis\ApiException
     */
    public function testGetWithNotFoundUrl()
    {
        $api = $this->makeApi('http://api.allocine.fr/rest/v3');
        $response = $api->r(['q' => 'scarface']);
        $this->assertEquals('Not Found', $response->get());
    }

    public function testGetWithErrorUrl()
    {
        $api = $this->makeApi('http://api.allocine.fr/rest/v3/search?q=Dinosaure');
        $response = $api->r();
        $this->assertTrue($response->isHtml());
        $this->assertFalse($response->isJson());
        $this->assertNotEmpty($response->toArray());
        $this->assertInternalType('string', $response->toJson());
    }
}
