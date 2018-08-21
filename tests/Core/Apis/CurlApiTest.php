<?php
namespace Tests\Rcnchris\Core\Apis;

use Rcnchris\Core\Apis\ApiException;
use Rcnchris\Core\Apis\CurlAPI;
use Rcnchris\Core\Tools\Items;
use SimpleXMLElement;
use Tests\Rcnchris\BaseTestCase;

class CurlApiTest extends BaseTestCase
{

    /**
     * Liste des URL d'api à tester
     *
     * @var Items
     */
    private $apis;

    public function setUp()
    {
        $this->apis = new Items([
            'dog' => [
                'baseUrl' => 'https://dog.ceo/api',
                'exec' => [
                    'breeds' => 'https://dog.ceo/api/breeds/list/all',
                    'image' => 'https://dog.ceo/api/breeds/image/random',
                ]
            ],
            'cats' => [
                'baseUrl' => 'http://thecatapi.com/api',
                'exec' => [
                    'image' => 'http://thecatapi.com/api/images/get?format=xml&results_per_page=1'
                ]
            ],
            'geo' => [
                'baseUrl' => 'https://geo.api.gouv.fr',
                'exec' => [
                    'communes' => 'https://geo.api.gouv.fr/communes?codePostal=83000&fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre',
                ]
            ],
            'gouv' => [
                'baseUrl' => 'https://etablissements-publics.api.gouv.fr/v1',
                'exec' => [
                    'organismes' => 'https://etablissements-publics.api.gouv.fr/v1/organismes/83/cpam'
                ]
            ],
            'allo' => [
                'baseUrl' => 'http://api.allocine.fr/rest/v3'
            ]
        ]);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - CurlAPI');
        foreach ($this->apis->extract('baseUrl')->toArray() as $url) {
            $this->assertInstanceOf(CurlAPI::class, $this->makeCurlApi($url),
                $this->getMessage("L'URL $url ne retourne pas la classe CurlAPI"));
        }
    }

    public function testInstanceWithWrongUrl()
    {
        $this->expectException(ApiException::class);
        $this->makeCurlApi('https://dog ceo/api');
    }

    public function testCurlInfos()
    {
        $api = $this->makeCurlApi($this->apis->get('dog.baseUrl'));
        $this->assertInternalType('array', $api->getCurlInfos());
        $this->assertEquals($this->apis->get('dog.baseUrl'), $api->getCurlInfos('url'));
    }

    public function testGetBaseUrl()
    {
        $url = $this->apis->get('dog.baseUrl');
        $this->assertEquals($url, $this->makeCurlApi($url)->getBaseUrl());
        $this->assertEquals($url, $this->makeCurlApi($url)->getUrl());
    }

    public function testGetExec()
    {
        foreach ($this->apis->extract('exec')->toArray() as $k => $urls) {
            foreach ($urls as $url) {
                //$this->ekoMsgInfo($url);
                $this->assertInstanceOf(CurlAPI::class, $this->makeCurlApi($url)->exec());
            }
        }
    }

    public function testGetExecWithWrongUrl()
    {
        $url = 'https://dog.fake/api';
        $this->assertNull($this->makeCurlApi($url)->exec()->get());
    }

    public function testGetContentType()
    {
        $url = $this->apis->get('geo.exec.communes');
        $this->assertEquals('application/json', $this->makeCurlApi($url)->exec()->getContentType());
    }

    public function testGetCharset()
    {

        $url = $this->apis->get('geo.exec.communes');
        $this->assertEquals('utf-8', $this->makeCurlApi($url)->exec()->getCharset(),
            $this->getMessage("L'URL $url ne retourne pas un charset null"));

        $url = $this->apis->get('gouv.exec.organismes');
        $this->assertEquals('UTF-8', $this->makeCurlApi($url)->exec()->getCharset(),
            $this->getMessage("L'URL $url ne retourne pas un charset UTF-8"));
    }

    public function testGetHttpCode200()
    {
        $url = $this->apis->get('geo.exec.communes');
        $this->assertEquals(200, $this->makeCurlApi($url)->exec()->getHttpCode());

        $url = $this->apis->get('gouv.exec.organismes');
        $this->assertEquals(200, $this->makeCurlApi($url)->exec()->getHttpCode());
    }

    public function testServerIP()
    {
        $url = $this->apis->get('dog.exec.image');
        $this->assertInternalType('string', $this->makeCurlApi($url)->exec()->getServerIP());
    }

    public function testGetHttpCodes()
    {
        $url = $this->apis->get('cats.exec.image');
        $this->assertNotEmpty($this->makeCurlApi($url)->getHttpCodes());
    }

    public function testGetHttpCodesWithCode()
    {
        $url = $this->apis->get('cats.exec.image');
        $this->assertInternalType('string', $this->makeCurlApi($url)->getHttpCodes(200));
    }

    public function testGetJsonToItems()
    {
        $url = $this->apis->get('dog.exec.breeds');
        $api = $this->makeCurlApi($url)->exec();
        $this->assertEquals('application/json', $api->getContentType());
        $this->assertInternalType('string', $api->get());
        $this->assertInstanceOf(Items::class, $api->get('items'));
    }

    public function testGetXmlToSimpleXML()
    {
        $url = $this->apis->get('cats.exec.image');
        $api = $this->makeCurlApi($url)->exec();
        if ($api->getHttpCode() === 200) {
            $this->assertEquals('text/xml', $api->getContentType());
            $this->assertInternalType('string', $api->get());
            $this->assertInstanceOf(SimpleXMLElement::class, $api->get('xml'));
        } else {
            $this->markTestSkipped('API non disponible : ' . $api->getHttpCode());
        }
    }

    public function testGetWithFormat()
    {
        $url = $this->apis->get('dog.exec.breeds');
        $this->assertInstanceOf(Items::class, $this->makeCurlApi($url)->exec()->get('items'));
    }

    public function testAddUrlParts()
    {
        $url = $this->apis->get('geo.baseUrl');
        $api = $this->makeCurlApi($url);
        $api->addUrlParts('departements');
        $this->assertEquals($url . '/departements', $api->getUrl());
        $this->assertEquals($url, $api->getBaseUrl());
    }

    public function testAddUrlParamsWithArray()
    {
        $url = $this->apis->get('geo.baseUrl');
        $api = $this->makeCurlApi($url);
        $api->addUrlParts('communes');
        $params = [
            'codePostal' => '83000',
            'fields' => 'nom,code,population'
        ];
        $api->addUrlParams($params);
        $this->assertEquals($url . '/communes?codePostal=83000&fields=nom%2Ccode%2Cpopulation', $api->getUrl());
    }

    public function testAddUrlParamsWithOneParam()
    {
        $api = $this->makeCurlApi($this->apis->get('geo.baseUrl'))
            ->addUrlParts('communes')
            ->addUrlParams('codePostal', '83000');
        $this->assertEquals($this->apis->get('geo.baseUrl') . '/communes?codePostal=83000', $api->getUrl());
    }

    public function testAddUrlParamsWithErase()
    {
        $api = $this->makeCurlApi($this->apis->get('geo.baseUrl'))
            ->addUrlParts('communes')
            ->addUrlParams('codePostal', '83000', true);
        $this->assertEquals($this->apis->get('geo.baseUrl') . '/communes?codePostal=83000', $api->getUrl());
    }

    public function testGetLog()
    {
        $api = $this->makeCurlApi($this->apis->get('geo.baseUrl'))
            ->addUrlParts('communes')
            ->addUrlParams('codePostal', '83000')
            ->exec();
        $this->assertCount(1, $api->getLog());
        $this->assertArrayHasKey('class', current($api->getLog()));
        $this->assertArrayHasKey('details', current($api->getLog()));
    }

    public function testWithUsertAgent()
    {
        $browser = 'Dalvik/1.6.0 (Linux; U; Android 4.2.2; Nexus 4 Build/JDQ39E)';
        $api = $this->makeCurlApi($this->apis->get('geo.baseUrl'))
            ->addUrlParts('communes')
            ->addUrlParams('codePostal', '83000')
            ->withUserAgent($browser)
            ->exec();
        $this->assertInstanceOf(CurlAPI::class, $api);
    }

    public function testGetCurlVersion()
    {
        $this->assertInstanceOf(Items::class, $this->makeCurlApi($this->apis->get('dog.exec.image'))->getVersion());
        $this->assertInternalType('string',
            $this->makeCurlApi($this->apis->get('dog.exec.image'))->getVersion('version'));
    }

    public function testSetApiKey()
    {
        $api = $this->makeCurlApi($this->apis->get('allo.baseUrl'));
        $this->assertInstanceOf(CurlAPI::class, $api->setApiKey('29d185d98c984a359e6e6f26a0474269'));
        $this->assertEquals('29d185d98c984a359e6e6f26a0474269', $api->getApiKey());
    }
}
