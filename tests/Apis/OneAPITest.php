<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\ApiException;
use Rcnchris\Core\Apis\OneAPI;
use Tests\Rcnchris\BaseTestCase;

class OneAPITest extends BaseTestCase {

    /**
     * URL de l'API RandomUser
     *
     * @var string
     */
    private $urlRandomUser;

    public function setUp()
    {
        $this->urlRandomUser = 'https://randomuser.me/api';
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

    public function testInstance()
    {
        $this->ekoTitre('API - OneAPI');
        $this->assertInstanceOf(OneAPI::class, $this->makeApi($this->urlRandomUser));
    }

    public function testGetCurl()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $this->assertNotEmpty($api->getCurl());
    }

    public function testGetCurlOptions()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $this->assertNotEmpty($api->getCurlInfos());
        $this->assertEquals('https://randomuser.me/api', $api->getCurlInfos('url'));
    }

    public function testAddQueryWithStringParam()
    {
        $api = $this->makeApi($this->urlRandomUser);

        $api->addQuery('results', 3);
        $this->assertEquals(['results' => 3], $api->getQueries(false));

        $api->addQuery(['ola' => 'ole'], null, false);
        $this->assertEquals(['results' => 3, 'ola' => 'ole'], $api->getQueries(false));
    }

    public function testAddQueryWithArrayParam()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addQuery(['results' => 3]);
        $this->assertEquals(['results' => 3], $api->getQueries(false));
    }

    public function testGetBuildQueries()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addQuery([
            'results' => 3
            , 'ola' => 'ole'
        ]);
        $this->assertEquals('results=3&ola=ole', $api->getQueries());
    }

    public function testGetUrl()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addQuery('results', 1);
        $this->assertEquals($this->urlRandomUser . '?results=1', $api->url());
    }

    public function testRequest()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $response = $api->addQuery('results', 3)->request();

        $this->assertInternalType('string', $response->toJson());
        $this->assertNotEmpty($response->toArray());
    }

    public function testRequestWithValidUrl()
    {
        $api = $this->makeApi();
        $this->assertNotEmpty($api->request('https://randomuser.me/api?results=3')->toArray());
    }

    public function testRequestWithoutUrl()
    {
        $api = $this->makeApi('');

        $this->expectException(ApiException::class);
        $api->request();

        $this->expectException(ApiException::class);
        $api->toArray();
    }

    public function testRequestWithWrongUrl()
    {
        $api = $this->makeApi('http://nexiste/pas');
        $this->assertFalse($api->request()->toArray());

        $api = $this->makeApi('https://randomuser.me/fake');
        $this->assertInternalType('string', $api->request()->toJson());
    }

    public function testToArrayWithoutRequest()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addQuery('results', 3);
        $this->assertNotEmpty($api->toArray());
    }

    public function testToJsonWithoutRequest()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addQuery('results', 3);
        $this->assertInternalType('string', $api->toJson());
    }

    public function testGetProperty()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addQuery('results', 3);
        $this->assertCount(3, $api->results);
    }
}