<?php
namespace Tests\Rcnchris\Core\Apis;

use Intervention\Image\Image;
use Rcnchris\Core\Apis\Curl;
use Rcnchris\Core\Tools\Items;
use Tests\Rcnchris\BaseTestCase;

class CurlTest extends BaseTestCase
{

    /**
     * @var Curl
     */
    private $curl;

    /**
     * URLs de base des APIs
     *
     * @var array
     */
    private $baseUrls = [
        'geo' => 'https://geo.api.gouv.fr',
        'user' => 'https://randomuser.me/api/',
        'image' => 'http://placekitten.com/200/300'
    ];

    public function setUp()
    {
        $this->curl = $this->makeCurl($this->baseUrls['geo']);
    }

    /**
     * @param string|null $url
     *
     * @return \Rcnchris\Core\Apis\Curl
     */
    public function makeCurl($url = null)
    {
        return new Curl($url);
    }

    public function testInstance()
    {
        $this->ekoTitre('API - Curl');

        $this->assertInstanceOf(Curl::class, $this->makeCurl());

        foreach ($this->baseUrls as $name => $url) {
            $curl = $this->makeCurl($url);
            $this->assertInstanceOf(Curl::class, $curl);
        }
    }

    public function testSetUrl()
    {
        $this->ekoMessage("Définir une URL");
        $url = $this->baseUrls['geo'];
        $curl = $this->makeCurl();
        $curl->setUrl($url);
        $this->assertEquals($url, $curl->getBaseUrl());
        $this->assertEquals($url, $curl->getUrl());
    }

    public function testSetUrlWithWrongUrl()
    {
        $this->ekoMessage("Mauvaise une URL");
        $url = 'https://geo.api.go uv.fr';
        $curl = $this->makeCurl();
        $this->assertFalse($curl->setUrl($url));
    }

    public function testGetCurl()
    {
        $this->ekoMessage("Ressource cURL");
        $this->assertInternalType('resource', $this->curl->getCurl());
    }

    public function testParseUrl()
    {
        $this->ekoMessage("Parties de l'URL");
        $this->assertInstanceOf(Items::class, $this->curl->parseUrl());
        $this->assertEquals('https', $this->curl->parseUrl('scheme'));
        $this->assertFalse($this->curl->parseUrl('fake'));
    }

    public function testExec()
    {
        $this->ekoMessage("Exécution");
        $curl = $this->makeCurl($this->baseUrls['user']);

        $this->assertInstanceOf(Curl::class, $curl);
        $this->assertFalse($curl->getParams());
        $response = $curl->exec('Utilisateur')->getResponse();
        $this->assertInstanceOf(Items::class, $response);
        $this->assertTrue($response->has('results'));

        $this->assertInstanceOf(Items::class, $curl->getLog());
        $this->assertEquals(1, $curl->getLog()->count());

        $this->assertEquals(200, $curl->getHttpCode());
        $this->assertEquals('application/json', $curl->getContentType());
        $this->assertEquals('utf-8', $curl->getCharset());
        $this->assertInternalType('string', $curl->getServerIP());
    }

    public function testExecWithStringParts()
    {
        $this->ekoMessage("Exécution avec ajout de parties en chaîne de caractères");
        $url = $this->baseUrls['geo'];
        $curl = $this->makeCurl($url);
        $this->assertInstanceOf(Curl::class, $curl);
        $this->assertInstanceOf(Curl::class, $curl->withParts('regions'));
        $this->assertEquals($url . '/regions', $curl->getUrl());
        $response = $curl->exec('Régions')->getResponse();
        $this->assertInstanceOf(Items::class, $response);
    }

    public function testExecWithArrayParts()
    {
        $this->ekoMessage("Exécution avec ajout de parties en tableau");
        $url = $this->baseUrls['geo'];
        $curl = $this->makeCurl($url);
        $this->assertInstanceOf(Curl::class, $curl);
        $this->assertInstanceOf(Curl::class, $curl->withParts(['regions', '93', 'departements']));
        $this->assertEquals($url . '/regions/93/departements', $curl->getUrl());
        $response = $curl->exec('Départements de la région PACA')->getResponse();
        $this->assertInstanceOf(Items::class, $response);
    }

    public function testExecWithParams()
    {
        $this->ekoMessage("Exécution avec paramètres");
        $url = $this->baseUrls['geo'];
        $curl = $this->makeCurl($url);
        $this->assertInstanceOf(Curl::class, $curl);
        $this->assertInstanceOf(
            Curl::class,
            $curl
                ->withParts(['regions', '93', 'departements'])
                ->withParams([
                    'fields' => 'nom,code',
                    'format' => 'json'
                ], true)
        );
        $this->assertEquals($url . '/regions/93/departements?fields=nom,code&format=json', $curl->getUrl(true));
        $this->assertInstanceOf(Items::class, $curl->getParams());

        // Obtention des paramètres
        $this->assertTrue($curl->getParams()->has('fields'));
        $this->assertEquals('nom,code', $curl->getParams()->get('fields'));
        $this->assertTrue($curl->getParams()->has('format'));
        $this->assertEquals('json', $curl->getParams()->get('format'));

        $response = $curl->exec('Départements de la région PACA')->getResponse();
        $this->assertInstanceOf(Items::class, $response);
    }

    public function testGetResponseWithImage()
    {
        $this->ekoMessage("JSON");
        $response = $this
            ->makeCurl($this->baseUrls['image'])
            ->exec('Test image')
            ->getResponse();

        $this->assertInstanceOf(Image::class, $response);

    }

    public function testGetResponseWithError()
    {
        $this->ekoMessage("Exécution avec erreur");
        $url = $this->baseUrls['geo'];
        $curl = $this->makeCurl($url);
        $response = $curl
            ->withParts('fake')
            ->exec('Fake part')
            ->getResponse();
        $this->assertTrue($response->has('error'));
        $this->assertTrue($response->has('infos'));
        $this->assertFalse($response->get('response'));
        $this->assertInternalType('string', $response->get('curlError'));
    }

    public function testPeclFunctions()
    {
        $this->ekoMessage("GeoIP si PECL installé");
        if (extension_loaded('pecl')) {
            $this->assertInternalType(
                'string',
                $this->curl->getGeoipInfos(
                    'country_name_by_name',
                    $this->curl->parseUrl('host')
                )
            );
        } else {
            $this->assertFalse(
                $this->curl->getGeoipInfos(
                    'country_name_by_name',
                    $this->curl->parseUrl('host')
                )
            );
        }
    }
}
