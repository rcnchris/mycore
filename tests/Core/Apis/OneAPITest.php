<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\ApiException;
use Rcnchris\Core\Apis\CurlResponse;
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

        $api->addParams('results', 3);
        $this->assertEquals(['results' => 3], $api->getParams(false));
    }

    public function testAddParamsWithArrayParam()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addParams(['results' => 3]);
        $this->assertEquals(['results' => 3], $api->getParams(false));
    }

    public function testAddParamsErase()
    {
        $api = $this->makeApi($this->urlRandomUser);

        $api->addParams('results', 3, true);
        $this->assertCount(1, $api->getParams(false));

        $api->addParams('ola', 2, false);
        $this->assertCount(2, $api->getParams(false));

        $api->addParams(['ole' => 2]);
        $this->assertCount(3, $api->getParams(false));

        $api->addParams(['ole' => 2], null, true);
        $this->assertCount(1, $api->getParams(false));
    }

    public function testGetBuildParams()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $params = [
            'results' => 3
            , 'ola' => 'ole'
        ];
        $api->addParams($params);
        $this->assertEquals($params, $api->getParams(false));
        $this->assertEquals('?results=3&ola=ole', $api->getParams());
    }

    public function testGetUrl()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addParams('results', 1);
        $this->assertEquals($this->urlRandomUser . '?results=1', $api->url());
        $this->assertEquals($this->urlRandomUser, $api->url(false));
    }

    public function testRequest()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $response = $api->r();
        $this->assertInstanceOf(CurlResponse::class, $response);
        $this->assertNotEmpty($response->toArray());
    }

    public function testRequestWithParamString()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $response = $api->addParams('results', 3)->r();
        $this->assertInternalType('string', $response->toJson());
        $this->assertNotEmpty($response->toArray());
    }

    public function testRequestWithParamArray()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $response = $api->r(['results' => 3]);
        $this->assertInternalType('string', $response->toJson());
        $this->assertNotEmpty($response->toArray());
    }

    public function testRequestWithValidUrlWithParams()
    {
        $api = $this->makeApi();
        $this->assertNotEmpty($api->r('https://randomuser.me/api?results=3')->toArray());
    }

    public function testRequestWithValidUrlWithAddParams()
    {
        $api = $this->makeApi();
        $api->addParams('results', 3);
        $response = $api->r('https://randomuser.me/api')->toArray();
        $this->assertNotEmpty($response);
        $this->assertCount(1, $response['results']);
    }

    public function testRequestWithoutUrl()
    {
        $api = $this->makeApi('');
        $this->expectException(ApiException::class);
        $api->r();
    }

    public function testWithUserAgent()
    {
        $api = $this->makeApi($this->urlRandomUser)->withUserAgent();
        $this->assertCount(3, $api->addParams('results', 3)->r()->toArray('results'));
    }

    public function testGetLog()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addParams('results', 3)->r();
        $api->addParams('results', 1)->r();
        $this->assertCount(2, $api->getLog());
    }

    public function testAddUrlPart()
    {
        $api = $this->makeApi($this->urlRandomUser);
        $api->addUrlPart('test/add/part');
        $this->assertEquals($this->urlRandomUser . '/' . 'test/add/part', $api->url(false));
    }
}
