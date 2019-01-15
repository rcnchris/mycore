<?php
namespace Core\Tools;

use Rcnchris\Core\Tools\Items;
use Rcnchris\Core\Tools\Url;
use Tests\Rcnchris\BaseTestCase;

class UrlTest extends BaseTestCase
{

    /**
     * @param string|null $url
     *
     * @return \Rcnchris\Core\Tools\Url
     */
    public function makeUrl($url = null)
    {
        return new Url($url);
    }

    public function testInstanceWithoutParameter()
    {
        $this->ekoTitre('Tools - URL');
        $this->assertInstanceOf(Url::class, $this->makeUrl());
    }

    public function testInstanceWith()
    {
        $this->assertInstanceOf(Url::class, $this->makeUrl('https://randomuser.me/api/'));
    }

    public function testMagicToString()
    {
        $url = $this->makeUrl('http://php.net/manual/fr/');
        $this->assertEquals('http://php.net/manual/fr/', (string)$url);
    }

    public function testMagicGet()
    {
        $url = $this->makeUrl('http://php.net/manual/fr/');
        $this->assertEquals('php.net', $url->host);
    }

    public function testQueries()
    {
        $url = $this->makeUrl('https://www.google.com/search?client=firefox-b-d&q=php');
        $this->assertInstanceOf(Items::class, $url->queries());
        $this->assertEquals(2, $url->queries()->count());
    }

    public function testGet()
    {
        $this->assertInternalType('string', $this->makeUrl('http://php.net/manual/fr/')->get());
    }

    public function testGetInfos()
    {
        $this->assertInternalType(
            'array',
            $this->makeUrl('http://php.net/manual/fr/')->getInfos()
        );
    }

    public function testGetInfosWithKey()
    {
        $this->assertEquals('text/html; charset=utf-8', $this->makeUrl('http://php.net/manual/fr/')->getInfos('content_type'));
    }
}
