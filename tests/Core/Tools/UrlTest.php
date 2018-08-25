<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Url;
use Tests\Rcnchris\BaseTestCase;

class UrlTest extends BaseTestCase
{
    /**
     * @param mixed|null $url
     *
     * @return \Rcnchris\Core\Tools\Url
     */
    public function makeUrl($url = null)
    {
        return new Url($url);
    }

    /**
     * Obtenir une instance et disposer des attributs attendus
     */
    public function testInstance()
    {
        $this->ekoTitre('Tools - URL');
        $url = $this->makeUrl('http://www.domaine.com:8080/part1/part2?page=12&user=rcn');
        $this->assertInstanceOf(Url::class, $url);
        $this->assertObjectHasAttributes($url, 'scheme,host,port,path,query');
        $this->assertEquals('http', $url->scheme);
        $this->assertEquals('www.domaine.com', $url->host);
        $this->assertEquals(8080, $url->port);
        $this->assertEquals('/part1/part2', $url->path);
        $this->assertEquals('page=12&user=rcn', $url->query);
    }

    /**
     * Obtenir les paramètres d'une URL sous forme de tableau
     */
    public function testParams()
    {
        $url = $this->makeUrl('http://www.domaine.com:8080/part1/part2?page=12&user=rcn');
        $params = $url->params();
        $this->assertInternalType('array', $params);
        $this->assertArrayHasKeys($params, 'page,user');
        $this->assertEquals(12, $params['page']);
        $this->assertEquals('rcn', $params['user']);
    }
}
